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

class Settings_Head_TermsAndConditionsSaveAjax_Action extends Settings_Head_Basic_Action {
    
    public function process(Head_Request $request) {
        $model = Settings_Head_TermsAndConditions_Model::getInstance();
        $model->setText($request->get('tandc'));
        $model->save();
        
        $response = new Head_Response();
        $response->emit();
    }
    
    public function validateRequest(Head_Request $request) { 
        $request->validateWriteAccess(); 
    } 
}