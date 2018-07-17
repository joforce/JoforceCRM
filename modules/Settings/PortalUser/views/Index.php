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

class Settings_PortalUser_Index_View extends Settings_Head_Index_View {

	public function process(Head_Request $request) {

		$qualified_module = $request->getModule(false);
		$viewer = $this->getViewer($request);
		require_once('user_privileges/portal_user_settings.php');
		$viewer->assign('QUALIFIED_MODULE_NAME', $qualified_module);
		$viewer->assign('MASQUERADE_USER_STATUS', $enable_masquerade_user);
		$viewer->view('Index.tpl', $qualified_module);
	}

}

?>
