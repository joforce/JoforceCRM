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
{assign var=MODULE value=$MODULE_NAME}
    <div class="left-block col-lg-6 col-md-6 col-sm-6 pull-left ml10 p0 {if in_array($MODULE,array('PurchaseOrder','SalesOrder','Invoice','Quotes'))} ms_width_invoice_page {/if}">
        {foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET'] name=count}
            {if $smarty.foreach.count.index % 2 == 0}
                <div class="summaryWidgetContainer ipad_summaryWidgetContainer">
                    <div class="widgetContainer_{$smarty.foreach.count.index}" data-url="{$DETAIL_VIEW_WIDGET->getUrl()}" data-name="{$DETAIL_VIEW_WIDGET->getLabel()}">
                        <div class="widget_header clearfix">
                            <input type="hidden" name="relatedModule" value="{$DETAIL_VIEW_WIDGET->get('linkName')}" />
                            <span class="toggleButton pull-left"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;</span>
                            <h4 class="display-inline-block pull-left">{vtranslate($DETAIL_VIEW_WIDGET->getLabel(),$MODULE_NAME)}</h4>

                            {if $DETAIL_VIEW_WIDGET->get('action')}
                                <div class="pull-right">
                                    <button class="btn addButton btn-default btn-sm createRecord" type="button" data-url="{$DETAIL_VIEW_WIDGET->get('actionURL')}">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp; {vtranslate('LBL_ADD',$MODULE_NAME)|cat:" "|cat:$DETAIL_VIEW_WIDGET->getLabel()}
                                    </button>
                                </div>
                            {/if}
                        </div>
                        <div class="widget_contents">
                        </div>
                    </div>
                </div>
            {/if}
        {/foreach}

    </div>

    <div class="right-block col-lg-6 col-md-6 col-sm-6 pull-left p0 {if in_array($MODULE,array('PurchaseOrder','SalesOrder','Invoice','Quotes'))} ms_width_for_invoice_act_cmts {/if}">

        {* Summary View Related Activities Widget*}
        <div id="relatedActivities">
            {$RELATED_ACTIVITIES}
        </div>
        {* Summary View Related Activities Widget Ends Here*}

        {foreach item=DETAIL_VIEW_WIDGET from=$DETAILVIEW_LINKS['DETAILVIEWWIDGET'] name=count}
            {if $smarty.foreach.count.index % 2 != 0}
                <div class="summaryWidgetContainer ipad_summaryWidgetContainer">
                    <div class="widgetContainer_{$smarty.foreach.count.index}" data-url="{$DETAIL_VIEW_WIDGET->getUrl()}" data-name="{$DETAIL_VIEW_WIDGET->getLabel()}">
                        <div class="widget_header clearfix">
                            <input type="hidden" name="relatedModule" value="{$DETAIL_VIEW_WIDGET->get('linkName')}" />
                            <span class="toggleButton pull-left"><i class="fa fa-angle-down"></i>&nbsp;&nbsp;</span>
                            <h4 class="display-inline-block pull-left">{vtranslate($DETAIL_VIEW_WIDGET->getLabel(),$MODULE_NAME)}</h4>

                            {if $DETAIL_VIEW_WIDGET->get('action')}
                                <div class="pull-right">
                                    <button class="btn addButton btn-default btn-sm createRecord" type="button" data-url="{$DETAIL_VIEW_WIDGET->get('actionURL')}">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;{vtranslate('LBL_ADD',$MODULE_NAME)|cat:" "|cat:$DETAIL_VIEW_WIDGET->getLabel()}
                                    </button>
                                </div>
                            {/if}
                        </div>
                        <div class="widget_contents">
                        </div>
                    </div>
                </div>
            {/if}
        {/foreach}

    </div>
{/strip}
