<?php
class EmailPlus_FetchEmailAndLogin_Action extends Head_Save_Action
{
	public function __construct()   {

	}

	public function process(Head_Request $request) {

		global $adb, $current_user, $site_URL;
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
        include_once('modules/EmailPlus/actions/FetchEmailAndLoginFunctionality.php');
	}
}
?>
