{************************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************}
{strip}
{if count($TAGS[1]) gt 0}
    <div class="tagsContainer" id="tagCloud">
	{foreach from=$TAGS[1] item=TAG_ID key=TAG_NAME}
	        <div class=" textOverflowEllipsis col-sm-4" title="{$TAG_NAME}">
			<a class="tagName cursorPointer" data-tagid="{$TAG_ID}" rel="{$TAGS[0][$TAG_NAME]}">{$TAG_NAME}</a>&nbsp;		
                </div>
	{/foreach}
     </div>
{else}
        <span class="noDataMsg">
                {vtranslate('LBL_NO')} {vtranslate('LBL_TAGS', $MODULE_NAME)} {vtranslate('LBL_MATCHED_THIS_CRITERIA')}
        </span>
{/if}
{/strip}	
