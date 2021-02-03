{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Settings/Profiles/views/Edit.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="editViewPageDiv">
		<form class="form-horizontal" id="EditView" name="EditProfile" method="post" action="index.php" enctype="multipart/form-data">
			<div class="editViewHeader">
				{if $RECORD_MODEL->getId()}
					<h4>
						{vtranslate('LBL_EDIT_PROFILE', $QUALIFIED_MODULE)}
					</h4>
				{else}
					<h4>
						{vtranslate('LBL_CREATE_PROFILE', $QUALIFIED_MODULE)}
					</h4>
				{/if}
			</div>
			<hr>
			<div class="editViewBody">
				<div class="editViewContents">
					<div id="submitParams">
						<input type="hidden" name="module" value="Profiles" />
						<input type="hidden" name="action" value="Save" />
						<input type="hidden" name="parent" value="Settings" />
						{assign var=RECORD_ID value=$RECORD_MODEL->getId()}
						<input type="hidden" name="record" value="{$RECORD_ID}" />
						<input type="hidden" name="mode" value="{$MODE}" />
						<input type="hidden" name="viewall" value="0" />
						<input type="hidden" name="editall" value="0" />
					</div>

					<div name='editContent'>
						<div class="row form-group pl15"><div class="col-lg-3 col-md-3 col-sm-3 control-label fieldLabel pr0 pl0" style="width: 20% !important;"> 
								<label>
									<strong>{vtranslate('LBL_PROFILE_NAME', $QUALIFIED_MODULE)}</strong>&nbsp;<span class="red-border">*</span>:&nbsp;
								</label></div>
							<div class="fieldValue col-lg-6 col-md-6 col-sm-6 pl0" style="width: 30% !important;"> 
								<input type="text" class="inputElement" style="margin-top: 13px;" name="profilename" id="profilename" value="{$RECORD_MODEL->getName()}" data-rule-required="true" />
							</div>
						</div>

						<div class="row form-group pl15"><div class="col-lg-3 col-md-3 col-sm-3 control-label fieldLabel pr0 pl0" style="width: 20% !important;"> 
								<label>
									<strong>{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}&nbsp;:&nbsp;</strong>
								</label></div>
							<div class="fieldValue col-lg-6 col-md-6 col-sm-6 pl0" style="width: 30% !important;">
								<textarea name="description" class="inputElement" id="description" style="margin-top:13px; height:50px; resize: vertical;padding:5px 8px;">{$RECORD_MODEL->getDescription()}</textarea>
							</div>
						</div>
						{include file='EditViewContents.tpl'|vtemplate_path:$QUALIFIED_MODULE}
					</div>
				</div>
			</div>
			<div class='modal-overlay-footer clearfix'>
				<div class="row clearfix">
					<div class=' textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
						<button type='submit' class='btn btn-primary saveButton' >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
						<a class='cancelLink btn btn-secondary' data-dismiss="modal" href="javascript:history.back()" type="reset">{vtranslate('LBL_CANCEL', $MODULE)}</a>
					</div>
				</div>
			</div>
		</form>
</div>
