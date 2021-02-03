<?php

/***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class ModuleDesigner
{
	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function modlib_handler($module_name, $event_type)
	{
		global $adb;
	
		$module = Head_Module::getInstance($module_name);
	
		if($event_type == 'module.postinstall')
		{
			//Don't allow users to download the module
			$adb->pquery("UPDATE jo_tab SET customized=? WHERE tabid=?", array(0, $module->id));
			
			//************* Set access right for all profiles ***********************//
			//Don't display module name in menu
			$adb->pquery("UPDATE jo_profile2tab SET permissions=? WHERE tabid=?", array(1, $module->id));
			
			//Don't allow action on the module
			$adb->pquery("UPDATE jo_profile2standardpermissions SET permissions=? WHERE tabid=?", array(1, $module->id));
			
			//Add link to the module in the Setting Panel
			$fieldid = $adb->getUniqueID('jo_settings_field');
			$blockid = getSettingsBlockId('LBL_STUDIO');
			
			$seq_res = $adb->query("SELECT max(sequence) AS max_seq FROM jo_settings_field WHERE blockid=$blockid");
			$seq = 1;
			if ($adb->num_rows($seq_res) > 0)
			{
				$cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
				
				if ($cur_seq != null)
				{
					$seq = $cur_seq + 1;
				}
			}
				
			$adb->pquery
			(
				'INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active) VALUES (?,?,?,?,?,?,?,?)',
				array
				(
					$fieldid,
					$blockid,
					$module_name,
					'layouts/modules/Settings/'.$module_name.'/assets/images/'.$module_name.'.png',
					'LBL_'.strtoupper($module_name).'_DESCRIPTION',
					$module_name.'/Index/Settings',
					$seq,
					0
				)
			);
		}
		else if($event_type == 'module.disabled')
		{
			// TODO Handle actions when this module is disabled.
		}
		else if($event_type == 'module.enabled')
		{
			// TODO Handle actions when this module is enabled.
		}
		else if($event_type == 'module.preuninstall')
		{
			$adb->pquery('DELETE FROM jo_settings_field WHERE name = ?', array($module_name));
		}
		else if($event_type == 'module.preupdate')
		{
			// TODO Handle actions before this module is updated.
		}
		else if($event_type == 'module.postupdate')
		{
			//Don't allow users to download the module
			$adb->pquery("UPDATE jo_tab SET customized=? WHERE tabid=?", array(0, $module->id));
			
			$query = "SELECT * FROM jo_settings_field WHERE name = ?";
			$result = $adb->pquery($query, array($module_name));
			
			//Add link to the module in the Setting Panel
			$fieldid = $adb->getUniqueID('jo_settings_field');
			$blockid = getSettingsBlockId('LBL_STUDIO');
			
			if($adb->num_rows($result) == 0)
			{			
				$seq_res = $adb->query("SELECT max(sequence) AS max_seq FROM jo_settings_field WHERE blockid=$blockid");
				$seq = 1;
				if ($adb->num_rows($seq_res) > 0)
				{
					$cur_seq = $adb->query_result($seq_res, 0, 'max_seq');
					
					if ($cur_seq != null)
					{
						$seq = $cur_seq + 1;
					}
				}
					
				$adb->pquery
				(
					'INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active) VALUES (?,?,?,?,?,?,?,?)',
					array
					(
						$fieldid,
						$blockid,
						$module_name,
						'layouts/modules/Settings/'.$module_name.'/assets/images/'.$module_name.'.png',
						'LBL_'.strtoupper($module_name).'_DESCRIPTION',
						'index.php?module='.$module_name.'&view=Index&parent=Settings',
						$seq,
						0
					)
				);
			}
		}
	}
}

?>
