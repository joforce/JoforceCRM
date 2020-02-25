<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class AddressLookup_GetFieldsName_Action extends Head_Action_Controller
{
    public function checkPermission()
    {
        return true;
    }

    public function process(Head_Request $request)
    {
        global $adb, $root_directory;
        $arrayValues = array();
        $fieldIDList = array();	
        $fieldSetList = array();
        $fieldSetTotalList = array();
        $modulename = $request->get('moduleName');
       
        if(file_exists($root_directory.'/modules/Settings/AddressLookup/APIkey.php'))
            include_once("$root_directory/modules/Settings/AddressLookup/APIkey.php");  
        else
            $APIkey='';
        if($modulename == 'Users'){
            $checkEnable =1;
            $result = array();
            $fieldname = array('address_street');
            $result[] = $fieldname;
           
        }
        else{
        $checkEnable = $adb->pquery("SELECT isenabled FROM jo_vtaddressmapping WHERE modulename = ?",array($modulename));	
        $checkEnable = $adb->fetch_array($checkEnable);
        $checkEnable = $checkEnable['isenabled'];

        if($checkEnable == 1){	
            $isenabled = "1";	
            $getFieldsQuery = $adb->pquery("SELECT street FROM jo_vtaddressmapping WHERE modulename = ? AND isenabled = ?", array($modulename,$isenabled));	
            $fetchFieldsQuery = $adb->fetch_array($getFieldsQuery);
            $fetchFieldsQuery = $fetchFieldsQuery['street'];
            $decodedFieldsList = base64_decode($fetchFieldsQuery);
            $unserializedFieldsList = unserialize($decodedFieldsList);

            if (array_filter($unserializedFieldsList)) {
                $fieldsCount = count($unserializedFieldsList);
                for($i = 0;$i < $fieldsCount;$i ++){	
                    $runQuery = $adb->pquery("SELECT fieldname FROM jo_field WHERE fieldid=?", array($unserializedFieldsList[$i]));
                    $fetchValues = $adb->fetch_array($runQuery);
                    array_push($arrayValues,$fetchValues['fieldname']);
# code...

                }

                array_push($fieldSetList,$arrayValues);
                $result = $fieldSetList;
            }
            else    {
                $result = "No field to map";
            }
        }
        else    {
            $result = "Not Enabled";
        }
     }

        $response = new Head_Response();
        $response->setEmitType(Head_Response::$EMIT_JSON);
        $response->setResult(array($APIkey,$result));
        $response->emit();
        die;
    }
}
