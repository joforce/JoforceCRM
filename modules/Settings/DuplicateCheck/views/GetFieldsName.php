<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class Settings_DuplicateCheck_GetFieldsName_View extends Settings_Head_Index_View{
	public function process(Head_Request $request){
		global $adb;
		$arrayValues = [];
		extract($_GET);
		$modulename = $_GET['moduleName'];
		$runQuery = $adb->pquery("SELECT fieldstomatch FROM jo_vtduplicatechecksettings WHERE modulename='$modulename' AND isenabled='1'");
		$fetchValues = $adb->fetch_array($runQuery);
		$count = $adb->num_rows($runQuery);
		$explodedValues = explode(",",$fetchValues['fieldstomatch']);
		array_push($arrayValues,count($explodedValues));
		foreach ($explodedValues as $key => $value) {
			$runQuery = $adb->pquery("SELECT fieldname FROM jo_field WHERE fieldid='$value'");
			$fetchValues = $adb->fetch_array($runQuery);
			array_push($arrayValues,$fetchValues['fieldname']);
			# code...
		}
	
	    $response = new Head_Response();
            $response->setEmitType(Head_Response::$EMIT_JSON);
            $response->setResult(array($count,$arrayValues));
            $response->emit();
            die;
	}
}


