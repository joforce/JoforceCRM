{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

<form class="form-horizontal" name="step5" method="post" action="index.php" style="height:100%;">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step7" />
	<input type=hidden name="auth_key" value="{$AUTH_KEY}" />

	<div class="row main-container step5" id="page5" style="height:100%;overflow:auto;">
        <div class="gs-info">
	        {include file="Sidebar.tpl"|vtemplate_path:'Install'}
        </div>
        <div class="col-lg-3"> 
        </div>        
	    <div class="inner-container col-lg-8">
	        <div class="mobile-view"><i class="fa fa-arrow-left"></i></div>
			{if $DB_CONNECTION_INFO['flag'] neq true}
				<div class="offset2 row" id="errorMessage">
					<div class="col-sm-10 offset-sm-1">
						<div class="alert alert-error" style="color: #0d4426;">
							{$DB_CONNECTION_INFO['error_msg']}
							{$DB_CONNECTION_INFO['error_msg_info']}
						</div>
					</div>
				</div>
			{/if}

			<div class="card-view"> 
                <div class="card-view-header d-flex justify-content-between">
                <h3>{vtranslate('LBL_CHECK_INSTALLATION_SETTINGS', 'Install')} </h3>
                </div>

                <div class=" ui accordion p-4">
                    <h3 class="title"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_DATABASE_INFORMATION','Install')}</h3>
                    <div class="content">
                        <div class="form-group">
                            <label class="install-info">{vtranslate('LBL_DATABASE_TYPE','Install')}</label>
                            <span class="install-label-value">{vtranslate('MySQL','Install')}</span>
                        </div>
                        <div class="form-group">
                            <label class="install-info">{vtranslate('LBL_DB_NAME','Install')}</label>
                            <span class="install-label-value">{$INFORMATION['db_name']}</span>
                        </div>  
                    </div>

                    <h3 class="title"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</h3>
                    <div class="content">
                        <div class="form-group">
                            <label class="install-info">{vtranslate('LBL_SYSTEM_INFORMATION','Install')}</label>
                            <span><a href="#">{$SITE_URL}</a></span>
                        </div>  
                        <div class="form-group">
                            <label class="install-info">{vtranslate('LBL_CURRENCY','Install')}</label>
                            <span class="install-label-value">{$INFORMATION['currency_name']}</span>
                        </div>  
                    </div>

                    <h3 class="title"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_ADMIN_USER_INFORMATION','Install')}</h3>
                    <div class="content">
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

                    <div class="row offset2">
                        <div class="col-sm-12">
                            <div class="d-flex mt-3">
                                <div class="fieldValueNew Tracker chckbox" style="width:33px!important"><input type="hidden" name="emailoptout" value="0"><input id="tracker_checkbox" class="inputElement" data-fieldname="emailoptout" data-fieldtype="checkbox" type="checkbox" name="emailoptout"> 
                               
                                </div> 
                                <span class="mt-2 ml-2 track_agree ">Yes, I understand that by installing Joforce, I consent to the collection of anonymous data to help improve Joforce CRM.</span>
                               
                            </div>
                            <div>
                            <p class="ml-3 mt-2"><span class="fa fa-exclamation-circle"> </span> We won't collect any personal data about you and CRM customers. Any information collected will be used for business purposes only.</p>
                            </div>
                            <div class="button-container joforce-install-btn">
                                <a href="{$SITE_URL}index.php?module=Install&view=Index&mode=Step4"><input type="button" class="btn btn-large btn-default" value="{vtranslate('LBL_BACK','Install')}" {if $DB_CONNECTION_INFO['flag'] eq true} disabled= "disabled" {/if} name="back"/></a>
                                {if $DB_CONNECTION_INFO['flag'] eq true}
                                    <input type="button" class="btn btn-large btn-primary btn-next install_btn but" disabled value="{vtranslate('Install','Install')}" name="step7"/>
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
    <footer class="app-footer">
    <a href="https://www.joforce.com" target="_blank">
        <img class="pull-right" src='{$SITEURL}layouts/skins/images/JoForce-footer.png' width="30px">
    </a>
    <p>
        Copyright © Joforce. Thanks to <a class="joforce-link" href="https://joforce.com/credits" target="_blank"> open source projects.</a>
    </p>
    </footer>
</form>

<div id="progressIndicator" class="row main-container hide" style="color: #30302; background: #F4F4F4;">
    <div class="inner-container" style="width:100% !important;float:left !important;">
        <div class="col-sm-12">
            <div class="col-sm-10 offset-sm-1">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style='background: transparent !important; box-shadow: none;'>
                    <ol class="carousel-indicators" style="margin-left:40% !important;">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="4"></li>
	                    <li data-target="#carousel-example-generic" data-slide-to="5"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="width:450px;height:480px;">
                                        <img src="layouts/modules/Install/resources/images/zapier.png" style="width:100%;height:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3> Zapier Integration </h3>
                                    <p> As a part of Sales, Marketing and Support team, every one of us may work across different apps. When all these app based works are handled right from your CRM, would it be awesome?</p>
                                    <p> Connect with your favourite app that you use everyday and get things done right from Joforce via Zapier. Build Workflows and automate your day-to-day activities. </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="width:450px;height:480px;">
                                        <img src="layouts/modules/Install/resources/images/EmailPlus.png" style="width:100%;height:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3> Email</h3>
                                    <p> Bring email communication within CRM by integrating your email client to Joforce. Now you can send and receive emails from Joforce. Gives a look-&-feel, that you’re inside your email inbox. Send email from Joforce and get it automatically logged in your Joforce and email client as well.  </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="width:450px;height:480px;">
                                        <img src="layouts/modules/Install/resources/images/kanban.png" style="widht:100%;height:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3> Kanban view </h3>
                                    <p>Visually track your sales process to efficiently track, analyse, prioritize and close deals at the faster rate. It gives a better insight of all your deals in each stage of your pipeline. Once you’re successful in your sales activities, just drag & drop deals to navigate between different stages of your pipeline. </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="width:450px;height:480px;">
                                        <img src="layouts/modules/Install/resources/images/pdf.png" style="width:100%;height:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3> PDF Maker</h3>
                                    <p> Seamlessly design your own PDF Templates with PDF Maker. Individual template designs for Invoice, Quotes, Sales order and Purchase order. Easy to add your own company and product information. You can design your PDF with different page layouts in the way you want. </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="width:450px;height:480px;">
                                        <img src="layouts/modules/Install/resources/images/mobile.png" style="width:100%;height:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3> Mobile</h3>
                                    <p>When sales keeps your sales rep moving, shouldn’t your CRM move along with? Empower your reps to carry the CRM wherever they go. Turn your mobile phone as your CRM and get things on the fly. </p>
                                    <p>Your sales on the go</p>
                                    <p>Take actions from anywhere</p>
                                </div>
                            </div>
                        </div>
	                    <div class="carousel-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div style="width:450px;height:480px;">
                                        <img src="layouts/modules/Install/resources/images/google.png" style="width:100%;height:100%;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h3> Google Integration </h3>
                                    <p> Google Calendar - Never miss out any of your important appointments, track & organize all your activities from one place. </p>
                                    <p> Google contacts - All your contact info at one spot. Keep your Google & Joforce Contact info sync in just a click. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="inner-container mt-5">
        <div class="row">
            <div class="col-sm-12 welcome-div alignCenter">
                <h3>{vtranslate('LBL_INSTALLATION_IN_PROGRESS','Install')}...</h3><br>
                {* <div class="load-bar">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div> *}

                <div id="progress" style="border:1px solid #ddd;color:#8B0000;font-size:14px;height:20px;font-weight:900;text-align:center; border-radius: 10px;width:96%;display:none;margin-left:2%;">
                    <span id="progressbarValue" style="color:#000;z-index:99;position:absolute;"></span>
                    <div id="progressbar" style="width:0%;position:relative">
                        <div class="load-bar">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
{literal}
.load-bar {
  width: 100%;
  height: 20px;
}
.bar {
  content: "";
  display: inline;
  position: absolute;
  width: 0;
  height: 100%;
  left: 50%;
  text-align: center;
  border-radius : 10px;
}
.load-bar .bar:nth-child(1) {
  background-color: #da4733;
  animation: loading 3s linear infinite;
}
.load-bar .bar:nth-child(2) {
  background-color: #3b78e7;
  animation: loading 3s linear 1s infinite;
}
.load-bar .bar:nth-child(3) {
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

