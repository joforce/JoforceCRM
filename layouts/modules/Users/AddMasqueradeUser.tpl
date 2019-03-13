{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
********************************************************************************/
-->*}
{strip}
<div class="modal-dialog">
        <div class="modal-content" id="add-main-menu-content-modal">
              <div class="modal-header" style="min-height:53px">
                <strong>{vtranslate('LBL_ADD_RECORD', $MODULE)}</strong>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: inherit;">
                        <span aria-hidden="true" class="fa fa-close"></span>
                </button>
              </div>

              <div class="modal-body">
                   <div class="">
                        <form class="masquerade-modal-form" action="index.php" method="post">
				<input type="hidden" name="module" value="{$MODULE}"/>
				<input type="hidden" name="record_id" value="{$RECORD_ID}"/>
				<input type="hidden" name="action" value="Save" />
				<input type="hidden" name="defaultCallDuration" value=5 />
				<input type="hidden" name="defaultOtherEventDuration" value=5 />
				<input type="hidden" name="isPreference" value="" />
				<input type="hidden" name="picklistDependency" value=[] />
				<input type="hidden" name="user_password" value="admin" />
				<input type="hidden" name="confirm_password" value="admin" />
                                <input type="hidden" name="is_admin" value=0 />
                                <input type="hidden" name="roleid" value="H6" />
                                <input type="hidden" name="lead_view" value="Today" />
                                <input type="hidden" name="currency_id" value=1 />
                                <input type="hidden" name="currency_grouping_pattern" value="123,456,789" />
                                <input type="hidden" name="currency_decimal_separator" value="." />
                                <input type="hidden" name="currency_grouping_separator" value="," />
                                <input type="hidden" name="currency_symbol_placement" value="$1.0" />
                                <input type="hidden" name="no_of_currency_decimals" value=2 />
                                <input type="hidden" name="truncate_trailing_zeros" value=0 />
                                <input type="hidden" name="phone_fax" value="" />
                                <input type="hidden" name="email2" value="" />
                                <input type="hidden" name="phone_work" value="" />
                                <input type="hidden" name="phone_mobile" value="" />
                                <input type="hidden" name="popupReferenceModule" value="{$MODULE}" />
                                <input type="hidden" name="reports_to_id" value="" />
                                <input type="hidden" name="reports_to_id_display" value="" />
                                <input type="hidden" name="phone_home" value="" />
                                <input type="hidden" name="phone_other" value="" />
                                <input type="hidden" name="signature" value="" />
                                <input type="hidden" name="internal_mailer" value=0 />
                                <input type="hidden" name="theme" value="softed" />
                                <input type="hidden" name="language" value="en_us" />
                                <input type="hidden" name="default_landing_page" value="Home" />
                                <input type="hidden" name="phone_crm_extension" value="" />
                                <input type="hidden" name="default_record_view" value="Summary" />
                                <input type="hidden" name="leftpanelhide" value=0 />
                                <input type="hidden" name="rowheight" value="medium" />
                                <input type="hidden" name="address_street" value="" />
                                <input type="hidden" name="address_country" value="" />
                                <input type="hidden" name="address_city" value="" />
                                <input type="hidden" name="address_postalcode" value="" />
                                <input type="hidden" name="address_state" value="" />
				<input type="hidden" name="is_masquerade_user" value=true />

                <div class="add-main-menu-content">

					<div class="col-md-12 pl0 mt10">
                        <div class="col-md-5 pl0 pr0">
						<label class="fieldLabel alignMiddle">{vtranslate('User Name', $MODULE)}</label>
                        </div>
                        <div class="col-md-7 pl0">
						<input type="text" name="user_name" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('lastname')}" />
                        </div>
					</div>
		
					<div class="col-md-12 pl0 mt10">    
                                                <div class="col-md-5 pl0 pr0">
                                                <label class="fieldLabel alignMiddle">{vtranslate('First Name', $MODULE)}</label>
                                                </div>
                                                <div class="col-md-7 pl0">
                                                <input type="text" name="first_name" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('firstname')}" />
                                                </div>
                                        </div>

					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                <label class="fieldLabel alignMiddle">{vtranslate('Last Name', $MODULE)}</label>
                                                </div>
                                                <div class="col-md-7 pl0">
                                                <input type="text" name="last_name" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('lastname')}" />
                                                </div>
                                        </div>
					
					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                                    <label class="fieldLabel alignMiddle">{vtranslate('Email', $MODULE)}</label>
                                                                    </div>
                                                                    <div class="col-md-7 pl0">
                                                <input type="text" name="email1" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('email')}" /></div>
                                        </div>

					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                                    <label class="fieldLabel alignMiddle">{vtranslate('Last Name', $MODULE)}</label>
                                                                    </div>
                                                                    <div class="col-md-7 pl0">
                                                <input type="text" name="title" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('title')}" /></div>
                                        </div>

					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                                    <label class="fieldLabel alignMiddle">{vtranslate('Phone', $MODULE)}</label>
                                                                    </div>
                                                                    <div class="col-md-7 pl0">
                                                <input type="text" name="phone" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('phone')}" /></div>
                                        </div>

					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                            <label class="fieldLabel alignMiddle">{vtranslate('Description', $MODULE)}</label>
                                                                    </div>
                                                                    <div class="col-md-7 pl0">
                                                <input type="text" name="description" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('des</div>cription')}" /></div>
                                        </div>

					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                                    <label class="fieldLabel alignMiddle">{vtranslate('Department', $MODULE)}</label>
                                                                    </div>
                                                                    <div class="col-md-7 pl0">
                                                <input type="text" name="department" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('dep</div>artment')}" /></div>
                                        </div>
	
					<div class="col-md-12 pl0 mt10">
                                                <div class="col-md-5 pl0 pr0">
                                                                    <label class="fieldLabel alignMiddle">{vtranslate('Secondary Email', $MODULE)}</label>
                                                                    </div>
                                                                    <div class="col-md-7 pl0">
                                                <input type="text" name="secondaryemail" class="inputElement col-md-7 pl0" value="{$RECORD_MODEL->get('sec</div>ondaryemail')}" />
                                        </div>
                                </div>
                                </div>
                        </form>
                    </div>
              </div>

              <div class="modal-footer ">
                    <center>
                        <button class="btn btn-success save-masquerade-user" id="save-masquerade-user" type="submit" name="saveButton" data-type="module">
                                <strong>Save</strong>
                        </button>
                        <a href="#" class="cancelLink" type="reset" data-dismiss="modal">Cancel</a>
                    </center>
              </div>
        </div>
</div>
{/strip}
