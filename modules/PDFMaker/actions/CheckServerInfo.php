<?php
class PDFMaker_CheckServerInfo_Action extends Head_Action_Controller {

    function checkPermission(Head_Request $request) {
    }


    public function process(Head_Request $request) {
        $db = PearDatabase::getInstance();
        $response = new Head_Response();

        $result = $db->pquery('SELECT 1 FROM jo_systems WHERE server_type = ?', array('email'));
        if($db->num_rows($result)) {
            $response->setResult(true);
        } else {
            $response->setResult(false);
        }
        return $response;

    }

}


