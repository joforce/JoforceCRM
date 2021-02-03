{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
 <style>
#extensionContainer{
padding-left: 10%;
padding-right: 10%; 
}
 </style>
<div class="main-container clearfix">
    <input type="hidden" name="module_name" id="module_name" class="module_name" value="{$MODULE}" />
    <input type="hidden" name="source_module_name" id="source_module_name" value="{$MODULE}">
    <input type="hidden" name="custom_view_id" id="custom_view_id" value="{$DEFAULT_CUSTOM_VIEW_ID}">
    <input type="hidden" name="picklist_name" id="picklist_name" class="picklist_name" value="{$picklist_name}" />
    <input type="hidden" name="picklist_id" id="picklist_id" class="picklist_id" value="{$picklist_id}" />

    <input type="hidden" name="total_pages" id="total_pages" value="{$page_count}" />
    <input type="hidden" name="pageStartRange" id="pageStartRange" value="{$PAGING_MODEL->getRecordStartRange()}" />
    <input type="hidden" name="pageEndRange" id="pageEndRange" value="{$PAGING_MODEL->getRecordEndRange()}" />
    <input type="hidden" name="previousPageExist" id="previousPageExist" value="{$PAGING_MODEL->isPrevPageExists()}" />
    <input type="hidden" name="nextPageExist" id="nextPageExist" value="{$PAGING_MODEL->isNextPageExists()}" />
    <input type="hidden" name="totalCount" id="totalCount" value="{$LISTVIEW_COUNT}" />
    <input type='hidden' name="pageNumber" value="{$PAGE_NUMBER}" id='pageNumber'>
    <input type='hidden' name="pageLimit" value="{$PAGING_MODEL->getPageLimit()}" id='pageLimit'>
    <input type="hidden" name="noOfEntries" value="{$LISTVIEW_ENTRIES_COUNT}" id="noOfEntries">
    <input type='hidden' name="sum_field_name" value="{$sum_field_name}" id='sum_field_name'>

    <div class = "col-sm-12 col-xs-12 content-area">
    <div class="col-lg-2 col-md-2 pull-right" style="">
        {include file="Pagination_shopview.tpl"|vtemplate_path:"Products" SHOWPAGEJUMP=true}
    </div>
	<input type="hidden" name="module_name" id="module_name" class="module_name" value="{$MODULE}">
	<input type="hidden" name="picklist_name" id="picklist_name" class="picklist_name" value="{$picklist_name}">
	<input type="hidden" name="picklist_id" id="picklist_id" class="picklist_id" value="{$picklist_id}">
			<div class="col-sm-12 col-xs-12" id="extensionContainer">
            <div class="row">
				    {foreach item=RECORD key=record_id from=$RECORDS}
					{assign var=recordModel value=Head_Record_Model::getInstanceById($record_id, $MODULE)}
					{assign var=IMAGE_DETAILS $recordModel->getImageDetails()}
					        	 <a href="{$RECORD->getDetailViewUrl()}">
                                <div class="col-lg-4 col-md-6 col-sm-6" style="margin-bottom:30px;">
                                    <div class="extension_container extensionWidgetContainer" style="padding:15px;border: 15px solid white;box-shadow: 1px 1px 10px 5px #E0E0E0;">
                                    <div class="">
                                    <ul id="imageContainer">
									{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
										{if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
											<ul>
												<img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="400" height="300" />
											</ul>
										{/if}
									{/foreach}
								</ul> 
                                
                                <div class="product_attributes">

										<h4>{$recordModel->get('productname')}</h4>
                                         {assign var=CURRENT_USER_MODEL value=Users_Record_Model::getCurrentUserModel()}
                                        {assign var=SYMBOL_PLACEMENT value=$CURRENT_USER_MODEL->get('currency_symbol_placement')}
                                        {assign var=CURRENCY_INFO value=getCurrencySymbolandCRate($CURRENT_USER_MODEL->get('currency_id'))}
                                        {assign var=CURRENCY_SYMBOL value=$CURRENCY_INFO['symbol']}
                                        {if $SYMBOL_PLACEMENT eq '$1.0'}
                                            {$CURRENCY_SYMBOL}&nbsp;<span class="currencyValue">{round($recordModel->get('unit_price'))}</span>
                                        {else}
                                            <span class="currencyValue">{$recordModel->get('unit_price')}</span>&nbsp;{$CURRENCY_SYMBOL}
                                        {/if}
                                </div>
                                
                                </div>
                    		</div>
                            </div>
                            </a>
				    {/foreach}
			</div>
			</div>
    </div>
</div>
