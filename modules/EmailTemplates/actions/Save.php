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

class EmailTemplates_Save_Action extends Head_Save_Action {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');

		$actionName = ($record) ? 'EditView' : 'CreateView';
		if(!Users_Privileges_Model::isPermitted($moduleName, $actionName, $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}

		if (!Users_Privileges_Model::isPermitted($moduleName, 'Save', $record)) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	public function process(Head_Request $request) {
		$site_URL = vglobal('site_URL');
		$moduleName = $request->getModule();
		$record = $request->get('record');
		$emitResponse = $request->get('emitResponse');
		$recordModel = new EmailTemplates_Record_Model();
		$recordModel->setModule($moduleName);

		if (!empty($record)) {
			$recordModel->setId($record);
		}

		$recordModel->set('templatename', $request->get('templatename'));
		$recordModel->set('description', $request->get('description'));
		$recordModel->set('subject', $request->get('subject'));
		$recordModel->set('module', $request->get('modulename'));
		$recordModel->set('systemtemplate', $request->get('systemtemplate'));
		$content = $request->getRaw('templatecontent');
		$processedContent = Emails_Mailer_Model::getProcessedContent($content); // To remove script tags
		$recordModel->set('body', $processedContent);

		$recordId = $recordModel->save();
		$recordModel->updateImageName($recordId);
		if ($request->get('returnmodule') && $request->get('returnview')){
			$loadUrl = 'index.php?'.$request->getReturnURL();
		} else {
			if ($request->get('returnmodule') && $request->get('returnview')) {
				$loadUrl = 'index.php?' . $request->getReturnURL();
			} else {
				$loadUrl = $recordModel->getDetailViewUrl();
			}
			header("Location: $loadUrl");
		}
	}

}
