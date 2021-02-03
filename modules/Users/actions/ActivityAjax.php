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

class Users_ActivityAjax_Action extends Head_RelationAjax_Action {

	public function __construct() {
		parent::__construct();
		$this->exposeMethod('getUserActivityFilters');
	}

	/**
	 * Function to get Filtered Activity
	 * @param Head_Request $request
	 */
    public function getUserActivityFilters(Head_Request $request) {
		$response = new Head_Response();
		$filtertype = $request->get('filtertype');
		$date = '';

		if($filtertype == 'today') {
			$date = date("Y-m-d");
		} else if($filtertype == 'yesterday') {
			$date = date('Y-m-d',strtotime("-1 days"));
		} else if($filtertype == 'by_date') {
			$date = date('Y-m-d',strtotime($request->get('date')));
		}
		$_SESSION['filter'] = [
			'user_id' => $request->get('user_id'),
			'date' => $date
		];

		$_SESSION['selected_id'] = $request->get('user_id');
		$_SESSION['filtertype'] = $request->get('filtertype');
		$response->setResult(array('success' => true, 'message' => 'hi'));
		$response->emit();
    }
}
