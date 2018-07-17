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
class Potentials_UpdateSalesStage_Action extends Head_Save_Action {

public function process(Head_Request $request) {
        global $site_URL;

	$moduleName = $request->getModule();
        $potential_id = $request->get('potential_id');
	$sales_stage_id = $request->get('sales_stage_id');

	$moduleModel = Head_Module_Model::getInstance($moduleName);

        $recordModel = Head_Record_Model::getInstanceById($potential_id, $moduleName);
        $recordModel->set('id', $potential_id);
        $recordModel->set('mode', 'edit');

        $fieldModelList = $moduleModel->getFields();
	$fieldValue = getStageName($sales_stage_id);
        $recordModel->set('sales_stage', $fieldValue);
	$recordModel->save();

//	header("Location: index.php?module=Potentials&view=Forecast&app=SALES");
}

}
