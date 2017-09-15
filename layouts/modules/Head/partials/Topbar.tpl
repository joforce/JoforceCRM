{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{strip}
{include file="modules/Head/Header.tpl"}
{assign var="APP_IMAGE_MAP" value=[	'MARKETING' => 'fa-users',
'SALES' => 'fa-dot-circle-o',
'SUPPORT' => 'fa-life-ring',
'INVENTORY' => 'vicon-inventory',
'PROJECT' => 'fa-briefcase' ]}
<nav class="navbar navbar-default navbar-fixed-top app-fixed-navbar">
<div class="container-fluid global-nav">
   <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-3 app-navigator-container">
         <div class="row">
            <div id="appnavigator" class="col-sm-2 col-xs-2 cursorPointer app-switcher-container" data-app-class="{if $MODULE eq 'Home' || !$MODULE}fa-dashboard{else}{$APP_IMAGE_MAP[$SELECTED_MENU_CATEGORY]}{/if}">
               <div class="row app-navigator">
                  <span class="app-icon fa fa-bars"></span>
               </div>
            </div>
            <div class="logo-container col-lg-8 col-md-8 col-sm-8 col-xs-8">
               <div class="row">
                  <a href="{$SITEURL}" class="company-logo">
                  <img src="{$COMPANY_LOGO->get('imagepath')}" alt="{$COMPANY_LOGO->get('alt')}"/>
                  </a>
               </div>
            </div>
         </div>
      </div>
      <!-- <div class="search-links-container col-md-3 col-lg-3 hidden-sm">
         <div class="search-link hidden-xs">
            <span class="fa fa-search" aria-hidden="true"></span>
            <input class="keyword-input" type="text" placeholder="{vtranslate('LBL_TYPE_SEARCH')}" value="{$GLOBAL_SEARCH_VALUE}">
            <span id="adv-search" class="adv-search fa fa-chevron-circle-down pull-right cursorPointer" aria-hidden="true"></span>
         </div>
      </div> -->
      <div id="navbar" class="col-sm-6 col-md-3 col-lg-3 collapse navbar-collapse navbar-right global-actions">
         <ul class="nav navbar-nav">
            <li>
               <div>
                  <div class="container-fluid">
                     <div class="searchbardiv" id="joforce-search-section">
			 <div class="search-link hidden-xs">
                           <div class="input-group">
                              <input type="text" id="joforce-search-box" class="keyword-input form-control" name="s" id="s" placeholder="Search....">
                              <div class="input-group-btn">
                                 <span class="input-group-btn">
                                 <button class="btn btn-danger joforce-search-close" type="reset"><span class="glyphicon glyphicon-remove"></span></button>
                                 </span>
                              </div>
                           </div>
			</div>
                     </div>
                  </div>
                  <a href="#"> <button type="button" class="joforce-search-btn" id="joforce-search-btn">
                  <i class="fa fa-search openclosesearch"></i>
                  </button></a>
               </div>
            </li>
            <li>
               <div class="dropdown">
                  <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                     <a href="#" id="menubar_quickCreate" class="qc-button fa fa-plus-circle" title="{vtranslate('LBL_QUICK_CREATE',$MODULE)}" aria-hidden="true"></a>
                  </div>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="width:500px;">
                     <li class="title" style="padding: 5px 0 0 15px;">
                        <strong>{vtranslate('LBL_QUICK_CREATE',$MODULE)}</strong>
                     </li>
                     <hr/>
                     <li id="quickCreateModules" style="padding: 0 5px;">
                        <div class="col-lg-12" style="padding-bottom:15px;">
                           {foreach key=moduleName item=moduleModel from=$QUICK_CREATE_MODULES}
                           {if $moduleModel->isPermitted('CreateView') || $moduleModel->isPermitted('EditView')}
                           {assign var='quickCreateModule' value=$moduleModel->isQuickCreateSupported()}
                           {assign var='singularLabel' value=$moduleModel->getSingularLabelKey()}
                           {assign var=hideDiv value={!$moduleModel->isPermitted('CreateView') && $moduleModel->isPermitted('EditView')}}
                           {if $quickCreateModule == '1'}
                           {if $count % 3 == 0}
                           <div class="row">
                              {/if}
                              {* Adding two links,Event and Task if module is Calendar *}
                              {if $singularLabel == 'SINGLE_Calendar'}
                              {assign var='singularLabel' value='LBL_TASK'}
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-lg-4{/if}">
                                 <a id="menubar_quickCreate_Events" class="quickCreateModule" data-name="Events"
                                    data-url="index.php?module=Events&view=QuickCreateAjax" href="javascript:void(0)"><i class="vicon-calendar pull-left"></i><span class="quick-create-module">{vtranslate('LBL_EVENT',$moduleName)}</span></a>
                              </div>
                              {if $count % 3 == 2}
                           </div>
                           <br>
                           <div class="row">
                              {/if}
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-lg-4{/if}">
                                 <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModule" data-name="{$moduleModel->getName()}"
                                    data-url="{$moduleModel->getQuickCreateUrl()}" href="javascript:void(0)"><i class="vicon-task pull-left"></i><span class="quick-create-module">{vtranslate($singularLabel,$moduleName)}</span></a>
                              </div>
                              {if !$hideDiv}
                              {assign var='count' value=$count+1}
                              {/if}
                              {else if $singularLabel == 'SINGLE_Documents'}
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-lg-4{/if} dropdown">
                                 <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModuleSubmenu dropdown-toggle" data-name="{$moduleModel->getName()}" data-toggle="dropdown" 
                                    data-url="{$moduleModel->getQuickCreateUrl()}" href="javascript:void(0)">
                                 <i class="vicon-{strtolower($moduleName)} pull-left"></i>
                                 <span class="quick-create-module">
                                 {vtranslate($singularLabel,$moduleName)}
                                 <i class="fa fa-caret-down quickcreateMoreDropdownAction"></i>
                                 </span>
                                 </a>
                                 <ul class="dropdown-menu quickcreateMoreDropdown" aria-labelledby="menubar_quickCreate_{$moduleModel->getName()}">
                                    <li class="dropdown-header"><i class="fa fa-upload"></i> {vtranslate('LBL_FILE_UPLOAD', $moduleName)}</li>
                                    <li id="HeadAction">
                                       <a href="javascript:Documents_Index_Js.uploadTo('Head')">
                                       <img style="  margin-top: -3px;margin-right: 4%;" title="JoForce" alt="JoForce" src="{$SITEURL}layouts/skins//images/JoForce.png">
                                       {vtranslate('LBL_TO_SERVICE', $moduleName, {vtranslate('LBL_VTIGER', $moduleName)})}
                                       </a>
                                    </li>
                                    <li class="dropdown-header"><i class="fa fa-link"></i> {vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', $moduleName)}</li>
                                    <li id="shareDocument"><a href="javascript:Documents_Index_Js.createDocument('E')">&nbsp;<i class="fa fa-external-link"></i>&nbsp;&nbsp; {vtranslate('LBL_FROM_SERVICE', $moduleName, {vtranslate('LBL_FILE_URL', $moduleName)})}</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li id="createDocument"><a href="javascript:Documents_Index_Js.createDocument('W')"><i class="fa fa-file-text"></i> {vtranslate('LBL_CREATE_NEW', $moduleName, {vtranslate('SINGLE_Documents', $moduleName)})}</a></li>
                                 </ul>
                              </div>
                              {else}
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-lg-4{/if}">
                                 <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModule" data-name="{$moduleModel->getName()}"
                                    data-url="{$moduleModel->getQuickCreateUrl()}" href="javascript:void(0)">
                                 <i class="vicon-{strtolower($moduleName)} pull-left"></i>
                                 <span class="quick-create-module">{vtranslate($singularLabel,$moduleName)}</span>
                                 </a>
                              </div>
                              {/if}
                              {if $count % 3 == 2}
                           </div>
                           <br>
                           {/if}
                           {if !$hideDiv}
                           {assign var='count' value=$count+1}
                           {/if}
                           {/if}
                           {/if}
                           {/foreach}
                        </div>
                     </li>
                  </ul>
               </div>
            </li>
            {assign var=USER_PRIVILEGES_MODEL value=Users_Privileges_Model::getCurrentUserPrivilegesModel()}
            {assign var=CALENDAR_MODULE_MODEL value=Head_Module_Model::getInstance('Calendar')}
            {if $USER_PRIVILEGES_MODEL->hasModulePermission($CALENDAR_MODULE_MODEL->getId())}
            <li>
               <div><a href="{$SITEURL}Calendar/{$CALENDAR_MODULE_MODEL->getDefaultViewName()}" class="fa fa-calendar" title="{vtranslate('Calendar','Calendar')}" aria-hidden="true"></a></div>
            </li>
            {/if}
            {assign var=REPORTS_MODULE_MODEL value=Head_Module_Model::getInstance('Reports')}
            {if $USER_PRIVILEGES_MODEL->hasModulePermission($REPORTS_MODULE_MODEL->getId())}
            <li>
               <div><a href="{$SITEURL}Reports/List" class="fa fa-bar-chart" title="{vtranslate('Reports','Reports')}" aria-hidden="true"></a></div>
            </li>
            {/if}
            {if $USER_PRIVILEGES_MODEL->hasModulePermission($CALENDAR_MODULE_MODEL->getId())}
            <li>
               <div><a href="#" class="taskManagement joforce-task vicon vicon-task" title="{vtranslate('Tasks','Head')}" aria-hidden="true"></a></div>
            </li>
            {/if}
            <li class="dropdown">
               <div style="margin-top: -5px;">
                  {assign var=last_name value=$USER_MODEL->get('last_name')}
                  {assign var=first_name value=$USER_MODEL->get('first_name')}
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" style="background: transparent;">
                     {assign var=IMAGE_DETAILS value=$USER_MODEL->getImageDetails()}
                     {if $IMAGE_DETAILS neq '' && $IMAGE_DETAILS[0] neq '' && $IMAGE_DETAILS[0].path neq ''}
                     {foreach item=IMAGE_INFO from=$IMAGE_DETAILS}
                     {if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
                     <div class="profile-img"><img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" class="user-image"></div>
                     {/if}
                     {/foreach}
                     {else}
                     <div class="profile-img"><button type="button" class="avatar-circle user-image">{if $first_name neq ''} {$first_name[0]} {else} {$last_name[0]}{/if}</button></div>
                     {/if}
                  </a>
                  <ul class="dropdown-menu profile-dropdown">
                     <h4>{$USER_MODEL->get('first_name')} {$USER_MODEL->get('last_name')}</h4>
                     <h5 class="textOverflowEllipsis" title='{$USER_MODEL->get('user_name')}'>{$USER_MODEL->get('user_name')}</h5>
                     <p>{$USER_MODEL->getUserRoleName()}</p>
                     <li class="divider"></li>
                     <li><a id="menubar_item_right_LBL_MY_PREFERENCES" href="{$USER_MODEL->getPreferenceDetailViewUrl()}">{vtranslate('LBL_MY_PREFERENCES')}</a></li>
                     <li><a id="menubar_item_right_LBL_SIGN_OUT" href="{$SITEURL}Users/Logout">{vtranslate('LBL_SIGN_OUT')}</a></li>
                  </ul>
               </div>
            </li>
         </ul>
      </div>
   </div>
</div>
{/strip}

