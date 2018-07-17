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
	<div class="joforce-box  pl0 pr0" id="listViewContent">
		<div class="joforce-dash" style="">
			<div class="joforce-dash-container" >
				<ul class="dash-widgets">
					<li class="dash-leads">
						{assign var=module value='Leads'}
						{include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-contacts">
						{assign var=module value='Contacts'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-organizations">
						{assign var=module value='Accounts'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-campaigns">
                                                {assign var=module value='Campaigns'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
					<li class="dash-opportunities">	
						{assign var=module value='Potentials'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-products">
						{assign var=module value='Products'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-services">
						{assign var=module value='Services'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>

					<li class="dash-quotes">
						{assign var=module value='Quotes'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-invoice">
						{assign var=module value='Invoice'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-sales-order">
                                                {assign var=module value='SalesOrder'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
					<li class="dash-vendor">
						{assign var=module value='Vendors'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-purchase-orders">
						{assign var=module value='PurchaseOrder'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-project">
						{assign var=module value='Project'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-milestone">
						{assign var=module value='ProjectMilestone'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
					<li class="dash-projecttask">
						{assign var=module value='ProjectTask'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
					</li>
				</ul>
				<ul class="dash-widgets-side">
					<li class="hover-li">
                                                {assign var=module value='Task'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
					<li class="hover-li">
                                                {assign var=module value='Events'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
					<li class="hover-li">
                                                {assign var=module value='Emails'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
					<li class="hover-li">
                                                {assign var=module value='PBXManager'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
					<li style="top: 0; right: 0;" class="hover-li">
                                                {assign var=module value='HelpDesk'}
                                                {include file="Link.tpl"|vtemplate_path:$MODULE}
                                        </li>
				</ul>
			</div>
		</div>		
	</div>

{/strip}

