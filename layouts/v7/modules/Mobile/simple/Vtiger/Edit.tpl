{*<!--
/*************************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Commercial
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
**************************************************************************************/
-->*}
{include file="../Header.tpl" scripts=$_scripts}
<script type="text/javascript" src="../../{$TEMPLATE_WEBPATH}/Vtiger/js/Edit.js"></script>
{literal}

    <form name="editForm" id="field-edit-form" ng-submit="saveThisRecord()" ng-controller="VtigerEditController">
        <header md-page-header fixed-top>
            <md-toolbar>
                <div class="md-toolbar-tools actionbar">
                    <md-button ng-click="gobacktoUrl()" class="md-icon-button" aria-label="side-menu-open">
                        <i class="mdi mdi-window-close actionbar-icon"></i>
                    </md-button>
                    <h2 flex>Edit Title</h2>
                    <span flex></span>
                </div>
            </md-toolbar>
        </header>
        <section layout="row" flex class="content-section">
            <div layout="column" class="edit-content" layout-fill layout-align="top center" ng-if="fieldsData.length">
                <md-list class="fields-list">
                    <md-list-item class="md-1-line" ng-repeat="field in fieldsData" ng-if="field.editable">
                        <div class="md-list-item-text field-row">
                            <md-input-container style="width:100%;">
                                <label>{{field.label}}</label>
                                <input name="field.name" ng-model="field.value" type="text" ng-required="field.mandatory">
                                <div ng-messages="myForm.name.$error">
                                    <div ng-show="field.mandatory" ng-message="required"> Mandatory Field.</div>
                                </div>
                            </md-input-container>
                        </div>
                    </md-list-item>
                </md-list>
            </div>
            <div class="no-records-message" ng-if="!fieldsData.length">
                <div class="no-records">No Fields Found</div>
            </div>
            <div flex></div>
        </section>
    </form>
{/literal}
