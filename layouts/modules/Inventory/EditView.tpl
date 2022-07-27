{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
                <form class="form-horizontal recordEditView" id="EditView" name="edit" method="post" action="{$SITEURL}index.php" enctype="multipart/form-data">
                    <!-- <div class="editViewHeader">
                        <div class='row'>
                        <div class="col-lg-12 col-md-12 col-lg-pull-0">
                                {assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
                                {if $RECORD_ID neq ''}
                                    <h4 class="editHeader" style="margin-top:5px;" title="{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} {$RECORD_STRUCTURE_MODEL->getRecordName()}">{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} - {$RECORD_STRUCTURE_MODEL->getRecordName()}</h4>
                                {else}
                                    <h4 class="editHeader" style="margin-top:5px;">{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)}</h4>
                                {/if}
                            </div>
                        </div>
                    </div> -->
                    <div class="editViewBody">
                        <div class="editViewContents">
                            {assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
                            {assign var=QUALIFIED_MODULE_NAME value={$MODULE}}
                            {assign var=IS_PARENT_EXISTS value=strpos($MODULE,":")}
                            {if $IS_PARENT_EXISTS}
                                {assign var=SPLITTED_MODULE value=":"|explode:$MODULE}
                                <input type="hidden" name="module" value="{$SPLITTED_MODULE[1]}" />
                                <input type="hidden" name="parent" value="{$SPLITTED_MODULE[0]}" />
                            {else}
                                <input type="hidden" name="module" value="{$MODULE}" />
                            {/if}
                            <input type="hidden" name="action" value="Save" />
                            <input type="hidden" name="record" id="recordId" value="{$RECORD_ID}" />
			    <input type="hidden" name="save_and_new" value="false" />
                            <input type="hidden" name="defaultCallDuration" value="{$USER_MODEL->get('callduration')}" />
                            <input type="hidden" name="defaultOtherEventDuration" value="{$USER_MODEL->get('othereventduration')}" />
                            {if $IS_RELATION_OPERATION }
                                <input type="hidden" name="sourceModule" value="{$SOURCE_MODULE}" />
                                <input type="hidden" name="sourceRecord" value="{$SOURCE_RECORD}" />
                                <input type="hidden" name="relationOperation" value="{$IS_RELATION_OPERATION}" />
                            {/if}
                            {if $RETURN_VIEW}
                                <input type="hidden" name="returnmodule" value="{$RETURN_MODULE}" />
                                <input type="hidden" name="returnview" value="{$RETURN_VIEW}" />
                                <input type="hidden" name="returnrecord" value="{$RETURN_RECORD}" />
                                <input type="hidden" name="returntab_label" value="{$RETURN_RELATED_TAB}" />
                                <input type="hidden" name="returnrelatedModule" value="{$RETURN_RELATED_MODULE}" />
                                <input type="hidden" name="returnpage" value="{$RETURN_PAGE}" />
                                <input type="hidden" name="returnviewname" value="{$RETURN_VIEW_NAME}" />
                                <input type="hidden" name="returnsearch_params" value='{Head_Functions::jsonEncode($RETURN_SEARCH_PARAMS)}' />
                                <input type="hidden" name="returnsearch_key" value={$RETURN_SEARCH_KEY} />
                                <input type="hidden" name="returnsearch_value" value={$RETURN_SEARCH_VALUE} />
                                <input type="hidden" name="returnoperator" value={$RETURN_SEARCH_OPERATOR} />
                                <input type="hidden" name="returnsortorder" value={$RETURN_SORTBY} />
                                <input type="hidden" name="returnorderby" value="{$RETURN_ORDERBY}" />
                                <input type="hidden" name="returnmode" value={$RETURN_MODE} />
                                <input type="hidden" name="returnrelationId" value="{$RETURN_RELATION_ID}" />
                            {/if}
                            {include file="partials/EditViewContents.tpl"|@vtemplate_path:'Inventory'}
                        </div>
                    </div>
                    <div class="modal-overlay-footer">
                        <div class="row clearfix">
                            <div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
				<button type='submit' class='btn btn-primary saveButton' data-savebtn="true">{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
				<button type='submit' class='btn btn-success save-and-new-Button' id="save-and-new-Button">{vtranslate('LBL_SAVE_AND_NEW', $MODULE)}</button>&nbsp;&nbsp;
				<a class='cancelLink btn btn-danger' href="javascript:history.back()" type="">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
</div>
