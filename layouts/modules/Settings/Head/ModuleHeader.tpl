{*<!--
/*+***********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************/
-->*}

{strip}
    <div class="col-lg-12 col-sm-12 col-xs-12 module-action-bar clearfix">
	<div class="module-action-content clearfix d-flex align-items-center">
	    <div class="col-lg-6 col-md-6  module-breadcrumb col-6">
		{if $USER_MODEL->isAdminUser()}
		    {assign var=sett_url value='Head/Settings/Index'}
		{else}
		    {assign var=sett_url value='Head/Settings/SettingsIndex'}
		{/if}
		<a title="{vtranslate('Home', $MODULE)}" href='{$SITEURL}{$sett_url}' class="pull-left" style="color: #0444a7 !important;">
		    <i class="fa fa-gears" style="margin: 10px;"></i>
		    <h4 class="module-title pull-left text-uppercase" style="color: #0444a7 !important;">{vtranslate('LBL_SETTINGS', $MODULE)} </h4>
		</a>
		&nbsp;<span class="fa fa-angle-right pull-left {if $VIEW eq 'Index' && $MODULE eq 'Head'} hide {/if}" aria-hidden="true" style="padding-top: 12px;padding-left: 5px;"></span>
		{if $MODULE neq 'Head' or $smarty.request.view neq 'Index'}
		    {if $ACTIVE_BLOCK['block']}
			<span class="current-filter-name filter-name pull-left">
			    {vtranslate($ACTIVE_BLOCK['block'], $QUALIFIED_MODULE)}&nbsp;
			    <span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;
			</span>
		    {/if}
		    {if $MODULE neq 'Head'}
			{assign var=ALLOWED_MODULES value=","|explode:'Users,Profiles,Groups,Roles,Webforms,Workflows'}
			    {if $MODULE_MODEL and $MODULE|in_array:$ALLOWED_MODULES}
				{if $MODULE eq 'Webforms'}
				    {assign var=URL value=$MODULE_MODEL->getListViewUrl()}
				{else}
				    {assign var=URL value=$MODULE_MODEL->getDefaultUrl()}
				{/if}
				{if $URL|strpos:'parent' eq ''}
				    {assign var=URL value=$URL|cat:'&parent='|cat:$smarty.request.parent}
				{/if}
			    {/if}
			    <span class="current-filter-name settingModuleName filter-name pull-left">	
			    {if $smarty.request.view eq 'Calendar'}
				{if $smarty.request.mode eq 'Edit'}
				    <a href="{"index.php?module="|cat:$smarty.request.module|cat:'&parent='|cat:$smarty.request.parent|cat:'&view='|cat:$smarty.request.view}">
					{vtranslate({$PAGETITLE}, $QUALIFIED_MODULE)}
				    </a>&nbsp;
				    <span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;
				    {vtranslate('LBL_EDITING', $MODULE)} :&nbsp;{vtranslate({$PAGETITLE}, $QUALIFIED_MODULE)}&nbsp;{vtranslate('LBL_OF',$QUALIFIED_MODULE)}&nbsp;{$USER_MODEL->getName()}
				{else}
				    {vtranslate({$PAGETITLE}, $QUALIFIED_MODULE)}&nbsp;<span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;{$USER_MODEL->getName()}
				{/if}
			    {else if $smarty.request.view neq 'List' and $smarty.request.module eq 'Users'}
				{if $smarty.request.view eq 'PreferenceEdit'}
				    <a href="{"index.php?module="|cat:$smarty.request.module|cat:'&parent='|cat:$smarty.request.parent|cat:'&view=PreferenceDetail&record='|cat:$smarty.request.record}">{vtranslate($ACTIVE_BLOCK['block'], $QUALIFIED_MODULE)}&nbsp;</a>
				    <span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;
				    {vtranslate('LBL_EDITING', $MODULE)} :&nbsp;{$USER_MODEL->getName()}
				{else if $smarty.request.view eq 'Edit' or $smarty.request.view eq 'Detail'}
				    <a href="{$SITEURL}Users/Settings/List">
					{if $smarty.request.extensionModule}
					    {$smarty.request.extensionModule}
					{else}
					    {vtranslate({$PAGETITLE}, $QUALIFIED_MODULE)}
					{/if}&nbsp;
				    </a>
				    <span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;
				    {if $smarty.request.view eq 'Edit'}
					{if $RECORD}
					    {vtranslate('LBL_EDITING', $MODULE)} :&nbsp;{$RECORD->getName()}
					{else}
					    {vtranslate('LBL_ADDING_NEW', $MODULE)}
					{/if}
				    {else}
					{$RECORD->getName()}
				    {/if}
				{else}
				    {$USER_MODEL->getName()}
				{/if}
			    {else if $URL and $URL|strpos:$smarty.request.view eq ''}
				<a href="{$URL}">
				    {if $smarty.request.extensionModule}
					{$smarty.request.extensionModule}
				    {else}
					{vtranslate({$PAGETITLE}, $QUALIFIED_MODULE)}
				    {/if}
				</a>&nbsp;
				<span class="fa fa-angle-right" aria-hidden="true"></span>&nbsp;
				    {if $RECORD}
					{if $smarty.request.view eq 'Edit'}
					    {vtranslate('LBL_EDITING', $MODULE)} :&nbsp;
					{/if}
					{$RECORD->getName()}
				    {/if}
			    {else}
				{if $smarty.request.extensionModule}{$smarty.request.extensionModule}{else}{vtranslate({$PAGETITLE}, $QUALIFIED_MODULE)}{/if}
			{/if}
		    </span>

		{else}
		    {if $smarty.request.view eq 'TaxIndex'}
			{assign var=SELECTED_MODULE value='LBL_TAX_MANAGEMENT'}
		    {elseif $smarty.request.view eq 'TermsAndConditionsEdit'}
			{assign var=SELECTED_MODULE value='LBL_TERMS_AND_CONDITIONS'}
		    {else}
			{assign var=SELECTED_MODULE value=$ACTIVE_BLOCK['menu']}
		    {/if}

		    <span class="current-filter-name">{vtranslate({$SELECTED_MODULE}, $QUALIFIED_MODULE)}</span>
		{/if}
	    {/if}
		
	</div>
	<div class="{if in_array($MODULE,array('Users'))} col-lg-2 col-md-4 {else} col-lg-3 col-md-5 {/if}">
	{if $MODULE eq 'Head' and $VIEW eq 'Index'}
	{else}
    	    {include file="modules/Settings/Head/Sidebar.tpl"}
	{/if}
	</div>
	<div class=" {if in_array($MODULE,array('Users'))} col-lg-4 col-md-7 pull-right col-7 {else} col-lg-3 col-md-6 pull-right col-6 {/if}">
	<div id="appnav" class="{if in_array($MODULE,array('Groups','Webforms','Workflows','Webforms','Webhooks','MailConverter','Currency','PickListDependency','Users','Head'))}  ml-auto nextappnav setting_page_head_btn {else} ml-auto nextappnav  {/if} {if in_array($module,array('Users'))} pull-right {/if}">
                {if $MODULE eq 'Head' and $VIEW eq 'Index'}
                    <div class = "settings_search {if in_array($MODULE,array('Head'))}setting_main_search{/if}">
                    	<input type ='text' class="search-settings pull-right" id="search_settings" style="" placeholder="{vtranslate('LBL_SEARCH_FOR_SETTINGS', $QUALIFIED_MODULE)}">
                    </div>
                {/if}
		<ul class="nav navbar-nav row {if in_array($MODULE,array('Users'))} user-top-menu {/if}">
		    {foreach item=BASIC_ACTION from=$MODULE_BASIC_ACTIONS}
			<li class="nav-item  {if in_array($MODULE,array('Webforms','','PickListDependency'))} Ms_hide_nav pull-right {elseif !in_array($MODULE,array('MailConverter'))} pull-left {/if}">
			{if $BASIC_ACTION->getLabel() == 'LBL_IMPORT'}
			   <div>
				<button id="{$MODULE}_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="btn addButton btn-primary" 
				{if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}
				    onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
				{else} 
				    onclick="Head_Import_Js.triggerImportAction('{$BASIC_ACTION->getUrl()}')"
				{/if}>
				<div class="fa {$BASIC_ACTION->getIcon()}" aria-hidden="true"></div>&nbsp;&nbsp;
				{vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
				</button>
			    </div>
			{else}
			    <button type="button" class="btn addButton btn-primary" id="{$MODULE}_listView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}"
				{if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}
				    onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
				{else} 
				    onclick='window.location.href="{$BASIC_ACTION->getUrl()}"'
				{/if}>
				<div class="fa {$BASIC_ACTION->getIcon()}" aria-hidden="true"></div>
				&nbsp;&nbsp;{vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
			    </button>
			{/if}
		    </li>
		{/foreach}
		{if $LISTVIEW_LINKS['LISTVIEWSETTING']|@count gt 0}
		    {if empty($QUALIFIEDMODULE)} 
			{assign var=QUALIFIEDMODULE value=$MODULE}
		    {/if}
		    <li class="nav-item">
			<div class="settingsIcon">
			    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="fa fa-wrench" aria-hidden="true" title="{vtranslate('LBL_SETTINGS', $MODULE)}"></span>
			    </button>
			    <ul class="detailViewSetting dropdown-menu">
				{foreach item=SETTING from=$LISTVIEW_LINKS['LISTVIEWSETTING']}
				    <li id="{$MODULE}_setings_lisview_advancedAction_{$SETTING->getLabel()}" class="dropdown-item">
                                        <a {if stripos($SETTING->getUrl(), 'javascript:') === 0}
						 onclick='{$SETTING->getUrl()|substr:strlen("javascript:")};'
                                           {else}
                                                 onclick='window.location.href="{$SETTING->getUrl()}"'
                                           {/if}>
                                           {vtranslate($SETTING->getLabel(), $QUALIFIEDMODULE)}
					</a>
				    </li>
				{/foreach}
			    </ul>
			</div>
		    </li>
		{/if}
		{assign var=RESTRICTED_MODULE_LIST value=['Users', 'EmailTemplates']}
		    {if $LISTVIEW_LINKS['LISTVIEWBASIC']|@count gt 0 and !in_array($MODULE, $RESTRICTED_MODULE_LIST)}
			{if empty($QUALIFIED_MODULE)} 
			    {assign var=QUALIFIED_MODULE value='Settings:'|cat:$MODULE}
			{/if}
			{foreach item=LISTVIEW_BASICACTION from=$LISTVIEW_LINKS['LISTVIEWBASIC']}
			    {if $MODULE eq 'Users'} {assign var=LANGMODULE value=$MODULE} {/if}
				<li class="nav-item {if in_array($MODULE,array(''))} Ms_hide_nav {/if}">
				    <button class="btn btn-primary addButton" id="{$MODULE}_listView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($LISTVIEW_BASICACTION->getLabel())}" 
					{if $MODULE eq 'Workflows'}
					    onclick='Settings_Workflows_List_Js.triggerCreate("{$LISTVIEW_BASICACTION->getUrl()}?mode=V7Edit")'
					{else}
					    {if stripos($LISTVIEW_BASICACTION->getUrl(), 'javascript:')===0}
						onclick='{$LISTVIEW_BASICACTION->getUrl()|substr:strlen("javascript:")};'
					    {else}
						onclick='window.location.href = "{$LISTVIEW_BASICACTION->getUrl()}"'
					    {/if}
					{/if}>
					{if $MODULE eq 'Tags'}
					    <i class="fa fa-plus"></i>&nbsp;&nbsp;{vtranslate('LBL_ADD_TAG', $QUALIFIED_MODULE)}
					{else}
					    {if $LISTVIEW_BASICACTION->getIcon()}
						<i class="{$LISTVIEW_BASICACTION->getIcon()}"></i>&nbsp;&nbsp;
					    {/if}
					    {vtranslate($LISTVIEW_BASICACTION->getLabel(), $QUALIFIED_MODULE)}
					{/if}
				    </button>
				</li>
			    {/foreach}
			{/if}
		    </ul>
		</div>
	    </div>
	</div>
	{if $FIELDS_INFO neq null}
	    <script type="text/javascript">
		var uimeta = (function () {
		    var fieldInfo = {$FIELDS_INFO};
		    return {
			field: {
				get: function (name, property) {
				    if (name && property === undefined) {
					return fieldInfo[name];
				    }
				    if (name && property) {
					return fieldInfo[name][property]
				    }
				},
				isMandatory: function (name) {
				    if (fieldInfo[name]) {
					return fieldInfo[name].mandatory;
				    }
				    return false;
				},
				getType: function (name) {
				    if (fieldInfo[name]) {
				    	return fieldInfo[name].type
				    }
				    return false;
				}
			},
		    };
		})();
	    </script>
	{/if}
    </div>
{/strip}
