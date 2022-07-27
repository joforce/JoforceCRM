{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Settings/MenuManager/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="listViewPageDiv card detailViewContainer" id="listViewContent">
<div class="row contenthead card-header-new mb20 pb0" >
      <div class='editViewHeader ml10 mr10 mt0 row col-md-12 pagenamehead '>
          <h3 class="col-md-10 col-sm-11 mt10 col-9" >{if $MODULE eq 'LanguageEditor'} Language Editor {else} Sharing Rules {/if} </h3>
          
          <span class="fa fa-info-circle alertShow pull-right ml20 mt15 "></span>
          <hr>
      </div>
</div>
    {* <div class ="add_section modal-dialog" id="add-section-modalbody" style="width: 600px;margin: 30px auto;position: relative;">
    </div> *}
    <div class="alerthide">
    <div class="col-sm-12 tooltiptext ">
	<div class="row">
    <div class="col-md-6"></div>
	    <div class=" vt-default-callout vt-info-callout col-md-5 pull-right">
		<h4 class="vt-callout-header">
		    <span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE)}
		</h4>
		<p>{vtranslate('LBL_LANGUAGE_EDITOR_INFO', $QUALIFIED_MODULE)}</p>
	    </div>
	</div>
    </div>
    </div>

    <div class="col-sm-12  float-right">
	<button class="btn addButton btn-primary float-right" id="add-language">
	    {vtranslate('LBL_ADD_LANGUAGE', $QUALIFIED_MODULE)}
	</button>
    </div>

    <div class="col-sm-12">
    <div class="mt30  Languages">
	    <div class="select-module col-sm-6 pull-left col-md-12">
            	<label class="col-sm-4 pull-left textAlignCenter" style="padding-top: 8px;">
                    {vtranslate('SELECT_MODULE', $QUALIFIED_MODULE)}
                </label>
                <div class="col-sm-7 pull-left">
	            <select class="select2 inputElement " name="lanugageEditorModules" id="lanugageEditorModules">
                    	<option value=''>{vtranslate('LBL_SELECT_MODULE', $QUALIFIED_MODULE)}</option>
                        {foreach item=MODULE_NAME from=$ALL_MODULES}
                        <option value="{$MODULE_NAME}" {if $MODULE_NAME eq $SELECTED_MODULE_NAME} selected {/if}>
                            {* Calendar needs to be shown as TODO so we are translating using Layout editor specific translations*}
                            {if $MODULE_NAME eq 'Calendar'}
                                {vtranslate($MODULE_NAME, $QUALIFIED_MODULE)}
                            {else}
                                {vtranslate($MODULE_NAME, $MODULE_NAME)}
                            {/if}
                        </option>
                        {/foreach}
			<option value='Settings'>{vtranslate('LBL_SETTINGS', $QUALIFIED_MODULE)}(Admin)</option>
                    </select>
                </div>
	    </div>
	    <div class="select-language col-sm-6 pull-left col-md-12">
		<label class="col-sm-4 pull-left textAlignCenter" style="padding-top: 8px;">
                    {vtranslate('SELECT_LANGUAGE', $QUALIFIED_MODULE)}
                </label>
                <div class="col-sm-7 pull-left">
		    <select class="select2 inputElement" name="lanugageEditorLanguages" id="lanugageEditorLanguages">
                    	<option value=''>{vtranslate('LBL_SELECT_LANGUAGE', $QUALIFIED_MODULE)}</option>
                        {foreach item=LANGUAGE_NAME key=folder_name from=$LANGUAGES}
                        <option value="{$folder_name}" >
			    {$LANGUAGE_NAME}
                        </option>
                        {/foreach}
                    </select>
                </div>
	    </div>
        </div>
    </div>

    <div style="display:none;">
	<table>
	    </tbody>
		<tr style="display:none" class="dummy-row le-row" id="dummy">
		    <td>
			<input type="text" value='' class="language-input new-label" style="border-bottom: 1px solid #0444a7;" />
		    </td>
		    <td name="" class="meaning active-editable">
                    	<input type="text" value="" class="language-input new-value" />
                    	<span data-label="" class="fa fa-pencil editor" style="display:none;"></span>
                    	<div class="language-edit">
                            <span class="fa fa-check save-add-edit new-one" data-label="" ></span>
                            <span class="fa fa-close close-edit new-one" ></span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="languageEditorDiv" style="">
    </div>
</div>
