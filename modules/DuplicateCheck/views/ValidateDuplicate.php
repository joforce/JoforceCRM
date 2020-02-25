<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */

class DuplicateCheck_ValidateDuplicate_View extends Head_Index_View
{
	public function process(Head_Request $request)
	{
		global $adb;		
		$arrayValues = [];
		$arrayQuery = "";
		$arrayQuery1="";
		$recordDetails = [];
		$fieldlabelQuery = "";
		$commonQuery = "";
		$countoffieldNames=array();
		$crossfieldname=array();
		$crosstablename=array();
		$crosscheckDetails=array();
		$fetchcrosscount=0;
	


		$record_view = $request->get('record_view');
		$record_id = $request->get('record_id');

		extract($_GET);
		$modulename = $_GET['moduleName'];
		$fieldName = $_GET['fieldName'];
		$fieldValues = $_GET['fieldValues'];
		require_once("modules/{$modulename}/{$modulename}.php");
		$focus = new $modulename();
		$tab_info = $focus->tab_name_index;
                unset($tab_info['jo_crmentity']);
                $getFieldQuery = $adb->pquery("SELECT entityidfield,tablename,fieldname,tabid FROM jo_entityname WHERE modulename='$modulename'");
                $fetchResult = $adb->fetch_array($getFieldQuery);
                $masterTableName = $fetchResult['tablename'];
                $masterEntityidfield = $fetchResult['entityidfield'];
                $columnname = $fetchResult['fieldname'];
		$tabid=$fetchResult['tabid'];
		$fields = explode(",",$columnname);
                $countoffields = count($fields);
                array_push($countoffieldNames,$countoffields);
                array_push($countoffieldNames,$fields);
	
                unset($tab_info[$masterTableName]);
		
                $arrayQuery .= "SELECT $masterTableName.$columnname,";
                $fieldlabelQuery .= "SELECT DISTINCT(fieldlabel) ";
                $commonQuery .= " FROM $masterTableName INNER JOIN jo_crmentity ON jo_crmentity.crmid = $masterTableName.$masterEntityidfield";
		$fieldlabelQuery .=$commonQuery;
                $columnnameQuery = $adb->pquery("SELECT columnname,tablename FROM jo_field WHERE fieldname=? AND tabid=?",array($fieldName,$tabid));

                $columnnameResult=$adb->query_result($columnnameQuery,0,'columnname');
                $tablenames=$adb->query_result($columnnameQuery,0,'tablename');
                if($tablenames != $masterTableName && $tablenames != 'jo_crmentity' )
                	$subtablenames = $tablenames;
                $arrayQuery .= " $tablenames.$columnnameResult , ";
                        
               
       		$arrayQuery .= " jo_crmentity.crmid as recordid,$masterTableName.$masterEntityidfield";
		$arrayQuery .= $commonQuery;
                        foreach ($tab_info as $key =>$value){
                              if ($key  == $subtablenames)
                                   $arrayQuery .= " INNER JOIN $key  ON $key.$value = jo_crmentity.crmid";
                              }

		$columnnamequery = $adb->pquery("SELECT columnname,tablename FROM jo_field WHERE fieldname=? AND tabid=? ",array($fieldName,$tabid));
		$columnnameresult=$adb->query_result($columnnamequery,0,'columnname');
		$columntablename=$adb->query_result($columnnamequery,0,'tablename');
                $fieldlabelQuery .= " INNER JOIN jo_field ON jo_field.columnname = '$columnnameresult'";
                $conditionQuery =  " WHERE $columntablename.$columnnameresult = '$fieldValues' AND jo_crmentity.deleted = '0'";
		if($record_id)
                        $conditionQuery .= " and jo_crmentity.crmid != {$record_id}";

                $arrayQuery .= $conditionQuery;
		
                $runFieldLabelQuery = $adb->pquery($fieldlabelQuery);
                $fetchFieldLabelQuery = $adb->fetch_array($runFieldLabelQuery);

                $runQuery = $adb->pquery($arrayQuery);

                $count = $adb->num_rows($runQuery);
                array_push($recordDetails,$count);
                array_push($recordDetails, $fetchFieldLabelQuery);
		array_push($recordDetails,$countoffieldNames);

                while ($fetchResultContent = $adb->fetch_array($runQuery)) {
                        array_push($recordDetails, $fetchResultContent);
                }
		
		$uitypeQuery=$adb->pquery("SELECT uitype from jo_field where fieldname=?",array($fieldName));
		$uitype=$adb->query_result($uitypeQuery,0,'uitype');
		$fetchcrossQuery=array();
		array_push($crosscheckDetails,$uitype);
		
		if(($uitype == 11) || ($uitype == 13))	{
		$crosscheckQuery = $adb->pquery("SELECT crosscheck FROM jo_duplicatechecksettings WHERE modulename='$modulename' AND isenabled='1'");
                $crosscheckvalue = $adb->query_result($crosscheckQuery,0,'crosscheck');
		if($crosscheckvalue == 1){
		$crossfieldnameQuery=$adb->pquery("SELECT columnname,tablename,tabid from jo_field where uitype='$uitype' and tabid in(6,7,4)");
		$crosscount = $adb->num_rows($crossfieldnameQuery);
		array_push($crosscheckDetails,$crosscount);
		for($i=0;$i<$crosscount;$i++){
		$crossfieldname=$adb->query_result($crossfieldnameQuery,$i,'columnname');
		$crosstablename=$adb->query_result($crossfieldnameQuery,$i,'tablename');
		$crosstabid=$adb->query_result($crossfieldnameQuery,$i,'tabid');
		$crossmodulenameQuery = $adb->pquery("SELECT name from jo_tab where tabid=$crosstabid");
		$crossmodulename = $adb->query_result($crossmodulenameQuery,0,'name');
		if($crossmodulename != $modulename) {
		$crossnamevalQuery = $adb->pquery("SELECT * FROM jo_entityname where modulename='$crossmodulename'");
		$crossname=$adb->query_result($crossnamevalQuery,0,'fieldname');
		$crossnametable=$adb->query_result($crossnamevalQuery,0,'tablename');
		$crossentityid=$adb->query_result($crossnamevalQuery,0,'entityidfield');
		$crosscheckvalQuery =" Select $crossnametable.$crossname,jo_crmentity.setype as modulename,jo_crmentity.crmid as recordid from $crossnametable inner join jo_crmentity on jo_crmentity.crmid = $crossnametable.$crossentityid ";
		if(($crossmodulename == 'Leads') && ($crosstablename != $crossnametable))
			$crosscheckvalQuery .=" inner join $crosstablename on $crosstablename.leadaddressid = jo_crmentity.crmid "
;
		if(($crossmodulename == 'Contacts') && ($crosstablename != $crossnametable))
                        $crosscheckvalQuery .=" inner join $crosstablename on $crosstablename.contactsubscriptionid = jo_crmentity.crmid ";
		$crosscheckvalQuery .=" where $crosstablename.$crossfieldname = '$fieldValues' AND jo_crmentity.deleted = '0' ";
		if($record_id)
                        $crosscheckvalQuery .= " AND jo_crmentity.crmid != {$record_id}";	
		
		
		
		$crosscheckcontentQuery = $adb->pquery($crosscheckvalQuery);
		$fetchcrosscount += $adb->num_rows($crosscheckcontentQuery);
		
		while($fetchcrosscheckvalQuery = $adb->fetch_array($crosscheckcontentQuery)) { 
			 array_push($fetchcrossQuery,$fetchcrosscheckvalQuery);
		}
      
		}
		}
	
		}
		}
		array_push($crosscheckDetails,$fetchcrosscount);	
		array_push($crosscheckDetails, $fetchcrossQuery);
                $response = new Head_Response();
                $response->setEmitType(Head_Response::$EMIT_JSON);
	  	$response->setResult(array($recordDetails,$crosscheckDetails));
		
                $response->emit();
                die;
        }
}

