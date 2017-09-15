<?php
class VTPDFMaker_AddSideBar_Action extends Head_BasicAjax_Action {

        public function process(Head_Request $request) {
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
                        $gettabId = $adb->pquery('select tabid from jo_tab where name = ?', array($module));
                        $tabId = $adb->query_result($gettabId, 0, 'tabid');
			$getLinkId = $adb->pquery('select * from jo_links where tabid = ? and linklabel = ? and linktype = ?', array($tabId, $linklabel, $linktype));
			$linkId = $adb->query_result($getLinkId, 0, 'linkid');
			if(empty($linkId)){
				include_once('vtlib/Head/Module.php');
				$moduleInstance = Head_Module::getInstance($module);
				$moduleInstance->addLink($linktype, $linklabel, $handler_path);
				$response = new Head_Response();
				$response->setEmitType(Head_Response::$EMIT_JSON);
				$response->setResult('Completed');
				$response->emit();
			}
		}
	}
}
