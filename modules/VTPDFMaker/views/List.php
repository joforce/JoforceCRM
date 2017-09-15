<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class VTPDFMaker_List_View extends Head_Index_View {
	protected $listViewEntries = false;
	protected $listViewCount = false;
	protected $listViewLinks = false;
	protected $listViewHeaders = false;
	protected $noOfEntries = false;
	protected $pagingModel = false;
	protected $listViewModel = false;
	function __construct() {
		parent::__construct();
	}

	function preProcess(Head_Request $request, $display=true) {
		parent::preProcess($request, false);

		$moduleName = $request->getModule();
		$customView = new CustomView();
		if($customView->isPermittedCustomView($request->get('viewname'), 'List', $moduleName) != 'yes') {
			$viewName = $customView->getViewIdByName('All', $moduleName);
			$request->set('viewname', $viewName);
			$_REQUEST['viewname'] = $viewName;
		}

		$viewer = $this->getViewer($request);
		$cvId = $this->viewName;

		if(!$cvId) {
			$customView = new CustomView();
			$cvId = $customView->getViewId($moduleName);
		}
		$listHeaders = $request->get('list_headers', array());
		$tag = $request->get('tag');

		$listViewSessionKey = $moduleName.'_'.$cvId;
		if(!empty($tag)) {
			$listViewSessionKey .='_'.$tag;
		}

		$orderParams = Head_ListView_Model::getSortParamsSession($listViewSessionKey);

		if(empty($listHeaders)) {
			$listHeaders = $orderParams['list_headers'];
		}

		$this->listViewModel = VTPDFMaker_ListView_Model::getInstance($moduleName, $cvId, $listHeaders);
		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'));
		$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($moduleName));
		$this->viewName = $request->get('viewname');
		if(empty($this->viewName)){
			//If not view name exits then get it from custom view
			//This can return default view id or view id present in session
			$customView = new CustomView();
			$this->viewName = $customView->getViewId($moduleName);
		}

		$quickLinkModels = $this->listViewModel->getSideBarLinks($linkParams);
		$viewer->assign('QUICK_LINKS', $quickLinkModels);
		$this->initializeListViewContents($request, $viewer);
		$viewer->assign('VIEWID', $this->viewName);
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$viewer->assign('MODULE_MODEL', $moduleModel);

		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Head_Request $request) {
                $viewer = $this->getViewer ($request);
                $moduleName = $request->getModule();
                $moduleModel = Head_Module_Model::getInstance($moduleName);

                $viewer->assign('IoncubeIsAvailable', "true" );

                if(!$moduleModel->checkIonCubeLoaded())
                {
                        $viewer->assign('IoncubeIsAvailable', "false" );
                }

		return 'ListViewPreProcess.tpl';
	}

	//Note : To get the right hook for immediate parent in PHP,
	// specially in case of deep hierarchy
	/*function preProcessParentTplName(Head_Request $request) {
		return parent::preProcessTplName($request);
	}*/

	protected function preProcessDisplay(Head_Request $request) {
		parent::preProcessDisplay($request);
	}


	function process (Head_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
                if(!$moduleModel->checkIonCubeLoaded())
                {
                        $viewer->view('IoncubeNotAvailable.tpl', "VTPDFMaker");
                }
                else if(!file_exists('modules/VTPDFMaker/mpdf/mpdf.php')){
                        $viewer->view('MPDFNotExists.tpl', "VTPDFMaker");
                }
                else {

		$viewName = $request->get('viewname');
		if(!empty($viewName)) {
			$this->viewName = $viewName;
		}

		$this->initializeListViewContents($request, $viewer);
		$this->assignCustomViews($request,$viewer);
		$viewer->assign('VIEW', $request->get('view'));
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('RECORD_ACTIONS', $this->getRecordActionsFromModule($moduleModel));
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('ListViewContents.tpl', $moduleName);
		}
	}

	function postProcess(Head_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();

		$viewer->view('ListViewPostProcess.tpl', $moduleName);
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
			'modules.Head.resources.List',
			"modules.$moduleName.resources.List",
			'modules.Head.resources.ListSidebar',
			"modules.$moduleName.resources.ListSidebar",
			'modules.CustomView.resources.CustomView',
			"modules.$moduleName.resources.CustomView",
			"libraries.jquery.ckeditor.ckeditor",
			"libraries.jquery.ckeditor.adapters.jquery",
			"modules.Head.resources.CkEditor",
			//for vtiger7 
			"modules.Head.resources.MergeRecords",
			"~layouts/v7/lib/jquery/Lightweight-jQuery-In-page-Filtering-Plugin-instaFilta/instafilta.min.js",
			'modules.Head.resources.Tag',
			"~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/jquery/floatThead/jquery.floatThead.js",
			"~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/jquery/perfect-scrollbar/js/perfect-scrollbar.jquery.js"
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Head_Request $request, Head_Viewer $viewer) {
		$moduleName = $request->getModule();
		$cvId = $this->viewName;
		$pageNumber = $request->get('page');
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		$searchKey = $request->get('search_key');
		$searchValue = $request->get('search_value');
		$operator = $request->get('operator');
		$searchParams = $request->get('search_params');
		$tagParams = $request->get('tag_params');
		$starFilterMode = $request->get('starFilterMode');
		$listHeaders = $request->get('list_headers', array());
		$tag = $request->get('tag');
		$requestViewName = $request->get('viewname');
		$tagSessionKey = $moduleName.'_TAG';

		if(!empty($requestViewName) && empty($tag)) {
			unset($_SESSION[$tagSessionKey]);
		}

		if(empty($tag)) {   
			$tagSessionVal = Head_ListView_Model::getSortParamsSession($tagSessionKey);
			if(!empty($tagSessionVal)) {
				$tag = $tagSessionVal;
			}
		}else{
			Head_ListView_Model::setSortParamsSession($tagSessionKey, $tag);
		}

		$listViewSessionKey = $moduleName.'_'.$cvId;
		if(!empty($tag)) {
			$listViewSessionKey .='_'.$tag;
		}

		if(empty($cvId)) {
			$customView = new CustomView();
			$cvId = $customView->getViewId($moduleName);
		}

		$orderParams = Head_ListView_Model::getSortParamsSession($listViewSessionKey);
		if($request->get('mode') == 'removeAlphabetSearch') {
			Head_ListView_Model::deleteParamsSession($listViewSessionKey, array('search_key', 'search_value', 'operator'));
			$searchKey = '';
			$searchValue = '';
			$operator = '';
		}
		if($request->get('mode') == 'removeSorting') {
			Head_ListView_Model::deleteParamsSession($listViewSessionKey, array('orderby', 'sortorder'));
			$orderBy = '';
			$sortOrder = '';
		}
		if(empty($listHeaders)) {
			$listHeaders = $orderParams['list_headers'];
		}

		 if(!empty($tag) && empty($tagParams)){
			$tagParams = $orderParams['tag_params'];
		}

		if(empty($orderBy) && empty($searchValue) && empty($pageNumber)) {
			if($orderParams) {
				$pageNumber = $orderParams['page'];
				$orderBy = $orderParams['orderby'];
				$sortOrder = $orderParams['sortorder'];
				$searchKey = $orderParams['search_key'];
				$searchValue = $orderParams['search_value'];
				$operator = $orderParams['operator'];
				if(empty($searchParams)) {
					$searchParams = $orderParams['search_params']; 
				}

				if(empty($starFilterMode)) {
					$starFilterMode = $orderParams['star_filter_mode'];
				}
			}
		} else if($request->get('nolistcache') != 1) {
			$params = array('page' => $pageNumber, 'orderby' => $orderBy, 'sortorder' => $sortOrder, 'search_key' => $searchKey,
				'search_value' => $searchValue, 'operator' => $operator, 'tag_params' => $tagParams,'star_filter_mode'=> $starFilterMode,'search_params' =>$searchParams);

			if(!empty($listHeaders)) {
				$params['list_headers'] = $listHeaders;
			}
			Head_ListView_Model::setSortParamsSession($listViewSessionKey, $params);
		}
		if($sortOrder == "ASC"){
			$nextSortOrder = "DESC";
			$sortImage = "icon-chevron-down";
			$faSortImage = "fa-sort-desc";
		}else{
			$nextSortOrder = "ASC";
			$sortImage = "icon-chevron-up";
			$faSortImage = "fa-sort-asc";
		}

		if(empty ($pageNumber)){
			$pageNumber = '1';
		}

		if(!$this->listViewModel) {
			$listViewModel = VTPDFMaker_ListView_Model::getInstance($moduleName, $cvId, $listHeaders);
		} else {
			$listViewModel = $this->listViewModel;
		}
		$currentUser = Users_Record_Model::getCurrentUserModel();

		$linkParams = array('MODULE'=>$moduleName, 'ACTION'=>$request->get('view'), 'CVID'=>$cvId);
		$linkModels = $listViewModel->getListViewMassActions($linkParams);

		// preProcess is already loading this, we can reuse
		if(!$this->pagingModel){
			$pagingModel = new Head_Paging_Model();
			$pagingModel->set('page', $pageNumber);
			$pagingModel->set('viewid', $request->get('viewname'));
		} else{
			$pagingModel = $this->pagingModel;
		}

		if(!empty($orderBy)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder',$sortOrder);
		}

		if(!empty($operator)) {
			$listViewModel->set('operator', $operator);
			$viewer->assign('OPERATOR',$operator);
			$viewer->assign('ALPHABET_VALUE',$searchValue);
		}
		if(!empty($searchKey) && !empty($searchValue)) {
			$listViewModel->set('search_key', $searchKey);
			$listViewModel->set('search_value', $searchValue);
		}

		if(empty($searchParams)) {
			$searchParams = array();
		}
		if(count($searchParams) == 2 && empty($searchParams[1])) {
			unset($searchParams[1]);
		}

		if(empty($tagParams)){
			$tagParams = array();
		}

		$searchAndTagParams = array_merge($searchParams, $tagParams);

		$transformedSearchParams = $this->transferListSearchParamsToFilterCondition($searchAndTagParams, $listViewModel->getModule());
		$listViewModel->set('search_params',$transformedSearchParams);


		//To make smarty to get the details easily accesible
		foreach($searchParams as $fieldListGroup){
			foreach($fieldListGroup as $fieldSearchInfo){
				$fieldSearchInfo['searchValue'] = $fieldSearchInfo[2];
				$fieldSearchInfo['fieldName'] = $fieldName = $fieldSearchInfo[0];
				$fieldSearchInfo['comparator'] = $fieldSearchInfo[1];
				$searchParams[$fieldName] = $fieldSearchInfo;
			}
		}

		foreach($tagParams as $fieldListGroup){
			foreach($fieldListGroup as $fieldSearchInfo){
				$fieldSearchInfo['searchValue'] = $fieldSearchInfo[2];
				$fieldSearchInfo['fieldName'] = $fieldName = $fieldSearchInfo[0];
				$fieldSearchInfo['comparator'] = $fieldSearchInfo[1];
				$tagParams[$fieldName] = $fieldSearchInfo;
			}
		}

		if(!$this->listViewHeaders){
			$this->listViewHeaders = $listViewModel->getListViewHeaders();
		}

		if(!$this->listViewEntries){
			$this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		}
		//if list view entries restricted to show, paging should not fail
		if(!$this->noOfEntries) {
//			$this->noOfEntries = $pagingModel->get('_listcount');
                $this->noOfEntries = count($this->listViewEntries);

		}
//var_dump($this->noOfEntries);die;
		if(!$this->noOfEntries) {
			$noOfEntries = count($this->listViewEntries);
		} else {
			$noOfEntries = $this->noOfEntries;
		}
		$viewer->assign('MODULE', $moduleName);

		if(!$this->listViewLinks){
			$this->listViewLinks = $listViewModel->getListViewLinks($linkParams);
		}
		$viewer->assign('LISTVIEW_LINKS', $this->listViewLinks);

		$viewer->assign('LISTVIEW_MASSACTIONS', $linkModels['LISTVIEWMASSACTION']);

		$viewer->assign('PAGING_MODEL', $pagingModel);
		if(!$this->pagingModel){
			$this->pagingModel = $pagingModel;
		}
		$viewer->assign('PAGE_NUMBER',$pageNumber);

		if(!$this->moduleFieldStructure) {
			$recordStructure = Head_RecordStructure_Model::getInstanceForModule($listViewModel->getModule(), Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
			$this->moduleFieldStructure = $recordStructure->getStructure();   
		}

		if(!$this->tags) {
			$this->tags = Head_Tag_Model::getAllAccessible($currentUser->id, $moduleName);
		}
		if(!$this->allUserTags) {
			$this->allUserTags = Head_Tag_Model::getAllUserTags($currentUser->getId());
		}

		$listViewController = $listViewModel->get('listview_controller');
//		$selectedHeaderFields = $listViewController->getListViewHeaderFields();

		$viewer->assign('ORDER_BY',$orderBy);
		$viewer->assign('SORT_ORDER',$sortOrder);
		$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('FASORT_IMAGE',$faSortImage);
		$viewer->assign('COLUMN_NAME',$orderBy);
		$viewer->assign('VIEWNAME',$this->viewName);

		$viewer->assign('LISTVIEW_ENTRIES_COUNT',$noOfEntries);
		$viewer->assign('LISTVIEW_HEADERS', $this->listViewHeaders);
		$viewer->assign('LIST_HEADER_FIELDS', json_encode(array_keys($this->listViewHeaders)));
		$viewer->assign('LISTVIEW_ENTRIES', $this->listViewEntries);
		$viewer->assign('MODULE_FIELD_STRUCTURE', $this->moduleFieldStructure);
//		$viewer->assign('SELECTED_HEADER_FIELDS', $selectedHeaderFields);
		$viewer->assign('TAGS', $this->tags);
		$viewer->assign('ALL_USER_TAGS', $this->allUserTags);
		$viewer->assign('ALL_CUSTOMVIEW_MODEL', CustomView_Record_Model::getAllFilterByModule($moduleName));
		$viewer->assign('CURRENT_TAG',$tag);
		$appName = $request->get('app');
		if(!empty($appName)){
			$viewer->assign('SELECTED_MENU_CATEGORY',$appName);
		}
		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			if(!$this->listViewCount){
				$this->listViewCount = $listViewModel->getListViewCount();
			}
			$totalCount = $this->listViewCount;
			$pageLimit = $pagingModel->getPageLimit();
			$pageCount = ceil((int) $totalCount / (int) $pageLimit);

			if($pageCount == 0){
				$pageCount = 1;
			}
			$viewer->assign('PAGE_COUNT', $pageCount);
			$viewer->assign('LISTVIEW_COUNT', $totalCount);
		}
		$viewer->assign('LIST_VIEW_MODEL', $listViewModel);
		$viewer->assign('GROUPS_IDS', Head_Util_Helper::getGroupsIdsForUsers($currentUser->getId()));
		$viewer->assign('IS_CREATE_PERMITTED', $listViewModel->getModule()->isPermitted('CreateView'));
		$viewer->assign('IS_MODULE_EDITABLE', $listViewModel->getModule()->isPermitted('EditView'));
		$viewer->assign('IS_MODULE_DELETABLE', $listViewModel->getModule()->isPermitted('Delete'));
		$viewer->assign('SEARCH_DETAILS', $searchParams);
		$viewer->assign('TAG_DETAILS', $tagParams);
		$viewer->assign('NO_SEARCH_PARAMS_CACHE', $request->get('nolistcache'));
		$viewer->assign('STAR_FILTER_MODE',$starFilterMode);
		$viewer->assign('VIEWID', $cvId);
		//Head7
		$viewer->assign('REQUEST_INSTANCE',$request);

		//vtiger7
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if($moduleModel->isQuickPreviewEnabled()){
			$viewer->assign('QUICK_PREVIEW_ENABLED', 'true');
		}

		$picklistDependencyDatasource = Head_DependencyPicklist::getPicklistDependencyDatasource($moduleName);
		$viewer->assign('PICKIST_DEPENDENCY_DATASOURCE',Zend_Json::encode($picklistDependencyDatasource));
	}

	protected function assignCustomViews(Head_Request $request, Head_Viewer $viewer) {
		$allCustomViews = CustomView_Record_Model::getAllByGroup($request->getModule());
		if (!empty($allCustomViews)) {
			$viewer->assign('CUSTOM_VIEWS', $allCustomViews);
			$currentCVSelectedFields = array();
			foreach ($allCustomViews as $cat => $views) {
				foreach ($views as $viewModel) {
					if ($viewModel->getId() === $viewer->get_template_vars('VIEWID')) {
						$currentCVSelectedFields = $viewModel->getSelectedFields();
						$viewer->assign('CURRENT_CV_MODEL', $viewModel);
						break;
					}
				}
			}
		}
	}

	/**
	 * Function returns the number of records for the current filter
	 * @param Head_Request $request
	 */
	function getRecordsCount(Head_Request $request) {
		$moduleName = $request->getModule();
		$cvId = $request->get('viewname');
		$count = $this->getListViewCount($request);

		$result = array();
		$result['module'] = $moduleName;
		$result['viewname'] = $cvId;
		$result['count'] = $count;

		$response = new Head_Response();
		$response->setEmitType(Head_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Function to get listView count
	 * @param Head_Request $request
	 */
	function getListViewCount(Head_Request $request){
		$moduleName = $request->getModule();
		$cvId = $request->get('viewname');
		if(empty($cvId)) {
			$cvId = '0';
		}

		$searchKey = $request->get('search_key');
		$searchValue = $request->get('search_value');
		$tagParams = $request->get('tag_params');

		$listViewModel = VTPDFMaker_ListView_Model::getInstance($moduleName, $cvId);

		if(empty($tagParams)){
			$tagParams = array();
		}

		$searchParams = $request->get('search_params');
		if(empty($searchParams) && !is_array($searchParams)){
			$searchParams = array();
		}
		$searchAndTagParams = array_merge($searchParams, $tagParams);

		$listViewModel->set('search_params',$this->transferListSearchParamsToFilterCondition($searchAndTagParams, $listViewModel->getModule()));

		$listViewModel->set('search_key', $searchKey);
		$listViewModel->set('search_value', $searchValue);
		$listViewModel->set('operator', $request->get('operator'));

		// for Documents folders we should filter with folder id as well
		$folder_value = $request->get('folder_value');
		if(!empty($folder_value)){
			$listViewModel->set('folder_id',$request->get('folder_id'));
			$listViewModel->set('folder_value',$folder_value);
		}

		$count = $listViewModel->getListViewCount();

		return $count;
	}



	/**
	 * Function to get the page count for list
	 * @return total number of pages
	 */
	function getPageCount(Head_Request $request){
		$listViewCount = $this->getListViewCount($request);
		$pagingModel = new Head_Paging_Model();
		$pageLimit = $pagingModel->getPageLimit();
		$pageCount = ceil((int) $listViewCount / (int) $pageLimit);

		if($pageCount == 0){
			$pageCount = 1;
		}
		$result = array();
		$result['page'] = $pageCount;
		$result['numberOfRecords'] = $listViewCount;
		$response = new Head_Response();
		$response->setResult($result);
		$response->emit();
	}


	public function transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel) {
		return Head_Util_Helper::transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel);
	}

	public function getHeaderCss(Head_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);
		$cssFileNames = array(
			"~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/jquery/perfect-scrollbar/css/perfect-scrollbar.css",
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);
		return $headerCssInstances;
	}

	public function getRecordActionsFromModule($moduleModel) {
		$editPermission = $deletePermission = 0;
		if ($moduleModel) {
			$editPermission	= $moduleModel->isPermitted('EditView');
			$deletePermission = $moduleModel->isPermitted('Delete');
		}

		$recordActions = array();
		$recordActions['edit'] = $editPermission;
		$recordActions['delete'] = $deletePermission;

		return $recordActions;
	}
}
