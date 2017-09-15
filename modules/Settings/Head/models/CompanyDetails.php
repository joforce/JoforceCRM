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

class Settings_Head_CompanyDetails_Model extends Settings_Head_Module_Model {

	STATIC $logoSupportedFormats = array('jpeg', 'jpg', 'png', 'gif', 'pjpeg', 'x-png');

	var $baseTable = 'jo_organizationdetails';
	var $baseIndex = 'organization_id';
	var $listFields = array('organizationname');
	var $nameFields = array('organizationname');
	var $logoPath = 'test/logo/';

	var $fields = array(
		'organizationname' => 'text',
		'logoname' => 'text',
		'logo' => 'file',
		'address' => 'textarea',
		'city' => 'text',
		'state' => 'text',
		'code'  => 'text',
		'country' => 'text',
		'phone' => 'text',
		'fax' => 'text',
		'website' => 'text',
		'vatid' => 'text' 
	);

	var $companyBasicFields = array(
		'organizationname' => 'text',
		'logoname' => 'text',
		'logo' => 'file',
		'address' => 'textarea',
		'city' => 'text',
		'state' => 'text',
		'code'  => 'text',
		'country' => 'text',
		'phone' => 'text',
		'fax' => 'text',
		'vatid' => 'text'
	);

	var $companySocialLinks = array(
		'website' => 'text',
	);

	/**
	 * Function to get Edit view Url
	 * @return <String> Url
	 */
	public function getEditViewUrl() {
		return 'index.php?module=Head&parent=Settings&view=CompanyDetailsEdit';
	}

	/**
	 * Function to get CompanyDetails Menu item
	 * @return menu item Model
	 */
	public function getMenuItem() {
		$menuItem = Settings_Head_MenuItem_Model::getInstance('LBL_COMPANY_DETAILS');
		return $menuItem;
	}

	/**
	 * Function to get Index view Url
	 * @return <String> URL
	 */
	public function getIndexViewUrl() {
        global $site_URL;
		$menuItem = $this->getMenuItem();
		return $site_URL.'Head/Settings/CompanyDetails/'.$menuItem->get('blockid').'/'.$menuItem->get('fieldid');
	}

	/**
	 * Function to get fields
	 * @return <Array>
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Function to get Logo path to display
	 * @return <String> path
	 */
	public function getLogoPath() {
        global $site_URL;
		$logoPath = $this->logoPath;
		$handler = @opendir($logoPath);
		$logoName = decode_html($this->get('logoname'));
		if ($logoName && $handler) {
			while ($file = readdir($handler)) {
				if($logoName === $file && in_array(str_replace('.', '', strtolower(substr($file, -4))), self::$logoSupportedFormats) && $file != "." && $file!= "..") {
					closedir($handler);
					return $site_URL.$logoPath.$logoName;
				}
			}
		}
		return '';
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
		$db = PearDatabase::getInstance();
		$id = $this->get('id');
		$fieldsList = $this->getFields();
		unset($fieldsList['logo']);
		$tableName = $this->baseTable;

		if ($id) {
			$params = array();

			$query = "UPDATE $tableName SET ";
			foreach ($fieldsList as $fieldName => $fieldType) {
				$query .= " $fieldName = ?, ";
				array_push($params, $this->get($fieldName));
			}
			$query .= " logo = NULL WHERE organization_id = ?";

			array_push($params, $id);
		} else {
			$params = $this->getData();

			$query = "INSERT INTO $tableName (";
			foreach ($fieldsList as $fieldName => $fieldType) {
				$query .= " $fieldName,";
			}
			$query .= " organization_id) VALUES (". generateQuestionMarks($params). ", ?)";

			array_push($params, $db->getUniqueID($this->baseTable));
		}
		$db->pquery($query, $params);

		$companyName = $this->get('organizationname');
		$companyName = preg_replace(array("/>/", "/</", "/&/", "/'/", '/""/', '/gt;/', '/lt;/', '/;/'), '', $companyName);
		$fileContent = file_get_contents('portal/config/config.inc.php');
		$pattern = '/\$companyName[\s]+=([^;]+);/';
		$replacedValue = sprintf("\$%s = '%s';", 'companyName', $companyName);
		$fileContent = preg_replace($pattern, $replacedValue, $fileContent);
		$fp = fopen('portal/config/config.inc.php', 'w');
		fwrite($fp, $fileContent);
		fclose($fp);
		// End
	}

	/**
	 * Function to get the instance of Company details module model
	 * @return <Settings_Head_CompanyDetais_Model> $moduleModel
	 */
	public static function getInstance() {
		$moduleModel = new self();
		$db = PearDatabase::getInstance();

		$result = $db->pquery("SELECT * FROM jo_organizationdetails", array());
		if ($db->num_rows($result) == 1) {
			$moduleModel->setData($db->query_result_rowdata($result));
			$moduleModel->set('id', $moduleModel->get('organization_id'));
		}

		$moduleModel->getFields();
		return $moduleModel;
	}
}
