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

class Settings_Pipeline_List_View extends Settings_Head_Index_View {

    public function  preProcess(Head_Request $request) {
        parent::preProcess($request);
    }

    public function process(Head_Request $request) {
	global $adb, $current_user;

	$mode = $request->get('mode');
	$pipeline_id = ($mode == 'edit') ? $request->get('id') : '';
	$moduleName = $request->getModule();
        $qualifiedModuleName = $request->getModule(false);

	$user_id = $current_user->id;
	$viewer = $this->getViewer($request);
	$model = new Settings_Pipeline_Module_Model();

        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
	$viewer->assign('MODULE_NAME', $moduleName);
	$viewer->assign('MODE', $mode);
	$viewer->assign("pipelineid", $pipeline_id);
	$viewer->assign("sel_picklist", '');

	if($mode == 'edit') {
	    $pipeline_info = $model->getPipelineDetails($pipeline_id, '');
	    $viewer->assign("pipeline_info", $pipeline_info);
            $selected_module = $pipeline_info['tabname'];
            $viewer->assign("sel_modulename", $selected_module);
            $viewer->assign("sel_picklist", $pipeline_info['picklist_name']);
	    $viewer->assign("picklists", $model->getPicklists($selected_module));

	    $sourceModuleModel = Head_Module_Model::getInstance($selected_module);
	    $fields = $model->getModuleFieldsWithoutNameFields($selected_module);
	    $viewer->assign('MODULE_FIELDS', $fields);

	    $selected_fields = json_decode(decode_html($pipeline_info['selected_fields']), true);
	    $viewer->assign('SELECTED_MODULE_FIELDS', $selected_fields);
	}

	$viewer->assign('ALL_MODULES', $model->getModuleList());
	$viewer->view('AddNew.tpl', $qualifiedModuleName);
    }

    function getHeaderScripts(Head_Request $request) {
        $headerScriptInstances = parent::getHeaderScripts($request);
        $moduleName = $request->getModule();

	$jsFileNames = array(
	    'modules.Head.resources.List',
            'modules.Settings.Head.resources.List',
	    "modules.Settings.$moduleName.resources.List",
            "~layouts/v7/lib/jquery/sadropdown.js",
        );

        $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
        $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
        return $headerScriptInstances;
    }
}
