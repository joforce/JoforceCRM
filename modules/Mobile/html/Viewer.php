<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

include_once 'includes/Loader.php';
include_once 'includes/runtime/Viewer.php';

class Mobile_HTML_Viewer extends Head_Viewer{

	private $parameters = array();
	private $_smarty = null;
	
	function assign($key, $value) {
		$this->parameters[$key] = $value;
	}

	function viewController() {
		if(!$this->_smarty) {
			$this->_smarty = Head_Viewer::getInstance();
		}
		foreach($this->parameters as $k => $v) {
			$this->_smarty->assign($k, $v);
		}

		$this->_smarty->assign("IS_SAFARI", Mobile::isSafari());
		$this->_smarty->assign("TEMPLATE_WEBPATH", "layouts/".Head_Viewer::getLayoutName()."/modules/Mobile/".Mobile::config('Default.Layout'));
		
		return $this->_smarty;
	}

	function process($module, $view) {
		$smarty = $this->viewController();
	
		$templateBasePath = $this->_getBaseTemplatePath();
		$templatePath = $templateBasePath. "/{$module}/{$view}.tpl";
        if (!$smarty->templateExists($templatePath)) {
			$templatePath = $templateBasePath . "/Head/{$view}.tpl";
			if (!$smarty->templateExists($templatePath)) {
				$templatePath = null;
			}
		}
		
		// adding view specific js files and controller
		$scripts = array();
		$controller = null;
		$moduleSpecificTemplatePath = $templateBasePath. "/.{$module}/js/{$view}.js";
		if(file_exists($moduleSpecificTemplatePath)) {
			$scripts[] = "/.{$module}/js/{$view}.js";
			$controller = $module.$view.'Controller';
		}
		$baseTemplatePath = $templateBasePath. "/Head/js/{$view}.js";
		if(file_exists($baseTemplatePath)) {
			$scripts[] = "/Head/js/{$view}.js";
			$controller = 'Head'.$view.'Controller';
		}
		
		$smarty->assign('_scripts', $scripts);
		$smarty->assign('_controller', $controller);
		
		if (!$templatePath) {
			throw new Exception("$module/$view not found.");
		}
		return $smarty->fetch($templatePath);
	}
	
	function _getBaseTemplatePath() {
		$smarty = $this->viewController();
		return $smarty->getTemplateDir(0) . DIRECTORY_SEPARATOR . "modules/Mobile/". Mobile::config('Default.Layout');
	}

}

function mobile_templatepath($template, $module) {
    $smarty = Head_Viewer::getInstance();
    $templateBasePath = $smarty->getTemplateDir(0) . DIRECTORY_SEPARATOR . "modules/Mobile/". Mobile::config('Default.Layout');
    $templatePath = $templateBasePath. "/{$module}/{$template}";
    if (!$smarty->templateExists($templatePath)) {
        $templatePath = $templateBasePath . "/Head/{$template}";
    }
    if (!$templatePath) {
        throw new Exception("$module/$template not found.");
    }
    return $smarty->fetch($templatePath);
}
