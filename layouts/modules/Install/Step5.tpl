{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<form class="form-horizontal" name="step5" method="post" action="index.php">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step7" />
	<input type=hidden name="auth_key" value="{$AUTH_KEY}" />

	<div class="row main-container step5">
		<div class="inner-container">
			<!-- <div class="row">
				<div class="col-sm-10">
					<h4>{vtranslate('LBL_CONFIRM_CONFIGURATION_SETTINGS','Install')}</h4>
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
        <div class="joforce-install-circle joforce-install-completed"><span>2</span></div>
        <p>{vtranslate('LBL_AGREE_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
       <div class="joforce-install-circle joforce-install-completed"><span>3</span></div>
       <p>{vtranslate('LBL_PREREQUISITES_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
        <div class="joforce-install-circle joforce-install-completed"><span>4</span></div>
        <p>{vtranslate('LBL_CONFIGURATION_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
         <div class="joforce-install-circle joforce-install-active"><span>5</span></div>
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

			{if $DB_CONNECTION_INFO['flag'] neq true}
				<div class="offset2 row" id="errorMessage">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="alert alert-error">
							{$DB_CONNECTION_INFO['error_msg']}
							{$DB_CONNECTION_INFO['error_msg_info']}
						</div>
					</div>
				</div>
			{/if}
			<div class="offset2 row install-form-section">
				<div class="col-md-5 col-md-offset-1">
				<section class="joforce-install-heading form-group">
                                    <h4>{vtranslate('LBL_DATABASE_INFORMATION','Install')}</h4><hr class="install-hr">
                                    </section>
					<div class="form-group">
                        <label class="install-info">{vtranslate('LBL_DATABASE_TYPE','Install')}<span class="no">*</span></label>
                        <span class="install-label-value">{vtranslate('MySQL','Install')}</span>
                     </div>
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_DB_NAME','Install')}<span class="no">*</span></label>
                        <span class="install-label-value">{$INFORMATION['db_name']}</span>
                     </div>  

                     <section class="joforce-install-heading form-group mt30">
                                    <h4>{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</h4><hr class="install-hr">
                                    </section>
					<div class="form-group">
                        <label class="install-info">{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</label>
                        <span><a href="#">{$SITE_URL}</a></span>
                     </div>  
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_CURRENCY','Install')}<span class="no">*</span></label>
                        <span class="install-label-value">{$INFORMATION['currency_name']}</span>
                     </div>  
				</div>
				<div class="col-md-4 col-md-offset-1">
				<section class="joforce-install-heading form-group">
                                    <h4>{vtranslate('LBL_ADMIN_USER_INFORMATION','Install')}</h4><hr class="install-hr">
                                    </section>
					<div class="form-group">
                        <label class="install-info">{vtranslate('LBL_USERNAME','Install')}</label>
                        <span class="install-label-value">{$INFORMATION['admin']}</span>
                     </div>  
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_EMAIL','Install')}<span class="no">*</span></label>
                        <span class="install-label-value">{$INFORMATION['admin_email']}</span>
                     </div>
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_TIME_ZONE','Install')}<span class="no">*</span></label>
                        <span class="install-label-value">{$INFORMATION['timezone']}</span>
                     </div>
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_DATE_FORMAT','Install')}<span class="no">*</span></label>
                        <span class="install-label-value">{$INFORMATION['dateformat']}</span>
                     </div>  
				</div>
			</div>
			<div class="row offset2">
				<div class="col-sm-12">
					<div class="button-container">
						<input type="button" class="btn btn-large" value="{vtranslate('LBL_BACK','Install')}" {if $DB_CONNECTION_INFO['flag'] eq true} disabled= "disabled" {/if} name="back"/>
						{if $DB_CONNECTION_INFO['flag'] eq true}
							<input type="button" class="btn btn-large btn-primary" value="{vtranslate('LBL_NEXT','Install')}" name="step7"/>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<div id="progressIndicator" class="row main-container hide">
    <div class="inner-container">
        <div class="inner-container">
            <div class="row">
                <div class="col-sm-12 welcome-div alignCenter">
                    <h3>{vtranslate('LBL_INSTALLATION_IN_PROGRESS','Install')}...</h3><br>
                    <img src="{'install_loading.gif'|vimage_path}"/>
                    <h6>{vtranslate('LBL_PLEASE_WAIT','Install')}.... </h6>
                </div>
            </div>
        </div>
    </div>
</div>

