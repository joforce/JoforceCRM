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

class PriceBooks_Module_Model extends Head_Module_Model {

	/**
	 * Function returns query for PriceBook-Product relation
	 * @param <Head_Record_Model> $recordModel
	 * @param <Head_Record_Model> $relatedModuleModel
	 * @return <String>
	 */
	function get_pricebook_products($recordModel, $relatedModuleModel) {
		$query = 'SELECT jo_products.productid, jo_products.productname, jo_products.productcode, jo_products.commissionrate,
						jo_products.qty_per_unit, jo_products.unit_price, jo_crmentity.crmid, jo_crmentity.smownerid,
						jo_pricebookproductrel.listprice
				FROM jo_products
				INNER JOIN jo_pricebookproductrel ON jo_products.productid = jo_pricebookproductrel.productid
				INNER JOIN jo_crmentity on jo_crmentity.crmid = jo_products.productid
				INNER JOIN jo_pricebook on jo_pricebook.pricebookid = jo_pricebookproductrel.pricebookid
				INNER JOIN jo_productcf on jo_productcf.productid = jo_products.productid
				LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid '
				. Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleModel->getName()) .'
				WHERE jo_pricebook.pricebookid = '.$recordModel->getId().' and jo_crmentity.deleted = 0';
		return $query;
	}


	/**
	 * Function returns query for PriceBooks-Services Relationship
	 * @param <Head_Record_Model> $recordModel
	 * @param <Head_Record_Model> $relatedModuleModel
	 * @return <String>
	 */
	function get_pricebook_services($recordModel, $relatedModuleModel) {
		$query = 'SELECT jo_service.serviceid, jo_service.servicename, jo_service.service_no, jo_service.commissionrate,
					jo_service.qty_per_unit, jo_service.unit_price, jo_crmentity.crmid, jo_crmentity.smownerid,
					jo_pricebookproductrel.listprice
			FROM jo_service
			INNER JOIN jo_pricebookproductrel on jo_service.serviceid = jo_pricebookproductrel.productid
			INNER JOIN jo_crmentity on jo_crmentity.crmid = jo_service.serviceid
			INNER JOIN jo_pricebook on jo_pricebook.pricebookid = jo_pricebookproductrel.pricebookid
			INNER JOIN jo_servicecf on jo_servicecf.serviceid = jo_service.serviceid
			LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
			LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid '
			. Users_Privileges_Model::getNonAdminAccessControlQuery($relatedModuleModel->getName()) .'
			WHERE jo_pricebook.pricebookid = '.$recordModel->getId().' and jo_crmentity.deleted = 0';
		return $query;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery, $currencyId = false) {
		$relatedModulesList = array('Products', 'Services');
		if (in_array($sourceModule, $relatedModulesList)) {
			$pos = stripos($listQuery, ' where ');
			if ($currencyId && in_array($field, array('productid', 'serviceid'))) {
				$condition = " jo_pricebook.pricebookid IN (SELECT pricebookid FROM jo_pricebookproductrel WHERE productid = $record)
								AND jo_pricebook.currency_id = $currencyId AND jo_pricebook.active = 1";
			} else if($field == 'productsRelatedList') {
				$condition = "jo_pricebook.pricebookid NOT IN (SELECT pricebookid FROM jo_pricebookproductrel WHERE productid = $record)
								AND jo_pricebook.active = 1";
			}
			if ($pos) {
				$split = spliti(' where ', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery . ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}
	
	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}
	
	/**
	 * Funtion that returns fields that will be showed in the record selection popup
	 * @return <Array of fields>
	 */
	public function getPopupViewFieldsList() {
		$popupFileds = $this->getSummaryViewFieldsList();
		$reqPopUpFields = array('Currency' => 'currency_id'); 
		foreach ($reqPopUpFields as $fieldLabel => $fieldName) {
			$fieldModel = Head_Field_Model::getInstance($fieldName,$this); 
			if ($fieldModel->getPermissions('readwrite')) { 
				$popupFileds[$fieldName] = $fieldModel; 
			}
		}
		return array_keys($popupFileds);
	}
    
    /**
	* Function is used to give links in the All menu bar
	*/
	public function getQuickMenuModels() {
		if($this->isEntityModule()) {
			$moduleName = $this->getName();
			$listViewModel = Head_ListView_Model::getCleanInstance($moduleName);
			$basicListViewLinks = $listViewModel->getBasicLinks();
		}
        
		if($basicListViewLinks) {
			foreach($basicListViewLinks as $basicListViewLink) {
				if(is_array($basicListViewLink)) {
					$links[] = Head_Link_Model::getInstanceFromValues($basicListViewLink);
				} else if(is_a($basicListViewLink, 'Head_Link_Model')) {
					$links[] = $basicListViewLink;
				}
			}
		}
		return $links;
	}

	/*
     * Function to get supported utility actions for a module
	 */
	function getUtilityActionsNames() {
        return array('Import', 'Export');
    }

	/**
	 * Function returns export query - deprecated
	 * @param <String> $where
	 * @return <String> export query
	 */
	public function getExportQuery($focus, $query) {
		$baseTableName = $focus->table_name;
		$splitQuery = spliti(' FROM ', $query, 2);
		$columnFields = explode(',', $splitQuery[0]);
		foreach ($columnFields as &$value) {
			if(trim($value) == "$baseTableName.currency_id") {
				$value = ' jo_currency_info.currency_name AS currency_id';
			}
		}
		array_push($columnFields, "jo_pricebookproductrel.productid as Relatedto", "jo_pricebookproductrel.listprice as ListPrice");
		$joinSplit = spliti(' WHERE ',$splitQuery[1], 2);
		$joinSplit[0] .= " LEFT JOIN jo_currency_info ON jo_currency_info.id = $baseTableName.currency_id "
				."LEFT JOIN jo_pricebookproductrel on jo_pricebook.pricebookid = jo_pricebookproductrel.pricebookid ";
		$splitQuery[1] = $joinSplit[0] . ' WHERE ' .$joinSplit[1];
		$query = implode(', ', $columnFields).' FROM ' . $splitQuery[1];
		return $query;
	}

	public function getAdditionalImportFields() {
		if (!$this->importableFields) {
			$fieldHeaders = array(
								'relatedto'=> array('label'=>'Related To', 'uitype'=>10),//For relation field
								'listprice'=> array('label'=>'ListPrice', 'uitype'=>83)//For related field currency
				);

			$this->importableFields = array();
			foreach ($fieldHeaders as $fieldName => $fieldInfo) {
				$fieldModel = new Head_Field_Model();
				$fieldModel->name = $fieldName;
				$fieldModel->label = $fieldInfo['label'];
				$fieldModel->column = $fieldName;
				$fieldModel->uitype = $fieldInfo['uitype'];
				$webServiceField = $fieldModel->getWebserviceFieldObject();
				$webServiceField->setFieldDataType($fieldModel->getFieldDataType());
				$fieldModel->webserviceField = $webServiceField;
				$this->importableFields[$fieldName] = $fieldModel;
			}
		}
		return $this->importableFields;
	}

}
