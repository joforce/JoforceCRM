<?php  

  class PushNotificaiton {

      public function PushNotificaitontomobile($data,$userid,$notificationid){      
        global $current_user;
        $getusertokendetails =PushNotificaiton::getnotifyauthtoken($userid);
        $getusertoken =$getusertokendetails['token'];
        $data['user_id'] = $current_user->id;
        $describtion =self::smackgetdescribtion($data);         
        $notification = [
            'notification_id' =>$notificationid,
            'notification_content' => $describtion,
            'module_name' =>$data['module'], 
            'recordid' => $data['recordid']
        ];  
                           
        if($getusertokendetails['devicetype']=='Android'){
            PushNotificaiton::PushNotificaitonAndroidCurl($getusertoken,$notification); 
        }elseif ($getusertokendetails['devicetype']=='iOS') {
            PushNotificaiton::PushNotificaitoniOSCurl($getusertoken,$notification); 
        } 
      }

      public function smackgetdescribtion($data){
       
        $createdname=ucwords(self::getuserdetails($data['user_id']));
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
                $oldvalue=ucwords(self::getuserdetails($data['oldvalue']));
                $newvalue=ucwords(self::getuserdetails($data['newvalue']));
                $describtion = $createdname.' Changed the Assignee of '.$data['module_name'].' from '.$oldvalue.' to '. $newvalue;

            }else{
                $describtion = $createdname.' Changed the Assignee of '.$data['module_name'].' to you ';
            }
        }  
        return  $describtion;        
    }

    public function getuserdetails($id){
        $moduleModel = \Head_Module_Model::getInstance('Users');
        $recordModel = \Head_Record_Model::getInstanceById($id, $moduleModel);
        return $recordModel->get('user_name');
    }

      public function getnotifyauthtoken($userid){
        global $adb;
        $select_query = "SELECT * FROM `jo_notifyauthtoken` WHERE `userid` = ?";
        $fetch_values = $adb->pquery($select_query, array($userid));
        $getuserdetails=$adb->fetchByAssoc($fetch_values);
        return $getuserdetails;
      }

      public function PushNotificaitonAndroidCurl($token,$notification) {

        $fcmNotification = [ 
            'to'        => $token, //single token
            'data' => $notification,
            'priority'=> 'high',
            'time_to_live' => 600
        ];
      
        $notify=json_encode($fcmNotification);
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS =>json_encode($fcmNotification),
          CURLOPT_HTTPHEADER => array(
            "Authorization: key=AAAAwkJXI-0:APA91bH0aBK_6VZ3kbSFcNYBr6_X63hNXXxaXzCCsxiUhb6HxOmAYNKVsn3LG39YnH552COPpW4mN7Uo8qP1OI-VqgmzUEuGRqbVtFXqo10CrXgpQbtVz8w167WwsGjqovYJ2uXF5dit",
            "Content-Type: application/json"
          ),
        ));
        $response = curl_exec($curl); 
        curl_close($curl);      
        return;  
      }

    public function PushNotificaitoniOSCurl($deviceToken,$notification) {

        $passphrase = 'Joforce@APNS';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'modules/Home/joforce_push_dev.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // $fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); // production
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx); // developement
        echo "<p>Connection Open</p>";
        if(!$fp){
            echo "<p>Failed to connect!<br />Error Number: " . $err . " <br />Code: " . $errstrn . "</p>";
            return;
        } else {
            echo "<p>Sending notification!</p>";
        }

        $body['aps'] = array('alert' => $notification['notification_content'] ,'sound' => 'default','content-available'=>1);
        $body['notification_data'] = array('module_name' => $notification['module_name'], 'record_id' => $notification['recordid'], 'notification_id' => $notification['notification_id']);

        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result)
        echo '<p>Message not delivered ' . PHP_EOL . '!</p>';
        else
        echo '<p>Message successfully delivered ' . PHP_EOL . '!</p>';
        fclose($fp); 
        
        return; 
    }
}

