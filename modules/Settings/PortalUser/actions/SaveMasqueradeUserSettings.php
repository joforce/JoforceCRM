<?php

class Settings_PortalUser_SaveMasqueradeUserSettings_Action extends Settings_Head_Index_Action {

	public function process (Head_Request $request) {

		$status = $request->get('user_status');
		$file_name = 'user_privileges/portal_user_settings.php';
		require_once($file_name);
		$myfile = fopen($file_name, "w") or die("Unable to open file!");
                fwrite($myfile, "<?php
	        	".'$enable_masquerade_user'." = " .$status. ";
        	?>");

                fclose($myfile);

                $response = new Head_Response();
                $response->setResult(array('success' => true));
                $response->emit();
	}
}

?>
