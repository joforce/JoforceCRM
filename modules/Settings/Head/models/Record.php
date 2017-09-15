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
 * Roles Record Model Class
 */
abstract class Settings_Head_Record_Model extends Head_Base_Model {

	abstract function getId();
	abstract function getName();

    /**
	 * Function to get the instance of Settings module model
	 * @return Settings_Head_Module_Model instance
	 */
	 public static function getInstance($name='Settings:Head') {
		$modelClassName  = Head_Loader::getComponentClassName('Model', 'Record', $name);
		 return new $modelClassName();
	 }
    
    
	public function getRecordLinks() {

		$links = array();
		$recordLinks = array();
		foreach ($recordLinks as $recordLink) {
			$links[] = Head_Link_Model::getInstanceFromValues($recordLink);
		}

		return $links;
	}
	
	public function getDisplayValue($key) {
		return $this->get($key);
	}
}
