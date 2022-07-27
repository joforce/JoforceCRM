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

class EmailTemplates_Detail_View extends Head_Index_View {

	function preProcess(Head_Request $request, $display=true) {
		parent::preProcess($request, false);
		global $current_user;
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		if(!$this->record){
			$this->record = EmailTemplates_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();

		$detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);
		$detailViewLinks = $this->record->getDetailViewLinks($detailViewLinkParams);

		$viewer = $this->getViewer($request);
		$viewer->assign('RECORD', $recordModel);
 		$user_id = $current_user->id;
               
		$viewer->assign('SECTION_ARRAY', getSectionList($user_id)); //section names
                $viewer->assign('MAIN_MENU_TAB_IDS', getMainMenuList($user_id)); //main menu
		$viewer->assign('APP_MODULE_ARRAY', getAppModuleList($user_id)); //modules and sections

                $userCurrencyInfo = getCurrencySymbolandCRate($current_user->currency_id);
                $viewer->assign('USER_CURRENCY_SYMBOL', $userCurrencyInfo['symbol']);
                //Get User Notifications
		$viewer->assign('NOTIFICATONS_COUNT', getUnseenNotificationCount($user_id));

		$viewer->assign('MODULE_MODEL', $this->record->getModule());
		$viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);

		$viewer->assign('IS_EDITABLE', $this->record->getRecord()->isEditable($moduleName));
		$viewer->assign('IS_DELETABLE', $this->record->getRecord()->isDeletable($moduleName));

		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		$linkModels = $this->record->getSideBarLinks($linkParams);
		$viewer->assign('QUICK_LINKS', $linkModels);

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('DEFAULT_RECORD_VIEW', $currentUserModel->get('default_record_view'));
		$viewer->assign('NO_PAGINATION', true);
		$viewer->assign('VIEW', $request->get('view'));
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Head_Request $request) {
		return 'DetailViewPreProcess.tpl';
	}

	function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');
		$viewer = $this->getViewer($request);

		$recordModel = EmailTemplates_Record_Model::getInstanceById($record);
		$recordModel->setModule($moduleName);

		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('MODULE_NAME', $moduleName);

		$viewer->view('DetailViewFullContents.tpl', $moduleName);
	}

	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);

		$jsFileNames = array(
			'modules.Head.resources.Detail',
			'modules.EmailTemplates.resources.Detail',
			'modules.Settings.Head.resources.Index',
			"~layouts/lib/jquery/Lightweight-jQuery-In-page-Filtering-Plugin-instaFilta/instafilta.min.js"

		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
