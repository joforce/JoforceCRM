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
			<title>{vtranslate($PAGETITLE, $MODULE_NAME)}</title>
			<link rel="SHORTCUT ICON" href="layouts/skins/images/favicon.ico">
			<meta name="viewport" content="width=device-width, initial-scale=1.0" />
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<link type='text/css' rel='stylesheet' href='layouts/lib/app.css'/>
			{* <link type='text/css' rel='stylesheet' href='layouts/lib/todc/css/todc-bootstrap.min.css'/> *}
			<link type='text/css' rel='stylesheet' href='layouts/lib/font-awesome/css/font-awesome.min.css'/>
			<link type='text/css' rel='stylesheet' href='layouts/lib/jquery/select2/select2.css'/>
			<link type='text/css' rel='stylesheet' href='libraries/bootstrap/js/eternicode-bootstrap-datepicker/css/datepicker3.css'/>
			<link type='text/css' rel='stylesheet' href='layouts/lib/jquery/jquery-ui-1.11.3.custom/jquery-ui.css'/>
			<link type='text/css' rel='stylesheet' href='layouts/lib/jo-icons/style.css'/>

			<link type="text/css" rel="stylesheet" href="layouts/skins/style.css" media="screen" />
			<link type="text/css" rel="stylesheet" href="layouts/skins/custom.css" media="screen" />
			<link type="text/css" rel="stylesheet" href="layouts/modules/Install/resources/css/custom.css" media="screen" />
			<link type='text/css' rel='stylesheet' href='layouts/lib/semantic/accordion.css'/>

			{foreach key=index item=cssModel from=$STYLES}
				<link type="text/css" rel="{$cssModel->getRel()}" href="{vresource_url($cssModel->getHref())}" media="{$cssModel->getMedia()}" />
			{/foreach}

			{* For making pages - print friendly *}
			<style type="text/css">
				@media print {
				.noprint { display:none; }
			}
			</style>

			<script type='text/javascript' src='layouts/lib/jquery/jquery.min.js'></script>
			<script type='text/javascript' src='layouts/lib/semantic/accordion.js'></script>

			
            <script type="text/javascript" src="layouts/lib/app-min.js"></script>
			<script type="text/javascript">
				var _META = { 'module': "{$MODULE}", view: "{$VIEW}", 'parent': "{$PARENT_MODULE}" };
				{if $EXTENSION_MODULE}
					var _EXTENSIONMETA = { 'module': "{$EXTENSION_MODULE}", view: "{$EXTENSION_VIEW}"};
				{/if}
				var _USERMETA;
				{if $CURRENT_USER_MODEL}
					_USERMETA =  { 'id' : "{$CURRENT_USER_MODEL->get('id')}", 'menustatus' : "{$CURRENT_USER_MODEL->get('leftpanelhide')}" };
				{/if}
			</script>
		</head>
		 {assign var=CURRENT_USER_MODEL value=Users_Record_Model::getCurrentUserModel()}
		<body style="font-size: 13px !important;" data-skinpath="{Head_Theme::getBaseThemePath()}" data-language="{$LANGUAGE}" data-user-decimalseparator="{$CURRENT_USER_MODEL->get('currency_decimal_separator')}" data-user-dateformat="{$CURRENT_USER_MODEL->get('date_format')}"
			data-user-groupingseparator="{$CURRENT_USER_MODEL->get('currency_grouping_separator')}" data-user-numberofdecimals="{$CURRENT_USER_MODEL->get('no_of_currency_decimals')}">
			<div id="page"class="inst-page" style="height:100%">
				<div id="pjaxContainer" class="hide noprint"></div>
{/strip}
