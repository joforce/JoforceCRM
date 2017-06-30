<?php
class VTPDFMaker_ExportPDF_View extends Vtiger_BasicAjax_View {

    public function process(Vtiger_Request $request) {
		global $adb;
                $viewer = $this->getViewer ($request);
                $moduleName = $request->getModule();
		$sourceModule = $request->get('source_module');
	        $moduleInfo = array('Accounts' => 'Organizations', 'SalesOrder' => 'Sales Orders', 'Potentials' => 'Opportunities', 'Contacts' => 'Contacts', 'Leads' => 'Leads', 'Invoice' => 'Invoices', 'Documents' => 'Documents', 'Products' => 'Products', 'HelpDesk' => 'Tickets', 'Quotes' => 'Quotes', 'PurchaseOrder' => 'Purchase Orders', 'PriceBooks' => 'Price Books', 'Vendors' => 'Vendors', 'Services' => 'Services', 'Project' => 'Projects', 'Reports' => 'Reports', 'Faq' => 'Faq', 'Calendar' => 'Calendar', 'Events' => 'Events');	
		$getDetails = $adb->pquery('select * from vtiger_vtpdfmaker where module = ? and status = ?', array($moduleInfo[$sourceModule], 1));
                $count = $adb->num_rows($getDetails);

		$tempId = $adb->query_result($getDetails, 0, 'vtpdfmakerid');
		$viewer->assign('COUNT', $count);
		$viewer->assign('TEMPLATE', $tempId);
                $viewer->assign('RECORD', $request->get('record'));
                $viewer->assign('MODULE', $moduleName);
		$viewer->assign('source_module', $sourceModule);
                $viewer->view('ExportPDF.tpl', $moduleName);
    }

}
