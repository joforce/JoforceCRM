<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class AddressLookup_GetFields_Action extends Head_Action_Controller
{
    public function checkPermission()
    {
        return true;
    }

    public function process(Head_Request $request)
    {
        global $adb;	
        $formArray = array();
        $fieldIDList = array();
        $fieldNameList = array();
        $moduleName = $request->get('moduleName');
        $fieldName = $request->get('fieldName');
        $gettabid = getTabID($moduleName);
        $getFieldID = $adb->pquery("SELECT fieldid from jo_field WHERE fieldname = ? AND tabid = ?",array($fieldName,$gettabid));
        $getFieldID = $adb->fetch_array($getFieldID);
        $getFieldID = $getFieldID['fieldid'];
        $getTotalFields = $adb->pquery("SELECT * FROM jo_vtaddressmapping WHERE modulename = ?",array($moduleName));
        $fetchTotalFields = $adb->fetch_array($getTotalFields);

        $getStreetID = $fetchTotalFields['street'];
        $streetValues = $this->decodeAndUnserialize($getStreetID);		
        array_push($formArray,$streetValues);	

        $getAreaID = $fetchTotalFields['area']; 
        $areaValues = $this->decodeAndUnserialize($getAreaID);
        array_push($formArray,$areaValues);	

        $getLocalityID = $fetchTotalFields['locality']; 
        $localityValues = $this->decodeAndUnserialize($getLocalityID);
        array_push($formArray,$localityValues);	

        $getCityID = $fetchTotalFields['city']; 
        $cityValues = $this->decodeAndUnserialize($getCityID);
        array_push($formArray,$cityValues);	

        $getStateID = $fetchTotalFields['state']; 
        $stateValues = $this->decodeAndUnserialize($getStateID);		
        array_push($formArray,$stateValues);	

        $getCountryID = $fetchTotalFields['country']; 
        $countryValues = $this->decodeAndUnserialize($getCountryID);	
        array_push($formArray,$countryValues);	

        $getPostalCodeID = $fetchTotalFields['postalcode'];
        $postalcodeValues = $this->decodeAndUnserialize($getPostalCodeID);
        array_push($formArray,$postalcodeValues);	

        $keyValue = array_search($getFieldID, $streetValues);

        foreach($formArray as $singleArray){
            $fieldID = $singleArray[$keyValue];			
            array_push($fieldIDList,$fieldID);				
        }
        foreach($fieldIDList as $key => $value){
            $getFieldName = $adb->pquery("SELECT fieldname FROM jo_field WHERE fieldid = ?",array($value));
            $fetchFieldName = $adb->fetch_array($getFieldName);
            array_push($fieldNameList,$fetchFieldName['fieldname']);
        }	

        $response = new Head_Response();
        $response->setEmitType(Head_Response::$EMIT_JSON);
        $response->setResult($fieldNameList);
        $response->emit();
        die;
    }

    public function decodeAndUnserialize($getValues)
    {
        $decodeStreetID = base64_decode($getValues);
        $unserializeStreetID = unserialize($decodeStreetID);
        return $unserializeStreetID;
    }
}
