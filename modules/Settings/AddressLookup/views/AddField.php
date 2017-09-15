<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class Settings_AddressLookup_AddField_View extends Settings_Head_Index_View{
	public function process(Head_Request $request){
	
		global $adb;
		$exceptModules = 'Calendar';
                        $moduleNames = array();
                        $moduleFieldsList = array();

		 $modulename = $request->get('modulename');

		 $isenabledcheck = $adb->pquery("SELECT isenabled FROM jo_vtaddressmapping WHERE modulename = ?",array($modulename));
                        $getenabledcheck = $adb->fetch_array($isenabledcheck);
                        $enabledCheck = $getenabledcheck['isenabled'];


                        $getModulenames = $adb->pquery("SELECT name, tablabel FROM jo_tab WHERE isentitytype='1' AND name!=?",array($exceptModules));
                        $qualifiedModule = $request->getModule(false);
                        while($fetchModuleNames = $adb->fetch_array($getModulenames)){
                                array_push($moduleNames, $fetchModuleNames);
                        }

                        $qualifiedModule = $request->getModule(false);


                        $runQueryTabid = $adb->pquery("SELECT tabid FROM jo_tab WHERE name=?",array($modulename));
                        $fetchQueryTabid = $adb->fetch_array($runQueryTabid);
                        $getTabid = $fetchQueryTabid['tabid'];

                        $getModuleFields = $adb->pquery("SELECT fieldid, fieldlabel FROM jo_field WHERE tabid = ?",array($getTabid));
                        $getModuleFieldsCount = $adb->num_rows($getModuleFields);
			array_push($moduleFieldsList,$getModuleFieldsCount);
			while($fetchModuleFields = $adb->fetch_array($getModuleFields)){
                                array_push($moduleFieldsList, $fetchModuleFields);
                        }
			
			$response = new Head_Response();
		            $response->setEmitType(Head_Response::$EMIT_JSON);
		            $response->setResult($moduleFieldsList);
		            $response->emit();
		            die;
                       


	}
}
