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

class Settings_Notifications_Index_View extends Settings_Head_Index_View {
    public function checkPermission(Head_Request $request) {
	return true;
    }

    public function  preProcess(Head_Request $request) {
	parent::preProcess($request);
    }

    public function process(Head_Request $request) {
	global $current_user;
	$user_id = $current_user->id;
	$viewer = $this->getViewer($request);
	$qualifiedModuleName = $request->getModule(false);

	$permittedTabIdList = getPermittedModuleIdList();
	$db = PearDatabase::getInstance();
	$query = "select id,global,notificationlist from jo_notification_manager where id = ?";
	$result = $db->pquery($query, array($user_id));
	$rows = $db->num_rows($result);
	if($rows <= 0){
		$query = "select id,global,notificationlist from jo_notification_manager where id = ?";
		$result = $db->pquery($query, array(0));
		$rows = $db->num_rows($result);
	}
	for ($i=0; $i<$rows; $i++) {
		$row = $db->query_result_rowdata($result, $i);
		$global_settings = $row['global'];
		$notification_settings = unserialize(base64_decode($row['notificationlist']));
	}
	$viewer->assign('GLOBAL_SETTINGS', $global_settings);
	//$viewer->assign('notify_all', $notification_for_all);
	// $viewer->assign('FILE_PATH', $file_name);
	$viewer->assign('PERMITTED_MODULES', $notification_settings);
	$viewer->assign('user_permitted_modules', $permittedTabIdList);
	$viewer->assign('QUALIFIED_MODULE_NAME', $qualifiedModuleName);
	$viewer->view('Index.tpl', $qualifiedModuleName);
    }

    function getHeaderScripts(Head_Request $request) {
	$headerScriptInstances = parent::getHeaderScripts($request);
	$moduleName = $request->getModule();

	$jsFileNames = array(
	    "modules.Settings.$moduleName.resources.List",
	);

	$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
	$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
	return $headerScriptInstances;
    }
}
