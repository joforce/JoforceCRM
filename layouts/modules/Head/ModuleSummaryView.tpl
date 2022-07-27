{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Head/views/Detail.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}

{strip}
        {assign var=summary_view_modules value=array('Contacts', 'Accounts', 'Leads', 'Invoice', 'SalesOrder', 'PurchaseOrder', 'Quotes', 'HelpDesk','Potentials')}
        <div class="recordDetails">
	    {if in_array($MODULE_NAME, $summary_view_modules)}
                {include file='SummaryViewContents.tpl'|@vtemplate_path:$MODULE_NAME}
            {else}
	    	{include file='DetailViewBlockView.tpl'|@vtemplate_path:$MODULE_NAME RECORD_STRUCTURE=$SUMMARY_RECORD_STRUCTURE MODULE_NAME=$MODULE_NAME}
	    {/if}
	</div>
{/strip}
