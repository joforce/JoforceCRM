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

class Project_Detail_View extends Head_Detail_View {
	
	function __construct() {
		parent::__construct();
		$this->exposeMethod('showRelatedRecords');
        $this->exposeMethod('showChart');
	}

	public function showModuleSummaryView($request) {
		$recordId = $request->get('record');
		$moduleName = $request->getModule();

		$recordModel = Head_Record_Model::getInstanceById($recordId);
		$recordStrucure = Head_RecordStructure_Model::getInstanceFromRecordModel($recordModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_SUMMARY);
		
		$viewer = $this->getViewer($request);
		$viewer->assign('RECORD', $recordModel);
        $viewer->assign('IS_AJAX_ENABLED', $this->isAjaxEnabled($recordModel));
		$viewer->assign('SUMMARY_INFORMATION', $recordModel->getSummaryInfo());
		$viewer->assign('SUMMARY_RECORD_STRUCTURE', $recordStrucure->getStructure());
        $viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('MODULE_NAME', $moduleName);

		return $viewer->view('ModuleSummaryView.tpl', $moduleName, true);
	}
	
	/**
	 * Function returns related records based on related moduleName
	 * @param Head_Request $request
	 * @return <type>
	 */
	function showRelatedRecords(Head_Request $request) {
		$parentId = $request->get('record');
		$pageNumber = $request->get('page');
		$limit = $request->get('limit');
		$relatedModuleName = $request->get('relatedModule');
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		$whereCondition = $request->get('whereCondition');
		$moduleName = $request->getModule();
		$relatedModuleInstance = Head_Module_Model::getInstance($relatedModuleName);
		
		if($sortOrder == "ASC") {
			$nextSortOrder = "DESC";
			$sortImage = "icon-chevron-down";
		} else {
			$nextSortOrder = "ASC";
			$sortImage = "icon-chevron-up";
		}
		
		$parentRecordModel = Head_Record_Model::getInstanceById($parentId, $moduleName);
		$relationListView = Head_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName);
		$relatedModuleModel = $relationListView->getRelationModel()->getRelationModuleModel();
		
		if(!empty($orderBy)) {
			$relationListView->set('orderby', $orderBy);
			$relationListView->set('sortorder', $sortOrder);
		}

		if(empty($pageNumber)) {
			$pageNumber = 1;
		}

		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('page', $pageNumber);
		if(!empty($limit)) {
			$pagingModel->set('limit', $limit);
		}
		
		if ($whereCondition) {
			$relationListView->set('whereCondition', $whereCondition);
		}
		
		$models = $relationListView->getEntries($pagingModel);
		$header = $relationListView->getHeaders();
		//ProjectTask Progress and Status should show in Projects summary view 
		if($relatedModuleName == 'ProjectTask') {
			$fieldModel = Head_Field_Model::getInstance('projecttaskstatus', $relatedModuleInstance);
			if($fieldModel && $fieldModel->isViewableInDetailView()) {
				$header['projecttaskstatus'] = $relatedModuleModel->getField('projecttaskstatus');
			}
			$fieldModel = Head_Field_Model::getInstance('projecttaskprogress', $relatedModuleInstance);
			if($fieldModel && $fieldModel->isViewableInDetailView()) {
				$header['projecttaskprogress'] = $relatedModuleModel->getField('projecttaskprogress');
			}
		}
		
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE' , $moduleName);
		$viewer->assign('RELATED_RECORDS' , $models);
		$viewer->assign('RELATED_HEADERS', $header);
		$viewer->assign('RELATED_MODULE' , $relatedModuleName);
		$viewer->assign('RELATED_MODULE_MODEL', $relatedModuleInstance);
		$viewer->assign('PAGING_MODEL', $pagingModel);

		return $viewer->view('SummaryWidgets.tpl', $moduleName, 'true');
	}

	/**
	 * Function to show Gantt chart
	 * @param Head_Request $request
	 */
	public function showChart(Head_Request $request) {
		$parentId = $request->get('record');
		$projectTasks = array();
		$moduleName = $request->getModule();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$parentRecordModel = Head_Record_Model::getInstanceById($parentId, $moduleName);
		$projectTaskModel = Head_Module_Model::getInstance('ProjectTask');
		$projectTasks['tasks'] = $parentRecordModel->getProjectTasks();
		$projectTasks["selectedRow"] = 0;
		$projectTasks["canWrite"] = true;
		$projectTasks["canWriteOnParent"] = true;
		$viewer = $this->getViewer($request);
		$viewer->assign('PARENT_ID', $parentId);
		$viewer->assign('MODULE' , $moduleName);
		$viewer->assign('PROJECT_TASKS' , $projectTasks);
		$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
		$viewer->assign('TASK_STATUS', Head_Util_Helper::getRoleBasedPicklistValues('projecttaskstatus', $currentUserModel->get('roleid')));
		$viewer->assign('TASK_STATUS_COLOR', $parentRecordModel->getStatusColors());
		$viewer->assign('STYLES',$this->getHeaderCss($request));
		$viewer->assign('USER_DATE_FORMAT', $currentUserModel->get('date_format'));
		$viewer->assign('STATUS_FIELD_MODEL', Head_Field_Model::getInstance('projecttaskstatus', $projectTaskModel));

		return $viewer->view('ShowChart.tpl', $moduleName, 'true');
	}

	/**
	 * Function get gantt specific headerscript
	 * @param Head_Request $request
	 */
	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$jsFileNames = array(
			'~/libraries/jquery/gantt/libs/jquery.livequery.min.js',
			'~/libraries/jquery/gantt/libs/jquery.timers.js',
			'~/libraries/jquery/gantt/libs/platform.js',
			'~/libraries/jquery/gantt/libs/date.js',
			'~/libraries/jquery/gantt/libs/i18nJs.js',
			'~/libraries/jquery/gantt/libs/JST/jquery.JST.js',
			'~/libraries/jquery/gantt/libs/jquery.svg.min.js',
			'~/libraries/jquery/gantt/ganttUtilities.js',
			'~/libraries/jquery/gantt/ganttTask.js',
			'~/libraries/jquery/gantt/ganttDrawerSVG.js',
			'~/libraries/jquery/gantt/ganttGridEditor.js',
			'~/libraries/jquery/gantt/ganttMaster.js',
			'~/libraries/jquery/gantt/libs/moment.min.js',
			'~/libraries/jquery/colorpicker/js/colorpicker.js',
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances,$jsScriptInstances);
		return $headerScriptInstances;
	}

	/**
	 * Function to get the css styles for gantt chart
	 * @param  Head_Request $request
	 */
	public function getHeaderCss(Head_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);
		$cssFileNames = array(
			'~/libraries/jquery/gantt/platform.css',
			'~/libraries/jquery/gantt/gantt.css',
			'~/libraries/jquery/colorpicker/css/colorpicker.css',
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($cssInstances, $headerCssInstances);
		return $headerCssInstances;
	}
}
?>
