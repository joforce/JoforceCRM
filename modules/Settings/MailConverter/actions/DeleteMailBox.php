<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_MailConverter_DeleteMailBox_Action extends Settings_Head_Index_Action {

	public function checkPermission(Head_Request $request) {
		parent::checkPermission($request);
		$recordId = $request->get('record');

		if (!$recordId) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $request->getModule(false)));
		}
	}

	public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
        
		$recordModel = Settings_MailConverter_Record_Model::getInstanceById($recordId);
		$recordModel->delete();

		$response = new Head_Response();
        $result = array('message' => vtranslate('LBL_DELETED_SUCCESSFULLY', $qualifiedModuleName));
        $result['id'] = $recordId;
		$response->setResult($result);
		$response->emit();
	}
        
        public function validateRequest(Head_Request $request) { 
            $request->validateWriteAccess(); 
        }
}
