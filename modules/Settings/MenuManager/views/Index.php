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

class Settings_MenuManager_Index_View extends Settings_Head_Index_View {
	
	public function checkPermission(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		global $adb, $current_user;
		$allModelsList = Head_Menu_Model::getAll(true);
		$menuModelStructure = Head_MenuStructure_Model::getInstanceFromMenuList($allModelsList);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$user_id = $current_user->id;
	
		$viewer = $this->getViewer($request);
		$viewer->assign('SECTION_ARRAY', getSectionList($user_id)); //section names
                $viewer->assign('MAIN_MENU_TAB_IDS', getMainMenuList($user_id)); //main menu
                $viewer->assign('APP_MODULE_ARRAY', getAppModuleList($user_id)); //modules and sections		
			
                $menuname = $request->get('menuname');
                $admin_status = Settings_MenuManager_Module_Model::isAdminUser();
                $moduleSequence = $request->get('sequence');

		$viewer->assign('ALL_MODULES', $menuModelStructure->getMore());
		$viewer->assign('SELECTED_MODULES', $menuModelStructure->getTop());
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('QUALIFIED_MODULE_NAME', $qualifiedModuleName);

		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		$viewer->view('Index.tpl', $qualifiedModuleName);
	}
}
