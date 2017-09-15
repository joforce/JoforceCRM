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

require_once 'modules/com_jo_workflow/include.inc';
require_once 'modules/com_jo_workflow/expression_engine/VTExpressionsManager.inc';

class Settings_Workflows_Module_Model extends Settings_Head_Module_Model {

	var $baseTable = 'com_jo_workflows';
	var $baseIndex = 'workflow_id';
//	var $listFields = array('summary' => 'Summary', 'module_name' => 'Module', 'execution_condition' => 'Execution Condition');
	var $listFields = array('module_name' => 'Module', 'workflowname' => 'Workflow Name', 'summary'=>'Description', 'execution_condition' => 'Trigger',  'test' => 'Conditions');
	var $name = 'Workflows';

	static $metaVariables = array(
		'Current Date' => '(general : (__HeadMeta__) date) ($_DATE_FORMAT_)',
		'Current Time' => '(general : (__HeadMeta__) time)',
		'System Timezone' => '(general : (__HeadMeta__) dbtimezone)',
		'User Timezone' => '(general : (__HeadMeta__) usertimezone)',
		'CRM Detail View URL' => '(general : (__HeadMeta__) crmdetailviewurl)',
		'Portal Detail View URL' => '(general : (__HeadMeta__) portaldetailviewurl)',
		'Site Url' => '(general : (__HeadMeta__) siteurl)',
		'Portal Url' => '(general : (__HeadMeta__) portalurl)',
		'Record Id' => '(general : (__HeadMeta__) recordId)',
		'LBL_HELPDESK_SUPPORT_NAME' => '(general : (__HeadMeta__) supportName)',
		'LBL_HELPDESK_SUPPORT_EMAILID' => '(general : (__HeadMeta__) supportEmailid)',
	);

	static $triggerTypes = array(
		1 => 'ON_FIRST_SAVE',
		2 => 'ONCE',
		3 => 'ON_EVERY_SAVE',
		4 => 'ON_MODIFY',
		// Reserving 5 & 6 for ON_DELETE and ON_SCHEDULED types.
		6=>	 'ON_SCHEDULE'
	);

	/**
	 * Function to get the url for default view of the module
	 * @return <string> - url
	 */
	public static function getDefaultUrl() {
        global $site_URL;
		return $site_URL.'Workflows/Settings/List';
	}

	/**
	 * Function to get the url for create view of the module
	 * @return <string> - url
	 */
	public static function getCreateViewUrl() {
		return "javascript:Settings_Workflows_List_Js.triggerCreate('index.php?module=Workflows&parent=Settings&view=Edit')";
	}

	public static function getCreateRecordUrl() {
        global $site_URL;
		return $site_URL.'Workflows/Settings/Edit';
	}

	public static function getSupportedModules() {
		$moduleModels = Head_Module_Model::getAll(array(0,2));
		$supportedModuleModels = array();
		foreach($moduleModels as $tabId => $moduleModel) {
			if($moduleModel->isWorkflowSupported() && $moduleModel->getName() != 'Webmails') {
				$supportedModuleModels[$tabId] = $moduleModel;
			}
		}
		return $supportedModuleModels;
	}

	public static function getTriggerTypes() {
		return self::$triggerTypes;
	}

	public static function getExpressions() {
		$db = PearDatabase::getInstance();

		$mem = new VTExpressionsManager($db);
		return $mem->expressionFunctions();
	}

	public static function getMetaVariables() {
		return self::$metaVariables;
	}

	public function getListFields() {
		if(!$this->listFieldModels) {
			$fields = $this->listFields;
			$fieldObjects = array();
			foreach($fields as $fieldName => $fieldLabel) {
				if($fieldName == 'module_name' || $fieldName == 'execution_condition') {
					$fieldObjects[$fieldName] = new Head_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel, 'sort'=>false));
				} else {
					$fieldObjects[$fieldName] = new Head_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel));
				}
			}
			$this->listFieldModels = $fieldObjects;
		}
		return $this->listFieldModels;
	}

	/**
	 * Function to get the count of active workflows
	 * @return <Integer> count of active workflows
	 */
	public function getActiveWorkflowCount($moduleCount = false){
		$db = PearDatabase::getInstance();

		$query = 'SELECT count(*) AS count, jo_tab.tabid FROM com_jo_workflows 
				  INNER JOIN jo_tab ON jo_tab.name = com_jo_workflows.module_name 
				  AND jo_tab.presence IN (0,2) WHERE com_jo_workflows.status = ? ';

		if($moduleCount){
		   $query .=' GROUP BY com_jo_workflows.module_name';
		}

		$result = $db->pquery($query, array(1));
		$count = 0;
		$wfModulesCount = array();
		$noOfRows = $db->num_rows($result);
		for($i=0; $i<$noOfRows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$count = $count+$row['count'];
			$wfModulesCount[$row['tabid']] = $row['count'];
		}

		if($moduleCount){
		   $wfModulesCount['All'] = $count;
		   return $wfModulesCount;
		} else {
		   return $count;
		}

	}

	public function getFields() {
	   return array();
	}

	public function getModuleBasicLinks(){
	   return array();
	}
}
