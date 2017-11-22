<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class AddressLookup 
{
	public function vtlib_handler($moduleName, $eventType)
	{
		global $adb;
		if ($eventType == 'module.postinstall') {
			$this->_registerLinks($moduleName);
			$runQuery = $adb->pquery("CREATE TABLE `jo_vtaddressmapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` text NOT NULL,
  `modulename` tinytext NOT NULL,
  `street` text,  
  `area` text,
  `locality` text,
  `city` text,
  `state` text,
  `country` text,
  `postalcode` text,
  `isenabled` enum('0','1') NOT NULL DEFAULT '0',
  `fieldset` int(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
)");
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
				VALUES (?,?,?,?,?,?,?)', array($fieldid, $blockid, 'Address Lookup', 'addreslookup.png', 'Auto Fill the address fields in each module',
					'AddressLookup/Settings/List', $seq));
		
		} else if ($eventType == 'module.enabled') {
			$this->_registerLinks($moduleName);
		} else if ($eventType == 'module.disabled') {
			$this->_deregisterLinks($moduleName);
		}
	}

	protected function _registerLinks($moduleName) {
		$thisModuleInstance = Head_Module::getInstance($moduleName);
		if ($thisModuleInstance) {
			$thisModuleInstance->addLink("HEADERSCRIPT", "Address Autofill", "layouts/modules/Settings/AddressLookup/jsresources/AddressLookup.js");
		}
	}

	protected function _deregisterLinks($moduleName) {
		$thisModuleInstance = Head_Module::getInstance($moduleName);
		if ($thisModuleInstance) {
			$thisModuleInstance->deleteLink("HEADERSCRIPT", "Address Autofill", "layouts/modules/Settings/AddressLookup/jsresources/AddressLookup.js");
		}
	}
}
