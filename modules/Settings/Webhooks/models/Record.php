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

class Settings_Webhooks_Record_Model extends Settings_Head_Record_Model {

	/**
	 * Function to get Id of this record instance
	 * @return <Integer> Id
	 */
	public function getId() {
		return $this->get('id');
	}

	/**
	 * Function to get Name of this record instance
	 * @return <String> Name
	 */
	public function getName() {
		return $this->get('name');
	}

	/**
	 * Function to get module of this record instance
	 * @return <Settings_Webhooks_Module_Model> $moduleModel
	 */
	public function getModule() {
		return $this->module;
	}

	/**
	 * Function to set module instance to this record instance
	 * @param <Settings_Webhooks_Module_Model> $moduleModel
	 * @return <Settings_Webhooks_Record_Model> this record
	 */
	public function setModule($moduleModel) {
		$this->module = $moduleModel;
		return $this;
	}

	/**
	 * Function to get Detail view url
	 * @return <String> Url
	 */
	public function getDetailViewUrl() {
        global $site_URL;
		$moduleModel = $this->getModule();
		return $site_URL.$moduleModel->getName()."/".$moduleModel->getParentName()."/Detail/".$this->getId();
	}

	/**
	 * Function to get Edit view url
	 * @return <String> Url
	 */
	public function getEditViewUrl() {
        global $site_URL;
		$moduleModel = $this->getModule();
		return $site_URL.$moduleModel->getName()."/".$moduleModel->getParentName()."/Edit/".$this->getId();
	}

	/**
	 * Function to get Delete url
	 * @return <String> Url
	 */
	public function getDeleteUrl() {
		$moduleModel = $this->getModule();
		return "index.php?module=".$moduleModel->getName()."&parent=".$moduleModel->getParentName()."&action=Delete&record=".$this->getId();
	}

	/**
	 * Function to get record links
	 * @return <Array> list of link models <Head_Link_Model>
	 */
	public function getRecordLinks() {
		$links = array();
		$recordLinks = array(
				array(
						'linktype' => 'LISTVIEWRECORD',
						'linklabel' => 'LBL_EDIT',
						'linkurl' => $this->getEditViewUrl(),
						'linkicon' => 'fa fa-pencil icon-pencil'
				),
				array(
						'linktype' => 'LISTVIEWRECORD',
						'linklabel' => 'LBL_DELETE',
						'linkurl' => "javascript:Settings_Head_List_Js.triggerDelete(event,'".$this->getDeleteUrl()."');",
						'linkicon' => 'fa fa-trash icon-trash'
				)
		);
		foreach($recordLinks as $recordLink) {
			$links[] = Head_Link_Model::getInstanceFromValues($recordLink);
		}

		return $links;
	}

	/**
	 * Function to get Detail view links for this record instance
	 * @return <Array> list of link models <Head_Link_Model>
	 */
	public function getDetailViewLinks() {
		$linkTypes = array('DETAILVIEWBASIC');
		$moduleModel = $this->getModule();
		$recordId = $this->getId();

		$detailViewLinks = array(
				array(
						'linktype' => 'DETAILVIEWBASIC',
						'linklabel' => 'LBL_EDIT',
						'linkurl' => $this->getEditViewUrl(),
						'linkicon' => ''
				),
				array(
						'linktype' => 'DETAILVIEW',
						'linklabel' => 'LBL_DELETE',
						'linkurl' => 'javascript:Settings_Webhooks_Detail_Js.deleteRecord("'.$this->getDeleteUrl().'")',
						'linkicon' => ''
				)
		);

		foreach ($detailViewLinks as $detailViewLink) {
			$linkModelList['DETAILVIEWBASIC'][] = Head_Link_Model::getInstanceFromValues($detailViewLink);
		}
		return $linkModelList;
	}

	/**
	 * Function to get List of fields
	 * @param <String> $targetModule
	 * @return <Array> list of Field models <Settings_Webhooks_Field_Model>
	 */
	public function getAllFieldsList($targetModule = false) {
		if (!$targetModule) {
			$targetModule = $this->get('targetmodule');
		}
		$targetModuleAllFieldsList = array();
		$targetModuleModel = Head_Module_Model::getInstance($targetModule);
		$restrictedFields = array('70','52','4','53');
		$blocks = $targetModuleModel->getBlocks();
		foreach ($blocks as $blockLabel => $blockModel) {
			$fieldModelsList = $blockModel->getFields();
			$webhookFieldList = array();
			foreach ($fieldModelsList as $fieldName => $fieldModel) {
				if (in_array($fieldModel->get('uitype'), $restrictedFields) || !$fieldModel->isViewable()) {
					continue;
				}
				if($fieldModel->isEditable()){
					$webhookFieldInstnace = Settings_Webhooks_ModuleField_Model::getInstanceFromFieldObject($fieldModel);
					if ($fieldModel->getDefaultFieldValue()) {
						$webhookFieldInstnace->set('fieldvalue', $fieldModel->getDefaultFieldValue());
					}
					$webhookFieldList[$webhookFieldInstnace->getName()] = $webhookFieldInstnace->get('label');
				}
			}
			$targetModuleAllFieldsList = array_merge($targetModuleAllFieldsList, $webhookFieldList);
		}
		return $targetModuleAllFieldsList;
	}

	/**
	 * Function generate public id for this record instance for first time only
	 * @return <String> id
	 */
	public function generatePublicId() {
		return md5(microtime(true) + $this->getName());
	}

	/**
	 * Function to delete this record
	 */
	public function delete() {
		$this->getModule()->deleteRecord($this);
	}

	/**
	 * Function to set db insert value value for checkbox
	 * @param <string> $fieldName
	 */
	public function setCheckBoxValue($fieldName) {
		if($this->get($fieldName) == "on"){
			$this->set($fieldName,1);
		} else {
			$this->set($fieldName,0);
		}
	}

	/**
	 * Function to save the record
	 */
	public function save() {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$mode = $this->get('mode');

		$db = PearDatabase::getInstance();		
		$this->setCheckBoxValue('enabled');

		//Saving data of source module fields info for this webhook
		$selectedFieldsData = $this->get('fields');
		$sourceModuleModel = Head_Module_Model::getInstance($this->get('targetmodule'));

		$selectedFieldsData = implode(' |##| ', $selectedFieldsData);
		$events = implode(' |##| ', $this->get('events'));

                if ($mode) {
                        $updateQuery = "UPDATE jo_webhooks SET description = ?, url = ?, ownerid = ?, enabled = ?, targetmodule = ?, events = ?, fields = ?  WHERE id = ?";
                        $params = array($this->get('description'), $this->get('url'), $this->get('ownerid'), $this->get('enabled'), $this->get('targetmodule'),  $events, $selectedFieldsData, $this->getId());
                        $db->pquery($updateQuery, $params);
                } else {
						$db->query("INSERT INTO jo_webhooks(name, targetmodule, enabled, description, ownerid, url, events, fields) VALUES('". $this->getName() . "', '" . $this->get('targetmodule') . "','" . $this->get('enabled') . "','" . $this->get('description') . "','" . $this->get('ownerid') . "','" . $this->get('url') . "','$events','$selectedFieldsData')");
                        $this->set('id', $db->getLastInsertID());
                }
	}

	/**
	 * Function check whether duplicate record exist or not with this name
	 * @return <boolean> true/false
	 */
	public function checkDuplicate() {
		$db = PearDatabase::getInstance();

		$query = "SELECT 1 FROM jo_webhooks WHERE name = ?";
		$params = array($this->getName());

		$record = $this->getId();
		if ($record) {
			$query .= " AND id != ?";
			array_push($params, $record);
		}

		$result = $db->pquery($query, $params);
		if ($db->num_rows($result)) {
			return true;
		}
		return false;
	}

	/**
	 * Function to get record instance by using id and moduleName
	 * @param <Integer> $recordId
	 * @param <String> $qualifiedModuleName
	 * @return <Settings_Webhooks_Record_Model> RecordModel
	 */
	static public function getInstanceById($recordId, $qualifiedModuleName) {
		$db = PearDatabase::getInstance();

		$result = $db->pquery("SELECT * FROM jo_webhooks WHERE id = ?", array($recordId));
		if ($db->num_rows($result)) {
			$recordModelClass = Head_Loader::getComponentClassName('Model', 'Record', $qualifiedModuleName);
			$moduleModel = Settings_Head_Module_Model::getInstance($qualifiedModuleName);
			$rowData = $db->query_result_rowdata($result, 0);
			$recordModel = new $recordModelClass();
			$recordModel->setData($rowData)->setModule($moduleModel);
			return $recordModel;
		}
		return false;
	}

	/**
	 * Function to get clean record instance by using moduleName
	 * @param <String> $moduleName
	 * @return <Settings_Head_Module_Model>
	 */
	static public function getCleanInstance($moduleName) {
		$recordModel = new self();
		$moduleModel = Settings_Head_Module_Model::getInstance($moduleName);
		return $recordModel->setModule($moduleModel);
	}

	/**
	 * Function to check whether field is custom or not
	 * @param <String> $fieldName
	 * @return <boolean> true/false
	 */
	static function isCustomField($fieldName) {
		if (substr($fieldName, 0, 3) === "cf_") {
			return true;
		}
		return false;
	}

	public function getDisplayValue($key) {
		$fields = $this->getModule()->getFields();
		$fieldModel = $fields[$key];
		return $fieldModel->getDisplayValue($this->get($key));
	}

	/**
	 * Function to check whether the captcha is enabled or not
	 * @return <boolean> true/false
	 */
	public function isCaptchaEnabled() { 
		if ($this->get('captcha') == '1') {
			return true;
		} else {
			return false;
		}
	}
}
