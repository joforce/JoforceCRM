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

class PBXManager_OutgoingCall_Action extends Head_Action_Controller{
    
    public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());

		if(!$permission) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}
    
    public function process(Head_Request $request) {
        $serverModel = PBXManager_Server_Model::getInstance();
        $gateway = $serverModel->get("gateway");
        $response = new Head_Response();
        $user = Users_Record_Model::getCurrentUserModel();
	$userNumber = $user->phone_crm_extension;
        
        if($gateway && $userNumber){
            try{
                $number = $request->get('number');
                $recordId = $request->get('record');
                $connector = $serverModel->getConnector();
                $result = $connector->call($number, $recordId);
                $response->setResult($result);
            }catch(Exception $e){
                throw new Exception($e);
            }
        }else{
            $response->setResult(false);
        }
        $response->emit();
    }
    
}