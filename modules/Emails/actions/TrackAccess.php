<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *********************************************************************************/

header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false);

//Opensource fix for tracking email access count
chdir(dirname(__FILE__). '/../../../');

require_once 'includes/Loader.php';
require_once 'includes/utils/utils.php';

vimport('includes.http.Request');
vimport('includes.runtime.Globals');
vimport('includes.runtime.BaseModel');
vimport ('includes.runtime.Controller');
vimport('includes.runtime.LanguageHandler');

class Emails_TrackAccess_Action extends Head_Action_Controller {

	public function process(Head_Request $request) {
		if (vglobal('application_unique_key') !== $request->get('applicationKey')) {
			exit;
		}
		if((strpos($_SERVER['HTTP_REFERER'], vglobal('site_URL')) !== false)) {
			exit;
		}

		global $current_user;
		$current_user = Users::getActiveAdminUser();
		
		if($request->get('method') == 'click') {
			$this->clickHandler($request);
		}else{
			$parentId = $request->get('parentId');
			$recordId = $request->get('record');

			if ($parentId && $recordId) {
				$recordModel = Emails_Record_Model::getInstanceById($recordId);
				$recordModel->updateTrackDetails($parentId);
				Head_ShortURL_Helper::sendTrackerImage();
			}
		}
	}
	
	public function clickHandler(Head_Request $request) {
		$parentId = $request->get('parentId');
		$recordId = $request->get('record');

		if ($parentId && $recordId) {
			$recordModel = Emails_Record_Model::getInstanceById($recordId);
			$recordModel->trackClicks($parentId);
		}
		
		$redirectUrl = $request->get('redirectUrl');
		if(!empty($redirectUrl)) {
			return Head_Functions::redirectUrl(rawurldecode($redirectUrl));
		}
	}
}

$track = new Emails_TrackAccess_Action();
$track->process(new Head_Request($_REQUEST));
