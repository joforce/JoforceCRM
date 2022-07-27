{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************}
{strip}
<div>
	{foreach item=KEYMETRIC from=$KEYMETRICS}
	<div style="padding-bottom:6px;">
		<span class="pull-right">{$KEYMETRIC.count}</span>
		<a href="{$SITEURL}{$KEYMETRIC.module}/List/{$KEYMETRIC.id}">{$KEYMETRIC.name}</a>
	</div>	
	{/foreach}
</div>
{/strip}
