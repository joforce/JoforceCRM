<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
     
class EmailPlus_Autologin_Model
{

	public function getAutologinUsers()
	{
		global $adb, $current_user;
		$user_id = $current_user->id;
		$result = $adb->pquery('SELECT  user_id, name, email, password, port FROM rc_server_details  WHERE user_id = ?', array($user_id));
		$rcUser = isset($_SESSION['AutoLoginUser']) ? $_SESSION['AutoLoginUser'] : FALSE;
		for ($i = 0; $i < $adb->num_rows($result); $i++) {
			$account = $adb->raw_query_result_rowdata($result, $i);
			$account['active'] = ($rcUser && $rcUser == $account['user_id']) ? TRUE : FALSE;
			$users[$account['user_id']] = $account;
		}
		return $account;
	}
}
