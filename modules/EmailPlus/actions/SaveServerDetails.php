<?php
class EmailPlus_SaveServerDetails_Action extends Head_Save_Action
{
	public function __construct()   {

	}

	public function process(Head_Request $request) {

		global $adb, $current_user, $site_URL;
		$server_name = $request->get('server');
		$email = $request->get('email');
		$account_type = $request->get('type');
		$port = $request->get('port');
		$password = $request->get('pwd');
		$password_encode = base64_encode($password);
		$getUserId = $adb->pquery('select user_id from rc_server_details where user_id = ?', array($current_user->id));
		$userId = $adb->query_result($getUserId, 0, 'user_id');

		if (isset($userId)) {
			$adb->pquery('update rc_server_details set name = ?, email = ?, account_type = ?, port = ?, password = ? where user_id = ?', array($server_name, $email, $account_type, $port, $password_encode, $userId));
		}
		else{
			$adb->pquery('insert into rc_server_details values(?, ?, ?, ?, ?, ?)', array($current_user->id, $server_name, $email, $password_encode, $account_type, $port));
		}
              header("Location: ".$site_URL."EmailPlus/view/List");
	}

}
