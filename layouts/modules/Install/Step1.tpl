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
<!-- new section start -->
<div class="col-sm-12 text-center">
			<div class="logo install-logo">
				<img src="{'logo.png'|vimage_path}"/>
			</div>
		</div>



<div class="joforce-install-section col-md-offset-1">
    <div class="joforce-install-row">
      <div class="joforce-install-step">
         <div class="joforce-install-circle joforce-install-active"><span>1</span></div>
         <p>{vtranslate('LBL_WELCOME_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step" id="step2">
        <div class="joforce-install-circle"><span>2</span></div>
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
		<form class="form-horizontal" name="step1" method="post" action="index.php">
			<input type=hidden name="module" value="Install" />
			<input type=hidden name="view" value="Index" />
			<input type=hidden name="mode" value="Step2" />


			<div class="row">
				
				<div class="col-md-8 text-center col-md-offset-2">
					<div class="welcome-div">
						<h3>{vtranslate('LBL_WELCOME_TO_VTIGER7_SETUP_WIZARD', 'Install')}</h3>
						{vtranslate('LBL_VTIGER7_SETUP_WIZARD_DESCRIPTION','Install')}
					</div></div>
					<div class="col-md-8 welcome-image text-center col-md-offset-2">
					<img src="{'wizard_screen.png'|vimage_path}" width="300" alt="Head Logo"/>
				</div>
					<div class="col-md-8 install-language text-center col-md-offset-2">
					{if $LANGUAGES|@count > 1}
						<div>
							<span>{vtranslate('LBL_CHOOSE_LANGUAGE', 'Install')}
								<select name="lang" id="lang">
									{foreach key=header item=language from=$LANGUAGES}
										<option value="{$header}" {if $header eq $CURRENT_LANGUAGE}selected{/if}>{vtranslate("$language",'Install')}</option>
									{/foreach}
								</select>
							</span>
						</div>
					{/if}
				</div>
				
			</div>
			<div class="row">
				<div class="button-container col-sm-12 joforce-install-btn">
					<input type="submit" class="btn btn-large btn-primary pull-right" value="{vtranslate('LBL_INSTALL_BUTTON','Install')}"/>
				<a href='migration'><input type="" class="btn btn-large btn-primary pull-right mr40" value="Vtiger Migration"/></a>
				</div>
			</div>
		</form>
	</div>
</div>
