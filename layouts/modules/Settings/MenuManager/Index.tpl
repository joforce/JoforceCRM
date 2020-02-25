{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Settings/MenuManager/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="listViewPageDiv detailViewContainer col-sm-12 joforce-bg" id="listViewContent">
    <div class ="add_section modal-dialog" id="add-section-modalbody" style="width: 600px;margin: 30px auto;position: relative;">
    </div>
    <div class="col-sm-12">
	<div class="row">
	    <div class=" vt-default-callout vt-info-callout">
		<h4 class="vt-callout-header">
		    <span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE_NAME)}
		</h4>
		<p>{vtranslate('LBL_MENU_MANAGEMENT_INFO', $QUALIFIED_MODULE_NAME)}</p>
	    </div>
	</div>
    </div>
    <br>
    <div class="row">
	<div class="col-lg-12 mt10">
	    <div class="col-lg-4 mt10">
		<div class="menu-box">
		    <div class="custom-menu-header">
		    	<div class="dropdown ">Main menu <i class="fa fa-ellipsis-h pull-right dropdown-toggle" data-toggle="dropdown" title="Add New"></i>
			    <ul class="dropdown-menu main-menu-dropdown">
				<li class="add-main-menu" id="add-main-menu" type="button" data-mode="module"><a>Add module</a></li>
				<li class="add-link" id="add-link" type="button" data-mode="link"><a>Add link</a></li>
			    </ul>
			</div>
		    </div>
		    <ul class="custom-menu">
		    	<div class="sortable-main-menu">
			{foreach item=tabarray key=sequence from=$MAIN_MENU_TAB_IDS}
			    {assign var=type value=$tabarray['type']}
			    {if $type == 'module'}
				{assign var=tabid value=$tabarray['tabid']}
			    	{if (Settings_MenuManager_Module_Model::isPermittedModule($tabid)) !== false }
				    {assign var=moduleModel value=Settings_MenuManager_Module_Model::getModuleInstanceById($tabid)}
				    {if $moduleModel->isActive()}
	                            	{assign var=moduleName value=$moduleModel->get('name')}
					{assign var=translatedModuleLabel value=vtranslate($moduleModel->get('label'),$moduleName )}
					<li class="custom-menu-list main-menu-container noConnect" data-menuname="{$moduleName}" data-tabid="{$tabid}">
					    <span class="menu-name">
					        <i class="joicon-{strtolower($moduleName)} marginRight10px pull-left"></i>{$translatedModuleLabel}
					    </span>
					    <span class="pull-right close-menu remove-main-menu fa fa-close"></span>
					</li>
				    {/if}
				{/if}
			    {else}
				{assign var=linklabel value=$tabarray['name']}
			        {assign var=linkurl value=$tabarray['linkurl']}
				<li class="custom-menu-list main-menu-container noConnect" data-menuname="{$tabarray['name']}">
                                    <span class="menu-name">{$linklabel}</span>
	                            <span class="pull-right close-menu remove-main-menu">X</span>
                                </li>
			    {/if}
			{/foreach}
		    </div>
		</ul>
	    </div>
	</div>
	<div class="col-lg-8 mt10">
	    <div class="menu-box" style="overflow:hidden;">
		<div class = "custom-menu-header more-menu-header pull-right">More Section
		    <i id="add_new_menu_bar" type="button" class="addButton add-module-buttons fa fa-plus pull-right" title="Add Section" ></i>
        	</div>
		{assign var=default_main_sections value=[ '0' => 'MARKETING', 
							  '1' => 'SALES',
							  '2' => 'INVENTORY',
  							  '3' => 'SUPPORT',
							  '4' => 'PROJECT',
							  '5' => 'TOOLS'
							]}
		<ul class="more-menu-section">
		    {foreach item=APP_IMAGE key=APP_NAME from=$SECTION_ARRAY name=APP_MAP}
			<li  class=" {if $smarty.foreach.APP_MAP.index eq 0 or count($APP_LIST) eq 1} {/if} more-menu-list">
			    {if ( in_array($APP_NAME, $default_main_sections))}
				<div class="more-menu-list-header menuEditorItem app-{$APP_NAME}" data-app-name="{$APP_NAME}">
				    <i class="fa fa-plus menuEditorAddItem pull-right" data-appname="{$APP_NAME}"></i>
					{assign var=TRANSLATED_APP_NAME value={vtranslate("LBL_$APP_NAME")}}
                                        <div class="textOverflowEllipsis" title="{$APP_NAME}"> <span class="{$APP_IMAGE}"></span> {$APP_NAME}</div>
				</div>
			    {else}
				<div class="more-menu-list-header menuEditorItem app-{$APP_NAME}" data-app-name="{$APP_NAME}">
				    <i class="fa fa-plus menuEditorAddItem pull-right" data-appname="{$APP_NAME}"></i>
				    <i data-appname="TOOLS" class="fa fa-times pull-right whiteIcon delete-section" id="delete-section" data-appname="{$APP_NAME}"></i>
                                    <div class="textOverflowEllipsis" title="{$APP_NAME}"> <span class="{$APP_IMAGE}"></span> {$APP_NAME}</div>
                                </div>
			    {/if}
			    <ul class="more-menu sortable appContainer" data-appname="{$APP_NAME}">
			    	{foreach item=tabid key=sequence from=$APP_MODULE_ARRAY[$APP_NAME]}
				    {assign var=moduleObject value=Settings_MenuManager_Module_Model::getModuleInstanceById($tabid)}
				    {if $moduleObject->isActive()}
				    	{assign var=moduleName value=$moduleObject->get('name')}
					{if (Settings_MenuManager_Module_Model::isPermittedModule($tabid)) !== false }
					    <li class="modules noConnect menu-name" data-module="{$moduleName}" >
					    	<div class="menuEditorItem menuEditorModuleItem" data-sequence="{$sequence}">
						    {assign var='translatedModuleLabel' value=vtranslate($moduleObject->get('label'),$moduleName )}
						    <span class="textOverflowEllipsis marginTop10px textAlignLeft" title="{$translatedModuleLabel}">
						    	<i class="joicon-{strtolower($moduleName)} marginRight10px pull-left"></i>{$translatedModuleLabel}
						    </span>
						    <span data-appname="{$APP_NAME}" class="pull-right whiteIcon menuEditorRemoveItem fa fa-close" style="cursor:pointer;">
						    </span>
						</div>
					    </li>
					{/if}
				    {/if}
				{/foreach}
			    </ul>
			</li>
		    {/foreach}
		</ul>
	    </div>
	</div>
    </div>
</div>
