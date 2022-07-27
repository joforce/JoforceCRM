{strip}
<div class="relatedHeader">
<div class="btn-toolbar row col-lg-12 col-md-12 col-sm-6 p0 m0">
        <div class="col-lg-12 col-md-12 col-sm-6 btn-toolbar">
            {foreach item=RELATED_LINK from=$RELATED_LIST_LINKS['LISTVIEWBASIC']}
                <div class="btn-group">
                    {assign var=DROPDOWNS value=$RELATED_LINK->get('linkdropdowns')}
                    {if count($DROPDOWNS) gt 0}
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="200" data-close-others="false" style="width:20px;height:18px;">
                                <img title="{$RELATED_LINK->getLabel()}" alt="{$RELATED_LINK->getLabel()}" src="{vimage_path("{$RELATED_LINK->getIcon()}")}">
                            </a>
                            <ul class="dropdown-menu">
                                {foreach item=DROPDOWN from=$DROPDOWNS}
                                    <li><a id="{$RELATED_MODULE_NAME}_relatedlistView_add_{Head_Util_Helper::replaceSpaceWithUnderScores($DROPDOWN['label'])}" class="{$RELATED_LINK->get('linkclass')}" href='javascript:void(0)' data-documentType="{$DROPDOWN['type']}" data-url="{$DROPDOWN['url']}" data-name="{$RELATED_MODULE_NAME}" data-firsttime="{$DROPDOWN['firsttime']}"><i class="icon-plus"></i>&nbsp;{vtranslate($DROPDOWN['label'], $RELATED_MODULE_NAME)}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    {else}
                        {assign var=IS_SELECT_BUTTON value={$RELATED_LINK->get('_selectRelation')}}
                        {* setting button module attribute to Events or Calendar based on link label *}
                        {assign var=LINK_LABEL value={$RELATED_LINK->get('linklabel')}}
                        {if $RELATED_LINK->get('_linklabel') === '_add_event'}
                            {assign var=RELATED_MODULE_NAME value='Events'}
                        {elseif $RELATED_LINK->get('_linklabel') === '_add_task'}
                            {assign var=RELATED_MODULE_NAME value='Calendar'}
                        {/if}
						{if $IS_SELECT_BUTTON || $IS_CREATE_PERMITTED}
							<button type="button" module="{$RELATED_MODULE_NAME}"  class="btn addButton btn-secondary
								{if $IS_SELECT_BUTTON eq true} selectRelation {/if} "
								{if $IS_SELECT_BUTTON eq true} data-moduleName={$RELATED_LINK->get('_module')->get('name')} {/if}
								{if ($RELATED_LINK->isPageLoadLink())}
								{if $RELATION_FIELD} data-name="{$RELATION_FIELD->getName()}" {/if}
								data-url="{$RELATED_LINK->getUrl()}"
								{/if}
							{if $IS_SELECT_BUTTON neq true}name="addButton"{/if}>{if $IS_SELECT_BUTTON eq false}<i class="fa fa-plus"></i>&nbsp;{/if}&nbsp;{$RELATED_LINK->getLabel()}</button>
						{/if}
                    {/if}
                </div>
            {/foreach}&nbsp;
        </div>
        {assign var=CLASS_VIEW_ACTION value='relatedViewActions'}
        {assign var=CLASS_VIEW_PAGING_INPUT value='relatedViewPagingInput'}
        {assign var=CLASS_VIEW_PAGING_INPUT_SUBMIT value='relatedViewPagingInputSubmit'}
        {assign var=CLASS_VIEW_BASIC_ACTION value='relatedViewBasicAction'}
   </div>
</div>
{/strip}
