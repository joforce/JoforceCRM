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

class Home_NotificationListAjax_View extends Head_Index_View 
{	
	public function process(Head_Request $request)
	{
		global $current_user, $adb;
		$user_id = $current_user->id;
		$moduleName = $request->getModule();

		$viewer = $this->getViewer($request);

		$notification_module = $request->get('moduleName');
		$get_notifications = getUserModuleNotifications($notification_module, $user_id);

		$viewer->assign('NOTIFICATIONS', $get_notifications);
		$viewer->assign('NOTIFICATIONS_COUNT', count($get_notifications));
		$viewer->assign('NOTIFICATION_MODULE', $notification_module);
		$viewer->assign('CURRENT_USER_ID', $user_id);

		$viewer->view('NotificationListAjax.tpl', $moduleName);
	}
}
