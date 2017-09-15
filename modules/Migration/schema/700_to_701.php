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
    header('Location: index.php?module=Users&parent=Settings&view=SystemSetup');
}
