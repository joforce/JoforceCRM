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

require_once 'libraries/modlib/Head/Cron.php';
class Home_ScheduleNotification_Model extends Head_Base_Model {

	public static function runScheduledNotification() {
		global $adb;
		$current_date = new DateTime(gmdate("Y-m-d H:i:s"));

		$query = 'SELECT id, created_at FROM jo_notification WHERE is_seen = 1';
		$date_field_array = [];
		$run_query = $adb->pquery($query, array());
		while( $fetch_date = $adb->fetch_array($run_query)) {
			array_push($date_field_array, $fetch_date);
		}

		foreach($date_field_array as $date_field)
		{
			$date = new DateTime( $date_field['created_at'] );
			$interval = date_diff($date, $current_date);
			$date_span = $interval->format('%R%a');
			if($date_span >= 30)
			{
				$delete_query = 'DELETE from jo_notification WHERE id = ?';
				$adb->pquery($delete_query, array($date_field['id']));
			}
		}
	}	
}
