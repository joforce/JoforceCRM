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

class EmailTemplates_DeleteAjax_Action extends Head_Delete_Action {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPrivilegesModel->isPermitted($moduleName, 'Delete', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$recordModel = EmailTemplates_Record_Model::getInstanceById($recordId);
		$recordModel->setModule($moduleName);
		$recordModel->delete();

		$cvId = $request->get('viewname');
		$response = new Head_Response();
		$response->setResult(array('viewname'=>$cvId, 'module'=>$moduleName));
		if($recordModel->isSystemTemplate()) {
			 $response->setError('502', vtranslate('LBL_NO_PERMISSIONS_TO_DELETE_SYSTEM_TEMPLATE', $moduleName));
		}
		$response->emit();
	}
}
