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

class Import_List_View extends Head_Popup_View{
	protected $listViewEntries = false;
	protected $listViewHeaders = false;

	public function  __construct() {
		$this->exposeMethod('getImportDetails');
		$this->exposeMethod('getPageCount');
	}

	public function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
	
		global $current_user;
		$user_id = $current_user->id;
                //menu details
                if(file_exists("menu/sections_".$user_id.".php"))
                        {
                        require( "menu/sections_".$user_id.".php");
                        }
                else
                        {
                        require("menu/default_sections.php");
                        }
                $viewer->assign('SECTION_ARRAY', $section_array); //section names

                if(file_exists("menu/main_menu_".$user_id.".php"))
                        {
                        require( "menu/main_menu_".$user_id.".php");
                        }
                else
                        {
                        require("menu/default_main_menu.php");
                        }
                $viewer->assign('MAIN_MENU_TAB_IDS', $main_menu_array); //main menu

                if(file_exists("menu/module_apps_".$user_id.".php"))
                        {
                        require( "menu/module_apps_".$user_id.".php");
                        }
                else
                        {
                        require("menu/default_module_apps.php");
                        }
                $viewer->assign('APP_MODULE_ARRAY', $app_menu_array); //modules and sections

		$mode = $request->get('mode');
		if(!empty($mode)){
			$this->invokeExposedMethod($mode,$request);
		} else{
			$this->initializeListViewContents($request, $viewer);
			$moduleName = $request->get('for_module');

			$companyDetails = Head_CompanyDetails_Model::getInstanceById();
			$companyLogo = $companyDetails->getLogo();
			$viewer->assign('COMPANY_LOGO',$companyLogo);

			$moduleModel = Head_Module_Model::getInstance($moduleName);
			$viewer->assign('MODULE_MODEL', $moduleModel);
			$viewer->assign('MODULE_NAME', $moduleName);
			$fieldList = $moduleModel->getFields();
			$fieldsInfo = array();
			foreach($fieldList as $name => $model){
				$fieldsInfo[$name] = $model->getFieldInfo();
			}
			$viewer->assign('FIELDS_INFO', json_encode($fieldsInfo));
			$viewer->assign('RELATED_MODULE', $moduleName);

			if($request->isAjax() && ($request->get('_showContents',true) == true)) {
				$viewer->view('PopupContents.tpl', $moduleName);
			} else {
				$viewer->view('Popup.tpl', $moduleName);
			}
		}
	}

	/*
	 * Function to initialize the required data in smarty to display the List View Contents
	 */
	public function initializeListViewContents(Head_Request $request, Head_Viewer $viewer) {
		$moduleName = $request->get('for_module');
		$cvId = $request->get('viewname');
		$pageNumber = $request->get('page');
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		$searchKey = $request->get('search_key');
		$searchValue = $request->get('search_value');
		$searchParams = $request->get('search_params');
		if($sortOrder == "ASC"){
			$nextSortOrder = "DESC";
			$sortImage = "downArrowSmall.png";
		}else{
			$nextSortOrder = "ASC";
			$sortImage = "upArrowSmall.png";
		}

		if(empty ($pageNumber)){
			$pageNumber = '1';
		}

		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$listViewModel = Import_ListView_Model::getInstance($moduleName, $cvId);
		$recordStructureInstance = Head_RecordStructure_Model::getInstanceForModule($moduleModel);

		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('page', $pageNumber);

		if(!empty($orderBy)) {
			$listViewModel->set('orderby', $orderBy);
			$listViewModel->set('sortorder',$sortOrder);
		}
		if(!empty($searchValue)) {
			$listViewModel->set('search_key', $searchKey);
			$listViewModel->set('search_value', $searchValue);
		}

		if(!empty($searchParams)){
			$transformedSearchParams = $this->transferListSearchParamsToFilterCondition($searchParams, $moduleModel);
			$listViewModel->set('search_params',$transformedSearchParams);
		}
		if(!$this->listViewHeaders){
			$this->listViewHeaders = $listViewModel->getListViewHeaders();
		}
		if(!$this->listViewEntries){
			$this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		}
		$noOfEntries = count($this->listViewEntries);
		$viewer->assign('MODULE', $moduleName);

		$viewer->assign('PAGING_MODEL', $pagingModel);
		$viewer->assign('PAGE_NUMBER',$pageNumber);

		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());

		$viewer->assign('ORDER_BY',$orderBy);
		$viewer->assign('SORT_ORDER',$sortOrder);
		$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('COLUMN_NAME',$orderBy);

		$viewer->assign('LISTVIEW_ENTRIES_COUNT',$noOfEntries);
		$viewer->assign('LISTVIEW_HEADERS', $this->listViewHeaders);
		$viewer->assign('LISTVIEW_ENTRIES', $this->listViewEntries);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('POPUP_CLASS_NAME', 'Import_Popup_Js');
	}

	public function getImportDetails(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$user = Users_Record_Model::getCurrentUserModel();
		$importRecords= Import_Data_Action::getImportDetails($user, $request->get('for_module'));
		$viewer->assign('IMPORT_RECORDS', $importRecords);
		$viewer->assign('TYPE',$request->get('type'));
		$viewer->assign('MODULE', $moduleName);
		$viewer->view('ImportDetails.tpl', 'Import');

	}

	/**
	 * Function to get listView count
	 * @param Head_Request $request
	 */
	function getListViewCount(Head_Request $request){
		$moduleName = $request->get('for_module');
		$searchKey = $request->get('search_key');
		$searchValue = $request->get('search_value');

		$listViewModel = Import_ListView_Model::getInstance($moduleName);
		$listViewModel->set('search_key', $searchKey);
		$listViewModel->set('search_value', $searchValue);

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

}
