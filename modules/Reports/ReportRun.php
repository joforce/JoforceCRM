<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ********************************************************************************/

global $calpath;
global $app_strings, $mod_strings;
global $theme;
global $log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
require_once('includes/database/PearDatabase.php');
require_once('includes/data/CRMEntity.php');
require_once("modules/Reports/Reports.php");
require_once 'modules/Reports/ReportUtils.php';
require_once("libraries/modlib/Head/Module.php");
require_once('modules/Head/helpers/Util.php');
require_once('includes/RelatedListView.php');

/*
 * Helper class to determine the associative dependency between tables.
 */
class ReportRunQueryDependencyMatrix {

	protected $matrix = array();
	protected $computedMatrix = null;

	function setDependency($table, array $dependents) {
		$this->matrix[$table] = $dependents;
	}

	function addDependency($table, $dependent) {
		if (isset($this->matrix[$table]) && !in_array($dependent, $this->matrix[$table])) {
			$this->matrix[$table][] = $dependent;
		} else {
			$this->setDependency($table, array($dependent));
		}
	}

	function getDependents($table) {
		$this->computeDependencies();
		return isset($this->computedMatrix[$table]) ? $this->computedMatrix[$table] : array();
	}

	protected function computeDependencies() {
		if ($this->computedMatrix !== null)
			return;

		$this->computedMatrix = array();
		foreach ($this->matrix as $key => $values) {
			$this->computedMatrix[$key] = $this->computeDependencyForKey($key, $values);
		}
	}

	protected function computeDependencyForKey($key, $values) {
		$merged = array();
		foreach ($values as $value) {
			$merged[] = $value;
			if (isset($this->matrix[$value])) {
				$merged = array_merge($merged, $this->matrix[$value]);
			}
		}
		return $merged;
	}

}

class ReportRunQueryPlanner {

	// Turn-off the query planning to revert back - backward compatiblity
	protected $disablePlanner = false;
	protected $tables = array();
	protected $tempTables = array();
	protected $tempTablesInitialized = false;
	// Turn-off in case the query result turns-out to be wrong.
	protected $allowTempTables = true;
	protected $tempTablePrefix = 'jo_reptmptbl_';
	protected static $tempTableCounter = 0;
	protected $registeredCleanup = false;
	var $reportRun = false;

	function addTable($table) {
		if (!empty($table))
			$this->tables[$table] = $table;
	}

	function requireTable($table, $dependencies = null) {

		if ($this->disablePlanner) {
			return true;
		}

		if (isset($this->tables[$table])) {
			return true;
		}
		if (is_array($dependencies)) {
			foreach ($dependencies as $dependentTable) {
				if (isset($this->tables[$dependentTable])) {
					return true;
				}
			}
		} else if ($dependencies instanceof ReportRunQueryDependencyMatrix) {
			$dependents = $dependencies->getDependents($table);
			if ($dependents) {
				return count(array_intersect($this->tables, $dependents)) > 0;
			}
		}
		return false;
	}

	function getTables() {
		return $this->tables;
	}

	function newDependencyMatrix() {
		return new ReportRunQueryDependencyMatrix();
	}

	function registerTempTable($query, $keyColumns, $module = null) {
		if ($this->allowTempTables && !$this->disablePlanner) {
			global $current_user;

			$keyColumns = is_array($keyColumns) ? array_unique($keyColumns) : array($keyColumns);

			// Minor optimization to avoid re-creating similar temporary table.
			$uniqueName = NULL;
			foreach ($this->tempTables as $tmpUniqueName => $tmpTableInfo) {
				if (strcasecmp($query, $tmpTableInfo['query']) === 0 && $tmpTableInfo['module'] == $module) {
					// Capture any additional key columns
					$tmpTableInfo['keycolumns'] = array_unique(array_merge($tmpTableInfo['keycolumns'], $keyColumns));
					$uniqueName = $tmpUniqueName;
					break;
				}
			}

			// Nothing found?
			if ($uniqueName === NULL) {
				// TODO Adding randomness in name to avoid concurrency
				// even when same-user opens the report multiple instances at same-time.
				$uniqueName = $this->tempTablePrefix .
						str_replace('.', '', uniqid($current_user->id, true)) . (self::$tempTableCounter++);

				$this->tempTables[$uniqueName] = array(
					'query' => $query,
					'keycolumns' => is_array($keyColumns) ? array_unique($keyColumns) : array($keyColumns),
					'module' => $module
				);
			}

			return $uniqueName;
		}
		return "($query)";
	}

	function initializeTempTables() {
		global $adb;

		$oldDieOnError = $adb->dieOnError;
		$adb->dieOnError = false; // If query planner is re-used there could be attempt for temp table...
		foreach ($this->tempTables as $uniqueName => $tempTableInfo) {
			$reportConditions = $this->getReportConditions($tempTableInfo['module']);
			if ($tempTableInfo['module'] == 'Emails') {
				$query1 = sprintf('CREATE TEMPORARY TABLE %s AS %s', $uniqueName, $tempTableInfo['query']);
			} else {
				$query1 = sprintf('CREATE TEMPORARY TABLE %s AS %s %s', $uniqueName, $tempTableInfo['query'], $reportConditions);
			}
			$adb->pquery($query1, array());

			$keyColumns = $tempTableInfo['keycolumns'];
			foreach ($keyColumns as $keyColumn) {
				$query2 = sprintf('ALTER TABLE %s ADD INDEX (%s)', $uniqueName, $keyColumn);
				$adb->pquery($query2, array());
			}
		}

		$adb->dieOnError = $oldDieOnError;

		// Trigger cleanup of temporary tables when the execution of the request ends.
		// NOTE: This works better than having in __destruct
		// (as the reference to this object might end pre-maturely even before query is executed)
		if (!$this->registeredCleanup) {
			register_shutdown_function(array($this, 'cleanup'));
			// To avoid duplicate registration on this instance.
			$this->registeredCleanup = true;
		}
	}

	function cleanup() {
		global $adb;

		$oldDieOnError = $adb->dieOnError;
		$adb->dieOnError = false; // To avoid abnormal termination during shutdown...
		foreach ($this->tempTables as $uniqueName => $tempTableInfo) {
			$adb->pquery('DROP TABLE ' . $uniqueName, array());
		}
		$adb->dieOnError = $oldDieOnError;

		$this->tempTables = array();
	}

	/**
	 * Function to get report condition query for generating temporary table based on condition given on report.
	 * It generates condition query by considering fields of $module's base table or jo_crmentity table fields.
	 * It doesn't add condition for reference fields in query.
	 * @param String $module Module name for which temporary table is generated (Reports secondary module)
	 * @return string Returns condition query for generating temporary table.
	 */
	function getReportConditions($module) {
		$db = PearDatabase::getInstance();
		$moduleModel = Head_Module_Model::getInstance($module);
		$moduleBaseTable = $moduleModel->get('basetable');
		$reportId = $this->reportRun->reportid;
		if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'generate') {
			$advanceFilter = $_REQUEST['advanced_filter'];
			$advfilterlist = transformAdvFilterListToDBFormat(json_decode($advanceFilter, true));
		} else {
			$advfilterlist = $this->reportRun->getAdvFilterList($reportId);
		}
		$newAdvFilterList = array();
		$k = 0;

		foreach ($advfilterlist as $i => $columnConditions) {
			$conditionGroup = $advfilterlist[$i]['columns'];
			reset($conditionGroup);
			$firstConditionKey = key($conditionGroup);
			$oldColumnCondition = $advfilterlist[$i]['columns'][$firstConditionKey]['column_condition'];
			foreach ($columnConditions['columns'] as $j => $condition) {
				$columnName = $condition['columnname'];
				$columnParts = explode(':', $columnName);
				list($moduleName, $fieldLabel) = explode('_', $columnParts[2], 2);
				$fieldInfo = getFieldByReportLabel($moduleName, $columnParts[3], 'name');
				if(!empty($fieldInfo)) {
					$fieldInstance = WebserviceField::fromArray($db, $fieldInfo);
					$dataType = $fieldInstance->getFieldDataType();
					$uiType = $fieldInfo['uitype'];
					$fieldTable = $fieldInfo['tablename'];
					$allowedTables = array('jo_crmentity', $moduleBaseTable);
					$columnCondition = $advfilterlist[$i]['columns'][$j]['column_condition'];
					if (!in_array($fieldTable, $allowedTables) || $moduleName != $module || isReferenceUIType($uiType) || $columnCondition == 'or' || $oldColumnCondition == 'or' || in_array($dataType, array('reference', 'multireference'))) {
						$oldColumnCondition = $advfilterlist[$i]['columns'][$j]['column_condition'];
					} else {
						$columnParts[0] = $fieldTable;
						$newAdvFilterList[$i]['columns'][$k]['columnname'] = implode(':', $columnParts);
						$newAdvFilterList[$i]['columns'][$k]['comparator'] = $advfilterlist[$i]['columns'][$j]['comparator'];
						$newAdvFilterList[$i]['columns'][$k]['value'] = $advfilterlist[$i]['columns'][$j]['value'];
						$newAdvFilterList[$i]['columns'][$k++]['column_condition'] = $oldColumnCondition;
					}
				}
			}
			if (count($newAdvFilterList[$i])) {
				$newAdvFilterList[$i]['condition'] = $advfilterlist[$i]['condition'];
			}
			if (isset($newAdvFilterList[$i]['columns'][$k - 1])) {
				$newAdvFilterList[$i]['columns'][$k - 1]['column_condition'] = '';
			}
			if (count($newAdvFilterList[$i]) != 2) {
				unset($newAdvFilterList[$i]);
			}
		}
		end($newAdvFilterList);
		$lastConditionsGrpKey = key($newAdvFilterList);
		if (count($newAdvFilterList[$lastConditionsGrpKey])) {
			$newAdvFilterList[$lastConditionsGrpKey]['condition'] = '';
		}

		$advfiltersql = $this->reportRun->generateAdvFilterSql($newAdvFilterList);
		if ($advfiltersql && !empty($advfiltersql)) {
			$advfiltersql = ' AND ' . $advfiltersql;
		}
		return $advfiltersql;
	}

}

class ReportRun extends CRMEntity {

	// Maximum rows that should be emitted in HTML view.
	static $HTMLVIEW_MAX_ROWS = 1000;
	var $reportid;
	var $primarymodule;
	var $secondarymodule;
	var $orderbylistsql;
	var $orderbylistcolumns;
	var $selectcolumns;
	var $groupbylist;
	var $reporttype;
	var $reportname;
	var $totallist;
	var $_groupinglist = false;
    var $_groupbycondition = false;
    var $_reportquery = false;
    var $_tmptablesinitialized = false;
	var $_columnslist = false;
	var $_stdfilterlist = false;
	var $_columnstotallist = false;
	var $_advfiltersql = false;
	// All UItype 72 fields are added here so that in reports the values are append currencyId::value
	var $append_currency_symbol_to_value = array('Products_Unit_Price', 'Services_Price',
		'Invoice_Total', 'Invoice_Sub_Total', 'Invoice_Pre_Tax_Total', 'Invoice_S&H_Amount', 'Invoice_Discount_Amount', 'Invoice_Adjustment',
		'Quotes_Total', 'Quotes_Sub_Total', 'Quotes_Pre_Tax_Total', 'Quotes_S&H_Amount', 'Quotes_Discount_Amount', 'Quotes_Adjustment',
		'SalesOrder_Total', 'SalesOrder_Sub_Total', 'SalesOrder_Pre_Tax_Total', 'SalesOrder_S&H_Amount', 'SalesOrder_Discount_Amount', 'SalesOrder_Adjustment',
		'PurchaseOrder_Total', 'PurchaseOrder_Sub_Total', 'PurchaseOrder_Pre_Tax_Total', 'PurchaseOrder_S&H_Amount', 'PurchaseOrder_Discount_Amount', 'PurchaseOrder_Adjustment',
		'Invoice_Received', 'PurchaseOrder_Paid', 'Invoice_Balance', 'PurchaseOrder_Balance'
	);
	var $ui10_fields = array();
	var $ui101_fields = array();
	var $groupByTimeParent = array('Quarter' => array('Year'),
		'Month' => array('Year')
	);
	var $queryPlanner = null;
	protected static $instances = false;
	// Added to support line item fields calculation, if line item fields
	// are selected then module fields cannot be selected and vice versa
	var $lineItemFieldsInCalculation = false;

	/** Function to set reportid,primarymodule,secondarymodule,reporttype,reportname, for given reportid
	 *  This function accepts the $reportid as argument
	 *  It sets reportid,primarymodule,secondarymodule,reporttype,reportname for the given reportid
	 *  To ensure single-instance is present for $reportid
	 *  as we optimize using ReportRunPlanner and setup temporary tables.
	 */
	function ReportRun($reportid) {
		$oReport = new Reports($reportid);
		$this->reportid = $reportid;
		$this->primarymodule = $oReport->primodule;
		$this->secondarymodule = $oReport->secmodule;
		$this->reporttype = $oReport->reporttype;
		$this->reportname = $oReport->reportname;
		$this->queryPlanner = new ReportRunQueryPlanner();
		$this->queryPlanner->reportRun = $this;
	}

	public static function getInstance($reportid) {
		if (!isset(self::$instances[$reportid])) {
			self::$instances[$reportid] = new ReportRun($reportid);
		}
		return self::$instances[$reportid];
	}

	/** Function to get the columns for the reportid
	 *  This function accepts the $reportid and $outputformat (optional)
	 *  This function returns  $columnslist Array($tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname As Header value,
	 * 					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 As Header value,
	 * 					      					|
	 * 					      $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen As Header value
	 * 				      	     )
	 *
	 */
	function getQueryColumnsList($reportid, $outputformat = '') {
		// Have we initialized information already?
		if ($this->_columnslist !== false) {
			return $this->_columnslist;
		}

		global $adb;
		global $modules;
		global $log, $current_user, $current_language;
		$ssql = "select jo_selectcolumn.* from jo_report inner join jo_selectquery on jo_selectquery.queryid = jo_report.queryid";
		$ssql .= " left join jo_selectcolumn on jo_selectcolumn.queryid = jo_selectquery.queryid";
		$ssql .= " where jo_report.reportid = ?";
		$ssql .= " order by jo_selectcolumn.columnindex";
		$result = $adb->pquery($ssql, array($reportid));
		$permitted_fields = Array();

        $selectedModuleFields = array();
        $get_userdetails = get_privileges($current_user->id);
        foreach ($get_userdetails as $key => $value) {
            if(is_object($value)){
                $value = (array) $value;
                foreach ($value as $decode_key => $decode_value) {
                    if(is_object($decode_value)){
                        $value[$decode_key] = (array) $decode_value;
                    }
                }
                $$key = $value;
                }else{
                    $$key = $value;
                }
        }
		while ($columnslistrow = $adb->fetch_array($result)) {
			$fieldname = "";
			$fieldcolname = $columnslistrow["columnname"];
			list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
			list($module, $field) = split("_", $module_field, 2);
            $selectedModuleFields[$module][] = $fieldname;
			$inventory_fields = array('serviceid');
			$inventory_modules = getInventoryModules();
			if (sizeof($permitted_fields[$module]) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1) {
				$permitted_fields[$module] = $this->getaccesfield($module);
			}
			if (in_array($module, $inventory_modules)) {
				if (!empty($permitted_fields)) {
					foreach ($inventory_fields as $value) {
						array_push($permitted_fields[$module], $value);
					}
				}
			}
			$selectedfields = explode(":", $fieldcolname);
			if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && !in_array($selectedfields[3], $permitted_fields[$module])) {
				//user has no access to this field, skip it.
				continue;
			}
			$querycolumns = $this->getEscapedColumns($selectedfields);
			if (isset($module) && $module != "") {
				$mod_strings = return_module_language($current_language, $module);
			}

			$targetTableName = $tablename;

			$fieldlabel = trim(preg_replace("/$module/", " ", $selectedfields[2], 1));
			$mod_arr = explode('_', $fieldlabel);
			$fieldlabel = trim(str_replace("_", " ", $fieldlabel));
			//modified code to support i18n issue
			$fld_arr = explode(" ", $fieldlabel);
			if (($mod_arr[0] == '')) {
				$mod = $module;
				$mod_lbl = getTranslatedString($module, $module); //module
			} else {
				$mod = $mod_arr[0];
				array_shift($fld_arr);
				$mod_lbl = getTranslatedString($fld_arr[0], $mod); //module
			}
			$fld_lbl_str = implode(" ", $fld_arr);
			$fld_lbl = getTranslatedString($fld_lbl_str, $module); //fieldlabel
			$fieldlabel = $mod_lbl . " " . $fld_lbl;
			if (($selectedfields[0] == "jo_usersRel1") && ($selectedfields[1] == 'user_name') && ($selectedfields[2] == 'Quotes_Inventory_Manager')) {
				$concatSql = getSqlForNameInDisplayFormat(array('first_name' => $selectedfields[0] . ".first_name", 'last_name' => $selectedfields[0] . ".last_name"), 'Users');
				$columnslist[$fieldcolname] = "trim( $concatSql ) as " . $module . "_Inventory_Manager";
				$this->queryPlanner->addTable($selectedfields[0]);
				continue;
			} 
			if ((CheckFieldPermission($fieldname, $mod) != 'true' && $colname != "crmid" && (!in_array($fieldname, $inventory_fields) && in_array($module, $inventory_modules))) || empty($fieldname)) {
				continue;
			} else {
				$this->labelMapping[$selectedfields[2]] = str_replace(" ", "_", $fieldlabel);

				// To check if the field in the report is a custom field
				// and if yes, get the label of this custom field freshly from the jo_field as it would have been changed.
				// Asha - Reference ticket : #4906

				if ($querycolumns == "") {
					$columnslist[$fieldcolname] = $this->getColumnSQL($selectedfields);
				} else {
					$columnslist[$fieldcolname] = $querycolumns;
				}

				$this->queryPlanner->addTable($targetTableName);
			}
		}

		if ($outputformat == "HTML" || $outputformat == "PDF" || $outputformat == "PRINT") {
			if($this->primarymodule == 'ModComments') {
				$columnslist['jo_modcomments:related_to:ModComments_Related_To_Id:related_to:V'] = "jo_modcomments.related_to AS '".$this->primarymodule."_LBL_ACTION'";
			} else {
				$columnslist['jo_crmentity:crmid:LBL_ACTION:crmid:I'] = 'jo_crmentity.crmid AS "' . $this->primarymodule . '_LBL_ACTION"';
			}
			if ($this->secondarymodule) {
				$secondaryModules = explode(':', $this->secondarymodule);
				foreach ($secondaryModules as $secondaryModule) {
                    $columnsSelected = (array)$selectedModuleFields[$secondaryModule];
					$moduleModel = Head_Module_Model::getInstance($secondaryModule);
                    /**
                     * To check whether any column is selected from secondary module. If so, then only add 
                     * that module table to query planner
                     */
                    $moduleFields = $moduleModel->getFields();
                    $moduleFieldNames = array_keys($moduleFields);
                    $commonFields = array_intersect($moduleFieldNames, $columnsSelected);
                    if(count($commonFields) > 0){
						$baseTable = $moduleModel->get('basetable');
						$this->queryPlanner->addTable($baseTable);
						if ($secondaryModule == "Emails") {
							$baseTable .= "Emails";
						}
						$baseTableId = $moduleModel->get('basetableid');
						$columnslist[$baseTable . ":" . $baseTableId . ":" . $secondaryModule . ":" . $baseTableId . ":I"] = $baseTable . "." . $baseTableId . " AS " . $secondaryModule . "_LBL_ACTION";
					}
				}
			}
		}
		// Save the information
		$this->_columnslist = $columnslist;

		$log->info("ReportRun :: Successfully returned getQueryColumnsList" . $reportid);
		return $columnslist;
	}

	function getColumnSQL($selectedfields) {
		global $adb;
		$header_label = $selectedfields[2] = addslashes($selectedfields[2]); // Header label to be displayed in the reports table

		list($module, $field) = split("_", $selectedfields[2]);
		$concatSql = getSqlForNameInDisplayFormat(array('first_name' => $selectedfields[0] . ".first_name", 'last_name' => $selectedfields[0] . ".last_name"), 'Users');
		$emailTableName = "jo_activity";
		if ($module != $this->primarymodule) {
			$emailTableName .="Emails";
		}

		if ($selectedfields[0] == 'jo_inventoryproductrel') {

			if ($selectedfields[1] == 'discount_amount') {
				$columnSQL = "CASE WHEN (jo_inventoryproductreltmp{$module}.discount_amount != '') THEN jo_inventoryproductreltmp{$module}.discount_amount ELSE ROUND((jo_inventoryproductreltmp{$module}.listprice * jo_inventoryproductreltmp{$module}.quantity * (jo_inventoryproductreltmp{$module}.discount_percent/100)),3) END AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable($selectedfields[0].'tmp'.$module);
			} else if ($selectedfields[1] == 'productid') {
				$columnSQL = "CASE WHEN (jo_products{$module}.productname NOT LIKE '') THEN jo_products{$module}.productname ELSE jo_service{$module}.servicename END AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable("jo_products{$module}");
				$this->queryPlanner->addTable("jo_service{$module}");
			} else if ($selectedfields[1] == 'listprice') {
				$moduleInstance = CRMEntity::getInstance($module);
				$fieldName = $selectedfields[0] .'tmp'. $module . "." . $selectedfields[1];
				$columnSQL = "CASE WHEN jo_currency_info{$module}.id = jo_users{$module}.currency_id THEN $fieldName/jo_currency_info{$module}.conversion_rate ELSE $fieldName/$moduleInstance->table_name.conversion_rate END AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable($selectedfields[0] .'tmp'. $module);
				$this->queryPlanner->addTable('jo_currency_info' . $module);
				$this->queryPlanner->addTable('jo_users' . $module);
			} else if(in_array($this->primarymodule, array('Products', 'Services'))) {
				$columnSQL = $selectedfields[0] . 'tmp' . $module . ".$selectedfields[1] AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable($selectedfields[0] . $module);
			} else {
				if($selectedfields[0] == 'jo_inventoryproductrel'){
					$selectedfields[0] = $selectedfields[0]. 'tmp';
				}
				$columnSQL = $selectedfields[0] . $module . ".$selectedfields[1] AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable($selectedfields[0] . $module);
			}
		} else if($selectedfields[0] == 'jo_pricebookproductrel'){
			if ($selectedfields[1] == 'listprice') {
				$listPriceFieldName = $selectedfields[0].'tmp'. $module . "." . $selectedfields[1];
				$currencyPriceFieldName = $selectedfields[0].'tmp'. $module . "." . 'usedcurrency';
				$columnSQL = 'CONCAT('.$currencyPriceFieldName.",'::',". $listPriceFieldName .")". " AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable($selectedfields[0] .'tmp'. $module);
			}
		} else if ($selectedfields[4] == 'C') {
			$field_label_data = split("_", $selectedfields[2]);
			$module = $field_label_data[0];
			if ($module != $this->primarymodule) {
				$columnSQL = "case when (" . $selectedfields[0] . "." . $selectedfields[1] . "='1')then 'yes' else case when (jo_crmentity$module.crmid !='') then 'no' else '-' end end AS '" . decode_html($selectedfields[2]) . "'";
				$this->queryPlanner->addTable("jo_crmentity$module");
			} else {
				$columnSQL = "case when (" . $selectedfields[0] . "." . $selectedfields[1] . "='1')then 'yes' else case when (jo_crmentity.crmid !='') then 'no' else '-' end end AS '" . decode_html($selectedfields[2]) . "'";
				$this->queryPlanner->addTable($selectedfields[0]);
			}
		} elseif ($selectedfields[4] == 'D' || $selectedfields[4] == 'DT') {
			if ($selectedfields[5] == 'Y') {
				if ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'date_start') {
					if ($module == 'Emails') {
						$columnSQL = "YEAR(cast(concat($emailTableName.date_start,'  ',$emailTableName.time_start) as DATE)) AS Emails_Date_Sent_Year";
					} else {
						$columnSQL = "YEAR(cast(concat(jo_activity.date_start,'  ',jo_activity.time_start) as DATETIME)) AS Calendar_Start_Date_and_Time_Year";
					}
				} else if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
					$columnSQL = "YEAR(jo_crmentity." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Year'";
				} else {
					$columnSQL = 'YEAR(' . $selectedfields[0] . "." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Year'";
				}
				$this->queryPlanner->addTable($selectedfields[0]);
			} elseif ($selectedfields[5] == 'M') {
				if ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'date_start') {
					if ($module == 'Emails') {
						$columnSQL = "MONTHNAME(cast(concat($emailTableName.date_start,'  ',$emailTableName.time_start) as DATE)) AS Emails_Date_Sent_Month";
					} else {
						$columnSQL = "MONTHNAME(cast(concat(jo_activity.date_start,'  ',jo_activity.time_start) as DATETIME)) AS Calendar_Start_Date_and_Time_Month";
					}
				} else if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
					$columnSQL = "MONTHNAME(jo_crmentity." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Month'";
				} else {
					$columnSQL = 'MONTHNAME(' . $selectedfields[0] . "." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "_Month'";
				}
				$this->queryPlanner->addTable($selectedfields[0]);
			} elseif ($selectedfields[5] == 'W') {
				if ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'date_start') {
					if ($module == 'Emails') {
						$columnSQL = "CONCAT('Week ',WEEK(cast(concat($emailTableName.date_start,'  ',$emailTableName.time_start) as DATE), 1)) AS Emails_Date_Sent_Week";
					} else {
						$columnSQL = "CONCAT('Week ',WEEK(cast(concat(jo_activity.date_start,'  ',jo_activity.time_start) as DATETIME), 1)) AS Calendar_Start_Date_and_Time_Week";
					}
				} else if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
					$columnSQL = "CONCAT('Week ',WEEK(jo_crmentity." . $selectedfields[1] . ", 1)) AS '" . decode_html($header_label) . "_Week'";
				} else {
					$columnSQL = "CONCAT('Week ',WEEK(" . $selectedfields[0] . "." . $selectedfields[1] . ", 1)) AS '" . decode_html($header_label) . "_Week'";
				}
				$this->queryPlanner->addTable($selectedfields[0]);
			} elseif ($selectedfields[5] == 'MY') { // used in charts to get the year also, which will be used for click throughs
				if ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'date_start') {
					if ($module == 'Emails') {
						$columnSQL = "date_format(cast(concat($emailTableName.date_start,'  ',$emailTableName.time_start) as DATE), '%M %Y') AS Emails_Date_Sent_Month";
					} else {
						$columnSQL = "date_format(cast(concat(jo_activity.date_start,'  ',jo_activity.time_start) as DATETIME), '%M %Y') AS Calendar_Start_Date_and_Time_Month";
					}
				} else if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
					$columnSQL = "date_format(jo_crmentity." . $selectedfields[1] . ", '%M %Y') AS '" . decode_html($header_label) . "_Month'";
				} else {
					$columnSQL = 'date_format(' . $selectedfields[0] . "." . $selectedfields[1] . ", '%M %Y') AS '" . decode_html($header_label) . "_Month'";
				}
				$this->queryPlanner->addTable($selectedfields[0]);
			} else {
				if ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'date_start') {
					if ($module == 'Emails') {
						$columnSQL = "cast(concat($emailTableName.date_start,'  ',$emailTableName.time_start) as DATE) AS Emails_Date_Sent";
					} else {
						$columnSQL = "cast(concat(jo_activity.date_start,'  ',jo_activity.time_start) as DATETIME) AS Calendar_Start_Date_and_Time";
					}
				} else if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
					$columnSQL = "jo_crmentity." . $selectedfields[1] . " AS '" . decode_html($header_label) . "'";
				} else {
					$columnSQL = $selectedfields[0] . "." . $selectedfields[1] . " AS '" . decode_html($header_label) . "'";
				}
				$this->queryPlanner->addTable($selectedfields[0]);
			}
		} elseif ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'status') {
			$columnSQL = " case when (jo_activity.status not like '') then jo_activity.status else jo_activity.eventstatus end AS Calendar_Status";
		} elseif ($selectedfields[0] == 'jo_activity' && $selectedfields[1] == 'date_start') {
			if ($module == 'Emails') {
				$columnSQL = "cast(concat($emailTableName.date_start,'  ',$emailTableName.time_start) as DATE) AS Emails_Date_Sent";
			} else {
				$columnSQL = "cast(concat(jo_activity.date_start,'  ',jo_activity.time_start) as DATETIME) AS Calendar_Start_Date_and_Time";
			}
		} elseif (stristr($selectedfields[0], "jo_users") && ($selectedfields[1] == 'user_name')) {
			$temp_module_from_tablename = str_replace("jo_users", "", $selectedfields[0]);
			if ($module != $this->primarymodule) {
				$condition = "and jo_crmentity" . $module . ".crmid!=''";
				$this->queryPlanner->addTable("jo_crmentity$module");
			} else {
				$condition = "and jo_crmentity.crmid!=''";
			}
			if ($temp_module_from_tablename == $module) {
				$concatSql = getSqlForNameInDisplayFormat(array('first_name' => $selectedfields[0] . ".first_name", 'last_name' => $selectedfields[0] . ".last_name"), 'Users');
				$columnSQL = " case when(" . $selectedfields[0] . ".last_name NOT LIKE '' $condition ) THEN " . $concatSql . " else jo_groups" . $module . ".groupname end AS '" . decode_html($header_label) . "'";
				$this->queryPlanner->addTable('jo_groups' . $module); // Auto-include the dependent module table.
			} else {//Some Fields can't assigned to groups so case avoided (fields like inventory manager)
				$columnSQL = $selectedfields[0] . ".user_name AS '" . decode_html($header_label) . "'";
			}
			$this->queryPlanner->addTable($selectedfields[0]);
		} elseif (stristr($selectedfields[0], "jo_crmentity") && ($selectedfields[1] == 'modifiedby')) {
			$targetTableName = 'jo_lastModifiedBy' . $module;
			$concatSql = getSqlForNameInDisplayFormat(array('last_name' => $targetTableName . '.last_name', 'first_name' => $targetTableName . '.first_name'), 'Users');
			$columnSQL = "trim($concatSql) AS $header_label";
			$this->queryPlanner->addTable("jo_crmentity$module");
			$this->queryPlanner->addTable($targetTableName);

			// Added when no fields from the secondary module is selected but lastmodifiedby field is selected
			$moduleInstance = CRMEntity::getInstance($module);
			$this->queryPlanner->addTable($moduleInstance->table_name);
		} else if (stristr($selectedfields[0], "jo_crmentity") && ($selectedfields[1] == 'smcreatorid')) {
			$targetTableName = 'jo_createdby' . $module;
			$concatSql = getSqlForNameInDisplayFormat(array('last_name' => $targetTableName . '.last_name', 'first_name' => $targetTableName . '.first_name'), 'Users');
			$columnSQL = "trim($concatSql) AS " . decode_html($header_label) . "";
			$this->queryPlanner->addTable("jo_crmentity$module");
			$this->queryPlanner->addTable($targetTableName);

			// Added when no fields from the secondary module is selected but creator field is selected
			$moduleInstance = CRMEntity::getInstance($module);
			$this->queryPlanner->addTable($moduleInstance->table_name);
		} elseif ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
			$columnSQL = "jo_crmentity." . $selectedfields[1] . " AS '" . decode_html($header_label) . "'";
		} elseif ($selectedfields[0] == 'jo_products' && $selectedfields[1] == 'unit_price') {
			$columnSQL = "concat(" . $selectedfields[0] . ".currency_id,'::',innerProduct.actual_unit_price) AS '" . decode_html($header_label) . "'";
			$this->queryPlanner->addTable("innerProduct");
		} elseif (in_array(decode_html($selectedfields[2]), $this->append_currency_symbol_to_value)) {
			if ($selectedfields[1] == 'discount_amount') {
				$columnSQL = "CONCAT(" . $selectedfields[0] . ".currency_id,'::', IF(" . $selectedfields[0] . ".discount_amount != ''," . $selectedfields[0] . ".discount_amount, (" . $selectedfields[0] . ".discount_percent/100) * " . $selectedfields[0] . ".subtotal)) AS " . decode_html($header_label);
			} else {
				$columnSQL = "concat(" . $selectedfields[0] . ".currency_id,'::'," . $selectedfields[0] . "." . $selectedfields[1] . ") AS '" . decode_html($header_label) . "'";
			}
		} elseif ($selectedfields[0] == 'jo_notes' && ($selectedfields[1] == 'filelocationtype' || $selectedfields[1] == 'filesize' || $selectedfields[1] == 'folderid' || $selectedfields[1] == 'filestatus')) {
			if ($selectedfields[1] == 'filelocationtype') {
				$columnSQL = "case " . $selectedfields[0] . "." . $selectedfields[1] . " when 'I' then 'Internal' when 'E' then 'External' else '-' end AS '" . decode_html($selectedfields[2]) . "'";
			} else if ($selectedfields[1] == 'folderid') {
				$columnSQL = "jo_attachmentsfolder.foldername AS '$selectedfields[2]'";
				$this->queryPlanner->addTable("jo_attachmentsfolder");
			} elseif ($selectedfields[1] == 'filestatus') {
				$columnSQL = "case " . $selectedfields[0] . "." . $selectedfields[1] . " when '1' then 'yes' when '0' then 'no' else '-' end AS '" . decode_html($selectedfields[2]) . "'";
			} elseif ($selectedfields[1] == 'filesize') {
				$columnSQL = "case " . $selectedfields[0] . "." . $selectedfields[1] . " when '' then '-' else concat(" . $selectedfields[0] . "." . $selectedfields[1] . "/1024,'  ','KB') end AS '" . decode_html($selectedfields[2]) . "'";
			}
		} else {
			$tableName = $selectedfields[0];
			if ($module != $this->primarymodule && $module == "Emails" && $tableName == "jo_activity") {
				$tableName = $emailTableName;
			}
			$columnSQL = $tableName . "." . $selectedfields[1] . " AS '" . decode_html($header_label) . "'";
			$this->queryPlanner->addTable($selectedfields[0]);
		}
		return $columnSQL;
	}

	/** Function to get field columns based on profile
	 *  @ param $module : Type string
	 *  returns permitted fields in array format
	 */
	function getaccesfield($module) {
		global $current_user;
		global $adb;
		$access_fields = Array();

		$profileList = getCurrentUserProfileList();
		$query = "select jo_field.fieldname from jo_field inner join jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid where";
		$params = array();
		if ($module == "Calendar") {
			if (count($profileList) > 0) {
				$query .= " jo_field.tabid in (9,16) and jo_field.displaytype in (1,2,3) and jo_profile2field.visible=0 and jo_def_org_field.visible=0
								and jo_field.presence IN (0,2) and jo_profile2field.profileid in (" . generateQuestionMarks($profileList) . ") group by jo_field.fieldid order by block,sequence";
				array_push($params, $profileList);
			} else {
				$query .= " jo_field.tabid in (9,16) and jo_field.displaytype in (1,2,3) and jo_profile2field.visible=0 and jo_def_org_field.visible=0
								and jo_field.presence IN (0,2) group by jo_field.fieldid order by block,sequence";
			}
		} else {
			array_push($params, $module);
			if (count($profileList) > 0) {
				$query .= " jo_field.tabid in (select tabid from jo_tab where jo_tab.name in (?)) and jo_field.displaytype in (1,2,3,5) and jo_profile2field.visible=0
								and jo_field.presence IN (0,2) and jo_def_org_field.visible=0 and jo_profile2field.profileid in (" . generateQuestionMarks($profileList) . ") group by jo_field.fieldid order by block,sequence";
				array_push($params, $profileList);
			} else {
				$query .= " jo_field.tabid in (select tabid from jo_tab where jo_tab.name in (?)) and jo_field.displaytype in (1,2,3,5) and jo_profile2field.visible=0
								and jo_field.presence IN (0,2) and jo_def_org_field.visible=0 group by jo_field.fieldid order by block,sequence";
			}
		}
		$result = $adb->pquery($query, $params);

		while ($collistrow = $adb->fetch_array($result)) {
			$access_fields[] = $collistrow["fieldname"];
		}
		//added to include ticketid for Reports module in select columnlist for all users
		if ($module == "HelpDesk")
			$access_fields[] = "ticketid";
		return $access_fields;
	}

	/** Function to get Escapedcolumns for the field in case of multiple parents
	 *  @ param $selectedfields : Type Array
	 *  returns the case query for the escaped columns
	 */
	function getEscapedColumns($selectedfields) {

		$tableName = $selectedfields[0];
		$columnName = $selectedfields[1];
		$moduleFieldLabel = $selectedfields[2];
		$fieldName = $selectedfields[3];
		list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
		$fieldInfo = getFieldByReportLabel($moduleName, $fieldLabel);

		if ($moduleName == 'ModComments' && $fieldName == 'creator') {
			$concatSql = getSqlForNameInDisplayFormat(array('first_name' => 'jo_usersModComments.first_name',
				'last_name' => 'jo_usersModComments.last_name'), 'Users');
			$queryColumn = "trim(case when (jo_usersModComments.user_name not like '' and jo_crmentity.crmid!='') then $concatSql end) AS ModComments_Creator";
			$this->queryPlanner->addTable('jo_usersModComments');
			$this->queryPlanner->addTable("jo_usersModComments");
		} elseif ((($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype'])) && $fieldInfo['tablename'] != 'jo_inventoryproductrel') && $fieldInfo['uitype'] != '52' && $fieldInfo['uitype'] != '53') {
			$fieldSqlColumns = $this->getReferenceFieldColumnList($moduleName, $fieldInfo);
			if (count($fieldSqlColumns) > 0) {
				$queryColumn = "(CASE WHEN $tableName.$columnName NOT LIKE '' THEN (CASE";
				foreach ($fieldSqlColumns as $columnSql) {
					$queryColumn .= " WHEN $columnSql NOT LIKE '' THEN $columnSql";
				}
				$queryColumn .= " ELSE '' END) ELSE '' END) AS '".decode_html($moduleFieldLabel)."'";
				$this->queryPlanner->addTable($tableName);
			}
		}
		return $queryColumn;
	}

	/** Function to get selectedcolumns for the given reportid
	 *  @ param $reportid : Type Integer
	 *  returns the query of columnlist for the selected columns
	 */
	function getSelectedColumnsList($reportid) {

		global $adb;
		global $modules;
		global $log;

		$ssql = "select jo_selectcolumn.* from jo_report inner join jo_selectquery on jo_selectquery.queryid = jo_report.queryid";
		$ssql .= " left join jo_selectcolumn on jo_selectcolumn.queryid = jo_selectquery.queryid where jo_report.reportid = ? ";
		$ssql .= " order by jo_selectcolumn.columnindex";

		$result = $adb->pquery($ssql, array($reportid));
		$noofrows = $adb->num_rows($result);

		if ($this->orderbylistsql != "") {
			$sSQL .= $this->orderbylistsql . ", ";
		}

		for ($i = 0; $i < $noofrows; $i++) {
			$fieldcolname = $adb->query_result($result, $i, "columnname");
			$ordercolumnsequal = true;
			if ($fieldcolname != "") {
				for ($j = 0; $j < count($this->orderbylistcolumns); $j++) {
					if ($this->orderbylistcolumns[$j] == $fieldcolname) {
						$ordercolumnsequal = false;
						break;
					} else {
						$ordercolumnsequal = true;
					}
				}
				if ($ordercolumnsequal) {
					$selectedfields = explode(":", $fieldcolname);
					if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule)
						$selectedfields[0] = "jo_crmentity";
					$sSQLList[] = $selectedfields[0] . "." . $selectedfields[1] . " '" . $selectedfields[2] . "'";
				}
			}
		}
		$sSQL .= implode(",", $sSQLList);

		$log->info("ReportRun :: Successfully returned getSelectedColumnsList" . $reportid);
		return $sSQL;
	}

	/** Function to get advanced comparator in query form for the given Comparator and value
	 *  @ param $comparator : Type String
	 *  @ param $value : Type String
	 *  returns the check query for the comparator
	 */
	function getAdvComparator($comparator, $value, $datatype = "", $columnName = '') {

		global $log, $adb, $default_charset, $ogReport;
		$value = html_entity_decode(trim($value), ENT_QUOTES, $default_charset);
		$value_len = strlen($value);
		$is_field = false;
		if ($value_len > 1 && $value[0] == '$' && $value[$value_len - 1] == '$') {
			$temp = str_replace('$', '', $value);
			$is_field = true;
		}
		if ($datatype == 'C') {
			$value = str_replace("yes", "1", str_replace("no", "0", $value));
		}

		if ($is_field == true) {
			$value = $this->getFilterComparedField($temp);
		}
		if ($comparator == "e" || $comparator == 'y') {
			if (trim($value) == "NULL") {
				$rtvalue = " is NULL";
			} elseif (trim($value) != "") {
				$rtvalue = " = " . $adb->quote($value);
			} elseif (trim($value) == "" && $datatype == "V") {
				$rtvalue = " = " . $adb->quote($value);
			} else {
				$rtvalue = " is NULL";
			}
		}
		if ($comparator == "n" || $comparator == 'ny') {
			if (trim($value) == "NULL") {
				$rtvalue = " is NOT NULL";
			} elseif (trim($value) != "") {
				if ($columnName)
					$rtvalue = " <> " . $adb->quote($value) . " OR " . $columnName . " IS NULL ";
				else
					$rtvalue = " <> " . $adb->quote($value);
			}elseif (trim($value) == "" && $datatype == "V") {
				$rtvalue = " <> " . $adb->quote($value);
			} else {
				$rtvalue = " is NOT NULL";
			}
		}
		if ($comparator == "s") {
			$rtvalue = " like '" . formatForSqlLike($value, 2, $is_field) . "'";
		}
		if ($comparator == "ew") {
			$rtvalue = " like '" . formatForSqlLike($value, 1, $is_field) . "'";
		}
		if ($comparator == "c") {
			$rtvalue = " like '" . formatForSqlLike($value, 0, $is_field) . "'";
		}
		if ($comparator == "k") {
			$rtvalue = " not like '" . formatForSqlLike($value, 0, $is_field) . "'";
		}
		if ($comparator == "l") {
			$rtvalue = " < " . $adb->quote($value);
		}
		if ($comparator == "g") {
			$rtvalue = " > " . $adb->quote($value);
		}
		if ($comparator == "m") {
			$rtvalue = " <= " . $adb->quote($value);
		}
		if ($comparator == "h") {
			$rtvalue = " >= " . $adb->quote($value);
		}
		if ($comparator == "b") {
			$rtvalue = " < " . $adb->quote($value);
		}
		if ($comparator == "a") {
			$rtvalue = " > " . $adb->quote($value);
		}
		if ($is_field == true) {
			$rtvalue = str_replace("'", "", $rtvalue);
			$rtvalue = str_replace("\\", "", $rtvalue);
		}
		$log->info("ReportRun :: Successfully returned getAdvComparator");
		return $rtvalue;
	}

	/** Function to get field that is to be compared in query form for the given Comparator and field
	 *  @ param $field : field
	 *  returns the value for the comparator
	 */
	function getFilterComparedField($field) {
		global $adb, $ogReport;
		if (!empty($this->secondarymodule)) {
			$secModules = explode(':', $this->secondarymodule);
			foreach ($secModules as $secModule) {
				$secondary = CRMEntity::getInstance($secModule);
				$this->queryPlanner->addTable($secondary->table_name);
			}
		}
		$field = split('#', $field);
		$module = $field[0];
		$fieldname = trim($field[1]);
		$tabid = getTabId($module);
		$field_query = $adb->pquery("SELECT tablename,columnname,typeofdata,fieldname,uitype FROM jo_field WHERE tabid = ? AND fieldname= ?", array($tabid, $fieldname));
		$fieldtablename = $adb->query_result($field_query, 0, 'tablename');
		$fieldcolname = $adb->query_result($field_query, 0, 'columnname');
		$typeofdata = $adb->query_result($field_query, 0, 'typeofdata');
		$fieldtypeofdata = ChangeTypeOfData_Filter($fieldtablename, $fieldcolname, $typeofdata[0]);
		$uitype = $adb->query_result($field_query, 0, 'uitype');
		/* if($tr[0]==$ogReport->primodule)
		  $value = $adb->query_result($field_query,0,'tablename').".".$adb->query_result($field_query,0,'columnname');
		  else
		  $value = $adb->query_result($field_query,0,'tablename').$tr[0].".".$adb->query_result($field_query,0,'columnname');
		 */
		if ($uitype == 68 || $uitype == 59) {
			$fieldtypeofdata = 'V';
		}
		if ($fieldtablename == "jo_crmentity" && $module != $this->primarymodule) {
			$fieldtablename = $fieldtablename . $module;
		}
		if ($fieldname == "assigned_user_id") {
			$fieldtablename = "jo_users" . $module;
			$fieldcolname = "user_name";
		}
		if ($fieldtablename == "jo_crmentity" && $fieldname == "modifiedby") {
			$fieldtablename = "jo_lastModifiedBy" . $module;
			$fieldcolname = "user_name";
		}
		if ($fieldname == "assigned_user_id1") {
			$fieldtablename = "jo_usersRel1";
			$fieldcolname = "user_name";
		}

		$value = $fieldtablename . "." . $fieldcolname;

		$this->queryPlanner->addTable($fieldtablename);
		return $value;
	}

	/** Function to get the advanced filter columns for the reportid
	 *  This function accepts the $reportid
	 *  This function returns  $columnslist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
	 * 					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
	 * 					      					|
	 * 					      $tablenamen:$columnnamen:$fieldlabeln:$fieldnamen:$typeofdatan=>$tablenamen.$columnnamen filtercriteria
	 * 				      	     )
	 *
	 */
	function getAdvFilterList($reportid, $forClickThrough = false) {
		global $adb, $log;

		$advft_criteria = array();

		// Not a good approach to get all the fields if not required(May leads to Performance issue)
		$sql = 'SELECT groupid,group_condition FROM jo_relcriteria_grouping WHERE queryid = ? ORDER BY groupid';
		$groupsresult = $adb->pquery($sql, array($reportid));

		$i = 1;
		$j = 0;
		while ($relcriteriagroup = $adb->fetch_array($groupsresult)) {
			$groupId = $relcriteriagroup["groupid"];
			$groupCondition = $relcriteriagroup["group_condition"];

			$ssql = 'select jo_relcriteria.* from jo_report
						inner join jo_relcriteria on jo_relcriteria.queryid = jo_report.queryid
						left join jo_relcriteria_grouping on jo_relcriteria.queryid = jo_relcriteria_grouping.queryid
								and jo_relcriteria.groupid = jo_relcriteria_grouping.groupid';
			$ssql.= " where jo_report.reportid = ? AND jo_relcriteria.groupid = ? order by jo_relcriteria.columnindex";

			$result = $adb->pquery($ssql, array($reportid, $groupId));
			$noOfColumns = $adb->num_rows($result);
			if ($noOfColumns <= 0)
				continue;

			while ($relcriteriarow = $adb->fetch_array($result)) {
				$columnIndex = $relcriteriarow["columnindex"];
				$criteria = array();
				$criteria['columnname'] = html_entity_decode($relcriteriarow["columnname"]);
				$criteria['comparator'] = $relcriteriarow["comparator"];
				$advfilterval = $relcriteriarow["value"];
				$col = explode(":",$relcriteriarow["columnname"]);
				$criteria['value'] = $advfilterval;
				$criteria['column_condition'] = $relcriteriarow["column_condition"];

				$advft_criteria[$i]['columns'][$j] = $criteria;
				$advft_criteria[$i]['condition'] = $groupCondition;
				$j++;

				$this->queryPlanner->addTable($col[0]);
			}
			if (!empty($advft_criteria[$i]['columns'][$j - 1]['column_condition'])) {
				$advft_criteria[$i]['columns'][$j - 1]['column_condition'] = '';
			}
			$i++;
		}
		// Clear the condition (and/or) for last group, if any.
		if (!empty($advft_criteria[$i - 1]['condition']))
			$advft_criteria[$i - 1]['condition'] = '';
		return $advft_criteria;
	}

	function generateAdvFilterSql($advfilterlist) {

		global $adb;

		$advfiltersql = "";
		$customView = new CustomView();
		$dateSpecificConditions = $customView->getStdFilterConditions();
		$specialDateComparators = array('yesterday', 'today', 'tomorrow');
		foreach ($advfilterlist as $groupindex => $groupinfo) {
			$groupcondition = $groupinfo['condition'];
			$groupcolumns = $groupinfo['columns'];

			if (count($groupcolumns) > 0) {

				$advfiltergroupsql = "";
				foreach ($groupcolumns as $columnindex => $columninfo) {
					$fieldcolname = $columninfo["columnname"];
					$comparator = $columninfo["comparator"];
					$value = $columninfo["value"];
					$columncondition = $columninfo["column_condition"];
					$advcolsql = array();

					$selectedFields = explode(':', $fieldcolname);
					$moduleFieldLabel = $selectedFields[2];
					list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
					$emailTableName = '';
					if ($moduleName == "Emails" && $moduleName != $this->primarymodule && $selectedFields[0] == "jo_activity") {
						$emailTableName = "jo_activityEmails";
					}

					if ($fieldcolname != "" && $comparator != "") {
						if (in_array($comparator, $dateSpecificConditions)) {
							if ($fieldcolname != 'none') {
								$selectedFields = explode(':', $fieldcolname);
								if ($selectedFields[0] == 'jo_crmentity' . $this->primarymodule) {
									$selectedFields[0] = 'jo_crmentity';
								}

								if ($comparator != 'custom') {
									list($startDate, $endDate) = $this->getStandarFiltersStartAndEndDate($comparator);
								} else {
									list($startDateTime, $endDateTime) = explode(',', $value);
									list($startDate, $startTime) = explode(' ', $startDateTime);
									list($endDate, $endTime) = explode(' ', $endDateTime);
								}

								$type = $selectedFields[4];
								if ($startDate != '0000-00-00' && $endDate != '0000-00-00' && $startDate != '' && $endDate != '') {
									if ($type == 'DT') {
										$startDateTime = new DateTimeField($startDate . ' ' . date('H:i:s'));
										$endDateTime = new DateTimeField($endDate . ' ' . date('H:i:s'));
										$userStartDate = $startDateTime->getDisplayDate() . ' 00:00:00';
										$userEndDate = $endDateTime->getDisplayDate() . ' 23:59:59';
									} else if (in_array($comparator, $specialDateComparators)) {
										$startDateTime = new DateTimeField($startDate . ' ' . date('H:i:s'));
										$endDateTime = new DateTimeField($endDate . ' ' . date('H:i:s'));
										$userStartDate = $startDateTime->getDisplayDate();
										$userEndDate = $endDateTime->getDisplayDate();
									} else {
										$startDateTime = new DateTimeField($startDate);
										$endDateTime = new DateTimeField($endDate);
										$userStartDate = $startDateTime->getDisplayDate();
										$userEndDate = $endDateTime->getDisplayDate();
									}
									$startDateTime = getValidDBInsertDateTimeValue($userStartDate);
									$endDateTime = getValidDBInsertDateTimeValue($userEndDate);

									if ($selectedFields[1] == 'birthday') {
										$tableColumnSql = 'DATE_FORMAT(' . $selectedFields[0] . '.' . $selectedFields[1] . ', "%m%d")';
										$startDateTime = "DATE_FORMAT('$startDateTime', '%m%d')";
										$endDateTime = "DATE_FORMAT('$endDateTime', '%m%d')";
									} else {
										if ($selectedFields[0] == 'jo_activity' && ($selectedFields[1] == 'date_start')) {
											$tableColumnSql = 'CAST((CONCAT(date_start, " ", time_start)) AS DATETIME)';
										} else {
											if (empty($emailTableName)) {
												$tableColumnSql = $selectedFields[0] . '.' . $selectedFields[1];
											} else {
												$tableColumnSql = $emailTableName . '.' . $selectedFields[1];
											}
										}
										$startDateTime = "'$startDateTime'";
										$endDateTime = "'$endDateTime'";
									}

									$advfiltergroupsql .= "$tableColumnSql BETWEEN $startDateTime AND $endDateTime";
									if (!empty($columncondition)) {
										$advfiltergroupsql .= ' ' . $columncondition . ' ';
									}

									$this->queryPlanner->addTable($selectedFields[0]);
								}
							}
							continue;
						}
						$selectedFields = explode(":", $fieldcolname);
						$tempComparators = array('e', 'n', 'bw', 'a', 'b');
						$tempComparators = array_merge($tempComparators, Head_Functions::getSpecialDateTimeCondtions());
						if ($selectedFields[4] == 'DT' && in_array($comparator, $tempComparators)) {
							if ($selectedFields[0] == 'jo_crmentity' . $this->primarymodule) {
								$selectedFields[0] = 'jo_crmentity';
							}

							if ($selectedFields[0] == 'jo_activity' && ($selectedFields[1] == 'date_start')) {
								$tableColumnSql = 'CAST((CONCAT(date_start, " ", time_start)) AS DATETIME)';
							} else {
								if (empty($emailTableName)) {
									$tableColumnSql = $selectedFields[0] . '.' . $selectedFields[1];
								} else {
									$tableColumnSql = $emailTableName . '.' . $selectedFields[1];
								}
							}

							if ($value != null && $value != '') {
								if ($comparator == 'e' || $comparator == 'n') {
									$dateTimeComponents = explode(' ', $value);
									$dateTime = new DateTime($dateTimeComponents[0] . ' ' . '00:00:00');
									$date1 = $dateTime->format('Y-m-d H:i:s');
									$dateTime->modify("+1 days");
									$date2 = $dateTime->format('Y-m-d H:i:s');
									$tempDate = strtotime($date2) - 1;
									$date2 = date('Y-m-d H:i:s', $tempDate);

									$start = getValidDBInsertDateTimeValue($date1);
									$end = getValidDBInsertDateTimeValue($date2);
									$start = "'$start'";
									$end = "'$end'";
									if ($comparator == 'e')
										$advfiltergroupsql .= "$tableColumnSql BETWEEN $start AND $end";
									else
										$advfiltergroupsql .= "$tableColumnSql NOT BETWEEN $start AND $end";
								}else if ($comparator == 'bw') {
									$values = explode(',', $value);
									$startDateTime = explode(' ', $values[0]);
									$endDateTime = explode(' ', $values[1]);

									$startDateTime = new DateTimeField($startDateTime[0] . ' ' . date('H:i:s'));
									$userStartDate = $startDateTime->getDisplayDate();
									$userStartDate = $userStartDate . ' 00:00:00';
									$start = getValidDBInsertDateTimeValue($userStartDate);

									$endDateTime = new DateTimeField($endDateTime[0] . ' ' . date('H:i:s'));
									$userEndDate = $endDateTime->getDisplayDate();
									$userEndDate = $userEndDate . ' 23:59:59';
									$end = getValidDBInsertDateTimeValue($userEndDate);

									$advfiltergroupsql .= "$tableColumnSql BETWEEN '$start' AND '$end'";
								} else if (in_array($comparator, Head_Functions::getSpecialDateConditions())) {
									$values = EnhancedQueryGenerator::getSpecialDateConditionValue($comparator, $value, $selectedFields[4]);
									$tableColumnSql = $selectedFields[0] . '.' . $selectedFields[1];
									$condtionQuery = EnhancedQueryGenerator::getSpecialDateConditionQuery($values['comparator'], $values['date']);
									$advfiltergroupsql .= "date($tableColumnSql) $condtionQuery";
								} else if (in_array($comparator, Head_Functions::getSpecialTimeConditions())) {
									$values = EnhancedQueryGenerator::getSpecialDateConditionValue($comparator, $value, $selectedFields[4]);
									$condtionQuery = EnhancedQueryGenerator::getSpecialDateConditionQuery($values['comparator'], $values['date']);
									$advfiltergroupsql .= "$tableColumnSql $condtionQuery";
								} else if ($comparator == 'a' || $comparator == 'b') {
									$value = explode(' ', $value);
									$dateTime = new DateTime($value[0]);
									if ($comparator == 'a') {
										$modifiedDate = $dateTime->modify('+1 days');
										$nextday = $modifiedDate->format('Y-m-d H:i:s');
										$temp = strtotime($nextday) - 1;
										$date = date('Y-m-d H:i:s', $temp);
										$value = getValidDBInsertDateTimeValue($date);
										$advfiltergroupsql .= "$tableColumnSql > '$value'";
									} else {
										$prevday = $dateTime->format('Y-m-d H:i:s');
										$temp = strtotime($prevday) - 1;
										$date = date('Y-m-d H:i:s', $temp);
										$value = getValidDBInsertDateTimeValue($date);
										$advfiltergroupsql .= "$tableColumnSql < '$value'";
									}
								}
								if (!empty($columncondition)) {
									$advfiltergroupsql .= ' ' . $columncondition . ' ';
								}
								$this->queryPlanner->addTable($selectedFields[0]);
							} else if ($value == '') {
								$sqlComparator = $this->getAdvComparator($comparator, $value, 'DT');
								if($sqlComparator) {
									$advfiltergroupsql .= " ".$selectedFields[0].".".$selectedFields[1].$sqlComparator;
								} else {
									$advfiltergroupsql .= " " . $selectedFields[0] . "." . $selectedFields[1] . " = '' ";
								}
							}
							continue;
						}

						$selectedfields = explode(":", $fieldcolname);
						$moduleFieldLabel = $selectedfields[2];
						list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
						$fieldInfo = getFieldByReportLabel($moduleName, $selectedfields[3], 'name');
						$concatSql = getSqlForNameInDisplayFormat(array('first_name' => $selectedfields[0] . ".first_name", 'last_name' => $selectedfields[0] . ".last_name"), 'Users');
						// Added to handle the crmentity table name for Primary module
						if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
							$selectedfields[0] = "jo_crmentity";
						}
						//Added to handle yes or no for checkbox  field in reports advance filters. -shahul
						if ($selectedfields[4] == 'C') {
							if (strcasecmp(trim($value), "yes") == 0)
								$value = "1";
							if (strcasecmp(trim($value), "no") == 0)
								$value = "0";
						}
						if (in_array($comparator, $dateSpecificConditions)) {
							$customView = new CustomView($moduleName);
							$columninfo['stdfilter'] = $columninfo['comparator'];
							$valueComponents = explode(',', $columninfo['value']);
							if ($comparator == 'custom') {
								if ($selectedfields[4] == 'DT') {
									$startDateTimeComponents = explode(' ', $valueComponents[0]);
									$endDateTimeComponents = explode(' ', $valueComponents[1]);
									$columninfo['startdate'] = DateTimeField::convertToDBFormat($startDateTimeComponents[0]);
									$columninfo['enddate'] = DateTimeField::convertToDBFormat($endDateTimeComponents[0]);
								} else {
									$columninfo['startdate'] = DateTimeField::convertToDBFormat($valueComponents[0]);
									$columninfo['enddate'] = DateTimeField::convertToDBFormat($valueComponents[1]);
								}
							}
							$dateFilterResolvedList = $customView->resolveDateFilterValue($columninfo);
							$startDate = DateTimeField::convertToDBFormat($dateFilterResolvedList['startdate']);
							$endDate = DateTimeField::convertToDBFormat($dateFilterResolvedList['enddate']);
							$columninfo['value'] = $value = implode(',', array($startDate, $endDate));
							$comparator = 'bw';
						}
						$datatype = (isset($selectedfields[4])) ? $selectedfields[4] : "";
						$fieldDataType = '';

						$fields = array();
						$moduleModel = Head_Module_Model::getInstance($moduleName);
						if ($moduleModel) {
							$fields = $moduleModel->getFields();
							if ($fields && $selectedfields[3]) {
								$fieldModel = $fields[$selectedfields[3]];
								if ($fieldModel) {
									$fieldDataType = $fieldModel->getFieldDataType();
								}
							}
						}
						$commaSeparatedFieldTypes = array('picklist', 'multipicklist', 'owner', 'date', 'datetime', 'time');
						if(in_array($fieldDataType, $commaSeparatedFieldTypes)) {
							$valuearray = explode(",", trim($value));
						} else {
							$valuearray = array($value);
						}
						if (isset($valuearray) && count($valuearray) > 1 && $comparator != 'bw') {

							$advcolumnsql = "";
							for ($n = 0; $n < count($valuearray); $n++) {
								$secondaryModules = explode(':', $this->secondarymodule);
								$firstSecondaryModule = $secondaryModules[0];
								$secondSecondaryModule = $secondaryModules[1]; 
								if (($selectedfields[0] == "jo_users" . $this->primarymodule || ($firstSecondaryModule && $selectedfields[0] == "jo_users".$firstSecondaryModule) || ($secondSecondaryModule && $selectedfields[0] == "jo_users".$secondSecondaryModule)) && $selectedfields[1] == 'user_name') {
									$module_from_tablename = str_replace("jo_users", "", $selectedfields[0]);
									$advcolsql[] = " (trim($concatSql)" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype) . " or jo_groups" . $module_from_tablename . ".groupname " . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype) . ")";
									$this->queryPlanner->addTable("jo_groups" . $module_from_tablename);
								} elseif ($selectedfields[1] == 'status') {//when you use comma seperated values.
									if ($selectedfields[2] == 'Calendar_Status') {
										$advcolsql[] = "(case when (jo_activity.status not like '') then jo_activity.status else jo_activity.eventstatus end)" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
									} else if ($selectedfields[2] == 'HelpDesk_Status') {
										$advcolsql[] = "jo_troubletickets.status" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
									} else if ($selectedfields[2] == 'Faq_Status') {
										$advcolsql[] = "jo_faq.status" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
									} else
										$advcolsql[] = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
								} elseif ($selectedfields[1] == 'description') {//when you use comma seperated values.
									if ($selectedfields[0] == 'jo_crmentity' . $this->primarymodule)
										$advcolsql[] = "jo_crmentity.description" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
									else
										$advcolsql[] = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
								} elseif ($selectedfields[2] == 'Quotes_Inventory_Manager') {
									$advcolsql[] = ("trim($concatSql)" . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype));
								} elseif ($selectedfields[1] == 'modifiedby') {
									$module_from_tablename = str_replace("jo_crmentity", "", $selectedfields[0]);
									if ($module_from_tablename != '') {
										$tableName = 'jo_lastModifiedBy' . $module_from_tablename;
									} else {
										$tableName = 'jo_lastModifiedBy' . $this->primarymodule;
									}
									$advcolsql[] = 'trim(' . getSqlForNameInDisplayFormat(array('last_name' => "$tableName.last_name", 'first_name' => "$tableName.first_name"), 'Users') . ')' .
											$this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
								} elseif ($selectedfields[1] == 'smcreatorid') {
									$module_from_tablename = str_replace("jo_crmentity", "", $selectedfields[0]);
									if ($module_from_tablename != '') {
										$tableName = 'jo_createdby' . $module_from_tablename;
									} else {
										$tableName = 'jo_createdby' . $this->primarymodule;
									}
									if ($moduleName == 'ModComments') {
										$tableName = 'jo_users' . $moduleName;
									}
									$this->queryPlanner->addTable($tableName);
									$advcolsql[] = 'trim(' . getSqlForNameInDisplayFormat(array('last_name' => "$tableName.last_name", 'first_name' => "$tableName.first_name"), 'Users') . ')' .
											$this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
								} else {
									$advcolsql[] = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($valuearray[$n]), $datatype);
								}
							}
							//If negative logic filter ('not equal to', 'does not contain') is used, 'and' condition should be applied instead of 'or'
							if ($comparator == 'n' || $comparator == 'k')
								$advcolumnsql = implode(" and ", $advcolsql);
							else
								$advcolumnsql = implode(" or ", $advcolsql);
							$fieldvalue = " (" . $advcolumnsql . ") ";
						} elseif ($selectedfields[1] == 'user_name') {
							if ($selectedfields[0] == "jo_users" . $this->primarymodule) {
								$module_from_tablename = str_replace("jo_users", "", $selectedfields[0]);
								$fieldvalue = " trim(case when (" . $selectedfields[0] . ".last_name NOT LIKE '') then " . $concatSql . " else jo_groups" . $module_from_tablename . ".groupname end) " . $this->getAdvComparator($comparator, trim($value), $datatype);
								$this->queryPlanner->addTable("jo_groups" . $module_from_tablename);
							} else {
								$secondaryModules = explode(':', $this->secondarymodule);
								$firstSecondaryModule = "jo_users" . $secondaryModules[0];
								$secondSecondaryModule = "jo_users" . $secondaryModules[1];
								if (($firstSecondaryModule && $firstSecondaryModule == $selectedfields[0]) || ($secondSecondaryModule && $secondSecondaryModule == $selectedfields[0])) {
									$module_from_tablename = str_replace("jo_users", "", $selectedfields[0]);
									$moduleInstance = CRMEntity::getInstance($module_from_tablename);
									$fieldvalue = " trim(case when (" . $selectedfields[0] . ".last_name NOT LIKE '') then " . $concatSql . " else jo_groups" . $module_from_tablename . ".groupname end) " . $this->getAdvComparator($comparator, trim($value), $datatype);
									$this->queryPlanner->addTable("jo_groups" . $module_from_tablename);
									$this->queryPlanner->addTable($moduleInstance->table_name);
								}
							}
						} elseif ($comparator == 'bw' && count($valuearray) == 2) {
							if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
								$fieldvalue = "(" . "jo_crmentity." . $selectedfields[1] . " between '" . trim($valuearray[0]) . "' and '" . trim($valuearray[1]) . "')";
							} else {
								$fieldvalue = "(" . $selectedfields[0] . "." . $selectedfields[1] . " between '" . trim($valuearray[0]) . "' and '" . trim($valuearray[1]) . "')";
							}
						} elseif ($selectedfields[0] == "jo_crmentity" . $this->primarymodule) {
							$fieldvalue = "jo_crmentity." . $selectedfields[1] . " " . $this->getAdvComparator($comparator, trim($value), $datatype);
						} elseif ($selectedfields[2] == 'Quotes_Inventory_Manager') {
							$fieldvalue = ("trim($concatSql)" . $this->getAdvComparator($comparator, trim($value), $datatype));
						} elseif ($selectedfields[1] == 'modifiedby') {
							$module_from_tablename = str_replace("jo_crmentity", "", $selectedfields[0]);
							if ($module_from_tablename != '') {
								$tableName = 'jo_lastModifiedBy' . $module_from_tablename;
							} else {
								$tableName = 'jo_lastModifiedBy' . $this->primarymodule;
							}
							$this->queryPlanner->addTable($tableName);
							$fieldvalue = 'trim(' . getSqlForNameInDisplayFormat(array('last_name' => "$tableName.last_name", 'first_name' => "$tableName.first_name"), 'Users') . ')' .
									$this->getAdvComparator($comparator, trim($value), $datatype);
						} elseif ($selectedfields[1] == 'smcreatorid') {
							$module_from_tablename = str_replace("jo_crmentity", "", $selectedfields[0]);
							if ($module_from_tablename != '') {
								$tableName = 'jo_createdby' . $module_from_tablename;
							} else {
								$tableName = 'jo_createdby' . $this->primarymodule;
							}
							if ($moduleName == 'ModComments') {
								$tableName = 'jo_users' . $moduleName;
							}
							$this->queryPlanner->addTable($tableName);
							$fieldvalue = 'trim(' . getSqlForNameInDisplayFormat(array('last_name' => "$tableName.last_name", 'first_name' => "$tableName.first_name"), 'Users') . ')' .
									$this->getAdvComparator($comparator, trim($value), $datatype);
						} elseif ($selectedfields[0] == "jo_activity" && ($selectedfields[1] == 'status' || $selectedfields[1] == 'eventstatus')) {
							// for "Is Empty" condition we need to check with "value NOT NULL" OR "value = ''" conditions
							if ($comparator == 'y') {
								$fieldvalue = "(case when (jo_activity.status not like '') then jo_activity.status
                                                else jo_activity.eventstatus end) IS NULL OR (case when (jo_activity.status not like '')
                                                then jo_activity.status else jo_activity.eventstatus end) = ''";
							} else {
								$fieldvalue = "(case when (jo_activity.status not like '') then jo_activity.status
                                                else jo_activity.eventstatus end)" . $this->getAdvComparator($comparator, trim($value), $datatype);
							}
						} else if ($comparator == 'ny') {
							if ($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype']))
								$fieldvalue = "(" . $selectedfields[0] . "." . $selectedfields[1] . " IS NOT NULL AND " . $selectedfields[0] . "." . $selectedfields[1] . " != '' AND " . $selectedfields[0] . "." . $selectedfields[1] . "  != '0')";
							else
								$fieldvalue = "(" . $selectedfields[0] . "." . $selectedfields[1] . " IS NOT NULL AND " . $selectedfields[0] . "." . $selectedfields[1] . " != '')";
						}elseif ($comparator == 'y' || ($comparator == 'e' && (trim($value) == "NULL" || trim($value) == ''))) {
							if ($selectedfields[0] == 'jo_inventoryproductrel') {
								$selectedfields[0] = 'jo_inventoryproductreltmp' . $moduleName;
							}
							if ($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype']))
								$fieldvalue = "(" . $selectedfields[0] . "." . $selectedfields[1] . " IS NULL OR " . $selectedfields[0] . "." . $selectedfields[1] . " = '' OR " . $selectedfields[0] . "." . $selectedfields[1] . " = '0')";
							else
								$fieldvalue = "(" . $selectedfields[0] . "." . $selectedfields[1] . " IS NULL OR " . $selectedfields[0] . "." . $selectedfields[1] . " = '')";
						} elseif ($selectedfields[0] == 'jo_inventoryproductrel') {
							$selectedfields[0] = $selectedfields[0]. 'tmp';
							if ($selectedfields[1] == 'productid') {
								$fieldvalue = "(jo_products$moduleName.productname " . $this->getAdvComparator($comparator, trim($value), $datatype);
								$fieldvalue .= " OR jo_service$moduleName.servicename " . $this->getAdvComparator($comparator, trim($value), $datatype);
								$fieldvalue .= ")";
								$this->queryPlanner->addTable("jo_products$moduleName");
								$this->queryPlanner->addTable("jo_service$moduleName");
							} else {
								//for inventory module table should be follwed by the module name
								$selectedfields[0] = 'jo_inventoryproductreltmp' . $moduleName;
								$fieldvalue = $selectedfields[0] . "." . $selectedfields[1] . $this->getAdvComparator($comparator, $value, $datatype);
							}
						} elseif ($fieldInfo['uitype'] == '10' || isReferenceUIType($fieldInfo['uitype'])) {

							$fieldSqlColumns = $this->getReferenceFieldColumnList($moduleName, $fieldInfo);
							$comparatorValue = $this->getAdvComparator($comparator, trim($value), $datatype, $fieldSqlColumns[0]);
							$fieldSqls = array();

							foreach ($fieldSqlColumns as $columnSql) {
								$fieldSqls[] = $columnSql . $comparatorValue;
							}
							$fieldvalue = ' (' . implode(' OR ', $fieldSqls) . ') ';
						} else if (in_array($comparator, Head_Functions::getSpecialDateConditions())) {
							$values = EnhancedQueryGenerator::getSpecialDateConditionValue($comparator, $value, $selectedFields[4]);
							$tableColumnSql = $selectedFields[0] . '.' . $selectedFields[1];
							$condtionQuery = EnhancedQueryGenerator::getSpecialDateConditionQuery($values['comparator'], $values['date']);
							$fieldvalue = "date($tableColumnSql) $condtionQuery";
						} else if (in_array($comparator, Head_Functions::getSpecialTimeConditions())) {
							$values = EnhancedQueryGenerator::getSpecialDateConditionValue($comparator, $value, $selectedFields[4]);
							$condtionQuery = EnhancedQueryGenerator::getSpecialDateConditionQuery($values['comparator'], $values['date']);
							$fieldvalue = "$tableColumnSql $condtionQuery";
						} else {
							$selectFieldTableName = $selectedfields[0];
							if (!empty($emailTableName)) {
								$selectFieldTableName = $emailTableName;
							}
							$fieldvalue = $selectFieldTableName . "." . $selectedfields[1] . $this->getAdvComparator($comparator, trim($value), $datatype);
						}
						$advfiltergroupsql .= $fieldvalue;
						if (!empty($columncondition)) {
							$advfiltergroupsql .= ' ' . $columncondition . ' ';
						}

						$this->queryPlanner->addTable($selectedfields[0]);
					}
				}
				if (trim($advfiltergroupsql) != "") {
					$advfiltergroupsql = "( $advfiltergroupsql ) ";
					if (!empty($groupcondition)) {
						$advfiltergroupsql .= ' ' . $groupcondition . ' ';
					}

					$advfiltersql .= $advfiltergroupsql;
				}
			}
		}
		if (trim($advfiltersql) != "")
			$advfiltersql = '(' . $advfiltersql . ')';

		return $advfiltersql;
	}

	function getAdvFilterSql($reportid) {
		// Have we initialized information already?
		if ($this->_advfiltersql !== false) {
			return $this->_advfiltersql;
		}
		global $log;

		$advfilterlist = $this->getAdvFilterList($reportid);
		$advfiltersql = $this->generateAdvFilterSql($advfilterlist);

		// Save the information
		$this->_advfiltersql = $advfiltersql;

		$log->info("ReportRun :: Successfully returned getAdvFilterSql" . $reportid);
		return $advfiltersql;
	}

	/** Function to get the Standard filter columns for the reportid
	 *  This function accepts the $reportid datatype Integer
	 *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel:$fieldname:$typeofdata=>$tablename.$columnname filtercriteria,
	 * 					      $tablename1:$columnname1:$fieldlabel1:$fieldname1:$typeofdata1=>$tablename1.$columnname1 filtercriteria,
	 * 				      	     )
	 *
	 */
	function getStdFilterList($reportid) {
		// Have we initialized information already?
		if ($this->_stdfilterlist !== false) {
			return $this->_stdfilterlist;
		}

		global $adb, $log;
		$stdfilterlist = array();

		$stdfiltersql = "select jo_reportdatefilter.* from jo_report";
		$stdfiltersql .= " inner join jo_reportdatefilter on jo_report.reportid = jo_reportdatefilter.datefilterid";
		$stdfiltersql .= " where jo_report.reportid = ?";

		$result = $adb->pquery($stdfiltersql, array($reportid));
		$stdfilterrow = $adb->fetch_array($result);
		if (isset($stdfilterrow)) {
			$fieldcolname = $stdfilterrow["datecolumnname"];
			$datefilter = $stdfilterrow["datefilter"];
			$startdate = $stdfilterrow["startdate"];
			$enddate = $stdfilterrow["enddate"];

			if ($fieldcolname != "none") {
				$selectedfields = explode(":", $fieldcolname);
				if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule)
					$selectedfields[0] = "jo_crmentity";

				$moduleFieldLabel = $selectedfields[3];
				list($moduleName, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
				$fieldInfo = getFieldByReportLabel($moduleName, $fieldLabel);
				$typeOfData = $fieldInfo['typeofdata'];
				list($type, $typeOtherInfo) = explode('~', $typeOfData, 2);

				if ($datefilter != "custom") {
					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);
					$startdate = $startenddate[0];
					$enddate = $startenddate[1];
				}

				if ($startdate != "0000-00-00" && $enddate != "0000-00-00" && $startdate != "" && $enddate != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {

					$startDateTime = new DateTimeField($startdate . ' ' . date('H:i:s'));
					$userStartDate = $startDateTime->getDisplayDate();
					if ($type == 'DT') {
						$userStartDate = $userStartDate . ' 00:00:00';
					}
					$startDateTime = getValidDBInsertDateTimeValue($userStartDate);

					$endDateTime = new DateTimeField($enddate . ' ' . date('H:i:s'));
					$userEndDate = $endDateTime->getDisplayDate();
					if ($type == 'DT') {
						$userEndDate = $userEndDate . ' 23:59:00';
					}
					$endDateTime = getValidDBInsertDateTimeValue($userEndDate);

					if ($selectedfields[1] == 'birthday') {
						$tableColumnSql = "DATE_FORMAT(" . $selectedfields[0] . "." . $selectedfields[1] . ", '%m%d')";
						$startDateTime = "DATE_FORMAT('$startDateTime', '%m%d')";
						$endDateTime = "DATE_FORMAT('$endDateTime', '%m%d')";
					} else {
						if ($selectedfields[0] == 'jo_activity' && ($selectedfields[1] == 'date_start')) {
							$tableColumnSql = '';
							$tableColumnSql = "CAST((CONCAT(date_start,' ',time_start)) AS DATETIME)";
						} else {
							$tableColumnSql = $selectedfields[0] . "." . $selectedfields[1];
						}
						$startDateTime = "'$startDateTime'";
						$endDateTime = "'$endDateTime'";
					}

					$stdfilterlist[$fieldcolname] = $tableColumnSql . " between " . $startDateTime . " and " . $endDateTime;
					$this->queryPlanner->addTable($selectedfields[0]);
				}
			}
		}
		// Save the information
		$this->_stdfilterlist = $stdfilterlist;

		$log->info("ReportRun :: Successfully returned getStdFilterList" . $reportid);
		return $stdfilterlist;
	}

	/** Function to get the RunTime filter columns for the given $filtercolumn,$filter,$startdate,$enddate
	 *  @ param $filtercolumn : Type String
	 *  @ param $filter : Type String
	 *  @ param $startdate: Type String
	 *  @ param $enddate : Type String
	 *  This function returns  $stdfilterlist Array($columnname => $tablename:$columnname:$fieldlabel=>$tablename.$columnname 'between' $startdate 'and' $enddate)
	 *
	 */
	function RunTimeFilter($filtercolumn, $filter, $startdate, $enddate) {
		if ($filtercolumn != "none") {
			$selectedfields = explode(":", $filtercolumn);
			if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule)
				$selectedfields[0] = "jo_crmentity";
			if ($filter == "custom") {
				if ($startdate != "0000-00-00" && $enddate != "0000-00-00" && $startdate != "" &&
						$enddate != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
					$stdfilterlist[$filtercolumn] = $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startdate . " 00:00:00' and '" . $enddate . " 23:59:00'";
				}
			} else {
				if ($startdate != "" && $enddate != "") {
					$startenddate = $this->getStandarFiltersStartAndEndDate($filter);
					if ($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
						$stdfilterlist[$filtercolumn] = $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startenddate[0] . " 00:00:00' and '" . $startenddate[1] . " 23:59:00'";
					}
				}
			}
		}
		return $stdfilterlist;
	}

	/** Function to get the RunTime Advanced filter conditions
	 *  @ param $advft_criteria : Type Array
	 *  @ param $advft_criteria_groups : Type Array
	 *  This function returns  $advfiltersql
	 *
	 */
	function RunTimeAdvFilter($advft_criteria, $advft_criteria_groups) {
		$adb = PearDatabase::getInstance();

		$advfilterlist = array();
		$advfiltersql = '';
		if (!empty($advft_criteria)) {
			foreach ($advft_criteria as $column_index => $column_condition) {

				if (empty($column_condition))
					continue;

				$adv_filter_column = $column_condition["columnname"];
				$adv_filter_comparator = $column_condition["comparator"];
				$adv_filter_value = $column_condition["value"];
				$adv_filter_column_condition = $column_condition["columncondition"];
				$adv_filter_groupid = $column_condition["groupid"];

				$column_info = explode(":", $adv_filter_column);

				$moduleFieldLabel = $column_info[2];
				$fieldName = $column_info[3];
				list($module, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
				$fieldInfo = getFieldByReportLabel($module, $fieldLabel);
				$fieldType = null;
				if (!empty($fieldInfo)) {
					$field = WebserviceField::fromArray($adb, $fieldInfo);
					$fieldType = $field->getFieldDataType();
				}

				if ($fieldType == 'currency') {
					// Some of the currency fields like Unit Price, Total, Sub-total etc of Inventory modules, do not need currency conversion
					if ($field->getUIType() == '72') {
						$adv_filter_value = CurrencyField::convertToDBFormat($adv_filter_value, null, true);
					} else {
						$adv_filter_value = CurrencyField::convertToDBFormat($adv_filter_value);
					}
				}

				$specialDateConditions = Head_Functions::getSpecialDateTimeCondtions();
				$temp_val = explode(",", $adv_filter_value);
				if (($column_info[4] == 'D' || ($column_info[4] == 'T' && $column_info[1] != 'time_start' && $column_info[1] != 'time_end') || ($column_info[4] == 'DT')) && ($column_info[4] != '' && $adv_filter_value != '' ) && !in_array($adv_filter_comparator, $specialDateConditions)) {
					$val = Array();
					for ($x = 0; $x < count($temp_val); $x++) {
						if ($column_info[4] == 'D') {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDBInsertDateValue();
						} elseif ($column_info[4] == 'DT') {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDBInsertDateTimeValue();
						} elseif ($fieldType == 'time') {
							$val[$x] = Head_Time_UIType::getTimeValueWithSeconds($temp_val[$x]);
						} else {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDBInsertTimeValue();
						}
					}
					$adv_filter_value = implode(",", $val);
				}
				$criteria = array();
				$criteria['columnname'] = $adv_filter_column;
				$criteria['comparator'] = $adv_filter_comparator;
				$criteria['value'] = $adv_filter_value;
				$criteria['column_condition'] = $adv_filter_column_condition;

				$advfilterlist[$adv_filter_groupid]['columns'][] = $criteria;
			}

			foreach ($advft_criteria_groups as $group_index => $group_condition_info) {
				if (empty($group_condition_info))
					continue;
				if (empty($advfilterlist[$group_index]))
					continue;
				$advfilterlist[$group_index]['condition'] = $group_condition_info["groupcondition"];
				$noOfGroupColumns = count($advfilterlist[$group_index]['columns']);
				if (!empty($advfilterlist[$group_index]['columns'][$noOfGroupColumns - 1]['column_condition'])) {
					$advfilterlist[$group_index]['columns'][$noOfGroupColumns - 1]['column_condition'] = '';
				}
			}
			$noOfGroups = count($advfilterlist);
			if (!empty($advfilterlist[$noOfGroups]['condition'])) {
				$advfilterlist[$noOfGroups]['condition'] = '';
			}

			$advfiltersql = $this->generateAdvFilterSql($advfilterlist);
		}
		return $advfiltersql;
	}

	/** Function to get standardfilter for the given reportid
	 *  @ param $reportid : Type Integer
	 *  returns the query of columnlist for the selected columns
	 */
	function getStandardCriterialSql($reportid) {
		global $adb;
		global $modules;
		global $log;

		$sreportstdfiltersql = "select jo_reportdatefilter.* from jo_report";
		$sreportstdfiltersql .= " inner join jo_reportdatefilter on jo_report.reportid = jo_reportdatefilter.datefilterid";
		$sreportstdfiltersql .= " where jo_report.reportid = ?";

		$result = $adb->pquery($sreportstdfiltersql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for ($i = 0; $i < $noofrows; $i++) {
			$fieldcolname = $adb->query_result($result, $i, "datecolumnname");
			$datefilter = $adb->query_result($result, $i, "datefilter");
			$startdate = $adb->query_result($result, $i, "startdate");
			$enddate = $adb->query_result($result, $i, "enddate");

			if ($fieldcolname != "none") {
				$selectedfields = explode(":", $fieldcolname);
				if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule)
					$selectedfields[0] = "jo_crmentity";
				if ($datefilter == "custom") {

					if ($startdate != "0000-00-00" && $enddate != "0000-00-00" && $selectedfields[0] != "" && $selectedfields[1] != "" && $startdate != '' && $enddate != '') {

						$startDateTime = new DateTimeField($startdate . ' ' . date('H:i:s'));
						$startdate = $startDateTime->getDisplayDate();
						$endDateTime = new DateTimeField($enddate . ' ' . date('H:i:s'));
						$enddate = $endDateTime->getDisplayDate();

						$sSQL .= $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startdate . "' and '" . $enddate . "'";
					}
				} else {

					$startenddate = $this->getStandarFiltersStartAndEndDate($datefilter);

					$startDateTime = new DateTimeField($startenddate[0] . ' ' . date('H:i:s'));
					$startdate = $startDateTime->getDisplayDate();
					$endDateTime = new DateTimeField($startenddate[1] . ' ' . date('H:i:s'));
					$enddate = $endDateTime->getDisplayDate();

					if ($startenddate[0] != "" && $startenddate[1] != "" && $selectedfields[0] != "" && $selectedfields[1] != "") {
						$sSQL .= $selectedfields[0] . "." . $selectedfields[1] . " between '" . $startdate . "' and '" . $enddate . "'";
					}
				}
			}
		}
		$log->info("ReportRun :: Successfully returned getStandardCriterialSql" . $reportid);
		return $sSQL;
	}

	/** Function to get standardfilter startdate and enddate for the given type
	 *  @ param $type : Type String
	 *  returns the $datevalue Array in the given format
	 * 		$datevalue = Array(0=>$startdate,1=>$enddate)
	 */
	function getStandarFiltersStartAndEndDate($type) {
		global $current_user;
		$userPeferredDayOfTheWeek = $current_user->column_fields['dayoftheweek'];

		$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		$todayName = date('l', strtotime($today));

		$tomorrow = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
		$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

		$currentmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m"), "01", date("Y")));
		$currentmonth1 = date("Y-m-t");
		$lastmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, "01", date("Y")));
		$lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
		$nextmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, "01", date("Y")));
		$nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

		// (Last Week) If Today is "Sunday" then "-2 week Sunday" will give before last week Sunday date
		if ($todayName == $userPeferredDayOfTheWeek)
			$lastweek0 = date("Y-m-d", strtotime("-1 week $userPeferredDayOfTheWeek"));
		else
			$lastweek0 = date("Y-m-d", strtotime("-2 week $userPeferredDayOfTheWeek"));
		$prvDay = date('l', strtotime(date('Y-m-d', strtotime('-1 day', strtotime($lastweek0)))));
		$lastweek1 = date("Y-m-d", strtotime("-1 week $prvDay"));

		// (This Week) If Today is "Sunday" then "-1 week Sunday" will give last week Sunday date
		if ($todayName == $userPeferredDayOfTheWeek)
			$thisweek0 = date("Y-m-d", strtotime("-0 week $userPeferredDayOfTheWeek"));
		else
			$thisweek0 = date("Y-m-d", strtotime("-1 week $userPeferredDayOfTheWeek"));
		$prvDay = date('l', strtotime(date('Y-m-d', strtotime('-1 day', strtotime($thisweek0)))));
		$thisweek1 = date("Y-m-d", strtotime("this $prvDay"));

		// (Next Week) If Today is "Sunday" then "this Sunday" will give Today's date
		if ($todayName == $userPeferredDayOfTheWeek)
			$nextweek0 = date("Y-m-d", strtotime("+1 week $userPeferredDayOfTheWeek"));
		else
			$nextweek0 = date("Y-m-d", strtotime("this $userPeferredDayOfTheWeek"));
		$prvDay = date('l', strtotime(date('Y-m-d', strtotime('-1 day', strtotime($nextweek0)))));
		$nextweek1 = date("Y-m-d", strtotime("+1 week $prvDay"));

		$next7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")));
		$next30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 29, date("Y")));
		$next60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 59, date("Y")));
		$next90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 89, date("Y")));
		$next120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 119, date("Y")));

		$last7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
		$last14days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 13, date("Y")));
		$last30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 29, date("Y")));
		$last60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 59, date("Y")));
		$last90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 89, date("Y")));
		$last120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 119, date("Y")));

		$currentFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
		$currentFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")));
		$lastFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") - 1));
		$lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") - 1));
		$nextFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
		$nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") + 1));

		if (date("m") <= 3) {
			$cFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
			$nFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y") - 1));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y") - 1));
		} else if (date("m") > 3 and date("m") <= 6) {
			$pFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
			$cFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
			$nFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
		} else if (date("m") > 6 and date("m") <= 9) {
			$nFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
			$cFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
		} else if (date("m") > 9 and date("m") <= 12) {
			$nFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y") + 1));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
			$cFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
		}

		if ($type == "today") {

			$datevalue[0] = $today;
			$datevalue[1] = $today;
		} elseif ($type == "yesterday") {

			$datevalue[0] = $yesterday;
			$datevalue[1] = $yesterday;
		} elseif ($type == "tomorrow") {

			$datevalue[0] = $tomorrow;
			$datevalue[1] = $tomorrow;
		} elseif ($type == "thisweek") {

			$datevalue[0] = $thisweek0;
			$datevalue[1] = $thisweek1;
		} elseif ($type == "lastweek") {

			$datevalue[0] = $lastweek0;
			$datevalue[1] = $lastweek1;
		} elseif ($type == "nextweek") {

			$datevalue[0] = $nextweek0;
			$datevalue[1] = $nextweek1;
		} elseif ($type == "thismonth") {

			$datevalue[0] = $currentmonth0;
			$datevalue[1] = $currentmonth1;
		} elseif ($type == "lastmonth") {

			$datevalue[0] = $lastmonth0;
			$datevalue[1] = $lastmonth1;
		} elseif ($type == "nextmonth") {

			$datevalue[0] = $nextmonth0;
			$datevalue[1] = $nextmonth1;
		} elseif ($type == "next7days") {

			$datevalue[0] = $today;
			$datevalue[1] = $next7days;
		} elseif ($type == "next30days") {

			$datevalue[0] = $today;
			$datevalue[1] = $next30days;
		} elseif ($type == "next60days") {

			$datevalue[0] = $today;
			$datevalue[1] = $next60days;
		} elseif ($type == "next90days") {

			$datevalue[0] = $today;
			$datevalue[1] = $next90days;
		} elseif ($type == "next120days") {

			$datevalue[0] = $today;
			$datevalue[1] = $next120days;
		} elseif ($type == "last7days") {

			$datevalue[0] = $last7days;
			$datevalue[1] = $today;
		} elseif ($type == "last14days") {
			$datevalue[0] = $last14days;
			$datevalue[1] = $today;
		} elseif ($type == "last30days") {

			$datevalue[0] = $last30days;
			$datevalue[1] = $today;
		} elseif ($type == "last60days") {

			$datevalue[0] = $last60days;
			$datevalue[1] = $today;
		} else if ($type == "last90days") {

			$datevalue[0] = $last90days;
			$datevalue[1] = $today;
		} elseif ($type == "last120days") {

			$datevalue[0] = $last120days;
			$datevalue[1] = $today;
		} elseif ($type == "thisfy") {

			$datevalue[0] = $currentFY0;
			$datevalue[1] = $currentFY1;
		} elseif ($type == "prevfy") {

			$datevalue[0] = $lastFY0;
			$datevalue[1] = $lastFY1;
		} elseif ($type == "nextfy") {

			$datevalue[0] = $nextFY0;
			$datevalue[1] = $nextFY1;
		} elseif ($type == "nextfq") {

			$datevalue[0] = $nFq;
			$datevalue[1] = $nFq1;
		} elseif ($type == "prevfq") {

			$datevalue[0] = $pFq;
			$datevalue[1] = $pFq1;
		} elseif ($type == "thisfq") {
			$datevalue[0] = $cFq;
			$datevalue[1] = $cFq1;
		} else {
			$datevalue[0] = "";
			$datevalue[1] = "";
		}
		return $datevalue;
	}

	function hasGroupingList() {
		global $adb;
		$result = $adb->pquery('SELECT 1 FROM jo_reportsortcol WHERE reportid=? and columnname <> "none"', array($this->reportid));
		return ($result && $adb->num_rows($result)) ? true : false;
	}

	/** Function to get getGroupingList for the given reportid
	 *  @ param $reportid : Type Integer
	 *  returns the $grouplist Array in the following format
	 *  		$grouplist = Array($tablename:$columnname:$fieldlabel:fieldname:typeofdata=>$tablename:$columnname $sorder,
	 * 				   $tablename1:$columnname1:$fieldlabel1:fieldname1:typeofdata1=>$tablename1:$columnname1 $sorder,
	 * 				   $tablename2:$columnname2:$fieldlabel2:fieldname2:typeofdata2=>$tablename2:$columnname2 $sorder)
	 * This function also sets the return value in the class variable $this->groupbylist
	 */
	function getGroupingList($reportid) {
		global $adb;
		global $modules;
		global $log;

		// Have we initialized information already?
		if ($this->_groupinglist !== false) {
			return $this->_groupinglist;
		}
		$primaryModule = $this->primarymodule; 

		$sreportsortsql = " SELECT jo_reportsortcol.*, jo_reportgroupbycolumn.* FROM jo_report";
		$sreportsortsql .= " inner join jo_reportsortcol on jo_report.reportid = jo_reportsortcol.reportid";
		$sreportsortsql .= " LEFT JOIN jo_reportgroupbycolumn ON (jo_report.reportid = jo_reportgroupbycolumn.reportid AND jo_reportsortcol.sortcolid = jo_reportgroupbycolumn.sortid)";
		$sreportsortsql .= " where jo_report.reportid =? AND jo_reportsortcol.columnname IN (SELECT columnname from jo_selectcolumn WHERE queryid=?) order by jo_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid, $reportid));
		$grouplist = array();

		$inventoryModules = getInventoryModules();
		while ($reportsortrow = $adb->fetch_array($result)) {
			$fieldcolname = $reportsortrow["columnname"];
			list($tablename, $colname, $module_field, $fieldname, $single) = split(":", $fieldcolname);
			$sortorder = $reportsortrow["sortorder"];

			if ($sortorder == "Ascending") {
				$sortorder = "ASC";
			} elseif ($sortorder == "Descending") {
				$sortorder = "DESC";
			}

			if ($fieldcolname != "none") {
				$selectedfields = explode(":", $fieldcolname);
				if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule)
					$selectedfields[0] = "jo_crmentity";
				if($selectedfields[0] == 'jo_inventoryproductrel') {
					list($moduleName, $field) = explode('_', $selectedfields[2], 2);
					$selectedfields[0] = $selectedfields[0].$moduleName;
				}
				if($selectedfields[0] == 'jo_pricebookproductrel') {
					list($moduleName, $field) = explode('_', $selectedfields[2], 2);
					$selectedfields[0] = $selectedfields[0].'tmp'.$moduleName;
				}

				$sqlvalue = $selectedfields[0] . '.' . $selectedfields[1] . ' ' . $sortorder;
				if ($selectedfields[4] == "D" && strtolower($reportsortrow["dategroupbycriteria"]) != "none") {
					$groupField = $module_field;
					$groupCriteria = $reportsortrow["dategroupbycriteria"];
					if (in_array($groupCriteria, array_keys($this->groupByTimeParent))) {
						$parentCriteria = $this->groupByTimeParent[$groupCriteria];
						foreach ($parentCriteria as $criteria) {
							$groupByCondition[] = $this->GetTimeCriteriaCondition($criteria, $groupField) . " " . $sortorder;
						}
					}
					$groupByCondition[] = $this->GetTimeCriteriaCondition($groupCriteria, $groupField) . " " . $sortorder;
					$sqlvalue = implode(", ", $groupByCondition);
				}
				$fieldModuleName = explode('_',$module_field); 
				$fieldId = getFieldid(getTabid($fieldModuleName[0]), $fieldname);
				$fieldModel = Head_Field_Model::getInstance($fieldId);
				if($fieldModel && ($fieldModel->getFieldDataType()=='reference' || $fieldModel->getFieldDataType()=='owner')){
					$sqlvalue = $module_field . ' ' . $sortorder;
				}
				$grouplist[$fieldcolname] = $sqlvalue;
				$temp = split("_", $selectedfields[2], 2);
				$module = $temp[0];
				if (in_array($module, $inventoryModules) && $fieldname == 'serviceid') {
					$grouplist[$fieldcolname] = $sqlvalue;
				} else if($primaryModule == 'PriceBooks' && $fieldname == 'listprice' && in_array($module, array('Products', 'Services'))){
					$grouplist[$fieldcolname] = $sqlvalue;
				} else if (CheckFieldPermission($fieldname, $module) == 'true') {
					$grouplist[$fieldcolname] = $sqlvalue;
				} else {
					$grouplist[$fieldcolname] = $selectedfields[0] . "." . $selectedfields[1];
				}

				$this->queryPlanner->addTable($tablename);
			}
		}

		// Save the information
		$this->_groupinglist = $grouplist;

		$log->info("ReportRun :: Successfully returned getGroupingList" . $reportid);
		return $grouplist;
	}

	/** function to replace special characters
	 *  @ param $selectedfield : type string
	 *  this returns the string for grouplist
	 */
	function replaceSpecialChar($selectedfield) {
		$selectedfield = decode_html(decode_html($selectedfield));
		preg_match('/&/', $selectedfield, $matches);
		if (!empty($matches)) {
			$selectedfield = str_replace('&', 'and', ($selectedfield));
		}
		return $selectedfield;
	}

	/** function to get the selectedorderbylist for the given reportid
	 *  @ param $reportid : type integer
	 *  this returns the columns query for the sortorder columns
	 *  this function also sets the return value in the class variable $this->orderbylistsql
	 */
	function getSelectedOrderbyList($reportid) {

		global $adb;
		global $modules;
		global $log;

		$sreportsortsql = "select jo_reportsortcol.* from jo_report";
		$sreportsortsql .= " inner join jo_reportsortcol on jo_report.reportid = jo_reportsortcol.reportid";
		$sreportsortsql .= " where jo_report.reportid =? order by jo_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for ($i = 0; $i < $noofrows; $i++) {
			$fieldcolname = $adb->query_result($result, $i, "columnname");
			$sortorder = $adb->query_result($result, $i, "sortorder");

			if ($sortorder == "Ascending") {
				$sortorder = "ASC";
			} elseif ($sortorder == "Descending") {
				$sortorder = "DESC";
			}

			if ($fieldcolname != "none") {
				$this->orderbylistcolumns[] = $fieldcolname;
				$n = $n + 1;
				$selectedfields = explode(":", $fieldcolname);
				if ($n > 1) {
					$sSQL .= ", ";
					$this->orderbylistsql .= ", ";
				}
				if ($selectedfields[0] == "jo_crmentity" . $this->primarymodule)
					$selectedfields[0] = "jo_crmentity";
				$sSQL .= $selectedfields[0] . "." . $selectedfields[1] . " " . $sortorder;
				$this->orderbylistsql .= $selectedfields[0] . "." . $selectedfields[1] . " " . $selectedfields[2];
			}
		}
		$log->info("ReportRun :: Successfully returned getSelectedOrderbyList" . $reportid);
		return $sSQL;
	}

	/** function to get secondary Module for the given Primary module and secondary module
	 *  @ param $module : type String
	 *  @ param $secmodule : type String
	 *  this returns join query for the given secondary module
	 */
	function getRelatedModulesQuery($module, $secmodule) {
		global $log, $current_user;
		$query = '';
		if ($secmodule != '') {
			$secondarymodule = explode(":", $secmodule);
			foreach ($secondarymodule as $key => $value) {
				if (!Head_Module_Model::getInstance($value)) {
					continue;
				}

				$foc = CRMEntity::getInstance($value);

				// Case handling: Force table requirement ahead of time.
				$this->queryPlanner->addTable('jo_crmentity' . $value);

				$focQuery = $foc->generateReportsSecQuery($module, $value, $this->queryPlanner);
				
				if ($focQuery) {
					if (count($secondarymodule) > 1) {
						$query .= $focQuery . $this->getReportsNonAdminAccessControlQuery($value, $current_user, $value);
					} else {
						$query .= $focQuery . getNonAdminAccessControlQuery($value, $current_user, $value);
					}
				}
			}
			if ($this->queryPlanner->requireTable('jo_inventoryproductreltmp'.$value) && stripos($query, 'join jo_inventoryproductrel') === false) {
				$query .= " LEFT JOIN jo_inventoryproductrel AS jo_inventoryproductreltmp$value ON jo_inventoryproductreltmp$value.id = $foc->table_name.$foc->table_index ";
			}
		}
		$log->info("ReportRun :: Successfully returned getRelatedModulesQuery" . $secmodule);

		return $query;
	}

	/**
	 * Non admin user not able to see the records of report even he has permission
	 * Fix for Case :- Report with One Primary Module, and Two Secondary modules, let's say for one of the
	 * secondary module, non-admin user don't have permission, then reports is not showing the record even
	 * the user has permission for another seconday module.
	 * @param type $module
	 * @param type $user
	 * @param type $scope
	 * @return $query
	 */
	function getReportsNonAdminAccessControlQuery($module, $user, $scope = '') {
		        $get_userdetails = get_privileges($user->id);
        foreach ($get_userdetails as $key => $value) {
            if(is_object($value)){
                $value = (array) $value;
                foreach ($value as $decode_key => $decode_value) {
                    if(is_object($decode_value)){
                        $value[$decode_key] = (array) $decode_value;
                    }
                }
                $$key = $value;
                }else{
                    $$key = $value;
                }
        }
        $get_sharingdetails = get_sharingprivileges($user->id);
        foreach ($get_sharingdetails as $key => $value) {
            if(is_object($value)){
                $value = (array) $value;
                    foreach ($value as $decode_key => $decode_value) {
                       if(is_object($decode_value)){
                          $value[$decode_key] = (array) $decode_value;
                        }
                    }
                    $$key = $value;
            }else{
                $$key = $value;
            }
        }

		$query = ' ';
		$tabId = getTabid($module);
		if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tabId] == 3) {
			$sharingRuleInfoVariable = $module . '_share_read_permission';
			$sharingRuleInfo = $$sharingRuleInfoVariable;
			$sharedTabId = null;

			if ($module == "Calendar") {
				$sharedTabId = $tabId;
				$tableName = 'vt_tmp_u' . $user->id . '_t' . $tabId;
			} else if (!empty($sharingRuleInfo) && (count($sharingRuleInfo['ROLE']) > 0 ||
					count($sharingRuleInfo['GROUP']) > 0)) {
				$sharedTabId = $tabId;
			}

			if (!empty($sharedTabId)) {
				$module = getTabModuleName($sharedTabId);
				if ($module == "Calendar") {
					// For calendar we have some special case to check like, calendar shared type
					$moduleInstance = CRMEntity::getInstance($module);
					$query = $moduleInstance->getReportsNonAdminAccessControlQuery($tableName, $tabId, $user, $current_user_parent_role_seq, $current_user_groups);
				} else {
					$query = $this->getNonAdminAccessQuery($module, $user, $current_user_parent_role_seq, $current_user_groups);
				}

				$db = PearDatabase::getInstance();
				$result = $db->pquery($query, array());
				$rows = $db->num_rows($result);
				for ($i = 0; $i < $rows; $i++) {
					$ids[] = $db->query_result($result, $i, 'id');
				}
				if (!empty($ids)) {
					$query = " AND jo_crmentity$scope.smownerid IN (" . implode(',', $ids) . ") ";
				}
			}
		}
		return $query;
	}

	/** function to get report query for the given module
	 *  @ param $module : type String
	 *  this returns join query for the given module
	 */
	function getReportsQuery($module, $type = '') {
		global $log, $current_user, $adb;
		$secondary_module = "'";
		$secondary_module .= str_replace(":", "','", $this->secondarymodule);
		$secondary_module .="'";

		if ($module == "Leads") {
			$query = "from jo_leaddetails
				inner join jo_crmentity on jo_crmentity.crmid=jo_leaddetails.leadid";

			if ($this->queryPlanner->requireTable('jo_leadsubdetails')) {
				$query .= "	inner join jo_leadsubdetails on jo_leadsubdetails.leadsubscriptionid=jo_leaddetails.leadid";
			}
			if ($this->queryPlanner->requireTable('jo_leadaddress')) {
				$query .= "	inner join jo_leadaddress on jo_leadaddress.leadaddressid=jo_leaddetails.leadid";
			}
			if ($this->queryPlanner->requireTable('jo_leadscf')) {
				$query .= " inner join jo_leadscf on jo_leaddetails.leadid = jo_leadscf.leadid";
			}
			if ($this->queryPlanner->requireTable('jo_groupsLeads')) {
				$query .= "	left join jo_groups as jo_groupsLeads on jo_groupsLeads.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('jo_usersLeads')) {
				$query .= " left join jo_users as jo_usersLeads on jo_usersLeads.id = jo_crmentity.smownerid";
			}

			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid
				left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('jo_lastModifiedByLeads')) {
				$query .= " left join jo_users as jo_lastModifiedByLeads on jo_lastModifiedByLeads.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyLeads')) {
				$query .= " left join jo_users as jo_createdbyLeads on jo_createdbyLeads.id = jo_crmentity.smcreatorid";
			}

			$query .= " " . $this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where jo_crmentity.deleted=0 and jo_leaddetails.converted=0";
		} else if ($module == "Accounts") {
			$query = "from jo_account
				inner join jo_crmentity on jo_crmentity.crmid=jo_account.accountid";

			if ($this->queryPlanner->requireTable('jo_accountbillads')) {
				$query .= " inner join jo_accountbillads on jo_account.accountid=jo_accountbillads.accountaddressid";
			}
			if ($this->queryPlanner->requireTable('jo_accountshipads')) {
				$query .= " inner join jo_accountshipads on jo_account.accountid=jo_accountshipads.accountaddressid";
			}
			if ($this->queryPlanner->requireTable('jo_accountscf')) {
				$query .= " inner join jo_accountscf on jo_account.accountid = jo_accountscf.accountid";
			}
			if ($this->queryPlanner->requireTable('jo_groupsAccounts')) {
				$query .= " left join jo_groups as jo_groupsAccounts on jo_groupsAccounts.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('jo_accountAccounts')) {
				$query .= "	left join jo_account as jo_accountAccounts on jo_accountAccounts.accountid = jo_account.parentid";
			}
			if ($this->queryPlanner->requireTable('jo_usersAccounts')) {
				$query .= " left join jo_users as jo_usersAccounts on jo_usersAccounts.id = jo_crmentity.smownerid";
			}

			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid
				left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('jo_lastModifiedByAccounts')) {
				$query.= " left join jo_users as jo_lastModifiedByAccounts on jo_lastModifiedByAccounts.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyAccounts')) {
				$query .= " left join jo_users as jo_createdbyAccounts on jo_createdbyAccounts.id = jo_crmentity.smcreatorid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where jo_crmentity.deleted=0 ";
		} else if ($module == "Contacts") {
			$query = "from jo_contactdetails
				inner join jo_crmentity on jo_crmentity.crmid = jo_contactdetails.contactid";

			if ($this->queryPlanner->requireTable('jo_contactaddress')) {
				$query .= "	inner join jo_contactaddress on jo_contactdetails.contactid = jo_contactaddress.contactaddressid";
			}
			if ($this->queryPlanner->requireTable('jo_customerdetails')) {
				$query .= "	inner join jo_customerdetails on jo_customerdetails.customerid = jo_contactdetails.contactid";
			}
			if ($this->queryPlanner->requireTable('jo_contactsubdetails')) {
				$query .= "	inner join jo_contactsubdetails on jo_contactdetails.contactid = jo_contactsubdetails.contactsubscriptionid";
			}
			if ($this->queryPlanner->requireTable('jo_contactscf')) {
				$query .= "	inner join jo_contactscf on jo_contactdetails.contactid = jo_contactscf.contactid";
			}
			if ($this->queryPlanner->requireTable('jo_groupsContacts')) {
				$query .= " left join jo_groups jo_groupsContacts on jo_groupsContacts.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('jo_contactdetailsContacts')) {
				$query .= "	left join jo_contactdetails as jo_contactdetailsContacts on jo_contactdetailsContacts.contactid = jo_contactdetails.reportsto";
			}
			if ($this->queryPlanner->requireTable('jo_accountContacts')) {
				$query .= "	left join jo_account as jo_accountContacts on jo_accountContacts.accountid = jo_contactdetails.accountid";
			}
			if ($this->queryPlanner->requireTable('jo_usersContacts')) {
				$query .= " left join jo_users as jo_usersContacts on jo_usersContacts.id = jo_crmentity.smownerid";
			}

			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid
				left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('jo_lastModifiedByContacts')) {
				$query .= " left join jo_users as jo_lastModifiedByContacts on jo_lastModifiedByContacts.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyContacts')) {
				$query .= " left join jo_users as jo_createdbyContacts on jo_createdbyContacts.id = jo_crmentity.smcreatorid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where jo_crmentity.deleted=0";
		} else if ($module == "Potentials") {
			$query = "from jo_potential
				inner join jo_crmentity on jo_crmentity.crmid=jo_potential.potentialid";

			if ($this->queryPlanner->requireTable('jo_potentialscf')) {
				$query .= " inner join jo_potentialscf on jo_potentialscf.potentialid = jo_potential.potentialid";
			}
			if ($this->queryPlanner->requireTable('jo_accountPotentials')) {
				$query .= " left join jo_account as jo_accountPotentials on jo_potential.related_to = jo_accountPotentials.accountid";
			}
			if ($this->queryPlanner->requireTable('jo_contactdetailsPotentials')) {
				$query .= " left join jo_contactdetails as jo_contactdetailsPotentials on jo_potential.contact_id = jo_contactdetailsPotentials.contactid";
			}
			if ($this->queryPlanner->requireTable('jo_campaignPotentials')) {
				$query .= " left join jo_campaign as jo_campaignPotentials on jo_potential.campaignid = jo_campaignPotentials.campaignid";
			}
			if ($this->queryPlanner->requireTable('jo_groupsPotentials')) {
				$query .= " left join jo_groups jo_groupsPotentials on jo_groupsPotentials.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('jo_usersPotentials')) {
				$query .= " left join jo_users as jo_usersPotentials on jo_usersPotentials.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('jo_lastModifiedByPotentials')) {
				$query .= " left join jo_users as jo_lastModifiedByPotentials on jo_lastModifiedByPotentials.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyPotentials')) {
				$query .= " left join jo_users as jo_createdbyPotentials on jo_createdbyPotentials.id = jo_crmentity.smcreatorid";
			}
			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where jo_crmentity.deleted=0 ";
		}

		//For this Product - we can related Accounts, Contacts (Also Leads, Potentials)
		else if ($module == "Products") {
			$query .= " from jo_products";
			$query .= " inner join jo_crmentity on jo_crmentity.crmid=jo_products.productid";
			if ($this->queryPlanner->requireTable("jo_productcf")) {
				$query .= " left join jo_productcf on jo_products.productid = jo_productcf.productid";
			}
			if ($this->queryPlanner->requireTable("jo_lastModifiedByProducts")) {
				$query .= " left join jo_users as jo_lastModifiedByProducts on jo_lastModifiedByProducts.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyProducts')) {
				$query .= " left join jo_users as jo_createdbyProducts on jo_createdbyProducts.id = jo_crmentity.smcreatorid";
			}
			if ($this->queryPlanner->requireTable("jo_usersProducts")) {
				$query .= " left join jo_users as jo_usersProducts on jo_usersProducts.id = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_groupsProducts")) {
				$query .= " left join jo_groups as jo_groupsProducts on jo_groupsProducts.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_vendorRelProducts")) {
				$query .= " left join jo_vendor as jo_vendorRelProducts on jo_vendorRelProducts.vendorid = jo_products.vendor_id";
			}
			if ($this->queryPlanner->requireTable("innerProduct")) {
				$query .= " LEFT JOIN (
						SELECT jo_products.productid,
								(CASE WHEN (jo_products.currency_id = 1 ) THEN jo_products.unit_price
									ELSE (jo_products.unit_price / jo_currency_info.conversion_rate) END
								) AS actual_unit_price
						FROM jo_products
						LEFT JOIN jo_currency_info ON jo_products.currency_id = jo_currency_info.id
						LEFT JOIN jo_productcurrencyrel ON jo_products.productid = jo_productcurrencyrel.productid
						AND jo_productcurrencyrel.currencyid = " . $current_user->currency_id . "
				) AS innerProduct ON innerProduct.productid = jo_products.productid";
			}
			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
						getNonAdminAccessControlQuery($this->primarymodule,$current_user)."
				where jo_crmentity.deleted=0";
		} else if ($module == "HelpDesk") {
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('jo_crmentityRelHelpDesk', array('jo_accountRelHelpDesk', 'jo_contactdetailsRelHelpDesk'));

			$query = "from jo_troubletickets inner join jo_crmentity on jo_crmentity.crmid=jo_troubletickets.ticketid";

			if ($this->queryPlanner->requireTable('jo_ticketcf')) {
				$query .= " inner join jo_ticketcf on jo_ticketcf.ticketid = jo_troubletickets.ticketid";
			}
			if ($this->queryPlanner->requireTable('jo_crmentityRelHelpDesk', $matrix)) {
				$query .= " left join jo_crmentity as jo_crmentityRelHelpDesk on jo_crmentityRelHelpDesk.crmid = jo_troubletickets.parent_id";
			}
			if ($this->queryPlanner->requireTable('jo_accountRelHelpDesk')) {
				$query .= " left join jo_account as jo_accountRelHelpDesk on jo_accountRelHelpDesk.accountid=jo_crmentityRelHelpDesk.crmid";
			}
			if ($this->queryPlanner->requireTable('jo_contactdetailsRelHelpDesk')) {
				$query .= " left join jo_contactdetails as jo_contactdetailsRelHelpDesk on jo_contactdetailsRelHelpDesk.contactid= jo_troubletickets.contact_id";
			}
			if ($this->queryPlanner->requireTable('jo_productsRel')) {
				$query .= " left join jo_products as jo_productsRel on jo_productsRel.productid = jo_troubletickets.product_id";
			}
			if ($this->queryPlanner->requireTable('jo_groupsHelpDesk')) {
				$query .= " left join jo_groups as jo_groupsHelpDesk on jo_groupsHelpDesk.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('jo_usersHelpDesk')) {
				$query .= " left join jo_users as jo_usersHelpDesk on jo_crmentity.smownerid=jo_usersHelpDesk.id";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_crmentity.smownerid=jo_users.id";

			if ($this->queryPlanner->requireTable('jo_lastModifiedByHelpDesk')) {
				$query .= "  left join jo_users as jo_lastModifiedByHelpDesk on jo_lastModifiedByHelpDesk.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyHelpDesk')) {
				$query .= " left join jo_users as jo_createdbyHelpDesk on jo_createdbyHelpDesk.id = jo_crmentity.smcreatorid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where jo_crmentity.deleted=0 ";
		} else if ($module == "Calendar") {
			$referenceModuleList = Head_Util_Helper::getCalendarReferenceModulesList();
			$referenceTablesList = array();
			foreach ($referenceModuleList as $referenceModule) {
				$entityTableFieldNames = getEntityFieldNames($referenceModule);
				$entityTableName = $entityTableFieldNames['tablename'];
				$referenceTablesList[] = $entityTableName . 'RelCalendar';
			}

			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('jo_cntactivityrel', array('jo_contactdetailsCalendar'));
			$matrix->setDependency('jo_seactivityrel', array('jo_crmentityRelCalendar'));
			$matrix->setDependency('jo_crmentityRelCalendar', $referenceTablesList);

			$query = "from jo_activity
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid";

			if ($this->queryPlanner->requireTable('jo_activitycf')) {
				$query .= " left join jo_activitycf on jo_activitycf.activityid = jo_crmentity.crmid";
			}
			if ($this->queryPlanner->requireTable('jo_cntactivityrel', $matrix)) {
				$query .= " left join jo_cntactivityrel on jo_cntactivityrel.activityid= jo_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('jo_contactdetailsCalendar')) {
				$query .= " left join jo_contactdetails as jo_contactdetailsCalendar on jo_contactdetailsCalendar.contactid= jo_cntactivityrel.contactid";
			}
			if ($this->queryPlanner->requireTable('jo_groupsCalendar')) {
				$query .= " left join jo_groups as jo_groupsCalendar on jo_groupsCalendar.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable('jo_usersCalendar')) {
				$query .= " left join jo_users as jo_usersCalendar on jo_usersCalendar.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable('jo_seactivityrel', $matrix)) {
				$query .= " left join jo_seactivityrel on jo_seactivityrel.activityid = jo_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('jo_activity_reminder')) {
				$query .= " left join jo_activity_reminder on jo_activity_reminder.activity_id = jo_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('jo_recurringevents')) {
				$query .= " left join jo_recurringevents on jo_recurringevents.activityid = jo_activity.activityid";
			}
			if ($this->queryPlanner->requireTable('jo_crmentityRelCalendar', $matrix)) {
				$query .= " left join jo_crmentity as jo_crmentityRelCalendar on jo_crmentityRelCalendar.crmid = jo_seactivityrel.crmid";
			}

			foreach ($referenceModuleList as $referenceModule) {
				$entityTableFieldNames = getEntityFieldNames($referenceModule);
				$entityTableName = $entityTableFieldNames['tablename'];
				$entityIdFieldName = $entityTableFieldNames['entityidfield'];
				$referenceTable = $entityTableName . 'RelCalendar';
				if ($this->queryPlanner->requireTable($referenceTable)) {
					$query .= " LEFT JOIN $entityTableName AS $referenceTable ON $referenceTable.$entityIdFieldName = jo_crmentityRelCalendar.crmid";
				}
			}

			if ($this->queryPlanner->requireTable('jo_lastModifiedByCalendar')) {
				$query .= " left join jo_users as jo_lastModifiedByCalendar on jo_lastModifiedByCalendar.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyCalendar')) {
				$query .= " left join jo_users as jo_createdbyCalendar on jo_createdbyCalendar.id = jo_crmentity.smcreatorid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" WHERE jo_crmentity.deleted=0 and (jo_activity.activitytype != 'Emails')";
		} else if ($module == "Quotes") {
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('jo_inventoryproductreltmpQuotes', array('jo_productsQuotes', 'jo_serviceQuotes'));

			$query = "from jo_quotes
			inner join jo_crmentity on jo_crmentity.crmid=jo_quotes.quoteid";

			if ($this->queryPlanner->requireTable('jo_quotesbillads')) {
				$query .= " inner join jo_quotesbillads on jo_quotes.quoteid=jo_quotesbillads.quotebilladdressid";
			}
			if ($this->queryPlanner->requireTable('jo_quotesshipads')) {
				$query .= " inner join jo_quotesshipads on jo_quotes.quoteid=jo_quotesshipads.quoteshipaddressid";
			}
			if ($this->queryPlanner->requireTable("jo_currency_info$module")) {
				$query .= " left join jo_currency_info as jo_currency_info$module on jo_currency_info$module.id = jo_quotes.currency_id";
			}
			if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				if ($this->queryPlanner->requireTable("jo_inventoryproductreltmpQuotes", $matrix)) {
					$query .= " left join jo_inventoryproductrel as jo_inventoryproductreltmpQuotes on jo_quotes.quoteid = jo_inventoryproductreltmpQuotes.id";
				}
				if ($this->queryPlanner->requireTable("jo_productsQuotes")) {
					$query .= " left join jo_products as jo_productsQuotes on jo_productsQuotes.productid = jo_inventoryproductreltmpQuotes.productid";
				}
				if ($this->queryPlanner->requireTable("jo_serviceQuotes")) {
					$query .= " left join jo_service as jo_serviceQuotes on jo_serviceQuotes.serviceid = jo_inventoryproductreltmpQuotes.productid";
				}
			}
			if ($this->queryPlanner->requireTable("jo_quotescf")) {
				$query .= " left join jo_quotescf on jo_quotes.quoteid = jo_quotescf.quoteid";
			}
			if ($this->queryPlanner->requireTable("jo_groupsQuotes")) {
				$query .= " left join jo_groups as jo_groupsQuotes on jo_groupsQuotes.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_usersQuotes")) {
				$query .= " left join jo_users as jo_usersQuotes on jo_usersQuotes.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("jo_lastModifiedByQuotes")) {
				$query .= " left join jo_users as jo_lastModifiedByQuotes on jo_lastModifiedByQuotes.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyQuotes')) {
				$query .= " left join jo_users as jo_createdbyQuotes on jo_createdbyQuotes.id = jo_crmentity.smcreatorid";
			}
			if ($this->queryPlanner->requireTable("jo_usersRel1")) {
				$query .= " left join jo_users as jo_usersRel1 on jo_usersRel1.id = jo_quotes.inventorymanager";
			}
			if ($this->queryPlanner->requireTable("jo_potentialRelQuotes")) {
				$query .= " left join jo_potential as jo_potentialRelQuotes on jo_potentialRelQuotes.potentialid = jo_quotes.potentialid";
			}
			if ($this->queryPlanner->requireTable("jo_contactdetailsQuotes")) {
				$query .= " left join jo_contactdetails as jo_contactdetailsQuotes on jo_contactdetailsQuotes.contactid = jo_quotes.contactid";
			}
			if ($this->queryPlanner->requireTable("jo_leaddetailsQuotes")) {
				$query .= " left join jo_leaddetails as jo_leaddetailsQuotes on jo_leaddetailsQuotes.leadid = jo_quotes.contactid";
			}
			if ($this->queryPlanner->requireTable("jo_accountQuotes")) {
				$query .= " left join jo_account as jo_accountQuotes on jo_accountQuotes.accountid = jo_quotes.accountid";
			}
			if ($this->queryPlanner->requireTable('jo_currency_info')) {
				$query .= ' LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_quotes.currency_id';
			}
			$focus = CRMEntity::getInstance($module);
			$query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
					getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
					" where jo_crmentity.deleted=0";
		} else if ($module == "PurchaseOrder") {

			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('jo_inventoryproductreltmpPurchaseOrder', array('jo_productsPurchaseOrder', 'jo_servicePurchaseOrder'));

			$query = "from jo_purchaseorder
			inner join jo_crmentity on jo_crmentity.crmid=jo_purchaseorder.purchaseorderid";

			if ($this->queryPlanner->requireTable("jo_pobillads")) {
				$query .= " inner join jo_pobillads on jo_purchaseorder.purchaseorderid=jo_pobillads.pobilladdressid";
			}
			if ($this->queryPlanner->requireTable("jo_poshipads")) {
				$query .= " inner join jo_poshipads on jo_purchaseorder.purchaseorderid=jo_poshipads.poshipaddressid";
			}
			if ($this->queryPlanner->requireTable("jo_currency_info$module")) {
				$query .= " left join jo_currency_info as jo_currency_info$module on jo_currency_info$module.id = jo_purchaseorder.currency_id";
			}
			if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				if ($this->queryPlanner->requireTable("jo_inventoryproductreltmpPurchaseOrder", $matrix)) {
					$query .= " left join jo_inventoryproductrel as jo_inventoryproductreltmpPurchaseOrder on jo_purchaseorder.purchaseorderid = jo_inventoryproductreltmpPurchaseOrder.id";
				}
				if ($this->queryPlanner->requireTable("jo_productsPurchaseOrder")) {
					$query .= " left join jo_products as jo_productsPurchaseOrder on jo_productsPurchaseOrder.productid = jo_inventoryproductreltmpPurchaseOrder.productid";
				}
				if ($this->queryPlanner->requireTable("jo_servicePurchaseOrder")) {
					$query .= " left join jo_service as jo_servicePurchaseOrder on jo_servicePurchaseOrder.serviceid = jo_inventoryproductreltmpPurchaseOrder.productid";
				}
			}
			if ($this->queryPlanner->requireTable("jo_purchaseordercf")) {
				$query .= " left join jo_purchaseordercf on jo_purchaseorder.purchaseorderid = jo_purchaseordercf.purchaseorderid";
			}
			if ($this->queryPlanner->requireTable("jo_groupsPurchaseOrder")) {
				$query .= " left join jo_groups as jo_groupsPurchaseOrder on jo_groupsPurchaseOrder.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_usersPurchaseOrder")) {
				$query .= " left join jo_users as jo_usersPurchaseOrder on jo_usersPurchaseOrder.id = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_accountsPurchaseOrder")) {
				$query .= " left join jo_account as jo_accountsPurchaseOrder on jo_accountsPurchaseOrder.accountid = jo_purchaseorder.accountid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("jo_lastModifiedByPurchaseOrder")) {
				$query .= " left join jo_users as jo_lastModifiedByPurchaseOrder on jo_lastModifiedByPurchaseOrder.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyPurchaseOrder')) {
				$query .= " left join jo_users as jo_createdbyPurchaseOrder on jo_createdbyPurchaseOrder.id = jo_crmentity.smcreatorid";
			}
			if ($this->queryPlanner->requireTable("jo_vendorRelPurchaseOrder")) {
				$query .= " left join jo_vendor as jo_vendorRelPurchaseOrder on jo_vendorRelPurchaseOrder.vendorid = jo_purchaseorder.vendorid";
			}
			if ($this->queryPlanner->requireTable("jo_contactdetailsPurchaseOrder")) {
				$query .= " left join jo_contactdetails as jo_contactdetailsPurchaseOrder on jo_contactdetailsPurchaseOrder.contactid = jo_purchaseorder.contactid";
			}
			if ($this->queryPlanner->requireTable('jo_currency_info')) {
				$query .= ' LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_purchaseorder.currency_id';
			}
			$query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
					getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
					" where jo_crmentity.deleted=0";
		} else if ($module == "Invoice") {
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('jo_inventoryproductreltmpInvoice', array('jo_productsInvoice', 'jo_serviceInvoice'));

			$query = "from jo_invoice
			inner join jo_crmentity on jo_crmentity.crmid=jo_invoice.invoiceid";

			if ($this->queryPlanner->requireTable("jo_invoicebillads")) {
				$query .=" inner join jo_invoicebillads on jo_invoice.invoiceid=jo_invoicebillads.invoicebilladdressid";
			}
			if ($this->queryPlanner->requireTable("jo_invoiceshipads")) {
				$query .=" inner join jo_invoiceshipads on jo_invoice.invoiceid=jo_invoiceshipads.invoiceshipaddressid";
			}
			if ($this->queryPlanner->requireTable("jo_currency_info$module")) {
				$query .=" left join jo_currency_info as jo_currency_info$module on jo_currency_info$module.id = jo_invoice.currency_id";
			}
			// lineItemFieldsInCalculation - is used to when line item fields are used in calculations
			if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				// should be present on when line item fields are selected for calculation
				if ($this->queryPlanner->requireTable("jo_inventoryproductreltmpInvoice", $matrix)) {
					$query .=" left join jo_inventoryproductrel as jo_inventoryproductreltmpInvoice on jo_invoice.invoiceid = jo_inventoryproductreltmpInvoice.id";
				}
				if ($this->queryPlanner->requireTable("jo_productsInvoice")) {
					$query .=" left join jo_products as jo_productsInvoice on jo_productsInvoice.productid = jo_inventoryproductreltmpInvoice.productid";
				}
				if ($this->queryPlanner->requireTable("jo_serviceInvoice")) {
					$query .=" left join jo_service as jo_serviceInvoice on jo_serviceInvoice.serviceid = jo_inventoryproductreltmpInvoice.productid";
				}
			}
			if ($this->queryPlanner->requireTable("jo_salesorderInvoice")) {
				$query .= " left join jo_salesorder as jo_salesorderInvoice on jo_salesorderInvoice.salesorderid=jo_invoice.salesorderid";
			}
			if ($this->queryPlanner->requireTable("jo_invoicecf")) {
				$query .= " left join jo_invoicecf on jo_invoice.invoiceid = jo_invoicecf.invoiceid";
			}
			if ($this->queryPlanner->requireTable("jo_groupsInvoice")) {
				$query .= " left join jo_groups as jo_groupsInvoice on jo_groupsInvoice.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_usersInvoice")) {
				$query .= " left join jo_users as jo_usersInvoice on jo_usersInvoice.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("jo_lastModifiedByInvoice")) {
				$query .= " left join jo_users as jo_lastModifiedByInvoice on jo_lastModifiedByInvoice.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbyInvoice')) {
				$query .= " left join jo_users as jo_createdbyInvoice on jo_createdbyInvoice.id = jo_crmentity.smcreatorid";
			}
			if ($this->queryPlanner->requireTable("jo_accountInvoice")) {
				$query .= " left join jo_account as jo_accountInvoice on jo_accountInvoice.accountid = jo_invoice.accountid";
			}
			if ($this->queryPlanner->requireTable("jo_contactdetailsInvoice")) {
				$query .= " left join jo_contactdetails as jo_contactdetailsInvoice on jo_contactdetailsInvoice.contactid = jo_invoice.contactid";
			}
			if ($this->queryPlanner->requireTable('jo_currency_info')) {
				$query .= ' LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_invoice.currency_id';
			}
			$query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
					getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
					" where jo_crmentity.deleted=0";
		} else if ($module == "SalesOrder") {
			$matrix = $this->queryPlanner->newDependencyMatrix();

			$matrix->setDependency('jo_inventoryproductreltmpSalesOrder', array('jo_productsSalesOrder', 'jo_serviceSalesOrder'));

			$query = "from jo_salesorder
			inner join jo_crmentity on jo_crmentity.crmid=jo_salesorder.salesorderid";

			if ($this->queryPlanner->requireTable("jo_sobillads")) {
				$query .= " inner join jo_sobillads on jo_salesorder.salesorderid=jo_sobillads.sobilladdressid";
			}
			if ($this->queryPlanner->requireTable("jo_soshipads")) {
				$query .= " inner join jo_soshipads on jo_salesorder.salesorderid=jo_soshipads.soshipaddressid";
			}
			if ($this->queryPlanner->requireTable("jo_currency_info$module")) {
				$query .= " left join jo_currency_info as jo_currency_info$module on jo_currency_info$module.id = jo_salesorder.currency_id";
			}
			if ($type !== 'COLUMNSTOTOTAL' || $this->lineItemFieldsInCalculation == true) {
				if ($this->queryPlanner->requireTable("jo_inventoryproductreltmpSalesOrder", $matrix)) {
					$query .= " left join jo_inventoryproductrel as jo_inventoryproductreltmpSalesOrder on jo_salesorder.salesorderid = jo_inventoryproductreltmpSalesOrder.id";
				}
				if ($this->queryPlanner->requireTable("jo_productsSalesOrder")) {
					$query .= " left join jo_products as jo_productsSalesOrder on jo_productsSalesOrder.productid = jo_inventoryproductreltmpSalesOrder.productid";
				}
				if ($this->queryPlanner->requireTable("jo_serviceSalesOrder")) {
					$query .= " left join jo_service as jo_serviceSalesOrder on jo_serviceSalesOrder.serviceid = jo_inventoryproductreltmpSalesOrder.productid";
				}
			}
			if ($this->queryPlanner->requireTable("jo_salesordercf")) {
				$query .=" left join jo_salesordercf on jo_salesorder.salesorderid = jo_salesordercf.salesorderid";
			}
			if ($this->queryPlanner->requireTable("jo_contactdetailsSalesOrder")) {
				$query .= " left join jo_contactdetails as jo_contactdetailsSalesOrder on jo_contactdetailsSalesOrder.contactid = jo_salesorder.contactid";
			}
			if ($this->queryPlanner->requireTable("jo_quotesSalesOrder")) {
				$query .= " left join jo_quotes as jo_quotesSalesOrder on jo_quotesSalesOrder.quoteid = jo_salesorder.quoteid";
			}
			if ($this->queryPlanner->requireTable("jo_accountSalesOrder")) {
				$query .= " left join jo_account as jo_accountSalesOrder on jo_accountSalesOrder.accountid = jo_salesorder.accountid";
			}
			if ($this->queryPlanner->requireTable("jo_potentialRelSalesOrder")) {
				$query .= " left join jo_potential as jo_potentialRelSalesOrder on jo_potentialRelSalesOrder.potentialid = jo_salesorder.potentialid";
			}
			if ($this->queryPlanner->requireTable("jo_invoice_recurring_info")) {
				$query .= " left join jo_invoice_recurring_info on jo_invoice_recurring_info.salesorderid = jo_salesorder.salesorderid";
			}
			if ($this->queryPlanner->requireTable("jo_groupsSalesOrder")) {
				$query .= " left join jo_groups as jo_groupsSalesOrder on jo_groupsSalesOrder.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_usersSalesOrder")) {
				$query .= " left join jo_users as jo_usersSalesOrder on jo_usersSalesOrder.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("jo_lastModifiedBySalesOrder")) {
				$query .= " left join jo_users as jo_lastModifiedBySalesOrder on jo_lastModifiedBySalesOrder.id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable('jo_createdbySalesOrder')) {
				$query .= " left join jo_users as jo_createdbySalesOrder on jo_createdbySalesOrder.id = jo_crmentity.smcreatorid";
			}
			if ($this->queryPlanner->requireTable('jo_currency_info')) {
				$query .= ' LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_salesorder.currency_id';
			}
			$query .= " " . $this->getRelatedModulesQuery($module, $this->secondarymodule) .
					getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
					" where jo_crmentity.deleted=0";
		} else if ($module == "Campaigns") {
			$query = "from jo_campaign
			inner join jo_crmentity on jo_crmentity.crmid=jo_campaign.campaignid";
			if ($this->queryPlanner->requireTable("jo_campaignscf")) {
				$query .= " inner join jo_campaignscf as jo_campaignscf on jo_campaignscf.campaignid=jo_campaign.campaignid";
			}
			if ($this->queryPlanner->requireTable("jo_productsCampaigns")) {
				$query .= " left join jo_products as jo_productsCampaigns on jo_productsCampaigns.productid = jo_campaign.product_id";
			}
			if ($this->queryPlanner->requireTable("jo_groupsCampaigns")) {
				$query .= " left join jo_groups as jo_groupsCampaigns on jo_groupsCampaigns.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_usersCampaigns")) {
				$query .= " left join jo_users as jo_usersCampaigns on jo_usersCampaigns.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("jo_lastModifiedBy$module")) {
				$query .= " left join jo_users as jo_lastModifiedBy" . $module . " on jo_lastModifiedBy" . $module . ".id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable("jo_createdby$module")) {
				$query .= " left join jo_users as jo_createdby$module on jo_createdby$module.id = jo_crmentity.smcreatorid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" where jo_crmentity.deleted=0";
		} else if ($module == "Emails") {
			$query = "from jo_activity
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid AND jo_activity.activitytype = 'Emails'";

			if ($this->queryPlanner->requireTable("jo_email_track")) {
				$query .= " LEFT JOIN jo_email_track ON jo_email_track.mailid = jo_activity.activityid";
			}
			if ($this->queryPlanner->requireTable("jo_groupsEmails")) {
				$query .= " LEFT JOIN jo_groups AS jo_groupsEmails ON jo_groupsEmails.groupid = jo_crmentity.smownerid";
			}
			if ($this->queryPlanner->requireTable("jo_usersEmails")) {
				$query .= " LEFT JOIN jo_users AS jo_usersEmails ON jo_usersEmails.id = jo_crmentity.smownerid";
			}

			// TODO optimize inclusion of these tables
			$query .= " LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";

			if ($this->queryPlanner->requireTable("jo_lastModifiedBy$module")) {
				$query .= " LEFT JOIN jo_users AS jo_lastModifiedBy" . $module . " ON jo_lastModifiedBy" . $module . ".id = jo_crmentity.modifiedby";
			}
			if ($this->queryPlanner->requireTable("jo_createdby$module")) {
				$query .= " left join jo_users as jo_createdby$module on jo_createdby$module.id = jo_crmentity.smcreatorid";
			}

			$query .= " ".$this->getRelatedModulesQuery($module,$this->secondarymodule).
					getNonAdminAccessControlQuery($this->primarymodule,$current_user).
					" WHERE jo_crmentity.deleted = 0";
		} else {
			if ($module != '') {
				$focus = CRMEntity::getInstance($module);
				$query = $focus->generateReportsQuery($module, $this->queryPlanner) .
						$this->getRelatedModulesQuery($module, $this->secondarymodule) .
						getNonAdminAccessControlQuery($this->primarymodule, $current_user) .
						" WHERE jo_crmentity.deleted=0";
			}
		}
		$log->info("ReportRun :: Successfully returned getReportsQuery" . $module);

		return $query;
	}

	/** function to get query for the given reportid,filterlist,type
	 *  @ param $reportid : Type integer
	 *  @ param $filtersql : Type Array
	 *  @ param $module : Type String
	 *  this returns join query for the report
	 */
	function sGetSQLforReport($reportid, $filtersql, $type = '', $chartReport = false, $startLimit = false, $endLimit = false) {
		global $log;

		$columnlist = $this->getQueryColumnsList($reportid, $type);
		$groupslist = $this->getGroupingList($reportid);
		$groupTimeList = $this->getGroupByTimeList($reportid);
		$stdfilterlist = $this->getStdFilterList($reportid);
		$columnstotallist = $this->getColumnsTotal($reportid);
		$advfiltersql = $this->getAdvFilterSql($reportid);

		$this->totallist = $columnstotallist;
		
		$wheresql = "";
		
		global $current_user;
		//Fix for ticket #4915.
		$selectlist = $columnlist;
		//columns list
		if (isset($selectlist)) {
			$selectedcolumns = implode(", ", $selectlist);
			if ($chartReport == true) {
				$selectedcolumns .= ", count(*) AS 'groupby_count'";
			}
		}
		//groups list
		if (isset($groupslist)) {
			$groupsquery = implode(", ", $groupslist);
		}
		if (isset($groupTimeList)) {
			$groupTimeQuery = implode(", ", $groupTimeList);
		}

		//standard list
		if (isset($stdfilterlist)) {
			$stdfiltersql = implode(", ", $stdfilterlist);
		}
		//columns to total list
		if (isset($columnstotallist)) {
			$columnstotalsql = implode(", ", $columnstotallist);
		}
		if ($stdfiltersql != "") {
			$wheresql = " and " . $stdfiltersql;
		}

		if (isset($filtersql) && $filtersql !== false && $filtersql != '') {
			$advfiltersql = $filtersql;
		}
		if ($advfiltersql != "") {
			$wheresql .= " and " . $advfiltersql;
		}

        if($this->_reportquery == false){
			$reportquery = $this->getReportsQuery($this->primarymodule, $type);
            $this->_reportquery = $reportquery;
        } else {
            $reportquery = $this->_reportquery;
        }

		// If we don't have access to any columns, let us select one column and limit result to shown we have not results
		// Fix for: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/4758 - Prasad
		$allColumnsRestricted = false;

		if ($type == 'COLUMNSTOTOTAL') {
			if ($columnstotalsql != '') {
				$reportquery = "select " . $columnstotalsql . " " . $reportquery . " " . $wheresql;
			}
		} else {
			if ($selectedcolumns == '') {
				// Fix for: http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/4758 - Prasad

				$selectedcolumns = "''"; // "''" to get blank column name
				$allColumnsRestricted = true;
			}

			$removeDistinct = false;
			foreach ($columnlist as $key => $value) {
				$tableList = explode(':', $key); // 0 => tablename, 1= > fieldname, 2=> FieldnameAliases, 3=>fieldname, 4=> typeof field
				if($tableList[0] == 'jo_inventoryproductrel'){
					$removeDistinct = true;
					break;
				}
			}
			if($removeDistinct) {
				$reportquery = "SELECT " . $selectedcolumns . " " . $reportquery . " " . $wheresql;
			} else {
				$reportquery = "SELECT DISTINCT " . $selectedcolumns . " " . $reportquery . " " . $wheresql;
			}
		}

		$reportquery = listQueryNonAdminChange($reportquery, $this->primarymodule);

		if (trim($groupsquery) != "" && $type !== 'COLUMNSTOTOTAL') {
			if ($chartReport == true) {
				$reportquery .= "group by " . $this->GetFirstSortByField($reportid);
			} else {
				$reportquery .= " order by " . $groupsquery;
			}
		}

		// Prasad: No columns selected so limit the number of rows directly.
		if ($allColumnsRestricted) {
			$reportquery .= " limit 0";
		} else if ($startLimit !== false && $endLimit !== false) {
			$reportquery .= " LIMIT $startLimit, $endLimit";
		}

		preg_match('/&amp;/', $reportquery, $matches);
		if (!empty($matches)) {
			$report = str_replace('&amp;', '&', $reportquery);
			$reportquery = $this->replaceSpecialChar($report);
		}
		$log->info("ReportRun :: Successfully returned sGetSQLforReport" . $reportid);

        if(!$this->_tmptablesinitialized){
			$this->queryPlanner->initializeTempTables();
            $this->_tmptablesinitialized = true;
        }

		return $reportquery;
	}

	/** function to get the report output in HTML,PDF,TOTAL,PRINT,PRINTTOTAL formats depends on the argument $outputformat
	 *  @ param $outputformat : Type String (valid parameters HTML,PDF,TOTAL,PRINT,PRINT_TOTAL)
	 *  @ param $filtersql : Type String
	 *  This returns HTML Report if $outputformat is HTML
	 *  		Array for PDF if  $outputformat is PDF
	 * 		HTML strings for TOTAL if $outputformat is TOTAL
	 * 		Array for PRINT if $outputformat is PRINT
	 * 		HTML strings for TOTAL fields  if $outputformat is PRINTTOTAL
	 * 		HTML strings for
	 */
	// Performance Optimization: Added parameter directOutput to avoid building big-string!
	function GenerateReport($outputformat, $filtersql, $directOutput = false, $startLimit = false, $endLimit = false, $operation = false) {
		global $adb, $current_user, $php_max_execution_time;
		global $modules, $app_strings;
		global $mod_strings, $current_language;
        $get_userdetails = get_privileges($current_user->id);
        foreach ($get_userdetails as $key => $value) {
            if(is_object($value)){
                $value = (array) $value;
                foreach ($value as $decode_key => $decode_value) {
                    if(is_object($decode_value)){
                        $value[$decode_key] = (array) $decode_value;
                    }
                }
                $$key = $value;
                }else{
                    $$key = $value;
                }
        }
		$modules_selected = array();
		$modules_selected[] = $this->primarymodule;
		if (!empty($this->secondarymodule)) {
			$sec_modules = split(":", $this->secondarymodule);
			for ($i = 0; $i < count($sec_modules); $i++) {
				$modules_selected[] = $sec_modules[$i];
			}
		}

		$userCurrencyInfo = getCurrencySymbolandCRate($current_user->currency_id);
		$userCurrencySymbol = $userCurrencyInfo['symbol'];

		// Update Reference fields list list
		$referencefieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from jo_field WHERE uitype in (10,101)", array());
		if ($referencefieldres) {
			foreach ($referencefieldres as $referencefieldrow) {
				$uiType = $referencefieldrow['uitype'];
				$modprefixedlabel = getTabModuleName($referencefieldrow['tabid']) . ' ' . $referencefieldrow['fieldlabel'];
				$modprefixedlabel = str_replace(' ', '_', $modprefixedlabel);

				if ($uiType == 10 && !in_array($modprefixedlabel, $this->ui10_fields)) {
					$this->ui10_fields[] = $modprefixedlabel;
				} elseif ($uiType == 101 && !in_array($modprefixedlabel, $this->ui101_fields)) {
					$this->ui101_fields[] = $modprefixedlabel;
				}
			}
		}

		if ($outputformat == "PDF") {
			$sSQL = $this->sGetSQLforReport($this->reportid, $filtersql, $outputformat, false, $startLimit, $endLimit);
			$result = $adb->pquery($sSQL, array());
			if ($is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
				$picklistarray = $this->getAccessPickListValues();
			$noofrows = $adb->num_rows($result);
        	$arr_val = array();
			if ($noofrows > 0) {
				// Number of fields in the result
				$y = $adb->num_fields($result);
				$custom_field_values = $adb->fetch_array($result);

				$fieldsList = array();
				// to get Field and it's Header Labels
				for ($i = 0; $i < $y; $i++) {
					$field = $adb->field_name($result, $i);

					list($module, $fieldLabel) = explode('_', $field->name, 2);
					$translatedLabel = getTranslatedString($fieldLabel, $module);
					if ($fieldLabel == $translatedLabel) {
						$translatedLabel = getTranslatedString(str_replace('_', ' ', $fieldLabel), $module);
					} else {
						$translatedLabel = str_replace('_', ' ', $translatedLabel);
					}
					// In reports we are converting "&" to "and" in query. So field name will not be translated
					// if this replacement is done. Added to handle that case.
					if ((strpos($fieldLabel, '_and_') !== false) && ($translatedLabel == str_replace('_', ' ', $fieldLabel))) {
						$tempLabel = getTranslatedString(str_replace('and', '&', $translatedLabel), $module);
						if ($tempLabel !== $translatedLabel) {
							$translatedLabel = $tempLabel;
						}
					}
					// End
					$moduleLabel ='';
					if(in_array($module,$modules_selected)){
						$moduleLabel = getTranslatedString($module,$module);
					}
					$headerLabel = $translatedLabel;
					if(!empty($this->secondarymodule)) {
						if($moduleLabel != '') {
							$headerLabel = $moduleLabel." ". $translatedLabel;
						}
					}
					$fieldsList[$i]['field'] = $field; 
					$fieldsList[$i]['headerlabel'] = $headerLabel; 
				}
				do {
					$arraylists = Array();
					for ($i = 0; $i < $y; $i++) {
						$fld = $fieldsList[$i]['field'];
						$headerLabel = $fieldsList[$i]['headerlabel'];
						// Check for role based pick list
						$fieldvalue = getReportFieldValue($this, $picklistarray, $fld, $custom_field_values, $i, $operation);

						if ($fld->name == $this->primarymodule . '_LBL_ACTION' && $fieldvalue != '-' && $operation != 'ExcelExport') {
							if($this->primarymodule == 'ModComments') {
								$fieldvalue = "<a href='index.php?module=".getSalesEntityType($fieldvalue)."&view=Detail&record=".$fieldvalue."' target='_blank'>" . getTranslatedString('LBL_VIEW_DETAILS', 'Reports') . "</a>";
							} else {
								$fieldvalue = "<a href='index.php?module={$this->primarymodule}&view=Detail&record={$fieldvalue}' target='_blank'>" . getTranslatedString('LBL_VIEW_DETAILS', 'Reports') . "</a>";
							}
						}
						if (is_array($sec_modules) && (in_array(str_replace('_LBL_ACTION', '', $fld->name), $sec_modules))) {
							continue;
						}

						$arraylists[$headerLabel] = $fieldvalue;
					}
					$arr_val[] = $arraylists;
					set_time_limit($php_max_execution_time);
				} while ($custom_field_values = $adb->fetch_array($result));
            	$data['data'] = $arr_val;
			}
			$data['count'] = $noofrows;
			return $data;
		} elseif ($outputformat == "TOTALXLS") {
			$escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
			$totalpdf = array();
			$sSQL = $this->sGetSQLforReport($this->reportid, $filtersql, "COLUMNSTOTOTAL");
			if (isset($this->totallist)) {
				if ($sSQL != "") {
					$result = $adb->query($sSQL);
					$y = $adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);

					static $mod_query_details = array();
					foreach ($this->totallist as $key => $value) {
						$fieldlist = explode(":", $key);
						$key = $fieldlist[1] . '_' . $fieldlist[2];
						if (!isset($mod_query_details[$key]['modulename']) && !isset($mod_query_details[$key]['uitype'])) {
							$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from jo_field where tablename = ? and columnname=?", array($fieldlist[1], $fieldlist[2]));
							$moduleName = getTabModuleName($adb->query_result($mod_query, 0, 'tabid'));
							$mod_query_details[$key]['translatedmodulename'] = getTranslatedString($moduleName, $moduleName);
							$mod_query_details[$key]['modulename'] = $moduleName;
							$mod_query_details[$key]['uitype'] = $adb->query_result($mod_query, 0, "uitype");
						}

						if ($adb->num_rows($mod_query) > 0) {
							$module_name = $mod_query_details[$key]['modulename'];
							$translatedModuleLabel = $mod_query_details[$key]['translatedmodulename'];
							$fieldlabel = trim(str_replace($escapedchars, " ", $fieldlist[3]));
							$fieldlabel = str_replace("_", " ", $fieldlabel);
							if ($module_name) {
								$field = $translatedModuleLabel . " " . getTranslatedString($fieldlabel, $module_name);
							} else {
								$field = getTranslatedString($fieldlabel);
							}
						}
						// Since there are duplicate entries for this table
						if ($fieldlist[1] == 'jo_inventoryproductrel') {
							$module_name = $this->primarymodule;
						}
						$uitype_arr[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $mod_query_details[$key]['uitype'];
						$totclmnflds[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $field;
					}
					for ($i = 0; $i < $y; $i++) {
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];
					}

					$rowcount = 0;
					foreach ($totclmnflds as $key => $value) {
						$col_header = trim(str_replace($modules, " ", $value));
						$fld_name_1 = $this->primarymodule . "_" . trim($value);
						$fld_name_2 = $this->secondarymodule . "_" . trim($value);
						if ($uitype_arr[$key] == 71 || $uitype_arr[$key] == 72 ||
								in_array($fld_name_1, $this->append_currency_symbol_to_value) || in_array($fld_name_2, $this->append_currency_symbol_to_value)) {
							$col_header .= " (" . $app_strings['LBL_IN'] . " " . $current_user->currency_symbol . ")";
							$convert_price = true;
						} else {
							$convert_price = false;
						}
						$value = trim($key);
						$arraykey = $value . '_SUM';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$arraykey] = $conv_value;
						} else {
							$totalpdf[$rowcount][$arraykey] = '';
						}

						$arraykey = $value . '_AVG';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$arraykey] = $conv_value;
						} else {
							$totalpdf[$rowcount][$arraykey] = '';
						}

						$arraykey = $value . '_MIN';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$arraykey] = $conv_value;
						} else {
							$totalpdf[$rowcount][$arraykey] = '';
						}

						$arraykey = $value . '_MAX';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71 ,72))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$arraykey] = $conv_value;
						} else {
							$totalpdf[$rowcount][$arraykey] = '';
						}
						$rowcount++;
					}
				}
			}
			return $totalpdf;
		} elseif ($outputformat == 'XLS') {
			$escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
			$totalpdf = array();
			$sSQL = $this->sGetSQLforReport($this->reportid, $filtersql, "COLUMNSTOTOTAL");
			if (isset($this->totallist)) {
				if ($sSQL != '') {
					$result = $adb->query($sSQL);
					$y = $adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);

					static $mod_query_details = array();
					foreach ($this->totallist as $key => $value) {
						$fieldlist = explode(':', $key);
						$key = $fieldlist[1].'_'.$fieldlist[2];
						if (!isset($mod_query_details[$this->reportid][$key]['modulename']) && !isset($mod_query_details[$this->reportid][$key]['uitype'])) {
							$mod_query = $adb->pquery('SELECT DISTINCT(tabid) AS tabid, uitype AS uitype FROM jo_field WHERE tablename = ? AND columnname=?', array($fieldlist[1], $fieldlist[2]));
							$moduleName = getTabModuleName($adb->query_result($mod_query, 0, 'tabid'));
							$mod_query_details[$this->reportid][$key]['translatedmodulename'] = getTranslatedString($moduleName, $moduleName);
							$mod_query_details[$this->reportid][$key]['modulename'] = $moduleName;
							$mod_query_details[$this->reportid][$key]['uitype'] = $adb->query_result($mod_query, 0, 'uitype');
						}

						if ($adb->num_rows($mod_query) > 0) {
							$module_name = $mod_query_details[$this->reportid][$key]['modulename'];
							$translatedModuleLabel = $mod_query_details[$this->reportid][$key]['translatedmodulename'];
							$fieldlabel = trim(str_replace($escapedchars, ' ', $fieldlist[3]));
							$fieldlabel = str_replace('_', ' ', $fieldlabel);
							if ($module_name) {
								$field = $translatedModuleLabel.' '.getTranslatedString($fieldlabel, $module_name);
							} else {
								$field = getTranslatedString($fieldlabel);
							}
						}
						// Since there are duplicate entries for this table
						if ($fieldlist[1] == 'jo_inventoryproductrel') {
							$module_name = $this->primarymodule;
						}
						$uitype_arr[str_replace($escapedchars, ' ', $module_name.'_'.$fieldlist[3])] = $mod_query_details[$this->reportid][$key]['uitype'];
						$totclmnflds[str_replace($escapedchars, ' ', $module_name.'_'.$fieldlist[3])] = $field;
					}

					$sumcount = 0;
					$avgcount = 0;
					$mincount = 0;
					$maxcount = 0;
					for ($i = 0; $i < $y; $i++) {
						$fld = $adb->field_name($result, $i);
						if (strpos($fld->name, '_SUM') !== false) {
							$sumcount++;
						} else if (strpos($fld->name, '_AVG') !== false) {
							$avgcount++;
						} else if (strpos($fld->name, '_MIN') !== false) {
							$mincount++;
						} else if (strpos($fld->name, '_MAX') !== false) {
							$maxcount++;
						}
						$keyhdr[decode_html($fld->name)] = $custom_field_values[$i];
					}

					$rowcount = 0;
					foreach ($totclmnflds as $key => $value) {
						$col_header = trim(str_replace($modules, ' ', $value));
						$fld_name_1 = $this->primarymodule.'_'.trim($value);
						$fld_name_2 = $this->secondarymodule.'_'.trim($value);
						if ($uitype_arr[$key] == 71 || $uitype_arr[$key] == 72 || $uitype_arr[$key] == 74 ||
								in_array($fld_name_1, $this->append_currency_symbol_to_value) || in_array($fld_name_2, $this->append_currency_symbol_to_value)) {
							$col_header .= ' ('.$app_strings['LBL_IN'].' '.$current_user->currency_symbol.')';
							$convert_price = true;
						} else {
							$convert_price = false;
						}
						$value = trim($key);
						$totalpdf[$rowcount]['Field Names'] = $col_header;
						$originalkey = $value.'_SUM';
						$arraykey = $this->replaceSpecialChar($value).'_SUM';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$originalkey] = $conv_value;
						} else if ($sumcount) {
							$totalpdf[$rowcount][$originalkey] = '';
						}

						$originalkey = $value.'_AVG';
						$arraykey = $this->replaceSpecialChar($value).'_AVG';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$originalkey] = $conv_value;
						} else if ($avgcount) {
							$totalpdf[$rowcount][$originalkey] = '';
						}

						$originalkey = $value.'_MIN';
						$arraykey = $this->replaceSpecialChar($value).'_MIN';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$originalkey] = $conv_value;
						} else if ($mincount) {
							$totalpdf[$rowcount][$originalkey] = '';
						}

						$originalkey = $value.'_MAX';
						$arraykey = $this->replaceSpecialChar($value).'_MAX';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, false, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							} else {
								if ($operation == 'ExcelExport') {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true, true);
									if ($uitype_arr[$key] == 74) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								} else {
									$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
									if (in_array($uitype_arr[$key], array(71, 72, 74))) {
										$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
									}
								}
							}
							$totalpdf[$rowcount][$originalkey] = $conv_value;
						} else if ($maxcount) {
							$totalpdf[$rowcount][$originalkey] = '';
						}
						$rowcount++;
					}
					$totalpdf[$rowcount]['sumcount'] = $sumcount;
					$totalpdf[$rowcount]['avgcount'] = $avgcount;
					$totalpdf[$rowcount]['mincount'] = $mincount;
					$totalpdf[$rowcount]['maxcount'] = $maxcount;
				}
			}
			return $totalpdf;
		} elseif ($outputformat == "TOTALHTML") {
			$escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
			$sSQL = $this->sGetSQLforReport($this->reportid, $filtersql, "COLUMNSTOTOTAL");

			static $modulename_cache = array();

			if (isset($this->totallist)) {
				if ($sSQL != "") {
					$result = $adb->query($sSQL);
					$y = $adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);
					$reportModule = 'Reports';
					$coltotalhtml .= "<table align='center' width='60%' cellpadding='3' cellspacing='0' border='0' class='rptTable'><tr><td class='rptCellLabel'>" . vtranslate('LBL_FIELD_NAMES', $reportModule) . "</td><td class='rptCellLabel'>" . vtranslate('LBL_SUM', $reportModule) . "</td><td class='rptCellLabel'>" . vtranslate('LBL_AVG', $reportModule) . "</td><td class='rptCellLabel'>" . vtranslate('LBL_MIN', $reportModule) . "</td><td class='rptCellLabel'>" . vtranslate('LBL_MAX', $reportModule) . "</td></tr>";

					// Performation Optimization: If Direct output is desired
					if ($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END

					foreach ($this->totallist as $key => $value) {
						$fieldlist = explode(":", $key);

						$module_name = NULL;
						$cachekey = $fieldlist[1] . ":" . $fieldlist[2];
						if (!isset($modulename_cache[$cachekey])) {
							$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from jo_field where tablename = ? and columnname=?", array($fieldlist[1], $fieldlist[2]));
							if ($adb->num_rows($mod_query) > 0) {
								$module_name = getTabModuleName($adb->query_result($mod_query, 0, 'tabid'));
								$modulename_cache[$cachekey] = $module_name;
							}
						} else {
							$module_name = $modulename_cache[$cachekey];
						}
						if ($module_name) {
							$fieldlabel = trim(str_replace($escapedchars, " ", $fieldlist[3]));
							$fieldlabel = str_replace("_", " ", $fieldlabel);
							$field = getTranslatedString($module_name, $module_name) . " " . getTranslatedString($fieldlabel, $module_name);
						} else {
							$field = getTranslatedString($fieldlabel);
						}

						$uitype_arr[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $adb->query_result($mod_query, 0, "uitype");
						$totclmnflds[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $field;
					}
					for ($i = 0; $i < $y; $i++) {
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];
					}

					foreach ($totclmnflds as $key => $value) {
						$coltotalhtml .= '<tr class="rptGrpHead" valign=top>';
						$col_header = trim(str_replace($modules, " ", $value));
						$fld_name_1 = $this->primarymodule . "_" . trim($value);
						$fld_name_2 = $this->secondarymodule . "_" . trim($value);
						if ($uitype_arr[$key] == 71 || $uitype_arr[$key] == 72 ||
								in_array($fld_name_1, $this->append_currency_symbol_to_value) || in_array($fld_name_2, $this->append_currency_symbol_to_value)) {
							$col_header .= " (" . $app_strings['LBL_IN'] . " " . $current_user->currency_symbol . ")";
							$convert_price = true;
						} else {
							$convert_price = false;
						}
						$coltotalhtml .= '<td class="rptData">' . $col_header . '</td>';
						$value = trim($key);
						$arraykey = $value . '_SUM';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price)
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
						}else {
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = $value . '_AVG';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price)
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
						}else {
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = $value . '_MIN';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price)
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
						}else {
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$arraykey = $value . '_MAX';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price)
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
							else
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							$coltotalhtml .= '<td class="rptTotal">' . $conv_value . '</td>';
						}else {
							$coltotalhtml .= '<td class="rptTotal">&nbsp;</td>';
						}

						$coltotalhtml .= '<tr>';

						// Performation Optimization: If Direct output is desired
						if ($directOutput) {
							echo $coltotalhtml;
							$coltotalhtml = '';
						}
						// END
					}

					$coltotalhtml .= "</table>";

					// Performation Optimization: If Direct output is desired
					if ($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END
				}
			}
			return $coltotalhtml;
		} elseif ($outputformat == "PRINT") {
			$reportData = $this->GenerateReport('PDF', $filtersql);
			if (is_array($reportData) && $reportData['count'] > 0) {
				$data = $reportData['data'];
				$noofrows = $reportData['count'];
				$firstRow = reset($data);
				$headers = array_keys($firstRow);
				foreach ($headers as $headerName) {
					if ($headerName == 'ACTION' || $headerName == vtranslate('LBL_ACTION', $this->primarymodule) || $headerName == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL_ACTION', $this->primarymodule) || $headerName == vtranslate('LBL ACTION', $this->primarymodule) || $key == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL ACTION', $this->primarymodule)) {
						continue;
					}
					$header .= '<th>' . $headerName . '</th>';
				}
				$groupslist = $this->getGroupingList($this->reportid);
				foreach ($groupslist as $reportFieldName => $reportFieldValue) {
					$nameParts = explode(":", $reportFieldName);
					list($groupFieldModuleName, $groupFieldName) = split("_", $nameParts[2], 2);
					$groupByFieldNames[] = vtranslate(str_replace('_', ' ', $groupFieldName), $groupFieldModuleName);
				}
				if (count($groupByFieldNames) > 0) {
					if (count($groupByFieldNames) == 1) {
						$firstField = $groupByFieldNames[0];
					} else if (count($groupByFieldNames) == 2) {
						$firstField = $groupByFieldNames[0];
						$secondField = $groupByFieldNames[1];
					} else if (count($groupByFieldNames) == 3) {
						$firstField = $groupByFieldNames[0];
						$secondField = $groupByFieldNames[1];
						$thirdField = $groupByFieldNames[2];
					}
					$firstValue = ' ';
					$secondValue = ' ';
					$thirdValue = ' ';
					foreach ($data as $key => $valueArray) {
						$valtemplate .= '<tr>';
						foreach ($valueArray as $fieldName => $fieldValue) {
							if ($fieldName == 'ACTION' || $fieldName == vtranslate('LBL_ACTION', $this->primarymodule) || $fieldName == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL_ACTION', $this->primarymodule) || $fieldName == vtranslate('LBL ACTION', $this->primarymodule) || $fieldName == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL ACTION', $this->primarymodule)) {
								continue;
							}
							if (($fieldName == $firstField || strstr($fieldName, $firstField)) && ($firstValue == $fieldValue || $firstValue == " ")) {
								if ($firstValue == ' ' || $fieldValue == '-') {
									$valtemplate .= "<td style='border-bottom: 0;'>" . $fieldValue . "</td>";
								} else {
									$valtemplate .= "<td style='border-bottom: 0; border-top: 0;'>&nbsp;</td>";
								}
								if ($fieldValue != ' ') {
									$firstValue = $fieldValue;
								}
							} else if (($fieldName == $secondField || strstr($fieldName, $secondField)) && ($secondValue == $fieldValue || $secondValue == " ")) {
								if ($secondValue == ' ' || $secondValue == '-') {
									$valtemplate .= "<td style='border-bottom: 0;'>" . $fieldValue . "</td>";
								} else {
									$valtemplate .= "<td style='border-bottom: 0; border-top: 0;'>&nbsp;</td>";
								}
								if ($fieldValue != ' ') {
									$secondValue = $fieldValue;
								}
							} else if (($fieldName == $thirdField || strstr($fieldName, $thirdField)) && ($thirdValue == $fieldValue || $thirdValue == " ")) {
								if ($thirdValue == ' ' || $thirdValue == '-') {
									$valtemplate .= "<td style='border-bottom: 0;'>" . $fieldValue . "</td>";
								} else {
									$valtemplate .= "<td style='border-bottom: 0; border-top: 0;'>&nbsp;</td>";
								}
								if ($fieldValue != ' ') {
									$thirdValue = $fieldValue;
								}
							} else {
								$valtemplate .= "<td style='border-bottom: 0;'>" . $fieldValue . "</td>";
								if ($fieldName == $firstField || strstr($fieldName, $firstField)) {
									$firstValue = $fieldValue;
								} else if ($fieldName == $secondField || strstr($fieldName, $secondField)) {
									$secondValue = $fieldValue;
								} else if ($fieldName == $thirdField || strstr($fieldName, $thirdField)) {
									$thirdValue = $fieldValue;
								}
							}
						}
						$valtemplate .= '</tr>';
					}
				} else {
					foreach ($data as $key => $values) {
						$valtemplate .= '<tr>';
						foreach ($values as $fieldName => $value) {
							if ($fieldName == 'ACTION' || $fieldName == vtranslate('LBL_ACTION', $this->primarymodule) || $fieldName == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL_ACTION', $this->primarymodule) || $fieldName == vtranslate('LBL ACTION', $this->primarymodule) || $fieldName == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL ACTION', $this->primarymodule)) {
								continue;
							}
							$valtemplate .= "<td>" . $value . "</td>";
						}
					}
				}
				$sHTML = '<thead>' . $header . '</thead>' . "<tbody>" . $valtemplate . "</tbody>";
				$return_data[] = $sHTML;
				$return_data[] = $noofrows;
			} else {
				$return_data = array('', 0);
			}
			return $return_data;
		} elseif ($outputformat == "PRINT_TOTAL") {
			$escapedchars = Array('_SUM', '_AVG', '_MIN', '_MAX');
			$sSQL = $this->sGetSQLforReport($this->reportid, $filtersql, "COLUMNSTOTOTAL");
			if (isset($this->totallist)) {
				if ($sSQL != "") {
					$result = $adb->query($sSQL);
					$y = $adb->num_fields($result);
					$custom_field_values = $adb->fetch_array($result);
					$reportModule = 'Reports';

					$coltotalhtml .= "<br /><table align='center' width='60%' cellpadding='3' cellspacing='0' border='1' class='printReport'><tr><td class='rptCellLabel'><b>" . vtranslate('LBL_FIELD_NAMES', $reportModule) . "</b></td><td><b>" . vtranslate('LBL_SUM', $reportModule) . "</b></td><td><b>" . vtranslate('LBL_AVG', $reportModule) . "</b></td><td><b>" . vtranslate('LBL_MIN', $reportModule) . "</b></td><td><b>" . vtranslate('LBL_MAX', $reportModule) . "</b></td></tr>";

					// Performation Optimization: If Direct output is desired
					if ($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END

					static $mod_query_details = array();
					foreach ($this->totallist as $key => $value) {
						$fieldlist = explode(":", $key);
						$detailsKey = implode('_', array($fieldlist[1], $fieldlist[2]));
						if (!isset($mod_query_details[$detailsKey]['modulename']) && !isset($mod_query_details[$detailsKey]['uitype'])) {
							$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid, uitype as uitype from jo_field where tablename = ? and columnname=?", array($fieldlist[1], $fieldlist[2]));
							$moduleName = getTabModuleName($adb->query_result($mod_query, 0, 'tabid'));
							$mod_query_details[$detailsKey]['modulename'] = $moduleName;
							$mod_query_details[$detailsKey]['translatedmodulename'] = getTranslatedString($moduleName, $moduleName);
							$mod_query_details[$detailsKey]['uitype'] = $adb->query_result($mod_query, 0, "uitype");
						}
						if ($adb->num_rows($mod_query) > 0) {
							$module_name = $mod_query_details[$detailsKey]['modulename'];
							$translated_moduleName = $mod_query_details[$detailsKey]['translatedmodulename'];
							$fieldlabel = trim(str_replace($escapedchars, " ", $fieldlist[3]));
							$fieldlabel = str_replace("_", " ", $fieldlabel);
							if ($module_name) {
								$field = $translated_moduleName . " " . getTranslatedString($fieldlabel, $module_name);
							} else {
								$field = getTranslatedString($fieldlabel);
							}
						}
						$uitype_arr[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $mod_query_details[$detailsKey]['uitype'];
						$totclmnflds[str_replace($escapedchars, " ", $module_name . "_" . $fieldlist[3])] = $field;
					}

					for ($i = 0; $i < $y; $i++) {
						$fld = $adb->field_name($result, $i);
						$keyhdr[$fld->name] = $custom_field_values[$i];
					}
					foreach ($totclmnflds as $key => $value) {
						$coltotalhtml .= '<tr class="rptGrpHead">';
						$col_header = getTranslatedString(trim(str_replace($modules, " ", $value)));
						$fld_name_1 = $this->primarymodule . "_" . trim($value);
						$fld_name_2 = $this->secondarymodule . "_" . trim($value);
						if (in_array($uitype_arr[$key], array('71', '72'))
								|| in_array($fld_name_1, $this->append_currency_symbol_to_value)
								|| in_array($fld_name_2, $this->append_currency_symbol_to_value)) {
							$convert_price = true;
						} else {
							$convert_price = false;
						}
						$coltotalhtml .= '<td class="rptData">' . $col_header . '</td>';
						$value = trim($key);
						$arraykey = $value . '_SUM';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
								$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
							} else {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							}
							$coltotalhtml .= "<td class='rptTotal'>" . $conv_value . '</td>';
						} else {
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$arraykey = $value . '_AVG';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
								$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
							} else {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							}
							$coltotalhtml .= "<td class='rptTotal'>" . $conv_value . '</td>';
						} else {
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$arraykey = $value . '_MIN';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
								$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
							} else {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							}
							$coltotalhtml .= "<td class='rptTotal'>" . $conv_value . '</td>';
						} else {
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$arraykey = $value . '_MAX';
						if (isset($keyhdr[$arraykey])) {
							if ($convert_price) {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey]);
								$conv_value = CurrencyField::appendCurrencySymbol($conv_value, $userCurrencySymbol);
							} else {
								$conv_value = CurrencyField::convertToUserFormat($keyhdr[$arraykey], null, true);
							}
							$coltotalhtml .= "<td class='rptTotal'>" . $conv_value . '</td>';
						} else {
							$coltotalhtml .= "<td class='rptTotal'>&nbsp;</td>";
						}

						$coltotalhtml .= '</tr>';

						// Performation Optimization: If Direct output is desired
						if ($directOutput) {
							echo $coltotalhtml;
							$coltotalhtml = '';
						}
						// END
					}

					$coltotalhtml .= "</table>";
					// Performation Optimization: If Direct output is desired
					if ($directOutput) {
						echo $coltotalhtml;
						$coltotalhtml = '';
					}
					// END
				}
			}
			return $coltotalhtml;
		}
	}

	//<<<<<<<new>>>>>>>>>>
	function getColumnsTotal($reportid) {
		// Have we initialized it already?
		if ($this->_columnstotallist !== false) {
			return $this->_columnstotallist;
		}

		global $adb;
		global $modules;
		global $log, $current_user;

		static $modulename_cache = array();

		// Not a good approach to get all the fields if not required(May leads to Performance issue)
		$query = "select primarymodule,secondarymodules from jo_reportmodules where reportmodulesid =?";
		$res = $adb->pquery($query, array($reportid));
		$modrow = $adb->fetch_array($res);
		$premod = $modrow["primarymodule"];
		$secmod = $modrow["secondarymodules"];
		$coltotalsql = "select jo_reportsummary.* from jo_report";
		$coltotalsql .= " inner join jo_reportsummary on jo_report.reportid = jo_reportsummary.reportsummaryid";
		$coltotalsql .= " where jo_report.reportid =?";

		$result = $adb->pquery($coltotalsql, array($reportid));

		while ($coltotalrow = $adb->fetch_array($result)) {
			$fieldcolname = $coltotalrow["columnname"];
			if ($fieldcolname != "none") {
				$fieldlist = explode(":", $fieldcolname);
				$field_tablename = $fieldlist[1];
				$field_columnname = $fieldlist[2];

				$cachekey = $field_tablename . ":" . $field_columnname;
				if (!isset($modulename_cache[$cachekey])) {
					$mod_query = $adb->pquery("SELECT distinct(tabid) as tabid from jo_field where tablename = ? and columnname=?", array($fieldlist[1], $fieldlist[2]));
					if ($adb->num_rows($mod_query) > 0) {
						$module_name = getTabModuleName($adb->query_result($mod_query, 0, 'tabid'));
						$modulename_cache[$cachekey] = $module_name;
					}
				} else {
					$module_name = $modulename_cache[$cachekey];
				}

				$fieldlabel = trim($fieldlist[3]);
				if ($field_tablename == 'jo_inventoryproductrel') {
					$field_columnalias = $premod . "_" . $fieldlist[3];
				} else {
					if ($module_name) {
						$field_columnalias = $module_name . "_" . $fieldlist[3];
					} else {
						$field_columnalias = $module_name . "_" . $fieldlist[3];
					}
				}

				//$field_columnalias = $fieldlist[3];
				$field_permitted = false;
				if (CheckColumnPermission($field_tablename, $field_columnname, $premod) != "false") {
					$field_permitted = true;
				} else {
					$mod = split(":", $secmod);
					foreach ($mod as $key) {
						if (CheckColumnPermission($field_tablename, $field_columnname, $key) != "false") {
							$field_permitted = true;
						}
					}
				}

				//Calculation fields of "Events" module should show in Calendar related report
				$secondaryModules = split(":", $secmod);
				if ($field_permitted === false && ($premod === 'Calendar' || in_array('Calendar', $secondaryModules)) && CheckColumnPermission($field_tablename, $field_columnname, "Events") != "false") {
					$field_permitted = true;
				}

				if ($field_permitted == true) {
					$field = $this->getColumnsTotalSQL($fieldlist, $premod);

					if ($fieldlist[4] == 2) {
						$stdfilterlist[$fieldcolname] = "sum($field) '" . $field_columnalias . "'";
					}
					if ($fieldlist[4] == 3) {
						//Fixed average calculation issue due to NULL values ie., when we use avg() function, NULL values will be ignored.to avoid this we use (sum/count) to find average.
						//$stdfilterlist[$fieldcolname] = "avg(".$fieldlist[1].".".$fieldlist[2].") '".$fieldlist[3]."'";
						$stdfilterlist[$fieldcolname] = "(sum($field)/count(*)) '" . $field_columnalias . "'";
					}
					if ($fieldlist[4] == 4) {
						$stdfilterlist[$fieldcolname] = "min($field) '" . $field_columnalias . "'";
					}
					if ($fieldlist[4] == 5) {
						$stdfilterlist[$fieldcolname] = "max($field) '" . $field_columnalias . "'";
					}

					$this->queryPlanner->addTable($field_tablename);
				}
			}
		}
		// Save the information
		$this->_columnstotallist = $stdfilterlist;

		$log->info("ReportRun :: Successfully returned getColumnsTotal" . $reportid);
		return $stdfilterlist;
	}

	//<<<<<<new>>>>>>>>>


	function getColumnsTotalSQL($fieldlist, $premod) {
		// Added condition to support detail report calculations
		if ($fieldlist[0] == 'cb') {
			$field_tablename = $fieldlist[1];
			$field_columnname = $fieldlist[2];
		} else {
			$field_tablename = $fieldlist[0];
			$field_columnname = $fieldlist[1];
			list($module, $fieldName) = split('_', $fieldlist[2], 2);
		}

		$field = $field_tablename . "." . $field_columnname;
		if ($field_tablename == 'jo_products' && $field_columnname == 'unit_price') {
			// Query needs to be rebuild to get the value in user preferred currency. [innerProduct and actual_unit_price are table and column alias.]
			$field = " innerProduct.actual_unit_price";
			$this->queryPlanner->addTable("innerProduct");
		}
		if ($field_tablename == 'jo_service' && $field_columnname == 'unit_price') {
			// Query needs to be rebuild to get the value in user preferred currency. [innerProduct and actual_unit_price are table and column alias.]
			$field = " innerService.actual_unit_price";
			$this->queryPlanner->addTable("innerService");
		}
		if (($field_tablename == 'jo_invoice' || $field_tablename == 'jo_quotes' || $field_tablename == 'jo_purchaseorder' || $field_tablename == 'jo_salesorder') && ($field_columnname == 'total' || $field_columnname == 'subtotal' || $field_columnname == 'discount_amount' || $field_columnname == 's_h_amount' || $field_columnname == 'paid' || $field_columnname == 'balance' || $field_columnname == 'received' || $field_columnname == 'adjustment' || $field_columnname == 'pre_tax_total')) {
			$field = " $field_tablename.$field_columnname/$field_tablename.conversion_rate ";
		}

		if ($field_tablename == 'jo_inventoryproductrel') {
			// Check added so that query planner can prepare query properly for inventory modules
			$this->lineItemFieldsInCalculation = true;
			$secondaryModules = explode(':', $this->secondarymodule);
			$inventoryModules = getInventoryModules();

			if(in_array($premod, $inventoryModules)){
				$inventoryModuleInstance = CRMEntity::getInstance($premod);
				$inventoryModuleName = $premod;
			} else {
				foreach($secondaryModules as $secondaryModule) {
					if(in_array($secondaryModule, $inventoryModules)){
						$inventoryModuleName = $secondaryModule;
						$inventoryModuleInstance = CRMEntity::getInstance($secondaryModule);
						$secmodule = $secondaryModule;
						break;
					}
				}
			}

			$field = $field_tablename.'tmp'.$inventoryModuleName.'.'.$field_columnname;
			$itemTableName = 'jo_inventoryproductreltmp' . $inventoryModuleName;
			$this->queryPlanner->addTable($itemTableName);
//			$primaryModuleInstance = CRMEntity::getInstance($premod);
			if ($field_columnname == 'listprice') {
				$field = $field . '/' . $inventoryModuleInstance->table_name . '.conversion_rate';
			} else if ($field_columnname == 'discount_amount') {
				$field = ' CASE WHEN ' . $itemTableName . '.discount_amount is not null THEN ' . $itemTableName . '.discount_amount/' . $inventoryModuleInstance->table_name . '.conversion_rate ' .
						'WHEN ' . $itemTableName . '.discount_percent IS NOT NULL THEN (' . $itemTableName . '.listprice*' . $itemTableName . '.quantity*' . $itemTableName . '.discount_percent/100/' . $inventoryModuleInstance->table_name . '.conversion_rate) ELSE 0 END ';
			}
		}
		return $field;
	}

	/** function to get query for the columns to total for the given reportid
	 *  @ param $reportid : Type integer
	 *  This returns columnstoTotal query for the reportid
	 */
	function getColumnsToTotalColumns($reportid) {
		global $adb;
		global $modules;
		global $log;

		$sreportstdfiltersql = "select jo_reportsummary.* from jo_report";
		$sreportstdfiltersql .= " inner join jo_reportsummary on jo_report.reportid = jo_reportsummary.reportsummaryid";
		$sreportstdfiltersql .= " where jo_report.reportid =?";

		$result = $adb->pquery($sreportstdfiltersql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for ($i = 0; $i < $noofrows; $i++) {
			$fieldcolname = $adb->query_result($result, $i, "columnname");

			if ($fieldcolname != "none") {
				$fieldlist = explode(":", $fieldcolname);
				if ($fieldlist[4] == 2) {
					$sSQLList[] = "sum(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
				}
				if ($fieldlist[4] == 3) {
					$sSQLList[] = "avg(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
				}
				if ($fieldlist[4] == 4) {
					$sSQLList[] = "min(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
				}
				if ($fieldlist[4] == 5) {
					$sSQLList[] = "max(" . $fieldlist[1] . "." . $fieldlist[2] . ") " . $fieldlist[3];
				}
			}
		}
		if (isset($sSQLList)) {
			$sSQL = implode(",", $sSQLList);
		}
		$log->info("ReportRun :: Successfully returned getColumnsToTotalColumns" . $reportid);
		return $sSQL;
	}

	/** Function to convert the Report Header Names into i18n
	 *  @param $fldname: Type Varchar
	 *  Returns Language Converted Header Strings
	 * */
	function getLstringforReportHeaders($fldname) {
		global $modules, $current_language, $current_user, $app_strings;
		$rep_header = ltrim($fldname);
		$rep_header = decode_html($rep_header);
		$labelInfo = explode('_', $rep_header);
		$rep_module = $labelInfo[0];
		if (is_array($this->labelMapping) && !empty($this->labelMapping[$rep_header])) {
			$rep_header = $this->labelMapping[$rep_header];
		} else {
			if ($rep_module == 'LBL') {
				$rep_module = '';
			}
			array_shift($labelInfo);
			$fieldLabel = decode_html(implode("_", $labelInfo));
			$rep_header_temp = preg_replace("/\s+/", "_", $fieldLabel);
			$rep_header = "$rep_module $fieldLabel";
		}
		$curr_symb = "";
		$fieldLabel = ltrim(str_replace($rep_module, '', $rep_header), '_');
		$fieldInfo = getFieldByReportLabel($rep_module, $fieldLabel);
		if ($fieldInfo['uitype'] == '71') {
			$curr_symb = " (" . $app_strings['LBL_IN'] . " " . $current_user->currency_symbol . ")";
		}
		$rep_header .=$curr_symb;

		return $rep_header;
	}

	/** Function to get picklist value array based on profile
	 *          *  returns permitted fields in array format
	 * */
	function getAccessPickListValues() {
		global $adb;
		global $current_user;
		$id = array(getTabid($this->primarymodule));
		if ($this->secondarymodule != '')
			array_push($id, getTabid($this->secondarymodule));

		$query = 'select fieldname,columnname,fieldid,fieldlabel,tabid,uitype from jo_field where tabid in(' . generateQuestionMarks($id) . ') and uitype in (15,33,55)'; //and columnname in (?)';
		$result = $adb->pquery($query, $id); //,$select_column));
		$roleid = $current_user->roleid;
		$subrole = getRoleSubordinates($roleid);
		if (count($subrole) > 0) {
			$roleids = $subrole;
			array_push($roleids, $roleid);
		} else {
			$roleids = $roleid;
		}

		$temp_status = Array();
		for ($i = 0; $i < $adb->num_rows($result); $i++) {
			$fieldname = $adb->query_result($result, $i, "fieldname");
			$fieldlabel = $adb->query_result($result, $i, "fieldlabel");
			$tabid = $adb->query_result($result, $i, "tabid");
			$uitype = $adb->query_result($result, $i, "uitype");

			$fieldlabel1 = str_replace(" ", "_", $fieldlabel);
			$keyvalue = getTabModuleName($tabid) . "_" . $fieldlabel1;
			$fieldvalues = Array();
			if (count($roleids) > 1) {
				$mulsel = "select distinct $fieldname from jo_$fieldname inner join jo_role2picklist on jo_role2picklist.picklistvalueid = jo_$fieldname.picklist_valueid where roleid in (\"" . implode($roleids, "\",\"") . "\") and picklistid in (select picklistid from jo_$fieldname)"; // order by sortid asc - not requried
			} else {
				$mulsel = "select distinct $fieldname from jo_$fieldname inner join jo_role2picklist on jo_role2picklist.picklistvalueid = jo_$fieldname.picklist_valueid where roleid ='" . $roleid . "' and picklistid in (select picklistid from jo_$fieldname)"; // order by sortid asc - not requried
			}
			if ($fieldname != 'firstname')
				$mulselresult = $adb->query($mulsel);
			for ($j = 0; $j < $adb->num_rows($mulselresult); $j++) {
				$fldvalue = $adb->query_result($mulselresult, $j, $fieldname);
				if (in_array($fldvalue, $fieldvalues))
					continue;
				$fieldvalues[] = $fldvalue;
			}
			$field_count = count($fieldvalues);
			if ($uitype == 15 && $field_count > 0 && ($fieldname == 'taskstatus' || $fieldname == 'eventstatus')) {
				$temp_count = count($temp_status[$keyvalue]);
				if ($temp_count > 0) {
					for ($t = 0; $t < $field_count; $t++) {
						$temp_status[$keyvalue][($temp_count + $t)] = $fieldvalues[$t];
					}
					$fieldvalues = $temp_status[$keyvalue];
				} else
					$temp_status[$keyvalue] = $fieldvalues;
			}

			if ($uitype == 33)
				$fieldlists[1][$keyvalue] = $fieldvalues;
			else if ($uitype == 55 && $fieldname == 'salutationtype')
				$fieldlists[$keyvalue] = $fieldvalues;
			else if ($uitype == 15)
				$fieldlists[$keyvalue] = $fieldvalues;
		}
		return $fieldlists;
	}

	function getReportPDF($filterlist = false) {
		require_once 'libraries/tcpdf/tcpdf.php';

		$reportData = $this->GenerateReport("PDF", $filterlist);
		$arr_val = $reportData['data'];

		if (isset($arr_val)) {
			foreach ($arr_val as $wkey => $warray_value) {
				foreach ($warray_value as $whd => $wvalue) {
					if (strlen($wvalue) < strlen($whd)) {
						$w_inner_array[] = strlen($whd);
					} else {
						$w_inner_array[] = strlen($wvalue);
					}
				}
				$warr_val[] = $w_inner_array;
				unset($w_inner_array);
			}

			foreach ($warr_val[0] as $fkey => $fvalue) {
				foreach ($warr_val as $wkey => $wvalue) {
					$f_inner_array[] = $warr_val[$wkey][$fkey];
				}
				sort($f_inner_array, 1);
				$farr_val[] = $f_inner_array;
				unset($f_inner_array);
			}

			foreach ($farr_val as $skkey => $skvalue) {
				if ($skvalue[count($arr_val) - 1] == 1) {
					$col_width[] = ($skvalue[count($arr_val) - 1] * 50);
				} else {
					$col_width[] = ($skvalue[count($arr_val) - 1] * 10) + 10;
				}
			}
			$count = 0;
			foreach ($arr_val[0] as $key => $value) {
				$headerHTML .= '<td width="' . $col_width[$count] . '" bgcolor="#DDDDDD"><b>' . $this->getLstringforReportHeaders($key) . '</b></td>';
				$count = $count + 1;
			}

			foreach ($arr_val as $key => $array_value) {
				$valueHTML = "";
				$count = 0;
				foreach ($array_value as $hd => $value) {
					$valueHTML .= '<td width="' . $col_width[$count] . '">' . $value . '</td>';
					$count = $count + 1;
				}
				$dataHTML .= '<tr>' . $valueHTML . '</tr>';
			}
		}

		$totalpdf = $this->GenerateReport("PRINT_TOTAL", $filterlist);
		$html = '<table border="0.5"><tr>' . $headerHTML . '</tr>' . $dataHTML . '<tr><td>' . $totalpdf . '</td></tr>' . '</table>';
		$columnlength = array_sum($col_width);
		if ($columnlength > 14400) {
			die("<br><br><center>" . $app_strings['LBL_PDF'] . " <a href='javascript:window.history.back()'>" . $app_strings['LBL_GO_BACK'] . ".</a></center>");
		}
		if ($columnlength <= 420) {
			$pdf = new TCPDF('P', 'mm', 'A5', true);
		} elseif ($columnlength >= 421 && $columnlength <= 1120) {
			$pdf = new TCPDF('L', 'mm', 'A3', true);
		} elseif ($columnlength >= 1121 && $columnlength <= 1600) {
			$pdf = new TCPDF('L', 'mm', 'A2', true);
		} elseif ($columnlength >= 1601 && $columnlength <= 2200) {
			$pdf = new TCPDF('L', 'mm', 'A1', true);
		} elseif ($columnlength >= 2201 && $columnlength <= 3370) {
			$pdf = new TCPDF('L', 'mm', 'A0', true);
		} elseif ($columnlength >= 3371 && $columnlength <= 4690) {
			$pdf = new TCPDF('L', 'mm', '2A0', true);
		} elseif ($columnlength >= 4691 && $columnlength <= 6490) {
			$pdf = new TCPDF('L', 'mm', '4A0', true);
		} else {
			$columnhight = count($arr_val) * 15;
			$format = array($columnhight, $columnlength);
			$pdf = new TCPDF('L', 'mm', $format, true);
		}
		$pdf->SetMargins(10, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setLanguageArray($l);
		$pdf->AddPage();

		$pdf->SetFillColor(224, 235, 255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('FreeSerif', 'B', 14);
		$pdf->Cell(($pdf->columnlength * 50), 10, getTranslatedString($oReport->reportname), 0, 0, 'C', 0);
		//$pdf->writeHTML($oReport->reportname);
		$pdf->Ln();

		$pdf->SetFont('FreeSerif', '', 10);

		$pdf->writeHTML($html);

		return $pdf;
	}

	function writeReportToExcelFile($fileName, $filterlist = '') {
		
		global $currentModule, $current_language;
		$mod_strings = return_module_language($current_language, $currentModule);

		

		$workbook = new Spreadsheet();
		$worksheet = $workbook->getActiveSheet();

		$reportData = $this->GenerateReport("PDF", $filterlist, false, false, false, 'ExcelExport');
		$arr_val = $reportData['data'];
		$totalxls = $this->GenerateReport("XLS", $filterlist, false, false, false, 'ExcelExport');
		$numericTypes = array('currency', 'double', 'integer', 'percentage');

		$header_styles = array(
			'fill' => array('type' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => array('rgb' => 'E1E0F7')),
				//'font' => array( 'bold' => true )
		);

		if (isset($arr_val)) {
			$count = 0;
			$rowcount = 1;
			//copy the first value details
			$arrayFirstRowValues = $arr_val[0];
			foreach ($arrayFirstRowValues as $key => $value) {
				// It'll not translate properly if you don't mention module of that string
				if ($key == 'ACTION' || $key == vtranslate('LBL_ACTION', $this->primarymodule) || $key == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL_ACTION', $this->primarymodule) || $key == vtranslate('LBL ACTION', $this->primarymodule) || $key == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL ACTION', $this->primarymodule)) {
					continue;
				}
				$worksheet->setCellValueByColumnAndRow($count, $rowcount, decode_html($key), true);
				//$worksheet->getStyle($count.':'.$rowcount)->applyFromArray($header_styles);

				// NOTE Performance overhead: http://stackoverflow.com/questions/9965476/phpexcel-column-size-issues
				$worksheet->getColumnDimension($count)->setAutoSize(true);

				$count = $count + 1;
			}

			$rowcount++;
			foreach ($arr_val as $key => $array_value) {
				$count = 0;
				foreach ($array_value as $hdr => $valueDataType) {
					if (is_array($valueDataType)) {
						$value = $valueDataType['value'];
						$dataType = $valueDataType['type'];
					} else {
						$value = $valueDataType;
						$dataType = '';
					}
					// It'll not translate properly if you don't mention module of that string
					if ($hdr == 'ACTION' || $hdr == vtranslate('LBL_ACTION', $this->primarymodule) || $hdr == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL_ACTION', $this->primarymodule) || $hdr == vtranslate('LBL ACTION', $this->primarymodule) || $hdr == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL ACTION', $this->primarymodule))
						continue;
					$value = decode_html($value);
					if (in_array($dataType, $numericTypes)) {
						$worksheet->setCellValueByColumnAndRow($count, $rowcount, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
					} else {
						$worksheet->setCellValueByColumnAndRow($count, $rowcount, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
					}
					$count = $count + 1;
				}
				$rowcount++;
			}

			// Summary Total
			$rowcount++;
			$count = 0;
			if (is_array($totalxls[0])) {
				foreach ($totalxls[0] as $key => $value) {
					$exploedKey = explode('_', $key);
					$chdr = end($exploedKey);
					$translated_str = in_array($chdr, array_keys($mod_strings)) ? $mod_strings[$chdr] : $chdr;
					$worksheet->setCellValueByColumnAndRow($count, $rowcount, $translated_str);

					$worksheet->getStyle($count.':'.$rowcount)->applyFromArray($header_styles);

					$count = $count + 1;
				}
			}

			$ignoreValues = array('sumcount','avgcount','mincount','maxcount');
			$rowcount++;
			foreach ($totalxls as $key => $array_value) {
				$count = 0;
				foreach ($array_value as $hdr => $value) {
					if (in_array($hdr, $ignoreValues)) {
						continue;
					}
					$value = decode_html($value);
					$excelDatatype = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING;
					if (is_numeric($value)) {
						$excelDatatype = \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC;
					}
					$worksheet->setCellValueByColumnAndRow($count, $key + $rowcount, $value, $excelDatatype);
					$count = $count + 1;
				}
			}
		}
		//Reference Article:  http://phpexcel.codeplex.com/discussions/389578
		ob_clean();
		$workbookWriter = new Xlsx($workbook);
		$workbookWriter->save($fileName);
	}

	function writeReportToCSVFile($fileName, $filterlist = '') {

		global $currentModule, $current_language;
		$mod_strings = return_module_language($current_language, $currentModule);

		$reportData = $this->GenerateReport("PDF", $filterlist);
		$arr_val = $reportData['data'];

		$fp = fopen($fileName, 'w+');

		if (isset($arr_val)) {
			$csv_values = array();
			// Header
			$csv_values = array_map('decode_html', array_keys($arr_val[0]));
			$unsetValue = false;
			// It'll not translate properly if you don't mention module of that string
			if (end($csv_values) == vtranslate('LBL_ACTION', $this->primarymodule) || end($csv_values) == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL_ACTION', $this->primarymodule) || end($csv_values) == vtranslate('LBL ACTION', $this->primarymodule) || end($csv_values) == vtranslate($this->primarymodule, $this->primarymodule) . " " . vtranslate('LBL ACTION', $this->primarymodule)) {
				unset($csv_values[count($csv_values) - 1]); //removed action header in csv file
				$unsetValue = true;
			}
			fputcsv($fp, $csv_values);
			foreach ($arr_val as $key => $array_value) {
				if ($unsetValue) {
					array_pop($array_value); //removed action link
				}
				$csv_values = array_map('decode_html', array_values($array_value));
				fputcsv($fp, $csv_values);
			}
		}
		fclose($fp);
	}

	function getGroupByTimeList($reportId) {
		global $adb;
        // Have we initialized information already?
		if ($this->_groupbycondition !== false) {
			return $this->_groupbycondition;
		}
        
		$groupByTimeQuery = "SELECT * FROM jo_reportgroupbycolumn WHERE reportid=?";
		$groupByTimeRes = $adb->pquery($groupByTimeQuery, array($reportId));
		$num_rows = $adb->num_rows($groupByTimeRes);
		for ($i = 0; $i < $num_rows; $i++) {
			$sortColName = $adb->query_result($groupByTimeRes, $i, 'sortcolname');
			list($tablename, $colname, $module_field, $fieldname, $single) = split(':', $sortColName);
			$groupField = $module_field;
			$groupCriteria = $adb->query_result($groupByTimeRes, $i, 'dategroupbycriteria');
			if (in_array($groupCriteria, array_keys($this->groupByTimeParent))) {
				$parentCriteria = $this->groupByTimeParent[$groupCriteria];
				foreach ($parentCriteria as $criteria) {
					$groupByCondition[] = $this->GetTimeCriteriaCondition($criteria, $groupField);
				}
			}
			$groupByCondition[] = $this->GetTimeCriteriaCondition($groupCriteria, $groupField);
			$this->queryPlanner->addTable($tablename);
		}
        $this->_groupbycondition = $groupByCondition;
		return $groupByCondition;
	}

	function GetTimeCriteriaCondition($criteria, $dateField) {
		$condition = "";
		if (strtolower($criteria) == 'year') {
			$condition = "DATE_FORMAT($dateField, '%Y' )";
		} else if (strtolower($criteria) == 'month') {
			$condition = "CEIL(DATE_FORMAT($dateField,'%m')%13)";
		} else if (strtolower($criteria) == 'quarter') {
			$condition = "CEIL(DATE_FORMAT($dateField,'%m')/3)";
		}
		return $condition;
	}

	function GetFirstSortByField($reportid) {
		global $adb;
		$groupByField = "";
		$sortFieldQuery = "SELECT * FROM jo_reportsortcol
                            LEFT JOIN jo_reportgroupbycolumn ON (jo_reportsortcol.sortcolid = jo_reportgroupbycolumn.sortid and jo_reportsortcol.reportid = jo_reportgroupbycolumn.reportid)
                            WHERE columnname!='none' and jo_reportsortcol.reportid=? ORDER By sortcolid";
		$sortFieldResult = $adb->pquery($sortFieldQuery, array($reportid));
		$inventoryModules = getInventoryModules();
		if ($adb->num_rows($sortFieldResult) > 0) {
			$fieldcolname = $adb->query_result($sortFieldResult, 0, 'columnname');
			list($tablename, $colname, $module_field, $fieldname, $typeOfData) = explode(":", $fieldcolname);
			list($modulename, $fieldlabel) = explode('_', $module_field, 2);
			$groupByField = $module_field;
			if ($typeOfData == "D") {
				$groupCriteria = $adb->query_result($sortFieldResult, 0, 'dategroupbycriteria');
				if (strtolower($groupCriteria) != 'none') {
					if (in_array($groupCriteria, array_keys($this->groupByTimeParent))) {
						$parentCriteria = $this->groupByTimeParent[$groupCriteria];
						foreach ($parentCriteria as $criteria) {
							$groupByCondition[] = $this->GetTimeCriteriaCondition($criteria, $groupByField);
						}
					}
					$groupByCondition[] = $this->GetTimeCriteriaCondition($groupCriteria, $groupByField);
					$groupByField = implode(", ", $groupByCondition);
				}
			} elseif (CheckFieldPermission($fieldname, $modulename) != 'true') {
				if (!(in_array($modulename, $inventoryModules) && $fieldname == 'serviceid')) {
					$groupByField = $tablename . "." . $colname;
				}
			}
		}
		return $groupByField;
	}

	function getReferenceFieldColumnList($moduleName, $fieldInfo) {
		$adb = PearDatabase::getInstance();

		$columnsSqlList = array();

		$fieldInstance = WebserviceField::fromArray($adb, $fieldInfo);
		$referenceModuleList = $fieldInstance->getReferenceList(false);
		if(in_array('Calendar', $referenceModuleList) && in_array('Events', $referenceModuleList)) {
			$eventKey = array_keys($referenceModuleList, 'Events');
			unset($referenceModuleList[$eventKey[0]]);
		}
		
		$reportSecondaryModules = explode(':', $this->secondarymodule);

		if ($moduleName != $this->primarymodule && in_array($this->primarymodule, $referenceModuleList)) {
			$entityTableFieldNames = getEntityFieldNames($this->primarymodule);
			$entityTableName = $entityTableFieldNames['tablename'];
			$entityFieldNames = $entityTableFieldNames['fieldname'];

			$columnList = array();
			if (is_array($entityFieldNames)) {
				foreach ($entityFieldNames as $entityColumnName) {
					$columnList["$entityColumnName"] = "$entityTableName.$entityColumnName";
				}
			} else {
				$columnList[] = "$entityTableName.$entityFieldNames";
			}
			if (count($columnList) > 1) {
				$columnSql = getSqlForNameInDisplayFormat($columnList, $this->primarymodule);
			} else {
				$columnSql = implode('', $columnList);
			}
			$columnsSqlList[] = $columnSql;
		} else {
			foreach ($referenceModuleList as $referenceModule) {
				$entityTableFieldNames = getEntityFieldNames($referenceModule);
				$entityTableName = $entityTableFieldNames['tablename'];
				$entityFieldNames = $entityTableFieldNames['fieldname'];
				$fieldName = $fieldInstance->getFieldName();

				$referenceTableName = '';
				$dependentTableName = '';
				if ($moduleName == 'Calendar' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsCalendar';
				} elseif ($moduleName == 'Calendar' && $fieldName == "parent_id") {
					$referenceTableName = $entityTableName . 'RelCalendar';
				} elseif ($moduleName == 'HelpDesk' && $referenceModule == 'Accounts' && $fieldName == "parent_id") {
					$referenceTableName = 'jo_accountRelHelpDesk';
				} elseif ($moduleName == 'HelpDesk' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsRelHelpDesk';
				} elseif ($moduleName == 'HelpDesk' && $referenceModule == 'Products' && $fieldName == "product_id") {
					$referenceTableName = 'jo_productsRel';
				} elseif ($moduleName == 'Contacts' && $referenceModule == 'Accounts' && $fieldName == "account_id") {
					$referenceTableName = 'jo_accountContacts';
				} elseif ($moduleName == 'Contacts' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsContacts';
				} elseif ($moduleName == 'Accounts' && $referenceModule == 'Accounts' && $fieldName == "account_id") {
					$referenceTableName = 'jo_accountAccounts';
				} elseif ($moduleName == 'Campaigns' && $referenceModule == 'Products' && $fieldName == "product_id") {
					$referenceTableName = 'jo_productsCampaigns';
				} elseif ($moduleName == 'Faq' && $referenceModule == 'Products' && $fieldName == "product_id") {
					$referenceTableName = 'jo_productsFaq';
				} elseif ($moduleName == 'Invoice' && $referenceModule == 'SalesOrder' && $fieldName == "salesorder_id") {
					$referenceTableName = 'jo_salesorderInvoice';
				} elseif ($moduleName == 'Invoice' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsInvoice';
				} elseif ($moduleName == 'Invoice' && $referenceModule == 'Accounts' && $fieldName == "account_id") {
					$referenceTableName = 'jo_accountInvoice';
				} elseif ($moduleName == 'Potentials' && $referenceModule == 'Campaigns' && $fieldName == "campaignid") {
					$referenceTableName = 'jo_campaignPotentials';
				} elseif ($moduleName == 'Products' && $referenceModule == 'Vendors' && $fieldName == "vendor_id") {
					$referenceTableName = 'jo_vendorRelProducts';
				} elseif ($moduleName == 'PurchaseOrder' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsPurchaseOrder';
				} elseif ($moduleName == 'PurchaseOrder' && $referenceModule == 'Accounts' && $fieldName == "accountid") {
					$referenceTableName = 'jo_accountsPurchaseOrder';
				} elseif ($moduleName == 'PurchaseOrder' && $referenceModule == 'Vendors' && $fieldName == "vendor_id") {
					$referenceTableName = 'jo_vendorRelPurchaseOrder';
				} elseif ($moduleName == 'Subscription' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsSubscription';
				} elseif ($moduleName == 'Subscription' && $referenceModule == 'Accounts' && $fieldName == "account_id") {
					$referenceTableName = 'jo_accountsSubscription';
				} elseif ($moduleName == 'Subscription' && $referenceModule == 'Potentials' && $fieldName == "potential_id") {
					$referenceTableName = 'jo_potentialSubscription';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Potentials' && $fieldName == "potential_id") {
					$referenceTableName = 'jo_potentialRelQuotes';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Accounts' && $fieldName == "account_id") {
					$referenceTableName = 'jo_accountQuotes';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsQuotes';
				} elseif ($moduleName == 'Quotes' && $referenceModule == 'Leads' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_leaddetailsQuotes';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Potentials' && $fieldName == "potential_id") {
					$referenceTableName = 'jo_potentialRelSalesOrder';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Accounts' && $fieldName == "account_id") {
					$referenceTableName = 'jo_accountSalesOrder';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsSalesOrder';
				} elseif ($moduleName == 'SalesOrder' && $referenceModule == 'Quotes' && $fieldName == "quote_id") {
					$referenceTableName = 'jo_quotesSalesOrder';
				} elseif ($moduleName == 'Potentials' && $referenceModule == 'Contacts' && $fieldName == "contact_id") {
					$referenceTableName = 'jo_contactdetailsPotentials';
				} elseif ($moduleName == 'Potentials' && $referenceModule == 'Accounts' && $fieldName == "related_to") {
					$referenceTableName = 'jo_accountPotentials';
				} elseif ($moduleName == 'ModComments' && $referenceModule == 'Users') {
					$referenceTableName = 'jo_usersModComments';
				} elseif (in_array($referenceModule, $reportSecondaryModules) && $fieldInstance->getUIType() != 10) {
					$referenceTableName = "{$entityTableName}Rel$referenceModule";
					$dependentTableName = "jo_crmentityRel{$referenceModule}{$fieldInstance->getFieldId()}";
				} elseif (in_array($moduleName, $reportSecondaryModules) && $fieldInstance->getUIType() != 10) {
					$referenceTableName = "{$entityTableName}Rel$moduleName";
					$dependentTableName = "jo_crmentityRel{$moduleName}{$fieldInstance->getFieldId()}";
				} else {
					$referenceTableName = "{$entityTableName}Rel{$moduleName}{$fieldInstance->getFieldId()}";
					$dependentTableName = "jo_crmentityRel{$moduleName}{$fieldInstance->getFieldId()}";
				}
				$this->queryPlanner->addTable($referenceTableName);

				if (isset($dependentTableName)) {
					$this->queryPlanner->addTable($dependentTableName);
				}
				$columnList = array();
				if (is_array($entityFieldNames)) {
					foreach ($entityFieldNames as $entityColumnName) {
						$columnList["$entityColumnName"] = "$referenceTableName.$entityColumnName";
					}
				} else {
					$columnList[] = "$referenceTableName.$entityFieldNames";
				}
				if (count($columnList) > 1) {
					$columnSql = getSqlForNameInDisplayFormat($columnList, $referenceModule);
				} else {
					$columnSql = implode('', $columnList);
				}
				if ($referenceModule == 'DocumentFolders' && $fieldInstance->getFieldName() == 'folderid') {
					$columnSql = 'jo_attachmentsfolder.foldername';
					$this->queryPlanner->addTable("jo_attachmentsfolder");
				}
				if ($referenceModule == 'Currency' && $fieldInstance->getFieldName() == 'currency_id') {
					$columnSql = "jo_currency_info$moduleName.currency_name";
					$this->queryPlanner->addTable("jo_currency_info$moduleName");
				}
				$columnsSqlList[] = "trim($columnSql)";
			}
		}
		return $columnsSqlList;
	}

}

?>
