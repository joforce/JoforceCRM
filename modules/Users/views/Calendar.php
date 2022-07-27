<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/
class Users_Calendar_View extends Head_Detail_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('calendarSettingsEdit');
		$this->exposeMethod('calendarSettingsDetail');
	}
	
	
	public function checkPermission(Head_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$record = $request->get('record');

		if($currentUserModel->isAdminUser() == true || $currentUserModel->get('id') == $record) {
			return true;
		} else {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	/**
	 * Function to returns the preProcess Template Name
	 * @param <type> $request
	 * @return <String>
	 */
	public function preProcessTplName(Head_Request $request) {
		return 'PreferenceDetailViewPreProcess.tpl';
	}

	public function preProcess(Head_Request $request, $display=true) {
		global $current_user;
if($this->checkPermission($request)) {
			$qualifiedModuleName = $request->getModule(false);
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$recordId = $request->get('record');
			$moduleName = $request->getModule();
			$detailViewModel = Head_DetailView_Model::getInstance($moduleName, $recordId);
			$recordModel = $detailViewModel->getRecord();

			$detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);
			$detailViewLinks = $detailViewModel->getDetailViewLinks($detailViewLinkParams);

			$viewer = $this->getViewer($request);
			$viewer->assign('RECORD', $recordModel);

			$viewer->assign('MODULE_MODEL', $detailViewModel->getModule());
			$viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);
			$viewer->assign('MODULE_BASIC_ACTIONS', array());

			$viewer->assign('IS_EDITABLE', $detailViewModel->getRecord()->isEditable($moduleName));
			$viewer->assign('IS_DELETABLE', $detailViewModel->getRecord()->isDeletable($moduleName));

			$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
			$linkModels = $detailViewModel->getSideBarLinks($linkParams);
			$viewer->assign('QUICK_LINKS', $linkModels);
			$viewer->assign('PAGETITLE', $this->getPageTitle($request));
			$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
			$viewer->assign('STYLES',$this->getHeaderCss($request));
			$viewer->assign('LANGUAGE_STRINGS', $this->getJSLanguageStrings($request));
			$viewer->assign('SEARCHABLE_MODULES', Head_Module_Model::getSearchableModules());

			$menuModelsList = Head_Menu_Model::getAll(true);
			$selectedModule = $request->getModule();
			$menuStructure = Head_MenuStructure_Model::getInstanceFromMenuList($menuModelsList, $selectedModule);

			// Order by pre-defined automation process for QuickCreate.
			uksort($menuModelsList, array('Head_MenuStructure_Model', 'sortMenuItemsByProcess'));

			$companyDetails = Head_CompanyDetails_Model::getInstanceById();
			$companyLogo = $companyDetails->getLogo();

			$viewer->assign('CURRENTDATE', date('Y-n-j'));
			$viewer->assign('MODULE', $selectedModule);
			$viewer->assign('PARENT_MODULE', $request->get('parent'));
            $viewer->assign('VIEW', $request->get('view'));
			$viewer->assign('MENUS', $menuModelsList);
            $viewer->assign('QUICK_CREATE_MODULES', Head_Menu_Model::getAllForQuickCreate());
			$viewer->assign('MENU_STRUCTURE', $menuStructure);
			$viewer->assign('MENU_SELECTED_MODULENAME', $selectedModule);
			$viewer->assign('MENU_TOPITEMS_LIMIT', $menuStructure->getLimit());
			$viewer->assign('COMPANY_LOGO',$companyLogo);
			$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

			$homeModuleModel = Head_Module_Model::getInstance('Home');
			$viewer->assign('HOME_MODULE_MODEL', $homeModuleModel);
			$viewer->assign('HEADER_LINKS',$this->getHeaderLinks());
			$viewer->assign('ANNOUNCEMENT', $this->getAnnouncement());
			$viewer->assign('CURRENT_VIEW', $request->get('view'));
			$viewer->assign('SKIN_PATH', Head_Theme::getCurrentUserThemePath());
			$viewer->assign('LANGUAGE', $currentUser->get('language'));
			$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
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
			$eventsModuleModel = Head_Module_Model::getInstance('Events');
			$eventFields = array('defaulteventstatus' => 'eventstatus', 'defaultactivitytype' => 'activitytype');
			foreach($eventFields as $userField => $eventField) {
				$fieldsInfo[$userField]['picklistvalues'] = $eventsModuleModel->getField($eventField)->getPicklistValues();
			}
			$viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));

			$activeBLock = Settings_Head_Module_Model::getActiveBlockName($request);
			$viewer->assign('ACTIVE_BLOCK', $activeBLock);
			$user_id = $current_user->id;
			$viewer->assign('SECTION_ARRAY', getSectionList($user_id)); //section names
                    	$viewer->assign('MAIN_MENU_TAB_IDS', getMainMenuList($user_id)); //main menu
			$viewer->assign('APP_MODULE_ARRAY', getAppModuleList($user_id)); //modules and sections

			$userCurrencyInfo = getCurrencySymbolandCRate($currentUser->get('currency_id'));
			$viewer->assign('USER_CURRENCY_SYMBOL', $userCurrencyInfo['symbol']);
			//Get User Notifications
			$viewer->assign('NOTIFICATONS_COUNT', getUnseenNotificationCount($user_id));

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
		$mode = $request->getMode();
		if($mode == 'Edit'){
			$this->invokeExposedMethod('calendarSettingsEdit',$request);
		} else {
			$this->invokeExposedMethod('calendarSettingsDetail',$request);
		}
	}
	
	public function initializeView($viewer,Head_Request $request){
		$recordId = $request->get('record');
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$module = $request->getModule();
		$detailViewModel = Head_DetailView_Model::getInstance('Users', $currentUserModel->id);
		$userRecordStructure = Head_RecordStructure_Model::getInstanceFromRecordModel($detailViewModel->getRecord(), Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$recordStructure = $userRecordStructure->getStructure();
//		$allUsers = Users_Record_Model::getAll(true);
		$sharedUsers = Calendar_Module_Model::getCaledarSharedUsers($currentUserModel->id);
		$sharedType = Calendar_Module_Model::getSharedType($currentUserModel->id);
		$dayStartPicklistValues = Users_Record_Model::getDayStartsPicklistValues($recordStructure);
        $hourFormatFeildModel = $recordStructure['LBL_CALENDAR_SETTINGS']['hour_format'];
		$calendarSettings['LBL_CALENDAR_SETTINGS'] = $recordStructure['LBL_CALENDAR_SETTINGS'];
		$recordModel = $detailViewModel->getRecord();
		$moduleModel = $recordModel->getModule();
		$viewer->assign('IS_AJAX_ENABLED', $recordModel->isEditable());
		$blocksList = $moduleModel->getBlocks();
		$viewer->assign('CURRENTUSER_MODEL',$currentUserModel);
		$viewer->assign('BLOCK_LIST',$blocksList);
		$viewer->assign('SHAREDUSERS', $sharedUsers);
		$viewer->assign("DAY_STARTS", Zend_Json::encode($dayStartPicklistValues));
//		$viewer->assign('ALL_USERS',$allUsers);
		$viewer->assign('RECORD_STRUCTURE',$calendarSettings);
		$viewer->assign('MODULE',$module);
		$viewer->assign('MODULE_NAME',$module);
		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('RECORD_ID', $recordId);
		
		$viewer->assign('SHAREDTYPE', $sharedType);
        $viewer->assign('HOUR_FORMAT_VALUE', $hourFormatFeildModel->get('fieldvalue'));
	}
	
	
	public function calendarSettingsEdit(Head_Request $request){
		$viewer = $this->getViewer($request);
		$this->initializeView($viewer,$request);
		$viewer->view('CalendarSettingsEditView.tpl', $request->getModule());
	}
	
	
	
	public function calendarSettingsDetail(Head_Request $request){
		$viewer = $this->getViewer($request);
		$this->initializeView($viewer,$request);
		$viewer->view('CalendarSettingsDetailView.tpl', $request->getModule());
	}

    public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();
        $moduleDetailFile = 'modules.'.$moduleName.'.resources.PreferenceDetail';
        unset($headerScriptInstances[$moduleDetailFile]);

		$jsFileNames = array(
            "modules.Users.resources.Detail",
			"modules.Users.resources.Users",
            'modules.'.$moduleName.'.resources.PreferenceDetail',
			'modules.'.$moduleName.'.resources.Calendar',
			'modules.'.$moduleName.'.resources.PreferenceEdit',
             'modules.Settings.Head.resources.Index',
			"~layouts/lib/jquery/Lightweight-jQuery-In-page-Filtering-Plugin-instaFilta/instafilta.min.js"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/*
	 * HTTP REFERER check was removed in Parent class Head_Detail_View, because of 
	 * CRM Detail View URL option in Workflow Send Mail task.
	 * But here http referer check is required.
	 */
	public function validateRequest(Head_Request $request) {
		$request->validateReadAccess();
	}

}
