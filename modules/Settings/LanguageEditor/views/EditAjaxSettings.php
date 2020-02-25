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
class Settings_LanguageEditor_EditAjaxSettings_View extends Settings_Head_Index_View {

    public function process(Head_Request $request) {
	global $current_user;
	$admin_language = $current_user->language;

	$module = $request->getModule();
	$qualifiedModuleName = $request->getModule(false);
	$selected_module = $request->get('moduleName');
	$selected_language = $admin_language;

	$folder_path = "languages/$admin_language/Settings/";
	$files = array_diff(scandir($folder_path), array('.', '..'));
	$default_path = "languages/en_us/Settings/";
	$default_files = array_diff(scandir($default_path), array('.', '..'));

	$viewer = $this->getViewer($request);
	$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

        $language_array = array();
	$html = '<div class="language-editor-div">';
        foreach($default_files as $default_file) {
	    if(strpos($default_file, '.swp')) {
		continue;
	    }
            if(in_array($default_file, $files)) {
	        $file_path = $folder_path . $default_file;
	        $default_file_path = $default_path . $default_file;
	        require_once($file_path);
            } else {
		$file_path = $default_file_path = $default_path . $default_file;
	        require_once($file_path);
	    }

	    if(file_exists($file_path)) {
		$viewer->assign('LANGUAGE_STRING_ARRAY', $languageStrings);
		$viewer->assign('FILE', $default_file_path);
		$viewer->assign('SAVE_FILE', $file_path);
                $viewer->assign('JS_LANGUAGE_STRING_ARRAY', $jsLanguageStrings);
		$viewer->assign('HEADING', str_replace(".php", "", $default_file));
	    } else {
                $viewer->assign('NOTHING', true);
	    }
	    $html .= $viewer->view('EditAjaxSettings.tpl', $qualifiedModuleName);
        }
	$html .= "</div>";
    }
}
