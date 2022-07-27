{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Settings/Picklist/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="listViewPageDiv detailViewContainer form-horizontal picklist_page_style card" id="listViewContent">
        <br>
        <div class=" card-header-new picklist_setting" >
		<h4>Picklist</h4>
		
	    </div>
        <div class="detailViewInfo ms_picklist_ml">
            <div class="row form-group"><div class="col-lg-4 col-md-5 col-sm-3 col-form-label fieldLabel pr0">
                    <label class="fieldLabel ">{vtranslate('LBL_SELECT_MODULE',$QUALIFIED_MODULE)} </label>
                </div>
                <div class="fieldValue col-sm-3 col-lg-3 col-xs-12 pl0">
                    <select class="select2 inputElement" id="pickListModules" name="pickListModules">
                        <option value="">{vtranslate('LBL_SELECT_OPTION',$QUALIFIED_MODULE)}</option>
                        {foreach item=PICKLIST_MODULE from=$PICKLIST_MODULES}
                            <option {if $SELECTED_MODULE_NAME eq $PICKLIST_MODULE->get('name')} selected="" {/if} value="{$PICKLIST_MODULE->get('name')}">{vtranslate($PICKLIST_MODULE->get('name'),$PICKLIST_MODULE->get('name'))}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>

        <div id="modulePickListContainer">
            {include file="ModulePickListDetail.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
        </div>
        <br>
        <div id="modulePickListValuesContainer">
            {if empty($NO_PICKLIST_FIELDS)}
                {include file="PickListValueDetail.tpl"|@vtemplate_path:$QUALIFIED_MODULE}
            {/if}
        </div>

</div>
