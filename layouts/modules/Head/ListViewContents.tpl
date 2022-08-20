{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Head/views/List.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="PicklistColorMap.tpl"|vtemplate_path:$MODULE}

<div class="col-sm-12 col-xs-12 lists  pl0 pr0">
<div id="licence-alert-waring" class="alert">
  <span class="closebtn " onclick="this.parentElement.style.display='none';">&times;</span> 

  <strong> <span class="licence-waring-icon"><i class="fa fa-warning"></i> </span> Danger!</strong>   You are not secure 

</div>
	<input type="hidden" name="view" id="view" value="{$VIEW}" />
	<input type="hidden" name="cvid" value="{$VIEWID}" />
	<input type="hidden" name="pageStartRange" id="pageStartRange" value="{$PAGING_MODEL->getRecordStartRange()}" />
	<input type="hidden" name="pageEndRange" id="pageEndRange" value="{$PAGING_MODEL->getRecordEndRange()}" />
	<input type="hidden" name="previousPageExist" id="previousPageExist" value="{$PAGING_MODEL->isPrevPageExists()}" />
	<input type="hidden" name="nextPageExist" id="nextPageExist" value="{$PAGING_MODEL->isNextPageExists()}" />
	<input type="hidden" name="alphabetSearchKey" id="alphabetSearchKey" value= "{$MODULE_MODEL->getAlphabetSearchField()}" />
	<input type="hidden" name="Operator" id="Operator" value="{$OPERATOR}" />
	<input type="hidden" name="totalCount" id="totalCount" value="{$LISTVIEW_COUNT}" />
	<input type='hidden' name="pageNumber" value="{$PAGE_NUMBER}" id='pageNumber'>
	<input type='hidden' name="pageLimit" value="{$PAGING_MODEL->getPageLimit()}" id='pageLimit'>
	<input type="hidden" name="noOfEntries" value="{$LISTVIEW_ENTRIES_COUNT}" id="noOfEntries">
	<input type="hidden" name="currentSearchParams" value="{Head_Util_Helper::toSafeHTML(Zend_JSON::encode($SEARCH_DETAILS))}" id="currentSearchParams" />
	<input type="hidden" name="currentTagParams" value="{Head_Util_Helper::toSafeHTML(Zend_JSON::encode($TAG_DETAILS))}" id="currentTagParams" />
	<input type="hidden" name="noFilterCache" value="{$NO_SEARCH_PARAMS_CACHE}" id="noFilterCache" >
	<input type="hidden" name="orderBy" value="{$ORDER_BY}" id="orderBy">
	<input type="hidden" name="sortOrder" value="{$SORT_ORDER}" id="sortOrder">
	<input type="hidden" name="list_headers" value='{$LIST_HEADER_FIELDS}'/>
	<input type="hidden" name="tag" value="{$CURRENT_TAG}" />
	<input type="hidden" name="folder_id" value="{$FOLDER_ID}" />
	<input type="hidden" name="folder_value" value="{$FOLDER_VALUE}" />
	<input type="hidden" name="viewType" value="{$VIEWTYPE}" />
	{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
		<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
	{/if}
	{if !$SEARCH_MODE_RESULTS}
		{include file="ListViewActions.tpl"|vtemplate_path:$MODULE}
	{/if}

	<div id="table-content"  class="table-container mt10 {if $LISTVIEW_ENTRIES_COUNT <= '5'} list_view_table_height {/if} {if $MODULE eq 'Calendar'} pt35 {/if} {if in_array($MODULE,array('EmailTemplates'))}ml10{/if}" style="overflow: auto;top:0px!important">
	<div class="table-toggle fixed-scroll-table">

	    <form name='list' id='listedit' action='' onsubmit="return false;">
		<table id="listview-table" class="table {if $LISTVIEW_ENTRIES_COUNT eq '0'}listview-table-norecords {/if} listview-table">
		    <thead>
			<tr class="listViewContentHeader">
			    <th>
				{if !$SEARCH_MODE_RESULTS}
				    {include file="ListViewHeaderActionsLeft.tpl"|vtemplate_path:$MODULE}
				{elseif $SEARCH_MODE_RESULTS}
				    {vtranslate('LBL_ACTIONS',$MODULE)}
				{/if}

				<!-- {if $MODULE_MODEL->isQuickSearchEnabled() && !$SEARCH_MODE_RESULTS} -->
				    {* <div class="searchRow">
					<h2></h2>
                                    </div> *}
				<!-- {/if} -->
			    </th>
			    {foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}					
					{if $SEARCH_MODE_RESULTS and ($LISTVIEW_HEADER->getFieldDataType() eq 'multipicklist')}
						{assign var=NO_SORTING value=1}
					{* {else} <div class="searchRow">
					<h2></h2>
                                    </div> *}
						{assign var=NO_SORTING value=0}
					{/if}
					<th {if $COLUMN_NAME eq $LISTVIEW_HEADER->get('name')} nowrap="nowrap" {/if} class="{if in_array($MODULE,array('PriceBooks'))} big_scr_th_width {/if}">
						{if $COLUMN_NAME eq $LISTVIEW_HEADER->get('name')}
						    <a href="#" class="removeSorting pull-right">x</a> 
						{/if}
						<a href="#" class="{if $NO_SORTING}noSorting{else}listViewContentHeaderValues{/if}" {if !$NO_SORTING}data-nextsortorderval="{if $COLUMN_NAME eq $LISTVIEW_HEADER->get('name')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-columnname="{$LISTVIEW_HEADER->get('name')}"{/if} data-field-id='{$LISTVIEW_HEADER->getId()}'>
							{if !$NO_SORTING}
								{if $COLUMN_NAME eq $LISTVIEW_HEADER->get('name')}
									<i class="fa pull-right {$FASORT_IMAGE}"></i>
								{else}
									<i class="fa {$DEFAULT_SORT} pull-right"></i>
								{/if}
							{/if}
							<span>{vtranslate($LISTVIEW_HEADER->get('label'), $LISTVIEW_HEADER->getModuleName())}</span>
						</a>
						<div class="inner-addon left-addon searchRow ml20" >
						    {if $MODULE_MODEL->isQuickSearchEnabled() && !$SEARCH_MODE_RESULTS}
							<i class="fa fa-search"></i>
                                                        {assign var=FIELD_UI_TYPE_MODEL value=$LISTVIEW_HEADER->getUITypeModel()}
                                                        {include file=vtemplate_path($FIELD_UI_TYPE_MODEL->getListSearchTemplateName(),$MODULE) FIELD_MODEL= $LISTVIEW_HEADER SEARCH_INFO=$SEARCH_DETAILS[$LISTVIEW_HEADER->getName()] USER_MODEL=$CURRENT_USER_MODEL}
                                                        <input data-val="{$FIELD_UI_TYPE_MODEL->getListSearchTemplateName()}" type="hidden" class="operatorValue" value="{$SEARCH_DETAILS[$LISTVIEW_HEADER->getName()]['comparator']}">
						    {/if}
						</div>
					</th>
			    {/foreach}
			    <th>
                                        	{if !$SEARCH_MODE_RESULTS}
                                                	{include file="ListViewHeaderActionsRight.tpl"|vtemplate_path:$MODULE}
                                                {elseif $SEARCH_MODE_RESULTS}
                                                        {vtranslate('LBL_ACTIONS',$MODULE)}
                                                {/if}
                            </th>
			</tr>

		    </thead>
		    <tbody class="overflow-y">
					{foreach item=LISTVIEW_ENTRY from=$LISTVIEW_ENTRIES name=listview}
						{assign var=DATA_ID value=$LISTVIEW_ENTRY->getId()}
						{assign var=DATA_URL value=$LISTVIEW_ENTRY->getDetailViewUrl()}
						{if $SEARCH_MODE_RESULTS && $LISTVIEW_ENTRY->getModuleName() == "ModComments"}
							{assign var=RELATED_TO value=$LISTVIEW_ENTRY->get('related_to_model')}
							{assign var=DATA_ID value=$RELATED_TO->getId()}
							{assign var=DATA_URL value=$RELATED_TO->getDetailViewUrl()}
						{/if}
						<tr class="listViewEntries" data-id='{$DATA_ID}' data-recordUrl='{$DATA_URL}' id="{$MODULE}_listView_row_{$smarty.foreach.listview.index+1}" {if $MODULE eq 'Calendar'}data-recurring-enabled='{$LISTVIEW_ENTRY->isRecurringEnabled()}'{/if}>
							<td class = "listViewRecordActions">
								{include file="ListViewRecordActionsLeft.tpl"|vtemplate_path:$MODULE}
							</td>
							{if ($LISTVIEW_ENTRY->get('document_source') eq 'Google Drive' && $IS_GOOGLE_DRIVE_ENABLED) || ($LISTVIEW_ENTRY->get('document_source') eq 'Dropbox' && $IS_DROPBOX_ENABLED)}
						<input type="hidden" name="document_source_type" value="{$LISTVIEW_ENTRY->get('document_source')}">
					{/if}
					{foreach item=LISTVIEW_HEADER from=$LISTVIEW_HEADERS}
						{assign var=LISTVIEW_HEADERNAME value=$LISTVIEW_HEADER->get('name')}
						{assign var=LISTVIEW_ENTRY_RAWVALUE value=$LISTVIEW_ENTRY->getRaw($LISTVIEW_HEADER->get('column'))}
						{if $LISTVIEW_HEADER->getFieldDataType() eq 'currency' || $LISTVIEW_HEADER->getFieldDataType() eq 'text'}
							{assign var=LISTVIEW_ENTRY_RAWVALUE value=$LISTVIEW_ENTRY->getTitle($LISTVIEW_HEADER)}
						{/if}
						{assign var=LISTVIEW_ENTRY_VALUE value=$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}
						<td class="listViewEntryValue" data-name="{$LISTVIEW_HEADER->get('name')}" title="{$LISTVIEW_ENTRY->getTitle($LISTVIEW_HEADER)}" data-rawvalue="{$LISTVIEW_ENTRY_RAWVALUE}" data-field-type="{$LISTVIEW_HEADER->getFieldDataType()}">
							<span class="fieldValue">
								<span class="value">
									{if ($LISTVIEW_HEADER->isNameField() eq true or $LISTVIEW_HEADER->get('uitype') eq '4') and $MODULE_MODEL->isListViewNameFieldNavigationEnabled() eq true }
										<a href="{$LISTVIEW_ENTRY->getDetailViewUrl()}">{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}</a>
										{if $MODULE eq 'Products' &&$LISTVIEW_ENTRY->isBundle()}
											&nbsp;-&nbsp;<i class="mute">{vtranslate('LBL_PRODUCT_BUNDLE', $MODULE)}</i>
										{/if}
									{else if $MODULE_MODEL->getName() eq 'Documents' && $LISTVIEW_HEADERNAME eq 'document_source'}
										{$LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME)}
									{else}
										{if $LISTVIEW_HEADER->get('uitype') eq '72'}
											{assign var=CURRENCY_SYMBOL_PLACEMENT value={$CURRENT_USER_MODEL->get('currency_symbol_placement')}}
											{if $CURRENCY_SYMBOL_PLACEMENT eq '1.0$'}
												{$LISTVIEW_ENTRY_VALUE}{$LISTVIEW_ENTRY->get('currencySymbol')}
											{else}
												{$LISTVIEW_ENTRY->get('currencySymbol')}{$LISTVIEW_ENTRY_VALUE}
											{/if}
										{else if $LISTVIEW_HEADER->get('uitype') eq '71'}
											{assign var=CURRENCY_SYMBOL value=$LISTVIEW_ENTRY->get('userCurrencySymbol')}
											{if $LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME) neq NULL}
												{CurrencyField::appendCurrencySymbol($LISTVIEW_ENTRY->get($LISTVIEW_HEADERNAME), $CURRENCY_SYMBOL)}
											{/if}
										{else if $LISTVIEW_HEADER->getFieldDataType() eq 'picklist'}
											{if $LISTVIEW_ENTRY->get('activitytype') eq 'Task'}
												{assign var=PICKLIST_FIELD_ID value={$LISTVIEW_HEADER->getId()}}
											{else}
												{if $LISTVIEW_HEADER->getName() eq 'taskstatus'}
													{assign var="EVENT_STATUS_FIELD_MODEL" value=Head_Field_Model::getInstance('eventstatus', Head_Module_Model::getInstance('Events'))}
													{if $EVENT_STATUS_FIELD_MODEL}
														{assign var=PICKLIST_FIELD_ID value={$EVENT_STATUS_FIELD_MODEL->getId()}}
													{else} 
														{assign var=PICKLIST_FIELD_ID value={$LISTVIEW_HEADER->getId()}}
													{/if}
												{else}
													{assign var=PICKLIST_FIELD_ID value={$LISTVIEW_HEADER->getId()}}
												{/if}
											{/if}
                                            <span {if !empty($LISTVIEW_ENTRY_VALUE)} class="picklist-color picklist-{$PICKLIST_FIELD_ID}-{Head_Util_Helper::convertSpaceToHyphen($LISTVIEW_ENTRY_RAWVALUE)}" {/if}> {$LISTVIEW_ENTRY_VALUE} </span>
										{else if $LISTVIEW_HEADER->getFieldDataType() eq 'multipicklist'}
											{assign var=MULTI_RAW_PICKLIST_VALUES value=explode('|##|',$LISTVIEW_ENTRY->getRaw($LISTVIEW_HEADERNAME))}
											{assign var=MULTI_PICKLIST_VALUES value=explode(',',$LISTVIEW_ENTRY_VALUE)}
											{assign var=ALL_MULTI_PICKLIST_VALUES value=array_flip($LISTVIEW_HEADER->getPicklistValues())}
											{foreach item=MULTI_PICKLIST_VALUE key=MULTI_PICKLIST_INDEX from=$MULTI_PICKLIST_VALUES}
												<span {if !empty($LISTVIEW_ENTRY_VALUE)} class="picklist-color picklist-{$LISTVIEW_HEADER->getId()}-{Head_Util_Helper::convertSpaceToHyphen(trim($ALL_MULTI_PICKLIST_VALUES[trim($MULTI_PICKLIST_VALUE)]))}"{/if} > 
													{if trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX]) eq vtranslate('LBL_NOT_ACCESSIBLE', $MODULE)} 
														<font color="red"> 
														{trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX])} 
														</font> 
													{else} 
														{trim($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX])} 
													{/if}
													{if !empty($MULTI_PICKLIST_VALUES[$MULTI_PICKLIST_INDEX + 1])},{/if}
												</span>
											{/foreach}
										{else}
											{$LISTVIEW_ENTRY_VALUE}
										{/if}
									{/if}
								</span>
							</span>
							{if $LISTVIEW_HEADER->isEditable() eq 'true' && $LISTVIEW_HEADER->isAjaxEditable() eq 'true'}
								<span class="hide edit">
								</span>
							{/if}
						</td>
					{/foreach}
						<td class = "listViewRecordActions">
                                                	{include file="ListViewRecordActionsRight.tpl"|vtemplate_path:$MODULE}
                                                </td>
					</tr>
				{/foreach}
				{if $LISTVIEW_ENTRIES_COUNT eq '0'}
					<tr class="emptyRecordsDiv">
						{assign var=COLSPAN_WIDTH value={count($LISTVIEW_HEADERS)}+2}
						<td colspan="{$COLSPAN_WIDTH}">
							<div class="emptyRecordsContent">
								{assign var=SINGLE_MODULE value="SINGLE_$MODULE"}
								{vtranslate('LBL_NO')} {vtranslate($MODULE, $MODULE)} {vtranslate('LBL_FOUND')}.
								{if $IS_CREATE_PERMITTED}
									<a class="joforce-link" href="{$MODULE_MODEL->getCreateRecordUrl()}"> {vtranslate('LBL_CREATE')}</a>
									{if Users_Privileges_Model::isPermitted($MODULE, 'Import') && $LIST_VIEW_MODEL->isImportEnabled()}
										{vtranslate('LBL_OR', $MODULE)}
										{* <a class="joforce-link" href="#" onclick="return Head_Import_Js.triggerImportAction()">{vtranslate('LBL_IMPORT', $MODULE)}</a> *}
										<a class="joforce-link" href="{$SITEURL}{$MODULE}/view/Import">{vtranslate('LBL_IMPORT', $MODULE)}</a>
										{vtranslate($MODULE, $MODULE)}
									{else}
										{vtranslate($SINGLE_MODULE, $MODULE)}
									{/if}
								{/if}
							</div>
						</td>
					</tr>
				{/if}
				</tbody>
			</table>
		</form>
		</div>
	</div>
	<div class="row">
            {assign var=RECORD_COUNT value=$LISTVIEW_ENTRIES_COUNT}
            {include file="Pagination.tpl"|vtemplate_path:$MODULE HEADSHOW=false}
	</div>
	<div class="row" style="height: 40px;">
		<div class="col-lg-12">
		</div>
	</div>	
	<div id="scroller_wrapper" class="bottom-fixed-scroll">
		<div id="scroller" class="scroller-div"></div>
	</div>

	<div class="quickviewcontent mt20 ml5 hide" id="quickviewcontent" style="width:30%;">
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){
		/*$('#joforce-table-search').on('click' , function(event){
			
			var className = $('.table-actions.table-left-column').attr("class");

			if( className == 'table-actions table-left-column'){
			$(".table-left-column").addClass("table-height");
				//alert('clickram');
			}
			else{
				$(".table-left-column").removeClass("table-height");
				//alert('chand');
			}
		})*/
		
		/*$( ".button.btn-success.btn-small.save").load('click',function() {
			//alert('rfdf');
			$(".table-left-column").addClass("");
			var newClass=$('.button.btn-success.btn-small.save').attr('class');
			if ( newClass == 'button btn-success btn-small save') {
				$(".table-left-column").addClass("mb50");
			}
			else{
				$(".table-left-column").removeClass("mb50");
			}
		});*/
		$('.fixed-scroll-table').floatingScroll();
		var screenheight = $(window).height();
		var alterheight = screenheight-180;
		var testheight = document.getElementById("table-content").scrollHeight;

		$('.fl-scrolls').css("top",alterheight+94).hide();

		if (document.querySelector('.fixed-scroll-table') !== null) {
			$('#page').css("min-height","auto");
			$('.content-area').css("min-height","auto");
			$('#table-content').css("height",alterheight);
		}

		if(testheight > alterheight){
			$('.fl-scrolls').show();
		}

		$('.quickView').on('click',function(){
			$('.fl-scrolls').css("width","68%");
			$('.more-actions-right').addClass('quickview-more-actions');
		});

		$('.fixed-scroll-table table tbody tr.listViewEntries').click(function(){
			$('#table-content').css("height",alterheight);
			if (document.querySelector('.listViewEntries') !== null) {
				$('#table-content').css("height",alterheight);
			}
		});
	});
</script>
