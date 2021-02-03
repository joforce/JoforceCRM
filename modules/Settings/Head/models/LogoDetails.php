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

class Settings_Head_LogoDetails_Model extends Settings_Head_Module_Model {

	STATIC $logoSupportedFormats = array('jpeg', 'jpg', 'png', 'gif', 'pjpeg', 'x-png');

	var $baseTable = 'jo_users';
	var $baseIndex = 'id';

	var $logoPath = 'cache/logo/';

	var $fields = array( 
		'imagename' => 'varchar',		
	);

	var $logoBasicFields = array(
		'imagename' => 'varchar',
	); 
	
	/**
	 * Function to get Logo path to display
	 * @return <String> path
	 */
	public function getLogoPath() { 
        global $site_URL;
		$logoPath = $this->logoPath;
		$handler = @opendir($logoPath);
		$logoName = decode_html($this->get('imagename'));
		if ($logoName && $handler) {
			while ($file = readdir($handler)) {
				if($logoName === $file && in_array(str_replace('.', '', strtolower(substr($file, -4))), self::$logoSupportedFormats) && $file != "." && $file!= "..") {
					closedir($handler);
					return $site_URL.$logoPath.$logoName;
				}
			}
		}else{
			return $site_URL.'layouts/resources/Images/JoForce-Logo.png';
		}
		return'';
	}

	/**
	 * Function to save the logoinfo
	 */
	public function saveLogo() {
		$uploadDir = vglobal('root_directory'). '/' .$this->logoPath;
		$logoName = $uploadDir.$_FILES["logo"]["name"];
		move_uploaded_file($_FILES["logo"]["tmp_name"], $logoName);
		copy($logoName, $uploadDir.'application.ico');
	}

	/**
	 * Function to save the Company details
	 */
	public function save() { 
		global $current_user;
		$db = PearDatabase::getInstance();
		$id = $current_user->id;
		$tableName = $this->baseTable; 
		if ($id) { 
			$query = "UPDATE $tableName SET imagename =?";
			$query .= " WHERE id = ?"; 
			$params=array($this->get('imagename'), $id);
		} 
	    $db->pquery($query, $params); 
	
	}

	/**
	 * Function to get the instance of Company details module model
	 * @return <Settings_Head_CompanyDetais_Model> $moduleModel
	 */
	public static function getInstance() { 
		global $current_user;
		$moduleModel = new self();
		$userid =$current_user->id;
		if(empty($userid))$userid =1;
		$db = PearDatabase::getInstance(); 
		$result = $db->pquery("SELECT * FROM jo_users where id =?", array($userid));
		if ($db->num_rows($result) == 1) {
			$moduleModel->setData($db->query_result_rowdata($result));
			$moduleModel->set('id', $moduleModel->get('id'));
		} 
		return $moduleModel;
	}
}