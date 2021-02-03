{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

<div class="app-menus" id="app-menus">
    <div class="">
	<div class="app-list-divider"></div>
	{assign var=USER_PRIVILEGES_MODEL value=Users_Privileges_Model::getCurrentUserPrivilegesModel()}
	<div>
            <ul class="sidebar-module-list" id="sidebar-module-list">
		{assign var=HOME_MODULE_MODEL value=Head_Module_Model::getInstance('Home')}
		{if $HOME_MODULE_MODEL && $USER_PRIVILEGES_MODEL->hasModulePermission($HOME_MODULE_MODEL->getId())}
		    <li class="custom-menu-list container-fluid {if $MODULE eq 'Home'} active {elseif empty($MODULE)} active {/if}" id="sidebar_Home">
		    	<a href="{$HOME_MODULE_MODEL->getDefaultUrl()}">
			    <span class="{if $MODULE eq 'Home' && $VIEW eq 'List'} joicon-home {else} fa fa-dashboard {/if} module-icon"></span>
			    <span class="module-name textOverflowEllipsis {if $LEFTPANELHIDE eq '1'} hide {/if}"> {vtranslate('Dashboard', 'Head')}</span>
		    	</a>
		    </li>
		{/if}

	    	{assign var=tabs_array  value=Settings_MenuManager_Module_Model::getMainMenuModuleIds()}
		{assign var="hidden_tab_array" value =Settings_MenuManager_Module_Model::getMainMenuModuleNamesOnly()}

		{foreach item=tabarray from=$MAIN_MENU_TAB_IDS}
		    {assign var=type value=$tabarray['type']}
                    {if $type == 'module'}
	                    {assign var=tabid value=$tabarray['tabid']}
	                    {assign var=moduleModel value=Settings_MenuManager_Module_Model::getModuleInstanceById($tabid)}
            	            {if $USER_PRIVILEGES_MODEL->hasModulePermission($tabid)}
                       	        {if $moduleModel->isActive()}
	                            {assign var=moduleName value=$moduleModel->get('name')}
                       		    {assign var=translatedModuleLabel value=vtranslate($moduleModel->get('label'),$moduleName)}
	                            {assign var=listid value=getCvIdOfAll($moduleName)}
				    <li class="custom-menu-list container-fluid {if $moduleName eq $MODULE} active {/if}" id="sidebar_{$moduleName}">
                		    	<a href="{$moduleModel->getDefaultUrl()}">
                                	    <span class="joicon-{strtolower($moduleName)} module-icon"></span>
		                            <span class="module-name textOverflowEllipsis {if $LEFTPANELHIDE eq '1'} hide {/if}"> {vtranslate($moduleModel->getName(), $moduleModel->getName())}</span>
                		        </a>
		                    </li>
                	        {/if}
	                    {/if}
            	    {else}
	                    {assign var=linklabel value=$tabarray['name']}
                	    {assign var=linkurl value=$tabarray['linkurl']}
		            <li class="custom-menu-list container-fluid" id="sidebar_{$linklabel}">
                	    	<a href="{$linkurl}" class="link-as-menu">
                        	    <span class="fa fa-link module-icon"></span>
                                    <span class="module-name textOverflowEllipsis {if $LEFTPANELHIDE eq '1'} hide {/if}">{$linklabel}</span>
	                        </a>
        	            </li>
                    {/if}
		{/foreach}

		<input type="hidden" name=shot-cut-menu-array[] id="shot-cut-menu-array" value="{$hidden_tab_array}">
                {if $QUALIFIED_MODULE eq $MODULE}
                	{if !in_array($MODULE, $tabs_array) && ($MODULE neq 'Home')}
                            <li class="custom-menu-list temporary-main-menu active container-fluid" id="sidebar_{$MODULE}">
                                {assign var=moduleid value=getTabid($MODULE)}
                                {assign var=moduleModel value=Settings_MenuManager_Module_Model::getModuleInstanceById($moduleid)}
				{assign var=moduleName value=$moduleModel->get('name')}
				<a href="{$moduleModel->getDefaultUrl()}">
                                    <span class="joicon-{strtolower($moduleName)} module-icon"></span>
                                    <span class="module-name textOverflowEllipsis {if $LEFTPANELHIDE eq '1'} hide {/if}"> {vtranslate($moduleModel->getName(), $moduleModel->getName())}</span>
                                </a>
                            </li>
                        {/if}
		{/if}

		<li class="divider"></li>
		<li class="custom-menu-list temporary-main-menu container-fluid" id="sidebar_settings_menu">
			{if $USER_MODEL->isAdminUser()}
	                    {assign var=sett_url value='Head/Settings/Index'}
        	        {else}
                	    {assign var=sett_url value='Head/Settings/SettingsIndex'}
	                {/if}
			<a href='{$SITEURL}{$sett_url}'>
				<span class="fa fa-gears mr5 module-icon"></span>
				<span class="module-name textOverflowEllipsis {if $LEFTPANELHIDE eq '1'} hide {/if}"> {vtranslate('LBL_SETTINGS', 'Head')}</span>
			</a>
		</li>
		<li class="divider"></li>
            </ul>

	    <div class="p0 panel-group ui container" id="sidebar-more-menu-list">
		<div class="ui accordion">
		    {foreach key=SECTION_NAME item=ICON from=$SECTION_ARRAY}
			<div class="title sidebar-more-menu-title">
			    <i class="{$ICON} menu-icon"></i>
			    <span class="menu-name {if $LEFTPANELHIDE eq '1'}hide{/if} text-font-crimson">{vtranslate($SECTION_NAME, $SECTION_NAME)}</span>
			    <i class="fa fa-angle-right dropdown-icon pull-right {if $LEFTPANELHIDE eq '1'}hide{/if}"></i>
			</div>
			<ul class="content ml40">
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
		    {/foreach}
		</div>
	    </div>
        </div>
    </div>
</div>
