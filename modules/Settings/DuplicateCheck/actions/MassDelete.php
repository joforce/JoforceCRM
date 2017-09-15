<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class Settings_DuplicateCheck_MassDelete_Action extends Head_Mass_Action {

	function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Delete')) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Head_Request $request) {
		return true;
	}

	function postProcess(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$recordIds = $this->getRecordsListFromRequest($request);

		foreach($recordIds as $recordId) 
		{
			if(Users_Privileges_Model::isPermitted($moduleName, 'Delete', $recordId)) 
			{
				$focus = array();
				$focus = CRMEntity::getInstance($moduleName);
		                $focus->id = $recordId;
                		$focus->retrieve_entity_info($recordId, $moduleName);
		                $modelClassName = Head_Loader::getComponentClassName('Model', 'Record', $moduleName);
                		$instance = new $modelClassName();
		                $recordModel = $instance->setData($focus->column_fields)->set('id',$recordId)->setModuleFromInstance($moduleModel)->setEntity($focus);
				$recordModel->delete();
			}
		}

		$cvId = $request->get('viewname');
		$response = new Head_Response();
		$response->setResult(array('viewname'=>$cvId, 'module'=>$moduleName));
		$response->emit();
	}
}
