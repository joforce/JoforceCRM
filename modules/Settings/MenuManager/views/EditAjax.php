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

class Settings_MenuManager_EditAjax_View extends Settings_Head_Index_View {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('showAddModule');
	}
	
	public function checkPermission(Head_Request $request) {
                return true;
        }

	public function process(Head_Request $request) {
		$mode = $request->getMode();
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	function showAddModule(Head_Request $request) {	
		global $current_user;
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$appName = $request->get('appname');
		$user_id = $current_user->id;
		$file_name = "storage/menu/sections_".$user_id.".php";
                if(file_exists($file_name)) {
                    require($file_name);
                } else {
                    require("storage/menu/default_sections.php");
		}

		$viewer->assign('APP_ARRAY', array_keys($section_array));
		$viewer->assign('SELECTED_APP_NAME', $appName);
		$viewer->assign('MODULE', $request->getModule());
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
		$viewer->view('AddModule.tpl', $qualifiedModuleName);
	}
}
