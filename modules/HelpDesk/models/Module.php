<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ************************************************************************************/

class HelpDesk_Module_Model extends Head_Module_Model {

	/**
	 * Function to get the Quick Links for the module
	 * @param <Array> $linkParams
	 * @return <Array> List of Head_Link_Model instances
	 */
	public function getSideBarLinks($linkParams) {
		$parentQuickLinks = parent::getSideBarLinks($linkParams);

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
			$parentQuickLinks['SIDEBARLINK'][] = Head_Link_Model::getInstanceFromValues($quickLink);
		}

		return $parentQuickLinks;
	}

	/**
	 * Function to get Settings links for admin user
	 * @return Array
	 */
	public function getSettingLinks() {
		$settingsLinks = parent::getSettingLinks();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		if ($currentUserModel->isAdminUser()) {
			$settingsLinks[] = array(
				'linktype' => 'LISTVIEWSETTING',
				'linklabel' => 'LBL_EDIT_MAILSCANNER',
				'linkurl' =>'index.php?parent=Settings&module=MailConverter&view=List',
				'linkicon' => ''
			);
		}
		return $settingsLinks;
	}


	/**
	 * Function returns Tickets grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	public function getOpenTickets() {
		$db = PearDatabase::getInstance();
		//TODO need to handle security
		$params = array();
		if(vtws_isRoleBasedPicklist('ticketstatus')) {
			$currentUserModel = Users_Record_Model::getCurrentUserModel();
			$picklistvaluesmap = getAssignedPicklistValues("ticketstatus",$currentUserModel->getRole(), $db);
			if(in_array('Open', $picklistvaluesmap)) $params[] = 'Open';
		}
		if(count($params) > 0) {
		$result = $db->pquery('SELECT count(*) AS count, COALESCE(jo_groups.groupname,concat(jo_users.first_name, " " ,jo_users.last_name)) as name, COALESCE(jo_groups.groupid,jo_users.id) as id  FROM jo_troubletickets
						INNER JOIN jo_crmentity ON jo_troubletickets.ticketid = jo_crmentity.crmid
						LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid AND jo_users.status="ACTIVE"
						LEFT JOIN jo_groups ON jo_groups.groupid=jo_crmentity.smownerid
						'.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()).
						' WHERE jo_troubletickets.status = ? AND jo_crmentity.deleted = 0 GROUP BY smownerid', $params);
/*              $result = $db->pquery('SELECT count(*) AS count, COALESCE(jo_groups.groupname,concat(jo_users.first_name, " " ,jo_users.last_name)) as name, COALESCE(jo_groups.groupid,jo_users.id) as id, priority FROM jo_troubletickets
                                                INNER JOIN jo_crmentity ON jo_troubletickets.ticketid = jo_crmentity.crmid
                                                LEFT JOIN jo_users ON jo_users.id=jo_crmentity.smownerid AND jo_users.status="ACTIVE"
                                                LEFT JOIN jo_groups ON jo_groups.groupid=jo_crmentity.smownerid
                                                '.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()).
                                                ' WHERE jo_troubletickets.status = ? AND jo_crmentity.deleted = 0 GROUP BY smownerid, priority', $params);*/
		}
		$data = array();
		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
						$row['name'] = decode_html($row['name']);
			$data[] = $row;
		}
		return $data;
	}

	/**
	 * Function returns Tickets grouped by Status
	 * @param type $data
	 * @return <Array>
	 */
	public function getTicketsByStatus($owner, $dateFilter) {
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
		if(vtws_isRoleBasedPicklist('ticketstatus')) {
			$currentUserModel = Users_Record_Model::getCurrentUserModel();
			$picklistvaluesmap = getAssignedPicklistValues("ticketstatus",$currentUserModel->getRole(), $db);
			foreach($picklistvaluesmap as $picklistValue) $params[] = $picklistValue;
		}

		$result = $db->pquery('SELECT COUNT(*) as count, CASE WHEN jo_troubletickets.status IS NULL OR jo_troubletickets.status = "" THEN "" ELSE jo_troubletickets.status END AS statusvalue 
							FROM jo_troubletickets INNER JOIN jo_crmentity ON jo_troubletickets.ticketid = jo_crmentity.crmid AND jo_crmentity.deleted=0
							'.Users_Privileges_Model::getNonAdminAccessControlQuery($this->getName()). $ownerSql .' '.$dateFilterSql.
							' INNER JOIN jo_ticketstatus ON jo_troubletickets.status = jo_ticketstatus.ticketstatus 
							WHERE jo_troubletickets.status IN ('.generateQuestionMarks($picklistvaluesmap).') 
							GROUP BY statusvalue ORDER BY jo_ticketstatus.sortorderid', $params);

		$response = array();

		for($i=0; $i<$db->num_rows($result); $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$response[$i][0] = $row['count'];
			$ticketStatusVal = $row['statusvalue'];
			if($ticketStatusVal == '') {
				$ticketStatusVal = 'LBL_BLANK';
			}
			$response[$i][1] = vtranslate($ticketStatusVal, $this->getName());
			$response[$i][2] = $ticketStatusVal;
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
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if (in_array($sourceModule, array('Assets', 'Project', 'ServiceContracts', 'Services'))) {
			$condition = " jo_troubletickets.ticketid NOT IN (SELECT relcrmid FROM jo_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM jo_crmentityrel WHERE relcrmid = '$record') ";
			$pos = stripos($listQuery, 'where');

			if ($pos) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0] . ' WHERE ' . $split[1] . ' AND ' . $condition;
			} else {
				$overRideQuery = $listQuery . ' WHERE ' . $condition;
			}
			return $overRideQuery;
		}
	}

	 /**
	 * Function to get list of field for header view
	 * @return <Array> list of field models <Head_Field_Model>
	 */
	function getConfigureRelatedListFields(){
		$summaryViewFields = $this->getSummaryViewFieldsList();
		$headerViewFields = $this->getHeaderViewFieldsList();
		$allRelationListViewFields = array_merge($headerViewFields,$summaryViewFields);
		$relatedListFields = array();
		if(count($allRelationListViewFields) > 0) {
			foreach ($allRelationListViewFields as $key => $field) {
				$relatedListFields[$field->get('column')] = $field->get('name');
			}
		}

		if(count($relatedListFields)>0) {
			$nameFields = $this->getNameFields();
			foreach($nameFields as $fieldName){
				if(!$relatedListFields[$fieldName]) {
					$fieldModel = $this->getField($fieldName);
					$relatedListFields[$fieldModel->get('column')] = $fieldModel->get('name');
				}
			}
		}

		return $relatedListFields;
	}
}
