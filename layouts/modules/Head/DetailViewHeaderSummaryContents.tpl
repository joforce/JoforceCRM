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
<div class="summary-table no-border   {if in_array($MODULE, $business_card_modules)} summary-table-ui {/if}">
	<div>
	{if empty($SUMMARY_RECORD_STRUCTURE['SUMMARY_FIELDS']) && !in_array($MODULE, array('Services','Products','Contacts','Accounts','Leads','Potentials','HelpDesk'))}
		
		{assign var=COUNTER value=0}
			{foreach key=BLOCK_LABEL_KEY item=FIELD_MODEL_LIST from=$RECORD_STRUCTURE}
				{foreach item=FIELD_MODEL key=FIELD_NAME from=$FIELD_MODEL_LIST}
					
					{assign var="COUNTER" value=$COUNTER+1}
					{if $FIELD_MODEL->get('label') neq 'Modified Time' && $FIELD_MODEL->get('label') neq 'Created Time'&& $FIELD_MODEL->get('label') neq 'Assigned To' && $COUNTER<=4}
					<h1>{$FIELD_MODEL->get('label')}</h1>
							<div class="fieldValue">
								<span  style="color:black">
{$FIELD_MODEL->get('label')}:</span>                 
		                        <span class="value textOverflowEllipsis" title="{vtranslate($FIELD_MODEL->get('label'), $MODULE)}:{strip_tags($DISPLAY_VALUE)}"  {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '20' or $FIELD_MODEL->get('uitype') eq '21'}style="word-wrap: break-word;"{/if}>
                		            {include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
		                        </span>
							</div>
					{/if}
				{/foreach}	
			{/foreach}
	{else}
			{foreach item=FIELD_MODEL key=FIELD_NAME from=$SUMMARY_RECORD_STRUCTURE['SUMMARY_FIELDS']}
			{assign var=fieldDataType value=$FIELD_MODEL->getFieldDataType()}
			{if $FIELD_MODEL->get('name') neq 'modifiedtime' && $FIELD_MODEL->get('name') neq 'createdtime'}
		                    <div class="fieldValue {if in_array($FIELD_MODEL->get('name'),array('firstname','lastname','website','city','country')) } unwanted_summary_feilds {/if}">
                		        {assign var=DISPLAY_VALUE value="{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get("fieldvalue"))}"} 
								<span  style="color:black">{$FIELD_MODEL->get('label')}:</span>                 
		                        <span class="value textOverflowEllipsis" title="{vtranslate($FIELD_MODEL->get('label'), $MODULE)}:{strip_tags($DISPLAY_VALUE)}"  {if $FIELD_MODEL->get('uitype') eq '19' or $FIELD_MODEL->get('uitype') eq '20' or $FIELD_MODEL->get('uitype') eq '21'}style="word-wrap: break-word;"{/if}>
                		            {include file=$FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName()|@vtemplate_path:$MODULE_NAME FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}
		                        </span>
								{/if}
							</div>
		{/foreach}
	{/if}
	 {include file="DetailViewTagList.tpl"|vtemplate_path:$MODULE}
	</div>
</div>

{/strip}
