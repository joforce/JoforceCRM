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

class Settings_LanguageEditor_Module_Model extends Settings_Head_Module_Model {

        var $name = 'LanguageEditor';

        /**
         * Function to get all module names
         **/
	public static function getAllModuleNames(){
		global $adb;
		$module_array = [];
		$query = "SELECT name FROM jo_tab";
		$runquery = $adb->pquery($query, array());
		while($fetch_value = $adb->fetch_array($runquery)){
			$module_name = $fetch_value['name'];
			$module_array[$module_name] = $module_name;
		}
		
		return $module_array;
	}
	
	/**
	 * Function to get all settings field names
	 **/
	public static function getAllSettingsFieldNames() {
		$files_array = [];
		foreach(glob('languages/en_us/Settings/*.*') as $filename){
		     array_push($files_array, basename($filename, '.php'));
 		}
	
		$array = [];
		foreach($files_array as $file)
		{
			array_push( $array, vtranslate($file, $file) );
		}

                return $array;
	}
}
