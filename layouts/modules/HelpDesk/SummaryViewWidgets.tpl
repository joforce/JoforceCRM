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
{foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET']}
	{if ($DETAIL_VIEW_WIDGET->getLabel() eq 'Documents') }
		{assign var=DOCUMENT_WIDGET_MODEL value=$DETAIL_VIEW_WIDGET}
	{elseif ($DETAIL_VIEW_WIDGET->getLabel() eq 'ModComments')}
		{assign var=COMMENTS_WIDGET_MODEL value=$DETAIL_VIEW_WIDGET}
	{elseif ($DETAIL_VIEW_WIDGET->getLabel() eq 'LBL_UPDATES')}
		{assign var=UPDATES_WIDGET_MODEL value=$DETAIL_VIEW_WIDGET}
	{/if}
{/foreach}

<div class="left-block col-lg-6 col-md-6 col-sm-6 pull-left p0 ml10">
    {* Summary View Documents Widget*}
    {if $DOCUMENT_WIDGET_MODEL}
        <div class="summaryWidgetContainer ipad_summaryWidgetContainer_1">
            <div class="widgetContainer_documents" data-url="{$DOCUMENT_WIDGET_MODEL->getUrl()}" data-name="{$DOCUMENT_WIDGET_MODEL->getLabel()}">
                <div class="widget_header clearfix">
                    <input type="hidden" name="relatedModule" value="{$DOCUMENT_WIDGET_MODEL->get('linkName')}" />
                    <span class="toggleButton pull-left"><i class="fa fa-arrow-down"></i>&nbsp;&nbsp;</span>
                    <h4 class="display-inline-block pull-left">{vtranslate($DOCUMENT_WIDGET_MODEL->getLabel(),$MODULE_NAME)}</h4>

                    {if $DOCUMENT_WIDGET_MODEL->get('action')}
                        {assign var=PARENT_ID value=$RECORD->getId()}
                        <div class="pull-right">
                            <div class="dropdown">
                                <button type="button"  onclick="Documents_Index_Js.uploadTo('Head',{$PARENT_ID},'{$MODULE_NAME}')" class="btn btn-default dropdown-toggle" >
                                    <span class="fa fa-plus" title="{vtranslate('LBL_NEW_DOCUMENT', $MODULE_NAME)}"></span>&nbsp;{vtranslate('LBL_NEW_DOCUMENT', 'Documents')}
                                </button>
                                {* <ul class="dropdown-menu">
                                    <li class="dropdown-header"><i class="fa fa-upload"></i> {vtranslate('LBL_FILE_UPLOAD', 'Documents')}</li>
                                    <li id="HeadAction">
                                        <a href="javascript:Documents_Index_Js.uploadTo('U',{$PARENT_ID},'{$MODULE_NAME}')">
                                            <img style="  margin-top: -3px;margin-right: 4%;" title="JoForce" alt="JoForce" src="{$SITEURL}layouts/skins//images/JoForce.png">
                                            {vtranslate('LBL_TO_SERVICE', 'Documents', {vtranslate('LBL_JOFORCE', 'Documents')})}
                                        </a>
                                    </li>
                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-header"><i class="fa fa-link"></i> {vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', 'Documents')}</li>
                                    <li id="shareDocument"><a href="javascript:Documents_Index_Js.createDocument('E',{$PARENT_ID},'{$MODULE_NAME}')">&nbsp;<i class="fa fa-external-link"></i>&nbsp;&nbsp; {vtranslate('LBL_FROM_SERVICE', 'Documents', {vtranslate('LBL_FILE_URL', 'Documents')})}</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li id="createDocument"><a href="javascript:Documents_Index_Js.createDocument('W',{$PARENT_ID},'{$MODULE_NAME}')"><i class="fa fa-file-text"></i> {vtranslate('LBL_CREATE_NEW', 'Documents', {vtranslate('SINGLE_Documents', 'Documents')})}</a></li>
                                </ul> *}
                            </div>
                        </div>
                    {/if}
                </div>
                <div class="widget_contents">

                </div>
            </div>
        </div>
    {/if}
    {* Summary View Documents Widget Ends Here*}
	
	{* Detail Summary Widget View*}
		{if $DETAIL_SUMMARY_WIDGET}
			{include file='AddDetailViewSummaryWidget.tpl'|@vtemplate_path:$MODULE_NAME}
		{/if}
	{* Detail Summary Widget View Ends Here*}

    {* Summary View Related Activities Widget*}
        <div id="relatedActivities">
            {$RELATED_ACTIVITIES}
        </div>
    {* Summary View Related Activities Widget Ends Here*}
 
    
</div>

<div class="middle-block p0 col-lg-6 pull-left ml10">   
    {* Summary View Comments Widget*}
    {if $COMMENTS_WIDGET_MODEL}
        <div class="summaryWidgetContainer">
            <div class="widgetContainer_comments" data-url="{$COMMENTS_WIDGET_MODEL->getUrl()}" data-name="{$COMMENTS_WIDGET_MODEL->getLabel()}">
                <div class="widget_header">
                    <input type="hidden" name="relatedModule" value="{$COMMENTS_WIDGET_MODEL->get('linkName')}" />
                    <h4 class="display-inline-block">{vtranslate($COMMENTS_WIDGET_MODEL->getLabel(),$MODULE_NAME)}</h4>
                </div>
                <div class="widget_contents">
                </div>
            </div>
        </div>
    {/if}
    {* Summary View Comments Widget Ends Here*}

</div>
{/strip}
