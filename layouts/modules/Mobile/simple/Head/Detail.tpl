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
    <section class="detail-content-wrapper" ng-controller="{$_controller}">
{literal}
        <header md-page-header fixed-top>
            <md-toolbar>
                <div class="md-toolbar-tools actionbar">
                    <md-button ng-click="gobacktoUrl()" class="md-icon-button" aria-label="side-menu-open">
                        <i class="mdi mdi-arrow-left actionbar-icon"></i>
                    </md-button>
                    <h2 flex>{{pageTitle}}</h2>
                    <span flex></span>
                    <md-button class="md-icon-button" ng-click="detailViewEditEvent();" aria-label="global-search">
                         <i class="mdi mdi-pencil actionbar-icon"></i>
                    </md-button>
                </div>
            </md-toolbar>
        </header>
        <section layout="row" flex class="content-section">
            <div flex class="detail-content">
                <div layout="column" layout-fill layout-align="top center" ng-if="fields.length">
                    <md-list class="fields-list" ng-controller="InlineEditorController"> <!-- infinite-scroll='loadMoreRecords()' infinite-scroll-distance='10'-->
                        <md-list-item class="md-2-line" ng-repeat="field in recordData">
                            <div class="md-list-item-text field-row">
                                <p class="field-label">
                                    {{field.label}}
                                </p>
                                <h3 class="field-value" ng-class="{'value-empty' : !field.value || field.value==='' || field.value==='--None' || field.value==0} ">
                                    {{field.value || field.label + ' not specified'}}
                                </h3>
                                <!--div class="tooltip" ng-click="$event.stopPropagation()" ng-show="showtooltip">
                                    <input type="text" ng-model="value" />
                                </div-->
                            </div>
                            <md-divider ></md-divider>
                        </md-list-item>
                    </md-list>

                </div>
                <div class="no-records-message" ng-if="!fields.length">
                    <div class="no-records">No Fields Found</div>
                </div>
                <div flex></div>
            </div>
        </section>
    </section>
{/literal}
