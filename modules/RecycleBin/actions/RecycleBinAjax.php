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

class RecycleBin_RecycleBinAjax_Action extends Head_Mass_Action {
	
	function __construct() {
		parent::__construct();
		$this->exposeMethod('restoreRecords');
		$this->exposeMethod('emptyRecycleBin');
		$this->exposeMethod('deleteRecords');
	}
	
	function checkPermission(Head_Request $request) {
        if($request->get('mode') == 'emptyRecycleBin') {
            //we dont check for permissions since recylebin axis will not be there for non admin users
            return true;
        }
		$targetModuleName = $request->get('sourceModule', $request->get('module'));
		$moduleModel = Head_Module_Model::getInstance($targetModuleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Delete')) {
			throw new AppException(getTranslatedString('LBL_PERMISSION_DENIED'));
		}
	}

	function preProcess(Head_Request $request) {
		return true;
	}

	function postProcess(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$mode = $request->get('mode');
		
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
	
	/**
	 * Function to restore the deleted records.
	 * @param type $sourceModule
	 * @param type $recordIds
	 */
	public function restoreRecords(Head_Request $request){
		$sourceModule = $request->get('sourceModule');
		$recordIds = $this->getRecordsListFromRequest($request);
		$recycleBinModule = new RecycleBin_Module_Model();
 
		$response = new Head_Response();	
		if ($recordIds) {
			$recycleBinModule->restore($sourceModule, $recordIds);
			$response->setResult(array(true));
		} 
		
		$response->emit();

	}
	
	/**
	 * Function to delete the records permanently in vitger CRM database
	 */
	public function emptyRecycleBin(Head_Request $request){
		$recycleBinModule = new RecycleBin_Module_Model();
		
		$status = $recycleBinModule->emptyRecycleBin();
		
		if($status){
			$response = new Head_Response();
			$response->setResult(array($status));
			$response->emit();
		}
	}
	
	/**
	 * Function to deleted the records permanently in CRM
	 * @param type $reocrdIds
	 */
	public function deleteRecords(Head_Request $request){
		$recordIds = $this->getRecordsListFromRequest($request);
		$recycleBinModule = new RecycleBin_Module_Model();
 
		$response = new Head_Response();	
		if ($recordIds) {
			$recycleBinModule->deleteRecords($recordIds);
			$response->setResult(array(true));
			$response->emit();
		} 
	}
	
}
