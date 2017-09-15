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

vimport('~~/vtlib/Head/Net/Client.php');
vimport('~~/vtlib/Head/Package.php');

class Settings_ModuleManager_Extension_Model extends Head_Base_Model {

	STATIC $EXTENSION_LOOKUP_URL = false;
	STATIC $EXTENSION_MANAGER_URL= false;

	var $fileName;

	public static function getUploadDirectory($isChild = false) {
		$uploadDir .= 'test/vtlib';
		if ($isChild) {
			$uploadDir = '../'.$uploadDir;
		}
		return $uploadDir;
	}

	public static function getExtensionsLookUpUrl() {
		return self::$EXTENSION_LOOKUP_URL;
	}

	public static function getExtensionsManagerUrl() {
		return self::$EXTENSION_MANAGER_URL;
	}

	/**
	 * Function to set id for this instance
	 * @param <Integer> $extensionId
	 * @return <type>
	 */
	public function setId($extensionId) {
		$this->set('id', $extensionId);
		return $this;
	}

	/**
	 * Function to set file name for this instance
	 * @param <type> $fileName
	 * @return <type>
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
		return $this;
	}

	/**
	 * Function to get Id of this instance
	 * @return <Integer> id
	 */
	public function getId() {
		return $this->get('id');
	}

	/**
	 * Function to get name of this instance
	 * @return <String> module name
	 */
	public function getName() {
		return $this->get('name');
	}

	/**
	 * Function to get file name of this instance
	 * @return <String> file name
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * Function to get package of this instance
	 * @return <Head_Package> package object
	 */
	public function getPackage() {
		$packageModel = new Head_Package();
		$moduleName = $packageModel->getModuleNameFromZip(self::getUploadDirectory(). '/' .$this->getFileName());
		if ($moduleName) {
			return $packageModel;
		}
		return false;
	}

	/**
	 * Function to check whether it is compatible with vtiger or not
	 * @return <boolean> true/false
	 */
	public function isHeadCompatible() {
		vimport('~~/vtlib/Head/Version.php');
		$vtigerVersion = $this->get('vtigerVersion');
		$vtigerMaxVersion = $this->get('vtigerMaxVersion');

		if ((Head_Version::check($vtigerVersion, '>=') && $vtigerMaxVersion && Head_Version::check($vtigerMaxVersion, '<'))
				|| Head_Version::check($vtigerVersion, '=')) {
			return true;
		}
		return false;
	}

	/**
	 * Function to check whether the module is already exists or not
	 * @return <true/false>
	 */
	public function isAlreadyExists() {
		$moduleName = $this->getName();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if($moduleModel) {
			return true;
		}
		return false;
	}

	/**
	 * Function to check whether the module is upgradable or not
	 * @return <type>
	 */
	public function isUpgradable() {
		$moduleName = $this->getName();
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		if ($moduleModel) {
			if ($moduleModel->get('version') < $this->get('pkgVersion')) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Function to store the details of tracking
	 * @return <boolean> true/false
	 */
	public function installTrackDetails() {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		$client = new Head_Net_Client(self::getExtensionsManagerUrl() . '/api.php');
		$client->setHeaders(array('Referer' => vglobal('site_URL')));

		$params['operation'] = 'extensionTrack';
		$params['extensionid'] = $this->getId();
		$params['email'] = $currentUserModel->get('email1');
		$params['lname'] = $currentUserModel->get('last_name');
		$params['fname'] = $currentUserModel->get('first_name');

		$client->doGet($params);
		return true;
	}

	/**
	 * Function to get instance by using XML node
	 * @param <XML DOM> $extensionXMLNode
	 * @return <Settings_ModuleManager_Extension_Model> $extensionModel
	 */
	public static function getInstanceFromXMLNodeObject($extensionXMLNode) {
		$extensionModel = new self();
		$objectProperties = get_object_vars($extensionXMLNode);

		foreach($objectProperties as $propertyName => $propertyValue) {
			$propertyValue = (string)$propertyValue;
			if ($propertyName === 'description') {
				$propertyValue = nl2br(str_replace(array('<','>'), array('&lt;', '&gt;'), br2nl(trim($propertyValue))));
			}
			$extensionModel->set($propertyName, $propertyValue);
		}

		$label = $extensionModel->get('label');
		if (!$label) {
			$extensionModel->set('label', $extensionModel->getName());
		}
		return $extensionModel;
	}

	/**
	 * Function to get instance by using id
	 * @param <Integer> $extensionId
	 * @param <String> $fileName
	 * @return <Settings_ModuleManager_Extension_Model> $extension Model
	 */
	public static function getInstanceById($extensionId, $fileName = false) {
		$uploadDir = self::getUploadDirectory();
		if ($fileName) {
			if (is_dir($uploadDir)) {
				$uploadFileName = "$uploadDir/$fileName";
				checkFileAccess(self::getUploadDirectory());

				$extensionModel = new self();
				$extensionModel->setId($extensionId)->setFileName($fileName);
				return $extensionModel;
			}
		} else {
			if (!is_dir($uploadDir)) {
				mkdir($uploadDir);
			}
			$uploadFile = 'usermodule_'. time() . '.zip';
			$uploadFileName = "$uploadDir/$uploadFile";
			checkFileAccess(self::getUploadDirectory());

			$packageAvailable = Settings_ModuleManager_Extension_Model::download($extensionId, $uploadFileName);
			if ($packageAvailable) {
				$extensionModel = new self();
				$extensionModel->setId($extensionId)->setFileName($uploadFile);
				return $extensionModel;
			}
		}
		return false;
	}

	/**
	 * Function to get all availible extensions
	 * @param <Object> $xmlContent
	 * @return <Array> list of extensions <Settings_ModuleManager_Extension_Model>
	 */
	public static function getAll() {
		$extensionModelsList = array();
		$extensionLookUpUrl = self::getExtensionsLookUpUrl();
		if ($extensionLookUpUrl) {
			$clientModel = new Head_Net_Client($extensionLookUpUrl);
			$xmlContent = $clientModel->doGet();

			if (!$extensionModelsList && $xmlContent && !stripos($xmlContent, "<?xml")) {
				$extensionsXML = simplexml_load_string($xmlContent);
				if ($extensionsXML->extension) {
					foreach ($extensionsXML->extension as $extensionXMLNode) {
						$extensionModelsList[(string) ($extensionXMLNode->id)] = self::getInstanceFromXMLNodeObject($extensionXMLNode);
					}
				}
			}
		}
		return $extensionModelsList;
	}

	/**
	 * Function to download the file of this instance
	 * @param <Integer> $extensionId
	 * @param <String> $targetFileName
	 * @return <boolean> true/false
	 */
	public static function download($extensionId, $targetFileName) {
		$extensions = self::getAll();
		$downloadURL = $extensions[$extensionId]->get('downloadURL');

		if ($downloadURL) {
			$clientModel = new Head_Net_Client($downloadURL);
			file_put_contents($targetFileName, $clientModel->doGet());
			return true;
		}
		return false;
	}
}