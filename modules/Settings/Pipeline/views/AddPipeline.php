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

class Settings_Pipeline_AddPipeline_View extends Settings_Head_Index_View {
	
    public function process(Head_Request $request) {
	global $adb, $current_user;
	$model = new Settings_Pipeline_Module_Model();
	$mode = $request->get('mode');
	$moduleName = $request->getModule();
        $qualifiedModuleName = $request->getModule(false);

	$user_id = $current_user->id;
	$viewer = $this->getViewer($request);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
	$viewer->assign('MODULE_NAME', $moduleName);
	$viewer->assign('MODE', $mode);
	$viewer->assign("sel_picklist", '');
	if($mode == 'showmodal') {
	    $entity_modules = $model->getModuleList();
	    $viewer->assign('ALL_MODULES', $entity_modules);
	    $viewer->assign("pipelineid", null);
	    $viewer->view('AddPipeline.tpl', $qualifiedModuleName);
	} elseif($mode == 'getpicklist') {
	    $selected_module = $request->get('moduleName');
	    $html = $model->getPicklistOfModule($selected_module);

	    $response = new Head_Response();
            $response->setResult(array('success' => true, 'data'=> $html));
            $response->emit();
	} elseif($mode == 'save') {
	    $save_result = $model->SavePipeline($request);
	    if(isset($save_result)) {
		$message = 'Saved successfully';
	    } else {
		$message = 'Something went wrong';
	    }
            $response = new Head_Response();
            $response->setResult(array('success' => true, 'message' => $message));
            $response->emit();
	} elseif($mode == 'edit') {
	    $pipeline_id = $request->get('pipeline_id');
	    $pipeline_info = $model->getPipelineDetails($pipeline_id, '');

	    $selected_module = $pipeline_info['tabname'];
	    $viewer->assign("pipelineid", $pipeline_id);
	    $viewer->assign("sel_modulename", $selected_module);
	    $viewer->assign("sel_picklist", $pipeline_info['picklist_name']);
	    $viewer->assign("picklists", $model->getPicklists($selected_module));
	    $viewer->view('AddPipeline.tpl', $qualifiedModuleName);
	} elseif($mode == 'delete') {
	    $pipeline_id = $request->get('pipeline_id');
	    $delete_result = $model->deletePipeline($pipeline_id);
            if(isset($delete_result)) {
                $message = 'Pipeline deleted';
            } else {
                $message = 'Something went wrong';
            }
            $response = new Head_Response();
            $response->setResult(array('success' => true, 'message' => $message));
            $response->emit();
	}
    }
}
