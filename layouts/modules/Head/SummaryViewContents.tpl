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
{assign var=business_card_modules value=array('Leads', 'Contacts', 'Accounts')}
<table class="summary-table no-border   {if in_array($MODULE, $business_card_modules)} summary-table-ui {/if}">
    {if in_array($MODULE, $business_card_modules)}
	{assign var=business_icon_array value=returnFieldIconsArray() }
	{assign var=default_business_field_array value=returnBusinessFieldArray()}
	<tbody>	
	    {foreach item=FIELD_MODEL key=FIELD_NAME from=$SUMMARY_RECORD_STRUCTURE['SUMMARY_FIELDS']}
	        {assign var=fieldDataType value=$FIELD_MODEL->getFieldDataType()}
		{if $FIELD_MODEL->get('name') neq 'modifiedtime' && $FIELD_MODEL->get('name') neq 'createdtime'}
		<tr class="col-lg-12 mt5">
			<td class="fieldLabel {if $FIELD_NAME == 'lastname'}pl15{/if}" >
				{if in_array($FIELD_NAME, $default_business_field_array)}
                                        <div class="{$business_icon_array[$FIELD_NAME]}" title="{vtranslate($FIELD_MODEL->get('label'), $MODULE)}"></div>
                                {else}
					 <label class="muted textOverflowEllipsis" title="{vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)}">
                                            {vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)}
                                            {if $FIELD_MODEL->get('uitype') eq '71' || $FIELD_MODEL->get('uitype') eq '72'}
                                                        {assign var=CURRENCY_INFO value=getCurrencySymbolandCRate($USER_MODEL->get('currency_id'))}
                                                        &nbsp;({$CURRENCY_INFO['symbol']})
                                            {/if}
                                        </label>
                                {/if}
			</td>
			<td class="fieldValue">
                        	<div class="">
                                        {assign var=DISPLAY_VALUE value="{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get("fieldvalue"))}"}
                                        <span class="value textOverflowEllipsis" title="{strip_tags($DISPLAY_VALUE)}"  {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '20' or $FIELD_MODEL->get('uitype') eq '21'}style="word-wrap: break-word;"{/if}>
                                            {include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
                                        </span>
                                        {if $FIELD_MODEL->isEditable() eq 'true' && $IS_AJAX_ENABLED && $FIELD_MODEL->isAjaxEditable() eq 'true' && $FIELD_MODEL->get('uitype') neq 69}
                                                <span class="hide edit">
                                                {if $FIELD_MODEL->getFieldDataType() eq 'multipicklist'}
                                                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}[]' data-type="{$fieldDataType}" data-displayvalue='{Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}' data-value="{$FIELD_MODEL->get('fieldvalue')}" />
                                                {else}
                                                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}' data-type="{$fieldDataType}" data-displayvalue='{Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}' data-value="{$FIELD_MODEL->get('fieldvalue')}" />
                                                {/if}
                                                </span>
                                                <span class="action"><a href="#" onclick="return false;" class="editAction fa fa-pencil"></a></span>
                                        {/if}
                                </div>
                	</td>
		</tr>
		{/if}
		{/foreach}
	</tbody>
    {else}
	<tbody>
	{foreach item=FIELD_MODEL key=FIELD_NAME from=$SUMMARY_RECORD_STRUCTURE['SUMMARY_FIELDS']}
        {assign var=fieldDataType value=$FIELD_MODEL->getFieldDataType()}
		{if $FIELD_MODEL->get('name') neq 'modifiedtime' && $FIELD_MODEL->get('name') neq 'createdtime'}
			<tr class="summaryViewEntries">
				<td class="fieldLabel" >
        		                <label class="muted textOverflowEllipsis" title="{vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)}">
                        		    {vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)}
		                            {if $FIELD_MODEL->get('uitype') eq '71' || $FIELD_MODEL->get('uitype') eq '72'}
							{assign var=CURRENCY_INFO value=getCurrencySymbolandCRate($USER_MODEL->get('currency_id'))}
							&nbsp;({$CURRENCY_INFO['symbol']})
                		            {/if}
		                        </label>
                	    	</td>
				<td class="fieldValue">
		                    <div class="">
                		        {assign var=DISPLAY_VALUE value="{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get("fieldvalue"))}"}                  
		                        <span class="value textOverflowEllipsis" title="{strip_tags($DISPLAY_VALUE)}"  {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '20' or $FIELD_MODEL->get('uitype') eq '21'}style="word-wrap: break-word;"{/if}>
                		            {include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
		                        </span>
                		        {if $FIELD_MODEL->isEditable() eq 'true' && $IS_AJAX_ENABLED && $FIELD_MODEL->isAjaxEditable() eq 'true' && $FIELD_MODEL->get('uitype') neq 69}
		                        	<span class="hide edit">
		                                {if $FIELD_MODEL->getFieldDataType() eq 'multipicklist'}
        			                        <input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}[]' data-type="{$fieldDataType}" data-displayvalue='{Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}' data-value="{$FIELD_MODEL->get('fieldvalue')}" />
                	        	        {else}
                        	        		<input type="hidden" class="fieldBasicData" data-name='{$FIELD_MODEL->get('name')}' data-type="{$fieldDataType}" data-displayvalue='{Head_Util_Helper::toSafeHTML($FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue')))}' data-value="{$FIELD_MODEL->get('fieldvalue')}" />
	                        	        {/if}
        	                    		</span>
	                            		<span class="action"><a href="#" onclick="return false;" class="editAction fa fa-pencil"></a></span>
        		                {/if}
                    		      </div>
				</td>
			</tr>
		{/if}
	{/foreach}
	</tbody>
    {/if}
</table>

{/strip}
