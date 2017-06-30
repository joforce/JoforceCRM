<?php

class VTPDFMaker_DownloadPDF_View extends Vtiger_Index_View
{

        public function __construct()   {
                parent::__construct();
                require_once('modules/VTPDFMaker/Helper.php');
        }

	public function process(Vtiger_Request $request) {
		$selectedTemplateId = $request->get('selected_template');
		$sourceModule = $request->get('sourceModule');
                $recordId = $request->get('recordId');
                $helperObj = new Helper();
                $helperObj->convertFieldAndExportPDF($sourceModule, $recordId, $selectedTemplateId);

	}

}
