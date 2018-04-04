{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

{assign var="topMenus" value=$MENU_STRUCTURE->getTop()}
{assign var="moreMenus" value=$MENU_STRUCTURE->getMore()}

<div id="modules-menu" class="modules-menu">
	{foreach key=moduleName item=moduleModel from=$SELECTED_CATEGORY_MENU_LIST}
		{assign var='translatedModuleLabel' value=vtranslate($moduleModel->get('label'),$moduleName )}
		<!--TODO EmailPlus PDFMaker -->
		<ul title="{$translatedModuleLabel}" class="module-qtip">
			<li {if $MODULE eq $moduleName}class="active"{else}class=""{/if}>
				{if $SELECTED_MENU_CATEGORY neq ''}
				<a href="{$moduleModel->getDefaultUrl()}">
					<i class="vicon-{strtolower($moduleName)}"></i>
					<span>{$translatedModuleLabel}</span>
				</a>
				{else}
				<a href="{$moduleModel->getDefaultUrl()}">
                                        <i class="vicon-{strtolower($moduleName)}"></i>
                                        <span>{$translatedModuleLabel}</span>
                                </a>
				{/if}
			</li>
		</ul>
	{/foreach}
</div>
