<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Head_GetStarredRecords_View extends Head_Index_View {

	function process(Head_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();

		$user_specific_table = Head_Functions::getUserSpecificTableName();
		$starred_array = getRecentlyStarred($user_specific_table);
		$moduleModel = Head_Module_Model::getInstance($moduleName);

		$starred_object_array =[];
		foreach($starred_array as $array)
		{
	        	$recordModel = Head_Record_Model::getInstanceById($array['crmid'], $moduleName);
			array_push($starred_object_array, $recordModel);
		}

		$viewer->assign('RECORDS', $starred_object_array);
		$viewer->view('RecordNamesList.tpl', $request->getModule());
        }

}
