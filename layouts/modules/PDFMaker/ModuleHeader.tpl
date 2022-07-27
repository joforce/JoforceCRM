{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}
     
{strip}
	<div class="col-lg-12 col-sm-12 col-xs-12 module-action-bar clearfix">
		<div class="module-action-content clearfix">
			<div class="col-lg-5 col-md-5 module-breadcrumb">
				{assign var=MODULE_MODEL value=Head_Module_Model::getInstance($MODULE)}
				<a title="{vtranslate($MODULE, $MODULE)}" href='{$MODULE_MODEL->getDefaultUrl()}'>
					<h4 class="module-title pull-left text-uppercase"> {vtranslate($MODULE, $MODULE)} &nbsp;&nbsp;</h4>
				</a>
				<p class="current-filter-name filter-name pull-left cursorPointer">&nbsp;&nbsp;
					<span class="fa fa-angle-right pull-left" aria-hidden="true"></span> 
					{if $smarty.request.view eq 'List'}
						{vtranslate('LBL_FILTER', $MODULE)}
					{/if}
					{if $smarty.request.view eq 'Detail'}
						<a title="{$RECORD->get('name')}">&nbsp;&nbsp;{$RECORD->get('name')} &nbsp;&nbsp;</a>
					{/if}
					{if $RECORD and $smarty.request.view eq 'Edit'}
						<a title="{$RECORD->get('name')}">&nbsp;&nbsp;{vtranslate('LBL_EDITING', $MODULE)} : {$RECORD->get('name')} &nbsp;&nbsp;</a>
					{else if $smarty.request.view eq 'Edit'}
						<a>&nbsp;&nbsp;{vtranslate('LBL_ADDING_NEW', $MODULE)}&nbsp;&nbsp;</a>
					{/if}
				</p>
			</div>
			<div class="col-lg-7 col-md-7 pull-right">
				<div id="appnav" class="ml-auto">
					<ul class="nav navbar-nav">
						{foreach item=BASIC_ACTION from=$MODULE_BASIC_ACTIONS}
							<li class="nav-item">
								<button id="{$MODULE}_listView_basicAction_{Head_Util_Helper::replaceSpaceWithUnderScores($BASIC_ACTION->getLabel())}" type="button" class="btn addButton btn-default" 
										{if stripos($BASIC_ACTION->getUrl(), 'javascript:')===0}  
											onclick='{$BASIC_ACTION->getUrl()|substr:strlen("javascript:")};'
										{else} 
											onclick='window.location.href = "{$BASIC_ACTION->getUrl()}"'
										{/if}>
									<div class="fa {$BASIC_ACTION->getIcon()}" aria-hidden="true"></div>&nbsp;&nbsp;
									{vtranslate($BASIC_ACTION->getLabel(), $MODULE)}
								</button>
							</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
		{if $FIELDS_INFO neq null}
			<script type="text/javascript">
				var uimeta = (function () {
					var fieldInfo = {$FIELDS_INFO};
					return {
						field: {
							get: function (name, property) {
								if (name && property === undefined) {
									return fieldInfo[name];
								}
								if (name && property) {
									return fieldInfo[name][property]
								}
							},
							isMandatory: function (name) {
								if (fieldInfo[name]) {
									return fieldInfo[name].mandatory;
								}
								return false;
							},
							getType: function (name) {
								if (fieldInfo[name]) {
									return fieldInfo[name].type
								}
								return false;
							}
						}
					};
				})();
			</script>
		{/if}
	</div>
{/strip}
