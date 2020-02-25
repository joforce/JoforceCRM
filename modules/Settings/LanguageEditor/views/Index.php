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

class Settings_LanguageEditor_Index_View extends Settings_Head_Index_View {

    public function  preProcess(Head_Request $request) {
	parent::preProcess($request);
    }

    public function process(Head_Request $request) {
	global $adb, $current_user;
	$moduleName = $request->getModule();
	$qualifiedModuleName = $request->getModule(false);
        $user_id = $current_user->id;

        $viewer = $this->getViewer($request);
	$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
	$userModel = Users_Record_Model::getCurrentUserModel();
        $userModuleModel = Users_Module_Model::getInstance('Users');
	$allModules = Settings_LanguageEditor_Module_Model::getAllModuleNames();
	$unwanted_modules = ['AddressLookup', 'CustomerPortal', 'Dashboard', 'DuplicateCheck', 'ModComments', 'ModTracker', 'Webmails', 'WSAPP', 'Mobile'];

	foreach($unwanted_modules as $unwanted_module) {
	    unset($allModules[$unwanted_module]);
	}

	$viewer->assign('ALL_MODULES', $allModules);
        $viewer->assign('LANGUAGES', $userModuleModel->getLanguagesList());
        $viewer->view('Index.tpl', $qualifiedModuleName);
    }
	
    function getHeaderScripts(Head_Request $request) {
	$headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

        $jsFileNames = array(
	    "modules.Settings.$moduleName.resources.List",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}
?>
