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
 * $Header: /advent/projects/wesat/jo_crm/sugarcrm/modules/Potentials/Potentials.php,v 1.65 2005/04/28 08:08:27 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class Potentials extends CRMEntity {
	var $log;
	var $db;

	var $module_name="Potentials";
	var $table_name = "jo_potential";
	var $table_index= 'potentialid';

	var $tab_name = Array('jo_crmentity','jo_potential','jo_potentialscf');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_potential'=>'potentialid','jo_potentialscf'=>'potentialid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_potentialscf', 'potentialid');

	var $column_fields = Array();

	var $sortby_fields = Array('potentialname','amount','closingdate','smownerid','accountname');

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
			'Potential'=>Array('potential'=>'potentialname'),
			'Organization Name'=>Array('potential'=>'related_to'),
			'Contact Name'=>Array('potential'=>'contact_id'),
			'Sales Stage'=>Array('potential'=>'sales_stage'),
			'Amount'=>Array('potential'=>'amount'),
			'Expected Close Date'=>Array('potential'=>'closingdate'),
			'Assigned To'=>Array('crmentity','smownerid')
			);

	var $list_fields_name = Array(
			'Potential'=>'potentialname',
			'Organization Name'=>'related_to',
			'Contact Name'=>'contact_id',
			'Sales Stage'=>'sales_stage',
			'Amount'=>'amount',
			'Expected Close Date'=>'closingdate',
			'Assigned To'=>'assigned_user_id');

	var $list_link_field= 'potentialname';

	var $search_fields = Array(
			'Potential'=>Array('potential'=>'potentialname'),
			'Related To'=>Array('potential'=>'related_to'),
			'Expected Close Date'=>Array('potential'=>'closedate')
			);

	var $search_fields_name = Array(
			'Potential'=>'potentialname',
			'Related To'=>'related_to',
			'Expected Close Date'=>'closingdate'
			);

	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'potentialname');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'potentialname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'potentialname';

	var $related_module_table_index = array(
		'Contacts' => array('table_name'=>'jo_contactdetails','table_index'=>'contactid','rel_index'=>'contactid')
	);

	var $LBL_POTENTIAL_MAPPING = 'LBL_OPPORTUNITY_MAPPING';
	//var $groupTable = Array('jo_potentialgrouprelation','potentialid');
	function Potentials() {
		$this->log = LoggerManager::getLogger('potential');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Potentials');
	}

	function save_module($module)
	{
	}

	/** Function to create list query
	* @param reference variable - where condition is passed when the query is executed
	* Returns Query.
	*/
	function create_list_query($order_by, $where)
	{
		global $log,$current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
	        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        	$tab_id = getTabid("Potentials");
		$log->debug("Entering create_list_query(".$order_by.",". $where.") method ...");
		// Determine if the jo_account name is present in the where clause.
		$account_required = preg_match("/accounts\.name/", $where);

		if($account_required)
		{
			$query = "SELECT jo_potential.potentialid,  jo_potential.potentialname, jo_potential.dateclosed FROM jo_potential, jo_account ";
			$where_auto = "account.accountid = jo_potential.related_to AND jo_crmentity.deleted=0 ";
		}
		else
		{
			$query = 'SELECT jo_potential.potentialid, jo_potential.potentialname, jo_crmentity.smcreatorid, jo_potential.closingdate FROM jo_potential inner join jo_crmentity on jo_crmentity.crmid=jo_potential.potentialid LEFT JOIN jo_groups on jo_groups.groupid = jo_crmentity.smownerid left join jo_users on jo_users.id = jo_crmentity.smownerid ';
			$where_auto = ' AND jo_crmentity.deleted=0';
		}

		$query .= $this->getNonAdminAccessControlQuery('Potentials',$current_user);
		if($where != "")
			$query .= " where $where ".$where_auto;
		else
			$query .= " where ".$where_auto;
		if($order_by != "")
			$query .= " ORDER BY $order_by";

		$log->debug("Exiting create_list_query method ...");
		return $query;
	}

	/** Function to export the Opportunities records in CSV Format
	* @param reference variable - order by is passed when the query is executed
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Potentials Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Potentials", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT $fields_list,case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
				FROM jo_potential
				inner join jo_crmentity on jo_crmentity.crmid=jo_potential.potentialid
				LEFT JOIN jo_users ON jo_crmentity.smownerid=jo_users.id
				LEFT JOIN jo_account on jo_potential.related_to=jo_account.accountid
				LEFT JOIN jo_contactdetails on jo_potential.contact_id=jo_contactdetails.contactid
				LEFT JOIN jo_potentialscf on jo_potentialscf.potentialid=jo_potential.potentialid
                LEFT JOIN jo_groups
        	        ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_campaign
					ON jo_campaign.campaignid = jo_potential.campaignid";

		$query .= $this->getNonAdminAccessControlQuery('Potentials',$current_user);
		$where_auto = "  jo_crmentity.deleted = 0 ";

                if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		return $query;

	}



	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		$accountid = $this->column_fields['related_to'];
		$search_string = "&fromPotential=true&acc_id=$accountid";

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab$search_string','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = 'select case when (jo_users.user_name not like "") then '.$userNameSql.' else jo_groups.groupname end as user_name,
					jo_contactdetails.accountid,jo_potential.potentialid, jo_potential.potentialname, jo_contactdetails.contactid,
					jo_contactdetails.lastname, jo_contactdetails.firstname, jo_contactdetails.title, jo_contactdetails.department,
					jo_contactdetails.email, jo_contactdetails.phone, jo_crmentity.crmid, jo_crmentity.smownerid,
					jo_crmentity.modifiedtime , jo_account.accountname from jo_potential
					left join jo_contpotentialrel on jo_contpotentialrel.potentialid = jo_potential.potentialid
					inner join jo_contactdetails on ((jo_contactdetails.contactid = jo_contpotentialrel.contactid) or (jo_contactdetails.contactid = jo_potential.contact_id))
					INNER JOIN jo_contactaddress ON jo_contactdetails.contactid = jo_contactaddress.contactaddressid
					INNER JOIN jo_contactsubdetails ON jo_contactdetails.contactid = jo_contactsubdetails.contactsubscriptionid
					INNER JOIN jo_customerdetails ON jo_contactdetails.contactid = jo_customerdetails.customerid
					INNER JOIN jo_contactscf ON jo_contactdetails.contactid = jo_contactscf.contactid
					inner join jo_crmentity on jo_crmentity.crmid = jo_contactdetails.contactid
					left join jo_account on jo_account.accountid = jo_contactdetails.accountid
					left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
					left join jo_users on jo_crmentity.smownerid=jo_users.id
					where jo_potential.potentialid = '.$id.' and jo_crmentity.deleted=0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns a list of the associated calls
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
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
				if(getFieldVisibilityPermission('Events',$current_user->id,'parent_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_activity.activityid as 'tmp_activity_id',jo_activity.*,jo_seactivityrel.crmid as parent_id, jo_contactdetails.lastname,jo_contactdetails.firstname,
					jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.modifiedtime,
					case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
					jo_recurringevents.recurringtype from jo_activity
					inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
					inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
					left join jo_cntactivityrel on jo_cntactivityrel.activityid = jo_activity.activityid
					left join jo_contactdetails on jo_contactdetails.contactid = jo_cntactivityrel.contactid
					inner join jo_potential on jo_potential.potentialid=jo_seactivityrel.crmid
					left join jo_users on jo_users.id=jo_crmentity.smownerid
					left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
					left outer join jo_recurringevents on jo_recurringevents.activityid=jo_activity.activityid
					where jo_seactivityrel.crmid=".$id." and jo_crmentity.deleted=0
					and ((jo_activity.activitytype='Task' and jo_activity.status not in ('Completed','Deferred'))
					or (jo_activity.activitytype NOT in ('Emails','Task') and  jo_activity.eventstatus not in ('','Held'))) ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	 /**
	 * Function to get Contact related Products
	 * @param  integer   $id  - contactid
	 * returns related Products record in array format
	 */
	function get_products($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_products(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

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

		$query = "SELECT jo_products.productid, jo_products.productname, jo_products.productcode,
				jo_products.commissionrate, jo_products.qty_per_unit, jo_products.unit_price,
				jo_crmentity.crmid, jo_crmentity.smownerid
				FROM jo_products
				INNER JOIN jo_seproductsrel ON jo_products.productid = jo_seproductsrel.productid and jo_seproductsrel.setype = 'Potentials'
				INNER JOIN jo_productcf
				ON jo_products.productid = jo_productcf.productid
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_products.productid
				INNER JOIN jo_potential ON jo_potential.potentialid = jo_seproductsrel.crmid
				LEFT JOIN jo_users
					ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups
					ON jo_groups.groupid = jo_crmentity.smownerid
				WHERE jo_crmentity.deleted = 0 AND jo_potential.potentialid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/**	Function used to get the Sales Stage history of the Potential
	 *	@param $id - potentialid
	 *	return $return_data - array with header and the entries in format Array('header'=>$header,'entries'=>$entries_list) where as $header and $entries_list are array which contains all the column values of an row
	 */
	function get_stage_history($id)
	{
		global $log;
		$log->debug("Entering get_stage_history(".$id.") method ...");

		global $adb;
		global $mod_strings;
		global $app_strings;

		$query = 'select jo_potstagehistory.*, jo_potential.potentialname from jo_potstagehistory inner join jo_potential on jo_potential.potentialid = jo_potstagehistory.potentialid inner join jo_crmentity on jo_crmentity.crmid = jo_potential.potentialid where jo_crmentity.deleted = 0 and jo_potential.potentialid = ?';
		$result=$adb->pquery($query, array($id));
		$noofrows = $adb->num_rows($result);

		$header[] = $app_strings['LBL_AMOUNT'];
		$header[] = $app_strings['LBL_SALES_STAGE'];
		$header[] = $app_strings['LBL_PROBABILITY'];
		$header[] = $app_strings['LBL_CLOSE_DATE'];
		$header[] = $app_strings['LBL_LAST_MODIFIED'];

		//Getting the field permission for the current user. 1 - Not Accessible, 0 - Accessible
		//Sales Stage, Expected Close Dates are mandatory fields. So no need to do security check to these fields.
		global $current_user;

		//If field is accessible then getFieldVisibilityPermission function will return 0 else return 1
		$amount_access = (getFieldVisibilityPermission('Potentials', $current_user->id, 'amount') != '0')? 1 : 0;
		$probability_access = (getFieldVisibilityPermission('Potentials', $current_user->id, 'probability') != '0')? 1 : 0;
		$picklistarray = getAccessPickListValues('Potentials');

		$potential_stage_array = $picklistarray['sales_stage'];
		//- ==> picklist field is not permitted in profile
		//Not Accessible - picklist is permitted in profile but picklist value is not permitted
		$error_msg = 'Not Accessible';

		while($row = $adb->fetch_array($result))
		{
			$entries = Array();

			$entries[] = ($amount_access != 1)? $row['amount'] : 0;
			$entries[] = (in_array($row['stage'], $potential_stage_array))? $row['stage']: $error_msg;
			$entries[] = ($probability_access != 1) ? $row['probability'] : 0;
			$entries[] = DateTimeField::convertToUserFormat($row['closedate']);
			$date = new DateTimeField($row['lastmodified']);
			$entries[] = $date->getDisplayDate();

			$entries_list[] = $entries;
		}

		$return_data = Array('header'=>$header,'entries'=>$entries_list);

	 	$log->debug("Exiting get_stage_history method ...");

		return $return_data;
	}

	/**
	* Function to get Potential related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
			global $log;
			$log->debug("Entering get_history(".$id.") method ...");
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
			$query = "SELECT jo_activity.activityid, jo_activity.subject, jo_activity.status,
		jo_activity.eventstatus, jo_activity.activitytype,jo_activity.date_start,
		jo_activity.due_date, jo_activity.time_start,jo_activity.time_end,
		jo_crmentity.modifiedtime, jo_crmentity.createdtime,
		jo_crmentity.description,case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
				from jo_activity
				inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				where (jo_activity.activitytype != 'Emails')
				and (jo_activity.status = 'Completed' or jo_activity.status = 'Deferred' or (jo_activity.eventstatus = 'Held' and jo_activity.eventstatus != ''))
				and jo_seactivityrel.crmid=".$id."
                                and jo_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in include/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Potentials',$query,$id);
	}


	  /**
	  * Function to get Potential related Quotes
	  * @param  integer   $id  - potentialid
	  * returns related Quotes record in array format
	  */
	function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'potential_id', 'readwrite') == '0') {
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
		$query = "select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
					jo_account.accountname, jo_crmentity.*, jo_quotes.*, jo_potential.potentialname from jo_quotes
					inner join jo_crmentity on jo_crmentity.crmid=jo_quotes.quoteid
					left outer join jo_potential on jo_potential.potentialid=jo_quotes.potentialid
					left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
                    LEFT JOIN jo_quotescf ON jo_quotescf.quoteid = jo_quotes.quoteid
					LEFT JOIN jo_quotesbillads ON jo_quotesbillads.quotebilladdressid = jo_quotes.quoteid
					LEFT JOIN jo_quotesshipads ON jo_quotesshipads.quoteshipaddressid = jo_quotes.quoteid
					left join jo_users on jo_users.id=jo_crmentity.smownerid
					LEFT join jo_account on jo_account.accountid=jo_quotes.accountid
					where jo_crmentity.deleted=0 and jo_potential.potentialid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}

	/**
	 * Function to get Potential related SalesOrder
 	 * @param  integer   $id  - potentialid
	 * returns related SalesOrder record in array format
	 */
	function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'potential_id', 'readwrite') == '0') {
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
		$query = "select jo_crmentity.*, jo_salesorder.*, jo_quotes.subject as quotename
			, jo_account.accountname, jo_potential.potentialname,case when
			(jo_users.user_name not like '') then $userNameSql else jo_groups.groupname
			end as user_name from jo_salesorder
			inner join jo_crmentity on jo_crmentity.crmid=jo_salesorder.salesorderid
			left outer join jo_quotes on jo_quotes.quoteid=jo_salesorder.quoteid
			left outer join jo_account on jo_account.accountid=jo_salesorder.accountid
			left outer join jo_potential on jo_potential.potentialid=jo_salesorder.potentialid
			left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
            LEFT JOIN jo_salesordercf ON jo_salesordercf.salesorderid = jo_salesorder.salesorderid
            LEFT JOIN jo_invoice_recurring_info ON jo_invoice_recurring_info.start_period = jo_salesorder.salesorderid
			LEFT JOIN jo_sobillads ON jo_sobillads.sobilladdressid = jo_salesorder.salesorderid
			LEFT JOIN jo_soshipads ON jo_soshipads.soshipaddressid = jo_salesorder.salesorderid
			left join jo_users on jo_users.id=jo_crmentity.smownerid
			 where jo_crmentity.deleted=0 and jo_potential.potentialid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
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

		$rel_table_arr = Array("Activities"=>"jo_seactivityrel","Contacts"=>"jo_contpotentialrel","Products"=>"jo_seproductsrel",
						"Attachments"=>"jo_seattachmentsrel","Quotes"=>"jo_quotes","SalesOrder"=>"jo_salesorder",
						"Documents"=>"jo_senotesrel");

		$tbl_field_arr = Array("jo_seactivityrel"=>"activityid","jo_contpotentialrel"=>"contactid","jo_seproductsrel"=>"productid",
						"jo_seattachmentsrel"=>"attachmentsid","jo_quotes"=>"quoteid","jo_salesorder"=>"salesorderid",
						"jo_senotesrel"=>"notesid");

		$entity_tbl_field_arr = Array("jo_seactivityrel"=>"crmid","jo_contpotentialrel"=>"potentialid","jo_seproductsrel"=>"crmid",
						"jo_seattachmentsrel"=>"crmid","jo_quotes"=>"potentialid","jo_salesorder"=>"potentialid",
						"jo_senotesrel"=>"crmid");

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
		parent::transferRelatedRecords($module, $transferEntityIds, $entityId);
		$log->debug("Exiting transferRelatedRecords...");
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryplanner){
		$matrix = $queryplanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityPotentials',array('jo_groupsPotentials','jo_usersPotentials','jo_lastModifiedByPotentials'));

		if (!$queryplanner->requireTable("jo_potential",$matrix)){
			return '';
		}
        $matrix->setDependency('jo_potential', array('jo_crmentityPotentials','jo_accountPotentials',
											'jo_contactdetailsPotentials','jo_campaignPotentials','jo_potentialscf'));

		$query = $this->getRelationQuery($module,$secmodule,"jo_potential","potentialid", $queryplanner);

		if ($queryplanner->requireTable("jo_crmentityPotentials",$matrix)){
			$query .= " left join jo_crmentity as jo_crmentityPotentials on jo_crmentityPotentials.crmid=jo_potential.potentialid and jo_crmentityPotentials.deleted=0";
		}
		if ($queryplanner->requireTable("jo_accountPotentials")){
			$query .= " left join jo_account as jo_accountPotentials on jo_potential.related_to = jo_accountPotentials.accountid";
		}
		if ($queryplanner->requireTable("jo_contactdetailsPotentials")){
			$query .= " left join jo_contactdetails as jo_contactdetailsPotentials on jo_potential.contact_id = jo_contactdetailsPotentials.contactid";
		}
		if ($queryplanner->requireTable("jo_potentialscf")){
			$query .= " left join jo_potentialscf on jo_potentialscf.potentialid = jo_potential.potentialid";
		}
		if ($queryplanner->requireTable("jo_groupsPotentials")){
			$query .= " left join jo_groups jo_groupsPotentials on jo_groupsPotentials.groupid = jo_crmentityPotentials.smownerid";
		}
		if ($queryplanner->requireTable("jo_usersPotentials")){
			$query .= " left join jo_users as jo_usersPotentials on jo_usersPotentials.id = jo_crmentityPotentials.smownerid";
		}
		if ($queryplanner->requireTable("jo_campaignPotentials")){
			$query .= " left join jo_campaign as jo_campaignPotentials on jo_potential.campaignid = jo_campaignPotentials.campaignid";
		}
		if ($queryplanner->requireTable("jo_lastModifiedByPotentials")){
			$query .= " left join jo_users as jo_lastModifiedByPotentials on jo_lastModifiedByPotentials.id = jo_crmentityPotentials.modifiedby ";
		}
        if ($queryplanner->requireTable("jo_createdbyPotentials")){
			$query .= " left join jo_users as jo_createdbyPotentials on jo_createdbyPotentials.id = jo_crmentityPotentials.smcreatorid ";
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
			"Calendar" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_potential"=>"potentialid"),
			"Products" => array("jo_seproductsrel"=>array("crmid","productid"),"jo_potential"=>"potentialid"),
			"Quotes" => array("jo_quotes"=>array("potentialid","quoteid"),"jo_potential"=>"potentialid"),
			"SalesOrder" => array("jo_salesorder"=>array("potentialid","salesorderid"),"jo_potential"=>"potentialid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_potential"=>"potentialid"),
			"Accounts" => array("jo_potential"=>array("potentialid","related_to")),
			"Contacts" => array("jo_potential"=>array("potentialid","contact_id")),
            "Emails" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_potential"=>"potentialid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;
		/*//Backup Activity-Potentials Relation
		$act_q = "select activityid from jo_seactivityrel where crmid = ?";
		$act_res = $this->db->pquery($act_q, array($id));
		if ($this->db->num_rows($act_res) > 0) {
			for($k=0;$k < $this->db->num_rows($act_res);$k++)
			{
				$act_id = $this->db->query_result($act_res,$k,"activityid");
				$params = array($id, RB_RECORD_DELETED, 'jo_seactivityrel', 'crmid', 'activityid', $act_id);
				$this->db->pquery("insert into jo_relatedlists_rb values (?,?,?,?,?,?)", $params);
			}
		}
		$sql = 'delete from jo_seactivityrel where crmid = ?';
		$this->db->pquery($sql, array($id));*/

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$this->trash($this->module_name, $id);
		} elseif($return_module == 'Campaigns') {
			$sql = 'UPDATE jo_potential SET campaignid = ? WHERE potentialid = ?';
			$this->db->pquery($sql, array(null, $id));
		} elseif($return_module == 'Products') {
			$sql = 'DELETE FROM jo_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Contacts') {
			$sql = 'DELETE FROM jo_contpotentialrel WHERE potentialid=? AND contactid=?';
			$this->db->pquery($sql, array($id, $return_id));
			
			//If contact related to potential through edit of record,that entry will be present in
			//jo_potential contact_id column,which should be set to zero
			$sql = 'UPDATE jo_potential SET contact_id = ? WHERE potentialid=? AND contact_id=?';
			$this->db->pquery($sql, array(0,$id, $return_id));

			// Potential directly linked with Contact (not through Account - jo_contpotentialrel)
			$directRelCheck = $this->db->pquery('SELECT related_to FROM jo_potential WHERE potentialid=? AND contact_id=?', array($id, $return_id));
			if($this->db->num_rows($directRelCheck)) {
				$this->trash($this->module_name, $id);
			}
		} elseif($return_module == 'Documents') {
            $sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
            $this->db->pquery($sql, array($id, $return_id));
        } else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids, $otherParams = array()) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Contacts') { //When we select contact from potential related list
				$sql = "insert into jo_contpotentialrel values (?,?)";
				$adb->pquery($sql, array($with_crmid, $crmid));

			} elseif($with_module == 'Products') {//when we select product from potential related list
				$sql = 'INSERT INTO jo_seproductsrel VALUES(?,?,?,?)';
				$adb->pquery($sql, array($crmid, $with_crmid,'Potentials', 1));

			} else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}
    
    function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $currentModule;
        $related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
        vtlib_setup_modulevars($related_module, $other);

        $returnset = '&return_module='.$currentModule.'&return_action=CallRelatedList&return_id='.$id;

		$button = '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT CASE WHEN (jo_users.user_name NOT LIKE '') THEN $userNameSql ELSE jo_groups.groupname END AS user_name,
                jo_activity.activityid, jo_activity.subject, jo_activity.activitytype, jo_crmentity.modifiedtime,
                jo_crmentity.crmid, jo_crmentity.smownerid, jo_activity.date_start, jo_activity.time_start,
                jo_seactivityrel.crmid as parent_id FROM jo_activity, jo_seactivityrel, jo_potential, jo_users,
                jo_crmentity LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid WHERE 
                jo_seactivityrel.activityid = jo_activity.activityid AND 
                jo_potential.potentialid = jo_seactivityrel.crmid AND jo_users.id = jo_crmentity.smownerid
                AND jo_crmentity.crmid = jo_activity.activityid  AND jo_potential.potentialid = $id AND
                jo_activity.activitytype = 'Emails' AND jo_crmentity.deleted = 0";

		$return_value = GetRelatedList($currentModule, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		return $return_value;
	}

	/**
	 * Invoked when special actions are to be performed on the module.
	 * @param String Module name
	 * @param String Event Type
	 */
	function vtlib_handler($moduleName, $eventType) {
		if ($moduleName == 'Potentials') {
			$db = PearDatabase::getInstance();
			if ($eventType == 'module.disabled') {
				$db->pquery('UPDATE jo_settings_field SET active=1 WHERE name=?', array($this->LBL_POTENTIAL_MAPPING));
			} else if ($eventType == 'module.enabled') {
				$db->pquery('UPDATE jo_settings_field SET active=0 WHERE name=?', array($this->LBL_POTENTIAL_MAPPING));
			}
		}
	}
}

?>