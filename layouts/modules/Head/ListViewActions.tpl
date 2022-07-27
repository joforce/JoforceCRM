{strip}
    {assign var=LISTVIEW_MASSACTIONS_1 value=array()}
    <div id="third-listview-actions" class="third-listview-actions-container msgsection row">
        {foreach item=LIST_MASSACTION from=$LISTVIEW_MASSACTIONS name=massActions}
            {if $LIST_MASSACTION->getLabel() eq 'LBL_EDIT'}
                {assign var=editAction value=$LIST_MASSACTION}
            {else if $LIST_MASSACTION->getLabel() eq 'LBL_DELETE'}
                {assign var=deleteAction value=$LIST_MASSACTION}
            {else if $LIST_MASSACTION->getLabel() eq 'LBL_ADD_COMMENT'}
                {assign var=commentAction value=$LIST_MASSACTION}
            {else}
                {$a = array_push($LISTVIEW_MASSACTIONS_1, $LIST_MASSACTION)}
                {* $a is added as its print the index of the array, need to find a way around it *}
            {/if}
        {/foreach}
        
	{if $LISTVIEW_ENTRIES_COUNT eq '0' and $REQUEST_INSTANCE and $REQUEST_INSTANCE->isAjax()}
	    {if $smarty.session.lvs.$MODULE.viewname}
		{assign var=VIEWID value=$smarty.session.lvs.$MODULE.viewname}
	    {/if}
	    {if $VIEWID}
	      {foreach item=FILTER_TYPES from=$CUSTOM_VIEWS}
		{foreach item=FILTERS from=$FILTER_TYPES}
		    {if $FILTERS->get('cvid') eq $VIEWID}
			{assign var=CVNAME value=$FILTERS->get('viewname')}{break}
		    {/if}
		{/foreach}
	      {/foreach}
	      {assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getDefaultUrl()}
	      {assign var=DEFAULT_FILTER_ID value=$MODULE_MODEL->getDefaultCustomFilter()}
	      {if $DEFAULT_FILTER_ID}
		{assign var=DEFAULT_FILTER_URL value=$MODULE_MODEL->getListViewUrl()|cat:"/"|cat:$DEFAULT_FILTER_ID}
	      {/if}
	      {if $CVNAME neq 'All'}
		<div>{vtranslate('LBL_DISPLAYING_RESULTS',$MODULE)} {vtranslate('LBL_FROM',$MODULE)} <b>{$CVNAME}</b>. <a style="color:blue" href='{$DEFAULT_FILTER_URL}'>{vtranslate('LBL_SEARCH_IN',$MODULE)} {vtranslate('All',$MODULE)} {vtranslate($MODULE, $MODULE)}</a> </div>
	      {/if}
	    {/if}
	{/if}
	<div class="hide messageContainer" style = "height:30px;">
    	    <center><a href="#" id="selectAllMsgDiv">{vtranslate('LBL_SELECT_ALL',$MODULE)}&nbsp;{vtranslate($MODULE ,$MODULE)}&nbsp;(<span id="totalRecordsCount" value=""></span>)</a></center>
	</div>
	<div class="hide messageContainer" style = "height:30px;">
	    <center><a href="#" id="deSelectAllMsgDiv">{vtranslate('LBL_DESELECT_ALL_RECORDS',$MODULE)}</a></center>
	</div>            
    </div>
{/strip}
