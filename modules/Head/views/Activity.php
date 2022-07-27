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

class Head_Activity_View extends Head_List_View {

	function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');

		$recordPermission = Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $recordId);
		if(!$recordPermission) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if ($recordId) {
			$recordEntityName = getSalesEntityType($recordId);
			if ($recordEntityName !== $moduleName) {
				throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
			}
		}
		return true;
	}

	function preProcess(Head_Request $request, $display=true) {
		global $current_user;
		parent::preProcess($request, false);

		$recordId = $request->get('record');
		$moduleName = $request->getModule();

	        $pipeline_model = new Settings_Pipeline_Module_Model();
        	$pipeine_modules = $pipeline_model->getPipelineEnabledModules();
	        $kanban_view = (in_array($moduleName, $pipeine_modules)) ? true : false;

		if(!$this->record){
			$this->record = Head_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();
		$recordStrucure = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_DETAIL);
		$summaryInfo = array();
		// Take first block information as summary information
		$stucturedValues = $recordStrucure->getStructure();
		foreach($stucturedValues as $blockLabel=>$fieldList) {
			$summaryInfo[$blockLabel] = $fieldList;
			break;
		}

		$detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);

		$detailViewLinks = $this->record->getDetailViewLinks($detailViewLinkParams);
		$navigationInfo = ListViewSession::getListViewNavigation($recordId);

		$viewer = $this->getViewer($request);
		$viewer->assign('kanban_view_enabled' ,$kanban_view);
		$viewer->assign('MASQUERADEUSERMODULE', $masquerade_user_module);
		$viewer->assign('RECORD', $recordModel);
		$viewer->assign('NAVIGATION', $navigationInfo);

		//Intially make the prev and next records as null
		$prevRecordId = null;
		$nextRecordId = null;
		$found = false;
		if ($navigationInfo) {
			foreach($navigationInfo as $page=>$pageInfo) {
				foreach($pageInfo as $index=>$record) {
					//If record found then next record in the interation
					//will be next record
					if($found) {
						$nextRecordId = $record;
						break;
					}
					if($record == $recordId) {
						$found = true;
					}
					//If record not found then we are assiging previousRecordId
					//assuming next record will get matched
					if(!$found) {
						$prevRecordId = $record;
					}
				}
				//if record is found and next record is not calculated we need to perform iteration
				if($found && !empty($nextRecordId)) {
					break;
				}
			}
		}

		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if(!empty($prevRecordId)) {
			$viewer->assign('PREVIOUS_RECORD_URL', $moduleModel->getDetailViewUrl($prevRecordId));
		}
		if(!empty($nextRecordId)) {
			$viewer->assign('NEXT_RECORD_URL', $moduleModel->getDetailViewUrl($nextRecordId));
		}

		$viewer->assign('MODULE_MODEL', $this->record->getModule());
		$viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);

		$viewer->assign('IS_EDITABLE', $this->record->getRecord()->isEditable($moduleName));
		$viewer->assign('IS_DELETABLE', $this->record->getRecord()->isDeletable($moduleName));

		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		$linkModels = $this->record->getSideBarLinks($linkParams);
		$viewer->assign('QUICK_LINKS', $linkModels);
		$viewer->assign('MODULE_NAME', $moduleName);

		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('DEFAULT_RECORD_VIEW', $currentUserModel->get('default_record_view'));

		$picklistDependencyDatasource = Head_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE', Head_Functions::jsonEncode($picklistDependencyDatasource));

		$tagsList = Head_Tag_Model::getAllAccessible($currentUserModel->getId(), $moduleName, $recordId);
		$allUserTags = Head_Tag_Model::getAllUserTags($currentUserModel->getId());
		$viewer->assign('TAGS_LIST', $tagsList);
		$viewer->assign('ALL_USER_TAGS', $allUserTags);

		$selectedTabLabel = $request->get('tab_label');
		$relationId = $request->get('relationId');

		if(empty($selectedTabLabel)) {
			if($currentUserModel->get('default_record_view') === 'Detail') {
				$selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName);
			} else{
				if($moduleModel->isSummaryViewSupported()) {
					$selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_SUMMARY', $moduleName);
				} else {
					$selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName);
				}
			}
		}

		$viewer->assign('SELECTED_TAB_LABEL', $selectedTabLabel);
		$viewer->assign('SELECTED_RELATION_ID',$relationId);

		//Head7 - TO show custom view name in Module Header
		$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($moduleName));
		/*masquerade for contacts*/
		$query = getMasqueradeUserRecordDetails();
		$result = $query['support_end_date'];
		$viewer->assign('RESULT', $result);
		/*masquerade for contacts*/
		$viewer->assign('IS_AJAX_ENABLED', $this->isAjaxEnabled($recordModel));

                $recordStrucure = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_SUMMARY);

                $viewer->assign('BLOCK_LIST', $moduleModel->getBlocks());
                $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());

                $viewer->assign('MODULE_NAME', $moduleName);
                $viewer->assign('SUMMARY_RECORD_STRUCTURE', $recordStrucure->getStructure());
                $viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
                $pagingModel = new Head_Paging_Model();
                $viewer->assign('PAGING_MODEL', $pagingModel);

                $picklistDependencyDatasource = Head_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
                $viewer->assign('PICKIST_DEPENDENCY_DATASOURCE', Head_Functions::jsonEncode($picklistDependencyDatasource));
				$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));

		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Head_Request $request) {
		return 'ActivityViewPreProcess.tpl';
	}

	function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$this->_showRecentActivities($request);

		$viewer = $this->getViewer($request);
		echo $viewer->view('RecordActivities.tpl', $moduleName, true);

	}

	public function postProcess(Head_Request $request) {
		$recordId = $request->get('record');
		$moduleName = $request->getModule();
		if($moduleName=="Calendar"){
			$recordModel = Head_Record_Model::getInstanceById($recordId);
			$activityType = $recordModel->getType();
			if($activityType=="Events"){
				$moduleName="Events";
			}
		}
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if(!$this->record){
			$this->record = Head_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$detailViewLinkParams = array('MODULE'=>$moduleName,'RECORD'=>$recordId);
		$detailViewLinks = $this->record->getDetailViewLinks($detailViewLinkParams);

		$selectedTabLabel = $request->get('tab_label');
		$relationId = $request->get('relationId');

		if(empty($selectedTabLabel)) {
			if($currentUserModel->get('default_record_view') === 'Detail') {
				$selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName);
			} else{
				if($moduleModel->isSummaryViewSupported()) {
					$selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_SUMMARY', $moduleName);
				} else {
					$selectedTabLabel = vtranslate('SINGLE_'.$moduleName, $moduleName).' '. vtranslate('LBL_DETAILS', $moduleName);
				}
			}
		}

		$viewer = $this->getViewer($request);

		$viewer->assign('SELECTED_TAB_LABEL', $selectedTabLabel);
		$viewer->assign('SELECTED_RELATION_ID',$relationId);
		$viewer->assign('MODULE_MODEL', $this->record->getModule());
		$viewer->assign('DETAILVIEW_LINKS', $detailViewLinks);

		$viewer->view('DetailViewPostProcess.tpl', $moduleName);

		parent::postProcess($request);
	}


	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'modules.Head.resources.Detail',
			"modules.$moduleName.resources.Detail",
			'modules.Head.resources.RelatedList',
			"modules.$moduleName.resources.RelatedList",
			'modules.Head.resources.Activity',
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

	/**
	 * Added to support Engagements view in Head7
	 * @param Head_Request $request
	 */
	function _showRecentActivities(Head_Request $request){
		$parentRecordId = $request->get('record');
		$pageNumber = $request->get('page');
		$limit = $request->get('limit');
		$moduleName = $request->getModule();

		if(empty($pageNumber)) {
			$pageNumber = 1;
		}

		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('page', $pageNumber);
		if(!empty($limit)) {
			$pagingModel->set('limit', $limit);
		}

		if(isset($_SESSION['filter_date'])) {
			$recentActivities = ModTracker_Record_Model::getUpdates($parentRecordId, $pagingModel,$moduleName, $_SESSION['filter_date']);
			$activityDates = ModTracker_Record_Model::getDateUpdates($parentRecordId, $pagingModel,$moduleName, $_SESSION['filter_date']);				
		} else {
			$recentActivities = ModTracker_Record_Model::getUpdates($parentRecordId, $pagingModel,$moduleName,"");
			$activityDates = ModTracker_Record_Model::getDateUpdates($parentRecordId, $pagingModel,$moduleName,"");			
		}
		unset($_SESSION['filter']);


		$pagingModel->calculatePageRange($recentActivities);

		if($pagingModel->getCurrentPage() == ModTracker_Record_Model::getTotalRecordCount($parentRecordId)/$pagingModel->getPageLimit()) {
			$pagingModel->set('nextPageExists', false);
		}
		$recordModel = Head_Record_Model::getInstanceById($parentRecordId);
		$viewer = $this->getViewer($request);
		$viewer->assign('SOURCE',$recordModel->get('source'));
		$viewer->assign('ACTIVITIES', $recentActivities);
		$viewer->assign('ACTIVITY_DATES', $activityDates);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('PAGING_MODEL', $pagingModel);
		$viewer->assign('RECORD_ID',$parentRecordId);
		$viewer->assign('FILTER_TYPE', isset($_SESSION['filtertype']) ? $_SESSION['filtertype'] : 'all');
	}

	/**
	 * Function to get Ajax is enabled or not
	 * @param Head_Record_Model record model
	 * @return <boolean> true/false
	 */
	function isAjaxEnabled($recordModel) {
		if(is_null($this->isAjaxEnabled)){
			$this->isAjaxEnabled = $recordModel->isEditable();
		}
		return $this->isAjaxEnabled;
	}


	public function getHeaderCss(Head_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);
		$cssFileNames = array(
			'~/libraries/jquery/bootstrapswitch/css/bootstrap2/bootstrap-switch.min.css',
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);
		return $headerCssInstances;
	}
}
