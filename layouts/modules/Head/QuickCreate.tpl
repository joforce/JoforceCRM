{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Head/views/QuickCreateAjax.php *}
    
{strip}
    {foreach key=index item=jsModel from=$SCRIPTS}
        <script type="{$jsModel->getType()}" src="{$SITEURL}{$jsModel->getSrc()}"></script>
    {/foreach}
    
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal recordEditView" id="QuickCreate" name="QuickCreate" method="post" action="{$SITEURL}index.php">
                {assign var=HEADER_TITLE value={vtranslate('LBL_QUICK_CREATE', $MODULE)}|cat:" "|cat:{vtranslate($SINGLE_MODULE, $MODULE)}}
                {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
                    
                <div class="modal-body">
                    {if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
                        <input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
                    {/if}
                    {if $MODULE eq 'Events'}
                        <input type="hidden" name="calendarModule" value="Events">
                        {if !empty($PICKIST_DEPENDENCY_DATASOURCE_EVENT)}
                            <input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE_EVENT)}' />
                        {/if}
                    {/if}
                    {if $MODULE eq 'Events'}
                        <input type="hidden" name="module" value="Calendar">
                    {else}
                        <input type="hidden" name="module" value="{$MODULE}">
                    {/if}
                    <input type="hidden" name="action" value="SaveAjax">
                    <div class="quickCreateContent">
                        <table class="massEditTable table no-border joforce-form">
			    <div class="col-lg-12">
                                {assign var=COUNTER value=0}
                                {foreach key=FIELD_NAME item=FIELD_MODEL from=$RECORD_STRUCTURE name=blockfields}
                                    {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
                                    {assign var="referenceList" value=$FIELD_MODEL->getReferenceList()}
                                    {assign var="referenceListCount" value=count($referenceList)}
                                    {if $FIELD_MODEL->get('uitype') eq "19"}
                                        {if $COUNTER eq '1'}
					    <div class="col-lg-6"></div><div class="col-lg-6"></div></div><div class="col-lg-12">
                                            {assign var=COUNTER value=0}
                                        {/if}
                                    {/if}
                                    {if $COUNTER eq 2}
                                    </div><div class="col-lg-12"> 
                                        {assign var=COUNTER value=1}
                                    {else}
                                        {assign var=COUNTER value=$COUNTER+1}
                                    {/if}
				    <div class="col-lg-6 pr0 pl0">
                                    <div class='fieldLabel'>
                                        {if $isReferenceField neq "reference"}<label class="muted">{/if}
                                            {if $isReferenceField eq "reference"}
                                                {if $referenceListCount > 1}
                                                    {assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
                                                    {assign var="REFERENCED_MODULE_STRUCT" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
                                                    {if !empty($REFERENCED_MODULE_STRUCT)}
                                                        {assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCT->get('name')}
                                                    {/if}
                                                    <span class="pull-right">
                                                        <select style="width: 100%;" class="select2 referenceModulesList {if $FIELD_MODEL->isMandatory() eq true}reference-mandatory{/if}">
                                                            {foreach key=index item=value from=$referenceList}
                                                                <option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if} >{vtranslate($value, $value)}</option>
                                                            {/foreach}
                                                        </select>
                                                    </span>
                                                {else}
                                                    <label class="muted">{vtranslate($FIELD_MODEL->get('label'), $MODULE)}&nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}</label>
                                                {/if}
                                            {else if $FIELD_MODEL->get('uitype') eq '83'}
												{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) COUNTER=$COUNTER MODULE=$MODULE PULL_RIGHT=true}
												{if $TAXCLASS_DETAILS}
                                                    {assign 'taxCount' count($TAXCLASS_DETAILS)%2}
													{if $taxCount eq 0}
                                                        {if $COUNTER eq 2}
                                                            {assign var=COUNTER value=1}
                                                        {else}
                                                            {assign var=COUNTER value=2}
                                                        {/if}
													{/if}
												{/if}
                                            {else}
                                                {vtranslate($FIELD_MODEL->get('label'), $MODULE)}&nbsp;{if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}
                                            {/if}
                                            {if $isReferenceField neq "reference"}</label>{/if}
                                    </div>
				    </div>
                                    {if $FIELD_MODEL->get('uitype') neq '83'}
					<div class="col-lg-6 pl0 pr0">
                                        <div class="fieldValue" {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
                                            {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
                                        </div>
                                        </div>
                                    {/if}
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
                        {assign var="EDIT_VIEW_URL" value=$MODULE_MODEL->getCreateRecordUrl()}
                        {* <button class="btn btn-success" id="goToFullForm" data-edit-view-url="{$EDIT_VIEW_URL}" type="button"><strong>{vtranslate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></button> *}
                        <a class="btn btn-success" id="goToFullForm" href="{$EDIT_VIEW_URL}" type="button"><strong>{vtranslate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></a>
                        <button {if $BUTTON_ID neq null} id="{$BUTTON_ID}" {/if} class="btn btn-primary" type="submit" name="saveButton"><strong>{$BUTTON_LABEL}</strong></button>
                        <a href="#" class="cancelLink btn btn-secondary" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                    </center>
                </div>
            </form>
        </div>
            {if $FIELDS_INFO neq null}
                <script type="text/javascript">
                    var quickcreate_uimeta = (function() {
                        var fieldInfo  = {$FIELDS_INFO};
                        return {
                            field: {
                                get: function(name, property) {
                                    if(name && property === undefined) {
                                        return fieldInfo[name];
                                    }
                                    if(name && property) {
                                        return fieldInfo[name][property]
                                    }
                                },
                                isMandatory : function(name){
                                    if(fieldInfo[name]) {
                                        return fieldInfo[name].mandatory;
                                    }
                                    return false;
                                },
                                getType : function(name){
                                    if(fieldInfo[name]) {
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
