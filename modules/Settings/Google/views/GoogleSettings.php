<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Google_GoogleSettings_View extends Settings_Head_Index_View
{
    public function process(Head_Request $request) {
        global $site_URL;
        $qualifiedModuleName = $request->getModule(false);
        include_once('modules/Google/connectors/Config.php');
        $block_id = $request->get('block');
        $field_id = $request->get('fieldid');
        $result = $request->get('error');
        $viewer = $this->getViewer($request);
        $viewer->assign('CLIENT_ID', Google_Config_Connector::$clientId);
        $viewer->assign('CLIENT_SECRET', Google_Config_Connector::$clientSecret);
        $viewer->assign('SITEURL', $site_URL);
        $viewer->assign('BLOCKID', $block_id);
        $viewer->assign('FIELDID', $field_id);
        $viewer->assign('RESULT', $result);
        $viewer->view('GoogleSettings.tpl', $qualifiedModuleName);
    }
}

