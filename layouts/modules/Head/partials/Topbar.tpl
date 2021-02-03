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
{assign var=LEFTPANELHIDE value=getCurrentUserFieldDetailsFromTable('leftpanelhide') scope=parent}
<input type="hidden" name="leftpanelhide" id="leftpanelhide" value="{$LEFTPANELHIDE}">
<input type="hidden" name="rightpanelhide" id="rightpanelhide" value="0">
<nav class="navbar navbar-default navbar-fixed-top app-fixed-navbar">
    <div class="container-fluid global-nav pl0 pr5">
	{assign var=BOARDID value=getDefaultBoardId()}
	<div class="logo-container app-navigator-container {if $LEFTPANELHIDE eq '1'}half-image{/if} {if $LEFTPANELHIDE eq '1'} logo-shrinked {else} logo-expand {/if}">
	    <a href="{if $BOARDID eq '1'} {$SITEURL}Home/view/List {else} {$SITEURL} {/if}" class="company-logo {if $LEFTPANELHIDE neq '1'} hide {/if} shrinked">
		<!--<img src="{$COMPANY_LOGO->get('imagepath')}" alt="{$COMPANY_LOGO->get('alt')}" style="margin: 4px 10px;width:50px;"/>-->
		<img src="{$SITEURL}/layouts/resources/Images/logos/light/fav-light.png" alt="LOGO"/>
	    </a>
	    <img class="mobilelogo" src="{$SITEURL}/layouts/resources/Images/logos/light/harizontal_light.png" alt="logo" />
	    <a href="{if $BOARDID eq '1'} {$SITEURL}Home/view/List {else} {$SITEURL} {/if}" class="company-logo {if $LEFTPANELHIDE eq '1'} hide {/if} expanded">
            	<!--<img src="{$COMPANY_LOGO->get('imagepath')}" alt="{$COMPANY_LOGO->get('alt')}" style="margin: 4px 10px;width:50px;"/>-->
		<img src="{$SITEURL}/layouts/resources/Images/logos/light/harizontal_light.png" alt="logo" />
	    </a>
	</div>
	
	<div class="row col-lg-12 col-md-12 col-sm-12 {if $LEFTPANELHIDE eq '1'} full-topbar{/if}" id="topbar-elements">
	    <div class="col-lg-6 col-md-4 col-sm-6 pl0 pr0"> 
		<div class="user-image-mobile">
		    <li class="dropdown">
			<div style="margin-top: -10px;">
			    {assign var=USER_PRIVILEGES_MODEL value=Users_Privileges_Model::getCurrentUserPrivilegesModel()}
        	            {assign var=last_name value=$USER_MODEL->get('last_name')}
                	    {assign var=first_name value=$USER_MODEL->get('first_name')}
	                    <a href="#" class="" data-toggle="dropdown" role="button" style="background:transparent;">
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
			    {include file="modules/Head/partials/ProfileOptions.tpl"}
	                </div>
		    </li>
		</div>
	        <div class="" style="display:inline;">
        	    <div class="col-lg-2 col-md-2 cursorPointer app-switcher-container">
	                <div class="row">
        	            <span id="menu-toggle-action" class="app-icon fa {if $LEFTPANELHIDE eq '1'} fa-align-justify {else} fa-align-left {/if}"></span>
			    <span id="responsive-menu-toggle-action" class="hide pull-right"><i class="fa fa-align-left"></i></span>
	                </div>
	            </div>
	        </div>
		<div class='search-link' style="display:flex;">
			<div class="dropdown" style="display:flex;justify-content:center;align-items:center;">
				<i class="fa fa-search"></i>
				<input type="text" id="joforce-search-box" style="flex:1;" class="keyword-input form-control" name="s" id="s" placeholder="search...">
				<span class='icon-down {if $LEFTPANELHIDE eq '1'}search-open-1{/if} {if $LEFTPANELHIDE eq '0'}search-open-0{/if}' id="joforce-advanced-search" data-toggle="dropdown" aria-expanded="false" aria-hidden="true">
				</span>
					<div id="searchResults-container" class="searchBox dropdown-menu">
						{* <div class="col-lg-12 clearfix">
							<div class="pull-right overlay-close">
								<button id="joforce-search-close" type="button" class="close" aria-label="Close" data-target="#overlayPage" data-dismiss="modal">
									<span aria-hidden="true" class="fa fa-close"></span>
								</button>
							</div>
						</div> *}
						<div style="padding:15px;background-color:#fff;padding-top:20px">
							<label>Select Module</label>
							{assign var=modulesList value=Head_Menu_Model::getAll(true)}
							<select id="joforce-select-search-box" class="select2 form-control" >
								<option value="" disabled selected>Search</option>
								{foreach item=modulelabel key=modulename from=$modulesList}
									<option value="{$modulename}">{vtranslate($modulename, 'Head')}</option>
								{/foreach}
							</select>		
						</div>
						<div class="searchResults">
							<input type="hidden" value="{$SEARCH_VALUE|escape:"html"}" id="searchValue">
							<div class="">
								<div class="container-fluid moduleResults-container" style="background-color:#fff !important;">
									<input type="hidden" name="groupStart" value="{$GROUP_START}" class="groupStart"/>
									<label>Select Field</label>
									<select class="select2 form-control" id="filterField" name="role2fieldnames[]" {if empty($SELECTED_MODULE_FIELDS) }  placeholder="{vtranslate("LBL_SELECT",$QUALIFIED_MODULE)}" {/if}>
										{foreach key=FIELD_NAME item=FIELD_MODEL from=$MODULE_FIELDS}
											<option class="role2fieldnames_{$FIELD_NAME}" value="{$FIELD_NAME}"
												{if is_array($SELECTED_MODULE_FIELDS)} 
													{if in_array($FIELD_NAME, $SELECTED_MODULE_FIELDS)} selected {/if}
												{/if}>
												{vtranslate($FIELD_MODEL->label,$SELECTED_MODULE_NAME)}
											</option>
										{/foreach}
									</select>	
								</div>
							</div>
						</div>

						<div class="conditionComparator" style="padding:15px;background-color:#fff;">
							<label>Select Condition</label>
							<select id="filterCondition" class="{if empty($NOCHOSEN)}select2{/if} form-control" name="comparator">
								<option value="equal">equal to</option>
								<option value="notequal">not equal to</option>
								<option value="starts">starts with</option>
								<option value="ends">ends with</option>
								<option value="contains">contains</option>
								<option value="notcontains">not contains</option>								
							</select>
						</div>  

						<div class="dropdown-search" style="padding:15px;background-color:#fff;padding-top:0px;">
							<label>Enter Value</label>
							<input id="filterValue" class="form-control" type="text" value="" />
						</div>

						<input id="joforce-search-btn" type="button" value="Search" class="btn btn-primary" style="float:right;margin-right:15px;"/>
							
					</div>

			</div>
			<!--<input type="text" id="joforce-search-box" class="keyword-input form-control" name="s" id="s" placeholder="search...">-->
			
			<!--
			{assign var=modulesList value=Head_Menu_Model::getAll(true)}
			<select id="joforce-select-search-box" class="form-control" >
				<option value="" disabled selected>Search</option>
				{foreach item=modulelabel key=modulename from=$modulesList}
					<option value="{$modulename}">{vtranslate($modulename, 'Head')}</option>
				{/foreach}
			</select>
			-->
		</div>

		<div class="nav-responsive"><i class="fa fa-bars"></i></div>
	      	<div class="nav-responsive-tab">
	            <ul class="nav navbar">
        	    	<li class="dropdown">{vtranslate('LBL_MENU', 'HEAD')}<i class="fa fa-caret-down"></i>
		    	<!--dropdown start-->
		    	<ul class="dropdown-menu p0">
			    {foreach key=SECTION_NAME item=ICON from=$SECTION_ARRAY}
				<li class="dropdown menu-Header"data-toggle="dropdown">
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
	    </div>
	    <div id="navbar" class="col-sm-4 col-md-3 col-lg-3 pr10 collapse navbar-collapse navbar-right global-actions" style="margin-left: -30px;">
		<ul class="nav navbar-nav">
	            <li class="dropdown" style="">
        		<div style="" class="" id="gndropdown">
	                    <a class="fa fa-bell global-notification-toggle" data-toggle="dropdown" role="button" style="background: transparent;" aria-expanded="true" title="Notifications" aria-hidden="true"></a>
			    {if $NOTIFICATONS_COUNT gt 0}
				<div class='notification_count'>{$NOTIFICATONS_COUNT}</div>
			    {/if}
                	    <ul class="dropdown-menu global-notification-dropdown" id='global-notification-dropdown' role="menu">
	                    </ul>
        	        </div>
	            </li>
        	    <li>
			<div class="dropdown">
	                    <div class="" data-toggle="dropdown" aria-expanded="true">
            			<a href="#" id="menubar_quickCreate" class="qc-button fa fa-plus" title="{vtranslate('LBL_QUICK_CREATE',$MODULE)}" aria-hidden="true"></a>
			    </div>
			    {include file="modules/Head/partials/TopQuickCreateList.tpl"}
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
               	    <div style="margin-top: -10px;">
                  	{assign var=last_name value=$USER_MODEL->get('last_name')}
                  	{assign var=first_name value=$USER_MODEL->get('first_name')}
                  	<a href="#" class="" data-toggle="dropdown" role="button" style="background: transparent;">
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
			{include file="modules/Head/partials/ProfileOptions.tpl"}
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

	$('.dropdown-search').on('click', function(event){
    	event.stopPropagation();
	});

   });
</script>
