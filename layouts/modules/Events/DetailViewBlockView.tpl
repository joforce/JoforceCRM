{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
   <input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
{/if}
{include file='DetailViewBlockView.tpl'|@vtemplate_path:'Head' RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE_NAME=$MODULE_NAME}
<div class="block block_LBL_INVITE_USER_BLOCK  {if in_array($MODULE,array('Calendar'))}   calender_page_block_view  {/if}">
    {assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
    {assign var="IS_HIDDEN" value=false}
    {assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}

    <div class='detailhead' style="background: #f7f7f7;color: #264da2;padding: 10px 20px 10px 20px;-webkit-border-top-right-radius: 7px;-webkit-border-top-left-radius: 7px;">
        <h4 class="textOverflowEllipsis maxWidth50">
        <img class="cursorPointer alignMiddle blockToggle {if !($IS_HIDDEN)} hide {/if}" src="{$SITEURL}{vimage_path('arrowRight.png')}" data-mode="hide" data-id={$BLOCK_LIST[$BLOCK_LABEL_KEY]->get('id')}>
					<img class="cursorPointer alignMiddle blockToggle {if ($IS_HIDDEN)} hide {/if}" src="{$SITEURL}{vimage_path('arrowdown.png')}" data-mode="show" data-id={$BLOCK_LIST[$BLOCK_LABEL_KEY]->get('id')}>&nbsp;
        {vtranslate('LBL_INVITE_USER_BLOCK',{$MODULE_NAME})}</h4>
    </div>

    <div class="blockData">
        <table class="table detailview-table no-border">
            <tbody>
                <tr>
                    <td class="fieldLabel {$WIDTHTYPE}">
                        <span class="muted">{vtranslate('LBL_INVITE_USERS', $MODULE)}</span>
                    </td>
                    <td class="fieldValue {$WIDTHTYPE}">
                        {foreach key=USER_ID item=USER_NAME from=$ACCESSIBLE_USERS}
                            {if in_array($USER_ID,$INVITIES_SELECTED)}
                                {$USER_NAME} - {vtranslate($INVITEES_DETAILS[$USER_ID],$MODULE)}
                                <br>
                            {/if}
                        {/foreach}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
{/strip}