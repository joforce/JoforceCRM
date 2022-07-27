<?php
namespace Joforce;

use GraphQL\Type\Definition\InputObjectType;

use GraphQL\Type\Definition\Type;

use GraphQL\Type\Definition\ObjectType;

use GraphQL\Type\Schema;

class GraphQL
{
    public $helper;

    /**
     * GraphQL constructor.
     *
     * @param JoHelper $helper
     */
    public function __construct(JoHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Generate query for given request
     *
     * @param $requested_data
     * @return ObjectType
     * @throws \WebServiceException
     */
    public function generateQueryType($requested_data)
    {
        $fields = $this->helper->generateFields($requested_data);

        $fields_type = new ObjectType($fields);

        if($fields['type'] == 'list')   {
            $type = Type::listOf($fields_type);
        }
        else    {
            $type = $fields_type;
        }

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                $fields['action'] => [
                    'type' => $type,
                    'args' => $fields['args'],
                    'resolve' => function($root, $args, $context) use ($requested_data) {
                        $data = $this->helper->resolve($requested_data, $args);
                        // Update the structure of retrieve information
                        if(isset($context['action']) && $context['action'] == 'get_record') {
                            foreach($data['blocks'] as $block_id => $block_information) {
                                foreach($block_information['fields'] as $field_id => $field_information) {
                                    if(is_array($field_information['value'])) {
                                        $data['blocks'][$block_id]['fields'][$field_id]['value'] = $field_information['value']['value'];
                                        $data['blocks'][$block_id]['fields'][$field_id]['record_label'] = $field_information['value']['label'];
                                    }
                                    else {
                                        $data['blocks'][$block_id]['fields'][$field_id]['record_label'] = ""; 
                                    }
                                }
                            }
                        }
                        else if(isset($context['action']) && $context['action'] == 'get_related_records') {
                            foreach($data['records'] as $record_id => $record_info) {
                                foreach($record_info['blocks'] as $block_id => $block_information) {
                                    foreach($block_information['fields'] as $field_id => $field_information) {
                                        if(is_array($field_information['value'])) {
                                            $data['records'][$record_id]['blocks'][$block_id]['fields'][$field_id]['value'] = $field_information['value']['value'];
                                            $data['records'][$record_id]['blocks'][$block_id]['fields'][$field_id]['record_label'] = $field_information['value']['label'];
                                        }
                                        else {
                                            $data['records'][$record_id]['blocks'][$block_id]['fields'][$field_id]['record_label'] = ""; 
                                        }
                                    }
                                }
                            }
                        }
                        return $data;
                    }
                ]
            ]
        ]);
        return $queryType;
    }

    /**
     * ObjectType for Mutation
     * @param $args
     * @return ObjectType
     * @throws \WebServiceException
     */
    public function generateMutationType($args)
    {
        $inputFields = [];
        if(isset($args['module']) && !empty($args['module'])) {
            $inputFields = $this->helper->generateModuleFields($args['module']);
        }

        $inputType = new InputObjectType([
            'name' => 'ModuleInputObject',
            'fields' => $inputFields,
        ]);

        $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'process' => [
                    'type' => Type::id(),
                    'args' => [
                        'data' => [
                            'type' => Type::nonNull($inputType),
                        ],
                        'module' => Type::nonNull(Type::string()),
                        'id' => Type::id()
                    ],
                    'resolve' => function($root, $request_data, $context) use ($args) {
                        $data = $this->helper->syncRecord($request_data['data'], $request_data['module'], $request_data['id']);
            			if($request_data['module'] == 'ModComments')	{
            				return $data['id'];
			            }
                        return $data['record']['id'];
                    }
                ],
                'delete_record' => [
                    'type' => Type::id(),
                    'args' => [
                        'module' => Type::nonNull(Type::string()),
                        'id' => Type::nonNull(Type::id())
                    ],
                    'resolve' => function($root, $request_data, $context) use ($args) {
                        $response = $this->helper->deleteRecord($request_data['module'], $request_data['id']);
                        return $response['success'];
                    }
                ]
            ]
        ]);

        return $mutationType;
    }

    /**
     * Generate Schema
     *
     * @param $queryType
     * @param $mutationType
     * @return Schema
     */
    public function schema($queryType, $mutationType = null)
    {
        $schema = new Schema([
            'query' => $queryType,
            'mutation' => $mutationType
        ]);

        return $schema;
    }

    /**
     * Execute the query
     *
     * @param $schema
     * @param $requested_data
     * @return array
     */
	public function execute($schema, $requested_data)
    {
        $result = \GraphQL\GraphQL::executeQuery(
            $schema,
            $requested_data['query'],
            null,
            $requested_data,
            (array) $requested_data
        );
	$module = $requested_data['module'];
	$action = $requested_data['action'];
	$response = $result->toArray();
      	 return $response;
    }
}

