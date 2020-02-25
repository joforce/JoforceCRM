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

class Install_MysqlImport_Model {

	public static function ImportDump($configParams) {
		include_once('includes/utils/utils.php');
		include_once("modules/Emails/mail.php");
		include_once('includes/http/Session.php');
		include_once('version.php');
		include_once('MySQLSearchReplace.php');
		include_once('config/config.inc.php');
		include_once('includes/utils/utils.php');

		require_once('vendor/autoload.php');
		include_once 'config/config.php';

		include_once 'vtlib/Head/Module.php';
		include_once 'includes/main/WebUI.php';
		global $adb, $dbconfig, $root_directory, $site_URL;

		// import mysql file
                $query = '';
		$adb->pquery('SET foreign_key_checks = 0');

                $sqlScript = file('schema/import.sql');
                foreach ($sqlScript as $line)   {
        
                        $startWith = substr(trim($line), 0 ,2);
                        $endWith = substr(trim($line), -1 ,1);
        
                        if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                                continue;
                        }
                
                        $query = $query . $line;
                        if ($endWith == ';') {
                                $adb->pquery($query);
                                $query= '';             
                        }
                }

		$adb->pquery('SET foreign_key_checks = 1');
		$currencyName = $configParams['currency_name'];
                $currencyCode = $configParams['currency_code'];
                $currencySymbol = $configParams['currency_symbol'];
                $adb->pquery("INSERT INTO jo_currency_info VALUES (?,?,?,?,?,?,?,?)", array($adb->getUniqueID("jo_currency_info"), $currencyName,$currencyCode,$currencySymbol,1,'Active','-11','0'));

		// Kanban view Extenion module related chanages - starts
		include_once('vtlib/Head/Module.php');
		$fieldid = $adb->getUniqueID('jo_settings_field');
		$blockid = getSettingsBlockId('LBL_MODULE_MANAGER');
		$seq_res = $adb->pquery("SELECT max(sequence) AS max_seq FROM jo_settings_field WHERE blockid = ?", array($blockid));
		$seq = 1;
		if ($adb->num_rows($seq_res) > 0) {
		    $cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
		    if ($cur_seq != null) {
		        $seq = $cur_seq + 1;
		    }
		}

		$adb->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active, pinned) VALUES (?,?,?,?,?,?,?,?,?)', array($fieldid, $blockid, 'Kanban view', 'fa fa-th-large', 'KanbanView', 'Pipeline/Settings/Index', $seq, 0, 0));

		if (!Head_Utils::CheckTable('jo_visualpipeline')) {
                        Head_Utils::CreateTable('jo_visualpipeline',
                                        	"(`pipeline_id` int(19) NOT NULL,
						  `tabid` int(10) DEFAULT NULL,
						  `tabname` varchar(200) DEFAULT NULL,
						  `picklist_name` varchar(100) DEFAULT NULL,
						  `records_per_page` int(10) DEFAULT NULL,
						  `selected_fields` varchar(255) DEFAULT NULL,
						  PRIMARY KEY (`pipeline_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8", true);
                }
		// Kanban view Extenion module related chanages - ends

		//Modules creation and updation
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
        	updateVtlibModule("Dutch","packages/head/optional/Dutch.zip");
        	updateVtlibModule("BrazilianLanguagePack_bz_bz","packages/head/optional/BrazilianLanguagePack_bz_bz.zip");
	        updateVtlibModule("BritishLanguagePack_br_br","packages/head/optional/BritishLanguagePack_br_br.zip");
	        updateVtlibModule("French","packages/head/optional/French.zip");
        	updateVtlibModule("Hungarian","packages/head/optional/Hungarian.zip");
	        updateVtlibModule("ItalianLanguagePack_it_it","packages/head/optional/ItalianLanguagePack_it_it.zip");
        	updateVtlibModule("MexicanSpanishLanguagePack_es_mx","packages/head/optional/MexicanSpanishLanguagePack_es_mx.zip");
	        updateVtlibModule("Deutsch","packages/head/optional/Deutsch.zip");
	        updateVtlibModule("PolishLanguagePack_pl_pl","packages/head/optional/PolishLanguagePack_pl_pl.zip");
        	updateVtlibModule("RomanianLanguagePack_rm_rm","packages/head/optional/RomanianLanguagePack_rm_rm.zip");
	        updateVtlibModule("Russian","packages/head/optional/Russian.zip");
        	updateVtlibModule("TurkishLanguagePack_tr_tr","packages/head/optional/TurkishLanguagePack_tr_tr.zip");
		updateVtlibModule("Spanish","packages/head/optional/Spanish.zip");
	
		//create files
	        create_tab_data_file();
        	crete_htacces_file();

		//Add related field to related list table for projects
		$project_milestone_tabid = getTabid('ProjectMilestone');
		$project_tabid = getTabid('Project');
		$project_task_tabid = getTabid('ProjectTask');
		$milestone_project = $adb->fetch_array($adb->pquery('select fieldid from jo_field where fieldname = ? and tabid = ?', array('projectid', $project_milestone_tabid)));
		$milestone_project_fieldid = $milestone_project['fieldid'];

		$task_project = $adb->fetch_array($adb->pquery('select fieldid from jo_field where fieldname = ? and tabid = ?', array('projectid', $project_milestone_tabid)));
		$task_project_fieldid = $task_project['fieldid'];
		$update_query = 'update jo_relatedlists set relationfieldid = ? where tabid = ? and related_tabid = ?';
		$adb->pquery($update_query , array($milestone_project_fieldid, $project_tabid, $project_milestone_tabid)); //milestone
		$adb->pquery($update_query , array($task_project_fieldid, $project_tabid, $project_task_tabid)); //project task
		
		$adb->pquery("INSERT INTO jo_relatedlists VALUES(" . $adb->getUniqueID('jo_relatedlists') . "," . getTabid('Potentials') . "," . getTabid('HelpDesk') . ",'get_related_list',5,'HelpDesk',0, 'add,select','','','1:N')");
		$adb->pquery("INSERT INTO jo_relatedlists VALUES(" . $adb->getUniqueID('jo_relatedlists') . "," . getTabid('HelpDesk') . "," . getTabid('Potentials') . ",'get_related_list',6,'Potentials',0, 'add,select','','','1:N')");

		$ticket_tabid = getTabid('HelpDesk');
	        $adb->pquery('update jo_field set summaryfield = ? where fieldname = ? and tabid = ?', array(0,'description',$ticket_tabid));

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
	}
}
?>
