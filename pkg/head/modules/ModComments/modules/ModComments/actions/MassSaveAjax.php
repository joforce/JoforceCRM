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

class ModComments_MassSaveAjax_Action extends Head_Mass_Action {

	function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Save')) {
			throw new AppException(vtranslate($moduleName, $moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	public function process(Head_Request $request) {
		$recordModels = $this->getRecordModelsFromRequest($request);
		foreach($recordModels as $recordId => $recordModel) {
			$recordModel->save();
		}
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Head_Request $request
	 * @return Head_Record_Model or Module specific Record Model instance
	 */
	private function getRecordModelsFromRequest(Head_Request $request) {

		$moduleName = $request->getModule();
		$recordIds = $this->getRecordsListFromRequest($request);
		$recordModels = array();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		foreach($recordIds as $recordId) {
			$recordModel = Head_Record_Model::getCleanInstance($moduleName);
			$recordModel->set('mode', '');
			$recordModel->set('commentcontent', $request->getRaw('commentcontent'));
			$recordModel->set('related_to', $recordId);
			$recordModel->set('assigned_user_id', $currentUserModel->getId());
			$recordModel->set('userid', $currentUserModel->getId());
			$recordModels[$recordId] = $recordModel;
		}
		return $recordModels;
	}
}
