<?php

class EmailPlus_List_View extends Vtiger_Index_View
{
	public function preProcess (Vtiger_Request $request, $display=true) {
		parent::preProcess($request, false);
		global $adb,$current_user;
                $viewer = $this->getViewer($request);
                $getUserId = $adb->pquery('select * from rc_server_details where user_id = ?', array($current_user->id));
                $userId = $adb->query_result($getUserId, 0, 'user_id');
                $server_name = $adb->query_result($getUserId, 0, 'name');
                $email = $adb->query_result($getUserId, 0, 'email');
                $get_password = $adb->query_result($getUserId, 0, 'password');
                $password = base64_decode($get_password);
		$moduleName = $request->getModule();
		if(!empty($moduleName)) {
			$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
			$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
			$viewer->assign('MODULE', $moduleName);

			if(!$permission) {
				$viewer->assign('MESSAGE', 'LBL_PERMISSION_DENIED');
				$viewer->view('OperationNotPermitted.tpl', $moduleName);
				exit;
			}

			$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
			$linkModels = $moduleModel->getSideBarLinks($linkParams);

			$viewer->assign('QUICK_LINKS', $linkModels);
		}
                $viewer->assign('SERVER', $server_name);
                $viewer->assign('email', $email);
                $viewer->assign('password', $password);
		
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('CURRENT_VIEW', $request->get('view'));
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Vtiger_Request $request) {
		return 'IndexViewPreProcess.tpl';
	}

    public function process(Vtiger_Request $request)
    {
        global $site_URL, $current_user, $adb;
        $user_id = $current_user->id;
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
        $moduleModel = Vtiger_Module_Model::getInstance($moduleName);

        if(!$moduleModel->checkIonCubeLoaded())
        {
            $viewer->view('IoncubeNotAvailable.tpl', $moduleName);
        }
        else
        {
            include_once('modules/EmailPlus/views/ListFunctionality.php');
        }
    }
}
