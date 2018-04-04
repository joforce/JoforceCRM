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

class Settings_MenuManager_SaveSection_Action extends Settings_Head_Index_Action {
	function __construct() {
                parent::__construct();
                $this->exposeMethod('deleteSection');
                $this->exposeMethod('addSection');
        }

        public function checkPermission(Head_Request $request) {
                return true;
        }

        public function process(Head_Request $request) {
                $mode = $request->get('mode');
                if (!empty($mode)) {
                        $this->invokeExposedMethod($mode, $request);
                        return;
                }
        }
	
	function addSection(Head_Request $request) {
		global $adb, $current_user;
		$admin_status = Settings_MenuManager_Module_Model::isAdminUser();
                $user_id = $current_user->id;
		$section_name = $request->get('section_name');
		$tabid = $request->get('tabid');
		$icon_info = $request->get('icon_info');
		$section_file_name = "storage/menu/sections_".$user_id.".php";
                        if(file_exists($section_file_name))
                        {
                        require($section_file_name);
                        }
                        else
                        {
                        require("storage/menu/default_sections.php");
                        }
		$section_array[$section_name] = $icon_info;

		$myfile = fopen($section_file_name, "w") or die("Unable to open file!");
                fwrite($myfile, "<?php
        ".'$section_array'." = " .var_export($section_array, true). ";
        ?>");
                fclose($myfile);
	
		$file_name = "storage/menu/module_apps_".$user_id.".php";
                        if(file_exists($file_name))
                        {
                        require($file_name);
                        }
                        else
                        {
                        require("storage/menu/default_module_apps.php");
                        }
		$app_menu_array[$section_name][0] = $tabid;
		$myfile = fopen($file_name, "w") or die("Unable to open file!");
                fwrite($myfile, "<?php
        ".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
        ?>");
                fclose($myfile);

		$response = new Head_Response();
                $response->setResult(array('success' => true));
                $response->emit();

	}
	
	function deleteSection(Head_Request $request) {
                global $adb, $current_user;
                $user_id = $current_user->id;
                $appName = $request->get('appname');
                $section_file_name = "storage/menu/sections_".$user_id.".php";
                        if(file_exists($section_file_name))
                        {
                        require($section_file_name);
                        }
                        else
                        {
                        require("storage/menu/default_sections.php");
                        }
                unset($section_array[$appName]);

                $myfile = fopen($section_file_name, "w") or die("Unable to open file!");
                fwrite($myfile, "<?php
        ".'$section_array'." = " .var_export($section_array, true). ";
        ?>");
                fclose($myfile);

                $file_name = "storage/menu/module_apps_".$user_id.".php";
                        if(file_exists($file_name))
                        {
                        require($file_name);
                        }
                        else
                        {
                        require("storage/menu/default_module_apps.php");
                        }
                unset($app_menu_array[$section_name]);
                $myfile = fopen($file_name, "w") or die("Unable to open file!");
                fwrite($myfile, "<?php
        ".'$app_menu_array'." = " .var_export($app_menu_array, true). ";
        ?>");
                fclose($myfile);

                $response = new Head_Response();
                $response->setResult(array('success' => true));
                $response->emit();

        }

}
