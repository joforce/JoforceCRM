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
	<div class="col-lg-12 col-sm-12 col-md-12 admin-settings m0 p0">

	    {foreach item=BLOCK_MENUS from=$SETTINGS_MENUS}
        	{assign var=BLOCK_NAME value=$BLOCK_MENUS->getLabel()}
                {assign var=BLOCK_MENU_ITEMS value=$BLOCK_MENUS->getMenuItems()}
                {assign var=NUM_OF_MENU_ITEMS value= $BLOCK_MENU_ITEMS|@sizeof}
                {if $NUM_OF_MENU_ITEMS gt 0}
		    <div class = "module_search">
                        <p class="horizontal-hr mb30"><span><b>{if vtranslate($BLOCK_NAME ,$MODULE)=='My Preferences'}CRM Preferences{else}{vtranslate($BLOCK_NAME ,$MODULE)} {/if}</b></span></p>
						<hr>
			<ul class="settings-list row mb50">
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
				{elseif $MENU eq 'LBL_PBXMANAGER'}
				    {assign var=MENU_URL value=$site_URL|cat:$MENUITEM->get('linkto')}
				{elseif $MENU eq 'LBL_RECYCLEBIN'}
				    {assign var=MENU_URL value=$site_URL|cat:$MENUITEM->get('linkto')}
                                {/if}
				{if vtranslate($MENU_LABEL,$QUALIFIED_MODULE)!='My Preferences'}
				<div class="col-lg-6 col-md-6 col-sm-12 d-flex newSearch">
				<a class = "ref_search d-flex" {if vtranslate($MENU_LABEL,$QUALIFIED_MODULE)=='Reports'} href="{$SITEURL}Reports/view/List {else} href="{$MENU_URL} {/if}">
				<li class = "list_values">
				    {* <a class = "ref_search" href="{$MENU_URL}"> *}
					<label>
					    <span>
						<i class="{$ICON} icon_search" data-fieldid="{$MENUITEM->get('fieldid')}"></i>
					    </span>
					</label>
					{* <p class = "para_list">{vtranslate($MENU_LABEL,$QUALIFIED_MODULE)}</p> *}
				    {* </a> *}
					
				</li>
				<div class="icons-hover">
				<p class = "para_list mt20 m0 mb5"><b>{vtranslate($MENU_LABEL,$QUALIFIED_MODULE)}</b></p>
				<span>{$MENUITEM->get('setting_detail')}</span>
				</div>
				</a>
				
				</div>
				{/if}
			    {/foreach}
			</ul>
		    </div>
		{/if}
	    {/foreach}
	</div>
	<div id = "search_display" class="search_display col-lg-12 col-sm-12 col-md-12 m0 p0">
	    <ul class = "result_found row"></ul>
	</div>
{/strip}
