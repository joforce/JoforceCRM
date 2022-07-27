{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<div class="row">
    <div class="language-editor-module outside-border mt30">
	<div class="accordion-module labels-content">
	    <h3 class="card-title">{vtranslate($HEADING, $MODULE)}
                <span class="toggle-icon fa fa-caret-right rotate" style="display: inline-block;"></span>
            </h3>
	</div>
	<div class="accordion-panel" style="display:none">
	<table class="table table-responsive language-table table-hover mt30" data-filename="{$SAVE_FILE}" data-file-path="{$FILE}" >
    	    <thead>
    	        <th>{vtranslate('LBL_MODULE_LABELS', $QUALIFIED_MODULE)}</th>
		<th class="label-button">{vtranslate('LBL_TRANSLATION', $QUALIFIED_MODULE)}
		    <button class="btn btn-primary" id="add-new-label" data-hint="lbl" data-set-module="{$HEADING}">
                        <i class="fa fa-plus" data-hint="lbl" type="button"></i>
                    </button>
		</th>
	    </thead>
			
	    <tbody class="{$HEADING}_lbl">
	    	{if !$NOTHING}
    	    	    {if $LANGUAGE_STRING_ARRAY}
		    	{include file="PicklistEditAjax.tpl"|vtemplate_path:$QUALIFIED_MODULE HINT='lbl'}
		    {else}
			<tr></tr>
	            {/if}
		{else}
		    <tr></tr>
	    	{/if}
	    </tbody>
	</table>
	<table class="table table-responsive language-table table-hover mt30" data-filename="{$SAVE_FILE}" data-file-path="{$FILE}">
            <thead>
                <th>{vtranslate('LBL_JAVA_LABELS', $QUALIFIED_MODULE)}</th>
                <th class="label-button">{vtranslate('LBL_TRANSLATION', $QUALIFIED_MODULE)}
                    <button class="btn btn-primary" id="add-new-js-label" data-hint="js_lbl" data-set-module="{$HEADING}">
                        <i class="fa fa-plus" data-hint="js_lbl" type="button"></i>
                    </button>
                </th>
            </thead>

            <tbody class="{$HEADING}_js_lbl">
                {if !$NOTHING}
                    {if $JS_LANGUAGE_STRING_ARRAY}
                        {include file="PicklistEditAjax.tpl"|vtemplate_path:$QUALIFIED_MODULE HINT='js_lbl' LANGUAGE_STRING_ARRAY=$JS_LANGUAGE_STRING_ARRAY}
		    {else}
			<tr></tr>
                    {/if}
		{else}
		    <tr></tr>
                {/if}
            </tbody>
        </table>
	</div>
    </div>
</div>
