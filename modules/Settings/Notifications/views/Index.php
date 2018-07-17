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

	public function process (Head_Request $request) {
	
		global $current_user;
		$user_id = $current_user->id;
		$viewer = $this->getViewer($request);

		if(file_exists("user_privileges/notifications/notification_".$user_id.".php"))
			$file_name = "user_privileges/notifications/notification_".$user_id.".php";
		else
			$file_name = 'user_privileges/notifications/default_settings.php';

                require($file_name);
		$viewer->assign('GLOBAL_SETTINGS', $global_settings);
		$viewer->assign('FILE_PATH', $file_name);
		$viewer->assign('PERMITTED_MODULES', $notification_settings);
		$qualifiedModuleName = $request->getModule(false);
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
