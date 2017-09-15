<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/
include_once dirname(__FILE__) . '/FetchRecordWithGrouping.php';

include_once 'include/Webservices/Create.php';
include_once 'include/Webservices/Update.php';

class Mobile_WS_SaveRecord extends Mobile_WS_FetchRecordWithGrouping {
	protected $recordValues = false;
	
	// Avoid retrieve and return the value obtained after Create or Update
	protected function processRetrieve(Mobile_API_Request $request) {
		return $this->recordValues;
	}
	
	function process(Mobile_API_Request $request) {
		global $current_user; // Required for vtws_update API
		$current_user = $this->getActiveUser();
		
		$module = $request->get('module');
		$recordid = $request->get('record');
		$valuesJSONString =  $request->get('values');
		
		$values = "";
		if(!empty($valuesJSONString) && is_string($valuesJSONString)) {
			$values = Zend_Json::decode($valuesJSONString);
		} else {
			$values = $valuesJSONString; // Either empty or already decoded.
		}

		$response = new Mobile_API_Response();

		if (empty($values)) {
			$response->setError(1501, "Values cannot be empty!");
			return $response;
		}

		try {
			if (vtws_recordExists($recordid)) {
				// Retrieve or Initalize
				if (!empty($recordid)) {
					$recordModel = Head_Record_Model::getInstanceById($recordid, $module);
				} else {
					$recordModel = Head_Record_Model::getCleanInstance($module);
				}

				// Set the modified values
				foreach($values as $name => $value) {
					$recordModel->set($name, $value);
				}

				$moduleModel = Head_Module_Model::getInstance($module);
				$fieldModelList = $moduleModel->getFields();
				foreach ($fieldModelList as $fieldName => $fieldModel) {
					$fieldValue = $values[$fieldName];
					$fieldDataType = $fieldModel->getFieldDataType();
					if($fieldDataType == 'time'){
						$fieldValue = Head_Time_UIType::getTimeValueWithSeconds($fieldValue);
					}
					if($fieldValue !== null) {
						if(!is_array($fieldValue) && $fieldDataType != 'currency') {
							$fieldValue = trim($fieldValue);
						}
						$recordModel->set($fieldName, $fieldValue);
					}
				}
				// Update or Create
				if (!empty($recordid)) {
					$recordModel->set('id', $recordid);
					$recordModel->set('mode', 'edit');
					$recordModel->save();
				} else {
					$recordModel->save();
				}
				$response->setResult($recordModel->getData());
			} else {
				$response->setError("RECORD_NOT_FOUND", "Record does not exist");
				return $response;
			}
		} catch(Exception $e) {
			$response->setError($e->getCode(), $e->getMessage());
		}
		return $response;
	}

}