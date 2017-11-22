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
vimport('~~/vtlib/Head/Module.php');

/**
 * Head Module Model Class
 */
class Head_Module_Model extends Head_Module {

	protected $blocks = false;
	protected $nameFields = false;
	protected $moduleMeta = false;
	protected $fields = false;
	protected $relations = null;

	/**
	 * Function to get the Module/Tab id
	 * @return <Number>
	 */
	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * Function to check whether the module is an entity type module or not
	 * @return <Boolean> true/false
	 */
	public function isEntityModule() {
		return ($this->isentitytype== '1') ? true :false ;
	}

	/**
	 * Function to check whether the module is enabled for quick create
	 * @return <Boolean> - true/false
	 */
	public function isQuickCreateSupported() {
		return $this->isEntityModule();
	}

	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return true;
	}

	/**
	 * Function to get singluar label key
	 * @return <String> - Singular module label key
	 */
	public function getSingularLabelKey(){
		return 'SINGLE_'.$this->get('name');
	}

	/**
	 * Function to get the value of a given property
	 * @param <String> $propertyName
	 * @return <Object>
	 * @throws Exception
	 */
	public function get($propertyName) {
		if(property_exists($this,$propertyName)){
			return $this->$propertyName;
		}
		throw new Exception( $propertyName.' doest not exists in class '.get_class($this));
	}

	/**
	 * Function to set the value of a given property
	 * @param <String> $propertyName
	 * @param <Object> $propertyValue
	 * @return Head_Module_Model instance
	 */
	public function set($propertyName, $propertyValue) {
		$this->$propertyName = $propertyValue;
		return $this;
	}

	/**
	 * Function checks if the module is Active
	 * @return <Boolean>
	 */
	public function isActive() {
		return in_array($this->get('presence'), array(0,2));
	}

	/**
	 * Function checks if the module is enabled for tracking changes
	 * @return <Boolean>
	 */
	public function isTrackingEnabled() {
		require_once 'modules/ModTracker/ModTracker.php';
		$trackingEnabled = ModTracker::isTrackingEnabledForModule($this->getName());
		return ($this->isActive() && $trackingEnabled);
	}

	/**
	 * Function checks if comment is enabled
	 * @return boolean
	 */
	public function isCommentEnabled() {
		$enabled = false;
		$db = PearDatabase::getInstance();
		$commentsModuleModel = Head_Module_Model::getInstance('ModComments');
		if($commentsModuleModel && $commentsModuleModel->isActive()) {
			$relatedToFieldResult = $db->pquery('SELECT fieldid FROM jo_field WHERE fieldname = ? AND tabid = ?',
					array('related_to', $commentsModuleModel->getId()));
			$fieldId = $db->query_result($relatedToFieldResult, 0, 'fieldid');
			if(!empty($fieldId)) {
				$relatedModuleResult = $db->pquery('SELECT relmodule FROM jo_fieldmodulerel WHERE fieldid = ?', array($fieldId));
				$rows = $db->num_rows($relatedModuleResult);

				for($i=0; $i<$rows; $i++) {
					$relatedModule = $db->query_result($relatedModuleResult, $i, 'relmodule');
					if($this->getName() == $relatedModule) {
						$enabled = true;
					}
				}
			}
		} else {
			$enabled = false;
		}
		return $enabled;
	}

	public function isQuickPreviewEnabled(){
		$enabled = false;
		if($this->isSummaryViewSupported()){
			$enabled = true;
		}
		return $enabled;
	}
	/**
	 * Function to save a given record model of the current module
	 * @param Head_Record_Model $recordModel
	 */
	public function saveRecord(Head_Record_Model $recordModel) {
		global $adb;
                 $this->db = PearDatabase::getInstance();
                $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
		$moduleName = $this->get('name');
		$focus = $recordModel->getEntity();
		$fields = $focus->column_fields;
		foreach($fields as $fieldName => $fieldValue) {
			$fieldValue = $recordModel->get($fieldName);
			if(is_array($fieldValue)){
				$focus->column_fields[$fieldName] = $fieldValue;
			}else if($fieldValue !== null) {
				/*
				 * for ajax edit, in Head_SaveAjax_Action we are setting relatedContact to 
				 * the record model which is an object
				 * Note : decode_html expects only strings
				 */
				$value = is_string($fieldValue) ? decode_html($fieldValue) : $fieldValue;
				$focus->column_fields[$fieldName] = $value;
			}
		}
		$focus->mode = $recordModel->get('mode');
		$focus->id = $recordModel->getId();
		$focus->save($moduleName);
		return $recordModel->setId($focus->id);
	}

	/**
	 * Function to delete a given record model of the current module
	 * @param Head_Record_Model $recordModel
	 */
	public function deleteRecord(Head_Record_Model $recordModel) {
		$moduleName = $this->get('name');
		$focus = CRMEntity::getInstance($moduleName);
		$focus->trash($moduleName, $recordModel->getId());
		if(method_exists($focus, 'transferRelatedRecords')) {
			if($recordModel->get('transferRecordIDs'))
				$focus->transferRelatedRecords($moduleName, $recordModel->get('transferRecordIDs'), $recordModel->getId());
		}
	}

	/**
	 * Function to get the module meta information
	 * @param <type> $userModel - user model
	 */
	public function getModuleMeta($userModel = false) {
		if(empty($this->moduleMeta)){
			if(empty($userModel)) {
			$userModel = Users_Record_Model::getCurrentUserModel();
		}
			$this->moduleMeta = Head_ModuleMeta_Model::getInstance($this->get('name'), $userModel);
		}
		return $this->moduleMeta;
	}

	//Note : This api is using only in RelationListview - for getting columnfields of Related Module
	//Need to review........

	/**
	 * Function to get the module field mapping
	 * @return <array>
	 */
	public function getColumnFieldMapping(){
		$moduleMeta = $this->getModuleMeta();
		$meta = $moduleMeta->getMeta();
		$fieldColumnMapping =  $meta->getFieldColumnMapping();
		return array_flip($fieldColumnMapping);
	}

	/**
	 * Function to get the ListView Component Name
	 * @return string
	 */
	public function getListViewName() {
		return 'List';
	}

	/**
	 * Function to get the DetailView Component Name
	 * @return string
	 */
	public function getDetailViewName() {
		return 'Detail';
	}

	/**
	 * Function to get the EditView Component Name
	 * @return string
	 */
	public function getEditViewName(){
		return 'Edit';
	}

	/**
	 * Function to get the DuplicateView Component Name
	 * @return string
	 */
	public function getDuplicateViewName(){
		return 'Edit';
	}

	/**
	 * Function to get the Delete Action Component Name
	 * @return string
	 */
	public function getDeleteActionName() {
		return 'Delete';
	}

	/**
	 * Function to get the Default View Component Name
	 * @return string
	 */
	public function getDefaultViewName() {
		return 'List';
	}

	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public function getDefaultUrl() {
                global $site_URL;
                return $site_URL . $this->get('name') . '/' . $this->getDefaultViewName();
	}

	/**
	 * Function to get the url for list view of the module
	 * @return <string> - url
	 */
	public function getListViewUrl() {
                global $site_URL;
                return $site_URL . $this->get('name') . '/' . $this->getListViewName();
	}

	/**
	 * Function returns the All filter for the module
	 * @return <Int> custom filter id
	 */
	public function getAllFilterCvidForModule() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery("SELECT cvid FROM jo_customview WHERE viewname = 'All' AND entitytype = ?",
					array($this->getName()));
		if ($db->num_rows($result)) {
			return $db->query_result($result, 0, 'cvid');
		}
		return false;
	}

	/**
	 * Function to get listview url with all filter
	 * @return <string> URL
	 */
	public function getListViewUrlWithAllFilter(){
            if($this->getAllFilterCvidForModule)
                return $this->getListViewUrl() . '/' . $this->getAllFilterCvidForModule();
            else
                return $this->getListViewUrl();
	}

	/**
	 * Function to get the url for the Create Record view of the module
	 * @return <String> - url
	 */
	public function getCreateRecordUrl($rest = false) {
                global $site_URL;
                if($rest)
                        return $site_URL . $this->get('name') . '/' . $this->getEditViewName();

	        return $site_URL . $this->get('name') . '/' . $this->getEditViewName();
	}

	/**
	 * Function to get the url for the Create Record view of the module
	 * @return <String> - url
	 */
	public function getQuickCreateUrl() {
                global $site_URL;
		return $site_URL.'index.php?module='.$this->get('name').'&view=QuickCreateAjax';
	}

	/**
	 * Function to get the url for the Import action of the module
	 * @return <String> - url
	 */
	public function getImportUrl() {
                global $site_URL;
	        return $site_URL . $this->get('name') . '/Import';
	}

	/**
	 * Function to get the url for the Export action of the module
	 * @return <String> - url
	 */
	public function getExportUrl() {
  //              global $site_URL;
//	        return $site_URL . $this->get('name') . '/Export';
                return 'index.php?module='.$this->get('name').'&view=Export';

	}

	/**
	 * Function to get the url for the Find Duplicates action of the module
	 * @return <String> - url
	 */
	public function getFindDuplicatesUrl() {
                global $site_URL;
	        return $site_URL . $this->get('name') . '/FindDuplicates';
	}

	/**
	 * Function to get the url to view Dashboard for the module
	 * @return <String> - url
	 */
	public function getDashBoardUrl() {
                global $site_URL;
	        return $site_URL . $this->get('name') . '/DashBoard';
	}

	/**
	 * Function to get the url to view Details for the module
	 * @return <String> - url
	 */
	public function getDetailViewUrl($id) {
                global $site_URL;
	        return $site_URL . $this->get('name') . '/' . $this->getDetailViewName() . '/' . $id;
	}
	/**
	 * Function to get a Head Record Model instance from an array of key-value mapping
	 * @param <Array> $valueArray
	 * @return Head_Record_Model or Module Specific Record Model instance
	 */
	public function getRecordFromArray($valueArray, $rawData=false) {
		$modelClassName = Head_Loader::getComponentClassName('Model', 'Record', $this->get('name'));
		$recordInstance = new $modelClassName();
		return $recordInstance->setData($valueArray)->setModuleFromInstance($this)->setRawData($rawData);
	}

	/**
	 * Function returns all the blocks for the module
	 * @return <Array of Head_Block_Model> - list of block models
	 */
	public function getBlocks() {
		if(empty($this->blocks)) {
			$blocksList = array();
			$moduleBlocks = Head_Block_Model::getAllForModule($this);
			foreach($moduleBlocks as $block){
				$blocksList[$block->get('label')] = $block;
			}
			$this->blocks = $blocksList;
		}
		return $this->blocks;
	}

	/**
	 * Function that returns all the fields for the module
	 * @return <Array of Head_Field_Model> - list of field models
	 */
	public function getFields() {
		if(empty($this->fields)){
			$moduleBlockFields = Head_Field_Model::getAllForModule($this);
			$this->fields = array();
			foreach($moduleBlockFields as $moduleFields){
				foreach($moduleFields as $moduleField){
					 $block = $moduleField->get('block');
					if(empty($block)) {
						continue;
				}
					$this->fields[$moduleField->get('name')] = $moduleField;
			}
			}
		}
		return $this->fields;
	}


	/**
	 * Function gives fields based on the type
	 * @param <String> $type - field type
	 * @return <Array of Head_Field_Model> - list of field models
	 */
	public function getFieldsByType($type) {
		if(!is_array($type)) {
			$type = array($type);
		}
		$fields = $this->getFields();
		$fieldList = array();
		foreach($fields as $field) {
			$fieldType = $field->getFieldDataType();
			if(in_array($fieldType,$type)) {
				$fieldList[$field->getName()] = $field;
			}
		}
		return $fieldList;
	}

	/**
	 * Function gives fields based on the type
	 * @return <Head_Field_Model> with field label as key
	 */
	public function getFieldsByLabel() {
		$fields = $this->getFields();
		$fieldList = array();
		foreach($fields as $field) {
			$fieldLabel = $field->get('label');
			$fieldList[$fieldLabel] = $field;
		}
		return $fieldList;
	}

	/**
	 * Function gives fields based on the fieldid
	 * @return <Head_Field_Model> with field id as key
	 */
	public function getFieldsById() {
		$fields = $this->getFields();
		$fieldList = array();
		foreach($fields as $field) {
			$fieldId = $field->getId();
			$fieldList[$fieldId] = $field;
		}
		return $fieldList;
	}

	/**
	 * Function returns all the relation models
	 * @return <Array of Head_Relation_Model>
	 */
	public function getRelations() {
		if(empty($this->relations)) {
			return Head_Relation_Model::getAllRelations($this);
		}
		return $this->relations;
	}

	/**
	 * Function that returns all the quickcreate fields for the module
	 * @return <Array of Head_Field_Model> - list of field models
	 */
	public function getQuickCreateFields() {
		$blocksList = $this->getBlocks();
		$quickCreateFieldList = array();
		foreach($blocksList as $blockName => $blockModel) {
			$fieldList = $blockModel->getFields();
			foreach($fieldList as $fieldName => $fieldModel) {
				if($fieldModel->isQuickCreateEnabled() && $fieldModel->isEditable()) {
					$quickCreateFieldList[$fieldName] = $fieldModel;
				}
			}
		}
		return $quickCreateFieldList;
	}

	/**
	 * Function to get the field mode
	 * @param <String> $fieldName - field name
	 * @return <Head_Field_Model>
	 */
	public function getField($fieldName){
		return Head_Field_Model::getInstance($fieldName,$this);
	}

	/**
	 * Function to get the field by column name.
	 * @param <String> $columnName - column name
	 * @return <Head_Field_Model>
	 */
	public function getFieldByColumn($columnName) {
		$fields = $this->getFields();
		if ($fields) {
			foreach ($fields as $field) {
				if ($field->get('column') == $columnName) {
					return $field;
				}
			}
		}
		return NULL;
	}

	/**
	 * Function to retrieve name fields of a module
	 * @return <array> - array which contains fields which together construct name fields
	 */
	public function getNameFields(){

		$nameFieldObject = Head_Cache::get('EntityField',$this->getName());
		$moduleName = $this->getName();
		if($nameFieldObject && $nameFieldObject->fieldname) {
			$this->nameFields = explode(',', $nameFieldObject->fieldname);
		} else {
			$adb = PearDatabase::getInstance();

			$query = "SELECT fieldname, tablename, entityidfield FROM jo_entityname WHERE tabid = ?";
			$result = $adb->pquery($query, array($this->getId()));
			$this->nameFields = array();
			if($result){
				$rowCount = $adb->num_rows($result);
				if($rowCount > 0){
					$fieldNames = $adb->query_result($result,0,'fieldname');
					$this->nameFields = explode(',', $fieldNames);
				}
			}

			//added to handle entity names for these two modules
			//@Note: need to move these to database
			switch($moduleName) {
				case 'HelpDesk': $this->nameFields = array('ticket_title'); $fieldNames = 'ticket_title'; break;
				case 'Documents': $this->nameFields = array('notes_title'); $fieldNames = 'notes_title';  break;
			}
			$entiyObj = new stdClass();
			$entiyObj->basetable = $adb->query_result($result, 0, 'tablename');
			$entiyObj->basetableid =  $adb->query_result($result, 0, 'entityidfield');
			$entiyObj->fieldname =  $fieldNames;
			Head_Cache::set('EntityField',$this->getName(), $entiyObj);
		}

		return $this->nameFields;
	}

	/**
	 * Function to get the list of recently visisted records
	 * @param <Number> $limit
	 * @return <Array> - List of Head_Record_Model or Module Specific Record Model instances
	 */
	public function getRecentRecords($limit=10) {
		$db = PearDatabase::getInstance();

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$deletedCondition = $this->getDeletedRecordCondition();
		$nonAdminQuery .= Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName());
		$query = 'SELECT * FROM jo_crmentity '.$nonAdminQuery.' WHERE setype=? AND '.$deletedCondition.' AND modifiedby = ? ORDER BY modifiedtime DESC LIMIT ?';
		$params = array($this->getName(), $currentUserModel->id, $limit);
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

	/**
	 * Function that returns deleted records condition
	 * @return <String>
	 */
	public function getDeletedRecordCondition() {
		return 'jo_crmentity.deleted = 0';
	}

	/**
	 * Funtion that returns fields that will be showed in the record selection popup
	 * @return <Array of fields>
	 */
	public function getPopupFields() {
		$entityInstance = CRMEntity::getInstance($this->getName());
		return $entityInstance->search_fields_name;
	}

	/**
	 * Function that returns related list header fields that will be showed in the Related List View
	 * @return <Array> returns related fields list.
	 */
	public function getRelatedListFields() {
		$entityInstance = CRMEntity::getInstance($this->getName());
		$list_fields_name = $entityInstance->list_fields_name;
		$list_fields = $entityInstance->list_fields;
		$relatedListFields = array();
		foreach ($list_fields as $key => $fieldInfo) {
			foreach ($fieldInfo as $columnName) {
				if(array_key_exists($key, $list_fields_name)){
					$relatedListFields[$columnName] = $list_fields_name[$key];
				}
			}

		}
		return $relatedListFields;
	}

	public function getConfigureRelatedListFields(){
		$showRelatedFieldModel = $this->getHeaderAndSummaryViewFieldsList();
		$relatedListFields = array();
		if(count($showRelatedFieldModel) > 0) {
			foreach ($showRelatedFieldModel as $key => $field) {
				$relatedListFields[$field->get('column')] = $field->get('name');
			}
		}

		if(count($relatedListFields)>0) {
			$nameFields = $this->getNameFields();
			foreach($nameFields as $fieldName){
				if(!$relatedListFields[$fieldName]) {
					$fieldModel = $this->getField($fieldName);
					$relatedListFields[$fieldModel->get('column')] = $fieldModel->get('name');
				}
			}
		}

		return $relatedListFields;
	}

	public function isWorkflowSupported() {
		vimport('~~modules/com_jo_workflow/VTWorkflowUtils.php');
		if($this->isEntityModule() && VTWorkflowUtils::checkModuleWorkflow($this->getName())) {
			return true;
		}
		return false;
	}

	/**
	 * Function checks if a module has module sequence numbering
	 * @return boolean
	 */
	public function hasSequenceNumberField() {
		if(!empty($this->fields)) {
			$fieldList = $this->getFields();
			foreach($fieldList as $fieldName => $fieldModel) {
				if($fieldModel->get('uitype') === '4') {
					return true;
				}
			}
		}else{
			$db = PearDatabase::getInstance();
			$query = 'SELECT 1 FROM jo_field WHERE uitype=4 and tabid=?';
			$params = array($this->getId());
			$result = $db->pquery($query, $params);
			return $db->num_rows($result) > 0 ? true : false;
		}
		return false;
	}

	/**
	 * Static Function to get the instance of Head Module Model for the given id or name
	 * @param mixed id or name of the module
	 */
	public static function getInstance($value) {
		$instance = false;
		$moduleObject = parent::getInstance($value);
		if($moduleObject) {
			$instance = self::getInstanceFromModuleObject($moduleObject);
		}
		return $instance;
	}


	/**
	 * Function to get the instance of Head Module Model from a given Head_Module object
	 * @param Head_Module $moduleObj
	 * @return Head_Module_Model instance
	 */
	public static function getInstanceFromModuleObject(Head_Module $moduleObj){
		$objectProperties = get_object_vars($moduleObj);
		$modelClassName = Head_Loader::getComponentClassName('Model', 'Module', $objectProperties['name']);
		$moduleModel = new $modelClassName();
		foreach($objectProperties as $properName=>$propertyValue){
			$moduleModel->$properName = $propertyValue;
		}
		return $moduleModel;
	}

	/**
	 * Function to get the instance of Head Module Model from a given list of key-value mapping
	 * @param <Array> $valueArray
	 * @return Head_Module_Model instance
	 */
	public static function getInstanceFromArray($valueArray) {
		$modelClassName = Head_Loader::getComponentClassName('Model', 'Module', $valueArray['name']);
		$instance = new $modelClassName();
		$instance->initialize($valueArray);
		return $instance;
	}

	/**
	 * Function to get all modules from CRM
	 * @param <array> $presence
	 * @param <array> $restrictedModulesList
	 * @return <array> List of module models <Head_Module_Model>
	 */
	public static function getAll($presence = array(), $restrictedModulesList = array()) {
		$db = PearDatabase::getInstance();
		self::preModuleInitialize2();
		$cacheKey = 'modules';
		if(!$presence){
			$moduleModels = Head_Cache::get('vtiger', $cacheKey);
		}else{
			$cacheKey = 'modules_'.implode("_",$presence);
			$moduleModels = Head_Cache::get('vtiger', $cacheKey);
		}


		if(!$moduleModels){
			$moduleModels = array();

			$query = 'SELECT * FROM jo_tab';
			$params = array();
			if($presence) {
				$query .= ' WHERE presence IN ('. generateQuestionMarks($presence) .')';
				array_push($params, $presence);
			}

			$result = $db->pquery($query, $params);
			$noOfModules = $db->num_rows($result);
			for($i=0; $i<$noOfModules; ++$i) {
				$row = $db->query_result_rowdata($result, $i);
				$moduleModels[$row['tabid']] = self::getInstanceFromArray($row);
				Head_Cache::set('module',$row['tabid'], $moduleModels[$row['tabid']]);
				Head_Cache::set('module',$row['name'], $moduleModels[$row['tabid']]);
			}
			if(!$presence){
				Head_Cache::set('vtiger',$cacheKey,$moduleModels);
			}else{
				Head_Cache::set('vtiger', $cacheKey,$moduleModels);
			}
		}

		if($presence && $moduleModels){
			foreach ($moduleModels as $key => $moduleModel){
				if(!in_array($moduleModel->get('presence'), $presence)){
					unset($moduleModels[$key]);
				}
			}
		}

		if($restrictedModulesList && $moduleModels) {
			foreach ($moduleModels as $key => $moduleModel){
				if(in_array($moduleModel->getName(), $restrictedModulesList)){
					unset($moduleModels[$key]);
				}
			}
		}

		return $moduleModels;
	}

	public static function getEntityModules() {
		self::preModuleInitialize2();
		$moduleModels = Head_Cache::get('vtiger','EntityModules');
		if(!$moduleModels){
			$presence = array(0, 2);
			$moduleModels = self::getAll($presence);
			$restrictedModules = array('Webmails', 'Emails', 'Integration', 'Dashboard');
			foreach($moduleModels as $key => $moduleModel){
				if(in_array($moduleModel->getName(),$restrictedModules) || $moduleModel->get('isentitytype') != 1){
					unset($moduleModels[$key]);
				}
			}
			Head_Cache::set('vtiger','EntityModules',$moduleModels);
		}
		return $moduleModels;
	}

	/**
	 * Function to get the list of all searchable modules
	 * @return <Array> - List of Head_Module_Model instances
	 */
	public static function getSearchableModules() {
		$userPrivModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$entityModules = self::getEntityModules();

		$searchableModules = array();
		foreach ($entityModules as $tabid => $moduleModel) {
				$moduleName = $moduleModel->getName();
				if ($moduleName == 'Users' || $moduleName == 'Emails' || $moduleName == 'Events') continue;
				if($userPrivModel->hasModuleActionPermission($moduleModel->getId(), 'DetailView')) {
						$searchableModules[$moduleName] = $moduleModel;
				}
		}
		return $searchableModules;
	}

	protected static function preModuleInitialize2() {
		if(!Head_Cache::get('EntityField','all')){
			$db = PearDatabase::getInstance();
			// Initialize meta information - to speed up instance creation (Head_ModuleBasic::initialize2)
			$result = $db->pquery('SELECT modulename,tablename,entityidfield,fieldname FROM jo_entityname', array());

			for($index = 0, $len = $db->num_rows($result); $index < $len; ++$index) {

				$fieldNames = $db->query_result($result, $index, 'fieldname');
				$modulename = $db->query_result($result, $index, 'modulename');
				//added to handle entity names for these two modules
				//@Note: need to move these to database
				switch($modulename) {
					case 'HelpDesk': $fieldNames = 'ticket_title'; break;
					case 'Documents': $fieldNames = 'notes_title';  break;
				}
				$entiyObj = new stdClass();
				$entiyObj->basetable = $db->query_result($result, $index, 'tablename');
				$entiyObj->basetableid =  $db->query_result($result, $index, 'entityidfield');
				$entiyObj->fieldname =  $fieldNames;

				Head_Cache::set('EntityField',$modulename,$entiyObj);
				Head_Cache::set('EntityField','all',true);
			}
		}
	}

	public static function getPicklistSupportedModules() {
		vimport('~~/modules/PickList/PickListUtils.php');
		$modules = getPickListModules();
		$modulesModelsList = array();
		foreach($modules as $moduleLabel => $moduleName) {
			$instance = new self();
			$instance->name = $moduleName;
			$instance->label = $moduleLabel;
			$modulesModelsList[] = $instance;
		}
		return $modulesModelsList;
	}

	public static function getCleanInstance($moduleName){
		$modelClassName = Head_Loader::getComponentClassName('Model', 'Module', $moduleName);
		$instance = new $modelClassName();
		$instance->name = $moduleName;
		return $instance;
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
				'linklabel' => 'LBL_RECORDS_LIST',
				'linkurl' => $this->getListViewUrl(),
				'linkicon' => '',
			),
		);
		foreach($quickLinks as $quickLink) {
			$links['SIDEBARLINK'][] = Head_Link_Model::getInstanceFromValues($quickLink);
		}

/*		$quickWidgets = array(
			array(
				'linktype' => 'SIDEBARWIDGET',
				'linklabel' => 'LBL_RECENTLY_MODIFIED',
				'linkurl' => 'module='.$this->get('name').'&view=IndexAjax&mode=showActiveRecords',
				'linkicon' => ''
			),
		);
		foreach($quickWidgets as $quickWidget) {
			$links['SIDEBARWIDGET'][] = Head_Link_Model::getInstanceFromValues($quickWidget);
		}*/

		return $links;
	}

	/**
	 * Function returns export query - deprecated
	 * @param <String> $where
	 * @return <String> export query
	 */
	public function getExportQuery($where) {
		$focus = CRMEntity::getInstance($this->getName());
		$query = $focus->create_export_query($where);
		return $query;
	}

	/**
	 * Function returns the default custom filter for the module
	 * @return <Int> custom filter id
	 */
	public function getDefaultCustomFilter() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery("SELECT cvid FROM jo_customview WHERE setdefault = 1 AND entitytype = ?",
					array($this->getName()));
		if ($db->num_rows($result)) {
			return $db->query_result($result, 0, 'cvid');
		}
		return false;
	}

	/**
	 * Function returns latest comments for the module
	 * @param <Head_Paging_Model> $pagingModel
	 * @return <Array>
	 */
	public function getComments($pagingModel,$user, $dateFilter='') {
		$comments = array();
		if(!$this->isCommentEnabled()) {
			return $comments;
		}
		//TODO: need to handle security and performance
		$db = PearDatabase::getInstance();
		$params = array($this->getName());
		$sql = 'SELECT jo_modcomments.*,jo_crmentity.createdtime AS createdtime,jo_crmentity.smownerid AS smownerid 
				FROM jo_modcomments INNER JOIN jo_crmentity ON jo_modcomments.modcommentsid = jo_crmentity.crmid 
				AND jo_crmentity.deleted = 0 
				INNER JOIN jo_crmentity crmentity2 ON jo_modcomments.related_to = crmentity2.crmid 
				AND crmentity2.deleted = 0 AND crmentity2.setype = ? 
				INNER JOIN jo_modtracker_basic ON jo_modtracker_basic.crmid = jo_crmentity.crmid';

		$currentUser = Users_Record_Model::getCurrentUserModel();
		if($user === 'all') {
			if(!$currentUser->isAdminUser()) {
				$nonAdminAccessQuery = Users_Privileges_Model::getNonAdminAccessControlQuery('ModComments');
				$sql .= $nonAdminAccessQuery;
				$accessibleUsers = array_keys($currentUser->getAccessibleUsers());
				$sql .= ' AND userid IN('.  generateQuestionMarks($accessibleUsers).')';
				$params = array_merge($params, $accessibleUsers);
			}
		}else{
			$sql .= ' AND userid = ?';
			$params[] = $user;
		}
		//handling date filter for history widget in home page
		if(!empty($dateFilter)) {
			$sql .= ' AND jo_modtracker_basic.changedon BETWEEN ? AND ? ';
			$params[] = $dateFilter['start'];
			$params[] = $dateFilter['end'];
		}

		$sql .= ' ORDER BY jo_crmentity.createdtime DESC LIMIT ?, ?';
		$params[] = $pagingModel->getStartIndex();
		$params[] = $pagingModel->getPageLimit();
		$result = $db->pquery($sql,$params);

		$noOfRows = $db->num_rows($result);
		//setting up the count of records before checking permissions in history
		$pagingModel->set('historycount', $noOfRows);
		for($i=0; $i<$noOfRows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$commentModel = Head_Record_Model::getCleanInstance('ModComments');
			$commentModel->setData($row);
			$comments[] = $commentModel;
		}

		return $comments;
	}

	/**
	 * Function returns comments and recent activities across module
	 * @param <Head_Paging_Model> $pagingModel
	 * @param <String> $type - comments, updates or all
	 * @return <Array>
	 */
	public function getHistory($pagingModel, $type='', $userId='', $dateFilter='') {
		if(empty($userId)) $userId = 'all';
				if(empty($type)) $type = 'all';

		//TODO: need to handle security
		$comments = array();
		if($type == 'all' || $type == 'comments') {
			$modCommentsModel = Head_Module_Model::getInstance('ModComments');
			if($modCommentsModel->isPermitted('DetailView')){
				$comments = $this->getComments($pagingModel, $userId, $dateFilter);
			}
			if($type == 'comments') {
				return $comments;
			}
		}

		$db = PearDatabase::getInstance();
				$sql = 'SELECT jo_modtracker_basic.*
								FROM jo_modtracker_basic
								INNER JOIN jo_crmentity ON jo_modtracker_basic.crmid = jo_crmentity.crmid
								AND module = ?';

				$currentUser = Users_Record_Model::getCurrentUserModel();
				$params = array($this->getName());

				if($userId === 'all') {
					if(!$currentUser->isAdminUser()) {
						$accessibleUsers = array_keys($currentUser->getAccessibleUsers());
						$sql .= ' AND whodid IN ('.  generateQuestionMarks($accessibleUsers).')';
						$params = array_merge($params, $accessibleUsers);
					}
				}else{
					$sql .= ' AND whodid = ?';
					$params[] = $userId;
				}
				//handling date filter for history widget in home page
				if(!empty($dateFilter)) {
					$sql .= ' AND jo_modtracker_basic.changedon BETWEEN ? AND ? ';
					$params[] = $dateFilter['start'];
					$params[] = $dateFilter['end'];
				}

				$sql .= ' ORDER BY jo_modtracker_basic.id DESC LIMIT ?, ?';
				$params[] = $pagingModel->getStartIndex();
				$params[] = $pagingModel->getPageLimit();
		$result = $db->pquery($sql,$params);

		$activites = array();
		$noOfRows = $db->num_rows($result);
		//set the records count before checking permissions and unsetting it
		//If updates count more than comments count, this count should consider
		if($pagingModel->get('historycount') < $noOfRows) {
			$pagingModel->set('historycount', $noOfRows);
		}
		for($i=0; $i<$noOfRows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			if(Users_Privileges_Model::isPermitted($row['module'], 'DetailView', $row['crmid'])){
				$modTrackerRecorModel = new ModTracker_Record_Model();
				$modTrackerRecorModel->setData($row)->setParent($row['crmid'], $row['module']);
				$time = $modTrackerRecorModel->get('changedon');
				$activites[] = $modTrackerRecorModel;
			}
		}

		$history = array_merge($activites, $comments);

		$dateTime = array();
		foreach($history as $model) {
			if(get_class($model) == 'ModComments_Record_Model') {
				$time = $model->get('createdtime');
			} else {
				$time = $model->get('changedon');
			}
			$dateTime[] = $time;
		}

		if(!empty($history)) {
			array_multisort($dateTime,SORT_DESC,SORT_STRING,$history);
			return $history;
		}
		return false;
	}

	/**
	 * Function returns the Calendar Events for the module
	 * @param <String> $mode - upcoming/overdue mode
	 * @param <Head_Paging_Model> $pagingModel - $pagingModel
	 * @param <String> $user - all/userid
	 * @param <String> $recordId - record id
	 * @return <Array>
	 */
	function getCalendarActivities($mode, $pagingModel, $user, $recordId = false) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();

		if (!$user) {
			$user = $currentUser->getId();
		}

		$nowInUserFormat = Head_Datetime_UIType::getDisplayDateTimeValue(date('Y-m-d H:i:s'));
		$nowInDBFormat = Head_Datetime_UIType::getDBDateTimeValue($nowInUserFormat);
		list($currentDate, $currentTime) = explode(' ', $nowInDBFormat);

		$query = "SELECT jo_crmentity.crmid, crmentity2.crmid AS parent_id, jo_crmentity.smownerid, jo_crmentity.setype, jo_activity.* FROM jo_activity
					INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
					INNER JOIN jo_seactivityrel ON jo_seactivityrel.activityid = jo_activity.activityid
					INNER JOIN jo_crmentity AS crmentity2 ON jo_seactivityrel.crmid = crmentity2.crmid AND crmentity2.deleted = 0 AND crmentity2.setype = ?
					LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";

		$query .= Users_Privileges_Model::getNonAdminAccessControlQuery('Calendar');

		$query .= " WHERE jo_crmentity.deleted=0
					AND (jo_activity.activitytype NOT IN ('Emails'))
					AND (jo_activity.status is NULL OR jo_activity.status NOT IN ('Completed', 'Deferred', 'Cancelled'))
					AND (jo_activity.eventstatus is NULL OR jo_activity.eventstatus NOT IN ('Held','Cancelled'))";

		$params = array($this->getName());

		if ($recordId) {
			$query .= " AND jo_seactivityrel.crmid = ?";
			array_push($params, $recordId);
		} elseif ($mode === 'upcoming') {
			$query .= " AND CASE WHEN jo_activity.activitytype='Task' THEN due_date >= '$currentDate' ELSE CONCAT(due_date,' ',time_end) >= '$nowInDBFormat' END";
		} elseif ($mode === 'overdue') {
			$query .= " AND CASE WHEN jo_activity.activitytype='Task' THEN due_date < '$currentDate' ELSE CONCAT(due_date,' ',time_end) < '$nowInDBFormat' END";
		}

		if($user != 'all' && $user != '') {
			$query .= " AND jo_crmentity.smownerid = ?";
			array_push($params, $user);
		}

		$query .= " ORDER BY date_start, time_start LIMIT ". $pagingModel->getStartIndex() .", ". ($pagingModel->getPageLimit()+1);


		$result = $db->pquery($query, $params);
		$numOfRows = $db->num_rows($result);

		$groupsIds = Head_Util_Helper::getGroupsIdsForUsers($currentUser->getId());
		$activities = array();
		$recordsToUnset = array();
		for($i=0; $i<$numOfRows; $i++) {
			$newRow = $db->query_result_rowdata($result, $i);
			$model = Head_Record_Model::getCleanInstance('Calendar');
			$ownerId = $newRow['smownerid'];
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$visibleFields = array('activitytype','date_start','time_start','due_date','time_end','assigned_user_id','visibility','smownerid','crmid');
			$visibility = true;
			if(in_array($ownerId, $groupsIds)) {
				$visibility = false;
			} else if($ownerId == $currentUser->getId()){
				$visibility = false;
			}
			if(!$currentUser->isAdminUser() && $newRow['activitytype'] != 'Task' && $newRow['visibility'] == 'Private' && $ownerId && $visibility) {
				foreach($newRow as $data => $value) {
					if(in_array($data, $visibleFields) != -1) {
						unset($newRow[$data]);
					}
				}
				$newRow['subject'] = vtranslate('Busy','Events').'*';
			}
			if($newRow['activitytype'] == 'Task') {
				unset($newRow['visibility']);

				$due_date = $newRow["due_date"];
				$dayEndTime = "23:59:59";
				$EndDateTime = Head_Datetime_UIType::getDBDateTimeValue($due_date." ".$dayEndTime);
				$dueDateTimeInDbFormat = explode(' ',$EndDateTime);
				$dueTimeInDbFormat = $dueDateTimeInDbFormat[1];
				$newRow['time_end'] = $dueTimeInDbFormat;
			}

			$model->setData($newRow);
			$model->setId($newRow['crmid']);
			$activities[$newRow['crmid']] = $model;
			if(!$currentUser->isAdminUser() && $newRow['activitytype'] == 'Task' && isToDoPermittedBySharing($newRow['crmid']) == 'no') { 
				$recordsToUnset[] = $newRow['crmid'];
			}
		}

		$pagingModel->calculatePageRange($activities);
		if($numOfRows > $pagingModel->getPageLimit()){
			array_pop($activities);
			$pagingModel->set('nextPageExists', true);
		} else {
			$pagingModel->set('nextPageExists', false);
		}
		//after setting paging model, unsetting the records which has no permissions
		foreach($recordsToUnset as $record) {
			unset($activities[$record]);
		}

		return $activities;
	}

	/**
	 * Function to get list of fields which are required while importing records
	 * @param <String> $module
	 * @return <Array> list of fields
	 */
	function getRequiredFields($module = '') {
		$moduleInstance = CRMEntity::getInstance($this->getName());
		$requiredFields = $moduleInstance->required_fields;
		if (empty ($requiredFields)) {
			if (empty ($module)) {
				$module = $this->getName();
			}
			$moduleInstance->initRequiredFields($module);
		}
		return $moduleInstance->required_fields;
	}

	/**
	 * Function to get the module is permitted to specific action
	 * @param <String> $actionName
	 * @return <boolean>
	 */
	public function isPermitted($actionName) {
		return ($this->isActive() && Users_Privileges_Model::isPermitted($this->getName(), $actionName));
	}

	/**
	 * Function to get Specific Relation Query for this Module
	 * @param <type> $relatedModule
	 * @return <type>
	 */
	public function getSpecificRelationQuery($relatedModule) {
		if($relatedModule == 'Documents'){
			return ' AND jo_notes.filestatus = 1 ';
		}
		return;
	}

	/**
	 * Function to get where condition query for dashboards
	 * @param <Integer> $owner
	 * @return <String> query
	 */
	public function getOwnerWhereConditionForDashBoards ($owner) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$sharingAccessModel = Settings_SharingAccess_Module_Model::getInstance($this->getName());
		$params = array();
		if(!empty($owner) && $currentUserModel->isAdminUser()) {//If admin user, then allow users data
			$ownerSql =  ' smownerid = '. $owner;
			$params[] = $owner;
		} else if(!empty($owner)){//If not admin user, then check sharing access for that module
			if($sharingAccessModel->isPrivate()) {
				$subordinateUserModels = $currentUserModel->getSubordinateUsers();
				$subordinateUsers = array();
				foreach($subordinateUserModels as $id=>$name) {
					$subordinateUsers[] = $id;
				}
				if(in_array($owner, $subordinateUsers)) {
					$ownerSql = ' smownerid = '. $owner ;
				} else {
					$ownerSql = ' smownerid = '. $currentUserModel->getId();
				}
			} else {
				$ownerSql = ' smownerid = '. $owner ;
			}
		} else {//If no owner filter, then check if the module access is Private
			if($sharingAccessModel->isPrivate() && (!$currentUserModel->isAdminUser())) {
				$subordinateUserModels = $currentUserModel->getSubordinateUsers();
				foreach($subordinateUserModels as $id=>$name) {
					$subordinateUsers[] = $id;
					$params[] = $id;
				}
				if($subordinateUsers) {
					$ownerSql =  ' smownerid IN ('. implode(',' , $subordinateUsers) .')';
				} else {
					$ownerSql =  ' smownerid = '.$currentUserModel->getId();
				}
			}
		}
		return $ownerSql;
	}

	/**
	 * Function to get Module Header Links (for Head7)
	 * @return array
	 */
	public function getModuleBasicLinks(){
		if(!$this->isEntityModule() && $this->getName() !== 'Users') {
			return array();
		}
		$createPermission = Users_Privileges_Model::isPermitted($this->getName(), 'CreateView');
		$moduleName = $this->getName();
		$basicLinks = array();
		if($createPermission) {
			if($moduleName === "Calendar"){
				$basicLinks[] = array(
					'linktype' => 'BASIC',
					'linklabel' => 'LBL_ADD_TASK',
					'linkurl' => $this->getCreateTaskRecordUrl(),
					'linkicon' => 'fa-plus'
				);
				$basicLinks[] = array(
					'linktype' => 'BASIC',
					'linklabel' => 'LBL_ADD_EVENT',
					'linkurl' => $this->getCreateEventRecordUrl(),
					'linkicon' => 'fa-plus'
				);
			} else {
				$basicLinks[] = array(
					'linktype' => 'BASIC',
					'linklabel' => 'LBL_ADD_RECORD',
					'linkurl' => $this->getCreateRecordUrl(),
					'linkicon' => 'fa-plus'
				);
			}
			$importPermission = Users_Privileges_Model::isPermitted($this->getName(), 'Import');
			if($importPermission && $createPermission) {
				$basicLinks[] = array(
					'linktype' => 'BASIC',
					'linklabel' => 'LBL_IMPORT',
					'linkurl' => $this->getImportUrl(),
					'linkicon' => 'fa-download'
				);
			}
		}
		return $basicLinks;
	}

	/**
	 * Function to get Settings links
	 * @return <Array>
	 */
	public function getSettingLinks(){
        global $site_URL;
		if(!$this->isEntityModule() && $this->getName() !== 'Users') {
			return array();
		}

		$layoutEditorImagePath = Head_Theme::getImagePath('LayoutEditor.gif');
		$editWorkflowsImagePath = Head_Theme::getImagePath('EditWorkflows.png');
		$settingsLinks = array();

		$currentUser = Users_Record_Model::getCurrentUserModel();

		if($currentUser->isAdminUser()) {
			$settingsLinks[] = array(
						'linktype' => 'LISTVIEWSETTING',
						'linklabel' => 'LBL_EDIT_FIELDS',
						'linkurl' => $site_URL.'Settings/LayoutEditor/'.$this->getName(),
						'linkicon' => $layoutEditorImagePath
			);

			if($this->isWorkflowSupported()) {
				$settingsLinks[] = array(
						'linktype' => 'LISTVIEWSETTING',
						'linklabel' => 'LBL_EDIT_WORKFLOWS',
						'linkurl' => $site_URL.'Settings/Workflows/List/'.$this->getName(),
						'linkicon' => $editWorkflowsImagePath
				);
			}

			$settingsLinks[] = array(
						'linktype' => 'LISTVIEWSETTING',
						'linklabel' => 'LBL_EDIT_PICKLIST_VALUES',
						'linkurl' => $site_URL.'Settings/Picklist/Index/'.$this->getName(),
						'linkicon' => ''
			);

			if($this->hasSequenceNumberField()) {
				$settingsLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_MODULE_SEQUENCE_NUMBERING',
					'linkurl' => $site_URL.'Settings/Head/CustomRecordNumbering/'.$this->getName(),
					'linkicon' => ''
				);
			}

			$webForms = Head_Module_Model::getInstance('Webforms');
			if ($webForms && $webForms->isActive()) {
				$webformSupportedModule = Settings_Webforms_Module_Model :: getSupportedModulesList();
				if(array_key_exists($this->getName(), $webformSupportedModule)){
					$settingsLinks[] =	array(
							'linktype' => 'LISTVIEWSETTING',
							'linklabel' => 'LBL_SETUP_WEBFORMS',
							'linkurl' => $site_URL.'Webforms/Settings/Edit/'.$this->getName(),
							'linkicon' => '');
				}
			}
		}

		return $settingsLinks;
	}

	public function isCustomizable() {
		return $this->customized == '1' ? true : false;
	}

	public function isModuleUpgradable() {
		return $this->isCustomizable() ? true : false;
	}

	public function isExportable() {
		return $this->isCustomizable() ? true : false;
	}

	/**
	 * Function to get list of field for summary view
	 * @return <Array> list of field models <Head_Field_Model>
	 */
	public function getSummaryViewFieldsList() {
		if (!$this->summaryFields) {
			$summaryFields = array();
			$fields = $this->getFields();
			foreach ($fields as $fieldName => $fieldModel) {
				if ($fieldModel->isSummaryField() && $fieldModel->isViewable()) {
					$summaryFields[$fieldName] = $fieldModel;
				}
			}
			$this->summaryFields = $summaryFields;
		}
		return $this->summaryFields;
	}

	/**
	 * Function to get list of field for header view
	 * @return <Array> list of field models <Head_Field_Model>
	 */
	public function getHeaderViewFieldsList() {
		if (!$this->headerFields) {
			$headerFields = array();
			$fields = $this->getFields();
			foreach ($fields as $fieldName => $fieldModel) {
				if ($fieldModel->isHeaderField() && $fieldModel->isViewable()) {
					$headerFields[$fieldName] = $fieldModel;
				}
			}
			$this->headerFields = $headerFields;
		}
		return $this->headerFields;
	}

	/**
	 * Function to get list of field for header view
	 * @return <Array> list of field models <Head_Field_Model>
	 */
	public function getHeaderAndSummaryViewFieldsList() {
		if(!$this->relationListViewFields) {
			$summaryViewFields = $this->getSummaryViewFieldsList();
			$headerViewFields = $this->getHeaderViewFieldsList();
			$allRelationListViewFields = array_merge($headerViewFields,$summaryViewFields);
			$relationListViewFields = array();
			$nameFields = $this->getNameFields();
			foreach($nameFields as $nameField) {
				if(array_key_exists($nameField, $summaryViewFields)) {
					$relationListViewFields[$nameField] = $summaryViewFields[$nameField];
				}
			}
			foreach($allRelationListViewFields as $fieldName => $fieldModel) {
				if(!in_array($fieldName, $nameFields)) {
					$relationListViewFields[$fieldName] = $fieldModel;
				}
			}
			$this->relationListViewFields = $relationListViewFields;
		}
		return $this->relationListViewFields;
	}


	/**
	 * Function returns query for module record's search
	 * @param <String> $searchValue - part of record name (label column of crmentity table)
	 * @param <Integer> $parentId - parent record id
	 * @param <String> $parentModule - parent module name
	 * @return <String> - query
	 */
	public function getSearchRecordsQuery($searchValue,$searchFields, $parentId=false, $parentModule=false) {
		return "SELECT ".implode(',',$searchFields)." FROM jo_crmentity WHERE label LIKE '%$searchValue%' AND jo_crmentity.deleted = 0";
	}

	/**
	 * Function searches the records in the module, if parentId & parentModule
	 * is given then searches only those records related to them.
	 * @param <String> $searchValue - Search value
	 * @param <Integer> $parentId - parent recordId
	 * @param <String> $parentModule - parent module name
	 * @return <Array of Head_Record_Model>
	 */
	public function searchRecord($searchValue, $parentId=false, $parentModule=false, $relatedModule=false) {
			$searchFields = array('crmid','label','setype');
		if(!empty($searchValue) && empty($parentId) && empty($parentModule)) {
			$matchingRecords = Head_Record_Model::getSearchResult($searchValue, $this->getName());
		} else if($parentId && $parentModule) {
			$db = PearDatabase::getInstance();
			$result = $db->pquery($this->getSearchRecordsQuery($searchValue,$searchFields, $parentId, $parentModule), array());
			$noOfRows = $db->num_rows($result);

			$moduleModels = array();
			$matchingRecords = array();
			for($i=0; $i<$noOfRows; ++$i) {
				$row = $db->query_result_rowdata($result, $i);
				if(Users_Privileges_Model::isPermitted($row['setype'], 'DetailView', $row['crmid'])){
					$row['id'] = $row['crmid'];
					$moduleName = $row['setype'];
					if(!array_key_exists($moduleName, $moduleModels)) {
						$moduleModels[$moduleName] = Head_Module_Model::getInstance($moduleName);
					}
					$moduleModel = $moduleModels[$moduleName];
					$modelClassName = Head_Loader::getComponentClassName('Model', 'Record', $moduleName);
					$recordInstance = new $modelClassName();
					$matchingRecords[$moduleName][$row['id']] = $recordInstance->setData($row)->setModuleFromInstance($moduleModel);
				}
			}
		}

		return $matchingRecords;
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Head_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule, $relationId) {
		$relatedModuleName = $relatedModule->getName();

		$focus = CRMEntity::getInstance($this->getName());
		$focus->id = $recordId;

		$result = $focus->$functionName($recordId, $this->getId(), $relatedModule->getId(), $relationId);
		$query = $result['query'] .' '. $this->getSpecificRelationQuery($relatedModuleName);
		$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);

		//modify query if any module has summary fields, those fields we are displayed in related list of that module
		$relatedListFields = $relatedModule->getConfigureRelatedListFields();

		if($relatedModuleName == 'Documents') {
			$relatedListFields['filelocationtype'] = 'filelocationtype';
			$relatedListFields['filestatus'] = 'filestatus';
		}

		if(count($relatedListFields) > 0) {
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$queryGenerator = new QueryGenerator($relatedModuleName, $currentUser);
			$queryGenerator->setFields($relatedListFields);
			$selectColumnSql = $queryGenerator->getSelectClauseColumnSQL();
			$newQuery = spliti('FROM', $query);
			$selectColumnSql = 'SELECT DISTINCT jo_crmentity.crmid,'.$selectColumnSql;
			$query = $selectColumnSql.' FROM '.$newQuery[1];
		}

		if ($nonAdminQuery) {
			$query = appendFromClauseToQuery($query, $nonAdminQuery);
		}

		return $query;
	}

	/**
	 * Function to get Non admin access control query
	 * @param <String> $relatedModuleName
	 * @return <String>
	 */
	public function getNonAdminAccessControlQueryForRelation($relatedModuleName) {
		$modulesList = array('Faq', 'PriceBook', 'Vendors', 'Users');

		if (!in_array($relatedModuleName, $modulesList)) {
			return Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleName);
		}
	}

	/**
	 * Function returns the default column for Alphabetic search
	 * @return <String> columnname
	 */
	public function getAlphabetSearchField(){
		$focus = CRMEntity::getInstance($this->get('name'));
		return $focus->def_basicsearch_col;
	}

	/**
	 * Function which will give complusory mandatory fields
	 * @return type
	 */
	public function getCompulsoryMandatoryFieldList() {
		$focus = CRMEntity::getInstance($this->getName());
		$compulsoryMandtoryFields = $focus->mandatory_fields;
		if(empty($compulsoryMandtoryFields)) {
			$compulsoryMandtoryFields = array();
		}
		return $compulsoryMandtoryFields;
	}


	/**
	 * Function returns all the related modules for workflows create entity task
	 * @return <JSON>
	 */
	public function vtJsonDependentModules() {
		vimport('~~/modules/com_jo_workflow/WorkflowComponents.php');
		$db = PearDatabase::getInstance();
		$param = array('modulename'=>$this->getName());
		return vtJsonDependentModules($db, $param);
	}

	/**
	 * Function returns mandatory field Models
	 * @return <Array of Head_Field_Model>
	 */
	public function getMandatoryFieldModels(){
		$fields = $this->getFields();
		$mandatoryFields = array();
		if ($fields) {
			foreach ($fields as $field) {
				if ($field->isMandatory()) {
					$mandatoryFields[] = $field;
				}
			}
		}
		return $mandatoryFields;
	}

	public function getRelatedModuleRecordIds(Head_Request $request, $recordIds = array(), $nonAdminCheck = false) {
		$db = PearDatabase::getInstance();
		$relationIds = $request->get('related_modules');
		if(empty($relationIds))  return array();

		$focus = CRMEntity::getInstance($this->getName());
		$relatedModuleMapping = $focus->related_module_table_index;

		$relationFieldMapping = array();
		$queryParams = array($this->getId());
		foreach($relationIds as $reltionId) {
			array_push($queryParams,$reltionId);
		}
		$query = "SELECT relationfieldid,related_tabid
					FROM jo_relatedlists
					WHERE jo_relatedlists.tabid=? AND relation_id IN (".generateQuestionMarks($relationIds).")";


		$relationRes = $db->pquery($query,$queryParams);

		$num_rows = $db->num_rows($relationRes);
		for($i=0 ;$i<$num_rows; $i++) {
			$relatedTabId = $db->query_result($relationRes,$i,'related_tabid');
			$relationfieldid = $db->query_result($relationRes,$i,'relationfieldid');
			$relatedModuleModel = Head_Module_Model::getInstance($relatedTabId);
			$relationFieldMapping[] = array('relatedModuleName'=>$relatedModuleModel->getName(),'relationfieldid'=>$relationfieldid);
		}

		$relatedIds = array();
		if(!empty($relationFieldMapping)) {
			foreach ($relationFieldMapping as $mappingDetails){
			//for ($i=0; $i<count($relatedModules); $i++) {
				$params = array();
				$module = $mappingDetails['relatedModuleName'];
				$relationFieldId = $mappingDetails['relationfieldid'];
				$sql = "SELECT jo_crmentity.crmid FROM jo_crmentity";

				if($nonAdminCheck) {
					if(empty($relatedModuleFocus)) $relatedModuleFocus = CRMEntity::getInstance($module);
					$user = Users_Record_Model::getCurrentUserModel();
					$relationAccessQuery = $relatedModuleFocus->getNonAdminAccessControlQuery($module, $user);
					$sql .= ' '.$relationAccessQuery;
				}

				if(empty($relationFieldId)){
					$tablename = $relatedModuleMapping[$module]['table_name'];
					$tabIndex = $relatedModuleMapping[$module]['table_index'];
					$relIndex = $relatedModuleMapping[$module]['rel_index'];

					//Fallback to jo_crmentityrel if both focus and relationfieldid is empty
					if(empty($tablename)) {
						$tablename = 'jo_crmentityrel';
						$tabIndex = 'crmid';
						$relIndex = 'crmid';
					}
					//END

					if($tablename == 'jo_crmentityrel'){
						$sql .= " INNER JOIN $tablename ON ($tablename.relcrmid = jo_crmentity.crmid OR $tablename.crmid = jo_crmentity.crmid)
							WHERE ($tablename.crmid IN (".  generateQuestionMarks($recordIds).")) OR ($tablename.relcrmid IN (".  generateQuestionMarks($recordIds)."))";
						foreach ($recordIds as $key => $recordId) {
							array_push($params, $recordId);
						}
					} else {
						$sql .= " INNER JOIN $tablename ON $tablename.$tabIndex = jo_crmentity.crmid
							WHERE $tablename.$relIndex IN (".  generateQuestionMarks($recordIds).")";
					}
				}else{
					$fieldModel = Head_Field_Model::getInstance($relationFieldId);
					$relatedModuleFocus = CRMEntity::getInstance($module);
					$tablename = $fieldModel->get('table');
					$relIndex = $fieldModel->get('column');
					if($tablename == $relatedModuleFocus->table_name){
						$tabIndex = $relatedModuleFocus->table_index;
						$sql .= " INNER JOIN $tablename ON $tablename.$tabIndex = jo_crmentity.crmid
							WHERE $tablename.$relIndex IN (".  generateQuestionMarks($recordIds).")";
					}else{
						$modulePrimaryTableName = $relatedModuleFocus->table_name;
						$modulePrimaryTableIndex = $relatedModuleFocus->table_index;
						$tabIndex = $relatedModuleFocus->tab_name_index[$tablename];
						$sql .= " INNER JOIN $modulePrimaryTableName ON $modulePrimaryTableName.$modulePrimaryTableIndex = jo_crmentity.crmid
							INNER JOIN $tablename ON $tablename.$tabIndex = $modulePrimaryTableName.$modulePrimaryTableIndex 
							WHERE $tablename.$relIndex IN (".  generateQuestionMarks($recordIds).")";
					}
				}

				$sql .=' AND jo_crmentity.deleted = 0';
				foreach ($recordIds as $key => $recordId) {
					array_push($params, $recordId);
				}

				$result1 = $db->pquery($sql, $params);
				$num_rows = $db->num_rows($result1);
				for($j=0; $j<$num_rows; $j++){
					$relatedIds[] = $db->query_result($result1, $j, 'crmid');
				}
			}
			return $relatedIds;
		} else {
			return $relatedIds;
		}
	}



	public function transferRecordsOwnership($transferOwnerId, $relatedModuleRecordIds){
		foreach($relatedModuleRecordIds as $recordId) {
			$recordModel = Head_Record_Model::getInstanceById($recordId);
			$recordModel->set('assigned_user_id', $transferOwnerId);
			$recordModel->set('mode', 'edit');
			// Transferring ownership with related module as Inventory modules, removes line item details.
			// So setting $_REQUEST['ajxaction'] to DETAILVIEW
			$_REQUEST['ajxaction'] = 'DETAILVIEW';
			$recordModel->save();
		}
	}

	/**
	* Function to get orderby sql from orderby field
	*/
	public function getOrderBySql($orderBy){
			 $orderByField = $this->getFieldByColumn($orderBy);
			 return $orderByField->get('table') . '.' . $orderBy;
	}

	 public function getDefaultSearchField(){
		$nameFields = $this->getNameFields();
		//To make the first field as the name field
		return $nameFields[0];
	}

	/**
	 * Function to get popup view fields
	 */
	public function getPopupViewFieldsList(){
		$summaryFieldsList = $this->getHeaderAndSummaryViewFieldsList();

		if(count($summaryFieldsList) > 0){
			 $popupFields = array_keys($summaryFieldsList);
		}else{
			$popupFields = array_values($this->getRelatedListFields());
		}
		return $popupFields;
	}

	/**
	 * Funxtion to identify if the module supports quick search or not
	 */
	public function isQuickSearchEnabled() {
		return true;
	}

	/**
	 * function to check if the extension module is permitted for utility action
	 * @return <boolean> false
	 */
	public function isUtilityActionEnabled() {
		return false;
	}

	public function isListViewNameFieldNavigationEnabled() {
		return true;
	}

	/**
	 * function to check if duplicate option is allowed in DetailView
	 * @param <string> $action, $recordId
	 * @return <boolean>
	 */
	public function isDuplicateOptionAllowed($action, $recordId) {
		return Users_Privileges_Model::isPermitted($this->getName(), $action, $recordId);
	}

	/**
	 * function to check if the module is related to supplied module name
	 * @param <string> $moduleName
	 * @return boolean - true if module is related
	 */
	public function isModuleRelated($moduleName) {
		$relations = $this->getRelations();
		$relatedModules = array();
		foreach($relations as $relation){
			$relatedModules[] = $relation->getRelationModuleName();
		}
		if(in_array($moduleName, $relatedModules)) return true; return false;
	}

	/**
	 * Function to get the autofill reference module for a module
	 * @param <String> $moduleName
	 * @return <String> Auto Fill Module Name
	 */
	public function getAutoFillModule($moduleName) {
		$autoFillModules = array();
		switch ($moduleName) {
			case 'Contacts'	: $autoFillModules[] = 'Accounts';					break;
			case 'Project'	: $autoFillModules = array('Contacts','Accounts');	break;
		}
		return $autoFillModules;
	}

	/**
	 * Function to get the Auto Fill Module and Field in array 
	 * @param <String> $moduleName
	 * @return <Array> $autoFill - Contains Module Name and Field Name
	 */
	public function getAutoFillModuleAndField($moduleName) {
		$autoFill = array();
		$autoFillValues = $fieldNamesList = array();
		$autoFillModule = $this->getAutoFillModule($moduleName);
		if (!$autoFillModule) {
			$referenceFields = $this->getFieldsByType('reference');
			foreach ($referenceFields as $fieldName=>$fieldModel) {
				$referenceList = $fieldModel->getReferenceList();
				foreach ($referenceList as $referenceModuleName) {
					if($referenceModuleName == $autoFillModule && !$fieldModel->isCustomField() && !in_array($fieldName, $fieldNamesList)) {
						$autoFill['module'] = $autoFillModule;
						$autoFill['fieldname'] = $fieldName;
						$fieldNamesList[] = $fieldName;
						$autoFillValues[] = $autoFill;
						break;
					}
				}
			}
		}
		return $autoFillValues;
	}

	/**
	* Function is used to give links in the All menu bar
	*/
	public function getQuickMenuModels() {
		if($this->isEntityModule()) {
			$moduleName = $this->getName();
			$listViewModel = Head_ListView_Model::getCleanInstance($moduleName);
			$basicListViewLinks = $listViewModel->getBasicLinks();

			$createPermission = Users_Privileges_Model::isPermitted($moduleName, 'CreateView');
			$importPermission = Users_Privileges_Model::isPermitted($moduleName, 'Import');
			if($importPermission && $createPermission) {
				$basicListViewLinks[] = array(
					'linktype' => 'LISTVIEW',
					'linklabel' => 'LBL_IMPORT',
					'linkurl' => $this->getImportUrl(),
					'linkicon' => ''
				);
			}
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
		return array('Import', 'Export', 'Merge', 'DuplicatesHandling');
	}

	/*
	 * Function to get pair of supported utility actionid and action name.
	 * Used when creating a new profile.
	 */
	function getUtilityActions() {
		$utilityActions = array();
		$utilityActionsArray = $this->getUtilityActionsNames();

		foreach($utilityActionsArray as $utilityAction) {
			$utilityActionId = getActionid($utilityAction);
			$utilityActions[$utilityActionId] = $utilityAction;
		}

		return $utilityActions;
	}

	/**
	 * function to check if module allows profile level utility
	 * @return <boolean>
	 */
	public function isProfileLevelUtilityAllowed() {
		return true;
	}

	/**
	 * function to check if module is restricted from compose email popup
	 * @return <boolean>
	 */
	public function restrictToListInComposeEmailPopup() {
		//does not restrict the module to be listed in compose email popup
		return false;
	}

	public function getAdditionalImportFields() {
		return array();
	}

	/**
	 * Function which will be give you the actions that are allowed when this module is added as a tab 
	 */
	public function getRelationShipActions() {
		return array("ADD","SELECT");
	}


	public function isNew() {
		return false;
	}

	/** 
	 * Function to get the basic view url of extension 
	 * @param type $sourceModule 
	 * @return type 
	 */ 
	function getBaseExtensionUrl($sourceModule) { 
        global $site_URL;
		return $site_URL.$sourceModule.'/Extension/'.$this->getName().'/Index'; 
	} 

	function getExtensionSettingsUrl($sourceModule) { 
		return $this->getBaseExtensionUrl($sourceModule).'/settings'; 
	}

	function getExtensionLogsListViewUrl($sourceModule) { 
		return $this->getBaseExtensionUrl($sourceModule).'&mode=showLogs'; 
	} 

	/**
	 * Function get the launch url of extension module.
	 */
	function getExtensionLaunchUrl() {
		return 'index.php?module='.$this->getName().'&view=List';
	}

	function isFilterColumnEnabled() {
		return true;
	}

	function isStarredEnabled(){
		return false;
	}

	/**
	 * Function to get the app name for module
	 */
	function getAppName() {
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT appname,visible FROM jo_app2tab WHERE tabid = ?', array($this->getId()));
		$count = $db->num_rows($result);
		$apps = array();
		if ($count > 0) {
			for ($i = 0; $i < $count; $i++) {
				$appName = $db->query_result($result, $i, 'appname');
				$visibility = $db->query_result($result, $i, 'visible');
				$apps[$appName] = $visibility;
			}
		}

		return $apps;
	}

	public function getCustomPicklistDependency() {
		return array();
	}

	function isTagsEnabled() {
		return true;
	}

}
