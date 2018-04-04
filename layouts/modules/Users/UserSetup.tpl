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

        <link REL="SHORTCUT ICON" HREF="{$SITEURL}layouts/v7/skins/images/favicon.ico">
        <link rel="stylesheet" href="{$SITEURL}libraries/bootstrap/css/bootstrap.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="{$SITEURL}resources/styles.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="{$SITEURL}libraries/jquery/select2/select2.css" />
        <link rel="stylesheet" href="{$SITEURL}libraries/jquery/posabsolute-jQuery-Validation-Engine/css/validationEngine.jquery.css" />
        <link rel="stylesheet" href="{$SITEURL}layouts/skins/custom.css" />

        <script type="text/javascript" src="{$SITEURL}libraries/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{$SITEURL}libraries/bootstrap/js/bootstrap-tooltip.js"></script>
        <script type="text/javascript" src="{$SITEURL}libraries/jquery/select2/select2.min.js"></script>
        <script type="text/javascript" src="{$SITEURL}libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine.js" ></script>
        <script type="text/javascript" src="{$SITEURL}libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine-en.js" ></script>

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
            .modal-backdrop { opacity: 0.35; }
            .tooltip { z-index: 1055; }
            input, select, textarea { font-size: 14px; }
            .controls{
                margin-top: 30px;
            }
            {/literal}
        </style>
    </head>
    <body>
    <div class="container">
        <div class="modal-backdrop"></div>
        <form class="form" method="POST" action="index.php?module=Users&action=UserSetupSave">
            <input type="hidden" name="record" value="{$CURRENT_USER_MODEL->getId()}">
            <div class="modal on-board" {if false && $IS_FIRST_USER}style="width: 700px;"{/if}> {* FirstUser information gather - paused *}
                <div class="modal-header">
                    <h3 class="modal-title">{vtranslate('LBL_ALMOST_THERE', $MODULE)}</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="offset1 span6">
                            <label class="control-label"><strong>{vtranslate('Preferences', $MODULE)}</strong><span class="muted">{vtranslate('LBL_ALL_FIELDS_BELOW_ARE_REQUIRED', $MODULE)}</label>
                            {if $IS_FIRST_USER}
                                <div class="controls" id="currency_name_controls">
                                    <select name="currency_name" id="currency_name" placeholder="{vtranslate('LBL_BASE_CURRENCY', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_BASE_CURRENCY', $MODULE)}" class="inputElement validate[required]" style="width:250px;">
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
                                <select name="lang_name" id="lang_name" style="width:250px;" placeholder="{vtranslate('LBL_LANGUAGE', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_LANGUAGE', $MODULE)}" class="inputElement validate[required]">
                                    <option value=""></option>
                                    {foreach key=header item=language from=$LANGUAGES}
                                        <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('language')}selected{/if}>{$language|@getTranslatedString:$MODULE}</option>
                                    {/foreach}
                                </select>
                                <div style="padding-top:10px;"></div>
                            </div>
                            <div class="controls">
                                <select name="time_zone" id="time_zone" style="width:250px;" placeholder="{vtranslate('LBL_CHOOSE_TIMEZONE', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_TIMEZONE', $MODULE)}" class="inputElement validate[required]">
                                    <option value=""></option>
                                    {foreach key=header item=time_zone from=$TIME_ZONES}
                                        <option value="{$header}" {if $header eq $CURRENT_USER_MODEL->get('time_zone')}selected{/if}>{$time_zone|@getTranslatedString:$MODULE}</option>
                                    {/foreach}
                                </select>
                                <div style="padding-top:10px;"></div>
                            </div>
                            <div class="controls">
                                <select name="date_format" id="date_format" style="width:250px;" placeholder="{vtranslate('LBL_DATE_FORMAT', $MODULE)}" data-errormessage="{vtranslate('LBL_CHOOSE_DATE_FORMAT', $MODULE)}" class="inputElement validate[required]">
                                    <option value=""></option>
                                    <option value="dd-mm-yyyy" {if $CURRENT_USER_MODEL->get('date_format') eq "dd-mm-yyyy"} selected{/if}>dd-mm-yyyy</option>
                                    <option value="mm-dd-yyyy" {if $CURRENT_USER_MODEL->get('date_format') eq "mm-dd-yyyy"} selected{/if}>mm-dd-yyyy</option>
                                    <option value="yyyy-mm-dd" {if $CURRENT_USER_MODEL->get('date_format') eq "yyyy-mm-dd"} selected{/if}>yyyy-mm-dd</option>
                                </select>
                                <div style="padding-top:10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{vtranslate('LBL_GET_STARTED', $MODULE)}</button>
                </div>
            </div>
        </form>
    </div>
    </body>
    </html>
{/strip}
