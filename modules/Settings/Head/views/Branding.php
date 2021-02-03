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

class Settings_Head_Branding_View extends Settings_Head_Index_View {

	public function process(Head_Request $request) {

		$qualifiedModuleName = $request->getModule(false);
		$moduleModel = Settings_Head_CompanyDetails_Model::getInstance();
		$loginimage = Settings_Head_LogoDetails_Model::getInstance();
		$viewer = $this->getViewer($request);
		$viewer->assign('LOGINIMAGE', $loginimage);
		$viewer->assign('COMPANY_DETAILS_MODULE_MODEL', $moduleModel);
		$viewer->assign('ERROR_MESSAGE', $request->get('error'));
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel()); 

		$systemDetailsModel = Settings_Head_Systems_Model::getInstanceFromServerType('email', 'OutgoingServer');
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		// $userModel = Users_Record_Model::getCurrentUserModel();
	    $userModuleModel = Users_Module_Model::getInstance('Users');
		$allModules = Settings_LanguageEditor_Module_Model::getAllModuleNames();
		$unwanted_modules = ['AddressLookup', 'CustomerPortal', 'Dashboard', 'DuplicateCheck', 'ModComments', 'ModTracker', 'Webmails', 'WSAPP', 'Mobile'];

		foreach($unwanted_modules as $unwanted_module) {
		    unset($allModules[$unwanted_module]);
	    }

		$viewer->assign('ALL_MODULES', $allModules);
        $viewer->assign('LANGUAGES', $userModuleModel->getLanguagesList());
		$viewer->assign('OUTGOING_SERVER_MODEL',$systemDetailsModel);
		$viewer->view('Branding.tpl', $qualifiedModuleName);
	}
	
	
	function getPageTitle(Head_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		return vtranslate('LBL_BRANDING',$qualifiedModuleName);
	}
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.$moduleName.resources.CompanyDetails",
			"modules/Settings/LanguageEditor/resources/List",
			"modules.Settings.$moduleName.resources.Branding"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	} 
}