<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *
 ********************************************************************************/
include_once 'modules/Quotes/QuotePDFController.php';
$controller = new Head_QuotePDFController($currentModule);
$controller->loadRecord(modlib_purify($_REQUEST['record']));
$quote_no = getModuleSequenceNumber($currentModule,modlib_purify($_REQUEST['record']));
if(isset($_REQUEST['savemode']) && $_REQUEST['savemode'] == 'file') {
	$quote_id = modlib_purify($_REQUEST['record']);
	$filepath='cache/product/'.$quote_id.'_Quotes_'.$quote_no.'.pdf';
	//added file name to make it work in IE, also forces the download giving the user the option to save
	$controller->Output($filepath,'F');
} else {
	//added file name to make it work in IE, also forces the download giving the user the option to save
	$controller->Output('Quotes_'.$quote_no.'.pdf', 'D');
	exit();
}

?>
