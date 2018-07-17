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
	{foreach item=NOTIFICATION key=COUNT from=$NOTIFICATIONS}
		{if $COUNT < 5}
			{assign var=EDITOR value=getUserName($NOTIFICATION['user_id'])}

			{if $NOTIFICATION_MODULE == 'Calendar'}
				{assign var=MODULE value=vtranslate(vtlib_toSingular('Tasks'), $NOTIFICATION['module_name'])}
			{else}
				{assign var=MODULE value=vtranslate(vtlib_toSingular($NOTIFICATION['module_name']), $NOTIFICATION['module_name'])}
			{/if}

			{assign var=ACTION value=$NOTIFICATION['action_type']}
			{assign var=isdeleted value=isEntityDeleted($NOTIFICATION['entity_id'])}
			{if !$isdeleted}
				{assign var=RECORD_MODEL value=Head_Record_Model::getInstanceById($NOTIFICATION['entity_id'], $NOTIFICATION_MODULE)}
				{if $ACTION == 'Created'}
					<p><a href="{$RECORD_MODEL->getDetailViewUrl()}">{$EDITOR} Assigned a {$MODULE} to you</a></p>
				{elseif $ACTION == 'Updated'}
					<p><a href="{$RECORD_MODEL->getDetailViewUrl()}">{$EDITOR} Updated a {$MODULE} </a></p>
				{else}
					<p>
						{assign var=record_assignee_id value=$RECORD_MODEL->get('assigned_user_id')}
						{assign var=assignee_name value=getUserName($record_assignee_id)}
						<a href="{$RECORD_MODEL->getDetailViewUrl()}">
							{if $record_assignee_id == $CURRENT_USER_ID}
								{$EDITOR} assigned  a {$MODULE} to You
							{else}
								{$EDITOR} changed the assignee of a {$MODULE} to {$assignee_name}
							{/if}
						</a>
					</p>
				{/if}
			{else}
				<p> Record Deleted </p>
			{/if}
		{/if}
	{/foreach}

	{if $NOTIFICATIONS_COUNT == 0}
		<p>{vtranslate("All caught up!", "Home")}</p>	
	{else}
		<hr>	
	{/if}
	
	{assign var=moduleid value=getTabid($NOTIFICATION_MODULE)}
	{assign var=module_model value=Head_Module_Model::getModuleInstanceById($moduleid)}

	{if $NOTIFICATION_MODULE == 'Events'}
		<a class="" id="" href="{$module_model->getListViewUrl()}?clear_notification=true&is_event_module=true"> show all </a>
	{else}
		<a class="" id="" href="{$module_model->getListViewUrl()}?clear_notification=true"> show all </a>
	{/if}
</div>
{/strip}
