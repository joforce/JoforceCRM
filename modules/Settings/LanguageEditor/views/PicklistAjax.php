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
class Settings_LanguageEditor_PicklistAjax_View extends Settings_Head_Index_View {

    public function process(Head_Request $request) {
	global $current_user;
	$admin_language = $current_user->language;

	$module = $request->getModule();
	$qualifiedModuleName = $request->getModule(false);
	$selected_module = $request->get('selectedModule');
	$selected_language = $request->get('language');
	$selected_picklist = $request->get('selected_field');

	$picklist_values = Head_Util_Helper::getPickListValues($selected_picklist);

	$language_path = "languages/$selected_language/$selected_module.php";
	$default_path = "languages/en_us/$selected_module.php";

	$selected_language_picklist_values = array();
	if(!file_exists($language_path)) {
            $language_path = $default_path;
        }  
	require_once ($language_path);
	foreach($picklist_values as $picklist_value_id => $picklist_value_label) {
	    $selected_language_picklist_values[$picklist_value_label] = $languageStrings[$picklist_value_label];
	}

	$default_language_picklist_values = array();
        require_once ($default_path);
        foreach($picklist_values as $picklist_value_id => $picklist_value_label) {
            $default_language_picklist_values[$picklist_value_label] = $languageStrings[$picklist_value_label];
        }

	$viewer = $this->getViewer($request);

        $language_array = array();
        foreach($picklist_values as $picklist_value_id => $picklist_value_label) {
	    $sel_value = $selected_language_picklist_values[$picklist_value_label];
	    $def_value = $default_language_picklist_values[$picklist_value_label];

	    if(empty($sel_value)) {
		if(!empty($def_value)) {
		    $language_array[$picklist_value_label] = $def_value;
		} else {
		    $language_array[$picklist_value_label] = $picklist_value_label;
		}
	    } else {
		$language_array[$picklist_value_label] = $sel_value;
	    } 
	}
	$viewer->assign('LANGUAGE_STRING_ARRAY', $language_array);
	$viewer->assign('HINT', 'lbl');
	$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
	$viewer->view('PicklistEditAjax.tpl', $qualifiedModuleName);
    }
}
