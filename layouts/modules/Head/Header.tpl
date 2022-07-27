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
	<title> {if $PAGETITLE eq 'Head'} {vtranslate('Joforce', $QUALIFIED_MODULE)} {else} {vtranslate($PAGETITLE, $QUALIFIED_MODULE)} {/if} </title>
	<link rel="SHORTCUT ICON" href="{$SITEURL}layouts/skins/images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/app.css' media="screen"/>
        <link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/jquery/select2/select2.css'>
        <link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/select2-bootstrap/select2-bootstrap.css'>
        <link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/jo-icons/style.css' media="screen"/>
        <link rel="stylesheet" href="{$SITEURL}layouts/lib/jquery/floating-scroll/jquery.floatingscroll.css">

        <link type="text/css" rel="stylesheet" href="{$SITEURL}layouts/skins/style.css" media="screen"/>
        <link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/skins/custom.css' media="screen"/>

	<link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/skins/joforce-theme.css' media="screen"/>

	{if $PARENT_MODULE eq 'Settings'}
	    <link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/skins/settings.css' media="screen"/>
	{/if}
	
	<!-- newly added css - starts -->
	<link type='text/css' rel='stylesheet' href='{$SITEURL}layouts/lib/semantic/accordion.css' media="screen"/>
	<!-- newly added css -  ends -->

	<input type="hidden" id="inventoryModules" value={ZEND_JSON::encode($INVENTORY_MODULES)}>
        {foreach key=index item=cssModel from=$STYLES}
	    <link type="text/css" rel="{$cssModel->getRel()}" href="{$SITEURL}{vresource_url($cssModel->getHref())}" media="{$cssModel->getMedia()}" />
	{/foreach}

	{* For making pages - print friendly *}
	<style type="text/css">
            @media print {
                .noprint { display:none; }
	    }
	</style>
	<script type="text/javascript">var __pageCreationTime = (new Date()).getTime();</script>
        <script type="text/javascript">
	    var _META = { 'module': "{$MODULE}", view: "{$VIEW}", 'parent': "{$PARENT_MODULE}", 'notifier':"{$NOTIFIER_URL}" };
            {if $EXTENSION_MODULE}
                var _EXTENSIONMETA = { 'module': "{$EXTENSION_MODULE}", view: "{$EXTENSION_VIEW}"};
            {/if}
            var _USERMETA;
            {if $CURRENT_USER_MODEL}
                _USERMETA =  { 	'id' : "{$CURRENT_USER_MODEL->get('id')}", 
				'menustatus' : "{$CURRENT_USER_MODEL->get('leftpanelhide')}",
                              	'currency' : "{$USER_CURRENCY_SYMBOL}",
				'currencySymbolPlacement' : "{$CURRENT_USER_MODEL->get('currency_symbol_placement')}",
                          	'currencyGroupingPattern' : "{$CURRENT_USER_MODEL->get('currency_grouping_pattern')}",
				'truncateTrailingZeros' : "{$CURRENT_USER_MODEL->get('truncate_trailing_zeros')}"};
            {/if}
	</script>
	<script src="{$SITEURL}{vresource_url('layouts/lib/app-min.js')}"></script>
        <script src="{$SITEURL}{vresource_url('layouts/lib/jquery/custom.js')}"></script>
        <script type="text/javascript" src="{$SITEURL}layouts/lib/jquery/floating-scroll/jquery.floatingscroll.js"></script>
    </head>
    {assign var=CURRENT_USER_MODEL value=Users_Record_Model::getCurrentUserModel()}
	<body    data-skinpath="{Head_Theme::getBaseThemePath()}" data-language="{$LANGUAGE}" data-user-decimalseparator="{$CURRENT_USER_MODEL->get('currency_decimal_separator')}" data-user-dateformat="{$CURRENT_USER_MODEL->get('date_format')}"
          data-user-groupingseparator="{$CURRENT_USER_MODEL->get('currency_grouping_separator')}" data-user-numberofdecimals="{$CURRENT_USER_MODEL->get('no_of_currency_decimals')}" data-user-hourformat="{$CURRENT_USER_MODEL->get('hour_format')}"
          data-user-calendar-reminder-interval="{$CURRENT_USER_MODEL->getCurrentUserActivityReminderInSeconds()}" class="text-font-crimson">
            <input type="hidden" id="start_day" value="{$CURRENT_USER_MODEL->get('dayoftheweek')}" />
            <input type="hidden" id="joforce_site_url" value="{$SITEURL}"/>
	    <div id="page">
            	<div id="pjaxContainer" class="hide noprint"></div>
            	<div id="messageBar" class="hide"></div>
