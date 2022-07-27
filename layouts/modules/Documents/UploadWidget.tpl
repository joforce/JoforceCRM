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
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3">
        <form id="docUpload" class="form-horizontal recordEditView" name="docUpload" method="post" action="{$SITEURL}index.php">
        <div id="upload" data-filelocationtype="I">
            {if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
                <input type="hidden" name="picklistDependency" value='{Head_Util_Helper::toSafeHTML($PICKIST_DEPENDENCY_DATASOURCE)}' />
            {/if}
            <input type="hidden" name="module" value="{$MODULE}" />
            <input type="hidden" name="action" value="SaveAjax" />
            <input type="hidden" name="document_source" value="Head" />
            {if $RELATION_OPERATOR eq 'true'}
                <input type="hidden" name="relationOperation" value="{$RELATION_OPERATOR}" />
                <input type="hidden" name="sourceModule" value="{$PARENT_MODULE}" />
                <input type="hidden" name="sourceRecord" value="{$PARENT_ID}" />
                {if $RELATION_FIELD_NAME}
                    <input type="hidden" name="{$RELATION_FIELD_NAME}" value="{$PARENT_ID}" /> 
                {/if}
            {/if}        
            <div id="" class="dragdrop-dotted drop-area file-upload-wrapper" style="padding: 11%;">
                <span class="fa fa-upload document-panel-icon"></span>
                <div style="font-size:115%;">
                    {vtranslate('LBL_DRAG_&_DROP_FILE_HERE', $MODULE)}
                </div>
                <input style="display:none;" type="file" id="droppedFile" class="file-upload" name="filename" data-rule-required="true"/>	
                <input id="file_notes_title" type="hidden" data-fieldname="notes_title" data-fieldtype="string" class="inputElement nameField" name="notes_title" value="" data-rule-required="true" aria-required="true">																	
            </div>
        </div>
        </form>            
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="dragdrop-dotted drag-drop-solid">
                <span class="fa fa-folder document-panel-icon"></span>
                <div style="margin-top: 3%;">
                    <button onclick="Documents_Index_Js.uploadTo('U',{$PARENT_ID},'{$PARENT_MODULE}')" type="button" class="btn btn-default module-buttons" style="width:100%;">
                        <img style="widht:30px;height:30px;margin-top: -3px;margin-right: 4%;" title="Joforce" alt="Joforce" src="{$SITEURL}layouts/skins/images/JoForce.png">&nbsp;{vtranslate('LBL_TO_SERVICE', $MODULE_NAME, {vtranslate('LBL_JOFORCE', $MODULE_NAME)})}
                    </button>
                </div>
                <div style="margin: 1% 0;">
                    <p>Click here to upload document</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="dragdrop-dotted drag-drop-solid">
                <span class="fa fa-github document-panel-icon"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa fa-dropbox document-panel-icon"></span>&nbsp;&nbsp;&nbsp;
                <span class="fa fa-ellipsis-h document-panel-icon"></span>
                <div style="margin-top: 3%;">
                    <button onclick="Documents_Index_Js.createDocument('E',{$PARENT_ID},'{$PARENT_MODULE}')" type="button" class="btn btn-default module-buttons" style="width:100%;">
                        <span style="font-weight: normal;font-size: 12px;" class="fa fa-external-link"></span>&nbsp;{vtranslate('LBL_LINK_EXTERNAL_DOCUMENT', $MODULE)}
                    </button>
                </div>
                <div style="margin: 1% 0;">
                    <p>Click here to link your document already on your server or existing online repository or file like Dropbox, Google Drive, Github</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3">
            <div class="dragdrop-dotted drag-drop-solid">
                <span class="fa fa-pencil-square-o document-panel-icon"></span>&nbsp;&nbsp;&nbsp;
                <div style="margin-top: 3%;">
                    <button onclick="Documents_Index_Js.createDocument('W',{$PARENT_ID},'{$PARENT_MODULE}')" type="button" class="btn btn-default module-buttons" style="width:100%;">
                        <span style="font-weight: normal;font-size: 12px;" class="fa fa-file-text"></span>&nbsp;{vtranslate('LBL_CREATE_NEW', $MODULE_NAME, {vtranslate('SINGLE_Documents', $MODULE_NAME)})}
                    </button>
                </div>
                <div style="margin-top: 1%;">
                    <p>Click here to link to existing online repository or file like Dropbox, Google Drive, Github</p>
                </div>
            </div>
        </div>
    </div>
{/strip}