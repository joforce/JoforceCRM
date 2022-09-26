{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
    {assign var=CURRENCY_MODEL_EXISTS value=true}
    {assign var=CURRENCY_ID value=$RECORD_MODEL->getId()}
    {if empty($CURRENCY_ID)}
        {assign var=CURRENCY_MODEL_EXISTS value=false}
    {/if}
    <div class="currencyModalContainer modal-dialog modelContainer {$MODULE}">
        {if $CURRENCY_MODEL_EXISTS}
            {assign var="HEADER_TITLE" value={vtranslate('LBL_EDIT_CURRENCY', $QUALIFIED_MODULE)}}
        {else}
            {assign var="HEADER_TITLE" value={vtranslate('LBL_ADD_NEW_CURRENCY', $QUALIFIED_MODULE)}}
        {/if}
        
        <div class="modal-content">
            <form id="editCurrency" class="form-horizontal" method="POST">
                <input type="hidden" name="record" value="{$CURRENCY_ID}" />
                {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
                <div class="modal-body">
                    <div class="row-fluid">
                        <div class="form-group row">
                            <label class="col-form-label fieldLabel col-md-6 pl0 pr0 pull-left">
                                {vtranslate('LBL_CURRENCY_NAME', $QUALIFIED_MODULE)}&nbsp;<span class="red-border">*</span>
                            </label>
                            <div class="controls fieldValue col-md-6 pl0">
                                <select class="select2 inputElement" name="currency_name">
                                    {foreach key=CURRENCY_ID item=CURRENCY_MODEL from=$ALL_CURRENCIES name=currencyIterator}
                                        {if !$CURRENCY_MODEL_EXISTS && $smarty.foreach.currencyIterator.first}
                                            {assign var=RECORD_MODEL value=$CURRENCY_MODEL}
                                        {/if}
                                        <option value="{$CURRENCY_MODEL->get('currency_name')}" data-code="{$CURRENCY_MODEL->get('currency_code')}" 
                                                data-symbol="{$CURRENCY_MODEL->get('currency_symbol')}" {if $RECORD_MODEL->get('currency_name') == $CURRENCY_MODEL->get('currency_name')} selected {/if}>
                                            {vtranslate($CURRENCY_MODEL->get('currency_name'), $QUALIFIED_MODULE)}&nbsp;({$CURRENCY_MODEL->get('currency_symbol')})</option>
                                        {/foreach}
                                </select>
                            </div>	
                        </div>
                        <div class="form-group row">
                        <label class="col-form-label fieldLabel col-md-6 pl0 pr0 pull-left">{vtranslate('LBL_CURRENCY_CODE', $QUALIFIED_MODULE)}&nbsp;<span class="red-border">*</span></label>
                            <div class="controls fieldValue col-md-6 pl0">
                                <input type="text" class="inputElement bgBlack cursorPointerNotAllowed" name="currency_code" readonly value="{$RECORD_MODEL->get('currency_code')}" data-rule-required = "true" />
                            </div>	
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label fieldLabel col-md-6 pull-left pl0 pr0">{vtranslate('LBL_CURRENCY_SYMBOL', $QUALIFIED_MODULE)}&nbsp;<span class="red-border">*</span></label>
                            <div class="controls fieldValue col-md-6 pl0">
                                <input type="text"  class="inputElement bgBlack cursorPointerNotAllowed" name="currency_symbol" readonly value="{$RECORD_MODEL->get('currency_symbol')}" data-rule-required = "true" />
                            </div>	
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label fieldLabel col-md-6 pull-left pl0 pr0">{vtranslate('LBL_CONVERSION_RATE', $QUALIFIED_MODULE)}&nbsp;<span class="red-border">*</span></label>
                            <div class="controls fieldValue col-md-6 pl0">
                                <input type="text" class="inputElement" name="conversion_rate" data-rule-required = "true" data-rule-positive ="true" data-rule-greater_than_zero = "true" placeholder="{vtranslate('LBL_ENTER_CONVERSION_RATE', $QUALIFIED_MODULE)}" 
                                       value="{$RECORD_MODEL->get('conversion_rate')}"/>
                                <br><span class="muted">({vtranslate('LBL_BASE_CURRENCY', $QUALIFIED_MODULE)} - {$BASE_CURRENCY_MODEL->get('currency_name')})</span>
                            </div>	
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label fieldLabel col-md-6 pl0 pr0">{vtranslate('LBL_STATUS', $QUALIFIED_MODULE)}</label>
                            <div class="controls fieldValue col-md-6 pl0">
                                <label class="checkbox pl-0">
                                    <input type="hidden" name="currency_status" value="Inactive" />
                                    <input type="checkbox" name="currency_status" value="Active" class="currencyStatus alignBottom" 
                                    {if !$CURRENCY_MODEL_EXISTS} checked {else}{$RECORD_MODEL->get('currency_status')}{if $RECORD_MODEL->get('currency_status') == 'Active'} checked {/if}{/if} />
                                <span>&nbsp;{vtranslate('LBL_CURRENCY_STATUS_DESC', $QUALIFIED_MODULE)}</span>
                            </label>
                        </div>	
                    </div>
                    <div class="form-group control-group transferCurrency hide">
                        <label class="muted col-form-label fieldLabel pl0 pr0">
                            {vtranslate('LBL_TRANSFER_CURRENCY', $QUALIFIED_MODULE)}&nbsp;{vtranslate('LBL_TO', $QUALIFIED_MODULE)}</label>&nbsp;<span class="red-border">*</span>
                        <div class="controls row-fluid fieldValue pl0">
                            <select class="select2 span6" name="transform_to_id">
                                {foreach key=CURRENCY_ID item=CURRENCY_MODEL from=$OTHER_EXISTING_CURRENCIES}
                                    <option value="{$CURRENCY_ID}">{vtranslate($CURRENCY_MODEL->get('currency_name'), $QUALIFIED_MODULE)}</option>
                                {/foreach}
                            </select>
                        </div>	
                    </div>
                </div>
            </div>
            {include file='ModalFooter.tpl'|@vtemplate_path:'Head'}
        </form>
    </div>
</div>
{/strip}
