{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
*************************************************************************************}

{strip}
<div class="centerbody">

	    <div class="col-lg-12">
	    <div class="hidden-sm hidden-xs recordImage bg_{$MODULE}">

	    

                {assign var=IMAGE_DETAILS value=$RECORD->getImageDetails()}

		{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
		    {if !empty($IMAGE_INFO.path)}
		    	{if $IMAGE_DETAILS|@count eq 1}
			    <img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="100%" height="100%" align="left"><br>
		    	{else if $IMAGE_DETAILS|@count eq 2}
			    <span><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="50%" height="100%" align="left"></span>
		    	{else if $IMAGE_DETAILS|@count eq 3}
			    <span ><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" {if $ITER eq 0 or $ITER eq 1}width="50%" height = "50%"{/if}{if $ITER eq 2}width="100%" height="50%"{/if} align="left"></span>
		    	{else if $IMAGE_DETAILS|@count eq 4 or $IMAGE_DETAILS|@count gt 4}
			    {if $ITER gt 3}{break}{/if}
			    <span><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}"width="50%" height="50%" align="left"></span>
			{/if}
		    {else}
		    	<img src="{vimage_path('summary_Products.png')}" class="summaryImg"/>
		    {/if}
		     {break}
		{/foreach}
		{if empty($IMAGE_DETAILS)}
		    <div class="name"><span><strong> <i class="joicon-products"></i> </strong></span></div>
	    	{/if}
	    </div>
</div></div>
<div class="col-lg-12 pull-left company_name"><h4 class="mt-0 mb-1 mt40 text-center">


   {foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
			    {assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
			    {if $FIELD_MODEL->getPermissions()}
				<span class="{$NAME_FIELD}">{$RECORD->get($NAME_FIELD)}</span>&nbsp;
			    {/if}
			{/foreach}

</div>

{/strip}
