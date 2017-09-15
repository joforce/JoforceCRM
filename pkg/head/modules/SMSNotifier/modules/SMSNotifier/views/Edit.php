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

class SMSNotifier_Edit_View extends Head_Edit_View {

	public function checkPermission(Head_Request $request) {
		throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $request->getModule()));
	}
}