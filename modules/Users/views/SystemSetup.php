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

class Users_SystemSetup_View extends Head_Index_View {
	
    public function preProcess(Head_Request $request, $display=true) {
	return true;
    }
	
    public function process(Head_Request $request) {
	global $site_URL;
	$moduleName = $request->getModule();
	$viewer = $this->getViewer($request);
	$userModel = Users_Record_Model::getCurrentUserModel();
	$isFirstUser = Users_CRMSetup::isFirstUser($userModel);

	if($request->get('mode') == 'showmodules') {
	    $this->showModules($request);
	} else {
	    if($isFirstUser) {
            	$viewer->assign('SITEURL', $site_URL);
		$viewer->assign('IS_FIRST_USER', $isFirstUser);
		$viewer->assign('PACKAGES_LIST', Users_CRMSetup::getPackagesList());
		$viewer->view('SystemSetup.tpl', $moduleName);
	    } else {
		header ('Location: index.php');
		exit();
	    }
	}
    }
	
    function postProcess(Head_Request $request) {
	return true;
    }

    public function showModules($request) {
	$moduleName = $request->getModule();
	$packages_string = $request->get(packages);
	$packages_array = explode(',',$packages_string);
	$packagesListFromDB = Users_CRMSetup::getPackagesList();

	$enabledModulesList = array();

	if(!empty($packages_array)) {
            foreach ($packagesListFromDB as $packageName => $packageInfo) {
                if (in_array($packageName, $packages_array)) {
                    $enabledModulesList[$packageName] =  array_values($packageInfo['modules']);
                }
            }
	}
	$additional_modules = $packagesListFromDB['Tools'];
	$enabledModulesList['Sales & Marketting'] = array_values($additional_modules['modules']);
	$enabledModulesList['Sales & Marketting'] = array_merge($enabledModulesList['Sales & Marketting'], array('Calendar', 'EmailTemplates', 'RecycleBin', 'Documents'));

	$html = '<center><b>Enabled Modules</b></center>';
	foreach($enabledModulesList as $package_name => $enabledModules) {
	    $html .= '<h2>' . $package_name . '</h2>';
	    $html .= '<table style="width:100%;border-collapse: collapse;border: 1px solid black;margin-top:30px;margin-bottom:30px;"><tbody>';
	    foreach($enabledModules as $key => $moduleName) {
		if ($key % 2 == 0) {
		    $html .= '<tr><td style="border: 1px solid black;">'. $moduleName .'</td>';
		} else {
		    $html .= '<td style="border: 1px solid black;">'. $moduleName .'</td></tr>';
		}
	    }
	    $html .= '</tbody></table>';
	}
	$result = array('success' => true, 'html' => $html);
	echo json_encode($result);
    }	
}
