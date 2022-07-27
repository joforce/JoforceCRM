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
		include_once('config/config.inc.php');
		include_once('MySQLSearchReplace.php');
		include_once('config/config.inc.php');
		include_once('includes/utils/utils.php');

		require_once('vendor/autoload.php');
		include_once 'config/config.php';

		include_once 'libraries/modlib/Head/Module.php';
		include_once 'includes/main/WebUI.php';
		global $adb, $dbconfig, $root_directory, $site_URL;

		// import mysql file
                $query = '';
		$adb->pquery('SET foreign_key_checks = 0');
		$adb->pquery('ALTER DATABASE '.$dbconfig['db_name'].' CHARACTER SET utf8 COLLATE utf8_general_ci');
				$fileCount = 0;
				$sqlScript = file('migrate/import.sql');
                foreach ($sqlScript as $line)   {
					if (strpos($line, 'CREATE TABLE') !== false) {
						$fileCount = $fileCount+1;
					}
				}
				if (strlen(session_id()) === 0) {
					session_start();
					unset($_SESSION['progress']);
				}
				$i = 0;
                $sqlScript = file('migrate/import.sql');
                foreach ($sqlScript as $line)   {
					$startWith = substr(trim($line), 0 ,2);
					$endWith = substr(trim($line), -1 ,1);
	
					if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
							continue;
					}

					if (strpos($line, 'CREATE TABLE') !== false) {
						$i = $i+1;

						$progress = round(($i / $fileCount) * 99);
						if (isset($_SESSION['progress'])) {
							session_start(); //IMPORTANT!
						}
						$_SESSION['progress'] = $progress;
						session_write_close(); //IMPORTANT!						
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
				
				$result = $adb->pquery("SELECT * From jo_currency_info Where currency_name = ?",array($currencyName));
				$rows = $adb->num_rows($result);
				if($rows <= 0){
                	$adb->pquery("INSERT INTO jo_currency_info VALUES (?,?,?,?,?,?,?,?)", array($adb->getUniqueID("jo_currency_info"), $currencyName,$currencyCode,$currencySymbol,1,'Active','-11','0'));
					$adb->pquery('update jo_currency_info set defaultid = 0 where currency_name != ?', array($currencyName));
				}
				$result = $adb->pquery("SELECT * From jo_currency_info Where currency_name = ?",array($currencyName));
				$currency_id = $adb->query_result($result,0,'id');
				$currency_name = $adb->query_result($result,0,'currency_name');
				$currency_code = $adb->query_result($result,0,'currency_code');
				$currency_symbol = $adb->query_result($result,0,'currency_symbol');

				$adb->pquery('update jo_users set currency_id = ? where id = 1', array($currency_id));

				$result = $adb->pquery('select * from jo_privileges where user_id = ?', array("1"));
				$user_privilege = $adb->query_result($result,0,'user_privilege');
				$user_priv = json_decode(html_entity_decode($user_privilege));
				$user_priv->user_info->currency_id = $currency_id;
				$user_priv->user_info->currency_name = $currency_name;
				$user_priv->user_info->currency_code = $currency_code;
				$user_priv->user_info->currency_symbol = $currency_symbol;
				$upd_user_priv = html_entity_decode(json_encode($user_priv));
				$adb->pquery('update jo_privileges set user_privilege = ? where user_id = 1', array($upd_user_priv));

		// Kanban view Extenion module related chanages - starts
		include_once('libraries/modlib/Head/Module.php');
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
	        updateModlibModule('Arabic_ar_ae', 'cache/packages/Arabic_ar_ae.zip');
        	updateModlibModule("Sweden_sv_se","cache/packages/Sweden_sv_se.zip");
        	updateModlibModule("Dutch","cache/packages/Dutch.zip");
        	updateModlibModule("BrazilianLanguagePack_bz_bz","cache/packages/BrazilianLanguagePack_bz_bz.zip");
	        updateModlibModule("BritishLanguagePack_br_br","cache/packages/BritishLanguagePack_br_br.zip");
	        updateModlibModule("French","cache/packages/French.zip");
        	updateModlibModule("Hungarian","cache/packages/Hungarian.zip");
	        updateModlibModule("ItalianLanguagePack_it_it","cache/packages/ItalianLanguagePack_it_it.zip");
        	updateModlibModule("MexicanSpanishLanguagePack_es_mx","cache/packages/MexicanSpanishLanguagePack_es_mx.zip");
	        updateModlibModule("Deutsch","cache/packages/Deutsch.zip");
	        updateModlibModule("PolishLanguagePack_pl_pl","cache/packages/PolishLanguagePack_pl_pl.zip");
        	updateModlibModule("RomanianLanguagePack_rm_rm","cache/packages/RomanianLanguagePack_rm_rm.zip");
	        updateModlibModule("Russian","cache/packages/Russian.zip");
        	updateModlibModule("TurkishLanguagePack_tr_tr","cache/packages/TurkishLanguagePack_tr_tr.zip");
			updateModlibModule("Spanish","cache/packages/Spanish.zip");
			//create files
	        create_tab_data_file();
			crete_htacces_file();
			
			$adb->query("update jo_settings_field as a 
							inner join jo_settings_blocks as b on b.label='LBL_AUTOMATION' 
							set a.blockid=b.blockid 
							where a.name in (
							'LBL_MAIL_SCANNER' , 'LBL_LEAD_MAPPING'
							)"
						);

			$adb->query("update jo_settings_field as a 
							inner join jo_settings_blocks as b on b.label='LBL_COMPANY_INFO' 
							set a.blockid=b.blockid 
							where a.name in (
							'LBL_TAX_SETTINGS' , 'INVENTORYTERMSANDCONDITIONS'
							)"
						);	

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
