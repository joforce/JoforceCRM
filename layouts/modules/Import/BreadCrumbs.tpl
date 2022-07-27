{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
*************************************************************************************}

<div id="{$BREADCRUMB_ID}" class="breadcrumb col-sm-12 col-xs-12 {if in_array($MODULE,array('Import'))} ms_sc_breadCrumb_view {/if}">
    <ul class="wizards row">
	
	{assign var=ZINDEX value=9}
	{foreach key=CRUMBID item=STEPINFO from=$BREADCRUMB_LABELS name=breadcrumbLabels}
	    {assign var=STEPTEXT value=$STEPINFO[0]}
	    {assign var=STEPICON value=$STEPINFO[1]}
	    {assign var=INDEX value=$smarty.foreach.breadcrumbLabels.index}
	    {assign var=INDEX value=$INDEX+1}
	    <li class="col-sm-4 col-xs-4 step {if $smarty.foreach.breadcrumbLabels.first} first {$FIRSTBREADCRUMB} {else} {$ADDTIONALCLASS} {/if} {if $smarty.foreach.breadcrumbLabels.last} last {/if} {if $ACTIVESTEP eq $INDEX}active{/if}" id="{$CRUMBID}" data-value="{$INDEX}" style="z-index:{$ZINDEX}">
		{if $CRUMBID!="step4"}
		<span class="breadcrumb_line"></span>
		{/if}
		<a href="#">
		    <span class="stepNum {$STEPICON}"></span>
		</a>
		<span class="stepText" title="{vtranslate($STEPTEXT,$MODULE)}">{vtranslate($STEPTEXT,$MODULE)}</span>
	    </li>
	    {assign var=ZINDEX value=$ZINDEX-1}
	{/foreach}
    </ul>
</div>
