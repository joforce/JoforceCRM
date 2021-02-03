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
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="record-header clearfix col-lg-12 col-md-12 col-sm-12">
	{if !$MODULE}
	    {assign var=MODULE value=$MODULE_NAME}
	{/if}
	<div class="col-lg-3 col-md-3 col-sm-3 white-background m0">
	    <div class="hidden-sm hidden-xs recordImage bg_{$MODULE} app-{$SELECTED_MENU_CATEGORY}" {if $IMAGE_DETAILS|@count gt 1}style = "display:block"{/if}>
                {assign var=IMAGE_DETAILS value=$RECORD->getImageDetails()}
		{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
		    {if !empty($IMAGE_INFO.path)}
		    	{if $IMAGE_DETAILS|@count eq 1}
			    <img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="100%" height="100%" align="left"><br>
		    	{else if $IMAGE_DETAILS|@count eq 2}
			    <span><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="50%" height="100%" align="left"></span>
		    	{else if $IMAGE_DETAILS|@count eq 3}
			    <span><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" {if $ITER eq 0 or $ITER eq 1}width="50%" height = "50%"{/if}{if $ITER eq 2}width="100%" height="50%"{/if} align="left"></span>
		    	{else if $IMAGE_DETAILS|@count eq 4 or $IMAGE_DETAILS|@count gt 4}
			    {if $ITER gt 3}{break}{/if}
			    <span><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}"width="50%" height="50%" align="left"></span>
			{/if}
		    {else}
		    	<img src="{vimage_path('summary_Products.png')}" class="summaryImg"/>
		    {/if}
		{/foreach}
		{if empty($IMAGE_DETAILS)}
		    <div class="name"><span><strong> <i class="joicon-products"></i> </strong></span></div>
	    	{/if}
	    </div>

	    <div class="recordBasicInfo">
		<div class="info-row">
		    <h4>
		    	<span class="recordLabel pushDown" title="{$RECORD->getName()}">
			{foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
			    {assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
			    {if $FIELD_MODEL->getPermissions()}
				<span class="{$NAME_FIELD}">{$RECORD->get($NAME_FIELD)}</span>&nbsp;
			    {/if}
			{/foreach}
		    	</span>
		    </h4>
	    	</div>
	    </div>

	</div>
	<div class="col-lg-2 col-md-2 col-sm-2 white-background m0">
	    {include file="DetailViewHeaderFieldsView.tpl"|vtemplate_path:$MODULE}
	</div>

	<div class="col-lg-6 col-md-6 col-sm-6 white-background m0">
            {include file="CumulativeSummary.tpl"|vtemplate_path:$MODULE}
        </div>
    </div>
</div>
{/strip}
