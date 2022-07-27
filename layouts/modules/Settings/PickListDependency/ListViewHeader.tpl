{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Settings/PickListDependency/views/List.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="listViewPageDiv card" id="listViewContent">
    <div class="col-sm-12 col-xs-12">
        <div id="listview-actions" class="listview-actions-container">
        <div class = "row card-header-new picklist_dependancy_setting">
                <div class='col-md-6 '>
                <h4 class="">PickList Dependency<h4>
                </div>






                <div class="col-md-6 ">
                    <div class="row pull-right">
                        <span class="pull-right listViewActions dropbox" style="padding-right: 15px;">
                            <select class="select2 pickListSupportedModules " name="pickListSupportedModules" style="min-width: 220px;">
                                <option value="">{vtranslate('LBL_ALL', $QUALIFIED_MODULE)}</option>
                                {foreach item=MODULE_MODEL from=$PICKLIST_MODULES_LIST}
                                    {assign var=MODULE_NAME value=$MODULE_MODEL->get('name')}
                                    <option value="{$MODULE_NAME}" {if $MODULE_NAME eq $FOR_MODULE} selected {/if}>
                                        {if $MODULE_MODEL->get('label') eq 'Calendar'}
                                            {vtranslate('LBL_TASK', $MODULE_MODEL->get('label'))}
                                        {else}
                                            {vtranslate($MODULE_MODEL->get('label'), $MODULE_MODEL->get('label'))}
                                        {/if}
                                    </option>
                                {/foreach}
                            </select>
                        </span>
                    </div>
                </div>
            </div>
            <br>
            <div class="list-content row">
