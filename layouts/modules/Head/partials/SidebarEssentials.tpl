{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
<div class="sidebar-menu">
    <div class="module-filters" id="module-filters">
        <div class="sidebar-container lists-menu-container list-group">
            <div class="sidebar-header clearfix list-header">
                <h5 class="pull-left">{vtranslate('LBL_LISTS',$MODULE)}</h5>
                <span id="createFilter" data-url="{CustomView_Record_Model::getCreateViewUrl($MODULE)}" class="pull-right sidebar-btn new-list btn addButton btn-primary" title="{vtranslate('LBL_CREATE_LIST',$MODULE)}"><i class="fa fa-filter"></i>Add Filter
                </span> 
            </div>
            <!-- <hr> -->
            
            <div class="mylist">
                <label>My List</label>
                <select name="cars" class="custom-select filter">
                {assign var=CUSTOM_VIEW_NAMES value=array()}
                        {if $CUSTOM_VIEWS && count($CUSTOM_VIEWS) > 0}

                            {foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
                            {if $GROUP_LABEL neq 'Mine' && $GROUP_LABEL neq 'Shared'}
                                {continue}
                             {/if}
                        {assign var=count value=0}
                                {assign var=LISTVIEW_URL value=$MODULE_MODEL->getListViewUrl()}
                                {$GROUP_CUSTOM_VIEWS|print_r}
                                {foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS name="customView"}
                                    {assign var=IS_DEFAULT value=$CUSTOM_VIEW->isDefault()}
                                    {assign var="CUSTOME_VIEW_RECORD_MODEL" value=CustomView_Record_Model::getInstanceById($CUSTOM_VIEW->getId())}
                                    {assign var="MEMBERS" value=$CUSTOME_VIEW_RECORD_MODEL->getMembers()}
                                    {assign var="LIST_STATUS" value=$CUSTOME_VIEW_RECORD_MODEL->get('status')}
                                    {foreach key=GROUP_LABEL item="MEMBER_LIST" from=$MEMBERS}
                                        {if $MEMBER_LIST|@count gt 0}
                                        {assign var="SHARED_MEMBER_COUNT" value=1}
                                        {/if}
                                    {/foreach}

                                        {assign var=VIEWNAME value={vtranslate($CUSTOM_VIEW->get('viewname'), $MODULE)}}
                                        {append var="CUSTOM_VIEW_NAMES" value=$VIEWNAME}
                    <option value="fiat" data-href ="{$LISTVIEW_URL|cat:'/filter/'|cat:$CUSTOM_VIEW->getId()}" oncontextmenu="return false;" data-filter-id="{$CUSTOM_VIEW->getId()}" >{$VIEWNAME|@escape:'html'}</option>
                                        {/foreach}
 {/foreach}
 {/if}
                </select>
            </div>



            <div class="sharelist">
                <label> Shared List</label>
                <select name="cars" class="custom-select">
                    <option value="fiat">All</option>
                    
                </select>
            </div>



            <div>
                <input class="search-list" type="text" placeholder="{vtranslate('LBL_SEARCH_FOR_LIST',$MODULE)}">
            </div>
            <div style="display: none;" class="menu-scroller scrollContainer" style="position:relative; top:0; left:0;">
				<div class="list-menu-content">
						{assign var=CUSTOM_VIEW_NAMES value=array()}
                        {if $CUSTOM_VIEWS && count($CUSTOM_VIEWS) > 0}
                            {foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
                            {if $GROUP_LABEL neq 'Mine' && $GROUP_LABEL neq 'Shared'}
                                {continue}
                             {/if}
                            <div class="list-group" id="{if $GROUP_LABEL eq 'Mine'}myList{else}sharedList{/if}">   
                                <h5 class="list-header {if count($GROUP_CUSTOM_VIEWS) <=0} hide {/if}" >
                                    <i class="fa fa-chevron-down mr5 list-accordion"></i>
                                    {if $GROUP_LABEL eq 'Mine'}
                                        {vtranslate('LBL_MY_LIST',$MODULE)}
                                    {else}
                                        {vtranslate('LBL_SHARED_LIST',$MODULE)}
                                    {/if}
                                </h5>
                                <input type="hidden" name="allCvId" value="{CustomView_Record_Model::getAllFilterByModule($MODULE)->get('cvid')}" />
                                <ul class="lists-menu ">
								{assign var=count value=0}
								{assign var=LISTVIEW_URL value=$MODULE_MODEL->getListViewUrl()}
                                {foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS name="customView"}
                                    {assign var=IS_DEFAULT value=$CUSTOM_VIEW->isDefault()}
									{assign var="CUSTOME_VIEW_RECORD_MODEL" value=CustomView_Record_Model::getInstanceById($CUSTOM_VIEW->getId())}
									{assign var="MEMBERS" value=$CUSTOME_VIEW_RECORD_MODEL->getMembers()}
									{assign var="LIST_STATUS" value=$CUSTOME_VIEW_RECORD_MODEL->get('status')}
									{foreach key=GROUP_LABEL item="MEMBER_LIST" from=$MEMBERS}
										{if $MEMBER_LIST|@count gt 0}
										{assign var="SHARED_MEMBER_COUNT" value=1}
										{/if}
									{/foreach}
									<li style="font-size:15px;" class='listViewFilter {if $VIEWID eq $CUSTOM_VIEW->getId() && ($CURRENT_TAG eq '')} active {if $smarty.foreach.customView.iteration gt 10} {assign var=count value=1} {/if} {else if $smarty.foreach.customView.iteration gt 10} filterHidden hide{/if} '> 
                                        {assign var=VIEWNAME value={vtranslate($CUSTOM_VIEW->get('viewname'), $MODULE)}}
										{append var="CUSTOM_VIEW_NAMES" value=$VIEWNAME}
                                         <a class="filterName listViewFilterElipsis" href="{$LISTVIEW_URL|cat:'/filter/'|cat:$CUSTOM_VIEW->getId()}" oncontextmenu="return false;" data-filter-id="{$CUSTOM_VIEW->getId()}" title="{$VIEWNAME|@escape:'html'}">{$VIEWNAME|@escape:'html'}</a> 
                                            <div class="pull-right">
                                                <span class="js-popover-container" style="cursor:pointer;">
                                                    <span  class="fa fa-ellipsis-v" rel="popover" data-toggle="popover" aria-expanded="true" 
                                                {if $CUSTOM_VIEW->isMine() and $CUSTOM_VIEW->get('viewname') neq 'All'}
                                                            data-deletable="{if $CUSTOM_VIEW->isDeletable()}true{else}false{/if}" data-editable="{if $CUSTOM_VIEW->isEditable()}true{else}false{/if}" 
                                                            {if $CUSTOM_VIEW->isEditable()} data-editurl="{$CUSTOM_VIEW->getEditUrl()}{/if}" {if $CUSTOM_VIEW->isDeletable()} {if $SHARED_MEMBER_COUNT eq 1 or $LIST_STATUS eq 3} data-shared="1"{/if} data-deleteurl="{$CUSTOM_VIEW->getDeleteUrl()}"{/if}
                                                           {/if}
                                                          toggleClass="fa {if $IS_DEFAULT}fa-check-square-o{else}fa-square-o{/if}" data-filter-id="{$CUSTOM_VIEW->getId()}" 
                                                          data-is-default="{$IS_DEFAULT}" data-defaulttoggle="{$CUSTOM_VIEW->getToggleDefaultUrl()}" data-default="{$CUSTOM_VIEW->getDuplicateUrl()}" data-isMine="{if $CUSTOM_VIEW->isMine()}true{else}false{/if}">
                                                    </span>
                                                     </span>
                                                </div>
                                            </li>
                                        {/foreach}
                                    </ul>
				{if $smarty.foreach.customView.iteration - 10 - $count} 
					<div class='clearfix'>
						<a class="toggleFilterSize" data-more-text=" {$smarty.foreach.customView.iteration - 10 - $count} {vtranslate('LBL_MORE',Head)|@strtolower}" data-less-text="Show less">
							{if $smarty.foreach.customView.iteration gt 10} 
								{$smarty.foreach.customView.iteration - 10 - $count} {vtranslate('LBL_MORE',Head)|@strtolower} 
							{/if} 
						</a>
					</div>
				{/if} 
                             </div>
					{/foreach}
								
							<input type="hidden" id='allFilterNames'  value='{Head_Util_Helper::toSafeHTML(Zend_JSON::encode($CUSTOM_VIEWS_NAMES))}'/>
                            <div id="filterActionPopoverHtml">
                                <ul class="listmenu hide" role="menu" style="min-width: 120px;">
                                    <li role="presentation" class="editFilter">
                                            <a role="menuitem"><i class="fa fa-pencil"></i>&nbsp;{vtranslate('LBL_EDIT',$MODULE)}</a>
                                        </li>
                                    <li role="presentation" class="deleteFilter">
                                            <a role="menuitem"><i class="fa fa-trash"></i>&nbsp;{vtranslate('LBL_DELETE',$MODULE)}</a>
                                    </li>
                                    <li role="presentation" class="duplicateFilter">
                                                <a role="menuitem" ><i class="fa fa-files-o"></i>&nbsp;{vtranslate('LBL_DUPLICATE',$MODULE)}</a>
                                            </li>
                                    <li role="presentation" class="toggleDefault">
                                                <a role="menuitem" >
                                            <i data-check-icon="fa-check-square-o" data-uncheck-icon="fa-square-o"></i>&nbsp;{vtranslate('LBL_DEFAULT',$MODULE)}
                                                </a>
                                            </li>
                                        </ul>
                            </div>

                        {/if}
                        <div class="list-group hide noLists">
                            <h6 class="lists-header"><center> {vtranslate('LBL_NO')} {vtranslate('LBL_LISTS')} {vtranslate('LBL_FOUND')} ... </center></h6>
                        </div>
                </div>
            </div>
        </div>
    </div>
    {assign var=EXTENSION_LINKS value=Head_Extension_View::getExtensionLinks($MODULE)}
    {if !empty($EXTENSION_LINKS)}
        <div class="module-filters module-extensions">
            <div class="sidebar-container lists-menu-container">
                <h5 class="sidebar-header">{vtranslate('LBL_EXTENSIONS',$MODULE)}</h5>
                <hr>
                <div class="menu-scroller scrollContainer" style="position:relative; top:0; left:0;">
                    <div class="list-menu-content">
                        <ul class="lists-menu"> 
                            {foreach from=$EXTENSION_LINKS item=LINK}
                                {if $LINK->isExtensionAccessible()}
                                    <li style="font-size:12px;" class="listViewFilter {if $EXTENSION_MODULE eq $LINK->get('linklabel')} active {/if}">
                                        <a href="{$SITEURL}{$LINK->get('linkurl')}">{vtranslate($LINK->get('linklabel'), $MODULE)}</a>
                                    </li>
                                {/if}
                            {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    <div class="module-filters">
        <div class="sidebar-container lists-menu-container">
            <h5 class="lists-header">
                {vtranslate('LBL_TAGS', $MODULE)}
            </h5>
            <hr>
            <div class="menu-scroller scrollContainer" style="position:relative; top:0; left:0;">
                <div class="list-menu-content">
                    <div id="listViewTagContainer" class="multiLevelTagList" 
                    {if $ALL_CUSTOMVIEW_MODEL} data-view-id="{$ALL_CUSTOMVIEW_MODEL->getId()}" {/if}
                    data-list-tag-count="{Head_Tag_Model::NUM_OF_TAGS_LIST}">
                        {foreach item=TAG_MODEL from=$TAGS name=tagCounter}
                            {assign var=TAG_LABEL value=$TAG_MODEL->getName()}
                            {assign var=TAG_ID value=$TAG_MODEL->getId()}
                            {if $smarty.foreach.tagCounter.iteration gt Head_Tag_Model::NUM_OF_TAGS_LIST}
                                {break}
                            {/if}
                            {include file="Tag.tpl"|vtemplate_path:$MODULE NO_DELETE=true ACTIVE= $CURRENT_TAG eq $TAG_ID}
                        {/foreach}
                        <div> 
                            <a class="moreTags {if (count($TAGS) - Head_Tag_Model::NUM_OF_TAGS_LIST) le 0} hide {/if}">
                                <span class="moreTagCount">{count($TAGS) - Head_Tag_Model::NUM_OF_TAGS_LIST}</span>
                                &nbsp;{vtranslate('LBL_MORE',$MODULE)|strtolower}
                            </a>
                            <div class="moreListTags hide">
                        {foreach item=TAG_MODEL from=$TAGS name=tagCounter}
                            {if $smarty.foreach.tagCounter.iteration le Head_Tag_Model::NUM_OF_TAGS_LIST}
                                {continue}
                            {/if}
                            {include file="Tag.tpl"|vtemplate_path:$MODULE NO_DELETE=true ACTIVE= $CURRENT_TAG eq $TAG_ID}
                        {/foreach}
                             </div>
                        </div>
                    </div>
                    {include file="AddTagUI.tpl"|vtemplate_path:$MODULE RECORD_NAME="" TAGS_LIST=array()}
                </div>
                <div id="dummyTagElement" class="hide">
                    {assign var=TAG_MODEL value=Head_Tag_Model::getCleanInstance()}
                    {include file="Tag.tpl"|vtemplate_path:$MODULE NO_DELETE=true}
                </div>
                <div>
                    <div class="editTagContainer hide">
                        <input type="hidden" name="id" value="" />
                        <div class="editTagContents">
                            <div>
                                <input type="text" class="inputElement" name="tagName" value="" style="width:100%" maxlength="25"/>
                            </div>
                            <div>
                                <div class="checkbox" style="padding:unset;margin-top:20px;">
                                    <label>
                                        <input type="hidden" name="visibility" value="{Head_Tag_Model::PRIVATE_TYPE}"/>
                                        <input type="checkbox" name="visibility" value="{Head_Tag_Model::PUBLIC_TYPE}" />
                                        &nbsp; {vtranslate('LBL_SHARE_TAG',$MODULE)}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-mini btn-success saveTag" type="button" style="width:50%;">
                                <center> <i class="fa fa-check"></i> </center>
                            </button>
                            <button class="btn btn-mini btn-danger cancelSaveTag" type="button" style="width:50%">
                                <center> <i class="fa fa-close"></i> </center>
                            </button>
                        </div>
                    </div>
                </div>
           </div>
        </div>
     </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.list-group .list-header').click(function(){
            $(this).children('i').toggleClass('fa-chevron-right').toggleClass('fa-chevron-down');
            $(this).siblings('.lists-menu').slideToggle();
        });
    });
</script>
