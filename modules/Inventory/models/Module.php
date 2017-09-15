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
 * Inventory Module Model Class
 */
class Inventory_Module_Model extends Head_Module_Model {

	/**
	 * Function to check whether the module is an entity type module or not
	 * @return <Boolean> true/false
	 */
	public function isQuickCreateSupported(){
		//SalesOrder module is not enabled for quick create
		return false;
	}
	
	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return true;
	}

	public function isCommentEnabled() {
		return true;
	}

	static function getAllCurrencies() {
		return getAllCurrencies();
	}

	static function getAllProductTaxes() {
		$taxes = array();
		$availbleTaxes = getAllTaxes('available');
		foreach ($availbleTaxes as $taxInfo) {
			if ($taxInfo['method'] === 'Deducted') {
				continue;
			}
			$taxInfo['compoundon'] = Zend_Json::decode(html_entity_decode($taxInfo['compoundon']));
			$taxInfo['regions'] = Zend_Json::decode(html_entity_decode($taxInfo['regions']));
			$taxes[$taxInfo['taxid']] = $taxInfo;
		}
		return $taxes;
	}

	static function getAllShippingTaxes() {
		return Inventory_Charges_Model::getChargeTaxesList();
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Head_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule, $relationId) {
		if ($functionName === 'get_activities') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

			$query = "SELECT CASE WHEN (jo_users.user_name not like '') THEN $userNameSql ELSE jo_groups.groupname END AS user_name,
						jo_crmentity.*, jo_activity.activitytype, jo_activity.subject, jo_activity.date_start, jo_activity.time_start,
						jo_activity.recurringtype, jo_activity.due_date, jo_activity.time_end, jo_activity.visibility, jo_seactivityrel.crmid AS parent_id,
						CASE WHEN (jo_activity.activitytype = 'Task') THEN (jo_activity.status) ELSE (jo_activity.eventstatus) END AS status
						FROM jo_activity
						INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
						LEFT JOIN jo_seactivityrel ON jo_seactivityrel.activityid = jo_activity.activityid
						LEFT JOIN jo_cntactivityrel ON jo_cntactivityrel.activityid = jo_activity.activityid
						LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
						LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
							WHERE jo_crmentity.deleted = 0 AND jo_activity.activitytype <> 'Emails'
								AND jo_seactivityrel.crmid = ".$recordId;

			$relatedModuleName = $relatedModule->getName();
			$query .= $this->getSpecificRelationQuery($relatedModuleName);
			$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
			if ($nonAdminQuery) {
				$query = appendFromClauseToQuery($query, $nonAdminQuery);
			}
		} else {
			$query = parent::getRelationQuery($recordId, $functionName, $relatedModule, $relationId);
		}

		return $query;
	}
	
	/**
	 * Function returns export query
	 * @param <String> $where
	 * @return <String> export query
	 */
	public function getExportQuery($focus, $query) {
		$baseTableName = $focus->table_name;
		$splitQuery = spliti(' FROM ', $query);
		$columnFields = explode(',', $splitQuery[0]);
		foreach ($columnFields as $key => &$value) {
			if($value == ' jo_inventoryproductrel.discount_amount'){
				$value = ' jo_inventoryproductrel.discount_amount AS item_discount_amount';
			} else if($value == ' jo_inventoryproductrel.discount_percent'){
				$value = ' jo_inventoryproductrel.discount_percent AS item_discount_percent';
			} else if($value == " $baseTableName.currency_id"){
				$value = ' jo_currency_info.currency_name AS currency_id';
			}
		}
		$joinSplit = spliti(' WHERE ',$splitQuery[1]);
		$joinSplit[0] .= " LEFT JOIN jo_currency_info ON jo_currency_info.id = $baseTableName.currency_id";
		$splitQuery[1] = $joinSplit[0] . ' WHERE ' .$joinSplit[1];

		$query = implode(',', $columnFields).' FROM ' . $splitQuery[1];
		
		return $query;
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	function getUtilityActionsNames() {
		return array('Import', 'Export');
	}
}
