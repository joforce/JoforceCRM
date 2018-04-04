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

class Potentials_Record_Model extends Head_Record_Model {

	function getCreateInvoiceUrl() {
        global $site_URL;
		$invoiceModuleModel = Head_Module_Model::getInstance('Invoice');
		return $site_URL."index.php?module=".$invoiceModuleModel->getName()."&view=".$invoiceModuleModel->getEditViewName().'&sourceRecord='.$this->getId().'&sourceModule='.$this->getModuleName().'&potential_id='.$this->getId().'&account_id='.$this->get('related_to').'&contact_id='.$this->get('contact_id');
	}

	/**
	 * Function returns the url for create event
	 * @return <String>
	 */
	function getCreateEventUrl() {
		$calendarModuleModel = Head_Module_Model::getInstance('Calendar');
		return $calendarModuleModel->getCreateEventRecordUrl().'?parent_id='.$this->getId();
	}

	/**
	 * Function returns the url for create todo
	 * @return <String>
	 */
	function getCreateTaskUrl() {
		$calendarModuleModel = Head_Module_Model::getInstance('Calendar');
		return $calendarModuleModel->getCreateTaskRecordUrl().'?parent_id='.$this->getId();
	}

	/**
	 * Function to get List of Fields which are related from Contacts to Inventyory Record
	 * @return <array>
	 */
	public function getInventoryMappingFields() {
		return array(
				array('parentField'=>'related_to', 'inventoryField'=>'account_id', 'defaultValue'=>''),
				array('parentField'=>'contact_id', 'inventoryField'=>'contact_id', 'defaultValue'=>''),
		);
	}

	/**
	 * Function returns the url for create quote
	 * @return <String>
	 */
	public function getCreateQuoteUrl() {
        global $site_URL;
		$quoteModuleModel = Head_Module_Model::getInstance('Quotes');
		return $site_URL."index.php?module=".$quoteModuleModel->getName()."&view=".$quoteModuleModel->getEditViewName().'&sourceRecord='.$this->getId().'&sourceModule='.$this->getModuleName().'&potential_id='.$this->getId().'&account_id='.$this->get('related_to').'&contact_id='.$this->get('contact_id').'&relationOperation=true';
	}

	/**
	 * Function returns the url for create Sales Order
	 * @return <String>
	 */
	public function getCreateSalesOrderUrl() {
        global $site_URL;
		$salesOrderModuleModel = Head_Module_Model::getInstance('SalesOrder');
		return $site_URL."index.php?module=".$salesOrderModuleModel->getName()."&view=".$salesOrderModuleModel->getEditViewName().'&sourceRecord='.$this->getId().'&sourceModule='.$this->getModuleName().
				'&potential_id='.$this->getId().'&account_id='.$this->get('related_to').'&contact_id='.$this->get('contact_id').
				'&relationOperation=true';
	}

	/**
	 * Function returns the url for converting potential
	 */
	function getConvertPotentialUrl() {
		return 'index.php?module='.$this->getModuleName().'&view=ConvertPotential&record='.$this->getId();
	}

	/**
	 * Function returns the fields required for Potential Convert
	 * @return <Array of Head_Field_Model>
	 */
	function getConvertPotentialFields() {
		$convertFields = array();
		$projectFields = $this->getProjectFieldsForPotentialConvert();
		if(!empty($projectFields)) {
			$convertFields['Project'] = $projectFields;
		}

		return $convertFields;
	}

	/**
	 * Function returns Project fields for Potential Convert
	 * @return Array
	 */
	function getProjectFieldsForPotentialConvert() {
		$projectFields = array();
		$privilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$moduleName = 'Project';

		if(!Users_Privileges_Model::isPermitted($moduleName, 'CreateView')) {
			return;
		}

		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if ($moduleModel->isActive()) {
			$fieldModels = $moduleModel->getFields();
			foreach ($fieldModels as $fieldName => $fieldModel) {
				if($fieldModel->isMandatory() && !in_array($fieldName, array('assigned_user_id', 'potentialid'))) {
					$potentialMappedField = $this->getConvertPotentialMappedField($fieldName, $moduleName);
					if($this->get($potentialMappedField)) {
						$fieldModel->set('fieldvalue', $this->get($potentialMappedField));
					} else {
						$fieldModel->set('fieldvalue', $fieldModel->getDefaultFieldValue());
					} 
					$projectFields[] = $fieldModel;
				}
			}
		}
		return $projectFields;
	}


	/**
	 * Function returns field mapped to Potentials field, used in Potential Convert for settings the field values
	 * @param <String> $fieldName
	 * @return <String>
	 */
	function getConvertPotentialMappedField($fieldName, $moduleName) {
		$mappingFields = $this->get('mappingFields');

		if (!$mappingFields) {
			$db = PearDatabase::getInstance();
			$mappingFields = array();

			$result = $db->pquery('SELECT * FROM jo_convertpotentialmapping', array());
			$numOfRows = $db->num_rows($result);

			$projectInstance = Head_Module_Model::getInstance('Project');
			$projectFieldInstances = $projectInstance->getFieldsById();

			$potentialInstance = Head_Module_Model::getInstance('Potentials');
			$potentialFieldInstances = $potentialInstance->getFieldsById();

			for($i=0; $i<$numOfRows; $i++) {
				$row = $db->query_result_rowdata($result,$i);
				if(empty($row['potentialfid'])) continue;

				$potentialFieldInstance = $potentialFieldInstances[$row['potentialfid']];
				if(!$potentialFieldInstance) continue;

				$potentialFieldName = $potentialFieldInstance->getName();
				$projectFieldInstance = $projectFieldInstances[$row['projectfid']];

				if ($row['projectfid'] && $projectFieldInstance) {
					$mappingFields['Project'][$projectFieldInstance->getName()] = $potentialFieldName;
				}
			}

			$this->set('mappingFields', $mappingFields);
		}
		return $mappingFields[$moduleName][$fieldName];
	}

	/**
	 * Function to check whether the Potential is converted or not
	 * @return True if the Potential is Converted false otherwise.
	 */
	function isPotentialConverted() {
		$db = PearDatabase::getInstance();
		$id = $this->getId();
		$sql = "select converted from  jo_potential where converted = 1 and potentialid=?";
		$result = $db->pquery($sql,array($id));
		$rowCount = $db->num_rows($result);
		if($rowCount > 0){
			return true;
		}
		return false;
	}

}
