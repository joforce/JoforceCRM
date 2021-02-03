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

/*
 * Workflow Task Type Model Class
 */
require_once 'modules/Workflow/TaskManager.inc';

class Settings_Workflows_TaskType_Model extends Head_Base_Model
{

	public function getId()
	{
		return $this->get('id');
	}

	public function getName()
	{
		return $this->get('tasktypename');
	}

	public function getLabel()
	{
		return $this->get('label');
	}

	public function getTemplatePath()
	{
		// TODO - Do required template path transformation once the new template files are created, till the database is updated with new path
		$templatePath = vtemplate_path('Tasks/' . $this->getName() . '.tpl', 'Settings:Workflows');
		return $templatePath;
	}

	public function getEditViewUrl()
	{
		global $site_URL;
		return $site_URL . 'Workflows/Settings/EditTask?type=' . $this->getName();
	}

	public function getV7EditViewUrl()
	{
		global $site_URL;
		return $site_URL . 'Workflows/Settings/EditV7Task?type=' . $this->getName();
	}

	public static function getInstanceFromClassName($taskClass)
	{
		$db = PearDatabase::getInstance();
		$result = $db->pquery("SELECT * FROM workflow_tasktypes where classname=?", array($taskClass));
		$row = $db->query_result_rowdata($result, 0);
		$taskTypeObject = VTTaskType::getInstance($row);
		return self::getInstanceFromTaskTypeObject($taskTypeObject);
	}

	public static function getAllForModule($moduleModel)
	{
		$taskTypes = VTTaskType::getAll($moduleModel->getName());
		$taskTypeModels = array();
		foreach ($taskTypes as $taskTypeObject) {
			$taskTypeModels[] = self::getInstanceFromTaskTypeObject($taskTypeObject);
		}
		return $taskTypeModels;
	}

	public static function getInstance($taskType)
	{
		$taskTypeObject = VTTaskType::getInstanceFromTaskType($taskType);
		return self::getInstanceFromTaskTypeObject($taskTypeObject);
	}

	public static function getInstanceFromTaskTypeObject($taskTypeObject)
	{
		return new self($taskTypeObject->data);
	}

	public function getTaskBaseModule()
	{
		$taskTypeName = $this->get('tasktypename');
		switch ($taskTypeName) {
			case 'CreateTodoTask':
				return Head_Module_Model::getInstance('Calendar');
			case 'CreateEventTask':
				return Head_Module_Model::getInstance('Events');
		}
	}
}
