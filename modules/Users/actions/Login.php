<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Users_Login_Action extends Head_Action_Controller {

	function loginRequired() {
		return false;
	}

	function checkPermission(Head_Request $request) {
		return true;
	} 

	function process(Head_Request $request) {
		$username = $request->get('username');
		$password = $request->getRaw('password');

		$user = CRMEntity::getInstance('Users');
		$user->column_fields['user_name'] = $username;

		if ($user->doLogin($password)) {
			session_regenerate_id(true); // to overcome session id reuse.
			global $site_URL;
			$userid = $user->retrieve_user_id($username);
			Head_Session::set('AUTHUSERID', $userid);

			// For Backward compatability
			// TODO Remove when switch-to-old look is not needed
			$_SESSION['authenticated_user_id'] = $userid;
			$_SESSION['app_unique_key'] = vglobal('application_unique_key');
			$_SESSION['authenticated_user_language'] = vglobal('default_language');

			//Enabled session variable for KCFINDER 
			$_SESSION['KCFINDER'] = array(); 
			$_SESSION['KCFINDER']['disabled'] = false; 
			$_SESSION['KCFINDER']['uploadURL'] = "cache/upload"; 
			$_SESSION['KCFINDER']['uploadDir'] = "../cache/upload";
			$deniedExts = implode(" ", vglobal('upload_badext'));
			$_SESSION['KCFINDER']['deniedExts'] = $deniedExts;
			// End

			//Track the login History
			$moduleModel = Users_Module_Model::getInstance('Users');
			$moduleModel->saveLoginHistory($user->column_fields['user_name']);
			//End
		
			if(isset($_SESSION['return_params'])){
				$return_params = $_SESSION['return_params'];
			}

			// Get system setup status
			$moduleName = $request->getModule();
        	        $systemSetUpModel = Users_Record_Model::getCurrentUserModel();
                	$isFirstUser = Users_CRMSetup::isFirstUser($systemSetUpModel);

/*			// Get user setup status
			$userModel = Users_Record_Model::getCurrentUserModel();
        	        $userSetupStatus = $userModel->isFirstTimeLogin($userModel->id);echo "<pre>"; print_r($userSetupStatus);
			
			$default_landing_page = Users_Module_Model::getDefaultLandingPage($userid);
print_r($default_landing_page);die;
			if(!$userSetupStatus){
				if($default_landing_page == "Dashboard")
					header ('Location: index.php?module=Users&parent=Settings&view=SystemSetup');
				else
					header ("Location: ".$site_URL.$default_landing_page."/view/List");
			}
			else{*/
			header ('Location: index.php?module=Users&parent=Settings&view=SystemSetup');

			exit();
		} else {
			header ('Location: index.php?module=Users&parent=Settings&view=Login&error=login');
			exit;
		}
	}

}
