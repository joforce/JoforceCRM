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

class Settings_MenuManager_AddSection_View extends Settings_Head_Index_View {

	public function checkPermission(Head_Request $request) {
                return true;
        }

	public function process(Head_Request $request) {
		global $adb, $current_user;
		$admin_status = Settings_MenuManager_Module_Model::isAdminUser();
        	$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
	
		$all_module_list = Settings_MenuManager_Module_Model::getModuleListForMainMenu();
		if($admin_status !== 'true')
                        {
                        foreach($all_module_list as $key => $moduleid)
        	                {
                	        if((Settings_MenuManager_Module_Model::isPermittedModule($moduleid)) == false)
                         	       {
                                       unset($all_module_list[$key]);
                                       }
                                }
                        }
		$viewer->assign('TADID_ARRAY', $all_module_list);
		$viewer->assign('SITEURL', $site_URL);

        	$viewer->view('AddSection.tpl', $qualifiedModuleName);
	    }

	}
?>
