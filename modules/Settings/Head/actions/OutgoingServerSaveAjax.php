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

class Settings_Head_OutgoingServerSaveAjax_Action extends Settings_Head_Basic_Action {
    
    public function process(Head_Request $request) {
        $outgoingServerSettingsModel = Settings_Head_Systems_Model::getInstanceFromServerType('email', 'OutgoingServer');
        $loadDefaultSettings = $request->get('default');
        if($loadDefaultSettings == "true") {
            $outgoingServerSettingsModel->loadDefaultValues();
        }else{
            $outgoingServerSettingsModel->setData($request->getAll());
        }
        $response = new Head_Response();
        try{
            $id = $outgoingServerSettingsModel->save($request);
            $data = $outgoingServerSettingsModel->getData();
            $response->setResult($data);
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}