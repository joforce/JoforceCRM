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
{if !$CLASS_VIEW_ACTION}
    {assign var=CLASS_VIEW_ACTION value='listViewActions'}
    {assign var=CLASS_VIEW_PAGING_INPUT value='listViewPagingInput'}
    {assign var=CLASS_VIEW_PAGING_INPUT_SUBMIT value='listViewPagingInputSubmit'}
    {assign var=CLASS_VIEW_BASIC_ACTION value='listViewBasicAction'}
{/if}
<div class = "{$CLASS_VIEW_ACTION} row">
    <div class="btn-group pull-right">
        <button type="button" id="PreviousPageButton_kanban" class="btn-paginate pull-left joforce-enable" {if !$PAGING_MODEL->isPrevPageExists()} disabled {/if}>
	    <i class="fa fa-chevron-left"></i>
	</button>
        {if $SHOWPAGEJUMP}
            <button type="button" id="PageJump_kanban" data-toggle="dropdown" class="btn-paginate">
                <i class="fa fa-ellipsis-h icon" title="{vtranslate('LBL_LISTVIEW_PAGE_JUMP',$moduleName)}"></i>
            </button>
            <ul class="{$CLASS_VIEW_BASIC_ACTION} dropdown-menu" id="PageJumpDropDown_kanban">
                <li>
                    <div class="listview-pagenum_kanban" style="text-align: center;">
                        <span >{vtranslate('LBL_PAGE',$moduleName)}</span>&nbsp;
                        <strong><span>{$PAGE_NUMBER}</span></strong>&nbsp;
                        <span >{vtranslate('LBL_OF',$moduleName)}</span>&nbsp;
                        <strong><span id="totalPageCount_kanban"></span></strong>
                    </div>
                    <div class="listview-pagejump_kanban" style="text-align: center;">
                        <input type="text" id="pageToJump_kanban" placeholder="Jump To" class="{$CLASS_VIEW_PAGING_INPUT}" style="text-align: center;" />
                        <button type="button" id="pageToJumpSubmit_kanban" class="btn btn-success {$CLASS_VIEW_PAGING_INPUT_SUBMIT}" style="text-align: center;">{'GO'}</button>
                    </div>    
                </li>
            </ul>
        {/if}
        <button type="button" id="NextPageButton_kanban" class="btn-paginate pull-right joforce-enable" {if !$PAGING_MODEL->isNextPageExists()}disabled{/if}>
	    <i class="fa fa-chevron-right"></i>
	</button>
    </div>
    <span class="pageNumbers_kanban">
        <span class="pageNumbersText_kanban">
            {if $RECORD_COUNT}{$PAGING_MODEL->getRecordStartRange()} {vtranslate('LBL_to', $MODULE)} {$PAGING_MODEL->getRecordEndRange()}{else}
            {/if}
        </span>
        <span class="totalNumberOfRecords_kanban cursorPointer{if !$RECORD_COUNT} hide{/if}" title="{vtranslate('LBL_SHOW_TOTAL_NUMBER_OF_RECORDS', $MODULE)}">
	    {vtranslate('LBL_OF', $MODULE)} <span class="toalcount_value"></span> <i class="fa fa-question showTotalCountIcon_kanban"></i>
	</span>
    </span>
</div>
