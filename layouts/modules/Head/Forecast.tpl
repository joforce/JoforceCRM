{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
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
	<input type="hidden" name="module_name" id="module_name" class="module_name" value="{$MODULE}">
	<input type="hidden" name="picklist_name" id="picklist_name" class="picklist_name" value="{$picklist_name}">
	<input type="hidden" name="picklist_id" id="picklist_id" class="picklist_id" value="{$picklist_id}">
	<table style="overflow-x: auto;overflow-y: auto;">
	    <tbody>
		<tr>
		   <td>
			<div style="white-space: nowrap;">
			{assign var=FIELDS_MODELS_LIST value=$SOURCE_MODULE_MODEL->getFields()}
			{foreach key=picklist_value_id item=picklist_value from=$PICKLISTS_VALUES}
			    {assign var=picklist_value_name value=$picklist_value.$picklist_name}
			    {assign var=picklist_value_color value=$picklist_value.color}
			    <div style="display: inline-block;vertical-align: top;white-space: normal;" id="forecast-div-{$picklist_value_id}" class="forecast-div" data-color="{$picklist_value_color}" data-pickid="{$picklist_value_id}">
				<ul class="table-header header-view p0">
		  		    <li style="display: inline-block;{if !empty($picklist_value_color)}background:{$picklist_value_color} {/if}" class="pipe_stage" id="pipe_stage_{$picklist_value_id}" data-stage_id="{$picklist_value_id}">
					<span class="stage_name">{$picklist_value_name}</span>
					<span>
					    <span class="stage_value ml15" id="total_amount-{$picklist_value_id}">{vtranslate($picklist_label, 'Head')}</span>
					</span>
					<span class="no-oppprtunity">
					    <small class="potential_count" id="total_count-{$picklist_value_id}"></small>
					    <small id="opportunity-{$picklist_value_id}"> 
					    </small>
                    			</span> 
				    </li>
				</ul>
				{if empty($picklist_value_color)} {$picklist_value_color = '#d2d6de'} {/if}
				<ul style="list-style-type: none;" class = "draggable" id="stageid-{$picklist_value_id}">
				    {foreach item=RECORD key=record_id from=$RECORDS}
					{assign var=recordModel value=Head_Record_Model::getInstanceById($record_id, $MODULE)}
					{if $recordModel->get($picklist_name) == $picklist_value_name}
					    <li data-potential-id="{$record_id}" data-stageid="{$picklist_value_id}" class="process box" id="sortlist-{$record_id}" style="border: 2px solid {$picklist_value_color};">
					        <div class="col-lg-12 drag-list">
			                            <div class="col-lg-9 p0">

							<a style="color:#1C7C54;" href="{$recordModel->getDetailViewUrl()}" class="table-list-front info-row rows headerAjaxEdit td" data-module-name="{$MODULE}">
						            <span class="recordLabel" title="{$recordModel->getName()}">
						            {foreach item=NAME_FIELD from=$SOURCE_MODULE_MODEL->getNameFields()}
						                {assign var=FIELD_MODEL value=$SOURCE_MODULE_MODEL->getField($NAME_FIELD)}
						                {if $FIELD_MODEL->getPermissions()}
						                    <span class="{$NAME_FIELD}">{$recordModel->get($NAME_FIELD)}</span>&nbsp;
						                {/if}
						            {/foreach}
						            </span>
						        </a>

							{foreach from=$SELECTED_MODULE_FIELDS item=FIELD_NAME key=key}
						            {assign var=FIELD_MODEL value=$FIELDS_MODELS_LIST[$FIELD_NAME]}
						            {assign var=FIELD_DATA_TYPE value=$FIELD_MODEL->getFieldDataType()}
						            {if $FIELD_MODEL->isActiveField() && $recordModel->get($FIELD_NAME) && $FIELD_MODEL->isViewable()}
						                {assign var=FIELD_MODEL value=$FIELD_MODEL->set('fieldvalue', $recordModel->get({$FIELD_NAME}))}
						                {assign var=DISPLAY_VALUE value="{$FIELD_MODEL->getDisplayValue($recordModel->get($FIELD_NAME))}"}
						                    <div class="{$FIELD_NAME} value" title="{vtranslate($FIELD_MODEL->get('label'),$SOURCE_MODULE_NAME)} : {strip_tags($DISPLAY_VALUE)}">
        					                    	{include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$SOURCE_MODULE_NAME FIELD_MODEL=$FIELD_MODEL MODULE=$SOURCE_MODULE_NAME RECORD=$recordModel}
						                    </div>
						            {/if}
						        {/foreach}
						    </div>
                		                </div>
					    </li>
					{/if}
				    {/foreach}
				</ul>
			    </div>
			{/foreach}
			<div>
		   </td>
		</tr>
	    </tbody>
	</table>
    </div>
</div>
