<?php

class PDFMaker_DownloadPDF_View extends Head_Index_View
{

        public function __construct()   {
                parent::__construct();
                require_once('modules/PDFMaker/Helper.php');
        }

	public function process(Head_Request $request) {
		$selectedTemplateId = $request->get('selected_template');
		$sourceModule = $request->get('sourceModule');
                $recordId = $request->get('recordId');
                $helperObj = new Helper();
                $helperObj->convertFieldAndExportPDF($sourceModule, $recordId, $selectedTemplateId);

	}

}
