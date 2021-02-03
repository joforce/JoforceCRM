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
require_once('includes/events/SqlResultIterator.inc');
require_once('WorkflowManager.inc');
require_once('EntityCache.inc');

require_once 'includes/Webservices/Utils.php';
require_once("modules/Users/Users.php");
require_once("includes/Webservices/HeadCRMObject.php");
require_once("includes/Webservices/HeadCRMObjectMeta.php");
require_once("includes/Webservices/DataTransform.php");
require_once("includes/Webservices/WebServiceError.php");
require_once 'includes/utils/utils.php';
require_once 'includes/Webservices/ModuleTypes.php';
require_once('includes/Webservices/Retrieve.php');
require_once('includes/Webservices/Update.php');
require_once 'includes/Webservices/WebserviceField.php';
require_once 'includes/Webservices/EntityMeta.php';
require_once 'includes/Webservices/HeadWebserviceObject.php';
require_once('WorkflowUtils.php');

/*
 * VTEventHandler
 */

class EventHandler extends VTEventHandler {

	/**
	 * Push tasks to the task queue if the conditions are true
	 * @param $entityData A EntityData object representing the entity.
	 */
	function handleEvent($eventName, $entityData, $entityCache = false, $relatedInfo = false) {
		$util = new WorkflowUtils();
		$user = $util->adminUser();
		global $adb;
		$isNew = $entityData->isNew();

		if (!$entityCache) {
			$entityCache = new EntityCache($user);
		}

		$wsModuleName = $util->toWSModuleName($entityData);
		$wsId = vtws_getWebserviceEntityId($wsModuleName,
						$entityData->getId());
		$entityData = $entityCache->forId($wsId);

		/*
		 * Customer - Feature #10254 Configuring all Email notifications including Ticket notifications
		 * workflows are intialised from ModCommentsHandler.php
		 * While adding a comment on any record which are supporting Comments ModCommentsHandler will trigger
		 */
		if (!is_array($this->workflows)) {
			$wfs = new WorkflowManager($adb);
			$this->workflows = $wfs->getWorkflowsForModule($entityData->getModuleName());
		}
		$workflows = $this->workflows;

		foreach ($workflows as $workflow) {
			if (!is_a($workflow, 'Workflow'))
				continue;
			switch ($workflow->executionCondition) {
				case WorkflowManager::$ON_FIRST_SAVE: {
						if ($isNew) {
							$doEvaluate = true;
						} else {
							$doEvaluate = false;
						}
						break;
					}
				case WorkflowManager::$ONCE: {
						$entity_id = vtws_getIdComponents($entityData->getId());
						$entity_id = $entity_id[1];
						if ($workflow->isCompletedForRecord($entity_id)) {
							$doEvaluate = false;
						} else {
							$doEvaluate = true;
						}
						break;
					}
				case WorkflowManager::$ON_EVERY_SAVE: {
						$doEvaluate = true;
						break;
					}
				case WorkflowManager::$ON_MODIFY: {
						$doEvaluate = !($isNew);
						break;
					}
                case WorkflowManager::$MANUAL: {
						$doEvaluate = false;
						break;
					}
				case WorkflowManager::$ON_SCHEDULE:{
						$doEvaluate = false;
						break;
					}
				default: {
						throw new Exception("Should never come here! Execution Condition:" . $workflow->executionCondition);
					}
			}
			if ($doEvaluate && $workflow->evaluate($entityCache, $entityData->getId())) {
				if (WorkflowManager::$ONCE == $workflow->executionCondition) {
					$entity_id = vtws_getIdComponents($entityData->getId());
					$entity_id = $entity_id[1];
					$workflow->markAsCompletedForRecord($entity_id);
				}

				$workflow->performTasks($entityData,$relatedInfo);
			}
		}
		$util->revertUser();
	}

}

?>