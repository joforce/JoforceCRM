<?php

class Contacts_DeleteMasqueradeUser_Action extends Head_Action_Controller
{
        public function checkPermission(Head_Request $request) {
                return;
        }
        public function process(Head_Request $request)
        {
                global $adb;
                $record = $request->get('record_id');
                $userModel = Users_Record_Model::getCurrentUserModel();
                $query = 'select portal_id from jo_masqueradeuserdetails where  record_id=? and masquerade_module= ?';
		$result = $adb->pquery($query, array($record, 'Contacts'));
                if ($adb->num_rows($result) > 0) {
                        $get_query = $adb->fetchByAssoc($result);
                        $update_query = $adb->pquery('update jo_masqueradeuserdetails set portal_id = 0 where record_id = ?', array($record));
                        $update = $adb->fetchByAssoc($update_query);
                        Users_Record_Model::deleteUserPermanently($get_query['portal_id'], $userModel->getId());
                }
        }
}
