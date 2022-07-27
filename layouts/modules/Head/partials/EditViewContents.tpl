{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
-->*}
{strip}
    	{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
	    <input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
    	{/if}

    	<div name='editContent'>
	    {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name=blockIterator}
	    	{if $BLOCK_FIELDS|@count gt 0}
		    <div class='fieldBlockContainer {if in_array($MODULE,array('Settings:Webhooks'))} webhooks_edit_view_page_style {/if}'>
		    	<h4 class='fieldBlockHeader mb20'>
				<b>{vtranslate($BLOCK_LABEL, $MODULE)}</b>
				</h4>
		    	<table class="table table-borderless">
			    <div class="col-lg-12 col-sm-12 pr0 pl0 m20 pull-left">
			    	{assign var=COUNTER value=0}
				{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
				    {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
				    {assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
				    {assign var="refrenceListCount" value=count($refrenceList)}
				    {if $FIELD_MODEL->isEditable() eq true}
					{if $FIELD_MODEL->get('uitype') eq "19"}
					    {if $COUNTER eq '1'}
						<div class="col-lg-3"></div>
						<div class="col-lg-3"></div>
						</div>
						<div class="col-lg-12 pull-left">
						{assign var=COUNTER value=0}
					    {/if}
					{/if}
					{if $COUNTER eq 2}
					    </div>
						<div class="col-lg-12 pr0 pl0 m20 pull-left">
					    {assign var=COUNTER value=1}
					{else}
					    {assign var=COUNTER value=$COUNTER+1}
					{/if}
					<div class="col-lg-6 col-sm-12 col-md-6 pull-left pr0 pl0 row-with-column pull-left {$MODULE}">
                                            <div class="{if $FIELD_MODEL->get('label') eq 'Purchase Cost'}col-lg-7 col-sm-7 col-md-7 pr0 label-column{else}col-lg-7 col-sm-7 col-md-7 pr0 pl0 label-column{/if}">
											
                                            	<div class=" {if $FIELD_MODEL->get('label') eq 'Fields'}new-fieldLabel alignMiddle {elseif $FIELD_MODEL->get('label') eq 'Events'} new-fieldLabel alignMiddle {else} fieldLabel alignMiddle {$isReferenceField}{if $FIELD_MODEL->get('uitype') == 56}bool{/if} {/if}">
												
						    <span class="inline-label">
							
							{if $FIELD_MODEL->get('label') neq 'File Name' && $FIELD_MODEL->get('label') neq 'Download Type' && $FIELD_MODEL->get('label') neq 'Note'}
						    {if $isReferenceField eq "reference"}
							{if $refrenceListCount > 1}
							    {assign var="DISPLAYID" value=$FIELD_MODEL->get('fieldvalue')}
							    {assign var="REFERENCED_MODULE_STRUCTURE" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($DISPLAYID)}
							    {if !empty($REFERENCED_MODULE_STRUCTURE)}
								{assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCTURE->get('name')}
							    {/if}
							    <select style="" class="select2 referenceModulesList">
								{foreach key=index item=value from=$refrenceList}
								    <option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if}>{vtranslate($value, $value)}</option>
								{/foreach}
							    </select>
							{else}
							    {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
							{/if}
						    {else if $FIELD_MODEL->get('uitype') eq "83"}
							{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE) COUNTER=$COUNTER MODULE=$MODULE}
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
							{if $MODULE eq 'Documents' && $FIELD_MODEL->get('label') eq 'File Name'}
								
							    {* 	{assign var=FILE_LOCATION_TYPE_FIELD value=$RECORD_STRUCTURE['LBL_FILE_INFORMATION']['filelocationtype']} *}
							    {if $FILE_LOCATION_TYPE_FIELD}
								{if $FILE_LOCATION_TYPE_FIELD->get('fieldvalue') eq 'E'}
								    {vtranslate("LBL_FILE_URL", $MODULE)}&nbsp;<span class="red-border">*</span>
								{else}
								    {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
								{/if}
							    {else}
								{vtranslate($FIELD_MODEL->get('label'), $MODULE)}
							    {/if}
							{else}
							    {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
							{/if}
						    {/if}
						    {if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}
						{/if}
						    </span>
						</div>
					    </div>
					    {if $FIELD_MODEL->get('uitype') neq '83'}
						<div class="{if $FIELD_MODEL->get('label') eq 'Purchase Cost'}col-lg-10 col-sm-10 col-md-10 pr0 value-column{else}col-lg-10 col-sm-10 col-md-10 pl0 pr0 value-column{/if}">
                                                    <div class="fieldValue"  {if $FIELD_MODEL->getFieldDataType() eq 'boolean'} style="width:25%" {/if} {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
                                                    {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
                                                    </div>
						</div>
					    {/if}
					</div>
				    {/if}
				{/foreach}
				{*If their are odd number of fields in edit then border top is missing so adding the check*}
				{if $COUNTER is odd}
				    <div class="col-lg-3"></div>
                                    <div class="col-lg-3"></div>
				{/if}
			    </tr>
			</table>
		    </div>
		{/if}
	    {/foreach}
	</div>
{/strip}
