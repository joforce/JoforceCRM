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
{assign var=moduleid value=getTabid($module)}
{assign var=module_model value=Head_Module_Model::getModuleInstanceById($moduleid)}
{assign var=image_name value=strtolower($module)}
{assign var=image_path value="notification/{$image_name}.png"}

{if in_array($module, $EXCEPTION_ARRAY)}
	{if $module == 'Task'}
		{assign var=permission value=$USER_PRIVILEGE_MODEL->hasModulePermission(getTabid('Calendar'))}
		<a href="{$SITEURL}Calendar/view/List" data-module="Calendar" class="notification-link {if !$permission} disable {/if}" >
			<div class="dash-icons">
        			<img src="{$SITEURL}{vimage_path($image_path)}" alt="">
		        </div>
        		<span>{vtranslate($module, $module)}</span>
	        	{if $NOTIFICATIONS_COUNT_ARRAY['Calendar']}
        			<div class="count">{$NOTIFICATIONS_COUNT_ARRAY['Calendar']}</div>
			{else}
				<div class="count zero">0</div>
		        {/if}
		</a>
	{else}
		{assign var=permission value=$USER_PRIVILEGE_MODEL->hasModulePermission($moduleid)}	
		<a href="" data-module="{$module}" class="notification-link {if !$permission} disable {/if}">
                        <div class="dash-icons">
                                <img src="{$SITEURL}{vimage_path($image_path)}" alt="">
                        </div>
                        <span>{vtranslate($module, $module)}</span>
                </a>
	{/if}
{else}
	{assign var=permission value=$USER_PRIVILEGE_MODEL->hasModulePermission($moduleid)}
	<a href="{$module_model->getListViewUrl()}?clear_notification=true" data-module="{$module}" class="notification-link {if !$permission} disable {/if}">
                <div class="dash-icons">
                        <img src="{$SITEURL}{vimage_path($image_path)}" alt="">
                </div>
		{if $module == 'PBXManager'}
			<span>Calls</span>
		{else}
	                <span>{vtranslate($module, $module)}</span>
		{/if}
                {if $NOTIFICATIONS_COUNT_ARRAY[$module]}
                        <div class="count">{$NOTIFICATIONS_COUNT_ARRAY[$module]}</div>
		{else}
                        <div class="count zero">0</div>
                {/if}
        </a>
{/if}
{/strip}
