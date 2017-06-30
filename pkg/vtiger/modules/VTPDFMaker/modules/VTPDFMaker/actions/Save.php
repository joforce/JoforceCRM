<?php
class VTPDFMaker_Save_Action extends Vtiger_Save_Action {

        public function checkPermission(Vtiger_Request $request) {
                return true;
        }

        public function process(Vtiger_Request $request) {

                $moduleName = $request->getModule();
                $record = $request->get('record');
                $recordModel = new VTPDFMaker_Record_Model();
                $recordModel->setModule($moduleName);

                if(!empty($record)) {
                        $recordModel->setId($record);
                }
		$status = $request->get('status');
		$pdf_settings = array();
		$pdf_settings['file_name'] = $request->get('filename');
                $pdf_settings['page_format'] = $request->get('page_format');
                $pdf_settings['page_orientation'] = $request->get('page_orientation');
                $pdf_settings['margin_top'] = $request->get('margin_top');
                $pdf_settings['margin_bottom'] = $request->get('margin_bottom');
                $pdf_settings['margin_left'] = $request->get('margin_left');
                $pdf_settings['margin_right'] = $request->get('margin_right');
		$pdf_settings['detailview'] = $request->get('detailview');
		$pdf_settings['listview'] = $request->get('listview');
		$settings = base64_encode(serialize($pdf_settings));

                $recordModel->set('name', $request->get('templatename'));
                $recordModel->set('description', $request->get('description'));
                $recordModel->set('module', $request->get('modulename'));
                $recordModel->set('body', $request->get('templatecontent'));
                $recordModel->set('header', $request->get('templatecontent-header'));
                $recordModel->set('footer', $request->get('templatecontent-footer'));

		if(!empty($status))
			$recordModel->set('status', $status);
		else{
			$status = 0;
                        $recordModel->set('status', $status);
		}	
		$recordModel->set('settings', $settings);
                $recordModel->save();

                $loadUrl = $recordModel->getDetailViewUrl();
                header("Location: $loadUrl");

	}

}
