{strip}
<link type="text/css" rel="stylesheet" href="{$SITEURL}layouts/skins/activity.css" media="screen"/>
<div class="activityContents" id="activityContentsContainer">
    <div class="">
        <div class="card-head">
            <div class="card-inner">
                <div class="d-flex flex-center flex-justify-space-between">
                    <h5 class="card-title">Activity</h5>
                    <div class="d-flex flex-justify-end mini-search ml-auto">
                        <a class="btn-link {if $FILTER_TYPE == 'all'}black{/if}" id="all_activity" onclick="Settings_Users_Activity_Js.getUserActivityFilters('all', '')">All</a>
                        <a class="btn-link {if $FILTER_TYPE == 'today'}black{/if}" id="today_activity" onclick="Settings_Users_Activity_Js.getUserActivityFilters('today', '')">Today</a>
                        <a class="btn-link {if $FILTER_TYPE == 'yesterday'}black{/if}" id="yesterday_activity" onclick="Settings_Users_Activity_Js.getUserActivityFilters('yesterday', '')">Yesterday</a>
                        <a class="btn-link by_date_link {if $FILTER_TYPE == 'by_date'}black{/if}" id="by_date_activity">By date</a>
                        <div class="input-group editByDate hide">
                            {* <input id="by_date" class="by_date dateField" data-date-format="dd-mm-yyyy" style="width:70%">
                            <div class="input-save-wrap">
                                <span class="input-group-addon inlineAjaxSave" onclick="Settings_Users_Activity_Js.getUserActivityFilters('by_date', 'by_date')" style="padding:2px 2px"><i class="fa fa-check"></i></span>
                                <span class="input-group-addon inlineAjaxCancel cancelByDate" style="margin-left:31px;padding:2px 2px"><i class="fa fa-close"></i></span>
                            </div> *}
                            <div class="input-group">
                                <input id="by_date" class="by_date dateField" data-date-format="dd-mm-yyyy">
                                <div class="input-group-append">
                                <button class="btn btn-secondary" onclick="Settings_Users_Activity_Js.getUserActivityFilters('by_date', 'by_date')" type="button" style="bottom:4px;right:10px;">
                                    <i class="fa fa-search"></i>
                                </button>
                                </div>
                                </div>
                        </div>
                        <select id="activityusersFilter" onchange="Settings_Users_Activity_Js.getUserActivityFilters('all', '')" class="select2 activityusersFilter">
                            {foreach $ALLUSERS as $users}
                                <option value="{$users->id}" {if $SELECTED_USER == $users->id}selected{/if}>{$users->first_name}&nbsp;{$users->last_name}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
            </div><hr>
            <div class="card-body">
                {if count($ACTIVITIES) eq 0}
                    <p class="no-record">{vtranslate('LBL_NO_RECORDS_FOUND')}</p>
                {else}
                    <div class="accordian" id="accordionActivity" role="tablist" aria-multiselectable="true">
                        {assign var=i value=1}
                        {foreach item=ACTIVITYDATE from=$ACTIVITY_DATES key=key}
                            <div class="card">
                                <div class="card-header activity-card-head" data-id="1" role="tab" id="headingOne{$i}">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordionActivity" href="#collapse{$i}" aria-expanded="true"
                                        aria-controls="collapse1">
                                        <h5 class="mb-0">
                                            {$ACTIVITYDATE}<i class="fa fa-minus"></i>
                                        </h5>
                                    </a>
                                </div>
                                <div id="collapse{$i}" class="collapse {if ($key == '0')}
                                show {/if}" role="tabpanel" aria-labelledby="headingOne{$i}" data-parent="#accordionActivity">
                                    <div class="timeline card-body " id="timeline-{$key}">
                                        {foreach item=ACTIVITY from=$ACTIVITIES key=activity_key}
                                            {if ($ACTIVITY->isRelationLink()) or ($ACTIVITY->isRelationUnLink())}
                                                {assign var=RELATION value=$ACTIVITY->getRelationInstance()}
                                            {/if}
                                            {if $ACTIVITY->get('date') == $ACTIVITYDATE}
                                                {assign var=ClassName value=''}
                                                {if $ACTIVITY->get('status') == Head_Activity_Model::CREATE}
                                                    {assign var=ClassName value='content-created'}
                                                {else if $ACTIVITY->get('status') == Head_Activity_Model::UPDATE}
                                                    {assign var=ClassName value='content-updated'}
                                                {else if $ACTIVITY->get('status') == Head_Activity_Model::DELETE}
                                                    {assign var=ClassName value='content-deleted'}
                                                {else if $ACTIVITY->get('status') == Head_Activity_Model::LINK}
                                                    {assign var=ClassName value='content-linked'}
                                                {else if $ACTIVITY->get('status') == Head_Activity_Model::UNLINK}
                                                    {assign var=ClassName value='content-unlinked'}
                                                {else if $ACTIVITY->get('status') == Head_Activity_Model::RESTORE}
                                                    {assign var=ClassName value='content-restored'}
                                                {/if}
                                                <div class="container" data-status="{$ACTIVITY->get('status')}">
                                                    <div class="content-timeline {$ClassName}">
                                                        <div class="vertical-timeline-element-content bounce-in">
                                                            {if $ACTIVITY->isCreate()}
                                                                <div class="timeline-content-right">
                                                                    <h4>{$ACTIVITY->getModifiedBy()->getName()}&nbsp;
                                                                        <span class="timeline-title">{vtranslate('LBL_CREATED', $MODULE_NAME)}</span>
                                                                    </h4>
                                                                    <p>{Head_Util_Helper::formatDateDiffInStrings($ACTIVITY->get('changedon'))}</p>
                                                                </div>
                                                                <span class="vertical-timeline-element-date">{$ACTIVITY->get('time')}</span>
                                                            {else if $ACTIVITY->isUpdate()}
                                                                <div class="timeline-content-right">
                                                                    <h4>{$ACTIVITY->getModifiedBy()->getDisplayName()}&nbsp;
                                                                        <span class="timeline-title">{vtranslate('LBL_UPDATED', $MODULE_NAME)}</span>
                                                                    </h4>
                                                                    {foreach item=FIELDMODEL from=$ACTIVITY->getFieldInstances()}
                                                                        <p>
                                                                            {if $FIELDMODEL && $FIELDMODEL->getFieldInstance() && $FIELDMODEL->getFieldInstance()->isViewable() && $FIELDMODEL->getFieldInstance()->getDisplayType() neq '5'}
                                                                                <b>{vtranslate($FIELDMODEL->getName(),$MODULE_NAME)}</b>
                                                                                {if $FIELDMODEL->get('prevalue') neq '' && $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && ($FIELDMODEL->get('postvalue') eq '0' || $FIELDMODEL->get('prevalue') eq '0'))}
                                                                                    <span> &nbsp;{vtranslate('LBL_CHANGED')}{Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue'))))}</span>
                                                                                {else if $FIELDMODEL->get('postvalue') eq '' || ($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
                                                                                    &nbsp;(<del>{Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue'))))}</del> ) {vtranslate('LBL_IS_REMOVED')}
                                                                                {else if $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
                                                                                    &nbsp;{vtranslate('LBL_UPDATED')}
                                                                                {else}
                                                                                    &nbsp;{vtranslate('LBL_CHANGED')}
                                                                                {/if}
                                                                            {/if}
                                                                            {if $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
                                                                                &nbsp;<span class="field-name">{vtranslate('LBL_TO')}</span>&nbsp;<span><b>{Head_Util_Helper::toHead6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('postvalue'))))}</b></span>
                                                                            {/if}
                                                                        </p>
                                                                    {{/foreach}}
                                                                </div>
                                                                <span class="vertical-timeline-element-date">{$ACTIVITY->get('time')}</span>
                                                            {else if $ACTIVITY->isDelete()}
                                                                <div class="timeline-content-right">
                                                                    <h4>{$ACTIVITY->getModifiedBy()->getDisplayName()}&nbsp;
                                                                        <span class="timeline-title">{vtranslate('LBL_DELETED', $MODULE_NAME)}</span>
                                                                    </h4>
                                                                    <p>
                                                                        {$ACTIVITY->get('module')} {vtranslate('LBL_RECORD')} {vtranslate('LBL_IS_REMOVED')}
                                                                    </p>
                                                                </div>
                                                                <span class="vertical-timeline-element-date">{$ACTIVITY->get('time')}</span>
                                                            {else if ($ACTIVITY->isRelationLink() || $ACTIVITY->isRelationUnLink())}
                                                                {assign var=RELATED_MODULE value= $RELATION->getLinkedRecord()->getModuleName()}
                                                                {if {$RELATED_MODULE|strtolower eq 'modcomments'}}
                                                                    {assign var="VICON_MODULES" value="joicon-chat"}
                                                                {else}
                                                                    {assign var="VICON_MODULES" value="joicon-{$RELATED_MODULE|strtolower}"}
                                                                {/if}
                                                                {assign var=RELATION value=$ACTIVITY->getRelationInstance()}
                                                                <div class="timeline-content-right">
                                                                    <h4>{$ACTIVITY->getModifiedBy()->getDisplayName()}&nbsp;
                                                                        <span class="timeline-title">
                                                                            {if $ACTIVITY->isRelationLink()}
                                                                                {vtranslate('LBL_LINKED', $MODULE_NAME)}&nbsp;
                                                                            {else}
                                                                                {vtranslate('LBL_UNLINKED', $MODULE_NAME)}&nbsp;
                                                                            {/if}
                                                                        </span>
                                                                    </h4>
                                                                    <p>
                                                                        <span>{vtranslate($RELATION->getLinkedRecord()->getModuleName(), $RELATION->getLinkedRecord()->getModuleName())}</span>&nbsp;
                                                                        <span>
                                                                            {if $ACTIVITY->isRelationLink()}
                                                                                {vtranslate('LBL_LINKED', $MODULE_NAME)}&nbsp;
                                                                            {else}
                                                                                {vtranslate('LBL_UNLINKED', $MODULE_NAME)}&nbsp;
                                                                            {/if}
                                                                        </span>
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
                                                                    </p>
                                                                </div>
                                                                <span class="vertical-timeline-element-date">{$ACTIVITY->get('time')}</span>
                                                            {else if $ACTIVITY->isRestore()}
                                                                <div class="timeline-content-right">
                                                                    <h4>{$ACTIVITY->getModifiedBy()->getDisplayName()}&nbsp;
                                                                        <span class="timeline-title">{vtranslate('LBL_RESTORED', $MODULE_NAME)}</span>
                                                                    </h4>
                                                                    <p>
                                                                        {$ACTIVITY->get('module')} {vtranslate('LBL_RECORD')} {vtranslate('LBL_IS')} {vtranslate('LBL_RESTORED')}
                                                                    </p>
                                                                </div>
                                                                <span class="vertical-timeline-element-date">{$ACTIVITY->get('time')}</span>
                                                            {/if}
                                                        </div>
                                                    </div>
                                                </div>
                                            {/if}
                                        {/foreach}
                                        <div class="container-fluid">
                                        <div id="loadMore">Load more ..</div>
                                        <div id="showLess">.. Show less</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {assign var=i value=$i+1}
                        {/foreach}
                    </div>                        
                {/if}
            </div>
        </div>
    </div>
</div>

{/strip}
