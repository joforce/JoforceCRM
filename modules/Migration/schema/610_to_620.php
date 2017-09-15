<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ******************************************************************************* */
if(defined('VTIGER_UPGRADE')) {
     updateVtlibModule('Google', 'packages/head/optional/Google.zip');
}
if(defined('INSTALLATION_MODE')) {
		// Set of task to be taken care while specifically in installation mode.
}

//Handle migration for http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/7552--senotesrel
$seDeleteQuery="DELETE from jo_senotesrel WHERE crmid NOT IN(select crmid from jo_crmentity)";
Migration_Index_View::ExecuteQuery($seDeleteQuery,array());
$seNotesSql="ALTER TABLE jo_senotesrel ADD CONSTRAINT fk1_crmid FOREIGN KEY(crmid) REFERENCES jo_crmentity(crmid) ON DELETE CASCADE";
Migration_Index_View::ExecuteQuery($seNotesSql,array());

//Update uitype of created_user_id field of jo_field from 53 to 52
$updateQuery = "UPDATE jo_field SET uitype = 52 WHERE fieldname = 'created_user_id'";
Migration_Index_View::ExecuteQuery($updateQuery,array());

/*141*/
//registering handlers for Google sync 
require_once 'includes/main/WebUI.php';
require_once 'modules/WSAPP/Utils.php'; 
require_once 'modules/Google/connectors/Config.php';
wsapp_RegisterHandler('Google_vtigerHandler', 'Google_Head_Handler', 'modules/Google/handlers/Head.php'); 
wsapp_RegisterHandler('Google_vtigerSyncHandler', 'Google_HeadSync_Handler', 'modules/Google/handlers/HeadSync.php'); 

//updating Google Sync Handler names 
$db = PearDatabase::getInstance();
$names = array('Head_GoogleContacts', 'Head_GoogleCalendar'); 
$result = $db->pquery("SELECT stateencodedvalues FROM jo_wsapp_sync_state WHERE name IN (".  generateQuestionMarks($names).")", array($names)); 
$resultRows = $db->num_rows($result); 
$appKey = array(); 
for($i=0; $i<$resultRows; $i++) { 
        $stateValuesJson = $db->query_result($result, $i, 'stateencodedvalues'); 
        $stateValues = Zend_Json::decode(decode_html($stateValuesJson)); 
        $appKey[] = $stateValues['synctrackerid']; 
}

if(!empty($appKey)) { 
    $sql = 'UPDATE jo_wsapp SET name = ? WHERE appkey IN ('.  generateQuestionMarks($appKey).')'; 
    $res = Migration_Index_View::ExecuteQuery($sql, array('Google_vtigerSyncHandler', $appKey)); 
}
        
//Ends 141

//Google Calendar sync changes
/**
 * Please refer this trac (http://trac.vtiger.com/cgi-bin/trac.cgi/ticket/8354#comment:3)
 * for configuration of vtiger to Google OAuth2
 */
global $adb;

if(!Head_Utils::CheckTable('jo_google_oauth2')) {
    Head_Utils::CreateTable('jo_google_oauth2',
            '(service varchar(20),access_token varchar(500),refresh_token varchar(500),userid int(19))',true);
    echo '<br> jo_google_oauth2 table created <br>';
}

//(start)Migrating GoogleCalendar ClientIds in wsapp_recordmapping to support v3
            
$syncTrackerIds = array();

if(Head_Utils::CheckTable('jo_wsapp_sync_state')) {

    $sql = 'SELECT stateencodedvalues from jo_wsapp_sync_state WHERE name = ?';
    $result = $db->pquery($sql,array('Head_GoogleCalendar'));
    $num_of_rows = $adb->num_rows($result);

    for($i=0;$i<$num_of_rows;$i++) {
        $stateEncodedValues = $adb->query_result($result,$i,'stateencodedvalues');
        $htmlDecodedStateEncodedValue = decode_html($stateEncodedValues);
        $stateDecodedValues = json_decode($htmlDecodedStateEncodedValue,true);
        if(is_array($stateDecodedValues) && isset($stateDecodedValues['synctrackerid'])) {
            $syncTrackerIds[] = $stateDecodedValues['synctrackerid'];
        }
    }

}

//$syncTrackerIds - list of all Calendar sync trackerIds

$appIds = array();

if(count($syncTrackerIds)) {

    $sql = 'SELECT appid FROM jo_wsapp WHERE appkey IN (' . generateQuestionMarks($syncTrackerIds) . ')';
    $result = Migration_Index_View::ExecuteQuery($sql,$syncTrackerIds);

    $num_of_rows = $adb->num_rows($result);

    for($i=0;$i<$num_of_rows;$i++) {
        $appId = $adb->query_result($result,$i,'appid');
        if($appId) $appIds[] = $appId;
    }

}

//$appIds - list of all Calendarsync appids

if(count($appIds)) {

    $sql = 'SELECT id,clientid FROM jo_wsapp_recordmapping WHERE appid IN (' . generateQuestionMarks($appIds) . ')';
    $result = Migration_Index_View::ExecuteQuery($sql,$appIds);

    $num_of_rows = $adb->num_rows($result);

    for($i=0;$i<$num_of_rows;$i++) {

        $id = $adb->query_result($result,$i,'id');
        $clientid = $adb->query_result($result,$i,'clientid');

        $parts = explode('/', $clientid);
        $newClientId = end($parts);

        Migration_Index_View::ExecuteQuery('UPDATE jo_wsapp_recordmapping SET clientid = ? WHERE id = ?',array($newClientId,$id));

    }

    echo '<br> jo_wsapp_recordmapping clientid migration completed for CalendarSync';

}
//(end)
            
//Google Calendar sync changes ends here

//Google migration : Create Sync setting table
$sql = 'CREATE TABLE jo_google_sync_settings (user int(11) DEFAULT NULL, 
    module varchar(50) DEFAULT NULL , clientgroup varchar(255) DEFAULT NULL, 
    direction varchar(50) DEFAULT NULL)';
$db->pquery($sql,array());
$sql = 'CREATE TABLE jo_google_sync_fieldmapping ( jo_field varchar(255) DEFAULT NULL,
        google_field varchar(255) DEFAULT NULL, google_field_type varchar(255) DEFAULT NULL,
        google_custom_label varchar(255) DEFAULT NULL, user int(11) DEFAULT NULL)';
$db->pquery($sql,array());
echo '<br>Google sync setting and mapping table added</br>';