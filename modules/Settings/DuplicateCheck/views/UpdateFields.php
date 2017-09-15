<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class Settings_DuplicateCheck_UpdateFields_View extends Settings_Head_Index_View
{
    public function __construct()
    {
        parent::__construct();
    }
    public function process(Head_Request $request){
        extract($_POST);

        global $adb, $site_URL;


        $isenabled=$_POST['isenabled'];
        $crosscheck=$_POST['ischecked'];
        if($isenabled == ""){
            $isenabled = 0;
        }
        if($ischecked == "")
            $crosscheck = 0;
        else
            $crosscheck = 1;
        $fieldID=$_POST['fieldID'];
        $modulename=$_POST['modulename'];       
        $fieldsID = implode(',', $fieldID);

        if(empty($modulename) || trim($modulename) == ''){
            header("location:".$site_URL."DuplicateCheck/Settings/List/$modulename");
            die('Failure');

        }

        if(($modulename != "") && (isset($_POST["savebutton"]))){
            $runQuery = $adb->pquery("UPDATE jo_vtduplicatechecksettings SET fieldstomatch='$fieldsID' ,isenabled='$isenabled' ,crosscheck='$crosscheck' WHERE modulename='$modulename'");
            header("location:".$site_URL."DuplicateCheck/Settings/List/$modulename/notify=1");
        }
        else{
            header("location:".$site_URL."DuplicateCheck/Settings/List");
        }
        if($result){
            $response = new Head_Response();
            $response->setEmitType(Head_Response::$EMIT_JSON);
            $response->setResult($result);
            $response->emit();
            die;
        }
        die('Failure');

    }
}

?>
