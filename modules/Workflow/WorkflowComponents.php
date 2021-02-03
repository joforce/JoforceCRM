<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ******************************************************************************/
require_once("includes/utils/CommonUtils.php");
require_once 'includes/Webservices/Utils.php';
require_once 'includes/Webservices/DescribeObject.php';
require_once("includes/Zend/Json.php");

require_once 'modules/Workflow/expression_engine/ExpressionsManager.inc';

function vtJsonFields($adb, $request) {
	$moduleName = $request['modulename'];
	$mem = new ExpressionsManager($adb);
	$fields = $mem->fields($moduleName);
	echo Zend_Json::encode(array('moduleFields' => $fields));
}

function vtJsonFunctions($adb) {
	$mem = new ExpressionsManager($adb);
	$functions = $mem->expressionFunctions();
	echo Zend_Json::encode($functions);
}

function vtJsonDependentModules($adb, $request) {
	$moduleName = $request['modulename'];
    
	$result = $adb->pquery("SELECT fieldname, tabid, typeofdata, jo_ws_referencetype.type as reference_module FROM jo_field
									INNER JOIN jo_ws_fieldtype ON jo_field.uitype = jo_ws_fieldtype.uitype
									INNER JOIN jo_ws_referencetype ON jo_ws_fieldtype.fieldtypeid = jo_ws_referencetype.fieldtypeid
							UNION
							SELECT fieldname, tabid, typeofdata, relmodule as reference_module FROM jo_field
									INNER JOIN jo_fieldmodulerel ON jo_field.fieldid = jo_fieldmodulerel.fieldid", array());
    
	$noOfFields = $adb->num_rows($result);
	$dependentFields = array();
	// List of modules which will not be supported by 'Create Entity' workflow task
	$filterModules = array('Invoice', 'Quotes', 'SalesOrder', 'PurchaseOrder', 'Emails', 'Calendar', 'Events', 'Accounts');
	$skipFieldsList = array();
	for ($i = 0; $i < $noOfFields; ++$i) {
		$tabId = $adb->query_result($result, $i, 'tabid');
		$fieldName = $adb->query_result($result, $i, 'fieldname');
		$typeOfData = $adb->query_result($result, $i, 'typeofdata');
		$referenceModule = $adb->query_result($result, $i, 'reference_module');
		$tabModuleName = getTabModuleName($tabId);
		if (in_array($tabModuleName, $filterModules))
			continue;
		if ($referenceModule == $moduleName && $tabModuleName != $moduleName) {
            if(!modlib_isModuleActive($tabModuleName))continue;
			$dependentFields[$tabModuleName] = array('fieldname' => $fieldName, 'modulelabel' => getTranslatedString($tabModuleName, $tabModuleName));            
		} else {
			$dataTypeInfo = explode('~', $typeOfData);
			if ($dataTypeInfo[1] == 'M') { // If the current reference field is mandatory
				$skipFieldsList[$tabModuleName] = array('fieldname' => $fieldName);
			}
		}
	}
	foreach ($skipFieldsList as $tabModuleName => $fieldInfo) {
		$dependentFieldInfo = $dependentFields[$tabModuleName];
		if ($dependentFieldInfo['fieldname'] != $fieldInfo['fieldname']) {
			unset($dependentFields[$tabModuleName]);
		}
	}
    
	$returnValue = array('count' => count($dependentFields), 'entities' => $dependentFields);
    
	echo Zend_Json::encode($returnValue);
}

function vtJsonOwnersList($adb) {
	$ownersList = array();
	$activeUsersList = get_user_array(false);
	$allGroupsList = get_group_array(false);
	foreach ($activeUsersList as $userId => $userName) {
		$ownersList[] = array('label' => $userName, 'value' => getUserName($userId));
	}
	foreach ($allGroupsList as $groupId => $groupName) {
		$ownersList[] = array('label' => $groupName, 'value' => $groupName);
	}

	echo Zend_Json::encode($ownersList);
}

global $adb;
$mode = modlib_purify($_REQUEST['mode']);

if ($mode == 'getfieldsjson') {
	vtJsonFields($adb, $_REQUEST);
} elseif ($mode == 'getfunctionsjson') {
	vtJsonFunctions($adb);
} elseif ($mode == 'getdependentfields') {
	vtJsonDependentModules($adb, $_REQUEST);
} elseif ($mode == 'getownerslist') {
	vtJsonOwnersList($adb);
}
