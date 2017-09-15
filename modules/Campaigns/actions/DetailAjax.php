<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

class Campaigns_DetailAjax_Action extends Head_BasicAjax_Action {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('getRecordsCount');
	}

	public function process(Head_Request $request) {
		$mode = $request->get('mode');
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	/**
	 * Function to get related Records count from this relation
	 * @param <Head_Request> $request
	 * @return <Number> Number of record from this relation
	 */
	public function getRecordsCount(Head_Request $request) {
		$moduleName = $request->getModule();
		$relatedModuleName = $request->get('relatedModule');
		$parentId = $request->get('record');
		$label = $request->get('tab_label');

		$parentRecordModel = Head_Record_Model::getInstanceById($parentId, $moduleName);
		$relationListView = Head_RelationListView_Model::getInstance($parentRecordModel, $relatedModuleName, $label);
		$count =  $relationListView->getRelatedEntriesCount();
		$result = array();
		$result['module'] = $moduleName;
		$result['viewname'] = $cvId;
		$result['count'] = $count;

		$response = new Head_Response();
		$response->setEmitType(Head_Response::$EMIT_JSON);
		$response->setResult($result);
		$response->emit();
	}
}
