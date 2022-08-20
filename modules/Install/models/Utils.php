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

class Install_Utils_Model {

    /**
     * variable has all the files and folder that should be writable
     * @var <Array>
     */
    public static $writableFilesAndFolders = array (
	'Configuration File' => './config/config.inc.php',
	'Cache Directory' => './cache/',
	'Image Cache Directory' => './cache/images/',
	'Import Cache Directory' => './cache/import/',
	'Storage Directory' => './storage/',
	'User Privileges Directory' => './user_privileges/',
	'Modules Directory' => './modules/',
	'Cron Modules Directory' => './cron/modules/',
	'Modlib Test Directory' => './cache/modlib/',
	'Modlib Test HTML Directory' => './cache/modlib/HTML',
	'Mail Merge Template Directory' => './cache/wordtemplatedownload/',
	'Product Image Directory' => './cache/product/',
	'User Image Directory' => './cache/user/',
	'Contact Image Directory' => './cache/contact/',
	'Logo Directory' => './cache/logo/',
	'Logs Directory' => './logs/',
    );

    /**
     * Function returns all the files and folder that are not writable
     * @return <Array>
     */
    public static function getFailedPermissionsFiles() {
	$writableFilesAndFolders = self::$writableFilesAndFolders;
	$failedPermissions = array();
	require_once ('includes/utils/ModlibUtils.php');
	foreach ($writableFilesAndFolders as $index => $value) {
	    if (!modlib_isWriteable($value)) {
		$failedPermissions[$index] = $value;
	    }
	}
	return $failedPermissions;
    }

    /**
     * Function returns the php.ini file settings required for installing vtigerCRM
     * @return <Array>
     */
    static function getCurrentDirectiveValue() {
		$cruDir = true;
		$directiveValues = array();
		if (ini_get('safe_mode') == '1' || stripos(ini_get('safe_mode'), 'On') > -1){
			$directiveValues['safe_mode'] = 'On';
			$cruDir = false;
		}
		else{
			$directiveValues['safe_mode'] = 'Off';
		}
		
		if (ini_get('display_errors') != '1' || stripos(ini_get('display_errors'), 'Off') > -1){
			$directiveValues['display_errors'] = 'Off';
			$cruDir = false;
		}
		else{
			$directiveValues['display_errors'] = 'On';
		}

		if (ini_get('file_uploads') != '1' || stripos(ini_get('file_uploads'), 'Off') > -1){
			$directiveValues['file_uploads'] = 'Off';
			$cruDir = false;
		}
		else{
			$directiveValues['file_uploads'] = 'On';
		}

		// if (ini_get('register_globals') == '1' || stripos(ini_get('register_globals'), 'On') > -1){
		// 	$directiveValues['register_globals'] = 'On';
		// }
		// else{
		// 	$directiveValues['register_globals'] = 'Off';
		// }

		if (ini_get(('output_buffering') < '4096' && ini_get('output_buffering') != '0') || stripos(ini_get('output_buffering'), 'Off') > -1){
			$directiveValues['output_buffering'] = 'Off';
			$cruDir = false;
		}
		else{
			$directiveValues['output_buffering'] = 'On';
		}

		if (ini_get('max_execution_time') != 0){
			$directiveValues['max_execution_time'] = ini_get('max_execution_time');
			$cruDir = false;
		}
		else{
			$directiveValues['max_execution_time'] = 0;
		}
		
		$directiveValues['memory_limit'] = substr(ini_get('memory_limit'), 0, -1);
		if($directiveValues['memory_limit'] < 32){
			$cruDir = false;
		}
			
		$errorReportingValue = ~E_WARNING & ~E_NOTICE;
		$strerrorReportingValue = '~E_WARNING & ~E_NOTICE';
		if(version_compare(PHP_VERSION, '5.5.0') >= 0){
			$errorReportingValue = ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT;
			$strerrorReportingValue = '~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT';
		} 
		else if(version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$errorReportingValue = ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED;
			$strerrorReportingValue = '~E_WARNING & ~E_NOTICE & ~E_DEPRECATED';
		}

		if (ini_get('error_reporting') == $errorReportingValue || ini_get('error_reporting') == $strerrorReportingValue){
			$directiveValues['error_reporting'] = $strerrorReportingValue;	
		}
		else{
			$directiveValues['error_reporting'] = 'E_ALL & ~E_DEPRECATED & ~E_STRICT';
			$cruDir = false;
		}

		if (ini_get('log_errors') == '1' || stripos(ini_get('log_errors'), 'On') > -1){
			$directiveValues['log_errors'] = 'On';
			$cruDir = false;
		}
		else{
			$directiveValues['log_errors'] = 'Off';
		}

		if (ini_get('short_open_tag') == '1' || stripos(ini_get('short_open_tag'), 'On') > -1){
			$directiveValues['short_open_tag'] = 'On';
			$cruDir = false;
		}
		else{
			$directiveValues['short_open_tag'] = 'Off';
		}
		$directiveValues['success'] = $cruDir;
		return $directiveValues;
    }

    /**
     * Variable has the recommended php settings for smooth running of vtigerCRM
     * @var <Array>
     */
    public static $recommendedDirectives = array (
	'safe_mode' => 'Off',
	'display_errors' => 'On',
	'file_uploads' => 'On',
	'register_globals' => 'On',
	'output_buffering' => 'On',
	'max_execution_time' => '0',
	'memory_limit' => '32',
	'error_reporting' => '~E_WARNING & ~E_NOTICE',
	'log_errors' => 'Off',
	'short_open_tag' => 'Off'
    );

    /**
     * Returns the recommended php settings for vtigerCRM
     * @return type
     */
    public static function getRecommendedDirectives(){
	if(version_compare(PHP_VERSION, '5.5.0') >= 0){
	    // self::$recommendedDirectives['error_reporting'] = 'E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT';
	    self::$recommendedDirectives['error_reporting'] = '~E_WARNING & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT';
	} else if(version_compare(PHP_VERSION, '5.3.0') >= 0) {
	    // self::$recommendedDirectives['error_reporting'] = 'E_WARNING & ~E_NOTICE & ~E_DEPRECATED';
	    self::$recommendedDirectives['error_reporting'] = '~E_WARNING & ~E_NOTICE & ~E_DEPRECATED';

	}
	return self::$recommendedDirectives;
    }

    /**
     * Function checks for vtigerCRM installation prerequisites
     * @return <Array>
     */
    public static function getSystemPreInstallParameters() {
	$server_type = $_SERVER['SERVER_SOFTWARE'];
	$webServerName = explode('/', $server_type)[0];		
	$SysPreIns = false;
	$preInstallConfig = array();
	// Name => array( System Value, Recommended value, supported or not(true/false) );
	$preInstallConfig['LBL_PHP_VERSION']	= array(phpversion(), '7.2.5 <= 7.4', (version_compare(phpversion(), '7.2.5', '>=')));
	if($webServerName != 'nginx'){
	    if(function_exists('apache_get_modules')){
	    	$preInstallConfig['LBL_MOD_REWRITE'] = array(in_array('mod_rewrite', apache_get_modules()), true, (in_array('mod_rewrite', apache_get_modules()) == true));
	    }
	}
	$preInstallConfig['LBL_IMAP_SUPPORT']	= array(function_exists('imap_open'), true, (function_exists('imap_open') == true));
	$preInstallConfig['LBL_ZLIB_SUPPORT']	= array(function_exists('gzinflate'), true, (function_exists('gzinflate') == true));
	$preInstallConfig['LBL_SIMPLE_XML_SUPPORT']=array(function_exists('simplexml_load_file'), true, (function_exists('simplexml_load_file') == true));
	if ($preInstallConfig['LBL_PHP_VERSION'] >= '5.5.0') {
		$preInstallConfig['LBL_MYSQLI_CONNECT_SUPPORT'] = array(extension_loaded('mysqli'), true, extension_loaded('mysqli'));
	}
	$preInstallConfig['LBL_OPEN_SSL'] = array(extension_loaded('openssl'), true, extension_loaded('openssl'));
	$preInstallConfig['LBL_CURL'] = array(extension_loaded('curl'), true, extension_loaded('curl'));
	$gnInstalled = false;
	if(!function_exists('gd_info')) {
	    eval(self::$gdInfoAlternate);
	}
	$gd_info = gd_info();
	if (isset($gd_info['GD Version'])) {
	    $gnInstalled = true;
	}
	$preInstallConfig['LBL_GD_LIBRARY'] = array((extension_loaded('gd') || $gnInstalled), true, (extension_loaded('gd') || $gnInstalled));
	$preInstallConfig['LBL_ZLIB_SUPPORT']	= array(function_exists('gzinflate'), true, (function_exists('gzinflate') == true));
	if(
		(version_compare(phpversion(), '7.2.5', '>=')) &&
		(in_array('mod_rewrite', apache_get_modules()) == true) &&
		(function_exists('imap_open') == true) &&
		(function_exists('gzinflate') == true) &&
		(function_exists('simplexml_load_file') == true) &&
		(extension_loaded('mysqli')) &&
		(extension_loaded('openssl')) &&
		(extension_loaded('curl')) &&
		(extension_loaded('gd') || $gnInstalled)
	)
	{
		$SysPreIns = true;
	}
	$preInstallConfig['LBL_Success'] = array('Success',$SysPreIns);
	return $preInstallConfig;
    }
	
    /**
     * Function that provides default configuration based on installer setup
     * @return <Array>
     */
    public static function getDefaultPreInstallParameters() {
	include 'config/config.db.php';
		
	$parameters = array(
	    'db_hostname' => '',
	    'db_username' => '',
	    'db_password' => '',
	    'db_name'     => '',
	    'admin_name'  => 'admin',
	    'admin_lastname'=> 'Administrator',
	    'admin_password'=>'',
	    'admin_email' => '',
	);
		
	if (isset($dbconfig) && isset($vtconfig)) {
	    if (isset($dbconfig['db_server']) && $dbconfig['db_server'] != '_DBC_SERVER_') {
		$parameters['db_hostname'] = $dbconfig['db_server'] . ':' . $dbconfig['db_port'];
		$parameters['db_username'] = $dbconfig['db_username'];
		$parameters['db_password'] = $dbconfig['db_password'];
		$parameters['db_name']     = $dbconfig['db_name'];
				
		$parameters['admin_password'] = $vtconfig['adminPwd'];
		$parameters['admin_email']    = $vtconfig['adminEmail'];
	    }
	}
	
	return $parameters;
    }

    /**
     * Function returns gd library information
     * @var type
     */
    public static $gdInfoAlternate = 'function gd_info() {
		$array = Array(
	               "GD Version" => "",
	               "FreeType Support" => 0,
	               "FreeType Support" => 0,
	               "FreeType Linkage" => "",
	               "T1Lib Support" => 0,
	               "GIF Read Support" => 0,
	               "GIF Create Support" => 0,
	               "JPG Support" => 0,
	               "PNG Support" => 0,
	               "WBMP Support" => 0,
	               "XBM Support" => 0
	             );
		       $gif_support = 0;

		       ob_start();
		       eval("phpinfo();");
		       $info = ob_get_contents();
		       ob_end_clean();

		       foreach(explode("\n", $info) as $line) {
		           if(strpos($line, "GD Version")!==false)
		               $array["GD Version"] = trim(str_replace("GD Version", "", strip_tags($line)));
		           if(strpos($line, "FreeType Support")!==false)
		               $array["FreeType Support"] = trim(str_replace("FreeType Support", "", strip_tags($line)));
		           if(strpos($line, "FreeType Linkage")!==false)
		               $array["FreeType Linkage"] = trim(str_replace("FreeType Linkage", "", strip_tags($line)));
		           if(strpos($line, "T1Lib Support")!==false)
		               $array["T1Lib Support"] = trim(str_replace("T1Lib Support", "", strip_tags($line)));
		           if(strpos($line, "GIF Read Support")!==false)
		               $array["GIF Read Support"] = trim(str_replace("GIF Read Support", "", strip_tags($line)));
		           if(strpos($line, "GIF Create Support")!==false)
		               $array["GIF Create Support"] = trim(str_replace("GIF Create Support", "", strip_tags($line)));
		           if(strpos($line, "GIF Support")!==false)
		               $gif_support = trim(str_replace("GIF Support", "", strip_tags($line)));
		           if(strpos($line, "JPG Support")!==false)
		               $array["JPG Support"] = trim(str_replace("JPG Support", "", strip_tags($line)));
		           if(strpos($line, "PNG Support")!==false)
		               $array["PNG Support"] = trim(str_replace("PNG Support", "", strip_tags($line)));
		           if(strpos($line, "WBMP Support")!==false)
		               $array["WBMP Support"] = trim(str_replace("WBMP Support", "", strip_tags($line)));
		           if(strpos($line, "XBM Support")!==false)
		               $array["XBM Support"] = trim(str_replace("XBM Support", "", strip_tags($line)));
		       }

		       if($gif_support==="enabled") {
		           $array["GIF Read Support"]  = 1;
		           $array["GIF Create Support"] = 1;
		       }

		       if($array["FreeType Support"]==="enabled"){
		           $array["FreeType Support"] = 1;    }

		       if($array["T1Lib Support"]==="enabled")
		           $array["T1Lib Support"] = 1;

		       if($array["GIF Read Support"]==="enabled"){
		           $array["GIF Read Support"] = 1;    }

		       if($array["GIF Create Support"]==="enabled")
		           $array["GIF Create Support"] = 1;

		       if($array["JPG Support"]==="enabled")
		           $array["JPG Support"] = 1;

		       if($array["PNG Support"]==="enabled")
		           $array["PNG Support"] = 1;

		       if($array["WBMP Support"]==="enabled")
		           $array["WBMP Support"] = 1;

		       if($array["XBM Support"]==="enabled")
		           $array["XBM Support"] = 1;

		       return $array;

		}';

    /**
     * Returns list of currencies
     * @return <Array>
     */
    public static function getCurrencyList() {
	require_once 'modules/Utilities/Currencies.php';
	return $currencies;
    }

    /**
     * Returns an array with the list of languages which are available in source
     * Note: the DB has not been initialized at this point, so we have to look at
     * the contents of the `languages/` directory.
     * @return <Array>
     */
    public static function getLanguageList() {
	$languageFolder = 'languages/';
	$handle = opendir($languageFolder);
	$language_list = array();
	while ($prefix = readdir($handle)) {
	    if (substr($prefix, 0, 1) === '.' || $prefix === 'Settings') {
		continue;
	    }
	    if (is_dir('languages/' . $prefix) && is_file('languages/' . $prefix . '/Install.php')) {
		$language_list[$prefix] = $prefix;
	    }
	}

	ksort($language_list);
	return $language_list;
    }

    /**
     * Function checks if its mysql type
     * @param type $dbType
     * @return type
     */
    static function isMySQL($dbType) {
	return (stripos($dbType ,'mysql') === 0);
    }

    /**
     * Function returns mysql version
     * @param type $serverInfo
     * @return type
     */
    public static function getMySQLVersion($serverInfo) {
	if(!is_array($serverInfo)) {
	    $version = explode('-',$serverInfo);
	    $mysql_server_version=$version[0];
	} else {
	    $mysql_server_version = $serverInfo['version'];
	}
	return $mysql_server_version;
    }

    /**
      +	 * Function to check sql_mode configuration
      +	 * @param DbConnection $conn 
      +	 * @return boolean
      +	 */
    public static function isMySQLSqlModeFriendly($conn) {		
        $rs = $conn->Execute("SHOW VARIABLES LIKE 'sql_mode'");
        if ($rs && ($row = $rs->fetchRow())) {
            $values = explode(',', strtoupper($row['Value']));
            $unsupported = array('ONLY_FULL_GROUP_BY', 'STRICT_TRANS_TABLES', 'NO_ZERO_IN_DATE', 'NO_ZERO_DATE');
            foreach ($unsupported as $check) {
                if (in_array($check, $values)) {
					return false;
				}
            }
        }
        return true;
    }

    /**
     * Function checks the database connection
     * @param <String> $db_type
     * @param <String> $db_hostname
     * @param <String> $db_username
     * @param <String> $db_password
     * @param <String> $db_name
     * @param <String> $create_db
     * @param <String> $create_utf8_db
     * @param <String> $root_user
     * @param <String> $root_password
     * @return <Array>
     */
    public static function checkDbConnection($db_type, $db_hostname, $db_username, $db_password, $db_name, $create_db=false, $create_utf8_db=true, $root_user='', $root_password='') {
	$dbCheckResult = array();
	$db_type_status = false; // is there a db type?
	$db_server_status = false; // does the db server connection exist?
	$db_creation_failed = false; // did we try to create a database and fail?
	$db_exist_status = false; // does the database exist?
	$db_utf8_support = false; // does the database support utf8?
    $db_sqlmode_support = false; // does the database having friendly sql_mode?

	//Checking for database connection parameters
	if($db_type) {
	    $conn = NewADOConnection($db_type);

	    $db_type_status = true;
	    if(@$conn->Connect($db_hostname,$db_username,$db_password)) {
		$db_server_status = true;
		$serverInfo = $conn->ServerInfo();
		if(self::isMySQL($db_type)) {
		    $mysql_server_version = self::getMySQLVersion($serverInfo);
		}
		$query1 = "set global sql_mode = ''";
			$conn->Execute($query1);

			//	 $conn->Execute("SET SESSION sql_mode = ''");
                $db_sqlmode_support = self::isMySQLSqlModeFriendly($conn);
                //$db_sqlmode_support = true; // Need to check sql mode is friendly
                if($create_db && $db_sqlmode_support) {
		    // drop the current database if it exists
		    $dropdb_conn = NewADOConnection($db_type);
		    if(@$dropdb_conn->Connect($db_hostname, $root_user, $root_password, $db_name)) {
			$query = "DROP DATABASE ".$db_name;
			$dropdb_conn->Execute($query);
			$dropdb_conn->Close();
		    }

		    // create the new database
		    $db_creation_failed = true;
		    $createdb_conn = NewADOConnection($db_type);
		    if(@$createdb_conn->Connect($db_hostname, $root_user, $root_password)) {
			$query = "CREATE DATABASE ".$db_name;
			if($create_utf8_db == 'true') {
			    if(self::isMySQL($db_type))
				$query .= " DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci";

			    $db_utf8_support = true;
			}
			if($createdb_conn->Execute($query)) {
			    $db_creation_failed = false;
			}
		//	 $createdb_conn->Execute("SET SESSION sql_mode = ''");
			$createdb_conn->Close();
		    }
		}

		if(@$conn->Connect($db_hostname, $db_username, $db_password, $db_name)) {
		    $db_exist_status = true;
		    if(!$db_utf8_support) {
			$db_utf8_support = Head_Util_Helper::checkDbUTF8Support($conn);
		    }
		}
		$conn->Close();
	    }
	}
	$dbCheckResult['db_utf8_support'] = $db_utf8_support;

	$error_msg = '';
	$error_msg_info = '';

	if(!$db_type_status || !$db_server_status) {
	    $error_msg = getTranslatedString('ERR_DATABASE_CONNECTION_FAILED', 'Install').'. '.getTranslatedString('ERR_INVALID_MYSQL_PARAMETERS', 'Install');
	    $error_msg_info = getTranslatedString('MSG_LIST_REASONS', 'Install').':<br>
					-  '.getTranslatedString('MSG_DB_PARAMETERS_INVALID', 'Install').'
					-  '.getTranslatedString('MSG_DB_USER_NOT_AUTHORIZED', 'Install');
	} elseif(self::isMySQL($db_type) && $mysql_server_version < 4.1) {
	    $error_msg = $mysql_server_version.' -> '.getTranslatedString('ERR_INVALID_MYSQL_VERSION', 'Install');
        } elseif(!$db_sqlmode_support) {
            $error_msg = getTranslatedString('ERR_DB_SQLMODE_NOTFRIENDLY', 'Install');     
	} elseif($db_creation_failed) {
	    $error_msg = getTranslatedString('ERR_UNABLE_CREATE_DATABASE', 'Install').' '.$db_name;
	    $error_msg_info = getTranslatedString('MSG_DB_ROOT_USER_NOT_AUTHORIZED', 'Install');
	} elseif(!$db_exist_status) {
	    $error_msg = $db_name.' -> '.getTranslatedString('ERR_DB_NOT_FOUND', 'Install');
	} 
	/*elseif(!$db_utf8_support) {
            $error_msg =  '<p>'. $db_name.' -> '.getTranslatedString('ERR_DB_NOT_UTF8', 'Install') .'<p>';
	    $error_msg .= '<p> Change the charater set by "ALTER DATABASE '.$db_name.' CHARACTER SET utf8 COLLATE utf8_general_ci;"</p>';
	}*/
       	else {
	    $dbCheckResult['flag'] = true;
	    return $dbCheckResult;
	}
	$dbCheckResult['flag'] = false;
	$dbCheckResult['error_msg'] = $error_msg;
	$dbCheckResult['error_msg_info'] = $error_msg_info;
	return $dbCheckResult;
    }
}
