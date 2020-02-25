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

class Home_ShowNotifications_View extends Head_Index_View {	
    public function process(Head_Request $request) {
	global $current_user, $adb;
	$user_id = $current_user->id;
	$moduleName = $request->getModule();
	$viewer = $this->getViewer($request);

	$user_notifications = getUserModuleNotifications('All', $user_id, true);
        $viewer->assign('CURRENT_USER_NOTIFICATONS', $user_notifications);
        $viewer->assign('NOTIFICATONS_COUNT', count($user_notifications));
	$viewer->assign('current_user_id', $user_id);

	$viewer->view('partials/TopbarNotifications.tpl', $moduleName);
    }
}
