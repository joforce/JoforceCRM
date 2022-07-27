{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

{strip}
    <div class="recentActivitiesContainer {if in_array($MODULE,array('Potentials','Products','HelpDesk','Services'))} Mob_rela_Width {elseif in_array($MODULE,array('Campaigns','PriceBooks','PurchaseOrder','SalesOrder','Documents','Vendors','Invoice','Quotes','Calendar'))} ms_recentActivities_width {$MODULE} {/if} {if in_array($MODULE,array('Products'))} mac_scr_align_recent {/if} {if in_array($MODULE,array('Services'))} big_scr_service_page_detailview_align {/if} " id="updates" >
        <input type="hidden" id="updatesCurrentPage" value="{$PAGING_MODEL->get('page')}"/>
        <div class='history'>
            {if !empty($RECENT_ACTIVITIES)}
                <div class="updates_timeline">
                    {foreach item=RECENT_ACTIVITY from=$RECENT_ACTIVITIES key=key}
                        {assign var=PROCEED value= TRUE}
                        {if ($RECENT_ACTIVITY->isRelationLink()) or ($RECENT_ACTIVITY->isRelationUnLink())}
                            {assign var=RELATION value=$RECENT_ACTIVITY->getRelationInstance()}
                            {if !($RELATION->getLinkedRecord())}
                                {assign var=PROCEED value= FALSE}
                            {/if}
                        {/if}
                        {if $PROCEED}
                            {if $RECENT_ACTIVITY->isCreate()}
                                <div class="timeline-wrapper {if $key is odd}jotimeline-odd{else} inverted-timeline jotimeline-even {/if}">
                                    {assign var=USER_MODEL value=$RECENT_ACTIVITY->getModifiedBy()}
                                    {assign var=IMAGE_DETAILS value=$USER_MODEL->getImageDetails()}
                                    {if $IMAGE_DETAILS neq '' && $IMAGE_DETAILS[0] neq '' && $IMAGE_DETAILS[0].path eq ''}
                                        <div class="update_icon bg-info">
                                            <i class='update_image joicon-vtigeruser'></i>
                                        </div>
                                    {else}
                                        {foreach item=IMAGE_INFO from=$IMAGE_DETAILS}
                                            {if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
                                                <div class="update_icon bg-info">
                                                    <img class="update_image" src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" >
                                                </div>
                                            {/if}
                                        {/foreach}
                                    {/if}
                                    <div class="update_info">
                                        <h5 class="timeline-title">
                                            <span class="field-name">{$RECENT_ACTIVITY->getModifiedBy()->getName()}</span> {vtranslate('LBL_CREATED', $MODULE_NAME)}
                                        </h5>
                                        <time class="update_time cursorDefault timeline-footer">
                                            <small title="{Head_Util_Helper::formatDateTimeIntoDayString($RECENT_ACTIVITY->getParent()->get('createdtime'))}">
                                            {Head_Util_Helper::formatDateDiffInStrings($RECENT_ACTIVITY->getParent()->get('createdtime'))}
                                            </small>
                                        </time>
                                    </div>
                                </div>
                            {else if $RECENT_ACTIVITY->isUpdate()}
				{if $key}
                                <div class="timeline-wrapper {if $key is odd}jotimeline-odd{else} inverted-timeline jotimeline-even {/if}">
                                    {assign var=USER_MODEL value=$RECENT_ACTIVITY->getModifiedBy()}
                                    {assign var=IMAGE_DETAILS value=$USER_MODEL->getImageDetails()}
                                    {if $IMAGE_DETAILS neq '' && $IMAGE_DETAILS[0] neq '' && $IMAGE_DETAILS[0].path eq ''}
                                        <div class="update_icon bg-info">
                                            <i class='update_image joicon-vtigeruser'></i>
                                        </div>
                                    {else}
                                        {foreach item=IMAGE_INFO from=$IMAGE_DETAILS}
                                            {if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
                                                <div class="update_icon bg-info">
                                                    <img class="update_image" src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" >
                                                </div>
                                            {/if}
                                        {/foreach}
                                    {/if}
                                    <div class="update_info">
                                        <div> 
                                            <h5 class="timeline-title">
                                                <span class="field-name">{$RECENT_ACTIVITY->getModifiedBy()->getDisplayName()} </span> {vtranslate('LBL_UPDATED', $MODULE_NAME)}
                                            </h5>
                                        </div>
                                        {foreach item=FIELDMODEL from=$RECENT_ACTIVITY->getFieldInstances()}
                                            {if $FIELDMODEL && $FIELDMODEL->getFieldInstance() && $FIELDMODEL->getFieldInstance()->isViewable() && $FIELDMODEL->getFieldInstance()->getDisplayType() neq '5'}
                                                <div class='font-x-small updateInfoContainer textOverflowEllipsis'>
                                                    <div class='update-name'><span class="field-name">{vtranslate($FIELDMODEL->getName(),$MODULE_NAME)}</span>
                                                        {if $FIELDMODEL->get('prevalue') neq '' && $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && ($FIELDMODEL->get('postvalue') eq '0' || $FIELDMODEL->get('prevalue') eq '0'))}
                                                            <span> &nbsp;{vtranslate('LBL_CHANGED')}</span>
                                                        </div>
                                                        <div class='update-from'><span class="field-name">{vtranslate('LBL_FROM')}</span>&nbsp;
                                                            <span><em style="white-space:pre-line;" title="{strip_tags({Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue'))))})}">{Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue'))))}</em></span>
                                                        </div>
                                                    {else if $FIELDMODEL->get('postvalue') eq '' || ($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
                                                        &nbsp;(<del>{Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue'))))}</del> ) {vtranslate('LBL_IS_REMOVED')}</div>
                                                    {else if $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
                                                    &nbsp;{vtranslate('LBL_UPDATED')}</div>
                                                {else}
                                                &nbsp;{vtranslate('LBL_CHANGED')}</div>
                                            {/if}
                                            {if $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
                                                <div class="update-to"><span class="field-name">{vtranslate('LBL_TO')}</span>&nbsp;<span><em style="white-space:pre-line;">{Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('postvalue'))))}</em></span>
                                                </div>
                                            {/if}
	                                    <time class="update_time cursorDefault timeline-footer">
        	                                <small title="{Head_Util_Helper::formatDateTimeIntoDayString($RECENT_ACTIVITY->getActivityTime())}">
                	                            {Head_Util_Helper::formatDateDiffInStrings($RECENT_ACTIVITY->getActivityTime())}
                        	                </small>
                                	    </time>
                                            </div>
                                        {/if}
                                    {/foreach}
                                    </div>
                                </div>
				{/if}

                            {else if ($RECENT_ACTIVITY->isRelationLink() || $RECENT_ACTIVITY->isRelationUnLink())}
                                {assign var=RELATED_MODULE value= $RELATION->getLinkedRecord()->getModuleName()}
                                <div class="timeline-wrapper {if $key is odd}jotimeline-odd{else} inverted-timeline jotimeline-even {/if}">
                                    {if {$RELATED_MODULE|strtolower eq 'modcomments'}}
                                        {assign var="VICON_MODULES" value="joicon-chat"}
                                    {else}
                                        {assign var="VICON_MODULES" value="joicon-{$RELATED_MODULE|strtolower}"}
                                    {/if}
                                    <div class="update_icon bg-info bg-info-{$RELATED_MODULE|strtolower}">
										<i class="update_image {$VICON_MODULES}"></i>
                                    </div>
                                    <div class="update_info">
                                        <h5 class="timeline-title">
                                            {assign var=RELATION value=$RECENT_ACTIVITY->getRelationInstance()}
                                           <span class="field-name">
                                                {vtranslate($RELATION->getLinkedRecord()->getModuleName(), $RELATION->getLinkedRecord()->getModuleName())}
                                            </span>&nbsp; 
                                            <span>
                                                {if $RECENT_ACTIVITY->isRelationLink()}
                                                    {vtranslate('LBL_LINKED', $MODULE_NAME)}
                                                {else}
                                                    {vtranslate('LBL_UNLINKED', $MODULE_NAME)}
                                                {/if}
                                            </span>
                                        </h5>
                                        <div class='font-x-small updateInfoContainer textOverflowEllipsis'>
                                            <span>
                                                {if $RELATION->getLinkedRecord()->getModuleName() eq 'Calendar'}
                                                    {if isPermitted('Calendar', 'DetailView', $RELATION->getLinkedRecord()->getId()) eq 'yes'}
                                                        {assign var=PERMITTED value=1}
                                                    {else}
                                                        {assign var=PERMITTED value=0}
                                                    {/if}
                                                {else}
                                                    {assign var=PERMITTED value=1}
                                                {/if}
                                                {if $PERMITTED}
                                                    {if $RELATED_MODULE eq 'ModComments'}
                                                        {$RELATION->getLinkedRecord()->getName()}
                                                    {else}
                                                        {assign var=DETAILVIEW_URL value=$RELATION->getRecordDetailViewUrl()}
                                                        {if $DETAILVIEW_URL}<a {if stripos($DETAILVIEW_URL, 'javascript:') === 0}onclick{else}href{/if}='{$DETAILVIEW_URL}'>{/if}
                                                            <strong>{$RELATION->getLinkedRecord()->getName()}</strong>
                                                            {if $DETAILVIEW_URL}</a>{/if}
                                                        {/if}
                                                    {/if}
                                            </span>
                                        </div>
                                    	<time class="update_time cursorDefault timeline-footer">
                                            <small title="{Head_Util_Helper::formatDateTimeIntoDayString($RELATION->get('changedon'))}">
                                            {Head_Util_Helper::formatDateDiffInStrings($RELATION->get('changedon'))} </small>
                                    	</time>
                                    </div>
                                </div>
                            {else if $RECENT_ACTIVITY->isRestore()}
                            {/if}
                        {/if}
                    {/foreach}
                    <div class="container-fluid"><div id="loadMore">Load more ..</div><div id="showLess">.. Show less</div></div>
                    {if $PAGING_MODEL->isNextPageExists()}
                        <div id='more_button'>
                            <div class='update_icon bg-info' id="moreLink">
                                <button type="button" class="btn btn-success moreRecentUpdates">{vtranslate('LBL_MORE',$MODULE_NAME)}..</button>
                            </div>
                        </div>
                    {/if}
                </div>
            {else}
                <div class="summaryWidgetContainer no-recent-update">
                    <p class="textAlignCenter">{vtranslate('LBL_NO_RECENT_UPDATES')}</p>
                </div>
            {/if}
        </div>
    </div>
    
{/strip}
