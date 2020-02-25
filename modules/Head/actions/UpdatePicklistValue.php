<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/
class Head_UpdatePicklistValue_Action extends Head_Save_Action {
    public function process(Head_Request $request) {
	global $adb, $site_URL, $current_user;
	$roleid = $current_user->roleid;
	$moduleName = $request->getModule();

        $record_id = $request->get('record_id');
	$new_stage_id = $request->get('new_stage_id');
	$picklist_name = $request->get('picklist_name');
	$picklist_id = $request->get('picklist_id');
	//Role based picklist values with picklistid and picklistvalueid
        $currenct_roles_picklist_values = getRolesBasedPicklistValues($picklist_name , $roleid);

	$new_picklist_value = $currenct_roles_picklist_values[$new_stage_id][$picklist_name];
	$moduleModel = Head_Module_Model::getInstance($moduleName);

        $recordModel = Head_Record_Model::getInstanceById($record_id, $moduleName);
        $recordModel->set('id', $record_id);
        $recordModel->set('mode', 'edit');

        $fieldModelList = $moduleModel->getFields();
        $recordModel->set($picklist_name, $new_picklist_value); // update value here
	$recordModel->save();

        $response = new Head_Response();
        $response->setResult(array('success' => 'true'));
        $response->emit();
    }
}
