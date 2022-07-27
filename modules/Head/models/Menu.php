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

/**
 * Head Menu Model Class
 */
class Head_Menu_Model extends Head_Module_Model {

	/**
	 * Static Function to get all the accessible menu models with/without ordering them by sequence
	 * @param <Boolean> $sequenced - true/false
	 * @return <Array> - List of Head_Menu_Model instances
	 */
	public static function getAll($sequenced = false) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$userPrivModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$restrictedModulesList = array('Emails','ModComments', 'Integration', 'PBXManager', 'Dashboard', 'Home');

		$allModules = parent::getAll(array('0','2'));
		$menuModels = array();
		$moduleSeqs = Array();
		$moduleNonSeqs = Array();
		foreach($allModules as $module){
			if($module->get('tabsequence') != -1){
				$moduleSeqs[$module->get('tabsequence')] = $module;
			}else {
				$moduleNonSeqs[] = $module;
			}
		}
		ksort($moduleSeqs);
		$modules = array_merge($moduleSeqs, $moduleNonSeqs);

		foreach($modules as $module) {
			if (($userPrivModel->isAdminUser() ||
					$userPrivModel->hasGlobalReadPermission() ||
					$userPrivModel->hasModulePermission($module->getId()))& !in_array($module->getName(), $restrictedModulesList) && $module->get('parent') != '') {
					$menuModels[$module->getName()] = $module;

			}
		}

		return $menuModels;
	}

	/**
	 * Static Function to get all the accessible module model for Quick Create
	 * @return <Array> - List of Head_Menu_Model instances
	 */
	public static function getAllForQuickCreate() {
		$userPrivModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$restrictedModulesList = array('Emails', 'ModComments', 'Integration', 'PBXManager', 'Dashboard', 'Home');
		$allModules = parent::getAll(array('0', '2'));
		$menuModels = array();

		foreach ($allModules as $module) {
			if (($userPrivModel->isAdminUser() || $userPrivModel->hasGlobalReadPermission() || $userPrivModel->hasModulePermission($module->getId())) && !in_array($module->getName(), $restrictedModulesList) && $module->get('parent') != '') {
				$menuModels[$module->getName()] = $module;
			}
		}

		uksort($menuModels, array('Head_MenuStructure_Model', 'sortMenuItemsByProcess'));
		return $menuModels;
	}

}
