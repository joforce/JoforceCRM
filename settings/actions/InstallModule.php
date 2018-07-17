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
		                    global $adb;
				    $success_message = getTranslatedString("LBL_INSTALL_SUCCESS", $qualifiedModuleName);
				    $moduleName = $request->get("name");
                                    $getEntity = $adb->pquery('select name from jo_ws_entity where name = ?', array($moduleName));
                                    $entityName = $adb->query_result($getEntity, 0, 'name');
                                    if(empty($entityName))  {
						$id = $adb->getUniqueID("jo_ws_entity");
                                                $adb->pquery('insert into jo_ws_entity (id, name, handler_path, handler_class, ismodule) values (?, ?, ?, ?, ?)', array($id, $moduleName, 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));
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
