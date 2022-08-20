{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is: vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
 * Contributor(s): JoForce.com
  *
 ********************************************************************************/
-->*}

{strip}
<link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/app.css' media="screen"/>
<link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/jo-icons/style.css' media="screen"/>
<link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/skins/custom.css' media="screen"/>

<div class="container-fluid container-error-box">
   <div class="ir-response-page">
	<div class="row">
             <h1 class="page-error text-center ">404</h1>
	
	    <div class="row">  
		<div class="col-md-12 offset-md-2 error-box"> 
            <p class="error-msg mb-1">{vtranslate($MESSAGE)}  <i class="joicon-smiley2 pl10" aria-hidden="true"></i></p>
            <h4>Oops! You're lost.</h4>
		<a href='javascript:window.history.back();' class="homelink" >{vtranslate('LBL_GO_BACK')}</a> 
       	        </div>	
	    </div>
        </div>
  </div>
</div>

{/strip}
