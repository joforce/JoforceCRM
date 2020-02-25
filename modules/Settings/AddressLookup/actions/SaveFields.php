<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class Settings_AddressLookup_SaveFields_Action extends Head_Action_Controller
{
    public function checkPermission() {
        return true;
    }

    public function process(Head_Request $request) {
        global $adb, $root_directory, $site_URL;
        $isenabled = "0";
        if(isset($_POST['isenabled']))
            $isenabled = "1";

        $street = $request->get('street');
        $area = $request->get('area');
        $locality = $request->get('locality');
        $city = $request->get('city');
        $state = $request->get('state');
        $country = $request->get('country');
        $postalcode = $request->get('postalcode');
        $modulename = $request->get('modulename');
        $APIkey = $_POST['APIkey'];

        $APIkeyfile=fopen("$root_directory/modules/Settings/AddressLookup/APIkey.php","w+");
        fwrite($APIkeyfile,'<?php $APIkey="');
        fwrite($APIkeyfile,$APIkey);
        fwrite($APIkeyfile,'"?>');
        fclose($APIkeyfile);

        if($modulename == ""){
            header("location: {$site_URL}AddressLookup/Settings/List?sourceModule=$modulename&check=1");
            die;
        }
        $street = base64_encode(serialize($street));
        $area = base64_encode(serialize($area));
        $locality = base64_encode(serialize($locality));
        $city = base64_encode(serialize($city));
        $state = base64_encode(serialize($state));
        $country = base64_encode(serialize($country));
        $postalcode = base64_encode(serialize($postalcode));

        $adb->pquery("INSERT INTO jo_vtaddressmapping(modulename) SELECT * FROM (SELECT ?)AS tmp WHERE NOT EXISTS (SELECT modulename FROM jo_vtaddressmapping WHERE modulename = ?)",array($modulename,$modulename));

        $saveFieldQuery = $adb->pquery("UPDATE jo_vtaddressmapping SET street = ?, area = ?, locality = ?, city = ?, state = ?, country = ?, postalcode = ?, isenabled = ? WHERE modulename = ?",array($street,$area,$locality,$city,$state,$country,$postalcode,$isenabled,$modulename));
        if($saveFieldQuery)	{
            if(isset($_POST["submitCustomModule"]))
                header("location: {$site_URL}AddressLookup/Settings/List?sourceModule=$modulename&success=true");
            else
                header("location: {$site_URL}AddressLookup/Settings/List?sourceModule=$modulename");
        }
        else {
            header("location:$site_URL/AddressLookup/Settings/List?sourceModule=$modulename&error=true");
        }
    }
}
