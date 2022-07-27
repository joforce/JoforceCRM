<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/

Class Settings_MenuManager_SaveAjax_Action extends Settings_Head_IndexAjax_View {

    function __construct() {
	parent::__construct();
	$this->exposeMethod('removeModule');
	$this->exposeMethod('addModule');
	$this->exposeMethod('saveSequence');
    }
	
    public function checkPermission(Head_Request $request) {
	return true;
    }

    public function process(Head_Request $request) {
	$mode = $request->get('mode');
	if (!empty($mode)) {
	    $this->invokeExposedMethod($mode, $request);
	    return;
	}
    }

    function removeModule(Head_Request $request) {
		global $adb, $current_user;
		$admin_status = Settings_MenuManager_Module_Model::isAdminUser();
		$user_id = $current_user->id;
		$sourceModule = $request->get('sourceModule');
		$appName = $request->get('appname');
		$tabid = getTabid($sourceModule);
		$app_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'module_apps');
		$array = $app_menu_array[$appName];
		$key = array_search($tabid, $array);
		unset($array[$key]);
		$app_menu_array[$appName] = array_values($array);
			
		$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'module_apps', $app_menu_array);

		$response = new Head_Response();
		$response->setResult(array('success' => true));
		$response->emit();
    }

    function addModule(Head_Request $request) {
		global $adb, $current_user;
		$user_id = $current_user->id;
		$sourceModules = array($request->get('sourceModule'));
		if ($request->has('sourceModules')) {
			$sourceModules = $request->get('sourceModules');
		}
		$source_tab_array = [];
		foreach($sourceModules as $moduleName) {
			$tabid = getTabid($moduleName);
			array_push($source_tab_array, $tabid);
		}

		$appName = $request->get('appname');
		$app_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'module_apps');
		if(empty ($app_menu_array[$appName]) ) {
			$app_menu_array[$appName] = [];
		}

		foreach($source_tab_array as $tabid) {
			array_push($app_menu_array[$appName], $tabid);
		}
		$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'module_apps', $app_menu_array);

		$response = new Head_Response();
		$response->setResult(array('success' => true));
		$response->emit();
    }

    function saveSequence(Head_Request $request) {
	global $adb, $current_user;
	$moduleSequence = $request->get('sequence');
	$appName = $request->get('appname');
	$user_id = $current_user->id;
	$new_app_menu_array = [];
	foreach($moduleSequence as $moduleName => $sequence) {
	    array_push($new_app_menu_array, getTabId($moduleName));
	}
	
	$app_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'module_apps');

	$app_menu_array[$appName] = $new_app_menu_array;
	$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'module_apps', $app_menu_array);

	$response = new Head_Response();
	$response->setResult(array('success' => true));
	$response->emit();
    }
}
