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

/*
 * Settings Module Model Class
 */
class Settings_Head_Module_Model extends Head_Base_Model {

	var $baseTable = 'jo_settings_field';
	var $baseIndex = 'fieldid';
	var $listFields = array('name' => 'Name', 'description' => 'Description');
	var $nameFields = array('name');
	var $name = 'Head';

	public function getName($includeParentIfExists = false) {
		if($includeParentIfExists) {
			return  $this->getParentName() .':'. $this->name;
		}
		return $this->name;
	}

	public function getParentName() {
		return 'Settings';
	}

	public function getBaseTable() {
		return $this->baseTable;
	}

	public function getBaseIndex() {
		return $this->baseIndex;
	}

	public function setListFields($fieldNames) {
		$this->listFields = $fieldNames;
		return $this;
	}

	public function getListFields() {
		if(!$this->listFieldModels) {
			$fields = $this->listFields;
			$fieldObjects = array();
			foreach($fields as $fieldName => $fieldLabel) {
				$fieldObjects[$fieldName] = new Head_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel));
			}
			$this->listFieldModels = $fieldObjects;
		}
		return $this->listFieldModels;
	}

	/**
	 * Function to get name fields of this module
	 * @return <Array> list field names
	 */
	public function getNameFields() {
		return $this->nameFields;
	}

	/**
	 * Function to get field using field name
	 * @param <String> $fieldName
	 * @return <Field_Model>
	 */
	public function getField($fieldName) {
		return new Head_Base_Model(array('name' => $fieldName, 'label' => $fieldName));
	}

	public function hasCreatePermissions() {
		return true;
	}

	/**
	 * Function to get all the Settings menus
	 * @return <Array> - List of Settings_Head_Menu_Model instances
	 */
	public function getMenus() {
		return Settings_Head_Menu_Model::getAll();
	}

	/**
	 * Function to get all the Settings menu items for the given menu
	 * @return <Array> - List of Settings_Head_MenuItem_Model instances
	 */
	public function getMenuItems($menu=false) {
		$menuModel = false;
		if($menu) {
			$menuModel = Settings_Head_Menu_Model::getInstance($menu);
		}
		return Settings_Head_MenuItem_Model::getAll($menuModel);
	}

	public function isPagingSupported(){
		return true;
	}

	/**
	 * Function to get the instance of Settings module model
	 * @return Settings_Head_Module_Model instance
	 */
	public static function getInstance($name='Settings:Head') {
		$modelClassName = Head_Loader::getComponentClassName('Model', 'Module', $name);
		return new $modelClassName();
	}

	/**
	 * Function to get Index view Url
	 * @return <String> URL
	 */
	public function getIndexViewUrl() {
		return 'index.php?module='.$this->getName().'&parent='.$this->getParentName().'&view=Index';
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	function getUtilityActionsNames() {
		return array();
	}

	/** 
	 * Fucntion to get the settings menu item
	 * @return <array> $settingsMenItems
	 */
	static function getSettingsMenuItem() {
		$settingsModel = Settings_Head_Module_Model::getInstance();
		$menuModels = $settingsModel->getMenus();

		//Specific change for Head7
		$settingsMenItems = array();
		foreach($menuModels as $menuModel) {
			$menuItems = $menuModel->getMenuItems();
			foreach($menuItems as $menuItem) {
				$settingsMenItems[$menuItem->get('name')] = $menuItem;
			}
		}

		return $settingsMenItems;
	}

	static function getActiveBlockName($request) {
		$finalResult = array();
		$view = $request->get('view');
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$whereCondition .= "linkto LIKE '%$moduleName%' AND ( linkto LIKE '%Settings%' OR linkto LIKE '%parent=Settings%' OR linkto LIKE '%parenttab=Settings%')";
		$db = PearDatabase::getInstance();
		$query = "SELECT jo_settings_blocks.label AS blockname, jo_settings_field.name AS menu FROM jo_settings_blocks
					INNER JOIN jo_settings_field ON jo_settings_field.blockid=jo_settings_blocks.blockid
					WHERE $whereCondition";
		$result = $db->pquery($query, array());
		$numOfRows = $db->num_rows($result);
		if ($numOfRows == 1) {
			$finalResult = array(	'block' => $db->query_result($result, 0, 'blockname'),
									'menu'	=> $db->query_result($result, 0, 'menu'));
		} elseif ($numOfRows > 1) {
			$result = $db->pquery("$query AND ( linkto LIKE '%view=$view%' OR linkto LIKE '%$view%')", array());
			$numOfRows = $db->num_rows($result);
			if ($numOfRows == 1) {
				$finalResult = array(	'block' => $db->query_result($result, 0, 'blockname'),
										'menu'	=> $db->query_result($result, 0, 'menu'));
			}
		}

		if (!$finalResult) {
			if ($moduleName === 'Users') {
				$moduleModel = Head_Module_Model::getInstance($moduleName);
			} else {
				$moduleModel = Settings_Head_Module_Model::getInstance($qualifiedModuleName);
			}
			//$finalResult = $moduleModel->getSettingsActiveBlock($view);
		}
		return $finalResult;
	}

	public function getSettingsActiveBlock($viewName) {
		$blocksList = array('OutgoingServerEdit' => array('block' => 'LBL_CONFIGURATION', 'menu' => 'LBL_MAIL_SERVER_SETTINGS'));
		return $blocksList[$viewName];
	}

	static function getSettingsMenuListForNonAdmin() {
        global $site_URL;
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$extension_blockid = Head_Deprecated::getSettingsBlockId('LBL_EXTENSIONS');
		$google_link_field_id = getSettingsFieldId($extension_blockid, 'LBL_GOOGLE');
		$settingsMenuList = array('LBL_MY_PREFERENCES'	=> array('My Preferences'	=> '',
									 'Calendar Settings'	=> '',
									 'LBL_MY_TAGS'		=> $site_URL.'Tags/Settings/List',
									 'LBL_MENU_MANAGEMENT' 	=> $site_URL.'MenuManager/Settings/Index',
									 'Notifications'	=> $site_URL.'Notifications/Settings/Index'),
					'LBL_EXTENSIONS'	=> array('LBL_GOOGLE'	=> $site_URL."Contacts/Settings/Extension/Google/Index/settings/$extension_blockid/$google_link_field_id")
					);
		if(!modlib_isModuleActive('Google')) {
			unset($settingsMenuList['LBL_EXTENSIONS']['LBL_GOOGLE']);
		}

		return $settingsMenuList;
	}

}
