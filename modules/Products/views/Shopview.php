<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/
class Products_Shopview_View extends Head_Index_View
{
	function __construct()
	{
		parent::__construct();
		$this->pipeModel = new Settings_Pipeline_Module_Model();
	}

	public function preProcess(Head_Request $request, $display = true)
	{
		$viewer = $this->getViewer($request);
		$sourceModule = $request->getModule();

		$viewer->assign('VIEW', $request->get('view'));
		$moduleName = $sourceModule;

		$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($moduleName));
		$pipeine_modules = $this->pipeModel->getPipelineEnabledModules();
		$kanban_view = (in_array($sourceModule, $pipeine_modules)) ? true : false;

		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$customList = Head_Filter::getAllForModule($moduleModel);
		$viewer->assign('CUSTOM_LIST', $customList);
		if (empty($sourceModule)) {
			$sourceModule = $request->get('relatedModule');
		}
		$viewer->assign('SOURCE_MODULE_NAME', $sourceModule);
		$viewer->assign('MODULE', $sourceModule);

		$pageNumber = $request->get('page');
		if (empty($pageNumber) || !is_numeric($pageNumber)) {
			$pageNumber = 1;
		}
		$cvId = $request->get('viewname');
		if (empty($cvId)) {
			$customView = new CustomView();
			$cvId = $_SESSION['lvs'][$sourceModule]['viewname'];
			if (empty($cvId)) {
				$cvId = $customView->getViewIdByName('All', $sourceModule);
			}
		}

		$pagingModel = $this->getPagingModal($sourceModule);;
		$pagingModel->set('page', $pageNumber);
		$pagingModel->set('viewid', $cvId);
		$listViewCount_total = $this->getListViewCount($sourceModule, $cvId);
		$listViewCount_page =  $this->getPageCountByTotal($listViewCount_total, $sourceModule);

		$listViewModel = $this->getListViewEntriesModel($sourceModule, $cvId);
		$listViewEntries = $listViewModel->getListViewEntries($pagingModel);

		$viewer->assign('page_count', $listViewCount_page['page']);
		$viewer->assign('RECORD_COUNT', $listViewCount_total);
		$viewer->assign('PAGE_NUMBER', $pageNumber);
		$viewer->assign('PAGING_MODEL', $pagingModel);

		parent::preProcess($request);
	}
	function preProcessTplName(Head_Request $request) {
		return 'kanban/ListViewPreProcessKanban.tpl';
	}

	public function getPagingModal($sourceModule)
	{
		$source_tab_id = getTabid($sourceModule);
		$records_per_page = $this->pipeModel->getRecordsPerPage($source_tab_id);
		if ($records_per_page > 15) $records_per_page = 15;
		$pagingModel = new Head_Paging_Model(array('limit' => $records_per_page));
		return $pagingModel;
	}

	function getListViewCount($moduleName, $cvId)
	{
		$listViewModel = $this->getListViewEntriesModel($moduleName, $cvId);
		$count = $listViewModel->getListViewCount();
		return $count;
	}

	function getListViewEntriesModel($moduleName, $cvId)
	{
		return Head_ListView_Model::getInstance($moduleName, $cvId);
	}

	function getPageCount($sourceModule, $cvId)
	{
		$listViewCount = $this->getListViewCount($sourceModule, $cvId);
		$pagingModel = $this->getPagingModal($sourceModule);
		$pageLimit = $pagingModel->getPageLimit();
		$pageCount = ceil((int) $listViewCount / (int) $pageLimit);

		if ($pageCount == 0) {
			$pageCount = 1;
		}
		return $pageCount;
	}

	function getPageCountByTotal($listViewCount, $sourceModule)
	{
		$pagingModel = $this->getPagingModal($sourceModule);
		$pageLimit = $pagingModel->getPageLimit();
		$pageCount = ceil((int) $listViewCount / (int) $pageLimit);

		if ($pageCount == 0) {
			$pageCount = 1;
		}
		$result = array();
		$result['page'] = $pageCount;
		$result['numberOfRecords'] = $listViewCount;
		return $result;
	}

	public function process(Head_Request $request)
	{
		global $adb, $site_URL, $current_user;
		$moduleName = $request->getModule();
		$roleid = $current_user->roleid;
		$cvId = $request->get('viewname');
		if (empty($cvId)) {
			$customView = new CustomView();
			$cvId = $_SESSION['lvs'][$sourceModule]['viewname'];
			if (empty($cvId)) {
				$cvId = $customView->getViewIdByName('All', $sourceModule);
			}
		}

		$viewer = $this->getViewer($request);
		$sales_array = getSalesStageArray('forecast');
		$sales_count = count($sales_array);
		$pageNumber = $request->get('page');
		if (empty($pageNumber) || !is_numeric($pageNumber)) {
			$pageNumber = 1;
		}


		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$listViewCount_total = $this->getListViewCount($moduleName, $cvId);
		$listViewCount_page =  $this->getPageCountByTotal($listViewCount_total, $sourceModule);

		$listViewModel = Head_ListView_Model::getInstance($moduleName, $cvId);
		$pagingModel = $this->getPagingModal($moduleName);
		$pagingModel->set('page', $pageNumber);
		$pagingModel->set('viewid', $cvId);
		$pagingModel->set('view_name', 'Kanban');
		$entries = $listViewModel->getListViewEntries($pagingModel);

		$currency_info = Head_Util_Helper::getUserCurrencyInfo();
		$currency_symbol = $currency_info['currency_symbol'];

		$viewer->assign('SOURCE_MODULE_MODEL', $moduleModel);
		$viewer->assign('SITEURL', $site_URL);
		$viewer->assign('RECORDS', $entries);

		$viewer->assign('DEFAULT_CUSTOM_VIEW_ID', $cvId);
		$viewer->assign('page_count', $listViewCount_page['page']);
		$viewer->assign('RECORD_COUNT', $listViewCount_total);
		$viewer->assign('PAGE_NUMBER', $pageNumber);
		$viewer->assign('PAGING_MODEL', $pagingModel);

		$viewer->view('Shopview.tpl', $request->getModule());
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	function getHeaderScripts(Head_Request $request)
	{
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~libraries/jquery/jquery.cycle.min.js',
			'~libraries/jquery/boxslider/jquery.bxslider.min.js',
			"modules.Head.resources.List",
			"modules.$moduleName.resources.Shopview"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}
}
