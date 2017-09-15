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

class Calendar_DeleteAjax_Action extends Head_DeleteAjax_Action {
	
	function checkPermission(Head_Request $request) {
		$sourceModule = $request->get('sourceModule');
		if (!$sourceModule) {
			$sourceModule = $request->getModule();
		}
		$record = $request->get('record');

		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserPrivilegesModel->isPermitted($sourceModule, 'Delete', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if ($record) {
			$activityModulesList = array('Calendar', 'Events');
			$recordEntityName = getSalesEntityType($record);

			if (!in_array($recordEntityName, $activityModulesList) || !in_array($sourceModule, $activityModulesList)) {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
	}
	
	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$recurringEditMode = $request->get('recurringEditMode');
		
		$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
		$recordModel->set('recurringEditMode', $recurringEditMode);
		$deletedRecords = $recordModel->delete();

		$cvId = $request->get('viewname');
		deleteRecordFromDetailViewNavigationRecords($recordId, $cvId, $moduleName);
		$response = new Head_Response();
		$response->setResult(array('viewname' => $cvId, 'module' => $moduleName, 'deletedRecords' => $deletedRecords));
		$response->emit();
	}

}
