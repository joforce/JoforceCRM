{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Head/views/Import.php *}

{strip}
	<div class='fc-overlay-modal modal-content'>
		<div class="overlayHeader">
			{*{assign var=TITLE value="{'LBL_IMPORT'|@vtranslate:$MODULE} {$FOR_MODULE|@vtranslate:$FOR_MODULE}"}
			{include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$TITLE}*}
		</div>
		<div class="importview-content">
			<form onsubmit="" action="index.php" enctype="multipart/form-data" method="POST" name="importBasic" id="importBasic">
				<input type="hidden" name="module" value="{$FOR_MODULE}" />
				<input type="hidden" name="view" value="Import" />
				<input type="hidden" name="mode" value="uploadAndParse" />
				<input type="hidden" id="auto_merge" name="auto_merge" value="0"/>
				<div class='modal-body' id ="importContainer">
					{assign var=LABELS value=[]}
					{if $FORMAT eq 'vcf'}
						{$LABELS["step1"] = array('LBL_UPLOAD_VCF', 'fa fa-file-text-o')}
					{else if $FORMAT eq 'ics'}
						{$LABELS["step1"] = array('LBL_UPLOAD_ICS', 'fa fa-file-text-o')}
					{else}
						{$LABELS["step1"] = array('LBL_UPLOAD_CSV', 'fa fa-file-text-o')}
					{/if}

					{if $FORMAT neq 'ics'}
						{if $DUPLICATE_HANDLING_NOT_SUPPORTED eq 'true'}
							{$LABELS["step3"] = array('LBL_FIELD_MAPPING', 'fa fa-chain')}
						{else}
							{$LABELS["step2"] = array('LBL_DUPLICATE_HANDLING', 'fa fa-files-o')}
							{$LABELS["step3"] = array('LBL_FIELD_MAPPING', 'fa fa-chain')}
						{/if}
					{/if}
					{$LABELS["step4"] = array('LBL_IMPORT_SUMMARY', 'fa fa-list-alt')}
					{include file="BreadCrumbs.tpl"|vtemplate_path:$MODULE BREADCRUMB_ID='navigation_links' ACTIVESTEP=1 BREADCRUMB_LABELS=$LABELS MODULE=$MODULE}
					{include file='ImportStepOne.tpl'|@vtemplate_path:'Import'}

					{if $FORMAT neq 'ics'}
						{include file='ImportStepTwo.tpl'|@vtemplate_path:'Import'}
					{/if}
					{if $FORMAT neq 'ics'}
						<div class = "importBlockContainer hide" id="importStep3Conatiner">
						</div>
						<div class = "importBlockContainer hide" id="importStep4Conatiner">
                                                </div>
					{/if}
				</div>
			</form>
		</div>
		<div class='modal-overlay-footer border1px clearfix'>
			<div class="row clearfix">
				<div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
					{if $FORMAT eq 'ics'}
						<button type="submit" name="import" id="importButton" class="btn btn-success btn-lg" onclick="return Calendar_Edit_Js.uploadAndParse();">{vtranslate('LBL_IMPORT_BUTTON_LABEL', $MODULE)}</button>
						&nbsp;&nbsp;&nbsp;<a class="cancelLink btn btn-danger" data-dismiss="modal" href="#">{vtranslate('LBL_CANCEL', $MODULE)}</a>
					{else}
						<div id="importStepOneButtonsDiv">
							{if $DUPLICATE_HANDLING_NOT_SUPPORTED eq 'true'}
								<button class="btn btn-success btn-lg" id="skipDuplicateMerge" onclick="Head_Import_Js.not_dup_uploadAndParse('0');">{vtranslate('LBL_NEXT_BUTTON_LABEL', $MODULE)}</button>
							{else}
								<button class="btn btn-primary btn-lg" id ="importStep2" onclick="Head_Import_Js.importActionStep2();">{vtranslate('LBL_NEXT_BUTTON_LABEL', $MODULE)}</button>
							{/if}
							&nbsp;&nbsp;&nbsp;
							{* <a class='cancelLink' onclick="Head_Import_Js.loadListRecords();" data-dismiss="modal" href="#">{vtranslate('LBL_CANCEL', $MODULE)}</a> *}
							<a class='cancelLink btn btn-danger' href="javascript:history.back()" type="reset">{vtranslate('LBL_CANCEL', $MODULE)}</a>
						</div>
						<div id="importStepTwoButtonsDiv" class = "hide">
							<button class="btn btn-default btn-lg" id="backToStep1" onclick="Head_Import_Js.bactToStep1();">{vtranslate('LBL_BACK', $MODULE)}</button>
							&nbsp;&nbsp;&nbsp;<button name="next" class="btn btn-success btn-lg" id="uploadAndParse" onclick="Head_Import_Js.uploadAndParse('1');">{vtranslate('LBL_NEXT_BUTTON_LABEL', $MODULE)}</button>
							&nbsp;&nbsp;&nbsp;<button class="btn btn-primary btn-lg" id="skipDuplicateMerge" onclick="Head_Import_Js.uploadAndParse('0');">{vtranslate('Skip this step', $MODULE)}</button>
							&nbsp;&nbsp;&nbsp;<a class='cancelLink btn btn-danger' href="javascript:history.back()" type="reset">{vtranslate('LBL_CANCEL', $MODULE)}</a>
						</div>
						<div id="importStepThreeButtonsDiv" class = "hide">
								<button type="submit" name="import" id="importButton" class="btn btn-success btn-lg" onclick="return Head_Import_Js.sanitizeAndSubmit()"
										>{'LBL_IMPORT_BUTTON_LABEL'|@vtranslate:$MODULE}</button>
								&nbsp;&nbsp;&nbsp;<a class='cancelLink btn btn-danger' href="javascript:history.back()" type="reset">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                				</div>
						<div id="importStepFourButtonsDiv" class = "hide">
						    <div class="row clearfix">
							<div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 ' style="display:flex;justify-content:center">
							    {* <button name="next" class="btn btn-primary btn-lg" onclick="return Head_Import_Js.triggerImportAction();">{'LBL_IMPORT_MORE'|@vtranslate:$MODULE}</button>*}
							    <a type="button" name="next" class="btn btn-primary btn-lg" href="{$SITEURL}{$FOR_MODULE}/view/Import">{'LBL_IMPORT_MORE'|@vtranslate:$MODULE}</a>
							    {if $MERGE_ENABLED eq '0'}
							    	<button name="next" class="btn btn-danger btn-lg" onclick="Head_Import_Js.undoImport('index.php?module={$FOR_MODULE}&view=Import&mode=undoImport&foruser={$OWNER_ID}')">{'LBL_UNDO_LAST_IMPORT'|@vtranslate:$MODULE}</button>
							    {/if}
							    <button class='btn btn-success btn-lg cancelLink' data-dismiss="modal" onclick="Head_Import_Js.loadListRecords();">{vtranslate('LBL_FINISH', $MODULE)}</button>
							</div>
						    </div>
						</div>
					{/if}
				</div>
			</div>
		</div>
	</div>
{/strip}
