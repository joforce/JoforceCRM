{*<!--
/*+***********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************/
-->*}

{strip}
    <div class="col-lg-12 col-sm-12 col-xs-12 module-action-bar clearfix">
	<div class="module-action-content clearfix d-flex">
	    <div class="col-lg-3 col-md-4 module-breadcrumb module-breadcrumb-{$smarty.request.view}">
		{assign var=MODULE_MODEL value=Head_Module_Model::getInstance($MODULE)}
		{if $MODULE_MODEL->getDefaultViewName() neq 'List'}
		    {assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getDefaultUrl()}
		{else}
		    {assign var=DEFAULT_FILTER_ID value=$MODULE_MODEL->getDefaultCustomFilter()}
		    {if $DEFAULT_FILTER_ID}
			{assign var=CVURL value="/"|cat:$DEFAULT_FILTER_ID}
			{assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getListViewUrl()|cat:$CVURL}
		    {else}
			{assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getListViewUrlWithAllFilter()}
		    {/if}
		{/if}
		<a title="{vtranslate($MODULE, $MODULE)}" href='{$DEFAULT_FILTER_URL}'><h4 class="module-title pull-left textOverflowEllipsis text-uppercase"> {vtranslate($MODULE, $MODULE)} </h4>&nbsp;&nbsp;</a>
		{if $smarty.session.lvs.$MODULE.viewname}
		    {assign var=VIEWID value=$smarty.session.lvs.$MODULE.viewname}
		{/if}
		{if $VIEWID}
		    {foreach item=FILTER_TYPES from=$CUSTOM_VIEWS}
			{foreach item=FILTERS from=$FILTER_TYPES}
			    {if $FILTERS->get('cvid') eq $VIEWID}
				{assign var=CVNAME value=$FILTERS->get('viewname')}
				{break}
			    {/if}
			{/foreach}
		    {/foreach}
		    <p  class="current-filter-name filter-name pull-left cursorPointer" title="{$CVNAME}"><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a  href='{$MODULE_MODEL->getListViewUrl()}/filter/{$VIEWID}'>&nbsp;&nbsp;{$CVNAME}&nbsp;&nbsp;</a> </p>
		{/if}
		{assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
		{if $RECORD and $smarty.request.view eq 'Edit'}
		    <p class="current-filter-name filter-name pull-left "><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a title="{$RECORD->get('label')}">&nbsp;&nbsp;{vtranslate('LBL_EDITING', $MODULE)} : {$RECORD->get('label')} &nbsp;&nbsp;</a></p>
		{else if $smarty.request.view eq 'Edit'}
		    <p class="current-filter-name filter-name pull-left "><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a>&nbsp;&nbsp;{vtranslate('LBL_ADDING_NEW', $MODULE)}&nbsp;&nbsp;</a></p>
		{/if}
		{if $smarty.request.view eq 'Detail'}
		    <p class="current-filter-name filter-name pull-left"><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a title="{$RECORD->get('label')}">&nbsp;&nbsp;{$RECORD->get('label')} &nbsp;&nbsp;</a></p>
		{/if}
	    </div>
		
	    {if !$IS_FORECAST_VIEW}
		{if $IS_LIST_VIEW}
		    <div class="col-lg-2 col-md-1 col-sm-2 col-xs-12">
		    </div>
		{/if}
	    {/if}
	    <div class="col-lg-7 col-md-7 pull-right ">
		<div id="appnav" class="ml-auto">
		    <ul class="nav navbar-nav" id="header-actions" style="position:unset !important;">
		    	<li class="nav-item">
			    <div class="dropdown-filter">
			    	<button class="btn btn-filter btn-warning" title="{vtranslate('LBL_FILTER', $MODULE)}">
				    <i class="fa fa-filter"></i>
			        </button>
				<div class="filter-open">
					{include file="modules/Documents/partials/SidebarEssentials.tpl"}
				</div>
			    </div>
			</li>
			{foreach item=BASIC_ACTION from=$MODULE_BASIC_ACTIONS}
			    {if $BASIC_ACTION->getLabel() eq 'LBL_ADD_RECORD'}
				<li class="nav-item">
				    <div>
					<button type="button" onclick="Documents_Index_Js.uploadTo('Head')" class="btn btn-primary dropdown-toggle">
					    <span class="fa fa-plus" title="{vtranslate('LBL_NEW_DOCUMENT', $MODULE)}"></span>&nbsp;{vtranslate('LBL_NEW_DOCUMENT', $MODULE)}
					</button>
					{* <ul class="dropdown-menu">
					    <li class="dropdown-header"><i class="fa fa-upload"></i> {vtranslate('LBL_FILE_UPLOAD', $MODULE)}</li>
					    <li id="HeadAction">
						<a href="javascript:Documents_Index_Js.uploadTo('Head')">
						    <img style="  margin-top: -3px;margin-right: 4%;" title="Joforce" alt="Joforce" src="{$SITEURL}layouts/skins/images/JoForce.png">
						    {vtranslate('LBL_TO_SERVICE', $MODULE_NAME, {vtranslate('LBL_JOFORCE', $MODULE_NAME)})}
						</a>
					    </li>
					    <li role="separator" class="divider"></li>
					    <li class="dropdown-header"><i class="fa fa-link"></i> {vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', $MODULE)}</li>
					    <li id="shareDocument"><a href="javascript:Documents_Index_Js.createDocument('E')">&nbsp;<i class="fa fa-external-link"></i>&nbsp; {vtranslate('LBL_FROM_SERVICE', $MODULE_NAME, {vtranslate('LBL_FILE_URL', $MODULE_NAME)})}</a></li>
					    <li role="separator" class="divider"></li>
					    <li id="createDocument"><a href="javascript:Documents_Index_Js.createDocument('W')"><i class="fa fa-file-text"></i> {vtranslate('LBL_CREATE_NEW', $MODULE_NAME, {vtranslate('SINGLE_Documents', $MODULE_NAME)})}</a></li>
					</ul> *}
				    </div>
				</li>
			    {/if}
			{/foreach}

			{if $MODULE_SETTING_ACTIONS|@count gt 0}
			    <li class="nav-item">
				<div class="">
				    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
					<span aria-hidden="true" title="{vtranslate('LBL_SETTINGS', $MODULE)}"></span>&nbsp;{vtranslate('LBL_MORE', 'Reports')}
				    </button>
				    <ul class="detailViewSetting dropdown-menu {if in_array($MODULE,array('Documents'))} documents_more {/if}">
					{foreach item=SETTING from=$MODULE_SETTING_ACTIONS}
					    {if {vtranslate($SETTING->getLabel())} eq "%s Numbering"}
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}"><a href={$SETTING->getUrl()}>{vtranslate($SETTING->getLabel(), $MODULE_NAME ,vtranslate($MODULE_NAME, $MODULE_NAME))}</a></li>
					    {else}
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}"><a href={$SETTING->getUrl()}>{vtranslate($SETTING->getLabel(), $MODULE_NAME, vtranslate("SINGLE_$MODULE_NAME", $MODULE_NAME))}</a></li>
					    {/if}
					{/foreach}
				    </ul>
				</div>
			    </li>
			{/if}
		    </ul>
		</div>
	    </div>
	</div>
	{if $FIELDS_INFO neq null}
	    <script type="text/javascript">
		var uimeta = (function() {
		var fieldInfo  = {$FIELDS_INFO};
		return {
		    field: {
			get: function(name, property) {
			    if(name && property === undefined) {
				return fieldInfo[name];
			    }
			    if(name && property) {
				return fieldInfo[name][property]
			    }
			},
			isMandatory : function(name){
			    if(fieldInfo[name]) {
				return fieldInfo[name].mandatory;
			    }
			    return false;
			},
			getType : function(name){
			    if(fieldInfo[name]) {
				return fieldInfo[name].type
			    }
			    return false;
			}
		    }
		};
	    })();
	</script>
    {/if}
</div>
{/strip}
