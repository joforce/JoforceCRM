{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<footer class="app-footer login_footer">
    <a href="https://www.joforce.com" target="_blank">
	<img class="pull-right" src='{$SITEURL}layouts/skins/images/JoForce-footer.png' width="30px" style="position:absolute;right: 0px;z-index: 1000;">
    </a>
    <p>
        Copyright © Joforce. Thanks to <a class="joforce-link" href="https://joforce.com/credits" target="_blank"> Smackcoders</a>
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
