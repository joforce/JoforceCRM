<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class Settings_DuplicateCheck_UpdateFields_Action extends Settings_Head_Basic_Action
{
    public function __construct()
    {
        parent::__construct();
    }
    public function process(Head_Request $request){
        global $adb, $site_URL;

        $isenabled = $request->get('isenabled');
        $crosscheck = $request->get('ischecked');
        if($isenabled == ""){
            $isenabled = 0;
        }
        if($ischecked == "")
            $crosscheck = 0;
        else
	    $crosscheck = 1;

        $fieldID = $request->get('fieldID');
        $modulename = $request->get('modulename');       
        $fieldsID = implode(',', $fieldID);

        $menu = Settings_Head_MenuItem_Model::getInstance('Duplicate Check');
        $fieldid = $menu->get('fieldid');
	$blockid = $menu->get('blockid');
	$check_exist = $adb->pquery('SELECT * from jo_duplicatechecksettings where modulename = ?', array($modulename));
	$count_val = $adb->getRowCount($check_exist);
        if(($modulename != "") && $count_val > 0){
            $result = $adb->pquery("UPDATE jo_duplicatechecksettings SET fieldstomatch='$fieldsID' ,isenabled='$isenabled' ,crosscheck='$crosscheck' WHERE modulename='$modulename'");
        } else {
	    $duplicate_id = $this->getPrimaryKeyId();
	    $result = $adb->pquery('insert into jo_duplicatechecksettings values(?,?,?,?,?)', array($duplicate_id, $modulename, $fieldsID, $isenabled, $crosscheck));
	}

        if($result){
            $response = new Head_Response();
            $response->setEmitType(Head_Response::$EMIT_JSON);
            $response->setResult($result);
            $response->emit();
        }
    }

    public function getPrimaryKeyId() {
	global $adb;
	$query = $adb->pquery('select id from jo_duplicatechecksettings order by id desc limit 1');
	$query_result = $adb->fetch_array($query);
	return $query_result['id']+1;
    }
}

?>
