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

require_once ('includes/events/VTEntityData.inc');
require_once ('includes/QueryGenerator/QueryGenerator.php');
require_once ('includes/Zend/Json.php');
require_once ('includes/utils/utils.php');
require_once ('modules/com_jo_workflow/VTWorkflowUtils.php');
require_once ('modules/com_jo_workflow/VTWorkflowManager.inc');
require_once ('modules/com_jo_workflow/VTTaskManager.inc');
require_once ('modules/com_jo_workflow/VTTaskQueue.inc');
require_once ('modules/com_jo_workflow/VTEntityCache.inc');
require_once ('includes/Webservices/WebserviceField.php');
require_once ('includes/Webservices/EntityMeta.php');
require_once ('includes/Webservices/HeadWebserviceObject.php');
require_once ('includes/Webservices/HeadCRMObject.php');
require_once ('includes/Webservices/HeadCRMObjectMeta.php');
require_once ('includes/Webservices/DataTransform.php');
require_once ('includes/Webservices/Retrieve.php');