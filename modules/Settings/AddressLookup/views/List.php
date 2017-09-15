<?php
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
class Settings_AddressLookup_List_View extends Settings_Head_Index_View {

	public function process(Head_Request $request){


		global $adb, $site_URL;


		$sourceModule = "";
		
			if(!isset($_GET['sourceModule']))
                header("location:{$site_URL}AddressLookup/Settings/List/Campaigns");

			if($_GET['success']){
			echo "<div style='height:20px; font-size:12px; margin-bottom:6px; padding:5px; height: 15px; length:100%; text-align: center; margin-left: 20%; margin-top:40px;margin-right:100px; display: inline-block;' class='pull-right fa fa-times-circle fa-2x alert alert-success notificationArea' id='notificationArea' onclick='clearNotificationArea();'> <span> Settings Saved Successfully </span><script>function clearNotificationArea(){ $('#notificationArea').hide(); }</script> </div>";
		}
			if($_GET['error']){
			echo "<div style='height:20px; font-size:12px; margin-bottom:6px;padding:5px; height: 15px; length:100%; text-align: center; margin-left: 20%; margin-top:40px; display: inline-block; margin-right:100px;' class='pull-right fa fa-times-circle fa-2x alert alert-danger notificationArea' id='notificationArea' onclick='clearNotificationArea();'> <span> Unfortunately Unsaved Changes </span><script>function clearNotificationArea(){ $('#notificationArea').hide(); }</script> </div>";
		}

			if($_GET['check']){
			echo "<div style='height:20px; font-size:12px; margin-bottom:6px;padding:5px; height: 15px; length:100%; text-align: center; margin-left: 20%; margin-top:40px; display: inline-block; margin-right:100px;' class='pull-right fa fa-times-circle fa-2x alert alert-danger notificationArea' id='notificationArea' onclick='clearNotificationArea();'> <span> Please select the module</span><script>function clearNotificationArea(){ $('#notificationArea').hide(); }</script> </div>";
		}	


	
			$exceptModules = 'Calendar';
			$moduleNames = array();
			$moduleFieldsList = array();
			$addTranslated = array();

			$sourceModule = $request->get('sourceModule');
			$modulename = $sourceModule;
			$isenabledcheck = $adb->pquery("SELECT isenabled FROM jo_vtaddressmapping WHERE modulename = ?",array($modulename));
			$getenabledcheck = $adb->fetch_array($isenabledcheck);
			$enabledCheck = $getenabledcheck['isenabled'];

			
			$getModulenames = $adb->pquery("SELECT name, tablabel FROM jo_tab WHERE isentitytype='1' AND name!=? AND presence='0'",array($exceptModules));
			$qualifiedModule = $request->getModule(false);
			while($fetchModuleNames = $adb->fetch_array($getModulenames)){
					$translatedModuleName = vtranslate($fetchModuleNames['tablabel']);					
					array_push($addTranslated,$translatedModuleName,$fetchModuleNames['name']);
					array_push($moduleNames, $addTranslated);					
					$addTranslated = array();
			}
			sort($moduleNames);	
			$qualifiedModule = $request->getModule(false);

			
			$runQueryTabid = $adb->pquery("SELECT tabid FROM jo_tab WHERE name=?",array($modulename));
			$fetchQueryTabid = $adb->fetch_array($runQueryTabid);
			$getTabid = $fetchQueryTabid['tabid'];

			$getModuleFields = $adb->pquery("SELECT fieldid, fieldlabel FROM jo_field WHERE tabid = ?",array($getTabid));
			while($fetchModuleFields = $adb->fetch_array($getModuleFields)){
				array_push($moduleFieldsList, $fetchModuleFields);
			}
			
			$selectedField = $adb->pquery("SELECT * FROM jo_vtaddressmapping WHERE modulename = ?",array($modulename));
			$fetchSelectedField = $adb->fetch_array($selectedField);
			$fieldSetCount = $fetchSelectedField['fieldset'];

			$selectedStreet = $fetchSelectedField['street'];
			$selectedStreet = $this->decodeAndUnserialize($selectedStreet);			
			
			$selectedArea = $fetchSelectedField['area'];		
			$selectedArea = $this->decodeAndUnserialize($selectedArea);			
	
			$selectedLocality = $fetchSelectedField['locality'];			
			$selectedLocality = $this->decodeAndUnserialize($selectedLocality);			

			$selectedCity = $fetchSelectedField['city'];			
			$selectedCity = $this->decodeAndUnserialize($selectedCity);			

			$selectedState = $fetchSelectedField['state'];				  
			$selectedState = $this->decodeAndUnserialize($selectedState);			

			$selectedCountry = $fetchSelectedField['country'];			
			$selectedCountry = $this->decodeAndUnserialize($selectedCountry);			

			$selectedPostalCode = $fetchSelectedField['postalcode'];
			$selectedPostalCode = $this->decodeAndUnserialize($selectedPostalCode);			
			
			global $root_directory;
                        $filename=$root_directory.'/modules/Settings/AddressLookup/APIkey.php';
                        if (file_exists($filename)) { 
                                include_once($root_directory.'/modules/Settings/AddressLookup/APIkey.php');
                                $apikey=$APIkey;
                       	}
                        else { 
	                       	$apikey='';
                    	}
		
     
			$viewer = $this->getViewer($request);
			$viewer->assign('SELECTED_ROUTE',$selectedRoute);		
			$viewer->assign('SELECTED_STREET',$selectedStreet);			
			$viewer->assign('SELECTED_AREA',$selectedArea);			
			$viewer->assign('SELECTED_LOCALITY',$selectedLocality);			
			$viewer->assign('SELECTED_CITY',$selectedCity);			
			$viewer->assign('SELECTED_STATE',$selectedState);			
			$viewer->assign('SELECTED_COUNTRY',$selectedCountry);			
			$viewer->assign('SELECTED_POSTALCODE',$selectedPostalCode);
			$viewer->assign('APIkey',$apikey);
			if($modulename != ""){
			$countOfRows = count($selectedStreet);			
			$countOfRows--;
			$viewer->assign('COUNT_OF_ROWS',$countOfRows);
			}
			$viewer->assign('ENABLE_CHECK', $enabledCheck);
			$viewer->assign('SELECTED_MODULE_NAME', $sourceModule);
			$viewer->assign('ENABLED_MODULES', $moduleNames);
			$viewer->assign('SELECTED_MODULE_FIELDS_LIST',$moduleFieldsList);
            $viewer->assign('SITEURL',$site_URL);
			
			$viewer->view('Index.tpl',$qualifiedModule);

	}
	// Injecting custom javascript resources
        public function getHeaderScripts(Head_Request $request) {
                $headerScriptInstances = parent::getHeaderScripts($request);
                $moduleName = $request->getModule();
                $jsFileNames = array(
			"layouts.v7.modules.Settings.$moduleName.jsresources.AddressLookup.js",
                );
                $jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
                $headerScriptInstances = array_merge($headerScriptInstances, $jsScriptInstances);
                return $headerScriptInstances;
        }
	#Decode And Unserialize
	public function decodeAndUnserialize($getValues){
		 $decodedValues = base64_decode($getValues);
                 $unserializedValues = unserialize($decodedValues);
                 return $unserializedValues;
		}

}

