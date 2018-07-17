<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Settings_LanguageEditor_SaveFile_Action extends Settings_Head_Index_Action {

        public function checkPermission(Head_Request $request) {
                return true;
        }

        public function process(Head_Request $request) {
		global $site_URL;
                $moduleName = $request->getModule(false);
		$file_path = $request->get('file_path');
		$label = $request->get('label');
		$edited_value = $request->get('value');
		$resource = $request->get('resource');

		require($file_path);
		if($resource == 'lbl')
		{
				$languageStrings[$label] = $edited_value;
		}
		else
		{
                                $jsLanguageStrings[$label] = $edited_value;
		}

		if(!$languageStrings)
		{
			$myfile = fopen($file_path, "w") or die("Unable to open file!");
                        fwrite($myfile, "<?php
                                /*+***********************************************************************************
                                 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
                                 * (License); You may not use this file except in compliance with the License
                                 * The Original Code is:  vtiger CRM Open Source
                                 * The Initial Developer of the Original Code is vtiger.
                                 * Portions created by vtiger are Copyright (C) vtiger.
                                 * All Rights Reserved.
                                 * Contributor(s): JoForce.com
                                 *************************************************************************************/
                                ".'$jsLanguageStrings'." = " .var_export($jsLanguageStrings, true). ";
                        ?>");
                        fclose($myfile);
		}
		elseif(!$jsLanguageStrings)
		{
			$myfile = fopen($file_path, "w") or die("Unable to open file!");
                        fwrite($myfile, "<?php
                                /*+***********************************************************************************
                                 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
                                 * (License); You may not use this file except in compliance with the License
                                 * The Original Code is:  vtiger CRM Open Source
                                 * The Initial Developer of the Original Code is vtiger.
                                 * Portions created by vtiger are Copyright (C) vtiger.
                                 * All Rights Reserved.
                                 * Contributor(s): JoForce.com
                                 *************************************************************************************/
                                ".'$languageStrings'." = " .var_export($languageStrings, true). ";
                        ?>");
                        fclose($myfile);
		}
		else
		{
			$myfile = fopen($file_path, "w") or die("Unable to open file!");
                        fwrite($myfile, "<?php
                                /*+***********************************************************************************
                                 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
                                 * (License); You may not use this file except in compliance with the License
                                 * The Original Code is:  vtiger CRM Open Source
                                 * The Initial Developer of the Original Code is vtiger.
                                 * Portions created by vtiger are Copyright (C) vtiger.
                                 * All Rights Reserved.
                                 * Contributor(s): JoForce.com
                                 *************************************************************************************/
                                ".'$languageStrings'." = " .var_export($languageStrings, true). ";
				".'$jsLanguageStrings'." = " .var_export($jsLanguageStrings, true). ";
                        ?>");
                        fclose($myfile);
		}

       		$loadUrl = "".$site_URL."LanguageEditor/Settings/Index";
                header("Location: $loadUrl");
        }

    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}
