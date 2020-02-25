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

class Calendar_Save_Action extends Head_Save_Action {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$actionName = ($record) ? 'EditView' : 'CreateView';
		if(!Users_Privileges_Model::isPermitted($moduleName, $actionName, $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if(!Users_Privileges_Model::isPermitted($moduleName, 'Save', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if ($record) {
			$activityModulesList = array('Calendar', 'Events');
			$recordEntityName = getSalesEntityType($record);

			if (!in_array($recordEntityName, $activityModulesList) || !in_array($moduleName, $activityModulesList)) {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
	}

	public function process(Head_Request $request) {
		global $site_URL;
		$recordModel = $this->saveRecord($request);
		$save_status = $request->get('save_and_new');
		$module_name = $request->get('module');
		$loadUrl = $recordModel->getDetailViewUrl();

		if ($request->get('returntab_label')) {
			$loadUrl = 'index.php?'.$request->getReturnURL();
		} else if($request->get('relationOperation')) {
			$parentModuleName = $request->get('sourceModule');
			$parentRecordId = $request->get('sourceRecord');
			$parentRecordModel = Head_Record_Model::getInstanceById($parentRecordId, $parentModuleName);

			//TODO : Url should load the related list instead of detail view of record
			$loadUrl = $parentRecordModel->getDetailViewUrl();
		} else if ($request->get('returnToList')) {
			$moduleModel = $recordModel->getModule();
			$listViewUrl = $moduleModel->getListViewUrl();

			if ($recordModel->get('visibility') === 'Private') {
				$loadUrl = $listViewUrl;
			} else {
				$userId = $recordModel->get('assigned_user_id');
				$sharedType = $moduleModel->getSharedType($userId);
				if ($sharedType === 'selectedusers') {
					$currentUserModel = Users_Record_Model::getCurrentUserModel();
					$sharedUserIds = Calendar_Module_Model::getCaledarSharedUsers($userId);
					if (!array_key_exists($currentUserModel->id, $sharedUserIds)) {
						$loadUrl = $listViewUrl;
					}
				} else if ($sharedType === 'private') {
					$loadUrl = $listViewUrl;
				}
			}
		} else if ($request->get('returnmodule') && $request->get('returnview')){
			$loadUrl = 'index.php?'.$request->getReturnURL();
		}

		if($save_status == 'true') {
		    if($module_name == 'Events') {
			header ("Location: ".$site_URL."Calendar/view/Edit/mode/".$module_name);
		    } else {
			header ("Location: ".$site_URL.$module_name."/view/Edit/mode/".$module_name);
		    }
		} else {
			header("Location: $loadUrl");
		}
	}

	/**
	 * Function to save record
	 * @param <Head_Request> $request - values of the record
	 * @return <RecordModel> - record Model of saved record
	 */
	public function saveRecord($request) {
		$recordModel = $this->getRecordModelFromRequest($request);
		$recordModel->save();
		if($request->get('relationOperation')) {
			$parentModuleName = $request->get('sourceModule');
			$parentModuleModel = Head_Module_Model::getInstance($parentModuleName);
			$parentRecordId = $request->get('sourceRecord');
			$relatedModule = $recordModel->getModule();
			if($relatedModule->getName() == 'Events'){
				$relatedModule = Head_Module_Model::getInstance('Calendar');
			}
			$relatedRecordId = $recordModel->getId();

			$relationModel = Head_Relation_Model::getInstance($parentModuleModel, $relatedModule);
			$relationModel->addRelation($parentRecordId, $relatedRecordId);
		}
		return $recordModel;
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Head_Request $request
	 * @return Head_Record_Model or Module specific Record Model instance
	 */
	protected function getRecordModelFromRequest(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$moduleModel = Head_Module_Model::getInstance($moduleName);

		if(!empty($recordId)) {
			$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
			$modelData = $recordModel->getData();
			$recordModel->set('id', $recordId);
			$recordModel->set('mode', 'edit');
            //Due to dependencies on the activity_reminder api in Activity.php(5.x)
            $_REQUEST['mode'] = 'edit';
		} else {
			$recordModel = Head_Record_Model::getCleanInstance($moduleName);
			$modelData = $recordModel->getData();
			$recordModel->set('mode', '');
		}

		$fieldModelList = $moduleModel->getFields();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $request->get($fieldName, null);
            // For custom time fields in Calendar, it was not converting to db insert format(sending as 10:00 AM/PM)
            $fieldDataType = $fieldModel->getFieldDataType();
			if($fieldDataType == 'time' && $fieldValue !== null){
				$fieldValue = Head_Time_UIType::getTimeValueWithSeconds($fieldValue);
            }
            // End
            if ($fieldName === $request->get('field')) {
				$fieldValue = $request->get('value');
			}

			if($fieldValue !== null) {
				if(!is_array($fieldValue)) {
					$fieldValue = trim($fieldValue);
				}
				$recordModel->set($fieldName, $fieldValue);
			}
		}

		//Start Date and Time values
		$startTime = Head_Time_UIType::getTimeValueWithSeconds($request->get('time_start'));
		$startDateTime = Head_Datetime_UIType::getDBDateTimeValue($request->get('date_start')." ".$startTime);
		list($startDate, $startTime) = explode(' ', $startDateTime);

		$recordModel->set('date_start', $startDate);
		$recordModel->set('time_start', $startTime);

		//End Date and Time values
		$endTime = $request->get('time_end');
		$endDate = Head_Date_UIType::getDBInsertedValue($request->get('due_date'));

		if ($endTime) {
			$endTime = Head_Time_UIType::getTimeValueWithSeconds($endTime);
			$endDateTime = Head_Datetime_UIType::getDBDateTimeValue($request->get('due_date')." ".$endTime);
			list($endDate, $endTime) = explode(' ', $endDateTime);
		}

		$recordModel->set('time_end', $endTime);
		$recordModel->set('due_date', $endDate);

		$activityType = $request->get('activitytype');
		if(empty($activityType)) {
			$recordModel->set('activitytype', 'Task');
			$recordModel->set('visibility', 'Private');
		}

		//Due to dependencies on the older code
		$setReminder = $request->get('set_reminder');
		if($setReminder) {
			$_REQUEST['set_reminder'] = 'Yes';
		} else {
			$_REQUEST['set_reminder'] = 'No';
		}

		return $recordModel;
	}
}
