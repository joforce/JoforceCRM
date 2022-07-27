{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}

<input type="hidden" name="file-path" id="file-path" value="{$FILE}" />
<input type="hidden" name="filename" id="filename" value="{$SAVE_FILE}" />
<div class="row">
    <div class="language-editor-module col-12 outside-border p0 mt30">
	<div class="accordion-module labels-content">
	    <h3 class="card-title">{vtranslate('LBL_MODULE_LABELS', $QUALIFIED_MODULE)}
		<span class="toggle-icon fa fa-caret-right rotate" style="display: inline-block;"></span>
	    </h3>
	</div>

	<table class="table table-responsive language-table accordion-panel table-hover mt30" id="module_labels" style="display:none">
	    <thead>
		<th>{vtranslate('LBL_LABEL', $QUALIFIED_MODULE)}</th>
		<th class="label-button">{vtranslate('LBL_TRANSLATION', $QUALIFIED_MODULE)}
		    <button class="btn btn-primary" id="add-new-label" data-hint="lbl">
		    	<i class="fa fa-plus" data-hint="lbl" type="button"></i>
		    </button>
	    	</th>
	    </thead>
	    <tbody>
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
    </div>
</div>

<div class="row">
    <div class="language-editor-module col-12 p0 outside-border">
	<div class="accordion-module labels-content">
	    <h3>{vtranslate('LBL_JAVA_LABELS', $QUALIFIED_MODULE)}
		<span class="toggle-icon fa fa-caret-right rotate" style="display: inline-block;"></span>
	    </h3>
	</div>
	<table class="table table-responsive language-table accordion-panel table-hover mt30" id="js_module_labels" style="display:none">
	    <thead>
		<th>{vtranslate('LBL_LABEL', $QUALIFIED_MODULE)}</th>
		<th class="label-button">{vtranslate('LBL_TRANSLATION', $QUALIFIED_MODULE)}
		    <button class="btn btn-primary" id="add-new-js-label" data-hint="js_lbl">
			<i class="fa fa-plus" data-hint="lbl" type="button"></i>
		    </button>
		</th>
	    </thead>
	    <tbody>
		<tr></tr>
	        {if !$NOTHING}  
        	    {if $JS_LANGUAGE_STRING_ARRAY}
			{include file="PicklistEditAjax.tpl"|vtemplate_path:$QUALIFIED_MODULE LANGUAGE_STRING_ARRAY=$JS_LANGUAGE_STRING_ARRAY HINT='js_lbl'}
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

<div class="row">
    <div class="customfields-languageeditor col-12 p0 language-editor-module outside-border">
	
	    <div class="accordion-module labels-content">
		<h3>{vtranslate('LBL_CUSTOM_FIELD_LABELS', $QUALIFIED_MODULE)}
		<span class="toggle-icon fa fa-caret-right rotate" style="display: inline-block;"></span></h3>
	    </div>
	    <table class="table table-responsive language-table accordion-panel table-hover mt30" style="display:none">
		<thead>
		    <tr>
			<th>{vtranslate('LBL_LABEL', $QUALIFIED_MODULE)}</th>
			<th>{vtranslate('LBL_TRANSLATION', $QUALIFIED_MODULE)}</th>
		    </tr>
		</thead>
		<tbody id="customfield-tbody">
	            {include file="PicklistEditAjax.tpl"|vtemplate_path:$QUALIFIED_MODULE LANGUAGE_STRING_ARRAY=$CF_LANGUAGE_STRING_ARRAY HINT='lbl'}
        	</tbody>
	    </table>
	
    </div>
</div>

<div class="row">
    <div class="picklist-languageeditor col-12 p0 language-editor-module outside-border">
	<div class="accordion-module labels-content picklist">
	    <h3>{vtranslate('PICKLIST VALUES AND LABELS', $QUALIFIED_MODULE)}
		<span class="toggle-icon fa fa-caret-right rotate" style="display:inline-block;"></span>
	    </h3>
	</div>

	<table class="table table-responsive language-table accordion-panel table-hover picklist mt30" style="display:none">
	    <thead>
		<tr class="row accordion-heading">
		    <th class="col-sm-2">
		    	<label>{vtranslate('SELECT_PICKLIST', $QUALIFIED_MODULE)} : </label>
		    </th>
		    <th class="col-sm-4">
		    	<select class="langugeeditor-picklist select2 inputElement" id="langugeeditor-picklist">
			    <option value=''>Select</option>
			    {foreach from=$MODULE_PICKLIST_FIELDS key=fieldname item=fieldlabel}
			    	<option value="{$fieldname}">{vtranslate($fieldlabel, $MODULE)}</option>
			    {/foreach}
			</select>
		    </th>
		</tr>
	        <tr>
		    <th>{vtranslate('LBL_LABEL', $QUALIFIED_MODULE)}</th>
		    <th>{vtranslate('LBL_TRANSLATION', $QUALIFIED_MODULE)}</th>
		</tr>
	    </thead>
	    <tbody id="picklist-tbody">
	    </tbody>
	</table>
    </div>
</div>
