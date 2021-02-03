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
  {if $SHOWPAGEJUMP}
    {assign var=tl_no_of_pages value=$NO_OF_PAGES['page']}
    {assign var=tl_no_of_records value=$NO_OF_PAGES['numberOfRecords']}

    <input type="hidden" id="tl_no_of_pages" value="{$tl_no_of_pages}" />
    <input type="hidden" id="tl_no_of_records" value="{$tl_no_of_records}" />
    <input type="hidden" id="current_page_number" value="{$PAGE_NUMBER}" />

    <div class="col-lg-2 col-md-2 listHead">
      <span class="pageNumbers  pull-right" style="position:relative;top:7px;">
        <span class="pageNumbersText">
            {if $RECORD_COUNT}{$PAGING_MODEL->getRecordStartRange()} {vtranslate('LBL_to', $MODULE)} {$PAGING_MODEL->getRecordEndRange()}{else}
            {/if}
        </span>
        &nbsp;<span class="totalNumberOfRecords cursorPointer{if !$RECORD_COUNT} hide{/if}" title="{vtranslate('LBL_SHOW_TOTAL_NUMBER_OF_RECORDS', $MODULE)}">{vtranslate('LBL_OF', $MODULE)} <i class="fa fa-question showTotalCountIcon"></i></span>&nbsp;&nbsp;
      </span>
    </div>
    <div class="col-lg-7 col-md-7">
        <ul class="pagination pagination-circled mb-0">
            <li class="page-item">
                <a class="previous-link page-link" href="#"><i class="fa fa-angle-left"></i></a>
            </li>
	    {if $tl_no_of_pages < 5}
		{for $foo=1 to $tl_no_of_pages}
                <li class="page-item">
                    <a class="page-link {if $foo == $PAGE_NUMBER} active {/if}" href="#" data-page="{$foo}">{$foo}</a>
                </li>
		{/for}
	    {else}
		<li class="page-item">
		    <a class="page-link {if $PAGE_NUMBER == 1} active {/if}" href="#" data-page=1>1</a>
		</li>

		<li class="page-item">
		    <a class="page-link disabled" href="#">...</a>
		</li>


		{if $PAGE_NUMBER < 5}
		    {for $foo=2 to 4}
	                <li class="page-item">
        	            <a class="page-link {if $foo == $PAGE_NUMBER} active {/if}" href="#" data-page="{$foo}">{$foo}</a>
	                </li>
                    {/for}
		{elseif $PAGE_NUMBER > 4}
		    {assign var=low value=$PAGE_NUMBER-2}
		    {if $PAGE_NUMBER+2 > $tl_no_of_pages}
		        {assign var=high value=$tl_no_of_pages}
		    {else}
		    	{assign var=high value=$PAGE_NUMBER+2}
		    {/if}

		    {for $foo=$low to $high}
			{if $foo neq $tl_no_of_pages}
	                <li class="page-item">
        	            <a class="page-link {if $foo == $PAGE_NUMBER} active {/if}" href="#" data-page="{$foo}">{$foo}</a>
                	</li>
			{/if}
                    {/for}
		{/if}
		<li class="page-item">
		    <a class="page-link disabled" href="#">...</a>
		</li>

		<li class="page-item">
			<a class="page-link {if $tl_no_of_pages == $PAGE_NUMBER} active {/if}" href="#" data-page="{$tl_no_of_pages}">{$tl_no_of_pages}</a>
		</li>
	    {/if}

            <li class="page-item">
                <a class="next-link page-link"><i class="fa fa-angle-right"></i></a>
            </li>
        </ul>
        <div class="hide messageContainer" style = "height:30px;">
            <center><a href="#" id="selectAllMsgDiv">{vtranslate('LBL_SELECT_ALL',$MODULE)}&nbsp;{vtranslate($MODULE ,$MODULE)}&nbsp;(<span id="totalRecordsCount" value=""></span>)</a></center>
        </div>
    </div>
    <div class="col-lg-3 col-md-3" style="margin-top:18px;">
	<div class="listview-pagejump">
                        <input type="text" id="pageToJump" placeholder="Jump To" class="jumpToInput {$CLASS_VIEW_PAGING_INPUT} text-center"/>&nbsp;
                        <button type="button" id="pageToJumpSubmit" class="btn btn-primary-gradient {$CLASS_VIEW_PAGING_INPUT_SUBMIT} text-center">{'GO'}</button>
	</div>
    </div>
  {/if}
</div>
