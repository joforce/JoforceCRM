
<?php
/*********************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *
 ********************************************************************************/

class PriceBooks extends CRMEntity
{
	var $log;
	var $db;
	var $table_name = "jo_pricebook";
	var $table_index = 'pricebookid';
	var $tab_name = array('jo_crmentity', 'jo_pricebook', 'jo_pricebookcf');
	var $tab_name_index = array('jo_crmentity' => 'crmid', 'jo_pricebook' => 'pricebookid', 'jo_pricebookcf' => 'pricebookid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = array('jo_pricebookcf', 'pricebookid');
	var $column_fields = array();

	var $sortby_fields = array('bookname');

	// This is the list of fields that are in the lists.
	var $list_fields = array(
		'Price Book Name' => array('pricebook' => 'bookname'),
		'Active' => array('pricebook' => 'active')
	);

	var $list_fields_name = array(
		'Price Book Name' => 'bookname',
		'Active' => 'active'
	);
	var $list_link_field = 'bookname';

	var $search_fields = array(
		'Price Book Name' => array('pricebook' => 'bookname')
	);
	var $search_fields_name = array(
		'Price Book Name' => 'bookname'
	);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'bookname';
	var $default_sort_order = 'ASC';

	var $mandatory_fields = array('bookname', 'currency_id', 'pricebook_no', 'createdtime', 'modifiedtime');

	// For Alphabetical search
	var $def_basicsearch_col = 'bookname';

	/**	Constructor which will set the column_fields in this object
	 */
	function PriceBooks()
	{
		$this->log = LoggerManager::getLogger('pricebook');
		$this->log->debug("Entering PriceBooks() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('PriceBooks');
		$this->log->debug("Exiting PriceBook method ...");
	}

	function save_module($module)
	{
		// Update the list prices in the price book with the unit price, if the Currency has been changed
		$this->updateListPrices();
	}

	/* Function to Update the List prices for all the products of a current price book
	   with its Unit price, if the Currency for Price book has changed. */
	function updateListPrices()
	{
		global $log, $adb;
		$log->debug("Entering function updateListPrices...");
		$pricebook_currency = $this->column_fields['currency_id'];
		$prod_res = $adb->pquery(
			"select * from jo_pricebookproductrel where pricebookid=? AND usedcurrency != ?",
			array($this->id, $pricebook_currency)
		);
		$numRows = $adb->num_rows($prod_res);

		for ($i = 0; $i < $numRows; $i++) {
			$product_id = $adb->query_result($prod_res, $i, 'productid');
			$list_price = $adb->query_result($prod_res, $i, 'listprice');
			$used_currency = $adb->query_result($prod_res, $i, 'usedcurrency');
			$product_currency_info = getCurrencySymbolandCRate($used_currency);
			$product_conv_rate = $product_currency_info['rate'];
			$pricebook_currency_info = getCurrencySymbolandCRate($pricebook_currency);
			$pb_conv_rate = $pricebook_currency_info['rate'];
			$conversion_rate = $pb_conv_rate / $product_conv_rate;
			$computed_list_price = $list_price * $conversion_rate;

			$query = "update jo_pricebookproductrel set listprice=?, usedcurrency=? where pricebookid=? and productid=?";
			$params = array($computed_list_price, $pricebook_currency, $this->id, $product_id);
			$adb->pquery($query, $params);
		}
		$log->debug("Exiting function updateListPrices...");
	}

	/**	function used to get the products which are related to the pricebook
	 *	@param int $id - pricebook id
	 *      @return array - return an array which will be returned from the function getPriceBookRelatedProducts
	 **/
	function get_pricebook_products($id, $cur_tab_id, $rel_tab_id, $actions = false)
	{
		global $log, $singlepane_view, $currentModule, $current_user;
		$log->debug("Entering get_pricebook_products(" . $id . ") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if ($singlepane_view == 'true')
			$returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
		else
			$returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

		$button = '';

		if ($actions) {
			if (is_string($actions)) $actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='submit' name='button' onclick=\"this.form.action.value='AddProductsToPriceBook';this.form.module.value='$related_module';this.form.return_module.value='$currentModule';this.form.return_action.value='PriceBookDetailView'\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
			}
		}

		$query = 'SELECT jo_products.productid, jo_products.productname, jo_products.productcode, jo_products.commissionrate,
						jo_products.qty_per_unit, jo_products.unit_price, jo_crmentity.crmid, jo_crmentity.smownerid,
						jo_pricebookproductrel.listprice
				FROM jo_products
				INNER JOIN jo_pricebookproductrel ON jo_products.productid = jo_pricebookproductrel.productid
				INNER JOIN jo_crmentity on jo_crmentity.crmid = jo_products.productid
				INNER JOIN jo_pricebook on jo_pricebook.pricebookid = jo_pricebookproductrel.pricebookid
				LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid '
			. getNonAdminAccessControlQuery($related_module, $current_user) . '
				WHERE jo_pricebook.pricebookid = ' . $id . ' and jo_crmentity.deleted = 0';

		$this->retrieve_entity_info($id, $this_module);
		$return_value = getPriceBookRelatedProducts($query, $this, $returnset);

		if ($return_value == null) $return_value = array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_pricebook_products method ...");
		return $return_value;
	}

	/**	function used to get the services which are related to the pricebook
	 *	@param int $id - pricebook id
	 *      @return array - return an array which will be returned from the function getPriceBookRelatedServices
	 **/
	function get_pricebook_services($id, $cur_tab_id, $rel_tab_id, $actions = false)
	{
		global $log, $singlepane_view, $currentModule, $current_user;
		$log->debug("Entering get_pricebook_services(" . $id . ") method ...");
		$this_module = $currentModule;

		$related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if ($singlepane_view == 'true')
			$returnset = '&return_module=' . $this_module . '&return_action=DetailView&return_id=' . $id;
		else
			$returnset = '&return_module=' . $this_module . '&return_action=CallRelatedList&return_id=' . $id;

		$button = '';

		if ($actions) {
			if (is_string($actions)) $actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='submit' name='button' onclick=\"this.form.action.value='AddServicesToPriceBook';this.form.module.value='$related_module';this.form.return_module.value='$currentModule';this.form.return_action.value='PriceBookDetailView'\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
			}
		}

		$query = 'SELECT jo_service.serviceid, jo_service.servicename, jo_service.commissionrate,
					jo_service.qty_per_unit, jo_service.unit_price, jo_crmentity.crmid, jo_crmentity.smownerid,
					jo_pricebookproductrel.listprice
			FROM jo_service
			INNER JOIN jo_pricebookproductrel on jo_service.serviceid = jo_pricebookproductrel.productid
			INNER JOIN jo_crmentity on jo_crmentity.crmid = jo_service.serviceid
			INNER JOIN jo_pricebook on jo_pricebook.pricebookid = jo_pricebookproductrel.pricebookid
			LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
			LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid '
			. getNonAdminAccessControlQuery($related_module, $current_user) . '
			WHERE jo_pricebook.pricebookid = ' . $id . ' and jo_crmentity.deleted = 0';

		$this->retrieve_entity_info($id, $this_module);
		$return_value = $other->getPriceBookRelatedServices($query, $this, $returnset);

		if ($return_value == null) $return_value = array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_pricebook_services method ...");
		return $return_value;
	}

	/**	function used to get whether the pricebook has related with a product or not
	 *	@param int $id - product id
	 *	@return true or false - if there are no pricebooks available or associated pricebooks for the product is equal to total number of pricebooks then return false, else return true
	 */
	function get_pricebook_noproduct($id)
	{
		global $log;
		$log->debug("Entering get_pricebook_noproduct(" . $id . ") method ...");

		$query = "select jo_crmentity.crmid, jo_pricebook.* from jo_pricebook inner join jo_crmentity on jo_crmentity.crmid=jo_pricebook.pricebookid where jo_crmentity.deleted=0";
		$result = $this->db->pquery($query, array());
		$no_count = $this->db->num_rows($result);
		if ($no_count != 0) {
			$pb_query = 'select jo_crmentity.crmid, jo_pricebook.pricebookid,jo_pricebookproductrel.productid from jo_pricebook inner join jo_crmentity on jo_crmentity.crmid=jo_pricebook.pricebookid inner join jo_pricebookproductrel on jo_pricebookproductrel.pricebookid=jo_pricebook.pricebookid where jo_crmentity.deleted=0 and jo_pricebookproductrel.productid=?';
			$result_pb = $this->db->pquery($pb_query, array($id));
			if ($no_count == $this->db->num_rows($result_pb)) {
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return false;
			} elseif ($this->db->num_rows($result_pb) == 0) {
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			} elseif ($this->db->num_rows($result_pb) < $no_count) {
				$log->debug("Exiting get_pricebook_noproduct method ...");
				return true;
			}
		} else {
			$log->debug("Exiting get_pricebook_noproduct method ...");
			return false;
		}
	}

	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module, $queryplanner)
	{
		$moduletable = $this->table_name;
		$moduleindex = $this->table_index;
		$modulecftable = $this->customFieldTable[0];
		$modulecfindex = $this->customFieldTable[1];

		$cfquery = '';
		if (isset($modulecftable) && $queryplanner->requireTable($modulecftable)) {
			$cfquery = "inner join $modulecftable as $modulecftable on $modulecftable.$modulecfindex=$moduletable.$moduleindex";
		}

		$query = "from $moduletable $cfquery
					inner join jo_crmentity on jo_crmentity.crmid=$moduletable.$moduleindex";
		if ($queryplanner->requireTable("jo_currency_info$module")) {
			$query .= "  left join jo_currency_info as jo_currency_info$module on jo_currency_info$module.id = $moduletable.currency_id";
		}
		if ($queryplanner->requireTable("jo_groups$module")) {
			$query .= " left join jo_groups as jo_groups$module on jo_groups$module.groupid = jo_crmentity.smownerid";
		}
		if ($queryplanner->requireTable("jo_users$module")) {
			$query .= " left join jo_users as jo_users$module on jo_users$module.id = jo_crmentity.smownerid";
		}
		$query .= " left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid";
		$query .= " left join jo_users on jo_users.id = jo_crmentity.smownerid";

		if ($queryplanner->requireTable("jo_lastModifiedByPriceBooks")) {
			$query .= " left join jo_users as jo_lastModifiedByPriceBooks on jo_lastModifiedByPriceBooks.id = jo_crmentity.modifiedby ";
		}
		if ($queryplanner->requireTable('jo_createdby' . $module)) {
			$query .= " left join jo_users as jo_createdby" . $module . " on jo_createdby" . $module . ".id = jo_crmentity.smcreatorid";
		}
		return $query;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module, $secmodule, $queryplanner)
	{

		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("jo_crmentityPriceBooks", array("jo_usersPriceBooks", "jo_groupsPriceBooks"));
		if (!$queryplanner->requireTable('jo_pricebook', $matrix)) {
			return '';
		}
		$matrix->setDependency("jo_pricebook", array("jo_crmentityPriceBooks", "jo_currency_infoPriceBooks"));

		$query = $this->getRelationQuery($module, $secmodule, "jo_pricebook", "pricebookid", $queryplanner);
		// TODO Support query planner
		if ($queryplanner->requireTable("jo_crmentityPriceBooks", $matrix)) {
			$query .= " left join jo_crmentity as jo_crmentityPriceBooks on jo_crmentityPriceBooks.crmid=jo_pricebook.pricebookid and jo_crmentityPriceBooks.deleted=0";
		}
		if ($queryplanner->requireTable("jo_currency_infoPriceBooks")) {
			$query .= " left join jo_currency_info as jo_currency_infoPriceBooks on jo_currency_infoPriceBooks.id = jo_pricebook.currency_id";
		}
		if ($queryplanner->requireTable("jo_usersPriceBooks")) {
			$query .= " left join jo_users as jo_usersPriceBooks on jo_usersPriceBooks.id = jo_crmentityPriceBooks.smownerid";
		}
		if ($queryplanner->requireTable("jo_groupsPriceBooks")) {
			$query .= " left join jo_groups as jo_groupsPriceBooks on jo_groupsPriceBooks.groupid = jo_crmentityPriceBooks.smownerid";
		}
		if ($queryplanner->requireTable("jo_lastModifiedByPriceBooks")) {
			$query .= " left join jo_users as jo_lastModifiedByPriceBooks on jo_lastModifiedByPriceBooks.id = jo_crmentityPriceBooks.smownerid";
		}
		if ($queryplanner->requireTable("jo_createdbyPriceBooks")) {
			$query .= " left join jo_users as jo_createdbyPriceBooks on jo_createdbyPriceBooks.id = jo_crmentityPriceBooks.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule)
	{
		$rel_tables = array(
			"Products" => array("jo_pricebookproductrel" => array("pricebookid", "productid"), "jo_pricebook" => "pricebookid"),
			"Services" => array("jo_pricebookproductrel" => array("pricebookid", "productid"), "jo_pricebook" => "pricebookid"),
		);
		return $rel_tables[$secmodule];
	}

	function createRecords($obj)
	{
		global $adb;
		$moduleName = $obj->module;
		$moduleHandler = vtws_getModuleHandlerFromName($moduleName, $obj->user);
		$moduleMeta = $moduleHandler->getMeta();
		$moduleObjectId = $moduleMeta->getEntityId();
		$moduleFields = $moduleMeta->getModuleFields();
		$focus = CRMEntity::getInstance($moduleName);
		$moduleSubject = 'bookname';

		$tableName = Import_Utils_Helper::getDbTableName($obj->user);
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE status = ' . Import_Data_Action::$IMPORT_RECORD_NONE . ' GROUP BY ' . $moduleSubject;

		if ($obj->batchImport) {
			$importBatchLimit = getImportBatchLimit();
			$sql .= ' LIMIT ' . $importBatchLimit;
		}
		$result = $adb->query($sql);
		$numberOfRecords = $adb->num_rows($result);

		if ($numberOfRecords <= 0) {
			return;
		}
		$bookNameList = array();
		$fieldMapping = $obj->fieldMapping;
		$fieldColumnMapping = $moduleMeta->getFieldColumnMapping();
		for ($i = 0; $i < $numberOfRecords; ++$i) {
			$row = $adb->raw_query_result_rowdata($result, $i);
			$rowId = $row['id'];
			$subject = $row['bookname'];
			$entityInfo = null;
			$fieldData = array();
			$subject = str_replace("\\", "\\\\", $subject);
			$subject = str_replace('"', '""', $subject);
			$sql = 'SELECT * FROM ' . $tableName . ' WHERE status = ' . Import_Data_Action::$IMPORT_RECORD_NONE . ' AND ' . $moduleSubject . ' = "' . $subject . '"';
			$subjectResult = $adb->query($sql);
			$count = $adb->num_rows($subjectResult);
			$subjectRowIDs = $fieldArray = $productList = array();
			for ($j = 0; $j < $count; ++$j) {
				$subjectRow = $adb->raw_query_result_rowdata($subjectResult, $j);
				array_push($subjectRowIDs, $subjectRow['id']);
				$productList[$j]['relatedto'] = $subjectRow['relatedto'];
				$productList[$j]['listprice'] = $subjectRow['listprice'];
			}
			foreach ($fieldMapping as $fieldName => $index) {
				$fieldData[$fieldName] = $row[strtolower($fieldName)];
			}

			$entityInfo = $this->importRecord($obj, $fieldData, $productList);
			unset($productList);
			if ($entityInfo == null) {
				$entityInfo = array('id' => null, 'status' => Import_Data_Action::$IMPORT_RECORD_FAILED);
			} else if (!$entityInfo['status']) {
				$entityInfo['status'] = Import_Data_Action::$IMPORT_RECORD_CREATED;
			}

			$entityIdComponents = vtws_getIdComponents($entityInfo['id']);
			$recordId = $entityIdComponents[1];
			if (!empty($recordId)) {
				$entityfields = getEntityFieldNames($moduleName);
				$label = '';
				if (is_array($entityfields['fieldname'])) {
					foreach ($entityfields['fieldname'] as $field) {
						$label .= $fieldData[$field] . " ";
					}
				} else {
					$label = $fieldData[$entityfields['fieldname']];
				}

				$adb->pquery('UPDATE jo_crmentity SET label=? WHERE crmid=?', array(trim($label), $recordId));
				//updating solr while import records
				$recordModel = Head_Record_Model::getCleanInstance($moduleName);
				$focus = $recordModel->getEntity();
				$focus->id = $recordId;
				$focus->column_fields = $fieldData;
				$this->entityData[] = EntityData::fromCRMEntity($focus);
			}

			$label = trim($label);
			$adb->pquery('UPDATE jo_crmentity SET label=? WHERE crmid=?', array($label, $recordId));
			//Creating entity data of updated records for post save events
			if ($entityInfo['status'] !== Import_Data_Action::$IMPORT_RECORD_CREATED) {
				$recordModel = Head_Record_Model::getCleanInstance($moduleName);
				$focus = $recordModel->getEntity();
				$focus->id = $recordId;
				$focus->column_fields = $entityInfo;
				$this->entitydata[] = EntityData::fromCRMEntity($focus);
			}

			foreach ($subjectRowIDs as $id) {
				$obj->importedRecordInfo[$id] = $entityInfo;
				$obj->updateImportStatus($id, $entityInfo);
			}
		}

		$obj->entitydata = null;
		$result = null;
		return true;
	}

	function importRecord($obj, $fieldData, $productList)
	{
		$moduleName = 'PriceBooks';
		$moduleHandler = vtws_getModuleHandlerFromName($moduleName, $obj->user);
		$moduleMeta = $moduleHandler->getMeta();
		unset($fieldData['listprice']);
		unset($fieldData['relatedto']);
		$fieldData = $obj->transformForImport($fieldData, $moduleMeta);
		try {
			$entityInfo = vtws_create($moduleName, $fieldData, $obj->user);
			if ($entityInfo && $productList) {
				$this->relatePriceBookWithProduct($entityInfo, $productList);
			}
		} catch (Exception $e) {
		}
		$entityInfo['status'] = $obj->getImportRecordStatus('created');
		return $entityInfo;
	}

	function relatePriceBookWithProduct($entityinfo, $productList)
	{
		if (count($productList) > 0) {
			foreach ($productList as $product) {
				if (!$product['relatedto'])
					continue;
				$productName = $product['relatedto'];
				$productName = explode('::::', $productName);
				$productId = getEntityId($productName[0], $productName[1]);
				$presence = isRecordExists($productId);
				if ($presence) {
					$productInstance = Head_Record_Model::getInstanceById($productId);
					$pricebookId = vtws_getIdComponents($entityinfo['id']);
					if ($productInstance) {
						$recordModel = Head_Record_Model::getInstanceById($pricebookId[1]);
						$recordModel->updateListPrice($productId, $product['listprice']);
					}
				}
			}
		}
	}

	function getGroupQuery($tableName)
	{
		return 'SELECT status FROM ' . $tableName . ' GROUP BY bookname';
	}
}
?>
