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

class Settings_ModuleDesigner_InstallModule_Action extends Settings_Head_Index_Action
{
	public function process(Head_Request $request)
	{
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
			
		$error_code = '';
	 	$error_message = '';
		$success_message = '';
		$success = true;
		
        if(!$request->get("name") && !$request->get("version") && !$request->get("zip"))
		{
			$error_code = 'error-param';
			$error_message = getTranslatedString("LBL_ERROR_PARAM", $qualifiedModuleName);
			$success = false;
		}
		else
		{
			
			require_once("vtlib/Head/Module.php");
			
			$module = Head_Module::getInstance($request->get("name"));
			
			if(empty($module))
			{
				require_once("vtlib/Head/PackageImport.php");
				
				$packageImport = new Head_PackageImport();
				$packageImport->import($request->get("zip"));
				
				$module = Head_Module::getInstance($request->get("name"));
				
				if(!empty($module))
				{
                    global $adb ,$current_user;
                    $user_id = $current_user->id;

                    $parent = strtoupper($request->get("parent_tab"));
                    $sequence = Settings_MenuManager_Module_Model::getMaxSequenceForApp($parent) + 1;

                    /*$adb->pquery("insert into jo_app2tab values(?,?,?,?)", array(getTabId($request->get("name")), $parent, $sequence, 1));*/

                    $appName = strtoupper($request->get("parent_tab"));
	                
	                $file_name = "storage/menu/module_apps_".$user_id.".php";
                    if(file_exists($file_name))
                    {
                    	require($file_name);
                    }
                    else
                    {
                    	require("storage/menu/default_module_apps.php");
                    }

	                if(empty ($app_menu_array[$appName]) )
                	{
	                	$app_menu_array[$appName] = [];
	                }

        		 	$tabid = getTabId($request->get("name") );

        		 	if($tabid && !in_array($tabid, $app_menu_array[$appName]))
                    	array_push($app_menu_array[$appName], $tabid);
	                
	                $myfile = fopen($file_name, "w");

	                if($myfile) {
	                	fwrite($myfile, "<?php
		                ".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
		                ?>");
		                fclose($myfile);	
	                }
	                else{

	                	$error_code = 'error-install';
						$error_message = getTranslatedString("LBL_MENU_NOTADDED_ERROR", $qualifiedModuleName);
						$success = false;		
	                }


                    $basetable = "jo_".strtolower($request->get("name"));
                    $tablename = $basetable.'_user_field';
                    $table_id = strtolower($request->get("name")).'id';
                    $adb->pquery("CREATE TABLE `$tablename` (
                      `recordid` int(25) NOT NULL,
                      `userid` int(25) NOT NULL,
                      `starred` varchar(100) DEFAULT NULL,
                       KEY `fk_contactid_$tablename` (`recordid`),
                       CONSTRAINT `fk_".$table_id."_".$tablename."` FOREIGN KEY (`recordid`) REFERENCES `$basetable` (`".$table_id."`) ON DELETE CASCADE) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());
					$success_message = getTranslatedString("LBL_INSTALL_SUCCESS", $qualifiedModuleName);
					$moduleName = $request->get("name");
                                        $getEntity = $adb->pquery('select name from jo_ws_entity where name = ?', array($moduleName));
                                        $entityName = $adb->query_result($getEntity, 0, 'name');
                                        if(empty($entityName))  {
                                                $adb->pquery('insert into jo_ws_entity (name, handler_path, handler_class, ismodule) values (?, ?, ?, ?)', array($moduleName, 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));
                                        }

				}
				else
				{
					$error_code = 'error-install';
					$error_message = getTranslatedString("LBL_INSTALL_ERROR", $qualifiedModuleName);
					$success = false;
				}
			}
			else
			{
				if($request->get("version") != $module->version)
				{	
					require_once("vtlib/Head/PackageUpdate.php");
				
					$packageUpdate = new Head_PackageUpdate();
					$packageUpdate->update($module, $request->get("zip"));
					
					$success_message = getTranslatedString("LBL_UPDATE_SUCCESS", $qualifiedModuleName);
				}
				else
				{
					$error_code = 'error-version';
					$error_message = getTranslatedString("LBL_UPDATE_ERROR_VERSION", $qualifiedModuleName);
					$success = false;
				}
			}
			
			//Make JSON response		
	        $response = new Head_Response();
			if(!$success)
			{
	        	$response->setError($error_code, $error_message);
			}
			else
	        {
	        	$response->setResult(array('message' => $success_message));
			}
	        $response->emit();
		}
	}
}
?>
