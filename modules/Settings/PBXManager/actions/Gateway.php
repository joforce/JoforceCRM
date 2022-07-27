<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

class Settings_PBXManager_Gateway_Action extends Settings_Head_IndexAjax_View{
    
    function __construct() {
        $this->exposeMethod('getSecretKey');
    }
    
    public function process(Head_Request $request) {
        $this->getSecretKey($request);
    }
    
    public function getSecretKey(Head_Request $request) {
        $serverModel = PBXManager_Server_Model::getInstance();
        $response = new Head_Response();
        $vtigersecretkey = $serverModel->get('vtigersecretkey');
        if($vtigersecretkey) {
            $connector = $serverModel->getConnector();
            $vtigersecretkey = $connector->getHeadSecretKey();
            $response->setResult($vtigersecretkey);
        }else {
            $vtigersecretkey = PBXManager_Server_Model::generateHeadSecretKey();
            $response->setResult($vtigersecretkey);
        }
        $response->emit();
    }
}
