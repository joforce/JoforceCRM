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

	public static function ImportDump() {
		include_once('includes/utils/utils.php');
		include_once("modules/Emails/mail.php");
		include_once('includes/logging.php');
		include_once('includes/http/Session.php');
		include_once('version.php');
		include_once('MySQLSearchReplace.php');
		include_once('config/config.inc.php');
		include_once('includes/utils/utils.php');

		require_once('vendor/autoload.php');
		include_once 'config/config.php';

		include_once 'vtlib/Head/Module.php';
		include_once 'includes/main/WebUI.php';
		global $adb, $dbconfig, $root_directory;
		global $log;
		global $site_URL;

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

		//Modules creation and updation
		/* updateVtlibModule('Import', 'packages/head/mandatory/Import.zip');
        	updateVtlibModule('PBXManager', 'packages/head/mandatory/PBXManager.zip');
	        updateVtlibModule('Mobile', 'packages/head/mandatory/Mobile.zip');
        	updateVtlibModule('ModTracker', 'packages/head/mandatory/ModTracker.zip');
	        updateVtlibModule('Services', 'packages/head/mandatory/Services.zip');
        	updateVtlibModule('WSAPP', 'packages/head/mandatory/WSAPP.zip'); */
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
	        //installVtlibModule('ModuleDesigner', 'packages/head/optional/ModuleDesigner.zip');
	
		//create files
	        create_tab_data_file();
        	crete_htacces_file();

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
