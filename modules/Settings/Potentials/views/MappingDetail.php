<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Potentials_MappingDetail_View extends Settings_Head_Index_View {

	function checkPermission(Head_Request $request) {
		parent::checkPermission($request);
		$sourceModule = 'Potentials';
		if(!vtlib_isModuleActive($sourceModule)){
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $sourceModule));
		}
	}

	public function process(Head_Request $request) {
		$qualifiedModuleName = $request->getModule(false);

		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_MODEL', Settings_Potentials_Mapping_Model::getInstance());
		$viewer->assign('ERROR_MESSAGE', $request->get('errorMessage'));
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('MappingDetail.tpl', $qualifiedModuleName);
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
			"modules.Settings.$moduleName.resources.PotentialMapping",
            "~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/jquery/floatThead/jquery.floatThead.js",
            "~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/jquery/perfect-scrollbar/js/perfect-scrollbar.jquery.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
        
        public function getHeaderCss(Head_Request $request) {
            $headerCssInstances = parent::getHeaderCss($request);
            $cssFileNames = array(
                "~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/jquery/perfect-scrollbar/css/perfect-scrollbar.css",
            );
            $cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
            $headerCssInstances = array_merge($headerCssInstances, $cssInstances);
            return $headerCssInstances;
        }
}