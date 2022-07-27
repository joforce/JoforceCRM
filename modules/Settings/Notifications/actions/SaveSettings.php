<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Notifications_SaveSettings_Action extends Settings_Head_Index_Action {
    public function checkPermission(Head_Request $request) {
		return true;
    }

    public function process(Head_Request $request) {
		global $current_user;
		$user_id = $current_user->id;
		$updatedFields = $request->get('updatedFields');
		$global_notification_settings = $request->get('global_settings');

		$checked_values = [];
		foreach($updatedFields as $key => $value) {
			$array = explode('_', $key);
			$module= $array[0];
			$keyvalue = $array[1];

			$checked_values[$module][$keyvalue] = $value;
		}

		$db = PearDatabase::getInstance();
		$query = "select id,global,notificationlist from jo_notification_manager where id = ?";
		$result = $db->pquery($query, array($user_id));
		$rows = $db->num_rows($result);
		if($rows <= 0){
			$query = "Insert into jo_notification_manager(id,global,notificationlist) values(?,?,?)";
			$result = $db->pquery($query, array($user_id,$global_notification_settings,base64_encode(serialize($checked_values))));
			$rows = $db->num_rows($result);
		}else{
			$query = "Update jo_notification_manager set global=?,notificationlist=? where id = ?";
			$result = $db->pquery($query, array($global_notification_settings,base64_encode(serialize($checked_values)),$user_id));
			$rows = 1;
		}
		if($rows >= 0) {
			$this->emitResponse('true', 'Settings are saved successfully.');
		} else {
			$this->emitResponse('false', 'Settings are not saved.');
		}
    }

    public function emitResponse($status, $message) {
	$response = new Head_Response();
        $response->setResult(array('saved' => $status, 'message' => $message,));
	$response->emit();
	die;
    }
}
