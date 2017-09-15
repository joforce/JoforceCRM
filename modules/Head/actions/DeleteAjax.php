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

class Head_DeleteAjax_Action extends Head_Delete_Action {

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
		$recordModel->delete();

		$cvId = $request->get('viewname');
		deleteRecordFromDetailViewNavigationRecords($recordId, $cvId, $moduleName);
		$response = new Head_Response();
		$response->setResult(array('viewname'=>$cvId, 'module'=>$moduleName));
		$response->emit();
	}
}
