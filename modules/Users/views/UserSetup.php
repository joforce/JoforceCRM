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

class Users_UserSetup_View extends Head_Index_View {

	public function preProcess(Head_Request $request) {
		return true;
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$userName = $request->get('user_name');
		$viewer = $this->getViewer($request);
		$userModel = Users_Record_Model::getCurrentUserModel();
		$userModuleModel = Users_Module_Model::getInstance($moduleName);
		$userSetupStatus = $userModel->isFirstTimeLogin($userModel->id);
		if($userSetupStatus) {
			$isFirstUser = Users_CRMSetup::isFirstUser($userModel);
			if($isFirstUser) {
				$defaultCurrencyKey = 'USA, Dollars';
				$currencies = $userModuleModel->getCurrenciesList();
				$defaultCurrencyValue = $currencies[$defaultCurrencyKey];
				unset($currencies[$defaultCurrencyKey]);
				$defaultcurrency[$defaultCurrencyKey] = $defaultCurrencyValue;
				$currenciesList = array_merge($defaultcurrency, $currencies);
				$viewer->assign('IS_FIRST_USER', $isFirstUser);
				$viewer->assign('CURRENCIES', $currenciesList);
			}

			$viewer->assign('CURRENT_USER_MODEL',$userModel);
			$viewer->assign('MODULE', $moduleName);
			$viewer->assign('USER_NAME', $userName);
			$viewer->assign('TIME_ZONES', $userModuleModel->getTimeZonesList());
			$viewer->assign('LANGUAGES', $userModuleModel->getLanguagesList());
			$viewer->assign('USER_ID', $request->get('record'));
			if($isFirstUser)    {
			    // For Outgoing server
                $systemDetailsModel = Settings_Head_Systems_Model::getInstanceFromServerType('email', 'OutgoingServer');
                $viewer->assign('OUTGOING_SERVER_MODEL',$systemDetailsModel);

			    // For Company Details
                $moduleModel = Settings_Head_CompanyDetails_Model::getInstance();
                $viewer->assign('COMPANY_DETAILS_MODULE_MODEL', $moduleModel);
                $viewer->assign('QUALIFIED_MODULE', 'Settings:Head');
			    $viewer->view('AdminUserSetup.tpl', $moduleName);
            }
            else {
                $viewer->view('UserSetup.tpl', $moduleName);
            }
		} else {
			if(isset($_SESSION['return_params'])) {
				$return_params = urldecode($_SESSION['return_params']);
				header("Location: index.php?$return_params");
				exit();
			} else {
				header("Location: index.php");
				exit();
			}
		}
	}

	function postProcess(Head_Request $request) {
		return true;
	}

}
