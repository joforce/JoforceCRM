{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}

{strip}
	<div class="col-sm-12 col-xs-12 module-action-bar clearfix coloredBorderTop">
		<div class="module-action-content clearfix">
			<span class="col-lg-7 col-md-7 module-breadcrumb module-breadcrumb-{$smarty.request.view}">
				<span>
					<h4 title="{vtranslate($MODULE, $MODULE)}" class="module-title pull-left text-uppercase"> {vtranslate($MODULE, $MODULE)} </h4>
				</span>
				<span>
					<p class="current-filter-name pull-left">&nbsp;&nbsp;<span class="fa fa-angle-right" aria-hidden="true"></span> {$VIEW} </p>
				</span>
				<span>
					<p class="current-filter-name pull-left textOverflowEllipsis" style="width:250px;">&nbsp;&nbsp;<span class="fa fa-angle-right" aria-hidden="true"></span> {vtranslate($SOURCE_MODULE,$SOURCE_MODULE)} </p>
				</span>
			</span>
		    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                        <div class="dropdown-filter pull-right">
                            <button class="btn btn-filter btn-warning" title="{vtranslate('LBL_LISTS', $MODULE)}">
                                <i class="fa fa-filter"></i>
                            </button>
                            <div class="filter-open">
				{include file="modules/RecycleBin/partials/SidebarEssentials.tpl"}
                            </div>
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
