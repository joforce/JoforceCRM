{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<form class="form-horizontal" name="step3" method="get" action="index.php">
	<input type=hidden name="module" value="Install" />
	<input type=hidden name="view" value="Index" />
	<input type=hidden name="mode" value="Step4" />

	<div class="row main-container" id="page3">
		
	<div class="gs-info">
	 {include file="Sidebar.tpl"|vtemplate_path:'Install'}
    <footer class="noprint">
      <div class="vtFooter">
        <p>
          {vtranslate('POWEREDBY')} 
          &copy; {date('Y')}
        </p>
      </div>
    </footer>
  </div>

	<div class="inner-container">
	<div class="mobile-view"><i class="fa fa-arrow-left"></i></div>
		<div class="col-sm-12 text-center">
            <div class="logo install-logo">
                <img src="{'logo.png'|vimage_path}"/>
            </div>
        </div>

		<div class="row">
				<div class="row offset2">
					
					<div class="col-sm-10 col-sm-offset-1">
						<div class=" pull-right">
							<div class="button-container">
								<a href ="#">
									<input type="button" class="btn btn-default" value="{vtranslate('LBL_RECHECK', 'Install')}" id='recheck'/>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row offset2">
					
				<div class="col-sm-10 col-sm-offset-1">
					<table class="config-table table table-responsive">
						<input type="hidden" value="{$HT_PER}" name="htperm" id="htperm" />
						<tr>
							<th>{$SERVERHEAD}</th>
							<th></th>
						</tr>
						<tr>
							<td>{$SERVERTYPE}</td>
							<td>{$HTACC_PER}</td>
						</tr>
					</table>

					<table class="config-table table table-responsive">
						<tr>
							<th>{vtranslate('LBL_PHP_CONFIGURATION', 'Install')}</th>
							<th>{vtranslate('LBL_REQUIRED_VALUE', 'Install')}</th>
							<th>{vtranslate('LBL_PRESENT_VALUE', 'Install')}</th>
						</tr>
						{foreach key=CONFIG_NAME item=INFO from=$SYSTEM_PREINSTALL_PARAMS}
							<tr>
								<td>{vtranslate($CONFIG_NAME, 'Install')}</td>
								<td>
									{if $INFO.1 eq 1} 
										{vtranslate('LBL_YES', 'Install')} 
									{else} 
										{$INFO.1} 
									{/if}
								</td>
								<td {if $INFO.2 eq false} class="no" >
									{if $CONFIG_NAME = 'LBL_PHP_VERSION'}
										{$INFO.0}
									{else}
										{vtranslate('LBL_NO', 'Install')}
									{/if}
								    {else if ($INFO.2 eq true and $INFO.1 === true)} > 
									{vtranslate('LBL_YES', 'Install')} 
								    {else} > 
									{$INFO.0} 
								    {/if}
								</td>
							</tr>
						{/foreach}
					</table>
					{if $PHP_INI_CURRENT_SETTINGS}
					<table class="config-table table table-responsive">
						<tr>
							<th colspan="3">{vtranslate('LBL_PHP_RECOMMENDED_SETTINGS', 'Install')}</th>
						</tr>
						{foreach key=DIRECTIVE item=VALUE from=$PHP_INI_CURRENT_SETTINGS name=directives}
						<tr>
							<td>{$DIRECTIVE}</td><td>{$PHP_INI_RECOMMENDED_SETTINGS[$DIRECTIVE]}</td><td class="no">{$VALUE}</td>
						</tr>
						{/foreach}
					</table>
					{/if}
					{if $FAILED_FILE_PERMISSIONS}
					<table class="config-table">
						<tr>
							<th colspan="2">{vtranslate('LBL_READ_WRITE_ACCESS', 'Install')}</th>
						</tr>
						{foreach item=FILE_PATH key=FILE_NAME from=$FAILED_FILE_PERMISSIONS}
						<tr>
							<td nowrap>{$FILE_NAME} ({str_replace("./","",$FILE_PATH)})</td><td class="no">{vtranslate('LBL_NO', 'Install')}</td>
						</tr>
						{/foreach}
					</table>
					{/if}
				</div>
			</div>
		</div>
		<div class="row offset2">		
			<div class="col-sm-12 ">
				<div class="button-container">
					<input type="button" class="btn btn-large btn-default" value="{vtranslate('LBL_BACK', 'Install')}" name="back"/>
					<input type="button" class="btn btn-large btn-primary btn-next" value="{vtranslate('LBL_NEXT', 'Install')}" name="step4" {if $FAILED_FILE_PERMISSIONS} disabled {/if}/>
				</div>
			</div>
		</div>
		</div>
	</div>
</form>

