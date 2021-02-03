{strip}

<style>

/* Activity Css start */
.activityContents .card {
  border: 0;
  -webkit-box-shadow: 0 10px 20px 0 rgba(0,0,0,.05);
  box-shadow: 0 10px 20px 0 rgba(0,0,0,.05);
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid rgba(0,0,0,.125);
  border-radius: .25rem;
  padding: 0;
}

.activityContents .card-body {
  -webkit-box-flex: 1;
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  padding: 1rem;
}

.activityContents .card-head, .card-header {
  background-color: hsla(0,0%,100%,0) !important;
  padding: 20px 0;
  margin-bottom: 0;
  background-color: rgba(0,0,0,.03);
}

.activityContents .card-head .mini-search select.custom-select{
  width:100%;
}

.activityContents .card-head .d-flex, .card-header .d-flex {
  display: flex !important;
}

.activityContents .card-head .mini-search{
  width: auto;
}

.activityContents .card-inner{
  padding: 0 20px;
}

.activityContents .card-footer {
  background-color: hsla(0,0%,100%,0) !important;
  padding: .5rem 1rem;
  background-color: rgba(0,0,0,.03);
  /* border-top: 1px solid rgba(0,0,0,.125); */
}


.btn-link {
  font-weight: normal;
  color: #909AAE !important;
  background: transparent;
  letter-spacing: 0.46px;
  text-align: right;
  font-size: 14px;
  padding: 0 10px;
  padding-top: 10px;
}

.no-record{
  font-size: 12px;
  color: rgba(29,38,69,0.50);
  letter-spacing: 0;
  text-align: center;
}

.activityusersFilter {
  width: 200px;
}

.btn-link:hover{
  text-decoration: none;
}

.activityContents {
  margin-top: 5%;
}

.activityContents .card {
  width: 100%;
}

.activityContents h4 {
  margin-bottom: 0px;
}
 
.flex-justify-end{
  justify-content: flex-end;
}

.card-title{
  margin-bottom: 0;
  font-weight: 600;
  font-size: 20px;
  letter-spacing: 0;
}

.ml-auto {
  margin-left: auto !important; 
}

.flex-center{
  align-items: center;
}

.flex-justify-space-between{
  justify-content: space-between;
}

.mini-search{
  width: auto;
}

@media screen and (max-width: 767px) {
  .card-head {
      padding-bottom: 5px;
      padding-top: 15px;
  }
}

@media(max-width:1100px){
  .activityContents .card {
    width: 78%;
    margin-left: 21%;
  }
}

@media(max-width:900px){
  .activityContents {
    margin-top: 15%;
  }
}

@media(max-width:800px){
  .activityContents .card {
    width: 100%;
    margin-left: 0;
  }

  .activityContents {
    margin-top: 10%;
  }
}

@media(max-width:500px){
  .activityContents {
    margin-top: 13%;
  }
}

@media(max-width:300px){
  .activityContents {
    margin-top: 45%;
  }
}
.accordian .card .card-header h5 {
  color: #fff;
  font-size: 14px;
  color: #fff;
  letter-spacing: 2px;
  text-transform: uppercase;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.accordian {
  width: 100%;
}

.accordian .card {
  border-radius: 0;
  box-shadow: -1px 0px 23px 0 #e6e7e8;
  margin-bottom: 2%;
  width: 100% !important;
  overflow-x: hidden;
  margin-left: 0;
  padding: 0;
}

.accordian .card-header{
  padding: 10px;
  background-color: #ecf0fa !important;
}

.activity-card-head i.fa{
  font-size: 12px;
}

.accordian .card .card-body{
  padding: 20px;
  border-top: 1px solid #eee;
}

@media (min-width: 1200px) {
  .col-lg-6 {
      width: 49%;
  }
}
.activity-card-head i.fa-minus {
  float: right;
}

.activity-card-head a.collapsed ~ .activity-card-head {
  border-bottom: 1px solid #eee;
}

.activity-card-head a.collapsed i.fa-minus:before {
  content: "\f067" !important;
}

.activity-card {
  margin-bottom: 1% !important;
  margin-right: 1% !important;
}

.activity-head {
  padding: 10px;
}

.activity-head h5 {
  font-size: 14px;
}

.fa-minus:before {
  content: "\f068";
}

.mb-0, .my-0 {
  margin-bottom: 0 !important;
}

.collapse {
  display: none;
}

.collapse.show {
  display: block !important;
  visibility: visible;
}

.card .card {
  border: 1px solid #dee2e6;
}

.by_date {
  border: 1px solid #eee;
  margin-right: 10px;
  height: 40px;
}

.activityContents .input-save-wrap {
  width: 22% !important;
  margin-right: 8px;
}

/* The actual timeline (the vertical ruler) */
.timeline::after {
  content: '';
  position: absolute;
  width: 2px;
  background-color: #ecf0fa;
  top: 25px;
  bottom: 30px;
  left: 10%;
}

.timeline {
  width: 100%;
  position: relative;
  padding: 1.5rem 0 1rem !important;
}

/* The circles on the timeline */
.content-timeline::before {
  content: '';
  position: absolute;
  width: 12px;
  height: 12px;
  left: 7%;
  background-color: white;
  top: 15%;
  border-radius: 50%;
  z-index: 1;
  box-shadow: 0 0 0 4px #fff;
}

.content-created::before, .content1:before {
  border: 3px solid #FF9F55;
}

.content-updated::before, .content2:before {
  border: 3px solid #2EC6BD;
}

.content-linked::before, .content3:before {
  border: 3px solid #369AFF;
}

.content-deleted::before, .content4:before {
  border: 3px solid #F64E61;
}

.content-unlinked:before, .content5:before {
  border: 3px solid #A277FE;
}

.content-restored:before, .content6:before {
  border: 3px solid #ffc107;
}

.content-timeline {
  padding: 10px 30px;
  position: relative;
  border-radius: 6px;
}

.left {
  margin-left: -2%;
}

.right {
  left: 50%;
}

.vertical-timeline-element-date {
  display: block;
  position: absolute;
  left: -11%;
  top: 0;
  padding-right: 10px;
  text-align: right;
  color: #635E5E;
  font-size: .7619rem;
  white-space: nowrap
}

.vertical-timeline-element-content {
  position: relative;
  margin-left: 90px;
  font-size: .8rem
}

.vertical-timeline-element-content .timeline-title {
  font-size: .8rem;
  text-transform: uppercase;
  margin: 0 0 .5rem;
  padding: 2px 0 0;
  font-weight: bold
}

.vertical-timeline-element-content h4 {
  font-size: .9rem;
  margin: 0 0 .5rem;
  padding: 2px 0 0;
}

.vertical-timeline-element-content:after {
  content: "";
  display: table;
  clear: both
}

@media screen and (max-width: 1300px) {
  .content-timeline::before {
    left: 7.5%;
  }

  .timeline::after {
    left: 11%;
  }
}

@media screen and (max-width: 1250px) {
  .timeline::after {
    left: 107px;
  }
}

@media screen and (max-width: 1200px) {
  .timeline::after {
    left: 15%;
  }

  .content-timeline::before {
    left: 12%;
  }

  .timeline-content-right {
    padding-left: 3%;
  }
}

@media screen and (max-width: 1150px) {
  .timeline::after {
    left: 15%;
  }

  .content-timeline::before {
    left: 11.5%;
  }

  .timeline-content-right {
    padding-left: 3%;
  }
}

@media screen and (max-width: 1120px) {
  .timeline::after {
    left: 15%;
  }

  .content-timeline::before {
    left: 11%;
  }

  .timeline-content-right {
    padding-left: 3%;
  }
}

@media screen and (max-width: 1070px) {
  .timeline::after {
    left: 18%;
  }

  .content-timeline::before {
    left: 12.5%;
  }

  .timeline-content-right {
    padding-left: 5%;
  }
}

.black {
  color: black !important;
}
/* Activity css end */

</style>

<div class="activityContents" id="activityContentsContainer">
    <div class="card">
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
                            <input id="by_date" class="by_date dateField" data-date-format="dd-mm-yyyy">
                            <div class="input-save-wrap">
                                <span class="pointerCursorOnHover input-group-addon input-group-addon-save inlineAjaxSave" onclick="Settings_Users_Activity_Js.getUserActivityFilters('by_date', 'by_date')" ><i class="fa fa-check"></i></span>
                                <span class="pointerCursorOnHover input-group-addon input-group-addon-cancel inlineAjaxCancel cancelByDate"><i class="fa fa-close"></i></span>
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
                                <div id="collapse{$i}" class="collapse" role="tabpanel" aria-labelledby="headingOne{$i}" data-parent="#accordionActivity">
                                    <div class="timeline card-body">
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
