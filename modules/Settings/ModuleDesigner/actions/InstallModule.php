<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): Jonathan SARDO.
 * Portions created by Jonathan SARDO are Copyright (C).
 *************************************************************************************/

class Settings_ModuleDesigner_InstallModule_Action extends Settings_Head_Index_Action {
    public function process(Head_Request $request) {
	global $adb, $current_user;
	$moduleName = $request->getModule();
	$qualifiedModuleName = $request->getModule(false);
			
	$error_code = '';
 	$error_message = '';
	$success_message = '';
	$success = true;
		
        if(!$request->get("name") && !$request->get("version") && !$request->get("zip")) {
	    $error_code = 'error-param';
	    $error_message = getTranslatedString("LBL_ERROR_PARAM", $qualifiedModuleName);
	    $success = false;
	} else {	
	    require_once("libraries/modlib/Head/Module.php");
	    $module = Head_Module::getInstance($request->get("name"));
			
	    if(empty($module)) {
		require_once("libraries/modlib/Head/PackageImport.php");
				
		$packageImport = new Head_PackageImport();
		$packageImport->import($request->get("zip"));
				
		$module = Head_Module::getInstance($request->get("name"));
				
		if(!empty($module)) {
		    $success_message = getTranslatedString("LBL_INSTALL_SUCCESS", $qualifiedModuleName);
		    $moduleName = $request->get("name");
                    $getEntity = $adb->pquery('select name from jo_ws_entity where name = ?', array($moduleName));
                    $entityName = $adb->query_result($getEntity, 0, 'name');
                    if(empty($entityName))  {
                        $adb->pquery('insert into jo_ws_entity (name, handler_path, handler_class, ismodule) values (?, ?, ?, ?)', array($moduleName, 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));
		    }

		    //Save tabid under Parent Tab - starts
		    $appName = $request->get("parent_tab");
		    if(!empty($appName)) {
		    	$user_id = $current_user->id;
		    	$module_tabid = getTabid($moduleName);

        	    	$file_name = "storage/menu/module_apps_".$user_id.".php";
		    	if(file_exists($file_name)) {
		            require($file_name);
		    	} else {
		            require("storage/menu/default_module_apps.php");
			}

			foreach(array_keys($app_menu_array) as $APP_NAME) {
			    if( !strcasecmp($APP_NAME,$appName) ) {
				$appName = $APP_NAME;
			    }
			}

		    	if(empty ($app_menu_array[$appName]) ) {
		            $app_menu_array[$appName] = [];
	            	}

	            	array_push($app_menu_array[$appName], $module_tabid);
		    	$myfile = fopen($file_name, "w") or die("Unable to open file!");
	            	fwrite($myfile, "<?php
	                    ".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
        	    	?>");
			fclose($myfile);
		    }
		    //Save tabid under Parent Tab - ends
                    $success = true;
		} else {
		    $error_code = 'error-install';
		    $error_message = getTranslatedString("LBL_INSTALL_ERROR", $qualifiedModuleName);
		    $success = false;
		}
	    } else {
		if($request->get("version") != $module->version) {	
		    require_once("libraries/modlib/Head/PackageUpdate.php");
		    $packageUpdate = new Head_PackageUpdate();
		    $packageUpdate->update($module, $request->get("zip"));
					
		    $success_message = getTranslatedString("LBL_UPDATE_SUCCESS", $qualifiedModuleName);
        	    $success = true;
		} else {
		    $error_code = 'error-version';
		    $error_message = getTranslatedString("LBL_UPDATE_ERROR_VERSION", $qualifiedModuleName);
		    $success = false;
		}
	    }
			
	    //Make JSON response		
	    $response = new Head_Response();
	    if(!$success) {
        	$response->setError($error_code, $error_message);
	    } else {
	        $response->setResult(array('message' => $success_message));
	    }
	    $response->emit();
	}
    }
}
?>
