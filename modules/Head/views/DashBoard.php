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

class Head_Dashboard_View extends Head_Index_View {

	protected static $selectable_dashboards;

	function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		if(!Users_Privileges_Model::isPermitted($moduleName, $actionName)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	function preProcess(Head_Request $request, $display=true) {
		parent::preProcess($request, false);
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$dashBoardModel = Head_DashBoard_Model::getInstance($moduleName);
		//check profile permissions for Dashboards
		$moduleModel = Head_Module_Model::getInstance('Dashboard');
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if($permission) {
			// TODO : Need to optimize the widget which are retrieving twice
			$dashboardTabs = $dashBoardModel->getActiveTabs();
			if ($request->get("tabid")) {
				$tabid = $request->get("tabid");
			} else {
				// If no tab, then select first tab of the user
				$tabid = $dashboardTabs[0]["id"];
			}
			$dashBoardModel->set("tabid", $tabid);
			$widgets = $dashBoardModel->getSelectableDashboard();
			self::$selectable_dashboards = $widgets;
		} else {
			$widgets = array();
		}
		$viewer->assign('MODULE_PERMISSION', $permission);
		$viewer->assign('WIDGETS', $widgets);
		$viewer->assign('MODULE_NAME', $moduleName);
		if($display) {
			$this->preProcessDisplay($request);
		}
	}

	function preProcessTplName(Head_Request $request) {
		return 'dashboards/DashBoardPreProcess.tpl';
	}

	function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$dashBoardModel = Head_DashBoard_Model::getInstance($moduleName);

		//check profile permissions for Dashboards
		$moduleModel = Head_Module_Model::getInstance('Dashboard');
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if($permission) {
			// TODO : Need to optimize the widget which are retrieving twice
		   $dashboardTabs = $dashBoardModel->getActiveTabs();
		   if($request->get("tabid")){
			   $tabid = $request->get("tabid");
		   } else {
			   // If no tab, then select first tab of the user
			   $tabid = $dashboardTabs[0]["id"];
		   }
		   $dashBoardModel->set("tabid",$tabid);
			$widgets = $dashBoardModel->getDashboards($moduleName);
		} else {
			return;
		}

		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('WIDGETS', $widgets);
		$viewer->assign('DASHBOARD_TABS', $dashboardTabs);
		$viewer->assign('DASHBOARD_TABS_LIMIT', $dashBoardModel->dashboardTabLimit);
		$viewer->assign('SELECTED_TAB',$tabid);
        if (self::$selectable_dashboards) {
			$viewer->assign('SELECTABLE_WIDGETS', self::$selectable_dashboards);
		}
		$viewer->assign('CURRENT_USER', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('TABID',$tabid);
		$viewer->view('dashboards/DashBoardContents.tpl', $moduleName);
	}

	public function postProcess(Head_Request $request) {
		parent::postProcess($request);
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_JsScript_Model instances
	 */
	public function getHeaderScripts(Head_Request $request) {
		$headerScriptInstances = parent::getHeaderScripts($request);
		$moduleName = $request->getModule();

		$jsFileNames = array(
			'~/libraries/jquery/gridster/jquery.gridster.min.js',
			'~/libraries/jquery/jqplot/jquery.jqplot.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasTextRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.pieRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.barRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.categoryAxisRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.pointLabels.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.funnelRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.barRenderer.min.js',
			'~/libraries/jquery/jqplot/plugins/jqplot.logAxisRenderer.min.js',
			'~/libraries/jquery/VtJqplotInterface.js',
                        '~/libraries/jquery/vtchart.js',
			'~/libraries/chart-js/Chart.bundle.js',
			'~layouts/'.Head_Viewer::getDefaultLayoutName().'/lib/jquery/gridster/jquery.gridster.min.js',
                        '~/libraries/jquery/vtchart.js',
			'~/libraries/chart-js/Chart.bundle.js',
			'modules.Head.resources.DashBoard',
			'modules.'.$moduleName.'.resources.DashBoard',
			'modules.Head.resources.dashboards.Widget',
			'~/layouts/'.Head_Viewer::getDefaultLayoutName().'/modules/Head/resources/Detail.js',
			'~/layouts/'.Head_Viewer::getDefaultLayoutName().'/modules/Reports/resources/Detail.js',
			'~/layouts/'.Head_Viewer::getDefaultLayoutName().'/modules/Reports/resources/ChartDetail.js',
			"modules.Emails.resources.MassEdit",
			"modules.Head.resources.CkEditor",
			"~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/bootstrap-daterangepicker/moment.js",
			"~layouts/".Head_Viewer::getDefaultLayoutName()."/lib/bootstrap-daterangepicker/daterangepicker.js",
		);

		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		$headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
		return $headerScriptInstances;
	}

	/**
	 * Function to get the list of Css models to be included
	 * @param Head_Request $request
	 * @return <Array> - List of Head_CssScript_Model instances
	 */
	public function getHeaderCss(Head_Request $request) {
		$parentHeaderCssScriptInstances = parent::getHeaderCss($request);

		$headerCss = array(
			'~layouts/'.Head_Viewer::getDefaultLayoutName().'/lib/jquery/gridster/jquery.gridster.min.css',
			'~layouts/'.Head_Viewer::getDefaultLayoutName().'/lib/bootstrap-daterangepicker/daterangepicker.css',
			'~libraries/jquery/jqplot/jquery.jqplot.min.css'
		);
		$cssScripts = $this->checkAndConvertCssStyles($headerCss);
		$headerCssScriptInstances = array_merge($parentHeaderCssScriptInstances , $cssScripts);
		return $headerCssScriptInstances;
	}
}
