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

Class Users_PreferenceEdit_View extends Head_Edit_View {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$record = $request->get('record');
		if (!empty($record) && $currentUserModel->get('id') != $record) {
			$recordModel = Head_Record_Model::getInstanceById($record, $moduleName);
			if($recordModel->get('status') != 'Active') {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
		if(($currentUserModel->isAdminUser() == true || $currentUserModel->get('id') == $record)) {
			return true;
		} else {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	function preProcessTplName(Head_Request $request) {
		return 'UserEditViewPreProcess.tpl';
	}


	public function preProcess (Head_Request $request, $display=true) {
		if($this->checkPermission($request)) {
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$viewer = $this->getViewer($request);
			$user_id = $currentUser->id;
	                $viewer->assign('SECTION_ARRAY', getSectionList($user_id)); //section names
        	        $viewer->assign('MAIN_MENU_TAB_IDS', getMainMenuList($user_id)); //main menu
			$viewer->assign('APP_MODULE_ARRAY', getAppModuleList($user_id)); //modules and sections

	                $userCurrencyInfo = getCurrencySymbolandCRate($currentUser->currency_id);
	                $viewer->assign('USER_CURRENCY_SYMBOL', $userCurrencyInfo['symbol']);
        	        //Get User Notifications
                	$viewer->assign('NOTIFICATONS_COUNT', getUnseenNotificationCount($user_id));

			$qualifiedModuleName = $request->getModule(false);
			$menuModelsList = Head_Menu_Model::getAll(true);
			$selectedModule = $request->getModule();
			$moduleName = $selectedModule;
			$menuStructure = Head_MenuStructure_Model::getInstanceFromMenuList($menuModelsList, $selectedModule);

			// Order by pre-defined automation process for QuickCreate.
			uksort($menuModelsList, array('Head_MenuStructure_Model', 'sortMenuItemsByProcess'));

			$companyDetails = Head_CompanyDetails_Model::getInstanceById();
			$companyLogo = $companyDetails->getLogo();

			$viewer->assign('CURRENTDATE', date('Y-n-j'));
			$viewer->assign('MODULE', $selectedModule);
			$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
			$viewer->assign('PARENT_MODULE', $request->get('parent'));
			$viewer->assign('VIEW', $request->get('view'));
			$viewer->assign('MENUS', $menuModelsList);
			$viewer->assign('QUICK_CREATE_MODULES', Head_Menu_Model::getAllForQuickCreate());
			$viewer->assign('MENU_STRUCTURE', $menuStructure);
			$viewer->assign('MENU_SELECTED_MODULENAME', $selectedModule);
			$viewer->assign('MENU_TOPITEMS_LIMIT', $menuStructure->getLimit());
			$viewer->assign('COMPANY_LOGO',$companyLogo);
			$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
			$viewer->assign('SEARCHABLE_MODULES', Head_Module_Model::getSearchableModules());

			$homeModuleModel = Head_Module_Model::getInstance('Home');
			$viewer->assign('HOME_MODULE_MODEL', $homeModuleModel);
			$viewer->assign('HEADER_LINKS',$this->getHeaderLinks());
			$viewer->assign('ANNOUNCEMENT', $this->getAnnouncement());

			$viewer->assign('CURRENT_VIEW', $request->get('view'));
			$viewer->assign('PAGETITLE', $this->getPageTitle($request));
			$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
			$viewer->assign('STYLES',$this->getHeaderCss($request));
			$viewer->assign('LANGUAGE_STRINGS', $this->getJSLanguageStrings($request));
			$viewer->assign('SKIN_PATH', Head_Theme::getCurrentUserThemePath());
			$viewer->assign('IS_PREFERENCE', true);
			$viewer->assign('CURRENT_USER_MODEL', $currentUser);
			$viewer->assign('LANGUAGE', $currentUser->get('language'));
			$viewer->assign('COMPANY_DETAILS_SETTINGS',new Settings_Head_CompanyDetails_Model());

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

			$settingsMenItems = array();
			foreach($menuModels as $menuModel) {
				$menuItems = $menuModel->getMenuItems();
				foreach($menuItems as $menuItem) {
					$settingsMenItems[$menuItem->get('name')] = $menuItem;
				}
			}
			$viewer->assign('SETTINGS_MENU_ITEMS', $settingsMenItems);

			$moduleModel = Head_Module_Model::getInstance($moduleName);

			$moduleFields = $moduleModel->getFields();
			foreach($moduleFields as $fieldName => $fieldModel){
				$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
			}
			$viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));

			if($display) {
				$this->preProcessDisplay($request);
			}
		}
	}

	protected function preProcessDisplay(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$viewer->view($this->preProcessTplName($request), $request->getModule());
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		if (!empty($recordId)) {
			$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
		} else {
			$recordModel = Head_Record_Model::getCleanInstance($moduleName);
		}

		$recordStructureInstance = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$dayStartPicklistValues = Users_Record_Model::getDayStartsPicklistValues($recordStructureInstance->getStructure());

		$viewer = $this->getViewer($request);	
		$viewer->assign("DAY_STARTS", Zend_Json::encode($dayStartPicklistValues));
		$viewer->assign('TAG_CLOUD', $recordModel->getTagCloudStatus());
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

		parent::process($request);
	}

	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();
		$moduleDetailFile = 'modules.'.$moduleName.'.resources.PreferenceEdit';
		unset($headerScriptInstances[$moduleDetailFile]);

		$jsFileNames = array(
			"modules.Users.resources.Edit",
			'modules.'.$moduleName.'.resources.PreferenceEdit',
			"modules.Head.resources.CkEditor",
			'modules.Settings.Head.resources.Index',
			"~layouts/lib/jquery/Lightweight-jQuery-In-page-Filtering-Plugin-instaFilta/instafilta.min.js"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
