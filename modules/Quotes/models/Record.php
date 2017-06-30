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
 * Quotes Record Model Class
 */
class Quotes_Record_Model extends Inventory_Record_Model {

	public function getCreateInvoiceUrl() {
        global $site_URL;
		$invoiceModuleModel = Vtiger_Module_Model::getInstance('Invoice');

		return $site_URL.$invoiceModuleModel->getName()."/".$invoiceModuleModel->getEditViewName()."/".$this->getId();
	}

	public function getCreateSalesOrderUrl() {
        global $site_URL;
		$salesOrderModuleModel = Vtiger_Module_Model::getInstance('SalesOrder');

		return $site_URL.$salesOrderModuleModel->getName()."/".$salesOrderModuleModel->getEditViewName()."/".$this->getId();
	}

	public function getCreatePurchaseOrderUrl() {
        global $site_URL;
		$purchaseOrderModuleModel = Vtiger_Module_Model::getInstance('PurchaseOrder');
		return $site_URL.$purchaseOrderModuleModel->getName()."/".$purchaseOrderModuleModel->getEditViewName()."/".$this->getId();
	}

	/**
	 * Function to get this record and details as PDF
	 */
	public function getPDF() {
		$recordId = $this->getId();
		$moduleName = $this->getModuleName();

		$controller = new Vtiger_QuotePDFController($moduleName);
		$controller->loadRecord($recordId);

		$fileName = $moduleName.'_'.getModuleSequenceNumber($moduleName, $recordId);
		$controller->Output($fileName.'.pdf', 'D');
	}

}
