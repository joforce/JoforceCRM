{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

{include file="modules/Head/partials/Topbar.tpl"}
{assign var=IS_LIST_VIEW value=true}
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
		<div class="listViewPageDiv content-area full-width" id="listViewContent">

