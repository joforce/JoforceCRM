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
    <div>
	{foreach item=NOTIFICATION from=$CURRENT_USER_NOTIFICATONS key=COUNT}
	    {assign var=notification_id value=$NOTIFICATION['id']}
	    {assign var=notify_module value=$NOTIFICATION['module_name']}
            {assign var=notify_module_model value=Head_Module_Model::getModuleInstanceById(getTabid($notify_module))}
            {assign var=singular_module_name value=$notify_module_model->getSingularLabelKey()}

            {assign var=notify_recordid value=$NOTIFICATION['entity_id']}
            {assign var=RECORD_MODEL value=Head_Record_Model::getInstanceById($notify_recordid, $notify_module)}
            {assign var=detail_url value=$RECORD_MODEL->getDetailViewUrl()}

            {assign var=editor_id value=$NOTIFICATION['user_id']}
            {assign var=editor_modal value=Users_Record_Model::getInstanceById($editor_id, 'Users')}
            {assign var=modifierName value=getUserFirstAndLastName($editor_id)}

            {assign var=notify_action value=$NOTIFICATION['action_type']}
            {assign var=namefields value=$notify_module_model->getNameFields()}

	    {if $COUNT < 5}
		{assign var=EDITOR value=getUserName($NOTIFICATION['user_id'])}
		{if $NOTIFICATION_MODULE == 'Calendar'}
		    {assign var=MODULE value=vtranslate(vtlib_toSingular('Tasks'), $NOTIFICATION['module_name'])}
		{else}
		    {assign var=MODULE value=vtranslate(vtlib_toSingular($NOTIFICATION['module_name']), $NOTIFICATION['module_name'])}
		{/if}
		<a {if $notify_action neq 'Deleted'} href="{$detail_url}" style="color:green;" {/if}>
                    {foreach item=NAME_FIELD from=$namefields}
                        <span class="{$NAME_FIELD}">{$RECORD_MODEL->get($NAME_FIELD)}</span>&nbsp;
                    {/foreach}
                </a>
                <p>
                    <span><b>{$modifierName}</b></span>
                    {if $notify_action eq 'Created'}
                        <i> {$notify_action} a {vtranslate($singular_module_name , $notify_module)} </i>
                    {elseif $notify_action eq 'Assignee Changed'}
                        {assign var=oldassignee value=getUserFirstAndLastName($NOTIFICATION['oldvalue'])}
                        {if $current_user_id eq $NOTIFICATION['newvalue']}
                            {assign var=newassignee value='You'}
                        {else}
                            {assign var=newassignee value=getUserFirstAndLastName($NOTIFICATION['newvalue'])}
                        {/if}
                        <i> Changed the Assignee of {vtranslate($singular_module_name , $notify_module)} from <b>{$oldassignee}</b> to <b>{$newassignee}</b>
                        </i>
                    {elseif $notify_action eq 'Created and Assigned'}
                        <i> Created and Assigned a {vtranslate($singular_module_name , $notify_module)} to <b>You </b></i>
                    {elseif $notify_action eq 'Updated'}
                        {assign var=fieldinstance value=Head_Field_Model::getInstance($NOTIFICATION['fieldname'], $notify_module_model)}
                        {assign var=field_label value=$fieldinstance->label}
                        <i> {$notify_action} the {vtranslate($singular_module_name , $notify_module)} field  {vtranslate($field_label, $notify_module)} 
			    {if empty({$NOTIFICATION['oldvalue']})}
				as {$NOTIFICATION['newvalue']} 
			    {else}
				from {$NOTIFICATION['oldvalue']} to {$NOTIFICATION['newvalue']}
			    {/if}
                        </i>
                    {else}
			{$notify_action} {vtranslate($singular_module_name , $notify_module)}
                    {/if}
                </p>
	    {/if}
	{/foreach}

	{if $NOTIFICATIONS_COUNT == 0}
	    <p>{vtranslate("All caught up!", "Home")}</p>	
	{else}
	    <hr>	
	{/if}
	
	{assign var=moduleid value=getTabid($NOTIFICATION_MODULE)}
	{assign var=module_model value=Head_Module_Model::getModuleInstanceById($moduleid)}

	<a class="show-all-notifications" id="show-all-notifications"> show all Notifications </a>
    </div>
{/strip}
