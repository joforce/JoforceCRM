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

class Settings_Leads_MappingSave_Action extends Settings_Head_Index_Action {

	public function process(Head_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		$mapping = $request->get('mapping');

        //removing csrf token from mapping array because it'll cause query failure
        $csrfKey = '__vtrftk';
        if (array_key_exists($csrfKey, $mapping)) {
            unset($mapping[$csrfKey]);
        }
        
        $mappingModel = Settings_Leads_Mapping_Model::getCleanInstance();

		$response = new Head_Response();
		if ($mapping) {
			$mappingModel->save($mapping);
			$response->setResult(array(vtranslate('LBL_SAVED_SUCCESSFULLY', $qualifiedModuleName)));
		} else {
			$response->setError(vtranslate('LBL_INVALID_MAPPING', $qualifiedModuleName));
		}
		$response->emit();
	}
    
    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}