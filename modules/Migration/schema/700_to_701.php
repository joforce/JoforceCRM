<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *********************************************************************************/

if(defined('VTIGER_UPGRADE')) {
	global $adb, $current_user;
	$db = PearDatabase::getInstance();

    // Centralize user field table for easy query with context of user across module
    $generalUserFieldTable = 'jo_crmentity_user_field';
    if (!Head_Utils::CheckTable($generalUserFieldTable)) {
        Head_Utils::CreateTable($generalUserFieldTable,
                '(`recordid` INT(19) NOT NULL,
                `userid` INT(19) NOT NULL,
                `starred` VARCHAR(100) DEFAULT NULL)', true);
    }

    if (Head_Utils::CheckTable($generalUserFieldTable)) {
        $indexRes = $db->pquery("SHOW INDEX FROM $generalUserFieldTable WHERE NON_UNIQUE=? AND KEY_NAME=?", array('1', 'record_user_idx'));
        if ($db->num_rows($indexRes) < 2) {
            $db->pquery('ALTER TABLE jo_crmentity_user_field ADD CONSTRAINT record_user_idx UNIQUE KEY(recordid, userid)', array());
        }

        $checkUserFieldConstraintExists = $db->pquery('SELECT DISTINCT 1 FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE table_name=? AND CONSTRAINT_SCHEMA=?', array($generalUserFieldTable, $db->dbName));
        if ($db->num_rows($checkUserFieldConstraintExists) < 1) {
            $db->pquery('ALTER TABLE jo_crmentity_user_field ADD CONSTRAINT `fk_jo_crmentity_user_field_recordid` FOREIGN KEY (`recordid`) REFERENCES `jo_crmentity`(`crmid`) ON DELETE CASCADE', array());
        }
    }

    echo '<br>Succesfully centralize user field table for easy query with context of user across module<br>';
    // Centralize user field table for easy query with context of user across module

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
       $db->pquery("CREATE TABLE `jo_loginhistory` (
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
	
	//Add Content for Notification Module

	//Create table jo_notification
	if (!Head_Utils::CheckTable('jo_notification')) {	
		$db->pquery("CREATE TABLE `jo_notification` (
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

	// remove customer portal for the version 1.3
	$db->pquery("DELETE FROM jo_settings_field WHERE name = ?", array('LBL_CUSTOMER_PORTAL'));

	header('Location: index.php?module=Users&parent=Settings&view=SystemSetup');
}
