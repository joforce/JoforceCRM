{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
*************************************************************************************}

{strip}
<div class="{if in_array($MODULE,array('Campaigns','Vendors','PriceBooks','PurchaseOrder','SalesOrder','Documents','Invoice','Quotes','Calendar'))} side-menu-1 {/if} side-menu ">
<i class="fa fa-chevron-left"></i>
</div>
<div  class='{if in_array($MODULE, array('Quotes','Vendors','SalesOrder','PurchaseOrder','Invoice','PriceBooks'))} related-tabs more-icons more-icons-new h-auto ms_more-icon {elseif in_array($MODULE, array('HelpDesk'))} related-tabs more-icons more-icons-new1 h-auto   {elseif !in_array($MODULE, array('Calendar','Campaigns','PriceBooks','Quotes'))} related-tabs more-icons {else} related-tabs more-icons h-auto ms_more-icon {/if} {if in_array($MODULE,array('Vendors'))} ms_scrn_more-icon {/if}' id="myDIV_new">
		<ul class="nav nav-tabs">
			{foreach item=RELATED_LINK from=$DETAILVIEW_LINKS['DETAILVIEWTAB']}
				{$engagementEnabledModules = ['Accounts','Contacts','Leads']}
				{if $MODULE_NAME|in_array:$engagementEnabledModules && (trim($RELATED_LINK->getLabel()) == 'LBL_UPDATES')}
					{continue}
				{else}
					{assign var=RELATEDLINK_URL value=$RELATED_LINK->getUrl()}
					{assign var=RELATEDLINK_LABEL value=$RELATED_LINK->getLabel()}
					{assign var=RELATED_TAB_LABEL value={vtranslate('SINGLE_'|cat:$MODULE_NAME, $MODULE_NAME)}|cat:" "|cat:$RELATEDLINK_LABEL}
				{/if}
				<li class="tab-item {if $RELATED_TAB_LABEL==$SELECTED_TAB_LABEL}active{/if}" data-url="{$RELATEDLINK_URL}" data-label-key="{$RELATEDLINK_LABEL}" data-link-key="{$RELATED_LINK->get('linkKey')}" >
					<a href="{$RELATEDLINK_URL}" class="textOverflowEllipsis">
						<span class="tab-label" title="{vtranslate($RELATEDLINK_LABEL,{$MODULE_NAME})}">
						    <img src="{$RELATED_LINK->get('linkicon')} " alt="{vtranslate($RELATEDLINK_LABEL,{$MODULE_NAME})}" width="25px" height="25px" />
						</span>
					</a>
				</li>   








			{/foreach}

			{assign var=RELATEDTABS value=$DETAILVIEW_LINKS['DETAILVIEWRELATED']}
			{assign var=COUNT value=$RELATEDTABS|@count}

			{assign var=LIMIT value = 10}
			{if $COUNT gt 10}
				{assign var=COUNT1 value = $LIMIT}
			{else}
				{assign var=COUNT1 value=$COUNT}
			{/if}

			{for $i = 0 to $COUNT1-1}
				{assign var=RELATED_LINK value=$RELATEDTABS[$i]}
				{assign var=RELATEDMODULENAME value=$RELATED_LINK->getRelatedModuleName()}
				{assign var=RELATEDFIELDNAME value=$RELATED_LINK->get('linkFieldName')}
				{assign var="DETAILVIEWRELATEDLINKLBL" value= vtranslate($RELATED_LINK->getLabel(),$RELATEDMODULENAME)}
				<li class="tab-item {if (trim($RELATED_LINK->getLabel())== trim($SELECTED_TAB_LABEL)) && ($RELATED_LINK->getId() == $SELECTED_RELATION_ID)}active{/if}"  data-url="{$SITEURL}{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" data-label-key="{$RELATED_LINK->getLabel()}"
					data-module="{$RELATEDMODULENAME}" data-relation-id="{$RELATED_LINK->getId()}" {if $RELATEDMODULENAME eq "ModComments"} title {else} title="{$DETAILVIEWRELATEDLINKLBL}"{/if} {if $RELATEDFIELDNAME}data-relatedfield ="{$RELATEDFIELDNAME}"{/if}>
					<a href="{$SITEURL}{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" class="textOverflowEllipsis" displaylabel="{$DETAILVIEWRELATEDLINKLBL}" recordsCount="" >
						{if $RELATEDMODULENAME eq "ModComments"}
							<span class="tab-icon" title="{$DETAILVIEWRELATEDLINKLBL}"><i class="fa fa-comments" ></i></span>
						{else}
							<span class="tab-icon">
								{assign var=RELATED_MODULE_MODEL value=Head_Module_Model::getInstance($RELATEDMODULENAME)}  
								<i class="joicon-{strtolower($RELATEDMODULENAME)}" ></i>
							</span>
						{/if}
						<span class="numberCircle hide">0</span>
					</a>
				</li>
				{if ($RELATED_LINK->getId() == {$smarty.request.relationId})}
					{assign var=MORE_TAB_ACTIVE value='true'}
				{/if}
			{/for}
			{if $MORE_TAB_ACTIVE neq 'true'}
				{for $i = 0 to $COUNT-1}
					{assign var=RELATED_LINK value=$RELATEDTABS[$i]}
					{if ($RELATED_LINK->getId() == {$smarty.request.relationId})}
						{assign var=RELATEDMODULENAME value=$RELATED_LINK->getRelatedModuleName()}
						{assign var=RELATEDFIELDNAME value=$RELATED_LINK->get('linkFieldName')}
						{assign var="DETAILVIEWRELATEDLINKLBL" value= vtranslate($RELATED_LINK->getLabel(),$RELATEDMODULENAME)}
						<li class="more-tab moreTabElement active"  data-url="{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" data-label-key="{$RELATED_LINK->getLabel()}"
							data-module="{$RELATEDMODULENAME}" data-relation-id="{$RELATED_LINK->getId()}" {if $RELATEDMODULENAME eq "ModComments"} title {else} title="{$DETAILVIEWRELATEDLINKLBL}"{/if} {if $RELATEDFIELDNAME}data-relatedfield ="{$RELATEDFIELDNAME}"{/if}>
							<a href="{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" class="textOverflowEllipsis" displaylabel="{$DETAILVIEWRELATEDLINKLBL}" recordsCount="" >
								{if $RELATEDMODULENAME eq "ModComments"}
									<span class="tab-icon" title="{$DETAILVIEWRELATEDLINKLBL}"><i class="fa fa-comments" ></i></span>&nbsp;
								{else}  
									<span class="tab-icon">
										{assign var=RELATED_MODULE_MODEL value=Head_Module_Model::getInstance($RELATEDMODULENAME)}  
										<i class="joicon-{strtolower($RELATEDMODULENAME)}" ></i>
									</span>
								{/if}
								<span class="numberCircle hide">0</span>
							</a>
						</li>
						{break}
					{/if}
				{/for}
			{/if}
			{if $COUNT gt $LIMIT}
				
				{for $j = $COUNT1 to $COUNT-1}
					{assign var=RELATED_LINK value=$RELATEDTABS[$j]}
					{assign var=RELATEDMODULENAME value=$RELATED_LINK->getRelatedModuleName()}
					{assign var=RELATEDFIELDNAME value=$RELATED_LINK->get('linkFieldName')}
					{assign var="DETAILVIEWRELATEDLINKLBL" value= vtranslate($RELATED_LINK->getLabel(),$RELATEDMODULENAME)}
					{if $RELATEDMODULENAME neq "PurchaseOrder"}
					<li id="demo"  class="tab-item hide collapse {if (trim($RELATED_LINK->getLabel())== trim($SELECTED_TAB_LABEL)) && ($RELATED_LINK->getId() == $SELECTED_RELATION_ID)}active{/if}" data-url="{$SITEURL}{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" data-label-key="{$RELATED_LINK->getLabel()}"
						data-module="{$RELATEDMODULENAME}" title="" data-relation-id="{$RELATED_LINK->getId()}" {if $RELATEDFIELDNAME}data-relatedfield ="{$RELATEDFIELDNAME}"{/if}>
						<a href="{$SITEURL}{$RELATED_LINK->getUrl()}&tab_label={$RELATED_LINK->getLabel()}" displaylabel="{$DETAILVIEWRELATEDLINKLBL}" recordsCount="">
							{if $RELATEDMODULENAME eq "ModComments"}
								<span class="tab-icon" title="{$DETAILVIEWRELATEDLINKLBL}"><i class="fa fa-comments" ></i></span>&nbsp;
							{else}  
								{assign var=RELATED_MODULE_MODEL value=Head_Module_Model::getInstance($RELATEDMODULENAME)}  
								<span class="tab-icon textOverflowEllipsis">
									<i class="joicon-{strtolower($RELATEDMODULENAME)}" title="{$DETAILVIEWRELATEDLINKLBL}"></i>
								</span>
							{/if}
							<span class="numberCircle hide">0</span>
						</a>
					</li>
					{/if}
				{/for}
			{/if}
			{if $COUNT>8}
			<li class="more-tab-items " id="modelTaps" data-toggle="collapse" data-target="#demo"  >
				  <a>
				    <span class="tab-icon" title="{vtranslate('LBL_MORE', $MODULE_NAME)}">
					<i class="fa fa-chevron-down"></i>
				    </span>
				  </a>
				</li>
				{/if}
		</ul>
	</div>
	<script>

jQuery(function($) {
  $('#modelTaps').on('click', function() {
    var $el = $(this),
      textNode = this.lastChild;
    $el.find('i').toggleClass('fa-chevron-down fa-chevron-up');
    
  });
});

$(document).ready(function(){
function checkWidth_side() {
    if ($(window).width() < 500) {
		 $('#myDIV_new').addClass('new_style');
		 $('.side-menu').addClass('side_menu_visible');
		 $('.resClass').removeClass('mr20 ml20');
    } 
	else if($(window).width()>500){
		$('.side-menu').addClass('side_menu_none');
		$('#myDIV_new').removeClass('new_style');
		$('.side-menu').removeClass('side_menu_visible');
		$('.resClass').addClass('mr20 ml20');
		
	}
	else {
		$('#myDIV_new').removeClass('new_style');
    }
}
$(window).resize(checkWidth_side);
window.onload = function() {	
$(window).resize(checkWidth_side);
if ($(window).width() < 500) {
	$('#myDIV_new').addClass('new_style');
	$('.side-menu').addClass('side_menu_visible');
	$('.resClass').removeClass('mr20 ml20');
} 
else if($(window).width()>500){
		$('.side-menu').addClass('side_menu_none');
		$('#myDIV_new').removeClass('new_style');
		$('.side-menu').removeClass('side_menu_visible');
		$('.resClass').addClass('mr20 ml20');
	}
}

});
$(document).ready(function(){
	$('.side-menu').click(function() {
    $("i", this).toggleClass("fa-chevron-left fa-chevron-right");
	$('#myDIV_new').toggleClass("new-style1");

	
})	
});

	</script>
	<style>
		.new_style{
			display:none;	
		}
		.side_menu_none{
			display:none;
		}
		.new-style1,.side_menu_visible{
			display:block;
		}
		.side-menu{
			background: white;
			padding: 8px;
			border-radius: 4px;
			transition:all .5s ease-in;	
			z-index: 1;
    		right: -8px;
			position:relative;
			top:-16px;
		}
		.side-menu-1{
			right: -11px!important;
    		top: 0px!important;
		}
		
	</style>
	{strip}
