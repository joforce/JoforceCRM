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

class Head_NoteBook_Action extends Head_Action_Controller {

	function __construct() {
		$this->exposeMethod('NoteBookCreate');
	}

	function process(Head_Request $request) {
		$mode = $request->getMode();

		if($mode){
			$this->invokeExposedMethod($mode,$request);
		}
	}

	function NoteBookCreate(Head_Request $request){
		$adb = PearDatabase::getInstance();

		$moduleName = $request->getModule();
		$userModel = Users_Record_Model::getCurrentUserModel();
		$linkId = $request->get('linkId');
		$noteBookName = $request->get('notePadName');
		$noteBookContent = $request->get('notePadContent');
		$tabId = $request->get("tab");
		$userid = $userModel->getId();

		// Added for Head7
		if(empty($tabId)){
			$dasbBoardModel = Head_DashBoard_Model::getInstance($moduleName);
			$defaultTab = $dasbBoardModel->getUserDefaultTab($userModel->getId());
			$tabId = $defaultTab['id'];
		}

		$date_var = date("Y-m-d H:i:s");
		$date = $adb->formatDate($date_var, true);

		$dataValue = array();
		$dataValue['contents'] = $noteBookContent;
		$dataValue['lastSavedOn'] = $date;

		$data = Zend_Json::encode((object) $dataValue);

		$query="INSERT INTO jo_module_dashboard_widgets(linkid, userid, filterid, title, data,dashboardtabid) VALUES(?,?,?,?,?,?)";
		$params= array($linkId,$userid,0,$noteBookName,$data,$tabId);
		$adb->pquery($query, $params);
		$id = $adb->getLastInsertID();

		$result = array();
		$result['success'] = TRUE;
		$result['widgetId'] = $id;
		$response = new Head_Response();
		$response->setResult($result);
		$response->emit();

	}

	public function validateRequest(Head_Request $request) {
		$request->validateWriteAccess();
	}
}
