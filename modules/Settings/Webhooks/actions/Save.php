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

class Settings_Webhooks_Save_Action extends Settings_Head_Index_Action {

	public function checkPermission(Head_Request $request) {
		return true;
		parent::checkPermission($request);

		$moduleModel = Head_Module_Model::getInstance($request->getModule());
		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if(!$currentUserPrivilegesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);

		if ($recordId) {
			$recordModel = Settings_Webhooks_Record_Model::getInstanceById($recordId, $qualifiedModuleName);
			$recordModel->set('mode', 'edit');
		} else {
			$recordModel = Settings_Webhooks_Record_Model::getCleanInstance($qualifiedModuleName);
			$recordModel->set('mode', '');
		}

		$fieldsList = $recordModel->getModule()->getFields();

		foreach ($fieldsList as $fieldName => $fieldModel) {
			$fieldValue = $request->get($fieldName);
			if (!$fieldValue) {
				$fieldValue = $fieldModel->get('defaultvalue');
			}
			$recordModel->set($fieldName, $fieldValue);
		}

		$returnUrl = $recordModel->getModule()->getListViewUrl();
		$recordModel->set('selectedFieldsData', $request->get('selectedFieldsData'));

		if (!$recordModel->checkDuplicate()) {
			$recordModel->save();
			$returnUrl = $recordModel->getDetailViewUrl();
		}
		header("Location: $returnUrl");
	}

	public function validateRequest(Head_Request $request) {
		$request->validateWriteAccess();
	}
}
