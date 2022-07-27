<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

class Install_Index_view extends Head_View_Controller {

	protected $debug = false;
	protected $viewer = null;

	function loginRequired() {
		return false;
	}

	public function __construct() {
		$this->PermissionCheck();
		$this->exposeMethod('Step1');
		$this->exposeMethod('Step2');
		$this->exposeMethod('Step3');
		$this->exposeMethod('Step4');
		$this->exposeMethod('Step5');
		$this->exposeMethod('Step6');
		$this->exposeMethod('Step7');
	}

	public function PermissionCheck(){
		$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$slash_pos = strrpos($request_uri, "/");
		if ($slash_pos === FALSE) {
			$request_uri_full = $request_uri;
		}
		else {
			$request_uri_full = substr($request_uri, 0, $slash_pos);
		}
		$filePath = $_SERVER['DOCUMENT_ROOT'] . $request_uri_full;

		$filePermission = substr(sprintf('%o', fileperms($filePath)),-4);

		$filePermission = (decoct(octdec($filePermission)));

		$filePermission = $this->octalToDecimal($filePermission);
		
		$writablePermission = $this->octalToDecimal(755);

		if ($filePermission < $writablePermission) {
			echo "<div id='myModal' class='myModal' style='display: block;position:fixed;left:0;top:0;width:100%;height:100%;background-color: rgba(0, 0, 0, 0.5);opacity: 1;z-index: 1;'>
					<div class='modal-content' style='position:absolute;top:50%;left:50%;transform: translate(-50%, -50%);background-color: white;padding: 1rem 1.5rem;border-radius: 0.5rem;width: 30%;z-index: 10;overflow: auto;'>
						<div style='display:flex;justify-content:space-between;'>
							<h3 style='margin:0px 0px 15px 0px;'><strong>Directory Access</strong></h3>
							<span class='close-button' style='float: right;width: 1.5rem;line-height: 1.5rem;text-align: center;cursor: pointer;border-radius: 0.25rem;background-color: lightgray;height:25px;' onclick=document.getElementById('myModal').style.display='none'>×</span>
						</div>
						<div style='float:right'>
							<label>The <b>'  $filePath  '</b> directory is not writable on this server please talk to your host or server admin about making writable <b>'  $filePath  '</b> directory on this server.</label>
						</div>
					</div>
				</div>";
			ob_flush(); 
			flush(); 
			die;
		}
	}

	public function octalToDecimal($n)
	{		
		$num = $n;
		$dec_value = 0;
		$base = 1;
		$temp = $num;
		while ($temp)
		{
			$last_digit = $temp % 10;
			$temp = $temp / 10;
			$dec_value += $last_digit * $base;
			$base = $base * 8;
		}
		return $dec_value;
	}

    protected function applyInstallFriendlyEnv() {
        // config.inc.php - will not be ready to control this yet.

        // version_compare(PHP_VERSION, '5.5.0') <= 0 ? error_reporting(~E_WARNING & ~E_NOTICE & ~E_DEPRECATED) : error_reporting(~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);

        set_time_limit(0); // override limits on execution time to allow install to finish
    }

	public function preProcess(Head_Request $request, $display = true) {
        $this->applyInstallFriendlyEnv();
		date_default_timezone_set('Europe/London'); // to overcome the pre configuration settings
		// Added to redirect to default module if already installed
		$configFileName = 'config/config.inc.php';
		if(is_file($configFileName) && filesize($configFileName) > 0) {
			$defaultModule = vglobal('default_module');
			if(empty($defaultModule)) {
			    $defaultModule = 'Home';
			}
			//$defaultModuleInstance = Head_Module_Model::getInstance($defaultModule);
			//$defaultView = $defaultModuleInstance->getDefaultViewName();
			$defaultView = 'List';
			header('Location:index.php?module='.$defaultModule.'&view='.$defaultView);
			exit;
		}

		parent::preProcess($request);
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		if ($chosenLanguage = $request->get('lang')) {
			$_SESSION['config_file_info']['default_language'] = $chosenLanguage;
		} elseif (empty($_SESSION['config_file_info']['default_language'])) {
			$_SESSION['config_file_info']['default_language'] = 'en_us';
		}
		vglobal('default_language', $_SESSION['config_file_info']['default_language']);

		define('INSTALLATION_MODE', true);
		define('INSTALLATION_MODE_DEBUG', $this->debug);
		$viewer->view('InstallPreProcess.tpl', $moduleName);
	}

	public function process(Head_Request $request) {
		global $default_charset;$default_charset='UTF-8';
		$mode = $request->getMode();
		if(!empty($mode) && $this->isMethodExposed($mode)) {
			return $this->$mode($request);
		}
		$this->Step1($request);
	}

	public function postProcess(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$viewer->view('InstallPostProcess.tpl', $moduleName);
	}

	public function Step1(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$viewer->assign('CURRENT_LANGUAGE', vglobal('default_language'));
		$viewer->assign('LANGUAGES', Install_Utils_model::getLanguageList());
		$viewer->view('Step1.tpl', $moduleName);
	}

	public function Step2(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$viewer->view('Step2.tpl', $moduleName);
	}

	public function Step3(Head_Request $request) {
		$webRoot = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
		$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$slash_pos = strrpos($request_uri, "/");
		$request_uri_full = substr($request_uri, 0, $slash_pos);
		$webRoot = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? "https://":"http://").$webRoot.$request_uri_full.'/';

		$viewer = $this->getViewer($request);
		$php_Self = $_SERVER['PHP_SELF'];
		$phpbase = str_replace('index.php','',$php_Self);
		$server_type = $_SERVER['SERVER_SOFTWARE'];
		$webServerName = explode('/', $server_type)[0];
		$moduleName = $request->getModule();
		$filename = '.htaccess';

		if (file_exists($filename)) {
			if (is_writable($filename)) {
				$viewer->assign('HTACC_PER','File Permission is Ok');
				$viewer->assign('HT_PER','true');
			}
			else {
				$viewer->assign('HTACC_PER','Please Provide the writable permission for .htaccess. That htaccess file placed in your root folder');
				$viewer->assign('HT_PER','false');
			}
		}else{
			$viewer->assign('HT_PER','false');
			$viewer->assign('HTACC_PER','Please create the .htaccess file in your Joforce root folder with writable permission.');
		}
		if($webServerName == 'nginx') {
			$content .="\n location ". $phpbase ." {"."<br>";
//			$content .=' if (!-e $request_filename){ rewrite ^'.$phpbase.'(.*)$ '.$phpbase.$php_Self.' last; '."<br>";
			$content .=' if (!-e $request_filename){ rewrite ^'.$phpbase.'(.*)$ '.$php_Self.' last; '."<br>";
			$content .= "\n } \n<br>";
			$content .= "fastcgi_read_timeout 1800s; <br> fastcgi_send_timeout 1800s;";
			$content .= "\n } \n<br>";
			$viewer->assign('HTACC_PER',$content);
			$viewer->assign('HT_PER','true');
			$viewer->assign('SERVERTYPE','Nginx');
			$viewer->assign('SERVERHEAD','Nginx configuration');

		}
		else {	
			$viewer->assign('SERVERTYPE','.htaccess');
			$viewer->assign('SERVERHEAD','File Permission Check');
		}
		$viewer->assign('FAILED_FILE_PERMISSIONS', Install_Utils_Model::getFailedPermissionsFiles());
		$viewer->assign('PHP_INI_CURRENT_SETTINGS', Install_Utils_Model::getCurrentDirectiveValue());
		$viewer->assign('PHP_INI_RECOMMENDED_SETTINGS', Install_Utils_Model::getRecommendedDirectives());
		$viewer->assign('SYSTEM_PREINSTALL_PARAMS', Install_Utils_Model::getSystemPreInstallParameters());
		$viewer->assign('PHP_INI_LOCATION',php_ini_loaded_file());
		$viewer->assign('SITE_URL',$webRoot);
		$viewer->view('Step3.tpl', $moduleName);
	}

	public function Step4(Head_Request $request) {
		$webRoot = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
		$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$slash_pos = strrpos($request_uri, "/");
		$request_uri_full = substr($request_uri, 0, $slash_pos);
		$webRoot = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? "https://":"http://").$webRoot.$request_uri_full.'/';

		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$viewer->assign('CURRENCIES', Install_Utils_Model::getCurrencyList());

		require_once 'modules/Users/UserTimeZonesArray.php';
		$timeZone = new UserTimeZones();
		$viewer->assign('TIMEZONES', $timeZone->userTimeZones());

		$defaultParameters = Install_Utils_Model::getDefaultPreInstallParameters();
		$viewer->assign('DB_HOSTNAME', $defaultParameters['db_hostname']);
		$viewer->assign('DB_USERNAME', $defaultParameters['db_username']);
		$viewer->assign('DB_PASSWORD', $defaultParameters['db_password']);
		$viewer->assign('DB_NAME', $defaultParameters['db_name']);
		$viewer->assign('ADMIN_NAME', $defaultParameters['admin_name']);
		$viewer->assign('ADMIN_LASTNAME', $defaultParameters['admin_lastname']);
		$viewer->assign('ADMIN_PASSWORD', $defaultParameters['admin_password']);
		$viewer->assign('ADMIN_EMAIL', $defaultParameters['admin_email']);
		$viewer->assign('SITE_URL',$webRoot);
		$viewer->view('Step4.tpl', $moduleName);
	}

	public function Step5(Head_Request $request) {
		$webRoot = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
		$request_uri = filter_input(INPUT_SERVER, 'REQUEST_URI');
		$slash_pos = strrpos($request_uri, "/");
		$request_uri_full = substr($request_uri, 0, $slash_pos);
		$webRoot = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? "https://":"http://").$webRoot.$request_uri_full.'/';
		set_time_limit(0); // Override default limit to let install complete.
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$requestData = $request->getAll();

		foreach($requestData as $name => $value) {
			$_SESSION['config_file_info'][$name] = $value;
		}

		$createDataBase = false;
		// $createDB = $request->get('create_db');
		$createDB = $request->get('db_action');
		if($createDB === 'create') {
			$rootUser = $request->get('db_username');
			$rootPassword = $request->get('db_password');
			$createDataBase = true;
		}
		$authKey = $_SESSION['config_file_info']['authentication_key'] = md5(microtime());

		//PHP 5.5+ mysqli is favourable.
		$dbConnection = Install_Utils_Model::checkDbConnection(function_exists('mysqli_connect')?'mysqli':'mysql', $request->get('db_hostname'),
			$request->get('db_username'), $request->get('db_password'), $request->get('db_name'),
			$createDataBase, true, $rootUser, $rootPassword);

		$webRoot = ($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"]:$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
		$webRoot .= $_SERVER["REQUEST_URI"];

		$webRoot = str_replace( "index.php", "", $webRoot);
		$webRoot = (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? "https://":"http://").$webRoot;

		$_SESSION['config_file_info']['site_URL'] = $webRoot;
		$viewer->assign('SITE_URL', $webRoot);

		$_SESSION['config_file_info']['root_directory'] = getcwd().'/';

		$currencies = Install_Utils_Model::getCurrencyList();
		$currencyName = $request->get('currency_name');
		if(isset($currencyName)) {
			$_SESSION['config_file_info']['currency_code'] = $currencies[$currencyName][0];
			$_SESSION['config_file_info']['currency_symbol'] = $currencies[$currencyName][1];
		}
		$viewer->assign('DB_CONNECTION_INFO', $dbConnection);
		$viewer->assign('INFORMATION', $requestData);
		$viewer->assign('AUTH_KEY', $authKey);
		$viewer->assign('SITE_URL',$webRoot);
		$viewer->view('Step5.tpl', $moduleName);
	}

	public function Step6(Head_Request $request) {
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);

		$viewer->assign('AUTH_KEY', $_SESSION['config_file_info']['authentication_key']);
		$viewer->view('Step6.tpl', $moduleName);
	}

    public function Step7(Head_Request $request) {
        require_once('includes/utils/utils.php');
        require_once('modules/Users/Users.php');
        global $adb, $current_user;
        $moduleName = $request->getModule();
        $webuiInstance = new Head_WebUI();
        $isInstalled = $webuiInstance->isInstalled();
        if($_SESSION['config_file_info']['authentication_key'] != $request->get('auth_key')) {
            die(vtranslate('ERR_NOT_AUTHORIZED_TO_PERFORM_THE_OPERATION', $moduleName));
        }

        // Create configuration file
        $configParams = $_SESSION['config_file_info'];
        $configFile = new Install_ConfigFileUtils_Model($configParams);
        $configFile->createConfigFile();

        $adb->resetSettings($configParams['db_type'], $configParams['db_hostname'], $configParams['db_name'], $configParams['db_username'], $configParams['db_password']);
        $adb->query('SET NAMES utf8');

        $import_sql = Install_MysqlImport_Model::ImportDump($configParams);
		
        $current_user = Users::getActiveAdminUser();
        $recordModel = Head_Record_Model::getInstanceById(1, 'Users');
        $recordModel->set('id', 1);
        $recordModel->set('mode','edit');
        $recordModel->set('first_name', $configParams['firstname']);
        $recordModel->set('last_name', $configParams['lastname']);
        $recordModel->set('email1', $configParams['admin_email']);
        $recordModel->set('date_format', $configParams['dateformat']);
        $recordModel->set('time_zone', $configParams['timezone']);
        $recordModel->set('user_password', $configParams['password']);
        $recordModel->save('Users');

        $users = CRMEntity::getInstance('Users');
        $users->retrieveCurrentUserInfoFromFile(1);
        $changePwdResponse = $users->change_password('admin', $configParams['password']);

        $viewer = $this->getViewer($request);
        $viewer->assign('PASSWORD', $_SESSION['config_file_info']['password']);
        $viewer->assign('APPUNIQUEKEY', $this->retrieveConfiguredAppUniqueKey());
        $viewer->assign('CURRENT_VERSION', $_SESSION['jo_version']);
        $viewer->assign('INDUSTRY', $request->get('industry'));

		if (isset($_SESSION['progress'])) {
			session_start(); //IMPORTANT!
		}
		$_SESSION['progress'] = 100;
		session_write_close(); //IMPORTANT!

        $viewer->view('Step7.tpl', $moduleName);
    }

	// Helper function as configuration file is still not loaded.
	protected function retrieveConfiguredAppUniqueKey() {
		include 'config/config.inc.php';
		return $application_unique_key;
	}

	public function getHeaderCss(Head_Request $request) {
		$moduleName = $request->getModule();
		$parentCSSScripts = parent::getHeaderCss($request);
		$styleFileNames = array(
			"~/layouts/modules/$moduleName/resources/css/style.css",
		);
		$cssScriptInstances = $this->checkAndConvertCssStyles($styleFileNames);
		$headerCSSScriptInstances = array_merge($parentCSSScripts, $cssScriptInstances);
		return $headerCSSScriptInstances;
	}

	public function getHeaderScripts(Head_Request $request) {
		$moduleName = $request->getModule();
		$parentScripts = parent::getHeaderScripts($request);
		$jsFileNames = array("modules.Head.resources.List",
							 "modules.Head.resources.Popup",
							 "modules.$moduleName.resources.Index");
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($parentScripts, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function validateRequest(Head_Request $request) { 
		return $request->validateWriteAccess(true); 
	}
}

// Write custom log when installation getting interrupted - Added by Fredrick Marks
set_error_handler("JoforceErrorHandler");
register_shutdown_function( "Joforce_Write_ErrorLogs" );

function JoforceErrorHandler($code, $message, $file, $line) {
}

/**
 * Checks for a fatal error, work around for set_error_handler not working on fatal errors.
 */
function Joforce_Write_ErrorLogs() {
        $log_path = dirname(__FILE__) . "/../../../logs/joforce-error.log";
        # Getting last error
        $error = error_get_last();
        # Checking if last error is a fatal error
        if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)|| ($error['type'] === E_CORE_ERROR) || ($error['type'] == E_COMPILE_ERROR)) {
                # Here we handle the error, displaying HTML, logging, ...
                $log_msg = "ERRORnr : " . $error['type']. " \n Msg : ".$error['message']." \n File : ".$error['file']. " \n Line : " . $error['line'];
                error_log(print_r($log_msg, true), 3, $log_path);
        } else {
        }
}
