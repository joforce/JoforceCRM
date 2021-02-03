<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ********************************************************************************/

require_once('config/config.php');
require_once('modules/Users/Users.php');
require_once('includes/utils/UserInfoUtil.php');
require_once('includes/utils/utils.php');
require_once('includes/utils/GetUserGroups.php');
require_once('includes/utils/GetGroupUsers.php');


/** Creates a file with all the user, user-role,user-profile, user-groups informations 
  * @param $userid -- user id:: Type integer
  * @returns user_privileges_userid file under the user_privileges directory
 */

function createUserPrivilegesfile($userid)
{
	global $root_directory,$adb;
		$new_privilege =  array();
		$user_focus= new Users();
		$user_focus->retrieve_entity_info($userid,"Users");
		$userInfo=Array();
		$user_focus->column_fields["id"] = '';
		$user_focus->id = $userid; 
		foreach($user_focus->column_fields as $field=>$value_iter)
        	{
               	if($field == 'currency_symbol'){
               		$userInfo[$field]= $user_focus->$field;
      			}else{
      				$userInfo[$field]= $user_focus->$field;
      			}
        	}
		if($user_focus->is_admin == 'on')
		{

			$new_privilege['is_admin'] = "1";
			$new_privilege['user_info'] = constructSingleStringKeyValueArray($userInfo);
			$json_privilege = json_encode($new_privilege,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
			$user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
			$user_query_cnt = $adb->num_rows($user_query);
			if($user_query_cnt > 0){
				$adb->pquery('UPDATE jo_privileges set user_privilege= ? where user_id=?',array($json_privilege,$userid));
			}else{
				$adb->pquery('INSERT INTO jo_privileges values(?,?,?,?,?)',array('',$userid,$json_privilege,'',''));
			}
			return;	
		} else {
			$new_privilege['is_admin'] = "0";
			$globalPermissionArr=getCombinedUserGlobalPermissions($userid);
			$tabsPermissionArr=getCombinedUserTabsPermissions($userid);
			$actionPermissionArr=getCombinedUserActionPermissions($userid);
			$user_role=fetchUserRole($userid);
			$user_role_info=getRoleInformation($user_role);
			$user_role_parent=$user_role_info[$user_role][1];
			$userGroupFocus=new GetUserGroups();
			$userGroupFocus->getAllUserGroups($userid);
			$subRoles=getRoleSubordinates($user_role);
			$subRoleAndUsers=getSubordinateRoleAndUsers($user_role);
			$def_org_share=getDefaultSharingAction();
			$parentRoles=getParentRole($user_role);
			$new_privilege['current_user_roles'] = $user_role;
			$new_privilege['current_user_parent_role_seq'] = $user_role_parent;
			$new_privilege['current_user_profiles'] = constructSingleArray(getUserProfile($userid));
			$new_privilege['profileGlobalPermission'] =$globalPermissionArr;
			$new_privilege['profileTabsPermission'] = $tabsPermissionArr;
			$new_privilege['profileActionPermission'] = constructTwoDimensionalArray($actionPermissionArr);
			$new_privilege['current_user_groups'] = constructSingleArray($userGroupFocus->user_groups);
			$new_privilege['subordinate_roles'] = constructSingleCharArray($subRoles);
			$new_privilege['parent_roles'] = constructSingleCharArray($parentRoles);
			$new_privilege['subordinate_roles_users'] = constructTwoDimensionalCharIntSingleArray($subRoleAndUsers);
			$new_privilege['user_info'] = constructSingleStringKeyValueArray($userInfo);
			$json_privilege = json_encode($new_privilege,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
      		$user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));

      		$user_query_cnt = $adb->num_rows($user_query);
		    if($user_query_cnt > 0){
         		$adb->pquery("update jo_privileges set user_privilege= ? where user_id=?",array($json_privilege,$userid));
        	}else{
          		$adb->pquery("insert into jo_privileges values(?,?,?,?,?)",array('',$userid,$json_privilege,'',''));
        	}
        	
		}
}

/** Creates a file with all the organization default sharing permissions and custom sharing permissins specific for the specified user. In this file the information of the other users whose data is shared with the specified user is stored.   
  * @param $userid -- user id:: Type integer
  * @returns sharing_privileges_userid file under the user_privileges directory
 */

function createUserSharingPrivilegesfile($userid)
{
	global $adb, $root_directory;
	        $get_userdetails = get_privileges($userid);
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
		$new_privilege =  array();
		$user_focus= new Users();
		$user_focus->retrieve_entity_info($userid,"Users");
		if($user_focus->is_admin == 'on')
		{
			$json_privilege = json_encode($new_privilege,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
      		$user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
      		$user_query_cnt = $adb->num_rows($user_query);
		    if($user_query_cnt > 0){
         		$adb->pquery("update jo_privileges set sharing_privilege = ? where user_id=?",array($json_privilege,$userid));
        	}else{
          		$adb->pquery("insert into jo_privileges values(?,?,?,?,?)",array('',$userid,'',$json_privilege,''));
        	}

			return;	
		}
		else
		{
			//Constructig the Default Org Share Array
			$def_org_share=getAllDefaultSharingAction();
			$new_privilege['defaultOrgSharingPermission'] = $def_org_share;
			
			$relModSharArr=Array();	
			$query ="select * from jo_datashare_relatedmodules";
                	$result=$adb->pquery($query, array());
                	$num_rows = $adb->num_rows($result);
                	for($i=0;$i<$num_rows;$i++)
                	{
                        	$parTabId=$adb->query_result($result,$i,'tabid');
                        	$relTabId=$adb->query_result($result,$i,'relatedto_tabid');
				if(is_array($relModSharArr[$relTabId]))
				{
					$temArr=$relModSharArr[$relTabId];
					$temArr[]=$parTabId;
				}
				else
				{
					$temArr=Array();
					$temArr[]=$parTabId;
				}
				$relModSharArr[$relTabId]=$temArr;	
                	}
            $new_privilege['related_module_share'] = constructTwoDimensionalValueArray($relModSharArr);
			//Constructing Lead Sharing Rules
			$lead_share_per_array=getUserModuleSharingObjects("Leads",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$lead_share_read_per=$lead_share_per_array['read'];
			$lead_share_write_per=$lead_share_per_array['write'];
			$lead_sharingrule_members=$lead_share_per_array['sharingrules'];	
			$new_privilege['Leads_share_read_permission']  = array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($lead_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($lead_share_read_per['GROUP']));
			$new_privilege['Leads_share_write_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($lead_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($lead_share_write_per['GROUP']));

			//Constructing the Lead Email Related Module Sharing Array
			$lead_related_email=getRelatedModuleSharingArray("Leads","Emails",$lead_sharingrule_members,$lead_share_read_per,$lead_share_write_per,$def_org_share);

			$lead_email_share_read_per=$lead_related_email['read'];
			$lead_email_share_write_per=$lead_related_email['write'];
			$new_privilege['Leads_Emails_share_read_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($lead_email_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($lead_email_share_read_per['GROUP']));
			$new_privilege['Leads_Emails_share_write_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($lead_email_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($lead_email_share_write_per['GROUP']));

			//Constructing Account Sharing Rules
			$account_share_per_array=getUserModuleSharingObjects("Accounts",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$account_share_read_per=$account_share_per_array['read'];
			$account_share_write_per=$account_share_per_array['write'];
			$account_sharingrule_members=$account_share_per_array['sharingrules'];
			
			$new_privilege['Accounts_share_read_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($account_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($account_share_read_per['GROUP']));
			$new_privilege['Accounts_share_write_permission'] = array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($account_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($account_share_write_per['GROUP']));	
			//Constructing Contact Sharing Rules
                        $contact_share_per_array=getUserModuleSharingObjects("Contacts",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$contact_share_read_per=$contact_share_per_array['read'];
			$contact_share_write_per=$contact_share_per_array['write'];
			$new_privilege['Contacts_share_read_permission'] = array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($contact_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($contact_share_read_per['GROUP']));
			$new_privilege['Contacts_share_write_permission'] = 	array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($contact_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($contact_share_write_per['GROUP']));

			//Constructing the Account Potential Related Module Sharing Array
			$acct_related_pot=getRelatedModuleSharingArray("Accounts","Potentials",$account_sharingrule_members,$account_share_read_per,$account_share_write_per,$def_org_share);

			$acc_pot_share_read_per=$acct_related_pot['read'];
			$acc_pot_share_write_per=$acct_related_pot['write'];
			$new_privilege['Accounts_Potentials_share_read_permission'] = array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_pot_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_pot_share_read_per['GROUP']));
			$new_privilege['Accounts_Potentials_share_write_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_pot_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_pot_share_write_per['GROUP']));

			//Constructing the Account Ticket Related Module Sharing Array
			$acct_related_tkt=getRelatedModuleSharingArray("Accounts","HelpDesk",$account_sharingrule_members,$account_share_read_per,$account_share_write_per,$def_org_share);

			$acc_tkt_share_read_per=$acct_related_tkt['read'];
			$acc_tkt_share_write_per=$acct_related_tkt['write'];
			$new_privilege['Accounts_HelpDesk_share_read_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_tkt_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_tkt_share_read_per['GROUP']));	
			$new_privilege['Accounts_HelpDesk_share_write_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_tkt_share_write_per['ROLE']).",'GROUP'=>".constructTwoDimensionalValueArray($acc_tkt_share_write_per['GROUP']));

			//Constructing the Account Email Related Module Sharing Array
			$acct_related_email=getRelatedModuleSharingArray("Accounts","Emails",$account_sharingrule_members,$account_share_read_per,$account_share_write_per,$def_org_share);

			$acc_email_share_read_per=$acct_related_email['read'];
			$acc_email_share_write_per=$acct_related_email['write'];
			$new_privilege['Accounts_Emails_share_read_permission'] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_email_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_email_share_read_per['GROUP']));	
			$new_privilege['Accounts_Emails_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_email_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_email_share_write_per['GROUP']));

			//Constructing the Account Quote Related Module Sharing Array
			$acct_related_qt=getRelatedModuleSharingArray("Accounts","Quotes",$account_sharingrule_members,$account_share_read_per,$account_share_write_per,$def_org_share);

			$acc_qt_share_read_per=$acct_related_qt['read'];
			$acc_qt_share_write_per=$acct_related_qt['write'];
			$new_privilege['Accounts_Quotes_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_qt_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_qt_share_read_per['GROUP']));	
			$new_privilege['Accounts_Quotes_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_qt_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_qt_share_write_per['GROUP']));

			//Constructing the Account SalesOrder Related Module Sharing Array
			$acct_related_so=getRelatedModuleSharingArray("Accounts","SalesOrder",$account_sharingrule_members,$account_share_read_per,$account_share_write_per,$def_org_share);

			$acc_so_share_read_per=$acct_related_so['read'];
			$acc_so_share_write_per=$acct_related_so['write'];
			$new_privilege['Accounts_SalesOrder_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_so_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_so_share_read_per['GROUP']));	
			$new_privilege['Accounts_SalesOrder_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_so_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_so_share_write_per['GROUP']));


			//Constructing the Account Invoice Related Module Sharing Array
			$acct_related_inv=getRelatedModuleSharingArray("Accounts","Invoice",$account_sharingrule_members,$account_share_read_per,$account_share_write_per,$def_org_share);

			$acc_inv_share_read_per=$acct_related_inv['read'];
			$acc_inv_share_write_per=$acct_related_inv['write'];

			$new_privilege['Accounts_Invoice_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_inv_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_inv_share_read_per['GROUP']));	
			$new_privilege['Accounts_Invoice_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($acc_inv_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($acc_inv_share_write_per['GROUP']));

			
			//Constructing Potential Sharing Rules
			$pot_share_per_array=getUserModuleSharingObjects("Potentials",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$pot_share_read_per=$pot_share_per_array['read'];
			$pot_share_write_per=$pot_share_per_array['write'];
			$pot_sharingrule_members=$pot_share_per_array['sharingrules'];
			$new_privilege['Potentials_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($pot_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($pot_share_read_per['GROUP']));	
			$new_privilege['Potentials_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($pot_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($pot_share_write_per['GROUP']));

			//Constructing the Potential Quotes Related Module Sharing Array
			$pot_related_qt=getRelatedModuleSharingArray("Potentials","Quotes",$pot_sharingrule_members,$pot_share_read_per,$pot_share_write_per,$def_org_share);

			$pot_qt_share_read_per=$pot_related_qt['read'];
			$pot_qt_share_write_per=$pot_related_qt['write'];

			$new_privilege['Potentials_Quotes_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($pot_qt_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($pot_qt_share_read_per['GROUP']));	
			$new_privilege['Potentials_Quotes_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($pot_qt_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($pot_qt_share_write_per['GROUP']));

			//Constructing the Potential SalesOrder Related Module Sharing Array
			$pot_related_inv=getRelatedModuleSharingArray("Potentials","SalesOrder",$pot_sharingrule_members,$pot_share_read_per,$pot_share_write_per,$def_org_share);
 	 
			

			$pot_inv_share_read_per=$pot_related_inv['read'];
			$pot_inv_share_write_per=$pot_related_inv['write'];

			$new_privilege['Potentials_SalesOrder_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($pot_inv_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($pot_inv_share_read_per['GROUP']));	
			$new_privilege['Potentials_SalesOrder_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($pot_inv_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($pot_inv_share_write_per['GROUP']));




			//Constructing HelpDesk Sharing Rules
			$hd_share_per_array=getUserModuleSharingObjects("HelpDesk",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$hd_share_read_per=$hd_share_per_array['read'];
			$hd_share_write_per=$hd_share_per_array['write'];
			$new_privilege['HelpDesk_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($hd_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($hd_share_read_per['GROUP']));	
			$new_privilege['HelpDesk_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($hd_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($hd_share_write_per['GROUP']));
	
			//Constructing Emails Sharing Rules
			$email_share_per_array=getUserModuleSharingObjects("Emails",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$email_share_read_per=$email_share_per_array['read'];
			$email_share_write_per=$email_share_per_array['write'];
			$new_privilege['Emails_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($email_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($email_share_read_per['GROUP']));	
			$new_privilege['Emails_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($email_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($email_share_write_per['GROUP']));

			//Constructing Campaigns Sharing Rules
			$campaign_share_per_array=getUserModuleSharingObjects("Campaigns",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$campaign_share_read_per=$campaign_share_per_array['read'];
			$campaign_share_write_per=$campaign_share_per_array['write'];
			$new_privilege['Campaigns_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($campaign_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($campaign_share_read_per['GROUP']));	
			$new_privilege['Campaigns_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($campaign_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($campaign_share_write_per['GROUP']));
	

			//Constructing Quotes Sharing Rules
			$quotes_share_per_array=getUserModuleSharingObjects("Quotes",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$quotes_share_read_per=$quotes_share_per_array['read'];
			$quotes_share_write_per=$quotes_share_per_array['write'];
			$quotes_sharingrule_members=$quotes_share_per_array['sharingrules'];
			$new_privilege['Quotes_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($quotes_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($quotes_share_read_per['GROUP']));	
			$new_privilege['Quotes_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($quotes_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($quotes_share_write_per['GROUP']));

			//Constructing the Quote SalesOrder Related Module Sharing Array
			$qt_related_so=getRelatedModuleSharingArray("Quotes","SalesOrder",$quotes_sharingrule_members,$quotes_share_read_per,$quotes_share_write_per,$def_org_share);

			$qt_so_share_read_per=$qt_related_so['read'];
			$qt_so_share_write_per=$qt_related_so['write'];

			$new_privilege['Quotes_SalesOrder_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($qt_so_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($qt_so_share_read_per['GROUP']));	
			$new_privilege['Quotes_SalesOrder_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($qt_so_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($qt_so_share_write_per['GROUP']));




			//Constructing Orders Sharing Rules
			$po_share_per_array=getUserModuleSharingObjects("PurchaseOrder",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$po_share_read_per=$po_share_per_array['read'];
			$po_share_write_per=$po_share_per_array['write'];
			$new_privilege['PurchaseOrder_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($po_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($po_share_read_per['GROUP']));	
			$new_privilege['PurchaseOrder_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($po_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($po_share_write_per['GROUP']));
	
			//Constructing Sales Order Sharing Rules
			$so_share_per_array=getUserModuleSharingObjects("SalesOrder",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$so_share_read_per=$so_share_per_array['read'];
			$so_share_write_per=$so_share_per_array['write'];
			$so_sharingrule_members=$so_share_per_array['sharingrules'];
			$new_privilege['SalesOrder_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($so_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($so_share_read_per['GROUP']));	
			$new_privilege['SalesOrder_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($so_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($so_share_write_per['GROUP']));

			//Constructing the SalesOrder Invoice Related Module Sharing Array
			$so_related_inv=getRelatedModuleSharingArray("SalesOrder","Invoice",$so_sharingrule_members,$so_share_read_per,$so_share_write_per,$def_org_share);

			$so_inv_share_read_per=$so_related_inv['read'];
			$so_inv_share_write_per=$so_related_inv['write'];

			$new_privilege['SalesOrder_Invoice_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($so_inv_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($so_inv_share_read_per['GROUP']));	
			$new_privilege['SalesOrder_Invoice_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($so_inv_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalValueArray($so_inv_share_write_per['GROUP']));


	
			//Constructing Invoice Sharing Rules
			$inv_share_per_array=getUserModuleSharingObjects("Invoice",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$inv_share_read_per=$inv_share_per_array['read'];
			$inv_share_write_per=$inv_share_per_array['write'];
			$new_privilege['Invoice_share_read_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($inv_share_read_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($inv_share_read_per['GROUP']));	
			$new_privilege['Invoice_share_write_permission']=array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($inv_share_write_per['ROLE']),'GROUP'=>constructTwoDimensionalArray($inv_share_write_per['GROUP']));
			
	
			// Writing Sharing Rules For Custom Modules.
			// TODO: We are ignoring rules that has already been calculated above, it is good to add GENERIC logic here.
			$custom_modules = getSharingModuleList(
				Array('Leads', 'Accounts', 'Contacts', 'Potentials', 'HelpDesk', 
				'Emails', 'Campaigns','Quotes', 'PurchaseOrder', 'SalesOrder', 'Invoice'));

			for($idx = 0; $idx < count($custom_modules); ++$idx) {
				$module_name = $custom_modules[$idx];
				$mod_share_perm_array = getUserModuleSharingObjects($module_name,$userid,
					$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);

				$mod_share_read_perm = $mod_share_perm_array['read'];
				$mod_share_write_perm= $mod_share_perm_array['write'];
				$new_privilege[$module_name."_share_read_permission"] =array('ROLE'=>
					constructTwoDimensionalCharIntSingleValueArray($mod_share_read_perm['ROLE']),'GROUP'=>constructTwoDimensionalArray($mod_share_read_perm['GROUP']));
				$new_privilege[$module_name."_share_write_permission"] =array('ROLE'=>constructTwoDimensionalCharIntSingleValueArray($mod_share_write_perm['ROLE']),'GROUP'=>
					constructTwoDimensionalArray($mod_share_write_perm['GROUP']));
			}
			// END
			$json_privilege = json_encode($new_privilege,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
      		$user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
      		$user_query_cnt = $adb->num_rows($user_query);
		    if($user_query_cnt > 0){
         		$adb->pquery("update jo_privileges set sharing_privilege= ? where user_id=?",array($json_privilege,$userid));
        	}else{
          		$adb->pquery("insert into jo_privileges values(?,?,?,?,?)",array('',$userid,'',$json_privilege,''));
        	}
			//Populating Temp Tables
			populateSharingtmptables($userid);
	}
}
/** Gives an array which contains the information for what all roles, groups and user data is to be shared with the spcified user for the specified module 

  * @param $module -- module name:: Type varchar
  * @param $userid -- user id:: Type integer
  * @param $def_org_share -- default organization sharing permission array:: Type array
  * @param $current_user_roles -- roleid:: Type varchar
  * @param $parent_roles -- parent roles:: Type varchar
  * @param $current_user_groups -- user id:: Type integer
  * @returns $mod_share_permission -- array which contains the id of roles,group and users data shared with specifed user for the specified module
 */
function getUserModuleSharingObjects($module,$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups)
{
	global $adb;

	$mod_tabid=getTabid($module);

	$mod_share_permission;
	$mod_share_read_permission=Array();
	$mod_share_write_permission=Array();
	$mod_share_read_permission['ROLE']=Array();
	$mod_share_write_permission['ROLE']=Array();
	$mod_share_read_permission['GROUP']=Array();
	$mod_share_write_permission['GROUP']=Array();

	$share_id_members=Array();
	$share_id_groupmembers=Array();
	//If Sharing of leads is Private
	if($def_org_share[$mod_tabid] == 3 || $def_org_share[$mod_tabid] == 0)
	{
		$role_read_per=Array();
		$role_write_per=Array();
		$rs_read_per=Array();
		$rs_write_per=Array();
		$grp_read_per=Array();
		$grp_write_per=Array();
		//Retreiving from jo_role to jo_role
		$query="select jo_datashare_role2role.* from jo_datashare_role2role inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_role2role.shareid where jo_datashare_module_rel.tabid=? and jo_datashare_role2role.to_roleid=?";
		$result=$adb->pquery($query, array($mod_tabid, $current_user_roles));
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_roleid=$adb->query_result($result,$i,'share_roleid');
			
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			$share_id_roles[]=$share_roleid;
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;
	
			$share_permission=$adb->query_result($result,$i,'permission');
			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}
				}
				if(! array_key_exists($share_roleid,$role_write_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_write_per[$share_roleid]=$share_role_users;
				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_roleid,$role_read_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_read_per[$share_roleid]=$share_role_users;
				}

			}

		}



		//Retreiving from role to rs
		$parRoleList = array();
		foreach($parent_roles as $par_role_id)
		{
			array_push($parRoleList, $par_role_id);		
		}
		array_push($parRoleList, $current_user_roles);
		$query="select jo_datashare_role2rs.* from jo_datashare_role2rs inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_role2rs.shareid where jo_datashare_module_rel.tabid=? and jo_datashare_role2rs.to_roleandsubid in (". generateQuestionMarks($parRoleList) .")";
		$result=$adb->pquery($query, array($mod_tabid, $parRoleList));
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_roleid=$adb->query_result($result,$i,'share_roleid');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			$share_id_roles[]=$share_roleid;
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;

			$share_permission=$adb->query_result($result,$i,'permission');
			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}
				}
				if(! array_key_exists($share_roleid,$role_write_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_write_per[$share_roleid]=$share_role_users;
				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_roleid,$role_read_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_read_per[$share_roleid]=$share_role_users;
				}

			}

		}

		//Get roles from Role2Grp
		$grpIterator=false;
		$groupList = $current_user_groups;
		if (empty($groupList)) $groupList = array(0);
		
		if (!empty($groupList)) {
			$query="select jo_datashare_role2group.* from jo_datashare_role2group inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_role2group.shareid where jo_datashare_module_rel.tabid=?";
			$qparams = array($mod_tabid);

			if (count($groupList) > 0) {
				$query .= " and jo_datashare_role2group.to_groupid in (". generateQuestionMarks($groupList) .")";
				array_push($qparams, $groupList);
			}
			$result=$adb->pquery($query, $qparams);
			$num_rows=$adb->num_rows($result);
			for($i=0;$i<$num_rows;$i++)
			{
				$share_roleid=$adb->query_result($result,$i,'share_roleid');
				$shareid=$adb->query_result($result,$i,'shareid');
				$share_id_role_members=Array();
				$share_id_roles=Array();
				$share_id_roles[]=$share_roleid;
				$share_id_role_members['ROLE']=$share_id_roles;
				$share_id_members[$shareid]=$share_id_role_members;

				$share_permission=$adb->query_result($result,$i,'permission');
				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}

			}
		}


		//Retreiving from rs to jo_role
		$query="select jo_datashare_rs2role.* from jo_datashare_rs2role inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_rs2role.shareid where jo_datashare_module_rel.tabid=? and jo_datashare_rs2role.to_roleid=?";
		$result=$adb->pquery($query, array($mod_tabid, $current_user_roles));
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_rsid=$adb->query_result($result,$i,'share_roleandsubid');
			$share_roleids=getRoleAndSubordinatesRoleIds($share_rsid);
			array_push($share_roleids, $share_rsid);
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			foreach($share_roleids as $share_roleid)
			{
				$share_id_roles[]=$share_roleid;
				

				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{	
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}
			}
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;

		}


		//Retreiving from rs to rs
		$parRoleList = array();
		foreach($parent_roles as $par_role_id)
		{
			array_push($parRoleList, $par_role_id);		
		}
		array_push($parRoleList, $current_user_roles);
		$query="select jo_datashare_rs2rs.* from jo_datashare_rs2rs inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_rs2rs.shareid where jo_datashare_module_rel.tabid=? and jo_datashare_rs2rs.to_roleandsubid in (". generateQuestionMarks($parRoleList) .")";
		$result=$adb->pquery($query, array($mod_tabid, $parRoleList));
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_rsid=$adb->query_result($result,$i,'share_roleandsubid');
			$share_roleids=getRoleAndSubordinatesRoleIds($share_rsid);
			array_push($share_roleids, $share_rsid);
			$share_permission=$adb->query_result($result,$i,'permission');
		
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			foreach($share_roleids as $share_roleid)
			{

				$share_id_roles[]=$share_roleid;

				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{	
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}
			}
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;	

		}

		//Get roles from Rs2Grp

		$query="select jo_datashare_rs2grp.* from jo_datashare_rs2grp inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_rs2grp.shareid where jo_datashare_module_rel.tabid=?";
		$qparams = array($mod_tabid);
		if (count($groupList) > 0) {
			$query .= " and jo_datashare_rs2grp.to_groupid in (". generateQuestionMarks($groupList) .")";
			array_push($qparams, $groupList);
		}
		$result=$adb->pquery($query, $qparams);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_rsid=$adb->query_result($result,$i,'share_roleandsubid');
			$share_roleids=getRoleAndSubordinatesRoleIds($share_rsid);
			array_push($share_roleids, $share_rsid);
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();

			foreach($share_roleids as $share_roleid)
			{
				
				$share_id_roles[]=$share_roleid;
			
				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{	
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}
			}
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;



		}
		$mod_share_read_permission['ROLE']=$role_read_per;
		$mod_share_write_permission['ROLE']=$role_write_per;
		
		//Retreiving from the grp2role sharing
		$query="select jo_datashare_grp2role.* from jo_datashare_grp2role inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_grp2role.shareid where jo_datashare_module_rel.tabid=? and jo_datashare_grp2role.to_roleid=?";
		$result=$adb->pquery($query, array($mod_tabid, $current_user_roles));
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_grpid=$adb->query_result($result,$i,'share_groupid');
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_grp_members=Array();
			$share_id_grps=Array();
			$share_id_grps[]=$share_grpid;
			

			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_grpid,$grp_read_per))
					{
						$focusGrpUsers = new GetGroupUsers();
						$focusGrpUsers->getAllUsersInGroup($share_grpid);

						//Clearing static cache for sub groups
						GetGroupUsers::$groupIdsList = array();

						$share_grp_users=$focusGrpUsers->group_users;
						$share_grp_subgroups=$focusGrpUsers->group_subgroups;
						$grp_read_per[$share_grpid]=$share_grp_users;
						foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
						{
							if(! array_key_exists($subgrpid,$grp_read_per))
							{
								$grp_read_per[$subgrpid]=$subgrpusers;	
							}
							if(! in_array($subgrpid,$share_id_grps))
							{
								$share_id_grps[]=$subgrpid;
							}
							
						}	
					}
				}
				if(! array_key_exists($share_grpid,$grp_write_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);

					//Clearing static cache for sub groups
					GetGroupUsers::$groupIdsList = array();

					$share_grp_users=$focusGrpUsers->group_users;
					$grp_write_per[$share_grpid]=$share_grp_users;
					foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
					{
						if(! array_key_exists($subgrpid,$grp_write_per))
						{
							$grp_write_per[$subgrpid]=$subgrpusers;	
						}
						if(! in_array($subgrpid,$share_id_grps))
						{
							$share_id_grps[]=$subgrpid;
						}	

					}	

				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_grpid,$grp_read_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);

					//Clearing static cache for sub groups
					GetGroupUsers::$groupIdsList = array();

					$share_grp_users=$focusGrpUsers->group_users;
					$grp_read_per[$share_grpid]=$share_grp_users;
					foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
					{
						if(! array_key_exists($subgrpid,$grp_read_per))
						{
							$grp_read_per[$subgrpid]=$subgrpusers;	
						}
						if(! in_array($subgrpid,$share_id_grps))
						{
							$share_id_grps[]=$subgrpid;
						}	

					}
				}

			}
			$share_id_grp_members['GROUP']=$share_id_grps;
			$share_id_members[$shareid]=$share_id_grp_members;

		}

		//Retreiving from the grp2rs sharing

		$query="select jo_datashare_grp2rs.* from jo_datashare_grp2rs inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_grp2rs.shareid where jo_datashare_module_rel.tabid=? and jo_datashare_grp2rs.to_roleandsubid in (". generateQuestionMarks($parRoleList) .")";
		$result=$adb->pquery($query, array($mod_tabid, $parRoleList));
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_grpid=$adb->query_result($result,$i,'share_groupid');
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_grp_members=Array();
			$share_id_grps=Array();
			$share_id_grps[]=$share_grpid;
			

			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_grpid,$grp_read_per))
					{
						$focusGrpUsers = new GetGroupUsers();
						$focusGrpUsers->getAllUsersInGroup($share_grpid);

						//Clearing static cache for sub groups
						GetGroupUsers::$groupIdsList = array();

						$share_grp_users=$focusGrpUsers->group_users;
						$grp_read_per[$share_grpid]=$share_grp_users;

						foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
						{
							if(! array_key_exists($subgrpid,$grp_read_per))
							{
								$grp_read_per[$subgrpid]=$subgrpusers;	
							}
							if(! in_array($subgrpid,$share_id_grps))
							{
								$share_id_grps[]=$subgrpid;
							}
							
						}	
					}
				}
				if(! array_key_exists($share_grpid,$grp_write_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);

					//Clearing static cache for sub groups
					GetGroupUsers::$groupIdsList = array();

					$share_grp_users=$focusGrpUsers->group_users;
					$grp_write_per[$share_grpid]=$share_grp_users;
					foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
					{
						if(! array_key_exists($subgrpid,$grp_write_per))
						{
								$grp_write_per[$subgrpid]=$subgrpusers;	
						}
						if(! in_array($subgrpid,$share_id_grps))
						{
							$share_id_grps[]=$subgrpid;
						}
					
					}

				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_grpid,$grp_read_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);

					//Clearing static cache for sub groups
					GetGroupUsers::$groupIdsList = array();

					$share_grp_users=$focusGrpUsers->group_users;
					$grp_read_per[$share_grpid]=$share_grp_users;
					foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
					{
						if(! array_key_exists($subgrpid,$grp_read_per))
						{
								$grp_read_per[$subgrpid]=$subgrpusers;	
						}
						if(! in_array($subgrpid,$share_id_grps))
						{
							$share_id_grps[]=$subgrpid;
						}
					
					}
				}

			}
			$share_id_grp_members['GROUP']=$share_id_grps;
			$share_id_members[$shareid]=$share_id_grp_members;

		}

		//Retreiving from the grp2grp sharing

		$query="select jo_datashare_grp2grp.* from jo_datashare_grp2grp inner join jo_datashare_module_rel on jo_datashare_module_rel.shareid=jo_datashare_grp2grp.shareid where jo_datashare_module_rel.tabid=?";
		$qparams = array($mod_tabid);
		if (count($groupList) > 0) {
			$query .= " and jo_datashare_grp2grp.to_groupid in (". generateQuestionMarks($groupList) .")";
			array_push($qparams, $groupList);
		}
		$result=$adb->pquery($query, $qparams);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_grpid=$adb->query_result($result,$i,'share_groupid');
			$share_permission=$adb->query_result($result,$i,'permission');
		
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_grp_members=Array();
			$share_id_grps=Array();
			$share_id_grps[]=$share_grpid;

			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_grpid,$grp_read_per))
					{
						$focusGrpUsers = new GetGroupUsers();
						$focusGrpUsers->getAllUsersInGroup($share_grpid);

						//Clearing static cache for sub groups
						GetGroupUsers::$groupIdsList = array();

						$share_grp_users=$focusGrpUsers->group_users;
						$grp_read_per[$share_grpid]=$share_grp_users;
						foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
						{
							if(! array_key_exists($subgrpid,$grp_read_per))
							{
								$grp_read_per[$subgrpid]=$subgrpusers;	
							}
							if(! in_array($subgrpid,$share_id_grps))
							{
								$share_id_grps[]=$subgrpid;
							}
							
							
						}
					}
				}
				if(! array_key_exists($share_grpid,$grp_write_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);

					//Clearing static cache for sub groups
					GetGroupUsers::$groupIdsList = array();

					$share_grp_users=$focusGrpUsers->group_users;
					$grp_write_per[$share_grpid]=$share_grp_users;
					foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
					{
						if(! array_key_exists($subgrpid,$grp_write_per))
						{
							$grp_write_per[$subgrpid]=$subgrpusers;	
						}
						if(! in_array($subgrpid,$share_id_grps))
						{
							$share_id_grps[]=$subgrpid;
						}
							
					}

				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_grpid,$grp_read_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);

					//Clearing static cache for sub groups
					GetGroupUsers::$groupIdsList = array();

					$share_grp_users=$focusGrpUsers->group_users;
					$grp_read_per[$share_grpid]=$share_grp_users;
					foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
					{
						if(! array_key_exists($subgrpid,$grp_read_per))
						{
							$grp_read_per[$subgrpid]=$subgrpusers;	
						}
						if(! in_array($subgrpid,$share_id_grps))
						{
							$share_id_grps[]=$subgrpid;
						}

					}
				}

			}
			$share_id_grp_members['GROUP']=$share_id_grps;
			$share_id_members[$shareid]=$share_id_grp_members;

		}
		$mod_share_read_permission['GROUP']=$grp_read_per;
		$mod_share_write_permission['GROUP']=$grp_write_per;	
	}
	$mod_share_permission['read']=$mod_share_read_permission;
	$mod_share_permission['write']=$mod_share_write_permission;
	$mod_share_permission['sharingrules']=$share_id_members;	
	return $mod_share_permission;
}

/** Gives an array which contains the information for what all roles, groups and user's related module data that is to be shared  for the specified parent module and shared module 

  * @param $par_mod -- parent module name:: Type varchar
  * @param $share_mod -- shared module name:: Type varchar
  * @param $userid -- user id:: Type integer
  * @param $def_org_share -- default organization sharing permission array:: Type array
  * @param $mod_sharingrule_members -- Sharing Rule Members array:: Type array
  * @param $$mod_share_read_per -- Sharing Module Read Permission array:: Type array
  * @param $$mod_share_write_per -- Sharing Module Write Permission array:: Type array
  * @returns $related_mod_sharing_permission; -- array which contains the id of roles,group and users related module data to be shared 
 */
function getRelatedModuleSharingArray($par_mod,$share_mod,$mod_sharingrule_members,$mod_share_read_per,$mod_share_write_per,$def_org_share)
{

	global $adb;
	$related_mod_sharing_permission=Array();
	$mod_share_read_permission=Array();
	$mod_share_write_permission=Array();

	$mod_share_read_permission['ROLE']=Array();
        $mod_share_write_permission['ROLE']=Array();
        $mod_share_read_permission['GROUP']=Array();
        $mod_share_write_permission['GROUP']=Array();

	$par_mod_id=getTabid($par_mod);
	$share_mod_id=getTabid($share_mod);

	if($def_org_share[$share_mod_id] == 3 || $def_org_share[$share_mod_id] == 0)
	{

		$role_read_per=Array();
		$role_write_per=Array();
		$grp_read_per=Array();
		$grp_write_per=Array();	



		foreach($mod_sharingrule_members as $sharingid => $sharingInfoArr)
		{
			$query = "select jo_datashare_relatedmodule_permission.* from jo_datashare_relatedmodule_permission inner join jo_datashare_relatedmodules on jo_datashare_relatedmodules.datashare_relatedmodule_id=jo_datashare_relatedmodule_permission.datashare_relatedmodule_id where jo_datashare_relatedmodule_permission.shareid=? and jo_datashare_relatedmodules.tabid=? and jo_datashare_relatedmodules.relatedto_tabid=?";
			$result=$adb->pquery($query, array($sharingid, $par_mod_id, $share_mod_id));
			$share_permission=$adb->query_result($result,0,'permission');	

			foreach($sharingInfoArr as $shareType => $shareEntArr)
			{
				foreach($shareEntArr as $key=>$shareEntId)
				{
					if($shareType == 'ROLE')
					{
						if($share_permission == 1)
						{
							if($def_org_share[$share_mod_id] == 3)
							{	
								if(! array_key_exists($shareEntId,$role_read_per))
								{
									if(array_key_exists($shareEntId,$mod_share_read_per['ROLE']))
									{
										$share_role_users=$mod_share_read_per['ROLE'][$shareEntId];
									}
									elseif(array_key_exists($shareEntId,$mod_share_write_per['ROLE']))
									{
										$share_role_users=$mod_share_write_per['ROLE'][$shareEntId];
									}
									else
									{	

										$share_role_users=getRoleUserIds($shareEntId);
									}

									$role_read_per[$shareEntId]=$share_role_users;

								}
							}
							if(! array_key_exists($shareEntId,$role_write_per))
							{
								if(array_key_exists($shareEntId,$mod_share_read_per['ROLE']))
								{
									$share_role_users=$mod_share_read_per['ROLE'][$shareEntId];
								}
								elseif(array_key_exists($shareEntId,$mod_share_write_per['ROLE']))
								{
									$share_role_users=$mod_share_write_per['ROLE'][$shareEntId];
								}
								else
								{	

									$share_role_users=getRoleUserIds($shareEntId);

								}

								$role_write_per[$shareEntId]=$share_role_users;
							}
						}
						elseif($share_permission == 0 && $def_org_share[$share_mod_id] == 3)
						{
							if(! array_key_exists($shareEntId,$role_read_per))
							{
								if(array_key_exists($shareEntId,$mod_share_read_per['ROLE']))
								{
									$share_role_users=$mod_share_read_per['ROLE'][$shareEntId];
								}
								elseif(array_key_exists($shareEntId,$mod_share_write_per['ROLE']))
								{
									$share_role_users=$mod_share_write_per['ROLE'][$shareEntId];
								}
								else
								{	

									$share_role_users=getRoleUserIds($shareEntId);
								}

								$role_read_per[$shareEntId]=$share_role_users;

							}


						}

					}
					elseif($shareType == 'GROUP')
					{
						if($share_permission == 1)
						{
							if($def_org_share[$share_mod_id] == 3)
							{
									
								if(! array_key_exists($shareEntId,$grp_read_per))
								{
									if(array_key_exists($shareEntId,$mod_share_read_per['GROUP']))
									{
										$share_grp_users=$mod_share_read_per['GROUP'][$shareEntId];
									}
									elseif(array_key_exists($shareEntId,$mod_share_write_per['GROUP']))
									{
										$share_grp_users=$mod_share_write_per['GROUP'][$shareEntId];
									}
									else
									{
										$focusGrpUsers = new GetGroupUsers();
										$focusGrpUsers->getAllUsersInGroup($shareEntId);

										//Clearing static cache for sub groups
										GetGroupUsers::$groupIdsList = array();
										$share_grp_users=$focusGrpUsers->group_users;
										
										foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
										{
											if(! array_key_exists($subgrpid,$grp_read_per))
											{
												$grp_read_per[$subgrpid]=$subgrpusers;	
											}

										}

									}

									$grp_read_per[$shareEntId]=$share_grp_users;	

								}
							}
							if(! array_key_exists($shareEntId,$grp_write_per))
							{
								if(! array_key_exists($shareEntId,$grp_write_per))
								{
									if(array_key_exists($shareEntId,$mod_share_read_per['GROUP']))
									{
										$share_grp_users=$mod_share_read_per['GROUP'][$shareEntId];
									}
									elseif(array_key_exists($shareEntId,$mod_share_write_per['GROUP']))
									{
										$share_grp_users=$mod_share_write_per['GROUP'][$shareEntId];
									}
									else
									{
										$focusGrpUsers = new GetGroupUsers();
										$focusGrpUsers->getAllUsersInGroup($shareEntId);

										//Clearing static cache for sub groups
										GetGroupUsers::$groupIdsList = array();
										$share_grp_users=$focusGrpUsers->group_users;
										foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
										{
											if(! array_key_exists($subgrpid,$grp_write_per))
											{
												$grp_write_per[$subgrpid]=$subgrpusers;	
											}

										}

									}

									$grp_write_per[$shareEntId]=$share_grp_users;	

								}
							}
						}
						elseif($share_permission == 0 && $def_org_share[$share_mod_id] == 3)
						{
							if(! array_key_exists($shareEntId,$grp_read_per))
							{
								if(array_key_exists($shareEntId,$mod_share_read_per['GROUP']))
								{
									$share_grp_users=$mod_share_read_per['GROUP'][$shareEntId];
								}
								elseif(array_key_exists($shareEntId,$mod_share_write_per['GROUP']))
								{
									$share_grp_users=$mod_share_write_per['GROUP'][$shareEntId];
								}
								else
								{
									$focusGrpUsers = new GetGroupUsers();
									$focusGrpUsers->getAllUsersInGroup($shareEntId);

									//Clearing static cache for sub groups
									GetGroupUsers::$groupIdsList = array();

									$share_grp_users=$focusGrpUsers->group_users;
									foreach($focusGrpUsers->group_subgroups as $subgrpid=>$subgrpusers)
									{
										if(! array_key_exists($subgrpid,$grp_read_per))
										{
											$grp_read_per[$subgrpid]=$subgrpusers;	
										}

									}

								}

								$grp_read_per[$shareEntId]=$share_grp_users;	

							}


						}
					}	
				}
			}			
		}
		$mod_share_read_permission['ROLE']=$role_read_per;
		$mod_share_write_permission['ROLE']=$role_write_per;
		$mod_share_read_permission['GROUP']=$grp_read_per;
		$mod_share_write_permission['GROUP']=$grp_write_per;
	}

	$related_mod_sharing_permission['read']=$mod_share_read_permission;
	$related_mod_sharing_permission['write']=$mod_share_write_permission;
	return $related_mod_sharing_permission;	


}


/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructArray($var)
{
	if (is_array($var))
	{
       		$code = 'array(';
       		foreach ($var as $key => $value)
		{
           		$code .= "'".$key."'=>".$value.',';
       		}
       		$code .= ')';
       		return $code;
   	}
}

/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructSingleStringValueArray($var)
{

        $size = sizeof($var);
        $i=1;
        if (is_array($var))
        {
                $code = 'array(';
                foreach ($var as $key => $value)
                {
                        if($i<$size)
                        {
                                $code .= $key."=>'".$value."',";
                        }
                        else
                        {
                                $code .= $key."=>'".$value."'";
                        }
                        $i++;
                }
                $code .= ')';
                return $code;
        }
}

/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructSingleStringKeyAndValueArray($var)
{

        $size = sizeof($var);
        $i=1;
        if (is_array($var))
        {
                $code = 'array(';
                foreach ($var as $key => $value)
                {
                        if($i<$size)
                        {
                                $code .= "'".$key."'=>".$value.",";
                        }
                        else
                        {
                                $code .= "'".$key."'=>".$value;
                        }
                        $i++;
                }
                $code .= ')';
                return $code;
        }
}



/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructSingleStringKeyValueArray($var) {
	global $adb;
    $size = sizeof($var);
    $i=1;
    if (is_array($var)) {
		/*$code = 'array(';
		foreach ($var as $key = $value) {
		    //fix for signatue quote(') issue
            $value = escape_single_quotes($value);
			if($i<$size) {
				$code .= "'".$key."'=>'".$value."',";
			} else {
				$code .= "'".$key."'=>'".$value."'";
			}
			$i++;
		}
	    $code .= ')';*/
	    return $var;
    }
}


/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructSingleArray($var)
{
	if (is_array($var))
	{
       		/*$code = 'array(';
       		foreach ($var as $value)
		{
           		$code .= $value.',';
       		}
       		$code .= ')';
       		return $code;*/
       		return $var;
   	}
}

/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructSingleCharArray($var)
{
	if (is_array($var))
	{/*
       		$code = "array(";
       		foreach ($var as $value)
		{
           		$code .="'".$value."',";
       		}
       		$code .= ")";
       		return $code;*/
       		return $var;
   	}
}


/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructTwoDimensionalArray($var)
{
	if (is_array($var))
	{
       		/*$code = 'array(';
       		foreach ($var as $key => $secarr)
		{
           		$code .= $key.'=>array(';
			foreach($secarr as $seckey => $secvalue)
			{
				$code .= $seckey.'=>'.$secvalue.',';
			}
			$code .= '),';
       		}
       		$code .= ')';
       		return $code;*/
       		return $var;
   	}
}

/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructTwoDimensionalValueArray($var)
{
	if (is_array($var))
	{
       		/*$code = 'array(';
       		foreach ($var as $key => $secarr)
		{
           		$code .= $key.'=>array(';
			foreach($secarr as $seckey => $secvalue)
			{
				$code .= $secvalue.',';
			}
			$code .= '),';
       		}
       		$code .= ')';
       		return $code;*/
       		return $var;
   	}
}

/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructTwoDimensionalCharIntSingleArray($var)
{
	if (is_array($var))
	{
       		/*$code = "array(";
       		foreach ($var as $key => $secarr)
		{
           		$code .= "'".$key."'=>array(";
			foreach($secarr as $seckey => $secvalue)
			{
				$code .= $seckey.",";
			}
			$code .= "),";
       		}
       		$code .= ")";
       		return $code;*/
       		return $var;
   	}
}

/** Converts the input array  to a single string to facilitate the writing of the input array in a flat file 

  * @param $var -- input array:: Type array
  * @returns $code -- contains the whole array in a single string:: Type array 
 */
function constructTwoDimensionalCharIntSingleValueArray($var)
{
	if (is_array($var))
	{
       		/*$code = "array(";
       		foreach ($var as $key => $secarr)
		{
           		$code .= "'".$key."'=>array(";
			foreach($secarr as $seckey => $secvalue)
			{
				$code .= $secvalue.",";
			}
			$code .= "),";
       		}
       		$code .= ")";*/
       		return $var;
   	}
}


/** Function to populate the read/wirte Sharing permissions data of user/groups for the specified user into the database 
  * @param $userid -- user id:: Type integer
 */

function populateSharingtmptables($userid)
{
	global $adb;
	$get_sharingdetails = get_sharingprivileges($userid);
        foreach ($get_sharingdetails as $key => $value) {
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
	//Deleting from the existing jo_tables
	$table_arr=Array('jo_tmp_read_user_sharing_per', 'jo_tmp_write_user_sharing_per','jo_tmp_read_group_sharing_per','jo_tmp_write_group_sharing_per','jo_tmp_read_user_rel_sharing_per','jo_tmp_write_user_rel_sharing_per','jo_tmp_read_group_rel_sharing_per','jo_tmp_write_group_rel_sharing_per');
	foreach($table_arr as $tabname)
	{
		$query = "delete from ".$tabname." where userid=?";
		$adb->pquery($query, array($userid));
	}

	// Look up for modules for which sharing access is enabled.
	$sharingArray = Array('Emails');
	$otherModules = getSharingModuleList();
	$sharingArray = array_merge($sharingArray, $otherModules);
	
	foreach($sharingArray as $module)
	{
		$module_sharing_read_permvar    = $module.'_share_read_permission';
		$module_sharing_write_permvar   = $module.'_share_write_permission';

		populateSharingPrivileges('USER',$userid,$module,'read',   $$module_sharing_read_permvar );
		populateSharingPrivileges('USER',$userid,$module,'write',  $$module_sharing_write_permvar );
		populateSharingPrivileges('GROUP',$userid,$module,'read',  $$module_sharing_read_permvar );
		populateSharingPrivileges('GROUP',$userid,$module,'write', $$module_sharing_write_permvar );
	}
	//Populating Values into the temp related sharing tables
	foreach($related_module_share as $rel_tab_id => $tabid_arr)
	{
		$rel_tab_name=getTabname($rel_tab_id);
		foreach($tabid_arr as $taid)
		{
			$tab_name=getTabname($taid);

			$relmodule_sharing_read_permvar    = $tab_name.'_'.$rel_tab_name.'_share_read_permission';
			$relmodule_sharing_write_permvar   = $tab_name.'_'.$rel_tab_name.'_share_write_permission';

			populateRelatedSharingPrivileges('USER',$userid,$tab_name,$rel_tab_name,'read', $$relmodule_sharing_read_permvar);
           	populateRelatedSharingPrivileges('USER',$userid,$tab_name,$rel_tab_name,'write', $$relmodule_sharing_write_permvar);
           	populateRelatedSharingPrivileges('GROUP',$userid,$tab_name,$rel_tab_name,'read', $$relmodule_sharing_read_permvar);
           	populateRelatedSharingPrivileges('GROUP',$userid,$tab_name,$rel_tab_name,'write', $$relmodule_sharing_write_permvar);			
		}	
	}			 
}

/** Function to populate the read/wirte Sharing permissions data for the specified user into the database 
  * @param $userid -- user id:: Type integer
  * @param $enttype -- can have the value of User or Group:: Type varchar
  * @param $module -- module name:: Type varchar
  * @param $pertype -- can have the value of read or write:: Type varchar
  * @param $var_name_arr - Variable to use instead of including the sharing access again
 */
function populateSharingPrivileges($enttype,$userid,$module,$pertype, $var_name_arr=false)
{
	global $adb;	
	$tabid=getTabid($module);

	if(!$var_name_arr) {
		$get_sharingdetails = get_sharingprivileges($userid);
        foreach ($get_sharingdetails as $key => $value) {
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
	}

	if($enttype=='USER')
	{
		if($pertype =='read')
		{
			$table_name='jo_tmp_read_user_sharing_per';
			$var_name=$module.'_share_read_permission';
		}
		elseif($pertype == 'write')
		{
			$table_name='jo_tmp_write_user_sharing_per';
			$var_name=$module.'_share_write_permission';
		}
		// Lookup for the variable if not set through function argument		
		if(!$var_name_arr) $var_name_arr=$$var_name;	
		$user_arr=Array();
		if(sizeof($var_name_arr['ROLE']) > 0)
		{
			foreach($var_name_arr['ROLE'] as $roleid=>$roleusers)
			{
				
				foreach($roleusers as $user_id)
				{
					if(! in_array($user_id,$user_arr))
					{
						$query="insert into ".$table_name." values(?,?,?)";
						$adb->pquery($query, array($userid, $tabid, $user_id));
						$user_arr[]=$user_id;
					}
				}
			}
		}
		if(sizeof($var_name_arr['GROUP']) > 0)
		{
			foreach($var_name_arr['GROUP'] as $grpid=>$grpusers)
			{
				foreach($grpusers as $user_id)
				{
					if(! in_array($user_id,$user_arr))
					{
						$query="insert into ".$table_name." values(?,?,?)";
						$adb->pquery($query, array($userid, $tabid, $user_id));
						$user_arr[]=$user_id;
					}
				}
			}
		}


	}
	elseif($enttype=='GROUP')
	{
		if($pertype =='read')
		{
			$table_name='jo_tmp_read_group_sharing_per';
			$var_name=$module.'_share_read_permission';
		}
		elseif($pertype == 'write')
		{
			$table_name='jo_tmp_write_group_sharing_per';
			$var_name=$module.'_share_write_permission';
		}
		// Lookup for the variable if not set through function argument
		if(!$var_name_arr) $var_name_arr=$$var_name;
		$grp_arr=Array();
		if(sizeof($var_name_arr['GROUP']) > 0)
		{

			foreach($var_name_arr['GROUP'] as $grpid=>$grpusers)
			{
				if(! in_array($grpid,$grp_arr))
				{
					$query="insert into ".$table_name." values(?,?,?)";
					$adb->pquery($query, array($userid, $tabid, $grpid));
					$grp_arr[]=$grpid;
				}
			}
		}

	}

}


/** Function to populate the read/wirte Sharing permissions related module data for the specified user into the database 
  * @param $userid -- user id:: Type integer
  * @param $enttype -- can have the value of User or Group:: Type varchar
  * @param $module -- module name:: Type varchar
  * @param $relmodule -- related module name:: Type varchar
  * @param $pertype -- can have the value of read or write:: Type varchar
  * @param $var_name_arr - Variable to use instead of including the sharing access again
 */

function populateRelatedSharingPrivileges($enttype,$userid,$module,$relmodule,$pertype, $var_name_arr=false)
{
	global $adb;	
	$tabid=getTabid($module);
	$reltabid=getTabid($relmodule);

	if(!$var_name_arr) {
		$get_sharingdetails = get_sharingprivileges($userid);
        foreach ($get_sharingdetails as $key => $value) {
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
	}

	if($enttype=='USER')
	{
		if($pertype =='read')
		{
			$table_name='jo_tmp_read_user_rel_sharing_per';
			$var_name=$module.'_'.$relmodule.'_share_read_permission';
		}
		elseif($pertype == 'write')
		{
			$table_name='jo_tmp_write_user_rel_sharing_per';
			$var_name=$module.'_'.$relmodule.'_share_write_permission';
		}
		// Lookup for the variable if not set through function argument
		if(!$var_name_arr) $var_name_arr=$$var_name;	
		$user_arr=Array();
		if(sizeof($var_name_arr['ROLE']) > 0)
		{
			foreach($var_name_arr['ROLE'] as $roleid=>$roleusers)
			{
				
				foreach($roleusers as $user_id)
				{
					if(! in_array($user_id,$user_arr))
					{
						$query="insert into ".$table_name." values(?,?,?,?)";
						$adb->pquery($query, array($userid, $tabid, $reltabid, $user_id));
						$user_arr[]=$user_id;
					}
				}
			}
		}
		if(sizeof($var_name_arr['GROUP']) > 0)
		{
			foreach($var_name_arr['GROUP'] as $grpid=>$grpusers)
			{
				foreach($grpusers as $user_id)
				{
					if(! in_array($user_id,$user_arr))
					{
						$query="insert into ".$table_name." values(?,?,?,?)";
						$adb->pquery($query, array($userid, $tabid, $reltabid, $user_id));
						$user_arr[]=$user_id;
					}
				}
			}
		}


	}
	elseif($enttype=='GROUP')
	{
		if($pertype =='read')
		{
			$table_name='jo_tmp_read_group_rel_sharing_per';
			$var_name=$module.'_'.$relmodule.'_share_read_permission';
		}
		elseif($pertype == 'write')
		{
			$table_name='jo_tmp_write_group_rel_sharing_per';
			$var_name=$module.'_'.$relmodule.'_share_write_permission';
		}
		// Lookup for the variable if not set through function argument
		if(!$var_name_arr) $var_name_arr=$$var_name;
		$grp_arr=Array();
		if(sizeof($var_name_arr['GROUP']) > 0)
		{

			foreach($var_name_arr['GROUP'] as $grpid=>$grpusers)
			{
				if(! in_array($grpid,$grp_arr))
				{
					$query="insert into ".$table_name." values(?,?,?,?)";
					$adb->pquery($query, array($userid, $tabid, $reltabid, $grpid));
					$grp_arr[]=$grpid;
				}
			}
		}

	}

}
?>
