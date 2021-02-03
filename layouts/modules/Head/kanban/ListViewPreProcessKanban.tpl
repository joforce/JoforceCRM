{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

{include file="modules/Head/partials/Topbar.tpl"}
<div class="container-fluid app-nav module-header {if $LEFTPANELHIDE eq '1'} full-header {/if}">
    <div class="row">
	{include file="kanban/ModuleHeader_kanban.tpl"|vtemplate_path:$MODULE}
    </div>
</div>
</nav>
<div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-60' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class="data">
    </div>
    <div class="modal-dialog">
    </div>
</div>
<div class="main-container main-container-{$SOURCE_MODULE_NAME}">
	<div id="sidebar-essentials" class="sidebar-essentials {if $LEFTPANELHIDE eq '1'} shrinked-sidebar {/if}">
            {include file="partials/SidebarAppMenu.tpl"|vtemplate_path:$MODULE}
	</div>
    <div class="quick-panel"></div>
	<div class="listViewPageDiv content-area detailViewContainer {if $LEFTPANELHIDE eq '1'} full-width {/if}" id="taskManagementContainer">
