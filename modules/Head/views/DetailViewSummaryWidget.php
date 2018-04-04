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

class Head_DetailViewSummaryWidget_View extends Head_Index_View {
	
	function __construct() {
                parent::__construct();
	}
	
	function checkPermission(Head_Request $request) {
                $moduleName = $request->getModule();
                $recordId = $request->get('record_id');

                $recordPermission = Users_Privileges_Model::isPermitted($moduleName, 'DetailView', $recordId);
                if(!$recordPermission) {
                        throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
                }

                if ($recordId) {
                        $recordEntityName = getSalesEntityType($recordId);
                        if ($recordEntityName !== $moduleName) {
                                throw new AppException(vtranslate('LBL_PERMISSION_DENIED'));
                        }
                }
                return true;
        }
	
	public function process(Head_Request $request){
		global $adb,$current_user;	
		$recordId = $request->get('record_id');
		$tabId = $request->get('module_id');
                $moduleName = $request->getModule();
                $viewer = $this->getViewer($request);

                $widget_arrayValues = getDetailViewSummaryWidget($moduleName);;
		$viewer->assign('WIDGET_ARRAY', $widget_arrayValues);
		$viewer->assign('TABID', $tabId);
		$viewer->assign('CURRENT_USER', $current_user);
		echo $viewer->view('DetailViewSummaryWidget.tpl', $moduleName, true);
	}
}

