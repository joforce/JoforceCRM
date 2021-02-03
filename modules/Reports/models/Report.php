<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */
vimport('~~/modules/Reports/Reports.php');

class Head_Report_Model extends Reports
{

	static function getInstance($reportId = "")
	{
		$self = new self();
		return $self->Reports($reportId);
	}

	function Reports($reportId = "")
	{
		$db = PearDatabase::getInstance();
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$userId = $currentUser->getId();
		$currentUserRoleId = $currentUser->get('roleid');
		$subordinateRoles = getRoleSubordinates($currentUserRoleId);
		array_push($subordinateRoles, $currentUserRoleId);

		$this->initListOfModules();

		if ($reportId != "") {
			// Lookup information in cache first
			$cachedInfo = CacheUtils::lookupReport_Info($userId, $reportId);
			$subOrdinateUsers = CacheUtils::lookupReport_SubordinateUsers($reportId);

			if ($cachedInfo === false) {
				$ssql = "SELECT jo_reportmodules.*, jo_report.* FROM jo_report
							INNER JOIN jo_reportmodules ON jo_report.reportid = jo_reportmodules.reportmodulesid
							WHERE jo_report.reportid = ?";
				$params = array($reportId);

				require_once('includes/utils/GetUserGroups.php');
				        $get_userdetails = get_privileges($userId);
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
				$userGroups->getAllUserGroups($userId);
				$userGroupsList = $userGroups->user_groups;

				if (!empty($userGroupsList) && $currentUser->isAdminUser() == false) {
					$userGroupsQuery = " (shareid IN (" . generateQuestionMarks($userGroupsList) . ") AND setype='groups') OR";
					foreach ($userGroupsList as $group) {
						array_push($params, $group);
					}
				}

				$nonAdminQuery = " jo_report.reportid IN (SELECT reportid from jo_reportsharing
									WHERE $userGroupsQuery (shareid=? AND setype='users'))";
				if ($currentUser->isAdminUser() == false) {
					$ssql .= " AND (($nonAdminQuery)
								OR jo_report.sharingtype = 'Public'
								OR jo_report.owner = ? OR jo_report.owner IN
									(SELECT jo_user2role.userid FROM jo_user2role
									INNER JOIN jo_users ON jo_users.id = jo_user2role.userid
									INNER JOIN jo_role ON jo_role.roleid = jo_user2role.roleid
									WHERE jo_role.parentrole LIKE '$current_user_parent_role_seq::%') 
								OR (jo_report.reportid IN (SELECT reportid FROM jo_report_shareusers WHERE userid = ?))";
					if (!empty($userGroupsList)) {
						$ssql .= " OR (jo_report.reportid IN (SELECT reportid FROM jo_report_sharegroups 
									WHERE groupid IN (" . generateQuestionMarks($userGroupsList) . ")))";
					}
					$ssql .= " OR (jo_report.reportid IN (SELECT reportid FROM jo_report_sharerole WHERE roleid = ?))
							   OR (jo_report.reportid IN (SELECT reportid FROM jo_report_sharers 
								WHERE rsid IN (" . generateQuestionMarks($subordinateRoles) . ")))
							  )";
					array_push($params, $userId, $userId, $userId);
					foreach ($userGroupsList as $groups) {
						array_push($params, $groups);
					}
					array_push($params, $currentUserRoleId);
					foreach ($subordinateRoles as $role) {
						array_push($params, $role);
					}
				}
				$result = $db->pquery($ssql, $params);

				if ($result && $db->num_rows($result)) {
					$reportModulesRow = $db->fetch_array($result);

					// Update information in cache now
					CacheUtils::updateReport_Info(
						$userId,
						$reportId,
						$reportModulesRow["primarymodule"],
						$reportModulesRow["secondarymodules"],
						$reportModulesRow["reporttype"],
						$reportModulesRow["reportname"],
						$reportModulesRow["description"],
						$reportModulesRow["folderid"],
						$reportModulesRow["owner"]
					);
				}

				$subOrdinateUsers = array();

				$subResult = $db->pquery("SELECT userid FROM jo_user2role
									INNER JOIN jo_users ON jo_users.id = jo_user2role.userid
									INNER JOIN jo_role ON jo_role.roleid = jo_user2role.roleid
									WHERE jo_role.parentrole LIKE '$current_user_parent_role_seq::%'", array());

				$numOfSubRows = $db->num_rows($subResult);

				for ($i = 0; $i < $numOfSubRows; $i++) {
					$subOrdinateUsers[] = $db->query_result($subResult, $i, 'userid');
				}

				// Update subordinate user information for re-use
				CacheUtils::updateReport_SubordinateUsers($reportId, $subOrdinateUsers);

				// Re-look at cache to maintain code-consistency below
				$cachedInfo = CacheUtils::lookupReport_Info($userId, $reportId);
			}

			if ($cachedInfo) {
				$this->primodule = $cachedInfo["primarymodule"];
				$this->secmodule = $cachedInfo["secondarymodules"];
				$this->reporttype = $cachedInfo["reporttype"];
				$this->reportname = decode_html($cachedInfo["reportname"]);
				$this->reportdescription = decode_html($cachedInfo["description"]);
				$this->folderid = $cachedInfo["folderid"];
				if ($currentUser->isAdminUser() == true || in_array($cachedInfo["owner"], $subOrdinateUsers) || $cachedInfo["owner"] == $userId) {
					$this->is_editable = true;
				} else {
					$this->is_editable = false;
				}
			}
		}
		return $this;
	}

	function isEditable()
	{
		return $this->is_editable;
	}

	function getModulesList()
	{
		foreach ($this->module_list as $key => $value) {
			if (isPermitted($key, 'index') == "yes") {
				$modules[$key] = vtranslate($key, $key);
			}
		}
		asort($modules);
		return $modules;
	}
}
