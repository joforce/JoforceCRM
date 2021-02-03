<?php
/*+***********************************************************************************
 * The contents of this file are subject to the Joforce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  Joforce
 * All Rights Reserved.
 *************************************************************************************/
class WebhookHandler extends VTEventHandler
{
        function handleEvent($eventName, $entityData)
        {
		global $adb;
		$getWebhooks = $adb->pquery("select * from jo_webhooks where enabled = ? and events != ''", array(1));
		if($adb->num_rows($getWebhooks) > 0){
	            while($result = $adb->fetch_array($getWebhooks)) {
			$module = $result['targetmodule'];
			$endpointurl = $result['url'];
			$events = $result['events'];
			$events = explode(' |##| ', $events);
			if($module == $entityData->focus->moduleName){
			if(in_array('created', $events) || in_array('updated', $events)){
				$fields = explode(' |##| ', $result['fields']);
				$column_fields = $entityData->focus->column_fields;
				$selected_fields = array();
				foreach($column_fields as $key => $singleValue){
					if(in_array($key, $fields)){
						$selected_fields[$key] = $singleValue;
					}
				}
		                  self::postHttpRequest($endpointurl, $selected_fields);
			}
			}
		    }
		}
	}

    public static function postHttpRequest($ws_url, $postParams = array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $ws_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
