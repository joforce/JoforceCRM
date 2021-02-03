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
class Google_Module_Model extends Head_Module_Model {
    
    public static function removeSync($module, $id) {
        $db = PearDatabase::getInstance();
        $query = "DELETE FROM jo_google_oauth WHERE service = ? AND userid = ?";
        $db->pquery($query, array($module, $id));
    }
    
    /**
     * Function to delete google synchronization completely. Deletes all mapping information stored.
     * @param <string> $module - Module Name
     * @param <integer> $user - User Id
     */
    public function deleteSync($module, $user) {
        $module = str_replace("Google", '', $module);
        if($module == 'Contacts' || $module == 'Calendar') {
            $name = 'Head_Google'.$module;
        }
        else {
            return;
        }
        $db = PearDatabase::getInstance();
        $db->pquery("DELETE FROM jo_google_oauth2 WHERE service = ? AND userid = ?", array('Google'.$module, $user));
        $db->pquery("DELETE FROM jo_google_sync WHERE googlemodule = ? AND user = ?", array($module, $user));
        
        $result = $db->pquery("SELECT stateencodedvalues FROM jo_wsapp_sync_state WHERE name = ? AND userid = ?", array($name, $user));
        $stateValuesJson = $db->query_result($result, 0, 'stateencodedvalues');
        $stateValues = Zend_Json::decode(decode_html($stateValuesJson));
        $appKey = $stateValues['synctrackerid'];
        
        $result = $db->pquery("SELECT appid FROM jo_wsapp WHERE appkey = ?", array($appKey));
        $appId = $db->query_result($result, 0, 'appid');
        
        $db->pquery("DELETE FROM jo_wsapp_recordmapping WHERE appid = ?", array($appId));
        $db->pquery("DELETE FROM jo_wsapp WHERE appid = ?", array($appId));
        $db->pquery("DELETE FROM jo_wsapp_sync_state WHERE name = ? AND userid = ?", array($name, $user));
        $db->pquery("DELETE FROM jo_google_sync_settings WHERE user = ? AND module = ?", array($user,$module));
        if($module == 'Contacts') {
            $db->pquery("DELETE FROM jo_google_sync_fieldmapping WHERE user = ?", array($user));
        } elseif($module == 'Calendar') {
            $db->pquery("DELETE FROM jo_google_event_calendar_mapping WHERE user_id = ?", array($user));
        }
        Google_Utils_Helper::errorLog();
        
        return;
    }
    
    /*
     * Function to get supported utility actions for a module
     */
    function getUtilityActionsNames() {
        return array();
    }
}

?>