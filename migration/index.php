<?php
chdir (dirname(__FILE__) . '/..');
include_once('includes/utils/utils.php');
include_once("modules/Emails/mail.php");
include_once('includes/logging.php');
include_once('includes/http/Session.php');
include_once('version.php');
include_once('migration/MySQLSearchReplace.php');
include_once('config/config.inc.php');
include_once('includes/utils/utils.php');
global $adb, $dbconfig, $root_directory;
global $log;
global $site_URL;
session_start();
//echo '<pre>'; print_r($_POST); die;
//error_reporting(E_ALL);
//ini_set('display_errors','on');
if($_POST['FinishMigration'] && $jo_current_version == '1.2') {
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
	//update tables


	//create jo_canonical tables
	$adb->pquery("
		CREATE TABLE `jo_canonical` (
	  	`id` int(19) NOT NULL AUTO_INCREMENT,
		  `input_format` text,
		  `output_format` text,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=latin1", array()
	);

	//create jo_canonical tables
	$adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([0-9]+)/([^/]+)/([0-9]+)$','index.php?&module=$1&view=$2&viewname=$3&search_params=$4&nolistcache=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(List|Edit|DashBoard|Import|Export|Calendar|SharedCalendar|EditFolder)$','index.php?module=$1&view=$2')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Save|Delete|DeleteImage|ExportData|MassDelete|MassSave|NoteBook|ProcessDuplicates|TagCloud)$','index.php?module=$1&action=$2')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(List|Edit|DashBoard|Import|Export|Calendar|SharedCalendar|EditFolder)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&app=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail|Edit)/([0-9]+)$','index.php?module=$1&view=$2&record=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Detail|Edit)/([^/]+)/([0-9]+)$','index.php?view=$1&module=$2&record=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(List|Edit|Calendar)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&app=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Calendar)/(Edit)/(Calendar|Events)$','index.php?module=$1&view=$2&mode=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Home)/(DashBoard)/([0-9]+)$','index.php?module=$1&view=$2&tabid=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Home)/(DashBoard)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&app=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(PurchaseOrder)/(Edit)/([0-9]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=PurchaseOrder&view=Edit&invoice_id=$3&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Invoice|PurchaseOrder)/(Edit)/([0-9]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=PurchaseOrder&view=Edit&salesorder_id=$3&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(PurchaseOrder|Invoice|SalesOrder)/(Edit)/([0-9]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=PurchaseOrder&view=Edit&quote_id=$3&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail|Edit|Calendar)/([0-9]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&record=$3&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail|Edit|Calendar)/(Calendar)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&mode=Calendar&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail)/([^/]+)$','index.php?module=$1&view=$2&record=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(ExportPDF)/([0-9]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&action=$2&record=$3&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(List)/([0-9]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&viewname=$3&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(List)/([0-9]+)$','index.php?module=$1&view=$2&viewname=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Contacts|Calendar)/(Extension)/(Google)/(Index)$','index.php?module=$1&view=Extension&extensionModule=Google&extensionView=Index')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Contacts|Calendar)/(Extension)/(Google)/(Index)/(settings)$','index.php?module=$1&view=Extension&extensionModule=Google&extensionView=Index&mode=settings')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Contacts|Calendar)/(Settings)/(Extension)/(Google)/(Index)/(settings)/([0-9]+)/([0-9]+)$','index.php?module=$1&parent=$2&view=Extension&extensionModule=Google&extensionView=Index&mode=settings&block=$7&fieldid=$8')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Contacts|Calendar)/(Settings)/(Extension)/(Google)/(Index)/(settings)$','index.php?module=$1&parent=$2&view=Extension&extensionModule=Google&extensionView=Index&mode=settings')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Contacts|Calendar)/(Extension)/(Google)/(Index)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=Extension&extensionModule=Google&extensionView=Index&app=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Users)/(Logout)$','index.php?module=$1&action=$2')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(ModuleManager)/(Settings)/(ModuleImport)/(importUserModuleStep1)$','index.php?module=ModuleManager&parent=Settings&view=ModuleImport&mode=importUserModuleStep1')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Workflows)/(Settings)/(Edit)/(V7Edit)/([^/]+)$','index.php?module=Workflows&parent=Settings&view=Edit&mode=V7Edit&source_module=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Workflows)/(Settings)/(Edit)/(V7Edit)$','index.php?module=Workflows&parent=Settings&view=Edit&mode=V7Edit')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Workflows)/(Settings)/(Edit)/([^/]+)/([[^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/$','index.php?module=Workflows&parent=Settings&view=Edit&record=$4&mode=V7Edit&returnmodule=Workflows&returnparent=Settings&returnpage=$8&returnview=$9')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Workflows)/(Settings)/(List)/([^/]+)/([[^/]+)/([^/]+)$','index.php?module=Workflows&parent=Settings&view=List&sourceModule=$4&page=$5&search_value=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/(GetPrintReport)/([^/]+)$','index.php?module=$1&view=$2&mode=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/(All)$','index.php?module=$1&view=$2&folder=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(Quote)$','index.php?module=$1&view=$2&quote_id=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/(Calendar)/([^/]+)/([^/]+)$','index.php?module=$1&parent=$2&view=$3&mode=$4&record=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/(Detail|Edit|Calendar)/([0-9]+)$','index.php?module=$1&parent=$2&view=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/(Detail|Edit)/([^/]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&parent=$2&view=$3&record=$4&app=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail)/([^/]+)/(Edit)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&module=$3&view=$4&account_id=$5&contact_id=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail)/([^/]+)/(Edit)/([^/]+)$','index.php?module=$1&view=$2&module=$3&view=$4&salesorder_id=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(MergeRecord)/([^/]+)/([^/]+)','index.php?module=$1&view=$2&records=$3&triggerEventName=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Calendar)/([^/]+)/([^/])$','index.php?module=Calendar&view=$2&mode=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/(clearCorruptedData)$','index.php?module=$1&view=$2&mode=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Calendar)/([^/]+)/([^/]+)/([^/]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=Calendar&view=$2&mode=$3&parent_id=$4&app=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Edit)/([^/]+)/([^/]+)/([^/]+)/(true)/([^/]+)$','index.php?module=$1&view=$2&mode=$3&sourceModule=$4&sourceRecord=$5&relationOperation=$6&parent_id=$7')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(CustomView)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&action=$2&sourceModule=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/(cancelImport)/([^/]+)','index.php?module=$1&view=$2&mode=cancelImport&import_id=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Edit)/([^/]+)/Copy/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=Edit&record=$3&isDuplicate=true&app=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Edit|ChartEdit)/([^/]+)/Copy$','index.php?module=$1&view=$2&record=$3&isDuplicate=true')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/(CopyEdit)/([0-9]+)','index.php?module=$1&parent=$2&view=Edit&from_record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Import)/([^/]+)/(index)','index.php?module=$1&view=Import&return_module=$2&return_action=index')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Import)/([^/]+)/(List)/([^/]+)/([^/]+)','index.php?module=$1&for_module=$2&view=$3&start=$4&foruser=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Import)/(undoImport)/([^/]+)','index.php?module=$1&view=Import&mode=undoImport&foruser=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(MergeRecord)/([^/]+)/([^/]+)','index.php?module=$1&view=MergeRecord&records=$3&triggerEventName=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(ProductsPopup)/([^/]+)/([^/]+)/([^/]+)/([^/]+)$','index.php?view=$1&module=$2&multi_select=$3&currency_id=$4&triggerEventName=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Documents)/(DownloadFile)/([^/]+)/([^/]+)$','index.php?module=$1&action=$2&record=$3&fieldid=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Documents)/([^/]+)/([^/]+)/([^/]+)/(true)$','index.php?module=$1&view=$2&sourceModule=$3&sourceRecord=$4&relationOperation=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(FindDuplicates)/([^/]+)/([^/]+)/([^/]+)','index.php?module=$1&view=FindDuplicates&fields=$3&ignoreEmpty=$4&saveButton=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Calendar)/([^/]+)/([^/]+)/(showDetailViewByMode)/([^/]+)$','index.php?module=$1&view=$2&record=$3&mode=$4&requestMode=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(showDetailViewByMode)/([^/]+)$','index.php?module=$1&view=$2&record=$3&mode=$4&requestMode=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/([^/]+)/(Popup)/([^/]+)$','index.php?module=$1&src_module=$2&src_record=$3&multi_select=$4&view=$5&triggerEventName=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Edit)/(Contacts)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&sourceModule=$3&sourceRecord=$4&relationOperation=$5&contact_id=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Edit)/(Accounts)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&sourceModule=$3&sourceRecord=$4&relationOperation=$5&account_id=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Edit)/([^/]+)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&view=Edit&sourceRecord=$3&sourceModule=$4&potential_id=&5&relationOperation=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(true)/(Popup)/([^/]+)$','index.php?module=$1&src_module=$2&src_record=$3&multi_select=true&view=Popup&triggerEventName=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(showDetailViewByMode)/([^/]+)/([^/]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&record=$3&mode=showDetailViewByMode&requestMode=$5&tab_label=$6&app=$7')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Detail)/([0-9]+)/(showChart)/([^/]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=Project&view=Detail&record=$3&mode=showChart&tab_label=$5&app=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(showDetailViewByMode)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&record=$3&mode=showDetailViewByMode&requestMode=$5&tab_label=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(SendEmail)/(composeMailData)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&mode=$3&record=$4&triggerEventName=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(showAllComments)/([^/]+)','index.php?module=$1&view=$2&record=$3&mode=showAllComments&tab_label=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(showRecentActivities)/([^/]+)/([^/]+)','index.php?module=$1&view=$2&record=$3&mode=showRecentActivities&page=$5&tab_label=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/(showHistory)/([^/]+)/([^/]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&view=$2&record=$3&mode=showHistory&page=$5&tab_label=$6&app=$7')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/([^/]+)/([^/]+)/(showRelatedList)/([0-9]+)/([^/]+)/(SALES|MARKETING|INVENTORY|SUPPORT|PROJECT)$','index.php?module=$1&relatedModule=$2&view=$3&record=$4&mode=showRelatedList&relationId=$6&tab_label=$7&app=$8')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Documents)/([^/]+)/([^/]+)/([^/]+)/(true)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&sourceModule=$3&sourceRecord=$4&relationOperation=$5&relatedcontact=$6&relatedorganization=$7&amount=$8')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(ModComments)/(DownloadFile)/([0-9]+)/([0-9]+)$','index.php?module=ModComments&action=DownloadFile&record=$3&fileid=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Reports)/(ChartEdit|ChartDetail)/([0-9]+)$','index.php?module=Reports&view=$2&record=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Reports)/(ChartEdit|ChartDetail)/([^/]+)$','index.php?module=Reports&view=$2&folder=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Settings)/([^/]+)/(MappingDetail)$','index.php?parent=$1&module=$2&view=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Settings)/([^/]+)/([^/]+)$','index.php?parent=$1&module=$2&sourceModule=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Settings)/(Picklist)/([^/]+)/([^/]+)$','index.php?parent=$1&module=$2&view=$3&source_module=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Settings)/([^/]+)/([^/]+)/([0-9]+)$','index.php?parent=$1&module=$2&view=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Settings)/([^/]+)/([^/]+)/([^/]+)$','index.php?parent=$1&module=$2&view=$3&sourceModule=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Roles)/(Settings)/(Edit)/([^/]+)$','index.php?module=$1&parent=$2&view=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/([^/]+)/([^/]+)$','index.php?module=$1&parent=Settings&view=$3&sourceModule=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Users)/(Settings)/(Detail|Edit)/([0-9]+)/([^/]+)$','index.php?module=$1&parent=$2&view=$3&record=$4&parentblock=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Head)/(Credits)/(Settings)$','index.php?module=$1&view=$2&parent=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Settings)/([^/]+)/([^/]+)/([0-9]+)/([^/]+)$','index.php?parent=$1&module=$2&view=$3&block=$4&fieldid=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Head|Users|MailPlus|Workflows|ModuleManager|Profiles|Groups|Webforms|MenuEditor)/(Settings)/([^/]+)$','index.php?module=$1&parent=$2&view=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Head)/(Settings)/(CompanyDetails)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&parent=$2&view=$3&block=$4&fieldid=$5&error=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Users)/(PreferenceDetail|PreferenceEdit)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&parent=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Roles)/(Settings)/(Edit)/([^/]+)$','index.php?module=$1&parent=$2&view=$3&record=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Roles)/(Settings)/(Edit)/([^/]+)/(create)$','index.php?module=$1&parent=$2&view=$3&parent_roleid=$4&mode=create')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/([^/]+)/(Settings)/([0-9]+)/([0-9]+)$','index.php?module=$1&view=$2&parent=$3&block=$4&fieldid=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/([^/]+)/([0-9]+)/([0-9]+)$','index.php?module=$1&parent=$2&view=$3&block=$4&fieldid=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Settings)/([^/]+)/([0-9]+)/([0-9]+)/([0-9]+)$','index.php?module=$1&parent=$2&view=$3&record=$4&block=$5&fieldid=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(MailConverter)/([^/]+)/([^/]+)/([^/]+)/(new)$','index.php?module=$1&parent=$2&view=$3&mode=$4&create=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(MailConverter)/([^/]+)/([^/]+)/([^/]+)/(new)/([0-9]+)$','index.php?module=$1&parent=$2&view=$3&mode=$4&create=$5&record=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(MailConverter)/(Settings)/([0-9]+)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=MailConverter&parent=Settings&record=$3&create=$4&view=$5&mode=$6')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Documents)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/(true)$','index.php?module=$1&view=$2&sourceModule=$3&return_action=$4&sourceRecord=$5&parent_id=$6&relationOperation=$7')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Export)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)$','index.php?module=$1&view=$2&selected_ids=$3&excluded_ids=$4&viewname=$5&page=$6&search_params=$7')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^value1=([^/]+)/value2=([^/]+)$','index.php?value1=$1&value2=$2')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(EmailTemplates)/(Settings)/(List)/([^/]+)$','index.php?module=EmailTemplates&parent=Settings&view=List&triggerEventName=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Popup)/(Documents)/(Emails)/(composeEmail)/([^/]+)$','index.php?view=Popup&module=Documents&src_module=Emails&src_field=composeEmail&triggerEventName=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^([^/]+)/(Emails)/(EmailsRelatedModulePopup)/([^/]+)$','index.php?module=$1&src_module=Emails&view=EmailsRelatedModulePopup&triggerEventName=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(EmailPlus)/([^/]+)$','index.php?module=$1&view=$2')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(DuplicateCheck)/(Settings)/(List)$','index.php?module=DuplicateCheck&parent=Settings&view=List')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(DuplicateCheck)/(Settings)/(List)/([^/]+)$','index.php?module=DuplicateCheck&parent=Settings&view=List&sourceModule=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(DuplicateCheck)/(Settings)/(List)/([^/]+)/(notify=([^/]+))$','index.php?module=DuplicateCheck&parent=Settings&view=List&sourceModule=$4&notify=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Users)/(Login)/(status=1)$','index.php?module=$1&view=$2&status=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Users)/(Login)/(statusError=1)$','index.php?module=$1&view=$2&statusError=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(Users)/(Login)/(fpError=1)$','index.php?module=$1&view=$2&fpError=$3')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(AddressLookup)/(Settings)/(List)$','index.php?module=AddressLookup&parent=Settings&view=List')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(AddressLookup)/(Settings)/(List)/([^/]+)$','index.php?module=AddressLookup&parent=Settings&view=List&sourceModule=$4')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(AddressLookup)/(Settings)/(List)/([0-9]+)/([0-9]+)$','index.php?module=AddressLookup&parent=Settings&view=List&block=$4&fieldid=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(AddressLookup)/(Settings)/(List)/([^/]+)/(success)$','index.php?module=AddressLookup&parent=Settings&view=List&sourceModule=$4&success=1')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(AddressLookup)/(Settings)/(List)/([^/]+)/(check=([^/]+))$','index.php?module=AddressLookup&parent=Settings&view=List&sourceModule=$4&check=$5')",array());
	 $adb->pquery("INSERT INTO jo_canonical (input_format,output_format) values('^(AddressLookup)/(Settings)/([^/]+)/([^/]+)/(error)$','index.php?module=AddressLookup&parent=Settings&view=$3&sourceModule=$4&error=1')",array());
	//insert jo_canonical tables
	//insert jo_canonical tables	

	//update jo_settings_field tables 
	$adb->pquery("update jo_settings_field set linkto='Users/Settings/List' where name='LBL_USERS'");
	$adb->pquery("update jo_settings_field set linkto='Roles/Settings/Index' where name='LBL_ROLES'");
	$adb->pquery(" update jo_settings_field set linkto='Profiles/Settings/List' where name='LBL_PROFILES'");
	$adb->pquery(" update jo_settings_field set linkto='Groups/Settings/List' where name='USERGROUPLIST'");
	$adb->pquery(" update jo_settings_field set linkto='SharingAccess/Settings/Index' where name='LBL_SHARING_ACCESS'");
	$adb->pquery(" update jo_settings_field set linkto='LoginHistory/Settings/List' where name='LBL_LOGIN_HISTORY_DETAILS'");
	$adb->pquery("update jo_settings_field set linkto='ModuleManager/Settings/List' where name='VTLIB_LBL_MODULE_MANAGER' ");
	$adb->pquery("update jo_settings_field set linkto='Settings/Picklist/Index' where name='LBL_PICKLIST_EDITOR' ");
	$adb->pquery(" update jo_settings_field set linkto='Settings/PickListDependency/List' where name='LBL_PICKLIST_DEPENDENCY'");
	$adb->pquery(" update jo_settings_field set linkto='MenuEditor/Settings/Index' where name='LBL_MENU_EDITOR'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/Head/CompanyDetails' where name='LBL_COMPANY_DETAILS'");
	$adb->pquery("update jo_settings_field set linkto='Settings/Head/OutgoingServerDetail' where name='LBL_MAIL_SERVER_SETTINGS' ");
	$adb->pquery(" update jo_settings_field set linkto='Settings/Currency/List' where name='LBL_CURRENCY_SETTINGS'");
	$adb->pquery(" update jo_settings_field set linkto='Head/Settings/TaxIndex' where name='LBL_TAX_SETTINGS'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/Server/ProxyConfig' where name='LBL_SYSTEM_INFO'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/DefModuleView/Settings' where name='LBL_DEFAULT_MODULE_VIEW'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/Head/TermsAndConditionsEdit' where name='INVENTORYTERMSANDCONDITION'");
	$adb->pquery(" update jo_settings_field set linkto='Head/Settings/CustomRecordNumbering' where name='LBL_CUSTOMIZE_MODENT_NUMBER'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/MailConverter/List' where name='LBL_MAIL_SCANNER'");
	$adb->pquery(" update jo_settings_field set linkto='Workflows/Settings/List' where name='LBL_LIST_WORKFLOWS'");
	$adb->pquery(" update jo_settings_field set linkto='Head/Settings/ConfigEditorDetail' where name='Configuration Editor'");
	$adb->pquery(" update jo_settings_field set linkto='CronTasks/Settings/List' where name='Scheduler'");
	$adb->pquery(" update jo_settings_field set linkto='ModTracker/BasicSettings/Settings/ModTracker' where name='ModTracker'");
	$adb->pquery("update jo_settings_field set linkto='PBXManager/Settings/Index' where name='LBL_PBXMANAGER' ");
	$adb->pquery(" update jo_settings_field set linkto='CustomerPortal/Settings/Index' where name='LBL_CUSTOMER_PORTAL'");
	$adb->pquery(" update jo_settings_field set linkto='Webforms/Settings/List' where name='Webforms'");
	$adb->pquery(" update jo_settings_field set linkto='LayoutEditor/Settings/Index' where name='LBL_EDIT_FIELDS'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/Leads/MappingDetail' where name='LBL_LEAD_MAPPING'");
	$adb->pquery(" update jo_settings_field set linkto='Settings/Potentials/MappingDetail' where name='LBL_OPPORTUNITY_MAPPING'");
	$adb->pquery(" update jo_settings_field set linkto='Users/PreferenceDetail/Settings/1' where name='My Preferences'");
	$adb->pquery(" update jo_settings_field set linkto='Users/Settings/Calendar/1' where name='Calendar Settings'");
	$adb->pquery(" update jo_settings_field set linkto='Tags/Settings/List/1' where name='LBL_MY_TAGS'");
	$adb->pquery(" update jo_settings_field set linkto='Contacts/Settings/Extension/Google/Index/settings' where name='LBL_GOOGLE'");
	$adb->pquery("delete from jo_settings_field where name='LBL_EXTENSION_STORE'",array());

	$adb->pquery("insert into jo_settings_blocks values(13,'LBL_JOFORCE',11)",array());
	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,description,linkto,sequence) values(?,?,?,?,?,?)",array($fieldid,13,'Contributors','Contributors','Head/Credits/Settings',1));
	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,description,linkto,sequence) values(?,?,?,?,?,?)",array($fieldid,13,'License','License','Head/Settings/License',2));

	$fieldid = $adb->getUniqueID('jo_settings_field');
	$adb->pquery("insert into jo_settings_field (fieldid,blockid,name,description,linkto,sequence) values(?,?,?,?,?,?)",array($fieldid,6,'Module Studio','Module Studio','ModuleDesigner/Index/Settings',3));

	//Service Contracts workflow deletion
	$adb->pquery("delete from jo_eventhandlers where handler_class='ServiceContractsHandler'",array());


	$unwantedmodule =  array(
		'Faq','ServiceContracts','Assets','SMSNotifier','ExtensionStore'
	);
	foreach($unwantedmodule as $key => $module){
		$adb->pquery("delete from jo_tab  where name = ?",array($module));	
	}
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
	installVtlibModule('VTPDFMaker', 'packages/head/migrate/VTPDFMaker.zip');
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
