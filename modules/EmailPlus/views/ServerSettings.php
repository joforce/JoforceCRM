<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class EmailPlus_ServerSettings_View extends Head_Index_View
{
    public function preProcess(Head_Request $request, $display = true)
    {
	parent::preProcess($request, false);
	global $adb, $current_user;
	$viewer = $this->getViewer($request);
	$viewer->assign('CVNAME', 'Configuration');
	if ($display) {
	    $this->preProcessDisplay($request);
	}
    }

    public function process(Head_Request $request)
    {
        global $site_URL, $current_user, $adb;
        $viewer = $this->getViewer($request);
	$viewer->assign('SITE_URL', $site_URL);
        $moduleName = $request->getModule();
        $moduleModel = Head_Module_Model::getInstance($moduleName);

        include_once('modules/EmailPlus/views/SettingsFunctionality.php');
    }
}
