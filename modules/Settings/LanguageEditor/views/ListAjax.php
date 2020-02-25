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
		global $current_user,$adb;
		$admin_language = $current_user->language;

		$module = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$selected_module = $request->get('moduleName');
		$selected_language = $request->get('languageFolder');
		if($selected_module == 'ModuleDesigner' || $selected_module == 'Webforms') {
			$file_name = "languages/$selected_language/Settings/".$selected_module.".php";
		} else {
			$file_name = "languages/$selected_language/".$selected_module.".php";
		}
		$file_path = $file_name;

		if(!file_exists($file_name)) {
		    if(strpos($file_name, 'Settings')) {
			$file_name = "languages/en_us/Settings/".$selected_module.".php";
		    } else {
			$file_name = "languages/en_us/".$selected_module.".php";
		    }
		}
		$viewer = $this->getViewer($request);

		$viewer->assign('MODULE', $module);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		if(file_exists($file_name))
                {
			require($file_name);
			$viewer->assign('LANGUAGE_STRING_ARRAY', $languageStrings);
			$viewer->assign('FILE', $file_name);
			$viewer->assign('SAVE_FILE', $file_path);
			if($jsLanguageStrings) {
				$viewer->assign('JS_LANGUAGE_STRING_ARRAY', $jsLanguageStrings);
			}
                }
		else
			$viewer->assign('NOTHING', true);


		//picklist values
		if($selected_module !== 'Settings') {
			//Picklist Strings
			require_once ('modules/Settings/PickListDependency/models/Record.php');
			$pd_obj = new Settings_PickListDependency_Record_Model(array('sourceModule' => $selected_module));
			$picklist_fields = $pd_obj->getAllPickListFields();

		        $viewer->assign('SELECTED_MODULE_NAME', $selected_module);
			$viewer->assign('MODULE_PICKLIST_FIELDS', $picklist_fields);

			//Custom field strings
			$focus = CRMEntity::getInstance($selected_module);
			$cf_table = $focus->customFieldTable;
			if (isset($cf_table)) {
		            $custom_tableName = $cf_table[0];
		        } else {
		            $custom_tableName= 'jo_'.strtolower($selected_module).'cf';
		        }

			$cffield_ln_strings= array();
			$cf_table_exists = Head_Utils::CheckTable($custom_tableName);
			if($cf_table_exists) {
			    $query_result = $adb->pquery("select fieldlabel from jo_field where tablename = ?", array($custom_tableName));
			    $row_count = $adb->getRowCount($query_result);
			    if($row_count > 0) {
				while($cf_result = $adb->fetch_array($query_result)) {
				    array_push($cffield_ln_strings, $cf_result['fieldlabel']);
				}
			    }
			}

			if(!empty($cffield_ln_strings)) {
			    $language_path = "languages/$selected_language/$selected_module.php";
			    $default_path = "languages/en_us/$selected_module.php";

		            $language_cf_field_values = array();
			    if(!file_exists($language_path)) {
				$language_path = $default_path;
			    }			
        		    require_once ($language_path);
			    foreach($cffield_ln_strings as $cf_label) {
		                $language_cf_field_values[$cf_label] = $languageStrings[$cf_label];
			    }

		            $default_cf_field_values = array();
		            require_once ($default_path);
		            foreach($cffield_ln_strings as $cf_label) {
		                $default_cf_field_values[$cf_label] = $languageStrings[$cf_label];
			    }
			}

			$language_array = array();
		        foreach($cffield_ln_strings as $cf_label) {
		            $sel_value = $language_cf_field_values[$cf_label];
		            $def_value = $default_cf_field_values[$cf_label];

		            if(!empty($sel_value)) {
		                $language_array[$cf_label] = $sel_value;
		            } elseif (!empty($def_value)) {
		                $language_array[$cf_label] = $def_value;
		            } else {
		                $language_array[$cf_label] = $cf_label;
		            }
		        }

		        $viewer->assign('CF_LANGUAGE_STRING_ARRAY', $language_array);
		}
		$viewer->view('EditAjax.tpl', $qualifiedModuleName);
	}
}
