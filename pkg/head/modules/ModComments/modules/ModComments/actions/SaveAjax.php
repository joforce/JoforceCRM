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

class ModComments_SaveAjax_Action extends Head_SaveAjax_Action {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');
		//Do not allow ajax edit of existing comments
		if ($record) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function process(Head_Request $request) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
        $userId = $currentUserModel->getId();
		$request->set('assigned_user_id', $userId);
		$request->set('userid', $userId);
		
		$recordModel = $this->saveRecord($request);
        
		$fieldModelList = $recordModel->getModule()->getFields();
		$result = array();
		foreach ($fieldModelList as $fieldName => $fieldModel) {
			$fieldValue = $recordModel->get($fieldName);
			$result[$fieldName] = array('value' => $fieldValue, 'display_value' => $fieldModel->getDisplayValue($fieldValue));
		}
		$result['id'] = $result['_recordId'] = $recordModel->getId();
		$result['_recordLabel'] = $recordModel->getName();
        
        if($request->get('source_module') == 'Cases'){
            $caseRecordModel = Head_Record_Model::getInstanceById($request->get('related_to'));
            //Notify to other users, who are there in detail view of the same record
            $caseRecordModel->notifyToUser($userId);
            //
        }
        
		$response = new Head_Response();
		$response->setEmitType(Head_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}
	
	/**
	 * Function to save record
	 * @param <Head_Request> $request - values of the record
	 * @return <RecordModel> - record Model of saved record
	 */
	public function saveRecord($request) {
		$recordModel = $this->getRecordModelFromRequest($request);
		
		$recordModel->save();
		if($request->get('relationOperation')) {
			$parentModuleName = $request->get('sourceModule');
			$parentModuleModel = Head_Module_Model::getInstance($parentModuleName);
			$parentRecordId = $request->get('sourceRecord');
			$relatedModule = $recordModel->getModule();
			$relatedRecordId = $recordModel->getId();

			$relationModel = Head_Relation_Model::getInstance($parentModuleModel, $relatedModule);
			$relationModel->addRelation($parentRecordId, $relatedRecordId);
		}
		return $recordModel;
	}
	
	/**
	 * Function to get the record model based on the request parameters
	 * @param Head_Request $request
	 * @return Head_Record_Model or Module specific Record Model instance
	 */
	public function getRecordModelFromRequest(Head_Request $request) {
		$recordModel = parent::getRecordModelFromRequest($request);
		
		$recordModel->set('commentcontent', $request->getRaw('commentcontent'));
        $recordModel->set('is_private', $request->get('is_private'));

		return $recordModel;
	}
}