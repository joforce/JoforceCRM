{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<div class="app-menu hide" id="app-menu">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-2 col-xs-2 cursorPointer app-switcher-container">
				<div class="row app-navigator">
					<span id="menu-toggle-action" class="app-icon fa fa-bars"></span>
				</div>
			</div>
		</div>
		{assign var=USER_PRIVILEGES_MODEL value=Users_Privileges_Model::getCurrentUserPrivilegesModel()}
		{assign var=HOME_MODULE_MODEL value=Head_Module_Model::getInstance('Home')}
		{assign var=DASHBOARD_MODULE_MODEL value=Head_Module_Model::getInstance('Dashboard')}
		<div class="app-list row">
			{if $USER_PRIVILEGES_MODEL->hasModulePermission($DASHBOARD_MODULE_MODEL->getId())}
				<div class="menu-item app-item dropdown-toggle" data-default-url="{$HOME_MODULE_MODEL->getDefaultUrl()}">
					<div class="menu-items-wrapper">
						<span class="app-icon-list fa fa-dashboard"></span>
						<span class="app-name textOverflowEllipsis"> {vtranslate('LBL_DASHBOARD',$MODULE)}</span>
					</div>
				</div>
			{/if}
			{assign var=APP_GROUPED_MENU value=Settings_MenuManager_Module_Model::getAllVisibleModules()}
			{assign var=APP_LIST value=Head_MenuStructure_Model::getAppMenuList()}
			{foreach item=APP_NAME from=$APP_LIST}
				{if $APP_NAME eq 'ANALYTICS'} {continue}{/if}
				{if count($APP_GROUPED_MENU.$APP_NAME) gt 0}
					<div class="dropdown app-modules-dropdown-container">
						{foreach item=APP_MENU_MODEL from=$APP_GROUPED_MENU.$APP_NAME}
							{assign var=FIRST_MENU_MODEL value=$APP_MENU_MODEL}
							{if $APP_MENU_MODEL}
								{break}
							{/if}
						{/foreach}
						<div class="menu-item app-item dropdown-toggle app-item-color-{$APP_NAME}" data-app-name="{$APP_NAME}" id="{$APP_NAME}_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-default-url="{$FIRST_MENU_MODEL->getDefaultUrl()}/{$APP_NAME}">
							<div class="menu-items-wrapper app-menu-items-wrapper">
								{if $APP_NAME eq 'INVENTORY'}
	                                                                <span class="app-icon-list {$APP_IMAGE_MAP.$APP_NAME}"></span>
								{else}
									<span class="app-icon-list fa {$APP_IMAGE_MAP.$APP_NAME}"></span>
								{/if}
								<span class="app-name textOverflowEllipsis"> {vtranslate("LBL_$APP_NAME")}</span>
								<span class="fa fa-chevron-right pull-right"></span>
							</div>
						</div>
						<ul class="dropdown-menu app-modules-dropdown" aria-labelledby="{$APP_NAME}_modules_dropdownMenu">
							{foreach item=moduleModel key=moduleName from=$APP_GROUPED_MENU[$APP_NAME]}
								{assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
								<li>
									<a href="{$moduleModel->getDefaultUrl()}" title="{$translatedModuleLabel}">
										<span class="joicon-{strtolower($moduleName)} module-icon"></span>
										<span class="module-name textOverflowEllipsis"> {$translatedModuleLabel}</span>
									</a>
								</li>
							{/foreach}
						</ul>
					</div>
				{/if}
			{/foreach}
			<div class="app-list-divider"></div>
			{assign var=DOCUMENTS_MODULE_MODEL value=Head_Module_Model::getInstance('Documents')}
			{if $USER_PRIVILEGES_MODEL->hasModulePermission($DOCUMENTS_MODULE_MODEL->getId())}
				<div class="menu-item app-item app-item-misc" data-default-url="{$SITEURL}Documents/view/List">
					<div class="menu-items-wrapper">
						<span class="app-icon-list joicon-documents"></span>
						<span class="app-name textOverflowEllipsis"> {vtranslate('Documents')}</span>
					</div>
				</div>
			{/if}
                                        {assign var=EMAILTEMPLATES_MODULE_MODEL value=Head_Module_Model::getInstance('EmailTemplates')}
                                        {if $EMAILTEMPLATES_MODULE_MODEL && $USER_PRIVILEGES_MODEL->hasModulePermission($EMAILTEMPLATES_MODULE_MODEL->getId())}
                                <div class="menu-item app-item app-item-misc" data-default-url="{$SITEURL}EmailTemplates/view/List">
                                        <div class="menu-items-wrapper">
                                                <span class="app-icon-list  joicon-emailtemplates"></span>
                                                <span class="app-name textOverflowEllipsis">{vtranslate($EMAILTEMPLATES_MODULE_MODEL->getName(), $EMAILTEMPLATES_MODULE_MODEL->getName())}</span>
                                        </div>
                                </div>
                        {/if}

			{if $USER_MODEL->isAdminUser()}
				{if vtlib_isModuleActive('ExtensionStore')}
					<div class="menu-item app-item app-item-misc" data-default-url="index.php?module=ExtensionStore&parent=Settings&view=ExtensionStore">
						<div class="menu-items-wrapper">
							<span class="app-icon-list fa fa-shopping-cart"></span>
							<span class="app-name textOverflowEllipsis"> {vtranslate('LBL_EXTENSION_STORE', 'Settings:Head')}</span>
						</div>
					</div>
				{/if}
			{/if}
				<div class="dropdown app-modules-dropdown-container dropdown-compact">
                                {foreach item=APP_MENU_MODEL from=$APP_GROUPED_MENU.$APP_NAME}
                                        {assign var=FIRST_MENU_MODEL value=$APP_MENU_MODEL}
                                        {if $APP_MENU_MODEL}
                                                {break}
                                        {/if}
                                {/foreach}
                                <div class="menu-item app-item dropdown-toggle app-item-misc" data-app-name="TOOLS" id="TOOLS_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <div class="menu-items-wrapper app-menu-items-wrapper">
                                                <span class="app-icon-list fa fa-ellipsis-h"></span>
                                                <span class="app-name textOverflowEllipsis"> {vtranslate("LBL_MORE")}</span>
                                                <span class="fa fa-chevron-right pull-right"></span>
                                        </div>
                                </div>
                                <ul class="dropdown-menu app-modules-dropdown dropdown-modules-compact" aria-labelledby="{$APP_NAME}_modules_dropdownMenu" data-height="0.34">
                                        {assign var=EMAILPLUS_MODULE_MODEL value=Head_Module_Model::getInstance('EmailPlus')}
                                        {if $EMAILPLUS_MODULE_MODEL && $USER_PRIVILEGES_MODEL->hasModulePermission($EMAILTEMPLATES_MODULE_MODEL->getId())}
                                                <li>
                                                        <a href="{$EMAILPLUS_MODULE_MODEL->getDefaultUrl()}">
                                                                <span class="joicon-mailmanager module-icon"></span>
                                                                <span class="module-name textOverflowEllipsis"> {vtranslate($EMAILPLUS_MODULE_MODEL->getName(), $EMAILPLUS_MODULE_MODEL->getName())}</span>
                                                        </a>
                                                </li>
                                        {/if}
                                       {assign var=PDFMAKER_MODULE_MODEL value=Head_Module_Model::getInstance('PDFMaker')}
                                        {if $PDFMAKER_MODULE_MODEL && $USER_PRIVILEGES_MODEL->hasModulePermission($PDFMAKER_MODULE_MODEL->getId())}
                                                <li>
                                                        <a href="{$PDFMAKER_MODULE_MODEL->getDefaultUrl()}">
                                                                <span class="fa fa-file-pdf-o module-icon"></span>
                                                                <span class="module-name textOverflowEllipsis"> {vtranslate($PDFMAKER_MODULE_MODEL->getName(), $PDFMAKER_MODULE_MODEL->getName())}</span>
                                                        </a>
                                                </li>
                                        {/if}
                                </ul>
                                </div>

                        {if $USER_MODEL->isAdminUser()}
                        <div class="dropdown app-modules-dropdown-container dropdown-compact">

					<div class="menu-item app-item dropdown-toggle app-item-misc" data-app-name="TOOLS" id="TOOLS_modules_dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-default-url="{$SITEURL}{if $USER_MODEL->isAdminUser()}Head/Settings/Index{else}Users/Settings{/if}">
						<div class="menu-items-wrapper app-menu-items-wrapper">
							<span class="app-icon-list fa fa-cog"></span>
							<span class="app-name textOverflowEllipsis"> {vtranslate('Admin', 'Settings:Head')}</span>
							{if $USER_MODEL->isAdminUser()}
								<span class="fa fa-chevron-right pull-right"></span>
							{/if}
						</div>
					</div>
					<ul class="dropdown-menu app-modules-dropdown dropdown-modules-compact" aria-labelledby="{$APP_NAME}_modules_dropdownMenu" data-height="0.27">
						<li>
							<a href="{$SITEURL}Head/Settings/Index">
								<span class="fa fa-cog module-icon"></span>
								<span class="module-name textOverflowEllipsis"> {vtranslate('LBL_CRM_SETTINGS','Head')}</span>
							</a>
						</li>
						<li>
							<a href="{$SITEURL}Users/Settings/List">
								<span class="fa fa-user module-icon"></span>
								<span class="module-name textOverflowEllipsis"> {vtranslate('LBL_MANAGE_USERS','Head')}</span>
							</a>
						</li>
					</ul>
				</div>
			{/if}
		</div>
	</div>
</div>
