<?php
class EmailPlus_CheckDetails_View extends Vtiger_Index_View
{

        public function process(Vtiger_Request $request)
        {
		global $adb, $current_user;
		$checkDetails = $adb->pquery('select * from rc_server_details where user_id = ?', array($current_user->id));
		$ifExists = $adb->num_rows($checkDetails);
		if(!empty($ifExists))
			$result = true;
		else
			$result = false;
		$response = new Vtiger_Response();
		$response->setResult($result);
                $response->emit(); die;

	}
}
