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

class Products_ListView_Model extends Head_ListView_Model {

	/**
	 * Function to get the list view entries
	 * @param Head_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Head_Record_Model instance.
	 */
	public function getListViewEntries($pagingModel) {
		$db = PearDatabase::getInstance();

		$moduleName = $this->getModule()->get('name');
		$moduleFocus = CRMEntity::getInstance($moduleName);
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$queryGenerator = $this->get('query_generator');
		$listViewContoller = $this->get('listview_controller');

         $searchParams = $this->get('search_params');
        if(empty($searchParams)) {
            $searchParams = array();
        }
        
        $glue = "";
        if(count($queryGenerator->getWhereFields()) > 0 && (count($searchParams)) > 0) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);
        
		$searchKey = $this->get('search_key');
		$searchValue = $this->get('search_value');
		$operator = $this->get('operator');
		if(!empty($searchKey)) {
			$queryGenerator->addUserSearchConditions(array('search_field' => $searchKey, 'search_text' => $searchValue, 'operator' => $operator));
		}
        
        $orderBy = $this->get('orderby');
		$sortOrder = $this->get('sortorder');
		
        if(!empty($orderBy)){
			$queryGenerator = $this->get('query_generator');
			$fieldModels = $queryGenerator->getModuleFields();
			$orderByFieldModel = $fieldModels[$orderBy];
			if($orderByFieldModel && ($orderByFieldModel->getFieldDataType() == Head_Field_Model::REFERENCE_TYPE ||
					$orderByFieldModel->getFieldDataType() == Head_Field_Model::OWNER_TYPE)){
                $queryGenerator->addWhereField($orderBy);
            }
        }
		$listQuery = $this->getQuery();

		if($this->get('subProductsPopup')){
			$listQuery = $this->addSubProductsQuery($listQuery);
		}

		$sourceModule = $this->get('src_module');
		$sourceField = $this->get('src_field');
		if(!empty($sourceModule)) {
			if(method_exists($moduleModel, 'getQueryByModuleField')) {
				$overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $sourceField, $this->get('src_record'), $listQuery);
				if(!empty($overrideQuery)) {
					$listQuery = $overrideQuery;
				}
			}
		}

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		if(!empty($orderBy) && $orderByFieldModel) {
			$listQuery .= ' ORDER BY '.$queryGenerator->getOrderByColumn($orderBy).' '.$sortOrder;
		} else if(empty($orderBy) && empty($sortOrder)){
			//List view will be displayed on recently created/modified records
			$listQuery .= ' ORDER BY jo_crmentity.modifiedtime DESC';
		}

		$viewid = ListViewSession::getCurrentView($moduleName);
        if(empty($viewid)){
            $viewid = $pagingModel->get('viewid');
        }
        $_SESSION['lvs'][$moduleName][$viewid]['start'] = $pagingModel->get('page');
		ListViewSession::setSessionQuery($moduleName, $listQuery, $viewid);

		//For Products popup in Price Book Related list
		if(($sourceModule !== 'PriceBooks' && $sourceField !== 'priceBookRelatedList')
				&& ($sourceModule !== 'Products' && $sourceField !== 'productsList')) {
			$listQuery .= " LIMIT $startIndex,".($pageLimit+1);
		}

		$listResult = $db->pquery($listQuery, array());
		$listViewRecordModels = array();
		$listViewEntries =  $listViewContoller->getListViewRecords($moduleFocus,$moduleName, $listResult);
		$pagingModel->calculatePageRange($listViewEntries);
        
		if($db->num_rows($listResult) > $pageLimit){
			array_pop($listViewEntries);
			$pagingModel->set('nextPageExists', true);
		}else{
			$pagingModel->set('nextPageExists', false);
		}

		$index = 0;
		foreach($listViewEntries as $recordId => $record) {
			$rawData = $db->query_result_rowdata($listResult, $index++);
			$record['id'] = $recordId;
			$listViewRecordModels[$recordId] = $moduleModel->getRecordFromArray($record, $rawData);
		}
		return $listViewRecordModels;
	}

	public function addSubProductsQuery($listQuery){
		$productId = $this->get('productId');

		$splitQuery = split('WHERE', $listQuery);
		$splitQuery[0] .= " LEFT JOIN jo_seproductsrel ON jo_seproductsrel.crmid = jo_products.productid AND jo_seproductsrel.setype='Products'";
		$splitQuery[1] .= " AND jo_seproductsrel.productid = $productId AND jo_products.discontinued = 1";

		$listQuery = $splitQuery[0]. ' WHERE ' . $splitQuery[1];
		return $listQuery;
	}

	public function getSubProducts($subProductId){
		$flag = false;
		if(!empty($subProductId)){
            $db = PearDatabase::getInstance();
			$query = 'SELECT jo_seproductsrel.crmid from jo_seproductsrel
						INNER JOIN jo_products ON jo_products.productid = jo_seproductsrel.crmid
						INNER JOIN jo_crmentity ON jo_seproductsrel.crmid = jo_crmentity.crmid
						AND jo_crmentity.deleted = 0 AND jo_seproductsrel.setype=?
						WHERE jo_seproductsrel.productid=? AND jo_products.discontinued = 1';
			$result = $db->pquery($query, array($this->getModule()->get('name'), $subProductId ));
			if($db->num_rows($result) > 0){
				$flag = true;
			}
		}
		return $flag;
	}

	/**
	 * Function to get the list view entries
	 * @param Head_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Head_Record_Model instance.
	 */
	public function getListViewCount() {
		$db = PearDatabase::getInstance();

		$queryGenerator = $this->get('query_generator');

        $searchParams = $this->get('search_params');
        if(empty($searchParams)) {
            $searchParams = array();
        }
        
        $glue = "";
        if(count($queryGenerator->getWhereFields()) > 0 && (count($searchParams)) > 0) {
            $glue = QueryGenerator::$AND;
        }
        $queryGenerator->parseAdvFilterList($searchParams, $glue);
        
        $searchKey = $this->get('search_key');
		$searchValue = $this->get('search_value');
		$operator = $this->get('operator');
		if(!empty($searchKey)) {
			$queryGenerator->addUserSearchConditions(array('search_field' => $searchKey, 'search_text' => $searchValue, 'operator' => $operator));
		}
        
        

		$listQuery = $this->getQuery();

		if($this->get('subProductsPopup')){
			$listQuery = $this->addSubProductsQuery($listQuery);
		}

		$sourceModule = $this->get('src_module');
		if(!empty($sourceModule)) {
			$moduleModel = $this->getModule();
			if(method_exists($moduleModel, 'getQueryByModuleField')) {
				$overrideQuery = $moduleModel->getQueryByModuleField($sourceModule, $this->get('src_field'), $this->get('src_record'), $listQuery);
				if(!empty($overrideQuery)) {
					$listQuery = $overrideQuery;
				}
			}
		}
		$position = stripos($listQuery, ' from ');
		if ($position) {
			$split = spliti(' from ', $listQuery);
			$splitCount = count($split);
			$listQuery = 'SELECT count(*) AS count ';
			for ($i=1; $i<$splitCount; $i++) {
				$listQuery = $listQuery. ' FROM ' .$split[$i];
			}
		}

		if($this->getModule()->get('name') == 'Calendar'){
			$listQuery .= ' AND activitytype <> "Emails"';
		}

		$listResult = $db->pquery($listQuery, array());
		return $db->query_result($listResult, 0, 'count');
	}

}
