{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is: vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
 * Contributor(s): JoForce.com
  *
 ********************************************************************************/
-->*}
{strip}
{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
    <input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
{/if}
<div name='editContent'>
    {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name=blockIterator}
		{if $BLOCK_LABEL neq 'LBL_CALENDAR_SETTINGS'}
         {if $BLOCK_FIELDS|@count gt 0}
             <div class="fieldBlockContainer ms_user_fieldBlockContainer">
                 <div>
                     <h4>{vtranslate($BLOCK_LABEL, $MODULE)}</h4>
                 </div>
                 <hr >
                 <table class="table table-borderless">
                     <div class="col-md-12 Composer">
                     {assign var=COUNTER value=0}
                     {foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
                         {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
                         {assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
                         {assign var="refrenceListCount" value=count($refrenceList)}
                         {if $FIELD_MODEL->getName() eq 'theme' or $FIELD_MODEL->getName() eq 'rowheight'}
                            <input type="hidden" name="{$FIELD_MODEL->getName()}" value="{$FIELD_MODEL->get('fieldvalue')}"/> 
                            {continue}
                         {/if}
                         {if $FIELD_MODEL->isEditable() eq true}
                             {if $FIELD_MODEL->get('uitype') eq "19"}
                                 {if $COUNTER eq '1'}
                                     <div class="col-md-3"></div><div class="col-md-3"></div></div><div class="col-md-12">
                                     {assign var=COUNTER value=0}
                                 {/if}
                             {/if}
                             {if $COUNTER eq 2}
                                 </div><div class="col-md-12 pull-left comper">
                                 {assign var=COUNTER value=1}
                             {else}
                                 {assign var=COUNTER value=$COUNTER+1}
                             {/if}
                             <div class="col-md-6 pl0 pull-left">
                             <div class="col-md-6 pull-left" style="">
                             <div class="fieldLabel user_fieldLabel">
                            
                             {if $isReferenceField eq "reference"}
                                 {if $refrenceListCount > 1}
                                     <select style="width: 140px;" class="select2 referenceModulesList">
                                        {foreach key=index item=value from=$refrenceList}
                                            <option value="{$value}">{vtranslate($value, $value)}</option>
                                        {/foreach}
                                    </select>
                                 {else}
                                     {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                 {/if}
                             {else}
                                 {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                             {/if}
                             &nbsp; {if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}
                         </div>
                         </div>
                         <div class="col-md-6 pl0 pull-left">
                         <div class="fieldValue" {if $FIELD_MODEL->getFieldDataType() eq 'boolean'} style="width:25%" {/if} {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
                             {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
                         </div>
                         </div>
                         </div>
                     {/if}
                     {/foreach}
                     {*If their are odd number of fields in edit then border top is missing so adding the check*}
                     {if $COUNTER is odd}
                         <div class="col-md-6"></div>
                         <div class="col-md-6"></div>
                     {/if}
                     </div>
                 </table>
             </div>
             <br>
         {/if}
		{/if}
     {/foreach}
</div>
