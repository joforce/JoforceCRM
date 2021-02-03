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
require_once dirname(__FILE__) . '/ModTracker.php';
require_once 'includes/data/EntityDelta.php';

class ModTrackerHandler extends VTEventHandler
{

	function handleEvent($eventName, $data)
	{
		global $adb, $current_user;
		$moduleName = $data->getModuleName();
		$isTrackingEnabled = ModTracker::isTrackingEnabledForModule($moduleName);
		if (!$isTrackingEnabled) {
			return;
		}
		if ($eventName == 'jo.entity.aftersave.final') {
			$recordId = $data->getId();
			$columnFields = $data->getData();
			$EntityDelta = new EntityDelta();
			$delta = $EntityDelta->getEntityDelta($moduleName, $recordId, true);

			$newerEntity = $EntityDelta->getNewEntity($moduleName, $recordId);
			$newerColumnFields = $newerEntity->getData();

			if (is_array($delta)) {
				$inserted = false;
				foreach ($delta as $fieldName => $values) {
					if ($fieldName != 'modifiedtime') {
						if (!$inserted) {
							$checkRecordPresentResult = $adb->pquery('SELECT * FROM jo_modtracker_basic WHERE crmid = ? AND status = ?', array($recordId, ModTracker::$CREATED));
							if (!$adb->num_rows($checkRecordPresentResult) && $data->isNew()) {
								$status = ModTracker::$CREATED;
							} else {
								$status = ModTracker::$UPDATED;
							}
							$this->id = $adb->getUniqueId('jo_modtracker_basic');
							$changedOn = $newerColumnFields['modifiedtime'];
							if ($moduleName == 'Users') {
								$date_var = date("Y-m-d H:i:s");
								$changedOn =  $adb->formatDate($date_var, true);
							}
							$adb->pquery('INSERT INTO jo_modtracker_basic(id, crmid, module, whodid, changedon, status)
										VALUES(?,?,?,?,?,?)', array(
								$this->id, $recordId, $moduleName,
								$current_user->id, $changedOn, $status
							));
							$inserted = true;
						}
						$adb->pquery(
							'INSERT INTO jo_modtracker_detail(id,fieldname,prevalue,postvalue) VALUES(?,?,?,?)',
							array($this->id, $fieldName, $values['oldValue'], $values['currentValue'])
						);
					}
				}
			}
		}

		if ($eventName == 'jo.entity.beforedelete') {
			$recordId = $data->getId();
			$columnFields = $data->getData();
			$id = $adb->getUniqueId('jo_modtracker_basic');
			$adb->pquery('INSERT INTO jo_modtracker_basic(id, crmid, module, whodid, changedon, status)
				VALUES(?,?,?,?,?,?)', array($id, $recordId, $moduleName, $current_user->id, date('Y-m-d H:i:s', time()), ModTracker::$DELETED));
		}

		if ($eventName == 'jo.entity.afterrestore') {
			$recordId = $data->getId();
			$columnFields = $data->getData();
			$id = $adb->getUniqueId('jo_modtracker_basic');
			$adb->pquery('INSERT INTO jo_modtracker_basic(id, crmid, module, whodid, changedon, status)
				VALUES(?,?,?,?,?,?)', array($id, $recordId, $moduleName, $current_user->id, date('Y-m-d H:i:s', time()), ModTracker::$RESTORED));
		}
	}
}
