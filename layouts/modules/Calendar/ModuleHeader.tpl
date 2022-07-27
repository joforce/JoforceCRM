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
    <input type="hidden" name="is_record_creation_allowed" id="is_record_creation_allowed" value="{$IS_CREATE_PERMITTED}">
    <div class="col-lg-12 col-sm-12 col-xs-12 module-action-bar clearfix ">
	<div class="module-action-content clearfix {$MODULE}-module-action-content ipad_calendar_clearfix">
	    <div class="col-lg-3 col-md-3 module-breadcrumb pull-left module-breadcrumb-{$smarty.request.view} transitionsAllHalfSecond">
		{assign var=MODULE_MODEL value=Head_Module_Model::getInstance($MODULE)}
		{if $MODULE_MODEL->getDefaultViewName() neq 'List'}
		    {assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getDefaultUrl()}
		{else}
		    {assign var=DEFAULT_FILTER_ID value=$MODULE_MODEL->getDefaultCustomFilter()}
		    {if $DEFAULT_FILTER_ID}
			{assign var=CVURL value="&viewname="|cat:$DEFAULT_FILTER_ID}
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
			<p class="current-filter-name filter-name pull-left cursorPointer" title="{$CVNAME}"><span class="fa fa-angle-right pull-left" aria-hidden="true"></span><a href='{$MODULE_MODEL->getListViewUrl()}/{$VIEWID}'>&nbsp;&nbsp;{$CVNAME}&nbsp;&nbsp;</a> </p>
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
			
	    {if $IS_LIST_VIEW}
		<!-- <div class="col-lg-3 col-md-3 col-sm-2 col-xs-12">
                </div> -->
            {/if}
	
	    <div class="col-lg-3 col-md-4 pull-left">
            	<div id="topbar-menu" class="topbar-menu text-center pt10">
                    <ul style="list-style: none;">
                    	{foreach item=SIDE_BAR_LINK from=$QUICK_LINKS['SIDEBARLINK']}
                            {assign var=CURRENT_LINK_NAME value="List"}
                            {assign var=VIEW_ICON_CLASS value="joicon-calendarlist"}
                            {if $SIDE_BAR_LINK->get('linklabel') eq 'LBL_CALENDAR_VIEW'}
                            	{assign var=CURRENT_LINK_NAME value="Calendar"}
                                {assign var=VIEW_ICON_CLASS value="joicon-mycalendar"}
                            {else if $SIDE_BAR_LINK->get('linklabel') eq 'LBL_SHARED_CALENDAR'}
                                {assign var=CURRENT_LINK_NAME value="SharedCalendar"}
                                {assign var=VIEW_ICON_CLASS value="joicon-sharedcalendar"}
                            {/if}
                            <li class="pull-left ml5 mr5 topbar-qtip {if $CURRENT_LINK_NAME eq $CURRENT_VIEW}active{/if}" title="{vtranslate($SIDE_BAR_LINK->get('linklabel'),'Calendar')}" style="display: inline-block;">
                            	<a href="{if $VIEW_ICON_CLASS == 'joicon-calendarlist'}{$SITEURL}Calendar/view/List {else} {$SIDE_BAR_LINK->get('linkurl')} {/if}" >
                                    <i class="{$VIEW_ICON_CLASS} mr5"></i>
                                    <!-- <span>{vtranslate($SIDE_BAR_LINK->get('linklabel'),'Calendar')}</span> -->
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                </div>
            </div>

	    <div class="col-lg-6 col-md-5 pull-right">
		<div id="appnav" class="calendar_apanav_mob ">
		    <ul class="nav navbar-nav row">
			<li class="nav-item pull-left">
			    <div class="dropdown-filter">
                            	<button class="btn btn-filter btn-warning" title="{vtranslate('LBL_FILTER', $MODULE)}">
                                    <i class="fa fa-filter"></i>
                            	</button>
                            	<div class="filter-open">
                                    {include file="modules/Head/partials/SidebarEssentials.tpl"}
                            	</div>
                            </div>
			</li>
			{foreach item=BASIC_ACTION from=$MODULE_BASIC_ACTIONS}
			    {if $BASIC_ACTION->getLabel() == 'LBL_IMPORT'}{else}
			    <li class="nav-item pull-left">
				<button id="{$MODULE}_listView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="btn addButton btn-primary" 
				{if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}
				    onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
				{else} 
				    onclick='window.location.href="{$BASIC_ACTION->getUrl()}"'
				{/if}>
				    <div class="fa {$BASIC_ACTION->getIcon()}" aria-hidden="true"></div>&nbsp;&nbsp;
				    {vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
				</button>
			    </li>
			    {/if}
		    	{/foreach}
			{if $MODULE_SETTING_ACTIONS|@count gt 0}
			    <li class="nav-item pull-left">
				<div class="settingsIcon">
				    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<span aria-hidden="true" title="{vtranslate('LBL_SETTINGS', $MODULE)}"></span>&nbsp;{vtranslate('LBL_MORE', 'Reports')}
				    </button>
				    <ul class="detailViewSetting dropdown-menu">
					{foreach item=SETTING from=$MODULE_SETTING_ACTIONS}
					    {if $SETTING->getLabel() eq 'LBL_EDIT_FIELDS'}
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}_Events"><a href="{$SITEURL}{$SETTING->getUrl()}/Events">{vtranslate($SETTING->getLabel(), $MODULE_NAME,vtranslate('LBL_EVENTS',$MODULE_NAME))}</a></li>
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}_Calendar"><a href="{$SITEURL}{$SETTING->getUrl()}/Calendar">{vtranslate($SETTING->getLabel(), $MODULE_NAME,vtranslate('LBL_TASKS','Calendar'))}</a></li>
					    {else if $SETTING->getLabel() eq 'LBL_EDIT_WORKFLOWS'} 
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}_WORKFLOWS"><a href="{$SITEURL}{$SETTING->getUrl()}/Events">{vtranslate('LBL_EVENTS', $MODULE_NAME)} {vtranslate('LBL_WORKFLOWS',$MODULE_NAME)}</a></li>	
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}_WORKFLOWS"><a href="{$SITEURL}{$SETTING->getUrl()}/Calendar">{vtranslate('LBL_TASKS', 'Calendar')} {vtranslate('LBL_WORKFLOWS',$MODULE_NAME)}</a></li>
					    {else}
						<li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}"><a href={$SITEURL}{$SETTING->getUrl()}>{vtranslate($SETTING->getLabel(), $MODULE_NAME, vtranslate($MODULE_NAME, $MODULE_NAME))}</a></li>
					    {/if}
					{/foreach}
                                        {foreach item=LISTVIEW_ADVANCEDACTIONS from=$LISTVIEW_LINKS['LISTVIEW']}
                                            {if $LISTVIEW_ADVANCEDACTIONS->getLabel() == 'LBL_IMPORT'}
                                                <li>
                                                        <a id="{$MODULE}_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="joforce-import-btn"
                                                            {if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}
                                                                onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
                                                            {else}
                                                                onclick="Head_Import_Js.triggerImportAction('{$BASIC_ACTION->getUrl()}')"
                                                            {/if}>
                                                            {vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
                                                        </a>
                                                </li>
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
			}
		    };
		})();
	    </script>
	{/if}
    </div>
{/strip}
