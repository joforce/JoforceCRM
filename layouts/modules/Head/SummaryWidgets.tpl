{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Head/views/Detail.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{strip}
	<input type="hidden" name="page" value="{$PAGING_MODEL->get('page')}" />
	<input type="hidden" name="pageLimit" value="{$PAGING_MODEL->get('limit')}" />
	{if $RELATED_MODULE && $RELATED_RECORDS}
		{assign var=FILENAME value=$RELATED_MODULE|cat:"SummaryWidgetContents.tpl"}
		{include file=$FILENAME|vtemplate_path:$MODULE RELATED_RECORDS=$RELATED_RECORDS}
    {else}
		<form id="dragDropUploadForm">
			{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
				<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
			{/if}
			<input type="hidden" name="module" value="Documents">
			<input type="hidden" name="action" value="SaveAjax">
			<input type="hidden" name="document_source" value="Head">

			{if $RELATION_OPERATOR eq 'true'}
				<input type="hidden" name="relationOperation" value="{$RELATION_OPERATOR}" />
				<input type="hidden" name="sourceModule" value="{$PARENT_MODULE}" />
				<input type="hidden" name="sourceRecord" value="{$PARENT_ID}" />
				{if $RELATION_FIELD_NAME}
					<input type="hidden" name="{$RELATION_FIELD_NAME}" value="{$PARENT_ID}" /> 
				{/if}
			{/if}
			
			<input type="hidden" name="max_upload_limit" value="{Head_Util_Helper::getMaxUploadSizeInBytes()}" />
			<input type="hidden" name="max_upload_limit_mb" value="{Head_Util_Helper::getMaxUploadSize()}" />

			<input type="hidden" name="assigned_user_id" value="{$USER_MODEL->id}">
			<input type="hidden" name="folderid" value="1">
			<input type="hidden" name="filelocationtype" value="I">
		</form>
		<div class="">
			{* <div class="summaryWidgetContainer noContent"> *}
			{* <div class="dragdrop-dotted drop-area" style="padding: 8%;">
				<span class="fa fa-upload document-panel-icon"></span>
				<div style="font-size:115%;">
					{vtranslate('LBL_DRAG_&_DROP_FILE_HERE', 'Documents')}
				</div>
			</div> *}
			{* <p class="textAlignCenter">{vtranslate('LBL_NO_RELATED',$MODULE)} {$RELATED_MODULE}</p> *}
			<form id="docUpload" class="form-horizontal recordEditView" name="docUpload" method="post" action="{$SITEURL}index.php">
				<div id="upload" data-filelocationtype="I">
					{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
						<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
					{/if}
					<input type="hidden" name="module" value="Documents" />
					<input type="hidden" name="action" value="SaveAjax" />
					<input type="hidden" name="document_source" value="Head" />
					{if $RELATION_OPERATOR eq 'true'}
						<input type="hidden" name="relationOperation" value="{$RELATION_OPERATOR}" />
						<input type="hidden" name="sourceModule" value="{$PARENT_MODULE}" />
						<input type="hidden" name="sourceRecord" value="{$PARENT_ID}" />
						{if $RELATION_FIELD_NAME}
							<input type="hidden" name="{$RELATION_FIELD_NAME}" value="{$PARENT_ID}" /> 
						{/if}
					{/if}
					
					<input type="hidden" name="max_upload_limit" value="{Head_Util_Helper::getMaxUploadSizeInBytes()}" />
					<input type="hidden" name="max_upload_limit_mb" value="{Head_Util_Helper::getMaxUploadSize()}" />

					<input type="hidden" name="assigned_user_id" value="{$USER_MODEL->id}">
					<input type="hidden" name="folderid" value="1">
					<input type="hidden" name="filelocationtype" value="I">       
					<div id="" class="dragdrop-dotted drop-area file-upload-wrapper" style="padding: 11%;">
						<span class="fa fa-upload document-panel-icon"></span>
						<div style="font-size:115%;">
							{vtranslate('LBL_DRAG_&_DROP_FILE_HERE', 'Documents')}
						</div>
						<input style="display:none;" type="file" id="droppedFile" class="file-upload" name="filename" data-rule-required="true"/>
						<input id="file_notes_title" type="hidden" data-fieldname="notes_title" data-fieldtype="string" class="inputElement nameField" name="notes_title" value="" data-rule-required="true" aria-required="true">																	
					</div>
				</div>
			</form>	
			<script type="text/javascript" src="{$SITEURL}layouts/modules/Documents/resources/Documents.js"><\/script>			
		</div>
	{/if}
{/strip}