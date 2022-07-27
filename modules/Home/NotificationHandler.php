<?php
/*+***********************************************************************************
 * The contents of this file are subject to the Joforce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  Joforce
 * All Rights Reserved.
 *************************************************************************************/
require_once('modules/Home/PushNotificaiton.php');
class NotificationHandler extends VTEventHandler
{
	function handleEvent($eventName, $entityData)
	{
$moduleName = $entityData->getModuleName();
		$allowed_event_handlers = array('jo.entity.aftersave', 'jo.entity.beforedelete', 'jo.entity.afterrestore');
		if (in_array($eventName, $allowed_event_handlers)) {
			if ($moduleName !== 'Users') {
				NotificationHandler::addNotificationForSaveEvent($entityData, $moduleName, $eventName);
			}
		}
	}

	/**
	 * Add Notification to assigned to user
	 */
	static function addNotificationForSaveEvent($entityData, $moduleName, $eventName)
	{
		global $adb, $current_user;
		$current_user_id = $current_user->id;
		$record_id = $entityData->getId();
		$data = $entityData->getData();
		$is_new = $entityData->isNew();
		if (!$is_new) {
			$entityDelta = new EntityDelta();
			$old_assigned_user_id = $entityDelta->getOldValue($entityData->getModuleName(), $entityData->getId(), 'assigned_user_id');
		}

		$user_ids = array();
		$owner_info = getRecordOwnerId($record_id);
		if (isset($owner_info['Groups'])) {
			$group_info = Settings_Groups_Member_Model::getAll();
			foreach ($group_info['Users'] as $single_group) {
				$user_ids[] = str_replace('Users:', '', $single_group->get('id'));
			}
		} else {
			$user_ids[] = $owner_info['Users'];
		}

		$all_followers = getUsersListForStarredRecords($record_id);
		$needed_followers = [];
		if (count($all_followers) > 0) {
			foreach ($all_followers as $follower_id) {
				$notification_permission = getNotificationSettingsForUser($follower_id, $moduleName, 'following');
				if ($notification_permission) {
					array_push($needed_followers, $follower_id);
				}
			}
		}

		$field_and_values = NotificationHandler::getFieldsAndValues($entityData, $moduleName, $eventName);

		$user_ids = array();

		$created_at = gmdate("Y-m-d H:i:s");
		$owner_info = getRecordOwnerId($record_id);
		if (isset($owner_info['Groups'])) {
			$group_info = Settings_Groups_Member_Model::getAll();
			foreach ($group_info['Users'] as $single_group) {
				$user_ids[] = str_replace('Users:', '', $single_group->get('id'));
			}
		} else {
			$user_ids[] = $owner_info['Users'];
		}

		if (count($user_ids) > 1) {
			foreach ($user_ids as $key => $id) {
				$notification_permission = getNotificationSettingsForUser($id, $moduleName, 'assigned');
				if (!$notification_permission) {
					unset($user_ids[$key]);
				}
			}
		}

		if (count($needed_followers) > 0)
			$user_array = array_unique(array_merge($user_ids, $needed_followers));
		else
			$user_array = $user_ids;

		foreach ($user_array as $related_user_id) {
			// No need to notify the owner
			if ($current_user_id == $related_user_id)
				continue;

			foreach ($field_and_values as $action_key => $action_details) {
				$single_action_types = array('deleted', 'restored', 'created_new_record');
				$notification_id = $adb->getUniqueId('jo_notification');
				$query = "INSERT into jo_notification values (?,?,?,?,?,?,?,?,?,?,?,?)";

				if (in_array($action_key, $single_action_types)) {
					$pushnotificationdata = $action_details;
					$value_array = array($notification_id, $current_user_id, $moduleName, $record_id, $related_user_id, 0, $created_at, '', $action_details['status'], '', '', '');
				} elseif ($action_key == 'editing') {
					foreach ($action_details as $edit_action_details) {
						$pushnotificationdata = $edit_action_details;
						$value_array = array($notification_id, $current_user_id, $moduleName, $record_id, $related_user_id, 0, $created_at, '', $edit_action_details['status'], $edit_action_details['fieldname'], $edit_action_details['oldvalue'], $edit_action_details['newvalue']);
					}
				}

				$adb->pquery($query, $value_array);
				#mobile push notification 
				// if (file_exists("user_privileges/notifications/notification_" . $related_user_id . ".php"))
				// 	$file_name = "user_privileges/notifications/notification_" . $related_user_id . ".php";
				// else
				// 	$file_name = 'user_privileges/notifications/default_settings.php';
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

				if ($global_settings == 1) {
					$exits = PushNotificaiton::getnotifyauthtoken($related_user_id);
					if (!empty($exits['token'])) {
						$notificationid = $adb->getUniqueId('jo_notification');
						$notifier_id = $notificationid - 1;
						PushNotificaiton::PushNotificaitontomobile($pushnotificationdata, $related_user_id, $notifier_id);
					}
				}
			}
		}
	}

	static function getFieldsAndValues($entityData, $moduleName, $eventName)
	{
		//Additional features - starts
		global $adb, $current_user;
		$recordId = $entityData->getId();
		$columnFields = $entityData->getData();
		$EntityDelta = new EntityDelta();
		$delta = $EntityDelta->getEntityDelta($moduleName, $recordId, true);
		$newerEntity = $EntityDelta->getNewEntity($moduleName, $recordId);
		$newerColumnFields = $newerEntity->getData();

		$fields_and_values = array();
		if ($eventName == 'jo.entity.aftersave') {
			if (is_array($delta)) {
				$inserted = false;
				foreach ($delta as $fieldName => $values) {
					if ($fieldName != 'modifiedtime' && $fieldName != 'label' && $fieldName != 'modifiedby') {
						if (!$inserted) {
							if ($entityData->isNew()) {
								$status = 'Created';
							} else {
								$status = 'Updated';
								if ($fieldName == 'assigned_user_id') {
									$status = 'Assignee Changed';
								}
							}
							$changedOn = $newerColumnFields['modifiedtime'];
							if ($moduleName == 'Users') {
								$date_var = date("Y-m-d H:i:s");
								$changedOn =  $adb->formatDate($date_var, true);
							}
							$inserted = true;
						}

						if ($status == 'Created') {
							$new_assigned_user_id = $entityData->get('assigned_user_id');
							$modified_by = $entityData->get('modifiedby');

							if ($new_assigned_user_id !== $modified_by) {
								$fields_and_values['created_new_record'] = array('module' => $moduleName, 'recordid' => $recordId, 'status' => 'Created and Assigned', 'time' => date('Y-m-d H:i:s', time()));
							}
						} else {
							if ($values['oldValue'] !== $values['currentValue']) {
								$fields_and_values['editing'][] = array('oldvalue' => $values['oldValue'], 'newvalue' => $values['currentValue'], 'fieldname' => $fieldName, 'module' => $moduleName, 'recordid' => $recordId, 'status' => $status, 'time' => date('Y-m-d H:i:s', time()));
							}
						}
					}
				}
			}
		}
		if ($eventName == 'jo.entity.beforedelete') {
			$fields_and_values['deleted'] = array('module' => $moduleName, 'recordid' => $recordId, 'status' => 'Deleted', 'time' => date('Y-m-d H:i:s', time()));
		}

		if ($eventName == 'jo.entity.afterrestore') {
			$fields_and_values['restored'] = array('module' => $moduleName, 'recordid' => $recordId, 'status' => 'Restored', 'time' => date('Y-m-d H:i:s', time()));
		}

		return $fields_and_values;
	}
}
