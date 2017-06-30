{*<!--
/*************************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
**************************************************************************************/
-->*}
{*<!--
/*************************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
**************************************************************************************/
-->*}
{include file="../Header.tpl" scripts=$_scripts}
{include file="../Vtiger/Toolbar.tpl"}

<section layout="row" flex class="content-section" ng-controller="{$_controller}">
    {include file="../Vtiger/SideMenu.tpl"}
    {literal}
        <md-button class="md-fab md-primary float-button md-fab-bottom-right" aria-label="addnew">
            <i class="mdi mdi-plus"></i>
        </md-button>
        <div flex class="list-content">
            <div class="list-filters" layout="row" >
                <div flex="60" class="change-filter">
                    <md-input-container class="current-filter">
                        <md-select ng-model="selectedFilter" aria-label="filter">
                            <md-optgroup label="Mine">
                                <md-option ng-repeat="filter in filters.Mine" ng-value="filter.id">{{filter.name}}</md-option>
                            </md-optgroup>
                            <md-optgroup label="Shared">
                                <md-option ng-repeat="filter in filters.Shared" ng-value="filter.id">{{filter.name}}</md-option>
                            </md-optgroup>
                        </md-select>
                    </md-input-container>
                </div>
                <div flex="40" class="sort-filter" ng-show="records.length">
                    <md-input-container class="current-sort-field">
                        <md-select ng-model="orderBy" aria-label="sortfield" placeholder="Select sort field">
                            <md-option ng-repeat="header in headers" ng-value="header.name">{{header.label}}</md-option>
                        </md-select>
                    </md-input-container>
                </div>
            </div>

            <div layout="column" layout-fill layout-align="top center" ng-if="records.length">
                <md-list class="records-list">
                    <md-list-item class="md-3-line" data-record-id="{{record.id}}" aria-label="row+{{record.id}}" ng-model="showActions" md-swipe-right="showActions=false;$event.stopPropagation();" md-swipe-left="showActions=true;$event.stopPropagation();" ng-click="gotoDetailView(record.id)" ng-repeat="record in records">
                        <img ng-src="../../layouts/v7/modules/Mobile/simple/resources/images/default_1.png" class="md-avatar" alt="{{item.name}}" />
                        <div class="md-list-item-text">
                            <h3>
                                <span ng-repeat="label in headers">
                                    <span  ng-repeat="name in nameFields" ng-if="label.name === name">{{record[label.name] + " "}}</span>
                                </span>
                            </h3>
                            <p class="header-fields" ng-repeat="header in headers" ng-if="headerIndex(nameFields,header.name)== -1">
                                {{record[header.name] || header.label + ' not specified'}}
                            </p>    
                        </div>
                        <div class="actions-slider animate-show" ng-show="showActions" ng-swipe-right="hideRecordActions();" ng-animate="{enter: 'animate-enter', leave: 'animate-leave'}">
                            <div class="button-wrap">
                                <md-button class="list-action-edit md-icon-button"  aria-label="list-action-edit" ng-click="listViewEditEvent(record.id);$event.stopPropagation();">
                                    <i class="mdi mdi-pencil"></i>
                                </md-button>
                                <md-button class="list-action-delete md-icon-button" aria-label="list-action-delete" ng-click="showConfirmDelete($event, record.id);$event.stopPropagation();">
                                    <i class="mdi mdi-delete"></i>
                                </md-button>
                            </div>
                        </div>
                        <md-divider ></md-divider>
                    </md-list-item>
                    <md-list-item class="md-1-line load-more-link" >
                        <div ng-click="loadMoreRecords()" ng-show="moreRecordsExists">
                            Load more records
                        </div>
                        <div ng-show="!moreRecordsExists" class="thats-all">
                            That's All
                        </div>
                    </md-list-item>
                </md-list>

            </div>
            <div class="no-records-message" ng-if="!records.length">
                <div class="no-records">No Records Found</div>
            </div>
            <div flex></div>
        </div>
    </section>
{/literal}
{include file="../Footer.tpl"}