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

class Contacts_Detail_View extends Accounts_Detail_View {

	function __construct() {
		parent::__construct();
	}

	function preProcess(Head_Request $request, $display=true) {
		global $adb;
		$viewer = $this->getViewer($request);
		$recordId = $request->get('record');
		$userModel = Users_Record_Model::getCurrentUserModel();
		$query = 'select portal_id from jo_masqueradeuserdetails where  record_id=? and masquerade_module= ?';
		$result = $adb->pquery($query, array($recordId, 'Contacts'));
		if ($adb->num_rows($result) > 0) {
			$fetched = $adb->fetch_array($result);
			$portal_user_id = $fetched['portal_id'];
			if($portal_user_id > 0) {
				$mas_status = 'yes';
			} else {
				$mas_status = 'no';
			}
		} else {
			$mas_status = 'no';
		}
		$viewer->assign('mas_status', $mas_status);
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
                $getRelatedDeals = getRelatedRecordSumValue($recordId, $request->getModule(), 'Potentials', 'amount');
                $getTicketCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'HelpDesk');
                $getEventsCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'Calendar');
                $getCallsCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'Calendar');

                $totalValue['Potentials'] = $getRelatedDeals? $getRelatedDeals : 0;
                $totalValue['HelpDesk'] = $getTicketCount? $getTicketCount : 0;
                $totalValue['Events'] = $getEventsCount? $getEventsCount : 0;
                $totalValue['Calendar'] = $getCallsCount? $getCallsCount : 0;

                $viewer->assign('TOTAL', $totalValue);
                parent::preProcess($request);
	}
	public function showModuleDetailView(Head_Request $request) {
		global $adb;
		$recordId = $request->get('record');
		$moduleName = $request->getModule();

		// Getting model to reuse it in parent 
		if (!$this->record) {
			$this->record = Head_DetailView_Model::getInstance($moduleName, $recordId);
		}
		$recordModel = $this->record->getRecord();
		$viewer = $this->getViewer($request);
		$viewer->assign('IMAGE_DETAILS', $recordModel->getImageDetails());

		return parent::showModuleDetailView($request);
	}
}
