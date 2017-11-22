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
 * $Header: /cvsroot/vtigercrm/jo_crm/includes/utils/ListViewUtils.php,v 1.32 2006/02/03 06:53:08 mangai Exp $
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('includes/database/PearDatabase.php');
require_once('includes/ComboUtil.php'); //new
require_once('includes/utils/CommonUtils.php'); //new
require_once('includes/utils/UserInfoUtil.php');
require_once('includes/Zend/Json.php');

/** Function to get the list query for a module
 * @param $module -- module name:: Type string
 * @param $where -- where:: Type string
 * @returns $query -- query:: Type query
 */
function getListQuery($module, $where = '') {
	global $log;
	$log->debug("Entering getListQuery(" . $module . "," . $where . ") method ...");

	global $current_user;
	require('user_privileges/user_privileges_' . $current_user->id . '.php');
	require('user_privileges/sharing_privileges_' . $current_user->id . '.php');
	$tab_id = getTabid($module);
	$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'jo_users.first_name', 'last_name' =>
				'jo_users.last_name'), 'Users');
	switch ($module) {
		Case "HelpDesk":
			$query = "SELECT jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_troubletickets.title, jo_troubletickets.status,
			jo_troubletickets.priority, jo_troubletickets.parent_id,
			jo_contactdetails.contactid, jo_contactdetails.firstname,
			jo_contactdetails.lastname, jo_account.accountid,
			jo_account.accountname, jo_ticketcf.*, jo_troubletickets.ticket_no
			FROM jo_troubletickets
			INNER JOIN jo_ticketcf
				ON jo_ticketcf.ticketid = jo_troubletickets.ticketid
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_troubletickets.ticketid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_contactdetails
				ON jo_troubletickets.parent_id = jo_contactdetails.contactid
			LEFT JOIN jo_account
				ON jo_account.accountid = jo_troubletickets.parent_id
			LEFT JOIN jo_users
				ON jo_crmentity.smownerid = jo_users.id
			LEFT JOIN jo_products
				ON jo_products.productid = jo_troubletickets.product_id";
			$query .= ' ' . getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;

		Case "Accounts":
			//Query modified to sort by assigned to
			$query = "SELECT jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_account.accountname, jo_account.email1,
			jo_account.email2, jo_account.website, jo_account.phone,
			jo_accountbillads.bill_city,
			jo_accountscf.*
			FROM jo_account
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_account.accountid
			INNER JOIN jo_accountbillads
				ON jo_account.accountid = jo_accountbillads.accountaddressid
			INNER JOIN jo_accountshipads
				ON jo_account.accountid = jo_accountshipads.accountaddressid
			INNER JOIN jo_accountscf
				ON jo_account.accountid = jo_accountscf.accountid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_account jo_account2
				ON jo_account.parentid = jo_account2.accountid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;

		Case "Potentials":
			//Query modified to sort by assigned to
			$query = "SELECT jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_account.accountname,
			jo_potential.related_to, jo_potential.potentialname,
			jo_potential.sales_stage, jo_potential.amount,
			jo_potential.currency, jo_potential.closingdate,
			jo_potential.typeofrevenue, jo_potential.contact_id,
			jo_potentialscf.*
			FROM jo_potential
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_potential.potentialid
			INNER JOIN jo_potentialscf
				ON jo_potentialscf.potentialid = jo_potential.potentialid
			LEFT JOIN jo_account
				ON jo_potential.related_to = jo_account.accountid
			LEFT JOIN jo_contactdetails
				ON jo_potential.contact_id = jo_contactdetails.contactid
			LEFT JOIN jo_campaign
				ON jo_campaign.campaignid = jo_potential.campaignid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;

		Case "Leads":
			$query = "SELECT jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_leaddetails.firstname, jo_leaddetails.lastname,
			jo_leaddetails.company, jo_leadaddress.phone,
			jo_leadsubdetails.website, jo_leaddetails.email,
			jo_leadscf.*
			FROM jo_leaddetails
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_leaddetails.leadid
			INNER JOIN jo_leadsubdetails
				ON jo_leadsubdetails.leadsubscriptionid = jo_leaddetails.leadid
			INNER JOIN jo_leadaddress
				ON jo_leadaddress.leadaddressid = jo_leadsubdetails.leadsubscriptionid
			INNER JOIN jo_leadscf
				ON jo_leaddetails.leadid = jo_leadscf.leadid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 AND jo_leaddetails.converted = 0 " . $where;
			break;
		Case "Products":
			$query = "SELECT jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.description, jo_products.*, jo_productcf.*
			FROM jo_products
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_products.productid
			INNER JOIN jo_productcf
				ON jo_products.productid = jo_productcf.productid
			LEFT JOIN jo_vendor
				ON jo_vendor.vendorid = jo_products.vendor_id
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid";
			if ((isset($_REQUEST["from_dashboard"]) && $_REQUEST["from_dashboard"] == true) && (isset($_REQUEST["type"]) && $_REQUEST["type"] == "dbrd"))
				$query .= " INNER JOIN jo_inventoryproductrel on jo_inventoryproductrel.productid = jo_products.productid";

			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= " WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Documents":
			$query = "SELECT case when (jo_users.user_name not like '') then $userNameSql else jo_groups.groupname end as user_name,jo_crmentity.crmid, jo_crmentity.modifiedtime,
			jo_crmentity.smownerid,jo_attachmentsfolder.*,jo_notes.*
			FROM jo_notes
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_notes.notesid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_attachmentsfolder
				ON jo_notes.folderid = jo_attachmentsfolder.folderid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Contacts":
			//Query modified to sort by assigned to
			$query = "SELECT jo_contactdetails.firstname, jo_contactdetails.lastname,
			jo_contactdetails.title, jo_contactdetails.accountid,
			jo_contactdetails.email, jo_contactdetails.phone,
			jo_crmentity.smownerid, jo_crmentity.crmid
			FROM jo_contactdetails
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_contactdetails.contactid
			INNER JOIN jo_contactaddress
				ON jo_contactaddress.contactaddressid = jo_contactdetails.contactid
			INNER JOIN jo_contactsubdetails
				ON jo_contactsubdetails.contactsubscriptionid = jo_contactdetails.contactid
			INNER JOIN jo_contactscf
				ON jo_contactscf.contactid = jo_contactdetails.contactid
			LEFT JOIN jo_account
				ON jo_account.accountid = jo_contactdetails.accountid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_contactdetails jo_contactdetails2
				ON jo_contactdetails.reportsto = jo_contactdetails2.contactid
			LEFT JOIN jo_customerdetails
				ON jo_customerdetails.customerid = jo_contactdetails.contactid";
			if ((isset($_REQUEST["from_dashboard"]) && $_REQUEST["from_dashboard"] == true) &&
					(isset($_REQUEST["type"]) && $_REQUEST["type"] == "dbrd")) {
				$query .= " INNER JOIN jo_campaigncontrel on jo_campaigncontrel.contactid = " .
						"jo_contactdetails.contactid";
			}
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Calendar":

			$query = "SELECT jo_activity.activityid as act_id,jo_crmentity.crmid, jo_crmentity.smownerid, jo_crmentity.setype,
		jo_activity.*,
		jo_contactdetails.lastname, jo_contactdetails.firstname,
		jo_contactdetails.contactid,
		jo_account.accountid, jo_account.accountname
		FROM jo_activity
		LEFT JOIN jo_activitycf
			ON jo_activitycf.activityid = jo_activity.activityid
		LEFT JOIN jo_cntactivityrel
			ON jo_cntactivityrel.activityid = jo_activity.activityid
		LEFT JOIN jo_contactdetails
			ON jo_contactdetails.contactid = jo_cntactivityrel.contactid
		LEFT JOIN jo_seactivityrel
			ON jo_seactivityrel.activityid = jo_activity.activityid
		LEFT OUTER JOIN jo_activity_reminder
			ON jo_activity_reminder.activity_id = jo_activity.activityid
		LEFT JOIN jo_crmentity
			ON jo_crmentity.crmid = jo_activity.activityid
		LEFT JOIN jo_users
			ON jo_users.id = jo_crmentity.smownerid
		LEFT JOIN jo_groups
			ON jo_groups.groupid = jo_crmentity.smownerid
		LEFT JOIN jo_users jo_users2
			ON jo_crmentity.modifiedby = jo_users2.id
		LEFT JOIN jo_groups jo_groups2
			ON jo_crmentity.modifiedby = jo_groups2.groupid
		LEFT OUTER JOIN jo_account
			ON jo_account.accountid = jo_contactdetails.accountid
		LEFT OUTER JOIN jo_leaddetails
	       		ON jo_leaddetails.leadid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_account jo_account2
	        	ON jo_account2.accountid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_potential
	       		ON jo_potential.potentialid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_troubletickets
	       		ON jo_troubletickets.ticketid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_salesorder
			ON jo_salesorder.salesorderid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_purchaseorder
			ON jo_purchaseorder.purchaseorderid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_quotes
			ON jo_quotes.quoteid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_invoice
	                ON jo_invoice.invoiceid = jo_seactivityrel.crmid
		LEFT OUTER JOIN jo_campaign
		ON jo_campaign.campaignid = jo_seactivityrel.crmid";

			//added to fix #5135
			if (isset($_REQUEST['from_homepage']) && ($_REQUEST['from_homepage'] ==
					"upcoming_activities" || $_REQUEST['from_homepage'] == "pending_activities")) {
				$query.=" LEFT OUTER JOIN jo_recurringevents
			             ON jo_recurringevents.activityid=jo_activity.activityid";
			}
			//end

			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query.=" WHERE jo_crmentity.deleted = 0 AND activitytype != 'Emails' " . $where;
			break;
		Case "Emails":
			$query = "SELECT DISTINCT jo_crmentity.crmid, jo_crmentity.smownerid,
			jo_activity.activityid, jo_activity.subject,
			jo_activity.date_start,
			jo_contactdetails.lastname, jo_contactdetails.firstname,
			jo_contactdetails.contactid
			FROM jo_activity
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_activity.activityid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_seactivityrel
				ON jo_seactivityrel.activityid = jo_activity.activityid
			LEFT JOIN jo_contactdetails
				ON jo_contactdetails.contactid = jo_seactivityrel.crmid
			LEFT JOIN jo_cntactivityrel
				ON jo_cntactivityrel.activityid = jo_activity.activityid
				AND jo_cntactivityrel.contactid = jo_cntactivityrel.contactid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_salesmanactivityrel
				ON jo_salesmanactivityrel.activityid = jo_activity.activityid
			LEFT JOIN jo_emaildetails
				ON jo_emaildetails.emailid = jo_activity.activityid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_activity.activitytype = 'Emails'";
			$query .= "AND jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Faq":
			/*$query = "SELECT jo_crmentity.crmid, jo_crmentity.createdtime, jo_crmentity.modifiedtime,
			jo_faq.*
			FROM jo_faq
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_faq.id
			LEFT JOIN jo_products
				ON jo_faq.product_id = jo_products.productid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;*/

		Case "Vendors":
			$query = "SELECT jo_crmentity.crmid, jo_vendor.*
			FROM jo_vendor
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_vendor.vendorid
			INNER JOIN jo_vendorcf
				ON jo_vendor.vendorid = jo_vendorcf.vendorid
			WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "PriceBooks":
			$query = "SELECT jo_crmentity.crmid, jo_pricebook.*, jo_currency_info.currency_name
			FROM jo_pricebook
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_pricebook.pricebookid
			INNER JOIN jo_pricebookcf
				ON jo_pricebook.pricebookid = jo_pricebookcf.pricebookid
			LEFT JOIN jo_currency_info
				ON jo_pricebook.currency_id = jo_currency_info.id
			WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Quotes":
			//Query modified to sort by assigned to
			$query = "SELECT jo_crmentity.*,
			jo_quotes.*,
			jo_quotesbillads.*,
			jo_quotesshipads.*,
			jo_potential.potentialname,
			jo_account.accountname,
			jo_currency_info.currency_name
			FROM jo_quotes
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_quotes.quoteid
			INNER JOIN jo_quotesbillads
				ON jo_quotes.quoteid = jo_quotesbillads.quotebilladdressid
			INNER JOIN jo_quotesshipads
				ON jo_quotes.quoteid = jo_quotesshipads.quoteshipaddressid
			LEFT JOIN jo_quotescf
				ON jo_quotes.quoteid = jo_quotescf.quoteid
			LEFT JOIN jo_currency_info
				ON jo_quotes.currency_id = jo_currency_info.id
			LEFT OUTER JOIN jo_account
				ON jo_account.accountid = jo_quotes.accountid
			LEFT OUTER JOIN jo_potential
				ON jo_potential.potentialid = jo_quotes.potentialid
			LEFT JOIN jo_contactdetails
				ON jo_contactdetails.contactid = jo_quotes.contactid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_users as jo_usersQuotes
			        ON jo_usersQuotes.id = jo_quotes.inventorymanager";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "PurchaseOrder":
			//Query modified to sort by assigned to
			$query = "SELECT jo_crmentity.*,
			jo_purchaseorder.*,
			jo_pobillads.*,
			jo_poshipads.*,
			jo_vendor.vendorname,
			jo_currency_info.currency_name
			FROM jo_purchaseorder
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_purchaseorder.purchaseorderid
			LEFT OUTER JOIN jo_vendor
				ON jo_purchaseorder.vendorid = jo_vendor.vendorid
			LEFT JOIN jo_contactdetails
				ON jo_purchaseorder.contactid = jo_contactdetails.contactid
			INNER JOIN jo_pobillads
				ON jo_purchaseorder.purchaseorderid = jo_pobillads.pobilladdressid
			INNER JOIN jo_poshipads
				ON jo_purchaseorder.purchaseorderid = jo_poshipads.poshipaddressid
			LEFT JOIN jo_purchaseordercf
				ON jo_purchaseordercf.purchaseorderid = jo_purchaseorder.purchaseorderid
			LEFT JOIN jo_currency_info
				ON jo_purchaseorder.currency_id = jo_currency_info.id
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "SalesOrder":
			//Query modified to sort by assigned to
			$query = "SELECT jo_crmentity.*,
			jo_salesorder.*,
			jo_sobillads.*,
			jo_soshipads.*,
			jo_quotes.subject AS quotename,
			jo_account.accountname,
			jo_currency_info.currency_name
			FROM jo_salesorder
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_salesorder.salesorderid
			INNER JOIN jo_sobillads
				ON jo_salesorder.salesorderid = jo_sobillads.sobilladdressid
			INNER JOIN jo_soshipads
				ON jo_salesorder.salesorderid = jo_soshipads.soshipaddressid
			LEFT JOIN jo_salesordercf
				ON jo_salesordercf.salesorderid = jo_salesorder.salesorderid
			LEFT JOIN jo_currency_info
				ON jo_salesorder.currency_id = jo_currency_info.id
			LEFT OUTER JOIN jo_quotes
				ON jo_quotes.quoteid = jo_salesorder.quoteid
			LEFT OUTER JOIN jo_account
				ON jo_account.accountid = jo_salesorder.accountid
			LEFT JOIN jo_contactdetails
				ON jo_salesorder.contactid = jo_contactdetails.contactid
			LEFT JOIN jo_potential
				ON jo_potential.potentialid = jo_salesorder.potentialid
			LEFT JOIN jo_invoice_recurring_info
				ON jo_invoice_recurring_info.salesorderid = jo_salesorder.salesorderid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Invoice":
			//Query modified to sort by assigned to
			//query modified -Code contribute by Geoff(http://forums.vtiger.com/viewtopic.php?t=3376)
			$query = "SELECT jo_crmentity.*,
			jo_invoice.*,
			jo_invoicebillads.*,
			jo_invoiceshipads.*,
			jo_salesorder.subject AS salessubject,
			jo_account.accountname,
			jo_currency_info.currency_name
			FROM jo_invoice
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_invoice.invoiceid
			INNER JOIN jo_invoicebillads
				ON jo_invoice.invoiceid = jo_invoicebillads.invoicebilladdressid
			INNER JOIN jo_invoiceshipads
				ON jo_invoice.invoiceid = jo_invoiceshipads.invoiceshipaddressid
			LEFT JOIN jo_currency_info
				ON jo_invoice.currency_id = jo_currency_info.id
			LEFT OUTER JOIN jo_salesorder
				ON jo_salesorder.salesorderid = jo_invoice.salesorderid
			LEFT OUTER JOIN jo_account
			        ON jo_account.accountid = jo_invoice.accountid
			LEFT JOIN jo_contactdetails
				ON jo_contactdetails.contactid = jo_invoice.contactid
			INNER JOIN jo_invoicecf
				ON jo_invoice.invoiceid = jo_invoicecf.invoiceid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Campaigns":
			//Query modified to sort by assigned to
			//query modified -Code contribute by Geoff(http://forums.vtiger.com/viewtopic.php?t=3376)
			$query = "SELECT jo_crmentity.*,
			jo_campaign.*
			FROM jo_campaign
			INNER JOIN jo_crmentity
				ON jo_crmentity.crmid = jo_campaign.campaignid
			INNER JOIN jo_campaignscf
			        ON jo_campaign.campaignid = jo_campaignscf.campaignid
			LEFT JOIN jo_groups
				ON jo_groups.groupid = jo_crmentity.smownerid
			LEFT JOIN jo_users
				ON jo_users.id = jo_crmentity.smownerid
			LEFT JOIN jo_products
				ON jo_products.productid = jo_campaign.product_id";
			$query .= getNonAdminAccessControlQuery($module, $current_user);
			$query .= "WHERE jo_crmentity.deleted = 0 " . $where;
			break;
		Case "Users":
			$query = "SELECT id,user_name,first_name,last_name,email1,phone_mobile,phone_work,is_admin,status,email2,
					jo_user2role.roleid as roleid,jo_role.depth as depth
				 	FROM jo_users
				 	INNER JOIN jo_user2role ON jo_users.id = jo_user2role.userid
				 	INNER JOIN jo_role ON jo_user2role.roleid = jo_role.roleid
					WHERE deleted=0 AND status <> 'Inactive'" . $where;
			break;
		default:
			// vtlib customization: Include the module file
			$focus = CRMEntity::getInstance($module);
			$query = $focus->getListQuery($module, $where);
		// END
	}

	if ($module != 'Users') {
		$query = listQueryNonAdminChange($query, $module);
	}
	$log->debug("Exiting getListQuery method ...");
	return $query;
}

/* * This function stores the variables in session sent in list view url string.
 * Param $lv_array - list view session array
 * Param $noofrows - no of rows
 * Param $max_ent - maximum entires
 * Param $module - module name
 * Param $related - related module
 * Return type void.
 */

function setSessionVar($lv_array, $noofrows, $max_ent, $module = '', $related = '') {
	$start = '';
	if ($noofrows >= 1) {
		$lv_array['start'] = 1;
		$start = 1;
	} elseif ($related != '' && $noofrows == 0) {
		$lv_array['start'] = 1;
		$start = 1;
	} else {
		$lv_array['start'] = 0;
		$start = 0;
	}

	if (isset($_REQUEST['start']) && $_REQUEST['start'] != '') {
		$lv_array['start'] = ListViewSession::getRequestStartPage();
		$start = ListViewSession::getRequestStartPage();
	} elseif ($_SESSION['rlvs'][$module][$related]['start'] != '') {

		if ($related != '') {
			$lv_array['start'] = $_SESSION['rlvs'][$module][$related]['start'];
			$start = $_SESSION['rlvs'][$module][$related]['start'];
		}
	}
	if (isset($_REQUEST['viewname']) && $_REQUEST['viewname'] != '')
		$lv_array['viewname'] = vtlib_purify($_REQUEST['viewname']);

	if ($related == '')
		$_SESSION['lvs'][$_REQUEST['module']] = $lv_array;
	else
		$_SESSION['rlvs'][$module][$related] = $lv_array;

	if ($start < ceil($noofrows / $max_ent) && $start != '') {
		$start = ceil($noofrows / $max_ent);
		if ($related == '')
			$_SESSION['lvs'][$currentModule]['start'] = $start;
	}
}

/* * Function to get the table headers for related listview
 * Param $navigation_arrray - navigation values in array
 * Param $url_qry - url string
 * Param $module - module name
 * Param $action- action file name
 * Param $viewid - view id
 * Returns an string value
 */

function getRelatedTableHeaderNavigation($navigation_array, $url_qry, $module, $related_module, $recordid) {
	global $log, $app_strings, $adb;
	$log->debug("Entering getTableHeaderNavigation(" . $navigation_array . "," . $url_qry . "," . $module . "," . $action_val . "," . $viewid . ") method ...");
	global $theme;
	$relatedTabId = getTabid($related_module);
	$tabid = getTabid($module);

	$relatedListResult = $adb->pquery('SELECT * FROM jo_relatedlists WHERE tabid=? AND
		related_tabid=?', array($tabid, $relatedTabId));
	if (empty($relatedListResult))
		return;
	$relatedListRow = $adb->fetch_row($relatedListResult);
	$header = $relatedListRow['label'];
	$actions = $relatedListRow['actions'];
	$functionName = $relatedListRow['name'];

	$urldata = "module=$module&action={$module}Ajax&file=DetailViewAjax&record={$recordid}&" .
			"ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$relatedListRow['relation_id']}" .
			"&actions={$actions}&{$url_qry}";

	$formattedHeader = str_replace(' ', '', $header);
	$target = 'tbl_' . $module . '_' . $formattedHeader;
	$imagesuffix = $module . '_' . $formattedHeader;

	$output = '<td align="right" style="padding="5px;">';
	if (($navigation_array['prev']) != 0) {
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=1\',\'' . $target . '\',\'' . $imagesuffix . '\');" alt="' . $app_strings['LBL_FIRST'] . '" title="' . $app_strings['LBL_FIRST'] . '"><img src="' . jo_imageurl('start.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=' . $navigation_array['prev'] . '\',\'' . $target . '\',\'' . $imagesuffix . '\');" alt="' . $app_strings['LNK_LIST_PREVIOUS'] . '"title="' . $app_strings['LNK_LIST_PREVIOUS'] . '"><img src="' . jo_imageurl('previous.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
	} else {
		$output .= '<img src="' . jo_imageurl('start_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="' . jo_imageurl('previous_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
	}

	$jsHandler = "return VT_disableFormSubmit(event);";
	$output .= "<input class='small' name='pagenum' type='text' value='{$navigation_array['current']}'
		style='width: 3em;margin-right: 0.7em;' onchange=\"loadRelatedListBlock('{$urldata}&start='+this.value+'','{$target}','{$imagesuffix}');\"
		onkeypress=\"$jsHandler\">";
	$output .= "<span name='listViewCountContainerName' class='small' style='white-space: nowrap;'>";
	$computeCount = $_REQUEST['withCount'];
	if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true
			|| ((boolean) $computeCount) == true) {
		$output .= $app_strings['LBL_LIST_OF'] . ' ' . $navigation_array['verylast'];
	} else {
		$output .= "<img src='" . jo_imageurl('windowRefresh.gif', $theme) . "' alt='" . $app_strings['LBL_HOME_COUNT'] . "'
			onclick=\"loadRelatedListBlock('{$urldata}&withCount=true&start={$navigation_array['current']}','{$target}','{$imagesuffix}');\"
			align='absmiddle' name='" . $module . "_listViewCountRefreshIcon'/>
			<img name='" . $module . "_listViewCountContainerBusy' src='" . jo_imageurl('vtbusy.gif', $theme) . "' style='display: none;'
			align='absmiddle' alt='" . $app_strings['LBL_LOADING'] . "'>";
	}
	$output .= '</span>';

	if (($navigation_array['next']) != 0) {
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=' . $navigation_array['next'] . '\',\'' . $target . '\',\'' . $imagesuffix . '\');"><img src="' . jo_imageurl('next.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
		$output .= '<a href="javascript:;" onClick="loadRelatedListBlock(\'' . $urldata . '&start=' . $navigation_array['verylast'] . '\',\'' . $target . '\',\'' . $imagesuffix . '\');"><img src="' . jo_imageurl('end.gif', $theme) . '" border="0" align="absmiddle"></a>&nbsp;';
	} else {
		$output .= '<img src="' . jo_imageurl('next_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
		$output .= '<img src="' . jo_imageurl('end_disabled.gif', $theme) . '" border="0" align="absmiddle">&nbsp;';
	}
	$output .= '</td>';
	$log->debug("Exiting getTableHeaderNavigation method ...");
	if ($navigation_array['first'] == '')
		return;
	else
		return $output;
}

/* Function to get the Entity Id of a given Entity Name */

function getEntityId($module, $entityName) {
	global $log, $adb;
	$log->info("in getEntityId " . $entityName);

	$query = "select fieldname,tablename,entityidfield from jo_entityname where modulename = ?";
	$result = $adb->pquery($query, array($module));
	$fieldsname = $adb->query_result($result, 0, 'fieldname');
	$tablename = $adb->query_result($result, 0, 'tablename');
	$entityidfield = $adb->query_result($result, 0, 'entityidfield');
	if (!(strpos($fieldsname, ',') === false)) {
		$fieldlists = explode(',', $fieldsname);
		$fieldsname = "trim(concat(";
		$fieldsname = $fieldsname . implode(",' ',", $fieldlists);
		$fieldsname = $fieldsname . "))";
		$entityName = trim($entityName);
	}

	if ($entityName != '') {
		$sql = "select $entityidfield from $tablename INNER JOIN jo_crmentity ON jo_crmentity.crmid = $tablename.$entityidfield " .
				" WHERE jo_crmentity.deleted = 0 and $fieldsname=?";
		$result = $adb->pquery($sql, array($entityName));
		if ($adb->num_rows($result) > 0) {
			$entityId = $adb->query_result($result, 0, $entityidfield);
		}
	}
	if (!empty($entityId))
		return $entityId;
	else
		return 0;
}

function decode_emptyspace_html($str){
	$str = str_replace("&nbsp;", "*#chr*#",$str); // (*#chr*#) used as jargan to replace it back with &nbsp;
	$str = str_replace("\xc2", "*#chr*#",$str); // Ã (for special chrtr)
	$str = decode_html($str);
	return str_replace("*#chr*#", "&nbsp;", $str);
	
}

function decode_html($str) {
	global $default_charset;
	// Direct Popup action or Ajax Popup action should be treated the same.
	if ($_REQUEST['action'] == 'Popup' || $_REQUEST['file'] == 'Popup')
		return html_entity_decode($str);
	else
		return html_entity_decode($str, ENT_QUOTES, $default_charset);
}

function popup_decode_html($str) {
	global $default_charset;
	$slashes_str = popup_from_html($str);
	$slashes_str = htmlspecialchars($slashes_str, ENT_QUOTES, $default_charset);
	return decode_html(br2nl($slashes_str));
}

//function added to check the text length in the listview.
function textlength_check($field_val) {
	global $listview_max_textlength, $default_charset;
	if ($listview_max_textlength && $listview_max_textlength > 0) {
		$temp_val = preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val);
		if (function_exists('mb_strlen')) {
			if (mb_strlen(decode_html($temp_val)) > $listview_max_textlength) {
				$temp_val = mb_substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", decode_html($field_val)), 0, $listview_max_textlength, $default_charset) . '...';
			}
		} elseif (strlen(html_entity_decode($field_val)) > $listview_max_textlength) {
			$temp_val = substr(preg_replace("/(<\/?)(\w+)([^>]*>)/i", "", $field_val), 0, $listview_max_textlength) . '...';
		}
	} else {
		$temp_val = $field_val;
	}
	return $temp_val;
}

/**
 * this function accepts a modulename and a fieldname and returns the first related module for it
 * it expects the uitype of the field to be 10
 * @param string $module - the modulename
 * @param string $fieldname - the field name
 * @return string $data - the first related module
 */
function getFirstModule($module, $fieldname) {
	global $adb;
	$sql = "select fieldid, uitype from jo_field where tabid=? and fieldname=?";
	$result = $adb->pquery($sql, array(getTabid($module), $fieldname));

	if ($adb->num_rows($result) > 0) {
		$uitype = $adb->query_result($result, 0, "uitype");

		if ($uitype == 10) {
			$fieldid = $adb->query_result($result, 0, "fieldid");
			$sql = "select * from jo_fieldmodulerel where fieldid=?";
			$result = $adb->pquery($sql, array($fieldid));
			$count = $adb->num_rows($result);

			if ($count > 0) {
				$data = $adb->query_result($result, 0, "relmodule");
			}
		}
	}
	return $data;
}

function VT_getSimpleNavigationValues($start, $size, $total) {
	$prev = $start - 1;
	if ($prev < 0) {
		$prev = 0;
	}
	if ($total === null) {
		return array('start' => $start, 'first' => $start, 'current' => $start, 'end' => $start, 'end_val' => $size, 'allflag' => 'All',
			'prev' => $prev, 'next' => $start + 1, 'verylast' => 'last');
	}
	if (empty($total)) {
		$lastPage = 1;
	} else {
		$lastPage = ceil($total / $size);
	}

	$next = $start + 1;
	if ($next > $lastPage) {
		$next = 0;
	}
	return array('start' => $start, 'first' => $start, 'current' => $start, 'end' => $start, 'end_val' => $size, 'allflag' => 'All',
		'prev' => $prev, 'next' => $next, 'verylast' => $lastPage);
}

function getRecordRangeMessage($listResult, $limitStartRecord, $totalRows = '') {
	global $adb, $app_strings;
	$numRows = $adb->num_rows($listResult);
	$recordListRangeMsg = '';
	if ($numRows > 0) {
		$recordListRangeMsg = $app_strings['LBL_SHOWING'] . ' ' . $app_strings['LBL_RECORDS'] .
				' ' . ($limitStartRecord + 1) . ' - ' . ($limitStartRecord + $numRows);
		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false) === true) {
			$recordListRangeMsg .= ' ' . $app_strings['LBL_LIST_OF'] . " $totalRows";
		}
	}
	return $recordListRangeMsg;
}

function listQueryNonAdminChange($query, $module, $scope = '') {
	$instance = CRMEntity::getInstance($module);
	return $instance->listQueryNonAdminChange($query, $scope);
}

function html_strlen($str) {
	$chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
	return count($chars);
}

function html_substr($str, $start, $length = NULL) {
	if ($length === 0)
		return "";
	//check if we can simply use the built-in functions
	if (strpos($str, '&') === false) { //No entities. Use built-in functions
		if ($length === NULL)
			return substr($str, $start);
		else
			return substr($str, $start, $length);
	}

	// create our array of characters and html entities
	$chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
	$html_length = count($chars);
	// check if we can predict the return value and save some processing time
	if (($html_length === 0) or ($start >= $html_length) or (isset($length) and ($length <= -$html_length)))
		return "";

	//calculate start position
	if ($start >= 0) {
		$real_start = $chars[$start][1];
	} else { //start'th character from the end of string
		$start = max($start, -$html_length);
		$real_start = $chars[$html_length + $start][1];
	}
	if (!isset($length)) // no $length argument passed, return all remaining characters
		return substr($str, $real_start);
	else if ($length > 0) { // copy $length chars
		if ($start + $length >= $html_length) { // return all remaining characters
			return substr($str, $real_start);
		} else { //return $length characters
			return substr($str, $real_start, $chars[max($start, 0) + $length][1] - $real_start);
		}
	} else { //negative $length. Omit $length characters from end
		return substr($str, $real_start, $chars[$html_length + $length][1] - $real_start);
	}
}

function counterValue() {
	static $counter = 0;
	$counter = $counter + 1;
	return $counter;
}

function getUsersPasswordInfo(){
	global $adb;
	$sql = "SELECT user_name, user_hash FROM jo_users WHERE deleted=?";
	$result = $adb->pquery($sql, array(0));
	$usersList = array();
	for ($i=0; $i<$adb->num_rows($result); $i++) {
		$userList['name'] = $adb->query_result($result, $i, "user_name");
		$userList['hash'] = $adb->query_result($result, $i, "user_hash");
		$usersList[] = $userList;
	}
	return $usersList;
}

?>
