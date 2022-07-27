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

class ModComments_DownloadFile_Action extends Head_Action_Controller {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		if (!Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $request->get('record'))) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
		}
	}

	public function process(Head_Request $request) {
		$moduleName = $request->getModule();
		$modCommentsRecordModel = Head_Record_Model::getInstanceById($request->get('record'), $moduleName);
		$attachmentId = $request->get('fileid');
		$modCommentsRecordModel->downloadFile($attachmentId);
	}

}
