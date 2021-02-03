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

class Inventory_MassSave_Action extends Head_MassSave_Action {

	public function process(Head_Request $request) {
        vglobal('JOFORCE_TIMESTAMP_NO_CHANGE_MODE', $request->get('_timeStampNoChangeMode',false));
		$moduleName = $request->getModule();
		$recordModels = $this->getRecordModelsFromRequest($request);
		foreach($recordModels as $recordId => $recordModel) {
			if(Users_Privileges_Model::isPermitted($moduleName, 'Save', $recordId)) {
				//Inventory line items getting wiped out
				$_REQUEST['ajxaction'] = 'DETAILVIEW';
				$recordModel->save();
			}
		}
        vglobal('JOFORCE_TIMESTAMP_NO_CHANGE_MODE', false);
		$response = new Head_Response();
		$response->setResult(true);
		$response->emit();
	}
}
