{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}

<div style = "margin-bottom: 10px">
    <div class = "form-group">
        <div class = "col-lg-2" style="margin-top:8px">
            <label class ="col-form-label" for="saved_maps">{'LBL_USE_SAVED_MAPS'|@vtranslate:$MODULE}</label>
        </div>
        <div class="col-lg-4">
            <select name="saved_maps" id="saved_maps" class="select2 form-control" onchange="Head_Import_Js.loadSavedMap();">
                <option id="-1" value="" selected>--{'LBL_SELECT_SAVED_MAPPING'|@vtranslate:$MODULE}--</option>
                {foreach key=_MAP_ID item=_MAP from=$SAVED_MAPS}
                    <option id="{$_MAP_ID}" value="{$_MAP->getStringifiedContent()}">{$_MAP->getValue('name')}</option>
                {/foreach}
            </select>
        </div>
        <div id="delete_map_container" class ="col-lg-1" style="display:none; margin-top: 10px">
            <a class="fa fa-trash cursorPointer" onclick="Head_Import_Js.deleteMap('{$FOR_MODULE}');" alt="{'LBL_DELETE'|@vtranslate:$FOR_MODULE}"></a>
        </div>
    </div>
</div>


