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

class Calendar_Calendar_View extends Head_Index_View
{
    public function preProcess(Head_Request $request, $display = true) {
        $viewer = $this->getViewer($request);

        $moduleName = $request->getModule();
        $viewer->assign('MODULE_NAME', $moduleName);
        $moduleModel = Head_Module_Model::getInstance($moduleName);
        $viewer->assign('IS_CREATE_PERMITTED', $moduleModel->isPermitted('CreateView'));
        $viewer->assign('IS_MODULE_EDITABLE', $moduleModel->isPermitted('EditView'));
        $viewer->assign('IS_MODULE_DELETABLE', $moduleModel->isPermitted('Delete'));

        parent::preProcess($request, false);
        if($display) {
            $this->preProcessDisplay($request);
        }
    }

	protected function preProcessTplName(Head_Request $request) {
		return 'ListViewPreProcess.tpl';
	}

	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
			"~layouts/lib/jquery/fullcalendar/lib/moment.min.js",
			"~layouts/lib/jquery/fullcalendar/fullcalendar.js",
			"~layouts/lib/jquery/webui-popover/dist/jquery.webui-popover.js",
			"~layouts/modules/Calendar/resources/Calendar.js",
			"~/libraries/jquery/colorpicker/js/colorpicker.js"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	public function getHeaderCss(Head_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);

		$cssFileNames = array(
			'~layouts/lib/jquery/fullcalendar/fullcalendar.css',
			'~layouts/lib/jquery/fullcalendar/fullcalendar-bootstrap.css',
			'~layouts/lib/jquery/webui-popover/dist/jquery.webui-popover.css',
			'~/libraries/jquery/colorpicker/css/colorpicker.css'
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);

		return $headerCssInstances;
	}

	public function process(Head_Request $request) {
		global $site_URL;
		$mode = $request->getMode();
		if($mode == 'settings'){
			$this->getCalendarSettings($request);
		}
		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if($request->getMode() == 'Settings'){
			return $this->getCalendarSettings($request);
		}
		$viewer->assign('CURRENT_USER', $currentUserModel);
		$viewer->assign('IS_CREATE_PERMITTED', isPermitted('Calendar', 'CreateView'));
		$viewer->assign('Site_Url',$site_URL);

		$viewer->view('CalendarView.tpl', $request->getModule());
	}

	/*
	 * Function to get the calendar settings view
	 */
	public function getCalendarSettings(Head_Request $request){

		$viewer = $this->getViewer($request);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$module = $request->getModule();
		$detailViewModel = Head_DetailView_Model::getInstance('Users', $currentUserModel->id);
		$userRecordStructure = Head_RecordStructure_Model::getInstanceFromRecordModel($detailViewModel->getRecord(), Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_EDIT);
		$recordStructure = $userRecordStructure->getStructure();
		$allUsers = Users_Record_Model::getAll(true);
		$sharedUsers = Calendar_Module_Model::getCaledarSharedUsers($currentUserModel->id);
		$sharedType = Calendar_Module_Model::getSharedType($currentUserModel->id);
		$dayStartPicklistValues = Users_Record_Model::getDayStartsPicklistValues($recordStructure);

		$hourFormatFeildModel = $recordStructure['LBL_CALENDAR_SETTINGS']['hour_format'];

		$viewer->assign('CURRENTUSER_MODEL',$currentUserModel);
		$viewer->assign('SHAREDUSERS', $sharedUsers);
		$viewer->assign("DAY_STARTS", Zend_Json::encode($dayStartPicklistValues));
		$viewer->assign('ALL_USERS',$allUsers);
		$viewer->assign('RECORD_STRUCTURE', $recordStructure);
		$viewer->assign('MODULE',$module);
		$viewer->assign('RECORD', $currentUserModel->id);
		$viewer->assign('SHAREDTYPE', $sharedType);
		$viewer->assign('HOUR_FORMAT_VALUE', $hourFormatFeildModel->get('fieldvalue'));

		$viewer->view('CalendarSettings.tpl', $request->getModule());
	}


}
