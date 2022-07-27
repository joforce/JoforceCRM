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
	
	{if $QUICK_PREVIEW_ENABLED eq 'true'}
        	<span class="quickView fa fa-eye icon action" id="quick_preview_{$DATA_ID}" title="{vtranslate('LBL_QUICK_VIEW', $MODULE)}"></span>
    	{/if}
	
	<span class="more dropdown action more-actions">
        	<span href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"></span>
        	<ul class="dropdown-menu more-actions-right dropdown-menu-right ">
	            <li><a data-id="{$LISTVIEW_ENTRY->getId()}" href="{$LISTVIEW_ENTRY->getFullDetailViewUrl()}">{vtranslate('LBL_DETAILS', $MODULE)}</a></li>
                        {if $RECORD_ACTIONS}
                                {if $RECORD_ACTIONS['edit']}
                                        <li><a data-id="{$LISTVIEW_ENTRY->getId()}" href="{$LISTVIEW_ENTRY->getEditViewUrl()}" name="editlink">{vtranslate('LBL_EDIT', $MODULE)}</a></li>
                                {/if}
                                {if $RECORD_ACTIONS['delete']}
                                        <li><a data-id="{$LISTVIEW_ENTRY->getId()}"  class="deleteRecordButton">{vtranslate('LBL_DELETE', $MODULE)}</a></li>
                                {/if}
                        {/if}
			<li><a data-id="{$LISTVIEW_ENTRY->getId()}" href="{$LISTVIEW_ENTRY->getRecordActivityUrl()}">{vtranslate('LBL_RECORD_ACTIVITY', $MODULE)}</a></li>
			{if $LISTVIEW_ROWACTIONS}
				{foreach item=ROWACTION from=$LISTVIEW_ROWACTIONS}
					<li><a data-id="{$LISTVIEW_ENTRY->getId()}" {if strpos($ROWACTION['linkurl'], 'javascript:')===0} href='javascript:void(0);' onclick='{$ROWACTION['linkurl']|substr:strlen("javascript:")};'{else}href="{$SITEURL}{$ROWACTION['linkurl']}" {/if}>{$ROWACTION['linklabel']}</a></li>
				{/foreach}
			{/if}
        	</ul>
    	</span>

    	<div class="btn-group inline-save hide">
        	<button class="button btn-success btn-small save" type="button" name="save"><i class="fa fa-check"></i></button>
	        <button class="button btn-danger btn-small cancel" type="button" name="Cancel"><i class="fa fa-close"></i></button>
    	</div>
</div>
