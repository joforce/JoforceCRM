{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

<form class="form-horizontal" name="step4" method="post" action="index.php">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step5" />

	<div class="row main-container" id="page4">
		
    <div class="gs-info">
	 {include file="Sidebar.tpl"|vtemplate_path:'Install'}
  </div>

        <div class="inner-container">
        <div class="mobile-view"><i class="fa fa-arrow-left"></i></div>
            <div class="col-sm-12 text-center">
            <div class="logo install-logo">
                <img src="{'logo.png'|vimage_path}"/>
            </div>
        </div>
             
			<div class="row hide" id="errorMessage"></div>
			<div class="row install-form-section">
				<div class="install-configuration-step col-md-4 col-md-offset-1">
				<section class="joforce-install-heading form-group">
                                    <h4>{vtranslate('LBL_DATABASE_INFORMATION', 'Install')}</h4><hr class="install-hr">
                                    </section>
                     <div class="form-group">
                        <label>{vtranslate('LBL_DATABASE_TYPE', 'Install')} <span class=""></span></label>
                        <span class="pl40 install-label-value">{vtranslate('MySQL', 'Install')}
            {if function_exists('mysqli_connect')}</span>
            <input type="hidden" class="form-control install-input" value="mysqli" name="db_type">
            {else}
            <input type="hidden" class="form-control install-input" value="mysql" name="db_type">
            {/if}
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_HOST_NAME', 'Install')} <span class="no"></span></label>
                        <input type="text" class="form-control install-input" value="{$DB_HOSTNAME}" name="db_hostname">
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_USERNAME', 'Install')} <span class="no"></span></label>
                        <input type="text" class="form-control install-input" value="{$DB_USERNAME}" name="db_username">
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_PASSWORD','Install')}</label>
                        <input type="password" class="form-control install-input" value="{$DB_PASSWORD}" name="db_password">
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_DB_NAME', 'Install')} <span class="no"></span></label>
                        <input type="text" class="form-control install-input" value="{$DB_NAME}" name="db_name">
                     </div>
                     <section class="joforce-install-heading form-group mt30">
                                    <h4>{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</h4><hr class="install-hr">
                                    </section>
                                 <div class="form-group">
                                         <label>{vtranslate('LBL_CURRENCIES','Install')} <span class="nos"></span></label>
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
                        <label>{vtranslate('LBL_PASSWORD', 'Install')} <span class="no"></span></label>
                        <input type="password" class="form-control install-input" value="{$ADMIN_PASSWORD}" name="password" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_RETYPE_PASSWORD', 'Install')} <span class="no"></span></label>
                        <input type="password" class="form-control install-input" value="{$ADMIN_PASSWORD}" name="retype_password" />
            <span id="passwordError" class="redColor"></span>
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('First Name', 'Install')}</label>
                        <input type="text" class="form-control install-input" value="" name="firstname" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('Last Name', 'Install')} <span class="no"></span></label>
                        <input type="text" class="form-control install-input" value="{$ADMIN_LASTNAME}" name="lastname" />
                     </div>
                     <div class="form-group">
                        <label>{vtranslate('LBL_EMAIL','Install')} <span class="no"></span></label>
                        <input type="text" class="form-control install-input" value="{$ADMIN_EMAIL}" name="admin_email">
                     </div>
                     <div class="form-group">
                         <label>{vtranslate('LBL_DATE_FORMAT','Install')} <span class="nos"></span></label>
                           <select class="select2 install-select"  name="dateformat">
               <option value="mm-dd-yyyy">mm-dd-yyyy</option>
               <option value="dd-mm-yyyy">dd-mm-yyyy</option>
               <option value="yyyy-mm-dd">yyyy-mm-dd</option>
            </select>
                    </div>
                    <div class="form-group">
                       <label>{vtranslate('LBL_TIME_ZONE','Install')} <span class="nos"></span></label>
                           <select class="select2 install-select" name="timezone">
            {foreach item=TIMEZONE from=$TIMEZONES}
            <option value="{$TIMEZONE}" {if $TIMEZONE eq 'America/Los_Angeles'}selected{/if}>{vtranslate($TIMEZONE, 'Users')}</option>
            {/foreach}
            </select>
                     </div>   
			</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="button-container joforce-install-btn">
						<input type="button" class="btn btn-large btn-default" value="{vtranslate('LBL_BACK','Install')}" name="back"/>
						<input type="button" class="btn btn-large btn-primary btn-next but" value="{vtranslate('LBL_NEXT','Install')}" name="step5"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
