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
	<div class="col-sm-12 col-xs-12" id="importModules" style="padding-left:15px;">
		<div class="row">
		
		<div class="col-sm-12 col-xs-4 pull-right">
				<div class="row pagenamehead">
					<div class='col-md-6 pull-left'>
					<h3> Extension Store
</h3>
					</div>
				
					<div class="col-sm-6 col-xs-8 pull-right">
						<input type="text" id="searchNewExtn" class="form-control" placeholder="{vtranslate('Search for an extension..', $QUALIFIED_MODULE)}"/>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="contents row">
			<div class="col-sm-12 col-xs-12" id="extensionContainer">
				{include file='ExtensionModules.tpl'|@vtemplate_path:$QUALIFIED_MODULE}
			</div>
		</div>

		{include file="CardSetupModals.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
	</div>
{/strip}
