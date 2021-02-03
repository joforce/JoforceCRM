<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Class Settings_Webhooks_Edit_View extends Settings_Head_Index_View {

	public function checkPermission(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
		$mode = '';
		$selectedFieldsList = $allFieldsList = array();
		$viewer = $this->getViewer($request);
		$supportedModules = Settings_Webhooks_Module_Model::getSupportedModulesList();

		if ($recordId) {
			$recordModel = Settings_Webhooks_Record_Model::getInstanceById($recordId, $qualifiedModuleName);
			$selectedFieldsList = $recordModel->get('fields');

			$sourceModule = $recordModel->get('targetmodule');
			$mode = 'edit';
		} else {
			$recordModel = Settings_Webhooks_Record_Model::getCleanInstance($qualifiedModuleName);
			$sourceModule = $request->get('sourceModule');
			if (!$sourceModule) {
				$sourceModule = reset(array_keys($supportedModules));
			}
			$recordModel->set('targetmodule',$sourceModule);
		}
		if(!$supportedModules[$sourceModule]){
			$message = vtranslate('LBL_ENABLE_TARGET_MODULES_FOR_WEBHOOK',$qualifiedModuleName);
			$viewer->assign('MESSAGE', $message);
			$viewer->view('OperationNotPermitted.tpl', 'Head');
			return false;
		}

		$allFieldsList = $recordModel->getAllFieldsList($sourceModule);
		$recordStructure = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);

		$viewer->assign('MODE', $mode);
		$viewer->assign('RECORD_ID', $recordId);
		$viewer->assign('RECORD_MODEL', $recordModel);
		$viewer->assign('MODULE', $qualifiedModuleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		$viewer->assign('SOURCE_MODULE', $sourceModule);
		$viewer->assign('ALL_FIELD_MODELS_LIST', $allFieldsList);
		$viewer->assign('SELECTED_FIELD_MODELS_LIST', $selectedFieldsList);
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructure);
		$viewer->assign('RECORD_STRUCTURE', $recordStructure->getStructure());
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		$viewer->view('EditView.tpl', $qualifiedModuleName);
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.$moduleName.resources.Field",
			"modules.Settings.$moduleName.resources.Edit"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function setModuleInfo($request, $moduleModel){
		$record = $request->get('record');
		if ($record) {
			parent::setModuleInfo($request, $moduleModel);
		}
	}
}
