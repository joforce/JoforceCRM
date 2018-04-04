<?php
/*+***********************************************************************************
 * The contents of this file are subject to the Joforce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  Joforce
 * All Rights Reserved.
 *************************************************************************************/

class NotificationHandler extends VTEventHandler 
{
    function handleEvent($eventName, $entityData) {
        $moduleName = $entityData->getModuleName();
        if ($eventName == 'vtiger.entity.aftersave') {
            NotificationHandler::addNotificationForSaveEvent($entityData, $moduleName);
        }
    }

	/**
	 * Add Notification to assigned to user
	 */
	static function addNotificationForSaveEvent($entityData, $moduleName)
	{
		global $adb, $current_user;
		$current_user_id = $current_user->id;
		$record_id = $entityData->getId();
		$data = $entityData->getData();
		$is_new = $entityData->isNew();

		$user_ids = array();
		$owner_info = getRecordOwnerId($record_id);
		if(isset($owner_info['Groups']))	{
			$group_info = Settings_Groups_Member_Model::getAll();
			foreach($group_info['Users'] as $single_group)	{
				$user_ids[] = str_replace('Users:', '', $single_group->get('id'));
			}
		}
		else	{
			$user_ids[] = $owner_info['Users'];
		}

		$user_ids = array();
		// If owner and assigned_user_id is same, skip it
		if($data['assigned_user_id'] != $data['modifiedby'])	{
			$action_type = 'Updated';
			if ($is_new)
				$action_type = 'Created';

			$created_at = gmdate("Y-m-d H:i:s");

			$owner_info = getRecordOwnerId($record_id);
			if(isset($owner_info['Groups']))	{
				$group_info = Settings_Groups_Member_Model::getAll();
				foreach($group_info['Users'] as $single_group)	{
					$user_ids[] = str_replace('Users:', '', $single_group->get('id'));
				}
			}
			else	{
				$user_ids[] = $owner_info['Users'];
			}

			foreach($user_ids as $user_id)	{
				// No need to notify the owner
				if($current_user_id == $user_id)
					continue;

				$checkUserNotified = $adb->pquery('select id from jo_notification where entity_id = ? and notifier_id = ?', array($record_id, $user_id));
				$count = $adb->num_rows($checkUserNotified);
				if($adb->num_rows($checkUserNotified) > 0)	{
					$notification_id = $adb->query_result($checkUserNotified, 0, 'id');
					$adb->pquery('update jo_notification set updated_at = ? where id = ?', array($created_at, $notification_id));
				}
				else	{
					$notification_id = $adb->getUniqueId('jo_notification');
					$query = "INSERT into jo_notification values (?,?,?,?,?,?,?,?,?)";
					$value_array = array($notification_id, $current_user_id, $moduleName, $record_id, $user_id, 0, $created_at, '', $action_type);
					$adb->pquery($query, $value_array);	
				}
			}
		}
	}
}
