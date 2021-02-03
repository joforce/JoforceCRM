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
class Vendors extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "jo_vendor";
	var $table_index= 'vendorid';
	var $tab_name = Array('jo_crmentity','jo_vendor','jo_vendorcf');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_vendor'=>'vendorid','jo_vendorcf'=>'vendorid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_vendorcf', 'vendorid');
	var $column_fields = Array();

        //Pavani: Assign value to entity_table
        var $entity_table = "jo_crmentity";
        var $sortby_fields = Array('vendorname','category');

        // This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone'),
                                'Email'=>Array('vendor'=>'email'),
                                'Category'=>Array('vendor'=>'category')
                                );
        var $list_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone',
                                        'Email'=>'email',
                                        'Category'=>'category'
                                     );
        var $list_link_field= 'vendorname';

	var $search_fields = Array(
                                'Vendor Name'=>Array('vendor'=>'vendorname'),
                                'Phone'=>Array('vendor'=>'phone')
                                );
        var $search_fields_name = Array(
                                        'Vendor Name'=>'vendorname',
                                        'Phone'=>'phone'
                                     );
	//Specifying required fields for vendors
        var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('createdtime', 'modifiedtime', 'vendorname', 'assigned_user_id');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'vendorname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'vendorname';

	/**	Constructor which will set the column_fields in this object
	 */
	function Vendors() {
		$this->log =LoggerManager::getLogger('vendor');
		$this->log->debug("Entering Vendors() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Vendors');
		$this->log->debug("Exiting Vendor method ...");
	}

	function save_module($module)
	{
	}

	/**	function used to get the list of products which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.parent_id.value=\"\";' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>";
			}
		}

		$query = "SELECT jo_products.productid, jo_products.productname, jo_products.productcode,
					jo_products.commissionrate, jo_products.qty_per_unit, jo_products.unit_price,
					jo_crmentity.crmid, jo_crmentity.smownerid,jo_vendor.vendorname
			  		FROM jo_products
			  		INNER JOIN jo_vendor ON jo_vendor.vendorid = jo_products.vendor_id
			  		INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_products.productid INNER JOIN jo_productcf
				    ON jo_products.productid = jo_productcf.productid
					LEFT JOIN jo_users
						ON jo_users.id=jo_crmentity.smownerid
					LEFT JOIN jo_groups
						ON jo_groups.groupid = jo_crmentity.smownerid
			  		WHERE jo_crmentity.deleted = 0 AND jo_vendor.vendorid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**	function used to get the list of purchase orders which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_purchase_orders($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_crmentity.*, jo_purchaseorder.*,jo_vendor.vendorname from jo_purchaseorder inner join jo_crmentity on jo_crmentity.crmid=jo_purchaseorder.purchaseorderid left outer join jo_vendor on jo_purchaseorder.vendorid=jo_vendor.vendorid LEFT JOIN jo_purchaseordercf ON jo_purchaseordercf.purchaseorderid = jo_purchaseorder.purchaseorderid LEFT JOIN jo_pobillads ON jo_pobillads.pobilladdressid = jo_purchaseorder.purchaseorderid LEFT JOIN jo_poshipads ON jo_poshipads.poshipaddressid = jo_purchaseorder.purchaseorderid  left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid left join jo_users on jo_users.id=jo_crmentity.smownerid where jo_crmentity.deleted=0 and jo_purchaseorder.vendorid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
	}
	//Pavani: Function to create, export query for vendors module
        /** Function to export the vendors in CSV Format
        * @param reference variable - where condition is passed when the query is executed
        * Returns Export Vendors Query.
        */
        function create_export_query($where)
        {
                global $log;
                global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

                include("includes/utils/ExportUtils.php");

                //To get the Permitted fields query and the permitted fields list
                $sql = getPermittedFieldsQuery("Vendors", "detail_view");
                $fields_list = getFieldsListFromQuery($sql);

                $query = "SELECT $fields_list FROM ".$this->entity_table."
                                INNER JOIN jo_vendor
                                        ON jo_crmentity.crmid = jo_vendor.vendorid
                                LEFT JOIN jo_vendorcf
                                        ON jo_vendorcf.vendorid=jo_vendor.vendorid
                                LEFT JOIN jo_seattachmentsrel
                                        ON jo_vendor.vendorid=jo_seattachmentsrel.crmid
                                LEFT JOIN jo_attachments
                                ON jo_seattachmentsrel.attachmentsid = jo_attachments.attachmentsid
                                LEFT JOIN jo_users
                                        ON jo_crmentity.smownerid = jo_users.id and jo_users.status='Active'
                                ";
                $where_auto = " jo_crmentity.deleted = 0 ";

                 if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

                $log->debug("Exiting create_export_query method ...");
                return $query;
        }

	/**	function used to get the list of contacts which are related to the vendor
	 *	@param int $id - vendor id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_contactdetails.*, jo_crmentity.crmid, jo_crmentity.smownerid,jo_vendorcontactrel.vendorid,jo_account.accountname from jo_contactdetails
				inner join jo_crmentity on jo_crmentity.crmid = jo_contactdetails.contactid
				inner join jo_vendorcontactrel on jo_vendorcontactrel.contactid=jo_contactdetails.contactid
				INNER JOIN jo_contactaddress ON jo_contactdetails.contactid = jo_contactaddress.contactaddressid
				INNER JOIN jo_contactsubdetails ON jo_contactdetails.contactid = jo_contactsubdetails.contactsubscriptionid
				INNER JOIN jo_customerdetails ON jo_contactdetails.contactid = jo_customerdetails.customerid
				INNER JOIN jo_contactscf ON jo_contactdetails.contactid = jo_contactscf.contactid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_account on jo_account.accountid = jo_contactdetails.accountid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				where jo_crmentity.deleted=0 and jo_vendorcontactrel.vendorid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/**
	 * Move the related records of the specified list of id's to the given record.
	 * @param String This module name
	 * @param Array List of Entity Id's from which related records need to be transfered
	 * @param Integer Id of the the Record to which the related records are to be moved
	 */
	function transferRelatedRecords($module, $transferEntityIds, $entityId) {
		global $adb,$log;
		$log->debug("Entering function transferRelatedRecords ($module, $transferEntityIds, $entityId)");

		$rel_table_arr = Array("Products"=>"jo_products","PurchaseOrder"=>"jo_purchaseorder","Contacts"=>"jo_vendorcontactrel","Emails"=>"jo_seactivityrel");

		$tbl_field_arr = Array("jo_products"=>"productid","jo_vendorcontactrel"=>"contactid","jo_purchaseorder"=>"purchaseorderid","jo_seactivityrel"=>"activityid");

		$entity_tbl_field_arr = Array("jo_products"=>"vendor_id","jo_vendorcontactrel"=>"vendorid","jo_purchaseorder"=>"vendorid","jo_seactivityrel"=>"crmid");

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
				$id_field = $tbl_field_arr[$rel_table];
				$entity_id_field = $entity_tbl_field_arr[$rel_table];
				// IN clause to avoid duplicate entries
				$sel_result =  $adb->pquery("select $id_field from $rel_table where $entity_id_field=? " .
						" and $id_field not in (select $id_field from $rel_table where $entity_id_field=?)",
						array($transferId,$entityId));
				$res_cnt = $adb->num_rows($sel_result);
				if($res_cnt > 0) {
					for($i=0;$i<$res_cnt;$i++) {
						$id_field_value = $adb->query_result($sel_result,$i,$id_field);
						$adb->pquery("update $rel_table set $entity_id_field=? where $entity_id_field=? and $id_field=?",
							array($entityId,$transferId,$id_field_value));
					}
				}
			}
		}
		$log->debug("Exiting transferRelatedRecords...");
	}

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	*/
	function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_emails(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
		checkFileAccessForInclusion("modules/$related_module/$related_module.php");
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        modlib_setup_modulevars($related_module, $other);
		$singular_modname = modlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
			jo_activity.activityid, jo_activity.subject,
			jo_activity.activitytype, jo_crmentity.modifiedtime,
			jo_crmentity.crmid, jo_crmentity.smownerid, jo_activity.date_start,jo_activity.time_start, jo_seactivityrel.crmid as parent_id
			FROM jo_activity, jo_seactivityrel, jo_vendor, jo_users, jo_crmentity
			LEFT JOIN jo_groups
				ON jo_groups.groupid=jo_crmentity.smownerid
			WHERE jo_seactivityrel.activityid = jo_activity.activityid
				AND jo_vendor.vendorid = jo_seactivityrel.crmid
				AND jo_users.id=jo_crmentity.smownerid
				AND jo_crmentity.crmid = jo_activity.activityid
				AND jo_vendor.vendorid = ".$id."
				AND jo_activity.activitytype='Emails'
				AND jo_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module, $queryPlanner) {
		$moduletable = $this->table_name;
		$moduleindex = $this->table_index;
		$modulecftable = $this->tab_name[2];
		$modulecfindex = $this->tab_name_index[$modulecftable];

		$query = "from $moduletable
			inner join $modulecftable as $modulecftable on $modulecftable.$modulecfindex=$moduletable.$moduleindex
			inner join jo_crmentity on jo_crmentity.crmid=$moduletable.$moduleindex
			left join jo_groups as jo_groups$module on jo_groups$module.groupid = jo_crmentity.smownerid
			left join jo_users as jo_users".$module." on jo_users".$module.".id = jo_crmentity.smownerid
			left join jo_groups on jo_groups.groupid = jo_crmentity.smownerid
			left join jo_users on jo_users.id = jo_crmentity.smownerid 
            left join jo_users as jo_createdby".$module." on jo_createdby".$module.".id = jo_crmentity.smcreatorid 
			left join jo_users as jo_lastModifiedByVendors on jo_lastModifiedByVendors.id = jo_crmentity.modifiedby ";
		return $query;
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule, $queryplanner) {

		$matrix = $queryplanner->newDependencyMatrix();

		$matrix->setDependency("jo_crmentityVendors",array("jo_usersVendors","jo_lastModifiedByVendors"));
		if (!$queryplanner->requireTable('jo_vendor', $matrix)) {
			return '';
		}
        $matrix->setDependency("jo_vendor",array("jo_crmentityVendors","jo_vendorcf","jo_email_trackVendors"));
		$query = $this->getRelationQuery($module,$secmodule,"jo_vendor","vendorid", $queryplanner);
		// TODO Support query planner
		if ($queryplanner->requireTable("jo_crmentityVendors",$matrix)){
		    $query .=" left join jo_crmentity as jo_crmentityVendors on jo_crmentityVendors.crmid=jo_vendor.vendorid and jo_crmentityVendors.deleted=0";
		}
		if ($queryplanner->requireTable("jo_vendorcf")){
		    $query .=" left join jo_vendorcf on jo_vendorcf.vendorid = jo_crmentityVendors.crmid";
		}
		if ($queryplanner->requireTable("jo_email_trackVendors")){
		    $query .=" LEFT JOIN jo_email_track AS jo_email_trackVendors ON jo_email_trackVendors.crmid = jo_vendor.vendorid";
		}
		if ($queryplanner->requireTable("jo_usersVendors")){
		    $query .=" left join jo_users as jo_usersVendors on jo_usersVendors.id = jo_crmentityVendors.smownerid";
		}
		if ($queryplanner->requireTable("jo_lastModifiedByVendors")){
		    $query .=" left join jo_users as jo_lastModifiedByVendors on jo_lastModifiedByVendors.id = jo_crmentityVendors.modifiedby ";
		}
        if ($queryplanner->requireTable("jo_createdbyVendors")){
			$query .= " left join jo_users as jo_createdbyVendors on jo_createdbyVendors.id = jo_crmentityVendors.smcreatorid ";
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
			"Products" =>array("jo_products"=>array("vendor_id","productid"),"jo_vendor"=>"vendorid"),
			"PurchaseOrder" =>array("jo_purchaseorder"=>array("vendorid","purchaseorderid"),"jo_vendor"=>"vendorid"),
			"Contacts" =>array("jo_vendorcontactrel"=>array("vendorid","contactid"),"jo_vendor"=>"vendorid"),
			"Emails" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_vendor"=>"vendorid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		//Deleting Vendor related PO.
		$po_q = 'SELECT jo_crmentity.crmid FROM jo_crmentity
			INNER JOIN jo_purchaseorder ON jo_crmentity.crmid=jo_purchaseorder.purchaseorderid
			INNER JOIN jo_vendor ON jo_vendor.vendorid=jo_purchaseorder.vendorid
			WHERE jo_crmentity.deleted=0 AND jo_purchaseorder.vendorid=?';
		$po_res = $this->db->pquery($po_q, array($id));
		$po_ids_list = array();
		for($k=0;$k < $this->db->num_rows($po_res);$k++)
		{
			$po_id = $this->db->query_result($po_res,$k,"crmid");
			$po_ids_list[] = $po_id;
			$sql = 'UPDATE jo_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($po_id));
		}
		//Backup deleted Vendors related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'jo_crmentity', 'deleted', 'crmid', implode(",", $po_ids_list));
		$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);

		//Backup Product-Vendor Relation
		$pro_q = 'SELECT productid FROM jo_products WHERE vendor_id=?';
		$pro_res = $this->db->pquery($pro_q, array($id));
		if ($this->db->num_rows($pro_res) > 0) {
			$pro_ids_list = array();
			for($k=0;$k < $this->db->num_rows($pro_res);$k++)
			{
				$pro_ids_list[] = $this->db->query_result($pro_res,$k,"productid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_products', 'vendor_id', 'productid', implode(",", $pro_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//Deleting Product-Vendor Relation.
		$pro_q = 'UPDATE jo_products SET vendor_id = 0 WHERE vendor_id = ?';
		$this->db->pquery($pro_q, array($id));

		/*//Backup Contact-Vendor Relaton
		$con_q = 'SELECT contactid FROM jo_vendorcontactrel WHERE vendorid = ?';
		$con_res = $this->db->pquery($con_q, array($id));
		if ($this->db->num_rows($con_res) > 0) {
			for($k=0;$k < $this->db->num_rows($con_res);$k++)
			{
				$con_id = $this->db->query_result($con_res,$k,"contactid");
				$params = array($id, RB_RECORD_DELETED, 'jo_vendorcontactrel', 'vendorid', 'contactid', $con_id);
				$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		//Deleting Contact-Vendor Relaton
		$vc_sql = 'DELETE FROM jo_vendorcontactrel WHERE vendorid=?';
		$this->db->pquery($vc_sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids, $otherParams = array()) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Contacts')
				$adb->pquery("insert into jo_vendorcontactrel values (?,?)", array($crmid, $with_crmid));
			elseif($with_module == 'Products')
				$adb->pquery("update jo_products set vendor_id=? where productid=?", array($crmid, $with_crmid));
			else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		if($return_module == 'Contacts') {
			$sql = 'DELETE FROM jo_vendorcontactrel WHERE vendorid=? AND contactid=?';
			$this->db->pquery($sql, array($id,$return_id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

}
?>
