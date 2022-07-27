{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*************************************************************************************}

{strip}
		<ul class="detail-summary-ul">
                	{foreach from=$WIDGET_ARRAY item=WIDGET}
				<div><h4>{$WIDGET['linklabel']}</h4></div>
                                <li id="{$WIDGET['linkid']}" class="widgetContainer_SummaryWidget_{$WIDGET['linkid']}" data-url="{$WIDGET['linkurl']}" data-mode="open" data-name="{$WIDGET['linklabel']}" data-type="summary_widget" data-tabid="{$TABID}" style="float: left; top:30px; left:10px; width:500px; height:250px;"></li>
                        {/foreach}
		</ul>
{/strip}
