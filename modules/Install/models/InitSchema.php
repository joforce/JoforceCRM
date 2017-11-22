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
vimport('~~modules/Users/DefaultDataPopulator.php');
vimport('~~includes/PopulateComboValues.php');

class Install_InitSchema_Model {

	/**
	 * Function starts applying schema changes
	 */
	public static function initialize() {
		global $adb;
		$adb = PearDatabase::getInstance();
		$adb->createTables("schema/DatabaseSchema.xml");

		$defaultDataPopulator = new DefaultDataPopulator();
		$defaultDataPopulator->create_tables();

		self::createDefaultUsersAccess();
		self::createDefaultCannonicalEntries();
		// create and populate combo tables
		$combo = new PopulateComboValues();
		$combo->create_tables();
		$combo->create_nonpicklist_tables();

		create_tab_data_file();
		create_parenttab_data_file();
		crete_htacces_file();
		self::create_dashboard_entry();
		// default report population
		vimport('~~modules/Reports/PopulateReports.php');

		// default customview population
		vimport('~~modules/CustomView/PopulateCustomView.php');

		// ensure required sequences are created (adodb creates them as needed, but if
		// creation occurs within a transaction we get problems
		$adb->getUniqueID("jo_crmentity");
		$adb->getUniqueID("jo_seactivityrel");
		$adb->getUniqueID("jo_freetags");

		$currencyName = $_SESSION['config_file_info']['currency_name'];
		$currencyCode = $_SESSION['config_file_info']['currency_code'];
		$currencySymbol = $_SESSION['config_file_info']['currency_symbol'];
		$adb->pquery("INSERT INTO jo_currency_info VALUES (?,?,?,?,?,?,?,?)", array($adb->getUniqueID("jo_currency_info"),
					$currencyName,$currencyCode,$currencySymbol,1,'Active','-11','0'));

		Install_InitSchema_Model::installDefaultEventsAndWorkflows();
	}
	public static function create_dashboard_entry() {
		global $adb;
		$chart_type = array('Pipelined Amount','Funnel','Total Revenue','Open Tickets');
		foreach($chart_type as $chart) {
			$link = $adb->pquery("select * from jo_links where linklabel=?",array($chart));
			$link_id = $adb->query_result($link,0,'linkid');
			$adb->pquery("insert into jo_module_dashboard_widgets (linkid,userid,position,dashboardtabid) values(?,?,?,?)",array($link_id,'1',NULL,2));
		}

	}

	/**
	 * Function upgrades the schema with changes post 540 version
	 */
	public static function upgrade() {
		$migrateVersions = Migration_Module_Model::getInstance('')->getAllowedMigrationVersions();

		define('VTIGER_UPGRADE', true);
		$oldVersion = null;
		foreach($migrateVersions as $migrateVersion) {
			foreach($migrateVersion as $newVersion => $versionLabel) {
				// Not ready?	
				if ($oldVersion == null) {
					$oldVersion = $newVersion;
					break;
				}
				$oldVersion = str_replace(array('.', ' '), '', $oldVersion);
				$newVersion = str_replace(array('.', ' '), '', $newVersion);
				$filename =  "modules/Migration/schema/".$oldVersion."_to_".$newVersion.".php";
				if(is_file($filename)) {
					include($filename);
				}
				$oldVersion = $newVersion;
			}
		}
	}
	/** Function to create the Canonical Entries
	**/
	public static function createDefaultCannonicalEntries() {
	 $adb = PearDatabase::getInstance();
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
	}
	/**
	 * Function creates default user's Role, Profiles
	 */
	public static function createDefaultUsersAccess() {
      	$adb = PearDatabase::getInstance();
        $roleId1 = $adb->getUniqueID("jo_role");
		$roleId2 = $adb->getUniqueID("jo_role");
		$roleId3 = $adb->getUniqueID("jo_role");
		$roleId4 = $adb->getUniqueID("jo_role");
		$roleId5 = $adb->getUniqueID("jo_role");

		$profileId1 = $adb->getUniqueID("jo_profile");
		$profileId2 = $adb->getUniqueID("jo_profile");
		$profileId3 = $adb->getUniqueID("jo_profile");
		$profileId4 = $adb->getUniqueID("jo_profile");

		$adb->pquery("INSERT INTO jo_role VALUES('H".$roleId1."','Organisation','H".$roleId1."',0)", array());
        $adb->pquery("INSERT INTO jo_role VALUES('H".$roleId2."','CEO','H".$roleId1."::H".$roleId2."',1)", array());
        $adb->pquery("INSERT INTO jo_role VALUES('H".$roleId3."','Vice President','H".$roleId1."::H".$roleId2."::H".$roleId3."',2)", array());
        $adb->pquery("INSERT INTO jo_role VALUES('H".$roleId4."','Sales Manager','H".$roleId1."::H".$roleId2."::H".$roleId3."::H".$roleId4."',3)", array());
        $adb->pquery("INSERT INTO jo_role VALUES('H".$roleId5."','Sales Person','H".$roleId1."::H".$roleId2."::H".$roleId3."::H".$roleId4."::H".$roleId5."',4)", array());

		//INSERT INTO jo_role2profile
		$adb->pquery("INSERT INTO jo_role2profile VALUES ('H".$roleId2."',".$profileId1.")", array());
		$adb->pquery("INSERT INTO jo_role2profile VALUES ('H".$roleId3."',".$profileId2.")", array());
	  	$adb->pquery("INSERT INTO jo_role2profile VALUES ('H".$roleId4."',".$profileId2.")", array());
		$adb->pquery("INSERT INTO jo_role2profile VALUES ('H".$roleId5."',".$profileId2.")", array());

		//New Security Start
		//Inserting into jo_profile jo_table
		$adb->pquery("INSERT INTO jo_profile VALUES ('".$profileId1."','Administrator','Admin Profile')", array());
		$adb->pquery("INSERT INTO jo_profile VALUES ('".$profileId2."','Sales Profile','Profile Related to Sales')", array());
		$adb->pquery("INSERT INTO jo_profile VALUES ('".$profileId3."','Support Profile','Profile Related to Support')", array());
		$adb->pquery("INSERT INTO jo_profile VALUES ('".$profileId4."','Guest Profile','Guest Profile for Test Users')", array());

		//Inserting into jo_profile2gloabal permissions
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId1."',1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId1."',2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId2."',1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId2."',2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId3."',1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId3."',2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId4."',1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2globalpermissions VALUES ('".$profileId4."',2,1)", array());

		//Inserting into jo_profile2tab
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",4,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",6,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",7,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",13,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",14,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",15,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",16,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",18,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",19,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",20,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",21,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",22,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",23,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",24,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",25,0)", array());
       	$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",26,0)", array());
       	$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId1.",27,0)", array());

		//Inserting into jo_profile2tab
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",4,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",6,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",7,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",13,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",14,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",15,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",16,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",18,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",19,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",20,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",21,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",22,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",23,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",24,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",25,0)", array());
        $adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",26,0)", array());
       	$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId2.",27,0)", array());

		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",4,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",6,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",7,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",13,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",14,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",15,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",16,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",18,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",19,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",20,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",21,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",22,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",23,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",24,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",25,0)", array());
        $adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",26,0)", array());
       	$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId3.",27,0)", array());

		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",4,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",6,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",7,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",13,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",14,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",15,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",16,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",18,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",19,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",20,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",21,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",22,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",23,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",24,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",25,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",26,0)", array());
		$adb->pquery("INSERT INTO jo_profile2tab VALUES (".$profileId4.",27,0)", array());
		//Inserting into jo_profile2standardpermissions  Adminsitrator

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",2,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",2,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",2,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",2,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",2,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",4,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",4,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",4,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",4,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",4,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",6,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",6,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",6,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",6,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",6,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",7,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",7,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",7,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",7,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",7,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",8,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",8,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",8,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",8,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",8,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",9,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",9,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",9,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",9,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",9,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",13,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",13,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",13,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",13,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",13,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",14,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",14,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",14,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",14,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",14,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",15,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",15,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",15,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",15,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",15,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",16,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",16,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",16,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",16,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",16,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",18,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",18,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",18,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",18,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",18,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",19,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",19,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",19,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",19,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",19,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",20,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",20,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",20,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",20,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",20,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",21,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",21,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",21,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",21,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",21,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",22,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",22,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",22,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",22,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",22,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",23,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",23,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",23,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",23,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",23,4,0)", array());

        $adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",26,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",26,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",26,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",26,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId1.",26,4,0)", array());

		//INSERT INTO Profile 2 std permissions for Sales User
		//Help Desk Create/Delete not allowed. Read-Only
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",2,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",2,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",2,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",2,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",2,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",4,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",4,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",4,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",4,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",4,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",6,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",6,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",6,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",6,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",6,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",7,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",7,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",7,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",7,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",7,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",8,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",8,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",8,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",8,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",8,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",9,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",9,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",9,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",9,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",9,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",13,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",13,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",13,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",13,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",13,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",14,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",14,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",14,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",14,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",14,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",15,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",15,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",15,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",15,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",15,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",16,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",16,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",16,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",16,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",16,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",18,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",18,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",18,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",18,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",18,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",19,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",19,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",19,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",19,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",19,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",20,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",20,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",20,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",20,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",20,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",21,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",21,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",21,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",21,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",21,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",22,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",22,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",22,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",22,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",22,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",23,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",23,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",23,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",23,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",23,4,0)", array());


        	$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",26,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",26,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",26,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",26,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId2.",26,4,0)", array());

		//Inserting into jo_profile2std for Support Profile
		// Potential is read-only
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",2,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",2,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",2,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",2,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",2,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",4,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",4,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",4,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",4,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",4,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",6,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",6,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",6,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",6,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",6,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",7,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",7,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",7,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",7,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",7,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",8,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",8,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",8,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",8,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",8,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",9,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",9,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",9,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",9,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",9,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",13,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",13,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",13,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",13,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",13,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",14,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",14,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",14,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",14,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",14,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",15,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",15,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",15,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",15,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",15,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",16,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",16,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",16,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",16,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",16,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",18,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",18,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",18,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",18,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",18,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",19,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",19,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",19,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",19,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",19,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",20,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",20,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",20,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",20,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",20,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",21,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",21,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",21,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",21,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",21,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",22,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",22,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",22,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",22,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",22,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",23,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",23,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",23,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",23,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",23,4,0)", array());


        $adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",26,0,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",26,1,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",26,2,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",26,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId3.",26,4,0)", array());

		//Inserting into jo_profile2stdper for Profile Guest Profile
		//All Read-Only
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",2,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",2,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",2,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",2,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",2,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",4,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",4,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",4,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",4,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",4,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",6,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",6,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",6,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",6,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",6,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",7,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",7,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",7,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",7,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",7,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",8,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",8,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",8,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",8,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",8,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",9,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",9,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",9,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",9,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",9,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",13,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",13,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",13,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",13,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",13,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",14,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",14,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",14,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",14,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",14,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",15,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",15,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",15,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",15,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",15,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",16,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",16,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",16,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",16,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",16,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",18,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",18,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",18,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",18,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",18,4,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",19,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",19,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",19,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",19,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",19,4,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",20,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",20,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",20,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",20,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",20,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",21,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",21,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",21,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",21,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",21,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",22,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",22,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",22,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",22,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",22,4,0)", array());

		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",23,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",23,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",23,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",23,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",23,4,0)", array());


        $adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",26,0,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",26,1,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",26,2,1)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",26,3,0)", array());
		$adb->pquery("INSERT INTO jo_profile2standardpermissions VALUES (".$profileId4.",26,4,0)", array());

		//Inserting into jo_profile 2 utility Admin
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",2,5,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",2,6,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",4,5,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",4,6,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",6,5,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",6,6,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",7,5,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",7,6,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",8,6,0)", array());
       	$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",7,8,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",6,8,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",4,8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",13,5,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",13,6,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",13,8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",14,5,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",14,6,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",7,9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",18,5,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",18,6,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",7,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",6,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",4,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",2,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",13,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",14,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId1.",18,10,0)", array());
        

		//Inserting into jo_profile2utility Sales Profile
		//Import Export Not Allowed.
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",2,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",2,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",4,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",4,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",6,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",6,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",7,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",7,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",8,6,1)", array());
       	$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",7,8,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",6,8,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",4,8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",13,5,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",13,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",13,8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",14,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",14,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",7,9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",18,5,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",18,6,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",7,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",6,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",4,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",2,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",13,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",14,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId2.",18,10,0)", array());
       
		//Inserting into jo_profile2utility Support Profile
		//Import Export Not Allowed.
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",2,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",2,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",4,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",4,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",6,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",6,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",7,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",7,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",8,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",7,8,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",6,8,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",4,8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",13,5,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",13,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",13,8,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",14,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",14,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",7,9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",18,5,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",18,6,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",7,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",6,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",4,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",2,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",13,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",14,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId3.",18,10,0)", array());
        

		//Inserting into jo_profile2utility Guest Profile Read-Only
		//Import Export BusinessCar Not Allowed.
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",2,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",2,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",4,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",4,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",6,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",6,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",7,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",7,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",8,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",7,8,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",6,8,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",4,8,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",13,5,1)", array());
    	$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",13,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",13,8,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",14,5,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",14,6,1)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",7,9,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",18,5,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",18,6,1)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",7,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",6,10,0)", array());
        $adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",4,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",2,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",13,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",14,10,0)", array());
		$adb->pquery("INSERT INTO jo_profile2utility VALUES (".$profileId4.",18,10,0)", array());
        

		 // Invalidate any cached information
    	VTCacheUtils::clearRoleSubordinates();

		$adminPassword = $_SESSION['config_file_info']['password'];
		$userDateFormat = $_SESSION['config_file_info']['dateformat'];
		$userTimeZone = $_SESSION['config_file_info']['timezone'];
		//Fix for http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/7974
        $userFirstName = $_SESSION['config_file_info']['firstname']; 
        $userLastName = $_SESSION['config_file_info']['lastname']; 
        $userLanguage = $_SESSION['config_file_info']['default_language'];
        // create default admin user
    	$user = CRMEntity::getInstance('Users');
		//Fix for http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/7974
        $user->column_fields["first_name"] = $userFirstName; 
 	$user->column_fields["last_name"] = $userLastName; 
        //Ends
        $user->column_fields["user_name"] = 'admin';
        $user->column_fields["status"] = 'Active';
        $user->column_fields["is_admin"] = 'on';
        $user->column_fields["user_password"] = $adminPassword;
        $user->column_fields["time_zone"] = $userTimeZone;
        $user->column_fields["language"] = $userLanguage;
        $user->column_fields["holidays"] = 'de,en_uk,fr,it,us,';
        $user->column_fields["workdays"] = '0,1,2,3,4,5,6,';
        $user->column_fields["weekstart"] = '1';
        $user->column_fields["namedays"] = '';
        $user->column_fields["currency_id"] = 1;
        $user->column_fields["reminder_interval"] = '1 Minute';
        $user->column_fields["reminder_next_time"] = date('Y-m-d H:i');
		$user->column_fields["date_format"] = $userDateFormat;
		$user->column_fields["hour_format"] = 'am/pm';
		$user->column_fields["start_hour"] = '08:00';
		$user->column_fields["end_hour"] = '23:00';
		$user->column_fields["imagename"] = '';
		$user->column_fields["internal_mailer"] = '1';
		$user->column_fields["activity_view"] = 'This Week';
		$user->column_fields["lead_view"] = 'Today';

		$adminEmail = $_SESSION['config_file_info']['admin_email'];
		if($adminEmail == '') $adminEmail ="admin@vtigeruser.com";
		$user->column_fields["email1"] = $adminEmail;
		$roleQuery = "SELECT roleid FROM jo_role WHERE rolename='CEO'";
		$adb->checkConnection();
		$adb->database->SetFetchMode(ADODB_FETCH_ASSOC);
		$roleResult = $adb->pquery($roleQuery, array());
		$roleId = $adb->query_result($roleResult,0,"roleid");
		$user->column_fields["roleid"] = $roleId;

        $user->save("Users");
        $adminUserId = $user->id;

		//Inserting into jo_groups table
		$groupId1 = $adb->getUniqueID("jo_users");
		$groupId2 = $adb->getUniqueID("jo_users");
		$groupId3 = $adb->getUniqueID("jo_users");

		$adb->pquery("INSERT INTO jo_groups VALUES ('".$groupId1."','Team Selling','Group Related to Sales')", array());
		$adb->pquery("INSERT INTO jo_group2role VALUES ('".$groupId1."','H".$roleId4."')", array());
		$adb->pquery("INSERT INTO jo_group2rs VALUES ('".$groupId1."','H".$roleId5."')", array());

		$adb->pquery("INSERT INTO jo_groups VALUES ('".$groupId2."','Marketing Group','Group Related to Marketing Activities')", array());
		$adb->pquery("INSERT INTO jo_group2role VALUES ('".$groupId2."','H".$roleId2."')", array());
		$adb->pquery("INSERT INTO jo_group2rs VALUES ('".$groupId2."','H".$roleId3."')", array());

		$adb->pquery("INSERT INTO jo_groups VALUES ('".$groupId3."','Support Group','Group Related to providing Support to Customers')", array());
		$adb->pquery("INSERT INTO jo_group2role VALUES ('".$groupId3."','H".$roleId3."')", array());
		$adb->pquery("INSERT INTO jo_group2rs VALUES ('".$groupId3."','H".$roleId3."')", array());

		// Setting user group relation for admin user
	 	$adb->pquery("INSERT INTO jo_users2group VALUES (?,?)", array($groupId2, $adminUserId), array());

		//Creating the flat files for admin user
		createUserPrivilegesfile($adminUserId);
		createUserSharingPrivilegesfile($adminUserId);

		//INSERT INTO jo_profile2field
		insertProfile2field($profileId1);
        insertProfile2field($profileId2);
        insertProfile2field($profileId3);
        insertProfile2field($profileId4);

		insert_def_org_field();
	}

	/**
	 * Function add necessary schema for event handlers and workflows, also add defaul workflows
	 */
	public static function installDefaultEventsAndWorkflows() {
		$adb = PearDatabase::getInstance();

		// Register All the Events
		self::registerEvents($adb);

		// Register All the Entity Methods
		self::registerEntityMethods($adb);

		// Populate Default Workflows
		self::populateDefaultWorkflows($adb);

		// Populate Links
		self::populateLinks();

		// Set Help Information for Fields
		self::setFieldHelpInfo();

		// Register Cron Jobs
		self::registerCronTasks();
	}

	/**
	 *  Register all the Cron Tasks
	 */
	public static function registerCronTasks() {
		vimport('~~vtlib/Head/Cron.php');
		Head_Cron::register( 'Workflow', 'cron/modules/com_jo_workflow/com_jo_workflow.service', 900, 'com_jo_workflow', 1, 1, 'Recommended frequency for Workflow is 15 mins');
		Head_Cron::register( 'RecurringInvoice', 'cron/modules/SalesOrder/RecurringInvoice.service', 43200, 'SalesOrder', 1, 2, 'Recommended frequency for RecurringInvoice is 12 hours');
		Head_Cron::register( 'SendReminder', 'cron/SendReminder.service', 900, 'Calendar', 1, 3, 'Recommended frequency for SendReminder is 15 mins');
		Head_Cron::register( 'ScheduleReports', 'cron/modules/Reports/ScheduleReports.service', 900, 'Reports', 1, 4, 'Recommended frequency for ScheduleReports is 15 mins');
		Head_Cron::register( 'MailScanner', 'cron/MailScanner.service', 900, 'Settings', 1, 5, 'Recommended frequency for MailScanner is 15 mins');
	}

	/**
	 * Function registers all the event handlers
	 */
	static function registerEvents($adb) {
		vimport('~~includes/events/include.inc');
		$em = new VTEventsManager($adb);

		// Registering event for Recurring Invoices
		$em->registerHandler('vtiger.entity.aftersave', 'modules/SalesOrder/RecurringInvoiceHandler.php', 'RecurringInvoiceHandler');

		//Registering Entity Delta handler for before save and after save events of the record to track the field value changes
		$em->registerHandler('vtiger.entity.beforesave', 'data/VTEntityDelta.php', 'VTEntityDelta');
		$em->registerHandler('vtiger.entity.aftersave', 'data/VTEntityDelta.php', 'VTEntityDelta');

		// Workflow manager
		$dependentEventHandlers = array('VTEntityDelta');
		$dependentEventHandlersJson = Zend_Json::encode($dependentEventHandlers);
		$em->registerHandler('vtiger.entity.aftersave', 'modules/com_jo_workflow/VTEventHandler.inc', 'VTWorkflowEventHandler',
									'',$dependentEventHandlersJson);

		//Registering events for On modify
		$em->registerHandler('vtiger.entity.afterrestore', 'modules/com_jo_workflow/VTEventHandler.inc', 'VTWorkflowEventHandler');

		// Registering event for HelpDesk - To reset from_portal value
		$em->registerHandler('vtiger.entity.aftersave.final', 'modules/HelpDesk/HelpDeskHandler.php', 'HelpDeskHandler');
	}

	/**
	 * Function registers all the work flow custom entity methods
	 * @param <PearDatabase> $adb
	 */
	static function registerEntityMethods($adb) {
		vimport("~~modules/com_jo_workflow/include.inc");
		vimport("~~modules/com_jo_workflow/tasks/VTEntityMethodTask.inc");
		vimport("~~modules/com_jo_workflow/VTEntityMethodManager.inc");
		$emm = new VTEntityMethodManager($adb);

		// Registering method for Updating Inventory Stock
		$emm->addEntityMethod("SalesOrder","UpdateInventory","includes/InventoryHandler.php","handleInventoryProductRel");//Adding EntityMethod for Updating Products data after creating SalesOrder
		$emm->addEntityMethod("Invoice","UpdateInventory","includes/InventoryHandler.php","handleInventoryProductRel");//Adding EntityMethod for Updating Products data after creating Invoice

		// Register Entity Method for Customer Portal Login details email notification task
		$emm->addEntityMethod("Contacts","SendPortalLoginDetails","modules/Contacts/ContactsHandler.php","Contacts_sendCustomerPortalLoginDetails");

		// Register Entity Method for Email notification on ticket creation from Customer portal
		$emm->addEntityMethod("HelpDesk","NotifyOnPortalTicketCreation","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_nofifyOnPortalTicketCreation");

		// Register Entity Method for Email notification on ticket comment from Customer portal
		$emm->addEntityMethod("HelpDesk","NotifyOnPortalTicketComment","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_notifyOnPortalTicketComment");

		// Register Entity Method for Email notification to Record Owner on ticket change, which is not from Customer portal
		$emm->addEntityMethod("HelpDesk","NotifyOwnerOnTicketChange","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_notifyOwnerOnTicketChange");

		// Register Entity Method for Email notification to Related Customer on ticket change, which is not from Customer portal
		$emm->addEntityMethod("HelpDesk","NotifyParentOnTicketChange","modules/HelpDesk/HelpDeskHandler.php","HelpDesk_notifyParentOnTicketChange");
	}

	/**
	 * Function adds default system workflows
	 * @param <PearDatabase> $adb
	 */
	static function populateDefaultWorkflows($adb) {
		vimport("~~modules/com_jo_workflow/include.inc");
		vimport("~~modules/com_jo_workflow/tasks/VTEntityMethodTask.inc");
		vimport("~~modules/com_jo_workflow/VTEntityMethodManager.inc");
		vimport("~~modules/com_jo_workflow/VTTaskManager.inc");

		// register the workflow tasks
		$taskTypes = array();
		$defaultModules = array('include' => array(), 'exclude'=>array());
		$createToDoModules = array('include' => array("Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns","Quotes","PurchaseOrder","SalesOrder","Invoice"), 'exclude'=>array("Calendar", "FAQ", "Events"));
		$createEventModules = array('include' => array("Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns"), 'exclude'=>array("Calendar", "FAQ", "Events"));

		$taskTypes[] = array("name"=>"VTEmailTask", "label"=>"Send Mail", "classname"=>"VTEmailTask", "classpath"=>"modules/com_jo_workflow/tasks/VTEmailTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTEmailTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
		$taskTypes[] = array("name"=>"VTEntityMethodTask", "label"=>"Invoke Custom Function", "classname"=>"VTEntityMethodTask", "classpath"=>"modules/com_jo_workflow/tasks/VTEntityMethodTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTEntityMethodTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
		$taskTypes[] = array("name"=>"VTCreateTodoTask", "label"=>"Create Todo", "classname"=>"VTCreateTodoTask", "classpath"=>"modules/com_jo_workflow/tasks/VTCreateTodoTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTCreateTodoTask.tpl", "modules"=>$createToDoModules, "sourcemodule"=>'');
		$taskTypes[] = array("name"=>"VTCreateEventTask", "label"=>"Create Event", "classname"=>"VTCreateEventTask", "classpath"=>"modules/com_jo_workflow/tasks/VTCreateEventTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTCreateEventTask.tpl", "modules"=>$createEventModules, "sourcemodule"=>'');
		$taskTypes[] = array("name"=>"VTUpdateFieldsTask", "label"=>"Update Fields", "classname"=>"VTUpdateFieldsTask", "classpath"=>"modules/com_jo_workflow/tasks/VTUpdateFieldsTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTUpdateFieldsTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
		$taskTypes[] = array("name"=>"VTCreateEntityTask", "label"=>"Create Entity", "classname"=>"VTCreateEntityTask", "classpath"=>"modules/com_jo_workflow/tasks/VTCreateEntityTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTCreateEntityTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
		$taskTypes[] = array("name"=>"VTSMSTask", "label"=>"SMS Task", "classname"=>"VTSMSTask", "classpath"=>"modules/com_jo_workflow/tasks/VTSMSTask.inc", "templatepath"=>"com_jo_workflow/taskforms/VTSMSTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'SMSNotifier');

		foreach ($taskTypes as $taskType) {
			VTTaskType::registerTaskType($taskType);
		}

		// Creating Workflow for Updating Inventory Stock for Invoice
		$vtWorkFlow = new VTWorkflowManager($adb);
		$invWorkFlow = $vtWorkFlow->newWorkFlow("Invoice");
		$invWorkFlow->test = '[{"fieldname":"subject","operation":"does not contain","value":"`!`"}]';
		$invWorkFlow->description = "UpdateInventoryProducts On Every Save";
		$invWorkFlow->defaultworkflow = 1;
		$vtWorkFlow->save($invWorkFlow);

		$tm = new VTTaskManager($adb);
		$task = $tm->createTask('VTEntityMethodTask', $invWorkFlow->id);
		$task->active=true;
		$task->methodName = "UpdateInventory";
		$tm->saveTask($task);

		// Creating Workflow for Accounts when Notifyowner is true
		$vtaWorkFlow = new VTWorkflowManager($adb);
		$accWorkFlow = $vtaWorkFlow->newWorkFlow("Accounts");
		$accWorkFlow->test = '[{"fieldname":"notify_owner","operation":"is","value":"true:boolean"}]';
		$accWorkFlow->description = "Send Email to user when Notifyowner is True";
		$accWorkFlow->executionCondition=2;
		$accWorkFlow->defaultworkflow = 1;
		$vtaWorkFlow->save($accWorkFlow);
		$id1=$accWorkFlow->id;

		$tm = new VTTaskManager($adb);
		$task = $tm->createTask('VTEmailTask',$accWorkFlow->id);
		$task->active=true;
		$task->methodName = "NotifyOwner";
		$task->recepient = "\$(assigned_user_id : (Users) email1)";
		$task->subject = "Regarding Account Creation";
		$task->content = "An Account has been assigned to you on JoForce<br>Details of account are :<br><br>".
				"AccountId:".'<b>$account_no</b><br>'."AccountName:".'<b>$accountname</b><br>'."Rating:".'<b>$rating</b><br>'.
				"Industry:".'<b>$industry</b><br>'."AccountType:".'<b>$accounttype</b><br>'.
				"Description:".'<b>$description</b><br><br><br>'."Thank You<br>Admin";
		$task->summary="An account has been created ";
		$tm->saveTask($task);
		$adb->pquery("update com_jo_workflows set defaultworkflow=? where workflow_id=?",array(1,$id1));

		// Creating Workflow for Contacts when Notifyowner is true

		$vtcWorkFlow = new VTWorkflowManager($adb);
		$conWorkFlow = 	$vtcWorkFlow->newWorkFlow("Contacts");
		$conWorkFlow->summary="A contact has been created ";
		$conWorkFlow->executionCondition=2;
		$conWorkFlow->test = '[{"fieldname":"notify_owner","operation":"is","value":"true:boolean"}]';
		$conWorkFlow->description = "Send Email to user when Notifyowner is True";
		$conWorkFlow->defaultworkflow = 1;
		$vtcWorkFlow->save($conWorkFlow);
		$id1=$conWorkFlow->id;
		$tm = new VTTaskManager($adb);
		$task = $tm->createTask('VTEmailTask',$conWorkFlow->id);
		$task->active=true;
		$task->methodName = "NotifyOwner";
		$task->recepient = "\$(assigned_user_id : (Users) email1)";
		$task->subject = "Regarding Contact Creation";
		$task->content = "An Contact has been assigned to you on JoForce<br>Details of Contact are :<br><br>".
				"Contact Id:".'<b>$contact_no</b><br>'."LastName:".'<b>$lastname</b><br>'."FirstName:".'<b>$firstname</b><br>'.
				"Lead Source:".'<b>$leadsource</b><br>'.
				"Department:".'<b>$department</b><br>'.
				"Description:".'<b>$description</b><br><br><br>'."Thank You<br>Admin";
		$task->summary="An contact has been created ";
		$tm->saveTask($task);
		$adb->pquery("update com_jo_workflows set defaultworkflow=? where workflow_id=?",array(1,$id1));


		// Creating Workflow for Contacts when PortalUser is true

		$vtcWorkFlow = new VTWorkflowManager($adb);
		$conpuWorkFlow = $vtcWorkFlow->newWorkFlow("Contacts");
		$conpuWorkFlow->test = '[{"fieldname":"portal","operation":"is","value":"true:boolean"}]';
		$conpuWorkFlow->description = "Send Email to user when Portal User is True";
		$conpuWorkFlow->executionCondition=2;
		$conpuWorkFlow->defaultworkflow = 1;
		$vtcWorkFlow->save($conpuWorkFlow);
		$id1=$conpuWorkFlow->id;

                $taskManager = new VTTaskManager($adb);
                $task = $taskManager->createTask('VTEntityMethodTask', $id1);
		$task->active = true;
		$task->summary = 'Email Customer Portal Login Details';
		$task->methodName = "SendPortalLoginDetails";
		$taskManager->saveTask($task);
		// Creating Workflow for Potentials

		$vtcWorkFlow = new VTWorkflowManager($adb);
		$potentialWorkFlow = $vtcWorkFlow->newWorkFlow("Potentials");
		$potentialWorkFlow->description = "Send Email to users on Potential creation";
		$potentialWorkFlow->executionCondition=1;
		$potentialWorkFlow->defaultworkflow = 1;
		$vtcWorkFlow->save($potentialWorkFlow);
		$id1=$potentialWorkFlow->id;

		$tm = new VTTaskManager($adb);
		$task = $tm->createTask('VTEmailTask',$potentialWorkFlow->id);

		$task->active=true;
		$task->recepient = "\$(assigned_user_id : (Users) email1)";
		$task->subject = "Regarding Potential Assignment";
		$task->content = "An Potential has been assigned to you on JoForce<br>Details of Potential are :<br><br>".
				"Potential No:".'<b>$potential_no</b><br>'."Potential Name:".'<b>$potentialname</b><br>'.
				"Amount:".'<b>$amount</b><br>'.
				"Expected Close Date:".'<b>$closingdate</b><br>'.
				"Type:".'<b>$opportunity_type</b><br><br><br>'.
				"Description :".'$description<br>'."<br>Thank You<br>Admin";

		$task->summary="An Potential has been created ";
		$tm->saveTask($task);

		$workflowManager = new VTWorkflowManager($adb);
		$taskManager = new VTTaskManager($adb);

		// Contact workflow on creation/modification
		$contactWorkFlow = $workflowManager->newWorkFlow("Contacts");
		$contactWorkFlow->test = '';
		$contactWorkFlow->description = "Workflow for Contact Creation or Modification";
		$contactWorkFlow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
		$contactWorkFlow->defaultworkflow = 1;
		$workflowManager->save($contactWorkFlow);

		$tm = new VTTaskManager($adb);
		$task = $tm->createTask('VTEmailTask',$contactWorkFlow->id);

		$task->active=true;
		$task->recepient = "\$(assigned_user_id : (Users) email1)";
		$task->subject = "Regarding Contact Assignment";
		$task->content = "An Contact has been assigned to you on JoForce<br>Details of Contact are :<br><br>".
				"Contact Id:".'<b>$contact_no</b><br>'."LastName:".'<b>$lastname</b><br>'."FirstName:".'<b>$firstname</b><br>'.
				"Lead Source:".'<b>$leadsource</b><br>'.
				"Department:".'<b>$department</b><br>'.
				"Description:".'<b>$description</b><br><br><br>'."And <b>CustomerPortal Login Details</b> is sent to the " .
				"EmailID :-".'$email<br>'."<br>Thank You<br>Admin";

		$task->summary="An contact has been created ";
		$tm->saveTask($task);
		$adb->pquery("update com_jo_workflows set defaultworkflow=? where workflow_id=?",array(1,$id1));
                
		// Trouble Tickets workflow on creation from Customer Portal
		$helpDeskWorkflow = $workflowManager->newWorkFlow("HelpDesk");
		$helpDeskWorkflow->test = '[{"fieldname":"from_portal","operation":"is","value":"true:boolean"}]';
		$helpDeskWorkflow->description = "Workflow for Ticket Created from Portal";
		$helpDeskWorkflow->executionCondition = VTWorkflowManager::$ON_FIRST_SAVE;
		$helpDeskWorkflow->defaultworkflow = 1;
		$workflowManager->save($helpDeskWorkflow);

		$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
		$task->active = true;
		$task->summary = 'Notify Record Owner and the Related Contact when Ticket is created from Portal';
		$task->methodName = "NotifyOnPortalTicketCreation";
		$taskManager->saveTask($task);

		// Trouble Tickets workflow on ticket update from Customer Portal
		$helpDeskWorkflow = $workflowManager->newWorkFlow("HelpDesk");
		$helpDeskWorkflow->test = '[{"fieldname":"from_portal","operation":"is","value":"true:boolean"}]';
		$helpDeskWorkflow->description = "Workflow for Ticket Updated from Portal";
		$helpDeskWorkflow->executionCondition = VTWorkflowManager::$ON_MODIFY;
		$helpDeskWorkflow->defaultworkflow = 1;
		$workflowManager->save($helpDeskWorkflow);

		$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
		$task->active = true;
		$task->summary = 'Notify Record Owner when Comment is added to a Ticket from Customer Portal';
		$task->methodName = "NotifyOnPortalTicketComment";
		$taskManager->saveTask($task);

		// Trouble Tickets workflow on ticket change, which is not from Customer Portal - Both Record Owner and Related Customer
		$helpDeskWorkflow = $workflowManager->newWorkFlow("HelpDesk");
		$helpDeskWorkflow->test = '[{"fieldname":"from_portal","operation":"is","value":"false:boolean"}]';
		$helpDeskWorkflow->description = "Workflow for Ticket Change, not from the Portal";
		$helpDeskWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
		$helpDeskWorkflow->defaultworkflow = 1;
		$workflowManager->save($helpDeskWorkflow);

		$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
		$task->active = true;
		$task->summary = 'Notify Record Owner on Ticket Change, which is not done from Portal';
		$task->methodName = "NotifyOwnerOnTicketChange";
		$taskManager->saveTask($task);

		$task = $taskManager->createTask('VTEntityMethodTask', $helpDeskWorkflow->id);
		$task->active = true;
		$task->summary = 'Notify Related Customer on Ticket Change, which is not done from Portal';
		$task->methodName = "NotifyParentOnTicketChange";
		$taskManager->saveTask($task);

		// Events workflow when Send Notification is checked
		$eventsWorkflow = $workflowManager->newWorkFlow("Events");
		$eventsWorkflow->test = '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]';
		$eventsWorkflow->description = "Workflow for Events when Send Notification is True";
		$eventsWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
		$eventsWorkflow->defaultworkflow = 1;
		$workflowManager->save($eventsWorkflow);

		$task = $taskManager->createTask('VTEmailTask', $eventsWorkflow->id);
		$task->active = true;
		$task->summary = 'Send Notification Email to Record Owner';
		$task->recepient = "\$(assigned_user_id : (Users) email1)";
		$task->subject = "Event :  \$subject";
		$task->content = '$(assigned_user_id : (Users) first_name) $(assigned_user_id : (Users) last_name) ,<br/>'
						.'<b>Activity Notification Details:</b><br/>'
						.'Subject             : $subject<br/>'
						.'Start date and time : $date_start  $time_start ( $(general : (__HeadMeta__) dbtimezone) ) <br/>'
						.'End date and time   : $due_date  $time_end ( $(general : (__HeadMeta__) dbtimezone) ) <br/>'
						.'Status              : $eventstatus <br/>'
						.'Priority            : $taskpriority <br/>'
						.'Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) '
												.'$(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) <br/>'
						.'Contacts List       : $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname) <br/>'
						.'Location            : $location <br/>'
						.'Description         : $description';
		$taskManager->saveTask($task);

		// Calendar workflow when Send Notification is checked
		$calendarWorkflow = $workflowManager->newWorkFlow("Calendar");
		$calendarWorkflow->test = '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]';
		$calendarWorkflow->description = "Workflow for Calendar Todos when Send Notification is True";
		$calendarWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
		$calendarWorkflow->defaultworkflow = 1;
		$workflowManager->save($calendarWorkflow);

		$task = $taskManager->createTask('VTEmailTask', $calendarWorkflow->id);
		$task->active = true;
		$task->summary = 'Send Notification Email to Record Owner';
		$task->recepient = "\$(assigned_user_id : (Users) email1)";
		$task->subject = "Task :  \$subject";
		$task->content = '$(assigned_user_id : (Users) first_name) $(assigned_user_id : (Users) last_name) ,<br/>'
						.'<b>Task Notification Details:</b><br/>'
						.'Subject : $subject<br/>'
						.'Start date and time : $date_start  $time_start ( $(general : (__HeadMeta__) dbtimezone) ) <br/>'
						.'End date and time   : $due_date ( $(general : (__HeadMeta__) dbtimezone) ) <br/>'
						.'Status              : $taskstatus <br/>'
						.'Priority            : $taskpriority <br/>'
						.'Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) '
						.'$(parent_id         : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title) <br/>'
						.'Contacts List       : $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname) <br/>'
						.'Location            : $location <br/>'
						.'Description         : $description';
		$taskManager->saveTask($task);
	}

	/**
	 * Function adds default details view links
	 */
	public static function populateLinks() {
		vimport('~~vtlib/Head/Module.php');

		// Links for Accounts module
		$accountInstance = Head_Module::getInstance('Accounts');
		// Detail View Custom link
		$accountInstance->addLink(
			'DETAILVIEWBASIC', 'LBL_ADD_NOTE',
			'index.php?module=Documents&action=EditView&return_module=$MODULE$&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$',
			'themes/images/bookMark.gif'
		);
		$accountInstance->addLink('DETAILVIEWBASIC', 'LBL_SHOW_ACCOUNT_HIERARCHY', 'index.php?module=Accounts&action=AccountHierarchy&accountid=$RECORD$');

		$leadInstance = Head_Module::getInstance('Leads');
		$leadInstance->addLink(
			'DETAILVIEWBASIC', 'LBL_ADD_NOTE',
			'index.php?module=Documents&action=EditView&return_module=$MODULE$&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$',
			'themes/images/bookMark.gif'
		);

		$contactInstance = Head_Module::getInstance('Contacts');
		$contactInstance->addLink(
			'DETAILVIEWBASIC', 'LBL_ADD_NOTE',
			'index.php?module=Documents&action=EditView&return_module=$MODULE$&return_action=DetailView&return_id=$RECORD$&parent_id=$RECORD$',
			'themes/images/bookMark.gif'
		);

		//PDFMAKER Instance
		$pdfInstance = Head_Module::getInstance('VTPDFMaker');
		$pdfInstance->addLink(
			'DETAILVIEWSIDEBARWIDGET','PDF Maker','module=VTPDFMaker&view=ExportPDF&record=$RECORD$','NULL'
			);
		$pdfInstance->addLink('HEADERSCRIPT', 'HEADERSCRIPT', 'layouts/modules/VTPDFMaker/resources/Helper.js');
		$duplicateInsance = Head_Module::getInstance('DuplicateCheck');
		$duplicateInsance->addLink(
			'HEADERSCRIPT','Duplicate Check','layouts/modules/Settings/DuplicateCheck/jsresources/duplicatecheck.js','NULL'
			);
		$duplicateInsance->addLink(
		'HEADERSCRIPT','Duplicate Check Quick Create','layouts/modules/Settings/DuplicateCheck/jsresources/quickcreateduplicatecheck.js','NULL'

		);

		$addressInstance = Head_Module::getInstance('AddressLookup');
		$addressInstance->addLink(
		'HEADERSCRIPT','Address Autofill','layouts/modules/Settings/AddressLookup/jsresources/AddressLookup.js','NULL'
		);

		$emailInstance = Head_Module::getInstance('EmailPlus');
		$emailInstance->addLink(
		'HEADERSCRIPT','Check Server Details','layouts/modules/EmailPlus/resources/checkServerInfo.js'
		);
	}

	/**
	 * Function add help information on special fields
	 */
	public static function setFieldHelpInfo() {
		// Added Help Info for Hours and Days fields of HelpDesk module.
		vimport('~~vtlib/Head/Module.php');
		$helpDeskModule = Head_Module::getInstance('HelpDesk');
		$field1 = Head_Field::getInstance('hours',$helpDeskModule);
		$field2 = Head_Field::getInstance('days',$helpDeskModule);

		$field1->setHelpInfo('This gives the estimated hours for the Ticket.'.
					'<br>When the same ticket is added to a Service Contract,'.
					'based on the Tracking Unit of the Service Contract,'.
					'Used units is updated whenever a ticket is Closed.');

		$field2->setHelpInfo('This gives the estimated days for the Ticket.'.
					'<br>When the same ticket is added to a Service Contract,'.
					'based on the Tracking Unit of the Service Contract,'.
					'Used units is updated whenever a ticket is Closed.');

		$usersModuleInstance = Head_Module::getInstance('Users');
		$field1 = Head_Field::getInstance('currency_grouping_pattern', $usersModuleInstance);
		$field2 = Head_Field::getInstance('currency_decimal_separator', $usersModuleInstance);
		$field3 = Head_Field::getInstance('currency_grouping_separator', $usersModuleInstance);
		$field4 = Head_Field::getInstance('currency_symbol_placement', $usersModuleInstance);

		$field1->setHelpInfo("<b>Currency - Digit Grouping Pattern</b> <br/><br/>".
									"This pattern specifies the format in which the currency separator will be placed.");
		$field2->setHelpInfo("<b>Currency - Decimal Separator</b> <br/><br/>".
											"Decimal separator specifies the separator to be used to separate ".
											"the fractional values from the whole number part. <br/>".
											"<b>Eg:</b> <br/>".
											". => 123.45 <br/>".
											", => 123,45 <br/>".
											"' => 123'45 <br/>".
											"  => 123 45 <br/>".
											"$ => 123$45 <br/>");
		$field3->setHelpInfo("<b>Currency - Grouping Separator</b> <br/><br/>".
											"Grouping separator specifies the separator to be used to group ".
											"the whole number part into hundreds, thousands etc. <br/>".
											"<b>Eg:</b> <br/>".
											". => 123.456.789 <br/>".
											", => 123,456,789 <br/>".
											"' => 123'456'789 <br/>".
											"  => 123 456 789 <br/>".
											"$ => 123$456$789 <br/>");
		$field4->setHelpInfo("<b>Currency - Symbol Placement</b> <br/><br/>".
											"Symbol Placement allows you to configure the position of the ".
											"currency symbol with respect to the currency value.<br/>".
											"<b>Eg:</b> <br/>".
											"$1.0 => $123,456,789.50 <br/>".
											"1.0$ => 123,456,789.50$ <br/>");
	}
}
