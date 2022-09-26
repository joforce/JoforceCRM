{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
-->*}
{strip}
    <div class="editViewContainer form-horizontal  card" id="TermsAndConditionsContainer">
    <div class="block TermsAndConditionsContainer card-body">
                <div>
                    <h4 class="card-header-new terms_and_condition">{vtranslate('LBL_TERMS_AND_CONDITIONS', $QUALIFIED_MODULE)}</h4>
                </div>
                <hr>
                <div class="contents row form-group">
                <div class="col-lg-2 col-md-1 col-sm-2 " ></div>
                    <div class="offset-lg-1 col-lg-3 col-md-4 col-sm-3 col-form-label fieldLabel pr0"><label>{vtranslate('LBL_SELECT_MODULE', 'Head')}</label></div>
                    <div class="fieldValue col-lg-4 col-md-7 col-sm-4 pl0">
                        <select class="select2-container select2 inputElement col-sm-6 selectModule">
                            {foreach item=MODULE_NAME from=$INVENTORY_MODULES}
                                <option value={$MODULE_NAME}>{vtranslate({$MODULE_NAME}, {$MODULE_NAME})}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <br>
                <div class="offset-lg-1 col-lg-11 col-md-11 col-sm-11 ml-5">
                    <textarea class=" TCContent form-control" rows="10" placeholder="{vtranslate('LBL_SPECIFY_TERMS_AND_CONDITIONS', $QUALIFIED_MODULE)}" style="width:100%;font-size:14px;" >{$CONDITION_TEXT}</textarea>
                </div>
                <div class='clearfix'></div>
                <br>
            </div>
	<br>
        <div class='modal-overlay-footer clearfix terms_and_conditions_footer '>
            <div class="row clearfix">
                <div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
                    <button type='submit' class='btn btn-primary saveButton saveTC hide' type="submit" >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
                </div>
            </div>
        </div>
    </div>
{/strip}

