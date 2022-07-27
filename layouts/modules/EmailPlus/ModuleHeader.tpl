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
	    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 mt10">
		{include file="partials/HeaderBreadCrumb.tpl"|vtemplate_path:$MODULE}
	    </div>
	    <div class="col-lg-6 col-md-6 col-xs-6 pull-right">
		<button class='btn btn-primary pull-right' style='position:relative;top:3px;' onclick="location.href='{$SITEURL}EmailPlus/view/ServerSettings';">{vtranslate('Settings', $MODULE_NAME)}</button>
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
