{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{strip}
<!--LIST VIEW RECORD ACTIONS-->

<div class="table-actions">
	{if !$SEARCH_MODE_RESULTS}
    <span class="input" >
        <input type="checkbox" value="{$LISTVIEW_ENTRY->getId()}" class="listViewEntriesCheckBox"/>
    </span>
    {/if}
    {if $LISTVIEW_ENTRY->get('starred') eq 'Yes'}
        {assign var=STARRED value=true}
    {else}
        {assign var=STARRED value=false}
    {/if}
        
    {if $MODULE_MODEL->isStarredEnabled()}
        <span class="markStar fa icon action {if $STARRED} fa-star active {else} fa-star-o{/if}" title="{if $STARRED} {vtranslate('LBL_STARRED', $MODULE)} {else} {vtranslate('LBL_NOT_STARRED', $MODULE)}{/if}"></span>
    {/if}

</div>
