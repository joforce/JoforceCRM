{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Head/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="modules/Head/partials/Topbar.tpl"}

<div class="container-fluid app-nav module-header {if $LEFTPANELHIDE eq '1'} full-header {/if} {if $VIEW eq 'Edit'} themebackground {/if}">
    <div class="row mt10">
	{if $VIEW eq 'Edit'}
		{include file="partials/HeaderBreadCrumb.tpl"|vtemplate_path:$MODULE}
	{else}
		{include file="ModuleHeader.tpl"|vtemplate_path:$MODULE}
	{/if}
    </div>
</div>
</nav>
<div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-60' tabindex='-1' role='dialog' aria-hidden='true'>
    <div class="data">
    </div>
    <div class="modal-dialog">
    </div>
</div>
<div class="main-container clearfix main-container-{$MODULE}">
	<div id="sidebar-essentials" class="sidebar-essentials {if $LEFTPANELHIDE eq '1'} shrinked-sidebar {/if}">
            {include file="modules/Head/partials/SidebarAppMenu.tpl"}
	</div>
    <div class="quick-panel"></div>

        <div class="clearfix">
                <div class="editViewPageDiv viewContent content-area {if $LEFTPANELHIDE eq '1'} full-width {/if} {if $VIEW eq 'Edit'}{if $MODULE eq 'PDFMaker'} mt50 {else} mt30 {/if} {/if}">
                 <div id="licence-alert-waring" class="alert mt-5">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 

  <strong> <span class="licence-waring-icon"><i class="fa fa-warning"></i> </span> Danger!</strong>  You are not secure 

</div>
                        <div class="col-sm-12 col-xs-12 pr0 pl0  {$MODULE} {$EDIT_VIEWS}">
