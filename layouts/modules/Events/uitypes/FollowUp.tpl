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
{assign var="dateFormat" value=$USER_MODEL->get('date_format')}
{assign var="currentDate" value=Head_Date_UIType::getDisplayDateValue('')}
{assign var="time" value=Head_Time_UIType::getDisplayTimeValue(null)}
{assign var="currentTimeInHeadFormat" value=Head_Time_UIType::getTimeValueInAMorPM($time)}
{if $COUNTER eq 2}
</tr>
	{assign var=COUNTER value=1}
{else}
	<td class="fieldLabel {$WIDTHTYPE}"></td><td class="{$WIDTHTYPE}"></td></tr>
	{assign var=COUNTER value=$COUNTER+1}
{/if}
{assign var=FOLLOW_UP_LABEL value={vtranslate('LBL_HOLD_FOLLOWUP_ON',$MODULE)}}
<tr class="{if !($SHOW_FOLLOW_UP)}hide {/if}followUpContainer massEditActiveField">
	<td class="fieldLabel">
		<label class="muted pull-right marginRight10px">
			<input name="followup" type="checkbox" class="alignTop" {if $FOLLOW_UP_STATUS} checked{/if}/>
			{$FOLLOW_UP_LABEL}
		</label>	
	</td>
	{$FIELD_INFO['label'] = {$FOLLOW_UP_LABEL}}
	<td class="fieldValue">
		<div>
			<div class="input-append row-fluid">
				<div class="span10 row-fluid date">
					<input name="followup_date_start" type="text" class="span9 dateField" data-date-format="{$dateFormat}" type="text"  data-fieldinfo= '{Head_Util_Helper::toSafeHTML(ZEND_JSON::encode($FIELD_INFO))}'
						   value="{if !empty($FOLLOW_UP_DATE)}{$FOLLOW_UP_DATE}{else}{$currentDate}{/if}" data-validation-engine="validate[funcCall[Head_greaterThanDependentField_Validator_Js.invokeValidation]]" />
					<span class="add-on"><i class="icon-calendar"></i></span>
				</div>	
			</div>		
		</div>
		<div>
			<div class="input-append time">
				<input type="text" name="followup_time_start" class="timepicker-default input-small" 
					   value="{if !empty($FOLLOW_UP_TIME)}{$FOLLOW_UP_TIME}{else}{$currentTimeInHeadFormat}{/if}" />
				<span class="add-on cursorPointer">
					<i class="icon-time"></i>
				</span>
			</div>	
		</div>
	</td>
	<td class="fieldLabel {$WIDTHTYPE}"></td><td class="{$WIDTHTYPE}"></td>
</tr> 
