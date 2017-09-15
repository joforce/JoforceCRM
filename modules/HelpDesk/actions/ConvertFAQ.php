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

class HelpDesk_ConvertFAQ_Action extends Head_Action_Controller {

	public function checkPermission(Head_Request $request) {
		$recordPermission = Users_Privileges_Model::isPermitted('Faq', 'CreateView');

		if(!$recordPermission) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$result = array();
		if (!empty ($recordId)) {
			$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);

			$faqRecordModel = Faq_Record_Model::getInstanceFromHelpDesk($recordModel);

			$answer = $faqRecordModel->get('faq_answer');
			if ($answer) {
				$faqRecordModel->save();
				header("Location: ".$faqRecordModel->getDetailViewUrl());
			} else {
				header("Location: ".$faqRecordModel->getEditViewUrl()."&parentId=$recordId&parentModule=$moduleName");
			}
		}
	}

	public function validateRequest(Head_Request $request) {
		$request->validateWriteAccess();
	}
}
