{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
<div class="tagContainer">
    <div class="tag-contents {if empty($TAGS_LIST)} hide{/if}">
        <div class="detailTagList" data-num-of-tags-to-show="{Head_Tag_Model::NUM_OF_TAGS_DETAIL}">
            {foreach from=$TAGS_LIST item=TAG_MODEL name=tagCounter}
                {if $smarty.foreach.tagCounter.iteration gt Head_Tag_Model::NUM_OF_TAGS_DETAIL}
                     {break}
                {/if}
                {assign var=TAG_LABEL value=$TAG_MODEL->getName()}
                {include file="Tag.tpl"|vtemplate_path:$MODULE}
            {/foreach}

            <a href="javascript:void(0);" class="moreTags {if count($TAGS_LIST) <= Head_Tag_Model::NUM_OF_TAGS_DETAIL } hide {/if}">
                <span class="tagMoreCount">{count($TAGS_LIST)-Head_Tag_Model::NUM_OF_TAGS_DETAIL}</span>
                &nbsp;{vtranslate('LBL_MORE',$MODULE)|strtolower}
            </a>
        </div>
    </div>
    <div id="addTagContainer" >
        <a id="addTagTriggerer" class="badge">
            <i class="fa fa-plus"></i>
            {vtranslate('LBL_ADD_NEW_TAG',$MODULE)}
        </a>
    </div>
    <div class="viewAllTagsContainer hide">
        <div class="modal-dialog">
            <div class="modal-content" style="min-height:200px">
                {assign var="TITLE" value="{vtranslate('LBL_TAG_FOR',$MODULE,$RECORD->getName())}"}
                {include file="ModalHeader.tpl"|vtemplate_path:$MODULE}
                <div class="modal-body detailShowAllModal">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-12 col-md-4 col-form-label">
                            {vtranslate('LBL_CURRENT_TAGS',$MODULE)}
                        </label>
                        <div class="col-lg-9 col-sm-12 col-md-8 ">
                            <div class="currentTag multiLevelTagList form-control">
                                {foreach item=TAG_MODEL from=$TAGS_LIST}
                                    {include file="Tag.tpl"|vtemplate_path:$MODULE }
                                {/foreach}
                            </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </div>
   {include file="AddTagUI.tpl"|vtemplate_path:$MODULE RECORD_NAME=$RECORD->getName()}
</div>
<div id="dummyTagElement" class="hide">
{assign var=TAG_MODEL value=Head_Tag_Model::getCleanInstance()}
{include file="Tag.tpl"|vtemplate_path:$MODULE}
</div>
      
