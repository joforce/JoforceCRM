<?php
////////////////////////////////////////////////////
// PHPMailer - PHP email class
//
// Class for sending email using either
// sendmail, PHP mail(), or SMTP.  Methods are
// based upon the standard AspEmail(tm) classes.
//
// Copyright (C) 2001 - 2003  Brent R. Matzelle
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

/**
 * PHPMailer - PHP email transport class
 * @package PHPMailer
 * @author Brent R. Matzelle
 * @copyright 2001 - 2003 Brent R. Matzelle
 */


require_once('includes/utils/utils.php');
require("modules/Emails/mail.php");
require_once('includes/logging.php');
require("config/config.php");


global $adb;
global $log;
global $HELPDESK_SUPPORT_EMAIL_ID,$HELPDESK_SUPPORT_NAME;
$log =& LoggerManager::getLogger('SendSupportNotification');
$log->debug(" invoked SendSupportNotification ");

// retrieve the translated strings.
$app_strings = return_application_language($current_language);


//To send email notification before a week
$query="select jo_contactdetails.contactid,jo_contactdetails.email,jo_contactdetails.firstname,jo_contactdetails.lastname,contactid  from jo_customerdetails inner join jo_crmentity on jo_crmentity.crmid=jo_customerdetails.customerid inner join jo_contactdetails on jo_contactdetails.contactid=jo_customerdetails.customerid  where jo_crmentity.deleted=0 and support_end_date=DATE_ADD(now(), INTERVAL 1 WEEK)";
$result = $adb->pquery($query, array());


if($adb->num_rows($result) >= 1)
{
	while($result_set = $adb->fetch_array($result))
	{	
	
		$content=getcontent_week($result_set["contactid"]);
		$body=$content["body"];
		$body = str_replace('$logo$','<img src="cid:logo" />',$body);
		$subject=$content["subject"];

		$status=send_mail("Support",$result_set["email"],$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$body,'',$HELPDESK_SUPPORT_EMAIL_ID
		);

	}

}
//comment / uncomment this line if you want to hide / show the sent mail status
//showstatus($status);
$log->debug(" Send Support Notification Before a week - Status: ".$status);

//To send email notification before a month
$query="select jo_contactdetails.contactid,jo_contactdetails.email,jo_contactdetails.firstname,jo_contactdetails.lastname,contactid  from jo_customerdetails inner join jo_crmentity on jo_crmentity.crmid=jo_customerdetails.customerid inner join jo_contactdetails on jo_contactdetails.contactid=jo_customerdetails.customerid  where jo_crmentity.deleted=0 and support_end_date=DATE_ADD(now(), INTERVAL 1 MONTH)";
$result = $adb->pquery($query, array());


if($adb->num_rows($result) >= 1)
{
	while($result_set = $adb->fetch_array($result))
	{
		$content=getcontent_month($result_set["contactid"]);
		$body=$content["body"];
		$body = str_replace('$logo$','<img src="cid:logo" />',$body);
		$subject=$content["subject"];

		$status=send_mail("Support",$result_set["email"],$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$body,'',$HELPDESK_SUPPORT_EMAIL_ID);
	}

}

//comment / uncomment this line if you want to hide / show the sent mail status
//showstatus($status);
$log->debug(" Send Support Notification Befoe a Month - Status: ".$status);

//used to dispaly the sent mail status
function showstatus($status)
{
	
	if($status == 1)
		echo "Mails sent successfully";
	else if($status == "")
		echo "No contacts matched";
	else
		echo "Error while sending mails: ".$status;	
}



//function used to get the header and body content of the mail to be sent.
function getcontent_month($id)
{
	global $adb;
	$query='select jo_emailtemplates.subject,jo_emailtemplates.body from jo_notificationscheduler inner join jo_emailtemplates on jo_emailtemplates.templateid=jo_notificationscheduler.notificationbody where schedulednotificationid=7';
	$result = $adb->pquery($query, array());
	$body=$adb->query_result($result,0,'body');
	$body=getMergedDescription($body,$id,"Contacts");
	$body=getMergedDescription($body,$id,"Users");
	$res_array["subject"]=$adb->query_result($result,0,'subject');
	$res_array["body"]=$body;
	return $res_array;

}

//function used to get the header and body content of the mail to be sent.
function getcontent_week($id)
{
	global $adb;
	$query='select jo_emailtemplates.subject,jo_emailtemplates.body from jo_notificationscheduler inner join jo_emailtemplates on jo_emailtemplates.templateid=jo_notificationscheduler.notificationbody where schedulednotificationid=6';
	$result = $adb->pquery($query, array());
	$body=$adb->query_result($result,0,'body');
	$body=getMergedDescription($body,$id,"Contacts");
	$body=getMergedDescription($body,$id,"Users");
	$res_array["subject"]=$adb->query_result($result,0,'subject');
	$res_array["body"]=$body;
	return $res_array;

}



?>
