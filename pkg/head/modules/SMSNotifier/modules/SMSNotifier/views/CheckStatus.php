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

class SMSNotifier_CheckStatus_View extends Head_IndexAjax_View {

	function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();

		if(!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'))) {
			throw new AppException(vtranslate($moduleName, $moduleName).' '.vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$notifierRecordModel = Head_Record_Model::getInstanceById($request->get('record'), $moduleName);
		$notifierRecordModel->checkStatus();

		$response = new Head_Response();
		$response->setResult(array(	'to'		=> $notifierRecordModel->get('tonumber'), 
									'status'	=> $notifierRecordModel->get('status'),
									'message'	=> $notifierRecordModel->get('statusmessage')
							));
		$response->emit();
	}
}