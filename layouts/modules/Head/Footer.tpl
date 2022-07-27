{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<script type="text/javascript" src="{$SITEURL}layouts/modules/Head/resources/Footer.js"></script>

<footer  class="app-footer {$MODULE} {$EDIT_VIEWS} {$MODE} {$settings}  {if $MODE eq 'edit'} Edit_potentials {/if}{if $kanban_view_enabled } kanbanview {/if}">
    <a href="//www.joforce.com" target="_blank">
	<img id="footer-logo" class="pull-right" src='{$SITEURL}{$Image_Footer}'  width="30px">
    </a>
    <p>
	Powered By <a class="joforce-link" id="footer-main-company" href="{$Company_Footer_Link}" target="_blank">{$Company_Footer}</a>
    </p>
</footer>
</div>
<div id='overlayPage'>
	<!-- arrow is added to point arrow to the clicked element (Ex:- TaskManagement), 
	any one can use this by adding "show" class to it -->
	<div class='arrow'></div>
	<div class='data'>
	</div>
</div>
<div id='helpPageOverlay'></div>
<div id="js_strings" class="hide noprint">{Zend_Json::encode($LANGUAGE_STRINGS)}</div>
<div class="modal myModal fade"></div>
{include file='JSResources.tpl'|@vtemplate_path}
</body>

</html>
