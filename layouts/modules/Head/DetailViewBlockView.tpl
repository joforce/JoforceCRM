{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}

{strip}
{* {assign var=FIELDS_MODELS_LIST value=$MODULE_MODEL->getFields()} *}
{* {assign var=MODULE value=$MODULE_NAME} *}
<div class="col-lg-12 col-xl-12 col-md-12 col-sm-11  {if in_array($MODULE,array(''))} mac_scr_details_view_table_page {elseif in_array($MODULE,array('Accounts','Potentials','HelpDesk'))} mac_scr_details_view_table_page_all {elseif in_array($MODULE,array('Products'))} big_scrn_product_details_page {elseif in_array($MODULE,array('Leads'))} mac_scrn_Leads_details_page_table {elseif in_array($MODULE,array('Vendors'))} mac_scrn_Vendors_details_page_table  {elseif in_array($MODULE,array('Campaigns'))} ipad_scrn_Vendors_details_page_table  {/if}">
	<div class="col-lg-12 col-xl-12 col-md-12 col-sm-11 p0 ">
{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
	<input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
	{/if}

	{foreach key=BLOCK_LABEL_KEY item=FIELD_MODEL_LIST from=$RECORD_STRUCTURE}
		{assign var=BLOCK value=$BLOCK_LIST[$BLOCK_LABEL_KEY]}
		{if $BLOCK eq null or $FIELD_MODEL_LIST|@count lte 0}{continue}{/if}
		<div class="block block_{$BLOCK_LABEL_KEY} ml20 {if in_array($MODULE,array('PurchaseOrder','Calendar'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_DESCRIPTION_INFORMATION','LBL_SO_INFORMATION')) } ms_lbl_des_info mt0 {elseif in_array($BLOCK_LABEL_KEY,array('LBL_ADDRESS_INFORMATION','LBL_EVENT_INFORMATION','LBL_TASK_INFORMATION'))} ms_lbl_des_info  {/if} {if in_array($BLOCK_LABEL_KEY,array('LBL_TERMS_INFORMATION','LBL_RECURRENCE_INFORMATION','LBL_RELATED_TO'))} ms_lbl_des_info-new  {/if} {/if} {if in_array($MODULE,array('Campaigns'))}  {if in_array($BLOCK_LABEL_KEY,array( 'LBL_DESCRIPTION_INFORMATION','LBL_REMINDER_INFORMATION')) } ms_lbl_des_info  {/if} {/if} {if in_array($MODULE,array('Calendar'))}  {if in_array($BLOCK_LABEL_KEY,array('LBL_REMINDER_INFORMATION')) } ms_top_0  {/if} {/if} {if in_array($MODULE,array('HelpDesk'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_DESCRIPTION_INFORMATION'))} ticket_description_laptop_scrn {/if} {/if}{if in_array($MODULE,array('Leads','Accounts','Contacts','Potentials','Products','HelpDesk'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_ADDRESS_INFORMATION','LBL_DESCRIPTION_INFORMATION'))} {if in_array($MODULE,array('Products','HelpDesk'))} lap_srn_product_details_view {/if} {if !in_array($MODULE,array('Contacts','Leads'))} lead_add_details_ms_view {/if} {/if} {/if}{if in_array($MODULE,array('Products'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_STOCK_INFORMATION'))} big_scrn_stock  {/if} {/if} {if in_array($MODULE,array('Leads','Quotes','Vendors','PriceBooks','PurchaseOrder','SalesOrder','Invoice'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_ADDRESS_INFORMATION','LBL_DESCRIPTION_INFORMATION'))} {if in_array($MODULE,array('Leads'))} lead_page_only  {/if} lead_add_details_big_view  {/if} {/if} {if in_array($MODULE,array('Leads'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_DESCRIPTION_INFORMATION'))} lead_page_only_lap_srn  {/if} {/if} {if in_array($MODULE,array('Accounts')) && in_array($BLOCK_LABEL_KEY,array('LBL_IMAGE_INFORMATION'))} profile_picture_lap_scr {/if}{if in_array($MODULE,array('Services','Vendors','PriceBooks'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_PRICING_INFORMATION','LBL_DESCRIPTION_INFORMATION'))} services_add_details_big_view {/if} {/if} {if in_array($MODULE,array('Quotes','Services','PurchaseOrder','SalesOrder','Invoice'))} {if in_array($BLOCK_LABEL_KEY,array('LBL_ADDRESS_INFORMATION','LBL_DESCRIPTION_INFORMATION'))} quotes_add_details_big_view {/if} {/if} ">
			{assign var=IS_HIDDEN value=$BLOCK->isHidden()}
			{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
			<input type=hidden name="timeFormatOptions" data-value='{$DAY_STARTS}' />
			<div class='detailhead' style="background: #f7f7f7;color: #264da2;padding: 10px 20px 10px 20px;-webkit-border-top-right-radius: 7px;-webkit-border-top-left-radius: 7px;">
				<h4 class="textOverflowEllipsis maxWidth50">
					<img class="cursorPointer alignMiddle blockToggle {if !($IS_HIDDEN)} hide {/if}" src="{$SITEURL}{vimage_path('arrowRight.png')}" data-mode="hide" data-id={$BLOCK_LIST[$BLOCK_LABEL_KEY]->get('id')}>
					<img class="cursorPointer alignMiddle blockToggle {if ($IS_HIDDEN)} hide {/if}" src="{$SITEURL}{vimage_path('arrowdown.png')}" data-mode="show" data-id={$BLOCK_LIST[$BLOCK_LABEL_KEY]->get('id')}>&nbsp;
					{vtranslate({$BLOCK_LABEL_KEY},{$MODULE_NAME})}
				</h4>
			</div>
			
			<div class="blockData {$MODULE}">
				<table class="table detailview-table no-border">
					<tbody {if $IS_HIDDEN} class="hide" {/if}>
						{assign var=COUNTER value=0}
						<tr>
							{foreach item=FIELD_MODEL key=FIELD_NAME from=$FIELD_MODEL_LIST}
								{assign var=fieldDataType value=$FIELD_MODEL->getFieldDataType()}
								{if !$FIELD_MODEL->isViewableInDetailView()}
									{continue}
								{/if}
								{if $FIELD_MODEL->get('uitype') eq "83"}
									{foreach item=tax key=count from=$TAXCLASS_DETAILS}
										{if $COUNTER eq 2}
											</tr><tr>
											{assign var="COUNTER" value=1}
										{else}
											{assign var="COUNTER" value=$COUNTER+1}
										{/if}
										<td class="fieldLabel {$WIDTHTYPE}">
											<span class='muted'>{vtranslate($tax.taxlabel, $MODULE)}(%)</span>
										</td>
										<td class="fieldValue {$WIDTHTYPE}" style="height:auto!important">
											<span class="value textOverflowEllipsis" data-field-type="{$FIELD_MODEL->getFieldDataType()}" >
												{if $tax.check_value eq 1}
													{$tax.percentage}
												{else}
													0
												{/if} 
											</span>
										</td>
									{/foreach}
								{else if $FIELD_MODEL->get('uitype') eq "69" || $FIELD_MODEL->get('uitype') eq "105"}
									{if $COUNTER neq 0}
										{if $COUNTER eq 2}
											</tr><tr>
											{assign var=COUNTER value=0}
										{/if}
									{/if}
									<td class="fieldLabel {$WIDTHTYPE}"><span class="muted">{vtranslate({$FIELD_MODEL->get('label')},{$MODULE_NAME})}</span></td>
									<td class="fieldValue {$WIDTHTYPE}" style="height:auto!important">
										<ul id="imageContainer">
											{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
												{if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
													<ul><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="400" height="300" /></ul>
												{/if}
											{/foreach}
										</ul>
									</td>
									{assign var=COUNTER value=$COUNTER+1}
								{else}
									{if $FIELD_MODEL->get('uitype') eq "20" or $FIELD_MODEL->get('uitype') eq "19" or $fieldDataType eq 'reminder' or $fieldDataType eq 'recurrence'}
										{if $COUNTER eq '1'}
											<td class="fieldLabel {$WIDTHTYPE}"></td><td class="{$WIDTHTYPE}"></td></tr><tr>
											{assign var=COUNTER value=0}
										{/if}
									{/if}
									{if $COUNTER eq 2}
										</tr><tr>
										{assign var=COUNTER value=1}
									{else}
										{assign var=COUNTER value=$COUNTER+1}
									{/if}
									<td class="fieldLabel textOverflowEllipsis {$WIDTHTYPE}" id="{$MODULE_NAME}_detailView_fieldLabel_{$FIELD_MODEL->getName()}" {if $FIELD_MODEL->getName() eq 'description' or $FIELD_MODEL->get('uitype') eq '69'} style='width:20%'{/if}>
										<span class="muted">
											{if $MODULE_NAME eq 'Documents' && $FIELD_MODEL->get('label') eq "File Name" && $RECORD->get('filelocationtype') eq 'E'}
												{vtranslate("LBL_FILE_URL",{$MODULE_NAME})}
											{else}
												{vtranslate({$FIELD_MODEL->get('label')},{$MODULE_NAME})}
											{/if}
											{if ($FIELD_MODEL->get('uitype') eq '72') && ($FIELD_MODEL->getName() eq 'unit_price')}
												({$BASE_CURRENCY_SYMBOL})
											{/if}
										</span>
									</td>
									<td class="fieldValue {$WIDTHTYPE}" id="{$MODULE_NAME}_detailView_fieldValue_{$FIELD_MODEL->getName()}" {if $FIELD_MODEL->get('uitype') eq '19' or $fieldDataType eq 'reminder' or $fieldDataType eq 'recurrence'} colspan="3" {assign var=COUNTER value=$COUNTER+1} style="height:auto" {/if} >
										{assign var=FIELD_VALUE value=$FIELD_MODEL->get('fieldvalue')}
										{if $fieldDataType eq 'multipicklist'}
											{assign var=FIELD_DISPLAY_VALUE value=$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}
										{else}
											{assign var=FIELD_DISPLAY_VALUE value=Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}
										{/if}

										<span class="value" data-field-type="{$FIELD_MODEL->getFieldDataType()}" {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '21'} style="white-space:normal;" {/if}>
											{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
										</span>
										{if $IS_AJAX_ENABLED && $FIELD_MODEL->isEditable() eq 'true' && $FIELD_MODEL->isAjaxEditable() eq 'true'}
											<span class="hide edit pull-left">
												{if $fieldDataType eq 'multipicklist'}
													<input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}[]' data-type="{$fieldDataType}" data-displayvalue='{$FIELD_DISPLAY_VALUE}' data-value="{$FIELD_VALUE}" />
												{else}
													<input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}' data-type="{$fieldDataType}" data-displayvalue='{$FIELD_DISPLAY_VALUE}' data-value="{$FIELD_VALUE}" />
												{/if}
											</span>
											<span class="action pull-right"><a href="#" onclick="return false;" class="editAction fa fa-pencil"></a></span>
										{/if}
									</td>
								{/if}

								{if $FIELD_MODEL_LIST|@count eq 1 and $FIELD_MODEL->get('uitype') neq "19" and $FIELD_MODEL->get('uitype') neq "20" and $FIELD_MODEL->get('uitype') neq "30" and $FIELD_MODEL->get('name') neq "recurringtype" and $FIELD_MODEL->get('uitype') neq "69" and $FIELD_MODEL->get('uitype') neq "105"}
									<td class="fieldLabel {$WIDTHTYPE}"></td><td class="{$WIDTHTYPE}" style="height:auto"></td>
								{/if}
							{/foreach}
							{* adding additional column for odd number of fields in a block *}
							{if $FIELD_MODEL_LIST|@end eq true and $FIELD_MODEL_LIST|@count neq 1 and $COUNTER eq 1}
								<td class="fieldLabel {$WIDTHTYPE}"></td><td class="{$WIDTHTYPE}" style="height:auto"></td>
							{/if}
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<br>
	{/foreach}
{/strip}
