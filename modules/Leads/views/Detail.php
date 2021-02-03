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

class Leads_Detail_View extends Head_Detail_View {
        function preProcess(Head_Request $request, $display=true) {
                global $adb;
                $viewer = $this->getViewer($request);
                $recordId = $request->get('record');
		$getRelatedDeals = getRelatedRecordSumValue($recordId, $request->getModule(), 'Products', 'unit_price');
                $getServicesCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'Services', 'unit_price');
                $getCalls = getRelatedRecordSumValue($recordId, $request->getModule(), 'PBXManager');
                $getEventsCount = getRelatedRecordSumValue($recordId, $request->getModule(), 'Calendar');

		$values = array();
                $values['Products'] = $getRelatedDeals? $getRelatedDeals : 0;
                $values['Services'] = $getServicesCount? $getServicesCount : 0;
                $values['PBXManager'] = $getCalls? $getCalls : 0;
                $values['Calendar'] = $getEventsCount? $getEventsCount : 0;
		$viewer->assign('TOTAL', $values);
                parent::preProcess($request);
        }
}
