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

class Settings_Webhooks_Detail_View extends Settings_Head_Index_View {

	public function checkPermission(Head_Request $request) {
		return true;
		parent::checkPermission($request);

		$recordId = $request->get('record');
		$moduleModel = Head_Module_Model::getInstance($request->getModule());

		$currentUserPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$recordId || !$currentUserPrivilegesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);

		$recordModel = Settings_Webhooks_Record_Model::getInstanceById($recordId, $qualifiedModuleName);

		$recordStructure = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_DETAIL);
		$moduleModel = $recordModel->getModule();

		$navigationInfo = ListViewSession::getListViewNavigation($recordId);

		$viewer = $this->getViewer($request);
		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('MODULE_NAME', $qualifiedModuleName);
		$viewer->assign('RECORD_STRUCTURE', $recordStructure->getStructure());
		$viewer->assign('MODULE_MODEL', $moduleModel);

		$viewer->assign('BLOCK_LIST', $moduleModel->getBlocks());
		$viewer->assign('SOURCE_MODULE', $recordModel->get('targetmodule'));
		$viewer->assign('DETAILVIEW_LINKS', $recordModel->getDetailViewLinks());
		$viewer->assign('NO_PAGINATION',true);

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$userCurrencyInfo = getCurrencySymbolandCRate($currentUserModel->get('currency_id'));
		$viewer->assign('USER_CURRENCY_SYMBOL', $userCurrencyInfo['symbol']);

		$viewer->view('DetailView.tpl', $qualifiedModuleName);
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"modules.Settings.Head.resources.Detail",
			"modules.Settings.$moduleName.resources.Detail"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

}
