<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Users_AddMasqueradeUser_View extends Head_Index_View {
	public function process (Head_Request $request) {
		$module = $request->getModule();
		$record_id = $request->get('record_id');
		$record_instance = Head_Record_Model::getInstanceById($record_id);
		$viewer = $this->getViewer($request);

		$viewer->assign('RECORD_MODEL', $record_instance);
		$viewer->assign('MODULE', $module);
		$viewer->assign('RECORD_ID', $record_id);
		$viewer->view('AddMasqueradeUser.tpl', $module);
	}
}
?>
