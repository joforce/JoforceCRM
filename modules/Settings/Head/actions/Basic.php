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
class Settings_Head_Basic_Action extends Settings_Head_IndexAjax_View {
    
    function __construct() {
		parent::__construct();
		$this->exposeMethod('updateFieldPinnedStatus');
	}
    
    function process(Head_Request $request) {
		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}
    
    public function updateFieldPinnedStatus(Head_Request $request) {
        $fieldId = $request->get('fieldid');
        $menuItemModel = Settings_Head_MenuItem_Model::getInstanceById($fieldId);
        
        $pin = $request->get('pin');
        if($pin == 'true') {
            $menuItemModel->markPinned();
        }else{
            $menuItemModel->unMarkPinned();
        }
        
	$response = new Head_Response();
	$response->setResult(array('SUCCESS'=>'OK'));
	$response->emit();
    }
    
    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}