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

class Settings_MenuManager_AddMainMenu_View extends Settings_Head_Index_View {
    public function __construct() {
	parent::__construct();
	$this->exposeMethod('ShowAddMenuModal');
    }

    public function checkPermission(Head_Request $request) {
    	return true;
    }

    public function process(Head_Request $request) {
    	$mode = $request->getMode();
        if (!empty($mode)) {
            $this->invokeExposedMethod($mode, $request);
            return;
        }
    }

    function ShowAddMenuModal(Head_Request $request) {
	global $adb, $current_user;
	$user_id = $current_user->id;
	$admin_status = Settings_MenuManager_Module_Model::isAdminUser();

	$viewer = $this->getViewer($request);
	$qualifiedModuleName = $request->getModule(false);
	$type = $request->get('type');

	if(file_exists("storage/menu/main_menu_".$user_id.".php")) {
	    require( "storage/menu/main_menu_".$user_id.".php");
	} else {
	    require("storage/menu/default_main_menu.php");
	}
	$menu_count = count($main_menu_array);
	if($menu_count == 10 || $menu_count > 10) {
	    $message_string = "LBL_MAXIMUM_10_MODULES";
	    $viewer->assign('MESSAGE', $message_string);
	} else {
	    if($type == "module") {
		$module_array = [];
		foreach($main_menu_array as $array) {
		    if($array['type'] == 'module') {
			array_push($module_array ,$array['tabid']);
		    }
		}
		$viewer->assign('TYPE', $type);
		$all_module_list = Settings_MenuManager_Module_Model::getModuleListForMainMenu();
		if($admin_status == 'true') {
		    foreach($all_module_list as $key => $moduleid) {
			if((array_search($moduleid, $module_array)) !== false) {
			    unset($all_module_list[$key]);
			}
		    }
		} else {
		    foreach($all_module_list as $key => $moduleid) {
                        if(((array_search($moduleid, $module_array)) !== false) || ((Settings_MenuManager_Module_Model::isPermittedModule($moduleid)) == false)) {
                            unset($all_module_list[$key]);
                        }
                    }
		}
		$viewer->assign('TADID_ARRAY', array_values($all_module_list));	
	    } else {
		$viewer->assign('TYPE', $type);
	    }
	}

	$viewer->assign('MODULE', $request->getModule());
	$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
	$viewer->view('AddMainMenu.tpl', $qualifiedModuleName);
    }
}
