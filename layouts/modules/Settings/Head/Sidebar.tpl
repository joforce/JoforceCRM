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
    {if $USER_MODEL->isAdminUser()}
	{assign var=SETTINGS_MODULE_MODEL value= Settings_Head_Module_Model::getInstance()}
	{assign var=SETTINGS_MENUS value=$SETTINGS_MODULE_MODEL->getMenus()}
	<div class="settingsgroup {if in_array($MODULE,array('Workflows','Groups','Webforms','Webhooks','MailConverter','Currency','PickListDependency','Users'))} {if $smarty.request.view eq 'Edit'} {if $MODULE neq 'MailConverter'} setting_grp_search {/if} {/if} {else} setting_grp_search  {/if}">
	    <div>
		<input type="text" placeholder="{vtranslate('LBL_SEARCH_FOR_SETTINGS', $QUALIFIED_MODULE)}" class="search-list col-lg-12" id='settingsMenuSearch'>
	    </div>
	    <div class="panel-group hide" id="accordion" role="tablist" aria-multiselectable="true">
		{foreach item=BLOCK_MENUS from=$SETTINGS_MENUS}
		    {assign var=BLOCK_NAME value=$BLOCK_MENUS->getLabel()}
		    {assign var=BLOCK_MENU_ITEMS value=$BLOCK_MENUS->getMenuItems()}
		    {assign var=NUM_OF_MENU_ITEMS value= $BLOCK_MENU_ITEMS|@sizeof}
		    {if $NUM_OF_MENU_ITEMS gt 0}
			<div class="settingsgroup-panel col-lg-12 p0 panel panel-default instaSearch">
			    <div id="{$BLOCK_NAME}_accordion" class="app-nav" role="tab">
				<div class="app-settings-accordion">
				    <div class="settingsgroup-accordion">
					<a data-toggle="collapse" data-parent="#accordion" class='collapsed' href="#{$BLOCK_NAME}">
					    <span>{vtranslate($BLOCK_NAME,$QUALIFIED_MODULE)}</span>
					    <i class="indicator fa{if $ACTIVE_BLOCK['block'] eq $BLOCK_NAME} fa-chevron-down {else} fa-chevron-right {/if}"></i>&nbsp;
					</a>
				    </div>
				</div>
			    </div>
			    <div id="{$BLOCK_NAME}" class="panel-collapse collapse ulBlock {if $ACTIVE_BLOCK['block'] eq $BLOCK_NAME} in {/if}">
				<ul class="list-group widgetContainer ramchandru">
				    {foreach item=MENUITEM from=$BLOCK_MENU_ITEMS}
					{assign var=MENU value= $MENUITEM->get('name')}
					{assign var=MENU_LABEL value=$MENU}
					{if $MENU eq 'LBL_EDIT_FIELDS'}
					    {assign var=MENU_LABEL value='LBL_MODULE_CUSTOMIZATION'}
					{elseif $MENU eq 'LBL_TAX_SETTINGS'}
					    {assign var=MENU_LABEL value='LBL_TAX_MANAGEMENT'}
					{elseif $MENU eq 'INVENTORYTERMSANDCONDITIONS'}
					    {assign var=MENU_LABEL value='LBL_TERMS_AND_CONDITIONS'}
					{/if}

					{assign var=MENU_URL value=$MENUITEM->getUrl()}
					{assign var=USER_MODEL value=Users_Record_Model::getCurrentUserModel()}
					{if $MENU eq 'My Preferences'}
					    {assign var=MENU_URL value=$USER_MODEL->getPreferenceDetailViewUrl()}
					{elseif $MENU eq 'Calendar Settings'}
					    {assign var=MENU_URL value=$USER_MODEL->getCalendarSettingsDetailViewUrl()}
					<!-- Custom URL for PickList Dependency - Added by Fredrick Marks -->
					{elseif $MENU eq 'LBL_PICKLIST_DEPENDENCY'}
					    {assign var=MENU_URL value=$USER_MODEL->getPicklistDependencyViewUrl()}
					{/if}
					<li>
					    <a data-name="{$MENU}" href="{if $BLOCK_NAME=='LBL_LOGS'} {$SITEURL}PBXManager/view/List {else} {$MENU_URL} {/if}" class="menuItemLabel {if $ACTIVE_BLOCK['menu'] eq $MENU} settingsgroup-menu-color {/if}">
						<i class="{$MENUITEM->get('iconpath')}"></i>
						{vtranslate($MENU_LABEL,$QUALIFIED_MODULE)}
					    </a>
					</li>
				    {/foreach}
				</ul>
			    </div>
			</div>
		    {/if}
		{/foreach}
	    </div>
	</div>
    {else}
	{if $VIEW neq 'SettingsIndex' && $MODULE neq 'Head'}
	    {include file='modules/Users/UsersSidebar.tpl'}
	{/if}
    {/if}
{/strip}
