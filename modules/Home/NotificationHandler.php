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
		if($moduleName !== 'Users') {
	            NotificationHandler::addNotificationForSaveEvent($entityData, $moduleName);
		}
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

		if(!$is_new){
			$entityDelta = new VTEntityDelta();
        	        $old_assigned_user_id = $entityDelta->getOldValue($entityData->getModuleName(), $entityData->getId(), 'assigned_user_id');
		}

		$new_assigned_user_id = $entityData->get('assigned_user_id');
		$modified_by = $entityData->get('modifiedby');

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

		$all_followers = getUsersListForStarredRecords($record_id);
		$needed_followers = [];
		if(count($all_followers) > 0) {
			foreach($all_followers as $follower_id) {
				$notification_permission = getNotificationSettingsForUser($follower_id, $moduleName, 'following');
				if($notification_permission) {
					array_push($needed_followers, $follower_id);
				}
			}
		}

		$user_ids = array();
		if ($is_new){
			$action_type = 'Created';
		}
		else{
			if($new_assigned_user_id == $old_assigned_user_id)
				$action_type = 'Update';
			else
				$action_type = 'Assignee Changed';
		}

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

		if(count($user_ids) > 1) {
                        foreach($user_ids as $key => $id) {
       	                        $notification_permission = getNotificationSettingsForUser($id, $moduleName, 'assigned');
               	                if(!$notification_permission) {
					unset($user_ids[$key]);
                               	}
                        }
       	        }

		if(count($needed_followers) > 0)
			$user_array = array_unique( array_merge($user_ids, $needed_followers) );
		else
			$user_array = $user_ids;

		foreach($user_array as $related_user_id)	{
			// No need to notify the owner
			if($current_user_id == $related_user_id)
				continue;

				$notification_id = $adb->getUniqueId('jo_notification');
				$query = "INSERT into jo_notification values (?,?,?,?,?,?,?,?,?)";
				$value_array = array($notification_id, $current_user_id, $moduleName, $record_id, $related_user_id, 0, $created_at, '', $action_type);
				$adb->pquery($query, $value_array);	
		}
	}
}
