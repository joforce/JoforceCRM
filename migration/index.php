<?php
chdir (dirname(__FILE__) . '/..');
include_once('includes/utils/utils.php');
include_once("modules/Emails/mail.php");
include_once('includes/logging.php');
include_once('includes/http/Session.php');
include_once('version.php');
include_once('MySQLSearchReplace.php');
include_once('config/config.inc.php');
include_once('includes/utils/utils.php');

require_once('vendor/autoload.php');
//Overrides GetRelatedList : used to get related query
//TODO : Eliminate below hacking solution
include_once 'config/config.php';
//require_once('routes.php');

include_once 'vtlib/Head/Module.php';
include_once 'includes/main/WebUI.php';
global $adb, $dbconfig, $root_directory;
global $log;
global $site_URL;
session_start();
//echo '<pre>'; print_r($_POST); die;
//error_reporting(E_ALL);
//ini_set('display_errors','on');
if($_POST['FinishMigration'] && $jo_current_version == '1.3') {
	//rename tables
	$query = "show tables";
        $result = $adb->pquery($query, array());
        if($adb->num_rows($result) >= 1)
        {
                $log->debug("get old tables");
                while($result_set = $adb->fetch_array($result))
                {
                        $prev_table = $result->fields[0];
                        $new_table = str_replace('vtiger','jo',$result->fields[0]);
                        $rename_query = "rename table $prev_table to $new_table";
                        $adb->pquery($rename_query, array());

                }
                $log->debug("all tables were renamed vtiger_ to jo_");
	}
	//rename tables

	//Update tables
	$config = array
	(
	    'server'   => $dbconfig['db_server'],
	    'user'     => $dbconfig['db_username'],
	    'password' => $dbconfig['db_password'],
	    'db'       => $dbconfig['db_name'],
	);

	$freplace = array(
		'Vtiger' => 'Head',
		'include/' => 'includes/',
		'vtiger_' => 'jo_',	
	);
	foreach($freplace as $search => $replace){
		$dbreplace = (new MySQLSearchReplace($config, $search, $replace))->startFindReplace();
	}
	
	$settings_field_name_array = array(
					0 => 'LBL_USERS',
					1 => 'LBL_ROLES',
					2 => 'LBL_PROFILES',
					3 => 'USERGROUPLIST',
					4 => 'LBL_SHARING_ACCESS',
					5 => 'LBL_LOGIN_HISTORY_DETAILS',
					6 => 'VTLIB_LBL_MODULE_MANAGER',
					7 => 'LBL_PICKLIST_EDITOR',
					8 => 'LBL_PICKLIST_DEPENDENCY',
					9 => 'LBL_COMPANY_DETAILS',
					10 => 'LBL_MAIL_SERVER_SETTINGS',
					11 => 'LBL_CURRENCY_SETTINGS',
					12 => 'LBL_TAX_SETTINGS',
					13 => 'INVENTORYTERMSANDCONDITIONS',
					14 => 'LBL_CUSTOMIZE_MODENT_NUMBER',
					15 => 'LBL_MAIL_SCANNER',
					16 => 'LBL_LIST_WORKFLOWS',
					17 => 'Configuration Editor',
					18 => 'Scheduler',
					19 => 'LBL_PBXMANAGER',
					20 => 'ModTracker',
					21 => 'LBL_CUSTOMER_PORTAL',
					22 => 'Webforms',
					23 => 'LBL_EDIT_FIELDS',
					24 => 'LBL_LEAD_MAPPING',
					25 => 'LBL_OPPORTUNITY_MAPPING',
					26 => 'My Preferences',
					27 => 'Calendar Settings',
					28 => 'LBL_MY_TAGS',
					29 => 'LBL_GOOGLE',
					30 => 'Google Settings'
					);
	
	$settings_field_link_array = array(
				'LBL_USERS' => 'Users/Settings/List',
				'LBL_ROLES' => 'Roles/Settings/Index',
				'LBL_PROFILES' => 'Profiles/Settings/List',
				'USERGROUPLIST' => 'Groups/Settings/List',
				'LBL_SHARING_ACCESS' => 'SharingAccess/Settings/Index',
				'LBL_LOGIN_HISTORY_DETAILS' => 'LoginHistory/Settings/List',
				'VTLIB_LBL_MODULE_MANAGER' => 'ModuleManager/Settings/List',
				'LBL_PICKLIST_EDITOR' => 'Picklist/Settings/Index',
				'LBL_PICKLIST_DEPENDENCY' => 'PickListDependency/Settings/List',
				'LBL_COMPANY_DETAILS' => 'Head/Settings/CompanyDetails',
				'LBL_MAIL_SERVER_SETTINGS' => 'Head/Settings/OutgoingServerDetail',
				'LBL_CURRENCY_SETTINGS' => 'Currency/Settings/List',
				'LBL_TAX_SETTINGS' => 'Head/Settings/TaxIndex',
				'INVENTORYTERMSANDCONDITIONS' => 'Head/Settings/TermsAndConditionsEdit',
				'LBL_CUSTOMIZE_MODENT_NUMBER' => 'Head/Settings/CustomRecordNumbering',
				'LBL_MAIL_SCANNER' => 'MailConverter/Settings/List',
				'LBL_LIST_WORKFLOWS' => 'Workflows/Settings/List',
				'Configuration Editor' => 'Head/Settings/ConfigEditorDetail',
				'Scheduler' => 'CronTasks/Settings/List',
				'LBL_PBXMANAGER' => 'PBXManager/Settings/Index',
				'ModTracker' => 'ModTracker/BasicSettings/Settings/ModTracker',
				'LBL_CUSTOMER_PORTAL' => 'CustomerPortal/Settings/Index',
				'Webforms' => 'Webforms/Settings/List',
				'LBL_EDIT_FIELDS' => 'LayoutEditor/Settings/Index',
				'LBL_LEAD_MAPPING' => 'Leads/Settings/MappingDetail',
				'LBL_OPPORTUNITY_MAPPING' => 'Potentials/Settings/MappingDetail',
				'My Preferences' => 'Users/Settings/PreferenceDetail/1',
				'Calendar Settings' => 'Users/Settings/Calendar/1',
				'LBL_MY_TAGS' => 'Tags/Settings/List/1',
				'LBL_GOOGLE' => 'Contacts/Settings/Extension/Google/Index/settings',
				'Google Settings' => 'Google/Settings/GoogleSettings'
					);

	$settings_field_icon_array = array(
				'LBL_USERS' => 'fa fa-user',
				'LBL_ROLES' => 'fa fa-registered',
				'LBL_PROFILES' => 'fa fa-user-plus',
				'USERGROUPLIST' => 'fa fa-users',
				'LBL_SHARING_ACCESS' => 'fa fa-share-alt',
				'LBL_LOGIN_HISTORY_DETAILS' => 'fa fa-history',
				'VTLIB_LBL_MODULE_MANAGER' => 'fa fa-chain',
				'LBL_PICKLIST_EDITOR' => 'fa fa-file-text-o',
				'LBL_PICKLIST_DEPENDENCY' => 'fa fa-list',
				'LBL_COMPANY_DETAILS' => 'fa fa-building-o',
				'LBL_MAIL_SERVER_SETTINGS' => 'fa fa-server ',
				'LBL_CURRENCY_SETTINGS' => 'fa fa-usd',
				'LBL_TAX_SETTINGS' => 'fa fa-money',
				'INVENTORYTERMSANDCONDITIONS' => 'fa fa-info-circle',
				'LBL_CUSTOMIZE_MODENT_NUMBER' => 'fa fa-sort-numeric-desc',
				'LBL_MAIL_SCANNER' => 'fa fa-envelope-o',
				'LBL_LIST_WORKFLOWS' => 'fa fa-sitemap',
				'Configuration Editor' => 'fa fa-pencil-square-o',
				'Scheduler' => 'fa fa-clock-o',
				'LBL_PBXMANAGER' => 'fa fa-phone',
				'ModTracker' => 'set-IcoLoginHistory.gif',
				'LBL_CUSTOMER_PORTAL' => 'fa fa-list-alt',
				'Webforms' => 'fa fa-file-zip-o',
				'LBL_EDIT_FIELDS' => 'fa fa-codepen',
				'LBL_LEAD_MAPPING' => 'fa fa-exchange',
				'LBL_OPPORTUNITY_MAPPING' => 'fa fa-map-signs',
				'My Preferences' =>  'fa fa-user',
				'Calendar Settings' => 'fa fa-calendar-check-o',
				'LBL_MY_TAGS' => 'fa fa-tags',
				'LBL_GOOGLE' => 'fa fa-google',
				'Google Settings' => 'fa fa-cogs'
					);
	//update jo_settings_field tables 
	foreach($settings_field_name_array as $field_name){
		$adb->pquery("update jo_settings_field set linkto=? , iconpath = ? where name= ? ", array( $settings_field_link_array[$field_name], $settings_field_icon_array[$field_name], $field_name) );
	}

	$adb->pquery("delete from jo_settings_field where name='LBL_EXTENSION_STORE'",array());
	$adb->pquery("delete from jo_settings_field where name='LBL_MENU_EDITOR'",array());
	$adb->pquery("delete from jo_settings_field where name='LBL_DEFAULT_MODULE_VIEW'",array());

	$adb->pquery("insert into jo_settings_blocks values(13,'LBL_JOFORCE',11)",array());
	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,iconpath,description,linkto,sequence) values(?,?,?,?,?,?,?)",array($fieldid,13,'Contributors', 'fa fa-plus-square','Contributors','Head/Settings/Credits',1));
	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,iconpath,description,linkto,sequence) values(?,?,?,?,?,?,?)",array($fieldid,13,'License','fa fa-exclamation-triangle','License','Head/Settings/License',2));

	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,iconpath,description,linkto,sequence) values(?,?,?,?,?,?,?)",array($fieldid,6,'Module Studio','fa fa-edit','Module Studio','ModuleDesigner/Settings/Index',3));
	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,iconpath,description,linkto,sequence) values(?,?,?,?,?,?,?)", array($fieldid, 11, 'LBL_MENU_MANAGEMENT','fa fa-bars', 'Menu management', 'MenuManager/Settings/Index', 4));
	//Service Contracts workflow deletion
	$adb->pquery("delete from jo_eventhandlers where handler_class='ServiceContractsHandler'",array());


	$unwantedmodule =  array(
		'Faq','ServiceContracts','Assets','SMSNotifier','ExtensionStore','Rss'
	);
	foreach($unwantedmodule as $key => $module){
		$adb->pquery("delete from jo_tab  where name = ?",array($module));	
	}

	//add default landing page to users table and field table
	$adb->pquery( "ALTER TABLE jo_users ADD COLUMN default_landing_page VARCHAR(200) DEFAULT 'Home'", array() );
	
	$usermoreinfoblock = $adb->getUniqueID('jo_blocks');
	$field_id = $adb->getUniqueID("jo_field");
	$adb->pquery("insert into jo_field values(29, " . $field_id . ", 'default_landing_page', 'jo_users', 1, 16, 'default_landing_page', 'Default Landing Page', 1, 2, 'Home', 100, 20, " .$usermoreinfoblock . " ,1, 'V~O',1,0,'BAS', 1, '',0, '', 0)", array() );

	$adb->pquery( "UPDATE jo_users SET default_landing_page = 'Home'", array() );
	
	// Delete from unwanted dashboard entry from the jo_dashboard_tabs
        $adb->pquery('delete from jo_dashboard_tabs where tabname = ?', array('Default'));

	//Delete unwanted checks
	$handler_path_array =array( 0 => 'modules/Head/handlers/CheckDuplicateHandler.php',
				    1 => 'modules/Head/handlers/CheckDuplicateHandler.php',
				    2 => 'modules/Head/handlers/FollowRecordHandler.php'
				);
	foreach($handler_path_array as $handler_path) {
		$adb->pquery("DELETE from jo_eventhandlers WHERE handler_path = ?", array($handler_path));
	}

        //delete unwanted extension links from jo_links table
        $adb->pquery('DELETE from jo_links WHERE linklabel = ?', array('Google Contacts'));
        $adb->pquery('DELETE from jo_links WHERE linklabel = ?', array('Google Calendar'));

	 // Centralize user field table for easy query with context of user across module
    	$generalUserFieldTable = 'jo_crmentity_user_field';
    	if (!Head_Utils::CheckTable($generalUserFieldTable)) {
        	Head_Utils::CreateTable($generalUserFieldTable,
                	'(`recordid` INT(19) NOT NULL,
	                `userid` INT(19) NOT NULL,
        	        `starred` VARCHAR(100) DEFAULT NULL)', true);
	}

    	if (Head_Utils::CheckTable($generalUserFieldTable)) {
        	$indexRes = $adb->pquery("SHOW INDEX FROM $generalUserFieldTable WHERE NON_UNIQUE=? AND KEY_NAME=?", array('1', 'record_user_idx'));
        	if ($adb->num_rows($indexRes) < 2) {
            		$adb->pquery('ALTER TABLE jo_crmentity_user_field ADD CONSTRAINT record_user_idx UNIQUE KEY(recordid, userid)', array());
        	}

        	$checkUserFieldConstraintExists = $adb->pquery('SELECT DISTINCT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE table_name=? AND CONSTRAINT_SCHEMA=?', array($generalUserFieldTable, $adb->dbName));
        	if ($adb->num_rows($checkUserFieldConstraintExists) < 1) {
            		$adb->pquery('ALTER TABLE jo_crmentity_user_field ADD CONSTRAINT `fk_jo_crmentity_user_field_recordid` FOREIGN KEY (`recordid`) REFERENCES `jo_crmentity`(`crmid`) ON DELETE CASCADE', array());
        	}
    	}

    	echo '<br>Succesfully centralize user field table for easy query with context of user across module<br>';
	// Centralize user field table for easy query with context of user across module

	$adb->pquery("UPDATE jo_field SET tablename = ? where fieldname = ?", array($generalUserFieldTable, 'starred'));

	if (!Head_Utils::CheckTable('jo_mailscanner')) {
		Head_Utils::CreateTable('jo_mailscanner', 
				"(`scannerid` INT(11) NOT NULL AUTO_INCREMENT,
				`scannername` VARCHAR(30) DEFAULT NULL,
				`server` VARCHAR(100) DEFAULT NULL,
				`protocol` VARCHAR(10) DEFAULT NULL,
				`username` VARCHAR(255) DEFAULT NULL,
				`password` VARCHAR(255) DEFAULT NULL,
				`ssltype` VARCHAR(10) DEFAULT NULL,
				`sslmethod` VARCHAR(30) DEFAULT NULL,
				`connecturl` VARCHAR(255) DEFAULT NULL,
				`searchfor` VARCHAR(10) DEFAULT NULL,
				`markas` VARCHAR(10) DEFAULT NULL,
				`isvalid` INT(1) DEFAULT NULL,
				`scanfrom` VARCHAR(10) DEFAULT 'ALL',
				`time_zone` VARCHAR(10) DEFAULT NULL,
				PRIMARY KEY (`scannerid`)
			  ) ENGINE=InnoDB DEFAULT CHARSET=utf8", true);
	}

	$updateModulesList = array(	'Project'		=> 'packages/head/optional/Projects.zip',
								'Google'		=> 'packages/head/optional/Google.zip',
								'ExtensionStore'=> 'packages/head/marketplace/ExtensionStore.zip');
	foreach ($updateModulesList as $moduleName => $packagePath) {
		$moduleInstance = Head_Module::getInstance($moduleName);
		if($moduleInstance) {
			updateVtlibModule($moduleName, $packagepath);
		}
	}

    	if (!Head_Utils::CheckTable('jo_loginhistory')) {
       		$adb->pquery("CREATE TABLE `jo_loginhistory` (
                    `login_id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_name` varchar(255) DEFAULT NULL,
                    `user_ip` varchar(25) NOT NULL,
                    `logout_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `login_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `status` varchar(25) DEFAULT NULL,
                    PRIMARY KEY (`login_id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1", array());
    	}

    include_once 'vtlib/Head/Module.php';
    $moduleLists = 'MailManager';
    $module = Head_Module::getInstance($moduleLists);
    if ($module) $module->delete();

	// Update the version of the joforce
	$adb->pquery("UPDATE jo_version SET old_version = ? , current_version = ? where id =?", array( $jo_current_version, 1.3, 1 ));
	//$adb->pquery(" ");
	//Modules creation and updation

	updateVtlibModule('Import', 'packages/head/mandatory/Import.zip');
	updateVtlibModule('PBXManager', 'packages/head/mandatory/PBXManager.zip');
	updateVtlibModule('Mobile', 'packages/head/mandatory/Mobile.zip');
	updateVtlibModule('ModTracker', 'packages/head/mandatory/ModTracker.zip');
	updateVtlibModule('Services', 'packages/head/mandatory/Services.zip');
	updateVtlibModule('WSAPP', 'packages/head/mandatory/WSAPP.zip');
	updateVtlibModule('Arabic_ar_ae', 'packages/head/optional/Arabic_ar_ae.zip');
	updateVtlibModule('Assets', 'packages/head/optional/Assets.zip');
	updateVtlibModule('EmailTemplates', 'packages/head/optional/EmailTemplates.zip');
	updateVtlibModule('CustomerPortal', 'packages/head/optional/CustomerPortal.zip');
	updateVtlibModule('Google', 'packages/head/optional/Google.zip');
	updateVtlibModule('ModComments', 'packages/head/optional/ModComments.zip');
	updateVtlibModule('Projects', 'packages/head/optional/Projects.zip');
	updateVtlibModule('RecycleBin', 'packages/head/optional/RecycleBin.zip');
	updateVtlibModule('SMSNotifier', "packages/head/optional/SMSNotifier.zip");
	updateVtlibModule("Sweden_sv_se","packages/head/optional/Sweden_sv_se.zip");
	updateVtlibModule("Webforms","packages/head/optional/Webforms.zip");
	updateVtlibModule("Arabic_ar_ae","packages/head/optional/Arabic_ar_ae.zip");
	updateVtlibModule("BrazilianLanguagePack_bz_bz","packages/head/optional/BrazilianLanguagePack_bz_bz.zip");
	updateVtlibModule("BritishLanguagePack_br_br","packages/head/optional/BritishLanguagePack_br_br.zip");
	updateVtlibModule("French","packages/head/optional/French.zip");
	updateVtlibModule("Hungarian","packages/head/optional/Hungarian.zip");
	updateVtlibModule("ItalianLanguagePack_it_it","packages/head/optional/ItalianLanguagePack_it_it.zip");
	updateVtlibModule("MexicanSpanishLanguagePack_es_mx","packages/head/optional/MexicanSpanishLanguagePack_es_mx.zip");
	updateVtlibModule("Hungarian","packages/head/optional/Hungarian.zip");
	updateVtlibModule("PolishLanguagePack_pl_pl","packages/head/optional/PolishLanguagePack_pl_pl.zip");
	updateVtlibModule("RomanianLanguagePack_rm_rm","packages/head/optional/RomanianLanguagePack_rm_rm.zip");
	updateVtlibModule("Russian","packages/head/optional/Russian.zip");
	updateVtlibModule("TurkishLanguagePack_tr_tr","packages/head/optional/TurkishLanguagePack_tr_tr.zip");
	installVtlibModule('ModuleDesigner', 'packages/head/optional/ModuleDesigner.zip');
	//Modules creation and updation

	//our joforce modules
	installVtlibModule('AddressLookup', 'packages/head/migrate/AddressLookup.zip');
	installVtlibModule('DuplicateCheck', 'packages/head/migrate/DuplicateCheck.zip');
	installVtlibModule('EmailPlus', 'packages/head/migrate/EmailPlus.zip');
	installVtlibModule('PDFMaker', 'packages/head/migrate/PDFMaker.zip');

	//create htaccess file
	crete_htacces_file();
        session_unset();
        session_destroy();
        header ('Location: '.$site_URL.'/index.php'); die();
}
?>
<?php if(!$_POST['startMigration']){?>
<html>
    <head>
		<title>Joforce CRM Setup</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="resources/js/jquery-min.js"></script>
		<link href="resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="resources/css/mkCheckbox.css" rel="stylesheet">
		<link href="resources/css/style.css" rel="stylesheet">
    </head>
    <body>
		<div class="container-fluid page-container">
			<div class="row-fluid">
				<div class="span6">
					<div class="logo">
						<img src="resources/images/logo.png" alt="Logo"/>
					</div>
				</div>
				<div class="span6">
					<div class="head pull-right">
						<h3>Migration Wizard</h3>
					</div>
				</div>
			</div>
			<div class="row-fluid main-container">
				<div class="span12 inner-container">
					<div class="row-fluid">
						<div class="span10">
							<h4 class=""> Welcome to Joforce Migration </h4>
						</div>
					</div>
					<hr>
					<div class="row-fluid">
						<div class="span12">
							<div style = 'margin-left: 20%'>
                                <br> <br>
									<strong> Warning: </strong>Please note that it is not possible to revert back to Vtiger v7.0 after the upgrade to Joforce v1.2 <br>
									So, it is important to take a backup of the Vtiger v7.0 files and database before upgrading.</p><br>
								<form action="index.php" method="POST">
									<div><input type="checkbox" id="checkBox1" name="checkBox1"/><div class="chkbox"></div> Backup of source folder </div><br>
									<div><input type="checkbox" id="checkBox4" name="checkBox4"/><div class="chkbox"></div> Backup of database </div><br>

									<div><input type="checkbox" id="checkBox2" name="checkBox2"/><div class="chkbox"></div> Copy the config.inc.php from root directory to <strong>config/</strong> folder and Change the following values in the <strong>config/config.inc.php file</strong> </div><br>
									<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> Change the <strong>$site_URL</strong> </div><br>
                                                                        <div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> Change the <strong>$root_directory</strong></div><br>
									<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> Change the <strong>include_once 'vtigerversion.php'</strong> to <strong>include_once 'version.php' </strong></div><br>
									<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> Change the value of $includeDirectory from <strong>$root_directory.'include/'</strong> to <strong>$root_directory.'includes/'</strong></div><br>
									<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> Change the last line of the file from <strong>include_once 'config.security.php'</strong> to <strong>include_once 'config/config.security.php' </strong></div><br>
		<?php $filename = '.htaccess';
            if (file_exists($filename)) {
                    if (is_writable($filename)) {
			?><input type='hidden' name='htaccess' id='htaccess' value='true' />
 <?php }
			?><input type='hidden' name='htaccess' id='htaccess' value='false' />
<?php }
	else { 
			?><input type='hidden' name='htaccess' id='htaccess' value='false' />
<?php } ?>
	 
								 <div><b>Create a .htaccess file in your root directory with writable access</b> </div><br>



                                  <div><input type="checkbox" id="checkBox3" name="checkBox3"/><div class="chkbox"></div> Replace your storage folder </div><br>
				<div><input type="checkbox" id="checkBox6" name="checkBox6"/><div class="chkbox"></div> Replace your user_privileges folder </div><br>


									<div class="button-container">
										<input type="submit" class="btn btn-large btn-primary" id="startMigration" name="startMigration" value="Next" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script>
				$(document).ready(function(){

                                        $('input[name="startMigration"]').click(function(){
                                                if($("#checkBox1").is(':checked') == false || $("#checkBox2").is(':checked') == false || $("#checkBox3").is(':checked') == false  || $("#checkBox4").is(':checked') == false || $("#checkBox6").is(':checked') == false ){
                                                        alert('Before starting migration, please take your database and source backup');
                                                        return false;
                                                }
					var ht = $('#htaccess').val();
					if(ht == 'false') {
                                                        alert('Please Create htaccess file in your Root Directory with writable access');
                                                        return false;

					}
                                                return true;
                                        });

					/*$('input[name="startMigration"]').click(function(){
                        var confirm_migration = confirm('Are you sure you want to start the migration ?');
                        if(!confirm_migration)  {
							return false;
						}
						return true;
					});*/
				});
				
			</script>
    </body>
</html>
<?php }?>
<?php if($_POST['startMigration']){?>
<html>
    <head>
		<title>Joforce CRM Setup</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="resources/js/jquery-min.js"></script>
		<link href="resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="resources/css/mkCheckbox.css" rel="stylesheet">
		<link href="resources/css/style.css" rel="stylesheet">
    </head>
    <body>
		<div class="container-fluid page-container">
			<div class="row-fluid">
				<div class="span6">
					<div class="logo">
						<img src="resources/images/logo.png" alt="Logo"/>
					</div>
				</div>
				<div class="span6">
					<div class="head pull-right">
						<h3>Migration Wizard</h3>
					</div>
				</div>
			</div>
			<div class="row-fluid main-container">
				<div class="span12 inner-container">
					<div class="row-fluid">
						<div class="span10">
							<h4 class=""> Welcome to Joforce Migration </h4>
						</div>
					</div>
					<hr>
					<div class="row-fluid">
						<div class="span12">
						<div id="progressIndicator" class="row main-container hide" style="padding-left:49px;">
						<div class="inner-container">
						<div class="inner-container">
						<div class="row" style="text-align:center;">
						<h3>Migration in progress...</h3><br>
						<img src="install_loading.gif"/>
						<h6>Please Wait.... </h6>
						</div>
						</div>
						</div>
						</div>


							<div style = 'margin-left: 20%' class='cont'>
                                				<form action="index.php" method="POST">
										
									
								<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div>  <strong>You agree that you’ve backed up the necessary details before making any changes.</strong> </div><br><br>
								<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span><div class="chkbox"></div> <strong>We hope it doesn’t happen, but Joforce is not responsible for any loss.</strong> </div><br>
									<br><br><br>
									<div class="button-container">
										<input type="submit" class="btn btn-large btn-primary" id="FinishMigration" name="FinishMigration" value="Start Migration" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script>
				$(document).ready(function(){

                                        /*$('input[name="FinishMigration"]').click(function(){
                                                if($("#checkBox1").is(':checked') == false || $("#checkBox2").is(':checked') == false || $("#checkBox3").is(':checked') == false  || $("#checkBox4").is(':checked') == false ){
                                                        alert('Before starting migration, please take your database and source backup');
                                                        return false;
                                                }
                                                return true;
                                        });*/

					$('input[name="FinishMigration"]').click(function(){
                        var confirm_migration = confirm('Are you sure you want to start the migration ?');
                        if(!confirm_migration)  {

							return false;
						}
				$('.cont').hide();
			$('#progressIndicator').show();
						return true;
					});
				});
				
			</script>
    </body>
</html>
<?php } ?>
