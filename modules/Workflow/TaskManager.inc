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

	/**
	 * Functionality to save and retrieve Tasks from the database.
	 */
	class TaskManager{
		function __construct($adb){
			$this->adb = $adb;
		}

		/**
		 * Save the task into the database.
		 *
		 * When a new task is saved for the first time a field is added to it called
		 * id that stores the task id used in the database.
		 *
		 * @param $summary A summary of the task instance.
		 * @param $task The task instance to save.
		 * @return The id of the task
		 */
		public function saveTask($task){
			$adb = $this->adb;
			if(is_numeric($task->id)){//How do I check whether a member exists in php?
				$taskId = $task->id;
				$adb->pquery("update workflowtasks set summary=?, task=? where task_id=?",
							array($task->summary, serialize($task), $taskId));
				return $taskId;
			}else{
				$taskId = $adb->getUniqueID("workflowtasks");
				$task->id = $taskId;
				$adb->pquery("insert into workflowtasks
							(task_id, workflow_id, summary, task)
							values (?, ?, ?, ?)",
					array($taskId, $task->workflowId, $task->summary, serialize($task)));
				return $taskId;
			}
		}

		public function deleteTask($taskId){
			$adb = $this->adb;
			$adb->pquery("delete from workflowtasks where task_id=?", array($taskId));
		}


		/**
		 * Create a new class instance
		 */
		public function createTask($taskType, $workflowId) {
			$taskTypeInstance = VTTaskType::getInstanceFromTaskType($taskType);
			$taskClass = $taskTypeInstance->get('classname');
			$this->requireTask($taskClass, $taskTypeInstance);
			$task = new $taskClass();
			$task->workflowId=$workflowId;
			$task->summary = "";
			$task->active=true;
			return $task;
		}


		/**
		 * Retrieve a task from the database
		 *
		 * @param $taskId The id of the task to retrieve.
		 * @return VTTask The retrieved task.
		 */
		public function retrieveTask($taskId){
			$adb = $this->adb;
			$result = $adb->pquery("select task from workflowtasks where task_id=?", array($taskId));
			$data = $adb->raw_query_result_rowdata($result, 0);
			$task = $data["task"];
			$task = $this->unserializeTask($task);
			
			return $task;
		}


		/**
		 *
		 */
		public function getTasksForWorkflow($workflowId){
			$adb = $this->adb;
			$result = $adb->pquery("select task from workflowtasks
									where workflow_id=?",
				array($workflowId));
			return $this->getTasksForResult($result);
		}


		/**
		 *
		 */
		public function unserializeTask($str){
			$this->requireTask(self::taskName($str));
			return unserialize($str);

		}
		/**
		 *
		 */
		function getTasks(){
			$adb = $this->adb;
			$result = $adb->query("select task from workflowtasks");
			return $this->getTasksForResult($result);
		}

		private function getTasksForResult($result){
			$adb = $this->adb;
			$it = new SqlResultIterator($adb, $result);
			$tasks = array();
			foreach($it as $row){
				$text = $row->task;

				$this->requireTask(self::taskName($text));
				$tasks[] = unserialize($text);
			}
			return $tasks;
		}

		private function taskName($serializedTask){
			$matches = array();
			preg_match ('/"([^"]+)"/', $serializedTask, $matches);
			return $matches[1];
		}

		private function requireTask($taskType, $taskTypeInstance='') {
			if(!empty($taskTypeInstance)) {
				$taskClassPath = $taskTypeInstance->get('classpath');
				require_once($taskClassPath);
			} else {
                if(!empty($taskType)) {
					require_once("tasks/".$taskType.".inc");
                }
			}
		}

		public function retrieveTemplatePath($moduleName, $taskTypeInstance) {
			$taskTemplatePath = $taskTypeInstance->get('templatepath');
			if(!empty($taskTemplatePath)) {
				return $taskTemplatePath;
			} else {
				$taskType = $taskTypeInstance->get('classname');
				return $moduleName."/taskforms/".$taskType.".tpl";
			}
		}

	}

	abstract class VTTask{
		public abstract function doTask($data);
		public abstract function getFieldNames();

		public function getTimeFieldList() {
			return array();
		}

		public function getContents($entity) {
			return $this->contents;
		}

		public function setContents($contents) {
			$this->contents = $contents;
		}

		public function setRelatedInfo($relatedInfo){
			$this->relatedInfo = $relatedInfo;
		}

		public function getRelatedInfo(){
			return $this->relatedInfo;
		}

		public function hasContents($entity) {
			$taskContents = $this->getContents($entity);
			if ($taskContents) {
				return true;
			}
			return false;
		}

		public function formatTimeForTimePicker($time) {
			list($h, $m, $s) = explode(':', $time);
			$mn = str_pad($m - $m % 15, 2, 0, STR_PAD_LEFT);
			$AM_PM = array('am', 'pm');
			return str_pad(($h%12), 2, 0, STR_PAD_LEFT).':'.$mn.$AM_PM[($h/12)%2];
		}

		public function getMetaVariables() {
			return array(
				'Current Date' => '(general : (__HeadMeta__) date) ($_DATE_FORMAT_)',
				'Current Time' => '(general : (__HeadMeta__) time)',
				'System Timezone' => '(general : (__HeadMeta__) dbtimezone)',
				'User Timezone' => '(general : (__HeadMeta__) usertimezone)',
				'CRM Detail View URL' => '(general : (__HeadMeta__) crmdetailviewurl)',
				'Portal Detail View URL' => '(general : (__HeadMeta__) portaldetailviewurl)',
				'Site Url' => '(general : (__HeadMeta__) siteurl)',
				'Portal Url' => '(general : (__HeadMeta__) portalurl)',
				'Record Id' => '(general : (__HeadMeta__) recordId)',
				'LBL_HELPDESK_SUPPORT_NAME' => '(general : (__HeadMeta__) supportName)',
				'LBL_HELPDESK_SUPPORT_EMAILID' => '(general : (__HeadMeta__) supportEmailid)',
			);
		}

	}

class VTTaskType {
	var $data;

	public function get($key) {
		return $this->data[$key];
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
		return $this;
	}

	public function setData($valueMap) {
		$this->data = $valueMap;
		return $this;
	}

	public static function getInstance($values) {
		$instance = new self();
		return $instance->setData($values);
	}

	public static function registerTaskType($taskType) {
		$adb = PearDatabase::getInstance();
		if (!Head_Utils::CheckTable('workflow_tasktypes')) {
			Head_Utils::CreateTable(
					'workflow_tasktypes',
					"(id int(11) NOTNULL,
						tasktypename varchar(255) NOTNULL,
						label varchar(255),
						classname varchar(255),
						classpath varchar(255),
						templatepath varchar(255),
						modules text(500),
						sourcemodule varchar(255)) ENGINE=InnoDB DEFAULT CHARSET=utf8",
					true);
		}

		$modules = Zend_Json::encode($taskType['modules']);

		$taskTypeId = $adb->getUniqueID("workflow_tasktypes");
		$taskType['id'] = $taskTypeId;

		$adb->pquery("INSERT INTO workflow_tasktypes
									(id, tasktypename, label, classname, classpath, templatepath, modules, sourcemodule)
									values (?,?,?,?,?,?,?,?)",
				array($taskTypeId, $taskType['name'], $taskType['label'], $taskType['classname'],  $taskType['classpath'], $taskType['templatepath'], $modules, $taskType['sourcemodule']));



	}

	public static function getAll($moduleName='') {
		$adb = PearDatabase::getInstance();
		$referenceModules = false;
		
		$result = $adb->pquery("SELECT * FROM workflow_tasktypes",array());
		$numrows = $adb->num_rows($result);
		for ($i = 0; $i < $numrows; $i++) {
			$rawData = $adb->raw_query_result_rowdata($result,$i);
			$taskName = $rawData['tasktypename'];
			$moduleslist = $rawData['modules'];
			$sourceModule = $rawData['sourcemodule'];
			$modules = Zend_Json::decode($moduleslist);
			$includeModules = $modules['include'];
			$excludeModules = $modules['exclude'];
			
			if(in_array($taskName, array('CreateTodoTask','CreateEventTask'))) {
				if($referenceModules == false) {
					$referenceModules = Head_Util_Helper::getCalendarReferenceModulesList();
				}
				$includeModules = array_unique(array_merge($includeModules, $referenceModules));
			}
			if(!empty ($sourceModule)) {
				if(getTabid($sourceModule) == null || !modlib_isModuleActive($sourceModule)) {
					continue;
				}
			}

			if(empty($includeModules) && empty($excludeModules)) {
				$taskTypeInstances[$taskName] = self::getInstance($rawData);
				continue;
			} elseif(!empty($includeModules)) {
				if(in_array($moduleName, $includeModules)) {
					$taskTypeInstances[$taskName] = self::getInstance($rawData);
				}
				continue;
			} elseif(!empty($excludeModules)) {
				if(!(in_array($moduleName, $excludeModules))) {
					$taskTypeInstances[$taskName] = self::getInstance($rawData);
				}
				continue;
			}
		}
		return $taskTypeInstances;
	}

	public static function getInstanceFromTaskType($taskType) {
		$adb = PearDatabase::getInstance();

		$result = $adb->pquery("SELECT * FROM workflow_tasktypes where tasktypename=?",array($taskType));
		$taskTypes['name'] = $adb->query_result($result, 0, 'tasktypename');
		$taskTypes['label'] = $adb->query_result($result, 0, 'label');
		$taskTypes['classname'] = $adb->query_result($result, 0, 'classname');
		$taskTypes['classpath'] = $adb->query_result($result, 0, 'classpath');
		$taskTypes['templatepath'] = $adb->query_result($result, 0, 'templatepath');
		$taskTypes['sourcemodule'] = $adb->query_result($result, 0, 'sourcemodule');

		$taskDetails = self::getInstance($taskTypes);
		return $taskDetails;
	}
}

?>
