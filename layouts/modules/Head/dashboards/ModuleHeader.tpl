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
	<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop">
	<input type="hidden" id="present-dashboard-tab" value="{$PRESENT_TAB}"/>
		<div class="module-action-content clearfix {$MODULE}-module-action-content">
			<div class="module-breadcrumb module-breadcrumb-{$smarty.request.view} transitionsAllHalfSecond dashboard-top" id="dashboard-option" data-boardid=1>
                                <a id="notificaiton-dashboard" href="{$SITEURL}Home/view/List" title="{vtranslate('LBL_DASHBOARD', $MODULE)}">
                                        <b><h4 class="module-title pull-left text-uppercase ml10">{vtranslate('LBL_DASHBOARD', $MODULE)}</h4></b>
                                </a>
                        </div>
			<div class="module-breadcrumb module-breadcrumb-{$smarty.request.view} transitionsAllHalfSecond dashboard-top" id="dashlets-option" data-boardid=2>
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
				<a title="{vtranslate('LBL_WIDGETS', $MODULE)}" href='{$DEFAULT_FILTER_URL}'><h4 class="module-title pull-left text-uppercase ml10">{vtranslate('LBL_WIDGETS', $MODULE)}</h4>&nbsp;&nbsp;</a>
				{if $smarty.session.lvs.$MODULE.viewname}
					{assign var=VIEWID value=$smarty.session.lvs.$MODULE.viewname}
				{/if}
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

