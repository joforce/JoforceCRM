<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

require_once 'includes/events/VTEventHandler.inc';

class FieldEventHandler extends VTEventHandler {

	function handleEvent($eventName, $fieldEntity) {
		global $log, $adb;

		if ($eventName == 'vtiger.field.afterdelete') {
			$this->triggerPostDeleteEvents($fieldEntity);
		}
	}

	function triggerPostDeleteEvents($fieldEntity) {
		$db = PearDatabase::getInstance();

		$fieldId		= $fieldEntity->id;
		$fieldName		= $fieldEntity->name;
		$columnName		= $fieldEntity->column;
		$fieldLabel		= $fieldEntity->label;
		$tableName		= $fieldEntity->table;
		$typeOfData		= $fieldEntity->typeofdata;
		$fieldModuleName= $fieldEntity->getModuleName();
		$fieldType		= explode('~', $typeOfData);

		$deleteColumnName	= "$tableName:$columnName:" . $fieldName . ':' . $fieldModuleName . '_' . str_replace(' ', '_', $fieldLabel) . ':' . $fieldType[0];
		$columnCvStdFilter	= "$tableName:$columnName:" . $fieldName . ':' . $fieldModuleName . '_' . str_replace(' ', '_', $fieldLabel);
		$selectColumnName	= "$tableName:$columnName:" . $fieldModuleName . '_' . str_replace(' ', '_', $fieldLabel) . ':' . $fieldName . ':' . $fieldType[0];
		$reportSummaryColumn= "$tableName:$columnName:" . str_replace(' ', '_', $fieldLabel);

		$query = 'ALTER TABLE ' . $db->sql_escape_string($tableName) . ' DROP COLUMN ' . $db->sql_escape_string($columnName);
		$db->pquery($query, array());

		//we have to remove the entries in customview and report related tables which have this field ($colName)
		$db->pquery('DELETE FROM jo_cvcolumnlist WHERE columnname = ?', array($deleteColumnName));
		$db->pquery('DELETE FROM jo_cvstdfilter WHERE columnname = ?', array($columnCvStdFilter));
		$db->pquery('DELETE FROM jo_cvadvfilter WHERE columnname = ?', array($deleteColumnName));
		$db->pquery('DELETE FROM jo_selectcolumn WHERE columnname = ?', array($selectColumnName));
		$db->pquery('DELETE FROM jo_relcriteria WHERE columnname = ?', array($selectColumnName));
		$db->pquery('DELETE FROM jo_reportsortcol WHERE columnname = ?', array($selectColumnName));
		$db->pquery('DELETE FROM jo_reportsummary WHERE columnname LIKE ?', array('%' . $reportSummaryColumn . '%'));
		$db->pquery('DELETE FROM jo_reportdatefilter WHERE datecolumnname = ?', array($columnCvStdFilter));

		if ($fieldModuleName == 'Leads') {
			$db->pquery('DELETE FROM jo_convertleadmapping WHERE leadfid=?', array($fieldId));
		} elseif ($fieldModuleName == 'Accounts' || $fieldModuleName == 'Contacts' || $fieldModuleName == 'Potentials') {
			$params = array('Accounts' => 'accountfid', 'Contacts' => 'contactfid', 'Potentials' => 'potentialfid');
			$query = 'UPDATE jo_convertleadmapping SET ' . $params[$fieldModuleName] . '=0 WHERE ' . $params[$fieldModuleName] . '=?';
			$db->pquery($query, array($fieldId));
		}

		if (in_array($fieldEntity->uitype, array(15, 33))) {
			$db->pquery('DROP TABLE IF EXISTS jo_' . $db->sql_escape_string($columnName), array());
			$db->pquery('DROP TABLE IF EXISTS jo_' . $db->sql_escape_string($columnName) . '_seq', array()); //To Delete Sequence Table  
			$db->pquery('DELETE FROM jo_picklist_dependency WHERE sourcefield=? OR targetfield=?', array($columnName, $columnName));

            //delete from picklist tables
            $picklistResult = $db->pquery('SELECT picklistid FROM jo_picklist WHERE name = ?', array($fieldName));
            $picklistRow = $db->num_rows($picklistResult);
            if($picklistRow) {
                $picklistId = $db->query_result($picklistResult, 0, 'picklistid');
                $db->pquery('DELETE FROM jo_picklist WHERE name = ?', array($fieldName));
                $db->pquery('DELETE FROM jo_role2picklist WHERE picklistid = ?', array($picklistId));
            }

			$rolesList = array_keys(getAllRoleDetails());
			Head_Cache::flushPicklistCache($fieldName, $rolesList);
		}

		$this->triggerInventoryFieldPostDeleteEvents($fieldEntity);
	}

	public function triggerInventoryFieldPostDeleteEvents($fieldEntity) {
		$db = PearDatabase::getInstance();
		$fieldId = $fieldEntity->id;
		$fieldModuleName = $fieldEntity->getModuleName();

		if (in_array($fieldModuleName, getInventoryModules())) {

			$db->pquery('DELETE FROM jo_inventorycustomfield WHERE fieldid=?', array($fieldId));

		} else if (in_array($fieldModuleName, array('Products', 'Services'))) {

			$refFieldName			= ($fieldModuleName == 'Products') ? 'productfieldid'			: 'servicefieldid';
			$refFieldDefaultValue	= ($fieldModuleName == 'Products') ? 'productFieldDefaultValue' : 'serviceFieldDefaultValue';

			$query = "SELECT jo_inventorycustomfield.* FROM jo_inventorycustomfield
							INNER JOIN jo_field ON jo_field.fieldid = jo_inventorycustomfield.fieldid
							WHERE $refFieldName = ? AND defaultvalue LIKE ?";
			$result = $db->pquery($query, array($fieldId, '%productFieldDefaultValue%serviceFieldDefaultValue%'));

			$removeCacheModules = array();
			while($rowData = $db->fetch_row($result)) {
				$lineItemFieldModel = Head_Field_Model::getInstance($rowData['fieldid']);
				if ($lineItemFieldModel) {
					$defaultValue = $lineItemFieldModel->getDefaultFieldValue();
					if (is_array($defaultValue)) {
						$defaultValue[$refFieldDefaultValue] = '';

						if ($defaultValue['productFieldDefaultValue'] === '' && $defaultValue['serviceFieldDefaultValue'] === '') {
							$defaultValue = '';
						} else {
							$defaultValue = Zend_Json::encode($defaultValue);
						}

						$lineItemFieldModel->set('defaultvalue', $defaultValue);
						$lineItemFieldModel->save();
					}

					$removeCacheModules[$rowData['tabid']][] = $lineItemFieldModel->get('block')->id;
				}
			}

			foreach ($removeCacheModules as $tabId => $blockIdsList) {
				$moduleModel = Head_Module_Model::getInstance($tabId);
				foreach ($blockIdsList as $blockId) {
					Head_Cache::flushModuleandBlockFieldsCache($moduleModel, $blockId);
				}
			}

			$db->pquery("UPDATE jo_inventorycustomfield SET $refFieldName=? WHERE fieldid=?", array('0', $fieldId));
		}

	}
}
