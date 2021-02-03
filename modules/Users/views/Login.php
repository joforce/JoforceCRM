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

require_once 'includes/main/WebUI.php';
require_once 'includes/utils/utils.php';
require_once 'includes/utils/ModlibUtils.php';
require_once 'modules/Emails/class.phpmailer.php';
require_once 'modules/Emails/mail.php';
require_once 'modules/Head/helpers/ShortURL.php';

vimport('~~/libraries/modlib/Head/Net/Client.php');
class Users_Login_View extends Head_View_Controller {

	function loginRequired() {
		return false;
	}
	
	function checkPermission(Head_Request $request) {
		return true;
	}
	
	function preProcess(Head_Request $request, $display = true) {
		$viewer = $this->getViewer($request);
		$viewer->assign('PAGETITLE', $this->getPageTitle($request));
		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
		$viewer->assign('STYLES', $this->getHeaderCss($request));
		$viewer->assign('MODULE', $request->getModule());
		$viewer->assign('VIEW', $request->get('view'));
		$viewer->assign('LANGUAGE_STRINGS', array());
		if ($display) {
			$this->preProcessDisplay($request);
		}
	}

	function process (Head_Request $request) {
		$mode = $request->get('mode');
		if($mode == 'ForgotPassword'){
			global $adb;
			if ($request->get('username') && $request->get('emailId')) {
				$username = modlib_purify($request->get('username'));
				$result = $adb->pquery('select email1 from jo_users where user_name= ? ', array($username));
				if ($adb->num_rows($result) > 0) {
					$email = $adb->query_result($result, 0, 'email1');
				}

				if (modlib_purify($request->get('emailId')) == $email) {
					$time = time();
					$options = array(
							'handler_path' => 'modules/Users/handlers/ForgotPassword.php',
							'handler_class' => 'Users_ForgotPassword_Handler',
							'handler_function' => 'changePassword',
							'handler_data' => array(
								'username' => $username,
								'email' => $email,
								'time' => $time,
								'hash' => md5($username.$time)
								)
							);
					$trackURL = Head_ShortURL_Helper::generateURL($options);
					$content = 'Dear Customer,<br><br>
							You recently requested a password reset for your JoForce Open source Account.<br>
							To create a new password, click on the link <a target="_blank" href='.$trackURL.'>here</a>.
							<br><br>
							This request was made on '.date("Y-m-d H:i:s").' and will expire in next 24 hours.<br><br>
							Regards,<br>
							JoForce Open source Support Team.<br>';
					$mail = new PHPMailer();
					$query = "select from_email_field,server_username from jo_systems where server_type=?";
					$params = array('email');
					$result = $adb->pquery($query, $params);
					$from = $adb->query_result($result, 0, 'from_email_field');
					if ($from == '') {
						$from = $adb->query_result($result, 0, 'server_username');
					}
					$subject = 'Request : ForgotPassword - JoForce';

					setMailerProperties($mail, $subject, $content, $from, $username, $email);
					$status = MailSend($mail);
					if ($status === 1) {
						header('Location:  index.php?modules=Users&view=Login&mailStatus=success');
					} else {
						header('Location:  index.php?modules=Users&view=Login&error=statusError');
					}
				} else {
					header('Location:  index.php?modules=Users&view=Login&error=fpError');
				}
			}
		}
		else{
			$finalJsonData = array();

			$viewer = $this->getViewer($request);
			$viewer->assign('DATA_COUNT', count($jsonData));
			$viewer->assign('JSON_DATA', $finalJsonData);

			$mailStatus = $request->get('mailStatus');
			$error = $request->get('error');
			$message = '';
			if ($error) {
				switch ($error) {
					case 'login'		:	$message = 'Invalid credentials';						break;
					case 'fpError'		:	$message = 'Invalid Username or Email address';			break;
					case 'statusError'	:	$message = 'Outgoing mail server was not configured';	break;
				}
			} else if ($mailStatus) {
				$message = 'Mail has been sent to your inbox, please check your e-mail';
			}
			$loginimage = Settings_Head_LogoDetails_Model::getInstance();
			$viewer->assign('LOGINIMAGE',$loginimage);	
			$viewer->assign('ERROR', $error);
			$viewer->assign('MESSAGE', $message);
			$viewer->assign('MAIL_STATUS', $mailStatus);
			$viewer->view('Login.tpl', 'Users');
		}
	}

	function postProcess(Head_Request $request) {
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$viewer->view('LoginFooter.tpl', $moduleName);
	}

	function getPageTitle(Head_Request $request) {
		$companyDetails = Head_CompanyDetails_Model::getInstanceById();
		return $companyDetails->get('organizationname');
	}

	function getHeaderScripts(Head_Request $request){
		$headerScriptInstances = parent::getHeaderScripts($request);

		$jsFileNames = array(
							'modules.Head.resources.List',
							'modules.Head.resources.Popup',
							);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($jsScriptInstances,$headerScriptInstances);
		return $headerScriptInstances;
	}
}
