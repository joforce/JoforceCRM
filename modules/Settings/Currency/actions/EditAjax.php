<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************/

class Settings_Currency_EditAjax_Action extends Settings_Head_Basic_Action {
    
    public function process(Head_Request $request) {
		global $adb;
		global $current_user;
		$current_user_id = $current_user->id;
        $record = $request->get('currency_id');
		$currency_name = Head_Functions::getCurrencyName($record,'');
        $response = new Head_Response();
		$fileName = 'config/config.inc.php';
        try{
	    //write on config file
	    $fileContent = file_get_contents($fileName);
	    if(!empty($fileContent)) {
	    	$updatedFields = array('currency_name' => $currency_name);
	    	foreach ($updatedFields as $fieldName => $fieldValue) {
            	    $patternString = "\$%s = '%s';";
                    $pattern = '/\$' . $fieldName . '[\s]+=([^;]+);/';
                    $replacement = sprintf($patternString, $fieldName, ltrim($fieldValue, '0'));
                    $fileContent = preg_replace($pattern, $replacement, $fileContent);
            	}
            	$filePointer = fopen($fileName, 'w');
            	fwrite($filePointer, $fileContent);
            	fclose($filePointer);

	    	$adb->pquery('update jo_currency_info set defaultid = -11 where id= ?', array($record));
	    	$adb->pquery('update jo_currency_info set defaultid = 0 where id != ?', array($record));
	    	$adb->pquery('update jo_users set currency_id = ? where id = ?', array($record,$current_user_id));
	    	
			$result = $adb->pquery('select * from jo_currency_info where id = ?', array($record));
			$currency_name = $adb->query_result($result,0,'currency_name');
			$currency_code = $adb->query_result($result,0,'currency_code');
			$currency_symbol = $adb->query_result($result,0,'currency_symbol');


	    	$result = $adb->pquery('select * from jo_privileges where user_id = ?', array($current_user_id));
			$user_privilege = $adb->query_result($result,0,'user_privilege');
			$user_priv = json_decode(html_entity_decode($user_privilege));
			$user_priv->user_info->currency_id = $record;
			$user_priv->user_info->currency_name = $currency_name;
			$user_priv->user_info->currency_code = $currency_code;
			$user_priv->user_info->currency_symbol = $currency_symbol;
			$upd_user_priv = html_entity_decode(json_encode($user_priv));
	    	$adb->pquery('update jo_privileges set user_privilege = ? where user_id = ?', array($upd_user_priv,$current_user_id));
            	$response->setResult(array('success'=>'true','message'=>$currency_name.' is set as default currency','status'=>200));
	    }
        }catch (Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
    public function validateRequest(Head_Request $request) {
        $request->validateWriteAccess();
    }
}
