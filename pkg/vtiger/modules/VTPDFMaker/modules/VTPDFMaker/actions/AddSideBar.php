<?php
class VTPDFMaker_AddSideBar_Action extends Vtiger_BasicAjax_Action {

        public function process(Vtiger_Request $request) {
		global $adb;
		$allowed_modules = array('Invoice', 'Quotes', 'PurchaseOrder', 'SalesOrder');
		$module = $request->get('moduleName');
		$viewType = $request->get('type');
		if(in_array($module , $allowed_modules)){
			if($viewType == 'Detail'){
				$linktype = 'DETAILVIEWSIDEBARWIDGET';
				$handler_path = 'module=VTPDFMaker&view=ExportPDF&record=$RECORD$';
				$linklabel = 'PDF Maker';
			}
                        $gettabId = $adb->pquery('select tabid from vtiger_tab where name = ?', array($module));
                        $tabId = $adb->query_result($gettabId, 0, 'tabid');
			$getLinkId = $adb->pquery('select * from vtiger_links where tabid = ? and linklabel = ? and linktype = ?', array($tabId, $linklabel, $linktype));
			$linkId = $adb->query_result($getLinkId, 0, 'linkid');
			if(empty($linkId)){
				include_once('vtlib/Vtiger/Module.php');
				$moduleInstance = Vtiger_Module::getInstance($module);
				$moduleInstance->addLink($linktype, $linklabel, $handler_path);
				$response = new Vtiger_Response();
				$response->setEmitType(Vtiger_Response::$EMIT_JSON);
				$response->setResult('Completed');
				$response->emit();
			}
		}
	}
}
