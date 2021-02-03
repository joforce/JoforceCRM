<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
//Todo  Remove this file when sequence table migration work is completed
/**
 * Start SEQ_TABLE
 */
include_once 'libraries/modlib/Head/Cron.php';
require_once 'config/config.inc.php';
require_once('modules/Emails/mail.php');

if (file_exists('config/config_override.php')) {
	include_once 'config/config_override.php';
}
// Extended inclusions
require_once 'includes/Loader.php';
vimport ('includes.runtime.EntryPoint');
global $adb,$dbconfig;
$sel_result =  $adb->pquery("SELECT table_name FROM information_schema.tables WHERE table_type = 'base table' AND table_schema='".$dbconfig['db_name']."' and table_name like '%seq'",array());
$res_cnt = $adb->num_rows($sel_result);

if($res_cnt > 0) {
	for($i=0;$i<$res_cnt;$i++) {
		$id_field_value = $adb->query_result($sel_result,$i,'table_name');
		$seq_table =  $adb->pquery("SELECT * FROM ".$id_field_value);
		$seq_cnt = $adb->num_rows($seq_table);
		$row_id = 0;
		 while ($myrow = $adb->fetch_array($seq_table)) {
            $row_id = $myrow['id'];
        }
        $tab_seq_table =  $adb->pquery("SELECT * FROM jo_tab_sequence where table_name=?",array($id_field_value));
        $tab_seq_cnt = $adb->num_rows($tab_seq_table);
        if($tab_seq_cnt > 0){
        	$adb->pquery("update jo_tab_sequence set sequence= ? where table_name=?",array($row_id,$id_field_value));
        }else{
        	$adb->pquery("insert into jo_tab_sequence values(?,?,?)",array('',$id_field_value,$row_id));
        }
        $adb->pquery("drop table ".$id_field_value);
	}
}

//User privilege and sharing privilege

$except_list = array('./user_privileges/audit_trail.php','./user_privileges/default_module_view.php','./user_privileges/enable_backup.php','./user_privileges/index.html','./user_privileges/permissions.php','./user_privileges/portal_user_settings.php');
$PRIVILEGE_ATTRS = array('is_admin', 'current_user_role', 'current_user_parent_role_seq',
    'current_user_profiles', 'profileGlobalPermission', 'profileTabsPermission', 'profileActionPermission',
    'current_user_groups', 'subordinate_roles', 'parent_roles', 'subordinate_roles_users', 'user_info'
  );
$SHARING_ATTRS = array('defaultOrgSharingPermission','related_module_share');
$privileges = array();
$i = 1;
foreach(glob('./user_privileges/*.*') as $filename){
  if(!in_array($filename, $except_list)){
    if (strpos($filename, 'user_privileges_') !== false) {
      
    require($filename);
    $privilege = '';
    foreach ($PRIVILEGE_ATTRS as $attr) {
      if($attr == 'currency_symbol'){
        $$attr = htmlspecialchars($$attr);
      }
      if (isset($attr))
         $privilege->$attr = $$attr;
      }

      $obj_json_format = json_encode($privilege);
   
      $userid = $privilege->user_info['id'];

      $shared_filename = './user_privileges/sharing_privileges_'.$userid.'.php';
      require($shared_filename);
      $shared_privilege = '';
       foreach ($SHARING_ATTRS as $attr) {
      if (isset($attr) && $$attr != ''){
        $shared_privilege->$attr = $$attr;
      }
         
      }
      $shared_json = json_encode($shared_privilege);
      $user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
      $user_query_cnt = $adb->num_rows($user_query);
      
      if($user_query_cnt > 0){
          $adb->pquery("update jo_privileges set user_privilege= ?,sharing_privilege=? where user_id=?",array($obj_json_format,$shared_json,$userid));
        }else{
          $adb->pquery("insert into jo_privileges values(?,?,?,?,?)",array('',$userid,$obj_json_format,$shared_json,''));
        }
  
      $i = $i+1;
    }
  }
}

die('Sequence table migration is completed');