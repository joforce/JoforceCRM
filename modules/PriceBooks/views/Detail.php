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

class PriceBooks_Detail_View extends Head_Detail_View {
	
	
	/**
	 * Function returns related records
	 * @param Head_Request $request
	 * @return <type>
	 */
	function showRelatedList(Head_Request $request) {
		$moduleName = $request->getModule();
		$relatedModuleName = $request->get('relatedModule');
		$parentId = $request->get('record');
		$label = $request->get('tab_label');

		$requestedPage = $request->get('page');
		if(empty ($requestedPage)) {
			$requestedPage = 1;
		}

		if($relatedModuleName != "Products"  &&  $relatedModuleName != "Services") {
			return parent::showRelatedList($request);
		}

		$pagingModel = new Head_Paging_Model();
		$pagingModel->set('page',$requestedPage);

		$parentRecordModel = Head_Record_Model::getInstanceById($parentId, $moduleName);
		$relationListView = Head_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);
		$orderBy = $request->get('orderby');
		$sortOrder = $request->get('sortorder');
		if($sortOrder == "ASC") {
			$nextSortOrder = "DESC";
			$sortImage = downsortImage;
			$faSortImage = downfaSortImage;
		} else {
			$nextSortOrder = "ASC";
			$sortImage = upsortImage;
			$faSortImage = upfaSortImage;
		}
		if(!empty($orderBy)) {
			$relationListView->set('orderby', $orderBy);
			$relationListView->set('sortorder',$sortOrder);
		}
		$models = $relationListView->getEntries($pagingModel);
		$links = $relationListView->getLinks();
		$header = $relationListView->getHeaders();
		$noOfEntries = count($models);

		$parentRecordCurrencyId = $parentRecordModel->get('currency_id');
		if ($parentRecordCurrencyId) {
			$relatedModuleModel = Head_Module_Model::getInstance($relatedModuleName);

			foreach ($models as $recordId => $recorModel) {
				$productIdsList[$recordId] = $recordId;
			}
			$unitPricesList = $relatedModuleModel->getPricesForProducts($parentRecordCurrencyId, $productIdsList);

			foreach ($models as $recordId => $recorModel) {
				$recorModel->set('unit_price', $unitPricesList[$recordId]);
			}

			$parentRecordCurrencyDetails = getCurrencySymbolandCRate($parentRecordCurrencyId);
		}

		$moduleFields = $relatedModuleModel->getFields();
		$fieldsInfo = array();
		foreach($moduleFields as $fieldName => $fieldModel){
			$fieldsInfo[$fieldName] = $fieldModel->getFieldInfo();
		}

		$relationModel = $relationListView->getRelationModel();
		$relationField = $relationModel->getRelationField();

		$viewer = $this->getViewer($request);
		$viewer->assign('RELATED_FIELDS_INFO', json_encode($fieldsInfo));
		$viewer->assign('RELATED_RECORDS' , $models);
		$viewer->assign('PARENT_RECORD', $parentRecordModel);
		$viewer->assign('RELATED_LIST_LINKS', $links);
		$viewer->assign('RELATED_HEADERS', $header);
		$viewer->assign('RELATED_MODULE', $relationModel->getRelationModuleModel());
		$viewer->assign('RELATED_ENTIRES_COUNT', $noOfEntries);
		$viewer->assign('RELATION_FIELD', $relationField);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('FASORT_IMAGE',$faSortImage);
		$viewer->assign('DEFAULT_SORT',defaultfaSortImage);

		if ($parentRecordCurrencyDetails) {
			$viewer->assign('PARENT_RECORD_CURRENCY_SYMBOL', $parentRecordCurrencyDetails['symbol']);
		}

		if (PerformancePrefs::getBoolean('LISTVIEW_COMPUTE_PAGE_COUNT', false)) {
			$totalCount = $relationListView->getRelatedEntriesCount();
			$pageLimit = $pagingModel->getPageLimit();
			$pageCount = ceil((int) $totalCount / (int) $pageLimit);

			if($pageCount == 0){
				$pageCount = 1;
			}
			$viewer->assign('PAGE_COUNT', $pageCount);
			$viewer->assign('TOTAL_ENTRIES', $totalCount);
			$viewer->assign('PERFORMANCE', true);
		}

		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('PAGING', $pagingModel);
		$viewer->assign('ORDER_BY',$orderBy);
		$viewer->assign('SORT_ORDER',$sortOrder);
		$viewer->assign('NEXT_SORT_ORDER',$nextSortOrder);
		$viewer->assign('SORT_IMAGE',$sortImage);
		$viewer->assign('COLUMN_NAME',$orderBy);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('TAB_LABEL', $request->get('tab_label'));
		
		return $viewer->view('RelatedList.tpl', $moduleName, 'true');
	}
}
