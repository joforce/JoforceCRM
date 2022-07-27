{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Settings/Head/views/OutgoingServerEdit.php *}

{strip}
    <div class="card" id="EditViewOutgoing">
	    <div class=" ml20 mr10 card-header-new">
		<h3 style="" >{vtranslate('LBL_OUTGOING_SERVER', $QUALIFIED_MODULE)}</h3>&nbsp;{vtranslate('LBL_OUTGOING_SERVER_DESC', $QUALIFIED_MODULE)}
	    </div>
	    {assign var=WIDTHTYPE value=$CURRENT_USER_MODEL->get('rowheight')}
	    <form id="OutgoingServerForm" data-detail-url="{$MODEL->getDetailViewUrl()}" method="POST">
		<input type="hidden" name="default" value="false" />
		<input type="hidden" name="server_port" value="0" />
		<input type="hidden" name="server_type" value="email"/>
		<input type="hidden" name="id" value="{$MODEL->get('id')}"/>
		<div class="blockData pt10 pr20 mb50  pl20" >
		    <br>
		    <div class="hide errorMessage">
			<div class="alert alert-danger">
			    {vtranslate('LBL_TESTMAILSTATUS', $QUALIFIED_MODULE)}
			    <strong>{vtranslate('LBL_MAILSENDERROR', $QUALIFIED_MODULE)}</strong>
			</div>
		    </div>
		    <div class="block">
			<div> <h4>{vtranslate('LBL_MAIL_SERVER_SMTP', $QUALIFIED_MODULE)}</h4> </div>
			<hr>
			<div class="d-flex justify-content-center">
			<table class="table editview-table no-border m50">
			    <tbody class="">
				<tr>
				    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_SERVER_TYPE', $QUALIFIED_MODULE)}</label></td>
				    <td class="{$WIDTHTYPE} fieldValue">
					<div class=" col-lg-6 col-md-12 col-sm-12">
					    <select class="select2 inputElement col-lg-12 col-md-12 col-lg-12" name="serverType">
						<option value="">{vtranslate('LBL_SELECT_OPTION','Head')}</option>
						<option value="{"ssl://smtp.gmail.com:465"}" {if {$MODEL->get('server')} eq "ssl://smtp.gmail.com:465"} selected {/if}>{vtranslate('LBL_GMAIL', $QUALIFIED_MODULE)} </option>
						<option value="{"smtp.live.com"}" {if {$MODEL->get('server')} eq "smtp.live.com"} selected {/if}>{vtranslate('LBL_HOTMAIL', $QUALIFIED_MODULE)}</option>
						<option value="{"smtp-mail.outlook.com"}" {if {$MODEL->get('server')} eq "smtp.live.com"} selected {/if}>{vtranslate('LBL_OFFICE365', $QUALIFIED_MODULE)}</option>
						<option value="{"smtp.mail.yahoo.com"}" {if {$MODEL->get('server')} eq "smtp.mail.yahoo.com"} selected {/if}>{vtranslate('LBL_YAHOO', $QUALIFIED_MODULE)}</option>
						<option value="">{vtranslate('LBL_OTHERS', $QUALIFIED_MODULE)}</option>
					    </select>
					</div>
				    </td>
				</tr>
				<tr>
				    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_SERVER_NAME', $QUALIFIED_MODULE)}</label>&nbsp;<span class="redColor">*</span></td>
				    <td class="{$WIDTHTYPE} fieldValue"><div class=" col-lg-6 col-md-12 col-sm-12"><input type="text" class="inputElement" name="server" data-rule-required="true" value="{$MODEL->get('server')}" ></div></td></tr>
				<tr>
				    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_USER_NAME', $QUALIFIED_MODULE)}</label></td>
				    <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-12 col-sm-12"><input type="text" class="inputElement" name="server_username" value="{$MODEL->get('server_username')}" ></div></td></tr>
				<tr>
				    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_PASSWORD', $QUALIFIED_MODULE)}</label></td>
				    <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-12 col-sm-12"><input type="password" class="inputElement" name="server_password" value="{$MODEL->get('server_password')}" ></div></td></tr>
				<tr>
				    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_FROM_EMAIL', $QUALIFIED_MODULE)}</label></td>
				    <td class="{$WIDTHTYPE} fieldValue" >
					<div class=" col-lg-6 col-md-11 col-sm-12 pull-left">
					<input type="text" class="inputElement" name="from_email_field" data-rule-email="true" data-rule-illegal="true" value="{$MODEL->get('from_email_field')}" >
					
					</div>
					<span class="fa fa-info-circle alertShow mt20  "></span>
					<div class="alerthide m0">
					<div class="tooltiptext ">
					<div class="vt-default-callout vt-info-callout col-md-5 pull-right ">
					<h4 class="vt-callout-header" >
						<span class="fa fa-info-circle"></span>NOTE
					</h4>
					<p >If "From Email" field is set to blank then the User Email address will be picked up.</p>
					</div>
					{* <div class="alert alert-info alert-mini note">NOTE: If "From Email" field is set to blank then the User Email address will be picked up.</div>  *}
					</div>
					</div>
	
	</td>
				</tr>
				<!-- <tr>
				    <td class="{$WIDTHTYPE} fieldLabel">&nbsp;</td>
				    <td class="{$WIDTHTYPE} fieldValue" >
						<div class=" col-lg-6 col-md-12 col-sm-12">
							<div class="alert alert-info alert-mini">{vtranslate('LBL_OUTGOING_SERVER_FROM_FIELD', $QUALIFIED_MODULE)}</div></div></td>
				</tr> -->
				<tr>
				    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_REQUIRES_AUTHENTICATION', $QUALIFIED_MODULE)}</label></td>
				    <td class="{$WIDTHTYPE}" style="border-left: none;">
					<div class=" col-lg-6 col-md-12 col-sm-12 fieldValueNew chckbox Default ml20" style="width:33px!important">
					<input type="checkbox" name="smtp_auth" class="inputElement" {if $MODEL->isSmtpAuthEnabled()}checked{/if} >
					</div>
					</td>
				</tr>
			    </tbody>
			</table>
			</div>
		    </div>
		    <br>	
		    <div class='modal-overlay-footer clearfix'>
			<div class="row clearfix">
			    <div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
				<button type='submit' class='btn btn-primary saveButton' >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
				<a class='cancelLink btn btn-danger' data-dismiss="modal" href="#">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			    </div>
			</div>
		    </div>
		</div>
	    </form>
    </div>
{/strip}
