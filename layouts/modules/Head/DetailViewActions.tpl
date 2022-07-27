{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
********************************************************************************/
-->*}
{strip}
        <div class="pull-right btn-toolbar joforce-detailview-btn">
            <span class="btn-group">
            {assign var=STARRED value=$RECORD->get('starred')}
            {if $MODULE_MODEL->isStarredEnabled()}
                <button class="btn btn-default markStar {if $STARRED} active {/if}" id="starToggle">
                    <div class='starredStatus' title="{vtranslate('LBL_STARRED', $MODULE)}">
                        <div class='unfollowMessage' data-label="{vtranslate('LBL_UNFOLLOW',$MODULE)}">
                            <i class="fa fa-star-o"></i>
                        </div>
                        <div class='followMessage' data-label="{vtranslate('LBL_FOLLOWING',$MODULE)}">
                            <i class="fa fa-star active"></i>
                        </div>
                    </div>
                    <div class='unstarredStatus fa fa-star-o' title="{vtranslate('LBL_NOT_STARRED', $MODULE)}" data-label="{vtranslate('LBL_FOLLOW',$MODULE)}"></div>
                </button>
            {/if}
            </span>

            {foreach item=DETAIL_VIEW_BASIC_LINK from=$DETAILVIEW_LINKS['DETAILVIEWBASIC']}
            <span class="btn-group detailview-basic-actions">
                <button class="btn btn-default {if $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_EDIT'} edit {elseif $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_SEND_EMAIL'} email {elseif $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_ACTIVITY'} activity {/if}" id="{$MODULE_NAME}_detailView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_BASIC_LINK->getLabel())}" title="{vtranslate($DETAIL_VIEW_BASIC_LINK->getLabel(), $MODULE_NAME)}"
                        {if $DETAIL_VIEW_BASIC_LINK->isPageLoadLink()}
                            onclick="window.location.href = '{URLCheck($DETAIL_VIEW_BASIC_LINK->getUrl())}'"
                        {else}
                            onclick="{$DETAIL_VIEW_BASIC_LINK->getUrl()}"
                        {/if}

                        {if $MODULE_NAME eq 'Documents' && $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_VIEW_FILE'}
                            data-filelocationtype="{$DETAIL_VIEW_BASIC_LINK->get('filelocationtype')}" data-filename="{$DETAIL_VIEW_BASIC_LINK->get('filename')}"
                        {/if}>
			{if $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_EDIT'}
			    <i class="fa fa-pencil"></i>
			{elseif $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_SEND_EMAIL'}
			    <i class="fa fa-paper-plane"></i>
			{elseif $DETAIL_VIEW_BASIC_LINK->getLabel() eq 'LBL_ACTIVITY'}
			    <i class="fa fa-history"></i>
			{else}
	                    {vtranslate($DETAIL_VIEW_BASIC_LINK->getLabel(), $MODULE_NAME)}
			{/if}
                </button>
            </span>
            {/foreach}

            {if $MODULE == 'Contacts'}
		{if $RESULT == ''}
	            {assign var=global_masquerade_permission value=getGlobalMasqueradeUserPermission()}
	            {if $global_masquerade_permission}
	                {assign var=masquerade_permission value=getMasqueradeUserActionPermission()}
            		{if $masquerade_permission}
			    {if $mas_status eq 'yes'}
			    	<span class="btn-group">
	                            <div class="btn btn-default" id="remove-masquerade-user" data-recordid="{$RECORD->getId()}">{$MASQUERADERUSERMODULE}{vtranslate('Suspend User', $MODULE)}</div>
		                </span>
			    {else}
	                        <span class="btn-group">
            	                    <div class="btn btn-primary" id="convert-masquerade-user" data-recordid="{$RECORD->getId()}">{$MASQUERADERUSERMODULE}{vtranslate('LBL_CONVERT_USER', $MODULE)}</div>
		                </span>
			    {/if}
	                {/if}
	            {/if}
            	{/if}
	    {/if}

            {if $DETAILVIEW_LINKS['DETAILVIEW']|@count gt 0}
            <span class="btn-group">
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                   {vtranslate('LBL_MORE', $MODULE_NAME)}
                </button>
                <ul class="dropdown-menu dropdown-menu-right {if in_array($MODULE,array('Products','Potentials','HelpDesk','Accounts'))} mon_scr_mr-left {elseif in_array($MODULE,array('Leads'))} mon_scr_mr-left_1 {elseif in_array($MODULE,array('Contacts'))} mon_scr_mr-left_2 {/if}">
                    {foreach item=DETAIL_VIEW_LINK from=$DETAILVIEW_LINKS['DETAILVIEW']}
                        {if $DETAIL_VIEW_LINK->getLabel() eq ""} 
                            <li class="divider"></li>   
                            {else}
                            <li id="{$MODULE_NAME}_detailView_moreAction_{Head_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_LINK->getLabel())}">
                                {if $DETAIL_VIEW_LINK->getUrl()|strstr:"javascript"} 
                                    <a href='{$DETAIL_VIEW_LINK->getUrl()}'>{vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE_NAME)}</a>
                                {else}
                                    <a href='{$DETAIL_VIEW_LINK->getUrl()}' >{vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE_NAME)}</a>
                                {/if}
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            </span>
            {/if}
            
            {if !{$NO_PAGINATION}}
            <div class="btn-group pull-right">
                <button class="btn-paginate" id="detailViewPreviousRecordButton" {if empty($PREVIOUS_RECORD_URL)} disabled="disabled" {else} onclick="window.location.href = '{$PREVIOUS_RECORD_URL}'" {/if} >
                    <i class="fa fa-chevron-left"></i>
                </button>
                <button class="btn-paginate" id="detailViewNextRecordButton"{if empty($NEXT_RECORD_URL)} disabled="disabled" {else} onclick="window.location.href = '{$NEXT_RECORD_URL}'" {/if}>
                    <i class="fa fa-chevron-right"></i>
                </button>
            </div>
            {/if}        
        </div>
        <input type="hidden" name="record_id" value="{$RECORD->getId()}">
{strip}
