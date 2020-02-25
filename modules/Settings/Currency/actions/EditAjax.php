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
