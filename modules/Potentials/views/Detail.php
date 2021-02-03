<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

class Potentials_Detail_View extends Head_Detail_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('showRelatedRecords');
	}

        function preProcess(Head_Request $request, $display=true) {
                global $adb;
                $viewer = $this->getViewer($request);
                $recordId = $request->get('record');
                $userModel = Users_Record_Model::getCurrentUserModel();

		$inventoryModules = array('Invoice' => array('id' => 'invoiceid', 'table' => 'jo_invoice'),
                        'Quotes' => array('id' => 'quoteid', 'table' => 'jo_quotes'),
                        'SalesOrder' => array('id' => 'salesorderid', 'table' => 'jo_salesorder'),
		);
		$totalValue = array();
                foreach($inventoryModules as $key => $singleModule){
                        $total = 0;
                        $query = $adb->pquery("select total from jo_crmentityrel join {$singleModule['table']} on {$singleModule['id']} = relcrmid where crmid = ? and relmodule = ?", array($recordId, $key));
                        if($adb->num_rows($query) > 0){
                                while($result = $adb->fetch_array($query)){
                                        $total += $result['total'];
                                }
                                $totalValue[$key] = $total;
                        }
                        else{
                                $totalValue[$key] = 0;
                        }
                }

		$getticketCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'HelpDesk');
		$getProducts = getRelatedRecordSumValue($recordId, $request->getModule(), 'Products', 'unit_price');
                $getServicesCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'Services', 'unit_price');

                $totalValue['HelpDesk'] = $getticketCount? $getticketCount : 0;
                $totalValue['Products'] = $getProducts? $getProducts : 0;
		$totalValue['Services'] = $getServicesCount? $getServicesCount : 0;

                $viewer->assign('TOTAL', $totalValue);
                parent::preProcess($request);
        }
	/**
	 * Function to get activities
	 * @param Head_Request $request
	 * @return <List of activity models>
	 */
	public function getActivities(Head_Request $request) {
		$moduleName = 'Calendar';
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if($currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			$moduleName = $request->getModule();
			$recordId = $request->get('record');

			$pageNumber = $request->get('page');
			if(empty ($pageNumber)) {
				$pageNumber = 1;
			}
			$pagingModel = new Head_Paging_Model();
			$pagingModel->set('page', $pageNumber);
			$pagingModel->set('limit', 10);

			if(!$this->record) {
				$this->record = Head_DetailView_Model::getInstance($moduleName, $recordId);
			}
			$recordModel = $this->record->getRecord();
			$moduleModel = $recordModel->getModule();

			$relatedActivities = $moduleModel->getCalendarActivities('', $pagingModel, 'all', $recordId);

			$viewer = $this->getViewer($request);
			$viewer->assign('RECORD', $recordModel);
			$viewer->assign('MODULE_NAME', $moduleName);
			$viewer->assign('PAGING_MODEL', $pagingModel);
			$viewer->assign('PAGE_NUMBER', $pageNumber);
			$viewer->assign('ACTIVITIES', $relatedActivities);

			return $viewer->view('RelatedActivities.tpl', $moduleName, true);
		}
	}
}
