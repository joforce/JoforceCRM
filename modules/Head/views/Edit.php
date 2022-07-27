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

Class Head_Edit_View extends Head_Index_View {
	protected $record = false;
	function __construct() {
		parent::__construct();
	}

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$actionName = 'CreateView';
		if ($record && !$request->get('isDuplicate')) {
			$actionName = 'EditView';
		}

		if(!Users_Privileges_Model::isPermitted($moduleName, $actionName, $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if ($record) {
			$recordEntityName = getSalesEntityType($record);
			if ($recordEntityName !== $moduleName) {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
	}

	public function setModuleInfo($request, $moduleModel) {
		$fieldsInfo = array();
		$basicLinks = array();
		$settingLinks = array();

		$moduleFields = $moduleModel->getFields();
		foreach($moduleFields as $fieldName => $fieldModel){
			$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
		}
		$viewer = $this->getViewer($request);
		$viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));
		$viewer->assign('MODULE_BASIC_ACTIONS', $basicLinks);
		$viewer->assign('MODULE_SETTING_ACTIONS', $settingLinks);
	}

	function preProcess(Head_Request $request, $display=true) {
		global $current_user;
		$viewer = $this->getViewer ($request); 
		
	        $moduleName = $request->getModule();
		$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($moduleName)); 
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$record = $request->get('record'); 
		if(!empty($record) && $moduleModel->isEntityModule()) { 
			$recordModel = $this->record?$this->record:Head_Record_Model::getInstanceById($record, $moduleName); 
			$viewer->assign('RECORD',$recordModel); 
		}  

		parent::preProcess($request, $display); 
   } 

	public function process(Head_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$record = $request->get('record');
		$Edit_views=$request->get('view');
		if(!empty($record) && $request->get('isDuplicate') == true) {
			$recordModel = $this->record?$this->record:Head_Record_Model::getInstanceById($record, $moduleName);
			$viewer->assign('MODE', '');

			//While Duplicating record, If the related record is deleted then we are removing related record info in record model
			$mandatoryFieldModels = $recordModel->getModule()->getMandatoryFieldModels();
			foreach ($mandatoryFieldModels as $fieldModel) {
				if ($fieldModel->isReferenceField()) {
					$fieldName = $fieldModel->get('name');
					if (Head_Util_Helper::checkRecordExistance($recordModel->get($fieldName))) {
						$recordModel->set($fieldName, '');
					}
				}
			}  
		} else if(!empty($record)) {
			$recordModel = $this->record ? $this->record : Head_Record_Model::getInstanceById($record, $moduleName);
			$viewer->assign('RECORD_ID', $record);
			$viewer->assign('MODE', 'edit');
		} else {
			$recordModel = Head_Record_Model::getCleanInstance($moduleName);
			$viewer->assign('MODE', '');
		}

		if(!$this->record){
			$this->record = $recordModel;
		}

		$moduleModel = $recordModel->getModule();
		$fieldList = $moduleModel->getFields();
		$requestFieldList = array_intersect_key($request->getAllPurified(), $fieldList);

		$relContactId = $request->get('contact_id');
		if ($relContactId && $moduleName == 'Calendar') {
			$contactRecordModel = Head_Record_Model::getInstanceById($relContactId);
			$requestFieldList['parent_id'] = $contactRecordModel->get('account_id');
		}
		foreach($requestFieldList as $fieldName=>$fieldValue){
			$fieldModel = $fieldList[$fieldName];
			$specialField = false;
			// We collate date and time part together in the EditView UI handling 
			// so a bit of special treatment is required if we come from QuickCreate 
			if ($moduleName == 'Calendar' && empty($record) && $fieldName == 'time_start' && !empty($fieldValue)) { 
				$specialField = true; 
				// Convert the incoming user-picked time to GMT time 
				// which will get re-translated based on user-time zone on EditForm 
				$fieldValue = DateTimeField::convertToDBTimeZone($fieldValue)->format("H:i"); 
			}

			if ($moduleName == 'Calendar' && empty($record) && $fieldName == 'date_start' && !empty($fieldValue)) { 
				$startTime = Head_Time_UIType::getTimeValueWithSeconds($requestFieldList['time_start']);
				$startDateTime = Head_Datetime_UIType::getDBDateTimeValue($fieldValue." ".$startTime);
				list($startDate, $startTime) = explode(' ', $startDateTime);
				$fieldValue = Head_Date_UIType::getDisplayDateValue($startDate);
			}
			if($fieldModel->isEditable() || $specialField) {
				$recordModel->set($fieldName, $fieldModel->getDBInsertValue($fieldValue));
			}
		}
		$recordStructureInstance = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$picklistDependencyDatasource = Head_DependencyPicklist::getPicklistDependencyDatasource($moduleName);

		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Head_Functions::jsonEncode($picklistDependencyDatasource));
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('EDIT_VIEWS',$Edit_views);

		$isRelationOperation = $request->get('relationOperation');

		//if it is relation edit
		$viewer->assign('IS_RELATION_OPERATION', $isRelationOperation);
		if($isRelationOperation) {
			$viewer->assign('SOURCE_MODULE', $request->get('sourceModule'));
			$viewer->assign('SOURCE_RECORD', $request->get('sourceRecord'));
		}

		// added to set the return values
		if($request->get('returnview')) {
			$request->setViewerReturnValues($viewer);
		}
		$viewer->assign('MAX_UPLOAD_LIMIT_MB', Head_Util_Helper::getMaxUploadSize());
		$viewer->assign('MAX_UPLOAD_LIMIT_BYTES', Head_Util_Helper::getMaxUploadSizeInBytes());
		if($request->get('displayMode')=='overlay'){
			$viewer->assign('SCRIPTS',$this->getOverlayHeaderScripts($request));
			$viewer->view('OverlayEditView.tpl', $moduleName);
		}
		else{
			$viewer->view('EditView.tpl', $moduleName);
		}
	}

	public function getOverlayHeaderScripts(Head_Request $request) {
		$moduleName = $request->getModule();
		$jsFileNames = array(
			"modules.$moduleName.resources.Edit",
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return $jsScriptInstances;
	}
}
