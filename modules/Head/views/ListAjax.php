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

class Head_ListAjax_View extends Head_List_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('getListViewCount');
		$this->exposeMethod('getRecordsCount');
		$this->exposeMethod('getPageCount');
		$this->exposeMethod('showSearchResults');
		$this->exposeMethod('ShowListColumnsEdit');
		$this->exposeMethod('showSearchResultsWithValue');
		$this->exposeMethod('searchAll');
		$this->exposeMethod('getpicklist');
		$this->exposeMethod('globalSearchAll');
	}

	function preProcess(Head_Request $request) {
		return true;
	}

	function postProcess(Head_Request $request) {
		return true;
	}

	function process(Head_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	function getFieldsHTML($fields, $sel_module) {
		$fields_html = '';

		foreach($fields as $fieldname => $fieldmodel) {
			$fields_html .= "<option class='role2fieldnames_{$fieldname}' value='{$fieldname}'>";
			$fields_html .= vtranslate($fieldmodel->label,$sel_module);
			$fields_html .= "</option>";
		}
		return $fields_html;
	}
	
	function getpicklist(Head_Request $request){
		$model = new Settings_Pipeline_Module_Model();
		$mode = $request->get('mode');
		$moduleName = $request->getModule();
			$qualifiedModuleName = $request->getModule(false);
		$user_id = $current_user->id;
	
		$response = new Head_Response();
		if($mode == 'getpicklist') {
			$selected_module = $request->get('moduleName');
			$html = $model->getPicklistOfModule($selected_module);
	
			$sourceModuleModel = Head_Module_Model::getInstance($selected_module);
			$fields = $model->getModuleFieldsWithNameFields($selected_module);
			$fields_html = $this->getFieldsHTML($fields, $selected_module);
	 
			$response->setResult(array('success' => true, 'picklists'=> $html, 'fields' => $fields_html));
		} 
		$response->emit();
	}

	public function showSearchResults(Head_Request $request) {
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$listMode = $request->get('listMode');
		if(!empty($listMode)) {
			$request->set('mode', $listMode);
		}

		$customView = new CustomView();
		$this->viewName = $customView->getViewIdByName('All', $moduleName);

		$this->initializeListViewContents($request, $viewer);
		$viewer->assign('VIEW', $request->get('view'));
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('RECORD_ACTIONS', $this->getRecordActionsFromModule($moduleModel));
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$moduleFields = $moduleModel->getFields();
		$fieldsInfo = array();
		foreach($moduleFields as $fieldName => $fieldModel){
			$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
		}
		$viewer->assign('ADV_SEARCH_FIELDS_INFO', json_encode($fieldsInfo));
		if($request->get('_onlyContents',false)){
			$viewer->view('UnifiedSearchResultsContents.tpl',$moduleName);
		}else{
			$viewer->view('UnifiedSearchResults.tpl', $moduleName);
		}
	}

	public function ShowListColumnsEdit(Head_Request $request){
		$viewer = $this->getViewer ($request);
		$moduleName = $request->getModule();
		$cvId = $request->get('cvid');
		$cvModel = CustomView_Record_Model::getInstanceById($cvId);

		$moduleModel = Head_Module_Model::getInstance($request->get('source_module'));
		$recordStructureModel = Head_RecordStructure_Model::getInstanceForModule($moduleModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
		$recordStructure = $recordStructureModel->getStructure();

		$cvSelectedFields = $cvModel->getSelectedFields();

		$cvSelectedFieldModelsMapping = array();
		foreach ($recordStructure as $blockFields) {
			foreach ($blockFields as $field) {
				$cvSelectedFieldModelsMapping[$field->getCustomViewColumnName()] = $field;
			}
		}

		$selectedFields = array();
		foreach ($cvSelectedFields as $cvFieldName) {
			$selectedFields[$cvFieldName] = $cvSelectedFieldModelsMapping[$cvFieldName];
		}

		$viewer->assign('CV_MODEL',$cvModel);
		$viewer->assign('RECORD_STRUCTURE',$recordStructure);
		$viewer->assign('SELECTED_FIELDS',$selectedFields);
		$viewer->assign('MODULE',$moduleName);
		$viewer->view('ListColumnsEdit.tpl',$moduleName);
	}

	public function globalSearchAll(Head_Request $request, $for_mobile = false) {
		$moduleName = $request->getModule();
		$searchValue = $request->get('value');
		$searchModule = $request->get('modulename');
		$searchField = $request->get('field');
		$searchCondition = $request->get('condition');

		$range = array();
		$range['start'] = 0;

		$pageLimit = $this->getGlobalSearchPageLimit();
		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('range', $range);
		$pagingModel->set('limit', $pageLimit-1);

		// For Mobile API, search for specific module
		// if(!empty($searchModule))   {
		//     $matchingRecords = [];
	    //         $searchableModules = Head_Module_Model::getSearchableModules();
        // 	    if(array_key_exists($searchModule, $searchableModules)) {
        //         	$searchedRecords = Head_Record_Model::getSearchResult($searchValue, $searchModule);
	    //             $matchingRecords[$searchModule] = isset($searchedRecords[$searchModule]) ? $searchedRecords[$searchModule] : [];
        // 	    }
	    //         return $matchingRecords;
        // 	}

		$searchableModules = Head_Module_Model::getSearchableModules();
		$matchingRecords = array();
		if(array_key_exists($searchModule, $searchableModules)) {
			$searchedRecords = Head_Record_Model::getGlobalSearchResult($searchValue, $searchModule, $searchField, $searchCondition);
			if ($searchedRecords[$searchModule]) {
				$matchingRecords[$searchModule] = $searchedRecords[$searchModule];
			}
		}

		// Return if call is from Mobile API
		if($for_mobile)
		    return $matchingRecords;

		$matchingRecordsList = array();

		foreach ($matchingRecords as $module => $recordModelsList) {
			$recordsCount = count($recordModelsList);
			$recordModelsList = array_keys($recordModelsList);
			$recordModelsList = array_slice($recordModelsList, 0, $pageLimit);

			$customView = new CustomView();
			$cvId = $customView->getViewIdByName('All', $module);

			$listViewModel = Head_ListView_Model::getInstance($module, $cvId);
			$listViewModel->listViewHeaders = $listViewModel->getListViewHeaders();
			$listViewModel->set('pageNumber', 1);

			$listviewPagingModel = clone $pagingModel;
			$listviewPagingModel->calculatePageRange($recordModelsList);
			$listViewModel->pagingModel = $listviewPagingModel;
			$listViewModel->recordsCount = $recordsCount;

			if (count($recordModelsList) == $pageLimit) {
				array_pop($recordModelsList);
			}

			$listViewEntries = array();
			foreach ($recordModelsList as $recordId) {
				$recordModel = Head_Record_Model::getInstanceById($recordId, $listViewModel->getModule());
				$recordModel->setRawData($recordModel->getData());

				foreach ($listViewModel->listViewHeaders as $fieldName => $fieldModel) {
					$recordModel->set($fieldName, $fieldModel->getDisplayValue($recordModel->get($fieldName)));
				}
				$listViewModel->listViewEntries[$recordId] = $recordModel;
			}
			$matchingRecordsList[$module] = $listViewModel;
		}

		$viewer = $this->getViewer($request);
		$viewer->assign('SEARCH_VALUE', $searchValue);
		$viewer->assign('PAGE_NUMBER', 1);
		$viewer->assign('MATCHING_RECORDS', $matchingRecordsList);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());

		// $sourceModuleModel = Head_Module_Model::getInstance($searchValue);
		// $model = new Settings_Pipeline_Module_Model();
	    // $fields = $model->getModuleFieldsWithNameFields($searchValue);
		// $viewer->assign('MODULE_FIELDS', $fields);

		// $recordStructureModel = Head_RecordStructure_Model::getInstanceForModule($sourceModuleModel, Head_RecordStructure_Model::RECORD_STRUCTURE_MODE_FILTER);
		// $recordStructure = $recordStructureModel->getStructure();
		// $viewer->assign('RECORD_STRUCTURE',$recordStructure);
		// $viewer->assign('MODULE',$searchValue);

		echo $viewer->view('SearchResults.tpl', '', true);
	}

	public function searchAll(Head_Request $request, $for_mobile = false) {
		$moduleName = $request->getModule();
		$searchValue = $request->get('value');
		$searchModule = $request->get('searchModule');

		$range = array();
		$range['start'] = 0;

		$pageLimit = $this->getGlobalSearchPageLimit();
		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('range', $range);
		$pagingModel->set('limit', $pageLimit-1);

		// For Mobile API, search for specific module
		if(!empty($searchModule))   {
		    $matchingRecords = [];
	            $searchableModules = Head_Module_Model::getSearchableModules();
        	    if(array_key_exists($searchModule, $searchableModules)) {
                	$searchedRecords = Head_Record_Model::getSearchResult($searchValue, $searchModule);
	                $matchingRecords[$searchModule] = isset($searchedRecords[$searchModule]) ? $searchedRecords[$searchModule] : [];
        	    }
	            return $matchingRecords;
        	}

		$searchableModules = Head_Module_Model::getSearchableModules();
		$matchingRecords = array();
		foreach ($searchableModules as $searchModule => $searchModuleModel) {
			$searchedRecords = Head_Record_Model::getSearchResult($searchValue, $searchModule);
			if ($searchedRecords[$searchModule]) {
				$matchingRecords[$searchModule] = $searchedRecords[$searchModule];
			}
		}

		// Return if call is from Mobile API
		if($for_mobile)
		    return $matchingRecords;

		$matchingRecordsList = array();
		foreach ($matchingRecords as $module => $recordModelsList) {
			$recordsCount = count($recordModelsList);
			$recordModelsList = array_keys($recordModelsList);
			$recordModelsList = array_slice($recordModelsList, 0, $pageLimit);

			$customView = new CustomView();
			$cvId = $customView->getViewIdByName('All', $module);

			$listViewModel = Head_ListView_Model::getInstance($module, $cvId);
			$listViewModel->listViewHeaders = $listViewModel->getListViewHeaders();
			$listViewModel->set('pageNumber', 1);

			$listviewPagingModel = clone $pagingModel;
			$listviewPagingModel->calculatePageRange($recordModelsList);
			$listViewModel->pagingModel = $listviewPagingModel;
			$listViewModel->recordsCount = $recordsCount;

			if (count($recordModelsList) == $pageLimit) {
				array_pop($recordModelsList);
			}

			$listViewEntries = array();
			foreach ($recordModelsList as $recordId) {
				$recordModel = Head_Record_Model::getInstanceById($recordId, $listViewModel->getModule());
				$recordModel->setRawData($recordModel->getData());

				foreach ($listViewModel->listViewHeaders as $fieldName => $fieldModel) {
					$recordModel->set($fieldName, $fieldModel->getDisplayValue($recordModel->get($fieldName)));
				}
				$listViewModel->listViewEntries[$recordId] = $recordModel;
			}
			$matchingRecordsList[$module] = $listViewModel;
		}

		$viewer = $this->getViewer($request);
		$viewer->assign('SEARCH_VALUE', $searchValue);
		$viewer->assign('PAGE_NUMBER', 1);
		$viewer->assign('MATCHING_RECORDS', $matchingRecordsList);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());

		echo $viewer->view('SearchResults.tpl', '', true);
	}

	public function showSearchResultsWithValue(Head_Request $request) {
		$moduleName = $request->getModule();
		$pageNumber = $request->get('page');
		$searchValue = $request->get('value');
		$recordsCount = $request->get('recordsCount');

		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$nameFields = $moduleModel->getNameFields();
		$params = array();
		foreach ($nameFields as $fieldName) {
			$params[] = array($fieldName, 'c', $searchValue);
		}
		$searchParams[] = array();
		$searchParams[] = $params;
		$request->set('search_params', $searchParams);
		$request->set('orderby', $moduleModel->basetableid);

		$pageLimit = $this->getGlobalSearchPageLimit();
		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('limit', $pageLimit-1);
		$pagingModel->set('page', $pageNumber);

		$range = array();
		$previousPageRecordCount = (($pageNumber-1)*$pageLimit);
		$range['start'] = $previousPageRecordCount+1;
		$range['end'] = $previousPageRecordCount+$pageLimit;
		$pagingModel->set('range', $range);
		$this->pagingModel = $pagingModel;

		$customView = new CustomView();
		$this->viewName = $customView->getViewIdByName('All', $moduleName);

		$viewer = $this->getViewer($request);
		$this->initializeListViewContents($request, $viewer);

		$viewer->assign('VIEW', $request->get('view'));
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('RECORDS_COUNT', $recordsCount);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('ModuleSearchResults.tpl', $moduleName);
	}

	public function getGlobalSearchPageLimit() {
		return 11;
	}
}
