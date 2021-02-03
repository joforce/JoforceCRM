<?php

namespace Joforce;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class JoNotificationHelper
{
    private $db;

    private $user;

    private $module_fields_info;

    private $records_per_page = 20;

    /**
     * JoHelper constructor.
     *
     * @param $adb
     * @param $user
     */
    public function __construct($adb, $user)
    {
        $this->db = $adb;

        $this->user = $user;
    }

    public function Notification($request_data)
    {   
        if($request_data['mode'] =="Read" || $request_data['mode'] =="ReadAll" || $request_data['action']=='list'){ 
            $notificationstatus=self::notificationstatus($request_data['userid']);
            if($notificationstatus == false){
                return array('success' => false, 'message' => 'Notification is off mode');
            }
        }
        
        if($request_data['mode']=='Read'){
            $data =$this->Readnotification($request_data);
        }elseif($request_data['mode']=='ReadAll'){  
            $data =$this->ReadAllnotification($request_data);
        }elseif($request_data['action'] == 'notifystatus') {
            $data =$this->Notificationenable($request_data);
        }elseif($request_data['action']=='list'){  
            $data =$this->getAllnotify($request_data['userid']);
        }elseif($request_data['action']=='Register'){  
            $data =$this->Registernotification($request_data);
        } 
        return $data;
    }

    public function getAllnotify($userid){
        
        $user_notifications = getUserModuleNotifications('All', $userid, true);
        $splitnotices = [];
        for ($i=0; $i <count($user_notifications); $i++) { 
            $describtion =$this->notificationdescribtion($user_notifications[$i]);
            $user_notify[$i]=array(
                "notification_id"=> $user_notifications[$i]['id'],
                "notifier_id"=> $user_notifications[$i]['notifier_id'],
                "notification_content"=>$describtion,
                "module_name" => $user_notifications[$i]['module_name'],
                "recordid"=> $user_notifications[$i]['entity_id'],
                "is_seen" => $user_notifications[$i]['is_seen']
            );
        }

        foreach($user_notify as $key=>$value){
            $group = $value['is_seen'];
            if(!isset($splitnotices[$group])) $splitnotices[$group ] = [];

            $splitnotices[$group][] = $value;
        } 
    
        $notificationsection=array(
               array("section_name"=> "Unread","notifications"=>$splitnotices[0]) ,
               array("section_name"=> "Read","notifications"=>$splitnotices[1])
        );      
        $data = array('notification_sections' => $notificationsection);
        $allnotice =array('success' => true, 'data' => $data);
        return $allnotice;   

    }

    public function Readnotification($request_data){
        global $adb;
        $current_date = gmdate("Y-m-d H:i:s");
        $query = "UPDATE jo_notification SET is_seen = ? , updated_at = ? WHERE id = ?";
        $value_array = array(1, $current_date, $request_data['notification_id']);
        $result = $adb->pquery($query, $value_array);
        return array('success' => true);
    }

    public function ReadAllnotification($request_data) {  
        global $adb, $current_user; 
       
        $current_user_id = $request_data['userid']; 

        $select_query = "SELECT * FROM jo_notification WHERE notifier_id = ? and is_seen=?";
        $select_array = array($current_user_id, 0);
        $fetch_values = $adb->pquery($select_query, $select_array);

        $notification_id_array = [];
        while($fetch_array = $adb->fetch_array($fetch_values)){
        array_push($notification_id_array, $fetch_array['id']);
        }

        $current_date = gmdate("Y-m-d H:i:s");
        foreach($notification_id_array as $notification_id) {
            $query = "UPDATE jo_notification SET is_seen = ? , updated_at = ? WHERE id = ?";
            $value_array = array(1, $current_date, $notification_id);
            $result = $adb->pquery($query, $value_array);
        } 

        return array('success' => true);
    }  

    public function Notificationenable($request_data){

        $user_id = $request_data['userid'];       
        $global_notification_settings = $request_data['notificationenable'];
        if(file_exists("user_privileges/notifications/notification_".$user_id.".php"))
            $file_name = "user_privileges/notifications/notification_".$user_id.".php";
            else
                $file_name = 'user_privileges/notifications/default_settings.php';

        require($file_name);
        $file_path ="user_privileges/notifications/notification_".$user_id.".php"; 
       
        if($global_notification_settings == 1) {
            $global_settings = true;        
            $myfile = fopen($file_path, "w"); 
                $write_status = fwrite($myfile, "<?php
            ".'$global_settings'." = true;
                    ".'$notification_settings'." = " .var_export($notification_settings, true). ";
            ?>");
            if($write_status) {
                $message =array('success' => true,'Message' => 'Settings are saved successfully.');            
            } else {
                $message =array('success' => false,'Message' => 'Settings are not saved. No writable permission for notifications folder.');
            }
        } else {  
            $myfile = fopen($file_path, "w");
            $write_status = fwrite($myfile, "<?php
                ".'$global_settings'." = false;
                        ".'$notification_settings'." = " .var_export($notification_settings, true). ";
                ?>");            
            if($write_status) {
                $message =array('success' => true,'Message' => 'Settings are saved successfully.');
            } else {
                $message =array('success' => false,'Message' => 'Settings are not saved. No writable permission for notifications folder.');
            }
        }
        fclose($myfile);
        return $message;
    }

    public function Registernotification($request_data){ 

        global $adb;
        $exists=self::checknotifytokenexits($request_data['userid']);
        if(!empty($exists)){
            $sql = "UPDATE `jo_notifyauthtoken` SET `token`=?,`devicetype`=? WHERE `userid`=?";
            $adb->pquery($sql,array($request_data['notifytoken'],$request_data['devicetype'],$request_data['userid']));   
        }else{
            $sql = "insert into jo_notifyauthtoken(userid,token,devicetype) values (?,?,?)";
            $adb->pquery($sql,array($request_data['userid'],$request_data['notifytoken'],$request_data['devicetype']));
        }

        return array('success' => true);      
    }

    public function checknotifytokenexits($userid){ 
        global $adb;
        $select_query = "SELECT * FROM `jo_notifyauthtoken` WHERE `userid` = ?";
        $fetch_values = $adb->pquery($select_query, array($userid));
        $getuserdetails=$adb->fetchByAssoc($fetch_values);
        return $getuserdetails;
    }

    public function DeletenotificationToken($userid){

        global $adb;
        $sql = "DELETE FROM `jo_notifyauthtoken` WHERE userid=?";
        $adb->pquery($sql,array($userid));
        return array('success' => true);      
    }

    public function notificationstatus($user_id){

        if(file_exists("user_privileges/notifications/notification_".$user_id.".php"))
        $file_name = "user_privileges/notifications/notification_".$user_id.".php";
        else
           $file_name = 'user_privileges/notifications/default_settings.php';
        require($file_name); 
        return $global_settings;

    }

    public function notificationdescribtion($data){
       
        $createdname=ucwords(self::getuserdetailsbyid($data['user_id']));
        if(empty($data['module_name']))
            $data['module_name']=$data['module'];
    
        if($data['action_type'] =='Created' || $data['status'] =='Created' ){
            $describtion = $createdname.' '.$data['action_type'].' a '.$data['module_name'];
        }elseif ($data['action_type'] == 'Created and Assigned' || $data['status'] =='Created and Assigned') {
            $describtion = $createdname.' Created and Assigned a '.$data['module_name']. ' to You'; 
        }elseif ($data['action_type'] == 'Updated' || $data['status'] == 'Updated') {
            $describtion =$createdname.' '.$data['action_type'] .' the '.$data['module_name'];
        }elseif ($data['action_type'] == 'Assignee Changed' || $data['status'] == 'Assignee Changed') {
            if(!empty($data['newvalue'])){
                $oldvalue=ucwords(self::getuserdetailsbyid($data['oldvalue']));
                $newvalue=ucwords(self::getuserdetailsbyid($data['newvalue']));
                $describtion = $createdname.' Changed the Assignee of '.$data['module_name'].' from '.$oldvalue.' to '. $newvalue;

            }else{
                $describtion = $createdname.' Changed the Assignee of '.$data['module_name'].' to you ';
            }
        }  
        return  $describtion;        
    }

    public function getuserdetailsbyid($id){
        $moduleModel = \Head_Module_Model::getInstance('Users');
        $recordModel = \Head_Record_Model::getInstanceById($id, $moduleModel);
        return $recordModel->get('user_name');
    }
}