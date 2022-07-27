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
require_once 'modules/WSAPP/Handlers/vtigerCRMHandler.php';
require_once 'includes/utils/GetUserGroups.php';


class OutlookHeadCRMHandler extends vtigerCRMHandler{
    
    public function translateReferenceFieldNamesToIds($entityRecords,$user){
        $entityRecordList = array();
        foreach($entityRecords as $index=>$record){
            $entityRecordList[$record['module']][$index] = $record;
        }
        foreach($entityRecordList as $module=>$records){
            $handler = vtws_getModuleHandlerFromName($module, $user);
            $meta = $handler->getMeta();
            $referenceFieldDetails = $meta->getReferenceFieldDetails();

            foreach($referenceFieldDetails as $referenceFieldName=>$referenceModuleDetails){
                $recordReferenceFieldNames = array();
                foreach($records as $index=>$recordDetails){
                    if(!empty($recordDetails[$referenceFieldName])) {
                    	$recordReferenceFieldNames[] = trim($recordDetails[$referenceFieldName]);
                    }
                }
                $entityNameIds = wsapp_getRecordEntityNameIds(array_values($recordReferenceFieldNames), $referenceModuleDetails, $user);
                if(is_array($entityNameIds))
                    $entityNameIds = array_change_key_case($entityNameIds, CASE_LOWER);
                foreach($records as $index=>$recordInfo){
                    $refFieldValue = strtolower(trim($recordInfo[$referenceFieldName]));
                    if(!empty($entityNameIds[$refFieldValue])){
                        $recordInfo[$referenceFieldName] = $entityNameIds[$refFieldValue];
                    } else {
                        if($referenceFieldName == 'account_id'){
                            if($recordInfo[$referenceFieldName]!=NULL){
                                $element['accountname'] = trim($recordInfo[$referenceFieldName]);
                                $element['assigned_user_id'] = vtws_getWebserviceEntityId('Users', $user->id);
                                $element['source'] = Head_Cache::get('WSAPP','appName');
                                $element['module'] = "Accounts";
                                $createRecord= array($element);
                                $createRecord = $this->fillNonExistingMandatoryPicklistValues($createRecord);
                                $createRecord = $this->fillMandatoryFields($createRecord, $user);
                                /**
                                 * It'll loop only once. Still we need to loop because to fill mandatory values we need
                                 * array of records
                                 */
                                foreach ($createRecord as $key => $record) {
                                	$result = vtws_create($record['module'], $record, $user);
                                    $entityNameIds[$refFieldValue] = $result['id'];
                                }
                                $recordInfo[$referenceFieldName] = $entityNameIds[$refFieldValue];
                            }
                        }
                        else{
                            $recordInfo[$referenceFieldName] = "";
                        }
                    }
                    $records[$index] = $recordInfo;
                }
            }
            $entityRecordList[$module] = $records;
        }

        $crmRecords = array();
        foreach($entityRecordList as $module=>$entityRecords){
            foreach($entityRecords as $index=>$record){
                $crmRecords[$index] = $record;
            }
        }
        return $crmRecords;
    }
   
    
    public function translateTheReferenceFieldIdsToName($records, $module, $user) {
        $db = PearDatabase::getInstance();
        global $current_user;
        $current_user = $user;
        $handler = vtws_getModuleHandlerFromName($module, $user);
        $meta = $handler->getMeta();
        $referenceFieldDetails = $meta->getReferenceFieldDetails();
        foreach ($referenceFieldDetails as $referenceFieldName => $referenceModuleDetails) {
            if($module == 'Events' && $referenceFieldName == "contact_id"){
                // to set all related Contacts of Event records
                foreach($records as $index => $record){
                    $id = $record['id'];
                    $idComp = vtws_getIdComponents($id);
                   $recordIds[] =  $idComp[1];
                }
                $eventRecordModel = new Events_Record_Model();
                $contactsInfos =  $eventRecordModel->getRelatedContactInfoFromIds($recordIds);

                foreach($records as $index => $record){
                    $id = $record['id'];
                    $idComp = vtws_getIdComponents($id);
                    if($contactsInfos[$idComp[1]]){
                        $records[$index]['attendees'] = $contactsInfos[$idComp[1]];
                    }
                }
            }else{
                $referenceFieldIds = array();
                $referenceModuleIds = array();
                $referenceIdsName = array();
                foreach ($records as $recordDetails) {
                    $referenceWsId = $recordDetails[$referenceFieldName];
                    if (!empty($referenceWsId)) {
                        $referenceIdComp = vtws_getIdComponents($referenceWsId);
                        $webserviceObject = HeadWebserviceObject::fromId($db, $referenceIdComp[0]);
                        if ($webserviceObject->getEntityName() == 'Currency') {
                            continue;
                        }
                        $referenceModuleIds[$webserviceObject->getEntityName()][] = $referenceIdComp[1];
                        $referenceFieldIds[] = $referenceIdComp[1];
                    }
                }

                foreach ($referenceModuleIds as $referenceModule => $idLists) {
                    $nameList = getEntityName($referenceModule, $idLists);
                    foreach ($nameList as $key => $value)
                        $referenceIdsName[$key] = $value;
                }
                $recordCount = count($records);
                for ($i = 0; $i < $recordCount; $i++) {
                    $record = $records[$i];
                    if (!empty($record[$referenceFieldName])) {
                        $wsId = vtws_getIdComponents($record[$referenceFieldName]);
                        $record[$referenceFieldName] = decode_html($referenceIdsName[$wsId[1]]);
                    }
                    $records[$i] = $record;
                }
            }
        }
        return $records;
    }
     /*
     * Function to attach outlook attendees to Contacts of Head Event
     */
    public function relateEventandContacts($Event){
        $contactids = array();
        foreach($Event['attendees'] as $attendee){
            $searchModule = "Contacts";
            $searchModuleLabel = vtranslate($searchModule, $searchModule);
            $emailsModule = new Emails_Module_Model();
            $result = $emailsModule->searchEmails($attendee,$searchModule);
            if($result[$searchModuleLabel]){
                $keys = array_keys($result[$searchModuleLabel]);
                $contactids = array_merge($contactids, $keys);
            }
        }
        $contactids = array_values(array_unique($contactids));
        if($contactids){
            $_REQUEST['contactidlist'] = implode(';', $contactids);
            $Event['contact_id_display'] = implode(',',$contactids);
        }
        unset($Event['attendees']);
        return $Event;
    }
    
    /*
     * Function overriden to handle duplication
     */
      public function put($recordDetails, $user) {
        global $log;
		$this->user = $user;
		$recordDetails = $this->syncToNativeFormat($recordDetails);
		$createdRecords = $recordDetails['created'];
		$updatedRecords = $recordDetails['updated'];
		$deletedRecords = $recordDetails['deleted'];


		if (count($createdRecords) > 0) {
			$createdRecords = $this->translateReferenceFieldNamesToIds($createdRecords, $user);
			$createdRecords = $this->fillNonExistingMandatoryPicklistValues($createdRecords);
			$createdRecords = $this->fillMandatoryFields($createdRecords, $user);
		}
		foreach ($createdRecords as $index => $record) {
            if($record['module'] == "Events" && isset($record['attendees'])){
                $record = $this->relateEventandContacts($record);
            }
			$createdRecords[$index] = vtws_create($record['module'], $record, $this->user);
		}

		if (count($updatedRecords) > 0) {
			$updatedRecords = $this->translateReferenceFieldNamesToIds($updatedRecords, $user);
            $updatedRecords = $this->removeMandatoryEmptyFields($updatedRecords, $user);
		}

		$crmIds = array();

		foreach ($updatedRecords as $index => $record) {
			$webserviceRecordId = $record["id"];
			$recordIdComp = vtws_getIdComponents($webserviceRecordId);
			$crmIds[] = $recordIdComp[1];
		}
		$assignedRecordIds = array();
		if ($this->isClientUserSyncType()) {
			$assignedRecordIds = wsapp_checkIfRecordsAssignToUser($crmIds, $this->user->id);
            // To check if the record assigned to group
            if ($this->isClientUserAndGroupSyncType()) {
                $getUserGroups = new GetUserGroups();
                $getUserGroups->getAllUserGroups($this->user->id);
                $groupIds = $getUserGroups->user_groups;
                if(!empty($groupIds)){
                    $groupRecordId = wsapp_checkIfRecordsAssignToUser($crmIds, $groupIds);
                    $assignedRecordIds = array_merge($assignedRecordIds, $groupRecordId);
                }
            }
            // End
        }
		foreach ($updatedRecords as $index => $record) {
            if($record['module'] == "Events" && isset($record['attendees'])){
                $record = $this->relateEventandContacts($record);
            }
			$webserviceRecordId = $record["id"];
			$recordIdComp = vtws_getIdComponents($webserviceRecordId);
			try {
				if (in_array($recordIdComp[1], $assignedRecordIds)) {
					$updatedRecords[$index] = vtws_revise($record, $this->user);
				} else if (!$this->isClientUserSyncType()) {
					$updatedRecords[$index] = vtws_revise($record, $this->user);
				} else {
					$this->assignToChangedRecords[$index] = $record;
				}
			} catch (Exception $e) {
				continue;
			}
            // Added to handle duplication
            if($record['duplicate']){
                $updatedRecords[$index]['duplicate'] = true;
            }
            // End
		}
		$hasDeleteAccess = null;
		$deletedCrmIds = array();
		foreach ($deletedRecords as $index => $record) {
			$webserviceRecordId = $record;
			$recordIdComp = vtws_getIdComponents($webserviceRecordId);
			$deletedCrmIds[] = $recordIdComp[1];
		}
		$assignedDeletedRecordIds = wsapp_checkIfRecordsAssignToUser($deletedCrmIds, $this->user->id);
        
        // To get record id's assigned to group of the current user
        if ($this->isClientUserAndGroupSyncType()) {
            if(!empty($groupIds)){
                foreach ($groupIds as $group) {
                    $groupRecordId = wsapp_checkIfRecordsAssignToUser($deletedCrmIds, $group);
                    $assignedDeletedRecordIds = array_merge($assignedDeletedRecordIds, $groupRecordId);
                }
            }
        }
        // End
        
		foreach ($deletedRecords as $index => $record) {
			$idComp = vtws_getIdComponents($record);
			if (empty($hasDeleteAccess)) {
				$handler = vtws_getModuleHandlerFromId($idComp[0], $this->user);
				$meta = $handler->getMeta();
				$hasDeleteAccess = $meta->hasDeleteAccess();
			}
			if ($hasDeleteAccess) {
				if (in_array($idComp[1], $assignedDeletedRecordIds)) {
					try {
						vtws_delete($record, $this->user);
					} catch (Exception $e) {
						continue;
					}
				}
			}
		}

		$recordDetails['created'] = $createdRecords;
		$recordDetails['updated'] = $updatedRecords;
		$recordDetails['deleted'] = $deletedRecords;
		return $this->nativeToSyncFormat($recordDetails);
	}
    
    /**
     * Function to remove empty mandatory fields from record as vtws_revise will
     * fail if we have empty mandaoty fields in record
     * @global type $adb
     * @param type $records
     * @param type $user
     * @return $records
     */
    function removeMandatoryEmptyFields($records,$user){
        foreach ($records as $index => $record) {
            $moduleHandler = vtws_getModuleHandlerFromName($record['module'],$user);
            $meta = $moduleHandler->getMeta();
            $mandatoryFields = $meta->getMandatoryFields();
            $updateFields = array_keys($record);
            $updateMandatoryFields = array_intersect($updateFields, $mandatoryFields);
            if(!empty($updateMandatoryFields)){
                foreach($updateMandatoryFields as $ind=>$field){
                    if( !isset($record[$field]) || $record[$field] === "" || $record[$field] === null ){
                        unset($records[$index][$field]);
                    }
                }
            }
        }
        return $records;
    }
    
}

?>