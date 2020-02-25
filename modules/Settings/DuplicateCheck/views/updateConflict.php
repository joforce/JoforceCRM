<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class Settings_DuplicateCheck_updateConflict_View extends Settings_Head_Index_View
{
	public function __construct()
	{
		parent::__construct();
	}

	public function process(Head_Request $request)
	{
		global $adb;
		$checked = $_REQUEST['checked'];
		$isenabled = 0;
		if($checked == 'true')
			$isenabled = 1;

		$adb->pquery("update jo_duplicatechecksettings set isenabled = ? where modulename = ?",array( $isenabled, 'deleteconflict'));
		die('SUCCESS');
	}
}
