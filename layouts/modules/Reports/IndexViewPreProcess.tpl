{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="modules/Head/partials/Topbar.tpl"}

<div class="container-fluid app-nav module-header {if $LEFTPANELHIDE eq '1'} full-header {/if}">
    <div class="row">
        {include file="ModuleHeader.tpl"|vtemplate_path:$MODULE}
    </div>
</div>
</nav>
<div class="main-container clearfix main-container-{$MODULE}">
        <div id="sidebar-essentials" class="sidebar-essentials {if $LEFTPANELHIDE eq '1'} shrinked-sidebar {/if}">
            {include file="modules/Head/partials/SidebarAppMenu.tpl"}
        </div>
		<div class="quick-panel"></div>
	<div>
            <div class="editViewPageDiv viewContent content-area {if $LEFTPANELHIDE eq '1'} full-width {/if} {if $VIEW eq 'Edit'} {/if}">
            	<div class="reports-content-area">

		    {if $REPORT_TYPE eq 'ChartEdit'}
			{include file="EditChartHeader.tpl"|vtemplate_path:$MODULE}
		    {else}
			{include file="EditHeader.tpl"|vtemplate_path:$MODULE}
		    {/if}
		    <div class="reportContents">
