{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

<div class="row main-container" id="page1">
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
		<form class="form-horizontal" name="step1" method="post" action="index.php">
			<input type=hidden name="module" value="Install" />
			<input type=hidden name="view" value="Index" />
			<input type=hidden name="mode" value="Step3" />
			<div class="row">
				<div class="col-md-12 text-center">
					<div class="welcome-div">
						<h3>{vtranslate('LBL_WELCOME_TO_VTIGER7_SETUP_WIZARD', 'Install')}</h3>
						{vtranslate('LBL_VTIGER7_SETUP_WIZARD_DESCRIPTION','Install')}
					</div></div>
					<div class="col-md-12 welcome-image text-center">
					<img src="{'wizard_screen.png'|vimage_path}" width="78%" alt="Head Logo"/>
				</div>
				<div class="col-md-8 install-language text-center col-md-offset-2">
					{assign var=LANGUAGES value=Install_Utils_model::getLanguageList()}
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
                <div>
                    <span class='msg'> To continue installing Joforce, you must agree to the terms of the <b><a href='#' class='show_licence'>license agreement</a></b> </span>
                </div>
            </div>
            <div class="row">
                <div class="license" style='display: none; margin-top: 10px !important;'>
                    <div class="lic-scroll">
                        {include file="licenses/License.html"}
                    </div>
                </div>
            </div>
	    <div class="row">
		<div class="button-container col-sm-12 joforce-install-btn">
			<input type="submit" class="btn btn-large btn-primary pull-right btn-next" value="{vtranslate('I Agree - Continue', 'Install')}"/>
	   	    	<a href='migration'><input type="" class="btn btn-large btn-primary pull-right mr40" value="Migrate from VtigerCRM ?"/></a>
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

    });
{/literal}
</script>
