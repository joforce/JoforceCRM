{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{strip}
    <div class="centerbody">
	<div class="col-lg-12">
	    <div class="hidden-sm  hidden-xs recordImage bg_{$MODULE}" style="border-radius:47px">
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
	
    </div>
    <div class="media-body">
	<h3 class="mt-0 mb-1">{$RECORD->getName()}</h3>
	<div class="col-lg-12 company_name">
	    {if $MODULE eq 'Contacts'}
		{assign var=FIELD_MODEL value=$MODULE_MODEL->getField('account_id')}
		<h4 class="mt-0 mb-1 text-center">{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}</h4>
	        <p class="text-muted">{$RECORD->get('title')}</p>
	    {/if}
	</div>
	{* Deals Page *}
	{assign var=map_array value=array('Potentials')}
	{if $MODULE_NAME|in_array:$map_array}
	<div class="recordBasicInfo">
		    <div class="info-row">
			
		    </div>

		    <form id="headerForm" method="POST">
    {assign var=FIELDS_MODELS_LIST value=$MODULE_MODEL->getFields()}
    {foreach item=FIELD_MODEL from=$FIELDS_MODELS_LIST}
        {assign var=FIELD_DATA_TYPE value=$FIELD_MODEL->getFieldDataType()}
        {assign var=FIELD_NAME value={$FIELD_MODEL->getName()}}
        {if $FIELD_MODEL->isHeaderField() && $FIELD_MODEL->isActiveField() && $RECORD->get($FIELD_NAME) && $FIELD_MODEL->isViewable()}
            {assign var=FIELD_MODEL value=$FIELD_MODEL->set('fieldvalue', $RECORD->get({$FIELD_NAME}))}
            <div class="info-row row headerAjaxEdit td">
                <div class="fieldLabel">
                    {assign var=DISPLAY_VALUE value="{$FIELD_MODEL->getDisplayValue($RECORD->get($FIELD_NAME))}"}
                    <span class="{$FIELD_NAME} value" title="{vtranslate($FIELD_MODEL->get('label'),$MODULE)} : {strip_tags($DISPLAY_VALUE)}">
                        {include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
                    </span>
                    {if $FIELD_MODEL->isEditable() eq 'true' && $LIST_PREVIEW neq 'true' && $IS_AJAX_ENABLED eq 'true'}
                        <span class="hide edit">
                            {if $FIELD_DATA_TYPE eq 'multipicklist'}
                                <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}[]' data-type="{$FIELD_MODEL->getFieldDataType()}" data-displayvalue='{Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}' data-value="{$FIELD_MODEL->get('fieldvalue')}" />
                            {else}
                                <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}' data-type="{$FIELD_MODEL->getFieldDataType()}" data-displayvalue='{Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}' data-value="{$FIELD_MODEL->get('fieldvalue')}" />
                            {/if}    
                        </span>
                        {* <span class="action">
                            <a href="#" onclick="return false;" class="editAction fa fa-pencil"></a>
                        </span> *}
                    {/if}
                </div>
            </div>
        {/if}
    {/foreach}
</form>
		    

		</div>
{/if}
	{* Deals Page *}
  	{assign var=map_array value=array('Contacts','Accounts','Leads', 'Vendors')}
	{if $MODULE eq 'Contacts'}
	    {assign var=_id_fields value=["fa fa-birthday-cake"=>"birthday","fa fa-envelope"=>"email","fa fa-phone"=>"phone"]}
	{elseif $MODULE eq 'Accounts'}
	    {assign var=_id_fields value=['fa fa-envelope'=>'email1','fa fa-phone'=>'phone','fa fa-globe'=>'website']}
	{elseif $MODULE eq 'Leads'}
	    {assign var=_id_fields value=['fa fa-envelope'=>'email','fa fa-phone'=>'phone','fa fa-globe'=>'website']}
	{elseif $MODULE eq 'Vendors'}
	    {assign var=_id_fields value=['fa fa-envelope'=>'email','fa fa-phone'=> 'phone','fa fa-globe'=>'website']}
	{/if}
    	{if $MODULE_NAME|in_array:$map_array}
	    {foreach item=_fieldname from=$_id_fields key=_icon}
			{$FIELD_MODEL = $MODULE_MODEL->getField($_fieldname)}
			{if $FIELD_MODEL->get('fieldvalue')}
				<div class="{if $_icon neq 'fa fa-birthday-cake'} d-flex justify-content-start align-items-center {/if}">
				<span style="" class="{$_icon}" title="{vtranslate($FIELD_MODEL->get('label'),$MODULE)}"></span>
				{* <p>{vtranslate($FIELD_MODEL->get('label'),$MODULE)}</p> *}
					{if $FIELD_MODEL->getFieldDataType() eq 'email'}
						<a style="display:block;" onmouseover="this.style.color='#1a73e8'" class="showMap" onmouseout="this.style.color='#000'" onclick="Head_Helper_Js.getInternalMailer({$RECORD->getId()}, 'email' ,'{$MODULE}');">
							{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
						</a>
					{else if $FIELD_MODEL->getFieldDataType() eq 'phone'}
						<a style="display:block;" href="tel:{$FIELD_MODEL->get('fieldvalue')}" class="showMap">
							{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
						</a>
					{else if $FIELD_MODEL->getFieldDataType() eq 'url'}
						<a style="display:block;" target="_blank" class="showMap "  href="//{$FIELD_MODEL->get('fieldvalue')}">
							{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
						</a>
					{else}
						<span>
							{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
						</span>
					{/if}
				</div>
			{/if}
	    {/foreach}
		{if $ADDRESS_STRING}
			<div class="buttons-row d-flex justify-content-start align-items-center">
			<i class="fa fa-map-marker" style=";"></i>&nbsp;
			<a style="display:block;" class="showMap new-showMap" href="javascript:void(0);" onclick='Head_Index_Js.showMap(this);' data-module='{$RECORD->getModule()->getName()}' data-record='{$RECORD->getId()}'>{$ADDRESS_STRING}</a>
			</div>
		{/if}
	{/if}
    </div>
{/strip}
