<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ************************************************************************************/

class Home_UpdateNotifications_Action extends Head_Save_Action {
    public function process(Head_Request $request) {
	global $adb, $current_user;
	$module = $request->getModule();
	$mark_all_as_read = $request->get('mark_all');
	$current_user_id = $current_user->id;

	if($mark_all_as_read !== 'false') {
	    $select_query = "SELECT * FROM jo_notification WHERE notifier_id = ? and is_seen=?";
	    $select_array = array($current_user_id, 0);
	    $fetch_values = $adb->pquery($select_query, $select_array);

	    $notification_id_array = [];
	    while($fetch_array = $adb->fetch_array($fetch_values)){
		array_push($notification_id_array, $fetch_array['id']);
	    }
	} else {
	    $notification_id_array = $request->get('notify_ids');
	}

	$current_date = gmdate("Y-m-d H:i:s");
	foreach($notification_id_array as $notification_id) {
	    $query = "UPDATE jo_notification SET is_seen = ? , updated_at = ? WHERE id = ?";
	    $value_array = array(1, $current_date, $notification_id);
	    $result = $adb->pquery($query, $value_array);
	}

	$response = new Head_Response();
	$response->setResult(array('success' => true));
	$response->emit();
    }
}
