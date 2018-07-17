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
class Settings_Notifications_SaveSettings_Action extends Settings_Head_Index_Action {
	
	public function checkPermission(Head_Request $request) {
                return true;
        }

	public function process(Head_Request $request) {
		global $current_user;
		$user_id = $current_user->id;
		$updatedFields = $request->get('updatedFields');
		$global_notification_settings = $request->get('global_settings');

		if(file_exists("user_privileges/notifications/notification_".$user_id.".php"))
                        $file_name = "user_privileges/notifications/notification_".$user_id.".php";
                else
                        $file_name = 'user_privileges/notifications/default_settings.php';

                require($file_name);
		$file_path ="user_privileges/notifications/notification_".$user_id.".php";

		if($global_notification_settings == 'enabled')
		{
			$global_settings = true;
			$checked_values = [];
        	        foreach($updatedFields as $array)
                	{
				$string = $array['name'];
				$array = explode('_', $string);
				$module= $array[0];
				$value = $array[1];
			
				$temp_array = array_keys($checked_values);
			
				if(in_array($module, $temp_array)) {
					$checked_values[$module][$value] = true;
				}
				else {
					$checked_values[$module] = array($value => true);
				}
        	        }
	
			foreach($notification_settings as $settings_module_name => $settings_value_array)
			{
				if(!isset($checked_values[$settings_module_name]))
				{
					$notification_settings[$settings_module_name]['assigned'] = 'false';
                                	$notification_settings[$settings_module_name]['following'] = 'false';
				}
				else{
					if(count($checked_values[$settings_module_name]) == 2)
					{
						$notification_settings[$settings_module_name]['assigned'] = 'true';
                                        	$notification_settings[$settings_module_name]['following'] = 'true';
					}
					else
					{
						$temp_array = $checked_values[$settings_module_name];
						$temp_value = array_keys($temp_array);
						$value = $temp_value[0];
						if($value == 'assigned'){
							$notification_settings[$settings_module_name][$value] = 'true';
							$notification_settings[$settings_module_name]['following'] = 'false';
						}
						else {
							$notification_settings[$settings_module_name][$value] = 'true';
	                                                $notification_settings[$settings_module_name]['assigned'] = 'false';
						}
					}
				}
			}
		$myfile = fopen($file_path, "w") or die("Unable to open file!");
                fwrite($myfile, "<?php
                ".'$global_settings'." = true;
                ".'$notification_settings'." = " .var_export($notification_settings, true). ";
                ?>");
		}
		else
		{
			$myfile = fopen($file_path, "w") or die("Unable to open file!");
	                fwrite($myfile, "<?php
        	        ".'$global_settings'." = false;
                	".'$notification_settings'." = " .var_export($notification_settings, true). ";
	                ?>");
		}

                fclose($myfile);
	}
}

?>
