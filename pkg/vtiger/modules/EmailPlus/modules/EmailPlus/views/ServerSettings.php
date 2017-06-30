<?php
class EmailPlus_ServerSettings_View extends Vtiger_List_View
{
	public function process(Vtiger_Request $request)
	{
		global $site_URL, $current_user, $adb;
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		if(!$moduleModel->checkIonCubeLoaded())
		{
			$viewer->view('IoncubeNotAvailable.tpl', $moduleName);
		}
		else
		{
                        include_once('modules/EmailPlus/views/SettingsFunctionality.php');
		}
	}
}
