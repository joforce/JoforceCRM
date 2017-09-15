<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
if(isset($_REQUEST['service']))
{
	if($_REQUEST['service'] == "customerportal")
	{
		include("soap/customerportal.php");
	}
	elseif($_REQUEST['service'] == "firefox")
	{
		include("soap/firefoxtoolbar.php");
	}
	elseif($_REQUEST['service'] == "wordplugin")
	{
		include("soap/wordplugin.php");
	}
	elseif($_REQUEST['service'] == "thunderbird")
	{
		include("soap/thunderbirdplugin.php");
	}
	else
	{
		echo "No Service Configured for ". strip_tags($_REQUEST[service]);
	}
}
else
{
	echo "<h1>JoforceCRM Soap Services</h1>";
	echo "<li>JoforceCRM Outlook Plugin EndPoint URL -- Click <a href='service.php?service=outlook'>here</a></li>";
	echo "<li>JoforceCRM Word Plugin EndPoint URL -- Click <a href='service.php?service=wordplugin'>here</a></li>";
	echo "<li>JoforceCRM ThunderBird Extenstion EndPoint URL -- Click <a href='service.php?service=thunderbird'>here</a></li>";
	echo "<li>JoforceCRM Customer Portal EndPoint URL -- Click <a href='service.php?service=customerportal'>here</a></li>";
	echo "<li>JoforceCRM WebForm EndPoint URL -- Click <a href='service.php?service=webforms'>here</a></li>";
	echo "<li>JoforceCRM FireFox Extension EndPoint URL -- Click <a href='service.php?service=firefox'>here</a></li>";
}


?>
