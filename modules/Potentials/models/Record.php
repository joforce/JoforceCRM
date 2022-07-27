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
}
