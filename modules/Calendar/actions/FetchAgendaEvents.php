<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

vimport('~~/include/Webservices/Query.php');

class Calendar_FetchAgendaEvents_Action extends Head_BasicAjax_Action {

	public function process(Head_Request $request) {
		$result = array();
		$start = $request->get('startDate');
		$noOfDays = $request->get('numOfDays');
		$dbStartDateOject = DateTimeField::convertToDBTimeZone($start);
		$dbStartDateTime = $dbStartDateOject->format('Y-m-d H:i:s');

		$dbEndDateTime = $this->addDays($dbStartDateTime, $noOfDays);

		$currentUser = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();

		$query = 'SELECT jo_activity.subject, jo_activity.eventstatus, jo_activity.priority ,jo_activity.visibility,
						jo_activity.date_start, jo_activity.time_start, jo_activity.due_date, jo_activity.time_end,
						jo_crmentity.smownerid, jo_activity.activityid, jo_activity.activitytype, jo_activity.recurringtype,
						jo_activity.location FROM jo_activity
						INNER JOIN jo_crmentity ON jo_activity.activityid = jo_crmentity.crmid
						LEFT JOIN jo_users ON jo_crmentity.smownerid = jo_users.id
						LEFT JOIN jo_groups ON jo_crmentity.smownerid = jo_groups.groupid
						WHERE jo_crmentity.deleted=0 AND jo_activity.activityid > 0 AND jo_activity.activitytype NOT IN ("Emails","Task") AND ';

		$hideCompleted = $currentUser->get('hidecompletedevents');
		if ($hideCompleted) {
			$query.= "jo_activity.eventstatus != 'HELD' AND ";
		}
		$query.= " (concat(date_start,'',time_start)) >= '$dbStartDateTime' AND (concat(date_start,'',time_start)) < '$dbEndDateTime'";

		$eventUserId = $currentUser->getId();
		$params = array_merge(array($eventUserId), $this->getGroupsIdsForUsers($eventUserId));

		$query.= " AND jo_crmentity.smownerid IN (".generateQuestionMarks($params).")";
		$query.= ' ORDER BY time_start';

		$queryResult = $db->pquery($query, $params);
		while ($record = $db->fetchByAssoc($queryResult)) {
			$item = array();
			$item['id']				= $record['activityid'];
			$item['visibility']		= $record['visibility'];
			$item['activitytype']	= $record['activitytype'];
			$item['status']			= $record['eventstatus'];
			$item['priority']		= $record['priority'];
			$item['userfullname']	= getUserFullName($record['smownerid']);
			$item['title']			= decode_html($record['subject']);

			$dateTimeFieldInstance = new DateTimeField($record['date_start'].' '.$record['time_start']);
			$userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
			$startDateComponents = explode(' ', $userDateTimeString);

			$item['start'] = $userDateTimeString;
			$item['startDate'] = $startDateComponents[0];
			$item['startTime'] = $startDateComponents[1];

			$dateTimeFieldInstance = new DateTimeField($record['due_date'].' '.$record['time_end']);
			$userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
			$endDateComponents = explode(' ', $userDateTimeString);

			$item['end'] = $userDateTimeString;
			$item['endDate'] = $endDateComponents[0];
			$item['endTime'] = $endDateComponents[1];

			if ($currentUser->get('hour_format') == '12') {
				$item['startTime'] = Head_Time_UIType::getTimeValueInAMorPM($item['startTime']);
				$item['endTime'] = Head_Time_UIType::getTimeValueInAMorPM($item['endTime']);
			}
			$recurringCheck = false;
			if($record['recurringtype'] != '' && $record['recurringtype'] != '--None--') {
				$recurringCheck = true;
			}
			$item['recurringcheck'] = $recurringCheck;
			$result[$startDateComponents[0]][] = $item;
		}

		$response = new Head_Response();
		$response->setResult($result);
		$response->emit();
	}

	public function addDays($datetime, $daysToAdd) {
		$datetime = strtotime($datetime);
		$secondsDelta = 24 * 60 * 60 * $daysToAdd;
		$futureDate = $datetime + $secondsDelta;
		return date("Y-m-d H:i:s", $futureDate);
	}

	protected function getGroupsIdsForUsers($userId) {
		vimport('~~/include/utils/GetUserGroups.php');

		$userGroupInstance = new GetUserGroups();
		$userGroupInstance->getAllUserGroups($userId);
		return $userGroupInstance->user_groups;
	}

}
