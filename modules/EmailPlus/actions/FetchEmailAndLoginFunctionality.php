<?php
			$user_id = $current_user->id;
			$recordId = $request->get('recordId');
			$module = $request->get('moduleName');
			$getUserId = $adb->pquery('select * from rc_server_details where user_id = ?', array($current_user->id));
			if(($adb->num_rows($getUserId)) == 0){
				$response = new Head_Response();
				$response->setResult('Failed');
				$response->emit();
				die;
			}
			else{
				$url = $site_URL . 'modules/EmailPlus/roundcube/';
				$config['autologinActive'] = true;
				if ($config['autologinActive'] == 'true') {
					$account = EmailPlus_Autologin_Model::getAutologinUsers();
					$checkUserId = $adb->pquery('select jo_user_id from users where username = ?', array($account['email']));
					$IsUpdated  = $adb->query_result($checkUserId, 0, 'jo_user_id');
					if(empty($IsUpdated)){
						$adb->pquery('update users set jo_user_id = ? where username = ?', array($user_id, $account['email']));
					}
					if ($account) {
						$url .= '?server='.$account['name'];
						$url .= '&port='.$account['port'];
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
								$rcl->login($account['email'], $rc_pass);
							}
						} catch (RoundcubeLoginException $ex) {
							$log = vglobal('log');
							$log->error('EmailPlus_List_View|RoundcubeLoginException: ' . $ex->getMessage());
						}
					}
					$moduleInfo = array('Contacts' => array('id' => 'contactid', 'table' => 'jo_contactdetails', 'email' => 'email'),
							'Accounts' => array('id' => 'accountid', 'table' => 'jo_account', 'email' => 'email1'),
							'Leads' => array('id' => 'leadid', 'table' => 'jo_leaddetails', 'email' => 'email'));
					$getEmail = $adb->pquery("select {$moduleInfo[$module]['email']} from {$moduleInfo[$module]['table']} where {$moduleInfo[$module]['id']} = ?", array($recordId));
					$emailId = $adb->query_result($getEmail, 0, 'email');
					$response = new Head_Response();
					$response->setResult($emailId);	
					$response->emit();			
					die;	

				}
			}
