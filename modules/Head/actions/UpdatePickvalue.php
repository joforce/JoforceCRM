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

class Head_UpdatePickvalue_Action extends Head_Save_Action {

    public function process(Head_Request $request) {
	global $adb, $current_user;
	$roleid = $current_user->roleid;
	$recordId = $request->get('recordId');
	$moduleName = $request->getModule();

	//Check if the pipeline view has been enabled for the current module
	$pipeModel = new Settings_Pipeline_Module_Model();
	$pipeine_modules = $pipeModel->getPipelineEnabledModules();
        $kanban_view = (in_array($moduleName, $pipeine_modules)) ? true : false;
	$html = '';

	if(isset($kanban_view)) {
	    $recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
	    $stages_info = getSalesStageArray('picklist');
	    $type = $request->get('type');

	    $pipeline_details = $pipeModel->getPipelineDetails('', $moduleName);
	    $picklist_name = $pipeline_details['picklist_name'];

	    if(!empty($picklist_name) && isset($picklist_name)) {
	    	//Role based picklist values with picklistid and picklistvalueid
	   	$currenct_roles_picklist_values = getRolesBasedPicklistValues($picklist_name , $roleid);

	    	if($type == 'update'){
		    $stageId = $request->get('stage_id');
		    $new_picklist_value = $currenct_roles_picklist_values[$stageId][$picklist_name];

		    $recordModel->set('id', $recordId);
		    $recordModel->set('mode', 'edit');

		    $recordModel->set($picklist_name, $new_picklist_value);
		    $recordModel->save();
	    	} elseif($type == 'onload') {
	            $new_picklist_value = $recordModel->get($picklist_name);
	            $stageId = getPicklistValueId($picklist_name , $roleid, $new_picklist_value);
		}

		$count_of_picklist = count($currenct_roles_picklist_values);
		if($count_of_picklist > 0) {
		    $width = 100/$count_of_picklist.'%';
		} else {
		    $width = '100%';
		}

	    	$current_stage_sequence = $currenct_roles_picklist_values[$stageId]['sortid'];
	    	foreach($currenct_roles_picklist_values as $id => $stage_info) {
	            $stage_value = vtranslate($stage_info[$picklist_name], $moduleName);
	            $stage_seq = $stage_info['sortorderid'];
	            if($id == $stageId && $stage_seq == $current_stage_sequence) {
		    	$html .= '<li style="width:'.$width.' !important;" id='.$id.' data-toggle="tooltip" title="'.$stage_value.'" class="active"><a href="#" data-toggle="tab">'. $stage_value .'</a></li>';
	            } elseif($stage_seq < $current_stage_sequence) {
		    	$html .= '<li style="width:'.$width.' !important;" id='.$id.' data-toggle="tooltip" title="'.$stage_value.'" class="completed"><a href="#" data-toggle="tab">'.$stage_value.'</a></li>';
	            } elseif($stage_seq > $current_stage_sequence) {
		    	$html .= '<li style="width:'.$width.' !important;" id='.$id.' data-toggle="tooltip" title="'.$stage_value.'"><a href="#" data-toggle="tab">'.$stage_value.'</a></li>';
	            }
	    	}
	    }
	}
	$response = new Head_Response();
	$response->setEmitType(Head_Response::$EMIT_JSON);
	$response->setResult($html);
	$response->emit();
    }
}
