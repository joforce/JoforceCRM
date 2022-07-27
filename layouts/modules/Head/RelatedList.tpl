{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{strip}
	{assign var=RELATED_MODULE_NAME value=$RELATED_MODULE->get('name')}
	{* {assign var=MODULE value=$MODULE_NAME} *}
	{include file="PicklistColorMap.tpl"|vtemplate_path:$MODULE LISTVIEW_HEADERS=$RELATED_HEADERS}
	<div class="relatedContainer  {if in_array($MODULE,array('Campaigns','PurchaseOrder','SalesOrder','Documents','Vendors','Invoice','Quotes'))} ms_relatedContainer ipad_scrn_campaigns {elseif in_array($MODULE,array('HelpDesk'))} big_scrn_details_view_ticket {elseif in_array($MODULE,array('Products'))} big_scrn_details_view_product {elseif in_array($MODULE,array('Services'))} big_scrn_details_view_Services {elseif in_array($MODULE,array('Contacts'))} big_scrn_details_view_Contact {/if} {if in_array($MODULE,array('Potentials','Accounts'))} mac_scr_left_align {/if}">
		{assign var=IS_RELATION_FIELD_ACTIVE value="{if $RELATION_FIELD}{$RELATION_FIELD->isActiveField()}{else}false{/if}"}
		<input type="hidden" name="currentPageNum" value="{$PAGING->getCurrentPage()}" />
		<input type="hidden" name="relatedModuleName" class="relatedModuleName" value="{$RELATED_MODULE_NAME}" />
		<input type="hidden" value="{$ORDER_BY}" id="orderBy">
		<input type="hidden" value="{$SORT_ORDER}" id="sortOrder">
		<input type="hidden" value="{$RELATED_ENTIRES_COUNT}" id="noOfEntries">
		<input type='hidden' value="{$PAGING->getPageLimit()}" id='pageLimit'>
		<input type='hidden' value="{$PAGING->get('page')}" id='pageNumber'>
		<input type="hidden" value="{$PAGING->isNextPageExists()}" id="nextPageExist"/>
		<input type='hidden' value="{$TOTAL_ENTRIES}" id='totalCount'>
		<input type='hidden' value="{$TAB_LABEL}" id='tab_label' name='tab_label'>
		<input type='hidden' value="{$IS_RELATION_FIELD_ACTIVE}" id='isRelationFieldActive'>

		{include file="partials/RelatedListHeader.tpl"|vtemplate_path:$RELATED_MODULE_NAME}
		{if $MODULE eq 'Products' && $RELATED_MODULE_NAME eq 'Products' && $TAB_LABEL === 'Product Bundles' && $RELATED_LIST_LINKS}
			<div data-module="{$MODULE}" style = "margin-left:20px">
				{assign var=IS_VIEWABLE value=$PARENT_RECORD->isBundleViewable()}
				<input type="hidden" class="isShowBundles" value="{$IS_VIEWABLE}">
				<label class="showBundlesInInventory checkbox"><input type="checkbox" {if $IS_VIEWABLE}checked{/if} value="{$IS_VIEWABLE}">&nbsp;&nbsp;{vtranslate('LBL_SHOW_BUNDLE_IN_INVENTORY', $MODULE)}</label>
			</div>
		{/if}

		<div class="relatedContents col-lg-12 col-md-12 col-sm-12 table-container tablec-fixedc-columnc-outterc">
		<div class="tablec-fixedc-columnc-innerc">
				<table id="listview-table" class="table listview-table tablec-fixedc-columnc tablec-fixedc-columnc tablec tablec-borderedc tablec-stripedc">
					<thead>
						<tr class="listViewHeaders">
							<th style="min-width:100px">
								<i class="fa fa-search cursorPointer" id="joforce-table-search" style="float:right;margin-left:25px;"></i>
							</th>
							{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
								{* hide time_start,time_end columns in the list as they are merged with with Start Date and End Date fields *}
								{if $HEADER_FIELD->get('column') eq 'time_start' or $HEADER_FIELD->get('column') eq 'time_end'}
									<th class="nowrap" style="width:15px">
								{else}
									<th class="nowrap">
									    {if $COLUMN_NAME eq $HEADER_FIELD->get('column')}
										<a href="#" class="removeSorting pull-right">x</a>
									    {/if}

									    {if $HEADER_FIELD->get('column') eq "access_count" or $HEADER_FIELD->get('column') eq "idlists"}
										<a href="javascript:void(0);" class="noSorting">{vtranslate($HEADER_FIELD->get('label'), $RELATED_MODULE->get('name'))}</a>
									{else}
										<a href="javascript:void(0);" class="listViewContentHeaderValues" data-nextsortorderval="{if $COLUMN_NAME eq $HEADER_FIELD->get('column')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-fieldname="{$HEADER_FIELD->get('column')}">
											{if $COLUMN_NAME eq $HEADER_FIELD->get('column')}
												<i class="fa pull-right {$FASORT_IMAGE} aruna"></i>
											{else}
												<i class="fa {$DEFAULT_SORT} pull-right"></i>
											{/if}
											&nbsp;
											<span>{vtranslate($HEADER_FIELD->get('label'), $RELATED_MODULE->get('name'))}</span>
										</a>
									    {/if}
								{/if}
								</th>
							{/foreach}
						</tr>
						<tr class="searchRow">
							<th class="inline-search-btn">
								<button class="btn btn-primary btn-sm" data-trigger="relatedListSearch">{vtranslate("LBL_SEARCH",$MODULE)}</button>
							</th>
							{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
								<th>
									{if $HEADER_FIELD->get('column') eq 'time_start' or $HEADER_FIELD->get('column') eq 'time_end' or $HEADER_FIELD->getFieldDataType() eq 'reference'}
									{else}
										{assign var=FIELD_UI_TYPE_MODEL value=$HEADER_FIELD->getUITypeModel()}
										{include file=vtemplate_path($FIELD_UI_TYPE_MODEL->getListSearchTemplateName(),$RELATED_MODULE_NAME) FIELD_MODEL= $HEADER_FIELD SEARCH_INFO=$SEARCH_DETAILS[$HEADER_FIELD->getName()] USER_MODEL=$USER_MODEL}
										<input type="hidden" class="operatorValue" value="{$SEARCH_DETAILS[$HEADER_FIELD->getName()]['comparator']}">
									{/if}
								</th>
							{/foreach}
						</tr>
					</thead>
					{foreach item=RELATED_RECORD from=$RELATED_RECORDS}
						<tr class="listViewEntries" data-id='{$RELATED_RECORD->getId()}' 
							{if $RELATED_MODULE_NAME eq 'Calendar'}
								data-recurring-enabled='{$RELATED_RECORD->isRecurringEnabled()}'
								{assign var=DETAILVIEWPERMITTED value=isPermitted($RELATED_MODULE->get('name'), 'DetailView', $RELATED_RECORD->getId())}
								{if $DETAILVIEWPERMITTED eq 'yes'}
									data-recordUrl='{$RELATED_RECORD->getDetailViewUrl()}'
								{/if}
							{else}
								data-recordUrl='{$RELATED_RECORD->getDetailViewUrl()}'
							{/if}>
							<td class="related-list-actions">
								<span class="actionImages">&nbsp;&nbsp;&nbsp;
									{if $RELATED_MODULE_NAME eq 'PriceBooks' AND (!empty($RELATED_HEADERS['listprice']) || !empty($RELATED_HEADERS['unit_price']))}
										{if !empty($RELATED_HEADERS['listprice'])}
											{assign var="LISTPRICE" value=CurrencyField::convertToUserFormat($RELATED_RECORD->get('listprice'), null, true)}
										{/if}
									{/if}
									{if $RELATED_MODULE_NAME eq 'PriceBooks'}
										<a data-url="index.php?module=PriceBooks&view=ListPriceUpdate&record={$PARENT_RECORD->getId()}&relid={$RELATED_RECORD->getId()}&currentPrice={$LISTPRICE}" class="editListPrice cursorPointer" data-related-recordid='{$RELATED_RECORD->getId()}' data-list-price={$LISTPRICE}>
											<i class="fa fa-usd" title="{vtranslate('LBL_EDIT', $MODULE)} {vtranslate('LBL_OF_PRICE', $MODULE)}"></i>
										</a> &nbsp;&nbsp;
									{/if}
									{if $IS_EDITABLE && $RELATED_RECORD->isEditable()}
										<a name="relationEdit" data-url="{$RELATED_RECORD->getRelatedEditViewUrl()}"><i title="{vtranslate('LBL_EDIT', $MODULE)}" class="fa fa-pencil"></i></a> &nbsp;&nbsp;
									{/if}

									{if $IS_DELETABLE}
										<a class="relationDelete"><i title="{vtranslate('LBL_UNLINK', $MODULE)}" class="joicon-linkopen"></i></a>
									{/if}
								</span>

							</td>
							{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
								{assign var=RELATED_HEADERNAME value=$HEADER_FIELD->get('name')}
								{assign var=RELATED_LIST_VALUE value=$RELATED_RECORD->get($RELATED_HEADERNAME)}
								<td class="relatedListEntryValues" title="{strip_tags($RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME))}" data-field-type="{$HEADER_FIELD->getFieldDataType()}" nowrap>
									<span class="value textOverflowEllipsis">
										{if $RELATED_MODULE->get('name') eq 'Documents' && $RELATED_HEADERNAME eq 'document_source'}
											<center>{$RELATED_RECORD->get($RELATED_HEADERNAME)}</center>
											{else}
												{if $HEADER_FIELD->isNameField() eq true or $HEADER_FIELD->get('uitype') eq '4'}
												<a href="{$RELATED_RECORD->getDetailViewUrl()}">{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}</a>
											{elseif $RELATED_HEADERNAME eq 'access_count'}
												{$RELATED_RECORD->getAccessCountValue($PARENT_RECORD->getId())}
											{elseif $RELATED_HEADERNAME eq 'time_start' or $RELATED_HEADERNAME eq 'time_end'}
											{elseif $RELATED_MODULE_NAME eq 'PriceBooks' AND ($RELATED_HEADERNAME eq 'listprice' || $RELATED_HEADERNAME eq 'unit_price')}
												{if $RELATED_HEADERNAME eq 'listprice'}
													{assign var="LISTPRICE" value=CurrencyField::convertToUserFormat($RELATED_RECORD->get($RELATED_HEADERNAME), null, true)}
												{/if}
												{CurrencyField::convertToUserFormat($RELATED_RECORD->get($RELATED_HEADERNAME), null, true)}
											{elseif $HEADER_FIELD->get('uitype') eq '71' or $HEADER_FIELD->get('uitype') eq '72'}
												{assign var=CURRENCY_SYMBOL value=Head_RelationListView_Model::getCurrencySymbol($RELATED_RECORD->get('id'), $HEADER_FIELD)}
												{assign var=CURRENCY_VALUE value=CurrencyField::convertToUserFormat($RELATED_RECORD->get($RELATED_HEADERNAME))}
												{if $HEADER_FIELD->get('uitype') eq '72'}
													{assign var=CURRENCY_VALUE value=CurrencyField::convertToUserFormat($RELATED_RECORD->get($RELATED_HEADERNAME), null, true)}
												{/if}
												{if Users_Record_Model::getCurrentUserModel()->get('currency_symbol_placement') eq '$1.0'}
													{$CURRENCY_SYMBOL}{$CURRENCY_VALUE}
												{else}
													{$CURRENCY_VALUE}{$CURRENCY_SYMBOL}
												{/if}
												{if $RELATED_HEADERNAME eq 'listprice'}
													{assign var="LISTPRICE" value=CurrencyField::convertToUserFormat($RELATED_RECORD->get($RELATED_HEADERNAME), null, true)}
												{/if}
											{else if $HEADER_FIELD->getFieldDataType() eq 'picklist'}
												{if $RELATED_MODULE_NAME eq 'Calendar' or $RELATED_MODULE_NAME eq 'Events'}
													{if $RELATED_RECORD->get('activitytype') eq 'Task'}
														{assign var=PICKLIST_FIELD_ID value={$HEADER_FIELD->getId()}}
													{else}
														{if $HEADER_FIELD->getName() eq 'taskstatus'}
															{assign var="EVENT_STATUS_FIELD_MODEL" value=Head_Field_Model::getInstance('eventstatus', Head_Module_Model::getInstance('Events'))}
															{if $EVENT_STATUS_FIELD_MODEL}
																{assign var=PICKLIST_FIELD_ID value={$EVENT_STATUS_FIELD_MODEL->getId()}}
															{else} 
																{assign var=PICKLIST_FIELD_ID value={$HEADER_FIELD->getId()}}
															{/if}
														{else}
															{assign var=PICKLIST_FIELD_ID value={$HEADER_FIELD->getId()}}
														{/if}
													{/if}
												{else}
													{assign var=PICKLIST_FIELD_ID value={$HEADER_FIELD->getId()}}
												{/if}
												<span {if !empty($RELATED_LIST_VALUE)} class="picklist-color picklist-{$PICKLIST_FIELD_ID}-{Head_Util_Helper::convertSpaceToHyphen($RELATED_LIST_VALUE)}" {/if}> {$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)} </span>
											{else}
												{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}
												{* Documents list view special actions "view file" and "download file" *}
												{if $RELATED_MODULE_NAME eq 'Documents' && $RELATED_HEADERNAME eq 'filename' && isPermitted($RELATED_MODULE->get('name'), 'DetailView', $RELATED_RECORD->getId()) eq 'yes'}
													<span class="actionImages">
														{assign var=RECORD_ID value=$RELATED_RECORD->getId()}
														{assign var="DOCUMENT_RECORD_MODEL" value=Head_Record_Model::getInstanceById($RECORD_ID)}
														{if $DOCUMENT_RECORD_MODEL->get('filename') && $DOCUMENT_RECORD_MODEL->get('filestatus')}
															<a name="viewfile" href="javascript:void(0)" data-filelocationtype="{$DOCUMENT_RECORD_MODEL->get('filelocationtype')}" data-filename="{$DOCUMENT_RECORD_MODEL->get('filename')}" onclick="Head_Header_Js.previewFile(event, {$RELATED_RECORD->getId()})"><i title="{vtranslate('LBL_VIEW_FILE', $RELATED_MODULE_NAME)}" class="icon-picture alignMiddle"></i></a>&nbsp;
															{/if}
															{if $DOCUMENT_RECORD_MODEL->get('filename') && $DOCUMENT_RECORD_MODEL->get('filestatus') && $DOCUMENT_RECORD_MODEL->get('filelocationtype') eq 'I'}
															<a name="downloadfile" href="{$DOCUMENT_RECORD_MODEL->getDownloadFileURL()}"><i title="{vtranslate('LBL_DOWNLOAD_FILE', $RELATED_MODULE_NAME)}" class="icon-download-alt alignMiddle"></i></a>&nbsp;
															{/if}
													</span>
												{/if}
											{/if}
										{/if}
									</span>
								</td>
							{/foreach}
						</tr>
					{/foreach}
				</table>
				</div>

			        <div class="col-lg-12 col-md-12 col-sm-12">
		        	    {assign var=PAGING_MODEL value=$PAGING}
			            {assign var=RECORD_COUNT value=$RELATED_RECORDS|@count}
			            {assign var=PAGE_NUMBER value=$PAGING->get('page')}
			            {include file="Pagination.tpl"|vtemplate_path:$MODULE }
		        	</div>
		</div>
		<div class="bottomscroll-div"></div>
		<script type="text/javascript">
			var related_uimeta = (function () {
				var fieldInfo = {$RELATED_FIELDS_INFO};
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
								return fieldInfo[name].type
							}
							return false;
						}
					}
				};
			})();
		</script>
	</div>
<style>
/*.tablec-fixedc-columnc-outterc {
  position: relative;
  margin: 2rem auto;
  max-width: 100%;
}

.tablec-fixedc-columnc-innerc {
  overflow-x: scroll;
  overflow-y: visible;
  margin-left: 177px;
}
.tablec-fixedc-columnc-innerc .tablec {
  margin-bottom: 0.25rem;
}

.tablec.tablec-fixedc-columnc {
  table-layout: fixed;
  width: 100%;
}

.tablec td,
.tablec th {
  width: 100px;
}

.tablec th:first-child,
.tablec tr td:first-child {
  position: absolute;
  left: 0;
  width: 100px;
}
@media only screen and (min-width: 412px) and (max-width: 767px) { 
	
.tablec-fixedc-columnc-innerc {

  margin-left: 100px !important;
}

}
@media only screen and (min-width: 375px) and (max-width: 767px) { 
	
.tablec-fixedc-columnc-innerc {

  margin-left: 100px !important;
}

}
@media only screen and (min-width: 757px) and (max-width: 1507px) { 
	
.tablec-fixedc-columnc-innerc {

  
}

}*/

</style>
{/strip}
