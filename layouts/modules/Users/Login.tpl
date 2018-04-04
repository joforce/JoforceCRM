{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Users/views/Login.php *}

{strip}
	<style>
		body {
			background: #fff;
			/*background: #2C3E50 url(layouts/resources/Images/joforce-login-bg.png);*/
			/*background: url(layouts/resources/Images/az-subtle.png);*/
			/*background-position: center;*/
			/*background-size: 150px 150px;*/
			/*width: 100%;
			height: 100%;*/
			/*background-repeat: repeat;*/
			/*background-color:#2C3E50;*/
			/*background: #3aa0d1;*/
    /*background: -webkit-linear-gradient(bottom, #2C4E50, #2C3E50);
    background: linear-gradient(to top, #2C4E50, #2C3E50);*/
			
		}
			.button {
			position: relative;
			display: inline-block;
			padding: 9px;
			margin: .3em 0 1em 0;
			width: 100%;
			vertical-align: middle;
			color: #fff;
			font-size: 16px;
			line-height: 20px;
			-webkit-font-smoothing: antialiased;
			text-align: center;
			letter-spacing: 1px;
			background: transparent;
			border: 0;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.button:focus {
			outline: 0;
		}
		.buttonBlue {
			/* background-image: linear-gradient(to bottom, #35aa47 0px, #35aa47 100%) */
			background: #1C7C54;
		}
		.forgotPasswordLink{
			color:#1C7C54 !important;
		}
		.bar {
			position: relative;
			display: block;
			width: 100%;
		}
		.bar:before, .bar:after {
			content: '';
			width: 0;
			bottom: 1px;
			position: absolute;
			height: 1px;
			background: #1C7C54;
			transition: all 0.2s ease;
		}
		.bar:before {
			left: 50%;
		}
		.bar:after {
			right: 50%;
		}

		#page{
			padding-top: 0px;
		}
		hr {
		    margin-top: 15px;
			background-color: #7C7C7C;
			height: 2px;
			border-width: 0;
		}
		h3, h4 {
			margin-top: 0px;
		}
		hgroup {
			text-align:center;
			margin-top: 4em;
		}
		input {
			font-size: 16px;
			padding: 10px 10px 10px 0px;
			-webkit-appearance: none;
			display: block;
			color: #636363;
			width: 100%;
			border: none;
			border-radius: 0;
			border-bottom: 1px solid #757575;
			background: none;
		}
		input:focus {
			outline: none;
		}
		label {
			font-size: 16px;
			font-weight: normal;
			position: absolute;
			pointer-events: none;
			left: 0px;
			top: 10px;
			transition: all 0.5s ease;
		}
		input:focus ~ label, input.used ~ label {
			top: -20px;
			transform: scale(.75);
			left: -12px;
			font-size: 18px;
		}
		input:focus ~ .bar:before, input:focus ~ .bar:after {
			width: 50%;
		}
		#page {
			padding-top: 0;
		}
		.widgetHeight {
			/*height: 410px;
			margin-top: 20px !important;*/
			min-height: 100vh;

		}
		.loginDiv {
			/*width: 380px;
			*/
			/*box-shadow: 0 0 10px gray;*/
			border-radius: 4px;
			/*background-color: #FFFFFF;*/
			/*margin: 0 auto;*/

		}
		.marketingDiv {
			color: #303030;
			padding: 10px 20px;
		}
		.separatorDiv {
			background-color: #7C7C7C;
			width: 2px;
			height: 460px;
			margin-left: 20px;
		}
		.user-logo {
			height: 150px;
			margin: 0 auto;
			padding-top: 40px;
			padding-bottom: 20px;
		}
		.blockLink {
			border: 1px solid #303030;
			padding: 3px 5px;
		}
		.group {
			position: relative;
			margin: 20px 20px 40px;
		}
		.failureMessage {
			color: red;
			display: block;
			text-align: center;
			padding: 0px 0px 10px;
		}
		.successMessage {
			color: green;
			display: block;
			text-align: center;
			padding: 0px 0px 10px;
		}
		.inActiveImgDiv {
			padding: 5px;
			text-align: center;
			margin: 30px 0px;
		}
		.app-footer p {
			bottom:0px;
			position:fixed;
		}
		.footer {
			background-color: #fbfbfb;
			height:26px;
		}
			
		
		.ripples {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			overflow: hidden;
			background: transparent;
		}

		//Animations
		@keyframes inputHighlighter {
			from {
				background: #4a89dc;
			}
			to 	{
				width: 0;
				background: transparent;
			}
		}
		@keyframes ripples {
			0% {
				opacity: 0;
			}
			25% {
				opacity: 1;
			}
			100% {
				width: 200%;
				padding-bottom: 200%;
				opacity: 0;
			}
		}
		.nopadding {
		   padding: 0 !important;
		   margin: 0 !important;
		}
	
.joforce-login{
	margin-top:15%;
	background-color: none;
	border-radius: 4px;
	padding-bottom:5px;
}
.joforce-forgetpass{
	font-size:14px;
}


/*  jan 20   */

.text-white{
	color:#fff;
}

.vertical-divider{
	border-right: 1px solid #ededed;
	padding: 10px 3px;
	height:50vh;
	margin-top: 25%;
}

.heading-1, .heading-2, .heading-3, .heading-4{
	font-size: 4.3em;
}

.heading-1{
	animation: heading-animation-1 2s 0.5s;
	opacity: 0;
	position: absolute;
}

.heading-2{
	animation: heading-animation-1 2s 2.5s;
	opacity: 0;
	position: absolute;
}

.heading-3{
	animation: heading-animation-1 2s 4.75s;
	opacity: 0;
	position: absolute;
}

.heading-4{
	animation:heading-animation-2 1s 6.55s;
	opacity: 0;
	position: absolute;
}

.header-opacity .heading-4{
	opacity: 1 !important;
}

.page-content{
	width:95%;
	padding:40px;
	position:absolute;
	top:15%;
}

.page-header-section{
	position:relative;
	margin-top:20%;
}

.fixed-text{
	/*position: absolute;*/
	padding-right: 40px;
	margin-top:65%;	
}

@media screen and (min-width: 1400px){
	.fixed-text{
		margin-top: 55%;
	}
	.page-content{
		top:30%;
	}
	.joforce-login{
		margin-top: 20%;
	}
}

.fixed-text p{
	font-size: 16px;
}

@keyframes heading-animation-1 {
  0% {
    transform: translateY(100px);
    opacity: 0; }
  20%, 80% {
    transform: translateY(0);
    opacity: 1; }
  100% {
    transform: translateY(-100px);
    opacity: 0; } }

@keyframes heading-animation-2 {
  0% {
    transform: translateY(100px);
    opacity: 0; }
  100% {
  	transform: translateY(0);
    opacity: 1; } }

.carousel-inner .item .item-img img{
	width:150px;
	text-align: center;
}

.carousel-inner .item .item-img{
	text-align: center;
}

.carousel-inner .item .item-content{
	margin-top:20px;
	text-align: center;
}

.carousel-inner .item .item-content p{
	line-height: 25px;
}

.carousel-indicators li, .carousel-indicators li.active{
	margin:0px 10px;
}

@media screen and (max-width: 800px){
	.heading-1, .heading-2, .heading-3, .heading-4{
		font-size: 3.5em;
	}
	.login-note{
		display: none;
	}
	.page-content{
		display: none;
	}
}


@media screen and (min-width: 800px) and (max-width: 1100px){
	.heading-1, .heading-2, .heading-3, .heading-4{
		font-size: 3.5em;
	}	
}

</style>


	<div class="clearfix"></div>

  <div class="">

  	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 login-note">

  	<div class="page-content">
  	
  	<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style='background: transparent !important; box-shadow: none;'>
  			<ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                <li data-target="#carousel-example-generic" data-slide-to="3"></li>
            </ol>
            <div class="carousel-inner">
            	<div class="item active">
            		<div class="item-img">
            			<img src="{$SITEURL}layouts/modules/Users/resources/images/google.png">
            		</div>
            		<div class="item-content">
			    <h3> Google Integration </h3>
			    <p>Integrate JoForce with your Google Calendar & Contacts for efficient tracking and organizing of all your calendar activities & contacts info in one place.</p>
            		</div>
            	</div>
            	<div class="item">
            		<div class="item-img">
            			<img src="{$SITEURL}layouts/modules/Users/resources/images/kanban.png">
            		</div>
            		<div class="item-content">
				<h3> Kanban view </h3>
				<p>Efficiently track, analyse, prioritize your sales activities and close deals at the faster rate. It gives a better insight of all your deals in each stage of your pipeline.</p>
            		</div>
            	</div>
            	<div class="item">
            		<div class="item-img">
            			<img src="{$SITEURL}layouts/modules/Users/resources/images/zapier.png">
            		</div>
            		<div class="item-content">
				<h3> Zapier Integration </h3>
				<p>Connect with your favourite app that you use everyday and get things done right from JoForce via Zapier. Build Workflows and automate your day-to-day activities.</p>
            		</div>
            	</div>
            	<div class="item">
            		<div class="item-img">
            			<img src="{$SITEURL}layouts/modules/Users/resources/images/pdf.png">
            		</div>
            		<div class="item-content">
				<h3> PDF Maker</h3>
				<p>Seamlessly design your own PDF Templates with PDF Maker. Individual template designs with different page layouts for Invoice, Quotes, Sales order and Purchase order.</p>
            		</div>
            	</div>
            </div>
  	</div>

  	</div>
  	<div class="vertical-divider"></div>
  	</div>


	<div class="loginDiv widgetHeight  col-sm-6 col-md-6 col-sm-6 col-xs-12" style="">
			<div class="joforce-login col-lg-8 col-lg-offset-2">
			
				<img class="img-responsive user-logo" src="layouts/resources/Images/JoForce-Logo.png">
				<div>
					<span class="{if !$ERROR}hide{/if} failureMessage" id="validationMessage">{$MESSAGE}</span>
					<span class="{if !$MAIL_STATUS}hide{/if} successMessage">{$MESSAGE}</span>
				</div>

				<div id="loginFormDiv">
					<form class="form-horizontal" method="POST" action="index.php">
						<input type="hidden" name="module" value="Users"/>
						<input type="hidden" name="action" value="Login"/>
						<div class="group">
							<input id="username" type="text" name="username" placeholder="Username" value="admin">
							<span class="bar"></span>
							<label>Username</label>
						</div>
						<div class="group">
							<input id="password" type="password" name="password" placeholder="Password" value="admin">
							<span class="bar"></span>
							<label>Password</label>
						</div>
						<div class="group">
							<button type="submit" class="button buttonBlue">Sign in</button><br>
							<a class="forgotPasswordLink joforce-forgetpass text-center" style="color: #15c;">Forgot Password?</a>
						</div>
					</form>
				</div>

				<div id="forgotPasswordDiv" class="hide">
					<form class="form-horizontal" action="forgotPassword.php" method="POST">
						<div class="group">
							<input id="username" type="text" name="username" placeholder="Username" >
							<span class="bar"></span>
							<label>Username</label>
						</div>
						<div class="group">
							<input id="email" type="email" name="emailId" placeholder="Email" >
							<span class="bar"></span>
							<label>Email</label>
						</div>
						<div class="group">
							<button type="submit" class="button buttonBlue forgot-submit-btn">Submit</button><br>
							<span>Please enter details and submit<a class="forgotPasswordLink pull-right" style="color: #15c;">Back</a></span>
						</div>
					</form>
				</div>


			</div>

			</div>

    </div>

 <div class="clearfix"></div>
		<script>
			jQuery(document).ready(function () {

				setTimeout(function(){
			       jQuery(".page-header-section").addClass("header-opacity");
			    }, 7000);

				var validationMessage = jQuery('#validationMessage');
				var forgotPasswordDiv = jQuery('#forgotPasswordDiv');

				var loginFormDiv = jQuery('#loginFormDiv');
				loginFormDiv.find('#password').focus();

				loginFormDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					forgotPasswordDiv.toggleClass('hide');
					validationMessage.addClass('hide');
				});

				forgotPasswordDiv.find('a').click(function () {
					loginFormDiv.toggleClass('hide');
					forgotPasswordDiv.toggleClass('hide');
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

				forgotPasswordDiv.find('button').on('click', function () {
					var username = jQuery('#forgotPasswordDiv #username').val();
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
