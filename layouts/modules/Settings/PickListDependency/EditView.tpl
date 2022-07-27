{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Settings/PickListDependency/views/Edit.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{strip}
    <div class="editViewPageDiv card mt0">
    <div class = "row card-header-new ml20 mr10">
                <div class='col-md-12 col-sm-12'>
                <h4 class="">Add PickList Dependency <h4>
                </div>
                </div>
            <div class="editViewContainer container-fluid">
                <br>
                <form id="pickListDependencyForm" class="form-horizontal" method="POST">
                    {if !empty($MAPPED_VALUES)}
                        <input type="hidden" class="editDependency" value="true"/>
                    {/if}
                    <div class="editViewBody">
                        <div class="editViewContents">
                            <div class="form-group row">
                            <div class="col-md-2 col-sm-12"></div>
                                <label class="muted col-form-label col-sm-3 col-xs-12 pull-left col-5 ml20">{vtranslate('LBL_SELECT_MODULE', $QUALIFIED_MODULE)}</label>
                                <div class="controls col-sm-3 col-xs-12 col-6 col-md-4">
                                    <select name="sourceModule" class="select2 form-control marginLeftZero">
                                        {foreach item=MODULE_MODEL from=$PICKLIST_MODULES_LIST}
                                            {assign var=MODULE_NAME value=$MODULE_MODEL->get('name')}
                                            <option value="{$MODULE_NAME}" {if $MODULE_NAME eq $SELECTED_MODULE} selected {/if}>
                                                {if $MODULE_MODEL->get('label') eq 'Calendar'}
                                                    {vtranslate('LBL_TASK', $MODULE_MODEL->get('label'))}
                                                {else}
                                                    {vtranslate($MODULE_MODEL->get('label'), $MODULE_MODEL->get('label'))}
                                                {/if}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row ">
                            <div class="col-md-2 col-sm-12"></div>
                                <label class="muted col-form-label col-sm-3 col-xs-12 pull-left col-5 ml20">{vtranslate('LBL_SOURCE_FIELD', $QUALIFIED_MODULE)}</label>
                                <div class="controls col-sm-3 col-xs-12 col-6 col-md-4">
                                <select id="sourceField" name="sourceField" class="select2 form-control" data-placeholder="{vtranslate('LBL_SELECT_FIELD', $QUALIFIED_MODULE)}" data-rule-required="true">
                                    <option value=''></option>
                                    {foreach key=FIELD_NAME item=FIELD_LABEL from=$PICKLIST_FIELDS}
                                        <option value="{$FIELD_NAME}" {if $RECORD_MODEL->get('sourcefield') eq $FIELD_NAME} selected {/if}>{vtranslate($FIELD_LABEL, $SELECTED_MODULE)}</option>
                                    {/foreach}
                                </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                            <div class="col-md-2 col-sm-12"></div>
                                <label class="muted col-form-label col-sm-3 col-xs-12 pull-left col-5 ml20">{vtranslate('LBL_TARGET_FIELD', $QUALIFIED_MODULE)}</label>
                                <div class="controls col-sm-3 col-xs-12 col-6 col-md-4">
                                    <select id="targetField" name="targetField" class="select2 form-control" data-placeholder="{vtranslate('LBL_SELECT_FIELD', $QUALIFIED_MODULE)}" data-rule-required="true">
                                        <option value=''></option>
                                        {foreach key=FIELD_NAME item=FIELD_LABEL from=$PICKLIST_FIELDS}
                                            <option value="{$FIELD_NAME}" {if $RECORD_MODEL->get('targetfield') eq $FIELD_NAME} selected {/if}>{vtranslate($FIELD_LABEL, $SELECTED_MODULE)}</option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="row hide errorMessage" style="margin: 5px;">
                                <div class="alert alert-error">
                                  <strong>{vtranslate('LBL_ERR_CYCLIC_DEPENDENCY', $QUALIFIED_MODULE)}</strong>  
                                </div>
                            </div>
                            <br>
                            <div id="dependencyGraph">
                                {if $DEPENDENCY_GRAPH}
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12">
                                            {$DEPENDENCY_GRAPH}
                                        </div>
                                    </div>
                                {/if}
                            </div>
                        </div>
                    </div>
            <div class='modal-overlay-footer clearfix'>
                <div class="row clearfix">
                    <div class=' textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
                        <button type='submit' class='btn btn-primary saveButton' >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
                        <a class='cancelLink btn btn-danger'  href="javascript:history.back()" type="">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                    </div>
                </div>
            </div>
                </form>
            </div>
    </div>
{/strip}
