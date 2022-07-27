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
    <div class="col-lg-12 col-sm-12 col-xs-12 module-action-bar clearfix">
	<div class="module-action-content clearfix {$MODULE}-module-action-content">
	    <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12 mt10 d-inline-block pull-left">
		{include file="partials/HeaderBreadCrumb.tpl"|vtemplate_path:$MODULE}
	    </div>
	    <div class="col-sm-3 col-md-3 col-lg-3 pull-left mt10">
		<select class="select2 col-sm-9 p-0" name="moduleFilters" id="moduleFilters" style="">
		    {foreach item=FILTER key=FILTER_ID from=$CUSTOM_LIST}
                    	<option value="{$FILTER->id}" {if $FILTER->id eq $VIEWID} selected {/if}>{$FILTER->name}</option>
                    {/foreach}
                </select>
            </div>

	    <div class="col-lg-7 col-md-4 pull-right">
		<div id="third-listview-actions" class="third-listview-actions-container pull-left mt-2">
                	{include file="kanban/Pagination_kanban.tpl"|vtemplate_path:"Head"}
        	</div>

	        <div id="appnav" class="ml-auto">
		    <ul class="nav navbar-nav" id="header-actions">
                        {if $kanban_view_enabled}
                            {if $VIEW == 'List' or $VIEW == 'Kanban'}
                                <input type="hidden" name="potential-view-type" id="potential-view-type" value="{$VIEW}" >
                                <li class="nav-item">
                                    <button id="forecast-view" type="button" class="btn" data-cvid="{$VIEWID}" data-modulename="{$MODULE}"><i class="joicon joicon-columns"></i></button>
                                </li>
                                <li class="nav-item">
                                    <button id="backto-list-view" type="button" class="btn" data-cvid="{$VIEWID}" data-modulename="{$MODULE}"><i class="joicon joicon-list" aria-hidden="true"></i></button>
                                </li>
                            {/if}
                        {/if}
			{if !empty($MODULE_BASIC_ACTIONS)}
			{foreach item=BASIC_ACTION from=$MODULE_BASIC_ACTIONS}
			    {if $BASIC_ACTION->getLabel() == 'LBL_IMPORT'}
				{assign var=import_option value=true}
			    {else}
				<li class="nav-item">
				    <button id="{$MODULE}_listView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="btn addButton btn-primary" 
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
			{/if}
			{if $MODULE_SETTING_ACTIONS|@count gt 0}
			    <li class="nav-item">
				<div class="settingsIcon">
				    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					<span aria-hidden="true" title="{vtranslate('LBL_MORE', $MODULE)}"></span>&nbsp;{vtranslate('LBL_MORE','Head')}
				    </button>
				    <ul class="detailViewSetting dropdown-menu">
					{foreach item=SETTING from=$MODULE_SETTING_ACTIONS}
					    <li id="{$MODULE_NAME}_listview_advancedAction_{$SETTING->getLabel()}"><a href={$SETTING->getUrl()}>{vtranslate($SETTING->getLabel(), $MODULE_NAME ,vtranslate($MODULE_NAME, $MODULE_NAME))}</a></li>
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
