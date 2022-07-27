{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
********************************************************************************/
-->*}
{strip}
{include file="SettingsMenuStart.tpl"|vtemplate_path:$QUALIFIED_MODULE}

<div class="bodyContents">
        <div>
	{if $VIEW eq 'Calendar'}
    {assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}
    <input id="recordId" type="hidden" value="{$RECORD->getId()}" />
    <div class="detailViewContainer">
        <div class="detailViewInfo userPreferences row-fluid mt0">
            <div class="details col-xs-12 joforce-bg">
	{else}
	{assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}
	<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
	<style>
		.main-container .content-area{
			background-color: #fbfbfb !important;
		}
	</style>
	<div class="detailViewContainer">
		<div class="detailViewTitle" id="prefPageHeader">
			<div class="col-lg-12 col-sm-12 col-xs-12 joforce-bg-prefer pull-left ml30 mt50">
				<div class="col-xs-9 col-sm-9 pull-left col-md-8">
					{assign var=IMAGE_DETAILS value=$RECORD->getImageDetails()}
					{foreach key=ITER item=IMAGE_INFO from=$IMAGE_DETAILS}
						{if !empty($IMAGE_INFO.path) && !empty($IMAGE_INFO.orgname)}
							<span class="logo col-xs-2">
								<img height="75px" width="75px" src="{$SITE_URL}{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" data-image-id="{$IMAGE_INFO.id}">
							</span>
						{/if}
					{/foreach}
					{if $IMAGE_DETAILS[0]['id'] eq null}
						<span class="logo col-xs-1 col-sm-2">
							<i class="fa fa-user" style="font-size: 75px"></i>
						</span>
					{/if}
					<span class="col-xs-9">
						<span id="myPrefHeading">
							<h3>{vtranslate('LBL_MY_PREFERENCES', $MODULE_NAME)} </h3>
						</span>
						<span>
							{vtranslate('LBL_USERDETAIL_INFO', $MODULE_NAME)}&nbsp;&nbsp;"<b>{$RECORD->getName()}</b>"
						</span>
					</span>
				</div>
				<div class="col-xs-3 col-sm-3 pull-right col-md-4">
					<div class="row detailViewButtoncontainer ">
						<div class="btn-group pull-right">
							{foreach item=DETAIL_VIEW_BASIC_LINK from=$DETAILVIEW_LINKS['DETAILVIEWPREFERENCE']}
								<button class="btn btn-default mr10 ml15"
										onclick="window.location.href='{$DETAIL_VIEW_BASIC_LINK->getUrl()}'">
									{vtranslate($DETAIL_VIEW_BASIC_LINK->getLabel(), $MODULE_NAME)}
								</button>
							{/foreach}
							{if $DETAILVIEW_LINKS['DETAILVIEW']|@count gt 0}
								<button class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
									{vtranslate('LBL_MORE', $MODULE)}
								</button>
								<ul class="dropdown-menu pull-right">
									{foreach item=DETAIL_VIEW_LINK from=$DETAILVIEW_LINKS['DETAILVIEW']}
										{if $DETAIL_VIEW_LINK->getLabel() eq "Delete"}
											{if $CURRENT_USER_MODEL->isAdminUser() && $CURRENT_USER_MODEL->getId() neq $RECORD->getId()}
												<li id="{$MODULE}_detailView_moreAction_{Head_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_LINK->getLabel())}">
													<a href="{$DETAIL_VIEW_LINK->getUrl()}">{vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE)}</a>
												</li>
											{/if}
										{else}
											<li id="{$MODULE}_detailView_moreAction_{Head_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_LINK->getLabel())}">
												<a href="{$DETAIL_VIEW_LINK->getUrl()}">{vtranslate($DETAIL_VIEW_LINK->getLabel(), $MODULE)}</a>
											</li>
										{/if}
									{/foreach}
								</ul>
							{/if}
						</div>
					</div>
				</div>
			</div>
			<div class="detailViewInfo userPreferences">
				<div class="details col-xs-12 joforce-bg-prefer-detail">
					<form id="detailView" data-name-fields='{ZEND_JSON::encode($MODULE_MODEL->getNameFields())}' method="POST">
						<div class="contents big_scr_user_Details_settings" style="margin-top: 14%;">
							<br>
		{/if}
						{/strip}
