<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Google_SaveSyncSettings_Action extends Head_BasicAjax_Action {

	public function process(Head_Request $request) {
        global $site_URL;
		$contactsSettings = $request->get('Contacts');
		$calendarSettings = $request->get('Calendar');
		$sourceModule = $request->get('sourceModule');
        $block_id = $request->get('block');
        $field_id = $request->get('fieldid');

		$contactRequest = new Head_Request($contactsSettings);
		$contactRequest->set('sourcemodule', 'Contacts');
		Google_Utils_Helper::saveSyncSettings($contactRequest);

		$calendarRequest = new Head_Request($calendarSettings);
		$calendarRequest->set('sourcemodule', 'Calendar');
		Google_Utils_Helper::saveSyncSettings($calendarRequest);
		$googleModuleModel = Head_Module_Model::getInstance('Google');

		$returnUrl = $googleModuleModel->getBaseExtensionUrl($sourceModule);
		header('Location: '.$returnUrl);
	}

}

?>
