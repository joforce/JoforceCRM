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

class Documents_CheckFileIntegrity_Action extends Head_Action_Controller {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();

		if(!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'))) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
		}
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$documentRecordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
		$resultVal = $documentRecordModel->checkFileIntegrity();

		$result = array('success'=>$resultVal);
		if ($resultVal) {
			$result['message'] = vtranslate('LBL_FILE_AVAILABLE', $moduleName);
		} else {
			if ($documentRecordModel->get('filelocationtype') == 'I') {
				$documentRecordModel->updateFileStatus();
			}
			$result['message'] = vtranslate('LBL_FILE_NOT_AVAILABLE', $moduleName);
		}

		$response = new Head_Response();
		$response->setResult($result);
		$response->emit();
	}
}
