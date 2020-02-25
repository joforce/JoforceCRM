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
	$file_name = "storage/menu/module_apps_".$user_id.".php";	
	if(file_exists($file_name)) {
	    require($file_name);
	} else {
	    require("storage/menu/default_module_apps.php");
        }

	$array = $app_menu_array[$appName];
	$key = array_search($tabid, $array);
	unset($array[$key]);
	$app_menu_array[$appName] = array_values($array);
		
	$myfile = fopen($file_name, "w") or die("Unable to open file!");
        fwrite($myfile, "<?php
		".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
	?>");
	fclose($myfile);	
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
	$file_name = "storage/menu/module_apps_".$user_id.".php";
        if(file_exists($file_name)) {
	    require($file_name);
        } else {
            require("storage/menu/default_module_apps.php");
        }
	if(empty ($app_menu_array[$appName]) ) {
	    $app_menu_array[$appName] = [];
	}

	foreach($source_tab_array as $tabid) {
	    array_push($app_menu_array[$appName], $tabid);
	}
	$myfile = fopen($file_name, "w") or die("Unable to open file!");
	fwrite($myfile, "<?php
		".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
	?>");
	fclose($myfile);

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
	
	$file_name = "storage/menu/module_apps_".$user_id.".php";
        if(file_exists($file_name)) {
	    require($file_name);
	} else {
	    require("storage/menu/default_module_apps.php");
        }
	$app_menu_array[$appName] = $new_app_menu_array;
	$myfile = fopen($file_name, "w") or die("Unable to open file!");
	fwrite($myfile, "<?php
		".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
	?>");
	fclose($myfile);
	$response = new Head_Response();
	$response->setResult(array('success' => true));
	$response->emit();
    }
}
