{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Settings/LayoutEditor/views/Index.php *}

{strip}

<div class="settingsmenu-starts  p0   col-lg-12 col-md-12 col-sm-12 col-xs-12  mt-0  ipad_pro_scr_layouteditorpage " id="settingsmenu-starts">
<div id="licence-alert-waring" class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 

  <strong> <span class="licence-waring-icon"><i class="fa fa-warning"></i> </span> Danger!</strong>  You are not secure 

</div>
	<div class="row row contenthead  card-header-new {if in_array($MODULE,array('LayoutEditor'))} LayoutEditor_card_header {/if} ">
		<div class="col-sm-6 Layouttop pull-left">
			<h3> Layout Editor</h3>
        </div>
			<!-- <label class="col-sm-2 textAlignCenter" style="padding-top: 15px;">
				{vtranslate('SELECT_MODULE', $QUALIFIED_MODULE)}
			</label> -->
			<div class="col-sm-6">
			<select class="select2 inputElement m0 col-sm-6 pull-right" name="layoutEditorModules">
					<option value=''>{vtranslate('LBL_SELECT_OPTION', $QUALIFIED_MODULE)}</option>
					{foreach item=MODULE_NAME from=$SUPPORTED_MODULES}
						<option value="{$MODULE_NAME}" {if $MODULE_NAME eq $SELECTED_MODULE_NAME} selected {/if}>
							{* Calendar needs to be shown as TODO so we are translating using Layout editor specific translations*}
							{if $MODULE_NAME eq 'Calendar'}
								{vtranslate($MODULE_NAME, $QUALIFIED_MODULE)}
							{else}
								{vtranslate($MODULE_NAME, $MODULE_NAME)}
							{/if}
						</option>
					{/foreach}
				</select>
			</div>
		</div>
	<div class="container-fluid pt0" id="layoutEditorContainer">
		<input id="selectedModuleName" type="hidden" value="{$SELECTED_MODULE_NAME}" />
		<input type="hidden" id="selectedModuleLabel" value="{vtranslate($SELECTED_MODULE_NAME,$SELECTED_MODULE_NAME)}" />
		
		
		{if $SELECTED_MODULE_NAME}
			<div class="contents tabbable">
				<ul class="nav nav-tabs layoutTabs massEditTabs">
				<li class=" tab detailviewTab col-md-6 pull-left"><a data-toggle="tab" href="#detailViewLayout"><strong>{vtranslate('LBL_DETAILVIEW_LAYOUT', $QUALIFIED_MODULE)}</strong></a></li>
					<li class="tab relatedListTab col-md-5"><a data-toggle="tab" href="#relatedTabOrder"><strong>{vtranslate('LBL_RELATION_SHIPS', $QUALIFIED_MODULE)}</strong></a></li>
				</ul>
				<div class="tab-content layoutContent themeTableColor overflowVisible">
					<div class="tab-pane active" id="detailViewLayout">
						{include file=vtemplate_path('FieldsList.tpl',$QUALIFIED_MODULE)}
					</div>
					<div class="tab-pane" id="relatedTabOrder">
					</div>
				</div>
			</div>
		{/if}
	</div>

	{if $FIELDS_INFO neq '[]'}
		<script type="text/javascript">
			var uimeta = (function () {
				var fieldInfo = {$FIELDS_INFO};
				var newFieldInfo = {$NEW_FIELDS_INFO};
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
						},
						getNewFieldInfo: function () {
							if (newFieldInfo['newfieldinfo']) {
								return newFieldInfo['newfieldinfo']
							}
							return false;
						}
					}
				};
			})();
		</script>
	{/if}

	{if !$REQUEST_INSTANCE->isAjax()}
		<script type="text/javascript">
			{literal}
				jQuery(document).ready(function () {
					var instance = new Settings_LayoutEditor_Js();
					instance.registerEvents();
				});
			{/literal}
		</script>
	{/if}

{/strip}
