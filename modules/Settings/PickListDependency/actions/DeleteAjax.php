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

class Settings_PickListDependency_DeleteAjax_Action extends Settings_Head_Index_Action {
    
    public function process(Head_Request $request) {
        $sourceModule = $request->get('sourceModule');
        $sourceField = $request->get('sourcefield');
        $targetField = $request->get('targetfield');
        $recordModel = Settings_PickListDependency_Record_Model::getInstance($sourceModule, $sourceField, $targetField);
        
        $response = new Head_Response();
        try{
            $result = $recordModel->delete();
            $response->setResult(array('success', $result));
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}