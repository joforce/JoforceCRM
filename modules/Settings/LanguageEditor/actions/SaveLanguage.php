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
class Settings_LanguageEditor_SaveLanguage_Action extends Settings_Head_Index_Action {

        public function process(Head_Request $request) {
//echo "<pre>"; print_r($request);die;
		global $adb;
                $module = $request->getModule();
                $qualifiedModuleName = $request->getModule(false);
                $new_language_name = $request->get('language_name');
                $new_language_code = $request->get('language_code');
                $copy_language_from = $request->get('language_to_copy');

		$userModuleModel = Users_Module_Model::getInstance('Users');
		$existing_languages = $userModuleModel->getLanguagesList();

		$response = new Head_Response();

		$language_code_array = array_keys($existing_languages);
		$language_name_array = array_values($existing_languages);

		if(in_array($new_language_code, $language_code_array) || in_array($new_language_name, $language_name_array)) 
		{
			$response->setResult(array('success' => false,'message'=>  vtranslate('LBL_LANGUAGES_EXIST',$request->getModule(false))));
		}
		else 
		{
			$last_updated = gmdate("Y-m-d H:i:s");
			$adb->pquery('insert into jo_language values (?,?,?,?,?,?,?,?)',array($adb->getUniqueID('jo_language'), $new_language_name, $new_language_code, $new_language_name, $last_updated, '', 0, 1));

		// Copy all files to new language folder
		
			$source_folder_name = "languages/$copy_language_from/";
                        $new_destination_folder_name = "languages/$new_language_code/";

                        mkdir($new_destination_folder_name);
                        chmod($new_destination_folder_name, 0777);
                        if(is_dir($new_destination_folder_name)) {

                                $files = glob("$source_folder_name*.*");
                                foreach($files as $file){
                                        $file_to_go = str_replace($source_folder_name, $new_destination_folder_name, $file);
                                        copy($file, $file_to_go);
                                        chmod($file_to_go, 0777);
                                }
                        }

                // Copy all files to new language settings folder       

                        $source_settings_folder_name = "languages/$copy_language_from/Settings/";
                        $new_destination_settings_folder_name = "languages/$new_language_code/Settings/";

                        mkdir($new_destination_settings_folder_name);
                        chmod($new_destination_settings_folder_name, 0777);
                        if(is_dir($new_destination_settings_folder_name)) {
                                $files = glob("$source_settings_folder_name*.*");
                                foreach($files as $file){
                                        $file_to_go = str_replace($source_settings_folder_name, $new_destination_settings_folder_name, $file);
                                        copy($file, $file_to_go);
                                        chmod($file_to_go, 0777);
                                }
                        }		 
		}
		
/*		$response = new Head_Response();
                if(!empty($recordModel)) {
                        $response->setResult(array('success' => true,'message'=>  vtranslate('LBL_DUPLICATES_EXIST',$request->getModule(false))));

                }else{
                        $response->setResult(array('success' => false));
                }*/
                $response->emit();
        }
}

?>
