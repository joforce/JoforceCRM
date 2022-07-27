<?php

class EmailPlus_List_View extends Head_Index_View
{
	public function preProcess(Head_Request $request, $display = true)
	{
		parent::preProcess($request, false);
		global $adb, $current_user;
		$viewer = $this->getViewer($request);
		$getUserId = $adb->pquery('select * from rc_server_details where user_id = ?', array($current_user->id));
		$userId = $adb->query_result($getUserId, 0, 'user_id');
		$server_name = $adb->query_result($getUserId, 0, 'name');
		$email = $adb->query_result($getUserId, 0, 'email');
		$get_password = $adb->query_result($getUserId, 0, 'password');
		$password = base64_decode($get_password);
		$moduleName = $request->getModule();
		if (!empty($moduleName)) {
			$moduleModel = Head_Module_Model::getInstance($moduleName);
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
			$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
			$viewer->assign('MODULE', $moduleName);

			if (!$permission) {
				$viewer->assign('MESSAGE', 'LBL_PERMISSION_DENIED');
				$viewer->view('OperationNotPermitted.tpl', $moduleName);
				exit;
			}

			$linkParams = array('MODULE' => $moduleName, 'ACTION' => $request->get('view'));
			$linkModels = $moduleModel->getSideBarLinks($linkParams);

			$viewer->assign('QUICK_LINKS', $linkModels);
		}

		$viewer->assign('SERVER', $server_name);
		$viewer->assign('email', $email);
		$viewer->assign('password', $password);

		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('CURRENT_VIEW', $request->get('view'));
		$viewer->assign('CVNAME', 'Mail Box');
		if ($display) {
			$this->preProcessDisplay($request);
		}
	}

	protected function preProcessTplName(Head_Request $request) {
		return 'IndexViewPreProcess.tpl';
	}

	public function process(Head_Request $request)
	{
		global $site_URL, $current_user, $adb, $application_unique_key;
		$user_id = $current_user->id;
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$hash_key = md5($application_unique_key . date('dFYaG'));

		$url = $site_URL . 'modules/EmailPlus/roundcube/';
		$account = EmailPlus_Autologin_Model::getAutologinUsers();
		$checkUserId = $adb->pquery('select jo_user_id from users where username = ?', array($account['email']));
		$IsUpdated = $adb->query_result($checkUserId, 0, 'jo_user_id');
		if (empty($IsUpdated)) {
			$adb->pquery('update users set jo_user_id = ? where username = ?', array($user_id, $account['email']));
		}

		if ($account) {
			$url .= '?server=' . $account['name'];
			$url .= '&port=' . $account['port'];
			$url .= '&jo_token=' . $hash_key;
			require_once 'modules/EmailPlus/RoundcubeLogin.class.php';
			$rcl = new RoundcubeLogin($url, false);
			try {
				if ($rcl->isLoggedIn()) {
					if ($rcl->getUsername() != $account['email']) {
						$rcl->logout();
						$rc_pass = base64_decode($account['password']);
						$rcl->login($account['email'], $rc_pass);
					}
				} else {
					$rc_pass = base64_decode($account['password']);
					$result = $rcl->login($account['email'], $rc_pass);
					if (empty($result)) {
						$viewer->assign('FAILED', true);
					}
				}
			} catch (RoundcubeLoginException $ex) {
				$log = vglobal('log');
				$log->error('EmailPlus_List_View|RoundcubeLoginException: ' . $ex->getMessage());
			}
		} else {
			header('Location: ' . $site_URL . 'EmailPlus/view/ServerSettings');
		}

		$viewer->assign('URL', $url);
		$viewer->view('List.tpl', $moduleName);
	}
}
