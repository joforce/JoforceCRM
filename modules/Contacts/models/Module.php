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

class Contacts_Module_Model extends Head_Module_Model {
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
	 * Function returns the Calendar Events for the module
	 * @param <Head_Paging_Model> $pagingModel
	 * @return <Array>
	 */
	public function getCalendarActivities($mode, $pagingModel, $user, $recordId = false) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();

		if (!$user) {
			$user = $currentUser->getId();
		}

		$nowInUserFormat = Head_Datetime_UIType::getDisplayDateTimeValue(date('Y-m-d H:i:s'));
		$nowInDBFormat = Head_Datetime_UIType::getDBDateTimeValue($nowInUserFormat);
		list($currentDate, $currentTime) = explode(' ', $nowInDBFormat);

		$query = "SELECT jo_crmentity.crmid, crmentity2.crmid AS contact_id, jo_crmentity.smownerid, jo_crmentity.setype, jo_activity.* FROM jo_activity
					INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
					INNER JOIN jo_cntactivityrel ON jo_cntactivityrel.activityid = jo_activity.activityid
					INNER JOIN jo_crmentity AS crmentity2 ON jo_cntactivityrel.contactid = crmentity2.crmid AND crmentity2.deleted = 0 AND crmentity2.setype = ?
					LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid";

		$query .= Users_Privileges_Model::getNonAdminAccessControlQuery('Calendar');

		$query .= " WHERE jo_crmentity.deleted=0
					AND (jo_activity.activitytype NOT IN ('Emails'))
					AND (jo_activity.status is NULL OR jo_activity.status NOT IN ('Completed', 'Deferred'))
					AND (jo_activity.eventstatus is NULL OR jo_activity.eventstatus NOT IN ('Held'))";

		if ($recordId) {
			$query .= " AND jo_cntactivityrel.contactid = ?";
		} elseif ($mode === 'upcoming') {
			$query .= " AND CASE WHEN jo_activity.activitytype='Task' THEN due_date >= '$currentDate' ELSE CONCAT(due_date,' ',time_end) >= '$nowInDBFormat' END";
		} elseif ($mode === 'overdue') {
			$query .= " AND CASE WHEN jo_activity.activitytype='Task' THEN due_date < '$currentDate' ELSE CONCAT(due_date,' ',time_end) < '$nowInDBFormat' END";
		}

		$params = array($this->getName());
		if ($recordId) {
			array_push($params, $recordId);
		}

		if($user != 'all' && $user != '') {
			$query .= " AND jo_crmentity.smownerid = ?";
			array_push($params, $user);
		}

		$query .= " ORDER BY date_start, time_start LIMIT ". $pagingModel->getStartIndex() .", ". ($pagingModel->getPageLimit()+1);

		$result = $db->pquery($query, $params);
		$numOfRows = $db->num_rows($result);
		
		$groupsIds = Head_Util_Helper::getGroupsIdsForUsers($currentUser->getId());
		$activities = array();
		$recordsToUnset = array();
		for($i=0; $i<$numOfRows; $i++) {
			$newRow = $db->query_result_rowdata($result, $i);
			$model = Head_Record_Model::getCleanInstance('Calendar');
			$ownerId = $newRow['smownerid'];
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$visibleFields = array('activitytype','date_start','time_start','due_date','time_end','assigned_user_id','visibility','smownerid','crmid');
			$visibility = true;
			if(in_array($ownerId, $groupsIds)) {
				$visibility = false;
			} else if($ownerId == $currentUser->getId()){
				$visibility = false;
			}
			if(!$currentUser->isAdminUser() && $newRow['activitytype'] != 'Task' && $newRow['visibility'] == 'Private' && $ownerId && $visibility) {
				foreach($newRow as $data => $value) {
					if(in_array($data, $visibleFields) != -1) {
						unset($newRow[$data]);
					}
				}
				$newRow['subject'] = vtranslate('Busy','Events').'*';
			}
			if($newRow['activitytype'] == 'Task') {
				unset($newRow['visibility']);

				$due_date = $newRow["due_date"];
				$dayEndTime = "23:59:59";
				$EndDateTime = Head_Datetime_UIType::getDBDateTimeValue($due_date . " " . $dayEndTime);
				$dueDateTimeInDbFormat = explode(' ', $EndDateTime);
				$dueTimeInDbFormat = $dueDateTimeInDbFormat[1];
				$newRow['time_end'] = $dueTimeInDbFormat;
			}
			
			$model->setData($newRow);
			$model->setId($newRow['crmid']);
			$activities[$newRow['crmid']] = $model;
			if(!$currentUser->isAdminUser() && $newRow['activitytype'] == 'Task' && isToDoPermittedBySharing($newRow['crmid']) == 'no') { 
				$recordsToUnset[] = $newRow['crmid'];
			}
		}
		
		$pagingModel->calculatePageRange($activities);
		if($numOfRows > $pagingModel->getPageLimit()){
			array_pop($activities);
			$pagingModel->set('nextPageExists', true);
		} else {
			$pagingModel->set('nextPageExists', false);
		}
		//after setting paging model, unsetting the records which has no permissions
		foreach ($recordsToUnset as $record) {
			unset($activities[$record]);
		}
		return $activities;
	}

	/**
	 * Function returns query for module record's search
	 * @param <String> $searchValue - part of record name (label column of crmentity table)
	 * @param <Integer> $parentId - parent record id
	 * @param <String> $parentModule - parent module name
	 * @return <String> - query
	 */
	function getSearchRecordsQuery($searchValue, $searchFields, $parentId=false, $parentModule=false) {
		if($parentId && $parentModule == 'Accounts') {
			$query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
						INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
						WHERE deleted = 0 AND jo_contactdetails.accountid = $parentId AND label like '%$searchValue%'";
			return $query;
		} else if($parentId && $parentModule == 'Potentials') {
			$query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
						INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
						LEFT JOIN jo_contpotentialrel ON jo_contpotentialrel.contactid = jo_contactdetails.contactid
						LEFT JOIN jo_potential ON jo_potential.contact_id = jo_contactdetails.contactid
						WHERE deleted = 0 AND (jo_contpotentialrel.potentialid = $parentId OR jo_potential.potentialid = $parentId)
						AND label like '%$searchValue%'";
			
				return $query;
		} else if ($parentId && $parentModule == 'HelpDesk') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_troubletickets ON jo_troubletickets.contact_id = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_troubletickets.ticketid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if($parentId && $parentModule == 'Campaigns') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_campaigncontrel ON jo_campaigncontrel.contactid = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_campaigncontrel.campaignid = $parentId AND label like '%$searchValue%'";

            return $query;
        } else if($parentId && $parentModule == 'Vendors') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_vendorcontactrel ON jo_vendorcontactrel.contactid = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_vendorcontactrel.vendorid = $parentId AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'Quotes') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_quotes ON jo_quotes.contactid = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_quotes.quoteid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'PurchaseOrder') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_purchaseorder ON jo_purchaseorder.contactid = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_purchaseorder.purchaseorderid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'SalesOrder') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_salesorder ON jo_salesorder.contactid = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_salesorder.salesorderid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        } else if ($parentId && $parentModule == 'Invoice') {
            $query = "SELECT ".implode(',',$searchFields)." FROM jo_crmentity
                        INNER JOIN jo_contactdetails ON jo_contactdetails.contactid = jo_crmentity.crmid
                        INNER JOIN jo_invoice ON jo_invoice.contactid = jo_contactdetails.contactid
                        WHERE deleted=0 AND jo_invoice.invoiceid  = $parentId  AND label like '%$searchValue%'";

            return $query;
        }

		return parent::getSearchRecordsQuery($searchValue,$searchFields,$parentId, $parentModule);
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
						jo_cntactivityrel.contactid, jo_seactivityrel.crmid AS parent_id,
						jo_crmentity.*, jo_activity.activitytype, jo_activity.subject, jo_activity.date_start, jo_activity.time_start,
						jo_activity.recurringtype, jo_activity.due_date, jo_activity.time_end, jo_activity.visibility,
						CASE WHEN (jo_activity.activitytype = 'Task') THEN (jo_activity.status) ELSE (jo_activity.eventstatus) END AS status
						FROM jo_activity
						INNER JOIN jo_crmentity ON jo_crmentity.crmid = jo_activity.activityid
						INNER JOIN jo_cntactivityrel ON jo_cntactivityrel.activityid = jo_activity.activityid
						LEFT JOIN jo_seactivityrel ON jo_seactivityrel.activityid = jo_activity.activityid
						LEFT JOIN jo_users ON jo_users.id = jo_crmentity.smownerid
						LEFT JOIN jo_groups ON jo_groups.groupid = jo_crmentity.smownerid
							WHERE jo_cntactivityrel.contactid = ".$recordId." AND jo_crmentity.deleted = 0
								AND jo_activity.activitytype <> 'Emails'";

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
		if (in_array($sourceModule, array('Campaigns', 'Potentials', 'Vendors', 'Products', 'Services', 'Emails'))
				|| ($sourceModule === 'Contacts' && $field === 'contact_id' && $record)) {
			switch ($sourceModule) {
				case 'Campaigns'	: $tableName = 'jo_campaigncontrel';	$fieldName = 'contactid';	$relatedFieldName ='campaignid';	break;
				case 'Potentials'	: $tableName = 'jo_contpotentialrel';	$fieldName = 'contactid';	$relatedFieldName ='potentialid';	break;
				case 'Vendors'		: $tableName = 'jo_vendorcontactrel';	$fieldName = 'contactid';	$relatedFieldName ='vendorid';		break;
				case 'Products'		: $tableName = 'jo_seproductsrel';		$fieldName = 'crmid';		$relatedFieldName ='productid';		break;
			}

			if ($sourceModule === 'Services') {
				$condition = " jo_contactdetails.contactid NOT IN (SELECT relcrmid FROM jo_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM jo_crmentityrel WHERE relcrmid = '$record') ";
			} elseif ($sourceModule === 'Emails') {
				$condition = ' jo_contactdetails.emailoptout = 0';
			} elseif ($sourceModule === 'Contacts' && $field === 'contact_id') {
				$condition = " jo_contactdetails.contactid != '$record'";
			} else {
				$condition = " jo_contactdetails.contactid NOT IN (SELECT $fieldName FROM $tableName WHERE $relatedFieldName = '$record')";
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
    
}