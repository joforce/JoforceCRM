{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
 <div class="col-sm-12 col-xs-12 mt0 " id="settingsmenu-starts"  style="">
 <div class=" googleaccount card mt30">
    <div class="row ">
	<div class="col-sm-12 col-xs-12  card-header-new">
	    <h3 class="module-title pull-left"> {vtranslate('LBL_SELECT_MODULES_TO_SYNC', $MODULE)} </h3>
	    {if $CURRENT_USER_MODEL->isAdminUser()}
		<a href="{$SITEURL}Google/Settings/GoogleSettings/4/39" target="_blank" style="float:right;color: white !important;" class="btn btn-primary saveButton">Configure Google Settings</a>
	    {/if}
        </div>
    </div>
    <br>
    <form name="settingsForm" action="{$SITEURL}index.php" method="POST" >
        <input type="hidden" name="module" value="{$MODULE}" />
        <input type="hidden" name="action" value="SaveSyncSettings" />
        <input type="hidden" name="sourceModule" value="{$SOURCEMODULE}" />
        <input type="hidden" name="parent" value="{$PARENT}">
        <input type="hidden" name="block" value="{$BLOCKID}">
        <input type="hidden" name="fieldid" value="{$FIELDID}">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
            <table class="listview-table table-bordered listbg" align="center">
                   <thead>
                      <th> {vtranslate($MODULE, $MODULE)} {vtranslate('LBL_DATA', $MODULE)} </th>
                      <th> {vtranslate('APPTITLE', $MODULE)} {vtranslate('LBL_DATA', $MODULE)} </th>
                      <th> {vtranslate('LBL_FIELD_MAPPING', $MODULE)} </th> 
                      <th> {vtranslate('LBL_ENABLE_SYNC', $MODULE)} </th>
                      <th> {vtranslate('LBL_SYNC_DIRECTION', $MODULE)} </th>
                   </thead>
                   <tbody>
                        <tr>
                            <td>
                            <select name="Contacts[google_group]" class="inputElement select2 row boder" style="min-width: 250px;border:none !important;">
                                    <option value="all">{vtranslate('LBL_ALL',$MODULENAME)}</option>
                                    {assign var=IS_GROUP_DELETED value=1}
                                    {foreach item=ENTRY from=$GOOGLE_CONTACTS_GROUPS['entry']}
                                        <option value="{$ENTRY['id']}" {if $ENTRY['id'] eq $SELECTED_CONTACTS_GROUP} {assign var=IS_GROUP_DELETED value=0} selected {/if}>{$ENTRY['title']}</option>
                                    {/foreach}
                                    {if $IS_GROUP_DELETED && $SELECTED_CONTACTS_GROUP != 'all'}
                                        {if $SELECTED_CONTACTS_GROUP != ''}<option value="none" selected>{vtranslate('LBL_NONE',$MODULENAME)}</option>{/if}
                                    {/if}
                                </select>
                            </td>
                            <td>{vtranslate('Contacts', 'Contacts')}</td>
                            <td><a id="syncSetting" class="extensionLink" data-sync-module="Contacts">{vtranslate('LBL_CONFIGURE', $MODULE)}</a></td>
                            <td><input name="Contacts[enabled]" type="checkbox" {if $CONTACTS_ENABLED} checked {/if}></td>
                            <td>
                            <select name="Contacts[sync_direction]" class="inputElement select2 row" style="min-width: 250px;border:none !important;">
                                    <option value="11" {if $CONTACTS_SYNC_DIRECTION eq 11} selected {/if}> {vtranslate('LBL_SYNC_BOTH_WAYS', $MODULE)} </option>
                                    <option value="10" {if $CONTACTS_SYNC_DIRECTION eq 10} selected {/if}> {vtranslate('LBL_SYNC_FROM_GOOGLE_TO_JOFORCE', $MODULE)} </option>
                                    <option value="01" {if $CONTACTS_SYNC_DIRECTION eq 01} selected {/if}> {vtranslate('LBL_SYNC_FROM_JOFORCE_TO_GOOGLE', $MODULE)} </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                            <select name="Calendar[google_group]" class="inputElement select2 row" style="min-width: 250px;border:none !important;">
                                    {if count($GOOGLE_CALENDARS) eq 0}
                                        <option value="primary">{vtranslate('LBL_PRIMARY',$MODULENAME)}</option>
                                    {/if}
                                    {foreach item=CALENDAR_ITEM from=$GOOGLE_CALENDARS}
                                        <option value="{if $CALENDAR_ITEM['primary'] eq 1}primary{else}{$CALENDAR_ITEM['id']}{/if}" {if $SELECTED_GOOGLE_CALENDAR eq $CALENDAR_ITEM['id']}selected{/if} {if $SELECTED_GOOGLE_CALENDAR eq 'primary' && $CALENDAR_ITEM['primary'] eq 1} selected {/if}>{$CALENDAR_ITEM['summary']}</option>
                                    {/foreach}
                                </select>
                            </td>
                            <td>{vtranslate('Calendar', 'Calendar')}</td>
                            <td><a id="syncSetting" class="extensionLink" data-sync-module="Calendar">{vtranslate('LBL_VIEW', $MODULE)}</a></td>
                            <td><input name="Calendar[enabled]" type="checkbox" {if $CALENDAR_ENABLED} checked {/if}></td>
                            <td>
                            <select name="Calendar[sync_direction]" class="inputElement select2 row" style="min-width: 250px;border:none !important;">
                                    <option value="11" {if $CALENDAR_SYNC_DIRECTION eq 11} selected {/if}> {vtranslate('LBL_SYNC_BOTH_WAYS', $MODULE)} </option>
                                    <option value="10" {if $CALENDAR_SYNC_DIRECTION eq 10} selected {/if}> {vtranslate('LBL_SYNC_FROM_GOOGLE_TO_JOFORCE', $MODULE)} </option>
                                    <option value="01" {if $CALENDAR_SYNC_DIRECTION eq 01} selected {/if}> {vtranslate('LBL_SYNC_FROM_JOFORCE_TO_GOOGLE', $MODULE)} </option>
                                </select>
                            </td>
                        </tr>
                   </tbody>
               </table>
            </div>
            <div id="scroller_wrapper" class="bottom-fixed-scroll">
                <div id="scroller" class="scroller-div"></div>
            </div>
            <div class="col-sm-2 col-xs-12">
            </div>
        </div>
        <br>
        {if !$IS_SYNC_READY}
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <h3 class="module-title pull-left"> {vtranslate('LBL_GOOGLE_CONNECT_MSG', $MODULE)} </h3>
                    </div>
                </div>
                <br>
                <div class="row">
                <div class="col-sm-6 col-xs-12 row">
                <a id="authorizeButton" class="btn btn-block btn-social btn-lg btn-google-plus" data-url='{$SITEURL}index.php?module={$MODULE}&view=List&operation=sync&sourcemodule={$SOURCEMODULE}'>
				<span class="google"><i class="fa fa-google-plus"></i></span>
				<span class="ml30 siging">{vtranslate('LBL_SIGN_IN_WITH_GOOGLE', $MODULE)}</span>
			    </a>
                    </div>
                </div>
            {else}
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <h3 class="module-title pull-left"> {vtranslate('LBL_GOOGLE_ACCOUNT_DETAILS', $MODULE)} </h3>
                    </div>
                </div>
                <br>
                {if $USER_EMAIL}
                    <div class="row">
                        <div class="col-sm-3 col-xs-12">
                            <h5 class="module-title pull-left fieldLabel"> {vtranslate('LBL_GOOGLE_ACCOUNT_SYNCED_WITH', $MODULE)}  </h5>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <input class="listSearchContributor col-sm-12 col-xs-12" type="text" value="{$USER_EMAIL}" disabled="disabled" style="height: 30px;">
                        </div>
                    </div>
                {/if}
                <div class="row" style="margin-top: 10px;">
                    <div class="col-sm-3 col-xs-12">
                        &nbsp;
                    </div>
                    <div class="col-sm-3 col-xs-12">
                        <a id="authorizeButton" class="btn btn-block btn-social btn-lg btn-google-plus" data-url='index.php?module={$MODULE}&view=List&operation=changeUser&sourcemodule={$SOURCEMODULE}'><i class="fa fa-google-plus"></i><span class="ml30"> {vtranslate('LBL_CHANGE_USER', $MODULE)} </span></a>
                    </div>
                </div>
        {/if}
        <div>
            <div>
                <button id="saveSettings" type="submit" class="btn btn-primary saveButton float-right">{vtranslate('LBL_SAVE_SETTINGS', $MODULENAME)}</button>
                {if $PARENT neq 'Settings'}
                    <a type="" href="{$MODULE_MODEL->getBaseExtensionUrl($SOURCEMODULE)}" class="cancelLink btn btn-danger navigationLink">{vtranslate('LBL_CANCEL', $MODULENAME)}</a>
                {/if}
            </div>
        </div>
    </form>
</div>
