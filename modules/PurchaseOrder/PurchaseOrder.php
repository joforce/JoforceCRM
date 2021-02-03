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
class PurchaseOrder extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "jo_purchaseorder";
	var $table_index= 'purchaseorderid';
	var $tab_name = Array('jo_crmentity','jo_purchaseorder','jo_pobillads','jo_poshipads','jo_purchaseordercf','jo_inventoryproductrel');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_purchaseorder'=>'purchaseorderid','jo_pobillads'=>'pobilladdressid','jo_poshipads'=>'poshipaddressid','jo_purchaseordercf'=>'purchaseorderid','jo_inventoryproductrel'=>'id');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_purchaseordercf', 'purchaseorderid');
	var $entity_table = "jo_crmentity";

	var $billadr_table = "jo_pobillads";

	var $column_fields = Array();

	var $sortby_fields = Array('subject','tracking_no','smownerid','lastname');

	// This is used to retrieve related jo_fields from form posts.
	var $additional_column_fields = Array('assigned_user_name', 'smownerid', 'opportunity_id', 'case_id', 'contact_id', 'task_id', 'note_id', 'meeting_id', 'call_id', 'email_id', 'parent_name', 'member_id' );

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
				//  Module Sequence Numbering
				//'Order No'=>Array('crmentity'=>'crmid'),
				'Order No'=>Array('purchaseorder'=>'purchaseorder_no'),
				// END
				'Subject'=>Array('purchaseorder'=>'subject'),
				'Vendor Name'=>Array('purchaseorder'=>'vendorid'),
				'Tracking Number'=>Array('purchaseorder'=> 'tracking_no'),
				'Total'=>Array('purchaseorder'=>'total'),
				'Assigned To'=>Array('crmentity'=>'smownerid')
				);

	var $list_fields_name = Array(
				        'Order No'=>'purchaseorder_no',
				        'Subject'=>'subject',
				        'Vendor Name'=>'vendor_id',
					'Tracking Number'=>'tracking_no',
					'Total'=>'hdnGrandTotal',
				        'Assigned To'=>'assigned_user_id'
				      );
	var $list_link_field= 'subject';

	var $search_fields = Array(
				'Order No'=>Array('purchaseorder'=>'purchaseorder_no'),
				'Subject'=>Array('purchaseorder'=>'subject'),
				);

	var $search_fields_name = Array(
				        'Order No'=>'purchaseorder_no',
				        'Subject'=>'subject',
				      );
	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('subject', 'vendor_id','createdtime' ,'modifiedtime', 'assigned_user_id', 'quantity', 'listprice', 'productid');

	// This is the list of jo_fields that are required.
	var $required_fields =  array("accountname"=>1);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'subject';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'subject';

	// For workflows update field tasks is deleted all the lineitems.
	var $isLineItemUpdate = true;

	//var $groupTable = Array('jo_pogrouprelation','purchaseorderid');
	/** Constructor Function for Order class
	 *  This function creates an instance of LoggerManager class using getLogger method
	 *  creates an instance for PearDatabase class and get values for column_fields array of Order class.
	 */
	function PurchaseOrder() {
		$this->log =LoggerManager::getLogger('PurchaseOrder');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('PurchaseOrder');
	}

	function save_module($module)
	{
		global $adb, $updateInventoryProductRel_deduct_stock;
		$updateInventoryProductRel_deduct_stock = false;

		$requestProductIdsList = $requestQuantitiesList = array();
		$totalNoOfProducts = $_REQUEST['totalProductCount'];
		for($i=1; $i<=$totalNoOfProducts; $i++) {
			$productId = $_REQUEST['hdnProductId'.$i];
			$requestProductIdsList[$productId] = $productId;
			//Checking same item more than once
			if(array_key_exists($productId, $requestQuantitiesList)) {
				$requestQuantitiesList[$productId] = $requestQuantitiesList[$productId] + $_REQUEST['qty'.$i];
				continue;
			}
			$requestQuantitiesList[$productId] = $_REQUEST['qty'.$i];
		}

		global $itemQuantitiesList, $isItemsRequest;
		$itemQuantitiesList = array();
		$statusValue = $this->column_fields['postatus'];

		if ($totalNoOfProducts) {
			$isItemsRequest = true;
		}

		if ($this->mode == '' && $statusValue === 'Received Shipment') {
			$itemQuantitiesList['new'] = $requestQuantitiesList;

		} else if ($this->mode != '' && in_array($statusValue, array('Received Shipment', 'Cancelled'))) {

			$productIdsList = $quantitiesList = array();
			$recordId = $this->id;
			$result = $adb->pquery("SELECT productid, quantity FROM jo_inventoryproductrel WHERE id = ?", array($recordId));
			$numOfRows = $adb->num_rows($result);
			for ($i=0; $i<$numOfRows; $i++) {
				$productId = $adb->query_result($result, $i, 'productid');
				$productIdsList[$productId] = $productId;
				if(array_key_exists($productId, $quantitiesList)) {
					$quantitiesList[$productId] = $quantitiesList[$productId] + $adb->query_result($result, $i, 'quantity');
					continue;
				}
				$qty = $adb->query_result($result, $i, 'quantity');
				$quantitiesList[$productId] = $qty;
				$subProductQtys = $this->getSubProductsQty($productId);
				if ($statusValue === 'Cancelled' && !empty($subProductQtys)) {
					foreach ($subProductQtys as $subProdId => $subProdQty) {
						$subProdQty = $subProdQty * $qty;
						if (array_key_exists($subProdId, $quantitiesList)) {
							$quantitiesList[$subProdId] = $quantitiesList[$subProdId] + $subProdQty;
							continue;
						}
						$quantitiesList[$subProdId] = $subProdQty;
					}
				}
			}
				
			if ($statusValue === 'Cancelled') {
				$itemQuantitiesList = $quantitiesList;
			} else {

				//Constructing quantities array for newly added line items
				$newProductIds = array_diff($requestProductIdsList, $productIdsList);
				if ($newProductIds) {
					$newQuantitiesList = array();
					foreach ($newProductIds as $productId) {
						$newQuantitiesList[$productId] = $requestQuantitiesList[$productId];
					}
					if ($newQuantitiesList) {
						$itemQuantitiesList['new'] = $newQuantitiesList;
					}
				}

				//Constructing quantities array for deleted line items
				$deletedProductIds = array_diff($productIdsList, $requestProductIdsList);
				if ($deletedProductIds && $totalNoOfProducts) {//$totalNoOfProducts is exist means its not ajax save
					$deletedQuantitiesList = array();
					foreach ($deletedProductIds as $productId) {
						//Checking same item more than once
						if(array_key_exists($productId, $deletedQuantitiesList)) {
							$deletedQuantitiesList[$productId] = $deletedQuantitiesList[$productId] + $quantitiesList[$productId];
							continue;
						}
						$deletedQuantitiesList[$productId] = $quantitiesList[$productId];
					}

					if ($deletedQuantitiesList) {
						$itemQuantitiesList['deleted'] = $deletedQuantitiesList;
					}
				}

				//Constructing quantities array for updated line items
				$updatedProductIds = array_intersect($productIdsList, $requestProductIdsList);
				if (!$totalNoOfProducts) {//$totalNoOfProducts is null then its ajax save
					$updatedProductIds = $productIdsList;
				}
				if ($updatedProductIds) {
					$updatedQuantitiesList = array();
					foreach ($updatedProductIds as $productId) {
						//Checking same item more than once
						if(array_key_exists($productId, $updatedQuantitiesList)) {
							$updatedQuantitiesList[$productId] = $updatedQuantitiesList[$productId] + $quantitiesList[$productId];
							continue;
						}
						
						$quantity = $quantitiesList[$productId];
						if ($totalNoOfProducts) {
							$quantity = $requestQuantitiesList[$productId] - $quantitiesList[$productId];
						}

						if ($quantity) {
							$updatedQuantitiesList[$productId] = $quantity;
						}
						//Check for subproducts
						$subProductQtys = $this->getSubProductsQty($productId);
						if (!empty($subProductQtys) && $quantity) {
							foreach ($subProductQtys as $subProdId => $subProductQty) {
								$subProductQty = $subProductQty * $quantity;
								if (array_key_exists($subProdId, $updatedQuantitiesList)) {
									$updatedQuantitiesList[$subProdId] = $updatedQuantitiesList[$subProdId] + ($subProductQty);
									continue;
								}
								$updatedQuantitiesList[$subProdId] = $subProductQty;
							}
						}
					}
					if ($updatedQuantitiesList) {
						$itemQuantitiesList['updated'] = $updatedQuantitiesList;
					}
				}
			}
		}

		/* $_REQUEST['REQUEST_FROM_WS'] is set from webservices script.
		 * Depending on $_REQUEST['totalProductCount'] value inserting line items into DB.
		 * This should be done by webservices, not be normal save of Inventory record.
		 * So unsetting the value $_REQUEST['totalProductCount'] through check point
		 */
		if (isset($_REQUEST['REQUEST_FROM_WS']) && $_REQUEST['REQUEST_FROM_WS']) {
			unset($_REQUEST['totalProductCount']);
		}

		//in ajax save we should not call this function, because this will delete all the existing product values
		if($_REQUEST['action'] != 'PurchaseOrderAjax' && $_REQUEST['ajxaction'] != 'DETAILVIEW'
				&& $_REQUEST['action'] != 'MassEditSave' && $_REQUEST['action'] != 'ProcessDuplicates'
				&& $_REQUEST['action'] != 'SaveAjax' && $this->isLineItemUpdate != false && $_REQUEST['action'] != 'FROM_WS') {

			//Based on the total Number of rows we will save the product relationship with this entity
			saveInventoryProductDetails($this, 'PurchaseOrder');
		}

		// Update the currency id and the conversion rate for the purchase order
		$update_query = "update jo_purchaseorder set currency_id=?, conversion_rate=? where purchaseorderid=?";
		$update_params = array($this->column_fields['currency_id'], $this->column_fields['conversion_rate'], $this->id);
		$adb->pquery($update_query, $update_params);
	}

	/** Function to get subproducts quantity for given product
	 *  This function accepts the productId as arguments and returns array of subproduct qty for given productId
	 */
	function getSubProductsQty($productId) {
		$subProductQtys = array();
		$adb = PearDatabase::getInstance();
		$result = $adb->pquery("SELECT sequence_no FROM jo_inventoryproductrel WHERE id = ? and productid=?", array($this->id, $productId));
		$numOfRows = $adb->num_rows($result);
		if ($numOfRows > 0) {
			for ($i = 0; $i < $numOfRows; $i++) {
				$sequenceNo = $adb->query_result($result, $i, 'sequence_no');
				$subProdQuery = $adb->pquery("SELECT productid, quantity FROM jo_inventorysubproductrel WHERE id=? AND sequence_no=?", array($this->id, $sequenceNo));
				if ($adb->num_rows($subProdQuery) > 0) {
					for ($j = 0; $j < $adb->num_rows($subProdQuery); $j++) {
						$subProdId = $adb->query_result($subProdQuery, $j, 'productid');
						$subProdQty = $adb->query_result($subProdQuery, $j, 'quantity');
						$subProductQtys[$subProdId] = $subProdQty;
					}
				}
			}
		}
		return $subProductQtys;
	}

	/** Function to get activities associated with the Purchase Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedActivities() method
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
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_contactdetails.lastname, jo_contactdetails.firstname, jo_contactdetails.contactid,jo_activity.*,jo_seactivityrel.crmid as parent_id,jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.modifiedtime from jo_activity inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid left join jo_cntactivityrel on jo_cntactivityrel.activityid= jo_activity.activityid left join jo_contactdetails on jo_contactdetails.contactid = jo_cntactivityrel.contactid left join jo_users on jo_users.id=jo_crmentity.smownerid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid where jo_seactivityrel.crmid=".$id." and activitytype='Task' and jo_crmentity.deleted=0 and (jo_activity.status is not NULL && jo_activity.status != 'Completed') and (jo_activity.status is not NULL and jo_activity.status != 'Deferred') ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/** Function to get the activities history associated with the Purchase Order
	 *  This function accepts the id as arguments and execute the MySQL query using the id
	 *  and sends the query and the id as arguments to renderRelatedHistory() method
	 */
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_contactdetails.lastname, jo_contactdetails.firstname,
			jo_contactdetails.contactid,jo_activity.* ,jo_seactivityrel.*,
			jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.modifiedtime,
			jo_crmentity.createdtime, jo_crmentity.description,case when
			(jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end
			as user_name from jo_activity
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
		//Don't add order by, because, for security, one more condition will be added with this query in includes/RelatedListView.php

        $returnValue = getHistory('PurchaseOrder',$query,$id);
		$log->debug("Exiting get_history method ...");
		return $returnValue;
	}


	/**	Function used to get the Status history of the Purchase Order
	 *	@param $id - purchaseorder id
	 *	@return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are arrays which contains header values and all column values of all entries
	 */
	function get_postatushistory($id)
	{
		global $log;
		$log->debug("Entering get_postatushistory(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select jo_postatushistory.*, jo_purchaseorder.purchaseorder_no from jo_postatushistory inner join jo_purchaseorder on jo_purchaseorder.purchaseorderid = jo_postatushistory.purchaseorderid inner join jo_crmentity on jo_crmentity.crmid = jo_purchaseorder.purchaseorderid where jo_crmentity.deleted = 0 and jo_purchaseorder.purchaseorderid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['Order No'];
		$header[] = $app_strings['Vendor Name'];
		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_PO_STATUS'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Vendor, Total are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$postatus_access = (getFieldVisibilityPermission('PurchaseOrder', $current_user->id, 'postatus') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('PurchaseOrder');

		$postatus_array = ($postatus_access != 1)? $picklistarray['postatus']: array();
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = ($postatus_access != 1)? 'Not Accessible': '-';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			//Module Sequence Numbering
			//$entries[] = $row['purchaseorderid'];
			$entries[] = $row['purchaseorder_no'];
			// END
			$entries[] = $row['vendorname'];
			$entries[] = $row['total'];
			$entries[] = (in_array($row['postatus'], $postatus_array))? $row['postatus']: $error_msg;
			$date = new DateTimeField($row['lastmodified']);
			$entries[] = $date->getDisplayDateTimeValue();

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_postatushistory method ...");

		return $return_data;
	}
	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryPlanner){

		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityPurchaseOrder', array('jo_usersPurchaseOrder', 'jo_groupsPurchaseOrder', 'jo_lastModifiedByPurchaseOrder'));
		$matrix->setDependency('jo_inventoryproductrelPurchaseOrder', array('jo_productsPurchaseOrder', 'jo_servicePurchaseOrder'));
		
		if (!$queryPlanner->requireTable('jo_purchaseorder', $matrix)) {
			return '';
		}
        $matrix->setDependency('jo_purchaseorder',array('jo_crmentityPurchaseOrder', "jo_currency_info$secmodule",
				'jo_purchaseordercf', 'jo_vendorRelPurchaseOrder', 'jo_pobillads',
				'jo_poshipads', 'jo_inventoryproductrelPurchaseOrder', 'jo_contactdetailsPurchaseOrder'));

		$query = $this->getRelationQuery($module,$secmodule,"jo_purchaseorder","purchaseorderid",$queryPlanner);
		if ($queryPlanner->requireTable("jo_crmentityPurchaseOrder", $matrix)){
			$query .= " left join jo_crmentity as jo_crmentityPurchaseOrder on jo_crmentityPurchaseOrder.crmid=jo_purchaseorder.purchaseorderid and jo_crmentityPurchaseOrder.deleted=0";
		}
		if ($queryPlanner->requireTable("jo_purchaseordercf")){
			$query .= " left join jo_purchaseordercf on jo_purchaseorder.purchaseorderid = jo_purchaseordercf.purchaseorderid";
		}
		if ($queryPlanner->requireTable("jo_pobillads")){
			$query .= " left join jo_pobillads on jo_purchaseorder.purchaseorderid=jo_pobillads.pobilladdressid";
		}
		if ($queryPlanner->requireTable("jo_poshipads")){
			$query .= " left join jo_poshipads on jo_purchaseorder.purchaseorderid=jo_poshipads.poshipaddressid";
		}
		if ($queryPlanner->requireTable("jo_currency_info$secmodule")){
			$query .= " left join jo_currency_info as jo_currency_info$secmodule on jo_currency_info$secmodule.id = jo_purchaseorder.currency_id";
		}
		if ($queryPlanner->requireTable("jo_inventoryproductrelPurchaseOrder", $matrix)){
		}
		if ($queryPlanner->requireTable("jo_productsPurchaseOrder")){
			$query .= " left join jo_products as jo_productsPurchaseOrder on jo_productsPurchaseOrder.productid = jo_inventoryproductreltmpPurchaseOrder.productid";
		}
		if ($queryPlanner->requireTable("jo_servicePurchaseOrder")){
			$query .= " left join jo_service as jo_servicePurchaseOrder on jo_servicePurchaseOrder.serviceid = jo_inventoryproductreltmpPurchaseOrder.productid";
		}
		if ($queryPlanner->requireTable("jo_usersPurchaseOrder")){
			$query .= " left join jo_users as jo_usersPurchaseOrder on jo_usersPurchaseOrder.id = jo_crmentityPurchaseOrder.smownerid";
		}
		if ($queryPlanner->requireTable("jo_groupsPurchaseOrder")){
			$query .= " left join jo_groups as jo_groupsPurchaseOrder on jo_groupsPurchaseOrder.groupid = jo_crmentityPurchaseOrder.smownerid";
		}
		if ($queryPlanner->requireTable("jo_vendorRelPurchaseOrder")){
			$query .= " left join jo_vendor as jo_vendorRelPurchaseOrder on jo_vendorRelPurchaseOrder.vendorid = jo_purchaseorder.vendorid";
		}
		if ($queryPlanner->requireTable("jo_contactdetailsPurchaseOrder")){
			$query .= " left join jo_contactdetails as jo_contactdetailsPurchaseOrder on jo_contactdetailsPurchaseOrder.contactid = jo_purchaseorder.contactid";
		}
		if ($queryPlanner->requireTable("jo_lastModifiedByPurchaseOrder")){
			$query .= " left join jo_users as jo_lastModifiedByPurchaseOrder on jo_lastModifiedByPurchaseOrder.id = jo_crmentityPurchaseOrder.modifiedby ";
		}
        if ($queryPlanner->requireTable("jo_createdbyPurchaseOrder")){
			$query .= " left join jo_users as jo_createdbyPurchaseOrder on jo_createdbyPurchaseOrder.id = jo_crmentityPurchaseOrder.smcreatorid ";
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
			"Calendar" =>array("jo_seactivityrel"=>array("crmid","activityid"),"jo_purchaseorder"=>"purchaseorderid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_purchaseorder"=>"purchaseorderid"),
			"Contacts" => array("jo_purchaseorder"=>array("purchaseorderid","contactid")),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Vendors') {
			$sql_req ='UPDATE jo_crmentity SET deleted = 1 WHERE crmid= ?';
			$this->db->pquery($sql_req, array($id));
		} elseif($return_module == 'Contacts') {
			$sql_req ='UPDATE jo_purchaseorder SET contactid=? WHERE purchaseorderid = ?';
			$this->db->pquery($sql_req, array(null, $id));
		} elseif($return_module == 'Documents') {
            $sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
            $this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Accounts') {
			$sql ='UPDATE jo_purchaseorder SET accountid=? WHERE purchaseorderid=?';
			$this->db->pquery($sql, array(null, $id));
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
	* Returns Export PurchaseOrder Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("PurchaseOrder", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		$fields_list .= getInventoryFieldsForExport($this->table_name);
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT $fields_list FROM ".$this->entity_table."
				INNER JOIN jo_purchaseorder ON jo_purchaseorder.purchaseorderid = jo_crmentity.crmid
				LEFT JOIN jo_purchaseordercf ON jo_purchaseordercf.purchaseorderid = jo_purchaseorder.purchaseorderid
				LEFT JOIN jo_pobillads ON jo_pobillads.pobilladdressid = jo_purchaseorder.purchaseorderid
				LEFT JOIN jo_poshipads ON jo_poshipads.poshipaddressid = jo_purchaseorder.purchaseorderid
				LEFT JOIN jo_inventoryproductrel ON jo_inventoryproductrel.id = jo_purchaseorder.purchaseorderid
				LEFT JOIN jo_products ON jo_products.productid = jo_inventoryproductrel.productid
				LEFT JOIN jo_service ON jo_service.serviceid = jo_inventoryproductrel.productid
				LEFT JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_purchaseorder.contactid
				LEFT JOIN jo_vendor ON jo_vendor.vendorid = jo_purchaseorder.vendorid
				LEFT JOIN jo_currency_info ON jo_currency_info.id = jo_purchaseorder.currency_id
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";

		$query .= $this->getNonAdminAccessControlQuery('PurchaseOrder',$current_user);
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