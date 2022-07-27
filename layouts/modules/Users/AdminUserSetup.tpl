{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}

{strip}
	<!DOCTYPE html>
	<html>
	<head>
		<title>Joforce</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<link REL="SHORTCUT ICON" href="layouts/skins/images/favicon.ico">
		<link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="libraries/resources/styles.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="libraries/jquery/select2/select2.css" />
		<link rel="stylesheet" href="libraries/jquery/posabsolute-jQuery-Validation-Engine/css/validationEngine.jquery.css" />
		<link rel="stylesheet" href="layouts/skins/custom.css">

		<script type="text/javascript" src="libraries/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-tooltip.js"></script>
		<script type="text/javascript" src="libraries/jquery/select2/select2.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine.js" ></script>
		<script type="text/javascript" src="libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine-en.js" ></script>

		<script type="text/javascript">
            {literal}
            jQuery(function(){
                jQuery('select').select2({blurOnChange:true});
                jQuery('[rel="tooltip"]').tooltip();
                jQuery('form').validationEngine({
                    prettySelect: true,
                    usePrefix: 's2id_',
                    autoPositionUpdate: true,
                    promptPosition : "topLeft",
                    showOneMessage: true
                });
                jQuery('#currency_name_controls').mouseenter(function() {
                    jQuery('#currency_name_tooltip').tooltip('show');
                });
                jQuery('#currency_name_controls').mouseleave(function() {
                    jQuery('#currency_name_tooltip').tooltip('hide');
                });
            });
            {/literal}
		</script>
		<style type="text/css">
			{literal}
			body { background: #ffffff url('layouts/skins/images/wizard_screen.png') no-repeat center top; background-size: 100%; font-size: 14px; }
			.modal-backdrop { opacity: 0.85; }
			.tooltip { z-index: 1055; }
			input, select, textarea { font-size: 14px; }
			{/literal}
		</style>
	</head>
	<body>
	<div class="container">
		<div class="modal-backdrop"></div>
			<input type="hidden" name="record" value="{$CURRENT_USER_MODEL->getId()}">
            <input type="hidden" id="current-step" value="1">
			<div class="modal on-board" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title step-1"> Welcome to Joforce! </h2> 
                        <h3 class="modal-title step-2 hide">Step 2</h3>
                        <h3 class="modal-title step-3 hide">Step 3</h3>
                    </div>
                    <div class="modal-body">
                        <div class="notification-section"></div>
					    <div class="step-1">
					    <div class="modal-detail">
						    <form class="form form-horizontal" id="updateCompanyDetailsForm" method="post" action="index.php" enctype="multipart/form-data">
							    <input type="hidden" name="module" value="Head" />
    							<input type="hidden" name="parent" value="Settings" />
	    						<input type="hidden" name="action" value="CompanyDetailsSave" />
		    					<div class="form-group companydetailsedit">
			    					<label class="col-sm-2 fieldLabel col-form-label"> {vtranslate('LBL_COMPANY_LOGO',$QUALIFIED_MODULE)}</label>
				    				<div class="fieldValue col-sm-5" >
					    				<div class="company-logo-content">
						    				<img src="{$COMPANY_DETAILS_MODULE_MODEL->getLogoPath()}" class="alignMiddle company_logo" style="max-width:200px; max-height: 100px;"/>
							    			<br><hr>
								    		<input type="file" name="logo" id="logoFile" />
									    </div>
									    <br>
									    <div class="alert alert-info" >
                                            {vtranslate('LBL_LOGO_RECOMMENDED_MESSAGE',$QUALIFIED_MODULE)}
									    </div>
								    </div>
							    </div>
                                {foreach from=$COMPANY_DETAILS_MODULE_MODEL->getFields() item=FIELD_TYPE key=FIELD}
                                    {if $FIELD neq 'logoname' && $FIELD neq 'logo' }
									<div class="form-group companydetailsedit" style="clear:both;">
										<label class="fieldLabel col-form-label" style="margin: 0px 0px 20px 40px;">
                                            {vtranslate($FIELD,$QUALIFIED_MODULE)}{if $FIELD eq 'organizationname'}&nbsp;<span class="red-border"></span>{/if}
										</label>
										<div class="fieldValue col-sm-5">
                                            {if $FIELD eq 'address'}
												<textarea class="form-control inputElement resize-vertical" rows="2" name="{$FIELD}">{$COMPANY_DETAILS_MODULE_MODEL->get($FIELD)}</textarea>
                                            {else if $FIELD eq 'website'}
												<input type="text" class="inputElement" data-rule-url="true" name="{$FIELD}" value="{$COMPANY_DETAILS_MODULE_MODEL->get($FIELD)}"/>
                                            {else}
												<input type="text" {if $FIELD eq 'organizationname'} data-rule-required="true" {/if} class="inputElement" name="{$FIELD}" value="{$COMPANY_DETAILS_MODULE_MODEL->get($FIELD)}"/>
                                            {/if}
										</div>
									</div>
                                    {/if}
                                {/foreach}
						    </form>
					    </div>
                        </div>
                        <div class="step-2 hide">
                        <div class="modal-detail">
                            <form id="OutgoingServerForm" method="POST">
                                <input type="hidden" name="default" value="false" />
                                <input type="hidden" name="server_port" value="0" />
                                <input type="hidden" name="server_type" value="email"/>
                                <input type="hidden" name="id" value="{$OUTGOING_SERVER_MODEL->get('id')}"/>
                                <div class="blockData">
                                    <br>
                                    <div class="block">
                                        <div>
                                            <h4>{vtranslate('LBL_MAIL_SERVER_SMTP', $QUALIFIED_MODULE)}</h4>
                                        </div>
                                        <hr>
                                        <table class="table editview-table no-border">
                                            <tbody>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_SERVER_TYPE', $QUALIFIED_MODULE)}</label></td>
                                                <td class="{$WIDTHTYPE} fieldValue">
                                                    <div class=" col-lg-6 col-md-6 col-sm-12">
                                                        <select class="select2 inputElement col-lg-12 col-md-12 col-lg-12" name="serverType">
                                                            <option value="">{vtranslate('LBL_SELECT_OPTION','Head')}</option>
                                                            <option value="{"ssl://smtp.gmail.com:465"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "ssl://smtp.gmail.com:465"} selected {/if}>{vtranslate('LBL_GMAIL', $QUALIFIED_MODULE)} </option>
                                                            <option value="{"smtp.live.com"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "smtp.live.com"} selected {/if}>{vtranslate('LBL_HOTMAIL', $QUALIFIED_MODULE)}</option>
                                                            <option value="{"smtp-mail.outlook.com"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "smtp.live.com"} selected {/if}>{vtranslate('LBL_OFFICE365', $QUALIFIED_MODULE)}</option>
                                                            <option value="{"smtp.mail.yahoo.com"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "smtp.mail.yahoo.com"} selected {/if}>{vtranslate('LBL_YAHOO', $QUALIFIED_MODULE)}</option>
                                                            <option value="">{vtranslate('LBL_OTHERS', $QUALIFIED_MODULE)}</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_SERVER_NAME', $QUALIFIED_MODULE)}</label>&nbsp;<span class="red-border"></span></td>
                                                <td class="{$WIDTHTYPE} fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="text" class="inputElement" name="server" data-rule-required="true" value="{$OUTGOING_SERVER_MODEL->get('server')}" ></div></td></tr>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_USER_NAME', $QUALIFIED_MODULE)}</label></td>
                                                <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-6 col-sm-12"><input type="text" class="inputElement" name="server_username" value="{$OUTGOING_SERVER_MODEL->get('server_username')}" ></div></td></tr>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_PASSWORD', $QUALIFIED_MODULE)}</label></td>
                                                <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-6 col-sm-12"><input type="password" class="inputElement" name="server_password" value="{$OUTGOING_SERVER_MODEL->get('server_password')}" ></div></td></tr>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_FROM_EMAIL', $QUALIFIED_MODULE)}</label></td>
                                                <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-6 col-sm-12"><input type="text" class="inputElement" name="from_email_field" data-rule-email="true" data-rule-illegal="true" value="{$OUTGOING_SERVER_MODEL->get('from_email_field')}" ></div> </td>
                                            </tr>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel">&nbsp;</td>
                                                <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-12 col-md-12 col-sm-12"><div class="alert alert-info alert-mini">{vtranslate('LBL_OUTGOING_SERVER_FROM_FIELD', $QUALIFIED_MODULE)}</div></div></td>
                                            </tr>
                                            <tr>
                                                <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_REQUIRES_AUTHENTICATION', $QUALIFIED_MODULE)}</label></td>
                                                <td class="{$WIDTHTYPE}" style="border-left: none;"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="checkbox" name="smtp_auth" {if $OUTGOING_SERVER_MODEL->isSmtpAuthEnabled()}checked{/if} ></div></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                </div>
                            </form>
                            </div>
                        </div>
                        <div class="step-3 hide">
                            <div class="modal-detail">
                            <form class="form user_setup" method="POST" action="index.php?module=Users&action=UserSetupSave">
                                <input type="hidden" name="record" value="{$CURRENT_USER_MODEL->getId()}">
                            <div class="row">
                                <div class="span4">
                                    <label class="col-form-label"><strong>{vtranslate('Preferences', $MODULE)}</strong><span class="muted">{vtranslate('LBL_ALL_FIELDS_BELOW_ARE_REQUIRED', $MODULE)}</label>
                                    {if $IS_FIRST_USER}
                                        <div class="controls" id="currency_name_controls">
                                            <select name="currency_name" id="currency_name" placeholder="{vtranslate('LBL_BASE_CURRENCY', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_BASE_CURRENCY', $MODULE)}" class="validate[required] select2 inputElement" style="width:250px;">
                                                <option value=""></option>
                                                {foreach key=header item=currency from=$CURRENCIES}
                                                    <!--Open source fix to select user preferred currency during installation -->
                                                    <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('currency_name')}selected{/if}>{$header|@getTranslatedCurrencyString}({$currency.1})</option>
                                                {/foreach}
                                            </select>&nbsp;
                                            <span rel="tooltip" title="{vtranslate('LBL_OPERATING_CURRENCY', $MODULE)}" id="currency_name_tooltip" class="icon-info-sign"></span>
                                            <div style="padding-top:10px;"></div>
                                        </div>
                                    {/if}
                                    <div class="controls">
                                        <select name="lang_name" id="lang_name" style="width:250px;" placeholder="{vtranslate('LBL_LANGUAGE', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_LANGUAGE', $MODULE)}" class="validate[required] select2 inputElement">
                                            <option value=""></option>
                                            {foreach key=header item=language from=$LANGUAGES}
                                                <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('language')}selected{/if}>{$language|@getTranslatedString:$MODULE}</option>
                                            {/foreach}
                                        </select>
                                        <div style="padding-top:10px;"></div>
                                    </div>
                                    <div class="controls">
                                        <select name="time_zone" id="time_zone" style="width:250px;" placeholder="{vtranslate('LBL_CHOOSE_TIMEZONE', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_TIMEZONE', $MODULE)}" class="validate[required] select2 inputElement">
                                            <option value=""></option>
                                            {foreach key=header item=time_zone from=$TIME_ZONES}
                                                <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('time_zone')}selected{/if}>{$time_zone|@getTranslatedString:$MODULE}</option>
                                            {/foreach}
                                        </select>
                                        <div style="padding-top:10px;"></div>
                                    </div>
                                    <div class="controls">
                                        <select name="date_format" id="date_format" style="width:250px;" placeholder="{vtranslate('LBL_DATE_FORMAT', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_DATE_FORMAT', $MODULE)}" class="validate[required] select2 inputElement">
                                            <option value=""></option>
                                            <option value="dd-mm-yyyy" {if $CURRENT_USER_MODEL->get('date_format') eq "dd-mm-yyyy"} selected{/if}>dd-mm-yyyy</option>
                                            <option value="mm-dd-yyyy" {if $CURRENT_USER_MODEL->get('date_format') eq "mm-dd-yyyy"} selected{/if}>mm-dd-yyyy</option>
                                            <option value="yyyy-mm-dd" {if $CURRENT_USER_MODEL->get('date_format') eq "yyyy-mm-dd"} selected{/if}>yyyy-mm-dd</option>
                                        </select>
                                        <div style="padding-top:10px;"></div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            </div>
                        </div>
                    </div>
				
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary form-submit">{vtranslate('Next', $MODULE)}</button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-secondary hide skip-the-form">{vtranslate('Skip', $MODULE)}</button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-secondary hide go-back">{vtranslate('Back', $MODULE)}</button>&nbsp;&nbsp;
                </div>
                </div>
			</div>
	    </div>
	</body>

	<script type="text/javascript">
        {literal}
        $(document).ready(function()	{

            // Skip the form and move to next
            $('.skip-the-form').on('click', function()  {
                var current_step = $('#current-step').val();
                var next_step = parseInt(current_step) + 1;
                // Update the current step value
                $('#current-step').val(next_step);

                $('.step-'+current_step).hide();
                $('.step-'+next_step).show();

                if(next_step == 3) {
                    $('.skip-the-form').hide();
                }
            });

            // Go to previous form
            $('.go-back').on('click', function()    {
                var current_step = $('#current-step').val();
                var previous_step = parseInt(current_step) - 1;
                // Update the current step
                $('#current-step').val(previous_step);

                $('.step-'+current_step).hide();
                $('.step-'+previous_step).show();

                if(previous_step == 1) {
                    $('.go-back').hide();
                    $('.skip-the-form').hide();
                }
                else if(previous_step == 2) {
                    $('.skip-the-form').show();
                }
            });
	  
	    $('[name="serverType"]').on('change', function()  {
		var Servertype = $('[name="serverType"]').val();
 		var Server = $('[name="server"]').val(Servertype);
	    });

            // Submit the form
            $('.form-submit').on('click', function()    {
                var current_step = $('#current-step').val();
                if(current_step == 1) {
                    $('#updateCompanyDetailsForm').submit();
                }
                else if(current_step == 2)  {
                    $('#OutgoingServerForm').submit();
		    $('.form-submit').attr('disabled','disabled');
                }
                else if(current_step == 3)  {
                    $('.user_setup').submit();
                }
            });

            // Save outgoing server
            $('#OutgoingServerForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'index.php?module=Head&action=OutgoingServerSaveAjax&parent=Settings',
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        var current_step = $('#current-step').val();
                        if(data.success == true)    {
                            $('.notification-section').html('');
                            var next_step = parseInt(current_step) + 1;
                            // Update the current step
                            $('#current-step').val(next_step);

                            $('.step-'+current_step).hide();
			    $('.form-submit').removeAttr('disabled');
                            $('.step-'+next_step).show();

                            // Show back button
                            $('.go-back').show();
                            $('.skip-the-form').hide();
                        }
                        else    {
                            // Show message
			    $('.form-submit').removeAttr('disabled');
                            $('.notification-section').html("<div class='alert alert-danger'>"+data.error.message+"</div>");
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

			// Save company details
	        $('#updateCompanyDetailsForm').submit(function(e) {
    	        e.preventDefault();
        	    var formData = new FormData(this);
            	$.ajax({
                	url: 'index.php?module=Head&action=CompanyDetailsSave&parent=Settings',
	                type: 'POST',
    	            data: formData,
        	        success: function (data) {
                	    var current_step = $('#current-step').val();
                	    if(data.success == true)    {
                	        $('.notification-section').html('');
                	        var next_step = parseInt(current_step) + 1;
                            // Update the current step
                            $('#current-step').val(next_step);

                	        // Update the logo
                            $('.company_logo').attr('src', data.result.res);
                            $('.step-'+current_step).hide();
                            $('.step-'+next_step).show();

                            // Show back button
                            $('.go-back').show();
                            $('.skip-the-form').show();
                        }
                        else    {
                	        // Show message
                            $('.notification-section').html("<div class='alert alert-danger'>"+data.error.message+"</div>");
                        }
                	},
	                cache: false,
    	            contentType: false,
        	        processData: false
            	});
	        });
        });
        {/literal}
	</script>

	</html>
{/strip}
