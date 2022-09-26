{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
{* modules/Settings/Picklist/views/IndexAjax.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
{strip}
	<div class="row" style="margin-left:150px">
        <div class="form-group w-50">
            <div class="col-form-label col-lg-2 col-md-2">&nbsp;</div>
            <div class="controls col-lg-10 col-md-10 ml-4">
                <select class="select2 form-control" id="role2picklist" multiple name="role2picklist[]">
                    {foreach key=PICKLIST_KEY item=PICKLIST_VALUE from=$ALL_PICKLIST_VALUES}
                        <option value="{$PICKLIST_VALUE}" data-id="{$PICKLIST_KEY}" {if in_array($PICKLIST_VALUE,$ROLE_PICKLIST_VALUES)} selected {/if}>
                            {vtranslate($PICKLIST_VALUE,$SELECTED_MODULE_NAME)}
                        </option>
                    {/foreach}
                </select>
            </div>
        </div>
	</div>
    <br>
    <div style="margin-left:150px">
        <button id="saveOrder" class="btn btn-primary">
            {vtranslate('LBL_SAVE',$QUALIFIED_MODULE)}
        </button>
    </div>
    
{/strip}
