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
	<div class="row">
		{assign var=IS_AUTH value=($REGISTRATION_STATUS and $PASSWORD_STATUS)}
		{assign var=EXTENSIONS_COUNT value=0}
		{foreach item=EXTENSION from=$EXTENSIONS_LIST name=extensions}
			{*{if !$EXTENSION->isHeadCompatible()}{continue}{/if}*}
			{assign var=EXTENSIONS_COUNT value=$EXTENSIONS_COUNT+1}

			{if $EXTENSION->isAlreadyExists()}
				{assign var=EXTENSION_MODULE_MODEL value= $EXTENSION->get('moduleModel')}
			{else}
				{assign var=EXTENSION_MODULE_MODEL value= 'false'}
			{/if}
			{assign var=IS_FREE value=(($EXTENSION->get('price') eq 'Free') or ($EXTENSION->get('price') eq 0))}
			<div class="col-lg-4 col-md-6 col-sm-6" style="margin-bottom:30px;">
				<div class="extension_container extensionWidgetContainer" style="padding:15px;border: 15px solid white;background-clip: border-box;box-shadow: 1px 1px 10px 5px #E0E0E0;border-radius: 25px;
					{if $smarty.foreach.extensions.index % 7 == 0}
						background-color:#db3236;
					{else if $smarty.foreach.extensions.index % 7 == 1}
						background-color:#4885ed;
					{else if $smarty.foreach.extensions.index % 7 == 2}
						background-color:#666666;
					{else if $smarty.foreach.extensions.index % 7 == 3}
						background-color:#f4c20d;
					{else if $smarty.foreach.extensions.index % 7 == 4}
						background-color:#3cba54;
					{else if $smarty.foreach.extensions.index % 7 == 5}
						background-color:#1560bd;
					{else if $smarty.foreach.extensions.index % 7 == 6}
						background-color:#DDA0DD;
					{/if}">
					<div class="">
						<div class="" style="font-size:17px"><a href="{$EXTENSION->get('Link')}" target="_blank" style="outline:none;color:white;"><label>{vtranslate($EXTENSION->get('Name'), $QUALIFIED_MODULE)}</label></a></div>
						<input type="hidden" name="extensionName" value="{$EXTENSION->get('Name')}" />
						<input type="hidden" name="extensionUrl" value="{$EXTENSION->get('downloadURL')}" />
						<input type="hidden" name="moduleAction" value="{if ($EXTENSION->isAlreadyExists()) and (!$EXTENSION_MODULE_MODEL->get('trial'))}{if $EXTENSION->isUpgradable()}Upgrade{else}Installed{/if}{else}Install{/if}" />
						<input type="hidden" name="extensionId" value="{$EXTENSION->get('id')}" />
					</div>
					<div style="padding-left:3px;">
						<div class="row extension_contents" style="border:none;">			
							<div class="col-sm-4 col-xs-4">
								{if $EXTENSION->get('Icon') neq NULL}
									{assign var=imageSource value=$EXTENSION->get('Icon')}
									<a href="{$EXTENSION->get('Link')}" target="_blank" style="outline:none;">
										<img width="100%" height="100%" class="thumbnailImage" src="{$imageSource}"/>
									</a>
									<p style="color:white;font-weight:bold;text-align:center;font-size:x-large;margin-top:20px;">${$EXTENSION->get('price')}</p>
								{else}
									<i class="fa fa-picture-o" style="color:#ddd;font-size: 90px;" title="Image not available"></i>
								{/if} 

							</div>
							<div class="col-sm-8 col-xs-8">
								<div class="row descriptions" style="height:170px;word-wrap:break-word;margin: 0px;font-size:14px;color:white;">
									{assign var=SUMMARY value=$EXTENSION->get('Description')}
									{if empty($SUMMARY)}
										{assign var=SUMMARY value={$EXTENSION->get('description')|truncate:100}}
									{/if}
									{$SUMMARY}
								</div>
							</div>
						</div>
						<div class="extensionInfo">
							<div class="row">
								{*{assign var=ON_RATINGS value=$EXTENSION->get('avgrating')}
								<div class="col-sm-5 col-xs-5">
									<span class="rating" data-score="{$ON_RATINGS}" data-readonly=true></span>
									<span>{if $EXTENSION->get('avgrating')}&nbsp;({$EXTENSION->get('avgrating')}){/if}</span>
								</div>*}
								<div class="" style="text-align:center;">
									<div class="">
										{if !$EXTENSION->isHeadCompatible()}
											<button class="moreDetails addButton" style="border: 2px solid white;padding: 15px;text-align: center;text-decoration: none;display: inline-block;font-size: 16px;margin: 4px 2px;cursor: pointer;background-color:rgba(231, 11, 51, 0.68);font-weight:bolder;color:white;border-radius:10px;" data-url="{$EXTENSION->get('Link')}" style="margin-right:5px;">{vtranslate('LBL_MORE_DETAILS', $QUALIFIED_MODULE)}</button>
											{if $EXTENSION->isAlreadyExists()}
												{if ($EXTENSION->isUpgradable())}
													<button class="btn btn-success btn-sm margin0px">
														{vtranslate('LBL_UPGRADE', $QUALIFIED_MODULE)}
													</button>
												{else}
													{if $EXTENSION_MODULE_MODEL neq 'false' && $EXTENSION_MODULE_MODEL->get('trial')}
														<span class="alert alert-info">{vtranslate('LBL_TRIAL_INSTALLED', $QUALIFIED_MODULE)}</span>
													{else}
														<span class="alert alert-info" style="vertical-align:middle; padding: 3px 8px;">{vtranslate('LBL_INSTALLED', $QUALIFIED_MODULE)}</span> 
													{/if}
													{if !($EXTENSION->get('price') eq 'Free' or $EXTENSION->get('price') eq 0)}
														{*<button data-url="{$EXTENSION->get('Link')}" style="width:350px;" class="btn btn-info buy" data-trial={if $EXTENSION->get('trialdays') gt 0}true{else}false{/if}>{vtranslate('LBL_BUY',$QUALIFIED_MODULE)}${$EXTENSION->get('price')}</button>*}
													{/if}   
												{/if}
											{else}
												{if $EXTENSION->get('price') eq 'Free' or $EXTENSION->get('price') eq 0}
													<button class="btn btn-success btn-sm">{vtranslate('LBL_INSTALL', $QUALIFIED_MODULE)}</button>
												{else}
													{*<button style="width:350px;" class="btn btn-info buy btn-sm" data-url="{$EXTENSION->get('Link')}" data-trial=false>{vtranslate('LBL_BUY',$QUALIFIED_MODULE)}${$EXTENSION->get('price')}</button>*}   
												{/if}
											{/if}
										{else}
											<span class="alert alert-error">{vtranslate('LBL_EXTENSION_NOT_COMPATABLE', $QUALIFIED_MODULE)}</span>
										{/if}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		{/foreach}
		{if empty($EXTENSIONS_LIST) || $EXTENSIONS_COUNT eq 0}
			<div class="row">
				<div class="col-sm-2 col-xs-2"></div>
				<div class="col-sm-8 col-xs-8">
					<br>
					<br>
					<br>
					<h3><center> {vtranslate('LBL_NO_EXTENSIONS_FOUND', $QUALIFIED_MODULE)} </center></h3>
				</div>
				<div class="col-sm-2 col-xs-2"></div>
			</div>
		{/if}
	</div>
{/strip}