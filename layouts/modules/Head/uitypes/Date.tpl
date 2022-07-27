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
{assign var="FIELD_INFO" value=$FIELD_MODEL->getFieldInfo()}
{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
{assign var="dateFormat" value=$USER_MODEL->get('date_format')}
{if (!$FIELD_NAME)}
  {assign var="FIELD_NAME" value=$FIELD_MODEL->getFieldName()}
{/if}
{assign var=value value=$FIELD_MODEL->getEditViewDisplayValue($FIELD_MODEL->get('fieldvalue'))}

<div class="input-group">
<input id="{$MODULE}_editView_fieldName_{$FIELD_NAME}" type="text" class="inputElement dateField form-control {if $IGNOREUIREGISTRATION}ignore-ui-registration{/if}" data-fieldname="{$FIELD_NAME}" data-fieldtype="date" name="{$FIELD_NAME}" data-date-format="{$dateFormat}" 
	{if empty($value)}placeholder="{$dateFormat}" {else}
    value="{$FIELD_MODEL->getEditViewDisplayValue($FIELD_MODEL->get('fieldvalue'))}" {/if}  {if !empty($SPECIAL_VALIDATOR)}data-validator='{Zend_Json::encode($SPECIAL_VALIDATOR)}'{/if}
    {if $MODE eq 'edit' && $FIELD_NAME eq 'due_date'} data-user-changed-time="true" {/if}
    {if $FIELD_INFO["mandatory"] eq true} data-rule-required="true" {/if}
    {if count($FIELD_INFO['validator'])}
        data-specific-rules='{ZEND_JSON::encode($FIELD_INFO["validator"])}'
    {/if}  data-rule-date="true" />
     {assign var=map_array value=array('Quotes')}
					    {if $MODULE_NAME|in_array:$map_array}
<span class="{if in_array($MODULE,array('Quotes','Calendar'))} input-group-addon new-icon-data {else}input-group-addon  new-date {/if}"><i class="fa fa-calendar "></i></span>
{else}
<span class="{if in_array($MODULE, array('Contacts','Products'))}input-group-addon date-icon {elseif in_array($MODULE,array('Potentials','Campaigns','SalesOrder','PurchaseOrder','Calendar','Services','Invoice','Events'))} input-group-addon new-icon-data ram {else} input-group-addon new-date{/if}"><i class="fa fa-calendar "></i></span>
{/if}
</div>
{/strip}
