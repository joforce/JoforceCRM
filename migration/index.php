<?php
chdir (dirname(__FILE__) . '/..');

$site_url=explode("migration/",$_SERVER['REQUEST_URI']);
if (!isset($_GET['reload'])) {
	if($_SERVER['REQUEST_URI'] === $site_url[0].$site_url[1].'migration/') {
		header('Refresh:0');
	echo '<meta http-equiv=Refresh content="0;url=?reload=1">';
	
	}
	elseif($_SERVER['REQUEST_URI'] === $site_url[0].$site_url[1].'migration/?reload=1') {
		header('Refresh:0');
	echo '<meta http-equiv=Refresh content="0;url=?reload=1">';
	}
}

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

include_once 'vtlib/Head/Module.php';
include_once 'includes/main/WebUI.php';
global $adb, $dbconfig, $root_directory, $site_URL, $log;
session_start();
if($_POST['FinishMigration'] && $jo_current_version == '2.0') {
	
	$sel_result =  $adb->pquery("SELECT table_name FROM information_schema.tables WHERE table_schema='".$dbconfig['db_name']."' and table_name like '%seq'",array());
	$res_cnt = $adb->num_rows($sel_result);
	$adb->pquery("DROP TABLE IF EXISTS `jo_tab_sequence`;");
	 $adb->pquery("CREATE TABLE `jo_tab_sequence` (`sequenceid` int(11) NOT NULL AUTO_INCREMENT, `table_name` varchar(255) DEFAULT '',  `sequence` varchar(255) NOT NULL,  PRIMARY KEY (`sequenceid`)) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;");
	if($res_cnt >= 0) {
		for($i=0;$i<$res_cnt;$i++) {
			$id_field_value = $adb->query_result($sel_result,$i,'table_name');
			$seq_table =  $adb->pquery("SELECT * FROM ".$id_field_value);
			// echo"<pre>";print_r($seq_table);die("dasda");
			$seq_cnt = $adb->num_rows($seq_table);
			$row_id = 0;
			
			while ($myrow = $adb->fetch_array($seq_table)) {
				$row_id = $myrow['id'];
			}
			$tab_seq_table =  $adb->pquery("SELECT * FROM jo_tab_sequence where table_name=?",array($id_field_value));
			$tab_seq_cnt = $adb->num_rows($tab_seq_table);
			// echo"<pre>";print_r($tab_seq_cnt);die("dasda");
			if($tab_seq_cnt >= 0){
				$adb->pquery("update jo_tab_sequence set sequence= ? where table_name=?",array($row_id,$id_field_value));
			}else{
				$adb->pquery("insert into jo_tab_sequence values(?,?,?)",array('',$id_field_value,$row_id));
			}
			$adb->pquery("drop table ".$id_field_value);
		}
	}

	$except_list = array('./user_privileges/audit_trail.php','./user_privileges/default_module_view.php','./user_privileges/enable_backup.php','./user_privileges/index.html','./user_privileges/permissions.php','./user_privileges/portal_user_settings.php');
	$PRIVILEGE_ATTRS = array('is_admin', 'current_user_role', 'current_user_parent_role_seq',
    'current_user_profiles', 'profileGlobalPermission', 'profileTabsPermission', 'profileActionPermission',
    'current_user_groups', 'subordinate_roles', 'parent_roles', 'subordinate_roles_users', 'user_info'
 	 );
		$SHARING_ATTRS = array('defaultOrgSharingPermission','related_module_share');
		$privileges = array();
		$i = 1;
		$adb->pquery("DROP TABLE IF EXISTS `jo_privileges`;");
		$adb->pquery("CREATE TABLE `jo_privileges` (
		`privilegesid` int(11) NOT NULL AUTO_INCREMENT,
		`user_id` int(11) NOT NULL,
		`user_privilege` text COLLATE utf8_unicode_ci,
		`sharing_privilege` text COLLATE utf8_unicode_ci NOT NULL,
		`updated_at` datetime DEFAULT NULL,
		PRIMARY KEY (`privilegesid`)
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		foreach(glob('./user_privileges/*.*') as $filename){
		if(!in_array($filename, $except_list)){

			if (strpos($filename, 'user_privileges_') !== false) {
			
			require($filename);
			$privilege = '';
			
			foreach ($PRIVILEGE_ATTRS as $attr) {
			if($attr == 'currency_symbol'){
				$$attr = htmlspecialchars($$attr);
			}
			if (isset($attr))
				$privilege->$attr = $$attr;
			}

			$obj_json_format = json_encode($privilege);
		
			$userid = $privilege->user_info['id'];

			$shared_filename = './user_privileges/sharing_privileges_'.$userid.'.php';
			require($shared_filename);
			$shared_privilege = '';
			foreach ($SHARING_ATTRS as $attr) {
			if (isset($attr) && $$attr != ''){
				$shared_privilege->$attr = $$attr;
			}
				
			}
			$shared_json = json_encode($shared_privilege);
			$user_query =  $adb->pquery("SELECT * FROM jo_privileges where user_id=?",array($userid));
			$user_query_cnt = $adb->num_rows($user_query);
			
			if($user_query_cnt > 0){
				$adb->pquery("update jo_privileges set user_privilege= ?,sharing_privilege=? where user_id=?",array($obj_json_format,$shared_json,$userid));
				}else{
				$adb->pquery("insert into jo_privileges values(?,?,?,?,?)",array('',$userid,$obj_json_format,$shared_json,''));
				}
		
			$i = $i+1;
			}
		}
		}
		// $adb->pquery("DROP TABLE IF EXISTS `jo_extnstore_users`;");
		$adb->pquery("CREATE TABLE `jo_extnstore_users` (`id` int(11) NOT NULL AUTO_INCREMENT,  `username` varchar(50) DEFAULT NULL,  `password` varchar(75) DEFAULT NULL,  `instanceurl` varchar(255) DEFAULT NULL,  `createdon` datetime DEFAULT NULL,  `deleted` int(1) NOT NULL DEFAULT '0',  PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		$adb->pquery("CREATE TABLE `jo_masqueradeuserdetails` (
  `record_id` int(11) NOT NULL,
  `portal_id` int(11) NOT NULL,
  `masquerade_module` varchar(255) DEFAULT NULL,
  `support_start_date` datetime DEFAULT NULL,
  `support_end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$adb->pquery("CREATE TABLE `jo_notification_manager` (
  `id` int(20) NOT NULL,
  `global` tinyint NOT NULL,
  `notificationlist` varchar(100000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$adb->pquery("CREATE TABLE `jo_notifyauthtoken` (
  `userid` int(19) NOT NULL,
  `token` varchar(255) NOT NULL,
  `devicetype` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$adb->pquery("CREATE TABLE `jo_webhooks` (
  `id` int(19) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `enabled` int(1) NOT NULL DEFAULT '1',
  `targetmodule` varchar(50) NOT NULL,
  `description` text,
  `events` varchar(50) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `fields` blob,
  `ownerid` int(19) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `webhookname` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$adb->pquery("DROP TABLE IF EXISTS `jo_settings_field`;");
$adb->pquery("DROP TABLE IF EXISTS `jo_settings_blocks`;");
$adb->pquery("CREATE TABLE `jo_settings_blocks` (
  `blockid` int(19) NOT NULL,
  `label` varchar(250) DEFAULT NULL,
  `sequence` int(19) DEFAULT NULL,
  PRIMARY KEY (`blockid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$adb->pquery("INSERT INTO `jo_settings_blocks` VALUES (1,'LBL_USER_MANAGEMENT',1),(4,'LBL_OTHER_SETTINGS',10),(5,'LBL_INTEGRATION',8),(6,'LBL_MODULE_MANAGER',2),(7,'LBL_AUTOMATION',3),(8,'LBL_CONFIGURATION',4),(9,'LBL_MARKETING_SALES',5),(10,'LBL_INVENTORY',6),(11,'LBL_MY_PREFERENCES',7),(12,'LBL_EXTENSIONS',9),(13,'LBL_JOFORCE',12),(14,'LBL_MARKETPLACE',11),(15,'LBL_COMPANY_INFO',13),(16,'LBL_LOGS',0);");
$adb->pquery("CREATE TABLE `jo_settings_field` (
  `fieldid` int NOT NULL,
  `blockid` int DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `iconpath` varchar(300) DEFAULT NULL,
  `description` text,
  `setting_detail` text,
  `linkto` text,
  `sequence` int DEFAULT NULL,
  `active` int DEFAULT '0',
  `pinned` int DEFAULT '0',
  PRIMARY KEY (`fieldid`),
  KEY `fk_1_jo_settings_field` (`blockid`),
  CONSTRAINT `fk_1_jo_settings_field` FOREIGN KEY (`blockid`) REFERENCES `jo_settings_blocks` (`blockid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;");
$adb->pquery("CREATE TABLE `jo_user_menu_arrangement` (
  `um_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `default_sections` text COLLATE utf8_unicode_ci,
  `main_menu` text COLLATE utf8_unicode_ci,
  `module_apps` text COLLATE utf8_unicode_ci,
  `notifications` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`um_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
$adb->pquery("INSERT INTO `jo_settings_field` (fieldid,blockid,name,iconpath,description,setting_detail,linkto,sequence,active,pinned) VALUES (1,1,'LBL_USERS','fa fa-user','LBL_USER_DESCRIPTION','Users provide you the detailed view of your user like Name, Mail, Role, Username, Active Status, etc. Also, You can import your user data by field mapping.','Users/Settings/List',1,0,1),(2,1,'LBL_ROLES','fa fa-registered','LBL_ROLE_DESCRIPTION','You can create roles and assign them to the required users. Also can give the privileges in the modules list according to the roles.','Roles/Settings/Index',2,0,0),(4,1,'USERGROUPLIST','fa fa-users','LBL_GROUP_DESCRIPTION','Creating a group with the respective roles and users will allow you to assign tasks across them.','Groups/Settings/List',5,0,0),(5,1,'LBL_SHARING_ACCESS','fa fa-share-alt','LBL_SHARING_ACCESS_DESCRIPTION','Create privileges that apply to public and private sharing. You can also create custom rules by clicking advanced sharing rules.','SharingAccess/Settings/Index',4,0,0),(6,16,'LBL_LOGIN_HISTORY_DETAILS','fa fa-history','LBL_LOGIN_HISTORY_DESCRIPTION','Get the detailed user login history through audit which includes the user name, user ip address sign-in and sign out time.','LoginHistory/Settings/List',6,0,0),(7,6,'VTLIB_LBL_MODULE_MANAGER','fa fa-chain','VTLIB_LBL_MODULE_MANAGER_DESCRIPTION','In Joforce CRM, the data is categorized into divisions based on their similarity. Example: Leads, Contacts, Deals, Quotes, Organizations etc are the modules which contain all your business details. It displays the list of all modules available in joforce.','ModuleManager/Settings/List',1,0,1),(8,8,'LBL_PICKLIST_EDITOR','fa fa-file-text-o','LBL_PICKLIST_DESCRIPTION','Picklists are drop-down fields where you can select a value from a list of options.','Picklist/Settings/Index',6,0,1),(9,8,'LBL_PICKLIST_DEPENDENCY','fa fa-list','LBL_PICKLIST_DEPENDENCY_DESCRIPTION','User-configurable filters which update a picklist based on the value of a second field.','PickListDependency/Settings/List',7,0,0),(10,8,'LBL_COMPANY_DETAILS','fa fa-building-o','LBL_COMPANY_DESCRIPTION','Joforce allows you to customize the setup by adding your company info and logo.','Head/Settings/CompanyDetails',1,0,0),(11,8,'LBL_MAIL_SERVER_SETTINGS','fa fa-server','LBL_MAIL_SERVER_DESCRIPTION','It handles the delivery of your emails by sending them from your client or webmail to an inbound server that can then send them to the recipient.','Head/Settings/OutgoingServerDetail',4,0,0),(12,8,'LBL_CURRENCY_SETTINGS','fa fa-usd','LBL_CURRENCY_DESCRIPTION','Joforce lets you configure national and international currencies. Having this capability allows you to carry out international transactions.','Currency/Settings/List',3,0,0),(13,15,'LBL_TAX_SETTINGS','fa fa-money','LBL_TAX_DESCRIPTION','We have provided you with the tax calculations for our sales and services. We also provide you with the detailed calculations for the charges we include.','Head/Settings/TaxIndex',1,0,0),(15,15,'INVENTORYTERMSANDCONDITIONS','fa fa-info-circle','LBL_INV_TANDC_DESCRIPTION','We have some terms and conditions for invoices, Quotes, Purchase orders and Sales orders.','Head/Settings/TermsAndConditionsEdit',2,0,0),(16,6,'LBL_CUSTOMIZE_MODENT_NUMBER','fa fa-sort-numeric-desc','LBL_CUSTOMIZE_MODENT_NUMBER_DESCRIPTION','You can do Custom numbering of records depending on your requirements with Joforce.','Head/Settings/CustomRecordNumbering',4,0,0),(17,7,'LBL_MAIL_SCANNER','fa fa-envelope-o','LBL_MAIL_SCANNER_DESCRIPTION','With Mail Converter, you can manage your incoming emails and take the appropriate action.','MailConverter/Settings/List',5,0,0),(18,7,'LBL_LIST_WORKFLOWS','fa fa-sitemap','LBL_LIST_WORKFLOWS_DESCRIPTION','By automating your workflow, you can streamline your business process within joforce.','Workflows/Settings/List',3,0,1),(19,8,'Configuration Editor','fa fa-pencil-square-o','LBL_CONFIG_EDITOR_DESCRIPTION','Using Joforce Configuration Editor, create a unique Helpdesk Support Name and Email.','Head/Settings/ConfigEditorDetail',5,0,0),(20,7,'Scheduler','fa fa-clock-o','Allows you to Configure Cron Task','Plan your list of activities that will run when Cron is activated.','CronTasks/Settings/List',2,0,0),(21,8,'Duplicate Check','fa fa-copy','DuplicateCheck','Using the built-in Duplicate Check feature, keep your CRM data clean by checking for duplicate entries at the time of data insertion.','DuplicateCheck/Settings/List',7,0,0),(22,8,'Address Lookup','fa fa-search-plus','Auto Fill the address fields in each module','Prevent typos in your address filling process with Address Lookup by automating it.','AddressLookup/Settings/List',8,0,0),(23,12,'LBL_PBXMANAGER','fa fa-phone','PBXManager module Configuration','This is to configure the Joforce asterisk connector in your asterisk server.','PBXManager/Settings/Index',2,0,0),(24,4,'ModTracker','set-IcoLoginHistory.gif','LBL_MODTRACKER_DESCRIPTION','','ModTracker/BasicSettings/Settings/ModTracker',9,0,0),(26,7,'Webforms','fa fa-file-zip-o','LBL_WEBFORMS_DESCRIPTION','Incorporating information about your website visitors or users into your CRM system.','Webforms/Settings/List',1,0,0),(28,6,'LBL_EDIT_FIELDS','fa fa-codepen','LBL_LAYOUT_EDITOR_DESCRIPTION','Create, Edit and Reorder any fields as per your need with the Module Layouts & Fields Editor.','LayoutEditor/Settings/Index',2,0,0),(29,7,'LBL_LEAD_MAPPING','fa fa-exchange','NULL','For each custom lead field, choose a custom account, contact, or opportunity field into which you want the information inserted when you convert a lead.','Leads/Settings/MappingDetail',1,0,1),(31,11,'My Preferences','fa fa-user','NULL','This offers you detailed information about the user, so you can amend your personal information, such as your password or manage your activities.','Users/Settings/PreferenceDetail/1',1,0,1),(32,11,'Calendar Settings','fa fa-calendar-check-o','NULL','Utilize the built-in calendar in Joforce CRM for planning and organizing your activities','Users/Settings/Calendar/1',2,0,1),(33,11,'LBL_MY_TAGS','fa fa-tags','NULL','Tags are descriptive keywords that you use to label your content and assist users in finding your content. These tags can both be public and private.','Tags/Settings/List/1',3,0,1),(34,11,'LBL_MENU_MANAGEMENT','fa fa-bars','NULL','In the menu manager you will find a list of all your modules and all the tools that are available in Joforce CRM.','MenuManager/Settings/Index',4,0,1),(35,12,'LBL_GOOGLE','fa fa-google','NULL','In this section, you can sync all your Clients Google settings with Joforce with field mapping.','Contacts/Settings/Extension/Google/Index/settings',1,0,1),(36,6,'Module Studio','fa fa-video-camera','LBL_MODULEDESIGNER_DESCRIPTION','Module Studio helps you to create custom modules for Joforce CRM based on your requirements.','ModuleDesigner/Settings/Index',3,0,0),(37,13,'Contributors','fa fa-plus-square','Contributors','Contributors are involved in providing or achieving something related to their business with others.','Head/Settings/Credits',1,0,0),(38,13,'License','fa fa-exclamation-triangle','License','We have a public license and any orders from joforce are permitted only under license with joforce.','Head/Settings/License',2,0,0),(39,4,'Google Settings','fa fa-cogs','Google Synchronization','In this section, you can sync all your Clients Google settings with Joforce with field mapping.','Google/Settings/GoogleSettings',12,1,0),(40,6,'Language Editor','fa fa-pencil','LBL_LANGUAGE_EDITOR','Language Editor, help you to change the CRM in your own language. Joforce supports Arabic, British English, ES Spanish, US English, Russian, Romania, etc. If your language is not available, then use the language editor and change the CRM in your language.','LanguageEditor/Settings/Index',3,0,0),(41,11,'Notifications','fa fa-bell','Notifications','Youll receive an immediate notification if a new record is assigned or a previous record is updated.','Notifications/Settings/Index',5,0,0),(42,12,'Masquerade User','fa fa-street-view','Masquerade User','Allow your customer to log into your Joforce CRM, view the data shared with them by Masquerade Users, and take actions on that data.','PortalUser/Settings/Index',6,0,0),(43,14,'ExtensionStore','joicon-inventory','ExtensionStore','The Extension Store features product lists and prices for each product.','ExtensionStore/Settings/ExtensionStore',7,0,0),(44,7,'Webhooks','fa fa-cog','LBL_WEBHOOKS_DESCRIPTION','A webhook, also known as a web callback, is a method for an app to provide real-time information to another application.','Webhooks/Settings/List',4,0,0),(45,6,'Kanban view','fa fa-th-large','KanbanView','Using the Kanban View, you can see a list of all your records in Joforce CRM, grouped into specific categories.','Pipeline/Settings/Index',5,0,0),(46,16,'LBL_PBXMANAGER','joicon-pbxmanager','LBL_PBXManager_DESCRIPTION','With Joforce CRM list all your call history with customer number, recording, Duration and start time.','PBXManager/view/List',1,0,1),(47,16,'LBL_RECYCLEBIN','joicon-recyclebin','LBL_RecycleBin_DESCRIPTION','Sort your list view by trashing any information that is not needed. In addition, the deleted data can be restored if you wish.','RecycleBin/view/List',1,0,1);");
$adb->pquery("INSERT INTO `jo_user_menu_arrangement`(user_id,default_sections,main_menu,module_apps,notifications) VALUES (0,'YTo1OntzOjk6Ik1BUktFVElORyI7czoxMToiZmEgZmEtdXNlcnMiO3M6NToiU0FMRVMiO3M6MTg6ImZhIGZhLWRvdC1jaXJjbGUtbyI7czo5OiJJTlZFTlRPUlkiO3M6MTY6ImpvaWNvbi1pbnZlbnRvcnkiO3M6NzoiU1VQUE9SVCI7czoxNToiZmEgZmEtbGlmZS1yaW5nIjtzOjU6IlRPT0xTIjtzOjEyOiJmYSBmYS13cmVuY2giO30',
'YTo2OntpOjA7YTozOntzOjU6InRhYmlkIjtzOjE6IjciO3M6NDoibmFtZSI7czo1OiJMZWFkcyI7czo0OiJ0eXBlIjtzOjY6Im1vZHVsZSI7fWk6MTthOjM6e3M6NToidGFiaWQiO3M6MToiNiI7czo0OiJ0eXBlIjtzOjY6Im1vZHVsZSI7czo0OiJuYW1lIjtzOjg6IkFjY291bnRzIjt9aToyO2E6Mzp7czo1OiJ0YWJpZCI7czoxOiI0IjtzOjQ6Im5hbWUiO3M6ODoiQ29udGFjdHMiO3M6NDoidHlwZSI7czo2OiJtb2R1bGUiO31pOjM7YTozOntzOjU6InRhYmlkIjtzOjE6IjIiO3M6NDoibmFtZSI7czoxMDoiUG90ZW50aWFscyI7czo0OiJ0eXBlIjtzOjY6Im1vZHVsZSI7fWk6NDthOjM6e3M6NToidGFiaWQiO3M6MjoiMTQiO3M6NDoibmFtZSI7czo4OiJQcm9kdWN0cyI7czo0OiJ0eXBlIjtzOjY6Im1vZHVsZSI7fWk6NTthOjM6e3M6NToidGFiaWQiO3M6MjoiMTMiO3M6NDoibmFtZSI7czo4OiJIZWxwRGVzayI7czo0OiJ0eXBlIjtzOjY6Im1vZHVsZSI7fX0',
'YTo1OntzOjk6Ik1BUktFVElORyI7YTo0OntpOjA7czoxOiI3IjtpOjE7czoxOiI0IjtpOjI7czoxOiI2IjtpOjM7czoyOiIyNiI7fXM6NToiU0FMRVMiO2E6Njp7aTowO3M6MToiMiI7aToxO3M6MToiNCI7aToyO3M6MToiNiI7aTozO3M6MjoiMTQiO2k6NDtzOjI6IjIwIjtpOjU7czoyOiIzNiI7fXM6OToiSU5WRU5UT1JZIjthOjk6e2k6MDtzOjE6IjQiO2k6MTtzOjE6IjYiO2k6MjtzOjI6IjE0IjtpOjM7czoyOiIxOCI7aTo0O3M6MjoiMTkiO2k6NTtzOjI6IjIxIjtpOjY7czoyOiIyMiI7aTo3O3M6MjoiMjMiO2k6ODtzOjI6IjM2Ijt9czo3OiJTVVBQT1JUIjthOjM6e2k6MDtzOjE6IjQiO2k6MTtzOjE6IjYiO2k6MjtzOjI6IjEzIjt9czo1OiJUT09MUyI7YTo3OntpOjA7czoyOiI0NiI7aToxO3M6MjoiMzciO2k6MjtzOjE6IjkiO2k6MztzOjE6IjgiO2k6NDtzOjI6IjQ4IjtpOjU7czoyOiIzMiI7aTo2O3M6MjoiMzMiO319',
'');");
$adb->pquery("RENAME TABLE com_jo_workflow_activatedonce to workflow_activatedonce,com_jo_workflow_tasktypes to workflow_tasktypes,com_jo_workflows to workflows,com_jo_workflowtask_queue to workflowtask_queue,com_jo_workflowtasks to workflowtasks,com_jo_workflowtasks_entitymethod to workflowtasks_entitymethod , com_jo_workflowtemplates to workflowtemplates");
$adb->pquery("DROP TABLE IF EXISTS jo_convertpotentialmapping,jo_customerportal_fields,jo_customerportal_prefs,jo_customerportal_relatedmoduleinfo,jo_customerportal_settings,jo_customerportal_tabs,jo_portal,jo_project,jo_projectcf,jo_projectmilestone,jo_projectmilestonecf,jo_projectmilestonetype,jo_projectpriority,jo_projectstatus,jo_projecttask,jo_projecttask_status_color,jo_projecttaskcf,jo_projecttaskpriority,jo_projecttaskprogress,jo_projecttaskstatus,jo_projecttasktype,jo_projecttype;");
$adb->pquery("DELETE FROM jo_tab WHERE tabid between 42 and 44;");
$adb->pquery("DELETE FROM jo_field WHERE tabid between 42 and 44;");
$adb->pquery("UPDATE workflow_tasktypes SET tasktypename=SUBSTRING(tasktypename, 3) WHERE tasktypename REGEXP '^VT';");
$adb->pquery("UPDATE workflow_tasktypes SET classname=SUBSTRING(classname, 3) WHERE classname REGEXP '^VT';");
$adb->pquery("UPDATE workflow_tasktypes SET classpath = REPLACE(classpath, 'VT', '');");
$adb->pquery("UPDATE workflow_tasktypes SET templatepath = REPLACE(templatepath, 'com_jo_', '');");
$adb->pquery("UPDATE workflow_tasktypes SET templatepath = REPLACE(templatepath, 'VT', '');");
$adb->pquery("UPDATE workflow_tasktypes SET classpath = REPLACE(classpath, 'com_jo_w', 'W');");
$adb->pquery("UPDATE workflowtasks SET task = REPLACE(task, 'VT', '');");
$adb->pquery("INSERT INTO `jo_relatedlists` VALUES (201,7,2,'get_opportunities',10,'Potentials',0,'add,select',0,'','1:N')");
$adb->pquery("update jo_relatedlists set label='Calendar' where related_tabid=9;");
$adb->pquery("update jo_relatedlists set label='SalesOrder' where related_tabid=22;");
$adb->pquery("update jo_relatedlists set label='PurchaseOrder' where related_tabid=21;");
$adb->pquery("INSERT INTO `jo_settings_field` VALUES  (48,7,'Reports','fa fa-bar-chart','Reports','Track your organization sales performance, sales forecast, sales pipelines, calls, lead source etc., with the reports. Prepare your own customized reports for each CRM modules.','Reports/view/List',3,0,0);");
$adb->pquery("INSERT INTO `jo_ws_referencetype` VALUES (34,'Contacts');");
$adb->pquery("CREATE TABLE Tracker_table (
    id MEDIUMINT NOT NULL AUTO_INCREMENT,
    User_Domain varchar(50) NOT NULL,
    Started_Date DateTime NOT NULL,
    PRIMARY KEY (id)
    );");
	//rename tables
	// $query = "show tables";
    //     $result = $adb->pquery($query, array());
    //     if($adb->num_rows($result) >= 1)
    //     {
    //             $log->debug("get old tables");
    //             while($result_set = $adb->fetch_array($result))
    //             {
    //                     $prev_table = $result->fields[0];
    //                     $new_table = str_replace('vtiger','jo',$result->fields[0]);
    //                     $rename_query = "rename table $prev_table to $new_table";
    //                     $adb->pquery($rename_query, array());

    //             }
    //             $log->debug("all tables were renamed vtiger_ to jo_");
	// }
	//rename tables
	$HeaderLocation = $_SERVER['REQUEST_URI'];
	$site_migrate_url=explode('/migration',$HeaderLocation);
	header("Location: $site_migrate_url[0]/index.php");
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
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,iconpath,description,linkto,sequence) values(?,?,?,?,?,?,?)",array($fieldid,6,'Module Studio','fa fa-video-camera','Module Studio','ModuleDesigner/Settings/Index',3));
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
	$adb->pquery("insert into jo_field values(29, " . $field_id . ", 'default_landing_page', 'jo_users', 1, 16, 'default_landing_page', 'Default Landing Page', 1, 2, 'Home', 100, 20, " .$usermoreinfoblock . " ,1, 'V~O',1,0,'BAS', 1, '',0, 0)", array() );

        // remove customer portal for the version 1.3
        $adb->pquery("DELETE FROM jo_settings_field WHERE name = ?", array('LBL_CUSTOMER_PORTAL'));

	
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

	$updateModulesList = array(	'Project'	=> 'packages/head/optional/Projects.zip',
					'Google'	=> 'packages/head/optional/Google.zip',);

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

    	// drop table 
        if (!Head_Utils::CheckTable('jo_app2tab')) {
       		$adb->pquery("DROP TABLE jo_app2tab", array());
    	}

    include_once 'vtlib/Head/Module.php';
    $moduleLists = 'MailManager';
    $module = Head_Module::getInstance($moduleLists);
    if ($module) $module->delete();

	// default_dashboard_view
        $adb->pquery( "ALTER TABLE jo_users ADD COLUMN default_dashboard_view int(2)", array() );
        $field_id = $adb->getUniqueID("jo_field");
        $adb->query("insert into jo_field values(29, " . $field_id . ", 'default_dashboard_view', 'jo_users', 1, 16, 'default_dashboard_view', 'Default Dashboard View', 1, 2, 1, 1, 20, '' ,1, 'V~O',1,0,'BAS', 1, '',0, 0)");

        $adb->pquery( "UPDATE jo_users SET default_dashboard_view = ?", array(1) );

        //Create table jo_notification
        if (!Head_Utils::CheckTable('jo_notification')) {
                $adb->pquery("CREATE TABLE `jo_notification` (
                                        `id` int(20) NOT NULL AUTO_INCREMENT,
                                        `user_id` int(10) NOT NULL,
                                        `module_name` varchar(40) NOT NULL,
                                        `entity_id` int(20) NOT NULL,
                                        `notifier_id` int(10) NOT NULL,
                                        `is_seen` int(1) NOT NULL,
                                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                        `updated_at` datetime DEFAULT NULL,
                                        `action_type` varchar(40) NOT NULL,
                                        PRIMARY KEY (`id`)
                                         ) ENGINE=InnoDB DEFAULT CHARSET=latin1",array());
        }

        // Add to jo_eventhandler for scheduling
            include_once('vtlib/Head/Module.php');
            include_once('vtlib/Head/Event.php');
            if(Head_Event::hasSupport()) {
                Head_Event::register(
                'Home', 'vtiger.entity.aftersave',
                'NotificationHandler', 'modules/Home/NotificationHandler.php'
                );
            }

        // add Language editor to settings field
//        $adb->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned) VALUES (?,?,?,?,?,?,?,?,?)' , array($adb->getUniqueID('jo_settings_field'), 6,'Language Editor', 'fa fa-pencil', 'LBL_LANGUAGE_EDITOR', 'LanguageEditor/Settings/Index', 3, 0, 0));

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

		//Write module contents on default_module_apps.php
	$file_contents = "<?php \$app_menu_array = array(
  'MARKETING' =>
  array (
    0 => '" . getTabid('Leads') . "',
    1 => '" . getTabid('Contacts') . "',
    2 => '" . getTabid('Accounts') . "',
    3 => '" . getTabid('Campaigns') . "'
  ),
  'SALES' =>
  array (
    0 => '" .getTabid('Potentials'). "',
    1 => '" .getTabid('Contacts'). "',
    2 => '" .getTabid('Accounts'). "',
    3 => '" .getTabid('Products'). "',
    4 => '" .getTabid('Quotes'). "',
    5 => '" .getTabid('Services'). "'
  ),
  'INVENTORY' =>
  array (
    0 => '" .getTabid('Contacts'). "',
    1 => '" .getTabid('Accounts'). "',
    2 => '" .getTabid('Products'). "',
    3 => '" .getTabid('Vendors'). "',
    4 => '" .getTabid('PriceBooks'). "',
    5 => '" .getTabid('PurchaseOrder'). "',
    6 => '" .getTabid('SalesOrder'). "',
    7 => '" .getTabid('Invoice'). "',
    8 => '" .getTabid('Services'). "'
  ),
  'SUPPORT' =>
  array (
    0 => '" .getTabid('Contacts'). "',
    1 => '" .getTabid('Accounts'). "',
    2 => '" .getTabid('HelpDesk'). "'
  ),
'PROJECT' =>
  array (
    0 => '" .getTabid('Contacts'). "',
    1 => '" .getTabid('Accounts'). "',
    2 => '" .getTabid('ProjectTask'). "',
    3 => '" .getTabid('ProjectMilestone'). "',
    4 => '" .getTabid('Project') ."'
  ),
'TOOLS' =>
  array (
    0 => '" .getTabid('EmailTemplates'). "',
    1 => '" .getTabid('PBXManager'). "',
    2 => '" .getTabid('Calendar')."',
    3 => '" .getTabid('Documents'). "',
    4 => '" .getTabid('RecycleBin'). "',
    5 => '" .getTabid('PDFMaker'). "',
    6 => '" .getTabid('EmailPlus'). "'
  ),
);
?>";

$myfile = fopen("storage/menu/default_module_apps.php", "w");
fwrite($myfile, $file_contents);
fclose($myfile);

//create htaccess file
crete_htacces_file();
session_unset();
session_destroy();
header ('Location: '.$site_URL.'index.php'); die();
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
	<div class="" style="display:flex;flex-direction:row">
		<div>
			<div class="gs-info">
				<div class="col-sm-12 text-center">
					<div class="logo install-logo">
						<div class="logo"> <img src="resources/images/logo.png" alt="Logo" /> </div>
					</div>
				</div>
				<div class="gs-wizard">
					<ul class="gs-wizard-section">
						<li class="completed">
							<a href="index.php?module=Install&amp;view=Index">
								<span class="wiz-circle"></span><span class="wiz-text">Install</span>
							</a>
						</li>

						<li class="active">
							<a href="index.php?module=Install&amp;view=Index&amp;mode=Step3">
								<span class="wiz-circle"></span><span class="wiz-text">Backup Permission Check</span>
							</a>
						</li>

						<!--        <li class="disabled">
            <a href="index.php?module=Install&amp;view=Index&amp;mode=Step4">

              <span class="wiz-circle"></span><span class="wiz-text">Installation Settings</span>
            </a>
          </li>   -->

						<li class="disabled">
							<a href="index.php?module=Install&amp;view=Index&amp;mode=Step5">
								<span class="wiz-circle"></span><span class="wiz-text">Start Migration</span>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>
		<div class="container-fluid page-container">

			<div class="row-fluid">
				<div class="span6">
					<div class="logo">
						<!-- <img src="resources/images/logo.png" alt="Logo"/>  -->
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
							<div style='margin-left: 20%'>
								<br> <br>
								<strong> Warning: </strong>Please note that it is not possible to revert back to Joforce
								v3.1 after the upgrade to Joforce v1.5 <br>
								So, it is important to take a backup of the Joforce v3.1 files and database before
								upgrading.</p><br>
								<form action="index.php" method="POST">
									<div><input type="checkbox" id="checkBox1" name="checkBox1" />
										<div class="chkbox"></div> Backup of source folder
									</div><br>
									<div><input type="checkbox" id="checkBox4" name="checkBox4" />
										<div class="chkbox"></div> Backup of database
									</div><br>
									<div><input type="checkbox" id="checkBox2" name="checkBox1" />
										<div class="chkbox"></div> Replace config/config.inc.php file from Joforce V1.5
										to Joforce v3.1
									</div><br>
									<div><input type="checkbox" id="checkBox3" name="checkBox4" />
										<div class="chkbox"></div> Replace user_privileges folder files from Joforce
										V1.5 to Joforce v3.1
									</div><br>

									<!--							<div><input type="checkbox" id="checkBox2" name="checkBox2"/><div class="chkbox"></div> Copy the config.inc.php from root directory to <strong>config/</strong> folder and Change the following values in the <strong>config/config.inc.php file</strong> </div><br>
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
				<div><input type="checkbox" id="checkBox6" name="checkBox6"/><div class="chkbox"></div> Replace your user_privileges folder </div><br>   -->


									<div class="button-container">
										<input type="submit" class="btn btn-large btn-primary" id="startMigration"
											name="startMigration" value="Next" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function () {

				$('input[name="startMigration"]').click(function () {
					if ($("#checkBox1").is(':checked') == false || $("#checkBox4").is(':checked') == false) {
						alert('Before starting migration, please take your database and source backup');
						return false;
					} else if ($("#checkBox2").is(':checked') == false || $("#checkBox3").is(':checked') ==
						false) {
						alert('Must Replace and Database name and file');
						return false;
					}
					var ht = $('#htaccess').val();
					if (ht == 'false') {
						alert('Please Create htaccess file in your Root Directory with writable access');
						return false;

					}
					return true;
				});

				$('#checkBox2').click(function () {
					if ($(this).is(':checked')) {
						$.ajax({
							type: 'GET',
							url: 'configmigration.php',
							success: function (data) {
								if (data == true) {
									
									alert('Add Joforce v1.5  to v3.1 Config.inc.php');
									window.location.reload();

								}
							},
							error: function (xhr, ajaxOptions, thrownerror) {}
						});
						
					} else if (!$(this).is(':checked')) {
						return confirm("Must checked, this checkbox ");
					}
				});

			});
		</script>
</body>

</html>
<?php }?>
<?php if($_POST['startMigration']){?>
<html>

<head>
	<title>Joforce Crm Setup</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="resources/js/jquery-min.js"></script>
	<link href="resources/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="resources/css/mkCheckbox.css" rel="stylesheet">
	<link href="resources/css/style.css" rel="stylesheet">
</head>

<body>
	<div class="" style="display:flex;flex-direction:row">
		<div>
			<div class="gs-info">
				<div class="col-sm-12 text-center">
					<div class="logo install-logo">
						<div class="logo"> <img src="resources/images/logo.png" alt="Logo" /> </div>
					</div>
				</div>
				<div class="gs-wizard">
					<ul class="gs-wizard-section">
						<li class="completed">
							<a href="index.php?module=Install&amp;view=Index">
								<span class="wiz-circle"></span><span class="wiz-text">Install</span>
							</a>
						</li>

						<li class="completed">
							<a href="index.php?module=Install&amp;view=Index&amp;mode=Step3">
								<span class="wiz-circle"></span><span class="wiz-text">Backup Permission Check</span>
							</a>
						</li>

						<li class="disabled">
							<a href="index.php?module=Install&amp;view=Index&amp;mode=Step5">
								<span class="wiz-circle"></span><span class="wiz-text">Start Migration</span>
							</a>
						</li>
					</ul>
				</div>

			</div>
		</div>
		<div class="container-fluid page-container">
			<div class="row-fluid">
				<div class="span6">
					<div class="logo">
						<!-- <img src="resources/images/logo.png" alt="Logo"/> -->
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
											<img src="install_loading.gif" />
											<h6>Please Wait.... </h6>
										</div>
									</div>
								</div>
							</div>


							<div style='margin-left: 20%' class='cont'>
								<form action="index.php" method="POST">


									<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span>
										<div class="chkbox"></div> <strong>You agree that you’ve backed up the necessary
											details before making any changes.</strong>
									</div><br><br>
									<div style="padding-left:49px;"><span style='color:green;font-size:12px;'>*</span>
										<div class="chkbox"></div> <strong>We hope it doesn’t happen, but Joforce is not
											responsible for any loss.</strong>
									</div><br>
									<br><br><br>
									<div class="button-container">
										<input type="submit" class="btn btn-large btn-primary" id="FinishMigration"
											name="FinishMigration" value="Start Migration" />
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function () {

				$('input[name="FinishMigration"]').click(function () {
					var confirm_migration = confirm('Are you sure you want to start the migration ?');
					if (!confirm_migration) {

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