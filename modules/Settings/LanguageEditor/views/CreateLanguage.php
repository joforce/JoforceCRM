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
class Settings_LanguageEditor_CreateLanguage_View extends Settings_Head_Index_View {

	public function process(Head_Request $request) {
		global $adb, $current_user;
                $moduleName = $request->getModule();
                $qualifiedModuleName = $request->getModule(false);
                $user_id = $current_user->id;

                $viewer = $this->getViewer($request);
                $userModuleModel = Users_Module_Model::getInstance('Users');

                $viewer->assign('LANGUAGES', $userModuleModel->getLanguagesList());
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
                $viewer->view('CreateLanguage.tpl', $qualifiedModuleName);
        }
}

?>
