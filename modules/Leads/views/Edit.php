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

class Leads_Edit_View extends Head_Edit_View {

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
        $recordModel = $this->record;
        if(!$recordModel){
            if (!empty($recordId)) {
                $recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
            } else {
                $recordModel = Head_Record_Model::getCleanInstance($moduleName);
            }
        }

		$viewer = $this->getViewer($request);

	$salutationFieldModel = Head_Field_Model::getInstance('salutationtype', $recordModel->getModule());
	$salutationValue = $request->get('salutationtype');
        if(!empty($salutationValue)){ 
        	$salutationFieldModel->set('fieldvalue', $salutationValue); 
        } else{ 
        	$salutationFieldModel->set('fieldvalue', $recordModel->get('salutationtype')); 
        } 
		$viewer->assign('SALUTATION_FIELD_MODEL', $salutationFieldModel);

		parent::process($request);
	}

}
