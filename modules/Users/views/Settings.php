<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Users_Settings_View extends Head_Basic_View {

	function checkPermission(Head_Request $request) {
        return true;
	}
    
    public function preProcess (Head_Request $request, $display=true) {
		parent::preProcess($request, false);
		$this->preProcessSettings($request,$display);
	}

	public function preProcessSettings (Head_Request $request ,$display=true) {
		$viewer = $this->getViewer($request);

		$moduleName = $request->getModule();
		$fieldId = $request->get('fieldid');
		
        $moduleModel = Head_Module_Model::getInstance($moduleName);
        $this->setModuleInfo($request, $moduleModel);
        
		$viewer->assign('SELECTED_FIELDID',$fieldId);
		$viewer->assign('MODULE', $moduleName);
        
        if($display) {
			$this->preProcessDisplay($request);
		}
	}
    
    public function preProcessTplName(Head_Request $request) {
        return 'UsersSettingsMenuStart.tpl';
	}
    
    public function process(Head_Request $request) {
        //Redirect to My Preference Page
        $userModel = Users_Record_Model::getCurrentUserModel();
        header('Location: ' . $userModel->getPreferenceDetailViewUrl());
    }
    
    public function postProcessSettings (Head_Request $request) {

		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule(false);
		$viewer->view('UsersSettingsMenuEnd.tpl', $moduleName);
	}

	public function postProcess (Head_Request $request) {
		$this->postProcessSettings($request);
		parent::postProcess($request);
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
			"modules.$moduleName.resources.Settings",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
    
    /**
     * Setting module related Information to $viewer (for Head7)
     * @param type $request
     * @param type $moduleModel
     */
    public function setModuleInfo($request, $moduleModel){
        $fieldsInfo = array();
        
        $moduleFields = $moduleModel->getFields();
        foreach($moduleFields as $fieldName => $fieldModel){
            $fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
        }

        $viewer = $this->getViewer($request);
        $viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));
    }
	
}
