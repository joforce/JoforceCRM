 <?php 

class EmailPlus_FetchUsersDetails_Action extends Head_Action_Controller {

    function checkPermission(Head_Request $request) {
        return;
    }

    public function process(Head_Request $request) {  
      
        global $adb, $current_user, $site_URL;
        $response=array();
        $action =$request->get('fetchdata');

		if($action =='users'){
			$getalluser = Users_Record_Model::getAll();			
			foreach ($getalluser as $userkey => $uservalue) {
		        $last_name =$uservalue->get('last_name');
		        $record_id =$uservalue->get('id');
		        $response[]=  array('name'=>$last_name,'record_id'=>$record_id);
			}
		}elseif ($action =='roles') {
			$getallRoles = Settings_Roles_Record_Model::getAll();
			foreach ($getallRoles as $rolekey => $rolevalue) {
				$rolename=$rolevalue->get('rolename');
				$response[]=array('name'=>$rolename,'record_id'=>$rolekey);		
			}	
		}else {	
	        $query = "SELECT userid,jo_users.first_name,jo_users.last_name FROM jo_user2role INNER JOIN jo_users ON jo_users.id = jo_user2role.userid WHERE roleid = ?" ;
	        $param = array($action);
			$result = $adb->pquery($query,$param);
	        while ($record = $adb->fetchByAssoc($result)) {
	        	$username =$record['first_name']." ".$record['last_name'];
	        	$response[]=array('name'=>$username,'record_id'=>$record['userid']);	
	        }
		}			
        echo Zend_Json::encode($response); 
        die;        
    }
}
