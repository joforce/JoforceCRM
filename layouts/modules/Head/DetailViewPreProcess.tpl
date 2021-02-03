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
    <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="col-md-4 col-sm-12 col-xs-4">
		{include file="partials/HeaderBreadCrumb.tpl"|vtemplate_path:$MODULE}
	</div>
	<div class="col-lg-8 col-md-4 col-sm-12 detailViewButtoncontainer">
        	{include file="DetailViewActions.tpl"|vtemplate_path:$MODULE}
	</div>
    </div>
    <!--<div class="container-fluid main-container">-->
    <div class='joforce-bg'>
	<div class="detailViewContainer viewContent clearfix">
	{assign var=FIELDS_MODELS_LIST value=$MODULE_MODEL->getFields()}
	    <div class="col-sm-12 col-xs-12">
		{if !in_array($MODULE, array('Invoice', 'Quotes','SalesOrder','PurchaseOrder'))}
		<div class=" detailview-header-block">
		    <div class="detailview-header">
		        <div class="row">
		            {include file="DetailViewHeaderTitle.tpl"|vtemplate_path:$MODULE}
		        </div>
		    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
        	            <!--{include file="DetailViewTagList.tpl"|vtemplate_path:$MODULE}-->
                	</div>
                    </div>
		</div>
		{/if}

		{if $kanban_view_enabled}
			<div id = 'pipeline_stages'>
			    <ul class="nav nav-pills nav-wizard nav-justified pipe-stage"></ul>
			</div>
		{/if}

		{*closing div of detailviewHeader*}

		<div class="joforce-tabs-list" id="joforce-tabs-list">
		    <div class="detailview-content container-fluid">
               	    	<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
			<div class="col-lg-1 col-xl-1 col-md-12 col-sm-12" style="float:right;">
	            	    {include file="ModuleRelatedTabs.tpl"|vtemplate_path:$MODULE}
                	</div>
                    	<div class="details row row-sm col-lg-11 col-xl-12 col-md-12 col-sm-11">
