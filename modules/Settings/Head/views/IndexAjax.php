<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Head_IndexAjax_View extends Settings_Head_Index_View {
	function __construct() {
		parent::__construct();
		$this->exposeMethod('getSettingsShortCutBlock');
	}
	
	public function preProcess (Head_Request $request) {
		return;
	}

	public function postProcess (Head_Request $request) {
		return;
	}
	
	public function process (Head_Request $request) {
		$mode = $request->getMode();

		if($mode){
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}
	
	public function getSettingsShortCutBlock(Head_Request $request) {
		$fieldid = $request->get('fieldid');
		$viewer = $this->getViewer($request);
		$qualifiedModuleName = $request->getModule(false);
		$pinnedSettingsShortcuts = Settings_Head_MenuItem_Model::getPinnedItems();
		$viewer->assign('SETTINGS_SHORTCUT',$pinnedSettingsShortcuts[$fieldid]);
		$viewer->assign('MODULE',$qualifiedModuleName);
		$viewer->view('SettingsShortCut.tpl', $qualifiedModuleName);
	}
}