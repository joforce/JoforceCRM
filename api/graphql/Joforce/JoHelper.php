<?php
namespace Joforce;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class JoHelper
{
    private $db;

    private $user;

    private $module_fields_info;

    private $records_per_page = 20;

    /**
     * JoHelper constructor.
     *
     * @param $adb
     * @param $user
     */
    public function __construct($adb, $user)
    {
        $this->db = $adb;

        $this->user = $user;
    }

    /**
     * Resolve data for given arguments
     *
     * @param array $requested_data
     * @param $args
     * @return array|mixed
     * @throws \Exception
     * @throws \WebServiceException
     */
    public function resolve($requested_data, $args)
    {
        $data = [];
        if($requested_data['action'] == 'get_modules') {
            $data = $this->getJoModules();
        }
        else if($requested_data['action'] == 'menu')  {
            $data = $this->getUserMenu();
        }
        else if($requested_data['action'] == 'global_search') {
            $data = $this->globalSearch($args);
            $data = ['results' => $data];
        }
        else if($requested_data['action'] == 'calendar_view') {
            $data = $this->getCalendarInfo($args);
            $data['events'] = $data;
        }
        else if($requested_data['action'] == 'describe')  {
            $data = $this->getModuleFields($args['module']);
        }
        else if($requested_data['action'] == 'filters')  {
            $data = $this->getUserFilters($args);
        }
        else if($requested_data['action'] == 'filter_columns')    {
            $data = $this->getFilterColumns($args);
        }
        else if($requested_data['action'] == 'get_module_relations') {
            $data = $this->returnRelatedModules($args['module']);
        }
        else if($requested_data['action'] == 'get_related_records') {
            $data = $this->returnRelatedRecords($args);
        }
        else if($requested_data['action'] == 'widget_info')   {
            $data = $this->getWidgetData($args);
            $data = ['data' => $data];
        }
        else if($requested_data['action'] == 'get_record')    {
            $data = $this->retrieve($args['module'], $args['id']);
        }
        else if($requested_data['action'] == 'list')  {
            $data = $this->listRecords($requested_data, $args);
        }
        else if($requested_data['action'] == 'get_users')   {
            $data = $this->returnUsers($requested_data, $args);
        }
        return $data;
    }

    /**
     * Retrieve record from module
     *
     * @param $module
     * @param $record_id
     * @return mixed
     * @throws \WebServiceException
     */
    public function retrieve($module, $record_id)
    {
        $module_name = ucfirst($module);
        include_once 'includes/Webservices/DescribeObject.php';
        $this->module_fields_info = \vtws_describe($module_name, $this->user);

        // TODO Check permission using vtws_retrieve function
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $recordModel = \Head_Record_Model::getInstanceById($record_id, $moduleModel);
        $unresolved_data = $recordModel->getData();
        $data = $this->resolveRecordValues($unresolved_data, $moduleModel);
        return $data;
    }

    /**
     * Return list of users and groups
     *
     * @param $requested_data
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function returnUsers($requested_data, $args)
    {
        global $current_user;
        $current_user = $this->user;
        $userModal = \Users_Record_Model::getCurrentUserModel();
        $users_info = $userModal->getAccessibleUsers();
        $groups_info = $userModal->getAccessibleGroups();

        $response = array();
        foreach($users_info as $user_id => $user_name)  {
            $response[] = ['id' => $user_id, 'label' => $user_name, 'is_user' => true];
        }

        if($args['groups'] === true)    {
            foreach($groups_info as $group_id => $group_name)   {
                $response[] = ['id' => $group_id, 'label' => $group_name, 'is_user' => false];
            }
        }
        return ['response' => $response];
    }

    /**
     * Return list of records
     *
     * @param $requested_data
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function listRecords($requested_data, $args)
    {
        global $current_user;

        $current_user = $this->user;

        $module_name = ucfirst($args['module']);
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $headerFieldModels = $moduleModel->getHeaderViewFieldsList();

        $orderBy = $args['order_by'];
        $sortOrder = $args['sort_by'];
        $filterId = $args['filter_id'];

        $headerFields = array();
        $fields = array();

        $nameFields = $moduleModel->getNameFields();
        if(is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name'=>$nameFieldModel->get('name'), 'label'=>$nameFieldModel->get('label'), 'fieldType'=>$nameFieldModel->getFieldDataType());
        }
        else if(is_array($nameFields)) {
            foreach($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name'=>$nameFieldModel->get('name'), 'label'=>$nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
            }
        }

        foreach($headerFieldModels as $fieldName => $fieldModel) {
            $headerFields[] = $fieldName;
            $fields[] = array('name'=>$fieldName, 'label'=>$fieldModel->get('label'), 'fieldType'=>$fieldModel->getFieldDataType());
        }

        $listViewModel = \Head_ListView_Model::getInstance($module_name, $filterId, $headerFields = array());
        if(!empty($sortOrder)) {
            $listViewModel->set('orderby', $orderBy);
            $listViewModel->set('sortorder',$sortOrder);
        }

        if(!empty($args['search_key']) && !empty($args['search_value']))    {
            $listViewModel->set('search_value', $args['search_value']);
            $listViewModel->set('search_key', $args['search_key']);
            $listViewModel->set('operator', 'c');
        }

        $pagingModel = new \Head_Paging_Model();
        if(isset($args['limit']) && !empty($args['limit'])) {
            $pageLimit = $args['limit'];
        }
        else    {
            $pageLimit = $pagingModel->getPageLimit();
        }
        $pagingModel->set('page', $args['page']);
        $pagingModel->set('limit', $pageLimit + 1);

        $listViewEntries = $listViewModel->getListViewEntries($pagingModel);

        $customView = new \CustomView($module_name);
        if(empty($filterId)) {
            $filterId = $customView->getViewId($module_name);
        }

        if($listViewEntries) {
            foreach($listViewEntries as $index => $listViewEntryModel) {
                $data = $listViewEntryModel->getData();
                $record = array('id'=>$listViewEntryModel->getId());
                foreach($data as $i => $value) {
                    if(is_string($i)) {
                        $record[$i]= decode_html($value);
                    }
                }
                $records[] = $record;
            }
        }

        $moreRecords = false;
        if(count($listViewEntries) > $pageLimit) {
            $moreRecords = true;
            array_pop($records);
        }

        $response = [
            'records' => $records,
            'headers' => $fields,
            'selectedFilter' => $filterId,
            'nameFields' => $nameFields,
            'moreRecords' => $moreRecords,
            'orderBy' => $orderBy,
            'sortOrder' => $sortOrder,
            'page' => $args['page']
        ];

        return $response;
    }

    /**
     * Sync record to Joforce
     *
     * @param $args
     * @param $module
     * @param $id
     * @return \Array|bool
     * @throws \Exception
     */
    public function syncRecord($args, $module, $id = null)
    {
        global $current_user;
        $current_user = $this->user;

        if(empty($args))    {
            throw new \Exception('No post data');
        }

        $module_name = ucfirst($module);
        if(empty($id)) {
            $recordModel = \Head_Record_Model::getCleanInstance($module_name);
        }
        else    {
            $record_exists = $this->checkRecordExists($id);
            if(!$record_exists)  {
                throw new \Exception('Record not exists');
            }
            $recordModel = \Head_Record_Model::getInstanceById($id, $module_name);
        }
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $fieldModelList = $moduleModel->getFields();

        foreach ($fieldModelList as $fieldName => $fieldModel) {
            if(isset($args[$fieldName])) {
                $fieldValue = $args[$fieldName];
                $recordModel->set($fieldName, $fieldValue);
            }
        }

        if (!empty($id)) {
            $recordModel->set('id', $id);
            $recordModel->set('mode', 'edit');
            $recordModel->save();
        }
        else {
            $recordModel->save();
        }
        return $recordModel->getData();
    }

    /**
     * Delete record
     *
     * @param $module
     * @param $record_id
     * @return array
     */
    public function deleteRecord($module, $record_id)
    {
        global $current_user;
        $current_user = $this->user;
        try {
            $module_name = ucfirst($module);
            $recordModel = \Head_Record_Model::getInstanceById($record_id, $module_name);
            $recordModel->delete();
            return ['success' => true, 'id' => $record_id];
        }
        catch(\Exception $e)    {
            $message = $e->getMessage();
            if(empty($message)) {
                $message = 'Something went wrong';
            }
            return ['success' => false, 'message' => $message];
        }
    }

    /**
     * Check record exists in Joforce
     *
     * @param $id
     * @return bool
     */
    public function checkRecordExists($id)
    {
        // Check record exists
        $checkRecord = $this->db->pquery('select crmid from jo_crmentity where crmid = ?', array($id));
        $crm_id = $this->db->query_result($checkRecord, 0, 'crmid');
        if(empty($crm_id)) {
            return false;
        }

        // Check record is deleted
        $checkRecordDeleted = $this->db->pquery('select crmid from jo_crmentity where deleted = 0 and crmid = ?', array($id));
        $crm_id = $this->db->query_result($checkRecordDeleted, 0, 'crmid');
        if(empty($crm_id)) {
            return false;
        }
        return true;
    }

    /**
     * Return related modules of given Module
     * @param $module
     * @return mixed
     */
    public function returnRelatedModules($module)
    {
        global $current_user;
        $current_user = $this->user;
        $module_name = ucfirst($module);
        $moduleModel = \Head_Module_Model::getInstance($module_name);
        $relations = $moduleModel->getRelations();
        foreach($relations as $relation)    {
            $relation_info['module_name'] = $relation->get('relatedModuleName');
            $relation_info['label'] = $relation->get('label');
            $relation_info['tab_id'] = $relation->get('related_tabid');

            $related_modules[] = $relation_info;
        }
        return ['relations' => $related_modules];
    }

    /**
     * Return related module records
     *
     * @param $args
     * @return array
     * @throws \Exception
     * @throws \WebServiceException
     */
    public function returnRelatedRecords($args)
    {
        include_once 'includes/Webservices/Query.php';
        global $current_user;
        $current_user = $this->user;

        $record_id = $args['id'];
        $related_module = ucfirst($args['related_module']);
        $currentPage = $args['page'];
        if(!empty($currentPage))    {
            $currentPage = $currentPage - 1;
        }

        if (empty($record_id)) {
            throw new \Exception('Record Id not passed');
        }

        $currentModule = ucfirst($args['module']);

        $functionHandler = $this->getRelatedFunctionHandler($currentModule, $related_module);

        if ($functionHandler) {
            $sourceFocus = \CRMEntity::getInstance($currentModule);
            $relationResult = call_user_func_array(	array($sourceFocus, $functionHandler), array($record_id, getTabid($currentModule), getTabid($related_module)));
            $query = $relationResult['query'];

            $moduleModel = \Head_Module_Model::getInstance($currentModule);
            $nameFields = $moduleModel->getNameFields();

            $querySEtype = "jo_crmentity.setype as setype";
            if ($related_module == 'Calendar') {
                $querySEtype = "jo_activity.activitytype as setype";
            }

            $query = sprintf("SELECT jo_crmentity.crmid, $querySEtype %s", substr($query, stripos($query, 'FROM')));
            $queryResult = $this->db->query($query);

            // Gather resolved record id's
            $relatedRecords = array();
            while($row = $this->db->fetch_array($queryResult)) {
                $targetSEtype = $row['setype'];
                if ($related_module == 'Calendar') {
                    if ($row['setype'] != 'Task' && $row['setype'] != 'Emails') {
                        $targetSEtype = 'Events';
                    } else {
                        $targetSEtype = $related_module;
                    }
                }
                $moduleWSId = $this->getModuleWSId($targetSEtype);
                $relatedRecords[] = sprintf("%sx%s", $moduleWSId, $row['crmid']);
            }

            $queryResult = null;
            if(count($relatedRecords) > 0)  {
                // Perform query to get record information with grouping
                $ws_query = sprintf("SELECT * FROM %s WHERE id IN ('%s')", $related_module, implode("','", $relatedRecords));
                $FETCH_LIMIT = $this->records_per_page;
                $startLimit = $currentPage * $FETCH_LIMIT;

                $queryWithLimit = sprintf("%s LIMIT %u,%u;", $ws_query, $startLimit, ($FETCH_LIMIT+1));
                $queryResult = \vtws_query($queryWithLimit, $current_user);
            }

            if(count($queryResult) > 0) {
                // Resolve the ID
                // TODO move the resolve to the GraphQL
                foreach($queryResult as $key => $single_entity) {
                    list($entity_tab_id, $entity_id) = explode('x', $single_entity['id']);
                    $queryResult[$key]['id'] = $entity_id;
                }
            }

            $moreRecords = false;
            if(count($queryResult) == $FETCH_LIMIT) {
                $moreRecords = true;
            }

            return ['related_records' => $queryResult, 'page' => $currentPage, "nameFields" => $nameFields, "moreRecords" => $moreRecords];
        }
        throw new \Exception('No handler for given module');
    }

    /**
     * Return handler function of related module
     *
     * @param $module
     * @param $related_module
     * @return bool
     */
    public function getRelatedFunctionHandler($module, $related_module)
    {
        $relationResult = $this->db->pquery("SELECT name FROM jo_relatedlists WHERE tabid = ? and related_tabid = ? and presence = 0", array(getTabid($module), getTabid($related_module)));
        $functionName = false;
        if ($this->db->num_rows($relationResult)) $functionName = $this->db->query_result($relationResult, 0, 'name');

        return $functionName;
    }

    /**
     * Return Module WS Id for given Module
     *
     * @param $module
     * @return bool
     */
    public function getModuleWSId($module)
    {
        $result = $this->db->pquery("SELECT id FROM jo_ws_entity WHERE name = ?", array($module));
        if ($result && $this->db->num_rows($result)) {
            return $this->db->query_result($result, 0, 'id');
        }
        return false;
    }

    /**
     * Generate fields
     *
     * @param $args
     * @return array|bool
     * @throws \WebServiceException
     */
    public function generateFields($args)
    {
        if($args['action'] == 'get_modules') {
            return [
                'name' => $args['module'] . 'Field',
                'description' => "CRM {$args['module']} fields",
                'type' => 'list',
                'action' => 'get_modules',
                'fields' => [
                    'id' => [
                        'type' => Type::id(),
                    ],
                    'name' => [
                        'type' => Type::string(),
                    ],
                    'isEntity' => [
                        'type' => Type::id(),
                    ],
                    'label' => [
                        'type' => Type::string(),
                    ],
                    'singular' => [
                        'type' => Type::string()
                    ],
                ]
            ];
        }
        else if($args['action'] == 'menu')  {
            $main_menu = new ObjectType([
                'name' => 'MainMenuInformation',
                'description' => 'Main Menu information',
                'fields' => [
                    'tabid' => Type::id(),
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'type' => Type::string()
                ]
            ]);

            $module_info = new ObjectType([
                'name' => 'ModuleInformation',
                'description' => 'Module Information',
                'fields' => [
                    'tabid' => Type::int(),
                    'name' => Type::string(),
                    'label' => Type::string()
                ]
            ]);

            $more_section = new ObjectType([
                'name' => 'MoreMenuInformation',
                'description' => 'More Menu information',
                'fields' => [
                    'section' => Type::string(),
                    'module_info' => Type::listOf($module_info),
                ]
            ]);

            return [
                'name' => 'UserMenu',
                'description' => "User menu",
                'type' => 'single',
                'action' => 'get_menu',
                'fields' => [
                    'Main' => Type::listOf($main_menu),
                    'More' => Type::listOf($more_section)
                ]
            ];
        }
        else if($args['action'] == 'global_search') {

            $jo_record = new ObjectType([
                'name' => 'RecordStructure',
                'description' => 'Record structure',
                'fields' => [
                    'label' => Type::string(),
                    'crmid' => Type::int(),
                    'createdtime' => Type::string()
                ]
            ]);

            $search_results = new ObjectType([
                'name' => 'SearchResults',
                'description' => 'Search results',
                'fields' => [
                    'module' => Type::string(),
                    'data' => Type::listOf($jo_record),
                ]
            ]);

            return [
                'name' => 'GlobalSearch',
                'description' => "Global Search",
                'args' => [
                    'value' => Type::nonNull(Type::string()),
                    'searchModule' => Type::string()
                ],
                'action' => 'global_search',
                'fields' => [
                    'results' => Type::listOf($search_results),
                ]
            ];
        }
        else if($args['action'] == 'calendar_view') {
            if(isset($args['day'])) {
                $calendarObject = new ObjectType([
                    'name' => 'CalendarInformation',
                    'description' => 'Calendar information',
                    'fields' => [
                        'id' => Type::int(),
                        'visibility' => Type::string(),
                        'activitytype' => Type::string(),
                        'status' => Type::string(),
                        'priority' => Type::string(),
                        'userfullname' => Type::string(),
                        'title' => Type::string(),
                        'start' => Type::string(),
                        'startDate' => Type::string(),
                        'startTime' => Type::string(),
                        'end' => Type::string(),
                        'endDate' => Type::string(),
                        'endTime' => Type::string(),
                        'recurringcheck' => Type::string(),
                    ]
                ]);
            }
            else {
                $calendarObject = new ObjectType([
                    'name' => 'CalendarInformation',
                    'description' => 'Calendar information',
                    'fields' => [
                        'date' => Type::string(),
                        'count' => Type::int()
                    ]
                ]);
            }

            return [
                'name' => $args['module'] . 'Module',
                'description' => "Joforce - Calendar",
                'type' => 'single',
                'action' => 'calendar_view',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'date' => Type::nonNull(Type::string()),
                    'day' => Type::boolean(),
                ],
                'fields' => [
                    'events' => Type::listOf($calendarObject),
                ]
            ];
        }
        else if($args['action'] == 'describe')  {

            $picklistType = new ObjectType([
                'name' => 'PicklistType',
                'Description' => 'Picklist field type',
                'fields' => [
                    'label' => Type::string(),
                    'value' => Type::string()
                ]
            ]);

            // Joforce Field Type - string, integer, boolean etc
            $type = new ObjectType([
                'name' => 'FieldType',
                'description' => 'Field type',
                'fields' => [
                    'name' => Type::string(),
                    'refersTo' => Type::listOf(Type::string()),
                    'defaultValue' => Type::string(),
                    'picklistValues' => Type::listOf($picklistType),
                    'format' => Type::string(),
                ]
            ]);

            $fields = new ObjectType([
                'name' => 'Fields',
                'description' => 'CRM Fields',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'mandatory' => Type::boolean(),
                    'nullable' => Type::boolean(),
                    'editable' => Type::boolean(),
                    'default' => Type::string(),
                    'headerfield' => Type::boolean(),
                    'summaryfield' => Type::boolean(),
                    'type' => $type,
                ]
            ]);

            return [
                'name' => $args['module'] . 'FieldInfo',
                'description' => "Joforce - {$args['module']} fields info",
                'type' => 'single',
                'action' => 'get_schema',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'label' => Type::string(),
                    'name' => Type::string(),
                    'createable' => Type::boolean(),
                    'updateable' => Type::boolean(),
                    'deleteable' => Type::boolean(),
                    'retrieveable' => Type::id(),
                    'nameFields' => Type::listOf(Type::string()),
                    'fields' => Type::listOf($fields),
                ]
            ];
        }
        else if($args['action'] == 'filters')    {

            $filter = new ObjectType([
                'name' => 'FilterInformation',
                'description' => 'Filter information',
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                    'default' => Type::string()
                ]
            ]);

            return [
                'name' => $args['module'] . 'Filters',
                'description' => "Joforce - {$args['module']} filters",
                'type' => 'single',
                'action' => 'get_filters',
                'args' => [
                    'module' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'Mine' => Type::listOf($filter),
                    'Shared' => Type::listOf($filter),
                    'Others' => Type::listOf($filter),
                ]
            ];
        }
        else if($args['action'] == 'filter_columns')    {

            $filter = new ObjectType([
                'name' => 'FilterInformation',
                'description' => 'Filter information',
                'fields' => [
                    'fieldname' => Type::string(),
                    'fieldlabel' => Type::string()
                ]
            ]);

            return [
                'name' => 'FilterColumns',
                'description' => "Filter column fields",
                'type' => 'single',
                'action' => 'get_filter_columns',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ],
                'fields' => [
                    'filter' => Type::listOf($filter),
                ]
            ];
        }
        else if($args['action'] == 'get_module_relations') {

            $relationType = new ObjectType([
                'name' => 'RelationsInformation',
                'description' => 'Relations information',
                'fields' => [
                    'module_name' => Type::string(),
                    'label' => Type::string(),
                    'tab_id' => Type::id()
                ]
            ]);

            return [
                'name' => 'ModuleRelations',
                'description' => "Module relations information",
                'type' => 'single',
                'action' => 'get_module_relations',
                'args' => [
                    'module' => Type::nonNull(Type::string())
                ],
                'fields' => [
                    'relations' => Type::listOf($relationType),
                ]
            ];
        }
        else if($args['action'] == 'widget_info')    {

            $widgetType = new ObjectType([
                'name' => 'WidgetInfo',
                'description' => 'Widget Information',
                'fields' => [
                    'count' => Type::int(),
                    'value' => Type::string(),
                    'label' => Type::string()
                ]
            ]);

            return [
                'name' => 'JoforceWidget',
                'description' => "Joforce widget",
                'type' => 'single',
                'action' => 'widget_info',
                'args' => [
                    'name' => Type::nonNull(Type::string()),
                ],
                'fields' => [
                    'data' => Type::listOf($widgetType)
                ],
            ];
        }
        else if($args['action'] == 'get_related_records') {

            $module_fields = $this->generateModuleFields($args['related_module']);

            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => 'RelatedModuleRecords',
                'description' => "Related module records",
                'type' => 'single',
                'action' => 'get_related_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id()),
                    'related_module' => Type::nonNull(Type::string()),
                    'page' => Type::nonNull(Type::id()),
                ],
                'fields' => [
                    'related_records' => Type::listOf($moduleFieldsType),
                    'page' => Type::int(),
                    'moreRecords' => Type::boolean(),
                    'nameFields' => Type::listOf(Type::string())
                ]
            ];
        }
        else if($args['action'] == 'get_users')  {

            $userInfo = new ObjectType([
                'name' => 'UserInfo',
                'description' => 'User/Group Information',
                'fields' => [
                    'id' => Type::int(),
                    'label' => Type::string(),
                    'is_user' => Type::boolean()
                ]
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_users',
                'args' => [
                    'groups' => Type::boolean(),
                ],
                'fields' => [
                    'response' => Type::listOf($userInfo),
                ]
            ];
        }
        // TODO Add related module type. Should be able to query in a single shot
        else if($args['action'] == 'list')  {

            $headerType = new ObjectType([
                'name' => 'HeaderFields',
                'description' => 'Header field information',
                'fields' => [
                    'name' => Type::string(),
                    'label' => Type::string(),
                    'fieldType' => Type::string()
                ]
            ]);

            $module_fields = $this->generateModuleFields($args['module']);

            $moduleFieldsType = new ObjectType([
                'name' => $args['module'] . 'FieldsType',
                'description' => $args['module'] . ' fields type',
                'fields' => $module_fields
            ]);

            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'action' => 'get_records',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'page' => Type::int(),
                    'limit' => Type::int(),
                    'order_by' => Type::string(),
                    'sort_by' => Type::string(),
                    'filter_id' => Type::int()
                ],
                'fields' => [
                    'records' => Type::listOf($moduleFieldsType),
                    'headers' => Type::listOf($headerType),
                    'selectedFilter' => Type::int(),
                    'nameFields' => Type::listOf(Type::string()),
                    'moreRecords' => Type::boolean(),
                    'orderBy' => Type::string(),
                    'sortOrder' => Type::string(),
                    'page' => Type::int(),
                ]
            ];
        }
        else if($args['action'] == 'get_record')   {
            $module_fields = $this->generateModuleFields($args['module']);
            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        }
        else    {
            $module_fields = $this->generateModuleFields($args['module']);
            return [
                'name' => $args['module'] . 'Fields',
                'description' => "{$args['module']} fields",
                'type' => 'single',
                'fields' => $module_fields,
                'action' => 'get_record',
                'args' => [
                    'module' => Type::nonNull(Type::string()),
                    'id' => Type::nonNull(Type::id())
                ]
            ];
        }
        return false;
    }

    /**
     * Generate Module fields
     *
     * @param $module
     * @return mixed
     * @throws \WebServiceException
     */
    public function generateModuleFields($module)
    {
        $module_schema = [];
        $module_name = ucfirst($module);
        include_once 'includes/Webservices/DescribeObject.php';
        $this->module_fields_info = vtws_describe($module_name, $this->user);
        if(isset($this->module_fields_info['fields']))    {
            foreach($this->module_fields_info['fields'] as $module_field) {
                // Check if field is related to other module or field is assigned_user_id. If so, add the module type as string
                if($module_field['type']['name'] == 'reference' || $module_field['name'] == 'assigned_user_id')    {
                    // We are passing reference as String. Need to resolve the Id to Value before passing.
                    $module_schema[$module_field['name']] = Type::string();
                }
                else if($module_field['name'] == 'id')    {
                    $module_schema[$module_field['name']] = Type::int();
                }
                else {
                    $module_schema[$module_field['name']] = $this->typesMapping($module_field['type']['name']);
                }
            }
        }
        return $module_schema;
    }

    /**
     * Return filter columns
     *
     * @param $args
     * @return array
     * @throws \Exception
     */
    public function getFilterColumns($args)
    {
        $module = $args['module'];
        $module_name = ucfirst($module);
        $tab_id = getTabid($module_name);
        $filter_id = $args['id'];
        if(empty($filter_id)) {
            throw new \Exception('Filter Id is mandatory');
        }

        $customView = new \CustomView($module_name);
        $filter_columns = $customView->getColumnsListByCvid($filter_id);
        if(empty($filter_columns))  {
            throw new \Exception('No column present in given Filter Id');
        }

        foreach($filter_columns as $filter_column) {
            $details = explode(':', $filter_column);
            if(empty($details[2]) && $details[1] == 'crmid' && $details[0] == 'jo_crmentity') {
                $name = 'id';
                $customViewFields[] = $name;
            } else {
                $fields[] = $details[2];
                $customViewFields[] = $details[2];
            }
        }

        $filter_field_names = "'" . implode("','", $fields) . "'";
        $getFieldLabels = $this->db->pquery("select fieldname, fieldlabel from jo_field where fieldname IN ($filter_field_names) and tabid = ?", array($tab_id));
        while($field_info = $this->db->fetch_row($getFieldLabels))    {
            $response[] = $field_info;
        }

        return ['filter' => $response];
    }

    /**
     * Return entity modules of Joforce
     *
     * @return array
     * @throws \WebServiceException
     */
    public function getJoModules()
    {
        $result = $this->db->pquery("SELECT id, name FROM jo_ws_entity WHERE ismodule = 1 and name NOT IN ('Users', 'Events')", array());
        while($module_info = $this->db->fetch_array($result)) {
            $modules[$module_info['name']] = $module_info['id'];
        }

        $list_types = vtws_listtypes(null, $this->user);

        $listing = array();
        foreach($list_types['types'] as $index => $module_name) {
            if(!isset($modules[$module_name])) continue;

            $listing[] = [
                'id'   => $modules[$module_name],
                'name' => $module_name,
                'isEntity' => $list_types['information'][$module_name]['isEntity'],
                'label' => $list_types['information'][$module_name]['label'],
                'singular' => $list_types['information'][$module_name]['singular'],
            ];
        }
        return $listing;
    }

    /**
     * Return user filters
     *
     * @param $args
     * @return array
     */
    public function getUserFilters($args)
    {
        global $current_user;
        $current_user = $this->user;

        $allFilters = \CustomView_Record_Model::getAllByGroup($args['module']);
        unset($allFilters['Public']);
        $result = array();
        if($allFilters) {
            foreach($allFilters as $group => $filters) {
                $result[$group] = array();
                foreach($filters as $filter) {
                    $result[$group][] = array('id'=>$filter->get('cvid'), 'name'=>$filter->get('viewname'), 'default'=>$filter->isDefault());
                }
            }
        }
        return $result;
    }

    /**
     * Return module fields
     *
     * @param $module
     * @return mixed
     * @throws \Exception
     * @throws \WebServiceException
     */
    public function getModuleFields($module)
    {
        $module = ucfirst($module);
        include_once 'includes/Webservices/DescribeObject.php';
        $describeInfo = vtws_describe($module, $this->user);

        $fields = $describeInfo['fields'];

        $moduleModel = \Head_Module_Model::getInstance($module);
        $nameFields = $moduleModel->getNameFields();
        if(is_string($nameFields)) {
            $nameFieldModel = $moduleModel->getField($nameFields);
            $headerFields[] = $nameFields;
            $fields = array('name'=>$nameFieldModel->get('name'), 'label'=>$nameFieldModel->get('label'), 'fieldType'=>$nameFieldModel->getFieldDataType());
        }
        else if(is_array($nameFields)) {
            foreach($nameFields as $nameField) {
                $nameFieldModel = $moduleModel->getField($nameField);
                $headerFields[] = $nameField;
                $fields[] = array('name'=>$nameFieldModel->get('name'), 'label'=>$nameFieldModel->get('label'), 'fieldType' => $nameFieldModel->getFieldDataType());
            }
        }

        $fieldModels = $moduleModel->getFields();
        foreach($fields as $index => $field) {
            if($field['type']['name'] == 'boolean' && $field['default'] == 'on')    {
                $field['default'] = true;
            }

            $fieldModel = $fieldModels[$field['name']];
            if($fieldModel) {
                $field['headerfield'] = $fieldModel->get('headerfield');
                $field['summaryfield'] = $fieldModel->get('summaryfield');
            }
            $newFields[] = $field;
        }
        $fields=null;
        $describeInfo['nameFields'] = $nameFields;
        $describeInfo['fields'] = $newFields;
        return $describeInfo;
    }

    /**
     * Return GraphQL type
     *
     * @param $type
     * @return \GraphQL\Type\Definition\BooleanType|\GraphQL\Type\Definition\IntType|\GraphQL\Type\Definition\StringType
     */
    public function typesMapping($type)
    {
        if($type == 'string' || $type == 'email' || $type == 'phone' || $type == 'date' || $type == 'datetime' || $type == 'text'
            || $type == 'picklist' || $type == 'url' || $type == 'password' || $type == 'autogenerated')   {
            return Type::string();
        }
        else if($type == 'boolean') {
            return Type::boolean();
        }
        else if($type == 'owner')   {
            return Type::int();
        }
        else    {
            return Type::string();
        }
    }

    /**
     * Resolve record values
     *
     * @param $data
     * @return mixed
     */
    function resolveRecordValues($data)
    {
        foreach($this->module_fields_info['fields'] as $field_info)   {
            if($field_info['type']['name'] == 'reference')    {
                $data[$field_info['name']] = decode_html(\Head_Functions::getCRMRecordLabel($data[$field_info['name']]));
            }
            else if($field_info['name'] == 'assigned_user_id')    {
                $data[$field_info['name']] = decode_html(\Head_Functions::getUserRecordLabel($data[$field_info['name']]));
            }
            else if($field_info['type']['name'] == 'boolean')   {
                $boolean_response = false;
                if(!empty($data[$field_info['name']]) && $data[$field_info['name']] == 1)
                    $boolean_response = true;

                $data[$field_info['name']] = $boolean_response;
            }
            else if($field_info['type']['name'] == 'time' && ($field_info['name'] == 'time_start' || $field_info['name'] == 'time_end'))   {
                if(!empty($data[$field_info['name']]))  {
                    $date = $data['date_start'];
                    if($field_info['name'] == 'time_end')
                        $date = $data['due_date'];

                    $temp_value = $date . ' ' . $data[$field_info['name']];
                    if($this->user->hour_format == 12)  {
                        $dateTimeObject = new \DateTime($temp_value);
                        $updated_datetime = $dateTimeObject->format('Y-m-d h:i:s');
                        $temp_value = $updated_datetime;
                    }

                    $converted_date = \DateTimeField::convertToUserFormat($temp_value, $this->user); 
                    $dateTime = new \DateTimeField($temp_value);
                    $converted_time = $dateTime->getDisplayTime();
                    $data[$field_info['name']] = $converted_time;
                }
            }
        }
        return $data;
    }

    /**
     * Return Widget data
     *
     * @param $args
     * @return mixed
     * @throws \Exception
     */
    public function getWidgetData($args)
    {
        global $current_user; $data = [];
        $current_user = $this->user;
        $allowed_widget_info = [
            'GroupedBySalesStage' => ['module' => 'Potentials', 'function' => 'getPotentialsCountBySalesStage'],
            'GroupedBySalesPerson' => ['module' => 'Potentials', 'function' => 'getPotentialsCountBySalesPerson'],
            'LeadsByStatus' => ['module' => 'Leads', 'function' => 'getLeadsByStatus'],
            'LeadsBySource' => ['module' => 'Leads', 'function' => 'getLeadsBySource'],
        ];

        if(!array_key_exists($args['name'], $allowed_widget_info))  {
            throw new \Exception('Widget not allowed');
        }

        $moduleModel = \Head_Module_Model::getInstance($allowed_widget_info[$args['name']]['module']);
        $response_data = $moduleModel->{$allowed_widget_info[$args['name']]['function']}($this->user->id, null);
        if($args['name'] == 'GroupedBySalesStage') {
            foreach($response_data as $data_key => $resolve_data)   {
                $data[$data_key]['count'] = $resolve_data[1];
                $data[$data_key]['value'] = $resolve_data[0];
                $data[$data_key]['label'] = $resolve_data[2];
            }
        }
        else if($args['name'] == 'GroupedBySalesPerson')   {
            foreach($response_data as $data_key => $resolve_data)   {
                $data[$data_key]['count'] = $resolve_data['count'];
                $data[$data_key]['value'] = $resolve_data['last_name'];
                $data[$data_key]['label'] = $resolve_data['last_name'];
            }
        }
        else {
            foreach ($response_data as $data_key => $resolve_data) {
                $data[$data_key]['count'] = $resolve_data[0];
                $data[$data_key]['value'] = $resolve_data[1];
                $data[$data_key]['label'] = $resolve_data[2];
            }
        }
        return $data;
    }

    /**
     * Return Calendar Info
     *
     * @param $args
     * @return array
     */
    public function getCalendarInfo($args)
    {
        global $current_user;
        $current_user = $this->user;

        $start = $args['date'];
        // TODO Need improvements on this function
        if(!empty($args['day']) && $args['day'] === true)  {
            $noOfDays = 1;
        }
        else    {
            $noOfDays = 31;
        }

        $user_formatted_date = \DateTimeField::convertToUserFormat($start, $current_user);

        $dbStartDateOject = \DateTimeField::convertToDBTimeZone($start, $current_user);
        $dbStartDateTime = $dbStartDateOject->format('Y-m-d H:i:s');

        $cache_datetime = strtotime($dbStartDateTime);
        $secondsDelta = 24 * 60 * 60 * $noOfDays;
        $futureDate = $cache_datetime + $secondsDelta;
        $dbEndDateTime = date("Y-m-d H:i:s", $futureDate);

        $currentUser = \Users_Record_Model::getCurrentUserModel();
        $db = $this->db;

        $query = 'SELECT jo_activity.subject, jo_activity.eventstatus, jo_activity.priority ,jo_activity.visibility,
						jo_activity.date_start, jo_activity.time_start, jo_activity.due_date, jo_activity.time_end,
						jo_crmentity.smownerid, jo_activity.activityid, jo_activity.activitytype, jo_activity.recurringtype,
						jo_activity.location FROM jo_activity
						INNER JOIN jo_crmentity ON jo_activity.activityid = jo_crmentity.crmid
						LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
						LEFT JOIN jo_groups ON jo_crmentity.smownerid = jo_groups.groupid
						WHERE jo_crmentity.deleted=0 AND jo_activity.activityid > 0 AND jo_activity.activitytype NOT IN ("Emails","Task") AND ';

        $hideCompleted = $currentUser->get('hidecompletedevents');
        if ($hideCompleted) {
            $query.= "jo_activity.eventstatus != 'HELD' AND ";
        }
        $query.= " (concat(date_start,' ',time_start)) >= '$dbStartDateTime' AND (concat(date_start,' ',time_start)) < '$dbEndDateTime'";

        $eventUserId = $currentUser->getId();
        $params = array_merge(array($eventUserId), $this->getGroupsIdsForUsers($eventUserId));
        $query.= " AND jo_crmentity.smownerid IN (".generateQuestionMarks($params).")";
        $query.= ' ORDER BY time_start';
        $queryResult = $db->pquery($query, $params);
        while ($record = $db->fetchByAssoc($queryResult)) {
            $item = array();
            $item['id']				= $record['activityid'];
            $item['visibility']		= $record['visibility'];
            $item['activitytype']	= $record['activitytype'];
            $item['status']			= $record['eventstatus'];
            $item['priority']		= $record['priority'];
            $item['userfullname']	= getUserFullName($record['smownerid']);
            $item['title']			= decode_html($record['subject']);

            $dateTimeFieldInstance = new \DateTimeField($record['date_start'].' '.$record['time_start']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $startDateComponents = explode(' ', $userDateTimeString);

            $item['start'] = $userDateTimeString;
            $item['startDate'] = $startDateComponents[0];
            $item['startTime'] = $startDateComponents[1];

            $dateTimeFieldInstance = new \DateTimeField($record['due_date'].' '.$record['time_end']);
            $userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
            $endDateComponents = explode(' ', $userDateTimeString);

            $item['end'] = $userDateTimeString;
            $item['endDate'] = $endDateComponents[0];
            $item['endTime'] = $endDateComponents[1];

            if ($currentUser->get('hour_format') == '12') {
                $item['startTime'] = \Head_Time_UIType::getTimeValueInAMorPM($item['startTime']);
                $item['endTime'] = \Head_Time_UIType::getTimeValueInAMorPM($item['endTime']);
            }
            $recurringCheck = false;
            if($record['recurringtype'] != '' && $record['recurringtype'] != '--None--') {
                $recurringCheck = true;
            }
            $item['recurringcheck'] = $recurringCheck;
            $result[$startDateComponents[0]][] = $item;
        }

        if(!isset($args['day']) || $args['day'] === false) {
            $response = [];
            foreach ($result as $date => $date_wise) {
                $response[] = ['date' => $date, 'count' => count($result[$date])];
            }
            return $response;
        }
        else    {
            return isset($result[$user_formatted_date]) && !empty($result[$user_formatted_date]) ? $result[$user_formatted_date] : [];
        }
    }

    /**
     * Return User menu
     *
     * @return array
     */
    public function getUserMenu()
    {
        $data = [];
        $more_section = [];
        $user_id = $this->user->id;
        $main_menu = getMainMenuList($user_id);
        // Adding module label
        foreach($main_menu as $key => $menu_info)    {
            $main_menu[$key]['label'] = vtranslate($menu_info['name']);
            $is_entity_module = $this->checkEntityModule($menu_info['name']);
            if(!$is_entity_module) {
                unset($main_menu[$key]);
            }
        }

        $modules_and_sections = getAppModuleList($user_id);
        $data['Main'] = $main_menu;
        $i = 0;
        foreach($modules_and_sections as $section => $modules)  {
            $more_section[$i]['section'] = $section;
            $more_section[$i]['module_info'] = [];
            foreach($modules as $tab_id) {
                $module_name = getTabModuleName($tab_id);
                $is_entity_module = $this->checkEntityModule($module_name);
                if($is_entity_module) {
                    $more_section[$i]['module_info'][] = ['name' => $module_name, 'label' => vtranslate($module_name), 'tabid' => $tab_id];
                }
            }

            // If no modules present in the section, unset the section
            if(count($more_section[$i]['module_info']) == 0) {
                unset($more_section[$i]);
                continue;
            }
            $i = $i + 1;
        }
        $data['More'] = $more_section;
        return $data;
    }

    /**
     * Check whether module is Entity or Extension
     *
     * @param $module_name
     * @return bool
     */
    public function checkEntityModule($module_name)
    {
        $getEntity = $this->db->pquery('select isentitytype from jo_tab where name = ?', array($module_name));
        if ($getEntity && $this->db->num_rows($getEntity)) {
            return $this->db->query_result($getEntity, 0, 'isentitytype');
        }
        return false;
    }

    /**
     * Search all modules and return response
     *
     * @param $args
     * @return array
     */
    public function globalSearch($args)
    {
        global $current_user;
        $current_user = $this->user;
        $request = new \Head_Request($args);
        $listView = new \Head_ListAjax_View();
        $search_result = $listView->searchAll($request, true);
        $i = 0;
        $response = [];
        if($search_result) {
            foreach ($search_result as $module => $module_records) {
                $response[$i]['module'] = $module;
                if(!empty($module_records)) {
                    foreach ($module_records as $module_record) {
                        $response[$i]['data'][] = $module_record->getData();
                    }
                }
                else    {
                    $response[$i]['data'] = [];
                }
                $i = $i + 1;
            }
        }
        return $response;
    }

    protected function getGroupsIdsForUsers($userId) {
        vimport('~~/includes/utils/GetUserGroups.php');
        $userGroupInstance = new \GetUserGroups();
        $userGroupInstance->getAllUserGroups($userId);
        return $userGroupInstance->user_groups;
    }
}
