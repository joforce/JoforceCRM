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
    <div class=" detailview-header-block">
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
        <div class="detailview-content container-fluid ">
            <div class="details row card ms_Email_detail_view">
                <div class="block m50">
                    {assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
                    <div class="card-header-new">
                        <h4>{vtranslate('Email Template - Properties of ', $MODULE_NAME)} " {$RECORD->get('templatename')} "</h4>
                    </div>
                    <hr>
                    <table class="table detailview-table no-border ml20 mb50">
                        <tbody class="mb50"> 
                            <tr>
                                <td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Templatename', $MODULE_NAME)}</label></td>
                                <td class="fieldValue {$WIDTHTYPE}">{$RECORD->get('templatename')}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Description', $MODULE_NAME)}</label></td>
                                <td class="fieldValue {$WIDTHTYPE}">{nl2br($RECORD->get('description'))}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('LBL_MODULE_NAME', $MODULE_NAME)}</label></td>
                                <td class="fieldValue {$WIDTHTYPE}">{if $RECORD->get('module')} {vtranslate($RECORD->get('module'), $RECORD->get('module'))}{/if}</td>
                            </tr>
                            <tr>
                                <td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Subject',$MODULE_NAME)}</label></td>
                                <td class="fieldValue {$WIDTHTYPE}">{$RECORD->get('subject')}</td>
                            </tr>
                            <tr class="mb50">
                                <td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Message',$MODULE_NAME)}</label></td>
                                <td class="fieldValue mb50 {$WIDTHTYPE}">
                                    <iframe id="TemplateIFrame"  class="col-sm-12 col-xs-12 overflowScrollBlock h-70 col-6"></iframe>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>         
{/strip}
