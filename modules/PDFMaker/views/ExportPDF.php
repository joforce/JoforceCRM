<?php
class PDFMaker_ExportPDF_View extends Head_BasicAjax_View {

    public function process(Head_Request $request) {
        global $adb;
        $viewer = $this->getViewer ($request);
        $moduleName = $request->getModule();
        $sourceModule = $request->get('source_module');
        $getDetails = $adb->pquery('select * from jo_pdfmaker where module = ? and status = ?', array($sourceModule, 1));
        $count = $adb->num_rows($getDetails);

        $tempId = $adb->query_result($getDetails, 0, 'pdfmakerid');
        $viewer->assign('COUNT', $count);
        $viewer->assign('TEMPLATE', $tempId);
        $viewer->assign('RECORD', $request->get('record'));
        $viewer->assign('MODULE', $moduleName);
        $viewer->assign('source_module', $sourceModule);
        $viewer->view('ExportPDF.tpl', $moduleName);
    }

}
