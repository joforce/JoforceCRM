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

class ServiceContracts_Module_Model extends Head_Module_Model {

	/**
	 * Function to check whether the module is summary view supported
	 * @return <Boolean> - true/false
	 */
	public function isSummaryViewSupported() {
		return false;
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	function getUtilityActionsNames() {
		return array('Import', 'Export', 'DuplicatesHandling');
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if ($sourceModule === 'HelpDesk') {
			$condition = " jo_servicecontracts.servicecontractsid NOT IN (SELECT relcrmid FROM jo_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM jo_crmentityrel WHERE relcrmid = '$record') ";

			$pos = stripos($listQuery, 'where');
			if ($pos) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0].' WHERE '.$split[1].' AND '.$condition;
			} else {
				$overRideQuery = $listQuery.' WHERE '.$condition;
			}
			return $overRideQuery;
		}
	}

}
