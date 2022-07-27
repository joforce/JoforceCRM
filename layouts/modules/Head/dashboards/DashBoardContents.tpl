{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Head/views/DashBoard.php *}
    
{strip}
<div class="dashBoardContainer clearfix">
<div id="licence-alert-waring" class="alert">
  <span class="closebtn " onclick="this.parentElement.style.display='none';">&times;</span> 

  <strong> <span class="licence-waring-icon"><i class="fa fa-warning"></i> </span> Danger!</strong>  You are not secure 

</div>
        <div class="tabContainer">
	    <div class="tab-content">
                {foreach key=index item=TAB_DATA from=$DASHBOARD_TABS}
                    <div id="tab_{$TAB_DATA["id"]}" data-tabid="{$TAB_DATA["id"]}" data-tabname="{$TAB_DATA["tabname"]}" class="tab-pane {if $TAB_DATA["id"] eq $SELECTED_TAB}in active{/if}">
                        {if $TAB_DATA["id"] eq $SELECTED_TAB}
                            {include file="dashboards/DashBoardTabContents.tpl"|vtemplate_path:$MODULE TABID=$TABID}
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
</div>
{/strip}
