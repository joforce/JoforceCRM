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

class Settings_Head_ConfigEditorSaveAjax_Action extends Settings_Head_Basic_Action {

	public function process(Head_Request $request) {
		$response = new Head_Response();
		$qualifiedModuleName = $request->getModule(false);
		$updatedFields = $request->get('updatedFields');
		$moduleModel = Settings_Head_ConfigModule_Model::getInstance();

		if ($updatedFields) {
			$moduleModel->set('updatedFields', $updatedFields);
			$status = $moduleModel->save();

			if ($status === true) {
				$response->setResult(array($status));
			} else {
				$response->setError(vtranslate($status, $qualifiedModuleName));
			}
		} else {
			$response->setError(vtranslate('LBL_FIELDS_INFO_IS_EMPTY', $qualifiedModuleName));
		}
		$response->emit();
	}
    
    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}