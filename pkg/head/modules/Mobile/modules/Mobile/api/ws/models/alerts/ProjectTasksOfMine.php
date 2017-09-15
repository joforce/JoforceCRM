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
include_once dirname(__FILE__) . '/../Alert.php';
class Mobile_WS_AlertModel_ProjectTasksOfMine extends Mobile_WS_AlertModel {
	function __construct() {
		parent::__construct();
		$this->name = 'My Project Task';
		$this->moduleName = 'ProjectTask';
		$this->refreshRate= 1 * (24* 60 * 60); // 1 day
		$this->description='Project Task Assigned To Me';
	}

	function query() {
		$sql = "SELECT crmid FROM jo_crmentity INNER JOIN jo_projecttask ON 
                    jo_projecttask.projecttaskid=jo_crmentity.crmid WHERE jo_crmentity.deleted=0 AND jo_crmentity.smownerid=? AND
                    jo_projecttask.projecttaskprogress <> '100%';";
		return $sql;
	}
        function queryParameters() {
		return array($this->getUser()->id);
	}

	
}

