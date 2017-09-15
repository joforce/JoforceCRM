<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class Settings_DuplicateCheck_ModuleManager_View extends Settings_Head_Index_View
{
	public function __construct()
	{
		parent::__construct();
	}

	public function process(Head_Request $request)
	{
		global $adb;
		$module = $_REQUEST['mod'];
		$mode   = $_REQUEST['mode'];

		if(empty($module) || trim($module) == '' || empty($mode) || trim($mode) == '')
		{
			die('FAILURE');
		}

		$enable = 0;
		if($mode == 'enable')	
		{
			$enable = 1;
		}

		$result = $adb->pquery("update jo_vtduplicatechecksettings set isenabled = ? where modulename = ?", array($enable, $module));
		if($result)	
		{	
			die('SUCCESS');		
		}
		die('FAILURE');
	}
}
