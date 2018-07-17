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
class Settings_LanguageEditor_ListAjax_View extends Settings_Head_Index_View {

	public function process(Head_Request $request) {
		$module = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$selected_module = $request->get('moduleName');
		$selected_language = $request->get('languageFolder');
		if($selected_module == 'ModuleDesigner' || $selected_module == 'Webforms')
		{
			$file_name = "languages/$selected_language/Settings/".$selected_module.".php";
		}
		else
		{
			$file_name = "languages/$selected_language/".$selected_module.".php";
		}
		$viewer = $this->getViewer($request);

		$viewer->assign('MODULE', $module);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		if(file_exists($file_name))
                {
			require($file_name);
			$viewer->assign('LANGUAGE_STRING_ARRAY', $languageStrings);
			$viewer->assign('FILE_PATH', $file_name);
			if($jsLanguageStrings) {
				$viewer->assign('JS_LANGUAGE_STRING_ARRAY', $jsLanguageStrings);
			}
                }
		else
			$viewer->assign('NOTHING', true);

		$viewer->view('EditAjax.tpl', $qualifiedModuleName);
	}
}

?>
