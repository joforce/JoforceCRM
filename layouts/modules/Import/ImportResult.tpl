{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Import/views/Main.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class='fc-overlay-modal modal-content'>
    <div class="overlayHeader">
        {assign var=TITLE value="{'LBL_IMPORT_SUMMARY'|@vtranslate:$MODULE}"}
	<h4 class="pull-left"> {$TITLE} </h4>
    </div>
    <div class='modal-body' style="margin-bottom:25px">
        <div class="summaryWidgetContainers">
            <input type="hidden" name="module" value="{$FOR_MODULE}" />
            <h4>{'LBL_TOTAL_RECORDS_SCANNED'|@vtranslate:$MODULE}&nbsp;&nbsp;:&nbsp;&nbsp;{$IMPORT_RESULT.TOTAL}</h4>
            {if $ERROR_MESSAGE neq ''}<span>{$ERROR_MESSAGE}</span>{/if}
            <hr>
            <div>{include file="Import_Result_Details.tpl"|@vtemplate_path:'Import'}</div>
        </div>
    </div>
</div>
