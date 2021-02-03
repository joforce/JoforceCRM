<?php

/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * ********************************************************************************** */

class Settings_Head_UserDetailsSave_Action extends Settings_Head_Basic_Action {

	public function save(Head_Request $request) {

		global $current_user,$adb;
	    $succes = true;   
	    $enablepercentagecompletion = $request->get('enablepercentagecompletion');
	    if(empty($enablepercentagecompletion)) $enablepercentagecompletion =0;
	   
		try { 
		    $result = $adb->pquery('select completedpercentage from jo_userscf where usersid= ? ', array($current_user->id));
		    if ($adb->num_rows($result) > 0) { 
		    	$query = 'UPDATE `jo_userscf` SET `enablepercentagecompletion`=? WHERE `usersid`=?';
			    $param =array($enablepercentagecompletion,$current_user->id );
		    }else{
		    	$query = 'INSERT INTO `jo_userscf`(`usersid`, `enablepercentagecompletion`) VALUES (?,?)';
			    $param =array($current_user->id,$enablepercentagecompletion);
		    }  
			$result = $adb->pquery($query, $param);
		} catch(Exception $e) {			
			$succes = false;
			$message = $e->getMessage();
		}
		return $succes;	
	}  

	public function completedpercentage($currentvalue,$type){
		global $current_user,$adb; 
		$result = $adb->pquery('select completedpercentage from jo_userscf where usersid= ? ', array($current_user->id));
		$exists =false;
		if ($adb->num_rows($result) > 0) { 
			$exists =true;
			$completedpercentage = $adb->query_result($result, 0, 'completedpercentage');
			$jsonData = stripslashes(html_entity_decode($completedpercentage));
			$decodeval=json_decode($jsonData,true);
			unset($decodeval['total']);
		} 
		$updatepercentage=array();
		$updatepercentage=$decodeval;
	
		if (!array_key_exists($type,$updatepercentage)){
			$updatepercentage[$type] = $currentvalue;	
		}elseif(array_key_exists($type,$updatepercentage)){
			$updatepercentage[$type] = $currentvalue;
		}

		$updatepercentage['total'] =array_sum($updatepercentage); 
        $encodedval =json_encode($updatepercentage);  
        if($exists ==1){
        	$query = 'UPDATE `jo_userscf` SET `completedpercentage`=? WHERE `usersid`=?';
		    $param =array($encodedval,$current_user->id );
        }else{
        	$query = 'INSERT INTO `jo_userscf`(`usersid`, `completedpercentage`) VALUES (?,?)';
		    $param =array($current_user->id,$encodedval);
        }  
		$updated = $adb->pquery($query, $param); 
		return $updatepercentage;
	}
}