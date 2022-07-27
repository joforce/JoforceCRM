{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Settings/Roles/views/EditAjax.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="editViewPageDiv card mt0 viewContent">
        <div class="editViewHeader card-header-new p10 ml10">
                {if $RECORD_MODEL->getId()}
                    <h4>
                        {vtranslate('LBL_EDIT_ROLE', $QUALIFIED_MODULE)}
                    </h4>
                {else}
                    <h4>
                        {vtranslate('LBL_CREATE_ROLE', $QUALIFIED_MODULE)}
                    </h4>
                {/if}
            </div>
        <form class="form-horizontal" id="EditView" name="EditRole" method="post" action="{$SITEURL}index.php" enctype="multipart/form-data">
            <div class="editViewBody">
                <div class="editViewContents">
                    <input type="hidden" name="module" value="Roles">
                    <input type="hidden" name="action" value="Save">
                    <input type="hidden" name="parent" value="Settings">
                    {assign var=RECORD_ID value=$RECORD_MODEL->getId()}
                    <input type="hidden" name="record" value="{$RECORD_ID}" />
                    <input type="hidden" name="mode" value="{$MODE}">
                    <input type="hidden" name="profile_directly_related_to_role_id" value="{$PROFILE_ID}" />
                    {assign var=HAS_PARENT value="{if $RECORD_MODEL->getParent()}true{/if}"}
                    {if $HAS_PARENT}
                        <input type="hidden" name="parent_roleid" value="{$RECORD_MODEL->getParent()->getId()}">
                    {/if}
                     <div name='editContent roles-edit-content col-lg-12 col-md-12 col-sm-12'>
                      <div class="col-lg-3 col-md-3 col-sm-3"></div>
               	      <div class="alluser col-lg-6 col-md-12 col-sm-6 mt0 ">
                        <div class="form-group pl15">
                            <label class="col-form-label fieldLabel col-lg-12 col-md-12 col-sm-12 pr0 pl0" style="width: 60% !important; margin:39px -38px;">
                                <strong>{vtranslate('LBL_NAME', $QUALIFIED_MODULE)}&nbsp;<span class="red-border">*</span></strong>
                            </label>
                            <div class="controls fieldValue col-lg-12 col-md-12 col-sm-12 pl0" >
                                <div class=""> <input type="text" class="inputElement" style="margin-top: 5px;" name="rolename" id="profilename" value="{$RECORD_MODEL->getName()}" data-rule-required='true'  />
                                </div> </div>
                        </div>
                        <div class="form-group pl15">
                            <label class="col-form-label2 fieldLabel2 col-lg-12 col-md-3 col-sm-12 pr0 pl0" >
                                <strong>{vtranslate('LBL_REPORTS_TO', $QUALIFIED_MODULE)}</strong>
                            </label>
                            <div class="controls fieldValue col-lg-12 col-md-12 col-sm-12 mt5 pl0" >
                                <input type="hidden" name="parent_roleid" {if $HAS_PARENT}value="{$RECORD_MODEL->getParent()->getId()}"{/if}>
                                <div class=""> <input type="text" class="inputElement" style="margin-top: 10px;" name="parent_roleid_display" {if $HAS_PARENT}value="{$RECORD_MODEL->getParent()->getName()}"{/if} readonly>
                                </div></div>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label3 fieldLabel3 col-lg-12 col-md-12 col-sm-12">
                                <strong>{vtranslate('LBL_CAN_ASSIGN_RECORDS_TO', $QUALIFIED_MODULE)}</strong>
                            </label>
                            <div class="controls fieldValue col-lg-12 col-md-12 col-sm-12">
                                <div class="form-check">
                                    <label>
                                    <input type="radio" value="1"{if !$RECORD_MODEL->get('allowassignedrecordsto')} checked=""{/if} {if $RECORD_MODEL->get('allowassignedrecordsto') eq '1'} checked="" {/if} name="allowassignedrecordsto" data-handler="new" class="alignTop"/>
                                    &nbsp;{vtranslate('LBL_ALL_USERS',$QUALIFIED_MODULE)}</label>
                                </div>
                                <div class="form-check">
                                    <label>
                                    <input type="radio" value="2" {if $RECORD_MODEL->get('allowassignedrecordsto') eq '2'} checked="" {/if} name="allowassignedrecordsto" data-handler="new" class="alignTop"/>
                                    &nbsp;{vtranslate('LBL_USERS_WITH_SAME_OR_LOWER_LEVEL',$QUALIFIED_MODULE)}</label>
                                </div>
                                <div class="form-check">
                                    <label>
                                    <input type="radio" value="3" {if $RECORD_MODEL->get('allowassignedrecordsto') eq '3'} checked="" {/if} name="allowassignedrecordsto" data-handler="new" class="alignTop"/>
                                    &nbsp;{vtranslate('LBL_USERS_WITH_LOWER_LEVEL',$QUALIFIED_MODULE)}</label>
                                </div>
                            </div>
                        </div>
			<div>
	                    <input type="hidden" value="1" name="profile_directly_related_to_role" data-handler="new" />
			</div>
                        <div class="col-lg-3 col-md-3 col-sm-3"></div>
                    </div>
		</div>
        <div class="form-group Privileges">
            <label class="col-form-label fieldLabel col-lg-12 col-md-12 col-sm-12">
                <strong>{vtranslate('LBL_PRIVILEGES',$QUALIFIED_MODULE)}</strong>
            </label>
        </div>        
        
                        <br>
                        <div class="form-group " data-content="new" >
                            <div class="profileData col-sm-12">
                            </div>
                        </div>
                        <div class="form-group " data-content="existing">
                           
                        </div>
                    </div>
                </div>
            <div class='modal-overlay-footer  clearfix'>
                <div class="row clearfix">
                    <div class=' textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
                        <button type='submit' class='btn btn-primary saveButton' >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
                        <a class='cancelLink btn btn-danger'  href="javascript:history.back()" type="">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>
</div>
</div>
