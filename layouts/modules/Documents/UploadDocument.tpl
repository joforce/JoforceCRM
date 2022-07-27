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
	<div class="modal-dialog modelContainer">
		{* {assign var=HEADER_TITLE value={vtranslate('LBL_UPLOAD_TO_JOFORCE', $MODULE)}} *}
		{assign var=HEADER_TITLE value={vtranslate('LBL_NEW_DOCUMENT', $MODULE)}}
		<div class="modal-content" style="">
			<form id="uploadDoc" class="form-horizontal recordEditView" name="upload" method="post" action="{$SITEURL}index.php">
				{include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
				<div class="modal-body">
					<div class="uploadview-content container-fluid">
						<div class="uploadcontrols ">
							<div id="upload" data-filelocationtype="I">
								{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
									<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
								{/if}
								<input type="hidden" name="module" value="{$MODULE}" />
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

								<input type="hidden" name="max_upload_limit" value="{$MAX_UPLOAD_LIMIT_BYTES}" />
								<input type="hidden" name="max_upload_limit_mb" value="{$MAX_UPLOAD_LIMIT_MB}" />
								<div class="dragdrop-dotted drop-area file-upload-wrapper" style="padding: 8%;">
									<span class="fa fa-upload document-panel-icon"></span>
									<div style="font-size:115%;">
										{vtranslate('LBL_DRAG_&_DROP_FILE_HERE', 'Documents')}
									</div>
									{assign var=FIELD_MODEL value=$FIELD_MODELS['filename']}
									<input style="display:none;" type="file" id="droppedFile" class="file-upload" name="{$FIELD_MODEL->getFieldName()}" value="{$FIELD_VALUE}" data-rule-required="true" />	
									<input id="file_notes_title" type="hidden" data-fieldname="notes_title" data-fieldtype="string" class="inputElement nameField" name="notes_title" value="" data-rule-required="true" aria-required="true">																	
								</div>
								<hr style="margin: 20px 0;">
								<div class="dragdrop-dotted drag-drop-solid">
									<span class="fa fa-folder document-panel-icon"></span>
									<div style="margin-top: 3%;">
										<button onclick="Documents_Index_Js.uploadTo('U','{$PARENT_MODULE}')" type="button" class="btn btn-default module-buttons">
											<img style=" width: 10%; margin-top: 2px;margin-right: 4%;" title="Joforce" alt="Joforce" src="{$SITEURL}layouts/skins/images/JoForce.png">&nbsp;{vtranslate('LBL_TO_SERVICE', 'Documents', {vtranslate('LBL_JOFORCE', 'Documents')})}
										</button>
									</div>
									<div style="margin: 1% 0;">
										<p>Click here to upload document</p>
									</div>
								</div>
								<hr style="margin: 20px 0;">
								<div class="dragdrop-dotted dragdrop-solid" style="border: 1px solid #eee;">
									<span class="fa fa-github document-panel-icon"></span>&nbsp;&nbsp;&nbsp;
									<span class="fa fa-dropbox document-panel-icon"></span>&nbsp;&nbsp;&nbsp;
									<span class="fa fa-ellipsis-h document-panel-icon"></span>
									<div style="margin-top: 3%;">
										<button onclick="Documents_Index_Js.createDocument('E','{$PARENT_MODULE}')" type="button" class="btn btn-default module-buttons">
											<span style="font-weight: normal;font-size: 12px;" class="fa fa-external-link"></span>&nbsp;{vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', $MODULE)}
										</button>
									</div>
									<div style="margin-top: 1%;">
										<p>Click here to link your document already on your server or existing online repository or file like Dropbox, Google Drive, Github</p>
									</div>
								</div>
								<hr style="margin: 20px 0;">
								<div class="dragdrop-dotted dragdrop-solid" style="border: 1px solid #eee;">
									<span class="fa fa-pencil-square-o document-panel-icon"></span>&nbsp;&nbsp;&nbsp;
									<div style="margin-top: 3%;">
										<button onclick="Documents_Index_Js.createDocument('W','{$PARENT_MODULE}')" type="button" class="btn btn-default module-buttons">
											<span style="font-weight: normal;font-size: 12px;" class="fa fa-file-text"></span>&nbsp;{vtranslate('LBL_CREATE_NEW', $MODULE_NAME, {vtranslate('SINGLE_Documents', $MODULE_NAME)})}
										</button>
									</div>
									<div style="margin-top: 1%;">
										<p>Click here to link to existing online repository or file like Dropbox, Google Drive, Github</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
			<script type="text/javascript" src="{$SITEURL}layouts/modules/Documents/resources/Documents.js"><\/script>						
		</div>
	</div>
{/strip}