<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Class PDFMaker_Edit_View extends Head_Edit_View {
	
	/**
	 * Function to check module Edit Permission
	 * @param Head_Request $request
	 * @return boolean
	 */
	public function checkPermission(Head_Request $request) {
                $record = $request->get('record');

                if(!$record) {
                        throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
                }

	}
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
				"libraries.jquery.ckeditor.ckeditor",
				"libraries.jquery.ckeditor.adapters.jquery",
				'modules.Head.resources.CkEditor',
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
	
	/**
	 * Funtioin to process the Edit view
	 * @param Head_Request $request
	 */
	public function process(Head_Request $request) {
		global $adb;
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
                $moduleModel = Head_Module_Model::getInstance($moduleName);
		$record = $request->get('record');
		
		if(!empty($record)) {
			$recordModel = PDFMaker_Record_Model::getInstanceById($record);
            $viewer->assign('RECORD_ID', $record);
            $viewer->assign('MODE', 'edit');
        } else {
			$recordModel = new PDFMaker_Record_Model();
            $viewer->assign('MODE', '');
			$recordModel->set('templatename','');
			$recordModel->set('description','');
			$recordModel->set('subject','');
			$recordModel->set('body','');
        }
		$recordModel->setModule('PDFMaker');
        if(!$this->record){
            $this->record = $recordModel;
        }
		$getLogoName = $adb->pquery('select logoname from jo_organizationdetails where organization_id = ?', array(1));
		$logoName = $adb->query_result($getLogoName, 0, 'logoname');
		$allFiledsOptions = $this->record->getTemplateFields();
		$settings = $this->record->get('settings');
		$viewer->assign('settings', unserialize(base64_decode($settings)));
		$viewer->assign('RECORD', $this->record);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('ALL_FIELDS', $allFiledsOptions);
		$viewer->assign('LOGO', $logoName);
		$viewer->view('EditView.tpl', $moduleName);
	}
}
