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

/**
 * Head JS Script Model Class
 */
class Head_JsScript_Model extends Head_Base_Model {

	const DEFAULT_TYPE = 'text/javascript';

	/**
	 * Function to get the type attribute value
	 * @return <String>
	 */
	public function getType() {
		$type = $this->get('type');
		if(empty($type)){
			$type = self::DEFAULT_TYPE;
		}
		return $type;
	}

	/**
	 * Function to get the src attribute value
	 * @return <String>
	 */
	public function getSrc() {
		$src = $this->get('src');
		if(empty($src)) {
            $src = $this->get('linkurl');
		}
		return $src;
	}

	/**
	 * Static Function to get an instance of Head JsScript Model from a given Head_Link object
	 * @param Head_Link $linkObj
	 * @return Head_JsScript_Model instance
	 */
	public static function getInstanceFromLinkObject (Head_Link $linkObj){
		$objectProperties = get_object_vars($linkObj);
		$linkModel = new self();
		foreach($objectProperties as $properName=>$propertyValue){
			$linkModel->$properName = $propertyValue;
		}
		return $linkModel->setData($objectProperties);
	}
}