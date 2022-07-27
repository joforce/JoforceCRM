{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Users/views/EditAjax.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{strip}
	<div id="massEditContainer " class="modal-dialog modelContainer {if in_array($MODULE,array('Users'))} change_pwd_user {/if}">
		{assign var=HEADER_TITLE value={vtranslate('LBL_CHANGE_PASSWORD', $MODULE)}}
		{include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
		<div class="modal-content">
			<form class="form-horizontal" id="changePassword" name="changePassword" method="post" action="{$SITEURL}index.php">
				<input type="hidden" name="module" value="{$MODULE}" />
				<input type="hidden" name="userid" value="{$USERID}" />
				<div name='massEditContent'>
					<div class="modal-body ">
						<div class="form-group">
							{if !$CURRENT_USER_MODEL->isAdminUser()}
								<label class="col-form-label fieldLabel col-sm-5 pl0 pr0">
									{vtranslate('LBL_OLD_PASSWORD', $MODULE)}&nbsp;
									<span class="red-border">*</span>
								</label>
								<div class="controls fieldValue col-sm-6 pl0">
									<input type="password" name="old_password" class="form-control inputElement" data-rule-required="true"/>
								</div>
							{/if}
						</div>

						<div class="form-group row">
							<label class="col-form-label- fieldLabel col-md-5 pull-left pl0 pr0">
								{vtranslate('LBL_NEW_PASSWORD', $MODULE)}&nbsp;
								<span class="red-border">*</span>
							</label>
							{* <div class="col-md-1 pl0 pr0"></div> *}
							<div class="controls fieldValue col-md-6 pl0">
								<input type="password" class="form-control inputElement	" name="new_password" data-rule-required="true" autofocus="autofocus"/>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label- fieldLabel col-md-5 pull-left pl0 pr0">
								{vtranslate('LBL_CONFIRM_PASSWORD', $MODULE)}&nbsp;
								<span class="red-border">*</span>
							</label>
							{* <div class="col-md-1 pl0 pr0"></div> *}
							<div class="controls fieldValue col-md-6 pl0">
								<input type="password" class="form-control inputElement	" name="confirm_password" data-rule-required="true"/>
							</div>
						</div>
					</div>
				</div>
				{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
			</form>
		</div>
	</div>
{/strip}
