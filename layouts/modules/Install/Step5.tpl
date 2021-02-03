{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

<form class="form-horizontal" name="step5" method="post" action="index.php">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step7" />
	<input type=hidden name="auth_key" value="{$AUTH_KEY}" />

	<div class="row main-container step5" id="page5">
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
			{if $DB_CONNECTION_INFO['flag'] neq true}
				<div class="offset2 row" id="errorMessage">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="alert alert-error" style="color: #0d4426;">
							{$DB_CONNECTION_INFO['error_msg']}
							{$DB_CONNECTION_INFO['error_msg_info']}
						</div>
					</div>
				</div>
			{/if}
			<div class="offset2 install-form-section"> 
				<div class="col-md-5 col-md-offset-1">
				<section class="joforce-install-heading form-group">
                    <h4>{vtranslate('LBL_DATABASE_INFORMATION','Install')}</h4><hr class="install-hr">
                </section>
		        <div class="form-group">
                    <label class="install-info">{vtranslate('LBL_DATABASE_TYPE','Install')}</label>
                    <span class="install-label-value">{vtranslate('MySQL','Install')}</span>
                </div>
                <div class="form-group">
                    <label class="install-info">{vtranslate('LBL_DB_NAME','Install')}</label>
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
                        <label class="install-info">{vtranslate('LBL_CURRENCY','Install')}</label>
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
                        <label class="install-info">{vtranslate('LBL_EMAIL','Install')}</label>
                        <span class="install-label-value">{$INFORMATION['admin_email']}</span>
                     </div>
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_TIME_ZONE','Install')}</label>
                        <span class="install-label-value">{$INFORMATION['timezone']}</span>
                     </div>
                     <div class="form-group">
                        <label class="install-info">{vtranslate('LBL_DATE_FORMAT','Install')}</label>
                        <span class="install-label-value">{$INFORMATION['dateformat']}</span>
                     </div>  
				</div>
			</div>
			<div class="row offset2">
				<div class="col-sm-12">
					<div class="button-container">
						<input type="button" class="btn btn-large btn-default" value="{vtranslate('LBL_BACK','Install')}" {if $DB_CONNECTION_INFO['flag'] eq true} disabled= "disabled" {/if} name="back"/>
						{if $DB_CONNECTION_INFO['flag'] eq true}
							<input type="button" class="btn btn-large btn-primary btn-next but" value="{vtranslate('Install','Install')}" name="step7"/>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
    <footer class="app-footer">
    <a href="//www.joforce.com" target="_blank">
        <img class="pull-right" src='{$SITEURL}layouts/skins/images/JoForce-footer.png' width="30px">
    </a>
    <p>
        Copyright © Joforce. Thanks to <a class="joforce-link" href="https://joforce.com/credits" target="_blank"> open source projects.</a>
    </p>
    </footer>
</form>
<div id="progressIndicator" class="row main-container hide" style="color: #30302; background: #F4F4F4">
    <div class="inner-container" style="width:100% !important;float:left !important;">
        <div class="col-sm-12">
            <div class="col-sm-10 col-sm-offset-1">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style='background: transparent !important; box-shadow: none;'>
                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="4"></li>
	                    <li data-target="#carousel-example-generic" data-slide-to="5"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="item active">
                            <div class="col-md-4">
                                <img src="layouts/modules/Install/resources/images/zapier.png">
                            </div>
                            <div class="col-md-8">
                                <h3> Zapier Integration </h3>
		                        <p> As a part of Sales, Marketing and Support team, every one of us may work across different apps. When all these app based works are handled right from your CRM, would it be awesome?</p>
		                        <p> Connect with your favourite app that you use everyday and get things done right from Joforce via Zapier. Build Workflows and automate your day-to-day activities. </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4">
                                <img src="layouts/modules/Install/resources/images/EmailPlus.png">
                            </div>
                            <div class="col-md-8">
                                <h3> Email</h3>
                                <p> Bring email communication within CRM by integrating your email client to Joforce. Now you can send and receive emails from Joforce. Gives a look-&-feel, that you’re inside your email inbox. Send email from Joforce and get it automatically logged in your Joforce and email client as well.  </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4">
                                <img src="layouts/modules/Install/resources/images/kanban.png">
                            </div>
                            <div class="col-md-8">
                                <h3> Kanban view </h3>
		                        <p>Visually track your sales process to efficiently track, analyse, prioritize and close deals at the faster rate. It gives a better insight of all your deals in each stage of your pipeline. Once you’re successful in your sales activities, just drag & drop deals to navigate between different stages of your pipeline. </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4">
                                <img src="layouts/modules/Install/resources/images/pdf.png">
                            </div>
                            <div class="col-md-8">
                                <h3> PDF Maker</h3>
                                <p> Seamlessly design your own PDF Templates with PDF Maker. Individual template designs for Invoice, Quotes, Sales order and Purchase order. Easy to add your own company and product information. You can design your PDF with different page layouts in the way you want. </p>
                            </div>
                        </div>
                        <div class="item">
                            <div class="col-md-4">
                                <img src="layouts/modules/Install/resources/images/mobile.png">
                            </div>
                            <div class="col-md-8">
                                <h3> Mobile</h3>
		                        <p>When sales keeps your sales rep moving, shouldn’t your CRM move along with? Empower your reps to carry the CRM wherever they go. Turn your mobile phone as your CRM and get things on the fly. </p>
                                <p>Your sales on the go</p>
                                <p>Take actions from anywhere</p>
                            </div>
                        </div>
	                    <div class="item">
                            <div class="col-md-4">
                            <img src="layouts/modules/Install/resources/images/google.png">
                        </div>
                        <div class="col-md-8">
                            <h3> Google Integration </h3>
                            <p> Google Calendar - Never miss out any of your important appointments, track & organize all your activities from one place. </p>
                            <p> Google contacts - All your contact info at one spot. Keep your Google & Joforce Contact info sync in just a click. </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="inner-container">
        <div class="row">
            <div class="col-sm-12 welcome-div alignCenter">
                <h3>{vtranslate('LBL_INSTALLATION_IN_PROGRESS','Install')}...</h3><br>
                <div class="load-bar">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
{literal}
.load-bar {
  position: relative;
  margin-top: 20px;
  width: 100%;
  height: 6px;
  background-color: #fdba2c;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
}
.bar:nth-child(1) {
  background-color: #da4733;
  animation: loading 3s linear infinite;
}
.bar:nth-child(2) {
  background-color: #3b78e7;
  animation: loading 3s linear 1s infinite;
}
.bar:nth-child(3) {
  background-color: #fdba2c;
  animation: loading 3s linear 2s infinite;
}
@keyframes loading {
    from {left: 50%; width: 0;z-index:100;}
    33.3333% {left: 0; width: 100%;z-index: 10;}
    to {left: 0; width: 100%;}
}
{/literal}
</style>
