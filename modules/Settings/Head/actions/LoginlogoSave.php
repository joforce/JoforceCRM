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

class Settings_Head_LoginlogoSave_Action extends Settings_Head_Basic_Action {

	public function process(Head_Request $request) { 
	    $succes = true;
	    $message = vtranslate('Successfully updated');
		$moduleModel = Settings_Head_LogoDetails_Model::getInstance();
		$reloadUrl = $moduleModel->getIndexViewUrl();

		try { 
			$result = $this->Save($request);
		} catch(Exception $e) {
			if($e->getMessage() == "LBL_INVALID_IMAGE") {
				$reloadUrl .= '&error=LBL_INVALID_IMAGE';
				$succes = false;
				$message = vtranslate('LBL_INVALID_IMAGE');
			} 
		}

		if($request->isAjax())  {
            $response = new Head_Response();
            if($succes) {
                $response->setResult(['success' => $succes, 'message' => $message, 'res' => $result]);
            }
            else    {
                $response->setError(419, $message);
            }
            $response->emit(); die;
        }

		// header('Location: ' . $reloadUrl);
	}

	public function Save(Head_Request $request) {
		$moduleModel = Settings_Head_LogoDetails_Model::getInstance();
		$status = false; 

		$saveLogo = $status = true;

		if(!empty($_FILES['logo']['name'])) {
			$logoDetails = $_FILES['logo'];
			$fileType = explode('/', $logoDetails['type']);
			$fileType = $fileType[1];

			if (!$logoDetails['size'] || !in_array($fileType, Settings_Head_LogoDetails_Model::$logoSupportedFormats)) {
				$saveLogo = false;
			} 

			//mime type check
			$mimeType = mime_content_type($logoDetails['tmp_name']);
			$mimeTypeContents = explode('/', $mimeType);
			if (!$logoDetails['size'] || $mimeTypeContents[0] != 'image' || !in_array($mimeTypeContents[1], Settings_Head_LogoDetails_Model::$logoSupportedFormats)) {
				$saveLogo = false;
			} 

			list($width, $height) = getimagesize($_FILES["logo"]["tmp_name"]);		

			if ($width > "150" || $height > "40") {$saveLogo = false; }

			// Check for php code injection
			$imageContents = file_get_contents($_FILES["logo"]["tmp_name"]);
			if (preg_match('/(<\?php?(.*?))/i', $imageContents) == 1) {
				$saveLogo = false;
			} 
			if ($saveLogo) {  
				$moduleModel->saveLogo(); 
			} 
		}else{
			$saveLogo = true;
		}			
		if (!empty($logoDetails['name'])) {
			$fieldValue = decode_html(ltrim(basename(" " . $logoDetails['name'])));
		} else {
			$fieldValue = decode_html($moduleModel->get('imagename'));
		}
		$moduleModel->set("imagename", $fieldValue);
		$moduleModel->save();
		if ($saveLogo && $status) {
			return $moduleModel->getLogoPath();
		} else if (!$saveLogo) {
			throw new Exception('LBL_INVALID_IMAGE',103);
		}
		return $moduleModel->getLogoPath();
	} 
}