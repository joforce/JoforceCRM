{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
<div class="sidebar-menu">
    <div class="module-filters" id="module-filters">
        <div class="sidebar-container lists-menu-container">
<div class="quickLinksDiv">
        {foreach item=SIDEBARLINK from=$QUICK_LINKS['SIDEBARLINK']}
        {assign var=SIDE_LINK_URL value=decode_html($SIDEBARLINK->getUrl())}
                {assign var="EXPLODED_PARSE_URL" value=explode('?',$SIDE_LINK_URL)}
                {assign var="COUNT_OF_EXPLODED_URL" value=count($EXPLODED_PARSE_URL)}
                {if $COUNT_OF_EXPLODED_URL gt 1}
                        {assign var="EXPLODED_URL" value=$EXPLODED_PARSE_URL[$COUNT_OF_EXPLODED_URL-1]}
                {/if}
                {assign var="PARSE_URL" value=explode('&',$EXPLODED_URL)}
                {assign var="CURRENT_LINK_VIEW" value='view='|cat:$CURRENT_VIEW}
                {assign var="LINK_LIST_VIEW" value=in_array($CURRENT_LINK_VIEW,$PARSE_URL)}
                {assign var="CURRENT_MODULE_NAME" value='module='|cat:$MODULE}
                {assign var="IS_LINK_MODULE_NAME" value=in_array($CURRENT_MODULE_NAME,$PARSE_URL)}
                <p onclick="window.location.href='{$SIDEBARLINK->getUrl()}{if $SELECTED_MENU_CATEGORY}/{$SELECTED_MENU_CATEGORY}{/if}'" id="{$MODULE}_sideBar_link_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($SIDEBARLINK->getLabel())}"
                   class="{if $LINK_LIST_VIEW and $IS_LINK_MODULE_NAME}selectedQuickLink {else}unSelectedQuickLink{/if}"><a class="quickLinks" href="{$SIDEBARLINK->getUrl()}">
                                <strong>{vtranslate($SIDEBARLINK->getLabel(), $MODULE)}</strong>
                </a></p>
        {/foreach}
</div>

<div class="quickWidgetContainer accordion">
        {assign var=val value=1}
        {foreach item=SIDEBARWIDGET key=index from=$QUICK_LINKS['SIDEBARWIDGET']}
                <div class="quickWidget">
                        <div class="accordion-heading accordion-toggle quickWidgetHeader" data-target="#{$MODULE}_sideBar_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($SIDEBARWIDGET->getLabel())}"
                                        data-toggle="collapse" data-parent="#quickWidgets" data-label="{$SIDEBARWIDGET->getLabel()}"
                                        data-widget-url="{$SIDEBARWIDGET->getUrl()}" onclick='Vtiger_Index_Js.registerWidgetsEvents();'>
                                <span class="pull-left"><img class="imageElement" data-rightimage="{$SITEURL}{vimage_path('rightArrowWhite.png')}" data-downimage="{$SITEURL}{vimage_path('downArrowWhite.png')}" src="{$SITEURL}{vimage_path('rightArrowWhite.png')}" /></span>
                                <h5 class="title widgetTextOverflowEllipsis pull-right" title="{vtranslate($SIDEBARWIDGET->getLabel(), $MODULE)}">{vtranslate($SIDEBARWIDGET->getLabel(), $MODULE)}</h5>
                                <div class="loadingImg hide pull-right">
                                        <div class="loadingWidgetMsg"><strong>{vtranslate('LBL_LOADING_WIDGET', $MODULE)}</strong></div>
                                </div>
                                <div class="clearfix"></div>
                        </div>
                        <div class="widgetContainer accordion-body collapse" id="{$MODULE}_sideBar_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($SIDEBARWIDGET->getLabel())}" data-url="{$SIDEBARWIDGET->getUrl()}">
                        </div>
                </div>
        {/foreach}
</div>

        </div>
    </div>
</div>
