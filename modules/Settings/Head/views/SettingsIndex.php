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

class Settings_Head_SettingsIndex_View extends Head_Basic_View {

	function __construct() {
		parent::__construct();
	}

	function checkPermission(Head_Request $request) {
		return true;
	}

	public function preProcess (Head_Request $request, $display=true) {
		parent::preProcess($request, false);
		$this->preProcessSettings($request,$display);
	}

	public function preProcessSettings (Head_Request $request ,$display=true) {
		global $current_user;
		$viewer = $this->getViewer($request);

		$parent_module = $request->get('parent');
		$moduleName = $request->getModule();
		$view_name = $request->get('view');
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

		$settingsMenItems = array();
		foreach($menuModels as $menuModel) {
			$menuItems = $menuModel->getMenuItems();
			foreach($menuItems as $menuItem) {
				$settingsMenItems[$menuItem->get('name')] = $menuItem;
			}
		}
		$viewer->assign('SETTINGS_MENU_ITEMS', $settingsMenItems);

		$activeBLock = Settings_Head_Module_Model::getActiveBlockName($request);
		$viewer->assign('ACTIVE_BLOCK', $activeBLock);

		$restrictedModules = array('Head', 'ExtensionStore', 'CustomerPortal', 'Roles', 'ExchangeConnector', 'LoginHistory', 'SharingAccess');

		if(!in_array($moduleName, $restrictedModules)) {
			if($moduleName === 'Users') {
				$moduleModel = Head_Module_Model::getInstance($moduleName);
			}else {
				$moduleModel = Settings_Head_Module_Model::getInstance($qualifiedModuleName);
			}
			$this->setModuleInfo($request, $moduleModel);
		}
		
		if($parent_module == 'Settings' && $moduleName == 'Head' && $view_name == 'Index')
			$viewer->assign('IS_SETTINGS_INDEX_PAGE', 'true');

		$viewer->assign('SELECTED_FIELDID',$fieldId);
		$viewer->assign('SELECTED_MENU', $selectedMenu);
		$viewer->assign('SETTINGS_MENUS', $menuModels);
		$viewer->assign('MODULE', $moduleName);
		
		try {
				$url = "https://www.joforce.com/news.xml";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		    	$string = curl_exec($ch);
		    	curl_close($ch);
				if (!empty($string)) {
					$xml   = simplexml_load_string($string);
					$xmlarray = json_decode(json_encode((array) $xml), true);
					$result= end($xmlarray);						
						if (count($xmlarray['item'][0])==0) {
							$result= end($xmlarray);
						}else{			
							$result= end($result);
						}
				 			
					$viewer->assign('TITLE',$result['title']);
					$viewer->assign('LINK',$result['link']);				
				}		
			} catch (Exception $e) {
				
			}
		
		$user_id = $current_user->id;
                $viewer->assign('SECTION_ARRAY', getSectionList($user_id)); //section names
                $viewer->assign('MAIN_MENU_TAB_IDS', getMainMenuList($user_id)); //main menu
                $viewer->assign('APP_MODULE_ARRAY', getAppModuleList($user_id)); //modules and sections

                $userCurrencyInfo = getCurrencySymbolandCRate($current_user->currency_id);
                $viewer->assign('USER_CURRENCY_SYMBOL', $userCurrencyInfo['symbol']);
                //Get User Notifications
                $viewer->assign('NOTIFICATONS_COUNT', getUnseenNotificationCount($user_id));
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Head_Request $request) {
		return 'SettingsMenuStart.tpl';
	}

	public function postProcessSettings (Head_Request $request) {

		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$viewer->view('SettingsMenuEnd.tpl', $qualifiedModuleName);
	}

	public function postProcess (Head_Request $request) {
		$this->postProcessSettings($request);
		parent::postProcess($request);
	}

	public function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$usersCount = Users_Record_Model::getCount(true);
		$activeWorkFlows = Settings_Workflows_Module_Model::getActiveWorkflowCount();
		$settings_menus = Settings_Head_Module_Model::getSettingsMenuListForNonAdmin();
		$icons_array = self::getNonAdminMenuIcons();
		$viewer->assign('ICONS_ARRAY', $icons_array);
		$viewer->assign('MODULE',$qualifiedModuleName);
		$viewer->view('SettingsIndex.tpl', $qualifiedModuleName);
	}

	public function getNonAdminMenuIcons() {
		return array( 	'My Preferences' => 'fa fa-user',
				'Calendar Settings' =>  'fa fa-calendar-check-o',
				'LBL_MY_TAGS' => 'fa fa-tags',
		            	'LBL_MENU_MANAGEMENT' => 'fa fa-bars',
				'Notifications' => 'fa fa-bell',
				'LBL_GOOGLE' => 'fa fa-google'
			    );
	}
	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			//'modules.Head.resources.Head',
			'modules.Settings.Head.resources.Head',
			'modules.Settings.Head.resources.Edit',
			"modules.Settings.$moduleName.resources.$moduleName",
			'modules.Settings.Head.resources.Index',
			"modules.Settings.$moduleName.resources.Index",
			"~layouts/lib/jquery/Lightweight-jQuery-In-page-Filtering-Plugin-instaFilta/instafilta.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public static function getSelectedFieldFromModule($menuModels,$moduleName) {
		if($menuModels) {
			foreach($menuModels  as $menuModel) {
				$menuItems = $menuModel->getMenuItems();
				foreach($menuItems as $item) {
					$linkTo = $item->getUrl();
					if(stripos($linkTo, '&module='.$moduleName) !== false || stripos($linkTo, '?module='.$moduleName) !== false) {
						return $item;
					}
				}
			}
		}
		return false;
	}


	/**
	 * Setting module related Information to $viewer (for Head7)
	 * @param type $request
	 * @param type $moduleModel
	 */
	public function setModuleInfo($request, $moduleModel){
		$fieldsInfo = array();
		$basicLinks = array();
		$viewer = $this->getViewer($request);

		if(method_exists($moduleModel, 'getFields')) {
			$moduleFields = $moduleModel->getFields();
			foreach($moduleFields as $fieldName => $fieldModel){
				$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
			}
			$viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));
		}

		if(method_exists($moduleModel, 'getModuleBasicLinks')) {
			$moduleBasicLinks = $moduleModel->getModuleBasicLinks();
			foreach($moduleBasicLinks as $basicLink){
				$basicLinks[] = Head_Link_Model::getInstanceFromValues($basicLink);
			}
			$viewer->assign('MODULE_BASIC_ACTIONS', $basicLinks);
		}
	}
}
