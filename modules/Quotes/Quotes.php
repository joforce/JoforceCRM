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
class Quotes extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "jo_quotes";
	var $table_index= 'quoteid';
	var $tab_name = Array('jo_crmentity','jo_quotes','jo_quotesbillads','jo_quotesshipads','jo_quotescf','jo_inventoryproductrel');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_quotes'=>'quoteid','jo_quotesbillads'=>'quotebilladdressid','jo_quotesshipads'=>'quoteshipaddressid','jo_quotescf'=>'quoteid','jo_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_quotescf', 'quoteid');
	var $entity_table = "jo_crmentity";

	var $billadr_table = "jo_quotesbillads";

	var $object_name = "Quote";

	var $new_schema = true;

	var $column_fields = Array();

	var $sortby_fields = Array('subject','crmid','smownerid','accountname','lastname');

	// This is used to retrieve related jo_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
				//'Quote No'=>Array('crmentity'=>'crmid'),
				// Module Sequence Numbering
				'Quote No'=>Array('quotes'=>'quote_no'),
				// END
				'Subject'=>Array('quotes'=>'subject'),
				'Quote Stage'=>Array('quotes'=>'quotestage'),
				'Potential Name'=>Array('quotes'=>'potentialid'),
				'Account Name'=>Array('account'=> 'accountid'),
				'Total'=>Array('quotes'=> 'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
				        'Quote No'=>'quote_no',
				        'Subject'=>'subject',
				        'Quote Stage'=>'quotestage',
				        'Potential Name'=>'potential_id',
					'Account Name'=>'account_id',
					'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				'Quote No'=>Array('quotes'=>'quote_no'),
				'Subject'=>Array('quotes'=>'subject'),
				'Account Name'=>Array('quotes'=>'accountid'),
				'Quote Stage'=>Array('quotes'=>'quotestage'),
				);

	var $search_fields_name = Array(
					'Quote No'=>'quote_no',
				        'Subject'=>'subject',
				        'Account Name'=>'account_id',
				        'Quote Stage'=>'quotestage',
				      );

	// This is the list of jo_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'ASC';
	//var $groupTable = Array('jo_quotegrouprelation','quoteid');

	var $mandatory_fields = Array('subject','createdtime' ,'modifiedtime', 'assigned_user_id', 'quantity', 'listprice', 'productid');

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	// For workflows update field tasks is deleted all the lineitems.
	var $isLineItemUpdate = true;

	/**	Constructor which will set the column_fields in this object
	 */
	function Quotes() {
		$this->log =LoggerManager::getLogger('quote');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Quotes');
	}

	function save_module()
	{
		global $adb;

		/* $_REQUEST['REQUEST_FROM_WS'] is set from webservices script.
		 * Depending on $_REQUEST['totalProductCount'] value inserting line items into DB.
		 * This should be done by webservices, not be normal save of Inventory record.
		 * So unsetting the value $_REQUEST['totalProductCount'] through check point
		 */
		if (isset($_REQUEST['REQUEST_FROM_WS']) && $_REQUEST['REQUEST_FROM_WS']) {
			unset($_REQUEST['totalProductCount']);
		}

		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'QuotesAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
				&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
				&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false) {
			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'Quotes');
		}

		// Update the currency id and the conversion rate for the quotes
		$update_query = "update jo_quotes set currency_id=?, conversion_rate=? where quoteid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
		$adb->pquery($update_query, $update_params);
	}

	/**	function used to get the list of sales orders which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
	function get_salesorder($id)
	{
		global $log,$singlepane_view;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		require_once('modules/SalesOrder/SalesOrder.php');
	        $focus = new SalesOrder();

		$button = '';

		if($singlepane_view == 'true')
			$returnset = '&return_module=Quotes&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module=Quotes&return_action=CallRelatedList&return_id='.$id;

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "select jo_crmentity.*, jo_salesorder.*, jo_quotes.subject as quotename
			, jo_account.accountname,case when (jo_users.user_name not like '') then
			$userNameSql else jo_groups.groupname end as user_name
		from jo_salesorder
		inner join jo_crmentity on jo_crmentity.crmid=jo_salesorder.salesorderid
		left outer join jo_quotes on jo_quotes.quoteid=jo_salesorder.quoteid
		left outer join jo_account on jo_account.accountid=jo_salesorder.accountid
		left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
        LEFT JOIN jo_salesordercf ON jo_salesordercf.salesorderid = jo_salesorder.salesorderid
        LEFT JOIN jo_invoice_recurring_info ON jo_invoice_recurring_info.start_period = jo_salesorder.salesorderid
		LEFT JOIN jo_sobillads ON jo_sobillads.sobilladdressid = jo_salesorder.salesorderid
		LEFT JOIN jo_soshipads ON jo_soshipads.soshipaddressid = jo_salesorder.salesorderid
		left join jo_users on jo_users.id=jo_crmentity.smownerid
		where jo_crmentity.deleted=0 and jo_salesorder.quoteid = ".$id;
		$log->debug("Exiting get_salesorder method ...");
		return GetRelatedList('Quotes','SalesOrder',$focus,$query,$button,$returnset);
	}

	/**	function used to get the list of activities which are related to the Quotes
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetRelatedList
	 */
	function get_activities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_activities(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/Activity.php");
		$other = new Activity();
        modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

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
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else
		jo_groups.groupname end as user_name, jo_contactdetails.contactid,
		jo_contactdetails.lastname, jo_contactdetails.firstname, jo_activity.*,
		jo_seactivityrel.crmid as parent_id,jo_crmentity.crmid, jo_crmentity.smownerid,
		jo_crmentity.modifiedtime,jo_recurringevents.recurringtype
		from jo_activity
		inner join jo_seactivityrel on jo_seactivityrel.activityid=
		jo_activity.activityid
		inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
		left join jo_cntactivityrel on jo_cntactivityrel.activityid=
		jo_activity.activityid
		left join jo_contactdetails on jo_contactdetails.contactid =
		jo_cntactivityrel.contactid
		left join jo_users on jo_users.id=jo_crmentity.smownerid
		left outer join jo_recurringevents on jo_recurringevents.activityid=
		jo_activity.activityid
		left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
		where jo_seactivityrel.crmid=".$id." and jo_crmentity.deleted=0 and
			activitytype='Task' and (jo_activity.status is not NULL and
			jo_activity.status != 'Completed') and (jo_activity.status is not NULL and
			jo_activity.status != 'Deferred')";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/**	function used to get the the activity history related to the quote
	 *	@param int $id - quote id
	 *	@return array - return an array which will be returned from the function GetHistory
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_activity.activityid, jo_activity.subject, jo_activity.status,
			jo_activity.eventstatus, jo_activity.activitytype,jo_activity.date_start,
			jo_activity.due_date,jo_activity.time_start, jo_activity.time_end,
			jo_contactdetails.contactid,
			jo_contactdetails.firstname,jo_contactdetails.lastname, jo_crmentity.modifiedtime,
			jo_crmentity.createdtime, jo_crmentity.description, case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
			from jo_activity
				inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
				left join jo_cntactivityrel on jo_cntactivityrel.activityid= jo_activity.activityid
				left join jo_contactdetails on jo_contactdetails.contactid= jo_cntactivityrel.contactid
                                left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				where jo_activity.activitytype='Task'
  				and (jo_activity.status = 'Completed' or jo_activity.status = 'Deferred')
	 	        	and jo_seactivityrel.crmid=".$id."
                                and jo_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in includes/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Quotes',$query,$id);
	}





	/**	Function used to get the Quote Stage history of the Quotes
	 *	@param $id - quote id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_quotestagehistory($id)
	{
		global $log;
		$log->debug("Entering get_quotestagehistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select jo_quotestagehistory.*, jo_quotes.quote_no from jo_quotestagehistory inner join jo_quotes on jo_quotes.quoteid = jo_quotestagehistory.quoteid inner join jo_crmentity on jo_crmentity.crmid = jo_quotes.quoteid where jo_crmentity.deleted = 0 and jo_quotes.quoteid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Quote No'];
		$header[] = $app_strings['LBL_ACCOUNT_NAME'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['Quote Stage'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Account Name , Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$quotestage_access = (getFieldVisibilityPermission('Quotes', $current_user->id, 'quotestage') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Quotes');

		$quotestage_array = ($quotestage_access != 1)? $picklistarray['quotestage']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($quotestage_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			// Module Sequence Numbering
			//$entries[] = $row['quoteid'];
			$entries[] = $row['quote_no'];
			// END
			$entries[] = $row['accountname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['quotestage'], $quotestage_array))? $row['quotestage']: $error_msg;
			$date = new DateTimeField($row['lastmodified']);
			$entries[] = $date->getDisplayDateTimeValue();

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_quotestagehistory method ...");

		return $return_data;
	}

	// Function to get column name - Overriding function of base class
	function get_column_value($columname, $fldvalue, $fieldname, $uitype, $datatype='') {
		if ($columname == 'potentialid' || $columname == 'contactid') {
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
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityQuotes', array('jo_usersQuotes', 'jo_groupsQuotes', 'jo_lastModifiedByQuotes'));
		$matrix->setDependency('jo_inventoryproductrelQuotes', array('jo_productsQuotes', 'jo_serviceQuotes'));
		
		if (!$queryPlanner->requireTable('jo_quotes', $matrix)) {
			return '';
		}
        $matrix->setDependency('jo_quotes',array('jo_crmentityQuotes', "jo_currency_info$secmodule",
				'jo_quotescf', 'jo_potentialRelQuotes', 'jo_quotesbillads','jo_quotesshipads',
				'jo_inventoryproductrelQuotes', 'jo_contactdetailsQuotes', 'jo_accountQuotes',
				'jo_invoice_recurring_info','jo_quotesQuotes','jo_usersRel1'));

		$query = $this->getRelationQuery($module,$secmodule,"jo_quotes","quoteid", $queryPlanner);
		if ($queryPlanner->requireTable("jo_crmentityQuotes", $matrix)){
			$query .= " left join jo_crmentity as jo_crmentityQuotes on jo_crmentityQuotes.crmid=jo_quotes.quoteid and jo_crmentityQuotes.deleted=0";
		}
		if ($queryPlanner->requireTable("jo_quotescf")){
			$query .= " left join jo_quotescf on jo_quotes.quoteid = jo_quotescf.quoteid";
		}
		if ($queryPlanner->requireTable("jo_quotesbillads")){
			$query .= " left join jo_quotesbillads on jo_quotes.quoteid=jo_quotesbillads.quotebilladdressid";
		}
		if ($queryPlanner->requireTable("jo_quotesshipads")){
			$query .= " left join jo_quotesshipads on jo_quotes.quoteid=jo_quotesshipads.quoteshipaddressid";
		}
		if ($queryPlanner->requireTable("jo_currency_info$secmodule")){
			$query .= " left join jo_currency_info as jo_currency_info$secmodule on jo_currency_info$secmodule.id = jo_quotes.currency_id";
		}
		if ($queryPlanner->requireTable("jo_inventoryproductrelQuotes",$matrix)){
		}
		if ($queryPlanner->requireTable("jo_productsQuotes")){
			$query .= " left join jo_products as jo_productsQuotes on jo_productsQuotes.productid = jo_inventoryproductreltmpQuotes.productid";
		}
		if ($queryPlanner->requireTable("jo_serviceQuotes")){
			$query .= " left join jo_service as jo_serviceQuotes on jo_serviceQuotes.serviceid = jo_inventoryproductreltmpQuotes.productid";
		}
		if ($queryPlanner->requireTable("jo_groupsQuotes")){
			$query .= " left join jo_groups as jo_groupsQuotes on jo_groupsQuotes.groupid = jo_crmentityQuotes.smownerid";
		}
		if ($queryPlanner->requireTable("jo_usersQuotes")){
			$query .= " left join jo_users as jo_usersQuotes on jo_usersQuotes.id = jo_crmentityQuotes.smownerid";
		}
		if ($queryPlanner->requireTable("jo_usersRel1")){
			$query .= " left join jo_users as jo_usersRel1 on jo_usersRel1.id = jo_quotes.inventorymanager";
		}
		if ($queryPlanner->requireTable("jo_potentialRelQuotes")){
			$query .= " left join jo_potential as jo_potentialRelQuotes on jo_potentialRelQuotes.potentialid = jo_quotes.potentialid";
		}
		if ($queryPlanner->requireTable("jo_contactdetailsQuotes")){
			$query .= " left join jo_contactdetails as jo_contactdetailsQuotes on jo_contactdetailsQuotes.contactid = jo_quotes.contactid";
		}
		if ($queryPlanner->requireTable("jo_accountQuotes")){
			$query .= " left join jo_account as jo_accountQuotes on jo_accountQuotes.accountid = jo_quotes.accountid";
		}
		if ($queryPlanner->requireTable("jo_lastModifiedByQuotes")){
			$query .= " left join jo_users as jo_lastModifiedByQuotes on jo_lastModifiedByQuotes.id = jo_crmentityQuotes.modifiedby ";
		}
        if ($queryPlanner->requireTable("jo_createdbyQuotes")){
			$query .= " left join jo_users as jo_createdbyQuotes on jo_createdbyQuotes.id = jo_crmentityQuotes.smcreatorid ";
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
			"SalesOrder" =>array("jo_salesorder"=>array("quoteid","salesorderid"),"jo_quotes"=>"quoteid"),
			"Calendar" =>array("jo_seactivityrel"=>array("crmid","activityid"),"jo_quotes"=>"quoteid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_quotes"=>"quoteid"),
			"Accounts" => array("jo_quotes"=>array("quoteid","accountid")),
			"Contacts" => array("jo_quotes"=>array("quoteid","contactid")),
			"Potentials" => array("jo_quotes"=>array("quoteid","potentialid")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts' ) {
			$this->trash('Quotes',$id);
		} elseif($return_module == 'Potentials') {
			$relation_query = 'UPDATE jo_quotes SET potentialid=? WHERE quoteid=?';
			$this->db->pquery($relation_query, array(null, $id));
		} elseif($return_module == 'Contacts') {
			$relation_query = 'UPDATE jo_quotes SET contactid=? WHERE quoteid=?';
			$this->db->pquery($relation_query, array(null, $id));
		} elseif($return_module == 'Documents') {
            $sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
            $this->db->pquery($sql, array($id, $return_id));
        } elseif($return_module == 'Leads'){
            $relation_query = 'UPDATE jo_quotes SET contactid=? WHERE quoteid=?';
            $this->db->pquery($relation_query, array(null, $id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
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
	* Returns Export Quotes Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Quotes", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$fields_list .= getInventoryFieldsForExport($this->table_name);
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT $fields_list FROM ".$this->entity_table."
				INNER JOIN jo_quotes ON jo_quotes.quoteid = jo_crmentity.crmid
				LEFT JOIN jo_quotescf ON jo_quotescf.quoteid = jo_quotes.quoteid
				LEFT JOIN jo_quotesbillads ON jo_quotesbillads.quotebilladdressid = jo_quotes.quoteid
				LEFT JOIN jo_quotesshipads ON jo_quotesshipads.quoteshipaddressid = jo_quotes.quoteid
				LEFT JOIN jo_inventoryproductrel ON jo_inventoryproductrel.id = jo_quotes.quoteid
				LEFT JOIN jo_products ON jo_products.productid = jo_inventoryproductrel.productid
				LEFT JOIN jo_service ON jo_service.serviceid = jo_inventoryproductrel.productid
				LEFT JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_quotes.contactid
				LEFT JOIN jo_potential ON jo_potential.potentialid = jo_quotes.potentialid
				LEFT JOIN jo_account ON jo_account.accountid = jo_quotes.accountid
				LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_quotes.currency_id
				LEFT JOIN jo_users AS jo_inventoryManager ON jo_inventoryManager.id = jo_quotes.inventorymanager
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";

		$query .= $this->getNonAdminAccessControlQuery('Quotes',$current_user);
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
