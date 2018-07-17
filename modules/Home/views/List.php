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

class Home_List_View extends Head_Index_View {

    public function preProcess(Head_Request $request, $display = true) {
                $viewer = $this->getViewer($request);
                $moduleName = $request->getModule();
                $viewer->assign('VIEW' , $request->get('view'));

		$viewer->assign('PRESENT_TAB', 'DASHBOARD');
                parent::preProcess($request, false);
                if($display) {
                        $this->preProcessDisplay($request);
                }
        }

    protected function preProcessTplName(Head_Request $request) {
                return 'dashboards/DashBoardPreProcess.tpl';
        }

    public function process(Head_Request $request) {
	global $current_user;
	$current_user_id = $current_user->id;

	$boardid = updateDefaultDashboardView($current_user_id, 1);

	$userPrivilegesModel = Users_Privileges_Model::getInstanceById($current_user_id);

	$moduleName = $request->getModule();
        $viewer = $this->getViewer($request);
	$entity_module_list = Head_Module_Model::getEntityModules();
	$notificaiton_array = Head_Module_Model::getNotificationCountForAllModules();
	
	$notification_count_array = [];
	$module_array = [];
	$count_array = [];
	foreach($notificaiton_array as $array)
	{
		array_push($module_array, $array['module_name']);
		array_push($count_array, $array['count(id)']);
	}
	
	$notificaiton_count_array = array_combine($module_array, $count_array);
	$exception_module_array = ['Emails', 'Task'];

        $viewer->assign('MODULE_NAME', $moduleName);
	$viewer->assign('USER_PRIVILEGE_MODEL', $userPrivilegesModel);
	$viewer->assign('NOTIFICATIONS_COUNT_ARRAY', $notificaiton_count_array);
	$viewer->assign('NOTIFICATIONS_MODULE_ARRAY', $module_array);
	$viewer->assign('EXCEPTION_ARRAY', $exception_module_array);
	$viewer->assign('ENTITY_MODULES', $entity_module_list);
	$viewer->view('Notifications.tpl', $request->getModule());
    }

    	/**
         * Function to get the list of Script models to be included
         * @param Head_Request $request
         * @return <Array> - List of Head_JsScript_Model instances
         */
        function getHeaderScripts(Head_Request $request) {
                $headerScriptInstances = parent::getHeaderScripts($request);
                $moduleName = $request->getModule();

                $jsFileNames = array(
                        "modules.Head.resources.Head",
			"modules.Home.resources.List"
                );

                $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
                $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
                return $headerScriptInstances;
        }
	
	 public function getHeaderCss(Head_Request $request) {
                $headerCssInstances = parent::getHeaderCss($request);
                $cssFileNames = array(
                        "~layouts/skins/notification.css"
                );
                $cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
                $headerCssInstances = array_merge($headerCssInstances, $cssInstances);
                return $headerCssInstances;
        }
}

?>

