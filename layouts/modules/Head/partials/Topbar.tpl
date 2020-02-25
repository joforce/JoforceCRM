{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{strip}
{include file="modules/Head/Header.tpl"}
{assign var="APP_IMAGE_MAP" value=$SECTION_ARRAY}
<nav class="navbar navbar-default navbar-fixed-top app-fixed-navbar">
<div class="container-fluid global-nav pl0">
   <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-1 app-navigator-container">
         <div class="row">
            <div class="logo-container pl30">
               <div class="row">
		{assign var=BOARDID value=getDefaultBoardId()}
		{if $BOARDID eq '1'}
                  <a href="{$SITEURL}Home/view/List" class="company-logo">
                  <img src="{$COMPANY_LOGO->get('imagepath')}" alt="{$COMPANY_LOGO->get('alt')}" style="width:100px !important;"/>
                  </a>
		{else}
		  <a href="{$SITEURL}" class="company-logo">
                  <img src="{$COMPANY_LOGO->get('imagepath')}" alt="{$COMPANY_LOGO->get('alt')}" style="width:100px !important;"/>
                  </a>
		{/if}
               </div>
            </div>
         </div>
      </div>
      <div class="col-lg-7 col-md-4 col-sm-3 pl0 pr0"> 
      <div class="user-image-mobile">
         
         <li class="dropdown">
               <div style="margin-top: -5px;">
	          {assign var=USER_PRIVILEGES_MODEL value=Users_Privileges_Model::getCurrentUserPrivilegesModel()}
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
         <div class="profile-info">
                           <div class="profile-content mt30">
                           <div class="col-md-4 col-sm-4 col-xs-4 profile-logo">
            {if $IMAGE_DETAILS neq '' && $IMAGE_DETAILS[0] neq '' && $IMAGE_DETAILS[0].path neq ''}
                              {foreach item=IMAGE_INFO from=$IMAGE_DETAILS}
                                     {if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
                                       <img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" class="img-responsive img-circle">
                                     {/if}
                                 {/foreach}
                           {else}
                                 <div class="user-image"><div class="avatar-circle img-responsive img-circle">{if $first_name neq ''} {$first_name[0]} {else} {$last_name[0]}{/if}</div></div>
                           {/if}
                           </div>
                           <div class="col-md-8 col-sm-8 col-xs-8">
                              <p class="mt10 mb0">{$USER_MODEL->get('first_name')} {$USER_MODEL->get('last_name')}</p>
                              <p>{$USER_MODEL->get('email1')}</p>
                              <p><a href="{$USER_MODEL->getPreferenceDetailViewUrl()}" class="btn btn-secondary">{vtranslate('LBL_MY_ACCOUNT', 'Head')}</a></p>
                           </div>
                        </div>
                        <div class="profile-footer">
         {if $USER_MODEL->isAdminUser()}
                           <a href="{$SITEURL}Head/Settings/Index" class="user-settings"><i class="fa fa-gears"></i>{vtranslate('LBL_SETTINGS', 'Head')}</a>
         {/if}
                           <a href="{$SITEURL}Users/action/Logout" class="btn btn-secondary pull-right">{vtranslate('LBL_SIGN_OUT')}</a>
                        </div>
                     </div>
                  </ul>
               </div>
            </li>
         
      </div>
		<div class="nav-responsive"><i class="fa fa-bars"></i></div>
	      	<div class="nav-responsive-tab">
	         <ul class="nav navbar">
        	    <li class="dropdown">
	               {vtranslate('LBL_MENU', 'HEAD')}<i class="fa fa-caret-down"></i>
        	    </li>
	         </ul>
		</div>
      	<ul class="nav navbar custom-nav col-md-offset-1" id="short-cut-modules">
	{assign var="hidden_tab_array" value =Settings_MenuManager_Module_Model::getMainMenuModuleNamesOnly()}
	{assign var=tabs_array  value=Settings_MenuManager_Module_Model::getMainMenuModuleIds()}

	{foreach item=tabarray from=$MAIN_MENU_TAB_IDS}
	    {assign var=type value=$tabarray['type']}
		{if $type == 'module'}
			{assign var=tabid value=$tabarray['tabid']}
			{assign var=moduleModel value=Settings_MenuManager_Module_Model::getModuleInstanceById($tabid)}
			{if $USER_PRIVILEGES_MODEL->hasModulePermission($tabid)}
			    {if $moduleModel->isActive()}
	                	{assign var=moduleName value=$moduleModel->get('name')}
				{assign var=temp_value value=$moduleName}
	        	        {assign var=translatedModuleLabel value=vtranslate($moduleModel->get('label'),$moduleName )}
				{assign var=listid value=getCvIdOfAll($moduleName)}
                		<li class="custom-menu-list" id="{$moduleName}">
				    <a class="menu-list" href="{$moduleModel->getListViewUrl()}">{$translatedModuleLabel} </a> 
		                </li>
			    {/if}
			{/if}
		{else}
			{assign var=linklabel value=$tabarray['name']}
			{assign var=temp_value value=$linklabel}
			{assign var=linkurl value=$tabarray['linkurl']}
				<li class="custom-menu-list" id="{$linklabel}">
                                       <a class="menu-list" href="{$linkurl}">{$linklabel}</a>
                                </li> 
		{/if}
	{/foreach}

	<input type="hidden" name=shot-cut-menu-array[] id="shot-cut-menu-array" value="{$hidden_tab_array}">
	
	{if $QUALIFIED_MODULE eq $MODULE}
		{if !in_array($MODULE, $tabs_array)}
			<li class="custom-menu-list temporary-main-menu active" id="{$MODULE}">
				{assign var=moduleid value=getTabid($MODULE)}
				{assign var=moduleModel value=Settings_MenuManager_Module_Model::getModuleInstanceById($moduleid)}
				{assign var=translatedModuleLabel value=vtranslate($moduleModel->get('label'),$MODULE )}
        	        	<a class="menu-list" href="{$moduleModel->getListViewUrl()}"> {$translatedModuleLabel} </a>
                	</li>
		{/if}
	{/if}

        <li class="dropdown">
	    <a href="" class="fa fa-ellipsis-h dropdown-toggle menu-list" data-toggle="dropdown" style="font-weight: normal;"></a>
	    <!--dropdown start-->
	    <ul class="dropdown-menu p0">
		{foreach key=SECTION_NAME item=ICON from=$SECTION_ARRAY}
		   <li class="dropdown dropdown-toggle menu-Header"data-toggle="dropdown">
                        <i class="{$ICON}"></i>{$SECTION_NAME}
			<ul class="mega-Menus dropdown-menu  ">
			    {foreach item=tabid from=$APP_MODULE_ARRAY[$SECTION_NAME]}
			       {assign var=moduleModel value=Head_Module_Model::getModuleInstanceById($tabid)}
                               {if $moduleModel->isActive()}
                                   {assign var=moduleName value=$moduleModel->get('name')}
                                   {assign var=translatedModuleLabel value=vtranslate($moduleModel->get('label'),$moduleName )}
				   {if $USER_PRIVILEGES_MODEL->hasModulePermission($tabid)}
					<li>
                                       	    <a href="{$moduleModel->getListViewUrl()}" class="dropdown-item">
                                           	{if $moduleName == 'EmailPlus'}
                                           	    <i class="joicon-mailmanager mr10"></i>{$translatedModuleLabel}
                                           	{elseif $moduleName == 'PDFMaker'}
                                                    <i class="fa fa-file-pdf-o mr10"></i>{$translatedModuleLabel}
                                           	{else}
                                                    <i class="joicon-{strtolower($moduleName)} mr10"></i>{$translatedModuleLabel}
                                           	{/if}
                                       	    </a>
                                   	</li>
				   {/if}
                               {/if}
                           {/foreach}
			</ul>	
		   </li>
		{/foreach}
	    </ul>
	    <!--dropdown end-->
	</li>
      	</ul>
	</div>
      <div id="navbar" class="col-sm-4 col-md-3 col-lg-3 pr10 collapse navbar-collapse navbar-right global-actions" style="margin-left: -30px;">
         <ul class="nav navbar-nav">
            <li>
		<div class='search-link' style="border: none;">
                      <input type="text" id="joforce-search-box" class="keyword-input form-control" name="s" id="s" placeholder="search...">
                </div>
            </li>
            <li class="dropdown" style="">
                <div style="" class="" id="gndropdown">
                    <a class="dropdown-toggle fa fa-bell global-notification-toggle" data-toggle="dropdown" role="button" style="background: transparent;" aria-expanded="true" title="Notifications" aria-hidden="true"></a>
		    {if $NOTIFICATONS_COUNT gt 0}
			<div class='notification_count'>{$NOTIFICATONS_COUNT}</div>
		    {/if}
                    <ul class="dropdown-menu global-notification-dropdown" id='global-notification-dropdown' role="menu">
                    </ul>
                </div>
            </li>
            <li>
               <div class="dropdown">
                  <div class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                     <a href="#" id="menubar_quickCreate" class="qc-button fa fa-plus" title="{vtranslate('LBL_QUICK_CREATE',$MODULE)}" aria-hidden="true"></a>
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
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-md-4{/if}">
                                 <a id="menubar_quickCreate_Events" class="quickCreateModule" data-name="Events"
                                    data-url="index.php?module=Events&view=QuickCreateAjax" href="javascript:void(0)"><i class="joicon-calendar pull-left"></i><span class="quick-create-module">{vtranslate('LBL_EVENT',$moduleName)}</span></a>
                              </div>
                              {if $count % 3 == 2}
                           </div>
                           <br>
                           <div class="row">
                              {/if}
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-md-4{/if}">
                                 <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModule" data-name="{$moduleModel->getName()}"
                                    data-url="{$moduleModel->getQuickCreateUrl()}" href="javascript:void(0)"><i class="joicon-task pull-left"></i><span class="quick-create-module">{vtranslate($singularLabel,$moduleName)}</span></a>
                              </div>
                              {if !$hideDiv}
                              {assign var='count' value=$count+1}
                              {/if}
                              {else if $singularLabel == 'SINGLE_Documents'}
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-lg-4{/if} dropdown">
                                 <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModuleSubmenu dropdown-toggle" data-name="{$moduleModel->getName()}" data-toggle="dropdown" 
                                    data-url="{$moduleModel->getQuickCreateUrl()}" href="javascript:void(0)">
                                 <i class="joicon-{strtolower($moduleName)} pull-left"></i>
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
                              <div class="{if $hideDiv}create_restricted_{$moduleModel->getName()} hide{else}col-md-4{/if}">
                                 <a id="menubar_quickCreate_{$moduleModel->getName()}" class="quickCreateModule" data-name="{$moduleModel->getName()}"
                                    data-url="{$moduleModel->getQuickCreateUrl()}" href="javascript:void(0)">
                                 <i class="joicon-{strtolower($moduleName)} pull-left"></i>
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
            {assign var=CALENDAR_MODULE_MODEL value=Head_Module_Model::getInstance('Calendar')}
            {if $USER_PRIVILEGES_MODEL->hasModulePermission($CALENDAR_MODULE_MODEL->getId())}
            <li>
               <div><a href="{$SITEURL}Calendar/view/{$CALENDAR_MODULE_MODEL->getDefaultViewName()}" class="joicon joicon-calendar" title="{vtranslate('Calendar','Calendar')}" aria-hidden="true"></a></div>
            </li>
            {/if}
            {assign var=REPORTS_MODULE_MODEL value=Head_Module_Model::getInstance('Reports')}
            {if $USER_PRIVILEGES_MODEL->hasModulePermission($REPORTS_MODULE_MODEL->getId())}
            <li>
               <div><a href="{$SITEURL}Reports/view/List" class="fa fa-bar-chart" title="{vtranslate('Reports','Reports')}" aria-hidden="true"></a></div>
            </li>
            {/if}
            {if $USER_PRIVILEGES_MODEL->hasModulePermission($CALENDAR_MODULE_MODEL->getId())}
            <li>
               <div><a href="#" class="taskManagement joforce-task joicon joicon-task" title="{vtranslate('Tasks','Head')}" aria-hidden="true"></a></div>
            </li>
            {/if}
	    <li>
		<div>
			<a href="https://docs.joforce.com" target="_blank" class="fa fa-question-circle" aria-hidden="true" style="font-size:18px" title="{vtranslate('LBL_HELP', 'Head')}"></a>
		</div>
	    </li>
            <li class="dropdown" style="padding: 0px 5px;">
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
			<div class="profile-info">
                           <div class="profile-content mt30">
                           <div class="col-md-4 profile-logo">
				{if $IMAGE_DETAILS neq '' && $IMAGE_DETAILS[0] neq '' && $IMAGE_DETAILS[0].path neq ''}
                          	   {foreach item=IMAGE_INFO from=$IMAGE_DETAILS}
                                     {if !empty($IMAGE_INFO.path) && !empty({$IMAGE_INFO.orgname})}
                                     	<img src="{$SITEURL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" class="img-responsive img-circle">
                                     {/if}
                             	   {/foreach}
	                        {else}
                                	<div class="user-image"><div class="avatar-circle img-responsive img-circle">{if $first_name neq ''} {$first_name[0]} {else} {$last_name[0]}{/if}</div></div>
                     		{/if}
                           </div>
                           <div class="col-md-8">
                              <p class="mt10 mb0">{$USER_MODEL->get('first_name')} {$USER_MODEL->get('last_name')}</p>
                              <p>{$USER_MODEL->get('email1')}</p>
                              <p><a href="{$USER_MODEL->getPreferenceDetailViewUrl()}" class="btn btn-secondary">{vtranslate('LBL_MY_ACCOUNT', 'Head')}</a></p>
                           </div>
                        </div>
                        <div class="profile-footer">
			{if $USER_MODEL->isAdminUser()}
                           <a href="{$SITEURL}Head/Settings/Index" class="user-settings"><i class="fa fa-gears mr5"></i>{vtranslate('LBL_SETTINGS', 'Head')}</a>
			{/if}
                           <a href="{$SITEURL}Users/action/Logout" class="btn btn-secondary pull-right">{vtranslate('LBL_SIGN_OUT')}</a>
                        </div>
                     </div>
                  </ul>
               </div>
            </li>
         </ul>
      </div>
   </div>
</div>
{/strip}

<script type="text/javascript">
   $(document).ready(function(){
      $('.nav-responsive').click(function(){
         $('.custom-nav').toggle();
      });
      $('.nav-responsive-tab').click(function(){
         $('.custom-nav').toggle();
      });
      $('.menu-Header').mouseover(function(){
	$(this).children('.mega-Menus').css({
		'display':'block',
		});
	});
      $('.menu-Header').mouseout (function(){
	$(this).children('.mega-Menus').css({
		'display':'none',
	 	});
	});
   });
</script>

