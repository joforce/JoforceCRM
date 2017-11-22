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


/**	function used to get the permitted blocks
 *	@param string $module - module name
 *	@param string $disp_view - view name, this may be create_view, edit_view or detail_view
 *	@return string $blockid_list - list of block ids within the paranthesis with comma seperated
 */
function getPermittedBlocks($module, $disp_view)
{
	global $adb, $log;
	$log->debug("Entering into the function getPermittedBlocks($module, $disp_view)");

        $tabid = getTabid($module);
        $block_detail = Array();
        $query="select blockid,blocklabel,show_title from jo_blocks where tabid=? and $disp_view=0 and visible = 0 order by sequence";
        $result = $adb->pquery($query, array($tabid));
        $noofrows = $adb->num_rows($result);
	$blockid_list ='(';
	for($i=0; $i<$noofrows; $i++)
	{
		$blockid = $adb->query_result($result,$i,"blockid");
		if($i != 0)
			$blockid_list .= ', ';
		$blockid_list .= $blockid;
		$block_label[$blockid] = $adb->query_result($result,$i,"blocklabel");
	}
	$blockid_list .= ')';

	$log->debug("Exit from the function getPermittedBlocks($module, $disp_view). Return value = $blockid_list");
	return $blockid_list;
}

/**	function used to get the query which will list the permitted fields
 *	@param string $module - module name
 *	@param string $disp_view - view name, this may be create_view, edit_view or detail_view
 *	@return string $sql - query to get the list of fields which are permitted to the current user
 */
function getPermittedFieldsQuery($module, $disp_view)
{
	global $adb, $log;
	$log->debug("Entering into the function getPermittedFieldsQuery($module, $disp_view)");

	global $current_user;
	require('user_privileges/user_privileges_'.$current_user->id.'.php');

	//To get the permitted blocks
	$blockid_list = getPermittedBlocks($module, $disp_view);

        $tabid = getTabid($module);
	if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || $module == "Users")
	{
 		$sql = "SELECT jo_field.columnname, jo_field.fieldlabel, jo_field.tablename FROM jo_field WHERE jo_field.tabid=".$tabid." AND jo_field.block IN $blockid_list AND jo_field.displaytype IN (1,2,4,5) and jo_field.presence in (0,2) ORDER BY block,sequence";
  	}
  	else
  	{
		$profileList = getCurrentUserProfileList();
		$sql = "SELECT jo_field.columnname, jo_field.fieldlabel, jo_field.tablename FROM jo_field INNER JOIN jo_profile2field ON jo_profile2field.fieldid=jo_field.fieldid INNER JOIN jo_def_org_field ON jo_def_org_field.fieldid=jo_field.fieldid WHERE jo_field.tabid=".$tabid." AND jo_field.block IN ".$blockid_list." AND jo_field.displaytype IN (1,2,4,5) AND jo_profile2field.visible=0 AND jo_def_org_field.visible=0 AND jo_profile2field.profileid IN (". implode(",", $profileList) .") and jo_field.presence in (0,2) GROUP BY jo_field.fieldid ORDER BY block,sequence";
	}

	$log->debug("Exit from the function getPermittedFieldsQuery($module, $disp_view). Return value = $sql");
	return $sql;
}

/**	function used to get the list of fields from the input query as a comma seperated string
 *	@param string $query - field table query which contains the list of fields
 *	@return string $fields - list of fields as a comma seperated string
 */
function getFieldsListFromQuery($query)
{
	global $adb, $log;
	$log->debug("Entering into the function getFieldsListFromQuery($query)");

	$result = $adb->query($query);
	$num_rows = $adb->num_rows($result);

	for($i=0; $i < $num_rows;$i++)
	{
		$columnName = $adb->query_result($result,$i,"columnname");
		$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
		$tablename = $adb->query_result($result,$i,"tablename");

		//HANDLE HERE - Mismatch fieldname-tablename in field table, in future we have to avoid these if elses
		if($columnName == 'smownerid')//for all assigned to user name
		{
			$fields .= "case when (jo_users.user_name not like '') then jo_users.user_name else jo_groups.groupname end as '".$fieldlabel."',";
		}
		elseif($tablename == 'jo_account' && $columnName == 'parentid')//Account - Member Of
		{
			 $fields .= "jo_account2.accountname as '".$fieldlabel."',";
		}
		elseif($tablename == 'jo_contactdetails' && $columnName == 'accountid')//Contact - Account Name
		{
			$fields .= "jo_account.accountname as '".$fieldlabel."',";
		}
		elseif($tablename == 'jo_contactdetails' && $columnName == 'reportsto')//Contact - Reports To
		{
			$fields .= " concat(jo_contactdetails2.lastname,' ',jo_contactdetails2.firstname) as 'Reports To Contact',";
		}
		elseif($tablename == 'jo_potential' && $columnName == 'related_to')//Potential - Related to (changed for B2C model support)
		{
			$fields .= "jo_potential.related_to as '".$fieldlabel."',";
		}
		elseif($tablename == 'jo_potential' && $columnName == 'campaignid')//Potential - Campaign Source
		{
			$fields .= "jo_campaign.campaignname as '".$fieldlabel."',";
		}
		elseif($tablename == 'jo_seproductsrel' && $columnName == 'crmid')//Product - Related To
		{
			$fields .= "case jo_crmentityRelatedTo.setype
					when 'Leads' then concat('Leads :::: ',jo_ProductRelatedToLead.lastname,' ',jo_ProductRelatedToLead.firstname)
					when 'Accounts' then concat('Accounts :::: ',jo_ProductRelatedToAccount.accountname)
					when 'Potentials' then concat('Potentials :::: ',jo_ProductRelatedToPotential.potentialname)
				    End as 'Related To',";
		}
		elseif($tablename == 'jo_products' && $columnName == 'contactid')//Product - Contact
		{
			$fields .= " concat(jo_contactdetails.lastname,' ',jo_contactdetails.firstname) as 'Contact Name',";
		}
		elseif($tablename == 'jo_products' && $columnName == 'vendor_id')//Product - Vendor Name
		{
			$fields .= "jo_vendor.vendorname as '".$fieldlabel."',";
		}
		elseif($tablename == 'jo_producttaxrel' && $columnName == 'taxclass')//avoid product - taxclass
		{
			$fields .= "";
		}
		elseif($tablename == 'jo_attachments' && $columnName == 'name')//Emails filename
		{
			$fields .= $tablename.".name as '".$fieldlabel."',";
		}
		//By Pavani...Handling mismatch field and table name for trouble tickets
      	elseif($tablename == 'jo_troubletickets' && $columnName == 'product_id')//Ticket - Product
        {
			$fields .= "jo_products.productname as '".$fieldlabel."',";
        }
        elseif($tablename == 'jo_notes' && ($columnName == 'filename' || $columnName == 'filetype' || $columnName == 'filesize' || $columnName == 'filelocationtype' || $columnName == 'filestatus' || $columnName == 'filedownloadcount' ||$columnName == 'folderid')){
			continue;
		}
		elseif(($tablename == 'jo_invoice' || $tablename == 'jo_quotes' || $tablename == 'jo_salesorder')&& $columnName == 'accountid') {
			$fields .= 'concat("Accounts::::",jo_account.accountname) as "'.$fieldlabel.'",';
		}
		elseif(($tablename == 'jo_invoice' || $tablename == 'jo_quotes' || $tablename == 'jo_salesorder' || $tablename == 'jo_purchaseorder') && $columnName == 'contactid') {
			$fields .= 'concat("Contacts::::",jo_contactdetails.lastname," ",jo_contactdetails.firstname) as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'jo_invoice' && $columnName == 'salesorderid') {
			$fields .= 'concat("SalesOrder::::",jo_salesorder.subject) as "'.$fieldlabel.'",';
		}
		elseif(($tablename == 'jo_quotes' || $tablename == 'jo_salesorder') && $columnName == 'potentialid') {
			$fields .= 'concat("Potentials::::",jo_potential.potentialname) as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'jo_quotes' && $columnName == 'inventorymanager') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=>'jo_inventoryManager.first_name', 'last_name' => 'jo_inventoryManager.last_name'), 'Users');
			$fields .= $userNameSql. ' as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'jo_salesorder' && $columnName == 'quoteid') {
			$fields .= 'concat("Quotes::::",jo_quotes.subject) as "'.$fieldlabel.'",';
		}
		elseif($tablename == 'jo_purchaseorder' && $columnName == 'vendorid') {
			$fields .= 'concat("Vendors::::",jo_vendor.vendorname) as "'.$fieldlabel.'",';
		}
		else
		{
			$fields .= $tablename.".".$columnName. " as '" .$fieldlabel."',";
		}
	}
	$fields = trim($fields,",");

	$log->debug("Exit from the function getFieldsListFromQuery($query). Return value = $fields");
	return $fields;
}



?>
