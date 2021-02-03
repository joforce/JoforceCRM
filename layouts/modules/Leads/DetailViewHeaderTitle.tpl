{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<div class="col-lg-12 col-md-12 col-sm-12">
    <div class="record-header clearfix col-lg-12 col-md-12 col-sm-12">
	<div class="col-lg-2 col-md-2 col-sm-2 white-background m0">
	    <div class="col-lg-12" style="height:60px;">
		<div class="hidden-sm hidden-xs recordImage bg_{$MODULE} app-{$SELECTED_MENU_CATEGORY}">
		    {assign var=IMAGE_DETAILS value=$RECORD->getImageDetails()}
		    {if empty($IMAGE_DETAILS)}
			{assign var=avatar value=$RECORD->getName()}
			<div class="name"><span class="nameSize"><strong> {strtoupper($avatar[0])} </strong></span></div>
		    {else}
			{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
			    {if !empty($IMAGE_INFO.path)}
				<img src="{$SITEURL}/{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="100%" height="100%" align="left"><br>
			    {else}
				{assign var=mod_img value="summary_`$MODULE_NAME`.png"}
				<img src="{$SITEURL}/{vimage_path($mod_img)}" class="summaryImg" value="{$mod_img}"/>
			    {/if}
			{/foreach}
		    {/if}
		</div>
	    </div>
	    <div class="media-body">
	    	<h4 class="mt-0 mb-1">{$RECORD->getName()}</h4>
	    	<p class="text-muted">{$RECORD->get('industry')}</p>
	    	<p class="text-muted"><i class="mdi mdi-office-building"></i>{$RECORD->get('industry')}</p>
	    	{assign var=map_array value=array('Contacts','Accounts','Leads')}
	    	{if $MODULE_NAME|in_array:$map_array}
		  <div class="buttons-row">
		    <i class="fa fa-map-marker"></i>&nbsp;
		    <a class="showMap" href="javascript:void(0);" onclick='Head_Index_Js.showMap(this);' data-module='{$RECORD->getModule()->getName()}' data-record='{$RECORD->getId()}'>{vtranslate('LBL_SHOW_MAP', $MODULE_NAME)}</a>
		  </div>
	        {/if}

	    	{assign var=email_field value=getModuleEmailField($MODULE)}
	    	{if !empty($email_field)}
		    <a class='emailField cursorPointer btn- btn-xs btn-info' onclick="Head_Helper_Js.getInternalMailer({$RECORD->getId()}, '{$email_field}' ,'{$MODULE}');">{vtranslate('LBL_SEND_EMAIL', $MODULE)}</a>
	    	{/if}
	    	{assign var=phone_field value=getModulePhoneField($MODULE)}
	    	{if !empty($phone_field)}
		    <a href="tel:{$RECORD->getDisplayValue($phone_field)}" class="btn- btn-xs btn-secondary">{vtranslate('LBL_CALL', $MODULE)}</a>
	    	{/if}
	    	{if Users_Privileges_Model::isPermitted($MODULE, 'EditView', $RECORD->getId())}
		    <a href="javascript: void(0);" class="btn- btn-xs btn-threed ">{vtranslate('LBL_EDIT', $MODULE)}</a>
	        {/if}
	    </div>
        </div>

	<div class="col-lg-3 col-md-3 col-sm-3 white-background m0">
	    <div class="cont">
		<a href="tel:{$RECORD->get('phone')}">{$RECORD->get('phone')}&nbsp;<i class="fa fa-phone" aria-hidden="true"></i></a>
		<a href="mailto:{$RECORD->get('email')}">{$RECORD->get('email')}&nbsp;<i class="fa fa-envelope" aria-hidden="true"></i></a>
		<a href="{$RECORD->get('website')}">{$RECORD->get('website')}&nbsp;<i class="fa fa-link" aria-hidden="true"></i></a>
		<a href="#">smackcoders Tech Pvt Ltd&nbsp<i class="fa fa-map-marker" aria-hidden="true"></i></a>
		<span>{$FIELDS_MODELS_LIST['company']->getDisplayValue($RECORD->get('company'))}<i class="fa fa-building" aria-hidden="true"></i></span>
	    </div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 white-background m0" data-aruna="{$MODULE}">
	    {include file="CumulativeSummary.tpl"|vtemplate_path:$MODULE}
	</div>
    </div>
</div>
