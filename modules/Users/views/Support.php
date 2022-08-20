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
			'modules.Head.resources.Detail',
			"modules.$moduleName.resources.Detail",
			'modules.Head.resources.RelatedList',
			"modules.$moduleName.resources.RelatedList",
			'libraries.jquery.jquery_windowmsg',
			"libraries.jquery.ckeditor.ckeditor",
			"libraries.jquery.ckeditor.adapters.jquery",
			"modules.Emails.resources.MassEdit",
			"modules.Head.resources.CkEditor",
			"~/libraries/jquery/twitter-text-js/twitter-text.js",
			"libraries.jquery.multiplefileupload.jquery_MultiFile",
			'~/libraries/jquery/bootstrapswitch/js/bootstrap-switch.min.js',
			'~/libraries/jquery.bxslider/jquery.bxslider.min.js',
			"~layouts/lib/jquery/Lightweight-jQuery-In-page-Filtering-Plugin-instaFilta/instafilta.js",
			'modules.Head.resources.Tag',
			'modules.Google.resources.Map'
				
		);
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
