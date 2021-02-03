<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/
require_once('modules/Workflow/EntityMethodManager.inc');
class EntityMethodTask extends VTTask{
	
	public $executeImmediately = true;
	
	public function getFieldNames(){return array('methodName');}
	public function doTask($entityData){
		global $adb;
		$emm = new EntityMethodManager($adb);
		$emm->executeMethod($entityData, $this->methodName);
	}
}
?>