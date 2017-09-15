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

abstract class Head_Footer_View extends Head_Header_View {

	function __construct() {
		parent::__construct();
	}

	//Note: To get the right hook for immediate parent in PHP,
	// specially in case of deep hierarchy
	/*function preProcessParentTplName(Head_Request $request) {
		return parent::preProcessTplName($request);
	}*/

	/*function postProcess(Head_Request $request) {
		parent::postProcess($request);
	}*/
       public function getHeaderCss(Head_Request $request) {
		$headerCssInstances = parent::getHeaderCss($request);
		$cssFileNames = array(
            '~layouts/'.Head_Viewer::getDefaultLayoutName().'/lib/jquery/timepicker/jquery.timepicker.css',
            '~/libraries/jquery/lazyYT/lazyYT.min.css'
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);
		return $headerCssInstances;
	}
}
