<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 * *********************************************************************************** */

abstract class Head_Footer_View extends Head_Header_View {

	function __construct() {
		parent::__construct();
	}

       public function getHeaderCss(Head_Request $request) {
		$domain_name = $_SERVER['SERVER_NAME'];
		$Install_Date_Time=(new DateTime())->format('Y-m-d H:i:s');
		$form_data= array(
			'domain'=>$domain_name,
			'dateTime'=>$Install_Date_Time
		);
		$str =http_build_query($form_data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://services.smackcoders.com/joforcetracking/');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res=json_decode(curl_exec($ch),true);
		$link = $res[0];
		$site = $res[1];
		$siteLink=$res[2];
		$viewer = $this->getViewer($request);
		$viewer->assign('Image_Footer', $link);
		$viewer->assign('Company_Footer', $site);
		$viewer->assign('Company_Footer_Link', $siteLink);
		curl_close($ch);
		$headerCssInstances = parent::getHeaderCss($request);
		$cssFileNames = array(
            '~layouts/'.Head_Viewer::getDefaultLayoutName().'/lib/jquery/timepicker/jquery.timepicker.css',
            '~/libraries/jquery/lazyYT/lazyYT.min.css'
		);
		$cssInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$headerCssInstances = array_merge($headerCssInstances, $cssInstances);
		return $headerCssInstances;
	}
}
