<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

class Google_Map_Helper {

	/**
	 * get the location for the record based on the module type
	 * @param type $request
	 * @return type
	 */
	static function getLocation($request) {
		$result = array();
		$recordId = $request->get('recordid');
		$module = $request->get('source_module');
		$locationFields = self::getLocationFields($module);
		$address = array();
		if (!empty($locationFields)) {
			$recordModel = Vtiger_Record_Model::getInstanceById($recordId, $module);
			foreach ($locationFields as $key => $value) {
				$address[$key] = Vtiger_Util_Helper::getDecodedValue($recordModel->get($value));
			}
			$result['label'] = $recordModel->getName();
		}
		$result['address'] = implode(",", $address);

		return $result;
	}

	/**
	 * get location values for:
	 * street, city, country
	 * @param type $module
	 * @return type
	 */
	static function getLocationFields($module) {
		$locationFields = array();
		switch ($module) {
			case 'Contacts'	:	$locationFields = array('street'	=> 'mailingstreet',
														'city'		=> 'mailingcity',
														'state'		=> 'mailingstate',
														'zip'		=> 'mailingzip',
														'country'	=> 'mailingcountry');
								break;
			case 'Leads'	:	$locationFields = array('street'	=> 'lane',
														'city'		=> 'city',
														'state'		=> 'state',
														'zip'		=> 'code',
														'country'	=> 'country');
								break;
			case 'Accounts'	:	$locationFields = array('street'	=> 'bill_street',
														'city'		=> 'bill_city',
														'state'		=> 'bill_state',
														'zip'		=> 'bill_code',
														'country'	=> 'bill_country');
								break;
		}
		return $locationFields;
	}

}

?>
