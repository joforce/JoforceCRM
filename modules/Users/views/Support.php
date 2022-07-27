<?php
class Users_Support_View extends Head_Index_view{
   
    function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
		$viewer->view('Support.tpl', $moduleName);
	}
	
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			"~layouts/modules/Users/resources/Supportmail.js",
				
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
