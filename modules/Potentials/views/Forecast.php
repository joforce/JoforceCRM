<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/
class Potentials_Forecast_View extends Head_Index_View {

public function preProcess(Head_Request $request, $display = true) {
                $viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$viewer->assign('VIEW' , $request->get('view'));
               	
		$viewer->assign('CUSTOM_VIEWS', CustomView_Record_Model::getAllByGroup($moduleName));	
		$viewer->assign('IS_FORECAST_VIEW', 'true');
		parent::preProcess($request, false);
                if($display) {
                        $this->preProcessDisplay($request);
                }
        }

protected function preProcessTplName(Head_Request $request) {
                return 'ListViewPreProcess.tpl';
        }


public function process(Head_Request $request) {
		
	global $adb, $site_URL, $current_user;
	$moduleName = $request->getModule();
	$cvId = $request->get('record');
        $viewer = $this->getViewer($request);
        $sales_array = getSalesStageArray();
	$sales_count = count($sales_array);
	$pageNumber = '1';

	$listViewModel = Head_ListView_Model::getInstance($moduleName, $cvId);
	$pagingModel = new Head_Paging_Model();
        $pagingModel->set('page', $pageNumber);
        $pagingModel->set('viewid', $cvId);
	$pagingModel->set('view_name', 'Forecast');
	$entries = $listViewModel->getListViewEntries($pagingModel);

	$amount_array = [];
	$potential_count_array = [];

	for($i = 1; $i <= $sales_count; $i++)
	{
	$sum = 0;
	$count = 0;
		foreach($entries as $entry)
		{
			if($sales_array[$i] == $entry->get('sales_stage'))
			{
			$sum = str_replace(',', '', number_format($sum)) + str_replace(',', '', $entry->get('amount'));
			$count = $count + 1;
			}
		}
	$amount_array[$sales_array[$i]] = $sum;
	$potential_count_array[$sales_array[$i]] = $count;
	}
	$currency_info = Head_Util_Helper::getUserCurrencyInfo();
	$currency_symbol = $currency_info['currency_symbol'];

        $viewer->assign('SALES_STAGES', $sales_array);
        $viewer->assign('SITEURL', $site_URL);
	$viewer->assign('POTENTIALS', $entries);
	$viewer->assign('AMOUNT_ARRAY',	$amount_array);
	$viewer->assign('COUNT_ARRAY', $potential_count_array);
	$viewer->assign('CURRENCY_SYMBOL', $currency_symbol);

        $viewer->view('Forecast.tpl', $request->getModule());
    }

	/**
         * Function to get the list of Script models to be included
         * @param Head_Request $request
         * @return <Array> - List of Head_JsScript_Model instances
         */
        function getHeaderScripts(Head_Request $request) {
                $headerScriptInstances = parent::getHeaderScripts($request);
                $moduleName = $request->getModule();

                $jsFileNames = array(
			"modules.$moduleName.resources.List"
                );

                $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
                $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
                return $headerScriptInstances;
        }

}
?>
