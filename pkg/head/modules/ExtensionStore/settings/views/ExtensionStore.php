<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/

include_once dirname(__FILE__).'/../libraries/LoaderSuggest.php';

class Settings_ExtensionStore_ExtensionStore_View extends Settings_Head_Index_View {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('searchExtension');
		$this->exposeMethod('detail');
		$this->exposeMethod('installationLog');
		$this->exposeMethod('oneClickInstall');
	}

	protected function getModelInstance() {
		if (!isset($this->modelInstance)) {
			$this->modelInstance = Settings_ExtensionStore_Extension_Model::getInstance();
		}
		return $this->modelInstance;
	}

	public function process(Head_Request $request) {
		$mode = $request->getMode();
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}

		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$modelInstance = $this->getModelInstance();
		$registrationStatus = $modelInstance->checkRegistration();

		if ($registrationStatus) {
			$userName = $modelInstance->getRegisteredUser();
			//check if remember password is enabled
			$pwdStatus = $modelInstance->passwordStatus();
			//check if password set in current session
			if (!$pwdStatus) {
				$sessionIdentifer = $modelInstance->getSessionIdentifier();
				$pwd = $_SESSION[$sessionIdentifer.'_password'];
				if (!empty($pwd)) {
					$pwdStatus = true;
				}
			}
			$viewer->assign('USER_NAME', $userName);
		}
		if ($registrationStatus && $pwdStatus) {
			$customerProfile = $modelInstance->getProfile();
			$customerCardId = $customerProfile['CustomerCardId'];
			if (!empty($customerCardId)) {
				$customerCardDetails = $modelInstance->getCardDetails($customerCardId);
				$viewer->assign('CUSTOMER_CARD_INFO', $customerCardDetails);
			}
			$viewer->assign('CUSTOMER_PROFILE', $customerProfile);
		}

		$loaderRequired = false;
		$loaderInstance = new Settings_ModuleManager_LoaderSuggest();
		$loaderInfo = $loaderRequired ? $loaderInstance->jo_extensionloader_suggest() : null;

		$viewer->assign('LOADER_REQUIRED', $loaderRequired);
		$viewer->assign('LOADER_INFO', $loaderInfo);
		$viewer->assign('PASSWORD_STATUS', $pwdStatus);
		$viewer->assign('IS_PRO', true);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('EXTENSIONS_LIST', $modelInstance->getListings());
		$viewer->assign('REGISTRATION_STATUS', $registrationStatus);
		$viewer->view('Index.tpl', $qualifiedModuleName);
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
			"libraries.jquery.jqueryRating",
			"libraries.jquery.boxslider.jqueryBxslider",
			"~modules/Settings/ExtensionStore/libraries/jasny-bootstrap.min.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	protected function searchExtension(Head_Request $request) {
		$searchTerm = $request->get('searchTerm');
		$searchType = $request->get('type');
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$modelInstance = $this->getModelInstance();
		$registrationStatus = $modelInstance->checkRegistration();

		if ($registrationStatus) {
			$pwdStatus = $modelInstance->passwordStatus();
			if (!$pwdStatus) {
				$sessionIdentifer = $modelInstance->getSessionIdentifier();
				$pwd = $_SESSION[$sessionIdentifer.'_password'];
				if (!empty($pwd)) {
					$pwdStatus = true;
				}
			}
		}

		$viewer->assign('PASSWORD_STATUS', $pwdStatus);
		$viewer->assign('IS_PRO', true);
		$viewer->assign('REGISTRATION_STATUS', $registrationStatus);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->assign('EXTENSIONS_LIST', $modelInstance->findListings($searchTerm, $searchType));
		$viewer->view('ExtensionModules.tpl', $qualifiedModuleName);
	}

	protected function detail(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$extensionId = $request->get('extensionId');
		$moduleAction = $request->get('moduleAction');
		$modelInstance = $this->getModelInstance();

		$extensionDetail = $modelInstance->getExtensionListings($extensionId);
		$extension = $extensionDetail[$extensionId];

		if ($extension && $extension->isHeadCompatible()) {
			$customerReviews = $modelInstance->getCustomerReviews($extensionId);
			$screenShots = $modelInstance->getScreenShots($extensionId);
			$authorInfo = $modelInstance->getListingAuthor($extensionId);
			$registrationStatus = $modelInstance->checkRegistration();

			if ($registrationStatus) {
				$pwdStatus = $modelInstance->passwordStatus();
				if (!$pwdStatus) {
					$sessionIdentifer = $modelInstance->getSessionIdentifier();
					$pwd = $_SESSION[$sessionIdentifer.'_password'];
					if (!empty($pwd)) {
						$pwdStatus = true;
					}
				}
				$viewer->assign('PASSWORD_STATUS', $pwdStatus);
			}

			if ($registrationStatus && $pwdStatus) {
				$customerProfile = $modelInstance->getProfile();
				$customerCardId = $customerProfile['CustomerCardId'];
				if (!empty($customerCardId)) {
					$customerCardDetails = $modelInstance->getCardDetails($customerCardId);
					$viewer->assign('CUSTOMER_CARD_INFO', $customerCardDetails);
				}
				$viewer->assign('CUSTOMER_PROFILE', $customerProfile);
			}

			$viewer->assign('IS_PRO', true);
			$viewer->assign('MODULE_ACTION', $moduleAction);
			$viewer->assign('SCREEN_SHOTS', $screenShots);
			$viewer->assign('AUTHOR_INFO', $authorInfo);
			$viewer->assign('CUSTOMER_REVIEWS', $customerReviews);
			$viewer->assign('EXTENSION_DETAIL', $extension);
			$viewer->assign('EXTENSION_MODULE_MODEL', $extension->get('moduleModel'));
			$viewer->assign('EXTENSION_ID', $extensionId);
			$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
			$viewer->assign('REGISTRATION_STATUS', $registrationStatus);
			$viewer->view('Detail.tpl', $qualifiedModuleName);
		} else {
			$viewer->assign('EXTENSION_LABEL', $extension->get('label'));
			$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
			$viewer->view('ExtensionCompatibleError.tpl', $qualifiedModuleName);
		}
	}

	protected function installationLog(Head_Request $request) {
		$viewer = $this->getViewer($request);
		global $Head_Utils_Log;
		$viewer->assign('VTIGER_UTILS_LOG', $Head_Utils_Log);
		$Head_Utils_Log = true;
		$qualifiedModuleName = $request->getModule(false);

		$extensionId = $request->get('extensionId');
		$targetModuleName = $request->get('targetModule');
		$moduleAction = $request->get('moduleAction');
		$modelInstance = $this->getModelInstance();

		$response = $modelInstance->getInstanceById($extensionId);
		if ($response['success']) {
			$extensionModel = $response['result'];
			$package = $extensionModel->getPackage();
			if ($package) {
				$importedModuleName = $package->getModuleName();
				$isLanguagePackage = $package->isLanguageType();

				if ($moduleAction === 'Upgrade') {
					if (($isLanguagePackage && (trim($package->xpath_value('prefix')) == $targetModuleName)) || (!$isLanguagePackage && $importedModuleName === $targetModuleName)) {
						$upgradeError = false;
					}
				} else {
					$upgradeError = false;
				}
				if (!$upgradeError) {
					if (!$isLanguagePackage) {
						$moduleModel = Head_Module_Model::getInstance($importedModuleName);
						$viewer->assign('MODULE_EXISTS', ($moduleModel) ? true : false);
						$viewer->assign('MODULE_DIR_NAME', '../modules/'.$importedModuleName);

						if (!$extensionModel->isUpgradable()) {
							$viewer->assign('SAME_VERSION', true);
						}
					}
					$moduleType = $package->type();
					$fileName = $extensionModel->getFileName();
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
			$viewer->assign('ERROR_MESSAGE', $response['message']);
		}

		if ($extensionId && $extensionModel) {
			if ($moduleAction !== 'Upgrade') {
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
			$viewer->assign('MODULE_FILE_NAME', Settings_ExtensionStore_Extension_Model::getUploadDirectory().'/'.$fileName);
		}
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('InstallationLog.tpl', $qualifiedModuleName);
	}

	protected function oneClickInstall(Head_Request $request) {
		$viewer = $this->getViewer($request);
		global $Head_Utils_Log;
		$viewer->assign('VTIGER_UTILS_LOG', $Head_Utils_Log);
		$Head_Utils_Log = true;
		$upgradeError = true;
		$qualifiedModuleName = $request->getModule(false);
		$extensionId = $request->get('extensionId');
		$moduleAction = $request->get('moduleAction'); //Import/Upgrade
		$trial = $request->get('trial');
		$modelInstance = $this->getModelInstance();
		$response = $modelInstance->getInstanceById($extensionId, $trial);

		if ($response['success']) {
			$extensionModel = $response['result'];
			$package = $extensionModel->getPackage();
			if ($package) {
				$importedModuleName = $package->getModuleName();
				$isLanguagePackage = $package->isLanguageType();

				if ($moduleAction === 'Upgrade') {
					$targetModuleName = $request->get('extensionName');
					if (($isLanguagePackage && (trim($package->xpath_value('prefix')) == $targetModuleName)) || (!$isLanguagePackage && $importedModuleName === $targetModuleName)) {
						$upgradeError = false;
					}
				} else {
					$upgradeError = false;
				}
				if (!$upgradeError) {
					if (!$isLanguagePackage) {
						$moduleModel = Head_Module_Model::getInstance($importedModuleName);

						if (!$extensionModel->isUpgradable()) {
							$viewer->assign('SAME_VERSION', true);
						}
					}

					$moduleType = $packageType = $package->type();
					$fileName = $extensionModel->getFileName();
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
			$viewer->assign('ERROR_MESSAGE', $response['message']);
		}

		if ($extensionId && $extensionModel) {
			if ($moduleAction !== 'Upgrade') {
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
			$viewer->assign('MODULE_FILE_NAME', Settings_ExtensionStore_Extension_Model::getUploadDirectory().'/'.$fileName);
		}

		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('InstallationLog.tpl', $qualifiedModuleName);
	}

}
