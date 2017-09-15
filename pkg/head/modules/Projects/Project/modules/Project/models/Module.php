<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ************************************************************************************/

class Project_Module_Model extends Head_Module_Model {

	public function getSideBarLinks($linkParams) {
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$linkTypes = array('SIDEBARLINK', 'SIDEBARWIDGET');
		$links = parent::getSideBarLinks($linkParams);
		$quickLinks = array();

		$projectTaskInstance = Head_Module_Model::getInstance('ProjectTask');
		if($userPrivilegesModel->hasModulePermission($projectTaskInstance->getId())) {
			$quickLinks[] = array(
								'linktype' => 'SIDEBARLINK',
								'linklabel' => 'LBL_TASKS_LIST',
								'linkurl' => $this->getTasksListUrl(),
								'linkicon' => '',
							);
		}

		$projectMileStoneInstance = Head_Module_Model::getInstance('ProjectMilestone');
		if($userPrivilegesModel->hasModulePermission($projectMileStoneInstance->getId())) {
			$quickLinks[] = array(
							'linktype' => 'SIDEBARLINK',
							'linklabel' => 'LBL_MILESTONES_LIST',
							'linkurl' => $this->getMilestonesListUrl(),
							'linkicon' => '',
						  );
		}

		foreach($quickLinks as $quickLink) {
			$links['SIDEBARLINK'][] = Head_Link_Model::getInstanceFromValues($quickLink);
		}

		return $links;
	}

	public function getTasksListUrl() {
		$taskModel = Head_Module_Model::getInstance('ProjectTask');
		return $taskModel->getListViewUrl();
	}
	public function getMilestonesListUrl() {
		$milestoneModel = Head_Module_Model::getInstance('ProjectMilestone');
		return $milestoneModel->getListViewUrl();
	}

	/*
	 * Function to get supported utility actions for a module
	 */
	function getUtilityActionsNames() {
		return array('Import', 'Export', 'DuplicatesHandling');
	}

	/**
	 * Function to get relation query for particular module with function name
	 * @param <record> $recordId
	 * @param <String> $functionName
	 * @param Head_Module_Model $relatedModule
	 * @return <String>
	 */
	public function getRelationQuery($recordId, $functionName, $relatedModule, $relationId) {
		$relatedModuleName = $relatedModule->getName();
		$query = parent::getRelationQuery($recordId, $functionName, $relatedModule, $relationId);
		return $query;
	}

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if ($sourceModule === 'HelpDesk') {
			$condition = " jo_project.projectid NOT IN (SELECT relcrmid FROM jo_crmentityrel WHERE crmid = '$record' UNION SELECT crmid FROM jo_crmentityrel WHERE relcrmid = '$record') ";

			$pos = stripos($listQuery, 'where');
			if ($pos) {
				$split = spliti('where', $listQuery);
				$overRideQuery = $split[0].' WHERE '.$split[1].' AND '.$condition;
			} else {
				$overRideQuery = $listQuery.' WHERE '.$condition;
			}
			return $overRideQuery;
		}
	}

}