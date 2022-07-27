{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}

{strip}
	<div class="col-sm-12 col-xs-12  card">
		<div class="container-fluid" id="AsteriskServerDetails">
			<input type="hidden" name="module" value="PBXManager"/>
			<input type="hidden" name="action" value="SaveAjax"/>
			<input type="hidden" name="parent" value="Settings"/>
			<input type="hidden" class="recordid" name="id" value="{$RECORD_ID}">
			<div class="row mt20 ml10 mr5 card-header-new">
				<div class="col-sm-8 col-8"><h4 class="ml30 pt20"><b>{vtranslate('LBL_PBXMANAGER', $QUALIFIED_MODULE)}<b></h4></div>
				{assign var=MODULE_MODEL value=Settings_PBXManager_Module_Model::getCleanInstance()}
				<div class="col-sm-4 col-4">
					<div class="clearfix">
						<div class="btn-group pull-right editbutton-container">
							<button class="btn btn-default editButton" data-url="{$MODULE_MODEL->getEditViewUrl()}&mode=showpopup&id={$RECORD_ID}" title="{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}">{vtranslate('LBL_EDIT',$QUALIFIED_MODULE)}</button>
						</div>
					</div>
				</div>
			</div>
			<div class="contents col-lg-12">
				<table class="table detailview-table no-border">
					<tbody>
						{assign var=FIELDS value=PBXManager_PBXManager_Connector::getSettingsParameters()}
						{foreach item=FIELD_TYPE key=FIELD_NAME from=$FIELDS}
							<tr>
								<td class="fieldLabel" style="width:25%"><label>{vtranslate($FIELD_NAME, $QUALIFIED_MODULE)}</label></td>
								<td style="word-wrap:break-word;">{$RECORD_MODEL->get($FIELD_NAME)}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-sm-12 col-xs-12">
			<div class="col-sm-8 col-xs-8">
				<div class="alert alert-danger container-fluid">
					<b>{vtranslate('LBL_NOTE', $QUALIFIED_MODULE)}</b>&nbsp;
					{vtranslate('LBL_PBXMANAGER_INFO', $QUALIFIED_MODULE)}
				</div>
			</div>
		</div>
	</div>
{/strip}
