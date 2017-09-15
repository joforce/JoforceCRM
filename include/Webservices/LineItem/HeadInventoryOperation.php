<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

require_once 'include/Webservices/HeadModuleOperation.php';
require_once 'include/Webservices/Utils.php';

/**
 * Description of HeadInventoryOperation
 */
class HeadInventoryOperation extends HeadModuleOperation {

	public function create($elementType, $element) {
		$element = $this->sanitizeInventoryForInsert($element);
		$element = $this->sanitizeShippingTaxes($element);
		$lineItems = $element['LineItems'];
		if (!empty($lineItems)) {
			$eventManager = new VTEventsManager(vglobal('adb'));
			$this->triggerBeforeSaveEvents($element, $eventManager);

			$currentBulkSaveMode = vglobal('VTIGER_BULK_SAVE_MODE');
			if ($currentBulkSaveMode === NULL) {
				$currentBulkSaveMode = false;
			}
			vglobal('VTIGER_BULK_SAVE_MODE', true);

			$element = parent::create($elementType, $element);
			$focus = CRMEntity::getInstance($elementType);
			$focus->updateMissingSeqNumber($elementType);

			vglobal('VTIGER_BULK_SAVE_MODE', $currentBulkSaveMode);

			$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
			$handler->setLineItems('LineItem', $lineItems, $element);
            $parent = $handler->getParentById($element['id']);
			$handler->updateParent($lineItems, $parent);
            $updatedParent = $handler->getParentById($element['id']);
            //since subtotal and grand total is updated in the update parent api
            $parent['hdnSubTotal'] = $updatedParent['hdnSubTotal'];
            $parent['hdnGrandTotal'] = $updatedParent['hdnGrandTotal'];
            $parent['pre_tax_total'] = $updatedParent['pre_tax_total'];
            $components = vtws_getIdComponents($element['id']);
            $parentId = $components[1];
            $parent['LineItems'] = $handler->getAllLineItemForParent($parentId);

			$currentValue = vglobal('updateInventoryProductRel_deduct_stock');
			vglobal('updateInventoryProductRel_deduct_stock', false);

			$this->triggerAfterSaveEvents($parent, $eventManager);

			vglobal('updateInventoryProductRel_deduct_stock', $currentValue);

		} else {
			throw new WebServiceException(WebServiceErrorCode::$MANDFIELDSMISSING, "Mandatory Fields Missing..");
		}
		return array_merge($element,$parent);
	}

	public function update($element) {
		$element = $this->sanitizeInventoryForInsert($element);
		$element = $this->sanitizeShippingTaxes($element);
		$lineItemList = $element['LineItems'];
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		if (!empty($lineItemList)) {
			$eventManager = new VTEventsManager(vglobal('adb'));
			$this->triggerBeforeSaveEvents($element, $eventManager);

			$currentBulkSaveMode = vglobal('VTIGER_BULK_SAVE_MODE');
			if ($currentBulkSaveMode === NULL) {
				$currentBulkSaveMode = false;
			}
			vglobal('VTIGER_BULK_SAVE_MODE', true);

			$updatedElement = parent::update($element);
			vglobal('VTIGER_BULK_SAVE_MODE', $currentBulkSaveMode);

			$handler->setLineItems('LineItem', $lineItemList, $updatedElement);
			$parent = $handler->getParentById($element['id']);
			$handler->updateParent($lineItemList, $parent);
            $updatedParent = $handler->getParentById($element['id']);
            //since subtotal and grand total is updated in the update parent api
            $parent['hdnSubTotal'] = $updatedParent['hdnSubTotal'];
            $parent['hdnGrandTotal'] = $updatedParent['hdnGrandTotal'];
            $parent['pre_tax_total'] = $updatedParent['pre_tax_total'];
            $updatedElement = array_merge($updatedElement,$parent);

			$currentValue = vglobal('updateInventoryProductRel_deduct_stock');
			vglobal('updateInventoryProductRel_deduct_stock', false);
			$original_update_product_array = vglobal('updateInventoryProductRel_update_product_array');

			$updateInventoryProductRel_update_product_array = array();
			$this->triggerAfterSaveEvents($updatedElement, $eventManager);

			vglobal('updateInventoryProductRel_update_product_array',$original_update_product_array);
			vglobal('updateInventoryProductRel_deduct_stock', $currentValue);

		} else {
			$updatedElement = $this->revise($element);
		}
		return $updatedElement;
	}

	public function revise($element) {
		$element = $this->sanitizeInventoryForInsert($element);
		$element = $this->sanitizeShippingTaxes($element);
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$components = vtws_getIdComponents($element['id']);
		$parentId = $components[1];

		if (!empty($element['LineItems'])) {
			$lineItemList = $element['LineItems'];
			unset($element['LineItems']);

			$eventManager = new VTEventsManager(vglobal('adb'));
			$this->triggerBeforeSaveEvents($element, $eventManager);

			$currentBulkSaveMode = vglobal('VTIGER_BULK_SAVE_MODE');
			if ($currentBulkSaveMode === NULL) {
				$currentBulkSaveMode = false;
			}
			vglobal('VTIGER_BULK_SAVE_MODE', true);

			$updatedElement = parent::revise($element);
			vglobal('VTIGER_BULK_SAVE_MODE', $currentBulkSaveMode);

			$handler->setLineItems('LineItem', $lineItemList, $updatedElement);
			$parent = $handler->getParentById($element['id']);
			$handler->updateParent($lineItemList, $parent);
			$updatedParent = $handler->getParentById($element['id']);
			//since subtotal and grand total is updated in the update parent api
			$parent['hdnSubTotal'] = $updatedParent['hdnSubTotal'];
			$parent['hdnGrandTotal'] = $updatedParent['hdnGrandTotal'];
			$parent['pre_tax_total'] = $updatedParent['pre_tax_total'];
			$parent['LineItems'] = $handler->getAllLineItemForParent($parentId);

			$updatedElement = array_merge($updatedElement,$parent);
			$currentValue = vglobal('updateInventoryProductRel_deduct_stock');
			vglobal('updateInventoryProductRel_deduct_stock', false);
			$original_update_product_array = vglobal('updateInventoryProductRel_update_product_array');

			$updateInventoryProductRel_update_product_array = array();
			$this->triggerAfterSaveEvents($updatedElement, $eventManager);

			vglobal('updateInventoryProductRel_update_product_array',$original_update_product_array);
			vglobal('updateInventoryProductRel_deduct_stock', $currentValue);
		} else {
			$prevAction = $_REQUEST['action'];
			// This is added as we are passing data in user format, so in the crmentity insertIntoEntity API
			// should convert to database format, we have added a check based on the action name there. But
			// while saving Invoice and Purchase Order we are also depending on the same action file names to
			// not to update stock if its an ajax save. In this case also we do not want line items to change.
			$_REQUEST['action'] = 'FROM_WS';

			$parent = parent::revise($element);
			$_REQUEST['action'] = $prevAction;
			$parent['LineItems'] = $handler->getAllLineItemForParent($parentId);
		}
		return array_merge($element,$parent);
	}

	public function retrieve($id) {
		$element = parent::retrieve($id);
		$chargesElement = $this->getChargesElement($element['id']);
		$element = array_merge($element, $chargesElement);

		$skipLineItemFields = getLineItemFields();
		foreach ($skipLineItemFields as $key => $field) {
			if (array_key_exists($field, $element)) {
				unset($element[$field]);
			}
		}
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$idComponents = vtws_getIdComponents($id);
		$lineItems = $handler->getAllLineItemForParent($idComponents[1]);
		$element['LineItems'] = $lineItems;
		$recordCompoundTaxesElement = $this->getCompoundTaxesElement($element, $lineItems);
		$element = array_merge($element, $recordCompoundTaxesElement);
		$element['productid'] = $lineItems[0]['productid'];
		return $element;
	}

	public function delete($id) {
		$components = vtws_getIdComponents($id);
		$parentId = $components[1];
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$handler->cleanLineItemList($id);
		$result = parent::delete($id);
		return $result;
	}

	/**
	 * function to display discounts,taxes and adjustments
	 * @param type $element
	 * @return type
	 */
	protected function sanitizeInventoryForInsert($element) {
		if (!empty($element['hdnTaxType'])) {
			$_REQUEST['taxtype'] = $element['hdnTaxType'];
		}
		if (!empty($element['hdnSubTotal'])) {
			$_REQUEST['subtotal'] = $element['hdnSubTotal'];
		}

		if ($element['hdnDiscountAmount']) {
			$_REQUEST['discount_type_final'] = 'amount';
			$_REQUEST['discount_amount_final'] = $element['hdnDiscountAmount'];
		} elseif ($element['hdnDiscountPercent']) {
			$_REQUEST['discount_type_final'] = 'percentage';
			$_REQUEST['discount_percentage_final'] = $element['hdnDiscountPercent'];
		} else {
			$_REQUEST['discount_type_final'] = '';
			$_REQUEST['discount_percentage_final'] = '';
		}

		if ($element['txtAdjustment']) {
			$_REQUEST['adjustmentType'] = ((int) $element['txtAdjustment'] < 0) ? '-' : '+';
			$_REQUEST['adjustment'] = abs($element['txtAdjustment']);
		} else {
			$_REQUEST['adjustmentType'] = '';
			$_REQUEST['adjustment'] = '';
		}
		if (!empty($element['hdnGrandTotal'])) {
			$_REQUEST['total'] = $element['hdnGrandTotal'];
		}

		if (isset($element['region_id'])) {
			$_REQUEST['region_id'] = $element['region_id'];
		}
		if (empty($element['conversion_rate']) && !$_REQUEST['conversion_rate']) {
			$element['conversion_rate'] = 1;
			$_REQUEST['conversion_rate'] = 1;
		}

		return $element;
	}

	public function sanitizeShippingTaxes($element){
		$subTotal = (float)$element['hdnSubTotal'];
		$overallDiscountAmount = $element['hdnDiscountAmount'];
		if ($element['hdnDiscountPercent']) {
			$overallDiscountAmount = ($subTotal * (float)$element['hdnDiscountPercent']) / 100;
		}
		$itemsTotalAfterOverAllDiscount = $subTotal - $overallDiscountAmount;

		$shippingTaxes = array();
		$allShippingTaxes = getAllTaxes('available', 'sh');
		foreach ($allShippingTaxes as $shTaxInfo) {
			$shippingTaxes[$shTaxInfo['taxid']] = $shTaxInfo;
		}

		$totalSHAmount = 0;
		$totalSHTaxesAmount = 0;
		$allCharges = getAllCharges();
		foreach ($allCharges as $chargeId => $chargeInfo) {
			$chargeName = html_entity_decode(strtolower(str_replace(' ', '_', $chargeInfo['name'])));

			if (array_key_exists($chargeName, $element)) {
				$chargeValue	= $element[$chargeName];
				$pos			= strpos($chargeValue, '%');
				$chargeValue	= str_replace('%', '', $chargeValue);

				if ($pos !== FALSE) {
					$_REQUEST['charges'][$chargeId]['percent'] = $chargeValue;
					$chargeValue = ((float)$itemsTotalAfterOverAllDiscount * (float)$chargeValue) / 100;
				}
				$totalSHAmount = $totalSHAmount + $chargeValue;
				$totalSHTaxesAmount = $totalSHTaxesAmount + $chargeValue;
				$_REQUEST['charges'][$chargeId]['value'] = $chargeValue;
			}

			foreach ($chargeInfo['taxes'] as $taxId) {
				$taxKey = $chargeName."_shtax$taxId";
				if (array_key_exists($taxKey, $element) && $shippingTaxes[$taxId]) {
					$_REQUEST['charges'][$chargeId]['taxes'][$taxId] = $element[$taxKey];
				}
			}
		}

		if ($totalSHAmount) {
			$_REQUEST['shipping_handling_charge'] = $element['hdnS_H_Amount'] = $totalSHAmount;
			$_REQUEST['s_h_percent'] = $totalSHTaxesAmount;
		} else {
			$_REQUEST['shipping_handling_charge'] = $_REQUEST['charges'][1]['value'] = $element['hdnS_H_Amount'];
			foreach ($shippingTaxes as $shTaxId => $shTaxInfo) {
				unset($_REQUEST['charges'][1]['taxes'][$shTaxId]);
				if(isset($element['hdnS_H_Percent']) && $element['hdnS_H_Percent'] != 0 && $element['hdnS_H_Amount'] != 0) {
					$_REQUEST['charges'][1]['taxes'][$shTaxId] = $element['hdnS_H_Percent'];
					$_REQUEST['s_h_percent'] = ($element['hdnS_H_Amount'] * $element['hdnS_H_Percent'])/100;
					break;
				} else {
					$shTaxValue = 0;
					if(isset($element[$shTaxInfo['taxname'] . '_sh_percent'])) {
						$shTaxValue = $element[$shTaxInfo['taxname'] . '_sh_percent'];
					}
					$_REQUEST['charges'][1]['taxes'][$shTaxId] = $shTaxValue;
				}
			}
		}

		return $element;
	}
    /* NOTE: Special case to pull the default setting of TermsAndCondition */

    public function describe($elementType) {
        $describe = parent::describe($elementType);
        $tandc = getTermsAndConditions($elementType);
        foreach ($describe['fields'] as $key => $list){
            if($list["name"] == 'terms_conditions'){
                $describe['fields'][$key]['default'] = $tandc;
            }
        }

		$shippingTaxes = array();
		$allShippingTaxes = getAllTaxes('available', 'sh');
		foreach ($allShippingTaxes as $shTaxInfo) {
			$shippingTaxes[$shTaxInfo['taxid']] = $shTaxInfo;
		}

		$allCharges = getAllCharges();
		foreach ($allCharges as $chargeId => $chargeInfo) {
			$chargeField = array();
			$chargeField['name']		= html_entity_decode(strtolower(str_replace(' ', '_', $chargeInfo['name'])));
			$chargeField['label']		= $chargeInfo['name'];
			$chargeField['type']		= array('name' => 'double');
			$chargeField['mandatory']	= false;
			$chargeField['nullable']	= true;
			$chargeField['editable']	= true;
			$chargeField['default']		= ($chargeInfo['format'] === 'Percent') ? $chargeInfo['value'].'%' : $chargeInfo['value'];

			$describe['fields'][] = $chargeField;

			foreach ($chargeInfo['taxes'] as $shTaxId) {
				$shTaxField = array();
				$shTaxField['name']		= $chargeField['name'].'_'.$shippingTaxes[$shTaxId]['taxname'];
				$shTaxField['label']	= $chargeInfo['name'].' '.$shippingTaxes[$shTaxId]['taxlabel'];
				$shTaxField['default']	= $shippingTaxes[$shTaxId]['percentage'];
				$shTaxField['type']		= array('name' => 'double');
				$shTaxField['nullable']	= true;
				$shTaxField['editable']	= true;
				$shTaxField['mandatory']= false;

				$describe['fields'][]	= $shTaxField;
			}

		}

        return $describe;
    }

	/**
	 * Function to trigger the events which are before save
	 * @param <type> $element
	 * @param <type> $eventManager
	 */
	public function triggerBeforeSaveEvents($element, $eventManager) {

		if ($eventManager) {
			$eventManager->initTriggerCache();
			$focusObj = $this->constructFocusObject($element);
			$entityData = VTEntityData::fromCRMEntity($focusObj);

			$eventManager->triggerEvent("vtiger.entity.beforesave.modifiable", $entityData);
			$eventManager->triggerEvent("vtiger.entity.beforesave", $entityData);
			$eventManager->triggerEvent("vtiger.entity.beforesave.final", $entityData);
		}
	}

	/**
	 * Function to trigger the events which are after save
	 * @param <type> $element
	 * @param <type> $eventManager
	 */
	public function triggerAfterSaveEvents($element, $eventManager) {

		if ($eventManager) {
			$focusObj = $this->constructFocusObject($element);
			$entityData = VTEntityData::fromCRMEntity($focusObj);

			$eventManager->triggerEvent("vtiger.entity.aftersave", $entityData);
			$eventManager->triggerEvent("vtiger.entity.aftersave.final", $entityData);
		}
	}

	/**
	 * Function to construct focus object
	 * @param <type> $element
	 * @param <type> $action
	 * @return <type>
	 */
	public function constructFocusObject($element) {

		$focus = CRMEntity::getInstance($this->getMeta()->getTabName());
		$fields = $focus->column_fields;

		foreach($fields as $fieldName => $fieldValue) {
			$fieldValue = $element[$fieldName];
			if(is_array($fieldValue)) {
				$focus->column_fields[$fieldName] = $fieldValue;
			} else if($fieldValue !== null) {
				$focus->column_fields[$fieldName] = decode_html($fieldValue);
			}
		}
		$ids = vtws_getIdComponents($element['id']);
		$focus->id = $ids[1];

		return $focus;
	}

	public function getChargesElement($elementId) {
		$chargesElement = array();
		if ($elementId) {
			$ids = vtws_getIdComponents($elementId);
			$id = $ids[1];
			$result = $this->pearDB->pquery('SELECT * FROM jo_inventorychargesrel WHERE recordid = ?', array($id));
			$rowData = $this->pearDB->fetch_array($result);

			if ($rowData['charges']) {
				$allCharges = getAllCharges();
				$shippingTaxes = array();
				$allShippingTaxes = getAllTaxes('all', 'sh');
				foreach ($allShippingTaxes as $shTaxInfo) {
					$shippingTaxes[$shTaxInfo['taxid']] = $shTaxInfo;
				}

				$charges = Zend_Json::decode(html_entity_decode($rowData['charges']));
				foreach ($charges as $chargeId => $chargeInfo) {
					$chargeName = html_entity_decode(strtolower(str_replace(' ', '_', $allCharges[$chargeId]['name'])));

					$chargeValue = $chargeInfo['value'];
					if (array_key_exists('percent', $chargeInfo)) {
						$chargeValue = $chargeInfo['percent'].'%';
					}
					$chargesElement[$chargeName] = $chargeValue;

					if ($chargeInfo['taxes']) {
						foreach ($chargeInfo['taxes'] as $taxId => $taxPercent) {
							if ($shippingTaxes[$taxId]) {
								$chargesElement[$chargeName.'_shtax'.$taxId] = $taxPercent;
							}
						}
					}
				}
			}
		}
		return $chargesElement;
	}

	public function getCompoundTaxesElement($element, $lineItems) {
		$idComponents = vtws_getIdComponents($element['id']);
		$recordId = $idComponents[1];
		$compoundTaxesElement = array();
		$recordTaxesCompoundInfo = array();

		$compoundInfo = getCompoundTaxesInfoForInventoryRecord($recordId, getSalesEntityType($recordId));
		if (is_array($compoundInfo)) {
			foreach ($compoundInfo as $taxId => $comInfo) {
				foreach ($comInfo as $cTaxId) {
					$recordTaxesCompoundInfo["tax$taxId"][] = "tax$cTaxId";
				}
			}
		}

		if ($recordTaxesCompoundInfo) {
			if ($element['hdnTaxType'] === 'group') {
				$compoundTaxesElement['compoundTaxInfo'] = $recordTaxesCompoundInfo;
			} else {
				foreach ($lineItems as $key => $lineItem) {
					$lineItems[$key]['compoundTaxInfo'] = $recordTaxesCompoundInfo;
				}
			}
		}
		$compoundTaxesElement['LineItems'] = $lineItems;
		return $compoundTaxesElement;
	}

}

?>