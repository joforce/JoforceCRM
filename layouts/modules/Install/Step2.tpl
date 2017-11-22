{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<div class="row main-container">
	<div class="inner-container">
		<!-- <div class="row">
			<div class="col-sm-10">
				<h4>{vtranslate('LBL_WELCOME', 'Install')}</h4>
			</div>
		</div>
		<hr> -->

    <div class="col-sm-12 text-center">
      <div class="logo install-logo">
        <img src="{'logo.png'|vimage_path}"/>
      </div>
    </div>

<!-- new section start -->

<div class="joforce-install-section joforce-install-row-bottom col-md-offset-1">
    <div class="joforce-install-row">
      <div class="joforce-install-step">
         <div class="joforce-install-circle joforce-install-completed"><span>1</span></div>
         <p>{vtranslate('LBL_WELCOME_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step" id="step2">
        <div class="joforce-install-circle joforce-install-active"><span>2</span></div>
        <p>{vtranslate('LBL_AGREE_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
       <div class="joforce-install-circle"><span>3</span></div>
       <p>{vtranslate('LBL_PREREQUISITES_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
        <div class="joforce-install-circle"><span>4</span></div>
        <p>{vtranslate('LBL_CONFIGURATION_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
         <div class="joforce-install-circle"><span>5</span></div>
         <p>{vtranslate('LBL_CONFIRM_CONFIGURATION_INSTALL','Install')}</p>
      </div>
      <!-- <div class="joforce-install-step">
        <div class="joforce-install-circle"><span>6</span></div>
        <p>{vtranslate('LBL_ONE_LAST_THING_INSTALL','Install')}</p>
      </div>
      <div class="joforce-install-step">
        <div class="joforce-install-circle"><span>7</span></div>
        <p>{vtranslate('LBL_LOADING_PLEASE_WAIT_INSTALL','Install')}</p>
      </div> -->
    </div>
  </div>

<!-- new section end -->

		<form class="form-horizontal" name="step2" method="get" action="index.php">
			<input type=hidden name="module" value="Install" />
			<input type=hidden name="view" value="Index" />
			<input type=hidden name="mode" value="Step3" />

			<div class="license">
				<div class="lic-scroll">
                    {include file="licenses/License.html"}
                </div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="button-container joforce-install-btn">
						<input name="back" type="button" class="btn btn-large" value="{vtranslate('LBL_DISAGREE', 'Install')}"/>
						<input id="agree" type="submit" class="btn btn-large btn-primary" value="{vtranslate('LBL_I_AGREE', 'Install')}"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
