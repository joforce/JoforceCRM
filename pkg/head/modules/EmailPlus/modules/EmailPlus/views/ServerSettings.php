<?php
class EmailPlus_ServerSettings_View extends Head_List_View
{
	public function process(Head_Request $request)
	{
		global $site_URL, $current_user, $adb;
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);

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
