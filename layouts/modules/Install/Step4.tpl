{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<form class="form-horizontal" name="step4" method="post" action="index.php">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step5" />

	<div class="row main-container">
		<div class="inner-container">
			<!-- <div class="row">
				<div class="col-sm-10 joforce-install-heading">
					<h3>{vtranslate('LBL_SYSTEM_CONFIGURATION', 'Install')} </h3>
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
        <div class="joforce-install-circle joforce-install-active"><span>4</span></div>
        <p>{vtranslate('LBL_CONFIGURATION_INSTALL', 'Install')}</p>
      </div>
      <div class="joforce-install-step">
         <div class="joforce-install-circle"><span>5</span></div>
         <p>{vtranslate('LBL_CONFIRM_CONFIGURATION_INSTALL','Install')}</p>
      </div>
     <!--  <div class="joforce-install-step">
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

			<div class="row hide" id="errorMessage"></div>
			<div class="row install-form-section">
				<div class="install-configuration-step col-md-4 col-md-offset-1">
				<section class="joforce-install-heading form-group">
                                    <h4>{vtranslate('LBL_DATABASE_INFORMATION', 'Install')}</h4><hr class="install-hr">
                                    </section>
                     <div class="form-group">
                        <label>{vtranslate('LBL_DATABASE_TYPE', 'Install')} <span class="no">*</span></label>
                        <span class="pl40 install-label-value">{vtranslate('MySQL', 'Install')}
            {if function_exists('mysqli_connect')}</span>
            <input type="hidden" class="form-control install-input" value="mysqli" name="db_type">
            {else}
            <input type="hidden" class="form-control install-input" value="mysql" name="db_type">
            {/if}
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_HOST_NAME', 'Install')} <span class="no">*</span></label>
                        <input type="text" class="form-control install-input" value="{$DB_HOSTNAME}" name="db_hostname">
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_USERNAME', 'Install')} <span class="no">*</span></label>
                        <input type="text" class="form-control install-input" value="{$DB_USERNAME}" name="db_username">
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_PASSWORD','Install')}</label>
                        <input type="password" class="form-control install-input" value="{$DB_PASSWORD}" name="db_password">
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_DB_NAME', 'Install')} <span class="no">*</span></label>
                        <input type="text" class="form-control install-input" value="{$DB_NAME}" name="db_name">
                     </div>
                     <!-- <div class="form-group">
                        <label>No of Device</label>
                        <input type="text" class="form-control" name="template_name" >
                     </div> -->
                     <div class="form-group">
                      <label>
            <input type="checkbox" name="create_db"/>
            <span class="pl10">{vtranslate('LBL_CREATE_NEW_DB','Install')}</span>
            </label>
            </div>
                     <div class="hide form-group" id="root_user">
                        <label>{vtranslate('LBL_ROOT_USERNAME', 'Install')}<span class="no">*</span></label>
                        <input type="text" class="form-control install-input" value="" name="db_root_username">
                     </div>
                     <div class="hide form-group" id="root_password">
                        <label>{vtranslate('LBL_ROOT_PASSWORD', 'Install')}</label>
                        <input type="password" class="form-control install-input" value="" name="db_root_password">
                     </div>

                     <section class="joforce-install-heading form-group mt30">
                                    <h4>{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</h4><hr class="install-hr">
                                    </section>
                                 <div class="form-group">
                                         <label>{vtranslate('LBL_CURRENCIES','Install')} <span class="no">*</span></label>
                                           <select name="currency_name" class="select2 install-select">
            {foreach key=CURRENCY_NAME item=CURRENCY_INFO from=$CURRENCIES}
            <option value="{$CURRENCY_NAME}" {if $CURRENCY_NAME eq 'USA, Dollars'} selected {/if}>{$CURRENCY_NAME} ({$CURRENCY_INFO.1})</option>
            {/foreach}
            </select>
                                       </div>
                 </div>
				 <div class="install-configuration-step col-md-4 col-md-offset-1">
				 <section class="joforce-install-heading form-group">
                                    <h4>{vtranslate('LBL_ADMIN_INFORMATION', 'Install')}</h4><hr class="install-hr">
                                    </section>
					<div class="form-group">
                        <label>{vtranslate('LBL_USERNAME', 'Install')}</label>
                        <span class="pl40 install-label-value">admin</span><input class="form-control install-input" type="hidden" name="{$ADMIN_NAME}" value="admin" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_PASSWORD', 'Install')} <span class="no">*</span></label>
                        <input type="password" class="form-control install-input" value="{$ADMIN_PASSWORD}" name="password" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_RETYPE_PASSWORD', 'Install')} <span class="no">*</span></label>
                        <input type="password" class="form-control install-input" value="{$ADMIN_PASSWORD}" name="retype_password" />
            <span id="passwordError" class="no"></span>
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('First Name', 'Install')}</label>
                        <input type="text" class="form-control install-input" value="" name="firstname" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('Last Name', 'Install')} <span class="no">*</span></label>
                        <input type="text" class="form-control install-input" value="{$ADMIN_LASTNAME}" name="lastname" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_EMAIL','Install')} <span class="no">*</span></label>
                        <input type="text" class="form-control install-input" value="{$ADMIN_EMAIL}" name="admin_email">
                     </div>
                     <div class="form-group">
                         <label>{vtranslate('LBL_DATE_FORMAT','Install')} <span class="no">*</span></label>
                           <select class="select2 install-select"  name="dateformat">
               <option value="mm-dd-yyyy">mm-dd-yyyy</option>
               <option value="dd-mm-yyyy">dd-mm-yyyy</option>
               <option value="yyyy-mm-dd">yyyy-mm-dd</option>
            </select>
                    </div>
                    <div class="form-group">
                       <label>{vtranslate('LBL_TIME_ZONE','Install')} <span class="no">*</span></label>
                           <select class="select2 install-select" name="timezone">
            {foreach item=TIMEZONE from=$TIMEZONES}
            <option value="{$TIMEZONE}" {if $TIMEZONE eq 'America/Los_Angeles'}selected{/if}>{vtranslate($TIMEZONE, 'Users')}</option>
            {/foreach}
            </select>
                     </div>   
                     <!-- <div class="form-group">
                        <label>No of Device</label>
                        <input type="text" class="form-control" name="template_name" >
                     </div>   --> 
					
				 </div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="button-container joforce-install-btn">
						<input type="button" class="btn btn-large" value="{vtranslate('LBL_BACK','Install')}" name="back"/>
						<input type="button" class="btn btn-large btn-primary" value="{vtranslate('LBL_NEXT','Install')}" name="step5"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
