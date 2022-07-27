<form id="OutgoingServerForm" method="POST" style="display: inline-block;width: -moz-available;">
    <input type="hidden" name="default" value="false" />
    <input type="hidden" name="server_port" value="0" />
    <input type="hidden" name="server_type" value="email"/>
    <input type="hidden" name="id" value="{$OUTGOING_SERVER_MODEL->get('id')}"/>
    <div class="blockData">
        <br>
        <div class="block">
            <div>
               <center><h4>{vtranslate('LBL_MAIL_SERVER_SMTP', $QUALIFIED_MODULE)}</h4></center>
            </div>
            <hr>
            <table class="table editview-table no-border">
                <tbody>
                <tr class="offset-md-2">
                    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_SERVER_TYPE', $QUALIFIED_MODULE)}</label></td>
                    <td class="{$WIDTHTYPE} fieldValue">
                        <div class=" col-lg-6 col-md-6 col-sm-12">
                            <select class="select2 inputElement col-lg-12 col-md-12 col-lg-12" name="serverType">
                                <option value="">{vtranslate('LBL_SELECT_OPTION','Head')}</option>
                                <option value="{"ssl://smtp.gmail.com:465"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "ssl://smtp.gmail.com:465"} selected {/if}>{vtranslate('LBL_GMAIL', $QUALIFIED_MODULE)} </option>
                                <option value="{"smtp.live.com"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "smtp.live.com"} selected {/if}>{vtranslate('LBL_HOTMAIL', $QUALIFIED_MODULE)}</option>
                                <option value="{"smtp-mail.outlook.com"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "smtp.live.com"} selected {/if}>{vtranslate('LBL_OFFICE365', $QUALIFIED_MODULE)}</option>
                                <option value="{"smtp.mail.yahoo.com"}" {if {$OUTGOING_SERVER_MODEL->get('server')} eq "smtp.mail.yahoo.com"} selected {/if}>{vtranslate('LBL_YAHOO', $QUALIFIED_MODULE)}</option>
                                <option value="">{vtranslate('LBL_OTHERS', $QUALIFIED_MODULE)}</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_SERVER_NAME', $QUALIFIED_MODULE)}</label>&nbsp;</td>
                    <td class="{$WIDTHTYPE} fieldValue"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="text" class="inputElement" name="server" data-rule-required="true" value="{$OUTGOING_SERVER_MODEL->get('server')}" ></div></td></tr>
                <tr>
                    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_USER_NAME', $QUALIFIED_MODULE)}</label></td>
                    <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-6 col-sm-12"><input type="text" class="inputElement" name="server_username" value="{$OUTGOING_SERVER_MODEL->get('server_username')}" ></div></td></tr>
                <tr>
                    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_PASSWORD', $QUALIFIED_MODULE)}</label></td>
                    <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-6 col-sm-12"><input type="password" class="inputElement" name="server_password" value="{$OUTGOING_SERVER_MODEL->get('server_password')}" ></div></td></tr>
                <tr>
                    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_FROM_EMAIL', $QUALIFIED_MODULE)}</label></td>
                    <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-6 col-md-6 col-sm-12"><input type="text" class="inputElement" name="from_email_field" data-rule-email="true" data-rule-illegal="true" value="{$OUTGOING_SERVER_MODEL->get('from_email_field')}" ></div> </td>
                </tr>
                <tr>
                    <td class="{$WIDTHTYPE} fieldLabel">&nbsp;</td>
                    <td class="{$WIDTHTYPE} fieldValue" ><div class=" col-lg-12 col-md-12 col-sm-12"><div class="alert alert-info alert-mini">{vtranslate('LBL_OUTGOING_SERVER_FROM_FIELD', $QUALIFIED_MODULE)}</div></div></td>
                </tr>
                <tr>
                    <td class="{$WIDTHTYPE} fieldLabel"><label>{vtranslate('LBL_REQUIRES_AUTHENTICATION', $QUALIFIED_MODULE)}</label></td>
                    <td class="{$WIDTHTYPE}" style="border-left: none;"><div class=" col-lg-6 col-md-6 col-sm-12"><input type="checkbox" name="smtp_auth" {if $OUTGOING_SERVER_MODEL->isSmtpAuthEnabled()}checked{/if} ></div></td>
                </tr>
                </tbody>
            </table>
        </div>
        <br>
    </div>
</form>
