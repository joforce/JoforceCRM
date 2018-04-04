{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
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
	<div class="col-lg-12 col-sm-12 col-md-12 admin-settings mt40">
	{foreach item=BLOCK_MENUS from=$SETTINGS_MENUS}
        	{assign var=BLOCK_NAME value=$BLOCK_MENUS->getLabel()}
                {assign var=BLOCK_MENU_ITEMS value=$BLOCK_MENUS->getMenuItems()}
                {assign var=NUM_OF_MENU_ITEMS value= $BLOCK_MENU_ITEMS|@sizeof}
                {if $NUM_OF_MENU_ITEMS gt 0}
			<div>
                        <p class="horizontal-hr"><span>{vtranslate($BLOCK_NAME ,$MODULE)}</span></p>
			<ul class="settings-list">
			{foreach item=MENUITEM from=$BLOCK_MENU_ITEMS}
	                        {assign var=MENU value= $MENUITEM->get('name')}
                                {assign var=MENU_LABEL value=$MENU}	
				{assign var=ICON value=$MENUITEM->get('iconpath')}
				{if empty($ICON)}
					{assign var=ICON value="fa fa-cubes"}
				{/if}
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
                                {/if}
				<li>
					<a href="{$MENU_URL}"><label><span><i class="{$ICON}" data-fieldid="{$MENUITEM->get('fieldid')}"></i></span></label>
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
