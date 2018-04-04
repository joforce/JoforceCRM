{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}

<div class="main-container clearfix">
        <div class = "col-sm-12 col-xs-12 content-area">
	<table style="overflow-x: auto;overflow-y: auto;">
	    <tbody>
		<tr>
		   <td>
			<div style="white-space: nowrap;">
			{foreach key=sales_stage_id item=sales_stage_name from=$SALES_STAGES}
			    <div style="display: inline-block;vertical-align: top;white-space: normal;" id="forecast-div">
				<ul class="table-header header-view p0">
		  		    <li style="display: inline-block;" class="sales_stage" id="sales_stage_{$sales_stage_id}" data-stage_id="{$sales_stage_id}">
					<span class="stage_name">{$sales_stage_name}</span>
					<span>
				                <span class="stage_value ml15" id="total_amount-{$sales_stage_id}">{$AMOUNT_ARRAY[$sales_stage_name]}
						</span>
						<b class="mr10 currency-symbol">{$CURRENCY_SYMBOL}</b>
					</span>
					<span class="no-oppprtunity">
						<small class="potential_count" id="total_count-{$sales_stage_id}">{$COUNT_ARRAY[$sales_stage_name]}</small>
						<small id="opportunity-{$sales_stage_id}"> 
							{if $COUNT_ARRAY[$sales_stage_name] == 0 || $COUNT_ARRAY[$sales_stage_name] == 1}
								Opportunity
							{else}
								Opportunities
							{/if}
						</small>
                    			</span> 
				    </li>
				</ul>
				<ul style="list-style-type: none;" class = "draggable" id="stageid-{$sales_stage_id}">
				    {foreach item=POTENTIAL key=potential_id from=$POTENTIALS}
					{if $POTENTIAL->get('sales_stage') == $sales_stage_name}
						<li data-potential-id="{$potential_id}" data-stageid="{$sales_stage_id}" class="process" id="sortlist-{$potential_id}">
						    <div class="box col-lg-12 drag-list">
                            <div class="col-lg-9 p0">
							<a style = "font-weight: 600;" href="{$POTENTIAL->getDetailViewUrl()}" class="process-detail table-list-front" data-module-name="deals" data-url="{$SITEURL}Potentials/Detail/{$POTENTIAL->get('potentialid')}/SALES">
						        <div> <strong class="table-list-strong">{$POTENTIAL->get('potentialname')}</strong></div>
                                <small class="table-list-small">
                                    <span id="amount-{$potential_id}" class="mr10"> {str_replace(',', '', $POTENTIAL->get('amount'))} </span>
                                    <span id="currency" class="mr10"> {$CURRENCY_SYMBOL} </span>
                                </small>
            				</a>
						    </div>
                            </div>
						</li>
					{/if}
				    {/foreach}
          
				</ul>
			    </div>
			{/foreach}
			<div>
		   </td>
		</tr>
	    </tbody>
	</table>
	</div>
</div>
