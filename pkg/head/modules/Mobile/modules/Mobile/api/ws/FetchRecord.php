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
include_once 'include/Webservices/Retrieve.php';

class Mobile_WS_FetchRecord extends Mobile_WS_Controller {

	private $module = false;

	protected $resolvedValueCache = array();
	
	protected function detectModuleName($recordid) {
		if($this->module === false) {
			$this->module = Mobile_WS_Utils::detectModulenameFromRecordId($recordid);
		}
		return $this->module;
	}
	
	protected function processRetrieve(Mobile_API_Request $request) {
		$current_user = $this->getActiveUser();

		$recordid = $request->get('record');
		$record = vtws_retrieve($recordid, $current_user);
		
		return $record;
	}

	function process(Mobile_API_Request $request) {
		$current_user = $this->getActiveUser();
		$record = $request->get('record');
		$module = $request->get('module');
		
		$moduleModel = Head_Module_Model::getInstance($module);
		$recordModel = Head_Record_Model::getInstanceById($record, $moduleModel);
		$data = $recordModel->getData();
		
		$data = $this->resolveRecordValues($data, $moduleModel);
		
		$response = new Mobile_API_Response();
		$response->setResult(array('record' => $data));
		
		return $response;
	}

	function resolveRecordValues($data, $moduleModel) {
		$fields = $moduleModel->getFields();
		
		foreach ($data as $fieldName => $value) {
			if ($fields[$fieldName]) {
				$fieldModel = $fields[$fieldName];
				$fieldType = $fieldModel->getFieldDataType();
				$referenceModules = $fieldModel->getReferenceList();
				if ($fieldType == 'reference' && !in_array('Users', $referenceModules)) {
					$data[$fieldName] = array('value' => $value, 'label' => decode_html(Head_Functions::getCRMRecordLabel($value)));
				} else if ($fieldType == 'reference' && in_array('Users', $referenceModules)) {
					$data[$fieldName] = array('value' => $value, 'label' => decode_html(Head_Functions::getUserRecordLabel($value)));
				} else if ($fieldType == 'url') {
					$data[$fieldName] = array('value' => $value, 'label' => $value);
				} else if ($fieldType == 'owner') {
					$ownerName = Head_Functions::getUserRecordLabel($value);
					if (!empty($ownerName)) {
						$data[$fieldName] = array('value' => $value, 'label' => decode_html($ownerName));
					} else {
						$data[$fieldName] = array('value' => $value, 'label' => decode_html(Head_Functions::getGroupRecordLabel($value)));
					}
				} else {
					$data[$fieldName] = array('value' => $value, 'label' => decode_html($fieldModel->getDisplayValue($value)));
				}
			}
		}
		return $data;
	}

	function fetchRecordLabelForId($id, $user) {
		$value = null;
		
		if (isset($this->resolvedValueCache[$id])) {
			$value = $this->resolvedValueCache[$id];
		} else if(!empty($id)) {
			$value = trim(vtws_getName($id, $user));
			$this->resolvedValueCache[$id] = $value;
		} else {
			$value = $id;
		}
		return $value;
	}
}