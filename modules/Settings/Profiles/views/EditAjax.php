<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Class Settings_Profiles_EditAjax_View extends Settings_Profiles_Edit_View {

    public function preProcess(Head_Request $request) {
        return true;
    }
    
    public function postProcess(Head_Request $request) {
        return true;
    }
    
    public function process(Head_Request $request) {
        echo $this->getContents($request);
    }
    
    public function getContents(Head_Request $request) {
        global $site_URL;
        $this->initialize($request);
		
        $qualifiedModuleName = $request->getModule(false);
        $viewer = $this->getViewer ($request);
        $viewer->assign('SITEURL', $site_URL);
		$viewer->assign('SCRIPTS', $this->getHeaderScripts($request));
        $viewer->assign('SHOW_EXISTING_PROFILES', true);
        return $viewer->view('EditViewContents.tpl',$qualifiedModuleName,true);
    }
	
	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.Profiles.resources.Profiles",
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return $jsScriptInstances;
	}
    
}
