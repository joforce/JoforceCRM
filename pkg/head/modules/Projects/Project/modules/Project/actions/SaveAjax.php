<?php
/* ***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ***********************************************************************************/

class Project_SaveAjax_Action extends Head_SaveAjax_Action {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('saveColor');
	}

	public function process(Head_Request $request) {
		$mode = $request->getMode();
		if (!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		} else {
			parent::process($request);
		}
	}

	function saveColor(Head_Request $request) {
		$db = PearDatabase::getInstance();
		$color = $request->get('color');
		$status = $request->get('status');

		$db->pquery('INSERT INTO jo_projecttask_status_color(status,color) VALUES(?,?) ON DUPLICATE KEY UPDATE color = ?', array($status, $color, $color));
		$response = new Head_Response();
		$response->setEmitType(Head_Response::$EMIT_JSON);
		$response->setResult(true);
		$response->emit();
	}

}
