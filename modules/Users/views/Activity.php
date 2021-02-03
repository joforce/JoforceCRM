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

class Users_Activity_View extends Users_PreferenceDetail_View {

	public function preProcess(Head_Request $request) {
		parent::preProcess($request, false);
		$this->preProcessSettings($request);
	}

	public function preProcessSettings(Head_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$selectedMenuId = $request->get('block');
		$fieldId = $request->get('fieldid');

		$settingsModel = Settings_Head_Module_Model::getInstance();
		$menuModels = $settingsModel->getMenus();

		if(!empty($selectedMenuId)) {
			$selectedMenu = Settings_Head_Menu_Model::getInstanceById($selectedMenuId);
		} elseif(!empty($moduleName) && $moduleName != 'Head') {
			$fieldItem = Settings_Head_Index_View::getSelectedFieldFromModule($menuModels,$moduleName);
			if($fieldItem){
				$selectedMenu = Settings_Head_Menu_Model::getInstanceById($fieldItem->get('blockid'));
				$fieldId = $fieldItem->get('fieldid');
			} else {
				reset($menuModels);
				$firstKey = key($menuModels);
				$selectedMenu = $menuModels[$firstKey];
			}
		} else {
			reset($menuModels);
			$firstKey = key($menuModels);
			$selectedMenu = $menuModels[$firstKey];
		}
        
        //Specific change for Head7
        $settingsMenItems = array();
        foreach($menuModels as $menuModel) {
            $menuItems = $menuModel->getMenuItems();
            foreach($menuItems as $menuItem) {
                $settingsMenItems[$menuItem->get('name')] = $menuItem;
            }
        }
        $viewer->assign('SETTINGS_MENU_ITEMS', $settingsMenItems);
        $moduleModel = Head_Module_Model::getInstance($moduleName);
        $this->setModuleInfo($request, $moduleModel);
        $viewer->assign('ACTIVE_BLOCK', array('block' => 'LBL_USER_MANAGEMENT', 
                                              'menu' => 'LBL_USERS'));
        
        $moduleFields = $moduleModel->getFields();
        foreach($moduleFields as $fieldName => $fieldModel){
            $fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
        }
        $viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));
        
		$viewer->assign('SELECTED_FIELDID',$fieldId);
		$viewer->assign('SELECTED_MENU', $selectedMenu);
		$viewer->assign('SETTINGS_MENUS', $menuModels);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('CURRENT_USER_MODEL', $currentUserModel);
		$viewer->view('SettingsMenuStart.tpl', $qualifiedModuleName);
	}

	public function postProcessSettings(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$viewer->view('SettingsMenuEnd.tpl', $qualifiedModuleName);
	}

	public function postProcess(Head_Request $request) {
		$this->postProcessSettings($request);
		parent::postProcess($request);
	}

	public function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('CURRENT_USER_MODEL', $currentUser);
		
		if(isset($_SESSION['filter'])) {
			$activities = ModTracker_Record_Model::getActivities($currentUser->id, $request->getModule(), $_SESSION['filter']);
			$date_activities = Head_Activity_Model::getDateActivities($currentUser->id, $request->getModule(), $_SESSION['filter']);
		} else {
			$activities = ModTracker_Record_Model::getActivities($currentUser->id, $request->getModule());
			$date_activities = Head_Activity_Model::getDateActivities($currentUser->id, $request->getModule());
		}
		unset($_SESSION['filter']);
		
		$viewer->assign('RECORD_COUNT', count($activities));
		$allUsers = Users_Record_Model::getAll(true);
		$viewer->assign('ALLUSERS', $allUsers);
		$viewer->assign('ACTIVITIES', $activities);
		$viewer->assign('ACTIVITY_DATES', $date_activities);
		$viewer->assign('MODULE_NAME', $request->getModule());
		$viewer->assign('SELECTED_USER', isset($_SESSION['selected_id']) ? $_SESSION['selected_id'] : $currentUser->id);
		$viewer->assign('FILTER_TYPE', isset($_SESSION['filtertype']) ? $_SESSION['filtertype'] : 'all');

		$viewer->view('ActivityContents.tpl', $request->getModule());
	}

	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Users.resources.Activity',
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

}
