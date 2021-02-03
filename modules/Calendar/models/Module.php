<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

vimport('~~/libraries/modlib/Head/Module.php');

/**
 * Calendar Module Model Class
 */
class Calendar_Module_Model extends Head_Module_Model {

	/**
	 * Function returns the default view for the Calendar module
	 * @return <String>
	 */
	public function getDefaultViewName() {
		return $this->getCalendarViewName();
	}

	/**
	 * Function returns the calendar view name
	 * @return <String>
	 */
	public function getCalendarViewName() {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$arrayofViews = array('ListView' => 'List', 'MyCalendar' => 'Calendar','SharedCalendar'=>'SharedCalendar');

		$calendarViewName = $currentUserModel->get('defaultcalendarview');
		if(array_key_exists($calendarViewName, $arrayofViews)) {
			$calendarViewName = $arrayofViews[$calendarViewName];
		}
		if(empty($calendarViewName)) {
			$calendarViewName = 'Calendar';
		}
		return $calendarViewName;
	}

	/**
	 *  Function returns the url for Calendar view
	 * @return <String>
	 */
	public function getCalendarViewUrl() {
        global $site_URL;
		return $site_URL.$this->get('name').'/view/'.$this->getCalendarViewName();
	}

	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}

	/**
	 * Function returns the URL for creating Events
	 * @return <String>
	 */
	public function getCreateEventRecordUrl() {
        global $site_URL;
		return $site_URL.$this->get('name').'/view/'.$this->getEditViewName().'/mode/Events';
	}

	/**
	 * Function returns the URL for creating Task
	 * @return <String>
	 */
	public function getCreateTaskRecordUrl() {
        global $site_URL;
		return $site_URL.$this->get('name').'/view/'.$this->getEditViewName().'/mode/Calendar';
	}

	/**
	 * Function to get a Head Record Model instance from an array of key-value mapping
	 * @param <Array> $valueArray
	 * @return Head_Record_Model or Module Specific Record Model instance
	 */
	public function getRecordFromArray($valueArray, $rawData = false) {
		$recordInstance = parent::getRecordFromArray($valueArray, $rawData);
		$recordInstance->setData($valueArray)->setModuleFromInstance($this)->setRawData($rawData);
		// added to fix picklist colorizer issue, list page not showing color for records
		if ($rawData['status'] && empty($rawData['taskstatus'])) {
			$recordInstance->rawData['taskstatus'] = $recordInstance->rawData['status'];
		}

		return $recordInstance;
	}

	/**
	 * Function that returns related list header fields that will be showed in the Related List View
	 * @return <Array> returns related fields list.
	 */
	public function getRelatedListFields() {
		$entityInstance = CRMEntity::getInstance($this->getName());
		$list_fields = $entityInstance->list_fields;
		$list_fields_name = $entityInstance->list_fields_name;
		$relatedListFields = array();
		foreach ($list_fields as $key => $fieldInfo) {
			foreach ($fieldInfo as $columnName) {
				if(array_key_exists($key, $list_fields_name)){
					if($columnName == 'lastname' || $columnName == 'activity') continue;
					if ($columnName == 'status') $relatedListFields[$columnName] = 'taskstatus';
					else $relatedListFields[$columnName] = $list_fields_name[$key];
				}
			}
		}
		return $relatedListFields;
	}

	/**
	 * Function to get list of field for related list
	 * @return <Array> empty array
	 */
	public function getConfigureRelatedListFields() {
		return array();
	}

	/**
	 * Function to get list of field for summary view
	 * @return <Array> empty array
	 */
	public function getSummaryViewFieldsList() {
		return array();
	}
	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Head_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
		$links = Head_Link_Model::getAllByType($this->getId(), $linkTypes, $linkParams);

		$quickLinks = array(
			array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_CALENDAR_VIEW',
				'linkurl' => $this->getCalendarViewUrl(),
				'linkicon' => '',
			),
			array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_SHARED_CALENDAR',
				'linkurl' => $this->getSharedCalendarViewUrl(),
				'linkicon' => '',
			),
			array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_RECORDS_LIST',
				'linkurl' => $this->getListViewUrl(),
				'linkicon' => '',
			),
		);
		foreach($quickLinks as $quickLink) {
			$links['SIDEBARLINK'][] = Head_Link_Model::getInstanceFromValues($quickLink);
		}

		$quickWidgets = array();

		if ($linkParams['ACTION'] == 'Calendar') {
			$quickWidgets[] = array(
				'linktype' => 'SIDEBARWIDGET',
				'linklabel' => 'LBL_ACTIVITY_TYPES',
				'linkurl' => 'module='.$this->get('name').'&view=ViewTypes&mode=getViewTypes',
				'linkicon' => ''
			);
		}

		if ($linkParams['ACTION'] == 'SharedCalendar') {
			$quickWidgets[] = array(
				'linktype' => 'SIDEBARWIDGET',
				'linklabel' => 'LBL_ADDED_CALENDARS',
				'linkurl' => 'module='.$this->get('name').'&view=ViewTypes&mode=getSharedUsersList',
				'linkicon' => ''
			);
		}

		/*$quickWidgets[] = array(
			'linktype' => 'SIDEBARWIDGET',
			'linklabel' => 'LBL_RECENTLY_MODIFIED',
			'linkurl' => 'module='.$this->get('name').'&view=IndexAjax&mode=showActiveRecords',
			'linkicon' => ''
		);*/

		foreach($quickWidgets as $quickWidget) {
			$links['SIDEBARWIDGET'][] = Head_Link_Model::getInstanceFromValues($quickWidget);
		}

		return $links;
	}

	/**
	 * Function returns the url that shows Calendar Import result
	 * @return <String> url
	 */
	public function getImportResultUrl() {
		return 'index.php?module='.$this->getName().'&view=ImportResult';
	}

	/**
	 * Function to get export query
	 * @return <String> query;
	 */
	public function getExportQuery() {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$userId = $currentUserModel->getId();
		$userGroup = new GetUserGroups();
		$userGroup->getAllUserGroups($userId);
		$userGroupIds = $userGroup->user_groups;
		array_push($userGroupIds, $userId);
		$value = implode(',', $userGroupIds);
		$query = "SELECT jo_activity.*, jo_crmentity.description, jo_activity_reminder.reminder_time FROM jo_activity
					INNER JOIN jo_crmentity ON jo_activity.activityid = jo_crmentity.crmid
					LEFT JOIN jo_activity_reminder ON jo_activity_reminder.activity_id = jo_activity.activityid AND jo_activity_reminder.recurringid = 0
					WHERE jo_crmentity.deleted = 0 AND jo_crmentity.smownerid IN ($value) AND jo_activity.activitytype NOT IN ('Emails')";
		return $query;
	}

	/**
	 * Function to set event fields for export
	 */
	public function setEventFieldsForExport() {
		$moduleFields = array_flip($this->getColumnFieldMapping());
		$userModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$keysToReplace = array('taskpriority');
		$keysValuesToReplace = array('taskpriority' => 'priority');

		foreach($moduleFields as $fieldName => $fieldValue) {
			$fieldModel = Head_Field_Model::getInstance($fieldName, $this);
			if($fieldName != 'id' && $fieldModel->getPermissions()) {
				if(!in_array($fieldName, $keysToReplace)) {
					$eventFields[$fieldName] = 'yes';
				} else {
					$eventFields[$keysValuesToReplace[$fieldName]] = 'yes';
				}
			}
		}
		$this->set('eventFields', $eventFields);
	}

	/**
	 * Function to set todo fields for export
	 */
	public function setTodoFieldsForExport() {
		$moduleFields = array_flip($this->getColumnFieldMapping());
		$userModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$keysToReplace = array('taskpriority', 'taskstatus');
		$keysValuesToReplace = array('taskpriority' => 'priority', 'taskstatus' => 'status');

		foreach($moduleFields as $fieldName => $fieldValue) {
			$fieldModel = Head_Field_Model::getInstance($fieldName, $this);
			if($fieldName != 'id' && $fieldModel->getPermissions()) {
				if(!in_array($fieldName, $keysToReplace)) {
					$todoFields[$fieldName] = 'yes';
				} else {
					$todoFields[$keysValuesToReplace[$fieldName]] = 'yes';
				}
			}
		}
		$this->set('todoFields', $todoFields);
	}

	/**
	 * Function to get the url to view Details for the module
	 * @return <String> - url
	 */
	public function getDetailViewUrl($id) {
	    global $site_URL;
	    return $site_URL . 'Calendar/view/' . $this->getDetailViewName() . '/' . $id;
	}

	/**
	* To get the lists of sharedids
	* @param $id --  user id
	* @returns <Array> $sharedids
	*/
	public static function getCaledarSharedUsers($id){
		$db = PearDatabase::getInstance();

		$query = "SELECT jo_users.user_name, jo_sharedcalendar.* FROM jo_sharedcalendar
				LEFT JOIN jo_users ON jo_sharedcalendar.sharedid=jo_users.id WHERE userid=?";
		$result = $db->pquery($query, array($id));
		$rows = $db->num_rows($result);

		$sharedids = Array();
		$focus = new Users();
		for($i=0; $i<$rows; $i++){
			$sharedid = $db->query_result($result,$i,'sharedid');
			$userId = $db->query_result($result, $i, 'userid');
			$sharedids[$sharedid]=$userId;
		}
		return $sharedids;
	}

	/**
	* To get the lists of sharedids
	* @param $id --  user id
	* @returns <Array> $sharedids
	*/
	public static function getSharedUsersOfCurrentUser($id){
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();
		if($currentUser->isAdminUser()) {
			$query = "SELECT first_name,last_name, id AS userid
					FROM jo_users WHERE status='Active' AND (user_name != 'admin' OR is_owner = 1) AND id <> ?";
			$result = $db->pquery($query, array($id));
		} else {
			$query = "SELECT jo_users.first_name,jo_users.last_name, jo_users.id AS userid
				FROM jo_sharedcalendar RIGHT JOIN jo_users ON jo_sharedcalendar.userid=jo_users.id AND jo_users.status= 'Active'
				WHERE jo_sharedcalendar.sharedid=? OR (jo_users.status='Active' AND jo_users.calendarsharedtype='public' AND jo_users.id <> ?)";
			$result = $db->pquery($query, array($id, $id));
		}
		$rows = $db->num_rows($result);

		$userIds = Array();
		for($i=0; $i<$rows; $i++){
			$id = $db->query_result($result,$i,'userid');
			$userName = $db->query_result($result,$i,'first_name').' '.$db->query_result($result,$i,'last_name');
			$userIds[$id] =$userName;
		}

		return $sharedids[$id] = $userIds;
	}

	/**
	* To get the lists of accessible groups
	* @param $id --  user id
	* @returns <Array> accessible groups
	*/
	public static function getSharedCalendarGroupsList($id) {
		$currentUsersRecordModel = Users_Record_Model::getCurrentUserModel();
		$sharedGroups = $currentUsersRecordModel->getAccessibleGroupForModule('Calendar');
		return $sharedGroups;
	}

	/**
	* To get the lists of sharedids and colors
	* @param $id --  user id
	* @returns <Array> $sharedUsers
	*/
	public static function getSharedUsersInfoOfCurrentUser($id){
		$db = PearDatabase::getInstance();

		$query = "SELECT shareduserid,color,visible FROM jo_shareduserinfo where userid = ?";
		$result = $db->pquery($query, array($id));
		$rows = $db->num_rows($result);

		$sharedUsers = Array();
		for($i=0; $i<$rows; $i++){
			$sharedUserId = $db->query_result($result,$i,'shareduserid');
			$color = $db->query_result($result,$i,'color');
			$visible = $db->query_result($result,$i,'visible');
			$sharedUsers[$sharedUserId] = array('visible' => $visible , 'color' => $color);
		}

		return $sharedUsers;
	}

	/**
	* To get the lists of sharedids and colors
	* @param $id --  user id
	* @returns <Array> $sharedUsers
	*/
	public static function getCalendarViewTypes($id){
		$db = PearDatabase::getInstance();

		$query = "SELECT * FROM jo_calendar_user_activitytypes 
			INNER JOIN jo_calendar_default_activitytypes on jo_calendar_default_activitytypes.id=jo_calendar_user_activitytypes.defaultid 
			WHERE jo_calendar_user_activitytypes.userid=?";
		$result = $db->pquery($query, array($id));
		$rows = $db->num_rows($result);

		$calendarViewTypes = Array();
		for($i=0; $i<$rows; $i++){
			$activityTypes = $db->query_result_rowdata($result, $i);
			$moduleInstance = Head_Module_Model::getInstance($activityTypes['module']);
			//If there is no module view permission, should not show in calendar view
			if($moduleInstance === false || !$moduleInstance->isPermitted('Detail')) {
				continue;
			}
			$type = '';
			if(in_array($activityTypes['module'], array('Events','Calendar')) && $activityTypes['isdefault']) {
				$type = $activityTypes['module'].'_'.$activityTypes['isdefault'];
			}
			$fieldNamesList = Zend_Json::decode(html_entity_decode($activityTypes['fieldname']));
			$fieldLabelsList = array();
			foreach ($fieldNamesList as $fieldName) {
				$fieldInstance = Head_Field_Model::getInstance($fieldName, $moduleInstance);
				if ($fieldInstance) {
					//If there is no field view permission, should not show in calendar view
					if (!$type && !$fieldInstance->isViewableInDetailView()) {
						$fieldLabelsList = array();
						break;
					}
					$fieldLabelsList[$fieldName] = $fieldInstance->label;
				}
			}

			$conditionsName = '';
			if (!empty($activityTypes['conditions'])) {
				$conditions = Zend_Json::decode(decode_html($activityTypes['conditions']));
				$conditions = Zend_Json::decode($conditions);
				$conditionsName = $conditions['value'];
			}
			$fieldInfo = array(	'module'	=> $activityTypes['module'],
								'fieldname' => implode(',', array_keys($fieldLabelsList)),
								'fieldlabel'=> implode(',', $fieldLabelsList),
								'visible'	=> $activityTypes['visible'],
								'color'		=> $activityTypes['color'],
								'type'		=> $type,
								'conditions'=> array(
												'name' => $conditionsName,
												'rules' => $activityTypes['conditions']
												)
			);

			if($activityTypes['visible'] == '1') {
				if ($fieldLabelsList) {
					$calendarViewTypes['visible'][] = $fieldInfo;
				}
			} else {
				$calendarViewTypes['invisible'][] = $fieldInfo;
			}
		}
		return $calendarViewTypes;
	}

	public static function getDateFieldModulesList() {
		$db = PearDatabase::getInstance();

		$query = 'SELECT DISTINCT(name) AS modulename FROM jo_tab 
				  LEFT JOIN jo_field ON jo_field.tabid = jo_tab.tabid
				  WHERE jo_field.typeofdata LIKE ?';
		$result = $db->pquery($query, array('D~%'));
		$num_rows = $db->num_rows($result);

		$moduleList = array();
		for($i=0; $i<$num_rows; $i++) {
			$moduleList[] = $db->query_result($result, $i, 'modulename');
		}
		//Remove the modules not having owner field, need to show activities based on owner field
		$moduleList = array_diff($moduleList, array('PriceBooks', 'Faq'));

		$moduleFieldsList = array();
		foreach($moduleList as $module) {
			$moduleModel = Head_Module_Model::getInstance($module);
			//If there is no module view permission, should not show in calendar view
			if(!$moduleModel->isPermitted('Detail')) {
				continue;
			}
			$dateFields = $moduleModel->getFieldsByType('date');
			$fieldsList = array();
			foreach($dateFields as $fieldName=>$fieldModel) {
				//If there is no field view permission, should not show in calendar view
				if($fieldModel->isViewableInDetailView()) {
					$fieldsList[$fieldName] = vtranslate($fieldModel->get('label'), $module);
				}
			}
			if(!empty($fieldsList)) {
				$moduleFieldsList[$module] = $fieldsList;
			}
		}

		$eventFieldsList = array('date_start','due_date');
		$eventFieldLabelsList = array();
		$moduleInstance = Head_Module_Model::getInstance('Events');
		foreach ($eventFieldsList as $fieldName) {
			$fieldInstance = Head_Field_Model::getInstance($fieldName, $moduleInstance);
			if ($fieldInstance) {
				$eventFieldLabelsList[$fieldName] = $fieldInstance->label;
			}
		}

		$calendarFieldsList = array('date_start','due_date');
		$calendarFieldLabelsList = array();
		$calendarInstance = Head_Module_Model::getInstance('Calendar');
		foreach ($calendarFieldsList as $fieldName) {
			$fieldModel = Head_Field_Model::getInstance($fieldName, $calendarInstance);
			if ($fieldModel) {
				$calendarFieldLabelsList[$fieldName] = $fieldModel->label;
			}
		}

		//Default activity types Events and Calendar should append to the date fields list
		$moduleFieldsList['Events'][implode(',', array_keys($eventFieldLabelsList))] = implode(',' , $eventFieldLabelsList);
		$moduleFieldsList['Calendar'][implode(',', array_keys($calendarFieldLabelsList))] = implode(',' , $calendarFieldLabelsList);

		return $moduleFieldsList;
	}

	function getCalendarViewTypesToAdd($userId) {
		$calendarViewTypes = self::getCalendarViewTypes($userId);
		$moduleViewTypes = self::getDateFieldModulesList();

		$visibleList = $calendarViewTypes['visible'];
		if(is_array($visibleList)) {
			foreach($visibleList as $list) {
				$fieldsListArray = $moduleViewTypes[$list['module']];
				if(count($fieldsListArray) == 1) {
					if($list['module'] !== 'Events') {
						unset($fieldsListArray[$list['fieldname']]);
					}
				}
				if(!empty($fieldsListArray)) {
					$moduleViewTypes[$list['module']] = $fieldsListArray;
				} else {
					unset($moduleViewTypes[$list['module']]);
				}
			}
		}
		return $moduleViewTypes;
	}

	function getVisibleCalendarViewTypes($userId) {
		$db = PearDatabase::getInstance();

		$query = "SELECT * FROM jo_calendar_user_activitytypes 
			INNER JOIN jo_calendar_default_activitytypes on jo_calendar_default_activitytypes.id=jo_calendar_user_activitytypes.defaultid 
			WHERE jo_calendar_user_activitytypes.userid=? AND jo_calendar_user_activitytypes.visible=?";
		$result = $db->pquery($query, array($userId,'1'));
		$rows = $db->num_rows($result);

		$calendarViewTypes = Array();
		for($i=0; $i<$rows; $i++) {
			$activityTypes = $db->query_result_rowdata($result, $i);
			$moduleInstance = Head_Module_Model::getInstance($activityTypes['module']);
			//If there is no module view permission, should not show in calendar view
			if(!$moduleInstance->isPermitted('Detail')) {
				continue;
			}

			$fieldNamesList = Zend_Json::decode(html_entity_decode($activityTypes['fieldname']));
			$fieldLabelsList = array();
			foreach ($fieldNamesList as $fieldName) {
				$fieldInstance = Head_Field_Model::getInstance($fieldName, $moduleInstance);
				if ($fieldInstance) {
					//If there is no field view permission, should not show in calendar view
					if (!$fieldInstance->isViewableInDetailView()) {
						$fieldLabelsList = array();
						break;
					}
					$fieldLabelsList[$fieldName] = $fieldInstance->label;
				}
			}
			if(!empty($fieldLabelsList)) {
				$calendarViewTypes[$activityTypes['module']][implode(',', array_keys($fieldLabelsList))] = implode(',' , $fieldLabelsList);
			}
		}
		return $calendarViewTypes;
	}

	/**
	 *  Function to check duplicate activity view while adding
	 * @return <boolean>
	 */
	public function checkDuplicateView(Head_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$userId = $currentUser->getId();
		$viewmodule = $request->get('viewmodule');
		$fieldName = $request->get('viewfieldname');
		$conditions = $request->get('viewConditions','');
		$viewfieldname = Array();
		$viewfieldname = Zend_Json::encode(explode(',',$fieldName));
		$db = PearDatabase::getInstance();
        if($conditions)
    		$queryResult = $db->pquery('SELECT id FROM jo_calendar_default_activitytypes WHERE module=? AND fieldname=? AND conditions=?', array($viewmodule, $viewfieldname,$conditions));
        else
            $queryResult = $db->pquery('SELECT id FROM jo_calendar_default_activitytypes WHERE module=? AND fieldname=?', array($viewmodule, $viewfieldname));
        
		if($db->num_rows($queryResult) > 0) {
			$defaultId = $db->query_result($queryResult, 0, 'id');

			$query = $db->pquery('SELECT 1 FROM jo_calendar_user_activitytypes WHERE defaultid=? AND userid=? AND visible=?', array($defaultId, $userId, '1'));
			if($db->num_rows($query) > 0) {
				return true;
			}
		}
		return false;
	}

	/**
	 *  Function to delete calendar view
	 */
	public function deleteCalendarView(Head_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$userId = $currentUser->getId();
		$viewmodule = $request->get('viewmodule');
		$fieldName = $request->get('viewfieldname');
		$viewconditions = $request->get('viewConditions','');
		$viewfieldname = Array();
		$viewfieldname = Zend_Json::encode(explode(',',$fieldName));

		$db = PearDatabase::getInstance();
		$defaultIdQueryResult = $db->pquery('SELECT id FROM jo_calendar_default_activitytypes WHERE module=? AND fieldname=? AND isdefault=? AND conditions=?', array($viewmodule, $viewfieldname, 0, $viewconditions));
		if($db->num_rows($defaultIdQueryResult) > 0) {
			$defaultId = $db->query_result($defaultIdQueryResult, 0, 'id');
			$db->pquery('DELETE FROM jo_calendar_user_activitytypes WHERE defaultid=? AND jo_calendar_user_activitytypes.userid=?', array($defaultId, $userId));

			$queryResult = $db->pquery('SELECT 1 FROM jo_calendar_user_activitytypes WHERE defaultid=?', array($defaultId));
			if($db->num_rows($queryResult) <= 0) {
				$db->pquery('DELETE FROM jo_calendar_default_activitytypes WHERE module=? AND fieldname=? AND isdefault=? AND id=? AND conditions=?', array($viewmodule, $viewfieldname, 0, $defaultId, $viewconditions));
			}
		} else {
			$db->pquery('UPDATE jo_calendar_user_activitytypes 
						INNER JOIN jo_calendar_default_activitytypes ON jo_calendar_default_activitytypes.id = jo_calendar_user_activitytypes.defaultid
						SET jo_calendar_user_activitytypes.visible=? WHERE jo_calendar_user_activitytypes.userid=? AND jo_calendar_default_activitytypes.module=? AND jo_calendar_default_activitytypes.fieldname=? AND 
						jo_calendar_default_activitytypes.conditions=?', 
							array('0', $userId, $viewmodule, $viewfieldname, $viewconditions));
		}
	}

	/**
	 *  Function to add calendar view
	 * @return <string>
	 */
	public function addCalendarView(Head_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$userId = $currentUser->getId();
		$viewmodule = $request->get('viewmodule');
		$fieldName = $request->get('viewfieldname');
		$viewcolor = $request->get('viewColor');
		$viewconditions = $request->get('viewConditions','');
		$viewfieldname = Array();
		$viewfieldname = Zend_Json::encode(explode(',',$fieldName));

		$db = PearDatabase::getInstance();
		$queryResult = $db->pquery('SELECT id,isdefault FROM jo_calendar_default_activitytypes WHERE module=? AND fieldname=? AND conditions=?', array($viewmodule, $viewfieldname, $viewconditions));
		$type = '';
		if($db->num_rows($queryResult) > 0) {
			$defaultId = $db->query_result($queryResult, 0, 'id');
			$isDefault = $db->query_result($queryResult, 0, 'isdefault');

			if(in_array($viewmodule, array('Events','Calendar')) && $isDefault) {
				$type = $viewmodule.'_'.$isDefault;
			}

			$query = $db->pquery('SELECT 1 FROM jo_calendar_user_activitytypes WHERE defaultid=? AND userid=?', array($defaultId, $userId));
			if($db->num_rows($query) > 0) {
				$db->pquery('UPDATE jo_calendar_user_activitytypes 
							INNER JOIN jo_calendar_default_activitytypes ON jo_calendar_default_activitytypes.id = jo_calendar_user_activitytypes.defaultid
							SET jo_calendar_user_activitytypes.color=?, jo_calendar_user_activitytypes.visible=? 
							WHERE jo_calendar_user_activitytypes.userid=? AND jo_calendar_default_activitytypes.module=? AND jo_calendar_default_activitytypes.fieldname=? 
							AND jo_calendar_default_activitytypes.conditions=?',
								array($viewcolor, '1', $userId, $viewmodule, $viewfieldname, $viewconditions));
			} else {
				$db->pquery('INSERT INTO jo_calendar_user_activitytypes (id, defaultid, userid, color) VALUES (?,?,?,?)', array($db->getUniqueID('jo_calendar_user_activitytypes'), $defaultId, $userId, $viewcolor));
			}
		} else {
			$defaultId = $db->getUniqueID('jo_calendar_default_activitytypes');
			$db->pquery('INSERT INTO jo_calendar_default_activitytypes (id, module, fieldname, defaultcolor, isdefault, conditions) VALUES (?,?,?,?,?,?)', array($defaultId, $viewmodule, $viewfieldname, $viewcolor, '0', $viewconditions));

			$db->pquery('INSERT INTO jo_calendar_user_activitytypes (id, defaultid, userid, color) VALUES (?,?,?,?)', array($db->getUniqueID('jo_calendar_user_activitytypes'), $defaultId, $userId, $viewcolor));
		}

		return $type;
	}

	/**
	 *  Function to get all calendar view conditions
	 * @return <string>
	 */
	public function getCalendarViewConditions() {
		$eventsModuleModel = Head_Module_Model::getInstance('Events');
		$eventTypePicklistValues = $eventsModuleModel->getField('activitytype')->getPicklistValues();
		$eventsModuleConditions = array();

		foreach($eventTypePicklistValues as $picklistValue=>$picklistLabel) {
			$eventsModuleConditions[$picklistLabel] = array('fieldname' => 'activitytype','operator' => 'e','value'=>$picklistValue);
		}

		$conditions = array(
			'Events' => $eventsModuleConditions
		);

		return $conditions;
	}

	/**
	 *  Function returns the url for Shared Calendar view
	 * @return <String>
	 */
	public function getSharedCalendarViewUrl() {
        global $site_URL;
		return $site_URL.$this->get('name').'/view/SharedCalendar';
	}

	/**
	 * Function to delete shared users
	 * @param type $currentUserId
	 */
	public function deleteSharedUsers($currentUserId){
		$db = PearDatabase::getInstance();
		$delquery = "DELETE FROM jo_sharedcalendar WHERE userid=?";
		$db->pquery($delquery, array($currentUserId));
	}

	/**
	 * Function to insert shared users
	 * @param type $currentUserId
	 * @param type $sharedIds
	 */
	public function insertSharedUsers($currentUserId, $sharedIds, $sharedType = FALSE){
		$db = PearDatabase::getInstance();
		foreach ($sharedIds as $sharedId) {
			if($sharedId != $currentUserId) {
				$sql = "INSERT INTO jo_sharedcalendar VALUES (?,?)";
				$db->pquery($sql, array($currentUserId, $sharedId));
			}
		}
	}

	/**
	 * Function to get shared type
	 * @param type $currentUserId
	 * @param type $sharedIds
	 */
	public function getSharedType($currentUserId){
		$db = PearDatabase::getInstance();

		$query = "SELECT calendarsharedtype FROM jo_users WHERE id=?";
		$result = $db->pquery($query, array($currentUserId));
		if($db->num_rows($result) > 0){
			$sharedType = $db->query_result($result,0,'calendarsharedtype');
		}
		return $sharedType;
	}

	/**
	 * Function to get Alphabet Search Field
	 */
	public function getAlphabetSearchField(){
		return 'subject';
	}

	/**
	 * Function to get the list of recently visisted records
	 * @param <Number> $limit
	 * @return <Array> - List of Calendar_Record_Model
	 */
	public function getRecentRecords($limit=10) {
		$db = PearDatabase::getInstance();

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$deletedCondition = parent::getDeletedRecordCondition();
		$nonAdminQuery .= Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName());

		$query = 'SELECT * FROM jo_crmentity ';
		if($nonAdminQuery){
			$query .= " INNER JOIN jo_activity ON jo_crmentity.crmid = jo_activity.activityid ".$nonAdminQuery;
		}
		$query .= ' WHERE setype=? AND '.$deletedCondition.' AND modifiedby = ? ORDER BY modifiedtime DESC LIMIT ?';
		$params = array('Calendar', $currentUserModel->id, $limit);
		$result = $db->pquery($query, $params);
		$noOfRows = $db->num_rows($result);
		$recentRecords = array();
		for($i=0; $i<$noOfRows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$row['id'] = $row['crmid'];
			$recentRecords[$row['id']] = $this->getRecordFromArray($row);
		}
		return $recentRecords;
	}

	public function getAllTasksbyPriority($conditions = false, $pagingModel) {
		global $current_user;
		$db = PearDatabase::getInstance();

		$queryGenerator = new QueryGenerator("Calendar",$current_user);

		$moduleModel = Head_Module_Model::getInstance("Calendar");
		$quickCreateFields = $moduleModel->getQuickCreateFields();
		$mandatoryFields = array("id","taskpriority","parent_id","contact_id");
		$fields = array_unique(array_merge($mandatoryFields,array_keys($quickCreateFields)));
		$queryGenerator->setFields($fields);
		$queryGenerator->addCondition("activitytype","Task","e","AND");
		if($conditions){
			foreach($conditions as $condition){
				if($condition["comparator"] === 'bw'){
					$condition['fieldValue'] = implode(",",$condition['fieldValue']);
				}
				$queryGenerator->addCondition($condition['fieldName'],$condition['fieldValue'],$condition['comparator'],"AND");
			}
		}
		$query = $queryGenerator->getQuery();

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		$query .= " LIMIT $startIndex,".($pageLimit+1);

		$result = $db->pquery($query,array());
		$noOfRows = $db->num_rows($result);

		$mandatoryReferenceFields = array("parent_id","contact_id");
		$tasks = array();
		for($i=0;$i<$noOfRows;$i++){
			$newRow = $db->query_result_rowdata($result, $i);
			$model = Head_Record_Model::getCleanInstance('Calendar');
			$model->setData($newRow);
			$model->setId($newRow['activityid']);
			$basicInfo = array();
			foreach($quickCreateFields as $fieldName => $fieldModel){
				if(in_array($fieldName,$mandatoryReferenceFields)){
					continue;
				}
				$columnName = $fieldModel->get("column");
				$fieldType = $fieldModel->getFieldDataType();
				$value = $model->get($columnName);
				switch($fieldType){
					case "reference":	if(!empty($value)){
											$value = array("id"=>$value,"display_value"=>Head_Functions::getCRMRecordLabel($value),"module"=>Head_Functions::getCRMRecordType($value));

										}
										break;
					case "datetime":	$value = Head_Date_UIType::getDisplayDateValue($value);
										break;
				}
				$basicInfo[$fieldName] = $value;
			}

			foreach($mandatoryReferenceFields as $fieldName){
				if($fieldName == "parent_id"){
					$value = $model->get("crmid");
				} else {
					$value = $model->get("contactid");
				}
				if(!empty($value)){
					$value = array("id"=>$value,"display_value"=>Head_Functions::getCRMRecordLabel($value),"module"=>Head_Functions::getCRMRecordType($value));

				}
				$basicInfo[$fieldName] = $value;
			}

			$model->set("basicInfo",  $basicInfo);

			$priority = $model->get('priority');
			if($priority){
				$tasks[$priority][$model->getId()] = $model;
			}
		}

		if(count($tasks[$priority]) > $pageLimit){
			array_pop($tasks[$priority]);
			$pagingModel->set('nextPageExists', true);
		}else{
			$pagingModel->set('nextPageExists', false);
		}

		return $tasks;
	}

	/**
	 * Function returns Calendar Reminder record models
	 * @return <Array of Calendar_Record_Model>
	 */
	public static function getCalendarReminder() {
		$db = PearDatabase::getInstance();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$activityReminder = $currentUserModel->getCurrentUserActivityReminderInSeconds();
		$recordModels = array();

		if($activityReminder != '' ) {
			$currentTime = time();
			$date = date('Y-m-d', strtotime("+$activityReminder seconds", $currentTime));
			$time = date('H:i',   strtotime("+$activityReminder seconds", $currentTime));
			$reminderActivitiesResult = "SELECT reminderid, recordid FROM jo_activity_reminder_popup
								INNER JOIN jo_activity on jo_activity.activityid = jo_activity_reminder_popup.recordid
								INNER JOIN jo_crmentity ON jo_activity_reminder_popup.recordid = jo_crmentity.crmid
								WHERE jo_activity_reminder_popup.status = 0
								AND jo_crmentity.smownerid = ? AND jo_crmentity.deleted = 0
								AND ((DATE_FORMAT(jo_activity_reminder_popup.date_start,'%Y-%m-%d') <= ?)
								AND (TIME_FORMAT(jo_activity_reminder_popup.time_start,'%H:%i') <= ?))
								AND jo_activity.eventstatus <> 'Held' AND (jo_activity.status <> 'Completed' OR jo_activity.status IS NULL) LIMIT 20";
			$result = $db->pquery($reminderActivitiesResult, array($currentUserModel->getId(), $date, $time));
			$rows = $db->num_rows($result);
			for($i=0; $i<$rows; $i++) {
				$recordId = $db->query_result($result, $i, 'recordid');
				$recordModels[] = Head_Record_Model::getInstanceById($recordId, 'Calendar');
			}
		}
		return $recordModels;
	}

	/**
	 * Function gives fields based on the type
	 * @param <String> $type - field type
	 * @return <Array of Head_Field_Model> - list of field models
	 */
	public function getFieldsByType($type) {
		$restrictedField = array('picklist'=>array('eventstatus', 'recurringtype', 'visibility', 'duration_minutes'));

		if(!is_array($type)) {
			$type = array($type);
		}
		$fields = $this->getFields();
		$fieldList = array();
		foreach($fields as $field) {
			$fieldType = $field->getFieldDataType();
			if(in_array($fieldType,$type)) {
				$fieldName = $field->getName();
				if($fieldType == 'picklist' && in_array($fieldName, $restrictedField[$fieldType])) {
				} else {
					$fieldList[$fieldName] = $field;
				}
			}
		}
		return $fieldList;
	}

	/**
	 * Function returns Settings Links
	 * @return Array
	 */
	public function getSettingLinks() {
		global $site_URL;
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$settingLinks = array();

		if($currentUserModel->isAdminUser()) {
			$settingLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_EDIT_FIELDS',
					'linkurl' => $site_URL.'LayoutEditor/Settings/source/'.$this->getName(),
					'linkicon' => Head_Theme::getImagePath('LayoutEditor.gif')
			);

			 $settingLinks[] = array( 
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_EDIT_WORKFLOWS',
					'linkurl' => $site_URL.'Workflows/Settings/List/source/'.$this->getName(),
					'linkicon' => ''
			);
			$settingLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_EDIT_PICKLIST_VALUES',
					'linkurl' => $site_URL.'Picklist/Settings/Index/source/'.$this->getName(),
					'linkicon' => ''
			);
		}
		return $settingLinks;
	}

	/**
	 * Function to get orderby sql from orderby field
	 */
	public function getOrderBySql($orderBy){
		 if($orderBy == 'status'){
			 return $orderBy;
		 }
		 return parent::getOrderBySql($orderBy);
	}

	/**
	* Function is used to give links in the All menu bar
	*/
	public function getQuickMenuModels() {
		if($this->isEntityModule()) {
			$moduleName = $this->getName();
			$listViewModel = Head_ListView_Model::getCleanInstance($moduleName);
			$basicListViewLinks = $listViewModel->getBasicLinks();
		}

		if($basicListViewLinks) {
			foreach($basicListViewLinks as $basicListViewLink) {
				if(is_array($basicListViewLink)) {
					$links[] = Head_Link_Model::getInstanceFromValues($basicListViewLink);
				} else if(is_a($basicListViewLink, 'Head_Link_Model')) {
					$links[] = $basicListViewLink;
				}
			}
		}
		return $links;
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	function getUtilityActionsNames() {
		return array('Import', 'Export');
	}

	 /**
	 * Function which will be give you the actions that are allowed when this module is added as a tab 
	 */
	public function getRelationShipActions() {
		return array('ADD');
	}
}
