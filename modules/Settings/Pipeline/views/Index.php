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

class Settings_Pipeline_Index_View extends Settings_Head_Index_View {

    function __construct() {
	parent::__construct();
    }

    function checkPermission(Head_Request $request) {
	$currentUserModel = Users_Record_Model::getCurrentUserModel();
	if(!$currentUserModel->isAdminUser()) {
	    throw new AppException(vtranslate('LBL_PERMISSION_DENIED', 'Head'));
	}
    }

    public function preProcess (Head_Request $request, $display=true) {
	parent::preProcess($request, false);
	$this->preProcessSettings($request,$display);
    }

    public function process(Head_Request $request) {
	global $adb, $current_user;
	$run_query = $adb->pquery("SELECT * FROM jo_visualpipeline");
	$row_count = $adb->getRowCount($run_query);

	$pipeline_modules = array();
	if($row_count > 0) {
	    while($query_result = $adb->fetch_array($run_query)) {
		array_push($pipeline_modules, $query_result);
	    }
	}

	$allModelsList = Head_Menu_Model::getAll(true);
	$menuModelStructure = Head_MenuStructure_Model::getInstanceFromMenuList($allModelsList);
	$moduleName = $request->getModule();
	$qualifiedModuleName = $request->getModule(false);
	$user_id = $current_user->id;

	$viewer = $this->getViewer($request);
	$viewer->assign('ALL_MODULES', $allModelsList);
	$viewer->assign('MODULE_NAME', $moduleName);
	$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
	$viewer->assign('count', $row_count);
	$viewer->assign('pipeline_modules', $pipeline_modules);

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
