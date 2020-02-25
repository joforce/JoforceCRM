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
class Home_ClearNotifications_Action extends Head_Save_Action
{
    public function process(Head_Request $request)
    {
        global $current_user;
        $user_id = $current_user->id;
        $notify_module = $request->get('moduleName');

	if($notify_module == 'All') {
	    $get_viewed_notifications = getUserModuleNotifications('All', $user_id, true);
	} else {
            $get_viewed_notifications = getUserModuleNotifications($notify_module, $user_id);
	}

	$notification_ids = [];
        foreach ($get_viewed_notifications as $notification) {
            array_push($notification_ids, $notification['id']);
        }

	$delete_status = deleteViewedNotifications($notification_ids);
	$response = new Head_Response();
	$response->setResult(array('success' => $delete_status));
	$response->emit();
    }
}
