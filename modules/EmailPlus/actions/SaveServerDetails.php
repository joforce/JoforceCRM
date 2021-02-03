<?php
class EmailPlus_SaveServerDetails_Action extends Head_Save_Action
{
	public function __construct()   {
		
	}

	public function process(Head_Request $request) {

		global $adb, $current_user, $site_URL; 	  

        $selecteduser =$request->get('selecteduser');
        $modulename = $request->get('module');
        $tabid=getTabid($modulename);
        $action =$request->get('optiontopermit');
        require_once('modules/Users/CreateUserPrivilegeFile.php');
        $type =$request->get('enable');
        $selectedrole =$request->get('selectedrole');

        if($type == 'selected_user'){
	        if($action =='users' || $action =='roles' ){
	        	for ($i=0; $i <count($selecteduser) ; $i++) { 
	        		$sql=" UPDATE `jo_profile2tab` SET `permissions`=0 WHERE `profileid` =? and `tabid`= ?";
					$param =array($selecteduser[$i],$tabid);
					$adb->pquery($sql,$param);
					createUserPrivilegesfile($selecteduser[$i]);        		
	        	}	
	        	if($action =='roles'){
	        		$serializedrole=array($selectedrole => $selecteduser);

	        	}else{
	        		$serializeduser =json_encode($selecteduser);
	        	}	        	
	        }
	    } 

		$server_name = $request->get('server');
		$email = $request->get('email');
		$account_type = $request->get('type');
		$port = $request->get('port');
		$password = $request->get('pwd');
		$password_encode = base64_encode($password);
		$getUserId = $adb->pquery('select * from rc_server_details where user_id = ?', array($current_user->id));     

		$userId = $adb->query_result($getUserId, 0, 'user_id');
		$roles =  $adb->query_result($getUserId, 0, 'role');
	
		if(empty($serializeduser))
			$serializeduser =$adb->query_result($getUserId, 0, 'user');
		if(empty($serializedrole)){
			$encodevalrole =$roles;
		}else{
			$getresponse=self::mergeandcheckexitrole($roles ,$serializedrole,$selectedrole);
			$encodevalrole=json_encode($getresponse);			
		}
		if (isset($userId)) {
			$adb->pquery('update rc_server_details set name = ?, email = ?, account_type = ?, port = ?, password = ?,enabletype = ?,user = ?,role =?  where user_id = ?', array($server_name, $email, $account_type, $port, $password_encode,$type,$serializeduser,$encodevalrole,$userId)); 
		}
		else{ 
			$adb->pquery('insert into rc_server_details values(?, ?, ?, ?, ?, ?, ?, ?, ?)', array($current_user->id, $server_name, $email, $password_encode, $account_type, $port,$encodevalrole,$serializeduser,$type));
		}
              header("Location: ".$site_URL."EmailPlus/view/List");
	} 

	function mergeandcheckexitrole($roles ,$serializedrole,$currenrole){

		$response =array();
		$jsonData = stripslashes(html_entity_decode($roles));
        $decoderole=json_decode($jsonData,true);
        $key=array();
    	foreach ($decoderole as $decokey => $decovalue) {
    		$key[]=$decokey;
        	if($decokey==$currenrole){
        		$response[$decokey] =$serializedrole[$decokey];
        	}else{
        		$response[$decokey] =$decovalue;
        	}        	
        }
        if(empty($response)){ 
        	$response =$serializedrole;
        }else{
        	foreach ($serializedrole as $rolekey => $rolevalue) {
        		if(!in_array($rolekey, $key)){
        			$response[$rolekey] =$rolevalue;
        		}
        	}
        }
        return $response;
	}
}
