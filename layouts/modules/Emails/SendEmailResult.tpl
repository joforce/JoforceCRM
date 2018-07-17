{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Emails/views/MassSaveAjax.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}

<span>
	{if $SUCCESS}
		<div class="mailSentSuccessfully" data-relatedload="{$RELATED_LOAD}">{vtranslate('LBL_MAIL_SENT_SUCCESSFULLY')}</div>
		{if $FLAG}
			<input type="hidden" id="flag" value="{$FLAG}">
		{/if}
	{else}
		<div class="failedToSend" data-relatedload="false">
			{vtranslate('LBL_FAILED_TO_SEND')}
			<br>
			{$MESSAGE}
		</div>
	{/if}
</span>
