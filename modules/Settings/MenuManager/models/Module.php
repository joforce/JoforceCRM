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

class Settings_MenuManager_Module_Model extends Settings_Head_Module_Model {

	var $name = 'MenuManager';

	/**
	 * Function to save the menu structure
	 */
	public function saveMenuStruncture() {
		$db = PearDatabase::getInstance();
		$selectedModulesList = $this->get('selectedModulesList');

		$updateQuery = "UPDATE jo_tab SET tabsequence = CASE tabid ";

		foreach ($selectedModulesList as $sequence => $tabId) {
			$updateQuery .= " WHEN $tabId THEN $sequence ";
		}
		$updateQuery .= "ELSE -1 END";

		$db->pquery($updateQuery, array());
	}

	/**
	 * Function to get all the modules which are hidden for an app
	 * @param <string> $appName
	 * @return <array> $modules
	 */
	public static function getHiddenModulesForApp($appName) {
		global $current_user, $adb;
		$module_name_array = getPermittedModuleNames();
		$tabids =[];

		$zero_entity_module_array = [];
	        $getZeroEntityModuleId = $adb->pquery('select name from jo_tab where isentitytype = ? and name != ?', array(0, "Dashboard"));
        	while($fetchValues =$adb->fetch_array($getZeroEntityModuleId))
                {
                        array_push($zero_entity_module_array, $fetchValues['name']);
                }


		$user_id = $current_user->id;
		foreach($module_name_array as $module_name)
		{
			$id = getTabid($module_name);
			array_push($tabids, $id);
		}
		$file_name = "storage/menu/module_apps_".$user_id.".php";
                        if(file_exists($file_name))
                        {
                        chmod($file_name, 0777);
                        require($file_name);
                        }
                        else
                        {
                        require("storage/menu/default_module_apps.php");
                        }
                $app_array = $app_menu_array[$appName];
		foreach($tabids as $key => $tabid)
		{
			if (in_array($tabid, $app_array))
			{
			unset($tabids[$key]);
			}
		}
		
		$modules = [];
		foreach($tabids as $tabid)
		{
			$moduleName = getTabModuleName($tabid);
                        $modules[$moduleName] = $moduleName;
		}
	
		foreach($zero_entity_module_array as $zero_entity_module)
		{
			if(in_array($zero_entity_module, $modules))
			{
				unset($modules[$zero_entity_module]);
			}
		}
	
		$not_list_view_modules = array( 'ModComments', 'ModTracker' );
		foreach($not_list_view_modules as $module_name)
			unset($modules[$module_name]);
		
		return $modules;
	}

	public static function getAllVisibleModules() {
		global $current_user;
		$user_id = $current_user->id;
		$app_menu_array = getAppModuleList($user_id);
		$module_object_array = array();
		
		foreach($app_menu_array as $app_name => $tab_array)
		{
			foreach($tab_array as $tabid)
			{
			$module_name = getTabModuleName($tabid);
			$module_instance = Head_Module_Model::getInstance($module_name);
			$module_object_array[$app_name][$module_name] = $module_instance;
			}
		}
		return $module_object_array;
	}
	
	// Have to rewrite back
/*	public static function addModuleToApp($moduleName, $parent) {
		$db = PearDatabase::getInstance();
		$parent = strtoupper($parent);
		$oldToNewAppMapping = Head_MenuStructure_Model::getOldToNewAppMapping();
		if (!empty($oldToNewAppMapping[$parent])) {
			$parent = $oldToNewAppMapping[$parent];
		}

		$ignoredModules = Head_MenuStructure_Model::getIgnoredModules();
		if (!in_array($moduleName, $ignoredModules)) {
			$moduleModel = Head_Module_Model::getInstance($moduleName);
			$result = $db->pquery('SELECT * FROM jo_app2tab WHERE tabid = ? AND appname = ?', array($moduleModel->getId(), $parent));

			$sequence = self::getMaxSequenceForApp($parent) + 1;
			if ($db->num_rows($result) == 0) {
				$db->pquery('INSERT INTO jo_app2tab(tabid,appname,sequence) VALUES(?,?,?)', array($moduleModel->getId(), $parent, $sequence));
			}
		}
	}*/


	/**
	 * Function to get the max sequence number for an app
	 * @param <string> $appName
	 * @return <integer>
	 */
/*	public static function getMaxSequenceForApp($appName) {
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT MAX(sequence) AS maxsequence FROM jo_app2tab WHERE appname=?', array($appName));
		$sequence = 0;
		if ($db->num_rows($result) > 0) {
			$sequence = $db->query_result($result, 0, 'maxsequence');
		}

		return $sequence;
	}
	*/
	/*
  	 * Get ModuleInstance by module id
	 */
	public static function getModuleInstanceById($tabid){
                $moduleName = getTabModuleName($tabid);
                $moduleInstance = Head_Module_Model::getInstance($moduleName);
		return $moduleInstance;
	}
	
	/*
	 * Get all module list
	 */
	public static function getModuleListForMainMenu(){
		global $adb;
		$fetch_array = $adb->pquery('select * from jo_tab');
		$tabids = [];
		while($value_array = $adb->fetch_array($fetch_array))
			{
			array_push($tabids, $value_array['tabid']);
			}
		return $tabids;
	}
	
	/*
	 * checking the current user is admin or not
	 */	
	 public static function isAdminUser() {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
                $adminStatus = $currentUserModel->get('is_admin');
                if ($adminStatus == 'on') {
                        return true;
                }
                return false;
        }

	/*
	 * checking the permission of the module for the current user 	
	 */
	public static function isPermittedModule($tabid) {
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
                if (!$currentUserPriviligesModel->hasModulePermission($tabid)) 
			return false;
		else
			return true;
	}	
	
	/*
	 * returns module names only as string
	 */
	public static function getMainMenuModuleNamesOnly() {
		global $current_user;
		$user_id = $current_user->id;
		$main_menu_list = getMainMenuList($user_id);
		$module_array = [];
		foreach($main_menu_list as $menu_array)
		{
			$module_name = $menu_array['name'];
			array_push($module_array, $module_name);
		}
		$array_string = implode(',', $module_array);
		return $array_string;
	}

	/*
         * returns module names as an array
         */	
	public static function getMainMenuModuleIds() {
		global $current_user;
                $user_id = $current_user->id;
                $main_menu_list = getMainMenuList($user_id);
                $module_names_array = [];
                foreach($main_menu_list as $menu_array)
                {
			$module_name = $menu_array['name'];
                        array_push($module_names_array, $module_name);
                }
                return $module_names_array;
	}
}
