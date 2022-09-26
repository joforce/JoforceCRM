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
	<div id="addNotePadWidgetContainer" class='modal-dialog'>
        <div class="modal-content">
            {assign var=HEADER_TITLE value={vtranslate('LBL_ADD', $MODULE)}|cat:" "|cat:{vtranslate('LBL_NOTEPAD', $MODULE)}}
            {include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
            <form class="form-horizontal" method="POST">
                <div class="row" style="padding:10px;">
                    <label class="fieldLabel col-lg-5 mt-2">
                        <label class="mt10">{vtranslate('LBL_NOTEPAD_NAME', $MODULE)}<span class="redColor">*</span> </label>
                    </label>
                    <div class="fieldValue col-lg-7">
                        <input type="text" name="notePadName" class="inputElement" data-rule-required="true" />
                    </div>
                </div>
                <div class="row" style="padding:10px;">
                    <label class="fieldLabel col-lg-5 mt-2">
                        <label class="">{vtranslate('LBL_NOTEPAD_CONTENT', $MODULE)}</label>
                    </label>
                    <div class="fieldValue col-lg-7">
                        <textarea type="text" name="notePadContent" style="min-height: 100px;resize: none;width:100%"></textarea>
                    </div>
                </div>
                
                {include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
            </form>
        </div>
	</div>
{/strip}