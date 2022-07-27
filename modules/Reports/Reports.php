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
require_once('includes/database/PearDatabase.php');
require_once('includes/data/CRMEntity.php');
require_once('includes/utils/UserInfoUtil.php');
require_once 'modules/Reports/ReportUtils.php';
global $calpath;
global $app_strings,$mod_strings;
global $app_list_strings;
global $modules;
global $blocks;
global $adv_filter_options;
global $log;

global $report_modules;
global $related_modules;
global $old_related_modules;

$adv_filter_options = array("e"=>"equals",
		            "n"=>"not equal to",
			    "s"=>"starts with",
			    "ew"=>"ends with",
			    "c"=>"contains",
			    "k"=>"does not contain",
			    "l"=>"less than",
			    "g"=>"greater than",
			    "m"=>"less or equal",
			    "h"=>"greater or equal",
			    "bw"=>"between",
			    "a"=>"after",
			    "b"=>"before",
				"y"=>"is empty",
			   );

//$report_modules = Array('Faq','Rss','Portal','Recyclebin','Emails','Reports','Dashboard','Home','Activities'
	//	       );

$old_related_modules = Array('Accounts'=>Array('Potentials','Contacts','Products','Quotes','Invoice'),
			 'Contacts'=>Array('Accounts','Potentials','Quotes','PurchaseOrder'),
			 'Potentials'=>Array('Accounts','Contacts','Quotes'),
			 'Calendar'=>Array('Leads','Accounts','Contacts','Potentials'),
			 'Products'=>Array('Accounts','Contacts'),
			 'HelpDesk'=>Array('Products'),
			 'Quotes'=>Array('Accounts','Contacts','Potentials'),
			 'PurchaseOrder'=>Array('Contacts'),
			 'Invoice'=>Array('Accounts'),
			 'Campaigns'=>Array('Products'),
			);

$related_modules =Array();

class Reports extends CRMEntity{



	/**
	 * This class has the informations for Reports and inherits class CRMEntity and
	 * has the variables required to generate,save,restore jo_reports
	 * and also the required functions for the same
	 * Contributor(s): ______________________________________..
	 */


	var $srptfldridjs;

	var $column_fields = Array();

	var $sort_fields = Array();
	var $sort_values = Array();

	var $id;
	var $mode;
	var $mcount;

	var $startdate;
	var $enddate;

	var $ascdescorder;

	var $stdselectedfilter;
	var $stdselectedcolumn;

	var $primodule;
	var $secmodule;
	var $columnssummary;
	var $is_editable;
	var $reporttype;
	var $reportname;
	var $reportdescription;
	var $folderid;
	var $module_blocks;

	var $pri_module_columnslist;
	var $sec_module_columnslist;

	var $advft_criteria;
	var $adv_rel_fields = Array();

	var $module_list = Array();

	/** Function to set primodule,secmodule,reporttype,reportname,reportdescription,folderid for given jo_reportid
	 *  This function accepts the jo_reportid as argument
	 *  It sets primodule,secmodule,reporttype,reportname,reportdescription,folderid for the given jo_reportid
	 */

	function Reports($reportid="")
	{
		global $adb,$current_user,$theme,$mod_strings;
		$this->initListOfModules();
		if($reportid != "")
		{
			// Lookup information in cache first
			$cachedInfo = CacheUtils::lookupReport_Info($current_user->id, $reportid);
			$subordinate_users = CacheUtils::lookupReport_SubordinateUsers($reportid);
			
			$reportModel = Reports_Record_Model::getCleanInstance($reportid);
			$sharingType = $reportModel->get('sharingtype');
			
			if($cachedInfo === false) {
				$ssql = "select jo_reportmodules.*,jo_report.* from jo_report inner join jo_reportmodules on jo_report.reportid = jo_reportmodules.reportmodulesid";
				$ssql .= " where jo_report.reportid = ?";
				$params = array($reportid);

				require_once('includes/utils/GetUserGroups.php');
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
				$userGroups = new GetUserGroups();
				$userGroups->getAllUserGroups($current_user->id);
				$user_groups = $userGroups->user_groups;
				if(!empty($user_groups) && $sharingType == 'Private'){
					$user_group_query = " (shareid IN (".generateQuestionMarks($user_groups).") AND setype='groups') OR";
					array_push($params, $user_groups);
				}

				$non_admin_query = " jo_report.reportid IN (SELECT reportid from jo_reportsharing WHERE $user_group_query (shareid=? AND setype='users'))";
				if($sharingType == 'Private'){
					$ssql .= " and (( (".$non_admin_query.") or jo_report.sharingtype='Public' or jo_report.owner = ? or jo_report.owner in(select jo_user2role.userid from jo_user2role inner join jo_users on jo_users.id=jo_user2role.userid inner join jo_role on jo_role.roleid=jo_user2role.roleid where jo_role.parentrole like '".$current_user_parent_role_seq."::%'))";
					array_push($params, $current_user->id);
					array_push($params, $current_user->id);
				}

				$query = $adb->pquery("select userid from jo_user2role inner join jo_users on jo_users.id=jo_user2role.userid inner join jo_role on jo_role.roleid=jo_user2role.roleid where jo_role.parentrole like '".$current_user_parent_role_seq."::%'",array());
				$subordinate_users = Array();
				for($i=0;$i<$adb->num_rows($query);$i++){
					$subordinate_users[] = $adb->query_result($query,$i,'userid');
				}

				// Update subordinate user information for re-use
				CacheUtils::updateReport_SubordinateUsers($reportid, $subordinate_users);
				
				//Report sharing
				$queryObj = new stdClass();
				$queryObj->query = $ssql;
				$queryObj->queryParams = $params;
				$queryObj = self::getReportSharingQuery($queryObj, $sharingType);
				
				$result = $adb->pquery($queryObj->query, $queryObj->queryParams);
				if($result && $adb->num_rows($result)) {
					$reportmodulesrow = $adb->fetch_array($result);

					// Update information in cache now
					CacheUtils::updateReport_Info(
						$current_user->id, $reportid, $reportmodulesrow["primarymodule"],
						$reportmodulesrow["secondarymodules"], $reportmodulesrow["reporttype"],
						$reportmodulesrow["reportname"], $reportmodulesrow["description"],
						$reportmodulesrow["folderid"], $reportmodulesrow["owner"]
					);
				}

				// Re-look at cache to maintain code-consistency below
				$cachedInfo = CacheUtils::lookupReport_Info($current_user->id, $reportid);
			}

			if($cachedInfo) {
				$this->primodule = $cachedInfo["primarymodule"];
				$this->secmodule = $cachedInfo["secondarymodules"];
				$this->reporttype = $cachedInfo["reporttype"];
				$this->reportname = decode_html($cachedInfo["reportname"]);
				$this->reportdescription = decode_html($cachedInfo["description"]);
				$this->folderid = $cachedInfo["folderid"];
				if($is_admin==true || in_array($cachedInfo["owner"],$subordinate_users) || $cachedInfo["owner"]==$current_user->id)
					$this->is_editable = 'true';
				else
					$this->is_editable = 'false';
			} 
			}
	}

	// Update the module list for listing columns for report creation.
	function updateModuleList($module) {
		global $adb;
		if (!isset($module)) return;
		require_once('includes/utils/utils.php');
		$tabid = getTabid($module);
		if ($module == 'Calendar') {
			$tabid = array(9, 16);
		}
		$sql = "SELECT blockid, blocklabel FROM jo_blocks WHERE tabid IN (". generateQuestionMarks($tabid) .")";
		$res = $adb->pquery($sql, array($tabid));
		$noOfRows = $adb->num_rows($res);
		if ($noOfRows <= 0) return;
		for($index = 0; $index < $noOfRows; ++$index) {
			$blockid = $adb->query_result($res,$index,'blockid');
			if(in_array($blockid, $this->module_list[$module])) continue;
			$blockid_list[] = $blockid;
			$blocklabel = $adb->query_result($res,$index,'blocklabel');
			$this->module_list[$module][$blocklabel] = $blockid;
		}
	}

	// Initializes the module list for listing columns for report creation.
	function initListOfModules() {
		global $adb, $current_user, $old_related_modules;

		$restricted_modules = array('Events','Webmails');
		$restricted_blocks = array('LBL_COMMENTS','LBL_COMMENT_INFORMATION');

		$this->module_id = array();
		$this->module_list = array();

		// Prefetch module info to check active or not and also get list of tabs
		$modulerows = modlib_prefetchModuleActiveInfo(false);

		$cachedInfo = CacheUtils::lookupReport_ListofModuleInfos();

		if($cachedInfo !== false) {
			$this->module_list = $cachedInfo['module_list'];
			$this->related_modules = $cachedInfo['related_modules'];

		} else {

			if($modulerows) {
				foreach($modulerows as $resultrow) {
					if($resultrow['presence'] == '1') continue;      // skip disabled modules
					if($resultrow['isentitytype'] != '1') continue;  // skip extension modules
					if(in_array($resultrow['name'], $restricted_modules)) { // skip restricted modules
						continue;
					}
					if($resultrow['name']!='Calendar'){
						$this->module_id[$resultrow['tabid']] = $resultrow['name'];
					} else {
						$this->module_id[9] = $resultrow['name'];
						$this->module_id[16] = $resultrow['name'];

					}
					$this->module_list[$resultrow['name']] = array();
				}

				$moduleids = array_keys($this->module_id);
				$reportblocks =
					$adb->pquery("SELECT blockid, blocklabel, tabid FROM jo_blocks WHERE tabid IN (" .generateQuestionMarks($moduleids) .")",
						array($moduleids));
				$prev_block_label = '';
				if($adb->num_rows($reportblocks)) {
					while($resultrow = $adb->fetch_array($reportblocks)) {
						$blockid = $resultrow['blockid'];
						$blocklabel = $resultrow['blocklabel'];
						$module = $this->module_id[$resultrow['tabid']];

						if(in_array($blocklabel, $restricted_blocks) ||
							in_array($blockid, $this->module_list[$module]) ||
							isset($this->module_list[$module][getTranslatedString($blocklabel,$module)])
						) {
							continue;
						}

						if(!empty($blocklabel)){
							if($module == 'Calendar' && $blocklabel == 'LBL_CUSTOM_INFORMATION')
								$this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
							else
								$this->module_list[$module][$blockid] = getTranslatedString($blocklabel,$module);
							$prev_block_label = $blocklabel;
						} else {
							$this->module_list[$module][$blockid] = getTranslatedString($prev_block_label,$module);
						}
					}
				}

				$relatedmodules = $adb->pquery(
					"SELECT jo_tab.name, jo_relatedlists.tabid FROM jo_tab
					INNER JOIN jo_relatedlists on jo_tab.tabid=jo_relatedlists.related_tabid
					WHERE jo_tab.isentitytype=1
					AND jo_tab.name NOT IN(".generateQuestionMarks($restricted_modules).")
					AND jo_tab.presence = 0 AND jo_relatedlists.label!='Activity History'
					UNION
					SELECT module, jo_tab.tabid FROM jo_fieldmodulerel
					INNER JOIN jo_tab on jo_tab.name = jo_fieldmodulerel.relmodule
					INNER JOIN jo_tab AS jo_tabrel ON jo_tabrel.name = jo_fieldmodulerel.module AND jo_tabrel.presence = 0
                    INNER JOIN jo_field ON jo_field.fieldid = jo_fieldmodulerel.fieldid
					WHERE jo_tab.isentitytype = 1
					AND jo_tab.name NOT IN(".generateQuestionMarks($restricted_modules).")
					AND jo_tab.presence = 0
                    AND jo_field.fieldname NOT LIKE ?",
					array($restricted_modules,$restricted_modules, 'cf_%')
				);
				if($adb->num_rows($relatedmodules)) {
					while($resultrow = $adb->fetch_array($relatedmodules)) {
						$module = $this->module_id[$resultrow['tabid']];

						if(!isset($this->related_modules[$module])) {
							$this->related_modules[$module] = array();
						}

						if($module != $resultrow['name']) {
							$this->related_modules[$module][] = $resultrow['name'];
						}

						// To achieve Backward Compatability with Report relations
						if(isset($old_related_modules[$module])){

							$rel_mod = array();
							foreach($old_related_modules[$module] as $key=>$name){
								if(modlib_isModuleActive($name) && isPermitted($name,'index','')){
									$rel_mod[] = $name;
								}
							}
							if(!empty($rel_mod)){
								$this->related_modules[$module] = array_merge($this->related_modules[$module],$rel_mod);
								$this->related_modules[$module] = array_unique($this->related_modules[$module]);
							}
						}
					}
				}
				foreach($this->related_modules as $module=>$related_modules) {
					if($module == 'Emails') {
						$this->related_modules[$module] = getEmailRelatedModules();
					}
				}
				// Put the information in cache for re-use
				CacheUtils::updateReport_ListofModuleInfos($this->module_list, $this->related_modules);
			}
		}
	}
	// END


	/** Function to get the Listview of Reports
	 *  This function accepts no argument
	 *  This generate the Reports view page and returns a string
	 *  contains HTML
	 */

	function sgetRptFldr($mode='')
	{

		global $adb,$log,$mod_strings;
		$returndata = Array();
		$sql = "select * from jo_reportfolder order by folderid";
		$result = $adb->pquery($sql, array());
		$reportfldrow = $adb->fetch_array($result);
		if($mode != '')
		{
			// Fetch detials of all reports of folder at once
			$reportsInAllFolders = $this->sgetRptsforFldr(false);

			do
			{
				if($reportfldrow["state"] == $mode)
				{
					$details = Array();
					$details['state'] = $reportfldrow["state"];
					$details['id'] = $reportfldrow["folderid"];
					$details['name'] = ($mod_strings[$reportfldrow["foldername"]] == '' ) ? $reportfldrow["foldername"]:$mod_strings[$reportfldrow["foldername"]];
					$details['description'] = $reportfldrow["description"];
					$details['fname'] = popup_decode_html($details['name']);
					$details['fdescription'] = popup_decode_html($reportfldrow["description"]);
					$details['details'] = $reportsInAllFolders[$reportfldrow["folderid"]];
					$returndata[] = $details;
				}
			}while($reportfldrow = $adb->fetch_array($result));
		}else
		{
			do
			{
				$details = Array();
				$details['state'] = $reportfldrow["state"];
				$details['id'] = $reportfldrow["folderid"];
				$details['name'] = ($mod_strings[$reportfldrow["foldername"]] == '' ) ? $reportfldrow["foldername"]:$mod_strings[$reportfldrow["foldername"]];
				$details['description'] = $reportfldrow["description"];
				$details['fname'] = popup_decode_html($details['name']);
				$details['fdescription'] = popup_decode_html($reportfldrow["description"]);
				$returndata[] = $details;
			}while($reportfldrow = $adb->fetch_array($result));
		}

		$log->info("Reports :: ListView->Successfully returned jo_report folder HTML");
		return $returndata;
	}
	
	/**
	 * Function returns the query object after joining necessary shared tables (users,groups,roles,rs) 
	 * for a non admin user
	 * @param type $queryObj
	 * @return type
	 */
	static function getReportSharingQuery($queryObj,$rpt_fldr_id = false){
		$currentUser = Users_Record_Model::getCurrentUserModel();
		//Report Sharing 
		$userPrivilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$sql = $queryObj->query;
		$params = $queryObj->queryParams;
		if($rpt_fldr_id == 'shared' || $rpt_fldr_id == "Private" || $rpt_fldr_id == 'All') {
			$userId = $currentUser->getId();
            $userGroups = new GetUserGroups();
            $userGroups->getAllUserGroups($userId);
            $groups = $userGroups->user_groups;
            $userRole = fetchUserRole($userId);
            $parentRoles=getParentRole($userRole);
            $parentRolelist= array();
            foreach($parentRoles as $par_rol_id)
            {
                array_push($parentRolelist, $par_rol_id);		
            }
            array_push($parentRolelist, $userRole);
			$userParentRoleSeq = $userPrivilegeModel->get('parent_role_seq');
			$sql .= " OR ( jo_report.sharingtype='Public' OR $userId IN (
								SELECT jo_user2role.userid FROM jo_user2role
									INNER JOIN jo_users ON jo_users.id = jo_user2role.userid
									INNER JOIN jo_role ON jo_role.roleid = jo_user2role.roleid
								WHERE jo_role.parentrole LIKE '".$userParentRoleSeq."::%') 
                            OR jo_report.reportid IN (SELECT jo_report_shareusers.reportid FROM jo_report_shareusers WHERE jo_report_shareusers.userid=?)";
            $params[] = $userId;
            if(!empty($groups)){
                $sql .= " OR jo_report.reportid IN (SELECT jo_report_sharegroups.reportid FROM jo_report_sharegroups WHERE jo_report_sharegroups.groupid IN (".  generateQuestionMarks($groups)."))";
                $params = array_merge($params,$groups);
            }
                            
            $sql.= " OR jo_report.reportid IN (SELECT jo_report_sharerole.reportid FROM jo_report_sharerole WHERE jo_report_sharerole.roleid =?)";
            $params[] = $userRole;
            if(!empty($parentRolelist)){
                $sql.= " OR jo_report.reportid IN (SELECT jo_report_sharers.reportid FROM jo_report_sharers WHERE jo_report_sharers.rsid IN (". generateQuestionMarks($parentRolelist) ."))";
				$params = array_merge($params,$parentRolelist);
            }
                            
            $sql.= ")) ";
		}
		
		$queryObj->query = $sql;
		$queryObj->queryParams = $params;
		
		return $queryObj;
	}
	

	/** Function to get the Reports inside each modules
	 *  This function accepts the folderid
	 *  This Generates the Reports under each Reports module
	 *  This Returns a HTML sring
	 */

	function sgetRptsforFldr($rpt_fldr_id, $paramsList=false)
	{
		$srptdetails="";
		global $adb;
		global $log;
		global $mod_strings,$current_user;
		$returndata = Array();
		
		require_once('includes/utils/UserInfoUtil.php');

		$userNameSql = getSqlForNameInDisplayFormat(array('first_name'=> 'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');
		$sql = "SELECT jo_report.*, jo_reportmodules.*, jo_reportfolder.folderid, jo_reportfolder.foldername,
			CASE WHEN (jo_users.user_name NOT LIKE '') THEN $userNameSql END AS ownername,
			jo_module_dashboard_widgets.reportid AS pinned FROM jo_report 
			LEFT JOIN jo_module_dashboard_widgets ON jo_module_dashboard_widgets.reportid = jo_report.reportid AND jo_module_dashboard_widgets.userid=$current_user->id 
			LEFT JOIN jo_users ON jo_report.owner = jo_users.id
			INNER JOIN jo_reportfolder ON jo_reportfolder.folderid = jo_report.folderid
			INNER JOIN jo_reportmodules ON jo_reportmodules.reportmodulesid = jo_report.reportid
			INNER JOIN jo_tab ON jo_tab.name = jo_reportmodules.primarymodule AND jo_tab.presence = 0";

		$params = array();

		// If information is required only for specific report folder?
		if($rpt_fldr_id !== false && $rpt_fldr_id !== 'shared' && $rpt_fldr_id !== 'All') {
			$sql .= " where jo_reportfolder.folderid=?";
			$params[] = $rpt_fldr_id;
		}
		
		if($rpt_fldr_id == 'shared') {
			$sql .= " where jo_report.sharingtype=? AND jo_report.owner != ?";
			$params[] = 'Private';
			$params[] = $current_user->id;
		}
		$searchCondition = getReportSearchCondition($paramsList['searchParams'], $rpt_fldr_id);
		if($searchCondition) {
			$sql .= $searchCondition;
		}

		if (strtolower($current_user->is_admin) != "on") {
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
			require_once('includes/utils/GetUserGroups.php');
			$userGroups = new GetUserGroups();
			$userGroups->getAllUserGroups($current_user->id);
			$user_groups = $userGroups->user_groups;
			if(!empty($user_groups) && ($rpt_fldr_id == 'shared' || $rpt_fldr_id == 'All')){
				$user_group_query = " (shareid IN (".generateQuestionMarks($user_groups).") AND setype='groups') OR";
				$non_admin_query = " jo_report.reportid IN (SELECT reportid FROM jo_reportsharing WHERE $user_group_query (shareid=? AND setype='users'))";
				foreach ($user_groups as $userGroup) {
					array_push($params, $userGroup);
				}
				array_push($params, $current_user->id);
			}

			if ($rpt_fldr_id == 'shared' || $rpt_fldr_id == 'All') {
				if ($non_admin_query) {
					$non_admin_query = "( $non_admin_query ) OR ";
				}
				$sql .= " AND ( ($non_admin_query jo_report.sharingtype='Public' OR "
						. "jo_report.owner = ? OR jo_report.owner IN (SELECT jo_user2role.userid "
						. "FROM jo_user2role INNER JOIN jo_users ON jo_users.id=jo_user2role.userid "
						. "INNER JOIN jo_role ON jo_role.roleid=jo_user2role.roleid "
						. "WHERE jo_role.parentrole LIKE '".$current_user_parent_role_seq."::%'))";
				array_push($params, $current_user->id);
			}

			$queryObj = new stdClass();
            $queryObj->query = $sql;
            $queryObj->queryParams = $params;
            //This function will append sharing access query for a current user
            $queryObj = self::getReportSharingQuery($queryObj,$rpt_fldr_id);
            $sql = $queryObj->query;
            $params = $queryObj->queryParams;
        }
		if ($paramsList) {
			$startIndex = $paramsList['startIndex'];
			$pageLimit = $paramsList['pageLimit'];
			$orderBy = $paramsList['orderBy'];
			$sortBy = $paramsList['sortBy'];
			if ($orderBy) {
				$sql .= " ORDER BY $orderBy $sortBy";
			}
			$sql .= " LIMIT $startIndex,".($pageLimit+1);
		}
		$query = $adb->pquery("SELECT userid FROM jo_user2role INNER JOIN jo_users "
				. "ON jo_users.id=jo_user2role.userid INNER JOIN jo_role "
				. "ON jo_role.roleid=jo_user2role.roleid WHERE jo_role.parentrole LIKE '".$current_user_parent_role_seq."::%'",array());
		$subordinate_users = Array();
		for($i=0;$i<$adb->num_rows($query);$i++){
			$subordinate_users[] = $adb->query_result($query,$i,'userid');
		}
		$result = $adb->pquery($sql, $params);
		$report = $adb->fetch_array($result);
		if(count($report)>0)
		{
			do
			{
				$report_details = Array();
				$report_details ['customizable'] = $report["customizable"];
				$report_details ['reportid'] = $report["reportid"];
				$report_details ['primarymodule'] = $report["primarymodule"];
				$report_details ['secondarymodules'] = $report["secondarymodules"];
				$report_details ['state'] = $report["state"];
				$report_details ['description'] = $report["description"];
				$report_details ['reportname'] = $report["reportname"];
                $report_details ['reporttype'] = $report["reporttype"];
				$report_details ['sharingtype'] = $report["sharingtype"];
				$report_details ['foldername'] = $report["foldername"];
				$report_details ['pinned'] = $report["pinned"]; // To check whether a record is pinned to dashboard or not
				$report_details ['owner'] = $report["ownername"];
				$report_details ['folderid'] = $report["folderid"];
				if($is_admin==true || in_array($report["owner"],$subordinate_users) || $report["owner"]==$current_user->id)
					$report_details ['editable'] = 'true';
				else
					$report_details['editable'] = 'false';

				if(isPermitted($report["primarymodule"],'index') == "yes") {
					if($rpt_fldr_id == false || $rpt_fldr_id == 'shared' || $rpt_fldr_id == 'All') {
						$returndata[] = $report_details;
					} else {
						$returndata[$report["folderid"]][] = $report_details;
					}
				}
			}while($report = $adb->fetch_array($result));
		}
		if($rpt_fldr_id !== false && $rpt_fldr_id !== 'shared' && $rpt_fldr_id !== 'All') {
			$returndata = $returndata[$rpt_fldr_id];
		}
		$log->info("Reports :: ListView->Successfully returned jo_report details HTML");
		return $returndata;
	}

	/** Function to get the array of ids
	 *  This function forms the array for the ExpandCollapse
	 *  Javascript
	 *  It returns the array of ids
	 *  Array('1RptFldr','2RptFldr',........,'9RptFldr','10RptFldr')
	 */

	function sgetJsRptFldr()
	{
		$srptfldr_js = "var ReportListArray=new Array(".$this->srptfldridjs.")
			setExpandCollapse()";
		return $srptfldr_js;
	}

	/** Function to set the Primary module jo_fields for the given Report
	 *  This function sets the primary module columns for the given Report
	 *  It accepts the Primary module as the argument and set the jo_fields of the module
	 *  to the varialbe pri_module_columnslist and returns true if sucess
	 */

	function getPriModuleColumnsList($module)
	{
		//$this->updateModuleList($module);
		$allColumnsListByBlocks =& $this->getColumnsListbyBlock($module, array_keys($this->module_list[$module]), true);
		foreach($this->module_list[$module] as $key=>$value) {
			$temp = $allColumnsListByBlocks[$key];

			if (!empty($ret_module_list[$module][$value])) {
				if (!empty($temp)) {
					$ret_module_list[$module][$value] = array_merge($ret_module_list[$module][$value], $temp);
				}
			} else {
				$ret_module_list[$module][$value] = $temp;
			}
		}
		if($module == 'Emails') {
			foreach($ret_module_list[$module] as $key => $value) {
				foreach($value as $key1 => $value1) {
					if($key1 == 'jo_activity:time_start:Emails_Time_Start:time_start:T') {
						unset($ret_module_list[$module][$key][$key1]);
					}
				}
			}
		}
		$this->pri_module_columnslist = $ret_module_list;
		return true;
	}

	/** Function to set the Secondary module fileds for the given Report
	 *  This function sets the secondary module columns for the given module
	 *  It accepts the module as the argument and set the jo_fields of the module
	 *  to the varialbe sec_module_columnslist and returns true if sucess
	 */

	function getSecModuleColumnsList($module)
	{
		if($module != "")
		{
			$secmodule = explode(":",$module);
			for($i=0;$i < count($secmodule) ;$i++)
			{
				//$this->updateModuleList($secmodule[$i]);
				if($this->module_list[$secmodule[$i]]){
					$this->sec_module_columnslist[$secmodule[$i]] = $this->getModuleFieldList(
							$secmodule[$i]);
					if($this->module_list[$secmodule[$i]] == 'Calendar') {
						if($this->module_list['Events']){
							$this->sec_module_columnslist['Events'] = $this->getModuleFieldList(
									'Events');
						}
					}
				}
			}
			if($module == 'Emails') {
				foreach($this->sec_module_columnslist[$module] as $key => $value) {
					foreach($value as $key1 => $value1) {
						if($key1 == 'jo_activity:time_start:Emails_Time_Start:time_start:T') {
							unset($this->sec_module_columnslist[$module][$key][$key1]);
						}
					}
				}
			}
		}
		return true;
	}

	/**
	 *
	 * @param String $module
	 * @param type $blockIdList
	 * @param Array $currentFieldList
	 * @return Array
	 */
	public function getBlockFieldList($module, $blockIdList, $currentFieldList,$allColumnsListByBlocks) {
		$temp = $allColumnsListByBlocks[$blockIdList];
		if(!empty($currentFieldList)){
			if(!empty($temp)){
				$currentFieldList = array_merge($currentFieldList,$temp);
			}
		} else {
			$currentFieldList = $temp;
		}
		return $currentFieldList;
	}

	public function getModuleFieldList($module) {
		$allColumnsListByBlocks =& $this->getColumnsListbyBlock($module, array_keys($this->module_list[$module]), true);
		foreach($this->module_list[$module] as $key=>$value) {
			$ret_module_list[$module][$value] = $this->getBlockFieldList(
					$module, $key, $ret_module_list[$module][$value],$allColumnsListByBlocks);
		}
		return $ret_module_list[$module];
	}

	/** Function to get jo_fields for the given module and block
	 *  This function gets the jo_fields for the given module
	 *  It accepts the module and the block as arguments and
	 *  returns the array column lists
	 *  Array module_columnlist[ jo_fieldtablename:fieldcolname:module_fieldlabel1:fieldname:fieldtypeofdata]=fieldlabel
	 */

	function getColumnsListbyBlock($module,$block,$group_res_by_block=false)
	{
		global $adb;
		global $log;
		global $current_user;

		if(is_string($block)) $block = explode(",", $block);
		$skipTalbes = array('jo_emaildetails','jo_attachments');

		$tabid = getTabid($module);
		if ($module == 'Calendar') {
			$tabid = array('9','16');
		}
		$params = array($tabid, $block);
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
		//Security Check
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$sql = "select * from jo_field where jo_field.tabid in (". generateQuestionMarks($tabid) .") and jo_field.block in (". generateQuestionMarks($block) .") and jo_field.displaytype in (1,2,3,5) and jo_field.presence in (0,2) AND tablename NOT IN (".generateQuestionMarks($skipTalbes).") ";

			//fix for Ticket #4016
			if($module == "Calendar")
				$sql.=" group by jo_field.fieldlabel order by sequence";
			else
			$sql.=" order by sequence";
		}
		else
		{

			$profileList = getCurrentUserProfileList();
			$sql = "select * from jo_field inner join jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid where jo_field.tabid in (". generateQuestionMarks($tabid) .")  and jo_field.block in (". generateQuestionMarks($block) .") and jo_field.displaytype in (1,2,3,5) and jo_profile2field.visible=0 and jo_def_org_field.visible=0 and jo_field.presence in (0,2)";
			if (count($profileList) > 0) {
				$sql .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($params, $profileList);
			}
			$sql .= ' and tablename NOT IN ('.generateQuestionMarks($skipTalbes).') ';

			//fix for Ticket #4016
			if($module == "Calendar")
				$sql.=" group by jo_field.fieldlabel order by sequence";
			else
				$sql.=" group by jo_field.fieldid order by sequence";
		}
		array_push($params, $skipTalbes);
		if($module!='AddressLookup'&& $module!='EmailPlus' && $module!='DuplicateCheck'){
                	$result = $adb->pquery($sql, $params);
		}
		$noofrows = $adb->num_rows($result);
		for($i=0; $i<$noofrows; $i++)
		{
			$fieldtablename = $adb->query_result($result,$i,"tablename");
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$fieldname = $adb->query_result($result,$i,"fieldname");
			$fieldtype = $adb->query_result($result,$i,"typeofdata");
			$uitype = $adb->query_result($result,$i,"uitype");
			$fieldtype = explode("~",$fieldtype);
			$fieldtypeofdata = $fieldtype[0];
			$blockid = $adb->query_result($result, $i, "block");

			//added to escape attachments fields in Reports as we have multiple attachments
            if(($module == 'HelpDesk' && $fieldname =='filename')
					|| ($fieldtablename == 'jo_inventoryproductrel' && $fieldname == 'image')) {
				continue;
			}

			//Here we Changing the displaytype of the field. So that its criteria will be displayed correctly in Reports Advance Filter.
			$fieldtypeofdata=ChangeTypeOfData_Filter($fieldtablename,$fieldcolname,$fieldtypeofdata);

			if($uitype == 68 || $uitype == 59 || $uitype == 10)
			{
				$fieldtypeofdata = 'V';
			}
			if($fieldtablename == "jo_crmentity")
			{
				$fieldtablename = $fieldtablename.$module;
			}
			if($fieldname == "assigned_user_id")
			{
				$fieldtablename = "jo_users".$module;
				$fieldcolname = "user_name";
			}
			if($fieldname == "assigned_user_id1")
			{
				$fieldtablename = "jo_usersRel1";
				$fieldcolname = "user_name";
			}

			$fieldlabel = $adb->query_result($result,$i,"fieldlabel");
			if ($module == 'Emails' and $fieldlabel == 'Date & Time Sent') {
				$fieldlabel = 'Date Sent';
				$fieldtypeofdata = 'D';
			}
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1.":".$fieldname.":".$fieldtypeofdata;

			$adv_rel_field_tod_value = '$'.$module.'#'.$fieldname.'$'."::".getTranslatedString($module,$module)." ".getTranslatedString($fieldlabel,$module);
			if (!is_array($this->adv_rel_fields[$fieldtypeofdata]) ||
					!in_array($adv_rel_field_tod_value, $this->adv_rel_fields[$fieldtypeofdata])) {
				$this->adv_rel_fields[$fieldtypeofdata][] = $adv_rel_field_tod_value;
			}

			if (is_string($block) || $group_res_by_block == false) {
				$module_columnlist[$optionvalue] = $fieldlabel;
			} else {
				$module_columnlist[$blockid][$optionvalue] = $fieldlabel;
			}
		}

		$primaryModule = $this->primodule;
		if ($primaryModule == 'PriceBooks') {
			if ($module == 'Products') {
				$module_columnlist[$blockid]['jo_pricebookproductrel:listprice:Products_List_Price:listprice:V'] = 'List Price';
			}
			if ($module == 'Services') {
				$module_columnlist[$blockid]['jo_pricebookproductrel:listprice:Services_List_Price:listprice:V'] = 'List Price';
			}
		}

		return $module_columnlist;
	}

	function fixGetColumnsListbyBlockForInventory($module, $blockid, &$module_columnlist) {
		global $log;

		$blockname = getBlockName($blockid);
		if($blockname == 'LBL_RELATED_PRODUCTS' && ($module=='PurchaseOrder' || $module=='SalesOrder' || $module=='Quotes' || $module=='Invoice')){
			$fieldtablename = 'jo_inventoryproductrel';
			$fields = array('productid'=>getTranslatedString('Product Name',$module),
							'serviceid'=>getTranslatedString('Service Name',$module),
							'listprice'=>getTranslatedString('List Price',$module),
							'discount_amount'=>getTranslatedString('Discount',$module),
							'quantity'=>getTranslatedString('Quantity',$module),
							'comment'=>getTranslatedString('Comments',$module),
			);
			$fields_datatype = array('productid'=>'V',
							'serviceid'=>'V',
							'listprice'=>'I',
							'discount_amount'=>'I',
							'quantity'=>'I',
							'comment'=>'V',
			);
			foreach($fields as $fieldcolname=>$label){
				$column_name = str_replace(' ', '_', $label);
				$fieldtypeofdata = $fields_datatype[$fieldcolname];
				$optionvalue =  $fieldtablename.":".$fieldcolname.":".$module."_".$column_name.":".$fieldcolname.":".$fieldtypeofdata;
				$module_columnlist[$optionvalue] = $label;
			}
		}
		$log->info("Reports :: FieldColumns->Successfully returned ColumnslistbyBlock".$module.$block);
		return $module_columnlist;
	}

	/** Function to set the standard filter jo_fields for the given jo_report
	 *  This function gets the standard filter jo_fields for the given jo_report
	 *  and set the values to the corresponding variables
	 *  It accepts the repordid as argument
	 */

	function getSelectedStandardCriteria($reportid) {
		global $adb;
		$sSQL = "select jo_reportdatefilter.* from jo_reportdatefilter inner join jo_report on jo_report.reportid = jo_reportdatefilter.datefilterid where jo_report.reportid=?";
		$result = $adb->pquery($sSQL, array($reportid));
		$selectedstdfilter = $adb->fetch_array($result);

		$this->stdselectedcolumn = $selectedstdfilter["datecolumnname"];
		$this->stdselectedfilter = $selectedstdfilter["datefilter"];

		if($selectedstdfilter["datefilter"] == "custom") {
			if($selectedstdfilter["startdate"] != "0000-00-00") {
				$startDateTime = new DateTimeField($selectedstdfilter["startdate"].' '. date('H:i:s'));
				$this->startdate = $startDateTime->getDisplayDate();
			}
			if($selectedstdfilter["enddate"] != "0000-00-00") {
				$endDateTime = new DateTimeField($selectedstdfilter["enddate"].' '. date('H:i:s'));
				$this->enddate = $endDateTime->getDisplayDate();
			}
		}
	}

	/** Function to get the combo values for the standard filter
	 *  This function get the combo values for the standard filter for the given jo_report
	 *  and return a HTML string
	 */

	function getSelectedStdFilterCriteria($selecteddatefilter = "")
	{
		global $mod_strings;

		$datefiltervalue = Array("custom","prevfy","thisfy","nextfy","prevfq","thisfq","nextfq",
				"yesterday","today","tomorrow","lastweek","thisweek","nextweek","lastmonth","thismonth",
				"nextmonth","last7days","last14days","last30days", "last60days","last90days","last120days",
				"next30days","next60days","next90days","next120days"
				);

		$datefilterdisplay = Array("Custom","Previous FY", "Current FY","Next FY","Previous FQ","Current FQ","Next FQ","Yesterday",
				"Today","Tomorrow","Last Week","Current Week","Next Week","Last Month","Current Month",
				"Next Month","Last 7 Days","Last 30 Days","Last 60 Days","Last 90 Days","Last 120 Days",
				"Next 7 Days","Next 30 Days","Next 60 Days","Next 90 Days","Next 120 Days"
				);

		for($i=0;$i<count($datefiltervalue);$i++)
		{
			if($selecteddatefilter == $datefiltervalue[$i])
			{
				$sshtml .= "<option selected value='".$datefiltervalue[$i]."'>".$mod_strings[$datefilterdisplay[$i]]."</option>";
			}else
			{
				$sshtml .= "<option value='".$datefiltervalue[$i]."'>".$mod_strings[$datefilterdisplay[$i]]."</option>";
			}
		}

		return $sshtml;
	}

	/** Function to get the selected standard filter columns
	 *  This function returns the selected standard filter criteria
	 *  which is selected for jo_reports as an array
	 *  Array stdcriteria_list[fieldtablename:fieldcolname:module_fieldlabel1]=fieldlabel
	 */

	function getStdCriteriaByModule($module)
	{
		global $adb;
		global $log;
		global $current_user;
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

		$tabid = getTabid($module);
		foreach($this->module_list[$module] as $key=>$blockid)
		{
			$blockids[] = $blockid;
		}
		$blockids = implode(",",$blockids);

		$params = array($tabid, $blockids);
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0)
		{
			//uitype 6 and 23 added for start_date,EndDate,Expected Close Date
			$sql = "select * from jo_field where jo_field.tabid=? and (jo_field.uitype =5 or jo_field.uitype = 6 or jo_field.uitype = 23 or jo_field.displaytype=2) and jo_field.block in (". generateQuestionMarks($block) .") and jo_field.presence in (0,2) order by jo_field.sequence";
		}
		else
		{
			$profileList = getCurrentUserProfileList();
			$sql = "select * from jo_field inner join jo_tab on jo_tab.tabid = jo_field.tabid inner join jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid  where jo_field.tabid=? and (jo_field.uitype =5 or jo_field.displaytype=2) and jo_profile2field.visible=0 and jo_def_org_field.visible=0 and jo_field.block in (". generateQuestionMarks($block) .") and jo_field.presence in (0,2)";
			if (count($profileList) > 0) {
				$sql .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($params, $profileList);
			}
			$sql .= " order by jo_field.sequence";
		}

		$result = $adb->pquery($sql, $params);

		while($criteriatyperow = $adb->fetch_array($result))
		{
			$fieldtablename = $criteriatyperow["tablename"];
			$fieldcolname = $criteriatyperow["columnname"];
			$fieldlabel = $criteriatyperow["fieldlabel"];

			if($fieldtablename == "jo_crmentity")
			{
				$fieldtablename = $fieldtablename.$module;
			}
			$fieldlabel1 = str_replace(" ","_",$fieldlabel);
			$optionvalue = $fieldtablename.":".$fieldcolname.":".$module."_".$fieldlabel1;
			$stdcriteria_list[$optionvalue] = $fieldlabel;
		}

		$log->info("Reports :: StdfilterColumns->Successfully returned Stdfilter for".$module);
		return $stdcriteria_list;

	}

	/** Function to form a javascript to determine the start date and end date for a standard filter
	 *  This function is to form a javascript to determine
	 *  the start date and End date from the value selected in the combo lists
	 */

	function getCriteriaJS()
	{

		$todayDateTime = new DateTimeField(date('Y-m-d H:i:s'));

		$tomorrow  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
		$tomorrowDateTime = new DateTimeField($tomorrow.' '. date('H:i:s'));

		$yesterday  = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
		$yesterdayDateTime = new DateTimeField($yesterday.' '. date('H:i:s'));

		$currentmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m"), "01",   date("Y")));
		$currentMonthStartDateTime = new DateTimeField($currentmonth0.' '. date('H:i:s'));
		$currentmonth1 = date("Y-m-t");
		$currentMonthEndDateTime = new DateTimeField($currentmonth1.' '. date('H:i:s'));

		$lastmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m")-1, "01",   date("Y")));
		$lastMonthStartDateTime = new DateTimeField($lastmonth0.' '. date('H:i:s'));
		$lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
		$lastMonthEndDateTime = new DateTimeField($lastmonth1.' '. date('H:i:s'));

		$nextmonth0 = date("Y-m-d",mktime(0, 0, 0, date("m")+1, "01",   date("Y")));
		$nextMonthStartDateTime = new DateTimeField($nextmonth0.' '. date('H:i:s'));
		$nextmonth1 = date("Y-m-t", strtotime("+1 Month"));
		$nextMonthEndDateTime = new DateTimeField($nextmonth1.' '. date('H:i:s'));

		$lastweek0 = date("Y-m-d",strtotime("-2 week Monday"));
		$lastWeekStartDateTime = new DateTimeField($lastweek0.' '. date('H:i:s'));
		$lastweek1 = date("Y-m-d",strtotime("-1 week Sunday"));
		$lastWeekEndDateTime = new DateTimeField($lastweek1.' '. date('H:i:s'));

		$thisweek0 = date("Y-m-d",strtotime("-1 week Monday"));
		$thisWeekStartDateTime = new DateTimeField($thisweek0.' '. date('H:i:s'));
		$thisweek1 = date("Y-m-d",strtotime("this Sunday"));
		$thisWeekEndDateTime = new DateTimeField($thisweek1.' '. date('H:i:s'));

		$nextweek0 = date("Y-m-d",strtotime("this Monday"));
		$nextWeekStartDateTime = new DateTimeField($nextweek0.' '. date('H:i:s'));
		$nextweek1 = date("Y-m-d",strtotime("+1 week Sunday"));
		$nextWeekEndDateTime = new DateTimeField($nextweek1.' '. date('H:i:s'));

		$next7days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+6, date("Y")));
		$next7DaysDateTime = new DateTimeField($next7days.' '. date('H:i:s'));

		$next30days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+29, date("Y")));
		$next30DaysDateTime = new DateTimeField($next30days.' '. date('H:i:s'));

		$next60days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+59, date("Y")));
		$next60DaysDateTime = new DateTimeField($next60days.' '. date('H:i:s'));

		$next90days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+89, date("Y")));
		$next90DaysDateTime = new DateTimeField($next90days.' '. date('H:i:s'));

		$next120days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+119, date("Y")));
		$next120DaysDateTime = new DateTimeField($next120days.' '. date('H:i:s'));

		$last7days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
		$last7DaysDateTime = new DateTimeField($last7days.' '. date('H:i:s'));
                
        $last14days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-13, date("Y")));
		$last14DaysDateTime = new DateTimeField($last14days.' '. date('H:i:s'));

		$last30days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-29, date("Y")));
		$last30DaysDateTime = new DateTimeField($last30days.' '. date('H:i:s'));

		$last60days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-59, date("Y")));
		$last60DaysDateTime = new DateTimeField($last60days.' '. date('H:i:s'));

		$last90days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-89, date("Y")));
		$last90DaysDateTime = new DateTimeField($last90days.' '. date('H:i:s'));

		$last120days = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-119, date("Y")));
		$last120DaysDateTime = new DateTimeField($last120days.' '. date('H:i:s'));

		$currentFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")));
		$currentFYStartDateTime = new DateTimeField($currentFY0.' '. date('H:i:s'));
		$currentFY1 = date("Y-m-t",mktime(0, 0, 0, "12", date("d"),   date("Y")));
		$currentFYEndDateTime = new DateTimeField($currentFY1.' '. date('H:i:s'));

		$lastFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")-1));
		$lastFYStartDateTime = new DateTimeField($lastFY0.' '. date('H:i:s'));
		$lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")-1));
		$lastFYEndDateTime = new DateTimeField($lastFY1.' '. date('H:i:s'));

		$nextFY0 = date("Y-m-d",mktime(0, 0, 0, "01", "01",   date("Y")+1));
		$nextFYStartDateTime = new DateTimeField($nextFY0.' '. date('H:i:s'));
		$nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")+1));
		$nextFYEndDateTime = new DateTimeField($nextFY1.' '. date('H:i:s'));

		if(date("m") <= 3) {
			$cFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$cFqStartDateTime = new DateTimeField($cFq.' '. date('H:i:s'));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")));
			$cFqEndDateTime = new DateTimeField($cFq1.' '. date('H:i:s'));

			$nFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$nFqStartDateTime = new DateTimeField($nFq.' '. date('H:i:s'));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$nFqEndDateTime = new DateTimeField($nFq1.' '. date('H:i:s'));

			$pFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")-1));
			$pFqStartDateTime = new DateTimeField($pFq.' '. date('H:i:s'));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")-1));
			$pFqEndDateTime = new DateTimeField($pFq1.' '. date('H:i:s'));

		} else if(date("m") > 3 and date("m") <= 6) {

			$pFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")));
			$pFqStartDateTime = new DateTimeField($pFq.' '. date('H:i:s'));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")));
			$pFqEndDateTime = new DateTimeField($pFq1.' '. date('H:i:s'));

			$cFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$cFqStartDateTime = new DateTimeField($cFq.' '. date('H:i:s'));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$cFqEndDateTime = new DateTimeField($cFq1.' '. date('H:i:s'));

			$nFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$nFqStartDateTime = new DateTimeField($nFq.' '. date('H:i:s'));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
			$nFqEndDateTime = new DateTimeField($nFq1.' '. date('H:i:s'));

		} else if(date("m") > 6 and date("m") <= 9) {

			$nFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")));
			$nFqStartDateTime = new DateTimeField($nFq.' '. date('H:i:s'));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));
			$nFqEndDateTime = new DateTimeField($nFq1.' '. date('H:i:s'));

			$pFq = date("Y-m-d",mktime(0, 0, 0, "04","01",date("Y")));
			$pFqStartDateTime = new DateTimeField($pFq.' '. date('H:i:s'));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "06","30",date("Y")));
			$pFqEndDateTime = new DateTimeField($pFq1.' '. date('H:i:s'));

			$cFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$cFqStartDateTime = new DateTimeField($cFq.' '. date('H:i:s'));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
			$cFqEndDateTime = new DateTimeField($cFq1.' '. date('H:i:s'));

		} else if(date("m") > 9 and date("m") <= 12) {
			$nFq = date("Y-m-d",mktime(0, 0, 0, "01","01",date("Y")+1));
			$nFqStartDateTime = new DateTimeField($nFq.' '. date('H:i:s'));
			$nFq1 = date("Y-m-d",mktime(0, 0, 0, "03","31",date("Y")+1));
			$nFqEndDateTime = new DateTimeField($nFq1.' '. date('H:i:s'));

			$pFq = date("Y-m-d",mktime(0, 0, 0, "07","01",date("Y")));
			$pFqStartDateTime = new DateTimeField($pFq.' '. date('H:i:s'));
			$pFq1 = date("Y-m-d",mktime(0, 0, 0, "09","30",date("Y")));
			$pFqEndDateTime = new DateTimeField($pFq1.' '. date('H:i:s'));

			$cFq = date("Y-m-d",mktime(0, 0, 0, "10","01",date("Y")));
			$cFqStartDateTime = new DateTimeField($cFq.' '. date('H:i:s'));
			$cFq1 = date("Y-m-d",mktime(0, 0, 0, "12","31",date("Y")));
			$cFqEndDateTime = new DateTimeField($cFq1.' '. date('H:i:s'));
		}

		$sjsStr = '<script language="JavaScript" type="text/javaScript">
			function showDateRange( type ) {
				if (type!="custom") {
					document.NewReport.startdate.readOnly=true
					document.NewReport.enddate.readOnly=true
					getObj("jscal_trigger_date_start").style.visibility="hidden"
					getObj("jscal_trigger_date_end").style.visibility="hidden"
				} else {
					document.NewReport.startdate.readOnly=false
					document.NewReport.enddate.readOnly=false
					getObj("jscal_trigger_date_start").style.visibility="visible"
					getObj("jscal_trigger_date_end").style.visibility="visible"
				}
				if( type == "today" ) {
					document.NewReport.startdate.value = "'.$todayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$todayDateTime->getDisplayDate().'";

				} else if( type == "yesterday" ) {
					document.NewReport.startdate.value = "'.$yesterdayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$yesterdayDateTime->getDisplayDate().'";

				} else if( type == "tomorrow" ) {
					document.NewReport.startdate.value = "'.$tomorrowDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$tomorrowDateTime->getDisplayDate().'";

				} else if( type == "thisweek" ) {
					document.NewReport.startdate.value = "'.$thisWeekStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$thisWeekEndDateTime->getDisplayDate().'";

				} else if( type == "lastweek" ) {
					document.NewReport.startdate.value = "'.$lastWeekStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$lastWeekEndDateTime->getDisplayDate().'";

				} else if( type == "nextweek" ) {
					document.NewReport.startdate.value = "'.$nextWeekStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$nextWeekEndDateTime->getDisplayDate().'";

				} else if( type == "thismonth" ) {
					document.NewReport.startdate.value = "'.$currentMonthStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$currentMonthEndDateTime->getDisplayDate().'";

				} else if( type == "lastmonth" ) {
					document.NewReport.startdate.value = "'.$lastMonthStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$lastMonthEndDateTime->getDisplayDate().'";

				} else if( type == "nextmonth" ) {
					document.NewReport.startdate.value = "'.$nextMonthStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$nextMonthEndDateTime->getDisplayDate().'";

				} else if( type == "next7days" ) {
					document.NewReport.startdate.value = "'.$todayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$next7DaysDateTime->getDisplayDate().'";

				} else if( type == "next30days" ) {
					document.NewReport.startdate.value = "'.$todayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$next30DaysDateTime->getDisplayDate().'";

				} else if( type == "next60days" ) {
					document.NewReport.startdate.value = "'.$todayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$next60DaysDateTime->getDisplayDate().'";

				} else if( type == "next90days" ) {
					document.NewReport.startdate.value = "'.$todayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$next90DaysDateTime->getDisplayDate().'";

				} else if( type == "next120days" ) {
					document.NewReport.startdate.value = "'.$todayDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$next120DaysDateTime->getDisplayDate().'";

				} else if( type == "last7days" ) {
					document.NewReport.startdate.value = "'.$last7DaysDateTime->getDisplayDate().'";
					document.NewReport.enddate.value =  "'.$todayDateTime->getDisplayDate().'";
                                            
                } else if( type == "last14days" ) {
					document.NewReport.startdate.value = "'.$last14DaysDateTime->getDisplayDate().'";
					document.NewReport.enddate.value =  "'.$todayDateTime->getDisplayDate().'";

				} else if( type == "last30days" ) {
					document.NewReport.startdate.value = "'.$last30DaysDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$todayDateTime->getDisplayDate().'";

				} else if( type == "last60days" ) {
					document.NewReport.startdate.value = "'.$last60DaysDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$todayDateTime->getDisplayDate().'";

				} else if( type == "last90days" ) {
					document.NewReport.startdate.value = "'.$last90DaysDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$todayDateTime->getDisplayDate().'";

				} else if( type == "last120days" ) {
					document.NewReport.startdate.value = "'.$last120DaysDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$todayDateTime->getDisplayDate().'";

				} else if( type == "thisfy" ) {
					document.NewReport.startdate.value = "'.$currentFYStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$currentFYEndDateTime->getDisplayDate().'";

				} else if( type == "prevfy" ) {
					document.NewReport.startdate.value = "'.$lastFYStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$lastFYEndDateTime->getDisplayDate().'";

				} else if( type == "nextfy" ) {
					document.NewReport.startdate.value = "'.$nextFYStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$nextFYEndDateTime->getDisplayDate().'";

				} else if( type == "nextfq" ) {
					document.NewReport.startdate.value = "'.$nFqStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$nFqEndDateTime->getDisplayDate().'";

				} else if( type == "prevfq" ) {
					document.NewReport.startdate.value = "'.$pFqStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$pFqEndDateTime->getDisplayDate().'";

				} else if( type == "thisfq" ) {
					document.NewReport.startdate.value = "'.$cFqStartDateTime->getDisplayDate().'";
					document.NewReport.enddate.value = "'.$cFqEndDateTime->getDisplayDate().'";

				} else {
					document.NewReport.startdate.value = "";
					document.NewReport.enddate.value = "";
				}
			}
		</script>';

		return $sjsStr;
	}
function getEscapedColumns($selectedfields)
	{
		$fieldname = $selectedfields[3];
		if($fieldname == "parent_id")
		{
			if($this->primarymodule == "HelpDesk" && $selectedfields[0] == "jo_crmentityRelHelpDesk")
			{
				$querycolumn = "case jo_crmentityRelHelpDesk.setype when 'Accounts' then jo_accountRelHelpDesk.accountname when 'Contacts' then jo_contactdetailsRelHelpDesk.lastname End"." '".$selectedfields[2]."', jo_crmentityRelHelpDesk.setype 'Entity_type'";
				return $querycolumn;
			}
			if($this->primarymodule == "Products" || $this->secondarymodule == "Products")
			{
				$querycolumn = "case jo_crmentityRelProducts.setype when 'Accounts' then jo_accountRelProducts.accountname when 'Leads' then jo_leaddetailsRelProducts.lastname when 'Potentials' then jo_potentialRelProducts.potentialname End"." '".$selectedfields[2]."', jo_crmentityRelProducts.setype 'Entity_type'";
			}
			if($this->primarymodule == "Calendar" || $this->secondarymodule == "Calendar")
			{
				$querycolumn = "case jo_crmentityRelCalendar.setype when 'Accounts' then jo_accountRelCalendar.accountname when 'Leads' then jo_leaddetailsRelCalendar.lastname when 'Potentials' then jo_potentialRelCalendar.potentialname when 'Quotes' then jo_quotesRelCalendar.subject when 'PurchaseOrder' then jo_purchaseorderRelCalendar.subject when 'Invoice' then jo_invoiceRelCalendar.subject End"." '".$selectedfields[2]."', jo_crmentityRelCalendar.setype 'Entity_type'";
			}
		}
		return $querycolumn;
	}
	function getaccesfield($module)
	{
		global $current_user;
		global $adb;
		$access_fields = Array();

		$profileList = getCurrentUserProfileList();
		$query = "select jo_field.fieldname from jo_field inner join jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid where";
		$params = array();
		if($module == "Calendar")
		{
			$query .= " jo_field.tabid in (9,16) and jo_field.displaytype in (1,2,3) and jo_profile2field.visible=0 and jo_def_org_field.visible=0 and jo_field.presence in (0,2)";
			if (count($profileList) > 0) {
				$query .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($params, $profileList);
			}
			$query .= " group by jo_field.fieldid order by block,sequence";
		}
		else
		{
			array_push($params, $this->primodule, $this->secmodule);
			$query .= " jo_field.tabid in (select tabid from jo_tab where jo_tab.name in (?,?)) and jo_field.displaytype in (1,2,3) and jo_profile2field.visible=0 and jo_def_org_field.visible=0 and jo_field.presence in (0,2)";
			if (count($profileList) > 0) {
				$query .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($params, $profileList);
			}
			$query .= " group by jo_field.fieldid order by block,sequence";
		}
		$result = $adb->pquery($query, $params);


		while($collistrow = $adb->fetch_array($result))
		{
			$access_fields[] = $collistrow["fieldname"];
		}
		return $access_fields;
	}

	/** Function to set the order of grouping and to find the columns responsible
	 *  to the grouping
	 *  This function accepts the jo_reportid as variable,sets the variable ascdescorder[] to the sort order and
	 *  returns the array array_list which has the column responsible for the grouping
	 *  Array array_list[0]=columnname
	 */


	function getSelctedSortingColumns($reportid)
	{

		global $adb;
		global $log;

		$sreportsortsql = "select jo_reportsortcol.* from jo_report";
		$sreportsortsql .= " inner join jo_reportsortcol on jo_report.reportid = jo_reportsortcol.reportid";
		$sreportsortsql .= " where jo_report.reportid =? order by jo_reportsortcol.sortcolid";

		$result = $adb->pquery($sreportsortsql, array($reportid));
		$noofrows = $adb->num_rows($result);

		for($i=0; $i<$noofrows; $i++)
		{
			$fieldcolname = $adb->query_result($result,$i,"columnname");
			$sort_values = $adb->query_result($result,$i,"sortorder");
			$this->ascdescorder[] = $sort_values;
			$array_list[] = $fieldcolname;
		}

		$log->info("Reports :: Successfully returned getSelctedSortingColumns");
		return $array_list;
	}

	/** Function to get the selected columns list for a selected jo_report
	 *  This function accepts the jo_reportid as the argument and get the selected columns
	 *  for the given jo_reportid and it forms a combo lists and returns
	 *  HTML of the combo values
	 */

	function getSelectedColumnsList($reportid)
	{
		global $adb;
		global $modules;
		global $log,$current_user;

		$ssql = "select jo_selectcolumn.* from jo_report inner join jo_selectquery on jo_selectquery.queryid = jo_report.queryid";
		$ssql .= " left join jo_selectcolumn on jo_selectcolumn.queryid = jo_selectquery.queryid";
		$ssql .= " where jo_report.reportid = ?";
		$ssql .= " order by jo_selectcolumn.columnindex";
		$result = $adb->pquery($ssql, array($reportid));
		$permitted_fields = Array();

		$selected_mod = split(":",$this->secmodule);
		array_push($selected_mod,$this->primodule);

		$inventoryModules = getInventoryModules();
		while($columnslistrow = $adb->fetch_array($result))
		{
			$fieldname ="";
			$fieldcolname = $columnslistrow["columnname"];

			$selmod_field_disabled = true;
			foreach($selected_mod as $smod){
				if((stripos($fieldcolname,":".$smod."_")>-1) && modlib_isModuleActive($smod)){
					$selmod_field_disabled = false;
					break;
				}
			}
			if($selmod_field_disabled==false){
				list($tablename,$colname,$module_field,$fieldname,$single) = split(":",$fieldcolname);
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
				list($module,$field) = split("_",$module_field);
				if(sizeof($permitted_fields) == 0 && $is_admin == false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1)
				{
					$permitted_fields = $this->getaccesfield($module);
				}
				$querycolumns = $this->getEscapedColumns($selectedfields);
				$fieldlabel = trim(str_replace($module," ",$module_field));
				$mod_arr=explode('_',$fieldlabel);
	                        $mod = ($mod_arr[0] == '')?$module:$mod_arr[0];
				$fieldlabel = trim(str_replace("_"," ",$fieldlabel));
				//modified code to support i18n issue
				$mod_lbl = getTranslatedString($mod,$module); //module
				$fld_lbl = getTranslatedString($fieldlabel,$module); //fieldlabel
				$fieldlabel = $mod_lbl." ".$fld_lbl;
				if (in_array($mod, $inventoryModules) && $fieldname == 'serviceid') {
					$shtml .= "<option permission='yes' value=\"".$fieldcolname."\">".$fieldlabel."</option>";
				}
				else if(CheckFieldPermission($fieldname,$mod) != 'true' && $colname!="crmid")
				{
					$shtml .= "<option permission='no' value=\"".$fieldcolname."\" disabled = 'true'>".$fieldlabel."</option>";
				}
				else
				{
					$shtml .= "<option permission='yes' value=\"".$fieldcolname."\">".$fieldlabel."</option>";
				}
			}
			//end
		}
		$log->info("ReportRun :: Successfully returned getQueryColumnsList".$reportid);
		return $shtml;
	}
	function getAdvancedFilterList($reportid)
	{
		global $adb;
		global $modules;
		global $log;
		global $current_user;

		$advft_criteria = array();

		$sql = 'SELECT * FROM jo_relcriteria_grouping WHERE queryid = ? ORDER BY groupid';
		$groupsresult = $adb->pquery($sql, array($reportid));

		$i = 1;
		$j = 0;
		while($relcriteriagroup = $adb->fetch_array($groupsresult)) {
			$groupId = $relcriteriagroup["groupid"];
			$groupCondition = $relcriteriagroup["group_condition"];

			$ssql = 'select jo_relcriteria.* from jo_report
						inner join jo_relcriteria on jo_relcriteria.queryid = jo_report.queryid
						left join jo_relcriteria_grouping on jo_relcriteria.queryid = jo_relcriteria_grouping.queryid
								and jo_relcriteria.groupid = jo_relcriteria_grouping.groupid';
			$ssql.= " where jo_report.reportid = ? AND jo_relcriteria.groupid = ? order by jo_relcriteria.columnindex";

			$result = $adb->pquery($ssql, array($reportid, $groupId));
			$noOfColumns = $adb->num_rows($result);
			if($noOfColumns <= 0) continue;

			while($relcriteriarow = $adb->fetch_array($result)) {
				$columnIndex = $relcriteriarow["columnindex"];
				$criteria = array();
				$criteria['columnname'] = $relcriteriarow["columnname"];
				$criteria['comparator'] = $relcriteriarow["comparator"];
				$advfilterval = $relcriteriarow["value"];
				$col = explode(":",$relcriteriarow["columnname"]);

				$moduleFieldLabel = $col[2];
				$fieldName = $col[3];

				list($module, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
				$fieldInfo = getFieldByReportLabel($module, $fieldLabel);
				$fieldType = null;
				if(!empty($fieldInfo)) {
					$field = WebserviceField::fromArray($adb, $fieldInfo);
					$fieldType = $field->getFieldDataType();
				}
				if($fieldType == 'currency') {
					if($field->getUIType() == '71') {
						$advfilterval = CurrencyField::convertToUserFormat($advfilterval,$current_user);
					} else if($field->getUIType() == '72') {
						$advfilterval = CurrencyField::convertToUserFormat($advfilterval,$current_user,true);
					}
				}
                $specialDateConditions = Head_Functions::getSpecialDateTimeCondtions();
				$temp_val = explode(",",$relcriteriarow["value"]);
                
				if(($col[4] == 'D' || ($col[4] == 'T' && $col[1] != 'time_start' && $col[1] != 'time_end') || ($col[4] == 'DT')) && !in_array($criteria['comparator'], $specialDateConditions)) {
					$val = Array();
					for($x=0;$x<count($temp_val);$x++) {
                        if($col[4] == 'D') {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDisplayDate();
						} elseif($col[4] == 'DT') {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDisplayDateTimeValue();
						} elseif($fieldType == 'time') {
                            $val[$x] = Head_Time_UIType::getTimeValueWithSeconds($temp_val[$x]);
                        } else {
							$date = new DateTimeField(trim($temp_val[$x]));
							$val[$x] = $date->getDisplayTime();
						}
					}
					$advfilterval = implode(",",$val);
				}
                
				//In vtiger6 report filter conditions, if the value has "(double quotes) then it is failed.
				$criteria['value'] = Head_Util_Helper::toSafeHTML(decode_html($advfilterval));
				$criteria['column_condition'] = $relcriteriarow["column_condition"];

				$advft_criteria[$relcriteriarow['groupid']]['columns'][$j] = $criteria;
				$advft_criteria[$relcriteriarow['groupid']]['condition'] = $groupCondition;
				$j++;
			}
			$i++;
		}
		// Clear the condition (and/or) for last group, if any.
		if(!empty($advft_criteria[$i-1]['condition'])) $advft_criteria[$i-1]['condition'] = '';
		$this->advft_criteria = $advft_criteria;
		$log->info("Reports :: Successfully returned getAdvancedFilterList");
		return true;
	}
	//<<<<<<<<advanced filter>>>>>>>>>>>>>>

	/** Function to get the list of jo_report folders when Save and run  the jo_report
	 *  This function gets the jo_report folders from database and form
	 *  a combo values of the folders and return
	 *  HTML of the combo values
	 */

	function sgetRptFldrSaveReport()
	{
		global $adb;
		global $log;

		$sql = "select * from jo_reportfolder order by folderid";
		$result = $adb->pquery($sql, array());
		$reportfldrow = $adb->fetch_array($result);
		$x = 0;
		do
		{
			$shtml .= "<option value='".$reportfldrow['folderid']."'>".$reportfldrow['foldername']."</option>";
		}while($reportfldrow = $adb->fetch_array($result));

		$log->info("Reports :: Successfully returned sgetRptFldrSaveReport");
		return $shtml;
	}

	/** Function to get the column to total jo_fields in Reports
	 *  This function gets columns to total jo_field
	 *  and generated the html for that jo_fields
	 *  It returns the HTML of the jo_fields along with the check boxes
	 */

	function sgetColumntoTotal($primarymodule,$secondarymodule)
	{
		$options = Array();
		$options []= $this->sgetColumnstoTotalHTML($primarymodule,0);
		if(!empty($secondarymodule))
		{
			//$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$options []= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}
		return $options;
	}

	/** Function to get the selected columns of total jo_fields in Reports
	 *  This function gets selected columns of total jo_field
	 *  and generated the html for that jo_fields
	 *  It returns the HTML of the jo_fields along with the check boxes
	 */


	function sgetColumntoTotalSelected($primarymodule,$secondarymodule,$reportid)
	{
		global $adb;
		global $log;
		$options = Array();
		if($reportid != "")
		{
			$ssql = "select jo_reportsummary.* from jo_reportsummary inner join jo_report on jo_report.reportid = jo_reportsummary.reportsummaryid where jo_report.reportid=?";
			$result = $adb->pquery($ssql, array($reportid));
			if($result)
			{
				$reportsummaryrow = $adb->fetch_array($result);

				do
				{
					$this->columnssummary[] = $reportsummaryrow["columnname"];

				}while($reportsummaryrow = $adb->fetch_array($result));
			}
		}
		$options []= $this->sgetColumnstoTotalHTML($primarymodule,0);
		if($secondarymodule != "")
		{
			$secondarymodule = explode(":",$secondarymodule);
			for($i=0;$i < count($secondarymodule) ;$i++)
			{
				$options []= $this->sgetColumnstoTotalHTML($secondarymodule[$i],($i+1));
			}
		}

		$log->info("Reports :: Successfully returned sgetColumntoTotalSelected");
		return $options;

	}

	/** Function to form the HTML for columns to total
	 *  This function formulates the HTML format of the
	 *  jo_fields along with four checkboxes
	 *  It returns the HTML of the jo_fields along with the check boxes
	 */


	function sgetColumnstoTotalHTML($module)
	{
		//retreive the jo_tabid
		global $adb;
		global $log;
		global $current_user;
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
		$tabid = getTabid($module);
		$escapedchars = Array('_SUM','_AVG','_MIN','_MAX');
		$sparams = array($tabid);
		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] ==0)
		{
			$ssql = "select * from jo_field inner join jo_tab on jo_tab.tabid = jo_field.tabid where jo_field.uitype != 50 and jo_field.tabid=? and jo_field.displaytype in (1,2,3) and jo_field.presence in (0,2) ";
		}
		else
		{
			$profileList = getCurrentUserProfileList();
			$ssql = "select * from jo_field inner join jo_tab on jo_tab.tabid = jo_field.tabid inner join jo_def_org_field on jo_def_org_field.fieldid=jo_field.fieldid inner join jo_profile2field on jo_profile2field.fieldid=jo_field.fieldid  where jo_field.uitype != 50 and jo_field.tabid=? and jo_field.displaytype in (1,2,3) and jo_def_org_field.visible=0 and jo_profile2field.visible=0 and jo_field.presence in (0,2)";
			if (count($profileList) > 0) {
				$ssql .= " and jo_profile2field.profileid in (". generateQuestionMarks($profileList) .")";
				array_push($sparams, $profileList);
			}
		}

		//Added to avoid display the Related fields (Account name,Vandor name,product name, etc) in Report Calculations(SUM,AVG..)
		switch($tabid)
		{
			case 2://Potentials
				//ie. Campaign name will not displayed in Potential's report calcullation
				$ssql.= " and jo_field.fieldname not in ('campaignid')";
				break;
			case 4://Contacts
				$ssql.= " and jo_field.fieldname not in ('account_id')";
				break;
			case 6://Accounts
				$ssql.= " and jo_field.fieldname not in ('account_id')";
				break;
			case 9://Calandar
				$ssql.= " and jo_field.fieldname not in ('parent_id','contact_id')";
				break;
			case 13://Trouble tickets(HelpDesk)
				$ssql.= " and jo_field.fieldname not in ('parent_id','product_id')";
				break;
			case 14://Products
				$ssql.= " and jo_field.fieldname not in ('vendor_id','product_id')";
				break;
			case 20://Quotes
				$ssql.= " and jo_field.fieldname not in ('potential_id','assigned_user_id1','account_id','currency_id')";
				break;
			case 21://Purchase Order
				$ssql.= " and jo_field.fieldname not in ('contact_id','vendor_id','currency_id')";
				break;
			case 22://SalesOrder
				$ssql.= " and jo_field.fieldname not in ('potential_id','account_id','contact_id','quote_id','currency_id')";
				break;
			case 23://Invoice
				$ssql.= " and jo_field.fieldname not in ('salesorder_id','contact_id','account_id','currency_id')";
				break;
			case 26://Campaigns
				$ssql.= " and jo_field.fieldname not in ('product_id')";
				break;

		}

		$ssql.= " order by sequence";

		$result = $adb->pquery($ssql, $sparams);
		$columntototalrow = $adb->fetch_array($result);
		$options_list = Array();
		do
		{
			$typeofdata = explode("~",$columntototalrow["typeofdata"]);

			if($typeofdata[0] == "N" || $typeofdata[0] == "I" || ($typeofdata[0] == "NN" && !empty($typeofdata[2])))
			{
				$options = Array();
				if(isset($this->columnssummary))
				{
					$selectedcolumn = "";
					$selectedcolumn1 = "";

					for($i=0;$i < count($this->columnssummary) ;$i++)
					{
						$selectedcolumnarray = explode(":",$this->columnssummary[$i]);
						$selectedcolumn = $selectedcolumnarray[1].":".$selectedcolumnarray[2].":".
							str_replace($escapedchars,"",$selectedcolumnarray[3]);

						if ($selectedcolumn != $columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.str_replace(" ","_",$columntototalrow['fieldlabel']))
						{
							$selectedcolumn = "";
						}else
						{
							$selectedcolumn1[$selectedcolumnarray[4]] = $this->columnssummary[$i];
						}

					}
					if(isset($_REQUEST["record"]) && $_REQUEST["record"] != '')
					{
						$options['label'][] = getTranslatedString($columntototalrow['tablabel'],$columntototalrow['tablabel']).' -'.getTranslatedString($columntototalrow['fieldlabel'],$columntototalrow['tablabel']);
					}

					$columntototalrow['fieldlabel'] = str_replace(" ","_",$columntototalrow['fieldlabel']);
					$options []= getTranslatedString($columntototalrow['tablabel'],$columntototalrow['tablabel']).' - '.getTranslatedString($columntototalrow['fieldlabel'],$columntototalrow['tablabel']);
					if($selectedcolumn1[2] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_SUM:2")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';
					}
					if($selectedcolumn1[3] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_AVG:3")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="">';
					}

					if($selectedcolumn1[4] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_MIN:4")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4" type="checkbox" value="">';
					}

					if($selectedcolumn1[5] == "cb:".$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel']."_MAX:5")
					{
						$options []=  '<input checked name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="">';
					}else
					{
						$options []=  '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="">';
					}
				}else
				{
					$options []= getTranslatedString($columntototalrow['tablabel'],$columntototalrow['tablabel']).' - '.getTranslatedString($columntototalrow['fieldlabel'],$columntototalrow['tablabel']);
					$options []= '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_SUM:2" type="checkbox" value="">';
					$options []= '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_AVG:3" type="checkbox" value="" >';
					$options []= '<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MIN:4"type="checkbox" value="" >';
					$options [] ='<input name="cb:'.$columntototalrow['tablename'].':'.$columntototalrow['columnname'].':'.$columntototalrow['fieldlabel'].'_MAX:5" type="checkbox" value="" >';
				}
				$options_list [] = $options;
			}
		}while($columntototalrow = $adb->fetch_array($result));

		$log->info("Reports :: Successfully returned sgetColumnstoTotalHTML");
		return $options_list;
	}

	/** Function to get the  advanced filter criteria for an option
	 *  This function accepts The option in the advenced filter as an argument
	 *  This generate filter criteria for the advanced filter
	 *  It returns a HTML string of combo values
	 */
	public static function getAdvCriteriaHTML($selected="") {
		global $adv_filter_options;

		foreach($adv_filter_options as $key=>$value) {
			if($selected == $key) {
				$shtml .= "<option selected value=\"".$key."\">".$value."</option>";
			} else {
				$shtml .= "<option value=\"".$key."\">".$value."</option>";
			}
		}
		return $shtml;
	}
}

/** Function to get the primary module list in jo_reports
 *  This function generates the list of primary modules in jo_reports
 *  and returns an array of permitted modules
 */

function getReportsModuleList($focus)
{
	global $adb;
	global $app_list_strings;
	//global $report_modules;
	global $mod_strings;
	$modules = Array();
	foreach($focus->module_list as $key=>$value) {
		if(isPermitted($key,'index') == "yes") {
			$count_flag = 1;
			$modules [$key] = getTranslatedString($key,$key);
		}
	}
	asort($modules);
	return $modules;
}
/** Function to get the Related module list in jo_reports
 *  This function generates the list of secondary modules in jo_reports
 *  and returns the related module as an Array
 */

function getReportRelatedModules($module,$focus)
{
	global $app_list_strings;
	global $related_modules;
	global $mod_strings;
	$optionhtml = Array();
	if(modlib_isModuleActive($module)){
		if(!empty($focus->related_modules[$module])) {
			foreach($focus->related_modules[$module] as $rel_modules)
			{
				if(isPermitted($rel_modules,'index') == "yes")
				{
					$optionhtml []= $rel_modules;
				}
			}
		}
	}


	return $optionhtml;
}

function updateAdvancedCriteria($reportid, $advft_criteria, $advft_criteria_groups) {

	global $adb, $log;

	$idelrelcriteriasql = "delete from jo_relcriteria where queryid=?";
	$idelrelcriteriasqlresult = $adb->pquery($idelrelcriteriasql, array($reportid));

	$idelrelcriteriagroupsql = "delete from jo_relcriteria_grouping where queryid=?";
	$idelrelcriteriagroupsqlresult = $adb->pquery($idelrelcriteriagroupsql, array($reportid));

	if(empty($advft_criteria)) return;

	foreach($advft_criteria as $column_index => $column_condition) {

		if(empty($column_condition)) continue;

		$adv_filter_column = $column_condition["columnname"];
		$adv_filter_comparator = $column_condition["comparator"];
		$adv_filter_value = $column_condition["value"];
		$adv_filter_column_condition = $column_condition["columncondition"];
		$adv_filter_groupid = $column_condition["groupid"];

		$column_info = explode(":",$adv_filter_column);
		$moduleFieldLabel = $column_info[2];
		$fieldName = $column_info[3];

		list($module, $fieldLabel) = explode('_', $moduleFieldLabel, 2);
		$fieldInfo = getFieldByReportLabel($module, $fieldLabel);
		$fieldType = null;
		if(!empty($fieldInfo)) {
			$field = WebserviceField::fromArray($adb, $fieldInfo);
			$fieldType = $field->getFieldDataType();
		}
		if($fieldType == 'currency') {
			// Some of the currency fields like Unit Price, Total, Sub-total etc of Inventory modules, do not need currency conversion
			if($field->getUIType() == '72') {
				$adv_filter_value = CurrencyField::convertToDBFormat($adv_filter_value, null, true);
			} else {
				$adv_filter_value = CurrencyField::convertToDBFormat($adv_filter_value);
			}
		}

		$temp_val = explode(",",$adv_filter_value);
		if(($column_info[4] == 'D' || ($column_info[4] == 'T' && $column_info[1] != 'time_start' && $column_info[1] != 'time_end') || ($column_info[4] == 'DT')) && ($column_info[4] != '' && $adv_filter_value != '' ))
		{
			$val = Array();
			for($x=0;$x<count($temp_val);$x++) {
				if(trim($temp_val[$x]) != '') {
					$date = new DateTimeField(trim($temp_val[$x]));
					if($column_info[4] == 'D') {
						$val[$x] = DateTimeField::convertToUserFormat(
								trim($temp_val[$x]));
					} elseif($column_info[4] == 'DT') {
						$val[$x] = $date->getDBInsertDateTimeValue();
					} else {
						$val[$x] = $date->getDBInsertTimeValue();
					}
				}
			}
			$adv_filter_value = implode(",",$val);
		}

		$irelcriteriasql = "insert into jo_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE,GROUPID,COLUMN_CONDITION) values (?,?,?,?,?,?,?)";
		$irelcriteriaresult = $adb->pquery($irelcriteriasql, array($reportid, $column_index, $adv_filter_column, $adv_filter_comparator, $adv_filter_value, $adv_filter_groupid, $adv_filter_column_condition));

		// Update the condition expression for the group to which the condition column belongs
		$groupConditionExpression = '';
		if(!empty($advft_criteria_groups[$adv_filter_groupid]["conditionexpression"])) {
			$groupConditionExpression = $advft_criteria_groups[$adv_filter_groupid]["conditionexpression"];
		}
		$groupConditionExpression = $groupConditionExpression .' '. $column_index .' '. $adv_filter_column_condition;
		$advft_criteria_groups[$adv_filter_groupid]["conditionexpression"] = $groupConditionExpression;
	}

	foreach($advft_criteria_groups as $group_index => $group_condition_info) {

		if(empty($group_condition_info)) continue;
		if(empty($group_condition_info["conditionexpression"])) continue; // Case when the group doesn't have any column criteria

		$irelcriteriagroupsql = "insert into jo_relcriteria_grouping(GROUPID,QUERYID,GROUP_CONDITION,CONDITION_EXPRESSION) values (?,?,?,?)";
		$irelcriteriagroupresult = $adb->pquery($irelcriteriagroupsql, array($group_index, $reportid, $group_condition_info["groupcondition"], $group_condition_info["conditionexpression"]));
	}
}
