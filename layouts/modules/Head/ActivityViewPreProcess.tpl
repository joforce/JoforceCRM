{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Head/views/Detail.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="modules/Head/partials/Topbar.tpl"}
{if !$MODULE} {assign var=MODULE value=$MODULE_NAME} {/if}
</nav>    
<div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-60' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class="data"></div>
    <div class="modal-dialog"></div>
</div>
<div class="hide container-fluid app-nav module-header {if $LEFTPANELHIDE eq '1'} full-header {/if}">
    <div class="row">
        {include file="ModuleHeader.tpl"|vtemplate_path:$MODULE}
    </div>
</div>
<div class="main-container main-container-{$MODULE} mt10">
  <div id="sidebar-essentials" class="sidebar-essentials {if $LEFTPANELHIDE eq '1'} shrinked-sidebar {/if}">
    {include file="partials/SidebarAppMenu.tpl"|vtemplate_path:$MODULE}
  </div>
  <div class="quick-panel"></div>
  <div class="detailViewPageDiv content-area {if $LEFTPANELHIDE eq '1'} full-width {/if}" id="detailViewContent">
  <div id="licence-alert-waring" class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 

  <strong> <span class="licence-waring-icon"><i class="fa fa-warning"></i> </span> Danger!</strong>  You are not secure 

</div>
    <div class="col-md-12 col-sm-12 col-xs-12 detail-view-header " id="detail-view-header">
	<div class="col-md-4 col-sm-12 col-xs-4 detail-view-breadcrumb">
		{include file="partials/HeaderBreadCrumb.tpl"|vtemplate_path:$MODULE}
	</div>
	<div class="col-lg-8 col-md-8 col-sm-12 detailViewButtoncontainer">
        	{include file="DetailViewActions.tpl"|vtemplate_path:$MODULE}
	</div>
    </div>
    <!--<div class="container-fluid main-container">-->
    <div class='joforce-bg'>
	<div class="detailViewContainer viewContent clearfix">
	{assign var=FIELDS_MODELS_LIST value=$MODULE_MODEL->getFields()}
	    <div class="col-lg-12 col-sm-12 col-xs-12">
		{if !in_array($MODULE, array('Invoice', 'Quotes','SalesOrder','PurchaseOrder'))}
		{/if}

		{* {if $kanban_view_enabled}
			<div id = 'pipeline_stages'>
			    <ul class="nav nav-pills nav-wizard nav-justified pipe-stage"></ul>
			</div>
		{/if} *}

		{*closing div of detailviewHeader*}

		<div class="joforce-tabs-list" id="joforce-tabs-list">
		    <div class="detailview-content container-fluid">
               	    	<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
                    	<div class="details row row-sm col-lg-12 col-xl-12 col-md-12 col-sm-12 pull-left">
