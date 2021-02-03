<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Picklist_Module_Model extends Head_Module_Model
{

	public function getPickListTableName($fieldName)
	{
		return 'jo_' . $fieldName;
	}

	public function getFieldsByType($type)
	{
		$presence = array('0', '2');

		$fieldModels = parent::getFieldsByType($type);
		$fields = array();
		foreach ($fieldModels as $fieldName => $fieldModel) {
			if (($fieldModel->get('displaytype') != '1' && $fieldName != 'salutationtype') || !in_array($fieldModel->get('presence'), $presence)) {
				continue;
			}
			$fields[$fieldName] = Settings_Picklist_Field_Model::getInstanceFromFieldObject($fieldModel);
		}
		return $fields;
	}

	public function addPickListValues($fieldModel, $newValue, $rolesSelected = array(), $color = '')
	{
		$db = PearDatabase::getInstance();
		$pickListFieldName = $fieldModel->getName();
		$id = $db->getUniqueID("jo_$pickListFieldName");
		vimport('~~/includes/ComboUtil.php');
		$picklist_valueid = getUniquePicklistID();
		$tableName = 'jo_' . $pickListFieldName;
		$maxSeqQuery = 'SELECT max(sortorderid) as maxsequence FROM ' . $tableName;
		$result = $db->pquery($maxSeqQuery, array());
		$sequence = $db->query_result($result, 0, 'maxsequence');

		if ($fieldModel->isRoleBased()) {
			$sql = 'INSERT INTO ' . $tableName . ' VALUES (?,?,?,?,?,?)';
			$db->pquery($sql, array($id, $newValue, 1, $picklist_valueid, ++$sequence, $color));
		} else {
			$sql = 'INSERT INTO ' . $tableName . ' VALUES (?,?,?,?,?)';
			$db->pquery($sql, array($id, $newValue, ++$sequence, 1, $color));
		}

		if ($fieldModel->isRoleBased() && !empty($rolesSelected)) {
			$sql = "select picklistid from jo_picklist where name=?";
			$result = $db->pquery($sql, array($pickListFieldName));
			$picklistid = $db->query_result($result, 0, "picklistid");
			//add the picklist values to the selected roles
			for ($j = 0; $j < count($rolesSelected); $j++) {
				$roleid = $rolesSelected[$j];
				Head_Cache::delete('PicklistRoleBasedValues', $pickListFieldName . $roleid);
				$sql = "SELECT max(sortid)+1 as sortid
					   FROM jo_role2picklist left join jo_$pickListFieldName
						   on jo_$pickListFieldName.picklist_valueid=jo_role2picklist.picklistvalueid
					   WHERE roleid=? and picklistid=?";
				$sortid = $db->query_result($db->pquery($sql, array($roleid, $picklistid)), 0, 'sortid');

				$sql = "insert into jo_role2picklist values(?,?,?,?)";
				$db->pquery($sql, array($roleid, $picklist_valueid, $picklistid, $sortid));
			}
		}
		// we should clear cache to update with latest values
		Head_Cache::flushPicklistCache($pickListFieldName);
		return array('picklistValueId' => $picklist_valueid, 'id' => $id);
	}

	public function renamePickListValues($pickListFieldName, $oldValue, $newValue, $moduleName, $id, $rolesList = false, $color = '')
	{
		$db = PearDatabase::getInstance();

		$query = 'SELECT tablename, fieldid, columnname FROM jo_field WHERE fieldname=? and presence IN (0,2)';
		$result = $db->pquery($query, array($pickListFieldName));
		$num_rows = $db->num_rows($result);

		//As older look utf8 characters are pushed as html-entities,and in new utf8 characters are pushed to database
		//so we are checking for both the values
		$primaryKey = Head_Util_Helper::getPickListId($pickListFieldName);
		if (!empty($color)) {
			$query = 'UPDATE ' . $this->getPickListTableName($pickListFieldName) . ' SET ' . $pickListFieldName . '= ?, color = ? WHERE ' . $primaryKey . ' = ?';
			$db->pquery($query, array($newValue, $color, $id));
		} else {
			$query = 'UPDATE ' . $this->getPickListTableName($pickListFieldName) . ' SET ' . $pickListFieldName . '=? WHERE ' . $primaryKey . ' = ?';
			$db->pquery($query, array($newValue, $id));
		}

		for ($i = 0; $i < $num_rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$tableName = $row['tablename'];
			$columnName = $row['columnname'];
			$query = 'UPDATE ' . $tableName . ' SET ' . $columnName . '=? WHERE ' . $columnName . '=?';
			$db->pquery($query, array($newValue, $oldValue));
		}

		$query = "UPDATE jo_field SET defaultvalue=? WHERE defaultvalue=? AND columnname=?";
		$db->pquery($query, array($newValue, $oldValue, $columnName));

		vimport('~~/includes/utils/CommonUtils.php');

		$query = "UPDATE jo_picklist_dependency SET sourcevalue=? WHERE sourcevalue=? AND sourcefield=?";
		$db->pquery($query, array($newValue, $oldValue, $pickListFieldName));

		//To update column name for Metric Picklist and Recalculate Transition Map
		$moduleInstance = Head_Module_Model::getInstance($moduleName);
		$fieldModel = Head_Field_Model::getInstance($pickListFieldName, $moduleInstance);

		Head_Cache::flushPicklistCache($pickListFieldName, $rolesList);

		$em = new EventsManager($db);
		$data = array();
		$data['fieldId'] = $db->query_result($result, 0, 'fieldid');
		$data['fieldname'] = $pickListFieldName;
		$data['oldvalue'] = $oldValue;
		$data['newvalue'] = $newValue;
		$data['module'] = $moduleName;
		$em->triggerEvent('vtiger.picklist.afterrename', $data);

		return true;
	}

	public function remove($pickListFieldName, $valueToDeleteId, $replaceValueId, $moduleName)
	{
		$db = PearDatabase::getInstance();
		if (!is_array($valueToDeleteId)) {
			$valueToDeleteId = array($valueToDeleteId);
		}
		$primaryKey = Head_Util_Helper::getPickListId($pickListFieldName);

		$pickListValues = array();
		$valuesOfDeleteIds = "SELECT $pickListFieldName FROM " . $this->getPickListTableName($pickListFieldName) . " WHERE $primaryKey IN (" . generateQuestionMarks($valueToDeleteId) . ")";
		$pickListValuesResult = $db->pquery($valuesOfDeleteIds, array($valueToDeleteId));
		$num_rows = $db->num_rows($pickListValuesResult);
		for ($i = 0; $i < $num_rows; $i++) {
			$pickListValues[] = decode_html($db->query_result($pickListValuesResult, $i, $pickListFieldName));
		}

		$replaceValueQuery = $db->pquery("SELECT $pickListFieldName FROM " . $this->getPickListTableName($pickListFieldName) . " WHERE $primaryKey IN (" . generateQuestionMarks($replaceValueId) . ")", array($replaceValueId));
		$replaceValue = decode_html($db->query_result($replaceValueQuery, 0, $pickListFieldName));

		//As older look utf8 characters are pushed as html-entities,and in new utf8 characters are pushed to database
		//so we are checking for both the values
		$encodedValueToDelete = array();
		foreach ($pickListValues as $key => $value) {
			$encodedValueToDelete[$key]  = Head_Util_Helper::toSafeHTML($value);
		}
		$mergedValuesToDelete = array_merge($pickListValues, $encodedValueToDelete);

		$fieldModel = Settings_Picklist_Field_Model::getInstance($pickListFieldName, $this);
		//if role based then we need to delete all the values in role based picklist
		if ($fieldModel->isRoleBased()) {
			$picklistValueIdToDelete = array();
			$query = 'SELECT DISTINCT picklist_valueid,roleid FROM ' . $this->getPickListTableName($pickListFieldName) .
				' AS picklisttable LEFT JOIN jo_role2picklist AS roletable ON roletable.picklistvalueid = picklisttable.picklist_valueid
					  WHERE ' . $primaryKey . ' IN (' . generateQuestionMarks($valueToDeleteId) . ')';
			$result = $db->pquery($query, $valueToDeleteId);
			$num_rows = $db->num_rows($result);
			for ($i = 0; $i < $num_rows; $i++) {
				$picklistValueId = $db->query_result($result, $i, 'picklist_valueid');
				$roleId = $db->query_result($result, $i, 'roleid');
				// clear cache to update with lates values
				Head_Cache::delete('PicklistRoleBasedValues', $pickListFieldName . $roleId);
				$picklistValueIdToDelete[$picklistValueId] = $picklistValueId;
			}
			$query = 'DELETE FROM jo_role2picklist WHERE picklistvalueid IN (' . generateQuestionMarks($picklistValueIdToDelete) . ')';
			$db->pquery($query, $picklistValueIdToDelete);
		}

		// we should clear cache to update with latest values
		Head_Cache::flushPicklistCache($pickListFieldName);

		$query = 'DELETE FROM ' . $this->getPickListTableName($pickListFieldName) .
			' WHERE ' . $primaryKey . ' IN (' .  generateQuestionMarks($valueToDeleteId) . ')';
		$db->pquery($query, $valueToDeleteId);

		vimport('~~/includes/utils/CommonUtils.php');
		$tabId = getTabId($moduleName);
		$query = 'DELETE FROM jo_picklist_dependency WHERE sourcevalue IN (' . generateQuestionMarks($pickListValues) . ')' .
			' AND sourcefield=?';
		$params = array();
		array_push($params, $pickListValues);
		array_push($params, $pickListFieldName);
		$db->pquery($query, $params);

		$query = 'SELECT tablename,columnname FROM jo_field WHERE fieldname=? AND presence in (0,2)';
		$result = $db->pquery($query, array($pickListFieldName));
		$num_row = $db->num_rows($result);

		for ($i = 0; $i < $num_row; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$tableName = $row['tablename'];
			$columnName = $row['columnname'];

			$query = 'UPDATE ' . $tableName . ' SET ' . $columnName . '=? WHERE ' . $columnName . ' IN (' .  generateQuestionMarks($pickListValues) . ')';
			$params = array($replaceValue);
			array_push($params, $pickListValues);
			$db->pquery($query, $params);
		}

		$query = 'UPDATE jo_field SET defaultvalue=? WHERE defaultvalue IN (' . generateQuestionMarks($pickListValues) . ') AND columnname=?';
		$params = array($replaceValue);
		array_push($params, $pickListValues);
		array_push($params, $columnName);
		$db->pquery($query, $params);

		$em = new EventsManager($db);
		$data = array();
		$data['fieldId'] = $fieldModel->id;
		$data['fieldname'] = $pickListFieldName;
		$data['valuetodelete'] = $pickListValues;
		$data['replacevalue'] = $replaceValue;
		$data['module'] = $moduleName;
		$em->triggerEvent('vtiger.picklist.afterdelete', $data);

		return true;
	}

	public function enableOrDisableValuesForRole($picklistFieldName, $valuesToEnables, $valuesToDisable, $roleIdList)
	{
		$db = PearDatabase::getInstance();
		//To disable die On error since we will be doing insert without chekcing
		$dieOnErrorOldValue = $db->dieOnError;
		$db->dieOnError = false;

		$sql = "select picklistid from jo_picklist where name=?";
		$result = $db->pquery($sql, array($picklistFieldName));
		$picklistid = $db->query_result($result, 0, "picklistid");

		$primaryKey = Head_Util_Helper::getPickListId($picklistFieldName);

		$pickListValueList = array_merge($valuesToEnables, $valuesToDisable);
		$pickListValueDetails = array();
		$query = 'SELECT picklist_valueid,' . $picklistFieldName . ', ' . $primaryKey .
			' FROM ' . $this->getPickListTableName($picklistFieldName) .
			' WHERE ' . $primaryKey . ' IN (' .  generateQuestionMarks($pickListValueList) . ')';
		$params = array();
		array_push($params, $pickListValueList);

		$result = $db->pquery($query, $params);
		$num_rows = $db->num_rows($result);

		for ($i = 0; $i < $num_rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);

			$pickListValueDetails[decode_html($row[$primaryKey])] = array(
				'picklistvalueid' => $row['picklist_valueid'],
				'picklistid' => $picklistid
			);
		}
		$insertValueList = array();
		$deleteValueList = array();
		foreach ($roleIdList as $roleId) {
			// clearing cache to update with latest values
			Head_Cache::delete('PicklistRoleBasedValues', $picklistFieldName . $roleId);
			foreach ($valuesToEnables  as $picklistValue) {
				$valueDetail = $pickListValueDetails[$picklistValue];
				if (empty($valueDetail)) {
					$valueDetail = $pickListValueDetails[Head_Util_Helper::toSafeHTML($picklistValue)];
				}
				$pickListValueId = $valueDetail['picklistvalueid'];
				$picklistId = $valueDetail['picklistid'];
				$insertValueList[] = '("' . $roleId . '","' . $pickListValueId . '","' . $picklistId . '")';
			}

			foreach ($valuesToDisable as $picklistValue) {
				$valueDetail = $pickListValueDetails[$picklistValue];
				if (empty($valueDetail)) {
					$valueDetail = $pickListValueDetails[Head_Util_Helper::toSafeHTML($picklistValue)];
				}
				$pickListValueId = $valueDetail['picklistvalueid'];
				$picklistId = $valueDetail['picklistid'];
				$deleteValueList[] = ' ( roleid = "' . $roleId . '" AND ' . 'picklistvalueid = "' . $pickListValueId . '") ';
			}
		}
		$query = 'INSERT IGNORE INTO jo_role2picklist (roleid,picklistvalueid,picklistid) VALUES ' . implode(',', $insertValueList);
		$result = $db->pquery($query, array());

		$deleteQuery = 'DELETE FROM jo_role2picklist WHERE ' . implode(' OR ', $deleteValueList);

		$result = $db->pquery($deleteQuery, array());

		//retaining to older value
		$db->dieOnError = $dieOnErrorOldValue;
	}

	public function updateSequence($pickListFieldName, $picklistValues, $rolesList = false)
	{
		$db = PearDatabase::getInstance();

		$primaryKey = Head_Util_Helper::getPickListId($pickListFieldName);

		$query = 'UPDATE ' . $this->getPickListTableName($pickListFieldName) . ' SET sortorderid = CASE ';
		foreach ($picklistValues as $values => $sequence) {
			$query .= ' WHEN ' . $primaryKey . '="' . $values . '" THEN "' . $sequence . '"';
		}
		$query .= ' END';
		$db->pquery($query, array());
		Head_Cache::flushPicklistCache($pickListFieldName, $rolesList);
	}


	public static function getPicklistSupportedModules()
	{
		$db = PearDatabase::getInstance();
		$restrictedPickListModule = array('Transactions');
		// modlib customization: Ignore disabled modules.
		$query = "SELECT distinct jo_tab.tablabel, jo_tab.name as tabname
				  FROM jo_tab
						inner join jo_field on jo_tab.tabid=jo_field.tabid
				  WHERE uitype IN (15,33,16,114) and jo_field.tabid NOT IN (29,10)  and jo_tab.presence != 1 and jo_field.presence in (0,2)
				  AND jo_tab.tablabel NOT IN (" . generateQuestionMarks($restrictedPickListModule) . ")
				  ORDER BY jo_tab.tabid ASC";
		// END
		$result = $db->pquery($query, array($restrictedPickListModule));

		$modulesModelsList = array();
		while ($row = $db->fetch_array($result)) {
			$moduleLabel = $row['tablabel'];
			$moduleName  = $row['tabname'];
			$instance = new self();
			$instance->name = $moduleName;
			$instance->label = $moduleLabel;
			$modulesModelsList[] = $instance;
		}
		return $modulesModelsList;
	}


	/**
	 * Static Function to get the instance of Head Module Model for the given id or name
	 * @param mixed id or name of the module
	 */
	public static function getInstance($value)
	{
		//TODO : add caching
		$instance = false;
		$moduleObject = parent::getInstance($value);
		if ($moduleObject) {
			$instance = self::getInstanceFromModuleObject($moduleObject);
		}
		return $instance;
	}

	/**
	 * Function to get the instance of Head Module Model from a given Head_Module object
	 * @param Head_Module $moduleObj
	 * @return Head_Module_Model instance
	 */
	public static function getInstanceFromModuleObject(Head_Module $moduleObj)
	{
		$objectProperties = get_object_vars($moduleObj);
		$moduleModel = new self();
		foreach ($objectProperties as $properName => $propertyValue) {
			$moduleModel->$properName = $propertyValue;
		}
		return $moduleModel;
	}

	public function handleLabels($moduleName, $newValues, $oldValues, $mode)
	{
		if (!is_array($newValues)) {
			$newValues = array($newValues);
		}
		if (!is_array($oldValues)) {
			$oldValues = array($oldValues);
		}

		$allLang = Head_Language_Handler::getAllLanguages();
		foreach ($allLang as $langKey => $langName) {
			$langDir = 'languages/' . $langKey . '/custom/';
			if (!file_exists($langDir)) {
				mkdir($langDir);
				mkdir($langDir . '/Settings');
			}
			$fileName = $langDir . $moduleName . '.php';
			if (file_exists($fileName)) {
				require $fileName;
			}
			if (!isset($languageStrings)) {
				$languageStrings = array();
			}
			//If mode is delete and $languageStrings is empty then no need of creating file.
			if ($mode == 'delete' && empty($languageStrings)) {
				continue;
			}
			if (!empty($newValues)) {
				foreach ($newValues as $newValue) {
					$newValue = decode_html($newValue);
					if (!isset($languageStrings[$newValue])) {
						$languageStrings[$newValue] = $newValue;
					}
				}
			}

			if ($mode == 'rename' || $mode == 'delete' && !empty($oldValues)) {
				foreach ($oldValues as $oldValue) {
					$oldValue = decode_html($oldValue);
					unset($languageStrings[$oldValue]);
				}
			}

			//Write file
			$fp = fopen($fileName, "w");
			if ($languageStrings) {
				fwrite($fp, "<?php\n\$languageStrings = array(\n");
				foreach ($languageStrings as $key => $value) {
					$key = addslashes($key);
					$value = addslashes($value);
					fwrite($fp, "'$key'\t=>\t'$value',\n");
				}
				fwrite($fp, ");");
			}
			if ($jsLanguageStrings) {
				fwrite($fp, "\n\$jsLanguageStrings = array(\n");
				foreach ($jsLanguageStrings as $key => $value) {
					$key = addslashes($key);
					$value = addslashes($value);
					fwrite($fp, "'$key'\t=>\t'$value',\n");
				}
				fwrite($fp, ");");
			}
			fclose($fp);
			unset($languageStrings);
			unset($jsLanguageStrings);
		}
	}

	function getActualPicklistValues($valueToDelete, $pickListFieldName)
	{
		$db = PearDatabase::getInstance();
		if (!is_array($valueToDelete)) {
			$valueToDeleteID = array($valueToDelete);
		} else {
			$valueToDeleteID = $valueToDelete;
		}

		$primaryKey = Head_Util_Helper::getPickListId($pickListFieldName);
		$pickListDeleteValue = array();
		$getPickListValueQuery = "SELECT $pickListFieldName FROM " . $this->getPickListTableName($pickListFieldName) . " WHERE $primaryKey IN (" . generateQuestionMarks($valueToDeleteID) . ")";
		$result = $db->pquery($getPickListValueQuery, array($valueToDeleteID));
		$num_rows = $db->num_rows($result);
		for ($i = 0; $i < $num_rows; $i++) {
			$pickListDeleteValue[] = decode_html($db->query_result($result, $i, $pickListFieldName));
		}
		return $pickListDeleteValue;
	}

	/**
	 * Function to get the picklist value color
	 * @param <string> $pickListFieldName
	 * @param <integer> $pickListId
	 * @return <string> $color
	 */
	public static function getPicklistColor($pickListFieldName, $pickListId)
	{
		$db = PearDatabase::getInstance();
		$primaryKey = Head_Util_Helper::getPickListId($pickListFieldName);
		$colums = $db->getColumnNames("jo_$pickListFieldName");
		if (in_array('color', $colums)) {
			$query = 'SELECT color FROM jo_' . $pickListFieldName . ' WHERE ' . $primaryKey . ' = ?';
			$result = $db->pquery($query, array($pickListId));
			if ($db->num_rows($result) > 0) {
				$color = $db->query_result($result, 0, 'color');
			}
		}

		return $color;
	}

	/**
	 * Function to get the accesable picklist values for current user
	 * @param <string> $name - picklist field name
	 * @return <array> $picklistValues
	 */
	public static function getAccessiblePicklistValues($name)
	{
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();
		$picklistValues = array();
		if (vtws_isRoleBasedPicklist($name)) {
			$picklistValues = getAssignedPicklistValues($name, $currentUser->roleid, $db);
		}

		return $picklistValues;
	}

	/**
	 * Function to get the picklist color map of all the picklist values for a field
	 * @param <string> $fieldName - Picklist field name
	 * @param <boolean> $key - to change key of the array
	 * @return <array> $pickListColorMap
	 */
	public static function getPicklistColorMap($fieldName, $key = false)
	{
		$db = PearDatabase::getInstance();
		$primaryKey = Head_Util_Helper::getPickListId($fieldName);
		$colums = $db->getColumnNames("jo_$fieldName");
		if (in_array('color', $colums)) {
			$query = 'SELECT ' . $primaryKey . ',color,' . $fieldName . ' FROM jo_' . $fieldName;
			$result = $db->pquery($query);
			$pickListColorMap = array();
			$accessablePicklistValues = self::getAccessiblePicklistValues($fieldName);
			if ($db->num_rows($result) > 0) {
				for ($i = 0; $i < $db->num_rows($result); $i++) {
					$pickListId = $db->query_result($result, $i, $primaryKey);
					$color = $db->query_result($result, $i, 'color');
					$picklistNameRaw = $db->query_result($result, $i, $fieldName);
					$picklistName = decode_html($picklistNameRaw);
					// show color only for accesable picklist values
					if (vtws_isRoleBasedPicklist($fieldName) && !isset($accessablePicklistValues[$picklistNameRaw])) {
						$color = '';
					}
					if (!empty($color)) {
						if ($key) {
							$pickListColorMap[$picklistName] = $color;
						} else {
							$pickListColorMap[$pickListId] = $color;
						}
					}
				}
			}
		}

		return $pickListColorMap;
	}

	/**
	 * Function to get the picklist color for a picklist value
	 * @param <string> $fieldName - picklist field name
	 * @param <string> $fieldValue - picklist value
	 * @return <string> $color
	 */
	public static function getPicklistColorByValue($fieldName, $fieldValue)
	{
		$db = PearDatabase::getInstance();
		$tableName = "jo_$fieldName";
		if (Head_Utils::CheckTable($tableName)) {
			$colums = $db->getColumnNames($tableName);
			$fieldValue = decode_html($fieldValue);
			if (in_array('color', $colums)) {
				$query = 'SELECT color FROM ' . $tableName . ' WHERE ' . $fieldName . ' = ?';
				$result = $db->pquery($query, array($fieldValue));
				if ($db->num_rows($result) > 0) {
					$color = $db->query_result($result, 0, 'color');
				}
			}
		}
		return $color;
	}

	public static function getTextColor($hexcolor)
	{
		$hexcolor = str_replace('#', '', $hexcolor);
		$r = intval(substr($hexcolor, 0, 2), 16);
		$g = intval(substr($hexcolor, 2, 2), 16);
		$b = intval(substr($hexcolor, 4, 2), 16);
		$yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

		return ($yiq >= 128) ? 'black' : 'white';
	}

	public function updatePicklistColor($pickListFieldName, $id, $color = '')
	{
		$db = PearDatabase::getInstance();

		//As older look utf8 characters are pushed as html-entities,and in new utf8 characters are pushed to database
		//so we are checking for both the values
		$primaryKey = Head_Util_Helper::getPickListId($pickListFieldName);
		if (!empty($color)) {
			$query = 'UPDATE ' . $this->getPickListTableName($pickListFieldName) . ' SET color = ? WHERE ' . $primaryKey . ' = ?';
			$db->pquery($query, array($color, $id));
		}

		return true;
	}
}
