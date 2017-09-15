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

class Head_Delete_Action extends Head_Action_Controller {

	function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPrivilegesModel->isPermitted($moduleName, 'Delete', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if ($record) {
			$recordEntityName = getSalesEntityType($record);
			if ($recordEntityName !== $moduleName) {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$ajaxDelete = $request->get('ajaxDelete');
		$recurringEditMode = $request->get('recurringEditMode');
		
		$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
		$recordModel->set('recurringEditMode', $recurringEditMode);
		$moduleModel = $recordModel->getModule();

		$recordModel->delete();
		$cv = new CustomView();
		$cvId = $cv->getViewId($moduleName);
		deleteRecordFromDetailViewNavigationRecords($recordId, $cvId, $moduleName);
		$listViewUrl = $moduleModel->getListViewUrl();
		if($ajaxDelete) {
			$response = new Head_Response();
			$response->setResult($listViewUrl);
			return $response;
		} else {
			header("Location: $listViewUrl");
		}
	}

	public function validateRequest(Head_Request $request) {
		$request->validateWriteAccess();
	}
}
