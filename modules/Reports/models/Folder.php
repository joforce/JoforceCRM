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

require_once 'modules/Reports/ReportUtils.php';
class Reports_Folder_Model extends Head_Base_Model {

	/**
	 * Function to get the id of the folder
	 * @return <Number>
	 */
	function getId() {
		return $this->get('folderid');
	}

	/**
	 * Function to set the if for the folder
	 * @param <Number>
	 */
	function setId($value) {
		$this->set('folderid', $value);
	}

	/**
	 * Function to get the name of the folder
	 * @return <String>
	 */
	function getName() {
		return $this->get('foldername');
	}

	/**
	 * Function returns the instance of Folder model
	 * @return <Reports_Folder_Model>
	 */
	public static function getInstance() {
		return new self();
	}

	/**
	 * Function saves the folder
	 */
	function save() {
		$db = PearDatabase::getInstance();

		$folderId = $this->getId();
		if(!empty($folderId)) {
			$db->pquery('UPDATE jo_reportfolder SET foldername = ?, description = ? WHERE folderid = ?',
					array($this->getName(), $this->getDescription(), $folderId));
		} else {
			$result = $db->pquery('SELECT MAX(folderid) AS folderid FROM jo_reportfolder', array());
			$folderId = (int) ($db->query_result($result, 0, 'folderid')) + 1;

			$db->pquery('INSERT INTO jo_reportfolder(folderid, foldername, description, state) VALUES(?, ?, ?, ?)', array($folderId, $this->getName(), $this->getDescription(), 'CUSTOMIZED'));
			$this->set('folderid', $folderId);
		}
	}

	/**
	 * Function deletes the folder
	 */
	function delete() {
		$db = PearDatabase::getInstance();
		$db->pquery('DELETE FROM jo_reportfolder WHERE folderid = ?', array($this->getId()));
	}

	/**
	 * Function returns Report Models for the folder
	 * @param <Head_Paging_Model> $pagingModel
	 * @return <Reports_Record_Model>
	 */
	function getReports($pagingModel) {
		$paramsList = array(
						'startIndex'=>$pagingModel->getStartIndex(),
						'pageLimit'=>$pagingModel->getPageLimit(),
						'orderBy'=>$this->get('orderby'),
						'sortBy'=>$this->get('sortby'));

		$reportClassInstance = Head_Module_Model::getClassInstance('Reports');

		$fldrId = $this->getId ();
		if($fldrId == 'All') {
			$paramsList = array( 'startIndex'=>$pagingModel->getStartIndex(),
								 'pageLimit'=>$pagingModel->getPageLimit(),
								 'orderBy'=>$this->get('orderby'),
								 'sortBy'=>$this->get('sortby')
							);
		}
		$paramsList['searchParams'] = $this->get('search_params');

		$reportsList = $reportClassInstance->sgetRptsforFldr($fldrId, $paramsList);
		$reportsCount = count($reportsList);

		$pageLimit = $pagingModel->getPageLimit();
		if($reportsCount > $pageLimit){
			array_pop($reportsList);
			$pagingModel->set('nextPageExists', true);
		}else{
			$pagingModel->set('nextPageExists', false);
		}

		$reportModuleModel = Head_Module_Model::getInstance('Reports');

		if($fldrId == 'All' || $fldrId == 'shared') {
			return $this->getAllReportModels($reportsList, $reportModuleModel);
		} else {
			$reportModels = array();
			for($i=0; $i < count($reportsList); $i++) {
				$reportModel = new Reports_Record_Model();

				$reportModel->setData($reportsList[$i])->setModuleFromInstance($reportModuleModel);
				$reportModels[] = $reportModel;
				unset($reportModel);
			}
			return $reportModels;
		}
	}

	/**
	 * Function to get the description of the folder
	 * @return <String>
	 */
	function getDescription() {
		return $this->get('description');
	}

	/**
	 * Function to get the url for edit folder from list view of the module
	 * @return <string> - url
	 */
	function getEditUrl() {
		return 'index.php?module=Reports&view=EditFolder&folderid='.$this->getId();
	}

	/**
	 * Function to get the url for delete folder from list view of the module
	 * @return <string> - url
	 */
	function getDeleteUrl() {
		return 'index.php?module=Reports&action=Folder&mode=delete&folderid='.$this->getId();
	}

	/**
	 * Function returns the instance of Folder model
	 * @param FolderId
	 * @return <Reports_Folder_Model>
	 */
	public static function getInstanceById($folderId) {
		$folderModel = Head_Cache::get('reportsFolder',$folderId);
		if(!$folderModel){
			$db = PearDatabase::getInstance();
			$folderModel = Reports_Folder_Model::getInstance();

			$result = $db->pquery("SELECT * FROM jo_reportfolder WHERE folderid = ?", array($folderId));

			if ($db->num_rows($result) > 0) {
				$values = $db->query_result_rowdata($result, 0);
				$folderModel->setData($values);
			}
			Head_Cache::set('reportsFolder',$folderId,$folderModel);
		}
		return $folderModel;
	}

	/**
	 * Function returns the instance of Folder model
	 * @return <Reports_Folder_Model>
	 */
	public static function getAll() {
		$db = PearDatabase::getInstance();
		$folders = Head_Cache::get('reports', 'folders');
		if (!$folders) {
			$folders = array();
			$result = $db->pquery("SELECT * FROM jo_reportfolder ORDER BY foldername ASC", array());
			$noOfFolders = $db->num_rows($result);
			if ($noOfFolders > 0) {
				for ($i = 0; $i < $noOfFolders; $i++) {
					$folderModel = Reports_Folder_Model::getInstance();
					$values = $db->query_result_rowdata($result, $i);
					$folders[$values['folderid']] = $folderModel->setData($values);
					Head_Cache::set('reportsFolder',$values['folderid'],$folderModel);
				}
			}
			Head_Cache::set('reports','folders',$folders);
		}
		return $folders;
	}

	/**
	 * Function returns duplicate record status of the module
	 * @return true if duplicate records exists else false
	 */
	function checkDuplicate() {
		$db = PearDatabase::getInstance();

		$query = 'SELECT 1 FROM jo_reportfolder WHERE foldername = ?'; 
		$params = array($this->getName());
		$folderId = $this->getId();
		if ($folderId) {
			$query .= ' AND folderid != ?';
			array_push($params, $folderId);
		}
		$folderName = $this->getName();
		$result = $db->pquery($query, $params);

		if (($db->num_rows($result) > 0)||($folderName == "Shared With Me")||($folderName == "All Reports")) {
			return true;
		}
		return false;
	}

	/**
	 * Function returns whether reports are exist or not in this folder
	 * @return true if exists else false
	 */
	function hasReports() {
		$db = PearDatabase::getInstance();

		$result = $db->pquery('SELECT 1 FROM jo_report WHERE folderid = ?', array($this->getId()));

		if ($db->num_rows($result) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Function returns whether folder is Default or not
	 * @return true if it is read only else false
	 */
	function isDefault() {
		if ($this->get('state') == 'SAVED') {
			return true;
		}
		return false;
	}

	/**
	 * Function to get info array while saving a folder
	 * @return Array  info array
	 */
	public function getInfoArray() {
		return array('folderId' => $this->getId(),
			'folderName' => $this->getName(),
			'editURL' => $this->getEditUrl(),
			'deleteURL' => $this->getDeleteUrl(),
			'isEditable' => $this->isEditable(),
			'isDeletable' => $this->isDeletable());
	}

	/**
	 * Function to check whether folder is editable or not
	 * @return <boolean>
	 */
	public function isEditable() {
		if ($this->isDefault()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to get check whether folder is deletable or not
	 * @return <boolean>
	 */
	public function isDeletable() {
		if ($this->isDefault()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to calculate number of reports in this folder
	 * @return <Integer>
	 */
	public function getReportsCount() {
		$db = PearDatabase::getInstance();
		$params = array();

		// To get the report ids which are permitted for the user
			$query = "SELECT reportmodulesid, primarymodule from jo_reportmodules";
			$result = $db->pquery($query, array());
			$noOfRows = $db->num_rows($result);
			$allowedReportIds = array();
			for($i=0;$i<$noOfRows;$i++){
				$primaryModule = $db->query_result($result,$i,'primarymodule');
				$reportid = $db->query_result($result,$i,'reportmodulesid');
				if(isPermitted($primaryModule,'index') == "yes"){
					$allowedReportIds[] = $reportid;
				}
			}
		//End
		$sql = "SELECT count(*) AS count FROM jo_report
				INNER JOIN jo_reportfolder ON jo_reportfolder.folderid = jo_report.folderid AND 
				jo_report.reportid in (".implode(',',$allowedReportIds).")";
		$fldrId = $this->getId();
		if($fldrId == 'All') {
			$fldrId = false;
		}

		// If information is required only for specific report folder?
		if($fldrId !== false) {
			$sql .= " WHERE jo_reportfolder.folderid=?";
			array_push($params,$fldrId);
		}

		$searchParams = $this->get('searchParams');
		$searchCondition = getReportSearchCondition($searchParams, $fldrId);
		if($searchCondition) {
			$sql .= $searchCondition;
		}

		$currentUserModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserModel->isAdminUser()) {
			$currentUserId = $currentUserModel->getId();

			$groupId = implode(',',$currentUserModel->get('groups'));
			if ($groupId) {
				$groupQuery = "(SELECT reportid from jo_reportsharing WHERE shareid IN ($groupId) AND setype = 'groups') OR ";
			}

			$sql .= " AND (jo_report.reportid IN (SELECT reportid from jo_reportsharing WHERE $groupQuery shareid = ? AND setype = 'users')
						OR jo_report.sharingtype = 'Public'
						OR jo_report.owner = ?
						OR jo_report.owner IN (SELECT jo_user2role.userid FROM jo_user2role
													INNER JOIN jo_users ON jo_users.id = jo_user2role.userid
													INNER JOIN jo_role ON jo_role.roleid = jo_user2role.roleid
													WHERE jo_role.parentrole LIKE ?))";

			$parentRoleSeq = $currentUserModel->get('parent_role_seq').'::%';
			array_push($params, $currentUserId, $currentUserId, $parentRoleSeq);
		}
		$result = $db->pquery($sql, $params);
		return $db->query_result($result, 0, 'count');
	}

	/**
	 * Function to get all Report Record Models
	 * @param <Array> $allReportsList
	 * @param <Head_Module_Model> - Reports Module Model
	 * @return <Array> Reports Record Models
	 */
	public function getAllReportModels($allReportsList, $reportModuleModel){
		$allReportModels = array();
		$folders = self::getAll();
		foreach ($allReportsList as $key => $reportsList) {
			$reportModel = new Reports_Record_Model();
			$reportModel->setData($reportsList)->setModuleFromInstance($reportModuleModel);
			$reportModel->set('foldername', $folders[$reportsList['folderid']]->getName());
			$allReportModels[] = $reportModel;
			unset($reportModel);
		}
		return $allReportModels;
	}

	 /**
	 * Function which provides the records for the current view
	 * @param <Boolean> $skipRecords - List of the RecordIds to be skipped
	 * @return <Array> List of RecordsIds
	 */
	public function getRecordIds($skipRecords=false, $module, $searchParams = array()) {
		$db = PearDatabase::getInstance();
		$baseTableName = "jo_report";
		$baseTableId = "reportid";
		$folderId = $this->getId();
		$listQuery = $this->getListViewQuery($folderId, $searchParams);

		if($skipRecords && !empty($skipRecords) && is_array($skipRecords) && count($skipRecords) > 0) {
			$listQuery .= ' AND '.$baseTableName.'.'.$baseTableId.' NOT IN ('. implode(',', $skipRecords) .')';
		}
		$result = $db->query($listQuery);
		$noOfRecords = $db->num_rows($result);
		$recordIds = array();
		for($i=0; $i<$noOfRecords; ++$i) {
			$recordIds[] = $db->query_result($result, $i, $baseTableId);
		}
		return $recordIds;
	}

	/**
	 * Function returns Report Models for the folder
	 * @return <Reports_Record_Model>
	 */
	function getListViewQuery($folderId, $searchParams = array()) {
		$sql = "select jo_report.*, jo_reportmodules.*, jo_reportfolder.folderid from jo_report 
				inner join jo_reportfolder on jo_reportfolder.folderid = jo_report.folderid 
				inner join jo_reportmodules on jo_reportmodules.reportmodulesid = jo_report.reportid ";

		if($folderId != "All") {
				$sql = $sql." where jo_reportfolder.folderid = ".$folderId;
		}  
		$searchCondition = getReportSearchCondition($searchParams, $folderId);
		if($searchCondition) {
			$sql .= $searchCondition;
		}
		return $sql;
	}
}
