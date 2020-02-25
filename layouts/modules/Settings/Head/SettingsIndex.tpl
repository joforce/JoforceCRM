{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{strip}
    {assign var=SETTINGS_MENU_LIST value=Settings_Head_Module_Model::getSettingsMenuListForNonAdmin()}
    <div class="col-lg-12 col-sm-12 col-md-12 admin-settings mt40">
	{foreach item=BLOCK_MENUS key=BLOCK_NAME from=$SETTINGS_MENU_LIST}
	    {assign var=NUM_OF_MENU_ITEMS value= $BLOCK_MENUS|@sizeof}
	    {if $NUM_OF_MENU_ITEMS gt 0}
		<div>
		    <p class="horizontal-hr"><span>{vtranslate($BLOCK_NAME ,$MODULE)}</span></p>
		    <ul class="settings-list">
			{foreach item=URL key=MENU from=$BLOCK_MENUS}
			    {assign var=ICON value=$ICONS_ARRAY[$MENU]}
			    {assign var=MENU_URL value='#'}
			    {assign var=MENU_LABEL value=$MENU}
			    {if $MENU eq 'My Preferences'}
				{assign var=MENU_URL value=$USER_MODEL->getPreferenceDetailViewUrl()}
			    {elseif $MENU eq 'Calendar Settings'}
				{assign var=MENU_URL value=$USER_MODEL->getCalendarSettingsDetailViewUrl()}
			    {elseif $MENU === $URL}
				{if $SETTINGS_MENU_ITEMS[$MENU]}
				    {assign var=MENU_URL value=$SETTINGS_MENU_ITEMS[$MENU]->getURL()}
				{/if}
			    {elseif is_string($URL)}
				{assign var=MENU_URL value=$URL}
			    {/if}

			    <li>
				<a href="{$MENU_URL}">
				    <label><span><i class="{$ICON}" data-fieldid=""></i></span></label>
				    <p>{vtranslate($MENU_LABEL,$QUALIFIED_MODULE)}</p>
				</a>
                            </li>
			{/foreach}
		    </ul>
		</div>
	    {/if}
	{/foreach}
    </div>
{/strip}
