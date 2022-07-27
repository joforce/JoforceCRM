{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{strip}
	{foreach key=index item=jsModel from=$SCRIPTS}
		<script type="{$jsModel->getType()}" src="{$SITEURL}{$jsModel->getSrc()}"></script>
	{/foreach}
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form class="form-horizontal recordEditView" id="QuickCreate" name="QuickCreate" method="post" action="index.php">
				{if $MODE eq 'edit' && !empty($RECORD_ID)}
					{assign var=HEADER_TITLE value={vtranslate('LBL_EDITING', $MODULE)}|cat:" "|cat:{vtranslate('SINGLE_'|cat:$MODULE, $MODULE)}}
				{else}
					{assign var=HEADER_TITLE value={vtranslate('LBL_QUICK_CREATE', $MODULE)}|cat:" "|cat:{vtranslate('SINGLE_'|cat:$MODULE, $MODULE)}}
				{/if}
				{include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}

				<div class="modal-body">
					{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
						<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
					{/if}
					<input type="hidden" name="module" value="{$MODULE}">
					<input type="hidden" name="action" value="SaveAjax">
					<input type="hidden" name="calendarModule" value="{$MODULE}">
					<input type="hidden" name="defaultCallDuration" value="{$USER_MODEL->get('callduration')}" />
					<input type="hidden" name="defaultOtherEventDuration" value="{$USER_MODEL->get('othereventduration')}" />
					{if $MODE eq 'edit' && !empty($RECORD_ID)}
						<input type="hidden" name="record" value="{$RECORD_ID}" />
						<input type="hidden" name="mode" value="{$MODE}" />
					{else}
						<input type="hidden" name="record" value="">
					{/if}

					{assign var="RECORD_STRUCTURE_MODEL" value=$QUICK_CREATE_CONTENTS[$MODULE]['recordStructureModel']}
					{assign var="RECORD_STRUCTURE" value=$QUICK_CREATE_CONTENTS[$MODULE]['recordStructure']}
					{assign var="MODULE_MODEL" value=$QUICK_CREATE_CONTENTS[$MODULE]['moduleModel']}

					<div class="quickCreateContent calendarQuickCreateContent" style="padding-top:2%;padding-bottom:5%;">
						{if $MODULE eq 'Calendar'}
							{if !empty($PICKIST_DEPENDENCY_DATASOURCE_TODO)}
								<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE_TODO)}' />
							{/if}
						{else}
							{if !empty($PICKIST_DEPENDENCY_DATASOURCE_EVENT)}
								<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE_EVENT)}' />
							{/if}
						{/if}

						<div>
							{assign var="FIELD_MODEL" value=$RECORD_STRUCTURE['subject']}
							<div style="margin-left: 14px;width: 95%;">
								{assign var="FIELD_INFO" value=$FIELD_MODEL->getFieldInfo()}
								{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
								<input id="{$MODULE}_editView_fieldName_{$FIELD_MODEL->get('name')}" type="text" class="inputElement {if $FIELD_MODEL->isNameField()}nameField{/if}" name="{$FIELD_MODEL->getFieldName()}" value="{$FIELD_MODEL->get('fieldvalue')}"
									   {if $FIELD_MODEL->get('uitype') eq '3' || $FIELD_MODEL->get('uitype') eq '4'|| $FIELD_MODEL->isReadOnly()} readonly {/if} {if !empty($SPECIAL_VALIDATOR)}data-validator="{Zend_Json::encode($SPECIAL_VALIDATOR)}"{/if}  
									   {if $FIELD_INFO["mandatory"] eq true} data-rule-required="true" {/if}
									   {foreach item=VALIDATOR from=$FIELD_INFO["validator"]}
										   {assign var=VALIDATOR_NAME value=$VALIDATOR["name"]}
										   data-rule-{$VALIDATOR_NAME} = "true" 
									   {/foreach}
									   placeholder="{vtranslate($FIELD_MODEL->get('label'), $MODULE)}" style="width: 100%;"/>
							</div>
						</div>

						<div class="row" style="padding-top: 2%;">
							<div class="col-sm-12">
								<div class="col-sm-5 pull-left">
									{assign var="FIELD_MODEL" value=$RECORD_STRUCTURE['date_start']}
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
								</div>
								<div class="muted col-sm-2 pull-left" style="line-height: 67px;left: 20px; padding-right: 7%;">
									{vtranslate('LBL_TO',$MODULE)}
								</div>
								<div class="col-sm-5 pull-left" {if $MODULE eq 'Calendar'}style="margin-top: 4%;"{/if}>
									{assign var="FIELD_MODEL" value=$RECORD_STRUCTURE['due_date']}
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
								</div>
							</div>
						</div>

						<table class="massEditTable table no-border">
							<div class="col-lg-12">
								{foreach key=FIELD_NAME item=FIELD_MODEL from=$RECORD_STRUCTURE name=blockfields}
									{if $FIELD_NAME eq 'subject' || $FIELD_NAME eq 'date_start' || $FIELD_NAME eq 'due_date'}
									</div>{continue}
								{/if}
								{assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
								{assign var="referenceList" value=$FIELD_MODEL->getReferenceList()}
								{assign var="referenceListCount" value=count($referenceList)}
								{if $FIELD_MODEL->get('uitype') eq "19"}
									{if $COUNTER eq '1'}
										<div class="col-lg-6"></div><div class="col-lg-6"></div></div><div class="col-lg-12">
											{assign var=COUNTER value=0}
										{/if}
									{/if}
								</div><div class="col-lg-12 row">
									<div class='fieldLabel col-lg-4 pr0 pl0 pull-let' style="padding-bottom: 3px;margin-top: 37px;">
										{if $isReferenceField neq "reference"}<label class="muted">{/if}
											{if $isReferenceField eq "reference"}
												{if $referenceListCount > 1}
													{assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
													{assign var="REFERENCED_MODULE_STRUCT" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
													{if !empty($REFERENCED_MODULE_STRUCT)}
														{assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCT->get('name')}
													{/if}
													<span class="pull-right">
														<select style="width: 150px;" class="select2 referenceModulesList">
															{foreach key=index item=value from=$referenceList}
																<option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if} >{vtranslate($value, $value)}</option>
															{/foreach}
														</select>
													</span>
												{else}
													<label class="muted">{vtranslate($FIELD_MODEL->get('label'), $MODULE)} &nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}</label>
												{/if}
											{else}
												{vtranslate($FIELD_MODEL->get('label'), $MODULE)}&nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}
											{/if}
											{if $isReferenceField neq "reference"}</label>{/if}
									</div>
									<div class="fieldValue col-lg-8 pl0 pr0 pull-left" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
										{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
									</div>
								{/foreach}
							</div>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<center>
						{if $BUTTON_NAME neq null}
							{assign var=BUTTON_LABEL value=$BUTTON_NAME}
						{else}
							{assign var=BUTTON_LABEL value={vtranslate('LBL_SAVE', $MODULE)}}
						{/if}
						{assign var="CALENDAR_MODULE_MODEL" value=$QUICK_CREATE_CONTENTS['Calendar']['moduleModel']}
						{assign var="EDIT_VIEW_URL" value=$CALENDAR_MODULE_MODEL->getCreateTaskRecordUrl()}
						{if $MODULE eq 'Events'}
							{assign var="EDIT_VIEW_URL" value=$CALENDAR_MODULE_MODEL->getCreateEventRecordUrl()}
						{/if}
						<a class="btn btn-success" id="goToFullForm" href="{$EDIT_VIEW_URL}" type=""><strong>{vtranslate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></a>
						<button {if $BUTTON_ID neq null} id="{$BUTTON_ID}" {/if} class="btn btn-primary" type="submit" name="saveButton"><strong>{$BUTTON_LABEL}</strong></button>
						<a href="#" class="cancelLink btn btn-danger" type="" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
					</center>
				</div>
			</form>
		</div>
		{if $FIELDS_INFO neq null}
			<script type="text/javascript">
				var quickcreate_uimeta = (function () {
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
									return fieldInfo[name].type;
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
