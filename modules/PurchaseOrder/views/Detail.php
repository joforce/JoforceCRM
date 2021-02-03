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

class PurchaseOrder_Detail_View extends Inventory_Detail_View {
protected $module;
	protected $focus = null;
	
	function process(Head_Request $request) {
		global $currentModule,$current_user,$root_directory,$adb;
		$this->moduleName= $currentModule;
		$viewer = $this->getViewer($request);
		$id = $request->get('record');
		$this->focus = $focus = CRMEntity::getInstance($this->moduleName);
		
		$focus->retrieve_entity_info($id,$this->moduleName);
		$focus->apply_field_security();
		$focus->id = $id;
		$recordModel = Inventory_Record_Model::getInstanceById($id, $this->moduleName);
			$currencyInfo = $recordModel->getCurrencyInfo();
			$taxes = $recordModel->getProductTaxes();
			$relatedProducts = $recordModel->getProducts();
		$this->associated_products = getAssociatedProducts($this->moduleName,$focus);
		$this->companydetails = $this->getcompanyinfo();
		$this->billingaddress = $this->getbillingaddress();
		$this->duedate =$this->focusColumnValue('duedate', false);
		$this->subject =$this->focusColumnValue('subject', false);
		$this->notes =$this->focusColumnValue('description', false);
		$this->postatus =$this->focusColumnValue('postatus', false);
		$currencySymbol = $this->buildCurrencySymbol();
		$viewer->assign('COMPANY_DETAIL', $this->companydetails);
		$viewer->assign('BILLING_ADDRESS', $this->billingaddress);
		$viewer->assign('DUE_DATE', $this->duedate);
		$viewer->assign('STATUS', $this->postatus);
		$viewer->assign('SUBJECT', $this->subject);
		$viewer->assign('RELATED_PRODUCTS', $relatedProducts);
		$viewer->assign('NOTES',$this->notes);
		$viewer->assign('currencySymbol',$currencySymbol);
		$viewer->assign('RECORDID',$id);
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}

		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		if ($currentUserModel->get('default_record_view') === 'Summary') {
			echo $this->showModuleBasicView($request);
		} else {
			echo $this->showModuleDetailView($request);
		}
	}
	function buildCurrencySymbol() {
		global $adb;
		$currencyId = $this->focus->column_fields['currency_id'];
		if(!empty($currencyId)) {
			$result = $adb->pquery("SELECT currency_symbol FROM jo_currency_info WHERE id=?", array($currencyId));
			return decode_html($adb->query_result($result,0,'currency_symbol'));
		}
		return false;
	}
	public function getbillingaddress() {
		$modelColumnRight =  $this->buildHeaderBillingAddress();
		return $modelColumnRight;
	}
	
	public function buildHeaderBillingAddress() {
		$billPoBox	= $this->focusColumnValues(array('bill_pobox'));
		$billStreet = $this->focusColumnValues(array('bill_street'));
		$billCity	= $this->focusColumnValues(array('bill_city'));
		$billState	= $this->focusColumnValues(array('bill_state'));
		$billCountry = $this->focusColumnValues(array('bill_country'));
		$billCode	=  $this->focusColumnValues(array('bill_code'));
		$address=array('address_1'=>$billPoBox, $billStreet,'address_2'=>$billCity, $billState,'address_3'=>$billCode, $billCountry);
		return $address;
	}

	
	function focusColumnValues($names, $delimeter="\n") {
		if(!is_array($names)) {
			$names = array($names);
		}
		$values = array();
		foreach($names as $name) {
			$value = $this->focusColumnValue($name, false);
			if($value !== false) {
				$values[] = $value;
			}
		}
		return $this->joinValues($values, $delimeter);
	}

	function focusColumnValue($key, $defvalue='') {
		$focus = $this->focus;
		if(isset($focus->column_fields[$key])) {
			return decode_html($focus->column_fields[$key]);
		}
		return $defvalue;
	}

	public function getcompanyinfo(){
		global $adb;
		$result = $adb->pquery("SELECT * FROM jo_organizationdetails", array());
		$num_rows = $adb->num_rows($result);
		if($num_rows) {
			$resultrow = $adb->fetch_array($result);

			$addressValues = array();
			$addressValues[] = $resultrow['address'];
			if(!empty($resultrow['city'])) $addressValues[]= "\n".$resultrow['city'];
			if(!empty($resultrow['state'])) $addressValues[]= ",".$resultrow['state'];
			if(!empty($resultrow['code'])) $addressValues[]= $resultrow['code'];
			if(!empty($resultrow['country'])) $addressValues[]= "\n".$resultrow['country'];

			$additionalCompanyInfo = array();
			if(!empty($resultrow['phone']))		$additionalCompanyInfo[]= "\n".getTranslatedString("Phone: ", $this->moduleName). $resultrow['phone'];
			$websiteCompanyInfo = array();
			if(!empty($resultrow['website']))	$websiteCompanyInfo[]= "\n".getTranslatedString("Website: ", $this->moduleName). $resultrow['website'];
                        if(!empty($resultrow['vatid']))         $additionalCompanyInfo[]= "\n".getTranslatedString("VAT ID: ", $this->moduleName). $resultrow['vatid']; 

			$modelColumnLeft = array(
					'summary' => decode_html($resultrow['organizationname']),
					'address' => decode_html($this->joinValues($addressValues)),
					 'phone'=>decode_html($this->joinValues($additionalCompanyInfo)),'website'=>decode_html($this->joinValues($websiteCompanyInfo))
			);
			return $modelColumnLeft;
		}
	}
	function joinValues($values, $delimeter= "\n") {
		$valueString = '';
		foreach($values as $value) {
			if(empty($value)) continue;
			$valueString .= $value . $delimeter;
		}
		return rtrim($valueString, $delimeter);
	}

}

