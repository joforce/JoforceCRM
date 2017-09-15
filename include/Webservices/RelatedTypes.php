<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

function vtws_relatedtypes($elementType, $user) {
    global $adb, $log;

    $allowedTypes = vtws_listtypes(null, $user);

    $webserviceObject = HeadWebserviceObject::fromName($adb, $elementType);
    $handlerPath  = $webserviceObject->getHandlerPath();
    $handlerClass = $webserviceObject->getHandlerClass();

    require_once $handlerPath;
    $handler = new $handlerClass($webserviceObject, $user, $adb, $log);
    $meta = $handler->getMeta();
    $tabid = $meta->getTabId();

    $sql = "SELECT jo_relatedlists.label, jo_tab.name, jo_tab.isentitytype FROM jo_relatedlists 
            INNER JOIN jo_tab ON jo_tab.tabid=jo_relatedlists.related_tabid 
            WHERE jo_relatedlists.tabid=? AND jo_tab.presence = 0";

    $params = array($tabid);
    $rs = $adb->pquery($sql, $params);

    $return = array('types' => array(), 'information' => array());

    while ($row = $adb->fetch_array($rs)) {
        if (in_array($row['name'], $allowedTypes['types'])) {
            $return['types'][] = $row['name'];
            // There can be same module related under different label - so label is our key.
            $return['information'][$row['label']] = array(
                'name' => $row['name'],
                'label'=> $row['label'],
                'isEntity' => $row['isentitytype']
            );
        }
    }

	return $return;
}

