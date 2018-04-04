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

class Home_UpdateNotification_Action extends Head_Save_Action {

        public function process(Head_Request $request) {
                global $adb, $current_user;
                $module = $request->getModule();
                $notify_module = $request->get('notify_module');

                $current_user_id = $current_user->id;

                $select_query = "SELECT * FROM jo_notification WHERE notifier_id = ? and module_name = ? and is_seen=?"
                $select_array = array($current_user_id, $module, 0);
                $fetch_values = $adb->pquery($select_query, $select_array);

                $notification_id_array = [];

                while($fetch_array = $adb->fetch_array($fetch_values)){
                        array_push($notification_id_array, $fetch_array['id']);
                }

                $current_date = gmdate("Y-m-d H:i:s");
                foreach($notification_id_array as $notification_id)
                {
                        $query = "UPDATE jo_notification SET is_seen = ? and updated_at = ? WHERE id = ?";
                        $value_array = array(1, $current_date, $notification_id);
                        $adb->pquery($query, $value_array);
                }
        }
}
