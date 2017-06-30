<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class ProjectTask_SaveTask_Action extends Vtiger_Save_Action {

	public function process(Vtiger_Request $request) {
		$recordModel = $this->saveRecord($request);

		$response = new Vtiger_Response();
		$response->setResult(array('record'=>$recordModel->getId(), 'module'=>$recordModel->getModuleName()));
		$response->emit();
	}

	/**
	 * Function to save record
	 * @param <Vtiger_Request> $request - values of the record
	 * @return <RecordModel> - record Model of saved record
	 */
	public function saveRecord($request) {
		$recordModel = $this->getRecordModelFromRequest($request);
		$recordModel->save();
		return $recordModel;
	}
}
