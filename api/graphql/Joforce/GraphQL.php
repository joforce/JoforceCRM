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

        $module_infos = new ObjectType([
            'name' => 'ModuleInformations',
            'description' => 'Module Information',
            'fields' => [
                 'productname' => Type::string(),
                'unit_price' =>Type::string(),
                'qtyinstock'=>Type::int(),
                'id'=>Type::id(),
       'servicename'=> Type::string()
            ]
        ]);

        $module_info =  new ObjectType([
            'name' => 'UserMenus',
            'description' => "User menu",
            'type' => 'single',
            'action' => 'get_menus',
            'fields' => [
                'Products' => Type::listOf($module_infos),
            'moreRecords' => Type::boolean()
            ]
        ]);
        $related_module_infos = new ObjectType([
            'name' => 'Relatedlineitems',
            'description' => 'Related line item',
            'fields' => [
                'productid' => Type::string(),
                'productid_id' =>Type::int(),
                'quantity'=>Type::int(),
                'listprice'=>Type::string(),
                'comment'=> Type::string()
            ]
        ]);
        
        $related_module_info =  new ObjectType([
            
            'name' => 'Relatedproductlineitems',
            'description' => "Related Product Lineitems",
            'type' => 'single',
            'action' => 'get_line',
            'fields' => [
                'Products' => Type::listOf($related_module_infos),
            ]
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
                ],
                'related_product_lineitems' => [
                    'type' => $related_module_info,
                    'args' => [
                        'module' => Type::nonNull(Type::string()),
                        'id'=> Type::nonNull(Type::id())
                    ],
                    'resolve' => function($root, $request_data, $context) use ($args) {
                        $response = $this->helper->related_product_lineitems($request_data['module'],$request_data['id']);
                        return $response['related_product_lineitems'];
                    }
                ],
                 'fetch_lineitems' => [
                    'type' => $module_info,
                    'args' => [
                        'module' => Type::nonNull(Type::string()),
                        'search_key'=>Type::string(),
                        'search_value'=>Type::string(),
			            'page'=>Type::int(),
                        'limit'=>Type::int()
                    ],
                    'resolve' => function($root, $request_data, $context) use ($args) {
                        $response = $this->helper->fetch_lineitems($request_data['module'],$request_data['search_key'],$request_data['search_value'],$request_data);
                        return $response['fetch_lineitems'];
                    }
                ],
                'relate_record' => [
                    'type' => Type::id(),
                    'args' => [
                        'sourcemodule' => Type::nonNull(Type::string()),
                        'sourceid' => Type::nonNull(Type::id()),
                        'relatedmodule' => Type::nonNull(Type::string()),
                        'realtedid' => Type::nonNull(Type::id())
                    ],
                    'resolve' => function($root, $request_data, $context) use ($args) {
                        $response = $this->helper->relateRecord($request_data['sourcemodule'], $request_data['sourceid'],$request_data['relatedmodule'], $request_data['realtedid']);
                        return $response['message'];
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

