{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is: vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
 * Contributor(s): JoForce.com
  *
 ********************************************************************************/
-->*}
{strip}
    {assign var=ALL_CONDITION_CRITERIA value=$ADVANCE_CRITERIA[2] }
    {assign var=ANY_CONDITION_CRITERIA value=$ADVANCE_CRITERIA[1] }

    {if empty($ALL_CONDITION_CRITERIA) }
        {assign var=ALL_CONDITION_CRITERIA value=array()}
    {/if}

    {if empty($ANY_CONDITION_CRITERIA) }
        {assign var=ANY_CONDITION_CRITERIA value=array()}
    {/if}
    <div class="filterContainer">
        <input type="hidden" name="date_filters"
            data-value='{Head_Util_Helper::toSafeHTML(ZEND_JSON::encode($DATE_FILTERS))}' />
        <input type=hidden name="advanceFilterOpsByFieldType"
            data-value='{ZEND_JSON::encode($ADVANCED_FILTER_OPTIONS_BY_TYPE)}' />
        {foreach key=ADVANCE_FILTER_OPTION_KEY item=ADVANCE_FILTER_OPTION from=$ADVANCED_FILTER_OPTIONS}
            {$ADVANCED_FILTER_OPTIONS[$ADVANCE_FILTER_OPTION_KEY] = vtranslate($ADVANCE_FILTER_OPTION, $MODULE)}
        {/foreach}
        <input type=hidden name="advanceFilterOptions" data-value='{ZEND_JSON::encode($ADVANCED_FILTER_OPTIONS)}' />
        <div class="form_trigger_element add_border or_condition" style="width:74%">
        <h5><b>When any one of the rule meet?</b></h5>
            <div class="allConditionContainer conditionGroup contentsBackground" style="padding-bottom:15px;">
                <br>
                <div class="contents">
                    <div class="conditionList">
                    {assign var=orcount value=0 }
                        {foreach item=CONDITION_INFO from=$ALL_CONDITION_CRITERIA['columns']}
                            {$orcount = $orcount+1}
                            {include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE CONDITION_INFO=$CONDITION_INFO MODULE=$MODULE COUNT=$orcount}
                        {/foreach}
                        {if count($ALL_CONDITION_CRITERIA) eq 0}
                            {include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE=$MODULE CONDITION_INFO=array()}
                        {/if}
                    </div>
                    <div class="hide basic">
                        {include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE CONDITION_INFO=array() MODULE=$MODULE NOCHOSEN=true}
                    </div>
                    <br>
                    
                    <div class="groupCondition">
                        {assign var=GROUP_CONDITION value=$ALL_CONDITION_CRITERIA['condition']}
                        {if empty($GROUP_CONDITION)}
                            {assign var=GROUP_CONDITION value="and"}
                        {/if}
                        <input type="hidden" name="condition" value="{$GROUP_CONDITION}" />
                    </div>
                </div>
                <div class="addCondition">
                       <button class="btn newbtn"><a href="#" id="add_new_or_row">And <i class="fa fa-plus"></i></a></button>
                       <style>
                            
                            button.btn.newbtn {
                border: 2px solid #dadada!important;
                border-radius: 30px!important;
                            }
                             button.btn.newbtn::after{
                                 content="\f067";
                                font-family: 'Font Awesome 5 Free';
                                font-weight: 900;
                                float: right;
                             }
                       </style>
                    </div>
            </div>
            </div>
            <div class="form_trigger_element add_border and_condition" style="width:74%">
            <h5><b>When all the rules meet?</b></h5>
            <div class="anyConditionContainer conditionGroup contentsBackground">
                <div class="contents">
                    <div class="conditionList">
                    {assign var=andcount value=0 }
                        {foreach item=CONDITION_INFO from=$ANY_CONDITION_CRITERIA['columns']}
                            {$andcount = $andcount+1}
                            {include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE CONDITION_INFO=$CONDITION_INFO MODULE=$MODULE CONDITION="or" COUNT=$andcount}
                        {/foreach}
                        {if count($ANY_CONDITION_CRITERIA) eq 0}
                            {include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE=$MODULE CONDITION_INFO=array() CONDITION="or"}
                        {/if}
                    </div>
                    <div class="hide basic">
                        {include file='AdvanceFilterCondition.tpl'|@vtemplate_path:$QUALIFIED_MODULE RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE=$MODULE CONDITION_INFO=array() CONDITION="or" NOCHOSEN=true}
                    </div>
                    <br>
                </div>
                <div class="addCondition">
                        <button class="btn newbtn"><a href="#" id="add_new_or_row">Or <i class="fa fa-plus" ></i></a></button>
                </div>
            </div>
            </div>
    </div>
{/strip}