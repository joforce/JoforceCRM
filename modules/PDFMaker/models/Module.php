<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class PDFMaker_Module_Model extends Head_Module_Model
{

	/**
	 * Function to get Alphabet Search Field 
	 */
	public function getAlphabetSearchField()
	{
		return 'templatename';
	}

	/**
	 * Function to get the url for the Create Record view of the module
	 * @return <String> - url
	 */
	public function getCreateRecordUrl()
	{
		global $site_URL;
		return $site_URL . $this->get('name') . '/view/' . $this->getEditViewName();
	}

	/**
	 * Function to save a given record model of the current module
	 * @param EmailtTemplates_Record_Model $recordModel
	 * @return <integer> template id
	 */

	public function isStarredEnabled()
	{
		return false;
	}

	public function saveRecord($recordModel)
	{
		$db = PearDatabase::getInstance();
		$templateid = $recordModel->getId();
		if (empty($templateid)) {
			$templateid = $db->getUniqueID('jo_pdfmaker');
			$sql = "INSERT INTO jo_pdfmaker(name, module, description, body, header, footer, status, settings, pdfmakerid) VALUES (?,?,?,?,?,?,?,?,?)";
		} else {
			$sql = "UPDATE jo_pdfmaker SET name=?, module=?, description=?, body=?, header=?, footer=?, status=?, settings=? WHERE pdfmakerid = ?";
		}
		$params = array(
			decode_html($recordModel->get('name')), decode_html($recordModel->get('module')),
			decode_html($recordModel->get('description')), $recordModel->get('body'), $recordModel->get('header'), $recordModel->get('footer'), $recordModel->get('status'), $recordModel->get('settings'), $templateid
		);
		$db->pquery($sql, $params);
		return $recordModel->setId($templateid);
	}

	/**
	 * Function to delete the email template
	 * @param type $recordIds
	 */
	public function deleteRecord($recordModel)
	{
		$recordId = $recordModel->getId();
		$db = PearDatabase::getInstance();
		$db->pquery('DELETE FROM jo_pdfmaker WHERE pdfmakerid = ? ', array($recordId));
	}

	/**
	 * Function to delete all the email templates
	 * @param type $recordIds
	 */
	public function deleteAllRecords()
	{
		$db = PearDatabase::getInstance();
		$db->pquery('DELETE FROM jo_pdfmaker', array());
	}

	/**
	 * Function to get template fields from modules
	 * @return <array> template fields
	 */
	public function getAllModuleFields()
	{
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$allModuleList = array('Invoice', 'Quotes', 'SalesOrder', 'PurchaseOrder', 'Products', 'Services', 'Contacts', 'Accounts');
		//$allRelFields = array();
		//$allFields = array(); 
		foreach ($allModuleList as $index => $module) {
			$allFields = array();
			$allRelFields = array();
			if ($module == 'Users') {
				$fieldList = $this->getRelatedModuleFieldList($module, $currentUserModel);
			} else {
				$fieldList = $this->getRelatedFields($module, $currentUserModel);
			}
			foreach ($fieldList as $key => $field) {
				$option = array(vtranslate($field['module'], $field['module']) . ':' . vtranslate($field['fieldlabel'], $field['module']), "$" . strtolower($field['module']) . "-" . $field['columnname'] . "$");
				$allFields[] = $option;
				if (!empty($field['referencelist'])) {
					foreach ($field['referencelist'] as $key => $relField) {
						$relOption = array(vtranslate($field['fieldlabel'], $field['module']) . ':' . '(' . vtranslate($relField['module'], $relField['module']) . ')' . vtranslate($relField['fieldlabel'], $relField['module']), "$" . strtolower($field['module']) . "-" . $field['columnname'] . ":" . $relField['columnname'] . "$");
						$allRelFields[] = $relOption;
					}
				}
			}
			if (is_array($allFields) && is_array($allRelFields)) {
				$allFields = array_merge($allFields, $allRelFields);
				$allRelFields = "";
			}
			$allOptions[$module] = $allFields;
			$allFields = "";
		}
		return $allOptions;
	}

	/**
	 * Function to get module fields
	 * @param type $module
	 * @param type $currentUserModel
	 * @return <arrau>
	 */
	function getRelatedFields($module, $currentUserModel)
	{
		$handler = vtws_getModuleHandlerFromName($module, $currentUserModel);
		$meta = $handler->getMeta();
		$moduleFields = $meta->getModuleFields();

		$returnData = array();
		foreach ($moduleFields as $key => $field) {
			$referencelist = array();
			$relatedField = $field->getReferenceList();
			if ($field->getFieldName() == 'assigned_user_id') {
				$relModule = 'Users';
				$referencelist = $this->getRelatedModuleFieldList($relModule, $currentUserModel);
			}
			if (!empty($relatedField)) {
				foreach ($relatedField as $ind => $relModule) {
					$referencelist = $this->getRelatedModuleFieldList($relModule, $currentUserModel);
				}
			}
			$returnData[] = array('module' => $module, 'fieldname' => $field->getFieldName(), 'columnname' => $field->getColumnName(), 'fieldlabel' => $field->getFieldLabelKey(), 'referencelist' => $referencelist);
		}
		return $returnData;
	}

	/**
	 * Function to get related module fields
	 * @param type $relModule
	 * @param type $user
	 * @return null
	 */

	function getRelatedModuleFieldList($relModule, $user)
	{
		$handler = vtws_getModuleHandlerFromName($relModule, $user);
		$relMeta = $handler->getMeta();
		if (!$relMeta->isModuleEntity()) {
			return null;
		}
		$relModuleFields = $relMeta->getModuleFields();
		$relModuleFieldList = array();
		foreach ($relModuleFields as $relind => $relModuleField) {
			if ($relModule == 'Users') {
				if ($relModuleField->getFieldDataType() == 'string' || $relModuleField->getFieldDataType() == 'email' || $relModuleField->getFieldDataType() == 'phone') {
					$skipFields = array(98, 115, 116, 31, 32);
					if (!in_array($relModuleField->getUIType(), $skipFields) && $relModuleField->getFieldName() != 'asterisk_extension') {
						$relModuleFieldList[] = array('module' => $relModule, 'fieldname' => $relModuleField->getFieldName(), 'columnname' => $relModuleField->getColumnName(), 'fieldlabel' => $relModuleField->getFieldLabelKey());
					}
				}
			} else {
				$relModuleFieldList[] = array('module' => $relModule, 'fieldname' => $relModuleField->getFieldName(), 'columnname' => $relModuleField->getColumnName(), 'fieldlabel' => $relModuleField->getFieldLabelKey());
			}
		}
		return $relModuleFieldList;
	}

	public function getAllModuleList()
	{
		$moduleModels = parent::getEntityModules();
		$restrictedModules = array('Emails', 'ProjectMilestone', 'ModComments', 'Rss', 'Portal', 'Integration', 'PBXManager', 'Dashboard', 'Home');
		foreach ($moduleModels as $key => $moduleModel) {
			if (in_array($moduleModel->getName(), $restrictedModules) || $moduleModel->get('isentitytype') != 1) {
				unset($moduleModels[$key]);
			}

			$modules[] = $moduleModel->name;
		}
		return $modules;
	}


	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Head_Link_Model instances
	 */
	public function getSideBarLinks($linkParams)
	{
		$linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
		$links = Head_Link_Model::getAllByType($this->getId(), $linkTypes, $linkParams);

		$quickLinks = array(
			array(
				'linktype' => 'SIDEBARLINK',
				'linklabel' => 'LBL_RECORDS_LIST',
				'linkurl' => $this->getDefaultUrl(),
				'linkicon' => '',
			),
		);
		foreach ($quickLinks as $quickLink) {
			$links['SIDEBARLINK'][] = Head_Link_Model::getInstanceFromValues($quickLink);
		}
		return $links;
	}

	public function getRecordIds($skipRecords)
	{
		$db = PearDatabase::getInstance();

		$query = 'SELECT pdfmakerid FROM jo_pdfmaker WHERE pdfmakerid NOT IN (' . generateQuestionMarks($skipRecords) . ')';
		$result = $db->pquery($query, $skipRecords);
		$num_rows = $db->num_rows($result);
		$recordIds = array();
		for ($i; $i < $num_rows; $i++) {
			$recordIds[] = $db->query_result($result, $i, 'pdfmakerid');
		}
		return $recordIds;
	}

	/**
	 * Function to get emails related modules
	 * @return <Array> - list of modules 
	 */
	public function getEmailRelatedModules()
	{
		$userPrivModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		$relatedModules = vtws_listtypes(array('email'), Users_Record_Model::getCurrentUserModel());
		$relatedModules = $relatedModules['types'];

		foreach ($relatedModules as $key => $moduleName) {
			if ($moduleName === 'Users') {
				unset($relatedModules[$key]);
			}
		}
		foreach ($relatedModules as $moduleName) {
			$moduleModel = Head_Module_Model::getInstance($moduleName);
			if ($userPrivModel->isAdminUser() || $userPrivModel->hasGlobalReadPermission() || $userPrivModel->hasModulePermission($moduleModel->getId())) {
				$emailRelatedModules[] = $moduleName;
			}
		}
		$emailRelatedModules[] = 'Users';
		return $emailRelatedModules;
	}


	/**
	 * Funxtion to identify if the module supports quick search or not
	 */
	public function isQuickSearchEnabled()
	{
		return false;
	}
}
