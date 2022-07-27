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
 * Description:  Includes generic helper functions used throughout the application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('includes/database/PearDatabase.php');
require_once('includes/ComboUtil.php'); //new
require_once('includes/utils/ListViewUtils.php');
require_once('includes/utils/EditViewUtils.php');
require_once('includes/utils/CommonUtils.php');
require_once('includes/utils/InventoryUtils.php');
require_once('includes/utils/SearchUtils.php');
require_once('includes/events/SqlResultIterator.inc');
require_once('includes/fields/DateTimeField.php');
require_once('includes/fields/CurrencyField.php');
require_once('includes/data/CRMEntity.php');
require_once 'libraries/modlib/Head/Language.php';
require_once("includes/ListView/ListViewSession.php");

require_once 'libraries/modlib/Head/Functions.php';
require_once 'libraries/modlib/Head/Deprecated.php';

require_once 'includes/runtime/Cache.php';
require_once 'modules/Head/helpers/Util.php';
require_once 'libraries/modlib/Head/AccessControl.php';
// Constants to be defined here

// For Migration status.
define("MIG_CHARSET_PHP_UTF8_DB_UTF8", 1);
define("MIG_CHARSET_PHP_NONUTF8_DB_NONUTF8", 2);
define("MIG_CHARSET_PHP_NONUTF8_DB_UTF8", 3);
define("MIG_CHARSET_PHP_UTF8_DB_NONUTF8", 4);

// For Customview status.
define("CV_STATUS_DEFAULT", 0);
define("CV_STATUS_PRIVATE", 1);
define("CV_STATUS_PENDING", 2);
define("CV_STATUS_PUBLIC", 3);

// For Restoration.
define("RB_RECORD_DELETED", 'delete');
define("RB_RECORD_INSERTED", 'insert');
define("RB_RECORD_UPDATED", 'update');

//Icons for listview
define('downsortImage', "icon-chevron-down");
define('downfaSortImage', "fa-arrow-down");
define('upsortImage', "icon-chevron-up");
define('upfaSortImage', "fa-arrow-up");
define('defaultfaSortImage', "fa-arrow-down");

/** Function to return a full name
  * @param $row -- row:: Type integer
  * @param $first_column -- first column:: Type string
  * @param $last_column -- last column:: Type string
  * @returns $fullname -- fullname:: Type string
  *
*/
function return_name(&$row, $first_column, $last_column) {
    global $log;
    $log->debug("Entering return_name(".$row.",".$first_column.",".$last_column.") method ...");
    $first_name = "";
    $last_name = "";
    $full_name = "";

    if(isset($row[$first_column])) {
	$first_name = stripslashes($row[$first_column]);
    }

    if(isset($row[$last_column])) {
	$last_name = stripslashes($row[$last_column]);
    }

    $full_name = $first_name;

    if($full_name != "" && $last_name != "") {
	// If we have a first name and we have a last name
	// append a space, then the last name
	$full_name .= " ".$last_name;
    }
    else if($last_name != "") {
	// If we have no first name, but we have a last name
	// append the last name without the space.
	$full_name .= $last_name;
    }

    $log->debug("Exiting return_name method ...");
    return $full_name;
}

/** Function returns the user key in user array
  * @param $add_blank -- boolean:: Type boolean
  * @param $status -- user status:: Type string
  * @param $assigned_user -- user id:: Type string
  * @param $private -- sharing type:: Type string
  * @returns $user_array -- user array:: Type array
  *
*/

//used in module file
function get_user_array($add_blank=true, $status="Active", $assigned_user="",$private="",$module=false) {
    global $log;
    $log->debug("Entering get_user_array(".$add_blank.",". $status.",".$assigned_user.",".$private.") method ...");
    global $current_user;
    if(isset($current_user) && $current_user->id != '') {
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
        $get_sharingdetails = get_sharingprivileges($current_user->id);
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
    static $user_array = null;
    if(!$module){
	$module=$_REQUEST['module'];
    }

    if($user_array == null) {
	require_once('includes/database/PearDatabase.php');
	$db = PearDatabase::getInstance();
	$temp_result = Array();
	// Including deleted jo_users for now.
	if (empty($status)) {
	    $query = "SELECT id, user_name from jo_users";
	    $params = array();
	} else {
	    if($private == 'private') {
		$log->debug("Sharing is Private. Only the current user should be listed");
		$query = "select id as id,user_name as user_name,first_name,last_name from jo_users where id=? and status='Active' union select jo_user2role.userid as id,jo_users.user_name as user_name , jo_users.first_name as first_name ,jo_users.last_name as last_name
 from jo_user2role inner join jo_users on jo_users.id=jo_user2role.userid inner join jo_role on jo_role.roleid=jo_user2role.roleid where jo_role.parentrole like ? and status='Active' union select shareduserid as id,jo_users.user_name as user_name , jo_users.first_name as first_name ,jo_users.last_name as last_name  from jo_tmp_write_user_sharing_per inner join jo_users on jo_users.id=jo_tmp_write_user_sharing_per.shareduserid where status='Active' and jo_tmp_write_user_sharing_per.userid=? and jo_tmp_write_user_sharing_per.tabid=? and (user_name != 'admin' OR is_owner=1)";
		$params = array($current_user->id, $current_user_parent_role_seq."::%", $current_user->id, getTabid($module));
	    } else {
		$log->debug("Sharing is Public. All jo_users should be listed");
		$query = "SELECT id, user_name,first_name,last_name from jo_users WHERE status=? and (user_name != 'admin' OR is_owner=1)";
		$params = array($status);
	    }
	}
	if (!empty($assigned_user)) {
	     $query .= " OR id=?";
	     array_push($params, $assigned_user);
	}

	$query .= " order by user_name ASC";
	$result = $db->pquery($query, $params, true, "Error filling in user array: ");

	if ($add_blank==true){
	    // Add in a blank row
	    $temp_result[''] = '';
	}

	// Get the id and the name.
	while($row = $db->fetchByAssoc($result)) {
	    $temp_result[$row['id']] = getFullNameFromArray('Users', $row);
	}
	$user_array = &$temp_result;
    }
    $log->debug("Exiting get_user_array method ...");
    return $user_array;
}

function get_group_array($add_blank=true, $status="Active", $assigned_user="",$private="",$module = false) {
    global $log;
    $log->debug("Entering get_user_array(".$add_blank.",". $status.",".$assigned_user.",".$private.") method ...");
    global $current_user;
    if(isset($current_user) && $current_user->id != '') {
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
        $get_sharingdetails = get_sharingprivileges($current_user->id);
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
    static $group_array = null;
    if(!$module) {
	$module=$_REQUEST['module'];
    }

    if($group_array == null) {
	require_once('includes/database/PearDatabase.php');
	$db = PearDatabase::getInstance();
	$temp_result = Array();
	// Including deleted jo_users for now.
	$log->debug("Sharing is Public. All jo_users should be listed");
	$query = "SELECT groupid, groupname from jo_groups";
	$params = array();

	if($private == 'private') {
	    $query .= " WHERE groupid=?";
	    $params = array( $current_user->id);

	    if(count($current_user_groups) != 0) {
		$query .= " OR jo_groups.groupid in (".generateQuestionMarks($current_user_groups).")";
		array_push($params, $current_user_groups);
	    }
	    $log->debug("Sharing is Private. Only the current user should be listed");
	    $query .= " union select jo_group2role.groupid as groupid,jo_groups.groupname as groupname from jo_group2role inner join jo_groups on jo_groups.groupid=jo_group2role.groupid inner join jo_role on jo_role.roleid=jo_group2role.roleid where jo_role.parentrole like ?";
	    array_push($params, $current_user_parent_role_seq."::%");

	    if(count($current_user_groups) != 0) {
		$query .= " union select jo_groups.groupid as groupid,jo_groups.groupname as groupname from jo_groups inner join jo_group2rs on jo_groups.groupid=jo_group2rs.groupid where jo_group2rs.roleandsubid in (".generateQuestionMarks($parent_roles).")";
		array_push($params, $parent_roles);
	    }

	    $query .= " union select sharedgroupid as groupid,jo_groups.groupname as groupname from jo_tmp_write_group_sharing_per inner join jo_groups on jo_groups.groupid=jo_tmp_write_group_sharing_per.sharedgroupid where jo_tmp_write_group_sharing_per.userid=?";
	    array_push($params, $current_user->id);

	    $query .= " and jo_tmp_write_group_sharing_per.tabid=?";
	    array_push($params,  getTabid($module));
	}
	$query .= " order by groupname ASC";
	$result = $db->pquery($query, $params, true, "Error filling in user array: ");
	if ($add_blank==true){
	    // Add in a blank row
	    $temp_result[''] = '';
	}

	// Get the id and the name.
	while($row = $db->fetchByAssoc($result)) {
	    $temp_result[$row['groupid']] = $row['groupname'];
	}
	$group_array = &$temp_result;
    }
    $log->debug("Exiting get_user_array method ...");
    return $group_array;
}

/** This function retrieves an application language file and returns the array of strings included in the $app_list_strings var.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */

function return_app_list_strings_language($language) {
    return Head_Deprecated::return_app_list_strings_language($language);
}

/**
 * Retrieve the app_currency_strings for the required language.
 */
function return_app_currency_strings_language($language) {
    return Head_Deprecated::return_app_list_strings_language($language);
}

/** This function retrieves an application language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 * If you are using the current language, do not call this function unless you are loading it for the first time */
function return_application_language($language) {
    return Head_Deprecated::return_app_list_strings_language($language);
}

/** This function retrieves a module's language file and returns the array of strings included.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 * If you are in the current module, do not call this function unless you are loading it for the first time */
function return_module_language($language, $module) {
    return Head_Deprecated::getModuleTranslationStrings($language, $module);
}

/*This function returns the mod_strings for the current language and the specified module*/
function return_specified_module_language($language, $module) {
    return Head_Deprecated::return_app_list_strings_language($language, $module);
}

/**
 * Return an array of directory names.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * Contributor(s): ______________________________________..
 */
function get_themes() {
    return Head_Theme::getAllSkins();
}

/** Function to set default varibles on to the global variable
  * @param $defaults -- default values:: Type array
  */
function set_default_config(&$defaults) {
    global $log;
    $log->debug("Entering set_default_config(".$defaults.") method ...");

    foreach ($defaults as $name=>$value) {
	if ( ! isset($GLOBALS[$name]) ) {
	    $GLOBALS[$name] = $value;
	}
    }
    $log->debug("Exiting set_default_config method ...");
}

/**
 * Function to decide whether to_html should convert values or not for a request
 * @global type $doconvert
 * @global type $inUTF8
 * @global type $default_charset
 */
function decide_to_html() {
    global $doconvert, $inUTF8, $default_charset;
    $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
    $inUTF8 = (strtoupper($default_charset) == 'UTF-8');
    $doconvert = true;
    if ($action == 'ExportData') {
        $doconvert = false; 
    } 
}
decide_to_html();

/** Function to convert the given string to html
  * @param $string -- string:: Type string
  * @param $encode -- boolean:: Type boolean
  * @returns $string -- string:: Type string
  */
function to_html($string, $encode=true) {
    // For optimization - default_charset can be either upper / lower case.
    global $doconvert,$inUTF8,$default_charset,$htmlCache;

    if(is_string($string)) {
	// In vtiger5 ajax request are treated specially and the data is encoded
	if ($doconvert == true) {
            if(isset($htmlCache[$string])){
                $string = $htmlCache[$string];
            }else{
		if($inUTF8)
		    $string = htmlentities($string, ENT_QUOTES, $default_charset);
		else
			$string = preg_replace(array('/</', '/>/', '/"/'), array('&lt;', '&gt;', '&quot;'), $string);

                $htmlCache[$string] = $string;
            }
	}
    }
    return $string;
}

/** Function to get the tablabel for a given id
  * @param $tabid -- tab id:: Type integer
  * @returns $string -- string:: Type string
  */
function getTabname($tabid) {
    global $log;
    $log->debug("Entering getTabname(".$tabid.") method ...");
    $log->info("tab id is ".$tabid);
    global $adb;

    static $cache = array();

    if (!isset($cache[$tabid])) {
	$sql = "select tablabel from jo_tab where tabid=?";
	$result = $adb->pquery($sql, array($tabid));
	$tabname=  $adb->query_result($result,0,"tablabel");
	$cache[$tabid] = $tabname;
    }

    $log->debug("Exiting getTabname method ...");
    return $cache[$tabid];
}

/** Function to get the tab module name for a given id
  * @param $tabid -- tab id:: Type integer
  * @returns $string -- string:: Type string
  */
function getTabModuleName($tabid) {
    return Head_Functions::getModuleName($tabid);
}

/** Function to get column fields for a given module
  * @param $module -- module:: Type string
  * @returns $column_fld -- column field :: Type array
  */
function getColumnFields($module) {
    global $log;
    $log->debug("Entering getColumnFields(".$module.") method ...");
    $log->debug("in getColumnFields ".$module);

    // Lookup in cache for information
    $cachedModuleFields = CacheUtils::lookupFieldInfo_Module($module);

    if($cachedModuleFields === false) {
	global $adb;
	$tabid = getTabid($module);

	if ($module == 'Calendar') {
	    $tabid = array('9','16');
    	}

	// To overcome invalid module names.
	if (empty($tabid)) {
	    return array();
	}

    	// Let us pick up all the fields first so that we can cache information
	$sql = "SELECT tabid, fieldname, fieldid, fieldlabel, columnname, tablename, uitype, typeofdata, presence FROM jo_field WHERE tabid in (" . generateQuestionMarks($tabid) . ")";

        $result = $adb->pquery($sql, array($tabid));
        $noofrows = $adb->num_rows($result);

        if($noofrows) {
	    while($resultrow = $adb->fetch_array($result)) {
        	// Update information to cache for re-use
        	CacheUtils::updateFieldInfo(
        	    $resultrow['tabid'], $resultrow['fieldname'], $resultrow['fieldid'],
        	    $resultrow['fieldlabel'], $resultrow['columnname'], $resultrow['tablename'],
        	    $resultrow['uitype'], $resultrow['typeofdata'], $resultrow['presence']
        	);
            }
     	}

        // For consistency get information from cache
	$cachedModuleFields = CacheUtils::lookupFieldInfo_Module($module);
    }

    if($module == 'Calendar') {
	$cachedEventsFields = CacheUtils::lookupFieldInfo_Module('Events');
	if (!$cachedEventsFields) {
            getColumnFields('Events');
            $cachedEventsFields = CacheUtils::lookupFieldInfo_Module('Events');
        }
        
	if (!$cachedModuleFields) {
            $cachedModuleFields = $cachedEventsFields;
	} else {
            $cachedModuleFields = array_merge($cachedModuleFields, $cachedEventsFields);
        }
    }

    $column_fld = new TrackableObject();
    if($cachedModuleFields) {
	foreach($cachedModuleFields as $fieldinfo) {
	    $column_fld[$fieldinfo['fieldname']] = '';
	}
    }

    $log->debug("Exiting getColumnFields method ...");
    return $column_fld;
}

/** Function to get a users's mail id
  * @param $userid -- userid :: Type integer
  * @returns $email -- email :: Type string
  */

function getUserEmail($userid) {
    global $log;
    $log->debug("Entering getUserEmail(".$userid.") method ...");
    $log->info("in getUserEmail ".$userid);

    global $adb;
    if($userid != '') {
	$sql = "select email1 from jo_users where id=?";
	$result = $adb->pquery($sql, array($userid));
	$email = $adb->query_result($result,0,"email1");
    }
    $log->debug("Exiting getUserEmail method ...");
    return $email;
}

/** Function to get a userid for outlook
  * @param $username -- username :: Type string
  * @returns $user_id -- user id :: Type integer
  */
//outlook security
function getUserId_Ol($username) {
    global $log;
    $log->debug("Entering getUserId_Ol(".$username.") method ...");
    $log->info("in getUserId_Ol ".$username);
    $cache = Head_Cache::getInstance();
    if($cache->getUserId($username) || $cache->getUserId($username) === 0){
	return $cache->getUserId($username);
    } else {
	global $adb;
	$sql = "select id from jo_users where user_name=?";
	$result = $adb->pquery($sql, array($username));
	$num_rows = $adb->num_rows($result);
	if($num_rows > 0) {
	    $user_id = $adb->query_result($result,0,"id");
    	} else {
	    $user_id = 0;
	}
	$log->debug("Exiting getUserId_Ol method ...");
	$cache->setUserId($username,$user_id);
	return $user_id;
    }
}

/** Function to get a action id for a given action name
  * @param $action -- action name :: Type string
  * @returns $actionid -- action id :: Type integer
  */
//outlook security
function getActionid($action) {
    global $log;
    $log->debug("Entering getActionid(".$action.") method ...");
    global $adb;
    $log->info("get Actionid ".$action);
    $actionid = '';
    if(file_exists('user_privileges/permissions.php') && (filesize('user_privileges/permissions.php') != 0)) {
	include('user_privileges/permissions.php');
	$actionid= $action_id_array[$action];
    } else {
	$query="select * from jo_actionmapping where actionname=?";
        $result =$adb->pquery($query, array($action));
        $actionid=$adb->query_result($result,0,'actionid');
    }
    $log->info("action id selected is ".$actionid );
    $log->debug("Exiting getActionid method ...");
    return $actionid;
}

/** Function to get a action for a given action id
  * @param $action id -- action id :: Type integer
  * @returns $actionname-- action name :: Type string
  */
function getActionname($actionid) {
    global $log;
    $log->debug("Entering getActionname(".$actionid.") method ...");
    global $adb;

    $actionname='';

    if (file_exists('user_privileges/permissions.php') && (filesize('user_privileges/permissions.php') != 0)) {
	include('user_privileges/permissions.php');
	$actionname= $action_name_array[$actionid];
    } else {
	$query="select * from jo_actionmapping where actionid=? and securitycheck=0";
	$result =$adb->pquery($query, array($actionid));
	$actionname=$adb->query_result($result,0,"actionname");
    }
    $log->debug("Exiting getActionname method ...");
    return $actionname;
}

/** Function to get a user id or group id for a given entity
  * @param $record -- entity id :: Type integer
  * @returns $ownerArr -- owner id :: Type array
  */
function getRecordOwnerId($record) {
    global $log;
    $log->debug("Entering getRecordOwnerId(".$record.") method ...");
    global $adb;
    $ownerArr=Array();

    // Look at cache first for information
    $ownerId = CacheUtils::lookupRecordOwner($record);

    if($ownerId === false) {
	$query="select smownerid from jo_crmentity where crmid = ?";
	$result=$adb->pquery($query, array($record));
	if($adb->num_rows($result) > 0) {
	    $ownerId=$adb->query_result($result,0,'smownerid');
	    // Update cache for re-use
	    CacheUtils::updateRecordOwner($record, $ownerId);
	}
    }

    if($ownerId) {
	// Look at cache first for information
	$count = CacheUtils::lookupOwnerType($ownerId);

	if($count === false) {
	    $sql_result = $adb->pquery('SELECT 1 FROM jo_users WHERE id = ?', array($ownerId));
	    $count = $adb->query_result($sql_result, 0, 1);
	    // Update cache for re-use
	    CacheUtils::updateOwnerType($ownerId, $count);
	}
	if($count > 0)
	    $ownerArr['Users'] = $ownerId;
	else
	    $ownerArr['Groups'] = $ownerId;
    }
    $log->debug("Exiting getRecordOwnerId method ...");
    return $ownerArr;
}

/** Function to insert value to profile2field table
  * @param $profileid -- profileid :: Type integer
  */
function insertProfile2field($profileid) {
    global $log;
    $log->debug("Entering insertProfile2field(".$profileid.") method ...");
    $log->info("in insertProfile2field ".$profileid);

    global $adb;
    $adb->database->SetFetchMode(ADODB_FETCH_ASSOC);
    $fld_result = $adb->pquery("select * from jo_field where generatedtype=1 and displaytype in (1,2,3) and jo_field.presence in (0,2) and tabid != 29", array());
    $num_rows = $adb->num_rows($fld_result);
    for($i=0; $i<$num_rows; $i++) {
	$tab_id = $adb->query_result($fld_result,$i,'tabid');
	$field_id = $adb->query_result($fld_result,$i,'fieldid');
	$params = array($profileid, $tab_id, $field_id, 0, 0);
	$adb->pquery("insert into jo_profile2field values (?,?,?,?,?)", $params);
    }
    $log->debug("Exiting insertProfile2field method ...");
}

/** Function to insert into default org field */
function insert_def_org_field() {
    global $log;
    $log->debug("Entering insert_def_org_field() method ...");
    global $adb;
    $adb->database->SetFetchMode(ADODB_FETCH_ASSOC);
    $fld_result = $adb->pquery("select * from jo_field where generatedtype=1 and displaytype in (1,2,3) and jo_field.presence in (0,2) and tabid != 29", array());
    $num_rows = $adb->num_rows($fld_result);
    for($i=0; $i<$num_rows; $i++) {
	$tab_id = $adb->query_result($fld_result,$i,'tabid');
	$field_id = $adb->query_result($fld_result,$i,'fieldid');
	$params = array($tab_id, $field_id, 0, 0);
	$adb->pquery("insert into jo_def_org_field values (?,?,?,?)", $params);
    }
    $log->debug("Exiting insert_def_org_field() method ...");
}

/** Function to update product quantity
  * @param $product_id -- product id :: Type integer
  * @param $upd_qty -- quantity :: Type integer
  */
function updateProductQty($product_id, $upd_qty) {
    global $log;
    $log->debug("Entering updateProductQty(".$product_id.",". $upd_qty.") method ...");
    global $adb;
    $query= "update jo_products set qtyinstock=? where productid=?";
    $adb->pquery($query, array($upd_qty, $product_id));
    $log->debug("Exiting updateProductQty method ...");
}

/** This Function adds the specified product quantity to the Product Quantity in Stock in the Warehouse
  * The following is the input parameter for the function:
  *  $productId --> ProductId, Type:Integer
  *  $qty --> Quantity to be added, Type:Integer
  */
function addToProductStock($productId,$qty) {
    global $log;
    $log->debug("Entering addToProductStock(".$productId.",".$qty.") method ...");
    global $adb;
    $qtyInStck=getProductQtyInStock($productId);
    $updQty=$qtyInStck + $qty;
    $sql = "UPDATE jo_products set qtyinstock=? where productid=?";
    $adb->pquery($sql, array($updQty, $productId));
    $log->debug("Exiting addToProductStock method ...");
}

/**	This Function adds the specified product quantity to the Product Quantity in Demand in the Warehouse
  *	@param int $productId - ProductId
  *	@param int $qty - Quantity to be added
  */
function addToProductDemand($productId,$qty) {
    global $log;
    $log->debug("Entering addToProductDemand(".$productId.",".$qty.") method ...");
    global $adb;
    $qtyInStck=getProductQtyInDemand($productId);
    $updQty=$qtyInStck + $qty;
    $sql = "UPDATE jo_products set qtyindemand=? where productid=?";
    $adb->pquery($sql, array($updQty, $productId));
    $log->debug("Exiting addToProductDemand method ...");
}

/**	This Function subtract the specified product quantity to the Product Quantity in Stock in the Warehouse
  *	@param int $productId - ProductId
  *	@param int $qty - Quantity to be subtracted
  */
function deductFromProductStock($productId,$qty) {
    global $log;
    $log->debug("Entering deductFromProductStock(".$productId.",".$qty.") method ...");
    global $adb;
    $qtyInStck=getProductQtyInStock($productId);
    $updQty=$qtyInStck - $qty;
    $sql = "UPDATE jo_products set qtyinstock=? where productid=?";
    $adb->pquery($sql, array($updQty, $productId));
    $log->debug("Exiting deductFromProductStock method ...");
}

/**	This Function subtract the specified product quantity to the Product Quantity in Demand in the Warehouse
  *	@param int $productId - ProductId
  *	@param int $qty - Quantity to be subtract
  */
function deductFromProductDemand($productId,$qty) {
    global $log;
    $log->debug("Entering deductFromProductDemand(".$productId.",".$qty.") method ...");
    global $adb;
    $qtyInStck=getProductQtyInDemand($productId);
    $updQty=$qtyInStck - $qty;
    $sql = "UPDATE jo_products set qtyindemand=? where productid=?";
    $adb->pquery($sql, array($updQty, $productId));
    $log->debug("Exiting deductFromProductDemand method ...");
}

/** This Function returns the current product quantity in stock.
  * The following is the input parameter for the function:
  *  $product_id --> ProductId, Type:Integer
  */
function getProductQtyInStock($product_id) {
    global $log;
    $log->debug("Entering getProductQtyInStock(".$product_id.") method ...");
    global $adb;
    $query1 = "select qtyinstock from jo_products where productid=?";
    $result=$adb->pquery($query1, array($product_id));
    $qtyinstck= $adb->query_result($result,0,"qtyinstock");
    $log->debug("Exiting getProductQtyInStock method ...");
    return $qtyinstck;
}

/**	This Function returns the current product quantity in demand.
  *	@param int $product_id - ProductId
  *	@return int $qtyInDemand - Quantity in Demand of a product
  */
function getProductQtyInDemand($product_id) {
    global $log;
    $log->debug("Entering getProductQtyInDemand(".$product_id.") method ...");
    global $adb;
    $query1 = "select qtyindemand from jo_products where productid=?";
    $result = $adb->pquery($query1, array($product_id));
    $qtyInDemand = $adb->query_result($result,0,"qtyindemand");
    $log->debug("Exiting getProductQtyInDemand method ...");
    return $qtyInDemand;
}

/**     Function to get the jo_table name from 'field' jo_table for the input jo_field based on the module
 *      @param  : string $module - current module value
 *      @param  : string $fieldname - jo_fieldname to which we want the jo_tablename
 *      @return : string $tablename - jo_tablename in which $fieldname is a column, which is retrieved from 'field' jo_table per $module basis
  */
function getTableNameForField($module,$fieldname) {
    global $log;
    $log->debug("Entering getTableNameForField(".$module.",".$fieldname.") method ...");
    global $adb;
    $tabid = getTabid($module);
    if($module == 'Calendar') {
	$tabid = array('9','16');
    }
    $sql = "select tablename from jo_field where tabid in (". generateQuestionMarks($tabid) .") and jo_field.presence in (0,2) and columnname like ?";
    $res = $adb->pquery($sql, array($tabid, '%'.$fieldname.'%'));
    $tablename = '';
    if($adb->num_rows($res) > 0) {
	$tablename = $adb->query_result($res,0,'tablename');
    }

    $log->debug("Exiting getTableNameForField method ...");
    return $tablename;
}

/** Function to get parent record owner
  * @param $tabid -- tabid :: Type integer
  * @param $parModId -- parent module id :: Type integer
  * @param $record_id -- record id :: Type integer
  * @returns $parentRecOwner -- parentRecOwner:: Type integer
  */
function getParentRecordOwner($tabid,$parModId,$record_id) {
    global $log;
    $log->debug("Entering getParentRecordOwner(".$tabid.",".$parModId.",".$record_id.") method ...");
    $parentRecOwner=Array();
    $parentTabName=getTabname($parModId);
    $relTabName=getTabname($tabid);
    $fn_name="get".$relTabName."Related".$parentTabName;
    $ent_id=$fn_name($record_id);
    if($ent_id != '') {
	$parentRecOwner=getRecordOwnerId($ent_id);
    }
    $log->debug("Exiting getParentRecordOwner method ...");
    return $parentRecOwner;
}

/** Function to get potential related accounts
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */
function getPotentialsRelatedAccounts($record_id) {
    global $log;
    $log->debug("Entering getPotentialsRelatedAccounts(".$record_id.") method ...");
    global $adb;
    $query="select related_to from jo_potential where potentialid=?";
    $result=$adb->pquery($query, array($record_id));
    $accountid=$adb->query_result($result,0,'related_to');
    $log->debug("Exiting getPotentialsRelatedAccounts method ...");
    return $accountid;
}

/** Function to get email related accounts
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */
function getEmailsRelatedAccounts($record_id) {
    global $log;
    $log->debug("Entering getEmailsRelatedAccounts(".$record_id.") method ...");
    global $adb;
    $query = "select jo_seactivityrel.crmid from jo_seactivityrel inner join jo_crmentity on jo_crmentity.crmid=jo_seactivityrel.crmid where jo_crmentity.setype='Accounts' and activityid=?";
    $result = $adb->pquery($query, array($record_id));
    $accountid=$adb->query_result($result,0,'crmid');
    $log->debug("Exiting getEmailsRelatedAccounts method ...");
    return $accountid;
}
/** Function to get email related Leads
  * @param $record_id -- record id :: Type integer
  * @returns $leadid -- leadid:: Type integer
  */
function getEmailsRelatedLeads($record_id) {
    global $log;
    $log->debug("Entering getEmailsRelatedLeads(".$record_id.") method ...");
    global $adb;
    $query = "select jo_seactivityrel.crmid from jo_seactivityrel inner join jo_crmentity on jo_crmentity.crmid=jo_seactivityrel.crmid where jo_crmentity.setype='Leads' and activityid=?";
    $result = $adb->pquery($query, array($record_id));
    $leadid=$adb->query_result($result,0,'crmid');
    $log->debug("Exiting getEmailsRelatedLeads method ...");
    return $leadid;
}

/** Function to get HelpDesk related Accounts
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getHelpDeskRelatedAccounts($record_id) {
    global $log;
    $log->debug("Entering getHelpDeskRelatedAccounts(".$record_id.") method ...");
    global $adb;
    $query="select parent_id from jo_troubletickets inner join jo_crmentity on jo_crmentity.crmid=jo_troubletickets.parent_id where ticketid=? and jo_crmentity.setype='Accounts'";
    $result=$adb->pquery($query, array($record_id));
    $accountid=$adb->query_result($result,0,'parent_id');
    $log->debug("Exiting getHelpDeskRelatedAccounts method ...");
    return $accountid;
}

/** Function to get Quotes related Accounts
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getQuotesRelatedAccounts($record_id) {
    global $log;
    $log->debug("Entering getQuotesRelatedAccounts(".$record_id.") method ...");
    global $adb;
    $query="select accountid from jo_quotes where quoteid=?";
    $result=$adb->pquery($query, array($record_id));
    $accountid=$adb->query_result($result,0,'accountid');
    $log->debug("Exiting getQuotesRelatedAccounts method ...");
    return $accountid;
}

/** Function to get Quotes related Potentials
  * @param $record_id -- record id :: Type integer
  * @returns $potid -- potid:: Type integer
  */

function getQuotesRelatedPotentials($record_id) {
    global $log;
    $log->debug("Entering getQuotesRelatedPotentials(".$record_id.") method ...");
    global $adb;
    $query="select potentialid from jo_quotes where quoteid=?";
    $result=$adb->pquery($query, array($record_id));
    $potid=$adb->query_result($result,0,'potentialid');
    $log->debug("Exiting getQuotesRelatedPotentials method ...");
    return $potid;
}

/** Function to get Quotes related Potentials
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */
function getSalesOrderRelatedAccounts($record_id) {
    global $log;
    $log->debug("Entering getSalesOrderRelatedAccounts(".$record_id.") method ...");
    global $adb;
    $query="select accountid from jo_salesorder where salesorderid=?";
    $result=$adb->pquery($query, array($record_id));
    $accountid=$adb->query_result($result,0,'accountid');
    $log->debug("Exiting getSalesOrderRelatedAccounts method ...");
    return $accountid;
}

/** Function to get SalesOrder related Potentials
  * @param $record_id -- record id :: Type integer
  * @returns $potid -- potid:: Type integer
  */
function getSalesOrderRelatedPotentials($record_id) {
    global $log;
    $log->debug("Entering getSalesOrderRelatedPotentials(".$record_id.") method ...");
    global $adb;
    $query="select potentialid from jo_salesorder where salesorderid=?";
    $result=$adb->pquery($query, array($record_id));
    $potid=$adb->query_result($result,0,'potentialid');
    $log->debug("Exiting getSalesOrderRelatedPotentials method ...");
    return $potid;
}
/** Function to get SalesOrder related Quotes
  * @param $record_id -- record id :: Type integer
  * @returns $qtid -- qtid:: Type integer
  */

function getSalesOrderRelatedQuotes($record_id) {
    global $log;
    $log->debug("Entering getSalesOrderRelatedQuotes(".$record_id.") method ...");
    global $adb;
    $query="select quoteid from jo_salesorder where salesorderid=?";
    $result=$adb->pquery($query, array($record_id));
    $qtid=$adb->query_result($result,0,'quoteid');
    $log->debug("Exiting getSalesOrderRelatedQuotes method ...");
    return $qtid;
}

/** Function to get Invoice related Accounts
  * @param $record_id -- record id :: Type integer
  * @returns $accountid -- accountid:: Type integer
  */

function getInvoiceRelatedAccounts($record_id) {
    global $log;
    $log->debug("Entering getInvoiceRelatedAccounts(".$record_id.") method ...");
    global $adb;
    $query="select accountid from jo_invoice where invoiceid=?";
    $result=$adb->pquery($query, array($record_id));
    $accountid=$adb->query_result($result,0,'accountid');
    $log->debug("Exiting getInvoiceRelatedAccounts method ...");
    return $accountid;
}
/** Function to get Invoice related SalesOrder
  * @param $record_id -- record id :: Type integer
  * @returns $soid -- soid:: Type integer
  */
function getInvoiceRelatedSalesOrder($record_id) {
    global $log;
    $log->debug("Entering getInvoiceRelatedSalesOrder(".$record_id.") method ...");
    global $adb;
    $query="select salesorderid from jo_invoice where invoiceid=?";
    $result=$adb->pquery($query, array($record_id));
    $soid=$adb->query_result($result,0,'salesorderid');
    $log->debug("Exiting getInvoiceRelatedSalesOrder method ...");
    return $soid;
}

/**
* the function is like unescape in javascript
* added by dingjianting on 2006-10-1 for picklist editor
*/
function utf8RawUrlDecode ($source) {
    global $default_charset;
    $decodedStr = "";
    $pos = 0;
    $len = strlen ($source);
    while ($pos < $len) {
        $charAt = substr ($source, $pos, 1);
        if ($charAt == '%') {
            $pos++;
            $charAt = substr ($source, $pos, 1);
            if ($charAt == 'u') {
                // we got a unicode character
                $pos++;
                $unicodeHexVal = substr ($source, $pos, 4);
                $unicode = hexdec ($unicodeHexVal);
                $entity = "&#". $unicode . ';';
                $decodedStr .= utf8_encode ($entity);
                $pos += 4;
	    } else {
                // we have an escaped ascii character
                $hexVal = substr ($source, $pos, 2);
                $decodedStr .= chr (hexdec ($hexVal));
                $pos += 2;
	    }
	} else {
            $decodedStr .= $charAt;
            $pos++;
        }
    }
    if(strtolower($default_charset) == 'utf-8')
	return html_to_utf8($decodedStr);
    else
	return $decodedStr;
}

/**
 *simple HTML to UTF-8 conversion:
 */
function html_to_utf8 ($data) {
    return preg_replace("/\\&\\#([0-9]{3,10})\\;/e", '_html_to_utf8("\\1")', $data);
}

function _html_to_utf8 ($data) {
    if ($data > 127) {
	$i = 5;
	while (($i--) > 0) {
	    if ($data != ($a = $data % ($p = pow(64, $i)))) {
		$ret = chr(base_convert(str_pad(str_repeat(1, $i + 1), 8, "0"), 2, 10) + (($data - $a) / $p));
		for ($i; $i > 0; $i--)
		    $ret .= chr(128 + ((($data % pow(64, $i)) - ($data % ($p = pow(64, $i - 1)))) / $p));
		break;
	    }
	}
    } else
	$ret = "&#$data;";

    return $ret;
}

// Return Question mark
function _questionify($v) {
    return "?";
}
function get_privileges($userid){
  global $adb;
  $user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
          $user_count = $adb->num_rows($user_query);
          if($user_count > 0){
              $user_privilege =  $adb->query_result($user_query,0,'user_privilege');
              $decode_user_privilege_value = json_decode(html_entity_decode($user_privilege));
              foreach ($decode_user_privilege_value as $key => $value) {
                
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
}
function get_sharingprivileges($userid){
  global $adb;
  $user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
          $user_count = $adb->num_rows($user_query);
          if($user_count > 0){
            $sharing_privilege =  $adb->query_result($user_query,0,'sharing_privilege');
            $decode_sharing_privilege_value = json_decode(html_entity_decode($sharing_privilege));
            foreach ($decode_sharing_privilege_value as $key => $value) {
              
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
}

function get_defaultOrgSharingPermission($userid){
    global $adb;
    $user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
            $user_count = $adb->num_rows($user_query);
            if($user_count > 0){
                $sharing_privilege =  $adb->query_result($user_query,0,'sharing_privilege');
                $decode_sharing_privilege_value = json_decode(html_entity_decode($sharing_privilege));
            
                foreach ($decode_sharing_privilege_value as $key => $value) {
                
                  if($key == 'defaultOrgSharingPermission'){
                     
                     if(is_object($value)){
  
                          $values = (array) $value;
                 
                      }
                  
                  
                  }
                  
                 
                  
                }
                
            }
    return $values;     
}

function get_related_module_share($userid){
    global $adb;
    $user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
            $user_count = $adb->num_rows($user_query);
            if($user_count > 0){
                $sharing_privilege =  $adb->query_result($user_query,0,'sharing_privilege');
                $decode_sharing_privilege_value = json_decode(html_entity_decode($sharing_privilege));
               
                foreach ($decode_sharing_privilege_value as $key => $value) {
                
                  if($key == 'related_module_share'){
                     
                     if(is_object($value)){
  
                          $values = (array) $value;
                     
                      }
                  
                  
                  }
                  
                 
                  
                }
            
                return $values;
            }
            
  }
/**
 * Function to generate question marks for a given list of items
 */
function generateQuestionMarks($items_list) {
    // array_map will call the function specified in the first parameter for every element of the list in second parameter
    if (is_array($items_list)) {
	return implode(",", array_map("_questionify", $items_list));
    } else {
	return implode(",", array_map("_questionify", explode(",", $items_list)));
    }
}

/**
 * Function to find the UI type of a field based on the uitype id
 */
function is_uitype($uitype, $reqtype) {
    $ui_type_arr = array(
	'_date_' => array(5, 6, 23, 70),
	'_picklist_' => array(15, 16, 52, 53, 54, 55, 59, 62, 63, 66, 68, 76, 77, 78, 80, 98, 101, 115, 357),
	'_users_list_' => array(52),
    );

    if ($ui_type_arr[$reqtype] != null) {
	if (in_array($uitype, $ui_type_arr[$reqtype])) {
	    return true;
	}
    }
    return false;
}
/**
 * Function to escape quotes
 * @param $value - String in which single quotes have to be replaced.
 * @return Input string with single quotes escaped.
  */
function escape_single_quotes($value) {
    if (isset($value)) $value = str_replace("'", "\'", $value);
	return $value;
}

/**
 * Function to format the input value for SQL like clause.
 * @param $str - Input string value to be formatted.
 * @param $flag - By default set to 0 (Will look for cases %string%).
 * If set to 1 - Will look for cases %string.
 * If set to 2 - Will look for cases string%.
 * @return String formatted as per the SQL like clause requirement
  */
function formatForSqlLike($str, $flag=0,$is_field=false) {
    global $adb;
    if (isset($str)) {
	if($is_field==false){
	    $str = str_replace('%', '\%', $str);
	    $str = str_replace('_', '\_', $str);
	    if ($flag == 0) {
                // If value what to search is null then we should not add % which will fail
                if(empty($str))
                    $str = ''.$str.'';
                else
                    $str = '%'. $str .'%';
	    } elseif ($flag == 1) {
		$str = '%'. $str;
	    } elseif ($flag == 2) {
		$str = $str .'%';
	    }
	} else {
	    if ($flag == 0) {
		$str = 'concat("%",'. $str .',"%")';
	    } elseif ($flag == 1) {
		$str = 'concat("%",'. $str .')';
	    } elseif ($flag == 2) {
		$str = 'concat('. $str .',"%")';
	    }
	}
    }
    return $adb->sql_escape_string($str);
}
/**	Function used to get all the picklists and their values for a module
	@param string $module - Module name to which the list of picklists and their values needed
	@return array $fieldlists - Array of picklists and their values
**/
function getAccessPickListValues($module) {
    global $adb, $log;
    global $current_user;
    $log->debug("Entering into function getAccessPickListValues($module)");

    $id = getTabid($module);
    $query = "select fieldname,columnname,fieldid,fieldlabel,tabid,uitype from jo_field where tabid = ? and uitype in ('15','33','55') and jo_field.presence in (0,2)";
    $result = $adb->pquery($query, array($id));

    $roleid = $current_user->roleid;
    $subrole = getRoleSubordinates($roleid);

    if(count($subrole)> 0) {
	$roleids = $subrole;
	array_push($roleids, $roleid);
    } else {
	$roleids = $roleid;
    }

    $temp_status = Array();
    for($i=0;$i < $adb->num_rows($result);$i++) {
	$fieldname = $adb->query_result($result,$i,"fieldname");
	$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
	$columnname = $adb->query_result($result,$i,"columnname");
	$tabid = $adb->query_result($result,$i,"tabid");
	$uitype = $adb->query_result($result,$i,"uitype");

	$keyvalue = $columnname;
	$fieldvalues = Array();
	if (count($roleids) > 1) {
	    $mulsel="select distinct $fieldname from jo_$fieldname inner join jo_role2picklist on jo_role2picklist.picklistvalueid = jo_$fieldname.picklist_valueid where roleid in (\"". implode($roleids,"\",\"") ."\") and picklistid in (select picklistid from jo_$fieldname) order by sortid asc";
	} else {
	    $mulsel="select distinct $fieldname from jo_$fieldname inner join jo_role2picklist on jo_role2picklist.picklistvalueid = jo_$fieldname.picklist_valueid where roleid ='".$roleid."' and picklistid in (select picklistid from jo_$fieldname) order by sortid asc";
	}
	if($fieldname != 'firstname')
	    $mulselresult = $adb->query($mulsel);

	for($j=0;$j < $adb->num_rows($mulselresult);$j++) {
	    $fieldvalues[] = $adb->query_result($mulselresult,$j,$fieldname);
	}
	$field_count = count($fieldvalues);
	if($uitype == 15 && $field_count > 0 && ($fieldname == 'taskstatus' || $fieldname == 'eventstatus')) {
	    $temp_count =count($temp_status[$keyvalue]);
	    if($temp_count > 0) {
		for($t=0;$t < $field_count;$t++) {
		    $temp_status[$keyvalue][($temp_count+$t)] = $fieldvalues[$t];
		}
		$fieldvalues = $temp_status[$keyvalue];
	    } else
		$temp_status[$keyvalue] = $fieldvalues;
	}

	if($uitype == 33)
	    $fieldlists[1][$keyvalue] = $fieldvalues;
	else if($uitype == 55 && $fieldname == 'salutationtype')
	    $fieldlists[$keyvalue] = $fieldvalues;
	else if($uitype == 15)
	    $fieldlists[$keyvalue] = $fieldvalues;
    }
    $log->debug("Exit from function getAccessPickListValues($module)");
    return $fieldlists;
}

function get_config_status() {
    global $default_charset;
    if(strtolower($default_charset) == 'utf-8')
	$config_status=1;
    else
	$config_status=0;
    return $config_status;
}

function getMigrationCharsetFlag() {
    global $adb;
    if(!$adb->isPostgres())
	$db_status=$adb->check_db_utf8_support();

    $config_status=get_config_status();

    if ($db_status == $config_status) {
	if ($db_status == 1) { // Both are UTF-8
	    $db_migration_status = MIG_CHARSET_PHP_UTF8_DB_UTF8;
	} else { // Both are Non UTF-8
	    $db_migration_status = MIG_CHARSET_PHP_NONUTF8_DB_NONUTF8;
	}
    } else {
	if ($db_status == 1) { // Database charset is UTF-8 and CRM charset is Non UTF-8
	    $db_migration_status = MIG_CHARSET_PHP_NONUTF8_DB_UTF8;
	} else { // Database charset is Non UTF-8 and CRM charset is UTF-8
	    $db_migration_status = MIG_CHARSET_PHP_UTF8_DB_NONUTF8;
	}
    }
    return $db_migration_status;
}

/** Function to get on clause criteria for duplicate check queries */
function get_on_clause($field_list,$uitype_arr,$module) {
    $field_array = explode(",",$field_list);
    $ret_str = '';
    $i=1;
    foreach($field_array as $fld) {
	$sub_arr = explode(".",$fld);
	$tbl_name = $sub_arr[0];
	$col_name = $sub_arr[1];
	$fld_name = $sub_arr[2];

	$ret_str .= " ifnull($tbl_name.$col_name,'null') = ifnull(temp.$col_name,'null')";
	if (count($field_array) != $i) $ret_str .= " and ";
	$i++;
    }
    return $ret_str;
}

// Update all the data refering to currency $old_cur to $new_cur
function transferCurrency($old_cur, $new_cur) {

    // Transfer User currency to new currency
    transferUserCurrency($old_cur, $new_cur);

    // Transfer Product Currency to new currency
    transferProductCurrency($old_cur, $new_cur);

     // Transfer PriceBook Currency to new currency
     transferPriceBookCurrency($old_cur, $new_cur);
    
    // Transfer Services Currency to new currency
    transferServicesCurrency($old_cur, $new_cur);
}

// Function to transfer the users with currency $old_cur to $new_cur as currency
function transferUserCurrency($old_cur, $new_cur) {
    global $log, $adb, $current_user;
    $log->debug("Entering function transferUserCurrency...");

    $sql = 'UPDATE jo_users SET currency_id=? WHERE currency_id=?';
    $adb->pquery($sql, array($new_cur, $old_cur));

    $currentUserId = $current_user->id;
    $current_user->retrieve_entity_info($currentUserId, 'Users');
    unset(Users_Record_Model::$currentUserModels[$currentUserId]);

    require_once('modules/Users/CreateUserPrivilegeFile.php'); 
    createUserPrivilegesfile($currentUserId);

    $log->debug("Exiting function transferUserCurrency...");
}

// Function to transfer the products with currency $old_cur to $new_cur as currency
function transferProductCurrency($old_cur, $new_cur) {
    global $log, $adb;
    $log->debug("Entering function updateProductCurrency...");
    $prod_res = $adb->pquery("select productid from jo_products where currency_id = ?", array($old_cur));
    $numRows = $adb->num_rows($prod_res);
    $prod_ids = array();
    for($i=0;$i<$numRows;$i++) {
	$prod_ids[] = $adb->query_result($prod_res,$i,'productid');
    }
    if(count($prod_ids) > 0) {
	$prod_price_list = getPricesForProducts($new_cur,$prod_ids);
	for($i=0;$i<count($prod_ids);$i++) {
	    $product_id = $prod_ids[$i];
	    $unit_price = $prod_price_list[$product_id];
	    $query = "update jo_products set currency_id=?, unit_price=? where productid=?";
	    $params = array($new_cur, $unit_price, $product_id);
	    $adb->pquery($query, $params);
	}
    }
    $log->debug("Exiting function updateProductCurrency...");
}

// Function to transfer the pricebooks with currency $old_cur to $new_cur as currency
// and to update the associated products with list price in $new_cur currency
function transferPriceBookCurrency($old_cur, $new_cur) {
    global $log, $adb;
    $log->debug("Entering function updatePriceBookCurrency...");
    $pb_res = $adb->pquery("select pricebookid from jo_pricebook where currency_id = ?", array($old_cur));
    $numRows = $adb->num_rows($pb_res);
    $pb_ids = array();
    for($i=0;$i<$numRows;$i++) {
	$pb_ids[] = $adb->query_result($pb_res,$i,'pricebookid');
    }
    if(count($pb_ids) > 0) {
	require_once('modules/PriceBooks/PriceBooks.php');

	for($i=0;$i<count($pb_ids);$i++) {
	    $pb_id = $pb_ids[$i];
	    $focus = new PriceBooks();
	    $focus->id = $pb_id;
	    $focus->mode = 'edit';
	    $focus->retrieve_entity_info($pb_id, "PriceBooks");
	    $focus->column_fields['currency_id'] = $new_cur;
	    $focus->save("PriceBooks");
	}
    }
    $log->debug("Exiting function updatePriceBookCurrency...");
}

//To transfer all services after deleting currency to transfered currency
function transferServicesCurrency($old_cur, $new_cur) {
    global $log, $adb;
    $log->debug("Entering function updateServicesCurrency...");
	$ser_res = $adb->pquery('SELECT serviceid FROM jo_service WHERE currency_id = ?', array($old_cur));
    $numRows = $adb->num_rows($ser_res);
    $ser_ids = array();
    for ($i = 0; $i < $numRows; $i++) {
        $ser_ids[] = $adb->query_result($ser_res, $i, 'serviceid');
    }
    if (count($ser_ids) > 0) {
        $ser_price_list = getPricesForProducts($new_cur, $ser_ids, 'Services');
        for ($i = 0; $i < count($ser_ids); $i++) {
            $service_id = $ser_ids[$i];
            $unit_price = $ser_price_list[$service_id];
			$query = 'UPDATE jo_service SET currency_id=?, unit_price=? WHERE serviceid=?';
            $params = array($new_cur, $unit_price, $service_id);
            $adb->pquery($query, $params);
        }
    }
    $log->debug("Exiting function updateServicesCurrency...");
}

/**
 * this function searches for a given number in vtiger and returns the callerInfo in an array format
 * currently the search is made across only leads, accounts and contacts modules
 *
 * @param $number - the number whose information you want
 * @return array in format array(name=>callername, module=>module, id=>id);
  */
function getCallerInfo($number){
    global $adb, $log;
    if(empty($number)){
	return false;
    }
    $caller = "Unknown Number (Unknown)"; //declare caller as unknown in beginning

    $params = array();
    $name = array('Contacts', 'Accounts', 'Leads');
    foreach ($name as $module) {
	$focus = CRMEntity::getInstance($module);
	$query = $focus->buildSearchQueryForFieldTypes(11, $number);
	if(empty($query)) return;

	$result = $adb->pquery($query, array());
	if($adb->num_rows($result) > 0 ) {
	    $callerName = $adb->query_result($result, 0, "name");
	    $callerID = $adb->query_result($result,0,'id');
	    $data = array("name"=>$callerName, "module"=>$module, "id"=>$callerID);
	    return $data;
	}
    }
    return false;
}

/**
 * this function returns the value of use_asterisk from the database for the current user
 * @param string $id - the id of the current user
 */
function get_use_asterisk($id){
    global $adb;
    if(!modlib_isModuleActive('PBXManager') || isPermitted('PBXManager', 'index') == 'no'){
	return false;
    }
    $sql = "select * from jo_asteriskextensions where userid = ?";
    $result = $adb->pquery($sql, array($id));
    if($adb->num_rows($result)>0){
	$use_asterisk = $adb->query_result($result, 0, "use_asterisk");
	$asterisk_extension = $adb->query_result($result, 0, "asterisk_extension");
	if($use_asterisk == 0 || empty($asterisk_extension)){
	    return 'false';
	}else{
	    return 'true';
	}
    }else{
	return 'false';
    }
}

/**
 * this function adds a record to the callhistory module
 * @param string $userExtension - the extension of the current user
 * @param string $callfrom - the caller number
 * @param string $callto - the called number
 * @param string $status - the status of the call (outgoing/incoming/missed)
 * @param object $adb - the peardatabase object
  */
function addToCallHistory($userExtension, $callfrom, $callto, $status, $adb, $useCallerInfo){
    $sql = "select * from jo_asteriskextensions where asterisk_extension=?";
    $result = $adb->pquery($sql,array($userExtension));
    $userID = $adb->query_result($result, 0, "userid");
    if(empty($userID)) {
	// we have observed call to extension not configured in Head will returns NULL
	return;
    }
    if(empty($callfrom)){
	$callfrom = "Unknown";
    }
    if(empty($callto)){
	$callto = "Unknown";
    }

    if($status == 'outgoing'){
	//call is from user to record
	$sql = "select * from jo_asteriskextensions where asterisk_extension=?";
	$result = $adb->pquery($sql, array($callfrom));
	if($adb->num_rows($result)>0){
	    $userid = $adb->query_result($result, 0, "userid");
	    $callerName = getUserFullName($userid);
	}

	$receiver = $useCallerInfo;
	if(empty($receiver)){
	    $receiver = "Unknown";
	} else {
	    $receiver = "<a href='index.php?module=".$receiver['module']."&action=DetailView&record=".$receiver['id']."'>".$receiver['name']."</a>";
	}
    }else{
	//call is from record to user
	$sql = "select * from jo_asteriskextensions where asterisk_extension=?";
	$result = $adb->pquery($sql,array($callto));
	if($adb->num_rows($result)>0){
	    $userid = $adb->query_result($result, 0, "userid");
	    $receiver = getUserFullName($userid);
	}
	$callerName = $useCallerInfo;
	if(empty($callerName)){
	    $callerName = "Unknown $callfrom";
	}else{
	    $callerName = "<a href='index.php?module=".$callerName['module']."&action=DetailView&record=".$callerName['id']."'>".decode_html($callerName['name'])."</a>";
	}
    }

    $crmID = $adb->getUniqueID('jo_crmentity');
    $timeOfCall = date('Y-m-d H:i:s');

    $query = "INSERT INTO jo_crmentity (crmid,smcreatorid,smownerid,modifiedby,setype,description,createdtime, modifiedtime,viewedtime,status,version,presence,deleted,label) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $adb->pquery($query, array($crmID, $userID, $userID, 0, "PBXManager", "", $timeOfCall, $timeOfCall, NULL, NULL, 0, 1, 0, $callerName));

    $sql = "insert into jo_pbxmanager (pbxmanagerid,callfrom,callto,timeofcall,status)values (?,?,?,?,?)";
    $params = array($crmID, $callerName, $receiver, $timeOfCall, $status);
    $adb->pquery($sql, $params);
    return $crmID;
}
//functions for asterisk integration end
/* Function to get the related tables data
 * @param - $module - Primary module name
 * @param - $secmodule - Secondary module name
 * return Array $rel_array tables and fields to be compared are sent
 * */
function getRelationTables($module,$secmodule) {
    global $adb;
    $primary_obj = CRMEntity::getInstance($module);
    $secondary_obj = CRMEntity::getInstance($secmodule);
    $ui10_query = $adb->pquery("SELECT jo_field.tabid AS tabid,jo_field.tablename AS tablename, jo_field.columnname AS columnname FROM jo_field INNER JOIN jo_fieldmodulerel ON jo_fieldmodulerel.fieldid = jo_field.fieldid WHERE (jo_fieldmodulerel.module=? AND jo_fieldmodulerel.relmodule=?) OR (jo_fieldmodulerel.module=? AND jo_fieldmodulerel.relmodule=?)",array($module,$secmodule,$secmodule,$module));
    if($adb->num_rows($ui10_query)>0) {
	$ui10_tablename = $adb->query_result($ui10_query,0,'tablename');
	$ui10_columnname = $adb->query_result($ui10_query,0,'columnname');
	$ui10_tabid = $adb->query_result($ui10_query,0,'tabid');

	if($primary_obj->table_name == $ui10_tablename){
	    $reltables = array($ui10_tablename=>array("".$primary_obj->table_index."","$ui10_columnname"));
	} else if($secondary_obj->table_name == $ui10_tablename){
	    $reltables = array($ui10_tablename=>array("$ui10_columnname","".$secondary_obj->table_index.""),"".$primary_obj->table_name."" => "".$primary_obj->table_index."");
	} else {
	    if(isset($secondary_obj->tab_name_index[$ui10_tablename])){
		$rel_field = $secondary_obj->tab_name_index[$ui10_tablename];
		$reltables = array($ui10_tablename=>array("$ui10_columnname","$rel_field"),"".$primary_obj->table_name."" => "".$primary_obj->table_index."");
	    } else {
		$rel_field = $primary_obj->tab_name_index[$ui10_tablename];
		$reltables = array($ui10_tablename=>array("$rel_field","$ui10_columnname"),"".$primary_obj->table_name."" => "".$primary_obj->table_index."");
	    }
	}
    }else {
	if(method_exists($primary_obj,setRelationTables)){
	    $reltables = $primary_obj->setRelationTables($secmodule);
	} else {
	    $reltables = '';
	}
    }
    if(is_array($reltables) && !empty($reltables)){
	$rel_array = $reltables;
    } else {
	$rel_array = array("jo_crmentityrel"=>array("crmid","relcrmid"),"".$primary_obj->table_name."" => "".$primary_obj->table_index."");
    }
    return $rel_array;
}

/**
 * This function returns no value but handles the delete functionality of each entity.
 * Input Parameter are $module - module name, $return_module - return module name, $focus - module object, $record - entity id, $return_id - return entity id.
  */
function DeleteEntity($module,$return_module,$focus,$record,$return_id) {
    global $log;
    $log->debug("Entering DeleteEntity method ($module, $return_module, $record, $return_id)");

    if ($module != $return_module && !empty($return_module) && !empty($return_id)) {
	$focus->unlinkRelationship($record, $return_module, $return_id);
	$focus->trackUnLinkedInfo($return_module, $return_id, $module, $record);
    } else {
	$focus->trash($module, $record);
    }
    $log->debug("Exiting DeleteEntity method ...");
}

/**
 * Function to related two records of different entity types
 */
function relateEntities($focus, $sourceModule, $sourceRecordId, $destinationModule, $destinationRecordIds) {
    if(!is_array($destinationRecordIds)) $destinationRecordIds = Array($destinationRecordIds);
    foreach($destinationRecordIds as $destinationRecordId) {
	$focus->save_related_module($sourceModule, $sourceRecordId, $destinationModule, $destinationRecordId);
	$focus->trackLinkedInfo($sourceModule, $sourceRecordId, $destinationModule, $destinationRecordId);
    }
}

/**
 * Track install/update modlib module in current run.
 */
$_installOrUpdateModlibModule = array();

/* Function to install Modlib Compliant modules
 * @param - $packagename - Name of the module
 * @param - $packagepath - Complete path to the zip file of the Module
  */
function installModlibModule($packagename, $packagepath, $customized=false) {
    global $log, $Head_Utils_Log, $_installOrUpdateModlibModule;
    if(!file_exists($packagepath)) return;

    if (isset($_installOrUpdateModlibModule[$packagename.$packagepath])) return;
    $_installOrUpdateModlibModule[$packagename.$packagepath] = 'install';

    require_once('libraries/modlib/Head/Package.php');
    require_once('libraries/modlib/Head/Module.php');
    $Head_Utils_Log = defined('INSTALLATION_MODE_DEBUG')? INSTALLATION_MODE_DEBUG : true;
    $package = new Head_Package();

    if($package->isLanguageType($packagepath)) {
	$package = new Head_Language();
	$package->import($packagepath, true);
	return;
    }
    $module = $package->getModuleNameFromZip($packagepath);
    // Customization
    if($package->isLanguageType()) {
	require_once('libraries/modlib/Head/Language.php');
	$languagePack = new Head_Language();
	@$languagePack->import($packagepath, true);
	return;
    }
    // END
    $module_exists = false;
    $module_dir_exists = false;
    if($module == null) {
	$log->fatal("$packagename Module zipfile is not valid!");
    } else if(Head_Module::getInstance($module)) {
	$log->fatal("$module already exists!");
	$module_exists = true;
    }
    if($module_exists == false) {
	$log->debug("$module - Installation starts here");
	$package->import($packagepath, true);
	$moduleInstance = Head_Module::getInstance($module);
	if (empty($moduleInstance)) {
	    $log->fatal("$module module installation failed!");
	}
    }
}

/* Function to update Modlib Compliant modules
 * @param - $module - Name of the module
 * @param - $packagepath - Complete path to the zip file of the Module
 */
function updateModlibModule($module, $packagepath) {
    global $log, $_installOrUpdateModlibModule;
    if(!file_exists($packagepath)) return;

    if (isset($_installOrUpdateModlibModule[$module.$packagepath])) return;
    $_installOrUpdateModlibModule[$module.$packagepath] = 'update';

    require_once('libraries/modlib/Head/Package.php');
    require_once('libraries/modlib/Head/Module.php');
    $Head_Utils_Log = defined('INSTALLATION_MODE_DEBUG')? INSTALLATION_MODE_DEBUG : true;
    $package = new Head_Package();

    if($package->isLanguageType($packagepath)) {
	require_once('libraries/modlib/Head/Language.php');
	$languagePack = new Head_Language();
	$languagePack->update(null, $packagepath, true);
	return;
    }

    if($module == null) {
	$log->fatal("Module name is invalid");
    } else {
	$moduleInstance = Head_Module::getInstance($module);
	if($moduleInstance || $package->isModuleBundle($packagepath)) {
	    $log->debug("$module - Module instance found - Update starts here");
	    $package->update($moduleInstance, $packagepath);
	} else {
	    $log->fatal("$module doesn't exists!");
	}
    }
}

/**
 * this function checks if a given column exists in a given table or not
 * @param string $columnName - the columnname
 * @param string $tableName - the tablename
 * @return boolean $status - true if column exists; false otherwise
 */
function columnExists($columnName, $tableName){
    global $adb;
    $columnNames = array();
    $columnNames = $adb->getColumnNames($tableName);

    if(in_array($columnName, $columnNames)){
	return true;
    }else{
        return false;
    }
}

/* To get modules list for which work flow and field formulas is permitted*/
function com_vtGetModules($adb) {
    $sql="select distinct jo_field.tabid, name from jo_field inner join jo_tab on jo_field.tabid=jo_tab.tabid where jo_field.tabid not in(9,10,16,15,29) and jo_tab.presence = 0 and jo_tab.isentitytype=1";
    $it = new SqlResultIterator($adb, $adb->query($sql));
    $modules = array();
    foreach($it as $row) {
	if(isPermitted($row->name,'index') == "yes") {
	    $modules[$row->name] = getTranslatedString($row->name);
	}
    }
    return $modules;
}

/**
 * Function to check if a given record exists (not deleted)
 * @param integer $recordId - record id
 */
function isRecordExists($recordId) {
    global $adb;
    $query = "SELECT crmid FROM jo_crmentity where crmid=? AND deleted=0";
    $result = $adb->pquery($query, array($recordId));
    if ($adb->num_rows($result)) {
	return true;
    }
    return false;
}

/** Function to set date values compatible to database (YY_MM_DD)
  * @param $value -- value :: Type string
  * @returns $insert_date -- insert_date :: Type string
  */
function getValidDBInsertDateValue($value) {
    global $log;
    $log->debug("Entering getValidDBInsertDateValue(".$value.") method ...");
    $value = trim($value);
    $delim = array('/','.');
    foreach ($delim as $delimiter){
	$x = strpos($value, $delimiter);
	if($x === false) continue;
	else{
	    $value=str_replace($delimiter, '-', $value);
	    break;
	}
    }
    list($y,$m,$d) = explode('-',$value);
    if(strlen($y) == 1) $y = '0'.$y;
    if(strlen($m) == 1) $m = '0'.$m;
    if(strlen($d) == 1) $d = '0'.$d;
    $value = implode('-', array($y,$m,$d));

    if(strlen($y)<4){
	$insert_date = DateTimeField::convertToDBFormat($value);
    } else {
	$insert_date = $value;
    }

    if (preg_match("/^[0-9]{2,4}[-][0-1]{1,2}?[0-9]{1,2}[-][0-3]{1,2}?[0-9]{1,2}$/", $insert_date) == 0) {
	return '';
    }

    $log->debug("Exiting getValidDBInsertDateValue method ...");
    return $insert_date;
}

function getValidDBInsertDateTimeValue($value) {
    $value = trim($value);
    $valueList = explode(' ',$value);
    if(count($valueList) == 2) {
	$dbDateValue = getValidDBInsertDateValue($valueList[0]);
	$dbTimeValue = $valueList[1];
	if(!empty($dbTimeValue) && strpos($dbTimeValue, ':') === false) {
	    $dbTimeValue = $dbTimeValue.':';
	}
	$timeValueLength = strlen($dbTimeValue);
	if(!empty($dbTimeValue) &&  strrpos($dbTimeValue, ':') == ($timeValueLength-1)) {
	    $dbTimeValue = $dbTimeValue.'00';
	}
	try {
	    $dateTime = new DateTimeField($dbDateValue.' '.$dbTimeValue);
	    return $dateTime->getDBInsertDateTimeValue();
	} catch (Exception $ex) {
	    return '';
	}
    } elseif(count($valueList == 1)) {
	return getValidDBInsertDateValue($value);
    }
}

/** Function to set the PHP memory limit to the specified value, if the memory limit set in the php.ini is less than the specified value
 * @param $newvalue -- Required Memory Limit
 */
function _phpset_memorylimit_MB($newvalue) {
    $current = @ini_get('memory_limit');
    if(preg_match("/(.*)M/", $current, $matches)) {
        // Check if current value is less then new value
        if($matches[1] < $newvalue) {
            @ini_set('memory_limit', "{$newvalue}M");
	}
    }
}

/** Function to sanitize the upload file name when the file name is detected to have bad extensions
 * @param String -- $fileName - File name to be sanitized
 * @return String - Sanitized file name
 */
function sanitizeUploadFileName($fileName, $badFileExtensions) {
    $fileName = preg_replace('/\s+/', '_', $fileName);//replace space with _ in filename
    $fileName = rtrim($fileName, '\\/<>?*:"<>|');

    $fileNameParts = explode(".", $fileName);
    $countOfFileNameParts = count($fileNameParts);
    $badExtensionFound = false;

    for ($i=0;$i<$countOfFileNameParts;++$i) {
	$partOfFileName = $fileNameParts[$i];
	if(in_array(strtolower($partOfFileName), $badFileExtensions)) {
	    $badExtensionFound = true;
	    $fileNameParts[$i] = $partOfFileName . 'file';
	}
    }

    $newFileName = implode(".", $fileNameParts);
    if ($badExtensionFound) {
	$newFileName .= ".txt";
    }
    return $newFileName;
}

/** Function to get the tab meta information for a given id
  * @param $tabId -- tab id :: Type integer
  * @returns $tabInfo -- array of preference name to preference value :: Type array
  */
function getTabInfo($tabId) {
    global $adb;
    $tabInfoResult = $adb->pquery('SELECT prefname, prefvalue FROM jo_tab_info WHERE tabid=?', array($tabId));
    $tabInfo = array();
    for($i=0; $i<$adb->num_rows($tabInfoResult); ++$i) {
	$prefName = $adb->query_result($tabInfoResult, $i, 'prefname');
	$prefValue = $adb->query_result($tabInfoResult, $i, 'prefvalue');
	$tabInfo[$prefName] = $prefValue;
    }
}

/** Function to return block name
 * @param Integer -- $blockid
 * @return String - Block Name
 */
function getBlockName($blockid) {
    global $adb;
    $blockname = CacheUtils::lookupBlockLabelWithId($blockid);

    if(!empty($blockid) && $blockname === false){
	$block_res = $adb->pquery('SELECT blocklabel FROM jo_blocks WHERE blockid = ?',array($blockid));
	if($adb->num_rows($block_res)){
	    $blockname = $adb->query_result($block_res,0,'blocklabel');
	} else {
	    $blockname = '';
	}
	CacheUtils::updateBlockLabelWithId($blockname, $blockid);
    }
    return $blockname;
}

function validateAlphaNumericInput($string){
    preg_match('/^[\w _\-]+$/', $string, $matches);
    if(count($matches) == 0) {
        return false;
	}
    return true;
}

function validateServerName($string){
    preg_match('/^[\w\-\.\\/:]+$/', $string, $matches);
    if(count($matches) == 0) {
        return false;
		}
    return true;
	}

function validateEmailId($string){
    preg_match('/^[a-zA-Z0-9]+([\_\-\.]*[a-zA-Z0-9]+[\_\-]?)*@[a-zA-Z0-9]+([\_\-]?[a-zA-Z0-9]+)*\.+([\-\_]?[a-zA-Z0-9])+(\.?[a-zA-Z0-9]+)*$/', $string, $matches);
    if(count($matches) == 0) {
        return false;
    }
    return true;
}

/**
* Function to get the approximate difference between two date time values as string
*/
function dateDiffAsString($d1, $d2) {
    global $currentModule;
    $dateDiff = dateDiff($d1, $d2);

    $years = $dateDiff['years'];
    $months = $dateDiff['months'];
    $days = $dateDiff['days'];
    $hours = $dateDiff['hours'];
    $minutes = $dateDiff['minutes'];
    $seconds = $dateDiff['seconds'];

    if($years > 0) {
	$diffString = "$years ".getTranslatedString('LBL_YEARS',$currentModule);
    } elseif($months > 0) {
	$diffString = "$months ".getTranslatedString('LBL_MONTHS',$currentModule);
    } elseif($days > 0) {
	$diffString = "$days ".getTranslatedString('LBL_DAYS',$currentModule);
    } elseif($hours > 0) {
	$diffString = "$hours ".getTranslatedString('LBL_HOURS',$currentModule);
    } elseif($minutes > 0) {
	$diffString = "$minutes ".getTranslatedString('LBL_MINUTES',$currentModule);
    } else {
	$diffString = "$seconds ".getTranslatedString('LBL_SECONDS',$currentModule);
    }
    return $diffString;
}

function getMinimumCronFrequency() {
    global $MINIMUM_CRON_FREQUENCY;

    if(!empty($MINIMUM_CRON_FREQUENCY)) {
	return $MINIMUM_CRON_FREQUENCY;
    }
    return 15;
}

//Function returns Email related Modules
function getEmailRelatedModules() {
    global $current_user;
    $handler = vtws_getModuleHandlerFromName('Emails',$current_user);
    $meta = $handler->getMeta();
    $moduleFields = $meta->getModuleFields();
    $fieldModel = $moduleFields['parent_id'];
    $relatedModules = $fieldModel->getReferenceList();
    foreach($relatedModules as $key=>$value) {
	if($value == 'Users') {
	    unset($relatedModules[$key]);
	}
    }
    return $relatedModules;
}

//Get the User selected NumberOfCurrencyDecimals
function getCurrencyDecimalPlaces($user = null) {
    global $current_user;
    if (!empty($user)) {
        $currency_decimal_places = $user->no_of_currency_decimals;
    } else {
        $currency_decimal_places = $current_user->no_of_currency_decimals;
    }
    if (isset($currency_decimal_places)) {
        return $currency_decimal_places;
    } else {
        return 2;
    }
}

function getInventoryModules() {
    $inventoryModules = array('Invoice','Quotes','PurchaseOrder','SalesOrder');
    return $inventoryModules;
}

/* Function to only initialize the update of Modlib Compliant modules
 * @param - $module - Name of the module
 * @param - $packagepath - Complete path to the zip file of the Module
 */
function initUpdateModlibModule($module, $packagepath) {
    global $log;
    require_once('libraries/modlib/Head/Package.php');
    require_once('libraries/modlib/Head/Module.php');
    $Head_Utils_Log = true;
    $package = new Head_Package();

    if($module == null) {
	$log->fatal("Module name is invalid");
    } else {
	$moduleInstance = Head_Module::getInstance($module);
	if($moduleInstance) {
	    $log->debug("$module - Module instance found - Init Update starts here");
	    $package->initUpdate($moduleInstance, $packagepath, true);
	} else {
	    $log->fatal("$module doesn't exists!");
	}
    }
}

/**
 * Function to get the list of Contacts related to an activity
 * @param Integer $activityId
 * @return Array $contactsList - List of Contact ids, mapped to Contact Names
 */
function getActivityRelatedContacts($activityId) {
    $adb = PearDatabase::getInstance();

    $query = 'SELECT * FROM jo_cntactivityrel WHERE activityid=?';
    $result = $adb->pquery($query, array($activityId));

    $noOfContacts = $adb->num_rows($result);
    $contactsList = array();
    for ($i = 0; $i < $noOfContacts; ++$i) {
	$contactId = $adb->query_result($result, $i, 'contactid');
	$displayValueArray = getEntityName('Contacts', $contactId);
	if (!empty($displayValueArray)) {
	    foreach ($displayValueArray as $key => $field_value) {
		$contact_name = $field_value;
	    }
	} else {
	    $contact_name='';
	}
	$contactsList[$contactId] = $contact_name;
    }
    return $contactsList;
}

function isLeadConverted($leadId) {
    $adb = PearDatabase::getInstance();
    $query = 'SELECT converted FROM jo_leaddetails WHERE converted = 1 AND leadid=?';
    $params = array($leadId);
    $result = $adb->pquery($query, $params);
    if($result && $adb->num_rows($result) > 0) {
	return true;
    }
    return false;
}

function getSelectedRecords($input,$module,$idstring,$excludedRecords) {
    global $current_user, $adb;
    if($idstring == 'relatedListSelectAll') {
	$recordid = modlib_purify($input['recordid']);
	if($module == 'Accounts') {
	    $result = getCampaignAccountIds($recordid);
	}
	if($module == 'Contacts') {
	    $result = getCampaignContactIds($recordid);
	}
	if($module == 'Leads') {
	    $result = getCampaignLeadIds($recordid);
	}
	$storearray = array();
	for ($i = 0; $i < $adb->num_rows($result); $i++) {
	    $storearray[] = $adb->query_result($result, $i, 'id');
	}
	$excludedRecords=explode(';',$excludedRecords);
	$storearray=array_diff($storearray,$excludedRecords);
    } else if($module == 'Documents') {
	if($input['selectallmode']=='true') {
	    $result = getSelectAllQuery($input,$module);
	    $storearray = array();
	    $focus = CRMEntity::getInstance($module);

	    for ($i = 0; $i < $adb->num_rows($result); $i++) {
		$storearray[] = $adb->query_result($result, $i, $focus->table_index);
	    }

	    $excludedRecords = explode(';',$excludedRecords);
	    $storearray = array_diff($storearray,$excludedRecords);
	    if($idstring != 'all') {
		$storearray = array_merge($storearray,explode(';',$idstring));
	    }
	    $storearray = array_unique($storearray);
	} else {
	    $storearray = explode(";",$idstring);
	}
    } elseif($idstring == 'all') {
	$result = getSelectAllQuery($input,$module);
	$storearray = array();
	$focus = CRMEntity::getInstance($module);

	for ($i = 0; $i < $adb->num_rows($result); $i++) {
	    $storearray[] = $adb->query_result($result, $i, $focus->table_index);
	}
	$excludedRecords = explode(';',$excludedRecords);
	$storearray = array_diff($storearray,$excludedRecords);
    } else {
	$storearray = explode(";",$idstring);
    }
    return $storearray;
}

function getSelectAllQuery($input,$module) {
    global $adb,$current_user;
    $viewid = modlib_purify($input['viewname']);

    if($module == "Calendar") {
	$listquery = getListQuery($module);
	$oCustomView = new CustomView($module);
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,$module);
	$where = '';
	if($input['query'] == 'true') {
	    list($where, $ustring) = split("#@@#",getWhereCondition($module, $input));
	    if(isset($where) && $where != '') {
		$query .= " AND " .$where;
	    }
	}
    } else {
	$queryGenerator = new QueryGenerator($module, $current_user);
	$queryGenerator->initForCustomViewById($viewid);

	if($input['query'] == 'true') {
	    $queryGenerator->addUserSearchConditions($input);
	}

	$queryGenerator->setFields(array('id'));
	$query = $queryGenerator->getQuery();

	if($module == 'Documents') {
	    $folderid = modlib_purify($input['folderidstring']);
	    $folderid = str_replace(';', ',', $folderid);
	    $query .= " AND jo_notes.folderid in (".$folderid.")";
	}
    }

    $result = $adb->pquery($query, array());
    return $result;
}

function getCampaignAccountIds($id) {
    global $adb;
    $sql="SELECT jo_account.accountid as id FROM jo_account
		INNER JOIN jo_campaignaccountrel ON jo_campaignaccountrel.accountid = jo_account.accountid
		LEFT JOIN jo_crmentity ON jo_crmentity.crmid = jo_account.accountid
		WHERE jo_campaignaccountrel.campaignid = ? AND jo_crmentity.deleted=0";
    $result = $adb->pquery($sql, array($id));
    return $result;
}

function getCampaignContactIds($id) {
    global $adb;
    $sql="SELECT jo_contactdetails.contactid as id FROM jo_contactdetails
		INNER JOIN jo_campaigncontrel ON jo_campaigncontrel.contactid = jo_contactdetails.contactid
		LEFT JOIN jo_crmentity ON jo_crmentity.crmid = jo_contactdetails.contactid
		WHERE jo_campaigncontrel.campaignid = ? AND jo_crmentity.deleted=0";
    $result = $adb->pquery($sql, array($id));
    return $result;
}

function getCampaignLeadIds($id) {
    global $adb;
    $sql="SELECT jo_leaddetails.leadid as id FROM jo_leaddetails
		INNER JOIN jo_campaignleadrel ON jo_campaignleadrel.leadid = jo_leaddetails.leadid
		LEFT JOIN jo_crmentity ON jo_crmentity.crmid = jo_leaddetails.leadid
		WHERE jo_campaignleadrel.campaignid = ? AND jo_crmentity.deleted=0";
    $result = $adb->pquery($sql, array($id));
    return $result;
}

/** Function to get the difference between 2 datetime strings or millisecond values */
function dateDiff($d1, $d2){
    $d1 = (is_string($d1) ? strtotime($d1) : $d1);
    $d2 = (is_string($d2) ? strtotime($d2) : $d2);

    $diffSecs = abs($d1 - $d2);
    $baseYear = min(date("Y", $d1), date("Y", $d2));
    $diff = mktime(0, 0, $diffSecs, 1, 1, $baseYear);
    return array(
		"years" => date("Y", $diff) - $baseYear,
		"months_total" => (date("Y", $diff) - $baseYear) * 12 + date("n", $diff) - 1,
		"months" => date("n", $diff) - 1,
		"days_total" => floor($diffSecs / (3600 * 24)),
		"days" => date("j", $diff) - 1,
		"hours_total" => floor($diffSecs / 3600),
		"hours" => date("G", $diff),
		"minutes_total" => floor($diffSecs / 60),
		"minutes" => (int) date("i", $diff),
		"seconds_total" => $diffSecs,
		"seconds" => (int) date("s", $diff)
    );
}

function getExportRecordIds($moduleName, $viewid, $input) {
    global $adb, $current_user, $list_max_entries_per_page;

    $idstring = modlib_purify($input['idstring']);
    $export_data = modlib_purify($input['export_data']);

    if (in_array($moduleName, getInventoryModules()) && $export_data == 'currentpage') {
	$queryGenerator = new QueryGenerator($moduleName, $current_user);
	$queryGenerator->initForCustomViewById($viewid);

	if($input['query'] == 'true') {
	    $queryGenerator->addUserSearchConditions($input);
	}

	$queryGenerator->setFields(array('id'));
	$query = $queryGenerator->getQuery();
	$current_page = ListViewSession::getCurrentPage($moduleName,$viewid);
	$limit_start_rec = ($current_page - 1) * $list_max_entries_per_page;
	if ($limit_start_rec < 0) $limit_start_rec = 0;
	$query .= ' LIMIT '.$limit_start_rec.','.$list_max_entries_per_page;

	$result = $adb->pquery($query, array());
	$idstring = array();
	$focus = CRMEntity::getInstance($moduleName);
	for ($i = 0; $i < $adb->num_rows($result); $i++) {
	    $idstring[] = $adb->query_result($result, $i, $focus->table_index);
	}
	$idstring = implode(';',$idstring);
	$export_data = 'selecteddata';
    }
    return $idstring. '#@@#' .$export_data;
}

/**
 * Function to get combinations of string from Array
 * @param <Array> $array
 * @param <String> $tempString
 * @return <Array>
 */
function getCombinations($array, $tempString = '') {
    for ($i=0; $i<count($array); $i++) {
	$splicedArray = $array;
	$element = array_splice($splicedArray, $i, 1);// removes and returns the i'th element
	if (count($splicedArray) > 0) {
	    if(!is_array($result)) {
		 $result = array();
	    }
	    $result = array_merge($result, getCombinations($splicedArray, $tempString. ' |##| ' .$element[0]));
	} else {
	    return array($tempString. ' |##| ' . $element[0]);
	}
    }
    return $result;
}

function getCompanyDetails() {
    global $adb;

    $sql="select * from jo_organizationdetails";
    $result = $adb->pquery($sql, array());

    $companyDetails = array();
    $companyDetails['companyname'] = $adb->query_result($result,0,'organizationname');
    $companyDetails['website'] = $adb->query_result($result,0,'website');
    $companyDetails['address'] = $adb->query_result($result,0,'address');
    $companyDetails['city'] = $adb->query_result($result,0,'city');
    $companyDetails['state'] = $adb->query_result($result,0,'state');
    $companyDetails['country'] = $adb->query_result($result,0,'country');
    $companyDetails['phone'] = $adb->query_result($result,0,'phone');
    $companyDetails['fax'] = $adb->query_result($result,0,'fax');
    $companyDetails['logoname'] = $adb->query_result($result,0,'logoname');

    return $companyDetails;
}

/** call back function to change the array values in to lower case */
function lower_array(&$string){
    $string = strtolower(trim($string));
}

/* PHP 7 support */
function php7_compat_split($delim, $str, $ignore_case=false) {
    $splits = array();
    while ($str) {
	$pos = $ignore_case ? stripos($str, $delim) : strpos($str, $delim);
	if ($pos !== false) {
	    $splits[] = substr($str, 0, $pos);
	    $str = substr($str, $pos + strlen($delim));
	} else {
	    $splits[] = $str;
	    $str = false;
	}
    }
    return $splits;
}

if (!function_exists('split'))  { function split($delim, $str)  {return php7_compat_split($delim, $str); } }
if (!function_exists('spliti')) { function spliti($delim, $str) {return php7_compat_split($delim, $str, true);}}

function php7_compat_ereg($pattern, $str, $ignore_case=false) {
    $regex = '/'. preg_replace('/\//', '\\/', $pattern) .'/' . ($ignore_case ? 'i': '');
    return preg_match($regex, $str);
}

if (!function_exists('ereg')) { function ereg($pattern, $str) { return php7_compat_ereg($pattern, $str); } }
if (!function_exists('eregi')) { function eregi($pattern, $str) { return php7_compat_ereg($pattern, $str, true); } }

if (!function_exists('get_magic_quotes_runtime')) { function get_magic_quotes_runtime() { return false; } }
if (!function_exists('set_magic_quotes_runtime')) { function set_magic_quotes_runtime($flag) {} }

/** 
 * Function to escape backslash (\ to \\) in a string
 * @param string $value String to be escaped
 * @return string escaped string
 */
function escapeSlashes($value) {
    return str_replace('\\', '\\\\', $value);
}

/** Function to get a user id or group id for a given entity
 *  @param $record -- entity id :: Type integer
 *  @returns reports to id <int>
 */
function getRecordOwnerReportsToId($record) {
    global $adb;
    $reportsToId = false;
    $ownerArr = getRecordOwnerId($record);
    if ($ownerArr['Users']) {
        $query = "SELECT reports_to_id FROM jo_users WHERE id = ?";
        $result = $adb->pquery($query, array($ownerArr['Users']));
        if ($adb->num_rows($result) > 0) {
            $reportsToId = $adb->query_result($result, 0, 'reports_to_id');
        }
    }
    return $reportsToId;
}

/**
 * Function to get last week range of give date
 * @param type $date
 * @return array($timestamps)
 */
function getLastWeekRange($date) {
    $given_time = strtotime($date);
    $day = gmdate('D', $given_time);

    if ($day == 'Sun') {
	$thissunday = strtotime("this sunday", $given_time);
    } else {
	$thissunday = strtotime("-1 week sunday", $given_time);
    }

    $lastSunday = $thissunday - (7 * 24 * 60 * 60); // 7 days; 24 hours; 60 mins; 60secs
    $lastSaturday = $lastSunday + (6 * 24 * 60 * 60);

    $lastWeekRange = array("start" => $lastSunday, "end" => $lastSaturday);
    return $lastWeekRange;
}

/**
 * Function to get current week range of given date
 * @param type $date
 * @return array($timestamps)
 */
function getCurrentWeekRange($date) {
    $given_time = strtotime($date);
    $day = gmdate('D', $given_time);

    if ($day == 'Sun') {
	$thissunday = $given_time;
    } else {
	$thissunday = strtotime("-1 week sunday", $given_time);
    }
    $thisSaturday = $thissunday + (6 * 24 * 60 * 60);

    $currentWeekRange = array("start" => $thissunday, "end" => $thisSaturday);
    return $currentWeekRange;
}

/**
 * Function to get a group id for a given entity
 * @param $record -- entity id :: Type integer
 * @returns group id <int>
 */
function getRecordGroupId($record) {
    global $adb;
    // Look at cache first for information
    $groupId = CacheUtils::lookupRecordGroup($record);

    if ($groupId === false) {
	$query = "SELECT smgroupid FROM jo_crmentity WHERE crmid = ?";
	$result = $adb->pquery($query, array($record));
	if ($adb->num_rows($result) > 0) {
	    $groupId = $adb->query_result($result, 0, 'smgroupid');
	    // Update cache forupdateRecordGroup re-use
	    CacheUtils::updateRecordGroup($record, $groupId);
	}
    }
    return $groupId;
}

/**
 * Function to delete record from $_SESSION[$moduleName.'_DetailView_Navigation'.$cvId]
 */
function deleteRecordFromDetailViewNavigationRecords($recordId, $cvId, $moduleName) {
    $recordNavigationInfo = Zend_Json::decode($_SESSION[$moduleName . '_DetailView_Navigation' . $cvId]);
    if (count($recordNavigationInfo) != 0) {
	foreach ($recordNavigationInfo as $key => $recordIdList) {
	    $recordIdList = array_diff($recordIdList, array($recordId));
	    $recordNavigationInfo[$key] = $recordIdList;
	}
	$_SESSION[$moduleName . '_DetailView_Navigation' . $cvId] = Zend_Json::encode($recordNavigationInfo);
    }
}

/**
 * Function to check if the module has the Detail view summary widget
 */
function getDetailViewSummaryWidget($moduleName){
    global $adb;
    $widget_arrayValues = [];
    $tabId = getTabid($moduleName);
    $linktype = 'DETAILVIEWSUMMARYWIDGET';
    $getLinkId = $adb->pquery('select * from jo_links where tabid = ? and linktype = ?', array($tabId, $linktype));
    while($fetchValues =$adb->fetch_array($getLinkId)) {
	array_push($widget_arrayValues, $fetchValues);
    }
    return $widget_arrayValues;
}

/**
 * Function to get the field id of the given field label and blockid
 **/
function getSettingsFieldId($blockid, $field_label) {
    global $adb;
    $getLinkId = $adb->pquery('select fieldid from jo_settings_field where blockid = ? and name = ?', array($blockid, $field_label));
    $fetchValues =$adb->fetch_array($getLinkId);
    return $fetchValues['fieldid'];
}


/**
 * Function to check if the module has the List view sidebar widget
 */
function getListViewSideBarWidget($moduleName){
    global $adb;
    $listview_widget_arrayValues = [];
    $tabId = getTabid($moduleName);
    $linktype = 'LISTVIEWSIDEBARWIDGET';
    $getLinkId = $adb->pquery('select * from jo_links where tabid = ? and linktype = ?', array($tabId, $linktype));
    while($fetchValues =$adb->fetch_array($getLinkId)) {
	array_push($listview_widget_arrayValues, $fetchValues);
    }
    return $listview_widget_arrayValues;
}

/**
 * Function to get the starred records
 */
function getRecentlyStarred($user_specific_table){
    global $adb;
    $getStarred = $adb->pquery("SELECT crmid, label, modifiedtime FROM jo_crmentity INNER JOIN .$user_specific_table ON recordid = crmid and starred=1 ORDER BY modifiedtime limit 5;");
    $starred_array = [];
    while($fetchValues = $adb->fetch_array($getStarred)) {
	array_push($starred_array, $fetchValues);
    }
    return $starred_array;
}

/**
 * Function to get the sales stage array
 **/
function getSalesStageArray($modue) {
    global $adb;
    $sales_arrayValues = [];
    $runQuery = $adb->pquery("SELECT * FROM jo_sales_stage order by sortorderid asc");
    while($fetchValues =$adb->fetch_array($runQuery)) {
	array_push($sales_arrayValues, $fetchValues);
    }

    $n = count($sales_arrayValues);
    $sales_stage_ids = [];
    $sales_stage_names = [];
    $sales_array = [];
    for($i = 0; $i<$n; $i++) {
        array_push($sales_stage_ids, $sales_arrayValues[$i]['sales_stage_id']);
	if($mode == 'forecast')
	    array_push($sales_stage_names, vtranslate($sales_arrayValues[$i]['sales_stage'], 'Potentials'));
	else
	    array_push($sales_stage_names, $sales_arrayValues[$i]['sales_stage']);
    }
    $sales_array = array_combine($sales_stage_ids, $sales_stage_names);
    return $sales_array;
}

/**
 * Function to get the sales stage sequence array
 **/
function getStageSequenceArray() {
    global $adb;
    $sales_arrayValues = [];
    $runQuery = $adb->pquery("SELECT * FROM jo_sales_stage");
    while($fetchValues =$adb->fetch_array($runQuery)) {
	array_push($sales_arrayValues, $fetchValues);
    }
    $n = count($sales_arrayValues);
    $sales_stage_ids = [];
    $sales_stage_sequence = [];
    $sales_sequence_array = [];
    for($i = 0; $i<$n; $i++) {
	array_push($sales_stage_ids, $sales_arrayValues[$i]['sales_stage_id']);
	array_push($sales_stage_sequence, $sales_arrayValues[$i]['sortorderid']);
    }
    $sales_sequence_array = array_combine($sales_stage_ids, $sales_stage_sequence);
    return $sales_sequence_array;
}

/**
 * Function to get the sales stage id
 **/
function getStageId($stage_name) {
    global $adb;
    $sales_arrayValues = [];
    $runQuery = $adb->pquery("SELECT sales_stage_id FROM jo_sales_stage where sales_stage = ? " , array($stage_name));
    $fetchValues =$adb->fetch_array($runQuery);
                
    return $fetchValues['sales_stage_id'];
}

/**
 * Function to get the sales stage name
 **/
function getStageName($stage_id) {
    global $adb;
    $sales_arrayValues = [];
    $runQuery = $adb->pquery("SELECT sales_stage FROM jo_sales_stage where sales_stage_id = ? " , array($stage_id));
    $fetchValues =$adb->fetch_array($runQuery);
                
    return $fetchValues['sales_stage'];
}

/**
 * Function to get table row list view more actions
 */
function getListViewRowActions($moduleName){
    global $adb;
    $list_view_row_action = [];
    $tabId = getTabid($moduleName);
    $linktype = 'LISTVIEWRECORDACTION';
    $getLinkId = $adb->pquery('select * from jo_links where tabid = ? and linktype = ?', array($tabId, $linktype));
    while($fetchValues =$adb->fetch_array($getLinkId)) {
	array_push($list_view_row_action, $fetchValues);
    }
    return $list_view_row_action;
}

/**
 * Function to get the permitted list view entry modules
 */
function getPermittedEntityModuleNames(){
    global $adb;
    $permitted_modules = getPermittedModuleNames();
    $zero_entity_module_array = [];
    $getZeroEntityModuleId = $adb->pquery('select name from jo_tab where isentitytype = ? and name != ?', array(0, "Dashboard"));
    while($fetchValues =$adb->fetch_array($getZeroEntityModuleId)) {
	array_push($zero_entity_module_array, $fetchValues['name']);
    }
    $permitted_entity_module_array = [];
    foreach($permitted_modules as $key => $module) {
	if(in_array($module, $zero_entity_module_array)) {
	    unset($permitted_modules[$key]);
	}
    }
    array_push($permitted_modules, 'Home');
    return array_values($permitted_modules);
}	

/**
 * function to get the array of amin menu list
 */
function getMainMenuList($user_id){
    $main_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'main_menu');
	foreach($main_menu_array as $sequence => $obj) {
	    $main_menu_array[$sequence] = (array)$obj;
	}
    return (array)$main_menu_array;
}

/**
 * function to get the array
 */
function getSectionList($user_id){
    $section_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'default_sections');
    return (array)$section_array;
}

/**
 * function to get sections and modules
 */
function getAppModuleList($user_id) {
    $app_menu_array = Settings_MenuManager_Module_Model::getUserMenuDetails($user_id, 'module_apps');
    return (array)$app_menu_array;
}

/**
 * Function to get all active users
 */
function getAllActiveUserIds() {
    global $adb;
    $user_array = [];
    $query_result = $adb->pquery("SELECT id FROM jo_users WHERE status = ?", array('active'));
    while($fetch_values = $adb->fetch_array($query_result)) {
	array_push($user_array, $fetch_values['id']);
    }
    return $user_array;
}

/**
 * Function to return the icons for the fields of the business modules
 */
function returnFieldIconsArray() {
    $icons_array = array(
		'firstname' => 'fa fa-male',
		'lastname' => '',
		'account_id' => 'fa fa-building',
		'title' => 'fa fa-info-circle',
		'assigned_user_id' => 'fa fa-user-secret',
		'mailingcity' => 'fa fa-home',
		'mailingcountry' => 'fa fa-map-marker',
		'accountname' => 'fa fa-building',
		'bill_city' => 'fa fa-home',
		'bill_country' => 'fa fa-map-marker',
		'company' => 'fa fa-building',
		'leadsource' => 'fa fa-credit-card',
		'website' => 'fa fa-globe',
		'city' => 'fa fa-home',
		'country' => 'fa fa-map-marker'
    );
    return $icons_array;
}
/**
 * function to update the default dashboard view
 **/
function updateDefaultDashboardView($current_user_id, $record) {
    global $adb;
    $adb->pquery("UPDATE jo_users SET default_dashboard_view =? WHERE id = ?", array($record, $current_user_id));
   return $record;
}

/**
 *fucntion to get the default dashboard id
 **/
function getDefaultBoardId() {
    global $current_user, $adb;
    $user_id = $current_user->id;
    $exe_query = $adb->pquery("SELECT default_dashboard_view FROM jo_users WHERE id = ?", array($user_id));
    $fetch_value = $adb->fetch_array($exe_query);
    return $fetch_value['default_dashboard_view'];
}

/**
 * Function to return field names of the business icons
 */

function returnBusinessFieldArray() {
    $business_field_array = array('firstname' ,'lastname' ,'account_id' ,'title' ,'assigned_user_id', 'mailingcity', 'mailingcountry', 'accountname', 'bill_city' ,'bill_country' ,'company', 'leadsource' , 'website', 'city' ,'country');
    return $business_field_array;
}

/**
 * Clear user notification entry
 */
function clearUserNotification($user_id, $module_name) {
    global $adb;
    $adb->pquery('delete from jo_notification where module_name = ? and notifier_id = ?', array($module_name, $user_id));
    return true;
}

/**
 * Function to get the user permitted notifiable entity modules
 **/
function userPermittedNotificationEntityModules() {
    $user_permitted_modules = getPermittedModuleNames();
    $entity_modules = getPermittedEntityModuleNames();
    $user_permitted_entity_modules = [];

    foreach($user_permitted_modules as $module_name) {
        if(in_array($module_name, $entity_modules)){
	    array_push($user_permitted_entity_modules, $module_name);
        }
    }

    $non_entity_modules = ['Dashboard' ,'Emails' ,'Webmails' ,'ModComments' ,'Home'];
    foreach($user_permitted_entity_modules as $key => $module) {
       	if(in_array($module, $non_entity_modules)){
       	    unset($user_permitted_entity_modules[$key]);
        }
    }
    return $user_permitted_entity_modules;
}

/**
 * Function to get users' list who starred the particular record
 **/
function getUsersListForStarredRecords($recordId) {
    global $adb;
    $getStarred = $adb->pquery("SELECT * FROM jo_crmentity_user_field where recordid = ? and starred = ?", array($recordId, 1));
    $starred_array = [];
    while($fetchValues = $adb->fetch_array($getStarred)) {
        array_push($starred_array, $fetchValues['userid']);
    }
    return $starred_array;
}

/**
 * Function to check the notification settings of a module for a user
 **/
function getNotificationSettingsForUser($user_id, $moduleName, $action) {
    // if(file_exists("user_privileges/notifications/notification_".$user_id.".php"))
    //     $file_name = "user_privileges/notifications/notification_".$user_id.".php";
    // else
    //     $file_name = 'user_privileges/notifications/default_settings.php';

    // require($file_name);
    $db = PearDatabase::getInstance();
    $query = "select id,global,notificationlist from jo_notification_manager where id = ?";
    $result = $db->pquery($query, array($related_user_id));
    $rows = $db->num_rows($result);
    if($rows <= 0){
        $query = "select id,global,notificationlist from jo_notification_manager where id = ?";
        $result = $db->pquery($query, array(0));
        $rows = $db->num_rows($result);
    }
    for ($i=0; $i<$rows; $i++) {
        $row = $db->query_result_rowdata($result, $i);
        $global_settings = $row['global'];
        $notification_settings = unserialize(base64_decode($row['notificationlist']));
    }

    if($global_settings == 1) {
        if(isset($notification_settings[$moduleName][$action]))
            return true;
        else
            return false;
        } 
    else {
        return false;
    }
}

/**
 * Function to get the unseen notification-count of a user
 **/
function getUnseenNotificationCount($user_id) {
    global $adb;
    $getNotifications = $adb->pquery("SELECT * FROM jo_notification where notifier_id = ? and is_seen = ?", array($user_id, 0));
    $fetchValues = $adb->fetch_array($getNotifications);
    $noti_count = $adb->num_rows($getNotifications);
    return $noti_count;
}

/**
 * Function to get the notifications of a user for a specific module (for ajax call)
 **/
function getUserModuleNotifications($module_name, $user_id, $no_limit = false) {
    global $adb;
    if(isset($no_limit) && !empty($no_limit)) {
	$getNotifications = $adb->pquery("SELECT * FROM jo_notification where notifier_id = ? order by updated_at desc", array($user_id));
    } else {
        $getNotifications = $adb->pquery("SELECT * FROM jo_notification where module_name = ? and notifier_id = ? and is_seen = ? order by updated_at desc limit 5", array($module_name, $user_id, 0));
    }
    $notification_array = [];
    while($fetchValues = $adb->fetch_array($getNotifications)) {
        array_push($notification_array, $fetchValues);
    }
    return $notification_array;
}

/**
 * Function to clear viewed notifications
 */
function deleteViewedNotifications($notification_ids) {
    global $adb;
    if(!empty($notification_ids)) {
	foreach($notification_ids as $id) {
	    $adb->pquery('delete from jo_notification where id = ?', array($id));
	}
    }
    return true;
}

/**
 * Function to get user profile action permission for adding masquerade user
 **/
function getMasqueradeUserActionPermission() {
    global $current_user;
    $user_id = $current_user->id;
    $profile_array = getUserProfile($user_id);
    foreach($profile_array as $profile) {
	$profile_instance = Settings_Profiles_Record_Model::getInstanceById($profile);
	$action_permission = $profile_instance->hasModuleActionPermission('Contacts', 14);
	if($action_permission) {
	    return true;
	}
    }
    return false;
}

function getNotPermittedRelatedRecordPermission($module_name) {
    global $current_user;
    $user_id = $current_user->id;
    $profile_array = getUserProfile($user_id);
    foreach($profile_array as $profile) {
        $profile_instance = Settings_Profiles_Record_Model::getInstanceById($profile);
        $action_permission = $profile_instance->hasModuleActionPermission($module_name, 15);
        if($action_permission) {
            return true;
        }
    }
    return false;
}

function getMasqueradeUserRecordDetails() {
    global $current_user,$adb;
    $user_id = $current_user->id;
    $query = $adb->pquery('select jo_masqueradeuserdetails.record_id as record_id, jo_masqueradeuserdetails.masquerade_module as masquerade_module from jo_masqueradeuserdetails where portal_id = ?', array($user_id));
    $num_of_rows = $adb->num_rows($query);
    if($num_of_rows > 0) {
        $result = $adb->fetch_array($query);
        return $result;
    } else {
        return array();
    }
}

function getRelatedModules($module) {
        global $adb;
        $tabvalue = getTabid($module);
        $query = "SELECT jo_tab.tabid, jo_tab.name,jo_relatedlists.name as functionname,jo_relatedlists.relation_id FROM jo_relatedlists
            INNER JOIN jo_tab ON jo_tab.tabid = jo_relatedlists.related_tabid
            WHERE jo_relatedlists.tabid = ?";
        $result = $adb->pquery($query, array($tabvalue));
        $Related_Modules = array();
        while ($rowvalue = $adb->fetchByAssoc($result)) {
            $Related_Modules[$rowvalue['name']] = $rowvalue;
        }
        return $Related_Modules;
}

/**
 * Function to get global masquerade user permission
 **/
function getGlobalMasqueradeUserPermission() {
    require_once('user_privileges/portal_user_settings.php');
    return $enable_masquerade_user;
}

/**
 * Check if the record is deleted or not
 **/
function isEntityDeleted($crmid) {
    global $adb;
    $query_result = $adb->pquery('SELECT deleted from jo_crmentity where crmid=?', array($crmid));
    $delete_status = $adb->query_result($query_result, 0, 'deleted');
    return $delete_status;
}

/**
 * Function to return the CRM routing list
 **/
function getRoutesArray() {
    $routes = [
		    ['method' => 'GET', 'pattern' => '{module}/{parent:Settings}/source/{sourceModule}'],
		    ['method' => 'GET', 'pattern' => '{module}/{parent:Settings}/{view}/source/{sourceModule}'],
		    ['method' => 'GET', 'pattern' => '{module}/{parent:Settings}/{view}/{record:\d+}/parent/{parentblock}'],
		    ['method' => 'GET', 'pattern' => '{module}/{parent:Settings}/{view}/{record}'],
		    ['method' => 'GET', 'pattern' => '{module}/{parent:Settings}/{view}'],
		    ['method' => 'GET', 'pattern' => '{module}/{parent:Settings}/{view}/{block:\d+}/{fieldid:\d+}[/{error}]'],
		    ['method' => 'POST','pattern' => '{module}/{parent:Settings}/{view}/{block:\d+}/{fieldid:\d+}[/{error}]'],
		    ['method' => 'GET', 'pattern' => '{module}/view/{view}/{record:\d+}/Duplicate/{isDuplicate:true}'],
		    ['method' => 'GET', 'pattern' => '{module}/view/{view}[/{record:\d+}]'],
		    ['method' => 'GET', 'pattern' => '{module}/view/{view}/filter/{id:\d+}'],
		    ['method' => 'GET', 'pattern' => '{module}/view/{view}/{record:\d+}/mode/{mode}'],
		    ['method' => 'POST','pattern' => '{module}/view/{view}/{record:\d+}/mode/{mode}'],
		    ['method' => 'GET', 'pattern' => '{module}/view/{view}/mode/{mode}'],
		    ['method' => 'GET', 'pattern' => '{module}/action/{action}'],
		    ['method' => 'GET', 'pattern' => '{module}/action/{action}/{record:\d+}'],
		    ['method' => 'GET', 'pattern' => '{module:Contacts}/{parent:Settings}/{view:Extension}/{extensionModule:Google}/{extensionView:Index}/{mode:settings}/{block:\d+}/{fieldid:\d+}']
    ];
    return $routes;
}

/**
 * Function to get Role based picklist values with picklistid and picklistvalueid
 **/
function getRolesBasedPicklistValues($picklist_name , $roleid) {
    global $adb;
    $query = $adb->pquery("select * from jo_". $picklist_name ." inner join jo_role2picklist on jo_role2picklist.picklistvalueid = jo_". $picklist_name .".picklist_valueid where roleid=? and picklistid in (select picklistid from jo_". $picklist_name ." ) order by sortid", array($roleid));
    $currenct_roles_picklist_values = array();
    while($pick_values = $adb->fetch_array($query)) {
	$currenct_roles_picklist_values[$pick_values['picklistvalueid']] = $pick_values;
    }
    return $currenct_roles_picklist_values;
}

/**
 * Function to get the picklist value id by picklistname, roleid and picklist value
 **/
function getPicklistValueId($picklist_name , $roleid, $picklist_value) {
    global $adb;
    $query = $adb->pquery("select * from jo_". $picklist_name ." inner join jo_role2picklist on jo_role2picklist.picklistvalueid = jo_". $picklist_name .".picklist_valueid where roleid=? and picklistid in (select picklistid from jo_". $picklist_name ." ) and $picklist_name = ? order by sortid", array($roleid, $picklist_value));
    $pick_values = $adb->fetch_array($query);
    return $pick_values['picklistvalueid'];
}

function convertUrlToArray($url){
    global $site_URL;
    $url = str_replace($site_URL, "", $url);
    $explodes = explode("?", $url);

    $canonical = explode("/", $explodes[0]);
    $request_vars = $explodes[1];
    $return = array();

    if(!empty($request_vars)) {
        $urlExploded = explode("&", $request_vars);
        foreach ($urlExploded as $param){
            $explodedPar = explode("=", $param);
            $return[$explodedPar[0]] = $explodedPar[1];
        }
    }

    if(!empty($return)) {
        $canonical = array_merge($canonical, $return);
    }
    return $canonical;
}

/**
 * Function to return the First & lastname of user 
 **/

function getUserFirstAndLastName($user_id) {
    global $adb;
    $user_query = $adb->pquery('select id,first_name,last_name from jo_users where id=?', array($user_id));
    $user_values = $adb->fetch_array($user_query);
    $name = $user_values['first_name'] .' '.$user_values['last_name'];
    return $name;
}

function URLCheck($url) {
    global $site_URL;
    if(strpos($url, $site_URL) !== false) {
        return $url;
    } else {
        return $site_URL.$url;
    }
}

function getCurrentUserFieldDetailsFromTable($columnname) {
    global $current_user, $adb;
    $userid = $current_user->id;
    $user_query = $adb->pquery('select * from jo_users where id=?', array($userid));
    $user_values = $adb->fetch_array($user_query);
    $field_value = $user_values[$columnname];
    return $field_value;
}

/**
 * Function to get one email field of a module
 **/
function getModuleEmailField($source_modules){
	global $adb;
	$tabid = getTabid($source_modules);
        //no email field accessible in the module. since its only association pick up the field any way.
        $query="SELECT fieldid,fieldlabel,columnname,fieldname FROM jo_field WHERE tabid=? and uitype=13 and presence in (0,2)";
        $result = $adb->pquery($query, array($tabid));

	//pick up the first field.
	$fieldname = '';
	if($adb->num_rows($result) > 0) {
		$fieldname = $adb->query_result($result,0,'fieldname');
	}
        return $fieldname;
}

/**
 * Function to get one phone field of a module
 **/
function getModulePhoneField($source_modules){
        global $adb;
        $tabid = getTabid($source_modules);
        //no email field accessible in the module. since its only association pick up the field any way.
        $query="SELECT fieldid,fieldlabel,columnname,fieldname FROM jo_field WHERE tabid=? and uitype=11 and presence in (0,2)";
        $result = $adb->pquery($query, array($tabid));

        //pick up the first field.
        $fieldname = '';
        if($adb->num_rows($result) > 0) {
                $fieldname = $adb->query_result($result,0,'fieldname');
        }
        return $fieldname;
}

function getRelatedRecordDetails($recordId, $module, $relationModuleName){
	$parentRecordModel = Head_Record_Model::getInstanceById($recordId);
	$relation_model = Head_RelationListView_Model::getInstance($parentRecordModel, $relationModuleName, $label=false);
	$pagingModel = new Head_Paging_Model();
	$pagingModel->set('page',1);
	$pagingModel->set('_relatedlistcount', 100);
	$entries = $relation_model->getEntries($pagingModel);
	return $entries;
}

function getRelatedRecordSumValue($recordId, $module, $relatedModuleName, $req_field = false) {
	global $current_user;
	$currency = $current_user->currency_symbol;
	$entries = getRelatedRecordDetails($recordId, $module, $relatedModuleName);
	if(empty($req_field)) {return count($entries);}
	$sum = 0;
	foreach($entries as $crmid => $record_obj) {
		$sum = $sum + $record_obj->get($req_field);
	}
	return $currency.$sum;
}

function getEntityModuleWSId($moduleName) {
	$moduleWSIdCache = array();
    if (!isset($moduleWSIdCache[$moduleName])) {
        global $adb;
        $result = $adb->pquery("SELECT id FROM jo_ws_entity WHERE name=?", array($moduleName));
        if ($result && $adb->num_rows($result)) {
            $moduleWSIdCache[$moduleName] = $adb->query_result($result, 0, 'id');
        }
    }
    return $moduleWSIdCache[$moduleName];
}

function gatherModuleFieldGroupInfo($module) {
    global $adb;
    $gatherModuleFieldGroupInfoCache = array();
    if($module == 'Events') $module = 'Calendar';
    
    // Cache hit?
    if(isset($gatherModuleFieldGroupInfoCache[$module])) {
        return $gatherModuleFieldGroupInfoCache[$module];
    }
    
    $result = $adb->pquery(
        "SELECT fieldname, fieldlabel, blocklabel, uitype FROM jo_field INNER JOIN
        jo_blocks ON jo_blocks.tabid=jo_field.tabid AND jo_blocks.blockid=jo_field.block 
        WHERE jo_field.tabid=? AND jo_field.presence != 1 ORDER BY jo_blocks.sequence, jo_field.sequence", array(getTabid($module))
    );

    $fieldgroups = array();
    while($resultrow = $adb->fetch_array($result)) {
        $blocklabel = getTranslatedString($resultrow['blocklabel'], $module);
        if(!isset($fieldgroups[$blocklabel])) {
            $fieldgroups[$blocklabel] = array();
        }
        $fieldgroups[$blocklabel][$resultrow['fieldname']] = 
            array(
                'label' => getTranslatedString($resultrow['fieldlabel'], $module),
                'uitype'=> fixUIType($module, $resultrow['fieldname'], $resultrow['uitype'])
            );
    }
    
    // Cache information
    $gatherModuleFieldGroupInfoCache[$module] = $fieldgroups;
    
    return $fieldgroups;
}

function detectFieldnamesToResolve($module) {
    global $adb;
    $detectFieldnamesToResolveCache = array();
    // Cache hit?
    if(isset($detectFieldnamesToResolveCache[$module])) {
        return $detectFieldnamesToResolveCache[$module];
    }
    
    $resolveUITypes = array(10, 101, 116, 117, 26, 357, 50, 51, 52, 53, 57, 58, 59, 66, 68, 73, 75, 76, 77, 78, 80, 81);
    
    $result = $adb->pquery(
        "SELECT DISTINCT fieldname FROM jo_field WHERE uitype IN(". 
        generateQuestionMarks($resolveUITypes) .") AND tabid=?", array($resolveUITypes, getTabid($module)) 
    );
    $fieldnames = array();
    while($resultrow = $adb->fetch_array($result)) {
        $fieldnames[] = $resultrow['fieldname'];
    }
    
    // Cache information		
    $detectFieldnamesToResolveCache[$module] = $fieldnames;
    
    return $fieldnames;
}

function detectModulenameFromRecordId($wsrecordid) {
    global $adb;
    $idComponents = vtws_getIdComponents($wsrecordid);
    $result = $adb->pquery("SELECT name FROM jo_ws_entity WHERE id=?", array($idComponents[0]));
    if($result && $adb->num_rows($result)) {
        return $adb->query_result($result, 0, 'name');
    }
    return false;
}

function fixUIType($module, $fieldname, $uitype) {
    if ($module == 'Contacts' || $module == 'Leads') {
        if ($fieldname == 'salutationtype') {
            return 16;
        }
    }
    else if ($module == 'Calendar' || $module == 'Events') {
        if ($fieldname == 'time_start' || $fieldname == 'time_end') {
            // Special type for mandatory time type (not defined in product)
            return 252;
        }
    }
    return $uitype;
}