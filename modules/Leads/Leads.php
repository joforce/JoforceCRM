<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
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
class Leads extends CRMEntity {
	var $log;
	var $db;

	var $table_name = "jo_leaddetails";
	var $table_index= 'leadid';

	var $tab_name = Array('jo_crmentity','jo_leaddetails','jo_leadsubdetails','jo_leadaddress','jo_leadscf');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_leaddetails'=>'leadid','jo_leadsubdetails'=>'leadsubscriptionid','jo_leadaddress'=>'leadaddressid','jo_leadscf'=>'leadid');

	var $entity_table = "jo_crmentity";

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_leadscf', 'leadid');

	//construct this from database;
	var $column_fields = Array();
	var $sortby_fields = Array('lastname','firstname','email','phone','company','smownerid','website');

	// This is used to retrieve related jo_fields from form posts.
	var $additional_column_fields = Array('smcreatorid', 'smownerid', 'contactid','potentialid' ,'crmid');

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
		'First Name'=>Array('leaddetails'=>'firstname'),
		'Last Name'=>Array('leaddetails'=>'lastname'),
		'Company'=>Array('leaddetails'=>'company'),
		'Phone'=>Array('leadaddress'=>'phone'),
		'Website'=>Array('leadsubdetails'=>'website'),
		'Email'=>Array('leaddetails'=>'email'),
		'Assigned To'=>Array('crmentity'=>'smownerid')
	);
	var $list_fields_name = Array(
		'First Name'=>'firstname',
		'Last Name'=>'lastname',
		'Company'=>'company',
		'Phone'=>'phone',
		'Website'=>'website',
		'Email'=>'email',
		'Assigned To'=>'assigned_user_id'
	);
	var $list_link_field= 'lastname';

	var $search_fields = Array(
		'Name'=>Array('leaddetails'=>'lastname'),
		'Company'=>Array('leaddetails'=>'company')
	);
	var $search_fields_name = Array(
		'Name'=>'lastname',
		'Company'=>'company'
	);

	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'lastname', 'createdtime' ,'modifiedtime');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('firstname','lastname','leadsource','leadstatus','rating','industry','secondaryemail','email','annualrevenue','designation','salutation');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'lastname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'lastname';

	var $LBL_LEAD_MAPPING = 'LBL_LEAD_MAPPING';
	//var $groupTable = Array('jo_leadgrouprelation','leadid');

	function Leads()	{
		$this->log = LoggerManager::getLogger('lead');
		$this->log->debug("Entering Leads() method ...");
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Leads');
		$this->log->debug("Exiting Lead method ...");
	}

	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module)
	{
	}

	// Mike Crowe Mod --------------------------------------------------------Default ordering for us

	/** Function to export the lead records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Leads Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
		$log->debug("Entering create_export_query(".$where.") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Leads", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT $fields_list,case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
					FROM ".$this->entity_table."
				INNER JOIN jo_leaddetails
					ON jo_crmentity.crmid=jo_leaddetails.leadid
				LEFT JOIN jo_leadsubdetails
					ON jo_leaddetails.leadid = jo_leadsubdetails.leadsubscriptionid
				LEFT JOIN jo_leadaddress
					ON jo_leaddetails.leadid=jo_leadaddress.leadaddressid
				LEFT JOIN jo_leadscf
					ON jo_leadscf.leadid=jo_leaddetails.leadid
							LEFT JOIN jo_groups
									ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users
					ON jo_crmentity.smownerid = jo_users.id and jo_users.status='Active'
				";

		$query .= $this->getNonAdminAccessControlQuery('Leads',$current_user);
		$where_auto = " jo_crmentity.deleted=0 AND jo_leaddetails.converted =0";

		if($where != "")
			$query .= " where ($where) AND ".$where_auto;
		else
			$query .= " where ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}


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
		$query ='select case when (jo_users.user_name not like "") then '.$userNameSql.' else jo_groups.groupname end as user_name, jo_leaddetails.leadid, jo_crmentity.crmid, jo_potential.potentialid, jo_potential.potentialname, jo_potential.potentialtype, jo_potential.sales_stage, jo_potential.amount, jo_potential.closingdate,jo_potential.related_to,jo_potential.contact_id, jo_crmentity.crmid, jo_crmentity.smownerid from jo_leaddetails left join jo_crmentityrel on jo_crmentityrel.crmid=jo_leaddetails.leadid left join jo_potential on (jo_potential.potentialid = jo_crmentityrel.relcrmid) left join jo_crmentity on jo_crmentity.crmid = jo_potential.potentialid
		LEFT JOIN jo_potentialscf ON jo_potential.potentialid = jo_potentialscf.potentialid left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid left join jo_users on jo_users.id=jo_crmentity.smownerid where jo_crmentity.deleted=0 and jo_leaddetails.leadid ='.$id;
		// die($query);
		if (!$ignoreOrganizationCheck) {
			// Restrict the scope of listing to only related contacts of the organization linked to potential via related_to of Potential
			$query .= ' and (jo_leaddetails.leadid = jo_potential.related_to or jo_leaddetails.contactid=jo_potential.contact_id)';
		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_opportunities method ...");
		return $return_value;
	}

	/** Returns a list of the associated tasks
	 * @param  integer   $id      - leadid
	 * returns related Task or Event record in array format
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
				if(getFieldVisibilityPermission('Events',$current_user->id,'parent_id', 'readwrite') == '0') {
					$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString('LBL_TODO', $related_module) ."' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\";this.form.return_module.value=\"$this_module\";this.form.activity_mode.value=\"Events\";' type='submit' name='button'" .
						" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString('LBL_EVENT', $related_module) ."'>";
				}
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_activity.*,jo_seactivityrel.crmid as parent_id, jo_contactdetails.lastname,
			jo_contactdetails.contactid, jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_crmentity.modifiedtime,case when (jo_users.user_name not like '') then
		$userNameSql else jo_groups.groupname end as user_name,
		jo_recurringevents.recurringtype
		from jo_activity inner join jo_seactivityrel on jo_seactivityrel.activityid=
		jo_activity.activityid inner join jo_crmentity on jo_crmentity.crmid=
		jo_activity.activityid left join jo_cntactivityrel on
		jo_cntactivityrel.activityid = jo_activity.activityid left join
		jo_contactdetails on jo_contactdetails.contactid = jo_cntactivityrel.contactid
		left join jo_users on jo_users.id=jo_crmentity.smownerid
		left outer join jo_recurringevents on jo_recurringevents.activityid=
		jo_activity.activityid left join jo_groups on jo_groups.groupid=
		jo_crmentity.smownerid where jo_seactivityrel.crmid=".$id." and
			jo_crmentity.deleted = 0 and ((jo_activity.activitytype='Task' and
			jo_activity.status not in ('Completed','Deferred')) or
			(jo_activity.activitytype NOT in ('Emails','Task') and
			jo_activity.eventstatus not in ('','Held'))) ";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

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
		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'account_id','readwrite') == '0') {
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

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT jo_crmentity.*, jo_quotes.*, jo_leaddetails.leadid,
					case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
					FROM jo_quotes
					INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_quotes.quoteid
					LEFT JOIN jo_leaddetails ON jo_leaddetails.leadid = jo_quotes.contactid
					LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
					LEFT JOIN jo_quotescf ON jo_quotescf.quoteid = jo_quotes.quoteid
					LEFT JOIN jo_quotesbillads ON jo_quotesbillads.quotebilladdressid = jo_quotes.quoteid
					LEFT JOIN jo_quotesshipads ON jo_quotesshipads.quoteshipaddressid = jo_quotes.quoteid
					LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
					WHERE jo_crmentity.deleted = 0 AND jo_leaddetails.leadid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
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
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name ,
				jo_campaign.campaignid, jo_campaign.campaignname, jo_campaign.campaigntype, jo_campaign.campaignstatus,
				jo_campaign.expectedrevenue, jo_campaign.closingdate, jo_crmentity.crmid, jo_crmentity.smownerid,
				jo_crmentity.modifiedtime from jo_campaign
				inner join jo_campaignleadrel on jo_campaignleadrel.campaignid=jo_campaign.campaignid
				inner join jo_crmentity on jo_crmentity.crmid = jo_campaign.campaignid
				inner join jo_campaignscf ON jo_campaignscf.campaignid = jo_campaign.campaignid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_users on jo_users.id = jo_crmentity.smownerid
				where jo_campaignleadrel.leadid=".$id." and jo_crmentity.deleted=0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
		return $return_value;
	}


		/** Returns a list of the associated emails
		 * @param  integer   $id      - leadid
		 * returns related emails record in array format
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
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query ="select case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name," .
				" jo_activity.activityid, jo_activity.subject, jo_activity.semodule, jo_activity.activitytype," .
				" jo_activity.date_start, jo_activity.time_start, jo_activity.status, jo_activity.priority, jo_crmentity.crmid," .
				" jo_crmentity.smownerid,jo_crmentity.modifiedtime, jo_users.user_name, jo_seactivityrel.crmid as parent_id " .
				" from jo_activity" .
				" inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid" .
				" inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid" .
				" left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid" .
				" left join jo_users on  jo_users.id=jo_crmentity.smownerid" .
				" where jo_activity.activitytype='Emails' and jo_crmentity.deleted=0 and jo_seactivityrel.crmid=".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}

	/**
	 * Function to get Lead related Task & Event which have activity type Held, Completed or Deferred.
	 * @param  integer   $id      - leadid
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
			jo_activity.due_date,jo_activity.time_start,jo_activity.time_end,
			jo_crmentity.modifiedtime,jo_crmentity.createdtime,
			jo_crmentity.description, $userNameSql as user_name,jo_groups.groupname
				from jo_activity
				inner join jo_seactivityrel on jo_seactivityrel.activityid=jo_activity.activityid
				inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid
				left join jo_groups on jo_groups.groupid=jo_crmentity.smownerid
				left join jo_users on jo_crmentity.smownerid= jo_users.id
				where (jo_activity.activitytype != 'Emails')
				and (jo_activity.status = 'Completed' or jo_activity.status = 'Deferred' or (jo_activity.eventstatus = 'Held' and jo_activity.eventstatus != ''))
				and jo_seactivityrel.crmid=".$id."
							and jo_crmentity.deleted = 0";
		//Don't add order by, because, for security, one more condition will be added with this query in includes/RelatedListView.php

		$log->debug("Exiting get_history method ...");
		return getHistory('Leads',$query,$id);
	}

	/**
	* Function to get lead related Products
	* @param  integer   $id      - leadid
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

		$query = "SELECT jo_products.productid, jo_products.productname, jo_products.productcode,
				jo_products.commissionrate, jo_products.qty_per_unit, jo_products.unit_price,
				jo_crmentity.crmid, jo_crmentity.smownerid
				FROM jo_products
				INNER JOIN jo_seproductsrel ON jo_products.productid = jo_seproductsrel.productid  and jo_seproductsrel.setype = 'Leads'
				INNER JOIN jo_productcf
					ON jo_products.productid = jo_productcf.productid
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_products.productid
				INNER JOIN jo_leaddetails ON jo_leaddetails.leadid = jo_seproductsrel.crmid
				LEFT JOIN jo_users
					ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups
					ON jo_groups.groupid = jo_crmentity.smownerid
			   WHERE jo_crmentity.deleted = 0 AND jo_leaddetails.leadid = $id";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/** Function to get the Columnnames of the Leads Record
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Lead()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Lead() method ...");
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
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "select fieldlabel from jo_field where tabid=7 and jo_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select jo_field.fieldid,fieldlabel from jo_field inner join jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid where jo_field.tabid=7 and jo_field.displaytype in (1,2,3,4) and jo_profile2field.visible=0 and jo_def_org_field.visible=0 and jo_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
				array_push($params1, $profileList);
			}
		}
		$result = $this->db->pquery($sql1, $params1);
		$numRows = $this->db->num_rows($result);
		for($i=0; $i < $numRows;$i++)
		{
		$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
		$custom_fields[$i] = preg_replace("/\s+/","",$custom_fields[$i]);
		$custom_fields[$i] = strtoupper($custom_fields[$i]);
		}
		$mergeflds = $custom_fields;
		$log->debug("Exiting getColumnNames_Lead method ...");
		return $mergeflds;
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

		$rel_table_arr = Array("Activities"=>"jo_seactivityrel","Documents"=>"jo_senotesrel","Attachments"=>"jo_seattachmentsrel",
					"Products"=>"jo_seproductsrel","Campaigns"=>"jo_campaignleadrel","Emails"=>"jo_seactivityrel");

		$tbl_field_arr = Array("jo_seactivityrel"=>"activityid","jo_senotesrel"=>"notesid","jo_seattachmentsrel"=>"attachmentsid",
					"jo_seproductsrel"=>"productid","jo_campaignleadrel"=>"campaignid","jo_seactivityrel"=>"activityid");

		$entity_tbl_field_arr = Array("jo_seactivityrel"=>"crmid","jo_senotesrel"=>"crmid","jo_seattachmentsrel"=>"crmid",
					"jo_seproductsrel"=>"crmid","jo_campaignleadrel"=>"leadid","jo_seactivityrel"=>"crmid");

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
	function generateReportsSecQuery($module,$secmodule, $queryPlanner) {
		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityLeads',array('jo_groupsLeads','jo_usersLeads','jo_lastModifiedByLeads'));

		// TODO Support query planner
		if (!$queryPlanner->requireTable("jo_leaddetails",$matrix)){
			return '';
		}

		$matrix->setDependency('jo_leaddetails',array('jo_crmentityLeads', 'jo_leadaddress','jo_leadsubdetails','jo_leadscf','jo_email_trackLeads'));

		$query = $this->getRelationQuery($module,$secmodule,"jo_leaddetails","leadid", $queryPlanner);
		if ($queryPlanner->requireTable("jo_crmentityLeads",$matrix)){
			$query .= " left join jo_crmentity as jo_crmentityLeads on jo_crmentityLeads.crmid = jo_leaddetails.leadid and jo_crmentityLeads.deleted=0";
		}
		if ($queryPlanner->requireTable("jo_leadaddress")){
			$query .= " left join jo_leadaddress on jo_leaddetails.leadid = jo_leadaddress.leadaddressid";
		}
		if ($queryPlanner->requireTable("jo_leadsubdetails")){
			$query .= " left join jo_leadsubdetails on jo_leadsubdetails.leadsubscriptionid = jo_leaddetails.leadid";
		}
		if ($queryPlanner->requireTable("jo_leadscf")){
			$query .= " left join jo_leadscf on jo_leadscf.leadid = jo_leaddetails.leadid";
		}
		if ($queryPlanner->requireTable("jo_email_trackLeads")){
			$query .= " LEFT JOIN jo_email_track AS jo_email_trackLeads ON jo_email_trackLeads.crmid = jo_leaddetails.leadid";
		}
		if ($queryPlanner->requireTable("jo_groupsLeads")){
			$query .= " left join jo_groups as jo_groupsLeads on jo_groupsLeads.groupid = jo_crmentityLeads.smownerid";
		}
		if ($queryPlanner->requireTable("jo_usersLeads")){
			$query .= " left join jo_users as jo_usersLeads on jo_usersLeads.id = jo_crmentityLeads.smownerid";
		}
		if ($queryPlanner->requireTable("jo_lastModifiedByLeads")){
			$query .= " left join jo_users as jo_lastModifiedByLeads on jo_lastModifiedByLeads.id = jo_crmentityLeads.modifiedby ";
		}
		if ($queryPlanner->requireTable("jo_createdbyLeads")){
			$query .= " left join jo_users as jo_createdbyLeads on jo_createdbyLeads.id = jo_crmentityLeads.smcreatorid ";
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
			"Calendar" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_leaddetails"=>"leadid"),
			"Products" => array("jo_seproductsrel"=>array("crmid","productid"),"jo_leaddetails"=>"leadid"),
			"Campaigns" => array("jo_campaignleadrel"=>array("leadid","campaignid"),"jo_leaddetails"=>"leadid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_leaddetails"=>"leadid"),
			"Services" => array("jo_crmentityrel"=>array("crmid","relcrmid"),"jo_leaddetails"=>"leadid"),
			"Emails" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_leaddetails"=>"leadid"),
		);
		return $rel_tables[$secmodule];
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Campaigns') {
			$sql = 'DELETE FROM jo_campaignleadrel WHERE leadid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		}
		elseif($return_module == 'Products') {
			$sql = 'DELETE FROM jo_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Documents') {
			$sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

	function getListButtons($app_strings) {
		$list_buttons = Array();

		if(isPermitted('Leads','Delete','') == 'yes') {
			$list_buttons['del'] =	$app_strings[LBL_MASS_DELETE];
		}
		if(isPermitted('Leads','EditView','') == 'yes') {
			$list_buttons['mass_edit'] = $app_strings[LBL_MASS_EDIT];
			$list_buttons['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
		}
		if(isPermitted('Emails','EditView','') == 'yes')
			$list_buttons['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];

		// end of mailer export
		return $list_buttons;
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids, $otherParams = array()) {
		$adb = PearDatabase::getInstance();

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Products')
				$adb->pquery('INSERT INTO jo_seproductsrel VALUES(?,?,?,?)', array($crmid, $with_crmid, $module, 1));
			elseif($with_module == 'Campaigns')
				$adb->pquery("insert into  jo_campaignleadrel values(?,?,1)", array($with_crmid, $crmid));
			else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

	function getQueryForDuplicates($module, $tableColumns, $selectedColumns = '', $ignoreEmpty = false, $requiredTables = array()) {
		if(is_array($tableColumns)) {
			$tableColumnsString = implode(',', $tableColumns);
		}
		$selectClause = "SELECT " . $this->table_name . "." . $this->table_index . " AS recordid," . $tableColumnsString;

		// Select Custom Field Table Columns if present
		if (isset($this->customFieldTable))
			$query .= ", " . $this->customFieldTable[0] . ".* ";

		$fromClause = " FROM $this->table_name";

		$fromClause .= " INNER JOIN jo_crmentity ON jo_crmentity.crmid = $this->table_name.$this->table_index";

		if($this->tab_name) {
			foreach($this->tab_name as $tableName) {
				if($tableName != 'jo_crmentity' && $tableName != $this->table_name && in_array($tableName, $requiredTables)) {
					if($this->tab_name_index[$tableName]) {
						$fromClause .= " INNER JOIN " . $tableName . " ON " . $tableName . '.' . $this->tab_name_index[$tableName] .
							" = $this->table_name.$this->table_index";
					}
				}
			}
		}

		$whereClause = " WHERE jo_crmentity.deleted = 0 AND jo_leaddetails.converted=0 ";
		$whereClause .= $this->getListViewSecurityParameter($module);

		if($ignoreEmpty) {
			foreach($tableColumns as $tableColumn){
				$whereClause .= " AND ($tableColumn IS NOT NULL AND $tableColumn != '') ";
			}
		}

		if (isset($selectedColumns) && trim($selectedColumns) != '') {
			$sub_query = "SELECT $selectedColumns FROM $this->table_name AS t " .
					" INNER JOIN jo_crmentity AS crm ON crm.crmid = t." . $this->table_index;
			// Consider custom table join as well.
			if (isset($this->customFieldTable)) {
				$sub_query .= " LEFT JOIN " . $this->customFieldTable[0] . " tcf ON tcf." . $this->customFieldTable[1] . " = t.$this->table_index";
			}
			$sub_query .= " WHERE crm.deleted=0 GROUP BY $selectedColumns HAVING COUNT(*)>1";
		} else {
			$sub_query = "SELECT $tableColumnsString $fromClause $whereClause GROUP BY $tableColumnsString HAVING COUNT(*)>1";
		}

		$i = 1;
		foreach($tableColumns as $tableColumn){
			$tableInfo = explode('.', $tableColumn);
			$duplicateCheckClause .= " ifnull($tableColumn,'null') = ifnull(temp.$tableInfo[1],'null')";
			if (count($tableColumns) != $i++) $duplicateCheckClause .= " AND ";
		}

		$query = $selectClause . $fromClause .
				" LEFT JOIN jo_users_last_import ON jo_users_last_import.bean_id=" . $this->table_name . "." . $this->table_index .
				" INNER JOIN (" . $sub_query . ") AS temp ON " . $duplicateCheckClause .
				$whereClause .
				" ORDER BY $tableColumnsString," . $this->table_name . "." . $this->table_index . " ASC";
		return $query;
	}

	/**
	 * Invoked when special actions are to be performed on the module.
	 * @param String Module name
	 * @param String Event Type
	 */
	function modlib_handler($moduleName, $eventType) {
		if ($moduleName == 'Leads') {
			$db = PearDatabase::getInstance();
			if ($eventType == 'module.disabled') {
				$db->pquery('UPDATE jo_settings_field SET active=1 WHERE name=?', array($this->LBL_LEAD_MAPPING));
			} else if ($eventType == 'module.enabled') {
				$db->pquery('UPDATE jo_settings_field SET active=0 WHERE name=?', array($this->LBL_LEAD_MAPPING));
			}
		}
	}
}

?>