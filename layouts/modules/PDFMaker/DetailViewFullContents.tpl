{*<!--                                                          
  /*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0 
  * ("License"); You may not use this file except in compliance with the License
  * The Original Code is: vtiger CRM Open Source                  
  * The Initial Developer of the Original Code is vtiger.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.                                          
  * Contributor(s): JoForce.com                                   
  ********************************************************************************/
  -->*}
{strip}
<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
<div class="col-sm-12 col-xs-12">
    <div class=" detailview-header-block pdfkil">
    	<div class="detailview-header">
            <div class="row">
	        <div class="col-sm-6 col-lg-6 col-md-6">
	    	    <div class="recordBasicInfo">
		        <div class="info-row">
	                    <h4>
            		        <span class="recordLabel pushDown" title="{$RECORD->getName()}">
	        	            <span class="templatename">{$RECORD->getName()}</span>&nbsp;
        	                </span>
	                    </h4>
            		</div>
		    </div>
	        </div>
                {include file="DetailViewActions.tpl"|vtemplate_path:$MODULE}
            </div>
        </div>
	{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
        <div class="detailview-content pdf_detailview_content container-fluid">
            <div class="details row PDF_details_row">
                <div class="block  pdf_block">
                    {assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
                    <div>
                        <h4>{vtranslate('PDF Maker - Properties of ', $MODULE_NAME)} " {$RECORD->get('name')} "</h4>
                    </div>
                    <hr>
	<table class="table detailview-table no-border {if in_array($MODULE,array('PDFMaker'))} PDFMaker_details_view {/if} ">
		<tbody> 
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Name', $MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('name'))}</td>
			</tr>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Description', $MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('description'))}</td>
			</tr>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Module',$MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('module'))}</td>
			</tr>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Message',$MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('body'))}</td>
			</tr>
		</tbody>
	</table>
</div></div></div>
{/strip}
