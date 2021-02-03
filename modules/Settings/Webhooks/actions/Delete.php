<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Webhooks_Delete_Action extends Settings_Head_Index_Action {

	public function checkPermission(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);

		$recordModel = Settings_Webhooks_Record_Model::getInstanceById($recordId, $qualifiedModuleName);
		$moduleModel = $recordModel->getModule();

		$recordModel->delete();

		$returnUrl = $moduleModel->getListViewUrl();
		$response = new Head_Response();
		$response->setResult($returnUrl);
		return $response;
	}

	public function validateRequest(Head_Request $request) {
		$request->validateWriteAccess();
	}

}
