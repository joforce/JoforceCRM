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
        <div class="col-lg-1 col-md-1 col-sm-1">
            <div class="hidden-sm hidden-xs recordImage bg_{$MODULE} app-{$SELECTED_MENU_CATEGORY}">
                    <div class="name"><span><strong> <i class="joicon-{strtolower($MODULE)}"></i> </strong></span></div>
            </div>
        </div>
	<div class="col-lg-3 col-md-3 col-sm-3">
	  <div class="recordBasicInfo">
	    <div class="info-row">
		<h4>
		    <span class="recordLabel pushDown" title="{$RECORD->getName()}">
			    {foreach item=NAME_FIELD from=$MODULE_MODEL->getNameFields()}
			    	{assign var=FIELD_MODEL value=$MODULE_MODEL->getField($NAME_FIELD)}
				    <span class="{$NAME_FIELD}">{trim($RECORD->get($NAME_FIELD))}</span>&nbsp;
			    {/foreach}
		    </span>
		</h4>
	    </div>

	    {assign var=map_array value=array('Contacts','Accounts','Leads')}
	    {if $MODULE_NAME|in_array:$map_array}
		<div class="info-row">
                    <i class="fa fa-map-marker"></i>&nbsp;
                    <a class="showMap" href="javascript:void(0);" onclick='Head_Index_Js.showMap(this);' data-module='{$RECORD->getModule()->getName()}' data-record='{$RECORD->getId()}'>{vtranslate('LBL_SHOW_MAP', $MODULE_NAME)}</a>
                </div>
	    {/if}
	  </div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
	    {include file="DetailViewHeaderFieldsView.tpl"|vtemplate_path:$MODULE}
	</div>
    </div>
</div>
{/strip}
