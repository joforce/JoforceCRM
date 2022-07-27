{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<style>
input:-webkit-autofill, input:-webkit-autofill:hover, input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0px 1000px #fff inset !important;
    transition: background-color 5000s ease-in-out 0s;
}
.label{
   position: absolute;
    z-index: 9;
    top: -21px;
    left: 10px;
    background-color: #fff;
}
.inputElement:not(.error):hover {
    border-color: #969696 !important;
    box-shadow: none !important;
}
.inputElement:not(.error):focus {
    border-color: #969696 !important;
    box-shadow: none !important;
}
.inputElement {
    width: 100% !important;
    margin-top: 10px !important;
    border-radius: 3px !important;
    box-shadow: none !important;
    background: white;
    display: block;
    height: 40px !important;
    padding: 0.375rem 0.75rem !important;
    font-size: 0.875rem !important;
    font-weight: 400;
    line-height: 1.5;
    color: #4d5875;
    background-color: #fff !important;
    background-clip: padding-box;
    border: 1px solid #e1e5ef;
}
.form-group{
   margin-bottom:30px !important;
}
.select2-arrow{
   border-left:unset !important;
   background:unset !important;
}
.select2-chosen{
   margin-top:unset !important;
   padding:5px !important;
}
.form-control{
   width:100% !important;
}
.alert{
   padding:10px 10px !important;
}
</style>
<form class="form-horizontal" name="step4" method="post" action="index.php" style="height:100%;">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step5" />

	<div class="row main-container" id="page4" style="height:100%;overflow:auto;">
		
      <div class="gs-info">
      	{include file="Sidebar.tpl"|vtemplate_path:'Install'}
      </div>
      <div class="col-lg-3"> 
      </div>      
      <div class="inner-container col-lg-8">

         <div class="row hide" id="errorMessage"></div>

         <div class="card-view">

            <div class="card-view-header d-flex justify-content-between">
               <h3>{vtranslate('LBL_INSTALLATION_SETTINGS', 'Install')}</h3>
               <div class="d-flex justify-content-center align-items-center p-1" id="recheck" style="cursor:pointer;">
                  <span>
                     <i class="fa fa-refresh" style="font-size:20px;"></i>
                  </span>
               </div>
            </div>

            <div class=" ui accordion p-4">
					<h3 class="title"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_DATABASE_INFORMATION', 'Install')}</h3>
               <div class="content">
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
                     <label class="label"><span class = "redColor">*</span> Action</label>
                     <select class="form-control install-select select2-container select2" name="db_action" id="db_action" style="font-size:15px;">
                        <option value="create" selected="">Create New Database</option>
                        <option value="empty">Connect and Remove All Data</option>
				         </select>
                  </div>                    
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('LBL_HOST_NAME', 'Install')} </label>
                     <input type="text" class="form-control install-input inputElement" value="localhost" name="db_hostname">
                  </div>
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('LBL_DB_NAME', 'Install')} </label>
                     <input type="text" class="form-control install-input inputElement" value="{$DB_NAME}" name="db_name">
                  </div>                  
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('LBL_USERNAME', 'Install')} </label>
                     <input type="text" class="form-control install-input inputElement" value="{$DB_USERNAME}" name="db_username">
                  </div>
                  <div class="form-group">
                     <label class="label">{vtranslate('LBL_PASSWORD','Install')}</label>
                     <input type="password" class="form-control install-input inputElement" value="{$DB_PASSWORD}" name="db_password">
                  </div>
               </div>
               
               <h3 class="title"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</h3>
               <div class="form-group content">
                  {* <label class="label" style="top:0;">{vtranslate('LBL_CURRENCIES','Install')}</label> *}
                  <select name="currency_name" class="form-control select2 install-select">
                  <option value="0">Currency </option>
                     {foreach key=CURRENCY_NAME item=CURRENCY_INFO from=$CURRENCIES}
                        <option value="{$CURRENCY_NAME}" {if $CURRENCY_NAME eq 'USA, Dollars'} selected {/if}>{$CURRENCY_NAME} ({$CURRENCY_INFO.1})</option>
                     {/foreach}
                  </select>
               </div>

               <h3 class="title"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_ADMIN_INFORMATION', 'Install')}</h3>

               <div class="content">
                  <div class="form-group">
                     <label>{vtranslate('LBL_USERNAME', 'Install')}</label>
                     <span class="pl40 install-label-value">admin</span><input class="form-control install-input" type="hidden" name="{$ADMIN_NAME}" value="admin" />
                  </div>
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('LBL_PASSWORD', 'Install')} </label>
                     <input type="password" class="form-control install-input inputElement" value="{$ADMIN_PASSWORD}" name="password" />
                  </div>
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('LBL_RETYPE_PASSWORD', 'Install')} </label>
                     <input type="password" class="form-control install-input inputElement" value="{$ADMIN_PASSWORD}" name="retype_password" />
                     <span id="passwordError" class="redColor"></span>
                  </div>
                  <div class="form-group">
                     <label class="label">{vtranslate('First Name', 'Install')}</label>
                     <input type="text" class="form-control install-input inputElement" value="" name="firstname" />
                  </div>
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('Last Name', 'Install')} </label>
                     <input type="text" class="form-control install-input inputElement" value="{$ADMIN_LASTNAME}" name="lastname" />
                  </div>
                  <div class="form-group">
                     <label class="label"><span class = "redColor">*</span> {vtranslate('LBL_EMAIL','Install')} </label>
                     <input type="text" class="form-control install-input inputElement" value="{$ADMIN_EMAIL}" name="admin_email" />
                  </div>
                  <div class="form-group">
                     <label class="label">{vtranslate('LBL_DATE_FORMAT','Install')}</span></label>
                     <select class="select2 install-select form-control"  name="dateformat">
                        <option value="mm-dd-yyyy">mm-dd-yyyy</option>
                        <option value="dd-mm-yyyy">dd-mm-yyyy</option>
                        <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                     </select>
                  </div>
                  <div class="form-group">
                     <label class="label">{vtranslate('LBL_TIME_ZONE','Install')}</label>
                     <select class="select2 install-select form-control" name="timezone">
                        {foreach item=TIMEZONE from=$TIMEZONES}
                           <option value="{$TIMEZONE}" {if $TIMEZONE eq 'America/Los_Angeles'}selected{/if}>{vtranslate($TIMEZONE, 'Users')}</option>
                        {/foreach}
                     </select>
                  </div>
               </div>

               <div class="row">
                  <div class="col-sm-12">
                     <div class="button-container joforce-install-btn">
                        <a href="{$SITE_URL}index.php?module=Install&view=Index&mode=Step3"><input type="button" class="btn btn-large btn-default" value="{vtranslate('LBL_BACK','Install')}"/></a>
                        <input type="button" class="btn btn-large btn-primary btn-next but" value="{vtranslate('LBL_NEXT','Install')}" name="step5"/>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
	</div>
</form>
