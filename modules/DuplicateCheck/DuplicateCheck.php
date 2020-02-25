<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class DuplicateCheck {
	
	/**
	* Invoked when special actions are performed on the module.
	* @param String Module name
	* @param String Event Type
	*/
	function vtlib_handler($moduleName, $eventType) {
		global $adb;
 		if($eventType == 'module.postinstall') 
		{	
			include_once('vtlib/Head/Module.php');
			$moduleInstance = Head_Module::getInstance($moduleName);

			$fieldid = $adb->getUniqueID('jo_settings_field');
			$blockid = getSettingsBlockId('LBL_OTHER_SETTINGS');
			$seq_res = $adb->pquery("SELECT max(sequence) AS max_seq FROM jo_settings_field WHERE blockid = ?", array($blockid));
			$seq = 1;
			if ($adb->num_rows($seq_res) > 0) {
				$cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
				if ($cur_seq != null) {
					$seq = $cur_seq + 1;
				}
			}
			$adb->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence)
				VALUES (?,?,?,?,?,?,?)', array($fieldid, $blockid, 'Duplicate Check', 'fa fa-copy', 'DuplicateCheck',
					'DuplicateCheck/Settings/List', $seq));
			// inserting values into settings table
			$modules = array('Contacts', 'Leads', 'Accounts', 'Potentials', 'Products', 'Services', 'HelpDesk', 'Project', 'ProjectTask', 'ProjectMilestone', 'Vendors', 'Calendar','Campaigns','Quotes','PurchaseOrder','SalesOrder','Invoice','PriceBooks','Documents','Emails','Events','Users','PBXManager','ModComments','SMSNotifier');
			foreach($modules as $module)
				$adb->pquery("insert into jo_duplicatechecksettings (modulename, isenabled, crosscheck) values (?, ?, ?)", array($module, 1, 0));

                        $adb->pquery('insert into jo_duplicatechecksettings (modulename, isenabled, crosscheck) values (?, ?, ?)', array('deleteconflict', 1, 0));
                        $adb->pquery('insert into jo_duplicatechecksettings (modulename, isenabled, crosscheck) values (?, ?, ?)', array('assignedto', 1, 0));
                        $adb->pquery("insert into jo_duplicatechecksettings (modulename, isenabled, crosscheck) values (?, ?, ?)", array($moduleName, 1, 0));
			// Check ws_entity added
			$checkEntry = $adb->pquery('select * from jo_ws_entity where name = ?', array($moduleName));
			$count = $adb->num_rows($checkEntry);

            $entityId = $adb->getUniqueID("jo_ws_entity"); 
            $adb->pquery('insert into jo_ws_entity (id, name, handler_path, handler_class, ismodule) values (?, ?, ?, ?, ?)', array($entityId, $moduleName, 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));
		} 
		else if($eventType == 'module.disabled') {
			// TODO Handle actions before this module is being uninstalled.
			$moduleInstance=Head_Module::getInstance($moduleName);
                        $moduleInstance->deleteLink('HEADERSCRIPT', 'Duplicate Check', 'layouts/modules/Settings/DuplicateCheck/jsresources/duplicatecheck.js');
                        $moduleInstance->deleteLink('HEADERSCRIPT', 'Duplicate Check Quick Create', 'layouts/modules/Settings/DuplicateCheck/jsresources/quickcreateduplicatecheck.js');
                } else if($eventType == 'module.enabled') {
                        // TODO Handle actions before this module is being installed.
                        $moduleInstance=Head_Module::getInstance($moduleName);
                        $moduleInstance->addLink('HEADERSCRIPT', 'Duplicate Check', 'layouts/modules/Settings/DuplicateCheck/jsresources/duplicatecheck.js');
                        $moduleInstance->addLink('HEADERSCRIPT', 'Duplicate Check Quick Create', 'layouts/modules/Settings/DuplicateCheck/jsresources/quickcreateduplicatecheck.js');
		} else if($eventType == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($eventType == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($eventType == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
			
		}
 	}
}

