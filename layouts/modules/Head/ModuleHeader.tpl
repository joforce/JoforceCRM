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
    <div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop">
	<div class="module-action-content clearfix {$MODULE}-module-action-content">
	    <div class="col-lg-4 col-md-4 module-breadcrumb module-breadcrumb-{$smarty.request.view} transitionsAllHalfSecond">
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
		<a title="{vtranslate($MODULE, $MODULE)}" href='{$DEFAULT_FILTER_URL}'><h4 class="module-title pull-left text-uppercase"> {vtranslate($MODULE, $MODULE)} </h4>&nbsp;&nbsp;</a>
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
		    <p class="current-filter-name filter-name pull-left cursorPointer" title="{$CVNAME}"><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a href='{$MODULE_MODEL->getListViewUrl()}/filter/{$VIEWID}'>&nbsp;&nbsp;{$CVNAME}&nbsp;&nbsp;</a> </p>
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
		    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
                    	<div class="dropdown-filter" style="width:100%;">
                            <button class="btn btn-filter">
				<i class="fa fa-filter mr10"></i> Lists 
				<i class="fa fa-caret-down ml5"></i> 
			    </button>
		            <div class="filter-open">
                		{include file="modules/Head/partials/SidebarEssentials.tpl"}
                    	    </div>
		        </div>
		    </div>
		{/if}
	    {/if}
	    <div class="col-lg-6 col-md-6 pull-right">
		<div id="appnav" class="navbar-right">
		    <ul class="nav navbar-nav" id="header-actions">				
			{if $kanban_view_enabled}
			    {if $VIEW == 'List' or $VIEW == 'Kanban'}	
				<input type="hidden" name="potential-view-type" id="potential-view-type" value="{$VIEW}" >
			        <li class="">
        		            <button id="forecast-view" type="button" class="btn" data-cvid="{$VIEWID}" data-modulename="{$MODULE}"><i class="joicon joicon-columns"></i></button>
                	        </li>
				<li class="">
                        	    <button id="backto-list-view" type="button" class="btn" data-cvid="{$VIEWID}" data-modulename="{$MODULE}"><i class="joicon joicon-list" aria-hidden="true"></i></button>
               	                </li>
                   	    {/if}
			{/if}
			{foreach item=BASIC_ACTION from=$MODULE_BASIC_ACTIONS}
			    {if $BASIC_ACTION->getLabel() == 'LBL_IMPORT'}
				<li>
				   <div class="joforce-import-btn">
					<button id="{$MODULE}_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="btn addButton btn-default module-buttons" 
					    {if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}
						onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
					    {else}
						onclick="Head_Import_Js.triggerImportAction('{$BASIC_ACTION->getUrl()}')"
					    {/if}>
					    <div class="fa {$BASIC_ACTION->getIcon()}" aria-hidden="true"></div>&nbsp;&nbsp;
					    {vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
					</button>
				    </div>
				</li>
			    {else}
				<li>
				    <button id="{$MODULE}_listView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="btn addButton btn-primary module-buttons" 
					{if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}
					    onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
					{else}
					    onclick='window.location.href = "{$BASIC_ACTION->getUrl()}"'
					{/if}>
					<div class="fa {$BASIC_ACTION->getIcon()}" aria-hidden="true"></div>&nbsp;&nbsp;
					{vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
				    </button>
				</li>
			    {/if}
			{/foreach}
			{if $MODULE_SETTING_ACTIONS|@count gt 0}
			    <li>
				<div class="settingsIcon">
				    <button type="button" class="btn btn-default module-buttons dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<span aria-hidden="true" title="{vtranslate('LBL_MORE', $MODULE)}"></span>&nbsp;{vtranslate('LBL_MORE','Head')}&nbsp;
					<span class="caret"></span>
				    </button>
				    <ul class="detailViewSetting dropdown-menu">
					{foreach item=SETTING from=$MODULE_SETTING_ACTIONS}
					    <li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}"><a href={$SETTING->getUrl()}>{vtranslate($SETTING->getLabel(), $MODULE_NAME ,vtranslate($MODULE_NAME, $MODULE_NAME))}</a></li>
					{/foreach}
					<li class="divider hide" style="margin:9px 0px;"></li>
					{assign var=FIND_DUPLICATES_EXITS value=false}
					{foreach item=LISTVIEW_ADVANCEDACTIONS from=$LISTVIEW_LINKS['LISTVIEW']}
					    {if $LISTVIEW_ADVANCEDACTIONS->getLabel() == 'Print'}
	                                        {assign var=PRINT_TEMPLATE value=$LISTVIEW_ADVANCEDACTIONS}
					    {else}
					        {if $LISTVIEW_ADVANCEDACTIONS->getLabel() == 'LBL_FIND_DUPLICATES'}
					            {assign var=FIND_DUPLICATES_EXISTS value=true}
						{/if}
					    {/if}
					{/foreach}
		                        {if $PRINT_TEMPLATE}
		                            <li><a id="{$MODULE}_listView_advancedAction_{Head_Util_Helper::replaceSpaceWithUnderScores($PRINT_TEMPLATE->getLabel())}" {if stripos($PRINT_TEMPLATE->getUrl(), 'javascript:')===0} href="javascript:void(0);" onclick='{$PRINT_TEMPLATE->getUrl()|substr:strlen("javascript:")};'{else} href='{$PRINT_TEMPLATE->getUrl()}' {/if}>{vtranslate($PRINT_TEMPLATE->getLabel(), $MODULE)}</a></li>
		                        {/if}
		                        {if $FIND_DUPLICATES_EXISTS}
		                            <li><a id="{$MODULE}_listView_advancedAction_MERGE_RECORD"  href="javascript:void(0);" onclick='Head_List_Js.triggerMergeRecord()'>{vtranslate('LBL_MERGE_SELECTED_RECORDS', $MODULE)}</a></li>
		                        {/if}
		                        {foreach item=LISTVIEW_ADVANCEDACTIONS from=$LISTVIEW_LINKS['LISTVIEW']}
		                            {if $LISTVIEW_ADVANCEDACTIONS->getLabel() == 'LBL_IMPORT'}
		                                {*Remove Import Action*}
		                            {elseif $LISTVIEW_ADVANCEDACTIONS->getLabel() == 'Print'}
		                                {assign var=PRINT_TEMPLATE value=$LISTVIEW_ADVANCEDACTIONS}
					    {else}
					    	{if $LISTVIEW_ADVANCEDACTIONS->getLabel() == 'LBL_FIND_DUPLICATES'}
						    {assign var=FIND_DUPLICATES_EXISTS value=true}
						{/if}
						{if $LISTVIEW_ADVANCEDACTIONS->getLabel() != 'Print'}
						    <li class="selectFreeRecords"><a id="{$MODULE}_listView_advancedAction_{Head_Util_Helper::replaceSpaceWithUnderScores($LISTVIEW_ADVANCEDACTIONS->getLabel())}" {if stripos($LISTVIEW_ADVANCEDACTIONS->getUrl(), 'javascript:')===0} href="javascript:void(0);" onclick='{$LISTVIEW_ADVANCEDACTIONS->getUrl()|substr:strlen("javascript:")};'{else} href='{$LISTVIEW_ADVANCEDACTIONS->getUrl()}' {/if}>{vtranslate($LISTVIEW_ADVANCEDACTIONS->getLabel(), $MODULE)}</a></li>
						{/if}
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
