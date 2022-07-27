<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */
vimport('~~/modules/ModTracker/core/ModTracker_Basic.php');

class ModTracker_Record_Model extends Head_Record_Model {

	const UPDATE = 0;
	const DELETE = 1;
	const CREATE = 2;
	const RESTORE = 3;
	const LINK = 4;
	const UNLINK = 5;

	/**
	 * Function to get the history of updates on a record
	 * @param <type> $record - Record model
	 * @param <type> $limit - number of latest changes that need to retrieved
	 * @return <array> - list of  ModTracker_Record_Model
	 */
	public static function getUpdates($parentRecordId, $pagingModel,$moduleName,$filter_date) {
		if($moduleName == 'Calendar') {
			if(getActivityType($parentRecordId) != 'Task') {
				$moduleName = 'Events';
			}
		}
		$db = PearDatabase::getInstance();
		$recordInstances = array();
		$date = '';

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		$listQuery = "SELECT * FROM jo_modtracker_basic WHERE 1=1";
		if($filter_date != '') {
			$listQuery .= " and changedon like '".$filter_date."%'";
		}
		$listQuery .= " and crmid = ? AND module = ? ".
						" ORDER BY changedon DESC LIMIT $startIndex, $pageLimit";

		$result = $db->pquery($listQuery, array($parentRecordId, $moduleName));
		$rows = $db->num_rows($result);
		$dateArray = [];
		for ($i=0; $i<$rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$recordInstance = new self();
			$date = explode(" ", $row['changedon']);
			$row["date"] = date_format(date_create($date[0]),"M d Y");
			$row["time"] = date('h:i A', strtotime($row['changedon']));

			$recordInstance->setData($row)->setParent($row['crmid'], $row['module']);
			$recordInstances[] = $recordInstance;
		}
		return $recordInstances;
	}

	public static function getDateUpdates($parentRecordId, $pagingModel,$moduleName,$filter_date) {
		if($moduleName == 'Calendar') {
			if(getActivityType($parentRecordId) != 'Task') {
				$moduleName = 'Events';
			}
		}
		$db = PearDatabase::getInstance();
		$recordInstances = array();
		$date = '';

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		$listQuery = "SELECT * FROM jo_modtracker_basic WHERE 1=1";
		if($filter_date != '') {
			$listQuery .= " and changedon like '".$filter_date."%'";
		}
		$listQuery .= " and crmid = ? AND module = ? ".
						" ORDER BY changedon DESC LIMIT $startIndex, $pageLimit";

		$result = $db->pquery($listQuery, array($parentRecordId, $moduleName));
		$rows = $db->num_rows($result);
		for ($i=0; $i<$rows; $i++) {
            $row = $db->query_result_rowdata($result, $i);
			$date = explode(" ", $row['changedon']);
            $recordInstances[] = date_format(date_create($date[0]),"M d Y");
        }
        return array_unique($recordInstances);
	}

	public static function getActivities($userId, $moduleName, $filters = []) {
		$db = PearDatabase::getInstance();
		$recordInstances = array();
		$date = '';
		$listQuery = "SELECT * FROM jo_modtracker_basic WHERE ";
		
		if(count($filters) > 0) {
			$userId = $filters['user_id'];
			$listQuery .= "whodid = ? ";
			if($filters['date'] != '') {
				$listQuery .= "and changedon like '".$filters['date']."%'";
			}
		} else {
			$listQuery .= "whodid = ?  ";
		}
		
		$listQuery .= " ORDER BY changedon DESC";
		
		$result = $db->pquery($listQuery, array($userId));
		$rows = $db->num_rows($result);
		$dateArray = [];

		for ($i=0; $i<$rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$recordInstance = new self();
			$date = explode(" ", $row['changedon']);
			$row["date"] = date_format(date_create($date[0]),"M d Y");
			$row["time"] = date('h:i A', strtotime($row['changedon']));						

			$recordInstance->setData($row)->setParent($row['crmid'], $row['module']);
			$recordInstances[] = $recordInstance;
		}
		return $recordInstances;
	}

	function setParent($id, $moduleName) {
		if(!Head_Util_Helper::checkRecordExistance($id)) {
			$this->parent = Head_Record_Model::getInstanceById($id, $moduleName);
		} else {
			$this->parent = Head_Record_Model::getCleanInstance($moduleName);
			$this->parent->id = $id;
			$this->parent->setId($id);
		}
	}

	function getParent() {
		return $this->parent;
	}

	function checkStatus($callerStatus) {
		$status = $this->get('status');
		if ($status == $callerStatus) {
			return true;
		}
		return false;
	}

	function isCreate() {
		return $this->checkStatus(self::CREATE);
	}

	function isUpdate() {
		return $this->checkStatus(self::UPDATE);
	}

	function isDelete() {
		return $this->checkStatus(self::DELETE);
	}

	function isRestore() {
		return $this->checkStatus(self::RESTORE);
	}

	function isRelationLink() {
		return $this->checkStatus(self::LINK);
	}

	function isRelationUnLink() {
		return $this->checkStatus(self::UNLINK);
	}

	function getModifiedBy() {
		$changeUserId = $this->get('whodid');
		return Users_Record_Model::getInstanceById($changeUserId, 'Users');
	}

	function getActivityTime() {
		return $this->get('changedon');
	}

	function getFieldInstances() {
		$id = $this->get('id');
		$db = PearDatabase::getInstance();

		$fieldInstances = array();
		if($this->isCreate() || $this->isUpdate()) {
			$result = $db->pquery('SELECT * FROM jo_modtracker_detail WHERE id = ?', array($id));
			$rows = $db->num_rows($result);
			for($i=0; $i<$rows; $i++) {
				$data = $db->query_result_rowdata($result, $i);
				$row = array_map('decode_html', $data);

				if($row['fieldname'] == 'record_id' || $row['fieldname'] == 'record_module') continue;

				$fieldModel = Head_Field_Model::getInstance($row['fieldname'], $this->getParent()->getModule());
				if(!$fieldModel) continue;
				
				$fieldInstance = new ModTracker_Field_Model();
				$fieldInstance->setData($row)->setParent($this)->setFieldInstance($fieldModel);
				$fieldInstances[] = $fieldInstance;
			}
		}
		return $fieldInstances;
	}

	function getRelationInstance() {
		$id = $this->get('id');
		$db = PearDatabase::getInstance();

		if($this->isRelationLink() || $this->isRelationUnLink()) {
			$result = $db->pquery('SELECT * FROM jo_modtracker_relations WHERE id = ?', array($id));
			$row = $db->query_result_rowdata($result, 0);
			$relationInstance = new ModTracker_Relation_Model();
			$relationInstance->setData($row)->setParent($this);
		}
		return $relationInstance;
	}
        
	public function getTotalRecordCount($recordId) {
    	$db = PearDatabase::getInstance();
        $result = $db->pquery("SELECT COUNT(*) AS count FROM jo_modtracker_basic WHERE crmid = ?", array($recordId));
        return $db->query_result($result, 0, 'count');
	}
}