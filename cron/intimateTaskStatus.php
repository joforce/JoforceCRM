<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
ini_set("include_path", "../");

require('send_mail.php');
require_once('config/config.php');
require_once('includes/utils/utils.php');

// Email Setup
global $adb;
$emailresult = $adb->pquery("SELECT email1 from jo_users", array());
$emailid = $adb->fetch_array($emailresult);
$emailaddress = $emailid[0];
$mailserveresult = $adb->pquery("SELECT server,server_username,server_password,smtp_auth FROM jo_systems where server_type = ?", array('email'));
$mailrow = $adb->fetch_array($mailserveresult);
$mailserver = $mailrow[0];
$mailuname = $mailrow[1];
$mailpwd = $mailrow[2];
$smtp_auth = $mailrow[3];
// End Email Setup


//query the jo_notificationscheduler jo_table and get data for those notifications which are active
$sql = "select active from jo_notificationscheduler where schedulednotificationid=1";
$result = $adb->pquery($sql, array());

$activevalue = $adb->fetch_array($result);

if($activevalue[0] == 1)
{
	//Delayed Tasks Notification

	//get all those activities where the status is not completed even after the passing of 24 hours
	$today = date("Ymd"); 
	$result = $adb->pquery("select jo_activity.status,jo_activity.activityid,subject,(jo_activity.date_start +1),jo_crmentity.smownerid from jo_activity inner join jo_crmentity on jo_crmentity.crmid=jo_activity.activityid where jo_crmentity.deleted=0 and jo_activity.status <> 'Completed' and activitytype='Task' and ".$today." > (jo_activity.date_start+1)", array());

	while ($myrow = $adb->fetch_array($result))
	{
		$status=$myrow[0];
		$subject = (strlen($myrow[2]) > 30)?substr($myrow[2],0,27).'...':$myrow[2];
		$user_id = $myrow[4];
		if($user_id != '')
		{
			$user_res = $adb->pquery('select user_name from jo_users where id=?',array($user_id));
			$assigned_user = $adb->query_result($user_res,0,'user_name');
		}
		$mail_body = getTranslatedString('Dear_Admin_tasks_not_been_completed')." ".getTranslatedString('LBL_SUBJECT').": ".$subject."<br> ".getTranslatedString('LBL_ASSIGNED_TO').": ".$assigned_user."<br><br>".getTranslatedString('Task_sign');
	 	sendmail($emailaddress,$emailaddress,getTranslatedString('Task_Not_completed').': '.$subject,$mail_body,$mailserver,$mailuname,$mailpwd,"",$smtp_auth);
	}
}

//Big Deal Alert
$sql = "select active from jo_notificationscheduler where schedulednotificationid=2";
$result = $adb->pquery($sql, array());

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
	$result = $adb->pquery("SELECT sales_stage,amount,potentialid,potentialname FROM jo_potential inner join jo_crmentity on jo_crmentity.crmid=jo_potential.potentialid where jo_crmentity.deleted=0 and sales_stage='Closed Won' and amount > 10000",array());
	while ($myrow = $adb->fetch_array($result))
	{
		$pot_id = $myrow['potentialid'];
		$pot_name = $myrow['potentialname'];
		$body_content = getTranslatedString('Dear_Team').getTranslatedString('Dear_Team_Time_to_Party')."<br><br>".getTranslatedString('Potential_Id')." ".$pot_id;
		$body_content .= getTranslatedString('Potential_Name')." ".$pot_name."<br><br>";
		sendmail($emailaddress,$emailaddress,getTranslatedString('Big_Deal_Closed_Successfully'),$body_content,$mailserver,$mailuname,$mailpwd,"",$smtp_auth);
	}
}
//Pending tickets
$sql = "select active from jo_notificationscheduler where schedulednotificationid=3";
$result = $adb->pquery($sql, array());

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
	$result = $adb->pquery("SELECT jo_troubletickets.status,ticketid FROM jo_troubletickets INNER JOIN jo_crmentity ON jo_crmentity.crmid=jo_troubletickets.ticketid WHERE jo_crmentity.deleted='0' AND jo_troubletickets.status <> 'Completed' AND jo_troubletickets.status <> 'Closed'", array());

	while ($myrow = $adb->fetch_array($result))
	{
		$ticketid = $myrow['ticketid'];
		sendmail($emailaddress,$emailaddress,getTranslatedString('Pending_Ticket_notification'),getTranslatedString('Kind_Attention').$ticketid .getTranslatedString('Thank_You_HelpDesk'),$mailserver,$mailuname,$mailpwd,"",$smtp_auth);
	}
}

//Too many tickets related to a particular jo_account/company causing concern
$sql = "select active from jo_notificationscheduler where schedulednotificationid=4";
$result = $adb->pquery($sql, array());

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
	$result = $adb->pquery("SELECT count(*) as count FROM jo_troubletickets INNER JOIN jo_crmentity ON jo_crmentity.crmid=jo_troubletickets.ticketid WHERE jo_crmentity.deleted='0' AND jo_troubletickets.status <> 'Completed' AND jo_troubletickets.status <> 'Closed'", array());
$count = $adb->query_result($result,0,'count');
//changes made to get too many tickets notification only when tickets count is greater than or equal to 5
	if($count >= 5)
	{
		sendmail($emailaddress,$emailaddress,getTranslatedString('Too_many_pending_tickets'),getTranslatedString('Dear_Admin_too_ many_tickets_pending'),$mailserver,$mailuname,$mailpwd,"",$smtp_auth);
	}
}

//Support Starting
$sql = "select active from jo_notificationscheduler where schedulednotificationid=5";
$result = $adb->pquery($sql, array());

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
	$result = $adb->pquery("SELECT jo_products.productname FROM jo_products inner join jo_crmentity on jo_products.productid = jo_crmentity.crmid where jo_crmentity.deleted=0 and start_date like ?", array(date('Y-m-d'). "%"));
	while ($myrow = $adb->fetch_array($result))
	{
		$productname=$myrow[0];
		sendmail($emailaddress,$emailaddress,getTranslatedString('Support_starting'),getTranslatedString('Hello_Support').$productname ."\n ".getTranslatedString('Congratulations'),$mailserver,$mailuname,$mailpwd,"",$smtp_auth);
	}
}

//Support ending
$sql = "select active from jo_notificationscheduler where schedulednotificationid=6";
$result = $adb->pquery($sql, array());

$activevalue = $adb->fetch_array($result);
if($activevalue[0] == 1)
{
	$result = $adb->pquery("SELECT jo_products.productname from jo_products inner join jo_crmentity on jo_products.productid = jo_crmentity.crmid where jo_crmentity.deleted=0 and expiry_date like ?", array(date('Y-m-d') ."%"));
	while ($myrow = $adb->fetch_array($result))
	{
		$productname=$myrow[0];
		sendmail($emailaddress,$emailaddress,getTranslatedString('Support_Ending_Subject'),getTranslatedString('Support_Ending_Content').$productname.getTranslatedString('kindly_renew'),$mailserver,$mailuname,$mailpwd,"",$smtp_auth);
	}
}

?>