{if $NOTIFICATONS_COUNT eq 0}
    <li class='empty_notification_li' style="margin: auto; width: 250px;height:250px;">
	<img src="{$SITEURL}layouts/skins/images/notification/ezgif.com-crop.gif" alt="No Notifications" class='empty_notification_image' id='empty_notification_image' />
    </li>
{else}
    {foreach item=NOTIFICATION from=$CURRENT_USER_NOTIFICATONS key=key}
	{assign var=notification_id value=$NOTIFICATION['id']}
	{assign var=notify_module value=$NOTIFICATION['module_name']}
	{assign var=notify_module_model value=Head_Module_Model::getModuleInstanceById(getTabid($notify_module))}
	{assign var=singular_module_name value=$notify_module_model->getSingularLabelKey()}

	{assign var=notify_recordid value=$NOTIFICATION['entity_id']}
	{assign var=notify_action value=$NOTIFICATION['action_type']}

        {if !isEntityDeleted($NOTIFICATION['entity_id'])}
            {assign var=RECORD_MODEL value=Head_Record_Model::getInstanceById($notify_recordid, $notify_module)}
            {assign var=detail_url value=$RECORD_MODEL->getDetailViewUrl()}
        {else}
            {if $notify_action neq 'Deleted'}
                {assign var=notify_action value='Deleted Record Action'}
            {/if}
        {/if}

	{assign var=editor_id value=$NOTIFICATION['user_id']}
	{assign var=editor_modal value=Users_Record_Model::getInstanceById($editor_id, 'Users')}
	{assign var=modifierName value=getUserFirstAndLastName($editor_id)}

	{assign var=namefields value=$notify_module_model->getNameFields()}
	<li class="global-notifications col-lg-12" data-notificationid="{$notification_id}" id="notification_number_{$notification_id}" {if $NOTIFICATION['is_seen']} style="background:#f0f0f0;"{/if}>
	    <div class='col-lg-10'>
		{if $notify_action eq 'Deleted'}
                        {$notify_action} {vtranslate($singular_module_name , $notify_module)}
		{elseif $notify_action eq 'Deleted Record Action'}
			Action of deleted record - {vtranslate($singular_module_name , $notify_module)}
                {else}
	    	<a {if $notify_action neq 'Deleted'} href="{$detail_url}" {/if}>
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
                        <i> {$notify_action} the {vtranslate($singular_module_name , $notify_module)} field {vtranslate($field_label, $notify_module)} 
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
	    	<p>{Head_Datetime_UIType::getDateTimeValue($RECORD_MODEL->get('modifiedtime'))}</p>
		{/if}
	    </div>
	    <div class='col-lg-2'>
            	<span id='noti_marker_{$notification_id}' class="mark-as-seen {if $NOTIFICATION['is_seen']} seen_notification{/if}" data-notificationid="{$notification_id}"><i class="fa fa-check"></i><span>
	    </div>
    	</li>
    {/foreach}
    <li class="global-notifications global-notifications-actions col-lg-12">
	<div class='col-lg-6'>
	    <a id="mark-all-as-read">{vtranslate('LBL_MARK_ALL_AS_READ', 'Home')} </a>
	</div>

        <div class='col-lg-6'>
	    <a id='clear-all-notifications'> {vtranslate('Clear all Notifications', 'Home')} </a>
        </div>
    </li>
{/if}
