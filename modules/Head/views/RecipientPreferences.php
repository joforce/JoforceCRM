<?php

class Head_RecipientPreferences_View extends Head_MassActionAjax_View {

	public function process(Head_Request $request) {
		$sourceModule = $request->getModule();
		$emailFieldsInfo = $this->getEmailFieldsInfo($sourceModule);
		$viewer = $this->getViewer($request);
		$viewer->assign('EMAIL_FIELDS_LIST', $emailFieldsInfo);
		$viewer->assign('MODULE', $request->getModule());
		$viewer->assign('SOURCE_MODULE', $sourceModule);
		echo $viewer->view('RecipientPreferences.tpl', $request->getModule(), true);
	}

	protected function getEmailFieldsInfo($moduleName) {
		$emailFieldsInfo = array();
		$emailFieldsList = array();
		$recipientPrefModel = Head_RecipientPreference_Model::getInstance($moduleName);
		if ($recipientPrefModel) {
			$prefs = $recipientPrefModel->getPreferences();
		}
		$sourceModuleModel = Head_Module_Model::getInstance($moduleName);
		$emailFields = $sourceModuleModel->getFieldsByType('email');
		$emailFieldsPref = $prefs[$sourceModuleModel->getId()];
		
		foreach ($emailFields as $field) {
			if ($field->isViewable()) {
				if ($emailFieldsPref && in_array($field->getId(), $emailFieldsPref)) {
					$field->set('isPreferred', true);
				}
				$emailFieldsList[$field->getName()] = $field;
			}
		}

		if (!empty($emailFieldsList)) {
			$emailFieldsInfo[$sourceModuleModel->getId()] = $emailFieldsList;
		}
		return $emailFieldsInfo;
	}

}
