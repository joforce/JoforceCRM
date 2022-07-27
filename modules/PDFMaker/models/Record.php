<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class PDFMaker_Record_Model extends Head_Record_Model {
	
	/**
	 * Function to get the id of the record
	 * @return <Number> - Record Id
	 */
	public function getId() {
		return $this->get('pdfmakerid');
	}
	
	/**
	 * Function to set the id of the record
	 * @param <type> $value - id value
	 * @return <Object> - current instance
	 */
	public function setId($value) {
		return $this->set('pdfmakerid',$value);
	}
	
	/**
	 * Function to delete the pdfmaker 
	 * @param type $recordIds
	 */
	public function delete(){
		$this->getModule()->deleteRecord($this);
	}
	
	/**
	 * Function to delete all the pdfmaker 
	 * @param type $recordIds
	 */
	public function deleteAllRecords(){
		$this->getModule()->deleteAllRecords();
	}
	
	/**
	 * Function to get template fields
	 * To get the fields from module, which has the email field
	 * @return <arrray> template fields
	 */
	public function getTemplateFields(){
		return $this->getModule()->getAllModuleFields();
	}
	
	/**
	 * Function to get the Email Template Record
	 * @param type $record
	 * @return <EmailTemplate_Record_Model>
	 */
	
	public function getTemplateData($record){
		return $this->getModule()->getTemplateData($record);
	}
	
	/**
	 * Function to get the Detail View url for the record
	 * @return <String> - Record Detail View Url
	 */
	public function getDetailViewUrl() {
        global $site_URL;
		$module = $this->getModule();
		return $site_URL.$this->getModuleName().'/view/'.$module->getDetailViewName().'/'.$this->getId();
	}
	
	/**
	 * Function to get the instance of Custom View module, given custom view id
	 * @param <Integer> $cvId
	 * @return CustomView_Record_Model instance, if exists. Null otherwise
	 */
	public static function getInstanceById($templateId, $module=null) {
		$db = PearDatabase::getInstance();
		$sql = 'SELECT * FROM jo_pdfmaker WHERE pdfmakerid = ?';
		$params = array($templateId);
		$result = $db->pquery($sql, $params);
		if($db->num_rows($result) > 0) {
			$row = $db->query_result_rowdata($result, 0);
			$recordModel = new self();
			return $recordModel->setData($row)->setModule('PDFMaker');
		}
		return null;
	}
	
	function getEntityType($id) {
		$db = PearDatabase::getInstance();
		$moduleModel = $this->getModule();
		$emailRelatedModules = $moduleModel->getEmailRelatedModules();
		$relatedModule = '';
		if (!empty($id)) {
			$sql = "SELECT setype FROM jo_crmentity WHERE crmid=?";
			$result = $db->pquery($sql, array($id));
			$relatedModule = $db->query_result($result, 0, "setype");

			if(!in_array($relatedModule, $emailRelatedModules)){
				$sql = 'SELECT id FROM jo_users WHERE id=?';
				$result = $db->pquery($sql, array($id));
				if($db->num_rows($result) > 0){
					$relatedModule = 'Users';
				}
			}
		}
		return $relatedModule;
	}

	public function checkUploadSize($documentIds = false) {
		$totalFileSize = 0;
		if (!empty ($_FILES)) {
			foreach ($_FILES as $fileDetails) {
				$totalFileSize = $totalFileSize + (int) $fileDetails['size'];
			}
		}
		if (!empty ($documentIds)) {
			$count = count($documentIds);
			for ($i=0; $i<$count; $i++) {
				$documentRecordModel = Head_Record_Model::getInstanceById($documentIds[$i], 'Documents');
				$totalFileSize = $totalFileSize + (int) $documentRecordModel->get('filesize');
			}
		}

		if ($totalFileSize > vglobal('upload_maxsize')) {
			return false;
		}
		return true;
	}

	/**
	 * Function sends mail
	 */
	public function send($mpdf, $filename, $unserializedValue) {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$rootDirectory =  vglobal('root_directory');

		$mailer = Emails_Mailer_Model::getInstance();
		$mailer->IsHTML(true);
		$emailObj = new Emails_Record_Model();
		$fromEmail = $emailObj->getFromEmailAddress();
		$replyTo = $currentUserModel->get('email1');
		$userName = $currentUserModel->getName();

		// To eliminate the empty value of an array
		$toEmailInfo = array_filter($this->get('toemailinfo'));
		$toMailNamesList = array_filter($this->get('toMailNamesList'));
		foreach($toMailNamesList as $id => $emailData){
			foreach($emailData as $key => $email){
				if($toEmailInfo[$id]){
					array_push($toEmailInfo[$id], $email['value']);
				}
			}
		}
		$emailsInfo = array();
		foreach ($toEmailInfo as $id => $emails) {
			foreach($emails as $key => $value){
				array_push($emailsInfo, $value);
			}
		}

		$toFieldData = array_diff(explode(',', $this->get('saved_toid')), $emailsInfo);
		$toEmailsData = array();
		$i = 1;
		foreach ($toFieldData as $value) {
			$toEmailInfo['to'.$i++] = array($value);
		}
		$attachments = $emailObj->getAttachmentDetails();
		$status = false;

		// Merge Users module merge tags based on current user.
		$mergedDescription = getMergedDescription($this->get('description'), $currentUserModel->getId(), 'Users');
		$mergedSubject = getMergedDescription($this->get('subject'),$currentUserModel->getId(), 'Users');

		foreach($toEmailInfo as $id => $emails) {
			$mailer->reinitialize();
			$mailer->ConfigSenderInfo($fromEmail, $userName, $replyTo);
			$old_mod_strings = vglobal('mod_strings');
			$description = $this->get('description');
			$subject = $this->get('subject');
			$parentModule = $this->getEntityType($id);
			if ($parentModule) {
				$currentLanguage = Head_Language_Handler::getLanguage();
				$moduleLanguageStrings = Head_Language_Handler::getModuleStringsFromFile($currentLanguage,$parentModule);
				vglobal('mod_strings', $moduleLanguageStrings['languageStrings']);

				if ($parentModule != 'Users') {
					// Apply merge for non-Users module merge tags.
					$description = getMergedDescription($mergedDescription, $id, $parentModule);
					$subject = getMergedDescription($mergedSubject, $id, $parentModule);
				} else {
					// Re-merge the description for user tags based on actual user.
					$description = getMergedDescription($description, $id, 'Users');
					$subject = getMergedDescription($mergedSubject, $id, 'Users');
					vglobal('mod_strings', $old_mod_strings);
				}
			}


			if (strpos($description, '$logo$')) {
				$description = str_replace('$logo$',"<img src='cid:logo' />", $description);
				$logo = true;
			}

			foreach($emails as $email) {
				$mailer->Body = '';
				if ($parentModule) {
					$mailer->Body = $emailObj->getTrackImageDetails($id, $emailObj->isEmailTrackEnabled());
				}
				$mailer->Body .= $description;
				$mailer->Signature = str_replace(array('\r\n', '\n'),'<br>',$currentUserModel->get('signature'));
				if($mailer->Signature != '') {
					$mailer->Body.= '<br><br>'.decode_html($mailer->Signature);
				}
				$mailer->Subject = $subject;
				$mailer->AddAddress($email);

				//Adding attachments to mail
				if(is_array($attachments)) {
					foreach($attachments as $attachment) {
						$fileNameWithPath = $rootDirectory.$attachment['path'].$attachment['fileid']."_".$attachment['attachment'];
						if(is_file($fileNameWithPath)) {
							$mailer->AddAttachment($fileNameWithPath, $attachment['attachment']);
						}
					}
				}
				//Attach pdf template
				if($unserializedValue['page_format'])
					$page_format = $unserializedValue['page_format'];
				else
					$page_format = 'A4';

				$mpdf->Output('cache/upload_cache/'.$filename.'.pdf', 'F');
				$mailer->AddAttachment('cache/upload_cache/'.$filename.'.pdf', $filename.'.pdf');

				if ($logo) {
					//While sending email template and which has '$logo$' then it should replace with company logo
					$mailer->AddEmbeddedImage(dirname(__FILE__).'/../../../layouts/skins/images/logo_mail.jpg', 'logo', 'logo.jpg', 'base64', 'image/jpg');
				}

				$ccs = array_filter(explode(',',$this->get('ccmail')));
				$bccs = array_filter(explode(',',$this->get('bccmail')));

				if(!empty($ccs)) {
					foreach($ccs as $cc) $mailer->AddCC($cc);
				}
				if(!empty($bccs)) {
					foreach($bccs as $bcc) $mailer->AddBCC($bcc);
				}
			}
			$status = $mailer->Send(true);
			if(!$status) {
				$status = $mailer->getError();
			} else {
                $status = true;
			}
		}
		return $status;
	}
	/**
	 * Function to set Access count value by default as 0
	 */
	public function setAccessCountValue() {
		$record = $this->getId();
		$moduleName = $this->getModuleName();
		include('modules/Emails/Emails.php');
		$focus = new Emails();
		$focus->setEmailAccessCountValue($record);
	}

}


