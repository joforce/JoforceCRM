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
 * $Header: /advent/projects/wesat/jo_crm/sugarcrm/modules/Accounts/Accounts.php,v 1.53 2005/04/28 08:06:45 rank Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/
class Accounts extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "jo_account";
	var $table_index= 'accountid';
	var $tab_name = Array('jo_crmentity','jo_account','jo_accountbillads','jo_accountshipads','jo_accountscf');
	var $tab_name_index = Array('jo_crmentity'=>'crmid','jo_account'=>'accountid','jo_accountbillads'=>'accountaddressid','jo_accountshipads'=>'accountaddressid','jo_accountscf'=>'accountid');
	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('jo_accountscf', 'accountid');
	var $entity_table = "jo_crmentity";

	var $column_fields = Array();

	var $sortby_fields = Array('accountname','bill_city','website','phone','smownerid');

	//var $groupTable = Array('jo_accountgrouprelation','accountid');

	// This is the list of jo_fields that are in the lists.
	var $list_fields = Array(
			'Account Name'=>Array('jo_account'=>'accountname'),
			'Billing City'=>Array('jo_accountbillads'=>'bill_city'),
			'Website'=>Array('jo_account'=>'website'),
			'Phone'=>Array('jo_account'=> 'phone'),
			'Assigned To'=>Array('jo_crmentity'=>'smownerid')
			);

	var $list_fields_name = Array(
			'Account Name'=>'accountname',
			'Billing City'=>'bill_city',
			'Website'=>'website',
			'Phone'=>'phone',
			'Assigned To'=>'assigned_user_id'
			);
	var $list_link_field= 'accountname';

	var $search_fields = Array(
			'Account Name'=>Array('jo_account'=>'accountname'),
			'Billing City'=>Array('jo_accountbillads'=>'bill_city'),
			'Assigned To'=>Array('jo_crmentity'=>'smownerid'),
			);

	var $search_fields_name = Array(
			'Account Name'=>'accountname',
			'Billing City'=>'bill_city',
			'Assigned To'=>'assigned_user_id',
			);
	// This is the list of jo_fields that are required
	var $required_fields =  array();

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to jo_field.fieldname values.
	var $mandatory_fields = Array('assigned_user_id', 'createdtime', 'modifiedtime', 'accountname');

	//Default Fields for Email Templates -- Pavani
	var $emailTemplate_defaultFields = array('accountname','account_type','industry','annualrevenue','phone','email1','rating','website','fax');

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'accountname';
	var $default_sort_order = 'ASC';

	// For Alphabetical search
	var $def_basicsearch_col = 'accountname';

	var $related_module_table_index = array(
		'Contacts' => array('table_name' => 'jo_contactdetails', 'table_index' => 'contactid', 'rel_index' => 'accountid'),
		'Potentials' => array('table_name' => 'jo_potential', 'table_index' => 'potentialid', 'rel_index' => 'related_to'),
		'Quotes' => array('table_name' => 'jo_quotes', 'table_index' => 'quoteid', 'rel_index' => 'accountid'),
		'SalesOrder' => array('table_name' => 'jo_salesorder', 'table_index' => 'salesorderid', 'rel_index' => 'accountid'),
		'Invoice' => array('table_name' => 'jo_invoice', 'table_index' => 'invoiceid', 'rel_index' => 'accountid'),
		'HelpDesk' => array('table_name' => 'jo_troubletickets', 'table_index' => 'ticketid', 'rel_index' => 'parent_id'),
		'Products' => array('table_name' => 'jo_seproductsrel', 'table_index' => 'productid', 'rel_index' => 'crmid'),
		'Calendar' => array('table_name' => 'jo_seactivityrel', 'table_index' => 'activityid', 'rel_index' => 'crmid'),
		'Documents' => array('table_name' => 'jo_senotesrel', 'table_index' => 'notesid', 'rel_index' => 'crmid'),
		'ServiceContracts' => array('table_name' => 'jo_servicecontracts', 'table_index' => 'servicecontractsid', 'rel_index' => 'sc_related_to'),
		'Services' => array('table_name' => 'jo_crmentityrel', 'table_index' => 'crmid', 'rel_index' => 'crmid'),
		'Campaigns' => array('table_name' => 'jo_campaignaccountrel', 'table_index' => 'campaignid', 'rel_index' => 'accountid'),
		'Assets' => array('table_name' => 'jo_assets', 'table_index' => 'assetsid', 'rel_index' => 'account'),
		'Project' => array('table_name' => 'jo_project', 'table_index' => 'projectid', 'rel_index' => 'linktoaccountscontacts'),
		'PurchaseOrder' => array('table_name' => 'jo_purchaseorder', 'table_index' => 'purchaseorderid', 'rel_index' => 'accountid'),
	);

	function Accounts() {
		$this->log =LoggerManager::getLogger('account');
		$this->db = PearDatabase::getInstance();
		$this->column_fields = getColumnFields('Accounts');
	}

	/** Function to handle module specific operations when saving a entity
	*/
	function save_module($module) {

	}


	// Mike Crowe Mod --------------------------------------------------------Default ordering for us
	/** Returns a list of the associated Campaigns
	 * @param $id -- campaign id :: Type Integer
	 * @returns list of campaigns in array format
	 */
	function get_campaigns($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_campaigns(".$id.") method ...");
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

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
		}

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_campaign.campaignid, jo_campaign.campaignname, jo_campaign.campaigntype, jo_campaign.campaignstatus,
				jo_campaign.expectedrevenue, jo_campaign.closingdate, jo_crmentity.crmid, jo_crmentity.smownerid,
				jo_crmentity.modifiedtime
				from jo_campaign
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_campaign.campaignid
				INNER JOIN jo_campaignscf ON jo_campaignscf.campaignid = jo_campaign.campaignid
				LEFT JOIN jo_campaignaccountrel ON jo_campaignaccountrel.campaignid=jo_campaign.campaignid
				LEFT JOIN jo_campaigncontrel ON jo_campaigncontrel.campaignid=jo_campaign.campaignid
				LEFT JOIN jo_groups ON jo_groups.groupid=jo_crmentity.smownerid
				LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
				WHERE jo_crmentity.deleted=0 AND (jo_campaignaccountrel.accountid=$id";

		if(!empty ($entityIds)){
			$query .= " OR jo_campaigncontrel.contactid IN (".$entityIds."))";
		} else {
			$query .= ")";
		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_campaigns method ...");
		return $return_value;
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

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_contactdetails.*,
			jo_crmentity.crmid,
                        jo_crmentity.smownerid,
			jo_account.accountname,
			case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
			FROM jo_contactdetails
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_contactdetails.contactid
			LEFT JOIN jo_account ON jo_account.accountid = jo_contactdetails.accountid
			INNER JOIN jo_contactaddress ON jo_contactdetails.contactid = jo_contactaddress.contactaddressid
			INNER JOIN jo_contactsubdetails ON jo_contactdetails.contactid = jo_contactsubdetails.contactsubscriptionid
			INNER JOIN jo_customerdetails ON jo_contactdetails.contactid = jo_customerdetails.customerid
			INNER JOIN jo_contactscf ON jo_contactdetails.contactid = jo_contactscf.contactid
			LEFT JOIN jo_groups	ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
			WHERE jo_crmentity.deleted = 0
			AND jo_contactdetails.accountid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

	/** Returns a list of the associated contacts
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
* Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	 */
	function get_purchaseorder($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_purchaseorder(".$id.") method ...");
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

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_purchaseorder.*, jo_crmentity.crmid, jo_crmentity.smownerid,
			case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
			FROM jo_purchaseorder
			INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_purchaseordercf ON jo_purchaseordercf.purchaseorderid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_poshipads ON jo_poshipads.poshipaddressid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_pobillads ON jo_pobillads.pobilladdressid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_account ON jo_account.accountid = jo_purchaseorder.accountid
			LEFT JOIN jo_groups	ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
			WHERE jo_crmentity.deleted = 0
			AND jo_purchaseorder.accountid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_purchaseorder method ...");
		return $return_value;
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
				$button .= "<input title='".getTranslatedString('LBL_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		// TODO: We need to add pull contacts if its linked as secondary in Potentials too.
		// These relations are captued in jo_contpotentialrel
		// Better to provide switch to turn-on / off this feature like in
		// Contacts::get_opportunities

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT jo_potential.potentialid, jo_potential.related_to, jo_potential.potentialname, jo_potential.sales_stage,jo_potential.contact_id,
				jo_potential.potentialtype, jo_potential.amount, jo_potential.closingdate, jo_potential.potentialtype, jo_account.accountname,
				case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_crmentity.crmid, jo_crmentity.smownerid
				FROM jo_potential
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_potential.potentialid
				LEFT JOIN jo_account ON jo_account.accountid = jo_potential.related_to
				INNER JOIN jo_potentialscf ON jo_potential.potentialid = jo_potentialscf.potentialid
				LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				WHERE jo_crmentity.deleted = 0 AND (jo_potential.related_to = $id ";
		if(!empty($entityIds)) {
			$query .= " OR jo_potential.contact_id IN (".$entityIds.")";
		}

		$query .= ')';

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

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT jo_activity.*, jo_cntactivityrel.*, jo_seactivityrel.crmid as parent_id, jo_contactdetails.lastname,
				jo_contactdetails.firstname, jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.modifiedtime,
				case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_recurringevents.recurringtype
				FROM jo_activity
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
				LEFT JOIN jo_seactivityrel ON jo_seactivityrel.activityid = jo_activity.activityid
				LEFT JOIN jo_cntactivityrel ON jo_cntactivityrel.activityid = jo_activity.activityid
				LEFT JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_cntactivityrel.contactid
				LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
				LEFT OUTER JOIN jo_recurringevents ON jo_recurringevents.activityid = jo_activity.activityid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				WHERE jo_crmentity.deleted = 0
				AND ((jo_activity.activitytype='Task' and jo_activity.status not in ('Completed','Deferred'))
				OR (jo_activity.activitytype not in ('Emails','Task') and  jo_activity.eventstatus not in ('','Held')))
				AND (jo_seactivityrel.crmid = $id";

		if(!empty ($entityIds)){
			$query .= " OR jo_cntactivityrel.contactid IN (".$entityIds."))";
		} else {
			$query .= ")";
        }
        // There could be more than one contact for an activity.
        $query .= ' GROUP BY jo_activity.activityid';

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);
		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_activities method ...");
		return $return_value;
	}

	/**
	 * Function to get Account related Task & Event which have activity type Held, Completed or Deferred.
 	 * @param  integer   $id      - accountid
 	 * returns related Task or Event record in array format
 	 */
	function get_history($id)
	{
		global $log;
                $log->debug("Entering get_history(".$id.") method ...");

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT DISTINCT(jo_activity.activityid), jo_activity.subject, jo_activity.status, jo_activity.eventstatus,
				jo_activity.activitytype, jo_activity.date_start, jo_activity.due_date, jo_activity.time_start, jo_activity.time_end,
				jo_crmentity.modifiedtime, jo_crmentity.createdtime, jo_crmentity.description,
				case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
				FROM jo_activity
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
				LEFT JOIN jo_seactivityrel ON jo_seactivityrel.activityid = jo_activity.activityid
				LEFT JOIN jo_cntactivityrel ON jo_cntactivityrel.activityid = jo_activity.activityid
				LEFT JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_cntactivityrel.contactid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
				WHERE (jo_activity.activitytype != 'Emails')
				AND (jo_activity.status = 'Completed'
					OR jo_activity.status = 'Deferred'
					OR (jo_activity.eventstatus = 'Held' AND jo_activity.eventstatus != ''))
				AND jo_crmentity.deleted = 0 AND (jo_seactivityrel.crmid = $id";

		if(!empty ($entityIds)){
			$query .= " OR jo_cntactivityrel.contactid IN (".$entityIds."))";
		} else {
			$query .= ")";
		}

		//Don't add order by, because, for security, one more condition will be added with this query in includes/RelatedListView.php
		$log->debug("Exiting get_history method ...");
		return getHistory('Accounts',$query,$id);
	}

	/** Returns a list of the associated emails
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved.
* Contributor(s): JoForce.com.
	 * Contributor(s): ______________________________________..
	*/
	function get_emails($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user, $adb;
		$log->debug("Entering get_emails(".$id.") method ...");
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

		$button .= '<input type="hidden" name="email_directing_module"><input type="hidden" name="record">';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."' accessyKey='F' class='crmbutton small create' onclick='fnvshobj(this,\"sendmail_cont\");sendmail(\"$this_module\",$id);' type='button' name='button' value='". getTranslatedString('LBL_ADD_NEW')." ". getTranslatedString($singular_modname)."'></td>";
			}
		}

		$entityIds = array_merge(array($id), $this->getRelatedContactsIds(), $this->getRelatedPotentialIds($id), $this->getRelatedTicketIds($id));
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
			jo_activity.activityid, jo_activity.subject, jo_activity.activitytype, jo_crmentity.modifiedtime,
			jo_crmentity.crmid, jo_crmentity.smownerid, jo_activity.date_start,jo_activity.time_start, jo_seactivityrel.crmid as parent_id
			FROM jo_activity, jo_seactivityrel, jo_account, jo_users, jo_crmentity
			LEFT JOIN jo_groups ON jo_groups.groupid=jo_crmentity.smownerid
			WHERE jo_seactivityrel.activityid = jo_activity.activityid
				AND jo_seactivityrel.crmid IN (".$entityIds.")
				AND jo_users.id=jo_crmentity.smownerid
				AND jo_crmentity.crmid = jo_activity.activityid
				AND jo_activity.activitytype='Emails'
				AND jo_account.accountid = ".$id."
				AND jo_crmentity.deleted = 0";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_emails method ...");
		return $return_value;
	}


	/**
	* Function to get Account related Quotes
	* @param  integer   $id      - accountid
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

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_crmentity.*, jo_quotes.*, jo_potential.potentialname, jo_account.accountname
				FROM jo_quotes
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_quotes.quoteid
				LEFT OUTER JOIN jo_account ON jo_account.accountid = jo_quotes.accountid
				LEFT OUTER JOIN jo_potential ON jo_potential.potentialid = jo_quotes.potentialid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
                LEFT JOIN jo_quotescf ON jo_quotescf.quoteid = jo_quotes.quoteid
				LEFT JOIN jo_quotesbillads ON jo_quotesbillads.quotebilladdressid = jo_quotes.quoteid
				LEFT JOIN jo_quotesshipads ON jo_quotesshipads.quoteshipaddressid = jo_quotes.quoteid
				LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
				WHERE jo_crmentity.deleted = 0 AND (jo_account.accountid = $id";

		if(!empty ($entityIds)){
			$query .= " OR jo_quotes.contactid IN (".$entityIds."))";
		} else {
			$query .= ")";
		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_quotes method ...");
		return $return_value;
	}
	/**
	* Function to get Account related Invoices
	* @param  integer   $id      - accountid
	* returns related Invoices record in array format
	*/
	function get_invoices($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_invoices(".$id.") method ...");
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

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				jo_crmentity.*, jo_invoice.*, jo_account.accountname, jo_salesorder.subject AS salessubject
				FROM jo_invoice
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_invoice.invoiceid
				LEFT OUTER JOIN jo_account ON jo_account.accountid = jo_invoice.accountid
				LEFT OUTER JOIN jo_salesorder ON jo_salesorder.salesorderid = jo_invoice.salesorderid
                LEFT JOIN jo_invoicecf ON jo_invoicecf.invoiceid = jo_invoice.invoiceid
				LEFT JOIN jo_invoicebillads ON jo_invoicebillads.invoicebilladdressid = jo_invoice.invoiceid
				LEFT JOIN jo_invoiceshipads ON jo_invoiceshipads.invoiceshipaddressid = jo_invoice.invoiceid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
				WHERE jo_crmentity.deleted = 0 AND (jo_invoice.accountid = $id";

		if(!empty ($entityIds)){
			$query .= " OR jo_invoice.contactid IN (".$entityIds."))";
		} else {
			$query .= ")";
		}

        $return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_invoices method ...");
		return $return_value;
	}

	/**
	* Function to get Account related SalesOrder
	* @param  integer   $id      - accountid
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

		$entityIds = $this->getRelatedContactsIds();
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT jo_crmentity.*, jo_salesorder.*, jo_quotes.subject AS quotename, jo_account.accountname,
				case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name
				FROM jo_salesorder
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_salesorder.salesorderid
				LEFT OUTER JOIN jo_quotes ON jo_quotes.quoteid = jo_salesorder.quoteid
				LEFT OUTER JOIN jo_account ON jo_account.accountid = jo_salesorder.accountid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
                LEFT JOIN jo_invoice_recurring_info ON jo_invoice_recurring_info.start_period = jo_salesorder.salesorderid
                LEFT JOIN jo_salesordercf ON jo_salesordercf.salesorderid = jo_salesorder.salesorderid
				LEFT JOIN jo_sobillads ON jo_sobillads.sobilladdressid = jo_salesorder.salesorderid
				LEFT JOIN jo_soshipads ON jo_soshipads.soshipaddressid = jo_salesorder.salesorderid
				LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
				WHERE jo_crmentity.deleted = 0 AND (jo_salesorder.accountid = $id";

		if(!empty ($entityIds)){
			$query .= " OR jo_salesorder.contactid IN (".$entityIds."))";
		} else {
			$query .= ")";
		}

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_salesorder method ...");
		return $return_value;
	}
	/**
	* Function to get Account related Tickets
	* @param  integer   $id      - accountid
	* returns related Ticket record in array format
	*/
	function get_tickets($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_tickets(".$id.") method ...");
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

		if($actions && getFieldVisibilityPermission($related_module, $current_user->id, 'parent_id','readwrite') == '0') {
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

		$entityIds = $this->getRelatedContactsIds($id);
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name, jo_users.id,
				jo_troubletickets.title, jo_troubletickets.ticketid AS crmid, jo_troubletickets.status, jo_troubletickets.priority,
				jo_troubletickets.parent_id, jo_troubletickets.contact_id, jo_troubletickets.ticket_no, jo_crmentity.smownerid, jo_crmentity.modifiedtime
				FROM jo_troubletickets
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_troubletickets.ticketid
				LEFT JOIN jo_ticketcf ON jo_troubletickets.ticketid = jo_ticketcf.ticketid
				LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				WHERE  jo_crmentity.deleted = 0 and (jo_troubletickets.parent_id = $id";

		if(!empty ($entityIds)){
			$query .= " OR jo_troubletickets.contact_id IN (".$entityIds."))";
		} else {
			$query .= ")";
		}
		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_tickets method ...");
		return $return_value;
	}
	/**
	* Function to get Account related Products
	* @param  integer   $id      - accountid
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

		$entityIds = $this->getRelatedContactsIds();
		array_push($entityIds, $id);
		$entityIds = implode(',', $entityIds);

		$query = "SELECT jo_products.productid, jo_products.productname, jo_products.productcode, jo_products.commissionrate,
				jo_products.qty_per_unit, jo_products.unit_price, jo_crmentity.crmid, jo_crmentity.smownerid
				FROM jo_products
				INNER JOIN jo_seproductsrel ON jo_products.productid = jo_seproductsrel.productid
				and jo_seproductsrel.setype IN ('Accounts', 'Contacts')
				INNER JOIN jo_productcf ON jo_products.productid = jo_productcf.productid
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_products.productid
				LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				WHERE jo_crmentity.deleted = 0 AND jo_seproductsrel.crmid IN (".$entityIds.")";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_products method ...");
		return $return_value;
	}

	/** Function to export the account records in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Accounts Query.
	*/
	function create_export_query($where)
	{
		global $log;
		global $current_user;
                $log->debug("Entering create_export_query(".$where.") method ...");

		include("includes/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Accounts", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list,case when (jo_users.user_name not like '') then jo_users.user_name else jo_groups.groupname end as user_name
	       			FROM ".$this->entity_table."
				INNER JOIN jo_account
					ON jo_account.accountid = jo_crmentity.crmid
				LEFT JOIN jo_accountbillads
					ON jo_accountbillads.accountaddressid = jo_account.accountid
				LEFT JOIN jo_accountshipads
					ON jo_accountshipads.accountaddressid = jo_account.accountid
				LEFT JOIN jo_accountscf
					ON jo_accountscf.accountid = jo_account.accountid
	                        LEFT JOIN jo_groups
                        	        ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_users
					ON jo_users.id = jo_crmentity.smownerid and jo_users.status = 'Active'
				LEFT JOIN jo_account jo_account2
					ON jo_account2.accountid = jo_account.parentid
				";//jo_account2 is added to get the Member of account

		$query .= $this->getNonAdminAccessControlQuery('Accounts',$current_user);
		$where_auto = " jo_crmentity.deleted = 0 ";

		if($where != "")
			$query .= " WHERE ($where) AND ".$where_auto;
		else
			$query .= " WHERE ".$where_auto;

		$log->debug("Exiting create_export_query method ...");
		return $query;
	}

	/** Function to get the Columnnames of the Account Record
	* Used By vtigerCRM Word Plugin
	* Returns the Merge Fields for Word Plugin
	*/
	function getColumnNames_Acnt()
	{
		global $log,$current_user;
		$log->debug("Entering getColumnNames_Acnt() method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			$sql1 = "SELECT fieldlabel FROM jo_field WHERE tabid = 6 and jo_field.presence in (0,2)";
			$params1 = array();
		}else
		{
			$profileList = getCurrentUserProfileList();
			$sql1 = "select jo_field.fieldid,fieldlabel from jo_field INNER JOIN jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid where jo_field.tabid=6 and jo_field.displaytype in (1,2,4) and jo_profile2field.visible=0 and jo_def_org_field.visible=0 and jo_field.presence in (0,2)";
			$params1 = array();
			if (count($profileList) > 0) {
				$sql1 .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")  group by fieldid";
			    array_push($params1,  $profileList);
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
		$log->debug("Exiting getColumnNames_Acnt method ...");
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

		$rel_table_arr = Array("Contacts"=>"jo_contactdetails","Potentials"=>"jo_potential","Quotes"=>"jo_quotes",
					"SalesOrder"=>"jo_salesorder","Invoice"=>"jo_invoice","Activities"=>"jo_seactivityrel",
					"Documents"=>"jo_senotesrel","Attachments"=>"jo_seattachmentsrel","HelpDesk"=>"jo_troubletickets",
					"Products"=>"jo_seproductsrel","ServiceContracts"=>"jo_servicecontracts","Campaigns"=>"jo_campaignaccountrel",
					"Assets"=>"jo_assets","Project"=>"jo_project", "Emails"=>"jo_seactivityrel");

		$tbl_field_arr = Array("jo_contactdetails"=>"contactid","jo_potential"=>"potentialid","jo_quotes"=>"quoteid",
					"jo_salesorder"=>"salesorderid","jo_invoice"=>"invoiceid","jo_seactivityrel"=>"activityid",
					"jo_senotesrel"=>"notesid","jo_seattachmentsrel"=>"attachmentsid","jo_troubletickets"=>"ticketid",
					"jo_seproductsrel"=>"productid","jo_servicecontracts"=>"servicecontractsid","jo_campaignaccountrel"=>"campaignid",
					"jo_assets"=>"assetsid","jo_project"=>"projectid","jo_seactivityrel"=>"activityid");

		$entity_tbl_field_arr = Array("jo_contactdetails"=>"accountid","jo_potential"=>"related_to","jo_quotes"=>"accountid",
					"jo_salesorder"=>"accountid","jo_invoice"=>"accountid","jo_seactivityrel"=>"crmid",
					"jo_senotesrel"=>"crmid","jo_seattachmentsrel"=>"crmid","jo_troubletickets"=>"parent_id",
					"jo_seproductsrel"=>"crmid","jo_servicecontracts"=>"sc_related_to","jo_campaignaccountrel"=>"accountid",
					"jo_assets"=>"account","jo_project"=>"linktoaccountscontacts","jo_seactivityrel"=>"crmid");

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
	 * Function to get the relation tables for related modules
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables =  array (
			"Contacts" => array("jo_contactdetails"=>array("accountid","contactid"),"jo_account"=>"accountid"),
			"Potentials" => array("jo_potential"=>array("related_to","potentialid"),"jo_account"=>"accountid"),
			"Quotes" => array("jo_quotes"=>array("accountid","quoteid"),"jo_account"=>"accountid"),
			"SalesOrder" => array("jo_salesorder"=>array("accountid","salesorderid"),"jo_account"=>"accountid"),
			"Invoice" => array("jo_invoice"=>array("accountid","invoiceid"),"jo_account"=>"accountid"),
			"Calendar" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_account"=>"accountid"),
			"HelpDesk" => array("jo_troubletickets"=>array("parent_id","ticketid"),"jo_account"=>"accountid"),
			"Products" => array("jo_seproductsrel"=>array("crmid","productid"),"jo_account"=>"accountid"),
			"Documents" => array("jo_senotesrel"=>array("crmid","notesid"),"jo_account"=>"accountid"),
			"Campaigns" => array("jo_campaignaccountrel"=>array("accountid","campaignid"),"jo_account"=>"accountid"),
			"Emails" => array("jo_seactivityrel"=>array("crmid","activityid"),"jo_account"=>"accountid"),
		);
		return $rel_tables[$secmodule];
	}

	/*
	 * Function to get the secondary query part of a report
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule,$queryPlanner){

		$matrix = $queryPlanner->newDependencyMatrix();
		$matrix->setDependency('jo_crmentityAccounts', array('jo_groupsAccounts', 'jo_usersAccounts', 'jo_lastModifiedByAccounts'));
		$matrix->setDependency('jo_account', array('jo_crmentityAccounts',' jo_accountbillads', 'jo_accountshipads', 'jo_accountscf', 'jo_accountAccounts', 'jo_email_trackAccounts'));

		if (!$queryPlanner->requireTable('jo_account', $matrix)) {
			return '';
		}

         // Activities related to contact should linked to accounts if contact is related to that account
        if($module == "Calendar"){
            // query to get all the contacts related to Accounts
            $relContactsQuery = "SELECT contactid FROM jo_contactdetails as jo_tmpContactCalendar
                        INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_tmpContactCalendar.contactid
                        WHERE jo_tmpContactCalendar.accountid IS NOT NULL AND jo_tmpContactCalendar.accountid !=''
                        AND jo_crmentity.deleted=0";

            $query = " left join jo_cntactivityrel as jo_tmpcntactivityrel ON
                jo_activity.activityid = jo_tmpcntactivityrel.activityid AND
                jo_tmpcntactivityrel.contactid IN ($relContactsQuery)
                left join jo_contactdetails as jo_tmpcontactdetails on jo_tmpcntactivityrel.contactid = jo_tmpcontactdetails.contactid ";
        }else {
            $query = "";
        }

		$query .= $this->getRelationQuery($module,$secmodule,"jo_account","accountid", $queryPlanner);

        if($module == "Calendar"){
            $query .= " OR jo_account.accountid = jo_tmpcontactdetails.accountid " ;
        }
        // End

		if ($queryPlanner->requireTable('jo_crmentityAccounts', $matrix)) {
			$query .= " left join jo_crmentity as jo_crmentityAccounts on jo_crmentityAccounts.crmid=jo_account.accountid and jo_crmentityAccounts.deleted=0";
		}
		if ($queryPlanner->requireTable('jo_accountbillads')) {
			$query .= " left join jo_accountbillads on jo_account.accountid=jo_accountbillads.accountaddressid";
		}
		if ($queryPlanner->requireTable('jo_accountshipads')) {
			$query .= " left join jo_accountshipads on jo_account.accountid=jo_accountshipads.accountaddressid";
		}
		if ($queryPlanner->requireTable('jo_accountscf')) {
			$query .= " left join jo_accountscf on jo_account.accountid = jo_accountscf.accountid";
		}
		if ($queryPlanner->requireTable('jo_accountAccounts', $matrix)) {
			$query .= "	left join jo_account as jo_accountAccounts on jo_accountAccounts.accountid = jo_account.parentid";
		}
		if ($queryPlanner->requireTable('jo_email_track')) {
			$query .= " LEFT JOIN jo_email_track AS jo_email_trackAccounts ON jo_email_trackAccounts .crmid = jo_account.accountid";
		}
		if ($queryPlanner->requireTable('jo_groupsAccounts')) {
			$query .= "	left join jo_groups as jo_groupsAccounts on jo_groupsAccounts.groupid = jo_crmentityAccounts.smownerid";
		}
		if ($queryPlanner->requireTable('jo_usersAccounts')) {
			$query .= " left join jo_users as jo_usersAccounts on jo_usersAccounts.id = jo_crmentityAccounts.smownerid";
		}
		if ($queryPlanner->requireTable('jo_lastModifiedByAccounts')) {
            $query .= " left join jo_users as jo_lastModifiedByAccounts on jo_lastModifiedByAccounts.id = jo_crmentityAccounts.modifiedby ";
		}
        if ($queryPlanner->requireTable("jo_createdbyAccounts")){
			$query .= " left join jo_users as jo_createdbyAccounts on jo_createdbyAccounts.id = jo_crmentityAccounts.smcreatorid ";
		}

		return $query;
	}

	/**
	* Function to get Account hierarchy of the given Account
	* @param  integer   $id      - accountid
	* returns Account hierarchy in array format
	*/
	function getAccountHierarchy($id) {
		global $log, $adb, $current_user;
        $log->debug("Entering getAccountHierarchy(".$id.") method ...");
		require('user_privileges/user_privileges_'.$current_user->id.'.php');

		$tabname = getParentTab();
		$listview_header = Array();
		$listview_entries = array();

		foreach ($this->list_fields_name as $fieldname=>$colname) {
			if(getFieldVisibilityPermission('Accounts', $current_user->id, $colname) == '0') {
				$listview_header[] = getTranslatedString($fieldname);
			}
		}

		$accounts_list = Array();

		// Get the accounts hierarchy from the top most account in the hierarch of the current account, including the current account
		$encountered_accounts = array($id);
		$accounts_list = $this->__getParentAccounts($id, $accounts_list, $encountered_accounts);

		// Get the accounts hierarchy (list of child accounts) based on the current account
		$accounts_list = $this->__getChildAccounts($id, $accounts_list, $accounts_list[$id]['depth']);

		// Create array of all the accounts in the hierarchy
		foreach($accounts_list as $account_id => $account_info) {
			$account_info_data = array();

			$hasRecordViewAccess = (is_admin($current_user)) || (isPermitted('Accounts', 'DetailView', $account_id) == 'yes');

			foreach ($this->list_fields_name as $fieldname=>$colname) {
				// Permission to view account is restricted, avoid showing field values (except account name)
				if(!$hasRecordViewAccess && $colname != 'accountname') {
					$account_info_data[] = '';
				} else if(getFieldVisibilityPermission('Accounts', $current_user->id, $colname) == '0') {
					$data = $account_info[$colname];
					if ($colname == 'accountname') {
						if ($account_id != $id) {
							if($hasRecordViewAccess) {
								$data = '<a href="index.php?module=Accounts&action=DetailView&record='.$account_id.'&parenttab='.$tabname.'">'.$data.'</a>';
							} else {
								$data = '<i>'.$data.'</i>';
							}
						} else {
							$data = '<b>'.$data.'</b>';
						}
						// - to show the hierarchy of the Accounts
						$account_depth = str_repeat(" .. ", $account_info['depth'] * 2);
						$data = $account_depth . $data;
					} else if ($colname == 'website') {
						$data = '<a href="http://'. $data .'" target="_blank">'.$data.'</a>';
					}
					$account_info_data[] = $data;
				}
			}
			$listview_entries[$account_id] = $account_info_data;
		}

		$account_hierarchy = array('header'=>$listview_header,'entries'=>$listview_entries);
        $log->debug("Exiting getAccountHierarchy method ...");
		return $account_hierarchy;
	}

	/**
	* Function to Recursively get all the upper accounts of a given Account
	* @param  integer   $id      		- accountid
	* @param  array   $parent_accounts   - Array of all the parent accounts
	* returns All the parent accounts of the given accountid in array format
	*/
	function __getParentAccounts($id, &$parent_accounts, &$encountered_accounts) {
		global $log, $adb;
        $log->debug("Entering __getParentAccounts(".$id.",".$parent_accounts.") method ...");

		$query = "SELECT parentid FROM jo_account " .
				" INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_account.accountid" .
				" WHERE jo_crmentity.deleted = 0 and jo_account.accountid = ?";
		$params = array($id);

		$res = $adb->pquery($query, $params);

		if ($adb->num_rows($res) > 0 &&
			$adb->query_result($res, 0, 'parentid') != '' && $adb->query_result($res, 0, 'parentid') != 0 &&
			!in_array($adb->query_result($res, 0, 'parentid'),$encountered_accounts)) {

			$parentid = $adb->query_result($res, 0, 'parentid');
			$encountered_accounts[] = $parentid;
			$this->__getParentAccounts($parentid,$parent_accounts,$encountered_accounts);
		}

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_account.*, jo_accountbillads.*," .
				" CASE when (jo_users.user_name not like '') THEN $userNameSql ELSE jo_groups.groupname END as user_name " .
				" FROM jo_account" .
				" INNER JOIN jo_crmentity " .
				" ON jo_crmentity.crmid = jo_account.accountid" .
				" INNER JOIN jo_accountbillads" .
				" ON jo_account.accountid = jo_accountbillads.accountaddressid " .
				" LEFT JOIN jo_groups" .
				" ON jo_groups.groupid = jo_crmentity.smownerid" .
				" LEFT JOIN jo_users" .
				" ON jo_users.id = jo_crmentity.smownerid" .
				" WHERE jo_crmentity.deleted = 0 and jo_account.accountid = ?";
		$params = array($id);
		$res = $adb->pquery($query, $params);

		$parent_account_info = array();
		$depth = 0;
		$immediate_parentid = $adb->query_result($res, 0, 'parentid');
		if (isset($parent_accounts[$immediate_parentid])) {
			$depth = $parent_accounts[$immediate_parentid]['depth'] + 1;
		}
		$parent_account_info['depth'] = $depth;
		foreach($this->list_fields_name as $fieldname=>$columnname) {
			if ($columnname == 'assigned_user_id') {
				$parent_account_info[$columnname] = $adb->query_result($res, 0, 'user_name');
			} else {
				$parent_account_info[$columnname] = $adb->query_result($res, 0, $columnname);
			}
		}
		$parent_accounts[$id] = $parent_account_info;
        $log->debug("Exiting __getParentAccounts method ...");
		return $parent_accounts;
	}

	/**
	* Function to Recursively get all the child accounts of a given Account
	* @param  integer   $id      		- accountid
	* @param  array   $child_accounts   - Array of all the child accounts
	* @param  integer   $depth          - Depth at which the particular account has to be placed in the hierarchy
	* returns All the child accounts of the given accountid in array format
	*/
	function __getChildAccounts($id, &$child_accounts, $depth) {
		global $log, $adb;
        $log->debug("Entering __getChildAccounts(".$id.",".$child_accounts.",".$depth.") method ...");

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>
							'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$query = "SELECT jo_account.*, jo_accountbillads.*," .
				" CASE when (jo_users.user_name not like '') THEN $userNameSql ELSE jo_groups.groupname END as user_name " .
				" FROM jo_account" .
				" INNER JOIN jo_crmentity " .
				" ON jo_crmentity.crmid = jo_account.accountid" .
				" INNER JOIN jo_accountbillads" .
				" ON jo_account.accountid = jo_accountbillads.accountaddressid " .
				" LEFT JOIN jo_groups" .
				" ON jo_groups.groupid = jo_crmentity.smownerid" .
				" LEFT JOIN jo_users" .
				" ON jo_users.id = jo_crmentity.smownerid" .
				" WHERE jo_crmentity.deleted = 0 and parentid = ?";
		$params = array($id);
		$res = $adb->pquery($query, $params);

		$num_rows = $adb->num_rows($res);

		if ($num_rows > 0) {
			$depth = $depth + 1;
			for($i=0;$i<$num_rows;$i++) {
				$child_acc_id = $adb->query_result($res, $i, 'accountid');
				if(array_key_exists($child_acc_id,$child_accounts)) {
					continue;
				}
				$child_account_info = array();
				$child_account_info['depth'] = $depth;
				foreach($this->list_fields_name as $fieldname=>$columnname) {
					if ($columnname == 'assigned_user_id') {
						$child_account_info[$columnname] = $adb->query_result($res, $i, 'user_name');
					} else {
						$child_account_info[$columnname] = $adb->query_result($res, $i, $columnname);
					}
				}
				$child_accounts[$child_acc_id] = $child_account_info;
				$this->__getChildAccounts($child_acc_id, $child_accounts, $depth);
			}
		}
        $log->debug("Exiting __getChildAccounts method ...");
		return $child_accounts;
	}

	// Function to unlink the dependent records of the given record by id
	function unlinkDependencies($module, $id) {
		global $log;

		//Deleting Account related Potentials.
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
		//Backup deleted Account related Potentials.
		$params = array($id, RB_RECORD_UPDATED, 'jo_crmentity', 'deleted', 'crmid', implode(",", $pot_ids_list));
		$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Deleting Account related Quotes.
		$quo_q = 'SELECT jo_crmentity.crmid FROM jo_crmentity
			INNER JOIN jo_quotes ON jo_crmentity.crmid=jo_quotes.quoteid
			INNER JOIN jo_account ON jo_account.accountid=jo_quotes.accountid
			WHERE jo_crmentity.deleted=0 AND jo_quotes.accountid=?';
		$quo_res = $this->db->pquery($quo_q, array($id));
		$quo_ids_list = array();
		for($k=0;$k < $this->db->num_rows($quo_res);$k++)
		{
			$quo_id = $this->db->query_result($quo_res,$k,"crmid");
			$quo_ids_list[] = $quo_id;
			$sql = 'UPDATE jo_crmentity SET deleted = 1 WHERE crmid = ?';
			$this->db->pquery($sql, array($quo_id));
		}
		//Backup deleted Account related Quotes.
		$params = array($id, RB_RECORD_UPDATED, 'jo_crmentity', 'deleted', 'crmid', implode(",", $quo_ids_list));
		$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);

		//Backup Contact-Account Relation
		$con_q = 'SELECT contactid FROM jo_contactdetails WHERE accountid = ?';
		$con_res = $this->db->pquery($con_q, array($id));
		if ($this->db->num_rows($con_res) > 0) {
			$con_ids_list = array();
			for($k=0;$k < $this->db->num_rows($con_res);$k++)
			{
				$con_ids_list[] = $this->db->query_result($con_res,$k,"contactid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_contactdetails', 'accountid', 'contactid', implode(",", $con_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);
		}
		//Deleting Contact-Account Relation.
		$con_q = 'UPDATE jo_contactdetails SET accountid = 0 WHERE accountid = ?';
		$this->db->pquery($con_q, array($id));

		//Backup Trouble Tickets-Account Relation
		$tkt_q = 'SELECT ticketid FROM jo_troubletickets WHERE parent_id = ?';
		$tkt_res = $this->db->pquery($tkt_q, array($id));
		if ($this->db->num_rows($tkt_res) > 0) {
			$tkt_ids_list = array();
			for($k=0;$k < $this->db->num_rows($tkt_res);$k++)
			{
				$tkt_ids_list[] = $this->db->query_result($tkt_res,$k,"ticketid");
			}
			$params = array($id, RB_RECORD_UPDATED, 'jo_troubletickets', 'parent_id', 'ticketid', implode(",", $tkt_ids_list));
			$this->db->pquery('INSERT INTO jo_relatedlists_rb VALUES(?,?,?,?,?,?)', $params);
		}
		//Deleting Trouble Tickets-Account Relation.
		$tt_q = 'UPDATE jo_troubletickets SET parent_id = 0 WHERE parent_id = ?';
		$this->db->pquery($tt_q, array($id));

		parent::unlinkDependencies($module, $id);
	}

	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;

		if($return_module == 'Campaigns') {
			$sql = 'DELETE FROM jo_campaignaccountrel WHERE accountid=? AND campaignid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else if($return_module == 'Products') {
			$sql = 'DELETE FROM jo_seproductsrel WHERE crmid=? AND productid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} elseif($return_module == 'Documents') {
			$sql = 'DELETE FROM jo_senotesrel WHERE crmid=? AND notesid=?';
			$this->db->pquery($sql, array($id, $return_id));
		} else {
			parent::unlinkRelationship($id, $return_module, $return_id);
		}
	}

	function save_related_module($module, $crmid, $with_module, $with_crmids, $otherParams = array()) {
		$adb = $this->db;

		if(!is_array($with_crmids)) $with_crmids = Array($with_crmids);
		foreach($with_crmids as $with_crmid) {
			if($with_module == 'Products')
				$adb->pquery('INSERT INTO jo_seproductsrel VALUES(?,?,?,?)', array($crmid, $with_crmid, $module, 1));
			elseif($with_module == 'Campaigns') {
				$checkResult = $adb->pquery('SELECT 1 FROM jo_campaignaccountrel WHERE campaignid = ? AND accountid = ?',
												array($with_crmid, $crmid));
				if($checkResult && $adb->num_rows($checkResult) > 0) {
					continue;
				}
				$adb->pquery("insert into jo_campaignaccountrel values(?,?,1)", array($with_crmid, $crmid));
			} else {
				parent::save_related_module($module, $crmid, $with_module, $with_crmid);
			}
		}
	}

	function getListButtons($app_strings,$mod_strings = false) {
		$list_buttons = Array();

		if(isPermitted('Accounts','Delete','') == 'yes') {
			$list_buttons['del'] = $app_strings[LBL_MASS_DELETE];
		}
		if(isPermitted('Accounts','EditView','') == 'yes') {
			$list_buttons['mass_edit'] = $app_strings[LBL_MASS_EDIT];
			$list_buttons['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
		}
		if(isPermitted('Emails','EditView','') == 'yes') {
			$list_buttons['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];
		}
		// mailer export
		if(isPermitted('Accounts','Export','') == 'yes') {
			$list_buttons['mailer_exp'] = $mod_strings[LBL_MAILER_EXPORT];
		}
		// end of mailer export
		return $list_buttons;
	}

	/* Function to get attachments in the related list of accounts module */
	function get_attachments($id, $cur_tab_id, $rel_tab_id, $actions = false) {

		global $currentModule, $app_strings, $singlepane_view;
		$this_module = $currentModule;
		$parenttab = getParentTab();

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		$other = CRMEntity::getInstance($related_module);

		// Some standard module class doesn't have required variables
		// that are used in the query, they are defined in this generic API
		vtlib_setup_modulevars($related_module, $other);

		$singular_modname = vtlib_toSingular($related_module);
		$button = '';
		if ($actions) {
			if (is_string($actions))
				$actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "'>&nbsp;";
			}
			if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
				$button .= "<input type='hidden' name='createmode' id='createmode' value='link' />" .
						"<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
						" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "'>&nbsp;";
			}
		}

		// To make the edit or del link actions to return back to same view.
		if ($singlepane_view == 'true'){
			$returnset = "&return_module=$this_module&return_action=DetailView&return_id=$id";
		} else {
			$returnset = "&return_module=$this_module&return_action=CallRelatedList&return_id=$id";
		}

		$entityIds = $this->getRelatedContactsIds();
		array_push($entityIds, $id);
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=> 'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,
				'Documents' ActivityType,jo_attachments.type  FileType,crm2.modifiedtime lastmodified,jo_crmentity.modifiedtime,
				jo_seattachmentsrel.attachmentsid attachmentsid, jo_notes.notesid crmid, jo_notes.notecontent description,jo_notes.*
				from jo_notes
				INNER JOIN jo_senotesrel ON jo_senotesrel.notesid= jo_notes.notesid
				LEFT JOIN jo_notescf ON jo_notescf.notesid= jo_notes.notesid
				INNER JOIN jo_crmentity ON jo_crmentity.crmid= jo_notes.notesid and jo_crmentity.deleted=0
				INNER JOIN jo_crmentity crm2 ON crm2.crmid=jo_senotesrel.crmid
				LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				LEFT JOIN jo_seattachmentsrel ON jo_seattachmentsrel.crmid =jo_notes.notesid
				LEFT JOIN jo_attachments ON jo_seattachmentsrel.attachmentsid = jo_attachments.attachmentsid
				LEFT JOIN jo_users ON jo_crmentity.smownerid= jo_users.id
				WHERE crm2.crmid IN (".$entityIds.")";

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if ($return_value == null)
			$return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;
		return $return_value;
	}

	/**
	 * Function to handle the merged list for the module.
	 * NOTE: UI type '10' is used to stored the references to other modules for a given record.
	 * These dependent records can be retrieved through this function.
	 * For eg: A trouble ticket can be related to an Account or a Contact.
	 * From a given Contact/Account if we need to fetch all such dependent trouble tickets, get_dependents_list function can be used.
	 */
	function get_merged_list($id, $cur_tab_id, $rel_tab_id, $actions = false) {

		global $currentModule, $app_strings, $singlepane_view, $current_user;

		$parenttab = getParentTab();

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		$other = CRMEntity::getInstance($related_module);

		// Some standard module class doesn't have required variables
		// that are used in the query, they are defined in this generic API
		vtlib_setup_modulevars($currentModule, $this);
		vtlib_setup_modulevars($related_module, $other);

		$singular_modname = 'SINGLE_' . $related_module;
		$button = '';

		// To make the edit or del link actions to return back to same view.
		if ($singlepane_view == 'true')
			$returnset = "&return_module=$currentModule&return_action=DetailView&return_id=$id";
		else
			$returnset = "&return_module=$currentModule&return_action=CallRelatedList&return_id=$id";

		$return_value = null;
		$dependentFieldSql = $this->db->pquery("SELECT tabid, fieldname, columnname FROM jo_field WHERE uitype='10' AND" .
				" fieldid IN (SELECT fieldid FROM jo_fieldmodulerel WHERE relmodule=? AND module=?)", array($currentModule, $related_module));
		$numOfFields = $this->db->num_rows($dependentFieldSql);

		if ($numOfFields > 0) {
			$dependentColumn = $this->db->query_result($dependentFieldSql, 0, 'columnname');
			$dependentField = $this->db->query_result($dependentFieldSql, 0, 'fieldname');

			$button .= '<input type="hidden" name="' . $dependentColumn . '" id="' . $dependentColumn . '" value="' . $id . '">';
			$button .= '<input type="hidden" name="' . $dependentColumn . '_type" id="' . $dependentColumn . '_type" value="' . $currentModule . '">';
			if ($actions) {
				if (is_string($actions))
					$actions = explode(',', strtoupper($actions));
				if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes'
						&& getFieldVisibilityPermission($related_module, $current_user->id, $dependentField, 'readwrite') == '0') {
					$button .= "<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname, $related_module) . "' class='crmbutton small create'" .
							" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
							" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname, $related_module) . "'>&nbsp;";
				}
			}

			$entityIds = $this->getRelatedContactsIds();
			$entityIds = implode(',', $entityIds);

			$contactColumn = null;
			$contactTableName = null;
			$otherModuleModel = Head_Module_Model::getInstance($related_module);
			$referenceFields = $otherModuleModel->getFieldsByType('reference');
			foreach ($referenceFields as $referenceFieldName => $fieldModel) {
				$referenceList = $fieldModel->getReferenceList();
				foreach($referenceList as $referencedModule) {
					if($referencedModule == 'Contacts' && (!$fieldModel->isCustomField())){
						$contactColumn = $fieldModel->get('column');
						$contactTableName = $fieldModel->get('table');
					}
				}
			}

			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name','last_name' => 'jo_users.last_name'), 'Users');

			$query = "SELECT jo_crmentity.*, $other->table_name.*";
			$query .= ", CASE WHEN (jo_users.user_name NOT LIKE '') THEN $userNameSql ELSE jo_groups.groupname END AS user_name";

			$more_relation = '';
			if (!empty($other->related_tables)) {
				foreach ($other->related_tables as $tname => $relmap) {
					$query .= ", $tname.*";

					// Setup the default JOIN conditions if not specified
					if (empty($relmap[1]))
						$relmap[1] = $other->table_name;
					if (empty($relmap[2]))
						$relmap[2] = $relmap[0];
					$more_relation .= " LEFT JOIN $tname ON $tname.$relmap[0] = $relmap[1].$relmap[2]";
				}
			}

			$query .= " FROM $other->table_name";
			$query .= " INNER JOIN jo_crmentity ON jo_crmentity.crmid = $other->table_name.$other->table_index";
			$query .= $more_relation;
			$query .= " LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid";
			$query .= " LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";
			$query .= " WHERE jo_crmentity.deleted = 0 AND ( ($other->table_name.$dependentColumn = $id) ";

			if(!empty($entityIds)) {
				$query .= " OR ($contactTableName.$contactColumn  IN (".$entityIds."))";
			}
			$query .= " )";
			$return_value = GetRelatedList($currentModule, $related_module, $other, $query, $button, $returnset);
		}
		if ($return_value == null)
			$return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		return $return_value;
	}

	/**
	 * Function to handle the related list for the module.
	 * NOTE: Head_Module::setRelatedList sets reference to this function in jo_relatedlists table
	 * if function name is not explicitly specified.
	 */
	function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions = false) {

		global $currentModule, $app_strings, $singlepane_view;

		$parenttab = getParentTab();

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		$other = CRMEntity::getInstance($related_module);

		// Some standard module class doesn't have required variables
		// that are used in the query, they are defined in this generic API
		vtlib_setup_modulevars($currentModule, $this);
		vtlib_setup_modulevars($related_module, $other);

		$singular_modname = 'SINGLE_' . $related_module;

		$button = '';
		if ($actions) {
			if (is_string($actions))
				$actions = explode(',', strtoupper($actions));
			if (in_array('SELECT', $actions) && isPermitted($related_module, 4, '') == 'yes') {
				$button .= "<input title='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module) . "' class='crmbutton small edit' " .
						" type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\"" .
						" value='" . getTranslatedString('LBL_SELECT') . " " . getTranslatedString($related_module, $related_module) . "'>&nbsp;";
			}
			if (in_array('ADD', $actions) && isPermitted($related_module, 1, '') == 'yes') {
				$button .= "<input type='hidden' name='createmode' id='createmode' value='link' />" .
						"<input title='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname) . "' class='crmbutton small create'" .
						" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
						" value='" . getTranslatedString('LBL_ADD_NEW') . " " . getTranslatedString($singular_modname, $related_module) . "'>&nbsp;";
			}
		}

		// To make the edit or del link actions to return back to same view.
		if ($singlepane_view == 'true') {
			$returnset = "&return_module=$currentModule&return_action=DetailView&return_id=$id";
		} else {
			$returnset = "&return_module=$currentModule&return_action=CallRelatedList&return_id=$id";
		}

		$more_relation = '';
		if (!empty($other->related_tables)) {
			foreach ($other->related_tables as $tname => $relmap) {
				$query .= ", $tname.*";

				// Setup the default JOIN conditions if not specified
				if (empty($relmap[1]))
					$relmap[1] = $other->table_name;
				if (empty($relmap[2]))
					$relmap[2] = $relmap[0];
				$more_relation .= " LEFT JOIN $tname ON $tname.$relmap[0] = $relmap[1].$relmap[2]";
			}
		}

		$entityIds = $this->getRelatedContactsIds();
		array_push($entityIds, $id);
		$entityIds = implode(',', $entityIds);

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

		$query = "SELECT jo_crmentity.*, $other->table_name.*,
				CASE WHEN (jo_users.user_name NOT LIKE '') THEN $userNameSql ELSE jo_groups.groupname END AS user_name FROM $other->table_name
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = $other->table_name.$other->table_index
				INNER JOIN jo_crmentityrel ON (jo_crmentityrel.relcrmid = jo_crmentity.crmid OR jo_crmentityrel.crmid = jo_crmentity.crmid)
				$more_relation
				LEFT  JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
				LEFT  JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
				WHERE jo_crmentity.deleted = 0 AND (jo_crmentityrel.crmid IN (" .$entityIds. ") OR jo_crmentityrel.relcrmid IN (". $entityIds . "))";

		$return_value = GetRelatedList($currentModule, $related_module, $other, $query, $button, $returnset);

		if ($return_value == null)
			$return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		return $return_value;
	}

	/* Function to get related contact ids for an account record*/
	function getRelatedContactsIds($id = null) {
		global $adb;
		if($id ==null)
		$id = $this->id;
		$entityIds = array();
		$query = 'SELECT contactid FROM jo_contactdetails
				INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_contactdetails.contactid
				WHERE jo_contactdetails.accountid = ? AND jo_crmentity.deleted = 0';
		$accountContacts = $adb->pquery($query, array($id));
		$numOfContacts = $adb->num_rows($accountContacts);
		if($accountContacts && $numOfContacts > 0) {
			for($i=0; $i < $numOfContacts; ++$i) {
				array_push($entityIds, $adb->query_result($accountContacts, $i, 'contactid'));
			}
		}
		return $entityIds;
	}

	function getRelatedPotentialIds($id) {
		$relatedIds = array();
		$db = PearDatabase::getInstance();
		$query = "SELECT DISTINCT jo_crmentity.crmid FROM jo_potential INNER JOIN jo_crmentity ON 
					jo_crmentity.crmid = jo_potential.potentialid LEFT JOIN jo_account ON jo_account.accountid = 
					jo_potential.related_to WHERE jo_crmentity.deleted = 0 AND jo_potential.related_to = ?";
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
					jo_crmentity.crmid = jo_troubletickets.ticketid WHERE jo_crmentity.deleted = 0 AND 
					jo_troubletickets.parent_id = ?";
		$result = $db->pquery($query, array($id));
		for ($i = 0; $i < $db->num_rows($result); $i++) {
			$relatedIds[] = $db->query_result($result, $i, 'crmid');
		}
		return $relatedIds;
	}
}

?>
