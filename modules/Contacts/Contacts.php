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
 * $Header: /advent/projects/wesat/jo_crm/sugarcrm/modules/Contacts/Contacts.php,v 1.70 2005/04/27 11:21:49 rank Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/
// Contact is used to store customer information.
class Contacts extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "jo_contactdetails";
	var $table_index= 'contactid';
	var $tab_name = Array('jo_crmentity','jo_contactdetails','jo_contactaddress','jo_contactsubdetails','jo_contactscf','jo_customerdetails');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_contactdetails'=>'contactid','jo_contactaddress'=>'contactaddressid','jo_contactsubdetails'=>'contactsubscriptionid','jo_contactscf'=>'contactid','jo_customerdetails'=>'customerid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_contactscf', 'contactid');

	var $column_fields = Array();

	var $sortby_fields = Array('lastname','firstname','title','email','phone','smownerid','accountname');

	var $list_link_field= 'lastname';

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
	'First Name' => Array('contactdetails'=>'firstname'),
	'Last Name' => Array('contactdetails'=>'lastname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name' => Array('account'=>'accountid'),
	'Email' => Array('contactdetails'=>'email'),
	'Office Phone' => Array('contactdetails'=>'phone'),
	'Assigned To' => Array('crmentity'=>'smownerid')
	);

	var $range_fields = Array(
		'first_name',
		'last_name',
		'primary_address_city',
		'account_name',
		'account_id',
		'id',
		'email1',
		'salutation',
		'title',
		'phone_mobile',
		'reports_to_name',
		'primary_address_street',
		'primary_address_city',
		'primary_address_state',
		'primary_address_postalcode',
		'primary_address_country',
		'alt_address_city',
		'alt_address_street',
		'alt_address_city',
		'alt_address_state',
		'alt_address_postalcode',
		'alt_address_country',
		'office_phone',
		'home_phone',
		'other_phone',
		'fax',
		'department',
		'birthdate',
		'assistant_name',
		'assistant_phone');


	var $list_fields_name = Array(
	'First Name' => 'firstname',
	'Last Name' => 'lastname',
	'Title' => 'title',
	'Account Name' => 'account_id',
	'Email' => 'email',
	'Office Phone' => 'phone',
	'Assigned To' => 'assigned_user_id'
	);

	var $search_fields = Array(
	'First Name' => Array('contactdetails'=>'firstname'),
	'Last Name' => Array('contactdetails'=>'lastname'),
	'Title' => Array('contactdetails'=>'title'),
	'Account Name'=>Array('contactdetails'=>'account_id'),
	'Assigned To'=>Array('crmentity'=>'smownerid'),
		);

	var $search_fields_name = Array(
	'First Name' => 'firstname',
	'Last Name' => 'lastname',
	'Title' => 'title',
	'Account Name'=>'account_id',
	'Assigned To'=>'assigned_user_id'
	);

	// This is the list of jo_fields that are required
	var $required_fields =  array("lastname"=>1);

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id','lastname','createdtime' ,'modifiedtime');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','salutation','title','email','department','phone','mobile','support_start_date','support_end_date');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'lastname';

	var $related_module_table_index = array(
		'Potentials' => array('table_name' => 'jo_potential', 'table_index' => 'potentialid', 'rel_index' => 'contact_id'),
		'Quotes' => array('table_name' => 'jo_quotes', 'table_index' => 'quoteid', 'rel_index' => 'contactid'),
		'SalesOrder' => array('table_name' => 'jo_salesorder', 'table_index' => 'salesorderid', 'rel_index' => 'contactid'),
		'PurchaseOrder' => array('table_name' => 'jo_purchaseorder', 'table_index' => 'purchaseorderid', 'rel_index' => 'contactid'),
		'Invoice' => array('table_name' => 'jo_invoice', 'table_index' => 'invoiceid', 'rel_index' => 'contactid'),
		'HelpDesk' => array('table_name' => 'jo_troubletickets', 'table_index' => 'ticketid', 'rel_index' => 'contact_id'),
		'Products' => array('table_name' => 'jo_seproductsrel', 'table_index' => 'productid', 'rel_index' => 'crmid'),
		'Calendar' => array('table_name' => 'jo_cntactivityrel', 'table_index' => 'activityid', 'rel_index' => 'contactid'),
		'Documents' => array('table_name' => 'jo_senotesrel', 'table_index' => 'notesid', 'rel_index' => 'crmid'),
		'ServiceContracts' => array('table_name' => 'jo_servicecontracts', 'table_index' => 'servicecontractsid', 'rel_index' => 'sc_related_to'),
		'Services' => array('table_name' => 'jo_crmentityrel', 'table_index' => 'crmid', 'rel_index' => 'crmid'),
		'Campaigns' => array('table_name' => 'jo_campaigncontrel', 'table_index' => 'campaignid', 'rel_index' => 'contactid'),
		'Assets' => array('table_name' => 'jo_assets', 'table_index' => 'assetsid', 'rel_index' => 'contact'),
		'Project' => array('table_name' => 'jo_project', 'table_index' => 'projectid', 'rel_index' => 'linktoaccountscontacts'),
		'Emails' => array('table_name' => 'jo_seactivityrel', 'table_index' => 'crmid', 'rel_index' => 'activityid'),
        'Vendors' => array('table_name' => 'jo_vendorcontactrel', 'table_index' => 'vendorid', 'rel_index' => 'contactid'),
	);

	function Contacts() {
		$this->log = LoggerManager::getLogger('contact');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Contacts');
	}

	// Mike Crowe Mod --------------------------------------------------------Default ordering for us
	/** Function to get the number of Contacts assigned to a particular User.
	*  @param varchar $user name - Assigned to User
	*  Returns the count of contacts assigned to user.
	*/
	function getCount($user_name)
	{
		global $log;
		$log->debug("Entering getCount(".$user_name.") method ...");
		$query = "select count(*) from jo_contactdetails  inner join jo_crmentity on jo_crmentity.crmid=jo_contactdetails.contactid inner join jo_users on jo_users.id=jo_crmentity.smownerid where user_name=? and jo_crmentity.deleted=0";
		$result = $this->db->pquery($query,array($user_name),true,"Error retrieving contacts count");
		$rows_found =  $this->db->getRowCount($result);
		$row = $this->db->fetchByAssoc($result, 0);


		$log->debug("Exiting getCount method ...");
		return $row["count(*)"];
	}

	// This function doesn't seem to be used anywhere. Need to check and remove it.
	/** Function to get the Contact Details assigned to a particular User based on the starting count and the number of subsequent records.
	*  @param varchar $user_name - Assigned User
	*  @param integer $from_index - Initial record number to be displayed
	*  @param integer $offset - Count of the subsequent records to be displayed.
	*  Returns Query.
	*/
    function get_contacts($user_name,$from_index,$offset)
    {
	global $log;
	$log->debug("Entering get_contacts(".$user_name.",".$from_index.",".$offset.") method ...");
      $query = "select jo_users.user_name,jo_groups.groupname,jo_contactdetails.department department, jo_contactdetails.phone office_phone, jo_contactdetails.fax fax, jo_contactsubdetails.assistant assistant_name, jo_contactsubdetails.otherphone other_phone, jo_contactsubdetails.homephone home_phone,jo_contactsubdetails.birthday birthdate, jo_contactdetails.lastname last_name,jo_contactdetails.firstname first_name,jo_contactdetails.contactid as id, jo_contactdetails.salutation as salutation, jo_contactdetails.email as email1,jo_contactdetails.title as title,jo_contactdetails.mobile as phone_mobile,jo_account.accountname as account_name,jo_account.accountid as account_id, jo_contactaddress.mailingcity as primary_address_city,jo_contactaddress.mailingstreet as primary_address_street, jo_contactaddress.mailingcountry as primary_address_country,jo_contactaddress.mailingstate as primary_address_state, jo_contactaddress.mailingzip as primary_address_postalcode,   jo_contactaddress.othercity as alt_address_city,jo_contactaddress.otherstreet as alt_address_street, jo_contactaddress.othercountry as alt_address_country,jo_contactaddress.otherstate as alt_address_state, jo_contactaddress.otherzip as alt_address_postalcode  from jo_contactdetails inner join jo_crmentity on jo_crmentity.crmid=jo_contactdetails.contactid inner join jo_users on jo_users.id=jo_crmentity.smownerid left join jo_account on jo_account.accountid=jo_contactdetails.accountid left join jo_contactaddress on jo_contactaddress.contactaddressid=jo_contactdetails.contactid left join jo_contactsubdetails on jo_contactsubdetails.contactsubscriptionid = jo_contactdetails.contactid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid left join jo_users on jo_crmentity.smownerid=jo_users.id where user_name='" .$user_name ."' and jo_crmentity.deleted=0 limit " .$from_index ."," .$offset;

	$log->debug("Exiting get_contacts method ...");
      return $this->process_list_query1($query);
    }


    /** Function to process list query for a given query
    *  @param $query
    *  Returns the results of query in array format
    */
    function process_list_query1($query)
    {
	global $log;
	$log->debug("Entering process_list_query1(".$query.") method ...");

        $result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
        $list = Array();
        $rows_found =  $this->db->getRowCount($result);
        if($rows_found != 0)
        {
		   $contact = Array();
               for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))

             {
                foreach($this->range_fields as $columnName)
                {
                    if (isset($row[$columnName])) {

                        $contact[$columnName] = $row[$columnName];
                    }
                    else
                    {
                            $contact[$columnName] = "";
                    }
	     }
// TODO OPTIMIZE THE QUERY ACCOUNT NAME AND ID are set separetly for every jo_contactdetails and hence
// jo_account query goes for ecery single jo_account row

                    $list[] = $contact;
                }
        }

        $response = Array();
        $response['list'] = $list;
        $response['row_count'] = $rows_found;
        $response['next_offset'] = $next_offset;
        $response['previous_offset'] = $previous_offset;


	$log->debug("Exiting process_list_query1 method ...");
        return $response;
    }


	/** Returns a list of the associated opportunities
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	*/
	function get_opportunities($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_opportunities(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_action.value=\"updateRelations\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		// Should Opportunities be listed on Secondary Contacts ignoring the boundaries of Organization.
		// Useful when the Reseller are working to gain Potential for other Organization.
		$ignoreOrganizationCheck = true;

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query ='select case when (jo_users.user_name not like "") then '.$userNameSql.' else jo_groups.groupname end as user_name,
		jo_contactdetails.accountid, jo_contactdetails.contactid , jo_potential.potentialid, jo_potential.potentialname,
		jo_potential.potentialtype, jo_potential.sales_stage, jo_potential.amount, jo_potential.closingdate,
		jo_potential.related_to, jo_potential.contact_id, jo_crmentity.crmid, jo_crmentity.smownerid, jo_account.accountname
		from jo_contactdetails
		left join jo_contpotentialrel on jo_contpotentialrel.contactid=jo_contactdetails.contactid
		left join jo_potential on (jo_potential.potentialid = jo_contpotentialrel.potentialid or jo_potential.contact_id=jo_contactdetails.contactid)
		inner join jo_crmentity on jo_crmentity.crmid = jo_potential.potentialid
		left join jo_account on jo_account.accountid=jo_contactdetails.accountid
		LEFT JOIN jo_potentialscf ON jo_potential.potentialid = jo_potentialscf.potentialid
		left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
		left join jo_users on jo_users.id=jo_crmentity.smownerid
		where  jo_crmentity.deleted=0 and jo_contactdetails.contactid ='.$id;

		if (!$ignoreOrganizationCheck) {
			// Restrict the scope of listing to only related contacts of the organization linked to potential via related_to of Potential
			$query .= ' and (jo_contactdetails.accountid = jo_potential.related_to or jo_contactdetails.contactid=jo_potential.contact_id)';
		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}


	/** Returns a list of the associated tasks
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
 * Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
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
				if(getFieldVisibilityPermission('Calendar',$current_user->id,'contact_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Task\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_TODO', $related_module) ."'>&nbsp;";
				}
				if(getFieldVisibilityPermission('Events',$current_user->id,'contact_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name," .
				" jo_contactdetails.lastname, jo_contactdetails.firstname,  jo_activity.activityid ," .
				" jo_activity.subject, jo_activity.activitytype, jo_activity.date_start, jo_activity.due_date," .
				" jo_activity.time_start,jo_activity.time_end, jo_cntactivityrel.contactid, jo_crmentity.crmid," .
				" jo_crmentity.smownerid, jo_crmentity.modifiedtime, jo_recurringevents.recurringtype," .
				" case when (jo_activity.activitytype = 'Task') then jo_activity.status else jo_activity.eventstatus end as status, " .
				" jo_seactivityrel.crmid as parent_id " .
				" from jo_contactdetails " .
				" inner join jo_cntactivityrel on jo_cntactivityrel.contactid = jo_contactdetails.contactid" .
				" inner join jo_activity on jo_cntactivityrel.activityid=jo_activity.activityid" .
				" inner join jo_crmentity on jo_crmentity.crmid = jo_cntactivityrel.activityid " .
				" left join jo_seactivityrel on jo_seactivityrel.activityid = jo_cntactivityrel.activityid " .
				" left join jo_users on jo_users.id=jo_crmentity.smownerid" .
				" left outer join jo_recurringevents on jo_recurringevents.activityid=jo_activity.activityid" .
				" left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid" .
				" where jo_contactdetails.contactid=".$id." and jo_crmentity.deleted = 0" .
						" and ((jo_activity.activitytype='Task' and jo_activity.status not in ('Completed','Deferred'))" .
						" or (jo_activity.activitytype Not in ('Emails','Task') and  jo_activity.eventstatus not in ('','Held')))";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}
	/**
	* Function to get Contact related Task & Event which have activity type Held, Completed or Deferred.
	* @param  integer   $id      - contactid
	* returns related Task or Event record in array format
	*/
	function get_history($id)
	{
		global $log;
		$log->debug("Entering get_history(".$id.") method ...");
		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_activity.activityid, jo_activity.subject, jo_activity.status
			, jo_activity.eventstatus,jo_activity.activitytype, jo_activity.date_start,
			jo_activity.due_date,jo_activity.time_start,jo_activity.time_end,
			jo_contactdetails.contactid, jo_contactdetails.firstname,
			jo_contactdetails.lastname, jo_crmentity.modifiedtime,
			jo_crmentity.createdtime, jo_crmentity.description,jo_crmentity.crmid,
			case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
				from jo_activity
				inner join jo_cntactivityrel on jo_cntactivityrel.activityid= jo_activity.activityid
				inner join jo_contactdetails on jo_contactdetails.contactid= jo_cntactivityrel.contactid
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
				left join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
                left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				where (jo_activity.activitytype != 'Emails')
				and (jo_activity.status = 'Completed' or jo_activity.status = 'Deferred' or (jo_activity.eventstatus = 'Held' and jo_activity.eventstatus != ''))
				and jo_cntactivityrel.contactid=".$id."
                                and jo_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in includes/RelatedListView.php
		$log->debug("Entering get_history method ...");
		return getHistory('Contacts',$query,$id);
	}
	/**
	* Function to get Contact related Tickets.
	* @param  integer   $id      - contactid
	* returns related Ticket records in array format
	*/
	function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_tickets(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'parent_id', 'readwrite') == '0') {
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
				jo_crmentity.crmid, jo_troubletickets.title, jo_contactdetails.contactid, jo_troubletickets.parent_id,
				jo_contactdetails.firstname, jo_contactdetails.lastname, jo_troubletickets.status, jo_troubletickets.priority,
				jo_crmentity.smownerid, jo_troubletickets.ticket_no, jo_troubletickets.contact_id
				from jo_troubletickets inner join jo_crmentity on jo_crmentity.crmid=jo_troubletickets.ticketid
				left join jo_contactdetails on jo_contactdetails.contactid=jo_troubletickets.contact_id
				LEFT JOIN jo_ticketcf ON jo_troubletickets.ticketid = jo_ticketcf.ticketid
				left join jo_users on jo_users.id=jo_crmentity.smownerid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				where jo_crmentity.deleted=0 and jo_contactdetails.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
		return $return_value;
	}
    
    /**
	  * Function to get Contact related Quotes
	  * @param  integer   $id  - contactid
	  * returns related Quotes record in array format
	  */
	 function get_quotes($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_quotes(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_crmentity.*, jo_quotes.*,jo_potential.potentialname,jo_contactdetails.lastname,jo_account.accountname from jo_quotes inner join jo_crmentity on jo_crmentity.crmid=jo_quotes.quoteid left outer join jo_contactdetails on jo_contactdetails.contactid=jo_quotes.contactid left outer join jo_potential on jo_potential.potentialid=jo_quotes.potentialid  left join jo_account on jo_account.accountid = jo_quotes.accountid LEFT JOIN jo_quotescf ON jo_quotescf.quoteid = jo_quotes.quoteid LEFT JOIN jo_quotesbillads ON jo_quotesbillads.quotebilladdressid = jo_quotes.quoteid LEFT JOIN jo_quotesshipads ON jo_quotesshipads.quoteshipaddressid = jo_quotes.quoteid left join jo_users on jo_users.id=jo_crmentity.smownerid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid where jo_crmentity.deleted=0 and jo_contactdetails.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	  }
	/**
	 * Function to get Contact related SalesOrder
 	 * @param  integer   $id  - contactid
	 * returns related SalesOrder record in array format
	 */
	 function get_salesorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_salesorder(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_crmentity.*, jo_salesorder.*, jo_quotes.subject as quotename, jo_account.accountname, jo_contactdetails.lastname from jo_salesorder inner join jo_crmentity on jo_crmentity.crmid=jo_salesorder.salesorderid LEFT JOIN jo_salesordercf ON jo_salesordercf.salesorderid = jo_salesorder.salesorderid LEFT JOIN jo_sobillads ON jo_sobillads.sobilladdressid = jo_salesorder.salesorderid LEFT JOIN jo_soshipads ON jo_soshipads.soshipaddressid = jo_salesorder.salesorderid left join jo_users on jo_users.id=jo_crmentity.smownerid left outer join jo_quotes on jo_quotes.quoteid=jo_salesorder.quoteid left outer join jo_account on jo_account.accountid=jo_salesorder.accountid LEFT JOIN jo_invoice_recurring_info ON jo_invoice_recurring_info.start_period = jo_salesorder.salesorderid left outer join jo_contactdetails on jo_contactdetails.contactid=jo_salesorder.contactid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid where jo_crmentity.deleted=0  and  jo_salesorder.contactid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
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

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		$query = 'SELECT jo_products.productid, jo_products.productname, jo_products.productcode,
		 		  jo_products.commissionrate, jo_products.qty_per_unit, jo_products.unit_price,
				  jo_crmentity.crmid, jo_crmentity.smownerid,jo_contactdetails.lastname
				FROM jo_products
				INNER JOIN jo_seproductsrel
					ON jo_seproductsrel.productid=jo_products.productid and jo_seproductsrel.setype="Contacts"
				INNER JOIN jo_productcf
					ON jo_products.productid = jo_productcf.productid
				INNER JOIN jo_crmentity
					ON jo_crmentity.crmid = jo_products.productid
				INNER JOIN jo_contactdetails
					ON jo_contactdetails.contactid = jo_seproductsrel.crmid
				LEFT JOIN jo_users
					ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups
					ON jo_groups.groupid = jo_crmentity.smownerid
			   WHERE jo_contactdetails.contactid = '.$id.' and jo_crmentity.deleted = 0';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	 }

	/**
	 * Function to get Contact related PurchaseOrder
 	 * @param  integer   $id  - contactid
	 * returns related PurchaseOrder record in array format
	 */
	 function get_purchase_orders($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchase_orders(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_crmentity.*, jo_purchaseorder.*,jo_vendor.vendorname,jo_contactdetails.lastname from jo_purchaseorder inner join jo_crmentity on jo_crmentity.crmid=jo_purchaseorder.purchaseorderid left outer join jo_vendor on jo_purchaseorder.vendorid=jo_vendor.vendorid left outer join jo_contactdetails on jo_contactdetails.contactid=jo_purchaseorder.contactid left join jo_users on jo_users.id=jo_crmentity.smownerid LEFT JOIN jo_purchaseordercf ON jo_purchaseordercf.purchaseorderid = jo_purchaseorder.purchaseorderid LEFT JOIN jo_pobillads ON jo_pobillads.pobilladdressid = jo_purchaseorder.purchaseorderid LEFT JOIN jo_poshipads ON jo_poshipads.poshipaddressid = jo_purchaseorder.purchaseorderid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid where jo_crmentity.deleted=0 and jo_purchaseorder.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchase_orders method ...");
		return $return_value;
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
        
        $relatedIds = array_merge(array($id), $this->getRelatedPotentialIds($id), $this->getRelatedTicketIds($id));
        $relatedIds = implode(', ', $relatedIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name," .
				" jo_activity.activityid, jo_activity.subject, jo_activity.activitytype, jo_crmentity.modifiedtime," .
				" jo_crmentity.crmid, jo_crmentity.smownerid, jo_activity.date_start, jo_activity.time_start, jo_seactivityrel.crmid as parent_id " .
				" from jo_activity, jo_seactivityrel, jo_contactdetails, jo_users, jo_crmentity" .
				" left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid" .
				" where jo_seactivityrel.activityid = jo_activity.activityid" .
				" and jo_seactivityrel.crmid IN ($relatedIds) and jo_users.id=jo_crmentity.smownerid" .
				" and jo_crmentity.crmid = jo_activity.activityid  and jo_contactdetails.contactid = ".$id." and" .
						" jo_activity.activitytype='Emails' and jo_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/** Returns a list of the associated Campaigns
	  * @param $id -- campaign id :: Type Integer
	  * @returns list of campaigns in array format
	  */

	function get_campaigns($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_campaigns(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
					jo_campaign.campaignid, jo_campaign.campaignname, jo_campaign.campaigntype, jo_campaign.campaignstatus,
					jo_campaign.expectedrevenue, jo_campaign.closingdate, jo_crmentity.crmid, jo_crmentity.smownerid,
					jo_crmentity.modifiedtime from jo_campaign
					inner join jo_campaigncontrel on jo_campaigncontrel.campaignid=jo_campaign.campaignid
					inner join jo_crmentity on jo_crmentity.crmid = jo_campaign.campaignid
					inner join jo_campaignscf ON jo_campaignscf.campaignid = jo_campaign.campaignid
					left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
					left join jo_users on jo_users.id = jo_crmentity.smownerid
					where jo_campaigncontrel.contactid=".$id." and jo_crmentity.deleted=0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
		return $return_value;
	}

	/**
	* Function to get Contact related Invoices
	* @param  integer   $id      - contactid
	* returns related Invoices record in array format
	*/
	function get_invoices($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_invoices(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'contact_id', 'readwrite') == '0') {
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
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
			jo_crmentity.*,
			jo_invoice.*,
			jo_contactdetails.lastname,jo_contactdetails.firstname,
			jo_salesorder.subject AS salessubject
			FROM jo_invoice
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_invoice.invoiceid
			LEFT OUTER JOIN jo_contactdetails
				ON jo_contactdetails.contactid = jo_invoice.contactid
			LEFT OUTER JOIN jo_salesorder
				ON jo_salesorder.salesorderid = jo_invoice.salesorderid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
            LEFT JOIN jo_invoicecf
                ON jo_invoicecf.invoiceid = jo_invoice.invoiceid
			LEFT JOIN jo_invoicebillads
				ON jo_invoicebillads.invoicebilladdressid = jo_invoice.invoiceid
			LEFT JOIN jo_invoiceshipads
				ON jo_invoiceshipads.invoiceshipaddressid = jo_invoice.invoiceid
			LEFT JOIN jo_users
				ON jo_crmentity.smownerid = jo_users.id
			WHERE jo_crmentity.deleted = 0
			AND jo_contactdetails.contactid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_invoices method ...");
		return $return_value;
	}

    /**
	* Function to get Contact related vendors.
	* @param  integer   $id      - contactid
	* returns related vendor records in array format
	*/
	function get_vendors($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_vendors(".$id.") method ...");
		$this_module = $currentModule;

        $related_module = modlib_getModuleNameById($rel_tab_id);
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'parent_id', 'readwrite') == '0') {
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
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_crmentity.crmid, jo_vendor.*,  jo_vendorcf.*
				from jo_vendor inner join jo_crmentity on jo_crmentity.crmid=jo_vendor.vendorid
                INNER JOIN jo_vendorcontactrel on jo_vendorcontactrel.vendorid=jo_vendor.vendorid
				LEFT JOIN jo_vendorcf on jo_vendorcf.vendorid=jo_vendor.vendorid
				LEFT JOIN jo_users on jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				WHERE jo_crmentity.deleted=0 and jo_vendorcontactrel.contactid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_vendors method ...");
		return $return_value;
	}

	/** Function to export the contact records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Contacts Query.
	*/
        function create_export_query($where)
        {
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Contacts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT jo_contactdetails.salutation as 'Salutation',$fields_list,case when (jo_users.user_name not like '') then jo_users.user_name else jo_groups.groupname end as user_name
                                FROM jo_contactdetails
                                inner join jo_crmentity on jo_crmentity.crmid=jo_contactdetails.contactid
                                LEFT JOIN jo_users ON jo_crmentity.smownerid=jo_users.id and jo_users.status='Active'
                                LEFT JOIN jo_account on jo_contactdetails.accountid=jo_account.accountid
				left join jo_contactaddress on jo_contactaddress.contactaddressid=jo_contactdetails.contactid
				left join jo_contactsubdetails on jo_contactsubdetails.contactsubscriptionid=jo_contactdetails.contactid
			        left join jo_contactscf on jo_contactscf.contactid=jo_contactdetails.contactid
			        left join jo_customerdetails on jo_customerdetails.customerid=jo_contactdetails.contactid
	                        LEFT JOIN jo_groups
                        	        ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_contactdetails jo_contactdetails2
					ON jo_contactdetails2.contactid = jo_contactdetails.reportsto";
		$query .= getNonAdminAccessControlQuery('Contacts',$current_user);
		$where_auto = " jo_crmentity.deleted = 0 ";

                if($where != "")
                   $query .= "  WHERE ($where) AND ".$where_auto;
                else
                   $query .= "  WHERE ".$where_auto;

		$log->info("Export Query Constructed Successfully");
		$log->debug("Exiting create_export_query method ...");
		return $query;
        }
	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
		// now handling in the crmentity for uitype 69
		//$this->insertIntoAttachment($this->id,$module);
	}

	/**
	 *      This function is used to add the jo_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the jo_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb,$upload_badext;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");

		$file_saved = false;
		//This is to added to store the existing attachment id of the contact where we should delete this when we give new image
		$old_attachmentid = $adb->query_result($adb->pquery("select jo_crmentity.crmid from jo_seattachmentsrel inner join jo_crmentity on jo_crmentity.crmid=jo_seattachmentsrel.attachmentsid where  jo_seattachmentsrel.crmid=?", array($id)),0,'crmid');
		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$files['original_name'] = modlib_purify($_REQUEST[$fileindex.'_hidden']);
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$imageNameSql = 'SELECT name FROM jo_seattachmentsrel INNER JOIN jo_attachments ON
								jo_seattachmentsrel.attachmentsid = jo_attachments.attachmentsid LEFT JOIN jo_contactdetails ON
								jo_contactdetails.contactid = jo_seattachmentsrel.crmid WHERE jo_seattachmentsrel.crmid = ?';
		$imageNameResult = $adb->pquery($imageNameSql,array($id));
		$imageName = decode_html($adb->query_result($imageNameResult, 0, "name"));

		//Inserting image information of record into base table
		$adb->pquery('UPDATE jo_contactdetails SET imagename = ? WHERE contactid = ?',array($imageName,$id));

		//This is to handle the delete image for contacts
		if($module == 'Contacts' && $file_saved)
		{
			if($old_attachmentid != '')
			{
				$setype = $adb->query_result($adb->pquery("select setype from jo_crmentity where crmid=?", array($old_attachmentid)),0,'setype');
				if($setype == 'Contacts Image')
				{
					$del_res1 = $adb->pquery("delete from jo_attachments where attachmentsid=?", array($old_attachmentid));
					$del_res2 = $adb->pquery("delete from jo_seattachmentsrel where attachmentsid=?", array($old_attachmentid));
				}
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
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

		$rel_table_arr = Array("Potentials"=>"jo_contpotentialrel","Potentials"=>"jo_potential","Activities"=>"jo_cntactivityrel",
				"Emails"=>"jo_seactivityrel","HelpDesk"=>"jo_troubletickets","Quotes"=>"jo_quotes","PurchaseOrder"=>"jo_purchaseorder",
				"SalesOrder"=>"jo_salesorder","Products"=>"jo_seproductsrel","Documents"=>"jo_senotesrel",
				"Attachments"=>"jo_seattachmentsrel","Campaigns"=>"jo_campaigncontrel",'Invoice'=>'jo_invoice',
                'ServiceContracts'=>'jo_servicecontracts','Project'=>'jo_project','Assets'=>'jo_assets',
				'Vendors'=>'jo_vendorcontactrel');

		$tbl_field_arr = Array("jo_contpotentialrel"=>"potentialid","jo_potential"=>"potentialid","jo_cntactivityrel"=>"activityid",
				"jo_seactivityrel"=>"activityid","jo_troubletickets"=>"ticketid","jo_quotes"=>"quoteid","jo_purchaseorder"=>"purchaseorderid",
				"jo_salesorder"=>"salesorderid","jo_seproductsrel"=>"productid","jo_senotesrel"=>"notesid",
				"jo_seattachmentsrel"=>"attachmentsid","jo_campaigncontrel"=>"campaignid",'jo_invoice'=>'invoiceid',
                'jo_servicecontracts'=>'servicecontractsid','jo_project'=>'projectid','jo_assets'=>'assetsid',
				'jo_vendorcontactrel'=>'vendorid');

		$entity_tbl_field_arr = Array("jo_contpotentialrel"=>"contactid","jo_potential"=>"contact_id","jo_cntactivityrel"=>"contactid",
				"jo_seactivityrel"=>"crmid","jo_troubletickets"=>"contact_id","jo_quotes"=>"contactid","jo_purchaseorder"=>"contactid",
				"jo_salesorder"=>"contactid","jo_seproductsrel"=>"crmid","jo_senotesrel"=>"crmid",
				"jo_seattachmentsrel"=>"crmid","jo_campaigncontrel"=>"contactid",'jo_invoice'=>'contactid',
                'jo_servicecontracts'=>'sc_related_to','jo_project'=>'linktoaccountscontacts','jo_assets'=>'contact',
				'jo_vendorcontactrel'=>'contactid');

		foreach($transferEntityIds as $transferId) {
			foreach($rel_table_arr as $rel_module=>$rel_table) {
                $relModuleModel = Head_Module::getInstance($rel_module);
				if($relModuleModel) {
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
			$adb->pquery("UPDATE jo_potential SET related_to = ? WHERE related_to = ?", array($entityId, $transferId));
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
		$matrix->setDependency('jo_crmentityContacts',array('jo_groupsContacts','jo_usersContacts','jo_lastModifiedByContacts'));
		
		if (!$queryplanner->requireTable('jo_contactdetails', $matrix)) {
			return '';
		}

        $matrix->setDependency('jo_contactdetails', array('jo_crmentityContacts','jo_contactaddress',
								'jo_customerdetails','jo_contactsubdetails','jo_contactscf'));

		$query = $this->getRelationQuery($module,$secmodule,"jo_contactdetails","contactid", $queryplanner);

		if ($queryplanner->requireTable("jo_crmentityContacts",$matrix)){
			$query .= " left join jo_crmentity as jo_crmentityContacts on jo_crmentityContacts.crmid = jo_contactdetails.contactid  and jo_crmentityContacts.deleted=0";
		}
		if ($queryplanner->requireTable("jo_contactdetailsContacts")){
			$query .= " left join jo_contactdetails as jo_contactdetailsContacts on jo_contactdetailsContacts.contactid = jo_contactdetails.reportsto";
		}
		if ($queryplanner->requireTable("jo_contactaddress")){
			$query .= " left join jo_contactaddress on jo_contactdetails.contactid = jo_contactaddress.contactaddressid";
		}
		if ($queryplanner->requireTable("jo_customerdetails")){
			$query .= " left join jo_customerdetails on jo_customerdetails.customerid = jo_contactdetails.contactid";
		}
		if ($queryplanner->requireTable("jo_contactsubdetails")){
			$query .= " left join jo_contactsubdetails on jo_contactdetails.contactid = jo_contactsubdetails.contactsubscriptionid";
		}
		if ($queryplanner->requireTable("jo_accountContacts")){
			$query .= " left join jo_account as jo_accountContacts on jo_accountContacts.accountid = jo_contactdetails.accountid";
		}
		if ($queryplanner->requireTable("jo_contactscf")){
			$query .= " left join jo_contactscf on jo_contactdetails.contactid = jo_contactscf.contactid";
		}
		if ($queryplanner->requireTable("jo_email_trackContacts")){
			$query .= " LEFT JOIN jo_email_track AS jo_email_trackContacts ON jo_email_trackContacts.crmid = jo_contactdetails.contactid";
		}
		if ($queryplanner->requireTable("jo_groupsContacts")){
			$query .= " left join jo_groups as jo_groupsContacts on jo_groupsContacts.groupid = jo_crmentityContacts.smownerid";
		}
		if ($queryplanner->requireTable("jo_usersContacts")){
			$query .= " left join jo_users as jo_usersContacts on jo_usersContacts.id = jo_crmentityContacts.smownerid";
		}
		if ($queryplanner->requireTable("jo_lastModifiedByContacts")){
			$query .= " left join jo_users as jo_lastModifiedByContacts on jo_lastModifiedByContacts.id = jo_crmentityContacts.modifiedby ";
		}
        if ($queryplanner->requireTable("jo_createdbyContacts")){
			$query .= " left join jo_users as jo_createdbyContacts on jo_createdbyContacts.id = jo_crmentityContacts.smcreatorid ";
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
			"Calendar" => array("jo_cntactivityrel"=>array("contactid","activityid"),"jo_contactdetails"=>"contactid"),
			"HelpDesk" => array("jo_troubletickets"=>array("contact_id","ticketid"),"jo_contactdetails"=>"contactid"),
			"Quotes" => array("jo_quotes"=>array("contactid","quoteid"),"jo_contactdetails"=>"contactid"),
			"PurchaseOrder" => array("jo_purchaseorder"=>array("contactid","purchaseorderid"),"jo_contactdetails"=>"contactid"),
			"SalesOrder" => array("jo_salesorder"=>array("contactid","salesorderid"),"jo_contactdetails"=>"contactid"),
			"Products" => array("jo_seproductsrel"=>array("crmid","productid"),"jo_contactdetails"=>"contactid"),
			"Campaigns" => array("jo_campaigncontrel"=>array("contactid","campaignid"),"jo_contactdetails"=>"contactid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_contactdetails"=>"contactid"),
			"Accounts" => array("jo_contactdetails"=>array("contactid","accountid")),
			"Invoice" => array("jo_invoice"=>array("contactid","invoiceid"),"jo_contactdetails"=>"contactid"),
			"Emails" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_contactdetails"=>"contactid"),
			"Vendors" =>array("jo_vendorcontactrel"=>array("contactid","vendorid"),"jo_contactdetails"=>"contactid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;

		//Deleting Contact related Potentials.
		$pot_q = 'SELECT jo_crmentity.crmid FROM jo_crmentity
			INNER JOIN jo_potential ON jo_crmentity.crmid=jo_potential.potentialid
			LEFT JOIN jo_account ON jo_account.accountid=jo_potential.related_to
			WHERE jo_crmentity.deleted=0 AND jo_potential.related_to=?';
		$pot_res = $this->db->pquery($pot_q, array($id));
		$pot_ids_list = array();
		for($k=0;$k < $this->db->num_rows($pot_res);$k++)
		{
			$pot_id = $this->db->query_result($pot_res,$k,"crmid");
			$pot_ids_list[] = $pot_id;
			$sql = 'UPDATE jo_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($pot_id));
		}
		//Backup deleted Contact related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'jo_crmentity', 'deleted', 'crmid', implode(",", $pot_ids_list));
		$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Backup Contact-Trouble Tickets Relation
		$tkt_q = 'SELECT ticketid FROM jo_troubletickets WHERE contact_id=?';
		$tkt_res = $this->db->pquery($tkt_q, array($id));
		if ($this->db->num_rows($tkt_res) > 0) {
			$tkt_ids_list = array();
			for($k=0;$k < $this->db->num_rows($tkt_res);$k++)
			{
				$tkt_ids_list[] = $this->db->query_result($tkt_res,$k,"ticketid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_troubletickets', 'contact_id', 'ticketid', implode(",", $tkt_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with Trouble Tickets
		$this->db->pquery('UPDATE jo_troubletickets SET contact_id=0 WHERE contact_id=?', array($id));

		//Backup Contact-PurchaseOrder Relation
		$po_q = 'SELECT purchaseorderid FROM jo_purchaseorder WHERE contactid=?';
		$po_res = $this->db->pquery($po_q, array($id));
		if ($this->db->num_rows($po_res) > 0) {
			$po_ids_list = array();
			for($k=0;$k < $this->db->num_rows($po_res);$k++)
			{
				$po_ids_list[] = $this->db->query_result($po_res,$k,"purchaseorderid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_purchaseorder', 'contactid', 'purchaseorderid', implode(",", $po_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with PurchaseOrder
		$this->db->pquery('UPDATE jo_purchaseorder SET contactid=0 WHERE contactid=?', array($id));

		//Backup Contact-SalesOrder Relation
		$so_q = 'SELECT salesorderid FROM jo_salesorder WHERE contactid=?';
		$so_res = $this->db->pquery($so_q, array($id));
		if ($this->db->num_rows($so_res) > 0) {
			$so_ids_list = array();
			for($k=0;$k < $this->db->num_rows($so_res);$k++)
			{
				$so_ids_list[] = $this->db->query_result($so_res,$k,"salesorderid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_salesorder', 'contactid', 'salesorderid', implode(",", $so_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with SalesOrder
		$this->db->pquery('UPDATE jo_salesorder SET contactid=0 WHERE contactid=?', array($id));

		//Backup Contact-Quotes Relation
		$quo_q = 'SELECT quoteid FROM jo_quotes WHERE contactid=?';
		$quo_res = $this->db->pquery($quo_q, array($id));
		if ($this->db->num_rows($quo_res) > 0) {
			$quo_ids_list = array();
			for($k=0;$k < $this->db->num_rows($quo_res);$k++)
			{
				$quo_ids_list[] = $this->db->query_result($quo_res,$k,"quoteid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_quotes', 'contactid', 'quoteid', implode(",", $quo_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
		}
		//removing the relationship of contacts with Quotes
		$this->db->pquery('UPDATE jo_quotes SET contactid=0 WHERE contactid=?', array($id));
		//remove the portal info the contact
		$this->db->pquery('DELETE FROM jo_portalinfo WHERE id = ?', array($id));
		$this->db->pquery('UPDATE jo_customerdetails SET portal=0,support_start_date=NULL,support_end_date=NULl WHERE customerid=?', array($id));
		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Accounts') {
			$sql = 'UPDATE jo_contactdetails SET accountid = ? WHERE contactid = ?';
			$this->db->pquery($sql, array(null, $id));
		} elseif($return_module == 'Potentials') {
			$sql = 'DELETE FROM jo_contpotentialrel WHERE contactid=? AND potentialid=?';
			$this->db->pquery($sql, array($id, $return_id));
			
			//If contact related to potential through edit of record,that entry will be present in
			//jo_potential contact_id column,which should be set to zero
			$sql = 'UPDATE jo_potential SET contact_id = ? WHERE contact_id=? AND potentialid=?';
			$this->db->pquery($sql, array(0,$id, $return_id));
		} elseif($return_module == 'Campaigns') {
			$sql = 'DELETE FROM jo_campaigncontrel WHERE contactid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Products') {
			$sql = 'DELETE FROM jo_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Vendors') {
			$sql = 'DELETE FROM jo_vendorcontactrel WHERE vendorid=? AND contactid=?';
			$this->db->pquery($sql, array($return_id, $id));
		} elseif($return_module == 'Documents') {
            $sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
            $this->db->pquery($sql, array($id, $return_id));
        } else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

	//added to get mail info for portal user
	//type argument included when when addin customizable tempalte for sending portal login details
	public static function getPortalEmailContents($entityData, $password, $type='') {
        require_once 'config/config.inc.php';
		global $PORTAL_URL, $HELPDESK_SUPPORT_EMAIL_ID;

		$adb = PearDatabase::getInstance();
		$moduleName = $entityData->getModuleName();

		$companyDetails = getCompanyDetails();

		$portalURL = vtranslate('Please ',$moduleName).'<a href="'.$PORTAL_URL.'" style="font-family:Arial, Helvetica, sans-serif;font-size:13px;">'.  vtranslate('click here', $moduleName).'</a>';

		//here id is hardcoded with 5. it is for support start notification in jo_notificationscheduler
		$query='SELECT jo_emailtemplates.subject,jo_emailtemplates.body
					FROM jo_notificationscheduler
						INNER JOIN jo_emailtemplates ON jo_emailtemplates.templateid=jo_notificationscheduler.notificationbody
					WHERE schedulednotificationid=5';

		$result = $adb->pquery($query, array());
		$body=decode_html($adb->query_result($result,0,'body'));
		$contents=$body;
		$contents = str_replace('$contact_name$',$entityData->get('firstname')." ".$entityData->get('lastname'),$contents);
		$contents = str_replace('$login_name$',$entityData->get('email'),$contents);
		$contents = str_replace('$password$',$password,$contents);
		$contents = str_replace('$URL$',$portalURL,$contents);
		$contents = str_replace('$support_team$',getTranslatedString('Support Team', $moduleName),$contents);
		$contents = str_replace('$logo$','<img src="cid:logo" />',$contents);

		//Company Details
		$contents = str_replace('$address$',$companyDetails['address'],$contents);
		$contents = str_replace('$companyname$',$companyDetails['companyname'],$contents);
		$contents = str_replace('$phone$',$companyDetails['phone'],$contents);
		$contents = str_replace('$companywebsite$',$companyDetails['website'],$contents);
		$contents = str_replace('$supportemail$',$HELPDESK_SUPPORT_EMAIL_ID,$contents);

		if($type == "LoginDetails") {
			$temp=$contents;
			$value["subject"]=decode_html($adb->query_result($result,0,'subject'));
			$value["body"]=$temp;
			return $value;
		}
		return $contents;
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids, $otherParams = array()) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Products') {
				$adb->pquery('INSERT INTO jo_seproductsrel VALUES(?,?,?,?)', array($crmid, $with_crmid, 'Contacts', 1));

			} elseif($with_module == 'Campaigns') {
				$adb->pquery("insert into jo_campaigncontrel values(?,?,1)", array($with_crmid, $crmid));

			} elseif($with_module == 'Potentials') {
				$adb->pquery("insert into jo_contpotentialrel values(?,?)", array($crmid, $with_crmid));

			}
            else if($with_module == 'Vendors'){
        		$adb->pquery("insert into jo_vendorcontactrel values (?,?)", array($with_crmid,$crmid));
            }else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

	function getListButtons($app_strings,$mod_strings = false) {
		$list_buttons = Array();

		if(isPermitted('Contacts','Delete','') == 'yes') {
			$list_buttons['del'] = $app_strings[LBL_MASS_DELETE];
		}
		if(isPermitted('Contacts','EditView','') == 'yes') {
			$list_buttons['mass_edit'] = $app_strings[LBL_MASS_EDIT];
			$list_buttons['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
		}
		if(isPermitted('Emails','EditView','') == 'yes'){
			$list_buttons['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];
		}
		return $list_buttons;
	}
    
    function getRelatedPotentialIds($id) {
        $relatedIds = array();
        $db = PearDatabase::getInstance();
        $query = "SELECT DISTINCT jo_crmentity.crmid FROM jo_contactdetails LEFT JOIN jo_contpotentialrel ON 
            jo_contpotentialrel.contactid = jo_contactdetails.contactid LEFT JOIN jo_potential ON 
            (jo_potential.potentialid = jo_contpotentialrel.potentialid OR jo_potential.contact_id = 
            jo_contactdetails.contactid) INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_potential.potentialid 
            WHERE jo_crmentity.deleted = 0 AND jo_contactdetails.contactid = ?";
        $result = $db->pquery($query, array($id));
		for ($i = 0; $i < $db->num_rows($result); $i++) {
            $relatedIds[] = $db->query_result($result, $i, 'crmid');
        }
        return $relatedIds;
    }
    
    function getRelatedTicketIds($id) {
        $relatedIds = array();
        $db = PearDatabase::getInstance();
        $query = "SELECT DISTINCT jo_crmentity.crmid FROM jo_troubletickets INNER JOIN jo_crmentity ON 
            jo_crmentity.crmid = jo_troubletickets.ticketid LEFT JOIN jo_contactdetails ON 
            jo_contactdetails.contactid = jo_troubletickets.contact_id WHERE jo_crmentity.deleted = 0 AND 
            jo_contactdetails.contactid = ?";
        $result = $db->pquery($query, array($id));
		for ($i = 0; $i < $db->num_rows($result); $i++) {
            $relatedIds[] = $db->query_result($result, $i, 'crmid');
        }
        return $relatedIds;
    }

}

?>