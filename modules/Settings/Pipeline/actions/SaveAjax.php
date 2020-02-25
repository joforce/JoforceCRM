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

class Settings_Pipeline_SaveAjax_Action extends Settings_Head_Basic_Action {
	
    public function process(Head_Request $request) {
	global $adb, $current_user;
	$model = new Settings_Pipeline_Module_Model();
	$mode = $request->get('mode');
	$moduleName = $request->getModule();
        $qualifiedModuleName = $request->getModule(false);
	$user_id = $current_user->id;

	$response = new Head_Response();
	if($mode == 'create' || $mode == 'edit') {
	    $save_result = $model->SavePipeline($request);
	    if(isset($save_result)) {
		$message = 'Saved successfully';
	    } else {
		$message = 'Something went wrong';
	    }
            $response->setResult(array('success' => true, 'message' => $message));
	} elseif($mode == 'delete') {
	    $pipeline_id = $request->get('pipeline_id');
	    $delete_result = $model->deletePipeline($pipeline_id);
            if(isset($delete_result)) {
                $message = 'Pipeline deleted';
            } else {
                $message = 'Something went wrong';
            }
            $response->setResult(array('success' => true, 'message' => $message));
	}
        $response->emit();
    }
}
