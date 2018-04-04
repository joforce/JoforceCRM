<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

if(defined('VTIGER_UPGRADE')) {
	global $adb, $current_user;
	$db = PearDatabase::getInstance();

	$result = $db->pquery('SELECT 1 FROM jo_ws_fieldtype WHERE uitype=?', array('98'));
	if (!$db->num_rows($result)) {
		$db->pquery('INSERT INTO jo_ws_fieldtype(uitype,fieldtype) VALUES(?, ?)', array('98', 'reference'));
	}

	$result = $db->pquery('SELECT fieldtypeid FROM jo_ws_fieldtype WHERE uitype=(SELECT DISTINCT uitype FROM jo_field WHERE fieldname=?)', array('modifiedby'));
	if ($db->num_rows($result)) {
		$fieldTypeId = $db->query_result($result, 0, 'fieldtypeid');
		$referenceResult = $db->pquery('SELECT * FROM jo_ws_referencetype WHERE fieldtypeid=?', array($fieldTypeId));
		while($rowData = $db->fetch_row($referenceResult)) {
			$type = $rowData['type'];
			if ($type != 'Users') {
				$db->pquery('DELETE FROM jo_ws_referencetype WHERE fieldtypeid=? AND type=?', array($fieldTypeId, $type));
			}
		}
	}

	if (!Head_Utils::CheckTable('jo_activity_recurring_info')) {
		$db->pquery('CREATE TABLE IF NOT EXISTS jo_activity_recurring_info(activityid INT(19) NOT NULL, recurrenceid INT(19) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=UTF8;', array());
	}

	$columns = $db->getColumnNames('jo_crmentity');
	if (!in_array('smgroupid', $columns)) {
		$db->pquery('ALTER TABLE jo_crmentity ADD COLUMN smgroupid INT(19)', array());
	}

	require_once 'modules/com_jo_workflow/VTWorkflowManager.inc';
	$result = $db->pquery('SELECT DISTINCT workflow_id FROM com_jo_workflows WHERE summary=?', array('Ticket Creation From Portal : Send Email to Record Owner and Contact'));
	if ($db->num_rows($result)) {
		$wfs = new VTWorkflowManager($db);
		$workflowModel = $wfs->retrieve($db->query_result($result, 0, 'workflow_id'));

		$selectedFields = array();
		$conditions = Zend_Json::decode(html_entity_decode($workflowModel->test));
		if ($conditions) {
			foreach ($conditions as $conditionKey => $condition) {
				if ($condition['fieldname'] == 'from_portal') {
					$selectedFieldKeys[] = $conditionKey;
				}
			}
			foreach ($selectedFieldKeys as $key => $conditionKey) {
				if ($key) {
					unset($conditions[$conditionKey]);
				}
			}
			$workflowModel->name = $workflowModel->description;
			$workflowModel->test = Zend_Json::encode($conditions);
			$wfs->save($workflowModel);
		}
	}

	$db->pquery('UPDATE jo_ws_entity SET handler_path=?, handler_class=? WHERE name IN("Products","Services")', array('includes/Webservices/HeadProductOperation.php', 'HeadProductOperation'));
	$db->pquery('UPDATE jo_def_org_share SET editstatus=? WHERE tabid=?', array(0, getTabid('Contacts')));
	$db->pquery('UPDATE jo_settings_field SET name=? WHERE name=?', array('Configuration Editor', 'LBL_CONFIG_EDITOR'));
	$db->pquery('UPDATE jo_links SET linktype=? WHERE linklabel=?', array('DETAILVIEW', 'LBL_SHOW_ACCOUNT_HIERARCHY'));
	$db->pquery('UPDATE jo_field SET typeofdata=? WHERE fieldname IN (?, ?)', array('DT~O', 'createdtime', 'modifiedtime'));
	$db->pquery('UPDATE jo_field SET presence=0 WHERE columnname=? AND fieldname=?', array('emailoptout', 'emailoptout'));
	$db->pquery('UPDATE jo_field SET defaultvalue=? WHERE fieldname=?', array('1', 'discontinued'));
	$db->pquery('UPDATE jo_field SET defaultvalue=? WHERE fieldname=?', array('.', 'currency_decimal_separator'));
	$db->pquery('UPDATE jo_field SET defaultvalue=? WHERE fieldname=?', array(',', 'currency_grouping_separator'));

	$lineItemModules = array('Products' => 'jo_products', 'Services' => 'jo_service');
	foreach ($lineItemModules as $moduleName => $tableName) {
		$moduleInstance = Head_Module::getInstance($moduleName);
		$blockInstance = Head_Block::getInstance('LBL_PRICING_INFORMATION', $moduleInstance);
		if ($blockInstance) {
			$fieldInstance = Head_Field::getInstance('purchase_cost', $moduleInstance);
			if (!$fieldInstance) {
				$fieldInstance = new Head_Field();
				$fieldInstance->name		= 'purchase_cost';
				$fieldInstance->column		= 'purchase_cost';
				$fieldInstance->label		= 'Purchase Cost';
				$fieldInstance->columntype	= 'decimal(27,8)';
				$fieldInstance->table		= $tableName;
				$fieldInstance->typeofdata	= 'N~O';
				$fieldInstance->uitype		= '71';
				$fieldInstance->presence	= '0';

				$blockInstance->addField($fieldInstance);
			}
		}
	}

	$columns = $db->getColumnNames('jo_relatedlists');
	if (!in_array('relationfieldid', $columns)) {
		$db->pquery('ALTER TABLE jo_relatedlists ADD COLUMN relationfieldid INT(19)', array());
	}
	if (!in_array('source', $columns)) {
		$db->pquery('ALTER TABLE jo_relatedlists ADD COLUMN source VARCHAR(25)', array());
	}
	if (!in_array('relationtype', $columns)) {
		$db->pquery('ALTER TABLE jo_relatedlists ADD COLUMN relationtype VARCHAR(10)', array());
	}
	$result = $db->pquery('SELECT relation_id FROM jo_relatedlists ORDER BY relation_id DESC LIMIT 1', array());
	$db->pquery('UPDATE jo_relatedlists_seq SET id=?', array($db->query_result($result, 0, 'relation_id')));

	$accountsTabId = getTabId('Accounts');
	$db->pquery('UPDATE jo_relatedlists SET name=? WHERE name=? and tabid=?', array('get_merged_list', 'get_dependents_list', $accountsTabId));

	$invoiceModuleInstance = Head_Module::getInstance('Invoice');
	$blockInstance = Head_Block::getInstance('LBL_INVOICE_INFORMATION', $invoiceModuleInstance);
	if ($blockInstance) {
		$fieldInstance = Head_Field::getInstance('potential_id', $invoiceModuleInstance);
		if (!$fieldInstance) {
			$field = new Head_Field();
			$field->name			= 'potential_id';
			$field->label			= 'Potential Name';
			$field->uitype			= 10;
			$field->typeofdata		= 'I~O';

			$blockInstance->addField($field);
			$field->setRelatedModules(Array('Potentials'));

			$oppModuleModel = Head_Module_Model::getInstance('Potentials');
			$oppModuleModel->setRelatedlist($invoiceModuleInstance, 'Invoice', array('ADD'), 'get_dependents_list');
		}
	}

	$documentsModuleModel = Head_Module_Model::getInstance('Documents');
	$noteContentFieldModel = Head_Field_Model::getInstance('notecontent', $documentsModuleModel);
	if ($noteContentFieldModel) {
		$noteContentFieldModel->set('masseditable', '0');
		$noteContentFieldModel->save();
	}

	$userModuleModel = Head_Module_Model::getInstance('Users');
	$defaultActivityTypeFieldModel = Head_Field_Model::getInstance('defaultactivitytype', $userModuleModel);
	if ($defaultActivityTypeFieldModel) {
		$defaultActivityTypeFieldModel->set('defaultvalue', 'Call');
		$defaultActivityTypeFieldModel->save();
		$db->pquery('UPDATE jo_users SET defaultactivitytype=? WHERE defaultactivitytype=? OR defaultactivitytype IS NULL', array('Call', ''));
	}

	$defaultEventStatusFieldModel = Head_Field_Model::getInstance('defaulteventstatus', $userModuleModel);
	if ($defaultEventStatusFieldModel) {
		$defaultEventStatusFieldModel->set('defaultvalue', 'Planned');
		$defaultEventStatusFieldModel->save();
		$db->pquery('UPDATE jo_users SET defaulteventstatus=? WHERE defaulteventstatus=? OR defaulteventstatus IS NULL', array('Planned', ''));
	}

	$moduleInstance = Head_Module::getInstance('Users');
	$blockInstance = Head_Block::getInstance('LBL_CALENDAR_SETTINGS', $moduleInstance);
	if ($blockInstance) {
		$fieldInstance = Head_Field::getInstance('defaultcalendarview', $moduleInstance);
		if (!$fieldInstance) {
			$fieldInstance				= new Head_Field();
			$fieldInstance->name		= 'defaultcalendarview';
			$fieldInstance->label		= 'Default Calendar View';
			$fieldInstance->table		= 'jo_users';
			$fieldInstance->column		= 'defaultcalendarview';
			$fieldInstance->uitype		= '16';
			$fieldInstance->presence	= '0';
			$fieldInstance->typeofdata	= 'V~O';
			$fieldInstance->columntype	= 'VARCHAR(100)';
			$fieldInstance->defaultvalue= 'MyCalendar';

			$blockInstance->addField($fieldInstance);
			$fieldInstance->setPicklistValues(array('ListView', 'MyCalendar', 'SharedCalendar'));
			echo '<br>Default Calendar view field added <br>';
		}
	}

	$fieldInstance = Head_Field_Model::getInstance('language', $moduleInstance);
	if ($fieldInstance) {
		$fieldInstance->set('defaultvalue', 'en_us');
		$fieldInstance->save();
	}

	$allUsers = Users_Record_Model::getAll(true);
	foreach ($allUsers as $userId => $userModel) {
		$db->pquery('UPDATE jo_users SET defaultcalendarview=? WHERE id=?', array('MyCalendar', $userId));
	}
	echo 'Default calendar view updated for all active users <br>';

	$fieldNamesList = array();
	$updateQuery = 'UPDATE jo_field SET fieldlabel = CASE fieldname';
	$result = $db->pquery('SELECT taxname, taxlabel FROM jo_inventorytaxinfo', array());
	while($row = $db->fetch_array($result)) {
		$fieldName = $row['taxname'];
		$fieldLabel = $row['taxlabel'];

		$updateQuery .= " WHEN '$fieldName' THEN '$fieldLabel' ";
		$fieldNamesList[] = $fieldName;
	}
	$updateQuery .= 'END WHERE fieldname in ('. generateQuestionMarks($fieldNamesList) .')';

	$db->pquery($updateQuery, $fieldNamesList);
	$db->pquery('UPDATE jo_field SET fieldlabel=? WHERE displaytype=? AND fieldname=?', array('Item Discount Amount', 5, 'discount_amount'));

	$inventoryModules = getInventoryModules();
	foreach ($inventoryModules as $moduleName) {
		$tabId = getTabid($moduleName);
		$blockId = getBlockId($tabId, 'LBL_ITEM_DETAILS');
		$db->pquery('UPDATE jo_field SET displaytype=?, block=? WHERE tabid=? AND fieldname IN (?, ?)', array(5, $blockId, $tabId, 'hdnDiscountAmount', 'hdnDiscountPercent'));
	}

	$itemFieldsName = array('image','purchase_cost','margin');
	$itemFieldsLabel = array('Image','Purchase Cost','Margin');
	$itemFieldsTypeOfData = array('V~O','N~O','N~O');
	$itemFieldsDisplayType = array('56', '71', '71');
	$itemFieldsDataType = array('VARCHAR(2)', 'decimal(27,8)', 'decimal(27,8)');

	$fieldIdsList = array();
	foreach ($inventoryModules as $moduleName) {
		$moduleInstance = Head_Module::getInstance($moduleName);
		$blockInstance = Head_Block::getInstance('LBL_ITEM_DETAILS', $moduleInstance);

		for($i=0; $i<count($itemFieldsName); $i++) {
			$fieldName = $itemFieldsName[$i];

			if ($moduleName === 'PurchaseOrder' && $fieldName !== 'image') {
				continue;
			}

			$fieldInstance = Head_Field::getInstance($fieldName, $moduleInstance);
			if (!$fieldInstance) {
				$fieldInstance = new Head_Field();

				$fieldInstance->name		= $fieldName;
				$fieldInstance->column		= $fieldName;
				$fieldInstance->label		= $itemFieldsLabel[$i];
				$fieldInstance->columntype	= $itemFieldsDataType[$i];
				$fieldInstance->typeofdata	= $itemFieldsTypeOfData[$i];
				$fieldInstance->uitype		= $itemFieldsDisplayType[$i];
				$fieldInstance->table		= 'jo_inventoryproductrel';
				$fieldInstance->presence	= '1';
				$fieldInstance->readonly	= '0';
				$fieldInstance->displaytype = '5';
				$fieldInstance->masseditable = '0';

				$blockInstance->addField($fieldInstance);
				$fieldIdsList[] = $fieldInstance->id;
			}
		}
	}

	$columns = $db->getColumnNames('jo_products');
	if (!in_array('is_subproducts_viewable', $columns)) {
		$db->pquery('ALTER TABLE jo_products ADD COLUMN is_subproducts_viewable INT(1) DEFAULT 1', array());
	}
	$columns = $db->getColumnNames('jo_seproductsrel');
	if (!in_array('quantity', $columns)) {
		$db->pquery('ALTER TABLE jo_seproductsrel ADD COLUMN quantity INT(19) DEFAULT 1', array());
	}
	$columns = $db->getColumnNames('jo_inventorysubproductrel');
	if (!in_array('quantity', $columns)) {
		$db->pquery('ALTER TABLE jo_inventorysubproductrel ADD COLUMN quantity INT(19) DEFAULT 1', array());
	}

	$columns = $db->getColumnNames('jo_calendar_default_activitytypes');
	if (!in_array('isdefault', $columns)) {
		$db->pquery('ALTER TABLE jo_calendar_default_activitytypes ADD COLUMN isdefault INT(11) DEFAULT 1', array());
	}
	if (!in_array('conditions', $columns)) {
		$db->pquery('ALTER TABLE jo_calendar_default_activitytypes ADD COLUMN conditions VARCHAR(255) DEFAULT ""', array());
	}

	$updateList = array();
	$updateList[] = array('module' => 'Events',		'fieldname' => 'Events',			'newfieldname' => array('date_start', 'due_date'));
	$updateList[] = array('module' => 'Calendar',	'fieldname' => 'Tasks',				'newfieldname' => array('date_start', 'due_date'));
	$updateList[] = array('module' => 'Contacts',	'fieldname' => 'support_end_date',	'newfieldname' => array('support_end_date'));
	$updateList[] = array('module' => 'Contacts',	'fieldname' => 'birthday',			'newfieldname' => array('birthday'));
	$updateList[] = array('module' => 'Potentials',	'fieldname' => 'Potentials',		'newfieldname' => array('closingdate'));
	$updateList[] = array('module' => 'Invoice',	'fieldname' => 'Invoice',			'newfieldname' => array('duedate'));
	$updateList[] = array('module' => 'Project',	'fieldname' => 'Project',			'newfieldname' => array('startdate', 'targetenddate'));
	$updateList[] = array('module' => 'ProjectTask','fieldname' => 'Project Task',		'newfieldname' => array('startdate', 'enddate'));

	foreach ($updateList as $list) {
		$db->pquery('UPDATE jo_calendar_default_activitytypes SET fieldname=? WHERE module=? AND fieldname=? AND isdefault=?', array(Zend_Json::encode($list['newfieldname']), $list['module'], $list['fieldname'], '1'));
	}

	$model = Settings_Head_TermsAndConditions_Model::getInstance('Inventory');
	$tAndC = $model->getText();
	$db->pquery('DELETE FROM jo_inventory_tandc', array());

	$inventoryModules = getInventoryModules();
	foreach ($inventoryModules as $moduleName) {
		$model = Settings_Head_TermsAndConditions_Model::getInstance($moduleName);
		$model->setText($tAndC);
		$model->setType($moduleName);
		$model->save();
	}

	$columns = $db->getColumnNames('jo_import_queue');
	if (!in_array('lineitem_currency_id', $columns)) {
		$db->pquery('ALTER TABLE jo_import_queue ADD COLUMN lineitem_currency_id INT(5)', array());
	}
	if (!in_array('paging', $columns)) {
		$db->pquery('ALTER TABLE jo_import_queue ADD COLUMN paging INT(1) DEFAULT 0', array());
	}

	$documentsInstance = Head_Module::getInstance('Documents');
	if ($documentsInstance) {
		$documentsInstance->setRelatedList(Head_Module::getInstance('Contacts'), 'Contacts', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Accounts'), 'Accounts', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Potentials'), 'Potentials', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Leads'), 'Leads', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Products'), 'Products', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Services'), 'Services', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Project'), 'Project', true);
//		$documentsInstance->setRelatedList(Head_Module::getInstance('Assets'), 'Assets', true);
//		$documentsInstance->setRelatedList(Head_Module::getInstance('ServiceContracts'), 'ServiceContracts', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Quotes'), 'Quotes', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('Invoice'), 'Invoice', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('SalesOrder'), 'SalesOrder', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('PurchaseOrder'), 'PurchaseOrder', true);
		$documentsInstance->setRelatedList(Head_Module::getInstance('HelpDesk'), 'HelpDesk', true);
//		$documentsInstance->setRelatedList(Head_Module::getInstance('Faq'), 'Faq', true);
	}

	//Update relation field for existing relation ships
	$ignoreRelationFieldMapping = array('Emails');
	$query = 'SELECT * FROM jo_relatedlists ORDER BY tabid ';
	$result = $db->pquery($query, array());
	$num_rows = $db->num_rows($result);
	$relationShipMapping = array();
	for ($i=0; $i<$num_rows; $i++) {
		$tabId = $db->query_result($result, $i, 'tabid');
		$relatedTabid = $db->query_result($result, $i, 'related_tabid');
		$relationId = $db->query_result($result, $i, 'relation_id');
		$primaryModuleInstance = Head_Module_Model::getInstance($tabId);
		$relatedModuleInstance = Head_Module_Model::getInstance($relatedTabid);

		if (empty($relatedModuleInstance)) {
			continue;
		}

		$primaryModuleName = $primaryModuleInstance->getName();
		$relatedModuleName = $relatedModuleInstance->getName();

		$relatedModulesIgnored = $ignoreRelationFieldMapping[$primaryModuleName];
		if (in_array($relatedModuleName, $ignoreRelationFieldMapping)) {
			continue;
		}
		$relatedModuleReferenceFields = $relatedModuleInstance->getFieldsByType('reference');
		foreach ($relatedModuleReferenceFields as $fieldModel) {
			if ($fieldModel->isCustomField()) {
				//for custom reference field we cannot do relation ships so ignoring them
				continue;
			}
			$referenceList = $fieldModel->getReferenceList(false);
			if (in_array($primaryModuleName, $referenceList)) {
				$relationShipMapping[$primaryModuleName][$relatedModuleName] = $fieldModel->getName();
				$updateQuery = 'UPDATE jo_relatedlists SET relationfieldid=? WHERE relation_id=?';
				$db->pquery($updateQuery, array($fieldModel->getId(), $relationId));
				break;
			}
		}
	}

	$columns = $db->getColumnNames('jo_links');
	if (!in_array('parent_link', $columns)) {
		$db->pquery('ALTER TABLE jo_links ADD COLUMN parent_link INT(19)', array());
	}

	$moduleName = 'Reports';
	$reportModel = Head_Module_Model::getInstance($moduleName);
	$reportTabId = $reportModel->getId();
	Head_Link::addLink($reportTabId, 'LISTVIEWBASIC', 'LBL_ADD_RECORD', '', '', '0');

	$reportAddRecordLink = $db->pquery('SELECT linkid FROM jo_links WHERE tabid=? AND linklabel=?', array($reportTabId, 'LBL_ADD_RECORD'));
	$parentLinkId = $db->query_result($reportAddRecordLink, 0, 'linkid');

	$reportModelHandler = array('path' => 'modules/Reports/models/Module.php', 'class' => 'Reports_Module_Model', 'method' => 'checkLinkAccess');
	Head_Link::addLink($reportTabId, 'LISTVIEWBASIC', 'LBL_DETAIL_REPORT', 'javascript:Reports_List_Js.addReport("'.$reportModel->getCreateRecordUrl().'")', '', '0', $reportModelHandler, $parentLinkId);
	Head_Link::addLink($reportTabId, 'LISTVIEWBASIC', 'LBL_CHARTS', 'javascript:Reports_List_Js.addReport("Reports/view/ChartEdit")', '', '0', $reportModelHandler, $parentLinkId);
	Head_Link::addLink($reportTabId, 'LISTVIEWBASIC', 'LBL_ADD_FOLDER', 'javascript:Reports_List_Js.triggerAddFolder("'.$reportModel->getAddFolderUrl().'")', '', '0', $reportModelHandler);

	$allFolders = Reports_Folder_Model::getAll();
	foreach ($allFolders as $folderId => $folderModel) {
		$folderModel->set('foldername', decode_html(vtranslate($folderModel->getName(), $moduleName)));
		$folderModel->set('folderdesc', decode_html(vtranslate($folderModel->get('folderdesc'), $moduleName)));
		$folderModel->save();
	}

	$columns = $db->getColumnNames('jo_schedulereports');
	if (!in_array('fileformat', $columns)) {
		$db->pquery('ALTER TABLE jo_schedulereports ADD COLUMN fileformat VARCHAR(10) DEFAULT "CSV"', array());
	}

	$modCommentsInstance = Head_Module_Model::getInstance('ModComments');
	$modCommentsTabId = $modCommentsInstance->getId();

	$modCommentFieldInstance = Head_Field_Model::getInstance('related_to', $modCommentsInstance);
	$modCommentFieldInstance->setRelatedModules(getInventoryModules());

	$refModulesList = $modCommentFieldInstance->getReferenceList();
	foreach ($refModulesList as $refModuleName) {
		$refModuleModel = Head_Module_Model::getInstance($refModuleName);
		$refModuleTabId = $refModuleModel->getId();
		$db->pquery('UPDATE jo_relatedlists SET sequence=(sequence+1) WHERE tabid=?', array($refModuleTabId));

		$query = 'SELECT 1 FROM jo_relatedlists WHERE tabid=? AND related_tabid =?';
		$result = $db->pquery($query, array($refModuleTabId, $modCommentsTabId));
		if (!$db->num_rows($result)) {
			$db->pquery('INSERT INTO jo_relatedlists VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', array($db->getUniqueID('jo_relatedlists'), $refModuleTabId, $modCommentsTabId, 'get_comments', '1', 'ModComments', '0', '', $fieldId, 'NULL', '1:N'));
		}
	}

	$columns = $db->getColumnNames('jo_modcomments');
	if (in_array('parent_comments', $columns)) {
		$db->pquery('ALTER TABLE jo_modcomments MODIFY parent_comments INT(19)',array());
	}
	if (in_array('customer', $columns)) {
		$db->pquery('ALTER TABLE jo_modcomments MODIFY customer INT(19)', array());
	}
	if (in_array('userid', $columns)) {
		$db->pquery('ALTER TABLE jo_modcomments MODIFY userid INT(19)', array());
	}

	$columns = $db->getColumnNames('jo_emailtemplates');
	if (!in_array('systemtemplate', $columns)) {
		$db->pquery('ALTER TABLE jo_emailtemplates ADD COLUMN systemtemplate INT(1) NOT NULL DEFAULT 0', array());
	}
	if (!in_array('templatepath', $columns)) {
		$db->pquery('ALTER TABLE jo_emailtemplates ADD COLUMN templatepath VARCHAR(100) AFTER templatename', array());
	}
	if (!in_array('module', $columns)) {
		$db->pquery('ALTER TABLE jo_emailtemplates ADD COLUMN module VARCHAR(100)', array());
	}
	$db->pquery('UPDATE jo_emailtemplates SET module=? WHERE module IS NULL', array('Contacts'));

	$moduleName = 'Calendar';

    //Creating new reminder block in calendar todo
	$calendarInstance = Head_Module_Model::getInstance($moduleName);
	$tabId = $calendarInstance->getId();

	//Updates sequence of blocks available in users module.
	Head_Block_Model::pushDown('1', $tabId);

	if (!Head_Block_Model::checkDuplicate('LBL_REMINDER_INFORMATION', $tabId)) {
		$reminderBlock = new Head_Block();
		$reminderBlock->label = 'LBL_REMINDER_INFORMATION';
		$reminderBlock->sequence = 2;
		$calendarInstance->addBlock($reminderBlock);
	}

	//updating block and displaytype for send reminder field
	$reminderBlockInstance = Head_Block_Model::getInstance('LBL_REMINDER_INFORMATION', $calendarInstance);
	$db->pquery('UPDATE jo_field SET block=?, displaytype=? WHERE tabid=? AND fieldname=?', array($reminderBlockInstance->id, '1', $tabId, 'reminder_time'));

	if (!Head_Utils::CheckTable('jo_emailslookup')) {
		$query = 'CREATE TABLE jo_emailslookup(crmid int(20) DEFAULT NULL, 
						setype varchar(30) DEFAULT NULL, value varchar(100) DEFAULT NULL, 
						fieldid int(20) DEFAULT NULL, UNIQUE KEY emailslookup_crmid_setype_fieldname_uk (crmid,setype,fieldid),
						KEY emailslookup_fieldid_setype_idx (fieldid, setype), 
						CONSTRAINT emailslookup_crmid_fk FOREIGN KEY (crmid) REFERENCES jo_crmentity (crmid) ON DELETE CASCADE)';
		$db->pquery($query, array());
	}

	$EventManager = new VTEventsManager($db);
	$createEvent = 'vtiger.entity.aftersave';
	$handler_path = 'modules/Head/handlers/EmailLookupHandler.php';
	$className = 'EmailLookupHandler';
	$EventManager->registerHandler($createEvent, $handler_path, $className, '', '["VTEntityDelta"]');

	$deleteEvent = 'vtiger.entity.afterdelete';
	$EventManager->registerHandler($deleteEvent, $handler_path, $className, '');

	$restoreEvent = 'vtiger.entity.afterrestore';
	$EventManager->registerHandler($restoreEvent, $handler_path, $className, '');

	$createBatchEvent = 'vtiger.batchevent.save';
	$EventManager->registerHandler($createBatchEvent, $handler_path, 'EmailLookupBatchHandler', '');

	$EmailsModuleModel = Head_Module_Model::getInstance('Emails');
	$emailSupportedModulesList = $EmailsModuleModel->getEmailRelatedModules();

	$recordModel = new Emails_Record_Model();
	foreach ($emailSupportedModulesList as $module) {
		if ($module != 'Users') {
			$moduleInstance = CRMEntity::getInstance($module);

			$query = $moduleInstance->buildSearchQueryForFieldTypes(array('13'));
			$moduleModel = Head_Module_Model::getInstance($module);
			$emailFieldModels = $moduleModel->getFieldsByType('email');
			$emailFieldNames = array_keys($emailFieldModels);
			foreach ($emailFieldModels as $fieldName => $fieldModel) {
				$emailFieldIds[$fieldModel->get('name')] = $fieldModel->get('id');
			}
			$result = $db->pquery($query, array());

			$values['setype'] = $module;
			while ($row = $db->fetchByAssoc($result)) {
				$values['crmid'] = $row['id'];
				foreach ($row as $fieldName => $value) {
					if (in_array($fieldName, $emailFieldNames) && !empty($value)) {
						$fieldId = $emailFieldIds[$fieldName];
						$values[$fieldId] = $value;
						$recordModel->recieveEmailLookup($fieldId, $values);
					}
				}
			}
		}
	}

	$massEditSql = 'UPDATE jo_field SET masseditable=0 WHERE fieldname IN(?,?,?,?)';
	$db->pquery($massEditSql, array('created_user_id', 'createdtime', 'modifiedtime', 'modifiedby'));

	$db->pquery('UPDATE jo_eventhandlers SET is_active = 1 WHERE handler_class=?', array('ModTrackerHandler'));
	Head_Link_Model::deleteLink('0', 'DETAILVIEWBASIC', 'Print');

	$db->pquery('ALTER TABLE jo_emailtemplates MODIFY COLUMN subject VARCHAR(255)', array());
	$db->pquery('ALTER TABLE jo_activity MODIFY COLUMN subject VARCHAR(255)', array());

	//Start: Update Currency symbol for Egypt
	$db->pquery('UPDATE jo_currencies SET currency_symbol=? WHERE currency_name=?', array('E£', 'Egypt, Pounds'));
	$db->pquery('UPDATE jo_currency_info SET currency_symbol=? WHERE currency_name=?', array('E£', 'Egypt, Pounds'));

	//setting is_private value of comments to 0 if internal comments is not supported for that module
	$modCommentsInstance = Head_Module::getInstance('ModComments');
	$blockInstance = Head_Block::getInstance('LBL_MODCOMMENTS_INFORMATION', $modCommentsInstance);
	if ($blockInstance) {
		$fieldInstance = Head_Field::getInstance('is_private', $modCommentsInstance);
		if (!$fieldInstance) {
			$fieldInstance				= new Head_Field();
			$fieldInstance->name		= 'is_private';
			$fieldInstance->label		= 'Is Private';
			$fieldInstance->uitype		= 7;
			$fieldInstance->column		= 'is_private';
			$fieldInstance->columntype	= 'INT(1) DEFAULT 0';
			$fieldInstance->typeofdata	= 'I~O';
			$blockInstance->addField($fieldInstance);
		}
		unset($fieldInstance);

		$fieldInstance = Head_Field::getInstance('filename', $modCommentsInstance);
		if (!$fieldInstance) {
			$fieldInstance = new Head_Field();
			$fieldInstance->name		= 'filename';
			$fieldInstance->column		= 'filename';
			$fieldInstance->label		= 'Attachment';
			$fieldInstance->columntype	= 'VARCHAR(255)';
			$fieldInstance->table		= 'jo_modcomments';
			$fieldInstance->typeofdata	= 'V~O';
			$fieldInstance->uitype		= '61';
			$fieldInstance->presence	= '0';
			$blockInstance->addField($fieldInstance);
		}
		unset($fieldInstance);

		$fieldInstance = Head_Field::getInstance('related_email_id', $modCommentsInstance);
		if (!$fieldInstance) {
			$fieldInstance = new Head_Field();
			$fieldInstance->name		= 'related_email_id';
			$fieldInstance->label		= 'Related Email Id';
			$fieldInstance->uitype		= 1;
			$fieldInstance->column		= $fieldInstance->name;
			$fieldInstance->columntype	= 'INT(11)';
			$fieldInstance->typeofdata	= 'I~O';
			$fieldInstance->defaultvalue= 0;
			$blockInstance->addField($fieldInstance);
		}
		unset($fieldInstance);
	}

	$internalCommentModules = Head_Functions::getPrivateCommentModules();
	$lastMaxCRMId = 0;
	do {
		$commentsResult = $db->pquery('SELECT jo_modcomments.modcommentsid FROM jo_modcomments 
												LEFT JOIN jo_crmentity ON jo_crmentity.crmid = jo_modcomments.related_to 
												WHERE (jo_crmentity.setype NOT IN ('.generateQuestionMarks($internalCommentModules).') 
												OR jo_crmentity.setype IS NULL) AND modcommentsid > ? LIMIT 500', array_merge($internalCommentModules, array($lastMaxCRMId)));
		if (!$db->num_rows($commentsResult)) {
			break;
		}

		$commentIds = array();
		while ($row = $db->fetch_array($commentsResult)) {
			$commentIds[] = $row['modcommentsid'];
		}

		if (count($commentIds) > 0) {
			$db->pquery('UPDATE jo_modcomments SET is_private = 0 WHERE modcommentsid IN ('.generateQuestionMarks($commentIds).')', $commentIds);
		}

		$commentId = end($commentIds);
		if (intval($commentId) > $lastMaxCRMId) {
			$lastMaxCRMId = intval($commentId);
		}
		$commentsResult = NULL;
		unset($commentsResult);
	} while (true);

	//Start - Add Contact Name to Default filter of project
	$cvidQuery = $db->pquery('SELECT cvid FROM jo_customview where viewname=? AND entitytype=?', array('All', 'Project'));
	$row = $db->fetch_array($cvidQuery);
	if ($row['cvid']) {
		$columnNameCount = $db->pquery('SELECT 1 FROM jo_cvcolumnlist WHERE cvid=? and columnname=?', array($row['cvid'], 'jo_project:contactid:contactid:Project_Contact_Name:V'));
		if (!$db->num_rows($columnNameCount)) {
			$columnIndexQuery = $db->pquery('SELECT MAX(columnindex) AS columnindex FROM jo_cvcolumnlist WHERE cvid=?', array($row['cvid']));
			$colIndex = $db->fetch_array($columnIndexQuery);
			$db->pquery('INSERT INTO jo_cvcolumnlist(cvid,columnindex,columnname) VALUES(?,?,?)', array($row['cvid'], $colIndex['columnindex']+11, 'jo_project:contactid:contactid:Project_Contact_Name:V'));
		}
	}
	//End

	$moduleSpecificHeaderFields = array(
		'Accounts'			=> array('website', 'email1', 'phone'),
		'Contacts'			=> array('email', 'phone'),
		'Leads'				=> array('email', 'phone'),
		'Potentials'		=> array('related_to', 'email', 'amount', 'sales_stage'),
		'HelpDesk'			=> array('ticketpriorities'),
		'Invoice'			=> array('contact_id', 'account_id', 'assigned_user_id', 'invoicestatus'),
		'Products'			=> array('product_no', 'discontinued', 'qtyinstock', 'productcategory'),
		'Project'			=> array('linktoaccountscontacts', 'contactid'),
		'PurchaseOrder'		=> array('contact_id', 'assigned_user_id', 'postatus'),
		'Quotes'			=> array('account_id', 'contact_id', 'hdnGrandTotal', 'quotestage'),
		'SalesOrder'		=> array('contact_id', 'account_id', 'assigned_user_id', 'sostatus'),
		'Vendors'			=> array('website', 'email', 'phone')
	);
	$moduleTabIds = array();
	foreach ($moduleSpecificHeaderFields as $moduleName => $headerFields) {
		$tabid = getTabid($moduleName);
		if ($tabid) {
			$sql = 'UPDATE jo_field SET headerfield=?, summaryfield=? WHERE tabid=? AND fieldname IN ('.generateQuestionMarks($headerFields).')';
			$db->pquery($sql, array_merge(array(1, 0, $tabid), $headerFields));
		}
	}

	//Update Calendar time_start as mandatory.
	$updateQuery = 'UPDATE jo_field SET typeofdata=? WHERE fieldname=? AND tabid=?';
	$db->pquery($updateQuery, array('T~M', 'time_start', getTabid('Calendar')));

	$ignoreModules = array('SMSNotifier', 'ModComments');
	$result = $db->pquery('SELECT name FROM jo_tab WHERE isentitytype=? AND name NOT IN ('.generateQuestionMarks($ignoreModules).')', array(1, $ignoreModules));
	$modules = array();
	while ($row = $db->fetchByAssoc($result)) {
		$modules[] = $row['name'];
	}

	foreach ($modules as $module) {
		$moduleInstance = Head_Module::getInstance($module);
		if ($moduleInstance) {
			$fieldInstance = Head_Field::getInstance('source', $moduleInstance);
			if ($fieldInstance) {
				continue;
			}
			$blockQuery = 'SELECT blockid FROM jo_blocks WHERE tabid=? ORDER BY sequence LIMIT 1';
			$result = $db->pquery($blockQuery, array($moduleInstance->id));
			$block = $db->query_result($result, 0, 'blockid');
			if ($block) {
				$blockInstance = Head_Block::getInstance($block, $moduleInstance);
				$field = new Head_Field();
				$field->name			= 'source';
				$field->label			= 'Source';
				$field->table			= 'jo_crmentity';
				$field->presence		= 2;
				$field->displaytype		= 2;
				$field->readonly		= 1;
				$field->uitype			= 1;
				$field->typeofdata		= 'V~O';
				$field->quickcreate		= 3;
				$field->masseditable	= 0;
				$blockInstance->addField($field);
			}
		}
	}

	$projectModule = Head_Module_Model::getInstance('Project');
	$emailModule = Head_Module_Model::getInstance('Emails');
	$projectModule->setRelatedList($emailModule, 'Emails', 'ADD', 'get_emails');

	$projectTaskModule = Head_Module_Model::getInstance('ProjectTask');
	$projectTaskModule->setRelatedList($emailModule, 'Emails', 'ADD', 'get_emails');

	$sql = "CREATE TABLE IF NOT EXISTS jo_emails_recipientprefs(`id` INT(11) NOT NULL AUTO_INCREMENT,`tabid` INT(11) NOT NULL,
				`prefs` VARCHAR(255) NULL DEFAULT NULL, `userid` INT(11), PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$db->pquery($sql, array());

	//To change the convert lead webserice operation parameters which was wrong earliear 
	require_once 'includes/Webservices/Utils.php';
	$convertLeadOperationQueryRes = $db->pquery('SELECT operationid FROM jo_ws_operation WHERE name=?', array('convertlead'));
	if (!$db->num_rows($convertLeadOperationQueryRes)) {
		$operationId = $db->query_result($convertLeadOperationQueryRes, '0', 'operationid');
		$deleteParameterQuery = $db->pquery('DELETE FROM jo_ws_operation_parameters WHERE operationid=?', array($operationId));
		vtws_addWebserviceOperationParam($operationId, 'element', 'encoded', 1);
	}

	//Start : Change fieldLabel of description field to Description - Project module.
	$fieldId = getFieldid(getTabid('Project'), 'description');
	$fieldModel = Head_Field_Model::getInstance($fieldId);
	$fieldModel->set('label', 'Description');
	$fieldModel->__update();

	$db->pquery('ALTER TABLE jo_mail_accounts MODIFY mail_password TEXT', array());

	//making priority as mandatory field in Tickets.
	$module = 'HelpDesk';
	$fieldModel = Head_Functions::getModuleFieldInfo(getTabid($module), 'ticketpriorities');
	$fieldInstance = Settings_LayoutEditor_Field_Model::getInstance($fieldModel['fieldid']);
	$fieldInstance->set('typeofdata', 'V~M');
	$fieldInstance->save();

	if (Head_Utils::CheckTable('jo_customerportal_tabs')) {
		$db->pquery('UPDATE jo_customerportal_tabs SET visible=? WHERE tabid IN(?,?)', array(0, getTabid('Contacts'), getTabid('Accounts')));
//		$moduleId = getTabid('ServiceContracts');
//		$db->pquery('DELETE FROM jo_customerportal_tabs WHERE tabid=?', array($moduleId));
//		$sequenceQuery = 'SELECT max(sequence) AS sequence FROM jo_customerportal_tabs';
//		$seqResult = $db->pquery($sequenceQuery, array());
//		$sequence = $db->query_result($seqResult, 0, 'sequence');
//		$db->pquery('INSERT INTO jo_customerportal_tabs(tabid,visible,sequence) VALUES (?,?,?)', array($moduleId, 1, $sequence+11));
	}

	if (Head_Utils::CheckTable('jo_customerportal_fields')) {
		$columns = $db->getColumnNames('jo_customerportal_fields');
		if (!in_array('fieldinfo', $columns)) {
			$db->pquery('ALTER TABLE jo_customerportal_fields CHANGE fieldid fieldinfo TEXT', array());
		}
		if (!in_array('records_visible', $columns)) {
			$db->pquery('ALTER TABLE jo_customerportal_fields CHANGE visible records_visible INT(1)', array());
		}

		$moduleModel = Settings_Head_Module_Model::getInstance('Settings:CustomerPortal');
		$modules = $moduleModel->getModulesList();

		foreach ($modules as $tabid => $model) {
			$moduleModel = Head_Module_Model::getInstance($model->getName());
			$allFields = $moduleModel->getFields();
			$mandatoryFields = array();
			foreach ($allFields as $key => $value) {
				if ($value->isMandatory() && $value->isViewableInDetailView()) {
					$mandatoryFields[$value->name] = 1;
				}
			}
			if ($tabid == getTabid('HelpDesk')) {
				$mandatoryFields['description'] = 1;
				$mandatoryFields['product_id'] = 1;
				$mandatoryFields['ticketseverities'] = 1;
				$mandatoryFields['ticketcategories'] = 1;
			}
			if ($tabid == getTabid('Documents')) {
				$mandatoryFields['filename'] = 0;
			}
			$recordVisibilityQuery = 'SELECT prefvalue from jo_customerportal_prefs WHERE tabid=? AND prefkey=?';
			$recordVisibilityQueryResult = $db->pquery($recordVisibilityQuery, array($tabid, 'showrelatedinfo'));
			$visibilty = 1;
			if (!$db->num_rows($recordVisibilityQueryResult)) {
				$visibilty = $db->query_result($recordVisibilityQueryResult, 0, 'prefvalue');
			}
			$db->pquery('INSERT INTO jo_customerportal_fields(tabid,fieldinfo,records_visible) VALUES(?,?,?)', array($tabid, json_encode($mandatoryFields), $visibilty));
		}
	}

	if (!Head_Utils::CheckTable('jo_customerportal_relatedmoduleinfo')) {
		$db->pquery('CREATE TABLE jo_customerportal_relatedmoduleinfo(module INT(11),relatedmodules TEXT) ', array());
		$moduleModel = Settings_Head_Module_Model::getInstance('Settings:CustomerPortal');
		$modules = $moduleModel->getModulesList();
		$oneOperation = array('Invoice', 'Quotes', 'Products', 'Services', 'Documents', 'Assets', 'ProjectMilestone');
		$twoOperations = array('ProjectTask');
		$fiveOperations = array('Project');
		$threeOperations = array('HelpDesk');
		$availableTwoOperations = array(array('name' => 'History', 'value' => 1), array('name' => 'ModComments', 'value' => 1));
		$availableThreeOperations = array(array('name' => 'History', 'value' => 1), array('name' => 'ModComments', 'value' => 1), array('name' => 'Documents', 'value' => 1));
		$availableOneOperations = array(array('name' => 'History', 'value' => 1));
		$availableFourOperations = array(array('name' => 'History', 'value' => 1), array('name' => 'ModComments', 'value' => 1), array('name' => 'ProjectTask', 'value' => 1), array('name' => 'ProjectMilestone', 'value' => 1));
		$availableFiveOperations = array(array('name' => 'History', 'value' => 1), array('name' => 'ModComments', 'value' => 1), array('name' => 'ProjectTask', 'value' => 1), array('name' => 'ProjectMilestone', 'value' => 1), array('name' => 'Documents', 'value' => 1));

		foreach ($modules as $tabid => $model) {
			$moduleName = $model->getName();
			$tabid = getTabid($moduleName);
			if (in_array($moduleName, $oneOperation)) {
				$db->pquery('INSERT INTO jo_customerportal_relatedmoduleinfo(module,relatedmodules) VALUES(?,?)', array($tabid, json_encode($availableOneOperations)));
			} else if (in_array($moduleName, $threeOperations)) {
				$db->pquery('INSERT INTO jo_customerportal_relatedmoduleinfo(module,relatedmodules) VALUES(?,?)', array($tabid, json_encode($availableThreeOperations)));
			} else if (in_array($moduleName, $twoOperations)) {
				$db->pquery('INSERT INTO jo_customerportal_relatedmoduleinfo(module,relatedmodules) VALUES(?,?)', array($tabid, json_encode($availableTwoOperations)));
			} else if (in_array($moduleName, $fiveOperations)) {
				$db->pquery('INSERT INTO jo_customerportal_relatedmoduleinfo(module,relatedmodules) VALUES(?,?)', array($tabid, json_encode($availableFiveOperations)));
			}
		}
	}

	$columns = $db->getColumnNames('jo_customerportal_relatedmoduleinfo');
	if (in_array('module', $columns)) {
		$db->pquery('ALTER TABLE jo_customerportal_relatedmoduleinfo CHANGE module tabid INT(19)', array());
		$db->pquery('ALTER TABLE jo_customerportal_relatedmoduleinfo ADD PRIMARY KEY(tabid)', array());
		$db->pquery('ALTER TABLE jo_customerportal_fields ADD PRIMARY KEY(tabid)', array());
	}

	if (!Head_Utils::CheckTable('jo_customerportal_settings')) {
		$db->pquery('CREATE TABLE jo_customerportal_settings(id int, url VARCHAR(250),default_assignee INT(11),
							support_notification INT(11), announcement TEXT, shortcuts TEXT,widgets TEXT,charts TEXT)', array());
		$availableModules = array('Documents' => array('LBL_ADD_DOCUMENT' => 1), 'HelpDesk' => array('LBL_CREATE_TICKET' => 1, 'LBL_OPEN_TICKETS' => 1));
		$availableWidgets = array('widgets' => array('HelpDesk' => 1, 'Documents' => 1, 'Faq' => 1));
		$availableCharts = array('charts' => array('OpenTicketsByPriority' => 1, 'TicketsClosureTimeByPriority' => 1));
		$encodedShortcuts = json_encode($availableModules);
		$encodedWidgets = json_encode($availableWidgets);
		$encodedCharts = json_encode($availableCharts);
		$db->pquery('INSERT INTO jo_customerportal_settings(id,default_assignee,shortcuts,widgets,charts) VALUES(?,?,?,?,?)', array(1, 1, $encodedShortcuts, $encodedWidgets, $encodedCharts));
	}

	$query = 'ALTER TABLE jo_portalinfo MODIFY user_password VARCHAR(255)';
	$db->pquery($query, array());

	//Enable mass edit for portal field under Contacts
	$moduleContacts = 'Contacts';
	$contactsFieldModel = Head_Functions::getModuleFieldInfo(getTabid($moduleContacts), 'portal');
	$contactsFieldId = $contactsFieldModel['fieldid'];
	$contactsFieldInstance = Settings_LayoutEditor_Field_Model::getInstance($contactsFieldId);
	$contactsFieldInstance->set('masseditable', '1');
	$contactsFieldInstance->save();
	//Customer portal changes end

	 $relatedWebservicesOperations = array(
		array(
			'name' => 'relatedtypes',
			'path' => 'includes/Webservices/RelatedTypes.php',
			'method' => 'vtws_relatedtypes',
			'type' => 'GET',
			'params' => array(
				array('name' => 'elementType', 'type' => 'string')
			)
		),
		array(
			'name' => 'retrieve_related',
			'path' => 'includes/Webservices/RetrieveRelated.php',
			'method' => 'vtws_retrieve_related',
			'type' => 'GET',
			'params' => array(
				array('name' => 'id', 'type' => 'string'),
				array('name' => 'relatedType', 'type' => 'string'),
				array('name' => 'relatedLabel', 'type' => 'string')
			)
		),
		array(
			'name' => 'query_related',
			'path' => 'includes/Webservices/QueryRelated.php',
			'method' => 'vtws_query_related',
			'type' => 'GET',
			'params' => array(
				array('name' => 'query', 'type' => 'string'),
				array('name' => 'id', 'type' => 'string'),
				array('name' => 'relatedLabel', 'type' => 'string')
			)
		)
	);
	foreach ($relatedWebservicesOperations as $operation) {
		$rs = $db->pquery('SELECT 1 FROM jo_ws_operation WHERE name=?', array($operation['name']));
		if (!$db->num_rows($rs)) {
			$operationId = vtws_addWebserviceOperation($operation['name'], $operation['path'], $operation['method'], $operation['type']);
			$sequence = 1;
			foreach ($operation['params'] as $param) {
				vtws_addWebserviceOperationParam($operationId, $param['name'], $param['type'], $sequence++);
			}
		}
	}
	//Change to modify shipping tax percent column type
	$db->pquery('ALTER TABLE jo_invoice MODIFY s_h_percent DECIMAL(25,8)', array());

	if (!Head_Utils::CheckTable('jo_projecttask_status_color')) {
		$db->pquery('CREATE TABLE jo_projecttask_status_color (
									status varchar(255),
									defaultcolor varchar(50),
									color varchar(50),
									UNIQUE KEY status (status)) ENGINE=InnoDB DEFAULT CHARSET=utf8');
	}

	$statusColorMap = array(
				'Open'			=> '#0099ff',
				'In Progress'	=> '#fdff00',
				'Completed'		=> '#3BBF67',
				'Deferred'		=> '#fbb11e',
				'Canceled'		=> '#660066');

	foreach ($statusColorMap as $status => $color) {
		$db->pquery('INSERT INTO jo_projecttask_status_color(status,defaultcolor) VALUES(?,?) ON DUPLICATE KEY UPDATE defaultcolor=?', array($status, $color, $color));
	}

	//Increasing Lead Status column size to 200 for Leads module
	$db->pquery('ALTER TABLE jo_leaddetails MODIFY leadstatus VARCHAR(200)', array());

	//Start : Increase tablabel and setype size
	$db->pquery('ALTER TABLE jo_tab MODIFY tablabel VARCHAR(100)', array());
	$db->pquery('ALTER TABLE jo_crmentity MODIFY setype VARCHAR(100)', array());

	//Changing type of data for Used Units and Total Units fields of Service Contracts module to Decimal
	$fields = array('total_units', 'used_units');
/*	$serviceContractsModuleModel = Head_Module_Model::getInstance('ServiceContracts');
	foreach ($fields as $field) {
		$fieldInstance = $serviceContractsModuleModel->getField($field);
		$typeOfData = 'NN~O';
		if ($fieldInstance->isMandatory()) {
			$typeOfData = 'NN~M';
		}
		$fieldInstance->set('typeofdata', $typeOfData);
		$fieldInstance->save();
	}
*/
	$db->pquery('ALTER TABLE jo_webforms_field MODIFY COLUMN defaultvalue TEXT', array());

	//Rollup Comments Settings table
	if (!Head_Utils::CheckTable('jo_rollupcomments_settings')) {
		Head_Utils::CreateTable('jo_rollupcomments_settings', 
				"(`rollupid` INT(19) NOT NULL AUTO_INCREMENT,
				`userid` INT(19) NOT NULL,
				`tabid` INT(19) NOT NULL,
				`rollup_status` INT(2) NOT NULL DEFAULT '0',
				PRIMARY KEY (`rollupid`))", true);
	}

	$modulesList = array('Products', 'Services');
	foreach ($modulesList as $moduleName) {
		$moduleModel = Head_Module_Model::getInstance($moduleName);
		$taxFieldModel = Head_Field_Model::getInstance('taxclass', $moduleModel);
		$taxFieldModel->set('label', 'Taxes');
		$taxFieldModel->set('quickcreate', 2);
		$taxFieldModel->save();
	}

	$columns = $db->getColumnNames('com_jo_workflowtask_queue');
	if (!in_array('relatedinfo', $columns)) {
		$db->pquery('ALTER TABLE com_jo_workflowtask_queue ADD COLUMN relatedinfo VARCHAR(255)', array());
	}

	$db->pquery('ALTER TABLE jo_freetagged_objects MODIFY module VARCHAR(100)', array());
	$db->pquery('ALTER TABLE jo_emailslookup MODIFY setype VARCHAR(100)', array());
	$db->pquery('ALTER TABLE jo_entityname MODIFY modulename VARCHAR(100)', array());
	$db->pquery('ALTER TABLE jo_modentity_num MODIFY semodule VARCHAR(100)', array());
	$db->pquery('ALTER TABLE jo_reportmodules MODIFY primarymodule VARCHAR(100)', array());

	$calendarModuleModel = Head_Module_Model::getInstance('Calendar');
	$ProjectModuleModel = Head_Module_Model::getInstance('Project');
	$relationModel = Head_Relation_Model::getInstance($ProjectModuleModel, $calendarModuleModel, 'Activities');

	if ($relationModel !== false) {
		$fieldModel = $calendarModuleModel->getField('parent_id');
		$fieldId = $fieldModel->getId();

		$projectTabId = getTabid('Project');
		$calendarTabId = getTabid('Calendar');
		$result = $db->pquery('SELECT fieldtypeid FROM jo_ws_fieldtype WHERE uitype=?', array($fieldModel->get('uitype')));
		$fieldType = $db->query_result($result, 0, 'fieldtypeid');

		$result = $db->pquery('SELECT 1 FROM jo_ws_referencetype WHERE fieldtypeid=? and type=?', array($fieldType, 'Project'));
		if (!$db->num_rows($result)) {
			$db->pquery('INSERT INTO jo_ws_referencetype(fieldtypeid,type) VALUES(?, ?)', array($fieldType, 'Project'));
		}

		if (!$relationModel->get('relationfieldid')) {
			$query = 'UPDATE jo_relatedlists SET relationfieldid=? ,name=?, relationtype=? WHERE tabid=? AND related_tabid=?';
			$db->pquery($query, array($fieldId, 'get_activities', '1:N', $projectTabId, $calendarTabId));
		}

		//Migrate data from jo_crmentityrel to jo_seactivityrel
		$query = 'SELECT 1 FROM jo_crmentityrel WHERE module=? AND relmodule= ?';
		$result = $db->pquery($query, array('Project', 'Calendar'));
		if ($db->num_rows($result)) {
			$insertQuery = 'INSERT INTO jo_seactivityrel(crmid, activityid) values(?,?)';
			while($data = $db->fetch_array($result)) {
				$db->pquery($insertQuery, array($data['crmid'], $data['relcrmid']));
			}
			$db->pquery('DELETE FROM jo_crmentityrel WHERE module=? AND relmodule= ?', array('Project', 'Calendar'));
		}
	}

	$result = $db->pquery('SHOW INDEX FROM jo_crmentityrel WHERE key_name=?', array('crmid_idx'));
	if (!$db->num_rows($result)) {
		$db->pquery('ALTER TABLE jo_crmentityrel ADD INDEX crmid_idx(crmid)', array());
	}
	$result = $db->pquery('SHOW INDEX FROM jo_crmentityrel WHERE key_name=?', array('relcrmid_idx'));
	if (!$db->num_rows($result)) {
		$db->pquery('ALTER TABLE jo_crmentityrel ADD INDEX relcrmid_idx(relcrmid)', array());
	}

	//Start : Inactivate update_log field from ticket module
	$fieldId = getFieldid(getTabid('HelpDesk'), 'update_log');
	$fieldModel = Head_Field_Model::getInstance($fieldId);
	if ($fieldModel) {
		$fieldModel->set('presence', 1);
		$fieldModel->__update();
	}

	//Start : Project added as related tab for Potentials module.
	$projectModuleModel = Head_Module_Model::getInstance('Project');
	$fieldModel = Head_Field::getInstance('potentialid', $projectModuleModel);
	if ($fieldModel) {
		$fieldModel->setRelatedModules(array('Potentials'));
		$result = $db->pquery('SELECT 1 FROM jo_relatedlists where tabid=? AND relationfieldid=? AND related_tabid=?', array(getTabid('Potentials'), $fieldModel->id, getTabid('Project')));
		if (!($db->num_rows($result))) {
			$potentialModuleModel = Head_Module_Model::getInstance('Potentials');
			$potentialModuleModel->setRelatedList($projectModuleModel, 'Projects', array('ADD', 'SELECT'), 'get_dependents_list', $fieldModel->id);
		}
	}
	//End

	//Start : Change fieldLabel of description field to Description - ProjectMilestone module.
	$fieldId = getFieldid(getTabid('ProjectMilestone'), 'description');
	$fieldModel = Head_Field_Model::getInstance($fieldId);
	if ($fieldModel) {
		$fieldModel->set('label', 'Description');
		$fieldModel->__update();
	}
	//End

	$module = Head_Module_Model::getInstance('Emails');
	$blocks = $module->getBlocks();
	$block = current($blocks);

	$field = new Head_Field();
	$field->label = 'Click Count';
	$field->name = 'click_count';
	$field->table = 'jo_email_track';
	$field->column = 'click_count';
	$field->columntype = 'INT';
	$field->uitype = 25;
	$field->typeofdata = 'I~O';
	$field->displaytype = 3;
	$field->masseditable = 0;
	$field->quickcreate = 0;
	$field->defaultvalue = 0;
	$block->addfield($field);

	$criteria = ' MODIFY COLUMN click_count INT NOT NULL default 0';
	Head_Utils::AlterTable('jo_email_track', $criteria);

	$em = new VTEventsManager($db);
	$em->registerHandler('vtiger.lead.convertlead', 'modules/Leads/handlers/LeadHandler.php', 'LeadHandler');

	Head_Cache::flushModuleCache('Contacts');
	Head_Cache::flushModuleCache('Leads');
	Head_Cache::flushModuleCache('Emails');

	//Add create and edit to field to jo_customerportal_tabs to track Create and Edit permission of a module.
	$columns = $db->getColumnNames('jo_customerportal_tabs');
	if (!in_array('createrecord', $columns)) {
		$db->pquery('ALTER TABLE jo_customerportal_tabs ADD createrecord BOOLEAN NOT NULL DEFAULT FALSE', array());
	}
	if (!in_array('editrecord', $columns)) {
		$db->pquery('ALTER TABLE jo_customerportal_tabs ADD editrecord BOOLEAN NOT NULL DEFAULT FALSE', array());
	}

	//Update create and edit status for HelpDesk and Assets.
	$updateCreateEditStatusQuery = 'UPDATE jo_customerportal_tabs SET createrecord=?,editrecord=? WHERE tabid IN (?)';
	$db->pquery($updateCreateEditStatusQuery, array(1, 1, getTabid('HelpDesk')));
	$db->pquery($updateCreateEditStatusQuery, array(0, 1, getTabid('Contacts')));
	$db->pquery($updateCreateEditStatusQuery, array(0, 1, getTabid('Accounts')));
	$db->pquery($updateCreateEditStatusQuery, array(1, 0, getTabid('Documents')));
	$db->pquery($updateCreateEditStatusQuery, array(0, 1, getTabid('Assets')));

	$accessCountFieldId = getFieldid(getTabid('Emails'), 'access_count');
	$accessCountFieldModel = Head_Field_Model::getInstance($accessCountFieldId);
	if ($accessCountFieldModel) {
		$accessCountFieldModel->set('typeofdata', 'I~O');
		$accessCountFieldModel->__update();
		Head_Cache::flushModuleCache('Emails');
	}

	//Adding Create Event and Create Todo workflow tasks for Project module.
	$taskResult = $db->pquery('SELECT id, modules FROM com_jo_workflow_tasktypes WHERE tasktypename IN (?, ?)', array('VTCreateTodoTask', 'VTCreateEventTask'));
	$taskResultCount = $db->num_rows($taskResult);
	for ($i=0; $i<$taskResultCount; $i++) {
		$taskId = $db->query_result($taskResult, $i, 'id');
		$modules = Zend_Json::decode(decode_html($db->query_result($taskResult, $i, 'modules')));
		$modules['include'][] = 'Project';
		$modulesJson = Zend_Json::encode($modules);
		$db->pquery('UPDATE com_jo_workflow_tasktypes SET modules=? WHERE id=?', array($modulesJson, $taskId));
	}
	//End

	//Multiple attachment support for comments
	$db->pquery('ALTER TABLE jo_seattachmentsrel DROP PRIMARY KEY', array());
	$db->pquery('ALTER TABLE jo_seattachmentsrel ADD CONSTRAINT PRIMARY KEY (crmid,attachmentsid)', array());
	$db->pquery('ALTER TABLE jo_project MODIFY COLUMN projectid INT(19) PRIMARY KEY');

	if (!Head_Utils::CheckTable('jo_wsapp_logs_basic')) {
		Head_Utils::CreateTable('jo_wsapp_logs_basic',
				'(`id` int(25) NOT NULL AUTO_INCREMENT,
				`extensiontabid` int(19) DEFAULT NULL,
				`module` varchar(50) NOT NULL,
				`sync_datetime` datetime NOT NULL,
				`app_create_count` int(11) DEFAULT NULL,
				`app_update_count` int(11) DEFAULT NULL,
				`app_delete_count` int(11) DEFAULT NULL,
				`app_skip_count` int(11) DEFAULT NULL,
				`vt_create_count` int(11) DEFAULT NULL,
				`vt_update_count` int(11) DEFAULT NULL,
				`vt_delete_count` int(11) DEFAULT NULL,
				`vt_skip_count` int(11) DEFAULT NULL,
				`userid` int(11) DEFAULT NULL,
				PRIMARY KEY (`id`))', true);
	}

	if (!Head_Utils::CheckTable('jo_wsapp_logs_details')) {
		Head_Utils::CreateTable('jo_wsapp_logs_details',
				'(`id` int(25) NOT NULL,
				`app_create_ids` mediumtext,
				`app_update_ids` mediumtext,
				`app_delete_ids` mediumtext,
				`app_skip_info` mediumtext,
				`vt_create_ids` mediumtext,
				`vt_update_ids` mediumtext,
				`vt_delete_ids` mediumtext,
				`vt_skip_info` mediumtext,
				KEY `jo_wsapp_logs_basic_ibfk_1` (`id`),
				CONSTRAINT `jo_wsapp_logs_basic_ibfk_1` FOREIGN KEY (`id`) REFERENCES `jo_wsapp_logs_basic` (`id`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_cv2users')) {
		Head_Utils::CreateTable('jo_cv2users', 
				'(`cvid` int(25) NOT NULL,
				`userid` int(25) NOT NULL,
				KEY `jo_cv2users_ibfk_1` (`cvid`),
				CONSTRAINT `jo_customview_ibfk_1` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
				CONSTRAINT `jo_users_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_cv2group')) {
		Head_Utils::CreateTable('jo_cv2group', 
				'(`cvid` int(25) NOT NULL,
				`groupid` int(25) NOT NULL,
				KEY `jo_cv2group_ibfk_1` (`cvid`),
				CONSTRAINT `jo_customview_ibfk_2` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
				CONSTRAINT `jo_groups_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `jo_groups` (`groupid`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_cv2role')) {
		Head_Utils::CreateTable('jo_cv2role',
				'(`cvid` int(25) NOT NULL,
				`roleid` varchar(255) NOT NULL,
				KEY `jo_cv2role_ibfk_1` (`cvid`),
				CONSTRAINT `jo_customview_ibfk_3` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
				CONSTRAINT `jo_role_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_cv2rs')) {
		Head_Utils::CreateTable('jo_cv2rs',
				'(`cvid` int(25) NOT NULL,
				`rsid` varchar(255) NOT NULL,
				KEY `jo_cv2role_ibfk_1` (`cvid`),
				CONSTRAINT `jo_customview_ibfk_4` FOREIGN KEY (`cvid`) REFERENCES `jo_customview` (`cvid`) ON DELETE CASCADE,
				CONSTRAINT `jo_rolesd_ibfk_1` FOREIGN KEY (`rsid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE)', true);
	}

	//Rollup Comments Settings table
	if (!Head_Utils::CheckTable('jo_rollupcomments_settings')) {
		Head_Utils::CreateTable('jo_rollupcomments_settings', 
				"(`rollupid` int(19) NOT NULL AUTO_INCREMENT,
				`userid` int(19) NOT NULL,
				`tabid` int(19) NOT NULL,
				`rollup_status` int(2) NOT NULL DEFAULT '0',
				PRIMARY KEY (`rollupid`))", true);
	}
	//END

	$transition_table_name = 'jo_picklist_transitions';
	if (!Head_Utils::CheckTable($transition_table_name)) {
		Head_Utils::CreateTable($transition_table_name,
				'(fieldname VARCHAR(255) NOT NULL PRIMARY KEY,
				module VARCHAR(100) NOT NULL,
				transition_data VARCHAR(1000) NOT NULL)', true);
	}

	//Invite users table mod to support status tracking
	$columns = $db->getColumnNames('jo_invitees');
	if (!in_array('status', $columns)) {
		$db->pquery('ALTER TABLE jo_invitees ADD COLUMN status VARCHAR(50) DEFAULT NULL', array());
	}

	$modules = array();
	$ignoreModules = array('SMSNotifier', 'ModComments');
	$result = $db->pquery('SELECT name FROM jo_tab WHERE isentitytype=? AND name NOT IN ('.generateQuestionMarks($ignoreModules).')', array(1, $ignoreModules));
	while ($row = $db->fetchByAssoc($result)) {
		$modules[] = $row['name'];
	}

	foreach ($modules as $module) {
		$moduleUserSpecificTable = Head_Functions::getUserSpecificTableName($module);
		$moduleInstance = Head_Module::getInstance($module);
		if ($moduleInstance) {
			$fieldInstance = Head_Field::getInstance('starred', $moduleInstance);
			if ($fieldInstance) {
				continue;
			}
			$blockQuery = 'SELECT blocklabel FROM jo_blocks WHERE tabid=? ORDER BY sequence LIMIT 1';
			$result = $db->pquery($blockQuery, array($moduleInstance->id));
			$block = $db->query_result($result, 0, 'blocklabel');
			if ($block) {
				$blockInstance = Head_Block::getInstance($block, $moduleInstance);
				if ($blockInstance) {
					$field = new Head_Field();
					$field->name		= 'starred';
					$field->label		= 'starred';
					$field->table		= $moduleUserSpecificTable;
					$field->presence	= 2;
					$field->displaytype = 6;
					$field->readonly	= 1;
					$field->uitype		= 56;
					$field->typeofdata	= 'C~O';
					$field->quickcreate	= 3;
					$field->masseditable = 0;
					$blockInstance->addField($field);
				}
			}
		}
	}
	//User specific field - star feature 

	$ignoreModules[] = 'Webmails';
	foreach ($modules as $module) {
		if (in_array($module, $ignoreModules)) {
			continue;
		}
		$moduleInstance = Head_Module::getInstance($module);
		if ($moduleInstance) {
			$fieldInstance = Head_Field::getInstance('tags', $moduleInstance);
			if ($fieldInstance) {
				continue;
			}
			$focus = CRMEntity::getInstance($module);
			$tableName = $focus->table_name;

			$blockQuery = 'SELECT blocklabel FROM jo_blocks WHERE tabid=? ORDER BY sequence LIMIT 1';
			$result = $db->pquery($blockQuery, array($moduleInstance->id));
			$block = $db->query_result($result, 0, 'blocklabel');
			if ($block) {
				$blockInstance = Head_Block::getInstance($block, $moduleInstance);
				if ($blockInstance) {
					$field = new Head_Field();
					$field->name		= 'tags';
					$field->label		= 'tags';
					$field->table		= $tableName;
					$field->presence	= 2;
					$field->displaytype	= 6;
					$field->readonly	= 1;
					$field->uitype		= 1;
					$field->typeofdata	= 'V~O';
					$field->columntype	= 'VARCHAR(1)';
					$field->quickcreate	= 3;
					$field->masseditable= 0;
					$blockInstance->addField($field);
				}
			}
		}
	}

	//Add column to track public and private for tags
	$columns = $db->getColumnNames('jo_freetags');
	if (!in_array('visibility', $columns)) {
		$db->pquery("ALTER TABLE jo_freetags ADD COLUMN visibility VARCHAR(100) NOT NULL DEFAULT 'PRIVATE'", array());
	}
	if (!in_array('owner', $columns)) {
		$db->pquery('ALTER TABLE jo_freetags ADD COLUMN owner INT(19) NOT NULL', array());
	}

	//remove ON update field property for tagged_on since below script will update details but we dont want to change time stamp 
	//and we did not find any test case where we will update tagged object
	$db->pquery('ALTER TABLE jo_freetagged_objects MODIFY tagged_on timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP', array());

	$query = 'SELECT DISTINCT tagger_id,tag_id,tag FROM jo_freetagged_objects INNER JOIN jo_freetags ON jo_freetagged_objects.tag_id = jo_freetags.id';
	$result = $db->pquery($query, array());
	$num_rows = $db->num_rows($result);

	if ($num_rows > 0) {
		$tagOwners = array();
		$tagNamesList = array();
		$visibility = Head_Tag_Model::PRIVATE_TYPE;
		for ($i=0; $i<$num_rows; $i++) {
			$row = $db->query_result_rowdata($result, $i);
			$tagId = $row['tag_id'];
			$tagOwners[$tagId][] = $row['tagger_id'];
			$tagNamesList[$tagId] = $row['tag'];
		}
		foreach ($tagOwners as $tagId => $ownerList) {
			$tagName = $tagNamesList[$tagId];
			foreach ($ownerList as $index => $ownerId) {
				//for frist user dont have create seperate tag.for rest of the users we need to create
				if ($index != 0) {
					//creating new Tag
					$newTagId = $db->getUniqueId('jo_freetags');
					$db->pquery('INSERT INTO jo_freetags values(?,?,?,?,?)', array($newTagId, $tagName, $tagName, $visibility, $ownerId));

					//update all existing record tags to new tags 
					$db->pquery('UPDATE jo_freetagged_objects SET tag_id=? WHERE tag_id=? and tagger_id=?', array($newTagId, $tagId, $ownerId));
				} else {
					//update owner column for tag 
					$db->pquery('UPDATE jo_freetags SET owner=? WHERE id=?', array($ownerId, $tagId));
				}
			}
		}
	}

	//Adding color column for picklists
	$fieldResult = $db->pquery('SELECT fieldname FROM jo_field WHERE uitype IN (?,?,?,?) AND tabid NOT IN (?)', array('15', '16', '33', '114', getTabid('Users')));
	$fieldRows = $db->num_rows($fieldResult);
	$ignorePickListFields = array('hdnTaxType', 'email_flag');

	for ($i=0; $i<$fieldRows; $i++) {
		$fieldName = $db->query_result($fieldResult, $i, 'fieldname');
		if (in_array($fieldName, $ignorePickListFields) || !Head_Utils::CheckTable("jo_$fieldName"))
			continue;

		//Add column in jo_tab which will hold source 
		$columns = $db->getColumnNames("jo_$fieldName");
		if (!in_array('color', $columns)) {
			$db->pquery("ALTER TABLE jo_$fieldName ADD COLUMN color VARCHAR(10)", array());
		}
	}

	//Removing color for users module
	$fieldResult = $db->pquery('SELECT fieldname FROM jo_field WHERE uitype IN (?,?,?,?) AND tabid IN (?)', array('15', '16', '33', '114', getTabid('Users')));
	$fieldRows = $db->num_rows($fieldResult);

	for ($i=0; $i<$fieldRows; $i++) {
		$fieldName = $db->query_result($fieldResult, $i, 'fieldname');
		if (!Head_Utils::CheckTable("jo_$fieldName"))
			continue;

		//Drop color column
		$columns = $db->getColumnNames("jo_$fieldName");
		if (in_array('color', $columns)) {
			$db->pquery("ALTER TABLE jo_$fieldName DROP COLUMN color", array());
		}
	}

	//Dashboard Widgets
	if (!Head_Utils::CheckTable('jo_dashboard_tabs')) {
		Head_Utils::CreateTable('jo_dashboard_tabs', 
				'(id int(19) primary key auto_increment,
				tabname VARCHAR(50),
				isdefault INT(1) DEFAULT 0,
				sequence INT(5) DEFAULT 2,
				appname VARCHAR(20),
				modulename VARCHAR(50),
				userid int(11),
				UNIQUE KEY(tabname,userid),
				FOREIGN KEY (userid) REFERENCES jo_users(id) ON DELETE CASCADE)', true);
	}

	$users = Users_Record_Model::getAll();
	$userIds = array_keys($users);
	$defaultTabQuery = 'INSERT INTO jo_dashboard_tabs(tabname,userid) VALUES(?,?) ON DUPLICATE KEY UPDATE tabname=?, userid=?';
	foreach ($userIds as $userId) {
		$db->pquery($defaultTabQuery, array('Default', $userId, 'Default', $userId));
	}

	$columns = $db->getColumnNames('jo_module_dashboard_widgets');
	if (!in_array('reportid', $columns)) {
		$db->pquery('ALTER TABLE jo_module_dashboard_widgets ADD COLUMN reportid INT(19) DEFAULT NULL', array());
	}
	if (!in_array('dashboardtabid', $columns)) {
		$result = $db->pquery('SELECT id FROM jo_dashboard_tabs WHERE userid=? AND tabname=?', array(1, 'Default'));
		$defaultTabid = $db->query_result($result, 0, 'id');
		//Setting admin user default tabid to DEFAULT
		$db->pquery("ALTER TABLE jo_module_dashboard_widgets ADD COLUMN dashboardtabid INT(11) DEFAULT $defaultTabid", array());

		//TODO : this will fail if there are any entries to jo_module_dashboard_widgets
		$db->pquery('ALTER TABLE jo_module_dashboard_widgets ADD CONSTRAINT FOREIGN KEY (dashboardtabid) REFERENCES jo_dashboard_tabs(id) ON DELETE CASCADE', array());
	}
	//End

/*	$result = $db->pquery('SELECT * FROM jo_module_dashboard_widgets', array());
	$num_rows = $db->num_rows($result);
	for ($i=0; $i<$num_rows; $i++) {
		$rowdata = $db->query_result_rowdata($result, $i);
		if ($rowdata['dashboardtabid'] == null) {
			$result1 = $db->pquery('SELECT id FROM jo_dashboard_tabs WHERE userid=? AND tabname=?', array($rowdata['userid'], 'My Dashboard'));
			if ($db->num_rows($result1) > 0) {
				$tabid = $db->query_result($result1, 0, 'id');
				$db->pquery('UPDATE jo_module_dashboard_widgets SET dashboardtabid=? WHERE id=? AND userid=?', array($tabid, $rowdata['id'], $rowdata['userid']));
			}
		}
	}
*/
	//Adding color column for jo_salutationtype.
	$fieldResult = $db->pquery('SELECT fieldname FROM jo_field WHERE fieldname=? AND tabid NOT IN (?)', array('salutationtype', getTabid('Users')));
	$fieldRows = $db->num_rows($fieldResult);

	for ($i=0; $i<$fieldRows; $i++) {
		$fieldName = $db->query_result($fieldResult, $i, 'fieldname');
		if (!Head_Utils::CheckTable("jo_$fieldName")) {
			continue;
		}

		//Add column in jo_tab which will hold source 
		$columns = $db->getColumnNames("jo_$fieldName");
		if (!in_array('color', $columns)) {
			$db->pquery("ALTER TABLE jo_$fieldName ADD COLUMN color VARCHAR(10)", array());
		}
	}

	//Adding Agenda view in default my calendar view settings
	$usersModuleModel = Head_Module_Model::getInstance('Users');
	$activityViewFieldModel = Head_Field_Model::getInstance('activity_view', $usersModuleModel);

	$existingActivityViewTypes = $activityViewFieldModel->getPicklistValues();
	$newActivityView = 'Agenda';
	if (!in_array($newActivityView, $existingActivityViewTypes)) {
		$activityViewFieldModel->setPicklistValues(array($newActivityView));
	}

	//deleting orphan picklist fields that were delete from jo_field table but not from jo_role2picklist table
	$deletedPicklistResult = $db->pquery('SELECT DISTINCT(picklistid) AS picklistid FROM jo_role2picklist 
								WHERE picklistid NOT IN (SELECT jo_picklist.picklistid FROM jo_picklist
										INNER JOIN jo_role2picklist ON jo_role2picklist.picklistid = jo_picklist.picklistid)', array());
	$rows = $db->num_rows($deletedPicklistResult);
	$deletablePicklists = array();
	for ($i=0; $i<$rows; $i++) {
		$deletablePicklists[] = $db->query_result($deletedPicklistResult, $i, 'picklistid');
	}
	if (count($deletablePicklists)) {
		$db->pquery('DELETE FROM jo_role2picklist WHERE picklistid IN ('.generateQuestionMarks($deletablePicklists).')', array($deletablePicklists));
	}

	//table name exceeds more than 50 characters.
	$db->pquery('ALTER TABLE jo_field MODIFY COLUMN tablename VARCHAR(100)', array());

	if (!Head_Utils::CheckTable('jo_report_shareusers')) {
		Head_Utils::CreateTable('jo_report_shareusers',
				'(`reportid` int(25) NOT NULL,
				`userid` int(25) NOT NULL,
				KEY `jo_report_shareusers_ibfk_1` (`reportid`),
				CONSTRAINT `jo_reports_reportid_ibfk_1` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
				CONSTRAINT `jo_users_userid_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `jo_users` (`id`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_report_sharegroups')) {
		Head_Utils::CreateTable('jo_report_sharegroups', 
				'(`reportid` int(25) NOT NULL,
				`groupid` int(25) NOT NULL,
				KEY `jo_report_sharegroups_ibfk_1` (`reportid`),
				CONSTRAINT `jo_report_reportid_ibfk_2` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
				CONSTRAINT `jo_groups_groupid_ibfk_1` FOREIGN KEY (`groupid`) REFERENCES `jo_groups` (`groupid`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_report_sharerole')) {
		Head_Utils::CreateTable('jo_report_sharerole',
				'(`reportid` int(25) NOT NULL,
				`roleid` varchar(255) NOT NULL,
				KEY `jo_report_sharerole_ibfk_1` (`reportid`),
				CONSTRAINT `jo_report_reportid_ibfk_3` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
				CONSTRAINT `jo_role_roleid_ibfk_1` FOREIGN KEY (`roleid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE)', true);
	}

	if (!Head_Utils::CheckTable('jo_report_sharers')) {
		Head_Utils::CreateTable('jo_report_sharers',
				'(`reportid` int(25) NOT NULL,
				`rsid` varchar(255) NOT NULL,
				KEY `jo_report_sharers_ibfk_1` (`reportid`),
				CONSTRAINT `jo_report_reportid_ibfk_4` FOREIGN KEY (`reportid`) REFERENCES `jo_report` (`reportid`) ON DELETE CASCADE,
				CONSTRAINT `jo_rolesd_rsid_ibfk_1` FOREIGN KEY (`rsid`) REFERENCES `jo_role` (`roleid`) ON DELETE CASCADE)', true);
	}

	//Migrating existing relations to N:N or 1:N based on relation fieldid
	$query = "UPDATE jo_relatedlists SET relationtype='N:N' WHERE relationfieldid IS NULL";
	$result = $db->pquery($query, array());

	$query = "UPDATE jo_relatedlists SET relationtype='1:N' WHERE relationfieldid IS NOT NULL";
	$result = $db->pquery($query, array());

	// For Google Synchronization
	Head_Link::addLink(getTabid('Contacts'), 'EXTENSIONLINK', 'Google', 'Contacts/view/Extension?extensionModule=Google&extensionView=Index&mode=settings');
	Head_Link::addLink(getTabid('Calendar'), 'EXTENSIONLINK', 'Google', 'Calendar/view/Extension?extensionModule=Google&extensionView=Index&mode=settings');
	
	//Add enabled column in jo_google_sync_settings
	$colums = $db->getColumnNames('jo_google_sync_settings');
	if (!in_array('enabled', $colums)) {
		$query = 'ALTER TABLE jo_google_sync_settings ADD COLUMN enabled TINYINT(3) DEFAULT 1';
		$db->pquery($query, array());
	}

	$result = $db->pquery('UPDATE jo_tab SET parent=NULL WHERE name=?', array('ExtensionStore'));

	//Start: Tax Enhancements - Compound Taxes, Regional Taxes, Deducted Taxes, Other Charges
	//Creating regions table
	if (!Head_Utils::checkTable('jo_taxregions')) {
		$db->pquery('CREATE TABLE jo_taxregions(regionid INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL)', array());
	}

	if (!Head_Utils::checkTable('jo_inventorycharges')) {
		//Creating inventory charges table
		$sql = 'CREATE TABLE jo_inventorycharges(
					chargeid INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(100) NOT NULL,
					format VARCHAR(10),
					type VARCHAR(10),
					value DECIMAL(12,5),
					regions TEXT,
					istaxable INT(1) NOT NULL DEFAULT 1,
					taxes VARCHAR(1024),
					deleted INT(1) NOT NULL DEFAULT 0
				)';
		$db->pquery($sql, array());

		$taxIdsList = array();
		$result = $db->pquery('SELECT taxid FROM jo_shippingtaxinfo', array());
		while ($rowData = $db->fetch_array($result)) {
			$taxIdsList[] = $rowData['taxid'];
		}

		$db->pquery('INSERT INTO jo_inventorycharges VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)', array(1, 'Shipping & Handling', 'Flat', 'Fixed', '', '[]', 1, ZEND_JSON::encode($taxIdsList), 0));
	}

	if (!Head_Utils::checkTable('jo_inventorychargesrel')) {
		//Creating inventory charges relation table
		$db->pquery('CREATE TABLE jo_inventorychargesrel(recordid INT(19) NOT NULL, charges TEXT)', array());

		$shippingTaxNamesList = array();
		$result = $db->pquery('SELECT taxid, taxname FROM jo_shippingtaxinfo', array());
		while ($rowData = $db->fetch_array($result)) {
			$shippingTaxNamesList[$rowData['taxid']] = $rowData['taxname'];
		}

		$tablesList = array('quoteid' => 'jo_quotes', 'purchaseorderid' => 'jo_purchaseorder', 'salesorderid' => 'jo_salesorder', 'invoiceid' => 'jo_invoice');
		$isResultExists = false;

		$query = 'INSERT INTO jo_inventorychargesrel VALUES';
		foreach ($tablesList as $index => $tableName) {
			$sql = "SELECT jo_inventoryshippingrel.*, s_h_amount FROM jo_inventoryshippingrel
			INNER JOIN $tableName ON $tableName.$index = jo_inventoryshippingrel.id";

			$result = $db->pquery($sql, array());
			while ($rowData = $db->fetch_array($result)) {
				$isResultExists = true;
				$recordId = $rowData['id'];

				$taxesList = array();
				foreach ($shippingTaxNamesList as $taxId => $taxName) {
					$taxesList[$taxId] = $rowData[$taxName];
				}

				$query .= "($recordId, '".Zend_Json::encode(array(1 => array('value' => $rowData['s_h_amount'], 'taxes' => $taxesList)))."'), ";
			}
		}
		if ($isResultExists) {
			$db->pquery(rtrim($query, ', '), array());
		}
	}

	//Updating existing tax tables
	$taxTablesList = array('jo_inventorytaxinfo', 'jo_shippingtaxinfo');
	foreach ($taxTablesList as $taxTable) {
		$columns = $db->getColumnNames($taxTable);
		if (!in_array('method', $columns)) {
			$db->pquery("ALTER TABLE $taxTable ADD COLUMN method VARCHAR(10)", array());
		}
		if (!in_array('type', $columns)) {
			$db->pquery("ALTER TABLE $taxTable ADD COLUMN type VARCHAR(10)", array());
		}
		if (!in_array('compoundon', $columns)) {
			$db->pquery("ALTER TABLE $taxTable ADD COLUMN compoundon VARCHAR(400)", array());
		}
		if (!in_array('regions', $columns)) {
			$db->pquery("ALTER TABLE $taxTable ADD COLUMN regions TEXT", array());
		}

		$db->pquery("UPDATE $taxTable SET method =?, type=?, compoundon=?, regions=?", array('Simple', 'Fixed', '[]', '[]'));
	}

	//Updating existing tax tables
	$columns = $db->getColumnNames('jo_producttaxrel');
	if (!in_array('regions', $columns)) {
		$db->pquery('ALTER TABLE jo_producttaxrel ADD COLUMN regions TEXT', array());
	}
	$db->pquery('UPDATE jo_producttaxrel SET regions=?', array('[]'));

	$modulesList = array('Quotes' => 'jo_quotes', 'PurchaseOrder' => 'jo_purchaseorder', 'SalesOrder' => 'jo_salesorder', 'Invoice' => 'jo_invoice');
	$fieldName = 'region_id';

	foreach ($modulesList as $moduleName => $tableName) {
		//Updating existing inventory tax tables
		$columns = $db->getColumnNames($tableName);
		if (!in_array('compound_taxes_info', $columns)) {
			$db->pquery("ALTER TABLE $tableName ADD COLUMN compound_taxes_info TEXT", array());
		}
		$db->pquery('UPDATE '.$tableName.' SET compound_taxes_info=?', array('[]'));

		//creating new field in entity tables
		$moduleInstance = Head_Module::getInstance($moduleName);
		$blockInstance = Head_Block::getInstance('LBL_ITEM_DETAILS', $moduleInstance);

		$fieldInstance = Head_Field::getInstance($fieldName, $moduleInstance);
		if (!$fieldInstance) {
			$fieldInstance = new Head_Field();

			$fieldInstance->name = $fieldName;
			$fieldInstance->column		= $fieldName;
			$fieldInstance->table		= $tableName;
			$fieldInstance->label		= 'Tax Region';
			$fieldInstance->columntype	= 'int(19)';
			$fieldInstance->typeofdata	= 'N~O';
			$fieldInstance->uitype		= '16';
			$fieldInstance->readonly	= '0';
			$fieldInstance->displaytype	= '5';
			$fieldInstance->masseditable= '0';

			$blockInstance->addField($fieldInstance);
		}
	}
	//End: Tax Enhancements - Compound Taxes, Regional Taxes, Deducted Taxes, Other Charges

	$restrictedModules = array('ModComments');
	$appsList = array(	'SALES'		=> array('Potentials', 'Quotes', 'Contacts', 'Accounts'),
						'PROJECT'	=> array('Project', 'ProjectTask', 'ProjectMilestone', 'Contacts', 'Accounts'));

	$menuModelsList = Head_Module_Model::getEntityModules();
	$menuStructure = Head_MenuStructure_Model::getInstanceFromMenuList($menuModelsList);
	$menuGroupedByParent = $menuStructure->getMenuGroupedByParent();
	$menuGroupedByParent = $menuStructure->regroupMenuByParent($menuGroupedByParent);
	foreach ($menuGroupedByParent as $app => $appModules) {
		$modules = array();
		if ($appsList[$app]) {
			$modules = $appsList[$app];
		}
		foreach ($appModules as $moduleName => $moduleModel) {
			if (!in_array($moduleName, $modules)) {
				$modules[] = $moduleName;
			}
		}
		
		// have to rewrite "addModuleToApp()" function
/*		foreach ($modules as $moduleName) {
			if (!in_array($moduleName, $restrictedModules)) {
				Settings_MenuManager_Module_Model::addModuleToApp($moduleName, $app);
			}
		}*/
	}

	$result = $db->pquery('SELECT tabid,name FROM jo_tab', array());
	$moduleTabIds = array();
	while ($row = $db->fetchByAssoc($result)) {
		$moduleName = $row['name'];
		$moduleTabIds[$moduleName] = $row['tabid'];
	}
	$leadsModuleInstance = Head_Module::getInstance('Leads');
	$quotesModuleInstance = Head_Module::getInstance('Quotes');
	$leadsModuleInstance->unsetRelatedList($quotesModuleInstance, 'Quotes', 'get_quotes');

	$leadsTabId = getTabid('Leads');
	$quotesTabId = getTabid('Quotes');
	$query = 'SELECT 1 FROM jo_relatedlists WHERE tabid=? AND related_tabid =? AND name=? AND label=?';
	$params = array($leadsTabId, $quotesTabId, 'get_quotes', 'Quotes');
	$result = $db->pquery($query, $params);

	if (!Head_Utils::CheckTable('jo_convertpotentialmapping')) {
		Head_Utils::CreateTable('jo_convertpotentialmapping',
				"(`cfmid` int(19) NOT NULL AUTO_INCREMENT,
				`potentialfid` int(19) NOT NULL,
				`projectfid` int(19) DEFAULT NULL,
				`editable` int(11) DEFAULT '1',
				PRIMARY KEY (`cfmid`)
				)", true);
		$fieldMap = array(
			array('potentialname', 'projectname', 0),
			array('description', 'description', 1),
		);

		$potentialTab = getTabid('Potentials');
		$projectTab = getTabid('Project');
		$mapSql = 'INSERT INTO jo_convertpotentialmapping(potentialfid, projectfid, editable) values(?,?,?)';

		foreach ($fieldMap as $values) {
			$potentialfid = getFieldid($potentialTab, $values[0]);
			$projectfid = getFieldid($projectTab, $values[1]);
			$editable = $values[4];
			$db->pquery($mapSql, array($potentialfid, $projectfid, $editable));
		}
	}

	$columns = $db->getColumnNames('jo_potential');
	if (!in_array('converted', $columns)) {
		$db->pquery('ALTER TABLE jo_potential ADD converted INT(1) NOT NULL DEFAULT 0', array());
	}

	$Head_Utils_Log = true;
	$moduleArray = array('Project' => 'LBL_PROJECT_INFORMATION');
	foreach ($moduleArray as $module => $block) {
		$moduleInstance = Head_Module::getInstance($module);
		$blockInstance = Head_Block::getInstance($block, $moduleInstance);

		$field = Head_Field::getInstance('isconvertedfrompotential', $moduleInstance);
		if (!$field) {
			$field = new Head_Field();
			$field->name		= 'isconvertedfrompotential';
			$field->label		= 'Is Converted From Opportunity';
			$field->uitype		= 56;
			$field->column		= 'isconvertedfrompotential';
			$field->displaytype	= 2;
			$field->columntype	= 'INT(1) NOT NULL DEFAULT 0';
			$field->typeofdata	= 'C~O';
			$blockInstance->addField($field);
		}
	}

	$projectInstance = Head_Module::getInstance('Project');
	$calendarModule = Head_Module::getInstance('Calendar');
	$projectInstance->setRelatedList($calendarModule, 'Activities', Array('ADD'));

	$quotesModule = Head_Module::getInstance('Quotes');
	$projectInstance->setRelatedList($quotesModule, 'Quotes', Array('SELECT'));

	if (!Head_Field::getInstance('potentialid', $projectInstance)) {
		$blockInstance = Head_Block_Model::getInstance('LBL_PROJECT_INFORMATION', $projectInstance);
		$potentialField = new Head_Field();
		$potentialField->name		= 'potentialid';
		$potentialField->label		= 'Potential Name';
		$potentialField->uitype		= 10;
		$potentialField->typeofdata	= 'I~O';

		$blockInstance->addField($potentialField);
		$potentialField->setRelatedModules(Array('Potentials'));
	}

	$productsInstance = Head_Module_Model::getInstance('Products');
	$poInstance = Head_Module_Model::getInstance('PurchaseOrder');
	$productsInstance->setRelatedList($poInstance, 'PurchaseOrder', false, 'get_purchase_orders');

	$modules = array('Potentials', 'Contacts', 'Accounts', 'Project');
	foreach ($modules as $moduleName) {
		$tabId = getTabid($moduleName);
		if ($moduleName == 'Project') {
			$db->pquery('UPDATE jo_field SET displaytype=? WHERE fieldname=? AND tabid=?', array(1, 'isconvertedfrompotential', $tabId));
		} else {
			$db->pquery('UPDATE jo_field SET displaytype=? WHERE fieldname=? AND tabid=?', array(1, 'isconvertedfromlead', $tabId));
		}
		Head_Cache::flushModuleCache($moduleName);
	}

	$db->pquery('DELETE FROM jo_links WHERE linktype=? AND handler_class=?', array('DETAILVIEWBASIC', 'Documents'));

	$result = $db->pquery('SELECT templateid FROM jo_emailtemplates ORDER BY templateid DESC LIMIT 1', array());
	$db->pquery('UPDATE jo_emailtemplates_seq SET id=?', array($db->query_result($result, 0, 'templateid')));

	//Migrating data missed in jo_settings_field from file to database.
	//Start:: user management block
	$userResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_USER_MANAGEMENT'));
	if ($db->num_rows($userResult)) {
		$userManagementBlockId = $db->query_result($userResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(1, $userManagementBlockId));
	} else {
		$userManagementBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($userManagementBlockId, 'LBL_USER_MANAGEMENT', 1));
	}

	$userManagementFields = array(	'LBL_USERS'	=> 'Users/Settings/List',
					'LBL_ROLES'	=> 'Roles/Settings/Index',
					'LBL_PROFILES'	=> 'Profiles/Settings/List',
					'LBL_SHARING_ACCESS'	=> 'SharingAccess/Settings/Index',
					'USERGROUPLIST'		=> 'Groups/Settings/List',
					'LBL_LOGIN_HISTORY_DETAILS'	=> 'LoginHistory/Settings/List');
		
	$userManagementIcons = array(
					'LBL_USERS' => 'fa fa-user',
					'LBL_ROLES' => 'fa fa-registered',
					'LBL_PROFILES' => 'fa fa-user-plus',
					'LBL_SHARING_ACCESS' => 'fa fa-share-alt',
					'USERGROUPLIST' => 'fa fa-users',
					'LBL_LOGIN_HISTORY_DETAILS' => 'fa fa-history'
				    );

	$userManagementSequence = 1;
	foreach ($userManagementFields as $fieldName => $linkTo) {
		$db->pquery('UPDATE jo_settings_field SET sequence=?, iconpath=?, linkto=? WHERE name=? AND blockid=?', array($userManagementSequence++,$userManagementIcons[$fieldName] ,$linkTo, $fieldName, $userManagementBlockId));
	}
	//End:: user management block

	//Start:: module manager block
	$moduleManagerResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_MODULE_MANAGER'));
	if ($db->num_rows($moduleManagerResult)) {
		$moduleManagerBlockId = $db->query_result($moduleManagerResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(2, $moduleManagerBlockId));
	} else {
		$moduleManagerBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($moduleManagerBlockId, 'LBL_MODULE_MANAGER', 2));
	}

	$moduleManagerFields = array(	
					'VTLIB_LBL_MODULE_MANAGER'	=> 'ModuleManager/Settings/List',
					'LBL_EDIT_FIELDS'		=> 'LayoutEditor/Settings/Index',
					'Labels Editor'			=> 'LanguageEditor/List',
					'LBL_CUSTOMIZE_MODENT_NUMBER'	=> 'Head/Settings/CustomRecordNumbering'
				);
		
	$moduleManagerFields_icons = array(
					'VTLIB_LBL_MODULE_MANAGER'      => 'fa fa-chain',
                                        'LBL_EDIT_FIELDS'               => 'fa fa-codepen',
                                        'Labels Editor'                 => 'fa fa-edit',
                                        'LBL_CUSTOMIZE_MODENT_NUMBER'   => 'fa fa-sort-numeric-desc'
					);
	$moduleManagerSequence = 1;
	foreach ($moduleManagerFields as $fieldName => $linkTo) {
		$db->pquery('UPDATE jo_settings_field SET sequence=?, linkto=?, iconpath=?, blockid=? WHERE name=?', array($moduleManagerSequence++, $linkTo, $moduleManagerFields_icons[$fieldName] ,$moduleManagerBlockId, $fieldName));
	}
	//End:: module manager block

	//Start:: automation block
	$automationResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_AUTOMATION'));
	if ($db->num_rows($automationResult)) {
		$automationBlockId = $db->query_result($automationResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(3, $automationBlockId));
	} else {
		$automationBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($automationBlockId, 'LBL_AUTOMATION', 3));
	}

	$automationFields = array(	'Webforms'	=> 'Webforms/Settings/List',
					'Scheduler'	=> 'CronTasks/Settings/List',
					'LBL_LIST_WORKFLOWS'=> 'Workflows/Settings/List');
		
	$automationFields_icons = array(
					'Webforms'      => 'fa fa-file-zip-o',
                                        'Scheduler'     => 'fa fa-clock-o',
                                        'LBL_LIST_WORKFLOWS'=> 'fa fa-sitemap'
					);

	$automationSequence = 1;
	foreach ($automationFields as $fieldName => $linkTo) {
		$db->pquery('UPDATE jo_settings_field SET sequence=?, linkto=?, iconpath=? , blockid=? WHERE name=?', array($automationSequence++, $linkTo, $automationFields_icons[$fieldName], $automationBlockId, $fieldName));
	}
	//End:: automation block

	//Start:: configuration block
	$configurationResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_CONFIGURATION'));
	if ($db->num_rows($configurationResult)) {
		$configurationBlockId = $db->query_result($configurationResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(4, $configurationBlockId));
	} else {
		$configurationBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($configurationBlockId, 'LBL_CONFIGURATION', 4));
	}

	$configurationFields = array(	
					'LBL_COMPANY_DETAILS'		=> 'Head/Settings/CompanyDetails',
					'LBL_CUSTOMER_PORTAL'		=> 'CustomerPortal/Settings/Index',
					'LBL_CURRENCY_SETTINGS'		=> 'Currency/Settings/List',
					'LBL_MAIL_SERVER_SETTINGS'	=> 'Head/Settings/OutgoingServerDetail',
					'Configuration Editor'		=> 'Head/Settings/ConfigEditorDetail',
					'LBL_PICKLIST_EDITOR'		=> 'Picklist/Settings/Index',
					'LBL_PICKLIST_DEPENDENCY'	=> 'PickListDependency/Settings/List',
				);

	$db->pquery('UPDATE jo_settings_field SET name=? WHERE name=?', array('LBL_PICKLIST_DEPENDENCY', 'LBL_PICKLIST_DEPENDENCY_SETUP'));
	$configurationSequence = 1;
	foreach ($configurationFields as $fieldName => $linkTo) {
		$db->pquery('UPDATE jo_settings_field SET sequence=?, linkto=?, blockid=? WHERE name=?', array($configurationSequence++, $linkTo, $configurationBlockId, $fieldName));
	}
	//End:: configuration block

	//Start:: marketing sales block
	$marketingSalesResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_MARKETING_SALES'));
	if ($db->num_rows($marketingSalesResult)) {
		$marketingSalesBlockId = $db->query_result($marketingSalesResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(5, $marketingSalesBlockId));
	} else {
		$marketingSalesBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($marketingSalesBlockId, 'LBL_MARKETING_SALES', 5));
	}

	$marketingSalesFields = array(	'LBL_LEAD_MAPPING'	=> 'Leads/Settings/MappingDetail',
					'LBL_OPPORTUNITY_MAPPING'	=> 'Potentials/Settings/MappingDetail');

	$marketingSalesFields_icons = array (
					'LBL_LEAD_MAPPING' => 'fa fa-exchange',
					'LBL_OPPORTUNITY_MAPPING' => 'fa fa-map-signs'
						);
	$marketingSequence = 1;
	foreach ($marketingSalesFields as $fieldName => $linkTo) {
		$marketingFieldResult = $db->pquery('SELECT 1 FROM jo_settings_field WHERE name=?', array($fieldName));
		if (!$db->num_rows($marketingFieldResult)) {
			$updateQuery = 'INSERT INTO jo_settings_field(fieldid,blockid,name,iconpath,description,linkto,sequence,active,pinned) VALUES(?,?,?,?,?,?,?,?,?)';
			$params = array($db->getUniqueID('jo_settings_field'), $marketingSalesBlockId, $fieldName, $marketingSalesFields_icons[$fieldName], 'NULL', $linkTo, $marketingSequence++, 0, 1);
			$db->pquery($updateQuery, $params);
		}
	}
	//End:: marketing sales block

	//Start:: inventory block
	$inventoryResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_INVENTORY'));
	if ($db->num_rows($inventoryResult)) {
		$inventoryBlockId = $db->query_result($inventoryResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(6, $inventoryBlockId));
	} else {
		$inventoryBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($inventoryBlockId, 'LBL_INVENTORY', 6));
	}

	$inventoryFields = array(	'LBL_TAX_SETTINGS'				=> 'Head/Settings/TaxIndex',
								'INVENTORYTERMSANDCONDITIONS'	=> 'Head/Settings/TermsAndConditionsEdit');

	$inventorySequence = 1;
	foreach ($inventoryFields as $fieldName => $linkTo) {
		$db->pquery('UPDATE jo_settings_field SET sequence=?, linkto=?, blockid=? WHERE name=?', array($inventorySequence++, $linkTo, $inventoryBlockId, $fieldName));
	}
	//End:: inventory block

	//Start:: mypreference block
	$myPreferenceResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_MY_PREFERENCES'));
	if ($db->num_rows($myPreferenceResult)) {
		$myPreferenceBlockId = $db->query_result($myPreferenceResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(7, $myPreferenceBlockId));
	} else {
		$myPreferenceBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid,label,sequence) VALUES(?,?,?)', array($myPreferenceBlockId, 'LBL_MY_PREFERENCES', 7));
	}

	$myPreferenceFields = array(	
					'My Preferences'	=> 'Users/Settings/PreferenceDetail/1',
					'Calendar Settings' => 'Users/Settings/Calendar/1',
					'LBL_MY_TAGS'		=> 'Tags/Settings/List/1',
					'LBL_MENU_MANAGEMENT' => 'MenuManager/Settings/Index'
				   );
	
	$mypreference_icon_array = array(
						'My Preferences' => 'fa fa-user' ,
						'Calendar Settings' => 'fa fa-calendar-check-o' ,
						'LBL_MY_TAGS' => 'fa fa-tags' ,
						'LBL_MENU_MANAGEMENT' => 'fa fa-bars'
					);

	$myPreferenceSequence = 1;
	foreach ($myPreferenceFields as $fieldName => $linkTo) {
		$myPrefFieldResult = $db->pquery('SELECT 1 FROM jo_settings_field WHERE name=?', array($fieldName));
		if (!$db->num_rows($myPrefFieldResult)) {
			$fieldQuery = 'INSERT INTO jo_settings_field(fieldid,blockid,name,iconpath,description,linkto,sequence,active,pinned) VALUES(?,?,?,?,?,?,?,?,?)';
			$params = array($db->getUniqueID('jo_settings_field'), $myPreferenceBlockId, $fieldName, $mypreference_icon_array[$fieldName], 'NULL', $linkTo, $myPreferenceSequence++, 0, 1);
			$db->pquery($fieldQuery, $params);
		}
	}
	//End:: mypreference block

	//Start:: integrations block
	$integrationBlockResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_INTEGRATION'));
	if ($db->num_rows($integrationBlockResult)) {
		$integrationBlockId = $db->query_result($integrationBlockResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(8, $integrationBlockId));
	} else {
		$integrationBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($integrationBlockId, 'LBL_INTEGRATION', 8));
	}
	//End:: integrations block

	//Start:: extensions block
	$extensionResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_EXTENSIONS'));
	if ($db->num_rows($extensionResult)) {
		$extensionsBlockId = $db->query_result($extensionResult, 0, 'blockid');
		$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE blockid=?', array(9, $extensionsBlockId));
	} else {
		$extensionsBlockId = $db->getUniqueID('jo_settings_blocks');
		$db->pquery('INSERT INTO jo_settings_blocks(blockid, label, sequence) VALUES(?, ?, ?)', array($extensionsBlockId, 'LBL_EXTENSIONS', 9));
	}

	$extensionFields = array(	'LBL_GOOGLE'			=> 'Contacts/Settings/Extension/Google/Index/settings');

	$extSequence = 1;
	foreach ($extensionFields as $fieldName => $linkTo) {
		$extFieldResult = $db->pquery('SELECT 1 FROM jo_settings_field WHERE name=?', array($fieldName));
		if (!$db->num_rows($extFieldResult)) {
			$fieldQuery = 'INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence, active, pinned) VALUES(?,?,?,?,?,?,?,?,?)';
			$params = array($db->getUniqueID('jo_settings_field'), $extensionsBlockId, $fieldName, 'fa fa-google', 'NULL', $linkTo, $extSequence++, 0, 1);
			$db->pquery($fieldQuery, $params);
		}
	}
	//End:: extensions block

	//Deleting duplicate entries of blocks and Fields
	$blocksAndNameFields = array(	$userManagementBlockId => array_keys($userManagementFields),
									$moduleManagerBlockId => array_keys($moduleManagerFields),
									$automationBlockId => array_keys($automationFields),
									$configurationBlockId => array_keys($configurationFields),
									$inventoryBlockId => array_keys($inventoryFields));

	foreach ($blocksAndNameFields as $blockId => $blockFields) {
		//Delete duplicate entries of block fields in other blocks.
		$db->pquery('DELETE FROM jo_settings_field WHERE name IN ('.generateQuestionMarks($blockFields).') AND blockid != ?', array($blockFields, $blockId));

		//Delete non block fields in specific blocks
		$db->pquery('DELETE FROM jo_settings_field WHERE name NOT IN ('.generateQuestionMarks($blockFields).') AND blockid=?', array($blockFields, $blockId));
	}

	//Deleting unused blocks from Settings page
	$unusedSettingsBlocks = array('LBL_STUDIO', 'LBL_COMMUNICATION_TEMPLATES');
	$db->pquery('DELETE FROM jo_settings_blocks WHERE label IN ('.generateQuestionMarks($unusedSettingsBlocks).')', array($unusedSettingsBlocks));
	echo 'Deleted unused blocks from settings page';

	//Update other settings block sequence to last
	$db->pquery('UPDATE jo_settings_blocks SET sequence=? WHERE label=?', array('10', 'LBL_OTHER_SETTINGS'));
	$otheBlockResult = $db->pquery('SELECT blockid FROM jo_settings_blocks WHERE label=?', array('LBL_OTHER_SETTINGS'));
	if ($db->num_rows($otheBlockResult) > 0) {
		$otherBlockId = $db->query_result($otheBlockResult, 0, 'blockid');
	}

	$duplicateOtherBlockFields = array('LBL_ANNOUNCEMENT');
	$db->pquery('DELETE FROM jo_settings_field WHERE name IN ('.generateQuestionMarks($duplicateOtherBlockFields).') AND blockid=?', array($duplicateOtherBlockFields, $otherBlockId));
	//Migration of data to jo_settings blocks and fields ends

	$result = $db->pquery('SELECT cvid, entitytype FROM jo_customview WHERE viewname=?', array('All'));
	if ($result && $db->num_rows($result) > 0) {
		while ($row = $db->fetch_array($result)) {
			$cvId = $row['cvid'];
			$cvModel = CustomView_Record_Model::getInstanceById($cvId);
			$cvSelectedFields = $cvModel->getSelectedFields();

			$moduleModel = Head_Module_Model::getInstance($row['entitytype']);
			if ($moduleModel) {
				$fields = $moduleModel->getFields();
				$cvSelectedFieldModels = array();

				foreach ($fields as $fieldModel) {
					$cvSelectedFieldModels[] = decode_html($fieldModel->getCustomViewColumnName());
				}

				foreach ($cvSelectedFields as $cvSelectedField) {
					if (!in_array($cvSelectedField, $cvSelectedFieldModels)) {
						$fieldData = explode(':', $cvSelectedField);
						$fieldName = $fieldData[2];
						$fieldInstance = Head_Field_Model::getInstance($fieldName, $moduleModel);
						if ($fieldInstance) {
							$columnname = decode_html($fieldInstance->getCustomViewColumnName());
							$db->pquery('UPDATE jo_cvcolumnlist SET columnname=? WHERE cvid=? AND columnname=?', array($columnname, $cvId, $cvSelectedField));
						}
					}
				}
			}
		}
	}

	$skippedTables = array('Calendar' => array('jo_seactivityrel', 'jo_cntactivityrel', 'jo_salesmanactivityrel'));
	$allEntityModules = Head_Module_Model::getEntityModules();
	$dbName = $db->dbName;
	foreach ($allEntityModules as $tabId => $moduleModel) {
		$moduleName = $moduleModel->getName();
		$baseTableName = $moduleModel->basetable;
		$baseTableIndex = $moduleModel->basetableid;

		if ($baseTableName) {
			//Checking foriegn key with jo_crmenity
			$query = 'SELECT 1 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
							WHERE CONSTRAINT_SCHEMA=? AND CONSTRAINT_NAME LIKE ?
								AND TABLE_NAME=? AND COLUMN_NAME=?
								AND REFERENCED_TABLE_NAME=? AND REFERENCED_COLUMN_NAME=?';
			$checkIfConstraintExists = $db->pquery($query, array($dbName, '%fk%', $baseTableName, $baseTableIndex, 'jo_crmentity', 'crmid'));
			if ($db->num_rows($checkIfConstraintExists) < 1) {
				$db->pquery("ALTER TABLE $baseTableName ADD CONSTRAINT fk_crmid_$baseTableName FOREIGN KEY ($baseTableIndex) REFERENCES jo_crmentity (crmid) ON DELETE CASCADE", array());
			}

			$focus = CRMEntity::getInstance($moduleName);
			$relatedTables = $focus->tab_name_index;
			unset($relatedTables[$baseTableName]);
			unset($relatedTables['jo_crmentity']);

			if (is_array($relatedTables)) {
				if ($skippedTables[$moduleName]) {
					$relatedTables = array_diff_key($relatedTables, array_flip($skippedTables[$moduleName]));
				}

				//Checking foriegn key with base table
				foreach ($relatedTables as $tableName => $index) {
					$referenceTable = $baseTableName;
					$referenceColumn = $baseTableIndex;

					if ($tableName == 'jo_producttaxrel' || $tableName == 'jo_inventoryproductrel') {
						$referenceTable = 'jo_crmentity';
						$referenceColumn = 'crmid';
					}

					$checkIfRelConstraintExists = $db->pquery($query, array($dbName, '%fk%', $tableName, $index, $referenceTable, $referenceColumn));
					if ($db->num_rows($checkIfRelConstraintExists) < 1) {
						$newForiegnKey = "fk_$referenceColumn"."_$tableName";
						$db->pquery("ALTER TABLE $tableName ADD CONSTRAINT $newForiegnKey FOREIGN KEY ($index) REFERENCES $referenceTable ($referenceColumn) ON DELETE CASCADE", array());
					}
				}
			}
			$db->pquery("DELETE FROM $baseTableName WHERE $baseTableIndex NOT IN (SELECT crmid FROM jo_crmentity WHERE setype=?)", array($moduleName));
		}
	}
    $getSeqId = $db->pquery('select id from jo_customview_seq', array());
    $seqId = $db->query_result($getSeqId, 0, 'id');
    $db->pquery('insert into jo_customview values(?,?,?,?,?,?,?)', array($seqId+1, 'All', 1, 0, 'PDFMaker', 0,1));
    $db->pquery('insert into jo_customview values(?,?,?,?,?,?,?)', array($seqId+2, 'All', 1, 0, 'EmailPlus', 0,1));
    $db->pquery('insert into jo_customview_seq values(?)', array($seqId+2));
	if (is_dir('modules/Head/resources')) {
		rename('modules/Head/resources', 'modules/Head/resources_650');
	}

    $db->pquery('insert into jo_ws_entity (name, handler_path, handler_class, ismodule) values (?, ?, ?, ?)', array('PDFMaker', 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));

    $db->pquery('insert into jo_ws_entity (name, handler_path, handler_class, ismodule) values (?, ?, ?, ?)', array('EmailPlus', 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));

   $db->pquery('insert into jo_ws_entity (name, handler_path, handler_class, ismodule) values (?, ?, ?, ?)', array('ModComments', 'includes/Webservices/HeadModuleOperation.php', 'HeadModuleOperation', 1));

    $db->pquery('update jo_settings_field set linkto=? where name=?', array('MailConverter/Settings/List','LBL_MAIL_SCANNER'));
    $db->pquery('update jo_settings_field set linkto=? where name=?', array('ModTracker/BasicSettings/Settings/ModTracker','ModTracker'));
    $db->pquery('update jo_settings_field set linkto=? where name=?', array('PBXManager/Settings/Index','LBL_PBXMANAGER'));
    $db->pquery('update jo_settings_field set linkto=? where name=?', array('Server/Settings/ProxyConfig','LBL_SYSTEM_INFO'));
    $db->pquery('update jo_settings_field set linkto=? where name=?', array('DefModuleView/Settings/Settings','LBL_DEFAULT_MODULE_VIEW'));

   $db->pquery('INSERT INTO jo_settings_field(fieldid, blockid, name, iconpath, description, linkto, sequence,active, pinned) VALUES (?, ?,?,?,?,?,?,?,?)',array($db->getUniqueID('jo_settings_field'), 6,'Module Studio', 'fa fa-edit', 'LBL_MODULEDESIGNER_DESCRIPTION', 'ModuleDesigner/Settings/Index', 3, 0, 0));

   $db->pquery('insert into jo_settings_blocks values (?,?,?)',array($db->getUniqueID('jo_settings_blocks'), 'LBL_JOFORCE', '11'));
   $getBlockId = $db->pquery('select blockid from jo_settings_blocks where label = ?', array('LBL_JOFORCE'));
   $blockId = $db->query_result($getBlockId, 0, 'blockid');
   $db->pquery('insert into jo_settings_field values (?,?,?,?,?,?,?,?,?)',array($db->getUniqueID('jo_settings_field'), $blockId, 'Contributors', 'fa fa-plus-square', 'Contributors', 'Head/Settings/Credits',1, 0,0));
   $db->pquery('insert into jo_settings_field values (?,?,?,?,?,?,?,?,?)',array($db->getUniqueID('jo_settings_field'), $blockId, 'License', 'fa fa-exclamation-triangle', 'License', 'Head/Settings/License',2, 0,0));
   $db->pquery('insert into jo_settings_field values (?,?,?,?,?,?,?,?,?)',array($db->getUniqueID('jo_settings_field'), 4, 'Google Settings', 'fa fa-cogs', 'Google Synchronization', 'Google/Settings/GoogleSettings',12, 0,0));
	//Update existing package modules
	Install_Utils_Model::installModules();

	//recalculate user files to finish
	RecalculateSharingRules();

	echo '<br>Successfully updated : <b>Head7</b><br>';

// Delete from unwanted dashboard entry from the jo_dashboard_tabs
   $db->pquery('delete from jo_dashboard_tabs where tabname = ?', array('Default'));

//delete unwanted extension links from jo_links table
   $db->pquery('DELETE from jo_links WHERE linklabel = ?', array('Google Contacts'));
   $db->pquery('DELETE from jo_links WHERE linklabel = ?', array('Google Calendar'));
	
}
