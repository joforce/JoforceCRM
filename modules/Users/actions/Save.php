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

class Users_Save_Action extends Head_Save_Action {

	public function checkPermission(Head_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');
		$recordModel = Head_Record_Model::getInstanceById($record, $moduleName);
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		if(!Users_Privileges_Model::isPermitted($moduleName, 'Save', $record) || ($recordModel->isAccountOwner() && 
							$currentUserModel->get('id') != $recordModel->getId() && !$currentUserModel->isAdminUser())) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
		}
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Head_Request $request
	 * @return Head_Record_Model or Module specific Record Model instance
	 */
	public function getRecordModelFromRequest(Head_Request $request) {
		$moduleName = $request->getModule();
		$recordId = $request->get('record');
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		if(!empty($recordId)) {
			$recordModel = Head_Record_Model::getInstanceById($recordId, $moduleName);
			$modelData = $recordModel->getData();
			$recordModel->set('id', $recordId);
			$sharedType = $request->get('sharedtype');
			if(!empty($sharedType))
				$recordModel->set('calendarsharedtype', $request->get('sharedtype'));
			$recordModel->set('mode', 'edit');
		} else {
			$recordModel = Head_Record_Model::getCleanInstance($moduleName);
			$modelData = $recordModel->getData();
			$recordModel->set('mode', '');
		}

		foreach ($modelData as $fieldName => $value) {
			$requestFieldExists = $request->has($fieldName);
			if(!$requestFieldExists){
				continue;
			}
			$fieldValue = $request->get($fieldName, null);
			if ($fieldName === 'is_admin' && (!$currentUserModel->isAdminUser() || !$fieldValue)) {
				$fieldValue = 'off';
			}
			//to not update is_owner from ui
			if ($fieldName == 'is_owner') {
				$fieldValue = null;
			}
			if($fieldValue !== null) {
				if(!is_array($fieldValue)) {
					$fieldValue = trim($fieldValue);
				}
				$recordModel->set($fieldName, $fieldValue);
			}
		}
		$homePageComponents = $recordModel->getHomePageComponents();
		$selectedHomePageComponents = $request->get('homepage_components', array());
		foreach ($homePageComponents as $key => $value) {
			if(in_array($key, $selectedHomePageComponents)) {
				$request->setGlobal($key, $key);
			} else {
				$request->setGlobal($key, '');
			}
		}
		if($request->has('tagcloudview')) {
			// Tag cloud save
			$tagCloud = $request->get('tagcloudview');
			if($tagCloud == "on") {
				$recordModel->set('tagcloud', 0);
			} else {
				$recordModel->set('tagcloud', 1);
			}
		}
		return $recordModel;
	}

	public function process(Head_Request $request) {
		$result = Head_Util_Helper::transformUploadedFiles($_FILES, true);
		$_FILES = $result['imagename'];

		$recordId = $request->get('record');
		if (!$recordId) {
			$module = $request->getModule();
			$userName = $request->get('user_name');
			$userModuleModel = Users_Module_Model::getCleanInstance($module);
			$status = $userModuleModel->checkDuplicateUser($userName);
			if ($status == true) {
				throw new AppException(vtranslate('LBL_DUPLICATE_USER_EXISTS', $module));
			}
		}
		$recordModel = $this->saveRecord($request);

		$masquerade_user_status = $request->get('is_masquerade_user');
		if($masquerade_user_status) {
			global $site_URL,$adb;
			$currentUserModel = Users_Record_Model::getCurrentUserModel(); 
			$from_name = $currentUserModel->getName();
			$from_email = getUserEmail($currentUserModel->getId());
			$to_email = $request->get('email1');
			
			$module = $request->getModule();
			//get contents from template.
			$query = $adb->pquery('select subject,body from jo_emailtemplates where systemtemplate = ? and templateid = ?', array(1,7));
			$result = $adb->fetchByAssoc($query);
			
			$subject=$result['subject'];
			$html=$result['body'];
			// replacing site_url users-user_name users-user_password_custom
			$html = str_replace('$users-first_name$',$request->get('user_name'), $html);

			$html = str_replace('$site_url$',$site_URL, $html);
			$html = str_replace('$users-user_name$',$request->get('user_name'), $html);
			$html = str_replace('$users-user_password_custom$',$request->get('user_password'), $html);

			$contents=htmlspecialchars_decode($html);
			
			require_once('modules/Emails/mail.php');
			send_mail($module, $to_email, $from_name, $from_email, $subject, $contents, '', '', '', '', '', '', '', '');
		}

		if ($request->get('relationOperation')) {
			$parentRecordModel = Head_Record_Model::getInstanceById($request->get('sourceRecord'), $request->get('sourceModule'));
			$loadUrl = $parentRecordModel->getDetailViewUrl();
		} else if ($request->get('isPreference')) {
			$loadUrl =  $recordModel->getPreferenceDetailViewUrl();
		} else if ($request->get('returnmodule') && $request->get('returnview')){
			$loadUrl = 'index.php?'.$request->getReturnURL();
		} else if($request->get('mode') == 'Calendar'){
			$loadUrl = $recordModel->getCalendarSettingsDetailViewUrl();
		}else {
			$loadUrl = $recordModel->getDetailViewUrl();
		}

		header("Location: $loadUrl");
	}
}
