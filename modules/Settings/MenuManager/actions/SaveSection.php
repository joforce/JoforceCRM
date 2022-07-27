<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Settings_MenuManager_SaveSection_Action extends Settings_Head_Index_Action {
    function __construct() {
	parent::__construct();
	$this->exposeMethod('deleteSection');
        $this->exposeMethod('addSection');
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
	
    function addSection(Head_Request $request) {
	global $adb, $current_user;
	$admin_status = Settings_MenuManager_Module_Model::isAdminUser();
	$user_id = $current_user->id;
	$section_name = $request->get('section_name');
	$tabid = $request->get('tabid');
	$icon_info = $request->get('icon_info');
	$section_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'default_sections');
        if(empty($section_array)) {
            require("storage/menu/default_sections.php");
        }
	$section_array[$section_name] = $icon_info;
	$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'default_sections', $section_array);

	$app_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'module_apps');
	if(empty($app_menu_array)) {
	    require("storage/menu/default_module_apps.php");
	}
	$app_menu_array[$section_name][0] = $tabid;
	$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'module_apps', $app_menu_array);

	$response = new Head_Response();
	$response->setResult(array('success' => true));
	$response->emit();
    }
    
    function deleteSection(Head_Request $request) {
	global $adb, $current_user;
	$user_id = $current_user->id;
	$appName = $request->get('appname');

	$section_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'default_sections');
	if(empty($section_array)) {
		require("storage/menu/default_sections.php");
	}

	unset($section_array[$appName]);
	$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'default_sections', $section_array);

	$app_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'module_apps');
	if(empty($app_menu_array)) {
	    require("storage/menu/default_module_apps.php");
	}
	unset($app_menu_array[$section_name]);
	$_save = Settings_MenuManager_Module_Model::updateDetailsInTable($user_id, 'module_apps', $app_menu_array);

	$response = new Head_Response();
	$response->setResult(array('success' => true));
	$response->emit();
    }
}
