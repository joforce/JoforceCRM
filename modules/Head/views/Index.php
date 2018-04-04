<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Head_Index_View extends Head_Basic_View {

	function __construct() {
		parent::__construct();
	}

	function checkPermission(Head_Request $request) {
		//Return true as WebUI.php is already checking for module permission
		return true;
	}

	public function preProcess (Head_Request $request, $display=true) {
		parent::preProcess($request, false);

		$viewer = $this->getViewer($request);

		$moduleName = $request->getModule();
        $currentUser = Users_Record_Model::getCurrentUserModel();
		if(!empty($moduleName)) {
			$moduleModel = Head_Module_Model::getInstance($moduleName);
			$userPrivilegesModel = Users_Privileges_Model::getInstanceById($currentUser->getId());
			$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
			$viewer->assign('MODULE', $moduleName);

			if(!$permission) {
				$viewer->assign('MESSAGE', vtranslate('LBL_PERMISSION_DENIED'));
				$viewer->view('OperationNotPermitted.tpl', $moduleName);
				exit;
			}

			$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
			$linkModels = $moduleModel->getSideBarLinks($linkParams);

			$viewer->assign('QUICK_LINKS', $linkModels);
			$this->setModuleInfo($request,$moduleModel);
		}

		$viewer->assign('CURRENT_USER_MODEL', $currentUser);
		$viewer->assign('REQUEST_INSTANCE', $request);
		$viewer->assign('CURRENT_VIEW', $request->get('view'));
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	/**
	 * Setting module related Information to $viewer
	 * @param type $request
	 * @param type $moduleModel
	 */
	public function setModuleInfo($request, $moduleModel) {
		$fieldsInfo = array();
		$basicLinks = array();
		$settingLinks = array();

		$moduleFields = $moduleModel->getFields();
		foreach ($moduleFields as $fieldName => $fieldModel) {
			$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
		}

		$moduleBasicLinks = $moduleModel->getModuleBasicLinks();
		if($moduleBasicLinks) {
			foreach ($moduleBasicLinks as $basicLink) {
				$basicLinks[] = Head_Link_Model::getInstanceFromValues($basicLink);
			}
		}

		$moduleSettingLinks = $moduleModel->getSettingLinks();
		if($moduleSettingLinks) {
			foreach ($moduleSettingLinks as $settingsLink) {
				$settingLinks[] = Head_Link_Model::getInstanceFromValues($settingsLink);
			}
		}
		$viewer = $this->getViewer($request);
		$viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));
		$viewer->assign('MODULE_BASIC_ACTIONS', $basicLinks);
		$viewer->assign('MODULE_SETTING_ACTIONS', $settingLinks);
	}

	protected function preProcessTplName(Head_Request $request) {
		return 'IndexViewPreProcess.tpl';
	}

	//Note : To get the right hook for immediate parent in PHP,
	// specially in case of deep hierarchy
	/*function preProcessParentTplName(Head_Request $request) {
		return parent::preProcessTplName($request);
	}*/

	public function postProcess(Head_Request $request) {
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$viewer->view('IndexPostProcess.tpl', $moduleName);

		parent::postProcess($request);
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$viewer->view('Index.tpl', $moduleName);
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
			'modules.Head.resources.Head',
			"modules.$moduleName.resources.$moduleName",
			"~libraries/jquery/jquery.stickytableheaders.min.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function validateRequest(Head_Request $request) {
		$request->validateReadAccess();
	}
}
