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

require_once ('includes/events/EntityData.inc');
require_once ('includes/QueryGenerator/QueryGenerator.php');
require_once ('includes/Zend/Json.php');
require_once ('includes/utils/utils.php');
require_once ('modules/Workflow/WorkflowUtils.php');
require_once ('modules/Workflow/WorkflowManager.inc');
require_once ('modules/Workflow/TaskManager.inc');
require_once ('modules/Workflow/TaskQueue.inc');
require_once ('modules/Workflow/EntityCache.inc');
require_once ('includes/Webservices/WebserviceField.php');
require_once ('includes/Webservices/EntityMeta.php');
require_once ('includes/Webservices/HeadWebserviceObject.php');
require_once ('includes/Webservices/HeadCRMObject.php');
require_once ('includes/Webservices/HeadCRMObjectMeta.php');
require_once ('includes/Webservices/DataTransform.php');
require_once ('includes/Webservices/Retrieve.php');