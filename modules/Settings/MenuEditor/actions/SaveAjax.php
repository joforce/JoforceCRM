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

Class Settings_MenuEditor_SaveAjax_Action extends Settings_Head_IndexAjax_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('removeModule');
		$this->exposeMethod('addModule');
		$this->exposeMethod('saveSequence');
	}

	public function process(Head_Request $request) {
		$mode = $request->get('mode');
		if (!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	function removeModule(Head_Request $request) {
		$sourceModule = $request->get('sourceModule');
		$appName = $request->get('appname');
		$db = PearDatabase::getInstance();
		$db->pquery('UPDATE jo_app2tab SET visible = ? WHERE tabid = ? AND appname = ?', array(0, getTabid($sourceModule), $appName));

		$response = new Head_Response();
		$response->setResult(array('success' => true));
		$response->emit();
	}

	function addModule(Head_Request $request) {
		$sourceModules = array($request->get('sourceModule'));
		if ($request->has('sourceModules')) {
			$sourceModules = $request->get('sourceModules');
		}
		$appName = $request->get('appname');
		$db = PearDatabase::getInstance();
		foreach ($sourceModules as $sourceModule) {
			$db->pquery('UPDATE jo_app2tab SET visible = ? WHERE tabid = ? AND appname = ?', array(1, getTabid($sourceModule), $appName));
		}

		$response = new Head_Response();
		$response->setResult(array('success' => true));
		$response->emit();
	}

	function saveSequence(Head_Request $request) {
		$moduleSequence = $request->get('sequence');
		$appName = $request->get('appname');
		$db = PearDatabase::getInstance();
		foreach ($moduleSequence as $moduleName => $sequence) {
			$moduleModel = Head_Module_Model::getInstance($moduleName);
			$db->pquery('UPDATE jo_app2tab SET sequence = ? WHERE tabid = ? AND appname = ?', array($sequence, $moduleModel->getId(), $appName));
		}

		$response = new Head_Response();
		$response->setResult(array('success' => true));
		$response->emit();
	}

}

?>
