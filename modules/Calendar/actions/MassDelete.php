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

class Calendar_MassDelete_Action extends Head_MassDelete_Action {

	public function process(Head_Request $request) {
		$adb = PearDatabase::getInstance();
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		if ($request->get('selected_ids') == 'all' && $request->get('mode') == 'FindDuplicates') {
            $recordIds = Head_FindDuplicate_Model::getMassDeleteRecords($request);
        } else {
            $recordIds = $this->getRecordsListFromRequest($request);
        }
		$cvId = $request->get('viewname');
		foreach($recordIds as $recordId) {
			if(Users_Privileges_Model::isPermitted($moduleName, 'Delete', $recordId)) {
				$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleModel);
				$parentRecurringId = $recordModel->getParentRecurringRecord();
				$adb->pquery('DELETE FROM jo_activity_recurring_info WHERE activityid=? AND recurrenceid=?', array($parentRecurringId, $recordId));
				$recordModel->delete();
				deleteRecordFromDetailViewNavigationRecords($recordId, $cvId, $moduleName);
			}
		}
		$response = new Head_Response();
		$response->setResult(array('viewname'=>$cvId, 'module'=>$moduleName));
		$response->emit();
	}
}
