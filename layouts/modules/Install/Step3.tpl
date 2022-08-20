{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
<style>
	.dropdown-menu{
		padding:5px;
		border : 1px solid #e8e8e8;
		box-shadow : 0px 0px 1px 0px rgba(200, 200, 200, 0.1);
		font-weight:100;
	}
	.dropdown-menu a{
		color : #0c4da2 !important;
	}
	.dropMnu{
		top: 50px !important;
	}
	.btn-primary:disabled{
		color : #fff !important;
	}
	{if $HT_PER eq 'false'}
		.config-table-ht{
			min-height:130px;
		}
	{/if}

	{if $PHP_INI_CURRENT_SETTINGS}
		.config-table-ini{
			min-height:155px;
		}
	{/if}
</style>
<form class="form-horizontal" name="step3" method="get" action="index.php" style="height:100%;">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step4" />

	<div class="row main-container" id="page3" style="height:100%;overflow:auto;">
		
	<div class="gs-info">
	 {include file="Sidebar.tpl"|vtemplate_path:'Install'}
  	</div>
	<div class="col-lg-3"> 
	</div>
	<div class="inner-container col-lg-8">
	<div class="mobile-view"><i class="fa fa-arrow-left"></i></div>
		<div class="card-view">
			<div class="card-view-header d-flex justify-content-between">
				<h3>{$SERVERHEAD}</h3>
				<div class="d-flex justify-content-center align-items-center p-1" id="recheck" style="cursor:pointer;">
					<span>
						<i id="recheck" class="fa fa-refresh" style="font-size:20px;"></i>
					</span>
				</div>
			</div>

			<div class="p-3">
				<div class="offset2">
				<div class="ui accordion">
					<h3 class="title {if $HT_PER eq 'false'} danger-alert {else} success-alert {/if}"><i class="fa fa-plus-square dropdown-plus p-1"></i>{$SERVERHEAD}</h3>
					<table class="content row config-table config-table-ht table table-responsive non-hover">
						<input type="hidden" value="{$HT_PER}" name="htperm" id="htperm" />
						<tr>
							<th colspan="3">{$SERVERHEAD}</th>
							<th></th>
						</tr>
						<tr>
							<td>{$SERVERTYPE}</td>
							<td></td>
							<td class="{if $HT_PER eq 'false'} no novalue {/if}">
								{if $HT_PER eq 'false'}
									<div class="dropdown" style="cursor:pointer;">
										<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">Fail</div>
										<div class="dropdown-menu dropMnu">{$HTACC_PER}</div>
									</div>
								{else}
									{$HTACC_PER}
								{/if}
							</td>
							<td></td>
						</tr>
					</table>

					<h3 class="title {if $HT_PER eq 'false'} restrict {else if $SYSTEM_PREINSTALL_PARAMS['LBL_Success'][1] eq 1} success-alert {else} danger-alert {/if}"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_PHP_CONFIGURATION', 'Install')}</h3>
					<table class="content row config-table table table-responsive non-hover">
						<tr>
							<th colspan="2">{vtranslate('LBL_PHP_CONFIGURATION', 'Install')}</th>
							<th colspan="1">{vtranslate('LBL_REQUIRED_VALUE', 'Install')}</th>
							<th colspan="1">{vtranslate('LBL_PRESENT_VALUE', 'Install')}</th>
						</tr>
						{foreach key=CONFIG_NAME item=INFO from=$SYSTEM_PREINSTALL_PARAMS}
							{if $CONFIG_NAME neq 'LBL_Success'}
							<tr>
								<td>{vtranslate($CONFIG_NAME, 'Install')}</td>
								<td></td>
								<td>
									{if $INFO.1 eq 1} 
										{vtranslate('LBL_YES', 'Install')} 
									{else} 
										{$INFO.1} 
									{/if}
								</td>
								<td {if $INFO.2 eq false} class="{if $CONFIG_NAME neq 'LBL_IMAP_SUPPORT' && $CONFIG_NAME neq 'LBL_ZLIB_SUPPORT' && $CONFIG_NAME neq 'LBL_OPEN_SSL' } no novalue {else} no-value {/if}">
										{if $CONFIG_NAME eq 'LBL_MOD_REWRITE'}
											<div class="dropdown" style="cursor:pointer;">
												<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{vtranslate('LBL_NO', 'Install')}</div>
												<div class="dropdown-menu"><a target="_blank" href="https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite/">You can install it by clicking this link</a></div>
											</div>
										{else if $CONFIG_NAME eq 'LBL_IMAP_SUPPORT'}	
											<div class="dropdown" style="cursor:pointer;">
												<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{vtranslate('LBL_NO', 'Install')}</div>
												<div class="dropdown-menu"><a target="_blank" href="https://phpadvices.com/install-or-enable-imap-extension-in-php/">You can install it by clicking this link</a></div>
											</div>
										{else if $CONFIG_NAME eq 'LBL_SIMPLE_XML_SUPPORT'}	
											<div class="dropdown" style="cursor:pointer;">
												<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{vtranslate('LBL_NO', 'Install')}</div>
												<div class="dropdown-menu"><a target="_blank" href="https://computingforgeeks.com/how-to-install-php-on-ubuntu/">You can install it by clicking this link</a></div>
											</div>												
										{else if $CONFIG_NAME eq 'LBL_MYSQLI_CONNECT_SUPPORT'}
											<div class="dropdown" style="cursor:pointer;">
												<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{vtranslate('LBL_NO', 'Install')}</div>
												<div class="dropdown-menu"><a target="_blank" href="https://computingforgeeks.com/how-to-install-php-on-ubuntu/">You can install it by clicking this link</a></div>
											</div>											
										{else if $CONFIG_NAME eq 'LBL_CURL'}	
											<div class="dropdown" style="cursor:pointer;">
												<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{vtranslate('LBL_NO', 'Install')}</div>
												<div class="dropdown-menu"><a target="_blank" href="https://computingforgeeks.com/how-to-install-php-on-ubuntu/">You can install it by clicking this link</a></div>
											</div>	
										{else if $CONFIG_NAME eq 'LBL_GD_LIBRARY'}	
											<div class="dropdown" style="cursor:pointer;">
												<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{vtranslate('LBL_NO', 'Install')}</div>
												<div class="dropdown-menu"><a target="_blank" href="https://www.digitalocean.com/community/questions/is-there-a-php-gd-command-for-php7/">You can install it by clicking this link</a></div>
											</div>	
										{else}
											{$INFO.0}
										{/if}
								    {else if ($INFO.2 eq true and $INFO.1 === true)} > 
										{vtranslate('LBL_YES', 'Install')} 
								    {else} > 
										{$INFO.0} 
								    {/if}
								</td>
							</tr>
							{/if}
						{/foreach}
					</table>

					<h3 class="title  {if $PHP_INI_CURRENT_SETTINGS['success'] eq 1} success-alert {else} danger-alert {/if}"><i class="fa fa-plus-square dropdown-plus p-1"></i>{vtranslate('LBL_PHP_RECOMMENDED_SETTINGS', 'Install')}</h3>
					{if $PHP_INI_CURRENT_SETTINGS}
					<table class="content row config-table config-table-ini table table-responsive non-hover" >
						<tr>
							<th colspan="3">{vtranslate('LBL_PHP_RECOMMENDED_SETTINGS', 'Install')}</th>
						</tr>
						{foreach key=DIRECTIVE item=VALUE from=$PHP_INI_CURRENT_SETTINGS name=directives}
							{if $DIRECTIVE ne 'success'}
								<tr>
									<td>{$DIRECTIVE}</td>
									<td>{$PHP_INI_RECOMMENDED_SETTINGS[$DIRECTIVE]}</td>
									<td {if $DIRECTIVE neq 'memory_limit'} {if $PHP_INI_RECOMMENDED_SETTINGS[$DIRECTIVE] neq $VALUE} class="no-value" {/if} {else if $PHP_INI_RECOMMENDED_SETTINGS[$DIRECTIVE] gt $VALUE} class=" no-value" {/if}>
										<div class="dropdown" style="cursor:pointer;">
											<div data-toggle="dropdown" aria-expanded="false" aria-hidden="true">{$VALUE}</div>
											<div class="dropdown-menu"><span>You can go to <b style="font-weight:bold !important;">{$PHP_INI_LOCATION}</b> and edit it and restart the server.</span></div>
										</div>	
									</td>
								</tr>
							{/if}
						{/foreach}
					</table>
					{/if}
					{if $FAILED_FILE_PERMISSIONS}
					<table class="content row config-table">
						<tr>
							<th colspan="2">{vtranslate('LBL_READ_WRITE_ACCESS', 'Install')}</th>
						</tr>
						{foreach item=FILE_PATH key=FILE_NAME from=$FAILED_FILE_PERMISSIONS}
						<tr>
							<td nowrap>{$FILE_NAME} ({str_replace("./","",$FILE_PATH)})</td><td class="no ">{vtranslate('LBL_NO', 'Install')}</td>
						</tr>
						{/foreach}
					</table>
					{/if}
				</div>
			</div>

			<div class="row offset2">		
				<div class="col-sm-12 ">
					<div class="button-container joforce-install-btn">
						<a href="{$SITE_URL}index.php?module=Install&view=Index"><input type="button" class="btn btn-large btn-default" value="{vtranslate('LBL_BACK', 'Install')}"/></a>						
						<input type="button" class="btn btn-large btn-primary btn-next but" value="{vtranslate('LBL_NEXT', 'Install')}" name="step4" {if $FAILED_FILE_PERMISSIONS} disabled {/if}/>
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>
	</div>
</form>

