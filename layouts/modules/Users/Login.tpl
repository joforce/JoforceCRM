{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Users/views/Login.php *}

{strip}
<link type="text/css" rel="stylesheet" href="layouts/modules/Users/resources/style.css" media="screen"/>
<div class=" row col-lg-12 col-md-12 col-sm-12 col-xs-12" style="background-image: url('layouts/resources/Images/loginbg.png');margin: 0 auto; background-size: 100% 100%;background-repeat: no-repeat;">
    <div class="loginback"></div>
    <div class="joforce-login">
	<div class="login_form">
		
	<div class="bg-soft">
		<div class="row">
			<div class="col-7">
				<div class="text-primary Skote">
					<h5 class="text-primary">Welcome Back !</h5>
					<p>Sign in to continue into Joforce CRM.</p>
				</div>
			</div>
			<div class="col-5 align-self-end">
			<!-- <img class="img-fluid user-logo" src="{$LOGINIMAGE->getLogoPath()}"> -->
			
				<img src="./layouts/resources/Images/profile-img.png" alt="" class="img-fluid">
			
			</div>
		</div>
	</div>

	<div>
	<div class="avatar-md profile-user-wid mb-4">
		<span class="avatar-title">
			<img src="./layouts/skins/images/JoForce-footer.png" alt="" height="34">
		</span>
	</div>

</div>

	    <div class="image-div">
		<!-- <img class="img-fluid user-logo" src="{$LOGINIMAGE->getLogoPath()}"> -->


	    </div>
	    <div class="error-div">
		<span class="{if !$ERROR}hide{/if} failureMessage" id="validationMessage">{$MESSAGE}</span>
		<span class="{if !$MAIL_STATUS}hide{/if} successMessage">{$MESSAGE}</span>
	    </div>

	    <div id="loginFormDiv">
		<form id ="login_form" class="form-horizontal"  method="POST" action="index.php">
		    <input type="hidden" name="module" value="Users"/>
		    <input type="hidden" name="action" value="Login"/>
		    <div class="group">
			<input id="username" type="text" name="username" placeholder="" value="">
			<span class="bar"></span>
			<label class="setting_label">Username</label>
		    </div>
		    <div class="group">
			<input id="password" type="password" name="password" placeholder="" value="">
			<span class="bar"></span>
			<label class="setting_label">Password</label>
		    </div>
		    <div class="group sign">
			<button type="submit" class="button buttonBlue forgot-submit-btn">Sign in</button><br>
			<a class="forgotPasswordLink joforce-forgetpass text-center"  style="color: #15c;">Forgot Password?</a>
		    </div>
			<div class="footclour">
				<img src="./layouts/resources/Images/logofoot.jpg"
			</div>
		</form>
	    </div>
		</div>

	    <div id="forgotPasswordDivs" class="hide" >
		<form id ="login_form" class="form-horizontal" action="index.php" method="POST">
		    <input type="hidden" name="module" value="Users" />
		    <input type="hidden" name="view" value="Login" />
		    <input type="hidden" name="mode" value="ForgotPassword" />
		    <div class="group">
			<input id="username" type="text" name="username" >
			<span class="bar"></span>
			<label class="setting_label">Username</label>
		    </div>
		    <div class="group">
			<input id="email" type="email" name="emailId" >
			<span class="bar"></span>
			<label class="setting_label">Email</label>
		    </div>
		    <div class="group sign">
			<button type="submit" class="button buttonBlue forgot-submit-btn">Submit</button><br>
			<a class="forgotPasswordLink pull-right mb20 mr50" style="color: #15c;"> Back </a>
		    </div>
		</form>
	    </div>
	</div>
    </div>
</div>

<div class="clearfix"></div>

<script>
jQuery(document).ready(function () {

		var validationMessage = jQuery('#validationMessage');
		var forgotPasswordDivs = jQuery('#forgotPasswordDivs');

		var loginFormDiv = jQuery('#loginFormDiv');
		loginFormDiv.find('#password').focus();

		loginFormDiv.find('a').click(function () {
		
			loginFormDiv.removeClass('show');
			forgotPasswordDivs.removeClass('hide');
			loginFormDiv.toggleClass('hide');
			forgotPasswordDivs.toggleClass('show');	
				validationMessage.addClass('hide');
				});

		forgotPasswordDivs.find('a').click(function () {
			
				loginFormDiv.removeClass('hide');
				forgotPasswordDivs.removeClass('show');
				loginFormDiv.toggleClass('show');
			
				forgotPasswordDivs.toggleClass('hide');
				validationMessage.addClass('hide');
				});

		loginFormDiv.find('button').on('click', function () {			
				var username = loginFormDiv.find('#username').val();
				var password = jQuery('#password').val();
				var result = true;
				var errorMessage = '';
				if (username === '') {
				errorMessage = 'Please enter valid username';
				result = false;
				} else if (password === '') {
				errorMessage = 'Please enter valid password';
				result = false;
				}
				if (errorMessage) {
				validationMessage.removeClass('hide').text(errorMessage);
				}
				return result;
				});

		forgotPasswordDivs.find('button').on('click', function () {	
				var username = jQuery('#forgotPasswordDivs #username').val();
				var email = jQuery('#email').val();

				var email1 = email.replace(/^\s+/, '').replace(/\s+$/, '');
				var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/;
				var illegalChars = /[\(\)\<\>\,\;\:\\\"\[\]]/;

				var result = true;
				var errorMessage = '';
				if (username === '') {
				errorMessage = 'Please enter valid username';
				result = false;
				} else if (!emailFilter.test(email1) || email == '') {
				errorMessage = 'Please enter valid email address';
				result = false;
				} else if (email.match(illegalChars)) {
				errorMessage = 'The email address contains illegal characters.';
				result = false;
				}
				if (errorMessage) {
					validationMessage.removeClass('hide').text(errorMessage);
				}
				return result;
		});
		jQuery('input').blur(function (e) {
				var currentElement = jQuery(e.currentTarget);
				if (currentElement.val()) {
				currentElement.addClass('used');
				} else {
				currentElement.removeClass('used');
				}
				});

		var ripples = jQuery('.ripples');
		ripples.on('click.Ripples', function (e) {
				jQuery(e.currentTarget).addClass('is-active');
				});

		ripples.on('animationend webkitAnimationEnd mozAnimationEnd oanimationend MSAnimationEnd', function (e) {
				jQuery(e.currentTarget).removeClass('is-active');
				});
		loginFormDiv.find('#username').focus();
});
</script>
{/strip}
