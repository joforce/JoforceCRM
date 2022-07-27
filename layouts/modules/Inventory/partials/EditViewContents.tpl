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
<div name='editContent editsviews'>
    {foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE name=blockIterator}
        {if $BLOCK_LABEL eq 'LBL_ITEM_DETAILS'}{continue}{/if}
         {if $BLOCK_FIELDS|@count gt 0}
             <div class='fieldBlockContainer p20'>
                     <h4 class='fieldBlockHeader p15'><b>{vtranslate($BLOCK_LABEL, $MODULE)}</b></h4>
                 <table class="table table-borderless {if $BLOCK_LABEL eq 'LBL_ADDRESS_INFORMATION'} addressBlock{/if}">
                     {if ($BLOCK_LABEL eq 'LBL_ADDRESS_INFORMATION') and ($MODULE neq 'PurchaseOrder')}
                        <div class="col-lg-12">
                            <div class="col-lg-6 pl0 pr0">
                            <div class="col-lg-5 pl0 pr0">
                            <div class="fieldLabel " name="copyHeader1" style="border-bottom: none;">
                                <label  name="togglingHeader">{vtranslate('LBL_BILLING_ADDRESS_FROM', $MODULE)}</label>
                            </div>
                            </div>
                            <div class="col-lg-7 pl0 pr0">
                            <div class="fieldValue mt20" name="copyAddress1">
                                <div class="form-check mr20" style="display: inline;">
                                    <label>
                                        <input type="radio" name="copyAddressFromRight" class="accountAddress" data-copy-address="billing" checked="checked">
                                        &nbsp;{vtranslate('SINGLE_Accounts', $MODULE)}
                                    </label>
                                </div>
                                <div class="form-check mr20" style="display: inline;">
                                    <label> 
                                        {if $MODULE eq 'Quotes'}
                                            <input type="radio" name="copyAddressFromRight" class="contactAddress" data-copy-address="billing" checked="checked">
                                            &nbsp;{vtranslate('Related To', $MODULE)}
                                        {else}
                                            <input type="radio" name="copyAddressFromRight" class="contactAddress" data-copy-address="billing" checked="checked">
                                            &nbsp;{vtranslate('SINGLE_Contacts', $MODULE)}
                                        {/if}
                                    </label>
                                </div>
                                <div class="form-check mr20" name="togglingAddressContainerRight" style="display: inline;">
                                    <label>
                                        <input type="radio" name="copyAddressFromRight" class="shippingAddress" data-target="shipping" checked="checked">
                                        &nbsp;{vtranslate('Shipping Address', $MODULE)}
                                    </label>
                                </div>
                                <div class="form-check hide mr20" name="togglingAddressContainerLeft" style="display: inline;">
                                    <label>
                                        <input type="radio" name="copyAddressFromRight"  class="billingAddress" data-target="billing" checked="checked">
                                        &nbsp;{vtranslate('Billing Address', $MODULE)}
                                    </label>
                                </div>
                            </div>
                            </div>
                            </div>
                            <div class="col-lg-6 pl0 pr0">
                            <div class="col-lg-5 pl0 pr0">
                            <div class="fieldLabel " name="copyHeader2" style="border-bottom: none;">
                                <label  name="togglingHeader">{vtranslate('LBL_SHIPPING_ADDRESS_FROM', $MODULE)}</label>
                            </div>
                            </div>
                            <div class="col-lg-7 pl0 pr0">
                            <div class="fieldValue mt20" name="copyAddress2">
                                <div class="form-check mr20" style="display: inline;">
                                    <label>
                                        <input type="radio" name="copyAddressFromLeft" class="accountAddress" data-copy-address="shipping" checked="checked">
                                        &nbsp;{vtranslate('SINGLE_Accounts', $MODULE)}
                                    </label>
                                </div>
                                <div class="form-check mr20" style="display: inline;">
                                    <label>
                                        {if $MODULE eq 'Quotes'}
                                            <input type="radio" name="copyAddressFromLeft" class="contactAddress" data-copy-address="shipping" checked="checked">
                                            &nbsp;{vtranslate('Related To', $MODULE)}
                                        {else}
                                            <input type="radio" name="copyAddressFromLeft" class="contactAddress" data-copy-address="shipping" checked="checked">
                                            &nbsp;{vtranslate('SINGLE_Contacts', $MODULE)}	
                                        {/if}
                                    </label>
                                </div>
                                <div class="form-check mr20" name="togglingAddressContainerLeft" style="display: inline;"> 
                                    <label>
                                        <input type="radio" name="copyAddressFromLeft" class="billingAddress" data-target="billing" checked="checked">
                                        &nbsp;{vtranslate('Billing Address', $MODULE)}
                                    </label>
                                </div>
                                <div class="form-check hide mr20" name="togglingAddressContainerRight" style="display: inline;">
                                    <label>
                                        <input type="radio" name="copyAddressFromLeft" class="shippingAddress" data-target="shipping" checked="checked">
                                        &nbsp;{vtranslate('Shipping Address', $MODULE)}
                                    </label>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    {/if}
                     <div class="col-lg-12 pr0 pl0 m20 pull-left">
                     {assign var=COUNTER value=0}
                     {foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
                         {assign var="isReferenceField" value=$FIELD_MODEL->getFieldDataType()}
                         {assign var="refrenceList" value=$FIELD_MODEL->getReferenceList()}
                         {assign var="refrenceListCount" value=count($refrenceList)}
                         {if $FIELD_MODEL->isEditable() eq true}
                             {if $FIELD_MODEL->get('uitype') eq "19"}
                                 {if $COUNTER eq '1'}
                                     <div class="col-lg-3"></div><div class="col-lg-3"></div></div><div class="col-lg-12">
                                     {assign var=COUNTER value=0}
                                 {/if}
                             {/if}
                             {if $COUNTER eq 2}
                                 </div><div class="col-lg-12 pr0 pl0 m20 pull-left">
                                 {assign var=COUNTER value=1}
                             {else}
                                 {assign var=COUNTER value=$COUNTER+1}
                             {/if}
                            <div class="col-lg-6 col-sm-12 col-md-6 pull-left pr0 pl0 row-with-column pull-left">
                            <div class="col-lg-7 col-sm-7 col-md-7 pr0 pl0 label-column {$MODULE} {if in_array($MODULE,array('SalesOrder','PurchaseOrder','Potentials'))} SalesOrder_page_label {/if}">
                             <div class="fieldLabel alignMiddle {$isReferenceField} "><span class="inline-label">
                             {if $FIELD_MODEL->isMandatory() eq true} <span class="red-border">*</span> {/if}
                             {if $isReferenceField eq "reference"}
                                 {if $refrenceListCount > 1}
                                     {assign var="REFERENCED_MODULE_ID" value=$FIELD_MODEL->get('fieldvalue')}
                                     {assign var="REFERENCED_MODULE_STRUCTURE" value=$FIELD_MODEL->getUITypeModel()->getReferenceModule($REFERENCED_MODULE_ID)}
                                     {if !empty($REFERENCED_MODULE_STRUCTURE)}
                                        {assign var="REFERENCED_MODULE_NAME" value=$REFERENCED_MODULE_STRUCTURE->get('name')}
                                     {/if}
                                     <select style="width: 140px;" class="select2 referenceModulesList">
                                        {foreach key=index item=value from=$refrenceList}
                                            <option value="{$value}" {if $value eq $REFERENCED_MODULE_NAME} selected {/if}>{vtranslate($value, $value)}</option>
                                        {/foreach}
                                    </select>
                                 {else}
                                     {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                                 {/if}
                             {else}
                                 {vtranslate($FIELD_MODEL->get('label'), $MODULE)}
                             {/if}
                             
                             </span>
                         </div>
                         </div>
                         <div class="col-lg-10 col-sm-10 col-md-10 pl0 pr0 value-column">
                         <div class="fieldValue" {if $FIELD_MODEL->getFieldDataType() eq 'boolean'} style="width:25%" {/if} {if $FIELD_MODEL->get('uitype') eq '19'} colspan="3" {assign var=COUNTER value=$COUNTER+1} {/if}>
                             {if $FIELD_MODEL->getFieldDataType() eq 'image' || $FIELD_MODEL->getFieldDataType() eq 'file'}
                                 <div class='col-lg-4 col-md-4 redColor'>
                                     {vtranslate('LBL_NOTE_EXISTING_ATTACHMENTS_WILL_BE_REPLACED', $MODULE)}
                                 </div>
                             {/if}
                             {include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE)}
                         </div>
                         </div>
                         </div>
                     {/if}
                     {/foreach}
                     {*If their are odd number of fields in edit then border top is missing so adding the check*}
                     {if $COUNTER is odd}
                         <div class="col-lg-3"></div>
                         <div class="col-lg-3"></div>
                     {/if}
                     </div>
                 </table>
             </div>
         {/if}
     {/foreach}
</div>
{include file="partials/LineItemsEdit.tpl"|@vtemplate_path:'Inventory'}
