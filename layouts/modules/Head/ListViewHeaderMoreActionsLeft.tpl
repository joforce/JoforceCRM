{strip}
{assign var=LISTVIEW_MASSACTIONS_1 value=array()}
	<div id="listview-actions" class="listview-actions-container">
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
        <div class="btn-group listViewActionsContainer" role="group" aria-label="...">
            {if count($LISTVIEW_MASSACTIONS_1) gt 0 or $LISTVIEW_LINKS['LISTVIEW']|@count gt 0}
                <div class="btn-group listViewMassActions dropdown" role="group">
			        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" disabled="disabled">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu more-actions-left" role="menu">
                        {foreach item=LISTVIEW_MASSACTION from=$LISTVIEW_MASSACTIONS_1 name=advancedMassActions}
                            <li class="hide"><a id="{$MODULE}_listView_massAction_{Head_Util_Helper::replaceSpaceWithUnderScores($LISTVIEW_MASSACTION->getLabel())}" {if stripos($LISTVIEW_MASSACTION->getUrl(), 'javascript:')===0} href="javascript:void(0);" onclick='{$LISTVIEW_MASSACTION->getUrl()|substr:strlen("javascript:")};'{else} href='{$LISTVIEW_MASSACTION->getUrl()}' {/if}>{vtranslate($LISTVIEW_MASSACTION->getLabel(), $MODULE)}</a></li>
                        {/foreach}
                        {if count($LISTVIEW_MASSACTIONS_1) gt 0 and $LISTVIEW_LINKS['LISTVIEW']|@count gt 0}
                            <li class="divider hide"></li>
                        {/if}
			            {if $MODULE_MODEL->isStarredEnabled()}
				            <li class="hide">
					            <a id="{$MODULE}_listView_massAction_LBL_ADD_STAR" onclick="Head_List_Js.triggerAddStar()">
					                {vtranslate('LBL_FOLLOW',$MODULE)}
					            </a>
				            </li>
				            <li class="hide">
					            <a id="{$MODULE}_listView_massAction_LBL_REMOVE_STAR" onclick="Head_List_Js.triggerRemoveStar()">
						            {vtranslate('LBL_UNFOLLOW',$MODULE)}
					            </a>
				            </li>
			            {/if}
                        <li class="hide">
                            <a id="{$MODULE}_listView_massAction_LBL_ADD_TAG" onclick="Head_List_Js.triggerAddTag()">
                                {vtranslate('LBL_ADD_TAG',$MODULE)}
                            </a>
                        </li>
			            {if $editAction}
     				        <li class="hide">
			                    <a id="{$MODULE}_listView_massAction_{$editAction->getLabel()}"
                        			    {if stripos($editAction->getUrl(), 'javascript:')===0} href="javascript:void(0);" onclick='{$editAction->getUrl()|substr:strlen("javascript:")}'{else} href='{$editAction->getUrl()}' {/if} >{vtranslate('LBL_EDIT', $MODULE)}</a>
			                </li>
			            {/if}
                
			    {if $deleteAction}
			          <li class="hide">
			                    <a id="{$MODULE}_listView_massAction_{$deleteAction->getLabel()}"
                            				{if stripos($deleteAction->getUrl(), 'javascript:')===0} href="javascript:void(0);" onclick='{$deleteAction->getUrl()|substr:strlen("javascript:")}'{else} href='{$deleteAction->getUrl()}' {/if}>{vtranslate('LBL_DELETE', $MODULE)}</a>
			          </li>
			    {/if}
				
			    {if $commentAction}
			          <li class="hide">
			                <a id="{$MODULE}_listView_massAction_{$commentAction->getLabel()}" onclick="Head_List_Js.triggerMassAction('{$commentAction->getUrl()}')">{vtranslate('LBL_COMMENT', $MODULE)}</a>
			          </li>
			    {/if}

                            {if $CURRENT_TAG neq ''}
                            <li class="hide">
                                <a id="{$MODULE}_listview_massAction_LBL_REMOVE_TAG" onclick="Head_List_Js.triggerRemoveTag({$CURRENT_TAG})">
                                    {vtranslate('LBL_REMOVE_TAG', $MODULE)}
                                </a>
                            </li>
                            {/if}
                        </ul>
                    </div>
                {/if}
	</div>
      </div>

<script>
{literal}
$(document).ready(function()    {
    $('.btn-group.listViewActionsContainer').click(function()   {
        if($("button.btn-disabled").is(":disabled"))    {
            $('.btn-group.listViewMassActions ul.dropdown-menu').addClass('listaction-disabled');
        }
    });
});
{/literal}
</script>
