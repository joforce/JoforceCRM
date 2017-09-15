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

class Settings_ModuleManager_ModuleImport_View extends Settings_Head_Index_View {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('step2');
		$this->exposeMethod('step3');
		$this->exposeMethod('importUserModuleStep1');
		$this->exposeMethod('importUserModuleStep2');
	}

	public function process(Head_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}

		$EXTENSIONS = Settings_ModuleManager_Extension_Model::getAll();
		$qualifiedModuleName = $request->getModule(false);
		$viewer = $this->getViewer($request);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('EXTENSIONS', $EXTENSIONS);
		$viewer->assign('EXTENSIONS_AVAILABLE', (count($EXTENSIONS) > 0)? true :false);
		$viewer->view('Step1.tpl', $qualifiedModuleName);
	}

	public function step2(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$upgradeError = true;
		$qualifiedModuleName = $request->getModule(false);
		$extensionId = $request->get('extensionId');
		$moduleAction = $request->get('moduleAction');	//Import/Upgrade

		$extensionModel = Settings_ModuleManager_Extension_Model::getInstanceById($extensionId);
		if ($extensionModel) {
			$package = $extensionModel->getPackage();
			if ($package) {
				$importedModuleName = $package->getModuleName();
				$isLanguagePackage = $package->isLanguageType();

				if ($moduleAction === 'Upgrade') {
					$targetModuleName = $request->get('extensionName');
					if(($isLanguagePackage && (trim($package->xpath_value('prefix')) == $targetModuleName)) || (!$isLanguagePackage && $importedModuleName === $targetModuleName)) {
						$upgradeError = false;
					}
				} else {
					$upgradeError = false;
				}
				if (!$upgradeError) {
					if(!$isLanguagePackage) {
						$moduleModel = Head_Module_Model::getInstance($importedModuleName);
						$viewer->assign('MODULE_EXISTS', ($moduleModel)? true :false);
						$viewer->assign('MODULE_DIR_NAME', '../modules/'. $importedModuleName);

						if (!$extensionModel->isUpgradable()) {
							$viewer->assign('SAME_VERSION', true);
						}
					}

					$viewer->assign('EXTENSION_ID', $extensionId);
					$viewer->assign('MODULE_NAME', $importedModuleName);
					$viewer->assign('MODULE_ACTION', $moduleAction);

					$viewer->assign('MODULE_TYPE', $package->type());
					$viewer->assign('FILE_NAME', $extensionModel->getFileName());
					$viewer->assign('MODULE_LICENSE', (string)$package->getLicense());
					$viewer->assign('SUPPORTED_VTVERSION', $package->getDependentHeadVersion());

				} else {
					$viewer->assign('ERROR', true);
					$viewer->assign('ERROR_MESSAGE', vtranslate('LBL_INVALID_FILE', $qualifiedModuleName));
				}
			} else {
				$viewer->assign('ERROR', true);
				$viewer->assign('ERROR_MESSAGE', vtranslate('LBL_INVALID_FILE', $qualifiedModuleName));
			}
		} else {
			$viewer->assign('ERROR', true);
			$viewer->assign('ERROR_MESSAGE', vtranslate('LBL_INVALID_FILE', $qualifiedModuleName));
		}

		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('Step2.tpl', $qualifiedModuleName);
	}

	public function step3(Head_Request $request) {
		$viewer = $this->getViewer($request);
		global $Head_Utils_Log;
		$viewer->assign('VTIGER_UTILS_LOG', $Head_Utils_Log);
		$Head_Utils_Log = true;
		$qualifiedModuleName = $request->getModule(false);

		$fileName = $request->get('fileName');
		$moduleType = $request->get('moduleType');
		$extensionId = $request->get('extensionId');
		$targetModuleName = $request->get('targetModule');
		$moduleAction = $request->get('moduleAction');

		if ($extensionId) {
			if ($moduleAction !== 'Upgrade') {
				$extensionModel = Settings_ModuleManager_Extension_Model::getInstanceById($extensionId, $fileName);
				$extensionModel->installTrackDetails();
			}

			if (strtolower($moduleType) === 'language') {
				$package = new Head_Language();
			} else {
				$package = new Head_Package();
			}

			$viewer->assign('MODULE_ACTION', $moduleAction);
			$viewer->assign('MODULE_PACKAGE', $package);
			$viewer->assign('TARGET_MODULE_INSTANCE', Head_Module_Model::getInstance($targetModuleName));
			$viewer->assign('MODULE_FILE_NAME', Settings_ModuleManager_Extension_Model::getUploadDirectory(). '/' .$fileName);
		} else {
			$viewer->assign('ERROR', true);
			$viewer->assign('ERROR_MESSAGE', vtranslate('LBL_INVALID_MODULE_INFO', $qualifiedModuleName));
		}
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('Step3.tpl', $qualifiedModuleName);
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
			"modules.Settings.$moduleName.resources.ModuleImport"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function importUserModuleStep1(Head_Request $request){
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('ImportUserModuleStep1.tpl', $qualifiedModuleName);
	}

	public function importUserModuleStep2(Head_Request $request){
		$viewer = $this->getViewer($request);
		$uploadDir = Settings_ModuleManager_Extension_Model::getUploadDirectory();
		$qualifiedModuleName = $request->getModule(false);

		$uploadFile = 'usermodule_'.time().'.zip';
		$uploadFileName = "$uploadDir/$uploadFile";
		checkFileAccess($uploadDir);
		if(!move_uploaded_file($_FILES['moduleZip']['tmp_name'], $uploadFileName)) {
			$viewer->assign('MODULEIMPORT_FAILED', true);
		}else{
			$package = new Head_Package();
			$importModuleName = $package->getModuleNameFromZip($uploadFileName);
			$importModuleDepVtVersion = $package->getDependentHeadVersion();

			if($importModuleName == null ) {
				$viewer->assign('MODULEIMPORT_FAILED', true);
				$viewer->assign("MODULEIMPORT_FILE_INVALID", true);
				checkFileAccessForDeletion($uploadFileName);
				unlink($uploadFileName);
			} else {
				// We need these information to push for Update if module is detected to be present.
				$moduleLicence = vtlib_purify($package->getLicense());

				$viewer->assign("MODULEIMPORT_FILE", $uploadFile);
				$viewer->assign("MODULEIMPORT_TYPE", $package->type());
				$viewer->assign("MODULEIMPORT_NAME", $importModuleName);
				$viewer->assign("MODULEIMPORT_DEP_VTVERSION", $importModuleDepVtVersion);
				$viewer->assign("MODULEIMPORT_LICENSE", $moduleLicence);

				if(!$package->isLanguageType() && !$package->isModuleBundle()) {
					$moduleInstance = Head_Module::getInstance($importModuleName);
					$moduleimport_exists = ($moduleInstance)? "true" : "false";
					$moduleimport_dir_name = "modules/$importModuleName";
					$moduleimport_dir_exists = (is_dir($moduleimport_dir_name)? "true" : "false");
					$viewer->assign("MODULEIMPORT_EXISTS", $moduleimport_exists);
					$viewer->assign("MODULEIMPORT_DIR", $moduleimport_dir_name);
					$viewer->assign("MODULEIMPORT_DIR_EXISTS", $moduleimport_dir_exists);
				}
			}
		}
		$viewer->view('ImportUserModuleStep2.tpl', $qualifiedModuleName);
	}

	public function validateRequest(Head_Request $request) {
		$request->validateReadAccess();
	}
}
