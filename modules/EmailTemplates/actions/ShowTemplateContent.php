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

class EmailTemplates_ShowTemplateContent_Action extends Head_Action_Controller {

	function __construct() {
		$this->exposeMethod('getContent');
	}

	public function process(Head_Request $request) {
		$mode = $request->getMode();
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
		} else {
			throw new Exception("Invalid Mode");
		}
	}

	public function checkPermission(Head_Request $request) {
		$record = $request->get('record');
		$moduleName = $request->getModule();

		if (!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function getContent(Head_Request $request) {
		$response = new Head_Response();
		$recordId = $request->get('record');
		$recordModel = EmailTemplates_Record_Model::getInstanceById($recordId);
		$response->setResult(array("content" => decode_html($recordModel->get('body'))));
		$response->emit();
	}

}
