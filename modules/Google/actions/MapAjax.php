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

class Google_MapAjax_Action extends Head_BasicAjax_Action {

    public function process(Head_Request $request) {
        switch ($request->get("mode")) {
            case 'getLocation'	:	$result = $this->getLocation($request);
									break;
        }
        echo json_encode($result);
    }

    /**
     * get address for the record, based on the module type.
     * @param Head_Request $request
     * @return type 
     */
    function getLocation(Head_Request $request) {
        $result = Google_Map_Helper::getLocation($request);
        return $result;
    }
    
    public function validateRequest(Head_Request $request) {
        $request->validateReadAccess();
    }
}

?>
