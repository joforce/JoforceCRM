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
<nav class="navbar navbar-default fixed-top app-fixed-navbar">
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

	<div class="row {if $LEFTPANELHIDE eq '1'} full-topbar{/if}" id="topbar-elements">
	  <div class="col-lg-12 col-md-12 col-sm-12">
	    <div class="col-lg-10 col-md-5 col-sm-6 pl0 pr0 d-inline-block"> 
		<div class="user-image-mobile">
		    <li class="dropdown">
			<div style="margin-top: 0px;">
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
        	<div class="col-lg-2 col-md-2 cursorPointer app-switcher-container">
	                <div class="row">
        	            <span id="menu-toggle-action" class="app-icon fa {if $LEFTPANELHIDE eq '1'} fa-align-justify {else} fa-align-left {/if}"></span>
			    <span id="responsive-menu-toggle-action" class="hide pull-right"><i class="fa fa-align-left"></i></span>
	                </div>
	        </div>
		<div class='search-link' style="display:flex;">
			{include file="modules/Head/partials/TopbarSearch.tpl"}
		</div>
		<div class="nav-responsive"><i class="fa fa-bars"></i></div>
	    </div>
	    <div id="navbar" class="col-sm-4 col-md-7 col-lg-2 pr10 navbar-collapse ml-auto global-actions">
		<ul class="row nav navbar">
	            <li class="dropdown nav-item" style="">
        		<div style="" class="" id="gndropdown">
	                    <a class="fa fa-bell global-notification-toggle" data-toggle="dropdown" role="button" style="background: transparent;" aria-expanded="true" title="Notifications" aria-hidden="true"></a>
			    {if $NOTIFICATONS_COUNT gt 0}
				<div class='notification_count'>{$NOTIFICATONS_COUNT}</div>
			    {/if}
                	    <ul class="dropdown-menu global-notification-dropdown dropdown-menu-right" id='global-notification-dropdown' role="menu">
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
	        {* {assign var=CALENDAR_MODULE_MODEL value=Head_Module_Model::getInstance('Calendar')}
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
        	{/if} *}
	    	{* <li>
		    <div>
			<a href="https://docs.joforce.com" target="_blank" class="fa fa-question-circle" aria-hidden="true" style="font-size:18px" title="{vtranslate('LBL_HELP', 'Head')}"></a>
		    </div>
	    	</li> *}
			{* <li class="support-icon"><a href="{$SITEURL}Users/view/Support" title="Support Details" class="fa fa-headphones "></a></li> *}
            	<li class="dropdown" style="padding: 0px 5px;">
               	    <div style="margin-top: 0px;">
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
                     		<div class="profile-img"><button type="button" class="avatar-circle">{if $first_name neq ''} {$first_name[0]} {else} {$last_name[0]}{/if}</button></div>
                     	    {/if}
                  	</a>
			{include file="modules/Head/partials/ProfileOptions.tpl"}
		    </div>
		</li>
	    </ul>
	</div>
      </div>
   </div>
</div>
{/strip}

<script type="text/javascript">
   $(document).ready(function(){
	$('.dropdown-search').on('click', function(event){
    	event.stopPropagation();
	});

   });
   $( ".nav-responsive" ).click(function() {
  $( "#navbar" ).toggleClass( "navbar-collapse-toggle" );
});
</script>
