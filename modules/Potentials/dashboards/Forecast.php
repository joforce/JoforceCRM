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

class Potentials_Forecast_Dashboard extends Head_IndexAjax_View {

	public function process(Head_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$linkId = $request->get('linkid');
		
		$expectedclosedate = $request->get('expectedclosedate');
		
		//Date conversion from user to database format
		if(!empty($expectedclosedate)) {
			$closingdates['start'] = Head_Date_UIType::getDBInsertedValue($expectedclosedate['start']);
			$closingdates['end'] = Head_Date_UIType::getDBInsertedValue($expectedclosedate['end']);
		}
		
		$dates = $request->get('createdtime');
		
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$data = $moduleModel->getForecast($closingdates,$dates);

		$widget = Head_Widget_Model::getInstance($linkId, $currentUser->getId());

		//Include special script and css needed for this widget
		$viewer->assign('SCRIPTS',$this->getHeaderScripts($request));
		
		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('DATA', $data);

		$content = $request->get('content');
		if(!empty($content)) {
			$viewer->view('dashboards/DashBoardWidgetContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/Forecast.tpl', $moduleName);
		}
	}
}
