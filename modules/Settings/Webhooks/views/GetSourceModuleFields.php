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

class Settings_Webhooks_GetSourceModuleFields_View extends Settings_Head_IndexAjax_View {

	public function checkPermission(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
		$sourceModule = $request->get('sourceModule');
		$viewer = $this->getViewer($request);
		$mode = '';
		$selectedFieldsList = array();

		if ($recordId) {
			$recordModel = Settings_Webhooks_Record_Model::getInstanceById($recordId, $qualifiedModuleName);
			$mode = 'edit';
			if ($sourceModule === $recordModel->get('targetmodule')) {
				$selectedFieldsList = $recordModel->getSelectedFieldsList();
			}
		} else {
			$recordModel = Settings_Webhooks_Record_Model::getCleanInstance($qualifiedModuleName);
		}
		$fields = $recordModel->getAllFieldsList($sourceModule);
		$html = '';
		foreach($fields as $key => $value){
			$html .= '<option value="'.$key.'">'.$value.'</option>';
		}
		$response = new Head_Response();
		$response->setResult($html);
		return $response;		
	}
}
