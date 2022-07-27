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

class EmailTemplates_Delete_Action extends Head_Delete_Action {
	
	function checkPermission(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$ajaxDelete = $request->get('ajaxDelete');
		
		$recordModel = EmailTemplates_Record_Model::getInstanceById($recordId);
		$moduleModel = $recordModel->getModule();

		$recordModel->delete($recordId);

		$listViewUrl = $moduleModel->getListViewUrl();
		$response = new Head_Response();
		if($recordModel->isSystemTemplate()) {
			$response->setError('502', vtranslate('LBL_NO_PERMISSIONS_TO_DELETE_SYSTEM_TEMPLATE', $moduleName));
		} else if($ajaxDelete) {
			$response->setResult($listViewUrl);
		} else {
			header("Location: $listViewUrl");
		}
		return $response;
	}
}
