<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Leads_Module_Model extends Head_Module_Model {
	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Head_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$links = parent::getSideBarLinks($linkParams);

		$quickLink = array(
			'linktype' => 'SIDEBARLINK',
			'linklabel' => 'LBL_DASHBOARD',
			'linkurl' => $this->getDashBoardUrl(),
			'linkicon' => '',
		);
		
		//Check profile permissions for Dashboards
		$moduleModel = Head_Module_Model::getInstance('Dashboard');
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if($permission) {
			$links['SIDEBARLINK'][] = Head_Link_Model::getInstanceFromValues($quickLink);
		}
		
		return $links;
	}

    /**
	 * Function returns Settings Links
	 * @return Array
	 */
	public function getSettingLinks() {
        global $site_URL;
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$settingLinks = parent::getSettingLinks();
		
		if($currentUserModel->isAdminUser()) {
			$settingLinks[] = array(
					'linktype' => 'LISTVIEWSETTING',
					'linklabel' => 'LBL_CUSTOM_FIELD_MAPPING',
					'linkurl' => $site_URL.'Settings/Leads/MappingDetail',
					'linkicon' => '');
			
		}
		return $settingLinks;
	}

    /**
    * Function returns deleted records condition
    */
    public function getDeletedRecordCondition() {
       return 'jo_crmentity.deleted = 0 AND jo_leaddetails.converted = 0';
    }

    /**
	 * Function to get the list of recently visisted records
	 * @param <Number> $limit
	 * @return <Array> - List of Head_Record_Model or Module Specific Record Model instances
	 */
	public function getRecentRecords($limit=10) {
		$db = PearDatabase::getInstance();

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
        $deletedCondition = $this->getDeletedRecordCondition();
		$query = 'SELECT * FROM jo_crmentity '.
            ' INNER JOIN jo_leaddetails ON
                jo_leaddetails.leadid = jo_crmentity.crmid
                WHERE setype=? AND '.$deletedCondition.' AND modifiedby = ? ORDER BY modifiedtime DESC LIMIT ?';
		$params = array($this->get('name'), $currentUserModel->id, $limit);
		$result = $db->pquery($query, $params);
		$noOfRows = $db->num_rows($result);

		$recentRecords = array();
		for($i=0; $i<$noOfRows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$row['id'] = $row['crmid'];
			$recentRecords[$row['id']] = $this->getRecordFromArray($row);
		}
		return $recentRecords;
	}

	/**
	 * Function returns the Number of Leads created per week
	 * @param type $data
	 * @return <Array>
	 */
	public function getLeadsCreated($owner, $dateFilter) {
		$db = PearDatabase::getInstance();

		$ownerSql = $this->getOwnerWhereConditionForDashBoards($owner);
		if(!empty($ownerSql)) {
			$ownerSql = ' AND '.$ownerSql;
		}
		
		$params = array();
		if(!empty($dateFilter)) {
			$dateFilterSql = ' AND createdtime BETWEEN ? AND ? ';
			//appended time frame and converted to db time zone in showwidget.php
			$params[] = $dateFilter['start'];
			$params[] = $dateFilter['end'];
		}

		$result = $db->pquery('SELECT COUNT(*) AS count, date(createdtime) AS time FROM jo_leaddetails
						INNER JOIN jo_crmentity ON jo_leaddetails.leadid = jo_crmentity.crmid
						AND deleted=0 '.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()).$ownerSql.' '.$dateFilterSql.' AND converted = 0 GROUP BY week(createdtime)',
					$params);

		$response = array();
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$response[$i][1] = $row['time'];
		}
		return $response;
	}

	/**
	 * Function returns Leads grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	public function getLeadsByStatus($owner,$dateFilter) {
		$db = PearDatabase::getInstance();

		$ownerSql = $this->getOwnerWhereConditionForDashBoards($owner);
		if(!empty($ownerSql)) {
			$ownerSql = ' AND '.$ownerSql;
		}
		
		$params = array();
		if(!empty($dateFilter)) {
			$dateFilterSql = ' AND createdtime BETWEEN ? AND ? ';
			//appended time frame and converted to db time zone in showwidget.php
			$params[] = $dateFilter['start'];
			$params[] = $dateFilter['end'];
		}
        if(vtws_isRoleBasedPicklist('leadstatus')) {
            $currentUserModel = Users_Record_Model::getCurrentUserModel();
            $picklistvaluesmap = getAssignedPicklistValues("leadstatus",$currentUserModel->getRole(), $db);
            foreach($picklistvaluesmap as $picklistValue) $params[] = $picklistValue;
        }

		$result = $db->pquery('SELECT COUNT(*) as count, CASE WHEN jo_leadstatus.leadstatus IS NULL OR jo_leadstatus.leadstatus = "" THEN "" ELSE 
						jo_leadstatus.leadstatus END AS leadstatusvalue FROM jo_leaddetails 
						INNER JOIN jo_crmentity ON jo_leaddetails.leadid = jo_crmentity.crmid
						AND deleted=0 AND converted = 0 '.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()). $ownerSql .' '.$dateFilterSql.
						'INNER JOIN jo_leadstatus ON jo_leaddetails.leadstatus = jo_leadstatus.leadstatus 
                        WHERE jo_leaddetails.leadstatus IN ('.generateQuestionMarks($picklistvaluesmap).') 
						GROUP BY leadstatusvalue ORDER BY jo_leadstatus.sortorderid', $params);

		$response = array();
		
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$leadStatusVal = $row['leadstatusvalue'];
			if($leadStatusVal == '') {
				$leadStatusVal = 'LBL_BLANK';
			}
			$response[$i][1] = vtranslate($leadStatusVal, $this->getName());
			$response[$i][2] = $leadStatusVal;
		}
		return $response;
	}

	/**
	 * Function returns Leads grouped by Source
	 * @param type $data
	 * @return <Array>
	 */
	public function getLeadsBySource($owner,$dateFilter) {
		$db = PearDatabase::getInstance();

		$ownerSql = $this->getOwnerWhereConditionForDashBoards($owner);
		if(!empty($ownerSql)) {
			$ownerSql = ' AND '.$ownerSql;
		}
		
		$params = array();
		if(!empty($dateFilter)) {
			$dateFilterSql = ' AND createdtime BETWEEN ? AND ? ';
			//appended time frame and converted to db time zone in showwidget.php
			$params[] = $dateFilter['start'];
			$params[] = $dateFilter['end'];
		}
        if(vtws_isRoleBasedPicklist('leadsource')) {
            $currentUserModel = Users_Record_Model::getCurrentUserModel();
            $picklistvaluesmap = getAssignedPicklistValues("leadsource",$currentUserModel->getRole(), $db);
            foreach($picklistvaluesmap as $picklistValue) $params[] = $picklistValue;
        }
        
		$result = $db->pquery('SELECT COUNT(*) as count, CASE WHEN jo_leaddetails.leadsource IS NULL OR jo_leaddetails.leadsource = "" THEN "" 
						ELSE jo_leaddetails.leadsource END AS leadsourcevalue FROM jo_leaddetails 
						INNER JOIN jo_crmentity ON jo_leaddetails.leadid = jo_crmentity.crmid
						AND deleted=0 AND converted = 0 '.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()). $ownerSql .' '.$dateFilterSql.
						'INNER JOIN jo_leadsource ON jo_leaddetails.leadsource = jo_leadsource.leadsource 
                        WHERE jo_leaddetails.leadsource IN ('.generateQuestionMarks($picklistvaluesmap).') 
						GROUP BY leadsourcevalue ORDER BY jo_leadsource.sortorderid', $params);
		
		$response = array();
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$leadSourceVal =  $row['leadsourcevalue'];
			if($leadSourceVal == '') {
				$leadSourceVal = 'LBL_BLANK';
			}
			$response[$i][1] = vtranslate($leadSourceVal, $this->getName());
			$response[$i][2] = $leadSourceVal;
		}
		return $response;
	}

	/**
	 * Function returns Leads grouped by Industry
	 * @param type $data
	 * @return <Array>
	 */
	public function getLeadsByIndustry($owner,$dateFilter) {
		$db = PearDatabase::getInstance();

		$ownerSql = $this->getOwnerWhereConditionForDashBoards($owner);
		if(!empty($ownerSql)) {
			$ownerSql = ' AND '.$ownerSql;
		}
		
		$params = array();
		if(!empty($dateFilter)) {
			$dateFilterSql = ' AND createdtime BETWEEN ? AND ? ';
			//appended time frame and converted to db time zone in showwidget.php
			$params[] = $dateFilter['start'];
			$params[] = $dateFilter['end'];
		}
        if(vtws_isRoleBasedPicklist('industry')) {
            $currentUserModel = Users_Record_Model::getCurrentUserModel();
            $picklistvaluesmap = getAssignedPicklistValues("industry",$currentUserModel->getRole(), $db);
            foreach($picklistvaluesmap as $picklistValue) $params[] = $picklistValue;
        }
		
		$result = $db->pquery('SELECT COUNT(*) as count, CASE WHEN jo_leaddetails.industry IS NULL OR jo_leaddetails.industry = "" THEN "" 
						ELSE jo_leaddetails.industry END AS industryvalue FROM jo_leaddetails 
						INNER JOIN jo_crmentity ON jo_leaddetails.leadid = jo_crmentity.crmid
						AND deleted=0 AND converted = 0 '.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()). $ownerSql .' '.$dateFilterSql.'
						INNER JOIN jo_industry ON jo_leaddetails.industry = jo_industry.industry 
                        WHERE jo_leaddetails.industry IN ('.generateQuestionMarks($picklistvaluesmap).') 
						GROUP BY industryvalue ORDER BY jo_industry.sortorderid', $params);
		
		$response = array();
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$industyValue = $row['industryvalue'];
			if($industyValue == '') {
				$industyValue = 'LBL_BLANK';
			}
			$response[$i][1] = vtranslate($industyValue, $this->getName());
			$response[$i][2] = $industyValue;
		}
		return $response;
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Head_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule, $relationId) {
		if ($functionName === 'get_activities') {
			$userNameSql = getSqlForNameInDisplayFormat(array('first_name' => 'jo_users.first_name', 'last_name' => 'jo_users.last_name'), 'Users');

			$query = "SELECT CASE WHEN (jo_users.user_name not like '') THEN $userNameSql ELSE jo_groups.groupname END AS user_name,
						jo_crmentity.*, jo_activity.activitytype, jo_activity.subject, jo_activity.date_start, jo_activity.time_start,
						jo_activity.recurringtype, jo_activity.due_date, jo_activity.time_end, jo_activity.visibility, jo_seactivityrel.crmid AS parent_id,
						CASE WHEN (jo_activity.activitytype = 'Task') THEN (jo_activity.status) ELSE (jo_activity.eventstatus) END AS status
						FROM jo_activity
						INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
						LEFT JOIN jo_seactivityrel ON jo_seactivityrel.activityid = jo_activity.activityid
						LEFT JOIN jo_cntactivityrel ON jo_cntactivityrel.activityid = jo_activity.activityid
						LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
						LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
							WHERE jo_crmentity.deleted = 0 AND jo_activity.activitytype <> 'Emails'
								AND jo_seactivityrel.crmid = ".$recordId;

			$relatedModuleName = $relatedModule->getName();
			$query .= $this->getSpecificRelationQuery($relatedModuleName);
			$nonAdminQuery = $this->getNonAdminAccessControlQueryForRelation($relatedModuleName);
			if ($nonAdminQuery) {
				$query = appendFromClauseToQuery($query, $nonAdminQuery);
			}
		} else {
			$query = parent::getRelationQuery($recordId, $functionName, $relatedModule, $relationId);
		}

		return $query;
	}

	/**
	 * Function to get Converted Information for selected records
	 * @param <array> $recordIdsList
	 * @return <array> converted Info
	 */
	public static function getConvertedInfo($recordIdsList = array()) {
		$convertedInfo = array();
		if ($recordIdsList) {
			$db = PearDatabase::getInstance();
			$result = $db->pquery("SELECT converted FROM jo_leaddetails WHERE leadid IN (".generateQuestionMarks($recordIdsList).")", $recordIdsList);
			$numOfRows = $db->num_rows($result);

			for ($i=0; $i<$numOfRows; $i++) {
				$convertedInfo[$recordIdsList[$i]] = $db->query_result($result, $i, 'converted');
			}
		}
		return $convertedInfo;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if (in_array($sourceModule, array('Campaigns', 'Products', 'Services', 'Emails'))) {
			switch ($sourceModule) {
				case 'Campaigns'	: $tableName = 'jo_campaignleadrel';	$fieldName = 'leadid';	$relatedFieldName ='campaignid';	break;
				case 'Products'		: $tableName = 'jo_seproductsrel';		$fieldName = 'crmid';		$relatedFieldName ='productid';		break;
			}

			if ($sourceModule === 'Services') {
				$condition = " jo_leaddetails.leadid NOT IN (SELECT relcrmid FROM jo_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM jo_crmentityrel WHERE relcrmid = '$record') ";
			} elseif ($sourceModule === 'Emails') {
				$condition = ' jo_leaddetails.emailoptout = 0';
			} else {
				$condition = " jo_leaddetails.leadid NOT IN (SELECT $fieldName FROM $tableName WHERE $relatedFieldName = '$record')";
			}

			$position = stripos($listQuery, 'where');
			if($position) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery. ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}

	public function getDefaultSearchField(){
		return "lastname";
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	public function getUtilityActionsNames() {
		return array('Import', 'Export', 'Merge', 'ConvertLead', 'DuplicatesHandling');
	}
}
