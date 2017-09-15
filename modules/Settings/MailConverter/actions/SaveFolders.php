<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ********************************************************************************** */

class Settings_MailConverter_SaveFolders_Action extends Settings_Head_Index_Action {

    public function process(Head_Request $request) {
		$recordId = $request->get('record');
		$qualifiedModuleName = $request->getModule(false);
		$checkedFolders = $request->get('folders');
		$folders = explode(',', $checkedFolders);
		Settings_MailConverter_Module_Model::updateFolders($recordId, $folders);

		$response = new Head_Response();

		$result = array('message' => vtranslate('LBL_SAVED_SUCCESSFULLY', $qualifiedModuleName));
		$result['id'] = $recordId;
		$response->setResult($result);

		$response->emit();
	}

}

?>