{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Settings/Head/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{include file="modules/Head/partials/Topbar.tpl"}

<div class="container-fluid app-nav">
    <div class="row">
        {include file="modules/Settings/Head/SidebarHeader.tpl"}
        {include file="modules/Settings/Head/ModuleHeader.tpl"}
    </div>
</div>
</nav>
 <div id='overlayPageContent' class='fade modal overlayPageContent content-area overlay-container-300' tabindex='-1' role='dialog' aria-hidden='true'>
        <div class="data">
        </div>
        <div class="modal-dialog">
        </div>
    </div>
{if $FIELDS_INFO neq null}
    <script type="text/javascript">
        var uimeta = (function() {
            var fieldInfo  = {$FIELDS_INFO};
            return {
                field: {
                    get: function(name, property) {
                        if(name && property === undefined) {
                            return fieldInfo[name];
                        }
                        if(name && property) {
                            return fieldInfo[name][property]
                        }
                    },
                    isMandatory : function(name){
                        if(fieldInfo[name]) {
                            return fieldInfo[name].mandatory;
                        }
                        return false;
                    },
                    getType : function(name){
                        if(fieldInfo[name]) {
                            return fieldInfo[name].type
                        }
                        return false;
                    }
                },
            };
        })();
    </script>
{/if}
<script type="text/javascript">
        $(document).ready(function(){
                if (document.querySelector('.settingsNav') !== null) {
            $('.main-container .content-area').css('padding-left','240px');
        }
        });
</script>
<div class="main-container clearfix">
	{assign var=LEFTPANELHIDE value=$USER_MODEL->get('leftpanelhide')}
{*+*******		{if !$IS_SETTINGS_INDEX_PAGE}
        		<div class="module-nav clearfix settingsNav" id="modnavigator">
		            <div class="hidden-xs hidden-sm height100Per">
                		{include file="modules/Settings/Head/Sidebar.tpl"}
		            </div>
		        </div>
		{/if}****}
        <div class="settingsPageDiv content-area clearfix">
