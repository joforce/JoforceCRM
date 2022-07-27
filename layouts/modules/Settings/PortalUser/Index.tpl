{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Settings/MenuManager/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="setPortalUser card">
        <div class="editViewHeader col-sm-12 ml10 mr10 mt0 card-header-new row" >
                <h1 class="col-md-10 col-sm-11">PortalUser</h1>
                <span class="fa fa-info-circle alertShow pull-right ml20 mt15 "></span>
        </div>
        <div class="alerthide">
        <div class="col-sm-12 tooltiptext">
                <div class="row ml50">
                <div class="col-md-6"></div>
                        <div class="col-md-5 pull-right vt-default-callout vt-info-callout">
                                <h4 class="vt-callout-header"><span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE_NAME)}</h4>
                                <p>{vtranslate('LBL_MASQUERADE_USER_INFO', $QUALIFIED_MODULE_NAME)}</p>
                        </div>
                </div>
        </div>
        </div>
        <br>
        <div class="row ml50">
            <div class="col-lg-12 mt30">
                <div class="col-lg-4 pl0">
                        <div class="menu-box">
				<span><b> Enable Masquerade User : </b></span>
				<input type="checkbox" class="ml10" name="enable-masquerade-user" id="enable-masquerade-user" {if $MASQUERADE_USER_STATUS} value=true checked {/if} />
			</div>
		</div>
	    </div>

	    <div class="col-lg-7 col-md-7 col-sm-7 pt30">
		<div  class="alert alert-warning">
            	Note : If you are enabling the masquerade user feature, Change the sharing privilege status as private for the modules enabled to the masquerade user.
		</div>
            </div>
	</div>
