<?php
require_once('modules/Settings/Webhooks/WebhookHandler.php');
class WebhookDeleteHandler extends VTEventHandler
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
                        if(in_array('deleted', $events)){
                                $fields = explode(' |##| ', $result['fields']);
                                $column_fields = $entityData->focus->column_fields;
                                $selected_fields = array();
                                foreach($column_fields as $key => $singleValue){
                                        if(in_array($key, $fields)){
                                                $selected_fields[$key] = $singleValue;
                                        }
                                }
				  $selected_fields['record_status'] = 'deleted';
                                  WebhookHandler::postHttpRequest($endpointurl, $selected_fields);
                        }
                        }
                    }
                }
	}
}
