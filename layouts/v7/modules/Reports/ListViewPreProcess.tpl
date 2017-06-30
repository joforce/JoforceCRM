{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

{include file="modules/Vtiger/partials/Topbar.tpl"}

<div class="container-fluid app-nav">
    <div class="row">
        {include file="partials/SidebarHeader.tpl"|vtemplate_path:$MODULE}
        {include file="ModuleHeader.tpl"|vtemplate_path:$MODULE}
    </div>
</div>
</nav>
<div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-60' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class="data">
    </div>
    <div class="modal-dialog">
    </div>
</div>
<div class="main-container main-container-{$MODULE}">
    {assign var=LEFTPANELHIDE value=$CURRENT_USER_MODEL->get('leftpanelhide')}
    <div id="modnavigator" class="module-nav" style='width:42px;height:100% !important;'>
   <div class="hidden-xs hidden-sm mod-switcher-container">
      {assign var="topMenus" value=$MENU_STRUCTURE->getTop()}
      {assign var="moreMenus" value=$MENU_STRUCTURE->getMore()}
      {assign var=APP_GROUPED_MENU value=Settings_MenuEditor_Module_Model::getAllVisibleModules()}
      {assign var=APP_LIST value=Vtiger_MenuStructure_Model::getAppMenuList()}
      <div id="modules-menu" class="dropdown modules-menu app-modules-dropdown-container" style='background: #2c3b49 none repeat scroll 0 0;'>
         <ul title="" class="module-qtip">
            <li  class="" title='MARKETING'>
               <input type='hidden' class='app-name' value='MARKETING'>


               <a href="">
                  <i class="app-icon-list fa fa-users"></i>
                  <!--                    <span class="app-icon-list fa fa-users"></span>-->
               </a>
       <div class="docs-submenu docs-marketing-submenu">
          <ul>
            <li>
               <a href="{$SITEURL}Leads/List/MARKETING" title="Leads">
                  <span class="vicon-leads module-icon docs-submenu-icon"></span>
                  <span class="module-name textOverflowEllipsis docs-submenu-text"> Leads</span>
               </a>
            </li>
            <li>
                <a href="{$SITEURL}Campaigns/List/MARKETING" title="Campaigns">
                  <span class="vicon-campaigns module-icon docs-submenu-icon"></span>
                  <span class="module-name textOverflowEllipsis docs-submenu-text"> Campaigns</span>
                </a>
            </li>
            <li>
               <a href="{$SITEURL}Contacts/List/MARKETING" title="Contacts">
                  <span class="vicon-contacts module-icon docs-submenu-icon"></span>
                  <span class="module-name textOverflowEllipsis docs-submenu-text"> Contacts</span>
               </a>
            </li>
         </ul>
       </div>
       </li>
            {foreach item=APP_NAME from=$APP_LIST}
            {if $APP_NAME eq 'MARKETING'} 
            {if count($APP_GROUPED_MENU.$APP_NAME) gt 0}
            <ul class="dropdown-menu app-modules-dropdown" aria-labelledby="{$APP_NAME}_modules_dropdownMenu">
               {foreach item=moduleModel key=moduleName from=$APP_GROUPED_MENU[$APP_NAME]}
               {assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
               <li>
                  <a href="{$moduleModel->getDefaultUrl()}&app={$APP_NAME}" title="{$translatedModuleLabel}">
                  <span class="vicon-{strtolower($moduleName)} module-icon"></span>
                  <span class="module-name textOverflowEllipsis"> {$translatedModuleLabel}</span>
                  </a>
               </li>
               {/foreach}
            </ul>
            {/if}{/if}      {/foreach}
         </ul>
         <ul title="" class="module-qtip">
            <li class="" title="SALES">
               <a href="">
                  <i class="app-icon-list fa fa-dot-circle-o"></i>
                  <!--                    <span class="app-icon-list fa fa-users"></span>-->
               </a>
                <div class="docs-submenu docs-sales-submenu">
                    <ul>

                        <li>
                           <a href="{$SITEURL}Quotes/List/SALES" title="Quotes">
                           <span class="vicon-quotes module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Quotes</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Products/List/SALES" title="Products">
                           <span class="vicon-products module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Products</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Services/List/SALES" title="Services">
                           <span class="vicon-services module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Services</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Contacts/List/SALES" title="Contacts">
                           <span class="vicon-contacts module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Contacts</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}SMSNotifier/List/SALES" title="SMS Notifier">
                           <span class="vicon-smsnotifier module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> SMS Notifier</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Potentials/List/SALES" title="Opportunities">
                           <span class="vicon-potentials module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Opportunities</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Accounts/List/SALES" title="Organizations">
                           <span class="vicon-accounts module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Organizations</span>
                           </a>
                        </li>
                  
                  </ul>
                </div>
            </li>
            {foreach item=APP_NAME from=$APP_LIST}
            {if $APP_NAME eq 'SALES'}
            {if count($APP_GROUPED_MENU.$APP_NAME) gt 0}
            <ul class="dropdown-menu app-modules-dropdown" aria-labelledby="{$APP_NAME}_modules_dropdownMenu">
               {foreach item=moduleModel key=moduleName from=$APP_GROUPED_MENU[$APP_NAME]}
               {assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
               <li>
                  <a href="{$moduleModel->getDefaultUrl()}&app={$APP_NAME}" title="{$translatedModuleLabel}">
                  <span class="vicon-{strtolower($moduleName)} module-icon"></span>
                  <span class="module-name textOverflowEllipsis"> {$translatedModuleLabel}</span>
                  </a>
               </li>
               {/foreach}
            </ul>
            {/if}{/if}              {/foreach}
         </ul>
         <ul title="" class="module-qtip">
            <li class="" title='INVENTORY'>
               <a href="">
                  <i class="app-icon-list vicon-inventory"></i>
                  <!--                    <span class="app-icon-list fa fa-users"></span>-->
               </a>
                <div class="docs-submenu docs-inventory-submenu">
                    <ul>
                        <li>
                           <a href="{$SITEURL}SalesOrder/List/INVENTORY" title="Sales Orders">
                           <span class="vicon-salesorder module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Sales Orders</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Invoice/List/INVENTORY" title="Invoices">
                           <span class="vicon-invoice module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Invoices</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Products/List/INVENTORY" title="Products">
                           <span class="vicon-products module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Products</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Vendors/List/INVENTORY" title="Vendors">
                           <span class="vicon-vendors module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Vendors</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}PriceBooks/List/INVENTORY" title="Price Books">
                           <span class="vicon-pricebooks module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Price Books</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}PurchaseOrder/List/INVENTORY" title="Purchase Orders">
                           <span class="vicon-purchaseorder module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Purchase Orders</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Services/List/INVENTORY" title="Services">
                           <span class="vicon-services module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Services</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Accounts/List/INVENTORY" title="Organizations">
                           <span class="vicon-accounts module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Organizations</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Contacts/List/INVENTORY" title="Contacts">
                           <span class="vicon-contacts module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Contacts</span>
                           </a>
                        </li>
                  </ul>
                </div>
            </li>
            {foreach item=APP_NAME from=$APP_LIST}
            {if $APP_NAME eq 'INVENTORY'}
            {if count($APP_GROUPED_MENU.$APP_NAME) gt 0}
            <ul class="dropdown-menu app-modules-dropdown" aria-labelledby="{$APP_NAME}_modules_dropdownMenu">
               {foreach item=moduleModel key=moduleName from=$APP_GROUPED_MENU[$APP_NAME]}
               {assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
               <li>
                  <a href="{$moduleModel->getDefaultUrl()}&app={$APP_NAME}" title="{$translatedModuleLabel}">
                  <span class="vicon-{strtolower($moduleName)} module-icon"></span>
                  <span class="module-name textOverflowEllipsis"> {$translatedModuleLabel}</span>
                  </a>
               </li>
               {/foreach}
            </ul>
            {/if}{/if}              {/foreach}
         </ul>
         <ul title="" class="module-qtip">
            <li class="" title='SUPPORT'>
               <a href="">
                  <i class="app-icon-list fa fa-life-ring"></i>
                  <!--                    <span class="app-icon-list fa fa-users"></span>-->
               </a>
                <div class="docs-submenu docs-support-submenu">
                    <ul>
                        <li>
                           <a href="{$SITEURL}HelpDesk/List/SUPPORT" title="Tickets">
                           <span class="vicon-helpdesk module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Tickets</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Accounts/List/SUPPORT" title="Organizations">
                           <span class="vicon-accounts module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Organizations</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}SMSNotifier/List/SUPPORT" title="SMS Notifier">
                           <span class="vicon-smsnotifier module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> SMS Notifier</span>
                           </a>
                        </li>
                        <li>
                           <a href="{$SITEURL}Contacts/List/SUPPORT" title="Contacts">
                           <span class="vicon-contacts module-icon docs-submenu-icon"></span>
                           <span class="module-name textOverflowEllipsis docs-submenu-text"> Contacts</span>
                           </a>
                        </li>
                  </ul>
                </div>
            </li>
            {foreach item=APP_NAME from=$APP_LIST}
            {if $APP_NAME eq 'SUPPORT'}
            {if count($APP_GROUPED_MENU.$APP_NAME) gt 0}
            <ul class="dropdown-menu app-modules-dropdown" aria-labelledby="{$APP_NAME}_modules_dropdownMenu">
               {foreach item=moduleModel key=moduleName from=$APP_GROUPED_MENU[$APP_NAME]}
               {assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
               <li>
                  <a href="{$moduleModel->getDefaultUrl()}&app={$APP_NAME}" title="{$translatedModuleLabel}">
                  <span class="vicon-{strtolower($moduleName)} module-icon"></span>
                  <span class="module-name textOverflowEllipsis"> {$translatedModuleLabel}</span>
                  </a>
               </li>
               {/foreach}
            </ul>
            {/if}{/if}              {/foreach}
         </ul>
         <ul title="" class="module-qtip">
            <li class="" title='PROJECTS'>
               <a href="">
                  <i class="app-icon-list fa fa-briefcase"></i>
                  <!--                    <span class="app-icon-list fa fa-users"></span>-->
               </a>
                <div class="docs-submenu docs-project-submenu">
                   <ul>
                     <li>
                        <a href="{$SITEURL}Project/List/PROJECT" title="Projects">
                        <span class="vicon-project module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> Projects</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}ProjectTask/List/PROJECT" title="Project Tasks">
                        <span class="vicon-projecttask module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> Project Tasks</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}ProjectMilestone/List/PROJECT" title="Project Milestones">
                        <span class="vicon-projectmilestone module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> Project Milestones</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}Contacts/List/PROJECT" title="Contacts">
                        <span class="vicon-contacts module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> Contacts</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}Accounts/List/PROJECT" title="Organizations">
                        <span class="vicon-accounts module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> Organizations</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}PBXManager/List/PROJECT" title="PBX Manager">
                        <span class="vicon-pbxmanager module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> PBX Manager</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}VTPDFMaker/List/PROJECT" title="PDF Maker">
                        <span class="vicon-vtpdfmaker module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> PDF Maker</span>
                        </a>
                     </li>
                     <li>
                        <a href="{$SITEURL}EmailPlus/List/PROJECT" title="Email Plus">
                        <span class="vicon-emailplus module-icon docs-submenu-icon"></span>
                        <span class="module-name textOverflowEllipsis docs-submenu-text"> Email Plus</span>
                        </a>
                     </li>
                  </ul>
                </div>
            </li>
            {foreach item=APP_NAME from=$APP_LIST}
            {if $APP_NAME eq 'PROJECT'}
            {if count($APP_GROUPED_MENU.$APP_NAME) gt 0}
            <ul class="dropdown-menu app-modules-dropdown" aria-labelledby="{$APP_NAME}_modules_dropdownMenu">
               {foreach item=moduleModel key=moduleName from=$APP_GROUPED_MENU[$APP_NAME]}
               {assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
               <li>
                  <a href="{$moduleModel->getDefaultUrl()}&app={$APP_NAME}" title="{$translatedModuleLabel}">
                  <span class="vicon-{strtolower($moduleName)} module-icon"></span>
                  <span class="module-name textOverflowEllipsis"> {$translatedModuleLabel}</span>
                  </a>
               </li>
               {/foreach}
            </ul>
            {/if}{/if}              {/foreach}
         </ul>
         <!--        {foreach key=moduleName item=moduleModel from=$SELECTED_CATEGORY_MENU_LIST}
            {assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
            <ul title="{$translatedModuleLabel}" class="module-qtip">
                    <li {if $MODULE eq $moduleName}class="active"{else}class=""{/if}>
                            <a href="{$moduleModel->getDefaultUrl()}&app={$SELECTED_MENU_CATEGORY}">
                                    <i class="vicon-{strtolower($moduleName)}"></i>
                                    <span>{$translatedModuleLabel}</span>
                            </a>
                    </li>
            </ul>
            {/foreach}-->
            
      </div>
      

   </div>
</div>
   <!--  <div id="modnavigator" class="module-nav">
        <div class="hidden-xs hidden-sm mod-switcher-container">
        </div>
    </div> -->
    <div id="sidebar-essentials" class="sidebar-essentials {if $LEFTPANELHIDE eq '1'} hide {/if}" style="padding-left:42px;">
        {include file="partials/SidebarEssentials.tpl"|vtemplate_path:$MODULE}
    </div>

    <div class="listViewPageDiv sidebar-table content-area {if $LEFTPANELHIDE eq '1'} full-width {/if}" id="listViewContent" style="position: relative;{if $LEFTPANELHIDE eq '1'} left:42px; {else} left:0px;{/if}">

