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
.select2-arrow{
   border-left:unset !important;
   background:unset !important;
   top:3px !important;
}
.select2-chosen{
   margin-top:unset !important;
   padding:3px !important;
}
.msg{
	padding:10px;
}
</style>
<div class="row main-container" id="page1" style="height:100%;overflow:auto;">
	<div class="gs-info"> 
	 {include file="Sidebar.tpl"|vtemplate_path:'Install'}
	</div>
	<div class="col-lg-3"> 
	</div>
	<div class="inner-container col-lg-8">
	<div class="mobile-view"><i class="fa fa-arrow-left"></i></div>
		<form class="form-horizontal card-view p-4" name="step1" method="post" action="index.php">
			<input type=hidden name="module" value="Install" />
			<input type=hidden name="view" value="Index" />
			<input type=hidden name="mode" value="Step3" />
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="welcome-div">
						<h3>{vtranslate('LBL_WELCOME_TO_JOFORCE_SETUP_WIZARD', 'Install')}</h3>
						{vtranslate('LBL_JOFORCE_SETUP_WIZARD_DESCRIPTION','Install')}
					</div></div>
					<div class="col-md-12 welcome-image text-center">
					<img src="{'wizard_screen.png'|vimage_path}" width="70%" alt="Head Logo"/>
				</div>
				<div class="col-md-8 install-language text-center offset-md-2">
					{assign var=LANGUAGES value=Install_Utils_model::getLanguageList()}
					{if $LANGUAGES|@count > 1}
						<div>
							<span>{vtranslate('LBL_CHOOSE_LANGUAGE', 'Install')}
								<select name="lang" id="lang" class="select2">
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
                <div class="col-md-12 text-center">
                    <span class='msg'> To continue installing Joforce, you must agree to the terms of the <b><a href='#' class='show_licence'>license agreement</a></b> </span>
                </div>
            </div>
            <div class="row">
                <div class="license" style='display: none; margin: 10px !important;'>
                    <div class="lic-scroll">
                        {include file="licenses/License.html"}
                    </div>
                </div>
            </div>
	    <div class="row">
		<div class="button-container_ col-sm-12 joforce-install-btn install_step_button">
			<input type="submit" class="btn btn-sm btn-primary  btn-next" value="{vtranslate('I Agree - Continue', 'Install')}"/>
	   	    	<a id="save_sale"><input type="" class="btn btn-sm btn-primary ml-4 " value="Upgrade from Joforce 2.0 ?"/></a>
		</div>
	    </div>
	</form>
	</div>
</div>
<script type='text/javascript'>
{literal}
    $(document).ready(function()    {
    
        $('.show_licence').on('click', function()   {
            $('.license').toggle();
        })
		$('#save_sale').click(function() {
    $.ajax({
		type: 'GET',
        url: 'migration/configmigration.php',
        success: function(data) { 
			if(data == true){
                alert('Add Joforce v1.5  to v3.0 Config.inc.php');
			}
			else {
				window.location.href = 'migration'
			}
            },
        error: function(xhr, ajaxOptions, thrownerror) { }
					});
});


    });
{/literal}
</script>
