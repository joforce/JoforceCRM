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
<div class='dashBoardTabContainer col-lg-12 pl0 pr0'>
    <br>
    <div class="dashboardBanner"></div>
    <div class="dashBoardTabContents clearfix">
	<div class="gridster_{$TABID}">
	    <ul class="col-sm-12 p0 ">
		{assign var=COLUMNS value=2}
		{assign var=ROW value=1}
		{foreach from=$WIDGETS item=WIDGET name=count}
		    {assign var=WIDGETDOMID value=$WIDGET->get('linkid')}
		    {if $WIDGET->getName() eq 'MiniList'}
			{assign var=WIDGETDOMID value=$WIDGET->get('linkid')|cat:'-':$WIDGET->get('widgetid')}
		    {elseif $WIDGET->getName() eq 'Notebook'}
			{assign var=WIDGETDOMID value=$WIDGET->get('linkid')|cat:'-':$WIDGET->get('widgetid')}
		    {/if}
		    {if $WIDGETDOMID}
			<li id="{$WIDGETDOMID}" 
			    {if $smarty.foreach.count.index % $COLUMNS == 0 and $smarty.foreach.count.index != 0} 
				{assign var=ROWCOUNT value=$ROW+1} data-row="{$WIDGET->getPositionRow($ROWCOUNT)}" 
			    {else} 
				data-row="1" 
			    {/if}
			    {assign var=COLCOUNT value=($smarty.foreach.count.index % $COLUMNS)+1} 
			    data-col="0" data-sizex="5" data-sizey="0" {if $WIDGET->get('position') eq ""} data-position="false"{/if}
			    class="dashboardWidget col-sm-6  dashboardWidget_{$smarty.foreach.count.index}" data-url="{$WIDGET->getUrl()}" data-mode="open" data-name="{$WIDGET->getName()}">
			</li>
		    {else}
			{assign var=CHARTWIDGETDOMID value=$WIDGET->get('reportid')}
			{assign var=WIDGETID value=$WIDGET->get('id')}
			<li id="{$CHARTWIDGETDOMID}-{$WIDGETID}" 
			    {if $smarty.foreach.count.index % $COLUMNS == 0 and $smarty.foreach.count.index != 0}
				{assign var=ROWCOUNT value=$ROW+1} data-row="1" 
			    {else}
				data-row="{$WIDGET->getPositionRow($ROW)}" 
			    {/if}
			    {assign var=COLCOUNT value=($smarty.foreach.count.index % $COLUMNS)+1} data-col="{$WIDGET->getPositionCol($COLCOUNT)}" data-sizex="{$WIDGET->getWidth()}" data-sizey="{$WIDGET->getHeight()}" {if $WIDGET->get('position') eq ""} data-position="false"{/if}
			    class="dashboardWidget col-sm-6  dashboardWidget_{$smarty.foreach.count.index}" data-url="{$WIDGET->getUrl()}" data-mode="open" data-name="ChartReportWidget"> 
			</li>
		    {/if}
		{/foreach}
	    </ul>
	    <input type="hidden" id=row value="{$ROWCOUNT}" />
	    <input type="hidden" id=col value="{$COLCOUNT}" />
	    <input type="hidden" id="userDateFormat" value="{$CURRENT_USER->get('date_format')}" />
	</div>
    </div>
</div>
{/strip}
