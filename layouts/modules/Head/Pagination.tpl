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
<div class = "{$CLASS_VIEW_ACTION}">
    <div class="btn-group pull-right">
        <button type="button" id="PreviousPageButton" class="btn-paginate pull-left joforce-enable" {if !$PAGING_MODEL->isPrevPageExists()} disabled {/if}><i class="fa fa-chevron-left"></i></button>
        {if $SHOWPAGEJUMP}
            <button type="button" id="PageJump" data-toggle="dropdown" class="btn-paginate">
                <i class="fa fa-ellipsis-h icon" title="{vtranslate('LBL_LISTVIEW_PAGE_JUMP',$moduleName)}"></i>
            </button>
            <ul class="{$CLASS_VIEW_BASIC_ACTION} dropdown-menu" id="PageJumpDropDown">
                <li>
                    <div class="listview-pagenum">
                        <span >{vtranslate('LBL_PAGE',$moduleName)}</span>&nbsp;
                        <strong><span>{$PAGE_NUMBER}</span></strong>&nbsp;
                        <span >{vtranslate('LBL_OF',$moduleName)}</span>&nbsp;
                        <strong><span id="totalPageCount"></span></strong>
                    </div>
                    <div class="listview-pagejump">
                        <input type="text" id="pageToJump" placeholder="Jump To" class="{$CLASS_VIEW_PAGING_INPUT} text-center"/>&nbsp;
                        <button type="button" id="pageToJumpSubmit" class="btn btn-primary {$CLASS_VIEW_PAGING_INPUT_SUBMIT} text-center">{'GO'}</button>
                    </div>    
                </li>
            </ul>
        {/if}
        <button type="button" id="NextPageButton" class="btn-paginate pull-right joforce-enable" {if !$PAGING_MODEL->isNextPageExists()}disabled{/if}><i class="fa fa-chevron-right"></i></button>
    </div>
    <span class="pageNumbers  pull-right" style="position:relative;top:7px;">
        <span class="pageNumbersText">
            {if $RECORD_COUNT}{$PAGING_MODEL->getRecordStartRange()} {vtranslate('LBL_to', $MODULE)} {$PAGING_MODEL->getRecordEndRange()}{else}
            {/if}
        </span>
        &nbsp;<span class="totalNumberOfRecords cursorPointer{if !$RECORD_COUNT} hide{/if}" title="{vtranslate('LBL_SHOW_TOTAL_NUMBER_OF_RECORDS', $MODULE)}">{vtranslate('LBL_OF', $MODULE)} <i class="fa fa-question showTotalCountIcon"></i></span>&nbsp;&nbsp;
    </span>
</div>
