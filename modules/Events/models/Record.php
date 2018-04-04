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

/**
 * Events Record Model Class
 */
class Events_Record_Model extends Calendar_Record_Model {
    
    protected $inviteesDetails;
    
    /**
	 * Function to get the Edit View url for the record
	 * @return <String> - Record Edit View Url
	 */
	public function getEditViewUrl() {
        global $site_URL;
        $module = $this->getModule();
        return $site_URL . 'Calendar/view/'.$module->getEditViewName().'/'.$this->getId();
	}

	/**
	 * Function to get the Delete Action url for the record
	 * @return <String> - Record Delete Action Url
	 */
	public function getDeleteUrl() {
        global $site_URL;
		$module = $this->getModule();
		return $site_URL . 'index.php?module=Calendar&action='.$module->getDeleteActionName().'&record='.$this->getId();
	}

	/**
     * Funtion to get Duplicate Record Url
     * @return <String>
     */
    public function getDuplicateRecordUrl(){
        global $site_URL;
        $module = $this->getModule();
        return $site_URL . 'Calendar/view/'.$module->getEditViewName().'/'.$this->getId().'/Duplicate/true';
    }

    public function getRelatedToContactIdList() {
        $adb = PearDatabase::getInstance();
        $query = 'SELECT * from jo_cntactivityrel where activityid=?';
        $result = $adb->pquery($query, array($this->getId()));
        $num_rows = $adb->num_rows($result);

        $contactIdList = array();
        for($i=0; $i<$num_rows; $i++) {
            $row = $adb->fetchByAssoc($result, $i);
            $contactIdList[$i] = $row['contactid'];
        }
        return $contactIdList;
    }

    public function getRelatedContactInfo() {
        $contactIdList = $this->getRelatedToContactIdList();
        $relatedContactInfo = array();
        foreach($contactIdList as $contactId) {
            $relatedContactInfo[] = array('name' => decode_html(Head_Util_Helper::toSafeHTML(Head_Util_Helper::getRecordName($contactId))) ,'id' => $contactId);
        }
        return $relatedContactInfo;
     }
     
     public function getRelatedContactInfoFromIds($eventIds){
         $adb = PearDatabase::getInstance();
        $query = 'SELECT jo_cntactivityrel.activityid as id, jo_cntactivityrel.contactid, jo_contactdetails.email FROM jo_cntactivityrel INNER JOIN jo_contactdetails
                  ON jo_contactdetails.contactid = jo_cntactivityrel.contactid  WHERE activityid in ('. generateQuestionMarks($eventIds) .')';
        $result = $adb->pquery($query, array($eventIds));
        $num_rows = $adb->num_rows($result);

        $contactInfo = array();
        for($i=0; $i<$num_rows; $i++) {
            $row = $adb->fetchByAssoc($result, $i);
            $contactInfo[$row['id']][] = array('name' => Head_Util_Helper::toSafeHTML(Head_Util_Helper::getRecordName($row['contactid'])),
                                    'email' => $row['email'], 'id' => $row['contactid']);
        }
        return $contactInfo;
     }
     
     /**
      * Funtion to get inviteed details for the event
      * @param <Int> $userId
      * @return <Array> - list with invitees and status details
      */
     public function getInviteesDetails($userId=FALSE) {
         if(!$this->inviteesDetails || $userId) {
            $adb = PearDatabase::getInstance();
            $sql = "SELECT jo_invitees.* FROM jo_invitees WHERE activityid=?";
            $sqlParams = array($this->getId());
            if($userId !== FALSE) {
                $sql .= " AND inviteeid = ?";
                $sqlParams[] = $userId;
            }
            $result = $adb->pquery($sql,$sqlParams);
            $inviteesDetails = array();

            $num_rows = $adb->num_rows($result);
            for($i=0; $i<$num_rows; $i++) {
                $inviteesDetails[$adb->query_result($result, $i,'inviteeid')] = $adb->query_result($result, $i,'status');
            }
            
            if(!$userId) {
                $this->inviteesDetails = $inviteesDetails;
            }
            return $inviteesDetails;
         }
         return $this->inviteesDetails;
     }
     
     /**
      * Function to get list of invitees id's
      * @return <Array> - List of invitees id's
      */
     public function getInvities() {
         return array_keys($this->getInviteesDetails());
     }
     
     /**
      * Function to update invitation status
      * @param <Int> $activityId
      * @param <Int> $userId
      * @param <String> $status
      */
     public function updateInvitationStatus($activityId,$userId,$status) {
         $adb = PearDatabase::getInstance();
         $sql = 'UPDATE jo_invitees SET status = ? WHERE activityid = ? AND inviteeid = ?';
         $adb->pquery($sql,array($status,$activityId,$userId));
         $this->inviteesDetails = NULL;
     }

     public function getInviteUserMailData() {
            $adb = PearDatabase::getInstance();

            $return_id = $this->getId();
            $cont_qry = "select * from jo_cntactivityrel where activityid=?";
            $cont_res = $adb->pquery($cont_qry, array($return_id));
            $noofrows = $adb->num_rows($cont_res);
            $cont_id = array();
            if($noofrows > 0) {
                for($i=0; $i<$noofrows; $i++) {
                    $cont_id[] = $adb->query_result($cont_res,$i,"contactid");
                }
            }
            $cont_name = '';
            foreach($cont_id as $key=>$id) {
                if($id != '') {
                    $contact_name = Head_Util_Helper::getRecordName($id);
                    $cont_name .= $contact_name .', ';
                }
            }

			$parentId = $this->get('parent_id');
			$parentName = '';
			if($parentId != '') {
				$parentName = Head_Util_Helper::getRecordName($parentId);
			}
			
            $cont_name  = trim($cont_name,', ');
            $mail_data = Array();
            $mail_data['user_id'] = $this->get('assigned_user_id');
            $mail_data['subject'] = $this->get('subject');
            $moduleName = $this->getModuleName();
            $mail_data['status'] = (($moduleName=='Calendar')?($this->get('taskstatus')):($this->get('eventstatus')));
            $mail_data['activity_mode'] = (($moduleName=='Calendar')?('Task'):('Events'));
            $mail_data['taskpriority'] = $this->get('taskpriority');
            $mail_data['relatedto'] = $parentName;
            $mail_data['contact_name'] = $cont_name;
            $mail_data['description'] = $this->get('description');
            $mail_data['assign_type'] = $this->get('assigntype');
            $mail_data['group_name'] = getGroupName($this->get('assigned_user_id'));
            $mail_data['mode'] = $this->get('mode');
            //TODO : remove dependency on request;
            $value = getaddEventPopupTime($_REQUEST['time_start'],$_REQUEST['time_end'],'24');
            $start_hour = $value['starthour'].':'.$value['startmin'].''.$value['startfmt'];
            if($_REQUEST['activity_mode']!='Task')
                $end_hour = $value['endhour'] .':'.$value['endmin'].''.$value['endfmt'];
            $startDate = new DateTimeField($_REQUEST['date_start']." ".$start_hour);
            $endDate = new DateTimeField($_REQUEST['due_date']." ".$end_hour);
            $mail_data['st_date_time'] = $startDate->getDBInsertDateTimeValue();
            $mail_data['end_date_time'] = $endDate->getDBInsertDateTimeValue();
            $mail_data['location']=$this->get('location');
            return $mail_data;
     }
}
