<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class Settings_DuplicateCheck_List_View extends Settings_Head_Index_View {

	

	function __construct() {
		$this->exposeMethod('showFieldLayout');
		$this->exposeMethod('showRelatedListLayout');
	}

	public function process(Head_Request $request) {
		$qualifiedModule = $request->getModule(false);
		$viewer = $this->getViewer ($request);
		if($_GET['notify']){
			echo "<div style='height:20px; font-size:12px; padding:5px; height: 15px; length:100%; text-align: center; margin-left: 20%; margin-top:10px; display: inline-block;' class='fa fa-times-circle fa-2x alert alert-success notificationArea' id='notificationArea' onclick='clearNotificationArea();'> <span> Settings Saved Successfully </span><script>function clearNotificationArea(){ $('#notificationArea').hide(); }</script> </div>";
		}
		if($_GET['error']){
			echo "<div style='height:20px; font-size:12px; padding:5px; height: 15px; length:100%; text-align: center; margin-left: 20%; margin-top:10px; display: inline-block;' class='fa fa-times-circle fa-2x alert alert-danger notificationArea' id='notificationArea' onclick='clearNotificationArea();'> <span> Please Enable the Module </span><script>function clearNotificationArea(){ $('#notificationArea').hide(); }</script> </div>";
		}
		
	
		$mode = $request->getMode();

		if($this->isMethodExposed($mode)) {
			$this->invokeExposedMethod($mode, $request);

		}else {
			//by default show field layout
			$this->showFieldLayout($request);
		}

	}

	public function showFieldLayout(Head_Request $request) {

		global $adb;

		$sourceModule = $request->get('sourceModule');
		
		$supportedModulesList = Settings_DuplicateCheck_Module_Model::getSupportedModules();

		if(empty($sourceModule)) {
			//To get the first element
			$sourceModule = reset($supportedModulesList);
		}
				$queryVTDup = $adb->pquery("SELECT isenabled,crosscheck FROM jo_vtduplicatechecksettings WHERE modulename='$sourceModule'");
		$valuesVTDup = $adb->fetch_array($queryVTDup);
		$isenabledValue = $valuesVTDup['isenabled'];
		$crosscheckValue = $valuesVTDup['crosscheck'];
		$moduleModel = Settings_DuplicateCheck_Module_Model::getInstanceByName($sourceModule);
		
		$fieldsToMatch = Settings_DuplicateCheck_Module_Model::getInstanceByFieldToMatch($sourceModule);


		$fieldModels = $moduleModel->getFields();
		$blockModels = $moduleModel->getBlocks();


		$blockIdFieldMap = array();
		$inactiveFields = array();
		foreach($fieldModels as $fieldModel) {
			$blockIdFieldMap[$fieldModel->getBlockId()][$fieldModel->getName()] = $fieldModel;
			if(!$fieldModel->isActiveField()) {
				$inactiveFields[$fieldModel->getBlockId()][$fieldModel->getId()] = vtranslate($fieldModel->get('label'), $sourceModule);
			}
		}

		foreach($blockModels as $blockLabel => $blockModel) {
			$fieldModelList = $blockIdFieldMap[$blockModel->get('id')];
			$blockModel->setFields($fieldModelList);
		}
		

		
		$qualifiedModule = $request->getModule(false);
	
		$viewer = $this->getViewer($request);
		$viewer->assign('SELECTED_MODULE_NAME', $sourceModule);
		$viewer->assign('MODULE_ENABLED_VALUE', $isenabledValue);
		
		$viewer->assign('FIELDSTOMATCH',$fieldsToMatch);
		$viewer->assign('CROSSCHECKVALUE',$crosscheckValue);
		$viewer->assign('SUPPORTED_MODULES',$supportedModulesList);

		$viewer->assign('SELECTED_MODULE_MODEL', $moduleModel);
	
		$viewer->assign('BLOCKS',$blockModels);
		$viewer->assign('ADD_SUPPORTED_FIELD_TYPES', $moduleModel->getAddSupportedFieldTypes());
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModule);
		$viewer->assign('IN_ACTIVE_FIELDS', $inactiveFields);
		$viewer->view('Index.tpl',$qualifiedModule);
	}

	public function showRelatedListLayout(Head_Request $request) {
		$sourceModule = $request->get('sourceModule');
		$supportedModulesList = Settings_DuplicateCheck_Module_Model::getSupportedModules();

		if(empty($sourceModule)) {
			//To get the first element
			$moduleInstance = reset($supportedModulesList);
			$sourceModule = $moduleInstance->getName();
		}
		$moduleModel = Settings_DuplicateCheck_Module_Model::getInstanceByName($sourceModule);
		$relatedModuleModels = $moduleModel->getRelations();

		$qualifiedModule = $request->getModule(false);
		$viewer = $this->getViewer($request);

		$viewer->assign('SELECTED_MODULE_NAME', $sourceModule);
		$viewer->assign('RELATED_MODULES',$relatedModuleModels);
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModule);
		$viewer->view('RelatedList.tpl',$qualifiedModule);
	}
	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Head.resources.List',
			'modules.Settings.Head.resources.List',
			"modules.Settings.$moduleName.resources.List",
			"modules.Settings.Head.resources.$moduleName",
            "~layouts/lib/jquery/sadropdown.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

}
