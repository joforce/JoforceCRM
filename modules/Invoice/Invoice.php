<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class Invoice extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "jo_invoice";
	var $table_index= 'invoiceid';
	var $tab_name = Array('jo_crmentity','jo_invoice','jo_invoicebillads','jo_invoiceshipads','jo_invoicecf', 'jo_inventoryproductrel');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_invoice'=>'invoiceid','jo_invoicebillads'=>'invoicebilladdressid','jo_invoiceshipads'=>'invoiceshipaddressid','jo_invoicecf'=>'invoiceid','jo_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_invoicecf', 'invoiceid');

	var $column_fields = Array();

	var $update_product_array = Array();

	var $sortby_fields = Array('subject','invoice_no','invoicestatus','smownerid','accountname','lastname');

	// This is used to retrieve related jo_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
				//'Invoice No'=>Array('crmentity'=>'crmid'),
				'Invoice No'=>Array('invoice'=>'invoice_no'),
				'Subject'=>Array('invoice'=>'subject'),
				'Sales Order'=>Array('invoice'=>'salesorderid'),
				'Status'=>Array('invoice'=>'invoicestatus'),
				'Total'=>Array('invoice'=>'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
						'Invoice No'=>'invoice_no',
						'Subject'=>'subject',
						'Sales Order'=>'salesorder_id',
						'Status'=>'invoicestatus',
						'Total'=>'hdnGrandTotal',
						'Assigned To'=>'assigned_user_id'
					  );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				//'Invoice No'=>Array('crmentity'=>'crmid'),
				'Invoice No'=>Array('invoice'=>'invoice_no'),
				'Subject'=>Array('purchaseorder'=>'subject'),
				'Account Name'=>Array('contactdetails'=>'account_id'),
				'Created Date' => Array('crmentity'=>'createdtime'),
				'Assigned To'=>Array('crmentity'=>'smownerid'),
				);

	var $search_fields_name = Array(
						'Invoice No'          => 'invoice_no',
						'Subject'             => 'subject',
						'Account Name'        => 'account_id',
						'Created Time'        => 'createdtime',
						'Assigned To'         => 'assigned_user_id'
					  );

	// This is the list of jo_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';

	//var $groupTable = Array('jo_invoicegrouprelation','invoiceid');

	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime', 'assigned_user_id', 'quantity', 'listprice', 'productid');
	var $_salesorderid;
	var $_recurring_mode;

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	var $entity_table = "jo_crmentity";

	// For workflows update field tasks is deleted all the lineitems.
	var $isLineItemUpdate = true;

	/**	Constructor which will set the column_fields in this object
	 */
	function Invoice() {
		$this->log =LoggerManager::getLogger('Invoice');
		$this->log->debug("Entering Invoice() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Invoice');
		$this->log->debug("Exiting Invoice method ...");
	}


	/** Function to handle the module specific save operations

	*/

	function save_module($module) {
		global $updateInventoryProductRel_deduct_stock;
		$updateInventoryProductRel_deduct_stock = true;

		/* $_REQUEST['REQUEST_FROM_WS'] is set from webservices script.
		 * Depending on $_REQUEST['totalProductCount'] value inserting line items into DB.
		 * This should be done by webservices, not be normal save of Inventory record.
		 * So unsetting the value $_REQUEST['totalProductCount'] through check point
		 */
		if (isset($_REQUEST['REQUEST_FROM_WS']) && $_REQUEST['REQUEST_FROM_WS']) {
			unset($_REQUEST['totalProductCount']);
		}
		//in ajax save we should not call this function, because this will delete all the existing product values
		if(isset($this->_recurring_mode) && $this->_recurring_mode == 'recurringinvoice_from_so' && isset($this->_salesorderid) && $this->_salesorderid!='') {
			// We are getting called from the RecurringInvoice cron service!
			$this->createRecurringInvoiceFromSO();

		} else if(isset($_REQUEST)) {
			if($_REQUEST['action'] != 'InvoiceAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
					&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
					&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false && $_REQUEST['action'] != 'FROM_WS') {
				//Based on the total Number of rows we will save the product relationship with this entity
				saveInventoryProductDetails($this, 'Invoice');
			} else if($_REQUEST['action'] == 'InvoiceAjax' || $_REQUEST['action'] == 'MassEditSave' || $_REQUEST['action'] == 'FROM_WS') {
				$updateInventoryProductRel_deduct_stock = false;
			}
		}
		// Update the currency id and the conversion rate for the invoice
		$update_query = "update jo_invoice set currency_id=?, conversion_rate=? where invoiceid=?";

		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
		$this->db->pquery($update_query, $update_params);
	}

	/**
	 * Customizing the restore procedure.
	 */
	function restore($module, $id) {
		global $updateInventoryProductRel_deduct_stock;
		$status = getInvoiceStatus($id);
		if($status != 'Cancel') {
			$updateInventoryProductRel_deduct_stock = true;
		}
		parent::restore($module, $id);
	}

	/**
	 * Customizing the Delete procedure.
	 */
	function trash($module, $recordId) {
		$status = getInvoiceStatus($recordId);
		if($status != 'Cancel') {
			addProductsToStock($recordId);
		}
		parent::trash($module, $recordId);
	}

	/**	function used to get the name of the current object
	 *	@return string $this->name - name of the current object
	 */
	function get_summary_text()
	{
		global $log;
		$log->debug("Entering get_summary_text() method ...");
		$log->debug("Exiting get_summary_text method ...");
		return $this->name;
	}


	/**	function used to get the list of activities which are related to the invoice
	 *	@param int $id - invoice id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
	function get_activities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_activities(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/Activity.php");
		$other = new Activity();
		vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		$button .= '<input type="hidden" name="activity_mode">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				if(getFieldVisibilityPermission('Calendar',$current_user->id,'parent_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_TODO', $related_module) ."'>&nbsp;";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_contactdetails.lastname, jo_contactdetails.firstname, jo_contactdetails.contactid,
				jo_activity.*,jo_seactivityrel.crmid as parent_id,jo_crmentity.crmid, jo_crmentity.smownerid,
				jo_crmentity.modifiedtime
				from jo_activity
				inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
				left join jo_cntactivityrel on jo_cntactivityrel.activityid= jo_activity.activityid
				left join jo_contactdetails on jo_contactdetails.contactid = jo_cntactivityrel.contactid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				where jo_seactivityrel.crmid=".$id." and activitytype='Task' and jo_crmentity.deleted=0
						and (jo_activity.status is not NULL and jo_activity.status != 'Completed')
						and (jo_activity.status is not NULL and jo_activity.status != 'Deferred')";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/**	function used to get the the activity history related to the quote
	 *	@param int $id - invoice id
	 *	@return array - return an array which will be returned from the function GetHistory
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_contactdetails.lastname, jo_contactdetails.firstname,
				jo_contactdetails.contactid,jo_activity.*,jo_seactivityrel.*,
				jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.modifiedtime,
				jo_crmentity.createdtime, jo_crmentity.description,
				case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
				from jo_activity
				inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
				left join jo_cntactivityrel on jo_cntactivityrel.activityid= jo_activity.activityid
				left join jo_contactdetails on jo_contactdetails.contactid = jo_cntactivityrel.contactid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				where jo_activity.activitytype='Task'
					and (jo_activity.status = 'Completed' or jo_activity.status = 'Deferred')
					and jo_seactivityrel.crmid=".$id."
					and jo_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Invoice',$query,$id);
	}



	/**	Function used to get the Status history of the Invoice
	 *	@param $id - invoice id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_invoicestatushistory($id)
	{
		global $log;
		$log->debug("Entering get_invoicestatushistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select jo_invoicestatushistory.*, jo_invoice.invoice_no from jo_invoicestatushistory inner join jo_invoice on jo_invoice.invoiceid = jo_invoicestatushistory.invoiceid inner join jo_crmentity on jo_crmentity.crmid = jo_invoice.invoiceid where jo_crmentity.deleted = 0 and jo_invoice.invoiceid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Invoice No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_INVOICE_STATUS'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Amount are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$invoicestatus_access = (getFieldVisibilityPermission('Invoice', $current_user->id, 'invoicestatus') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Invoice');

		$invoicestatus_array = ($invoicestatus_access != 1)? $picklistarray['invoicestatus']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($invoicestatus_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['invoiceid'];
			$entries[] = $row['invoice_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['invoicestatus'], $invoicestatus_array))? $row['invoicestatus']: $error_msg;
			$entries[] = DateTimeField::convertToUserFormat($row['lastmodified']);

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

		$log->debug("Exiting get_invoicestatushistory method ...");

		return $return_data;
	}

	// Function to get column name - Overriding function of base class
	function get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype = '') {
		if ($columname == 'salesorderid') {
			if ($fldvalue == '') return null;
		}
		return parent::get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype);
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryPlanner){

		// Define the dependency matrix ahead
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityInvoice', array('jo_usersInvoice', 'jo_groupsInvoice', 'jo_lastModifiedByInvoice'));
		$matrix->setDependency('jo_inventoryproductrelInvoice', array('jo_productsInvoice', 'jo_serviceInvoice'));

		if (!$queryPlanner->requireTable('jo_invoice', $matrix)) {
			return '';
		}

		$matrix->setDependency('jo_invoice',array('jo_crmentityInvoice', "jo_currency_info$secmodule",
				'jo_invoicecf', 'jo_salesorderInvoice', 'jo_invoicebillads',
				'jo_invoiceshipads', 'jo_inventoryproductrelInvoice', 'jo_contactdetailsInvoice', 'jo_accountInvoice'));

		$query = $this->getRelationQuery($module,$secmodule,"jo_invoice","invoiceid", $queryPlanner);

		if ($queryPlanner->requireTable('jo_crmentityInvoice', $matrix)) {
			$query .= " left join jo_crmentity as jo_crmentityInvoice on jo_crmentityInvoice.crmid=jo_invoice.invoiceid and jo_crmentityInvoice.deleted=0";
		}
		if ($queryPlanner->requireTable('jo_invoicecf')) {
			$query .= " left join jo_invoicecf on jo_invoice.invoiceid = jo_invoicecf.invoiceid";
		}
		if ($queryPlanner->requireTable("jo_currency_info$secmodule")) {
			$query .= " left join jo_currency_info as jo_currency_info$secmodule on jo_currency_info$secmodule.id = jo_invoice.currency_id";
		}
		if ($queryPlanner->requireTable('jo_salesorderInvoice')) {
			$query .= " left join jo_salesorder as jo_salesorderInvoice on jo_salesorderInvoice.salesorderid=jo_invoice.salesorderid";
		}
		if ($queryPlanner->requireTable('jo_invoicebillads')) {
			$query .= " left join jo_invoicebillads on jo_invoice.invoiceid=jo_invoicebillads.invoicebilladdressid";
		}
		if ($queryPlanner->requireTable('jo_invoiceshipads')) {
			$query .= " left join jo_invoiceshipads on jo_invoice.invoiceid=jo_invoiceshipads.invoiceshipaddressid";
		}
		if ($queryPlanner->requireTable('jo_inventoryproductrelInvoice', $matrix)) {
		}
		if ($queryPlanner->requireTable('jo_productsInvoice')) {
			$query .= " left join jo_products as jo_productsInvoice on jo_productsInvoice.productid = jo_inventoryproductreltmpInvoice.productid";
		}
		if ($queryPlanner->requireTable('jo_serviceInvoice')) {
			$query .= " left join jo_service as jo_serviceInvoice on jo_serviceInvoice.serviceid = jo_inventoryproductreltmpInvoice.productid";
		}
		if ($queryPlanner->requireTable('jo_groupsInvoice')) {
			$query .= " left join jo_groups as jo_groupsInvoice on jo_groupsInvoice.groupid = jo_crmentityInvoice.smownerid";
		}
		if ($queryPlanner->requireTable('jo_usersInvoice')) {
			$query .= " left join jo_users as jo_usersInvoice on jo_usersInvoice.id = jo_crmentityInvoice.smownerid";
		}
		if ($queryPlanner->requireTable('jo_contactdetailsInvoice')) {
			$query .= " left join jo_contactdetails as jo_contactdetailsInvoice on jo_invoice.contactid = jo_contactdetailsInvoice.contactid";
		}
		if ($queryPlanner->requireTable('jo_accountInvoice')) {
			$query .= " left join jo_account as jo_accountInvoice on jo_accountInvoice.accountid = jo_invoice.accountid";
		}
		if ($queryPlanner->requireTable('jo_lastModifiedByInvoice')) {
			$query .= " left join jo_users as jo_lastModifiedByInvoice on jo_lastModifiedByInvoice.id = jo_crmentityInvoice.modifiedby ";
		}
		if ($queryPlanner->requireTable("jo_createdbyInvoice")){
			$query .= " left join jo_users as jo_createdbyInvoice on jo_createdbyInvoice.id = jo_crmentityInvoice.smcreatorid ";
		}
		return $query;
	}

	/*
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array (
			"Calendar" =>array("jo_seactivityrel"=>array("crmid","activityid"),"jo_invoice"=>"invoiceid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_invoice"=>"invoiceid"),
			"Accounts" => array("jo_invoice"=>array("invoiceid","accountid")),
			"Contacts" => array("jo_invoice"=>array("invoiceid","contactid")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' || $return_module == 'Contacts') {
			$this->trash('Invoice',$id);
		} elseif($return_module=='SalesOrder') {
			$relation_query = 'UPDATE jo_invoice set salesorderid=? where invoiceid=?';
			$this->db->pquery($relation_query, array(null,$id));
		} elseif($return_module == 'Documents') {
			$sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

	/*
	 * Function to get the relations of salesorder to invoice for recurring invoice procedure
	 * @param - $salesorder_id Salesorder ID
	 */
	function createRecurringInvoiceFromSO(){
		global $adb;
		$salesorder_id = $this->_salesorderid;
		$query1 = "SELECT * FROM jo_inventoryproductrel WHERE id=?";
		$res = $adb->pquery($query1, array($salesorder_id));
		$no_of_products = $adb->num_rows($res);
		$fieldsList = $adb->getFieldsArray($res);
		$update_stock = array();
		for($j=0; $j<$no_of_products; $j++) {
			$row = $adb->query_result_rowdata($res, $j);
			$col_value = array();
			for($k=0; $k<count($fieldsList); $k++) {
				if($fieldsList[$k]!='lineitem_id'){
					$col_value[$fieldsList[$k]] = $row[$fieldsList[$k]];
				}
			}
			if(count($col_value) > 0) {
				$col_value['id'] = $this->id;
				$columns = array_keys($col_value);
				$values = array_values($col_value);
				$query2 = "INSERT INTO jo_inventoryproductrel(". implode(",",$columns) .") VALUES (". generateQuestionMarks($values) .")";
				$adb->pquery($query2, array($values));
				$prod_id = $col_value['productid'];
				$qty = $col_value['quantity'];
				$update_stock[$col_value['sequence_no']] = $qty;
				updateStk($prod_id,$qty,'',array(),'Invoice');
			}
		}

		$query1 = "SELECT * FROM jo_inventorysubproductrel WHERE id=?";
		$res = $adb->pquery($query1, array($salesorder_id));
		$no_of_products = $adb->num_rows($res);
		$fieldsList = $adb->getFieldsArray($res);
		for($j=0; $j<$no_of_products; $j++) {
			$row = $adb->query_result_rowdata($res, $j);
			$col_value = array();
			for($k=0; $k<count($fieldsList); $k++) {
					$col_value[$fieldsList[$k]] = $row[$fieldsList[$k]];
			}
			if(count($col_value) > 0) {
				$col_value['id'] = $this->id;
				$columns = array_keys($col_value);
				$values = array_values($col_value);
				$query2 = "INSERT INTO jo_inventorysubproductrel(". implode(",",$columns) .") VALUES (". generateQuestionMarks($values) .")";
				$adb->pquery($query2, array($values));
				$prod_id = $col_value['productid'];
				$qty = $update_stock[$col_value['sequence_no']];
				updateStk($prod_id,$qty,'',array(),'Invoice');
			}
		}

		//Adding charge values
		$adb->pquery('DELETE FROM jo_inventorychargesrel WHERE recordid = ?', array($this->id));
		$adb->pquery('INSERT INTO jo_inventorychargesrel SELECT ?, charges FROM jo_inventorychargesrel WHERE recordid = ?', array($this->id, $salesorder_id));

		//Update the netprice (subtotal), taxtype, discount, S&H charge, adjustment and total for the Invoice
		$updatequery  = " UPDATE jo_invoice SET ";
		$updateparams = array();
		// Remaining column values to be updated -> column name to field name mapping
		$invoice_column_field = Array (
			'adjustment' => 'txtAdjustment',
			'subtotal' => 'hdnSubTotal',
			'total' => 'hdnGrandTotal',
			'taxtype' => 'hdnTaxType',
			'discount_percent' => 'hdnDiscountPercent',
			'discount_amount' => 'hdnDiscountAmount',
			's_h_amount' => 'hdnS_H_Amount',
			'region_id' => 'region_id',
			's_h_percent' => 'hdnS_H_Percent',
			'balance' => 'hdnGrandTotal'
		);
		$updatecols = array();
		foreach($invoice_column_field as $col => $field) {
			$updatecols[] = "$col=?";
			$updateparams[] = $this->column_fields[$field];
		}
		if (count($updatecols) > 0) {
			$updatequery .= implode(",", $updatecols);

			$updatequery .= " WHERE invoiceid=?";
			array_push($updateparams, $this->id);

			$adb->pquery($updatequery, $updateparams);
		}
	}

	function insertIntoEntityTable($table_name, $module, $fileid = '')  {
		//Ignore relation table insertions while saving of the record
		if($table_name == 'jo_inventoryproductrel') {
			return;
		}
		parent::insertIntoEntityTable($table_name, $module, $fileid);
	}

	/*Function to create records in current module.
	**This function called while importing records to this module*/
	function createRecords($obj) {
		$createRecords = createRecords($obj);
		return $createRecords;
	}

	/*Function returns the record information which means whether the record is imported or not
	**This function called while importing records to this module*/
	function importRecord($obj, $inventoryFieldData, $lineItemDetails) {
		$entityInfo = importRecord($obj, $inventoryFieldData, $lineItemDetails);
		return $entityInfo;
	}

	/*Function to return the status count of imported records in current module.
	**This function called while importing records to this module*/
	function getImportStatusCount($obj) {
		$statusCount = getImportStatusCount($obj);
		return $statusCount;
	}

	function undoLastImport($obj, $user) {
		$undoLastImport = undoLastImport($obj, $user);
	}

	/** Function to export the lead records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Invoice Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Invoice", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$fields_list .= getInventoryFieldsForExport($this->table_name);
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT $fields_list FROM ".$this->entity_table."
				INNER JOIN jo_invoice ON jo_invoice.invoiceid = jo_crmentity.crmid
				LEFT JOIN jo_invoicecf ON jo_invoicecf.invoiceid = jo_invoice.invoiceid
				LEFT JOIN jo_salesorder ON jo_salesorder.salesorderid = jo_invoice.salesorderid
				LEFT JOIN jo_invoicebillads ON jo_invoicebillads.invoicebilladdressid = jo_invoice.invoiceid
				LEFT JOIN jo_invoiceshipads ON jo_invoiceshipads.invoiceshipaddressid = jo_invoice.invoiceid
				LEFT JOIN jo_inventoryproductrel ON jo_inventoryproductrel.id = jo_invoice.invoiceid
				LEFT JOIN jo_products ON jo_products.productid = jo_inventoryproductrel.productid
				LEFT JOIN jo_service ON jo_service.serviceid = jo_inventoryproductrel.productid
				LEFT JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_invoice.contactid
				LEFT JOIN jo_account ON jo_account.accountid = jo_invoice.accountid
				LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_invoice.currency_id
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";

		$query .= $this->getNonAdminAccessControlQuery('Invoice',$current_user);
		$where_auto = " jo_crmentity.deleted=0";

		if($where != "") {
			$query .= " where ($where) AND ".$where_auto;
		} else {
			$query .= " where ".$where_auto;
		}

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/**
	 * Function to get importable mandatory fields
	 * By default some fields like Quantity, List Price is not mandaroty for Invertory modules but
	 * import fails if those fields are not mapped during import.
	 */
	function getMandatoryImportableFields() {
		return getInventoryImportableMandatoryFeilds($this->moduleName);
	}
}
?>