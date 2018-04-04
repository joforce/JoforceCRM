{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}
{strip}       

            <div class="main-container clearfix">
                <div class="editViewPageDiv full-width">
                        <div class="col-sm-12 col-xs-12 ">

	<form class="form-horizontal recordEditView" id="EditView" name="EditView" method="post" action="{$SITEURL}index.php">
		{assign var=QUALIFIED_MODULE_NAME value={$MODULE}}
		{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
		<input type="hidden" name="module" value="{$MODULE}" />
		<input type="hidden" name="action" value="Save" />
		<input type="hidden" name="record" value="{$RECORD_ID}" />

		<div class="editViewHeader row-fluid">
		{assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MODULE}
		{if $RECORD_ID neq ''}
			<span class="span8 font-x-x-large textOverflowEllipsis" title="{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} {decode_html($RECORD->get('name'))}">{vtranslate('LBL_EDITING', $MODULE)} {vtranslate($SINGLE_MODULE_NAME, $MODULE)} - {decode_html($RECORD->get('name'))}</span>
		{else}
			<span class="span8 font-x-x-large textOverflowEllipsis">{vtranslate('LBL_CREATING_NEW', $MODULE)} {vtranslate('template', $MODULE)}</span>
		{/if}
		</div>
	<div class = "editViewBody">
        <div class="editViewContents tabbable">
            <ul class="nav nav-tabs pdfTabs massEditTabs">
                <li id='div1' class="active" data-value="division1"><a data-toggle="tab" href="#division1"><strong>{vtranslate('LBL_GENERAL', $QUALIFIED_MODULE)}</strong></a></li>
                <li id='div2' data-value="division2"><a data-toggle="tab" href="#division2"><strong>{vtranslate('LBL_COMPANY_INFO', $QUALIFIED_MODULE)}</strong></a></li>
                <li id='div3' data-value="division3"><a data-toggle="tab" href="#division3"><strong>{vtranslate('Product Details', $QUALIFIED_MODULE)}</strong></a></li>
                <li id='div4' data-value="division4"><a data-toggle="tab" href="#division4"><strong>{vtranslate('Header / Footer', $QUALIFIED_MODULE)}</strong></a></li>

                <li id='div5' data-value="division5"><a data-toggle="tab" href="#division5"><strong>{vtranslate('LBL_SETTINGS', $QUALIFIED_MODULE)}</strong></a></li>

            </ul>
            <div class="tab-content layoutContent themeTableColor overflowVisible">
                <div class="tab-pane active" id="division1">

		<table class="table table-bordered blockContainer showInlineTable">
			<div class="col-md-12">
				<h4 class="">{vtranslate('SINGLE_PDFMaker', $MODULE)}</h4>
			</div>
			<div class="col-md-6">
                <div class="col-md-5 pr0">
				<div class="fieldLabel {$WIDTHTYPE}"><span class="red-border" style="left: 15px;"></span>{vtranslate('LBL_NAME', $MODULE)}</div>
                </div>
                <div class="col-md-6 pl0">
				<div class="fieldValue {$WIDTHTYPE}">
					<input id="{$MODULE}_editView_fieldName_templatename" type="text" class="input-large inputElement" data-validation-engine="validate[required]" name="templatename" value="{decode_html($RECORD->get('name'))}">
				</div>
                </div>
			</div>
			<div class="col-md-6">
                            <div class="col-md-5 pr0">
                                <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Template Active', $MODULE)}</div>
                            </div>
                            <div class="col-md-6 pl0">
                                <div class="fieldValue {$WIDTHTYPE}">
                                        <input id="{$MODULE}_editView_fieldName_status" type="checkbox" class="input-large" name="status" value="1" {if decode_html($RECORD->get('status')) == '1'} checked {else} {/if}>
				</div>
                </div>
			</div>
			<div class="col-md-6">
                <div class="col-md-5 pr0">
				<div class="fieldLabel {$WIDTHTYPE}">{vtranslate('LBL_DESCRIPTION', $MODULE)}</div>
                </div>
                <div class="col-md-6 pl0">
				<div class="fieldValue {$WIDTHTYPE}"><textarea class="row-fluid inputElement" id="description" name="description">{decode_html($RECORD->get('description'))}</textarea></div>
                </div>
			</div>
			<div class="col-md-6">
                <div class="col-md-5 pr0">
				<div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Module', $MODULE)}</div>
                </div>
                <div class="col-md-6 pl0">
				<div class="fieldValue {$WIDTHTYPE}">
					<span class="filterContainer" >
						<input type="hidden" name="moduleFields" data-value='{ZEND_JSON::encode($ALL_FIELDS)|escape}' />
						<span class="span4 conditionRow">
							<select class="inputElement select2 chzn-select" name="modulename" id='moduleName' style="height: 30px;width: 200px;">
								<option value="none">{vtranslate('LBL_SELECT_MODULE',$MODULE)}</option>
								{foreach key=MODULENAME item=FILEDS from=$ALL_FIELDS}
            							    <option value="{$MODULENAME}" {if decode_html($RECORD->get('module')) eq $MODULENAME} selected {/if}>{vtranslate($MODULENAME, $MODULENAME)}</option>
								{/foreach}
							</select>
						</span>&nbsp;&nbsp;
						<span class="span6">
							<select class="inputElement select2 chzn-select span5" id="templateFields" name="templateFields" style="height: 30px;width: 200px;">
								<option value="">{vtranslate('LBL_NONE',$MODULE)}</option>
							</select>
						</span>
					</span>
					
				</div>
                </div>
			</div>
		</table>
		</div>
                <div class="tab-pane inactive" id="division2">
                    <table class="table table-bordered blockContainer showInlineTable">
                        <div class="col-md-12">
                           <h4 class="">{vtranslate('Company Details', $MODULE)}</h4>
                        </div>
                        <div class="col-md-6">  
			    <input type='hidden' id = 'logo' value='{$LOGO}'>
                <div class="col-md-5 pr0">
		            <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Company Information', $MODULE)}</div>
                </div>
                <div class="col-md-6 pl0">
                            <div class="fieldValue {$WIDTHTYPE}">
                                <span class="filterContainer" >
                                    <span class="span4 conditionRow">
                                        <select name='companydetails' class="inputElement select2 chzn-select" style="height: 30px;width: 200px;">
			                    <option>Select</option>
				            <option>Company Information</option>
				        </select>
			            </span>&nbsp;&nbsp;
                                    <span class="span6">
                                        <select class="inputElement select2 chzn-select span5" id='companyFields' name='companyFields' style="height:30px;width:200px;">
                                            <option value="">{vtranslate('LBL_NONE',$MODULE)}</option>
                                        </select>
                                    </span>
				</span>
			    </div>
                </div>
			</div>
                        <div class="col-md-6">
                            <div class="col-md-5 pr0">
                            <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Terms and Conditions', $MODULE)}</div>
                            </div>
                            <div class="col-md-6 pl0">
                            <div class="fieldValue {$WIDTHTYPE}">
                                <span class="filterContainer" >
                                    <span class="span4 conditionRow">
                                        <select name='termsandconditions' class="inputElement select2 chzn-select" id='termsandconditions' style="height: 30px;width: 200px;">
                                           <option>Select</option>
                                           <option>Terms and Conditions</option>
                                        </select>
				    </span>
				</span>
                            </div>
                            </div>
                        </div>
		</table>
                <table class="table table-bordered blockContainer showInlineTable">
                        <div class="col-md-12">
                                <h4 class="">{vtranslate('General Fields', $MODULE)}</h4>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-5 pr0">
                            <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Current Date & Time', $MODULE)}</div>
                            </div>
                            <div class="col-md-6 pl0">
                            <div class="fieldValue {$WIDTHTYPE}">
                                <span class="filterContainer" >
                                    <span class="span4 conditionRow">
                                        <select id='currentdate_time' name='currentdate_time' class="inputElement select2 chzn-select" style="height:30px;width: 200px;">
                                            <option>Select</option>
						<option value='currentdate'>Current Date</option>
						<option value='currenttime'>Current Time</option>
                                        </select>
				    </span>
				</span>
                            </div>
                            </div>
                        </div>
		</table>
         </div>
         <div class="tab-pane inactive" id="division3">
             <table class="table table-bordered blockContainer showInlineTable">
                <div class="col-md-12">
                    <h4 class="">{vtranslate('Product Block', $MODULE)}</h4>
                </div>
		<div class="col-md-6">
                <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Product block template', $MODULE)}</div>
                </div>
                <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <span class="filterContainer" >
                            <select name='product_tax_block' id='product_tax_block' class='inputElement select2 chzn-select' style="height: 30px;width: 200px;">
                                <option value=''>{vtranslate('LBL_SELECT',$MODULE)}</option>
                                <option value='group'>Product block for group tax</option>
			        <option value='individual'>Product block for individual tax</option>
			    </select>
			</span>
		    </div>
            </div>
		</div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Product block', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <span class="filterContainer" >
                            <select name='product_block' id='product_block' class='inputElement select2 chzn-select' style="height: 30px;width: 200px;">
                                <option value="">{vtranslate('LBL_SELECT',$MODULE)}</option>
                                <option value='$productblock_start$'>Block Start</option>
                                <option value='$productblock_end$'>Block End</option>
                            </select>
			</span>&nbsp;&nbsp;
                        <span>
		            <i class="fa fa-info-circle fa-lg help-icon" rel="tooltip" title="{vtranslate('This block helps to export the product / service details from inventory modules.', $moduleName)}"></i>
                        </span>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Product fields', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <span class="filterContainer" >
                            <select class="inputElement select2 chzn-select" id="product_fields" name="product_fields" style="height: 30px;width: 200px;">
                            </select>
			</span>
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Service fields', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <span class="filterContainer" >
                            <select class="inputElement select2 chzn-select" id="service_fields" name="service_fields" style="height: 30px;width: 200px;">
                            </select>
                        </span>
                    </div>
                    </div>
                </div>
	     </table>	
	 </div>
         <div class="tab-pane inactive" id="division4">
              <table class="table table-bordered blockContainer showInlineTable">
                <div class="col-md-12">
                    <h3 class="">{vtranslate('Header/Footer', $MODULE)}</h3>
                </div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Header/Footer variables', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <span class="filterContainer" >
                            <select class="inputElement select2 chzn-select" id="header_footer" name="header_footer" style="height: 30px;width: 200px;">
                               <option value="">{vtranslate('LBL_NONE',$MODULE)}</option>
<!--                               <option value="##Page/Pages##">{vtranslate('Page / Pages',$MODULE)}</option>-->
                               <option value="##Page##">{vtranslate('Current Page',$MODULE)}</option>
                            </select>
                        </span>
                    </div>
                    </div>
                </div>
	      </table>
	 </div>
         <div class="tab-pane inactive" id="division5">
              <table class="table table-bordered blockContainer showInlineTable">
                <div class="col-md-12">
                    <h4 class="">{vtranslate('PDF Settings', $MODULE)}</h4>
                </div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('File Name', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <input id="{$MODULE}_editView_fieldName_templatename" type="text" class="input-large inputElement" data-validation-engine="validate[required]" name="filename" value="{$settings['file_name']}">
                    </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Page Format', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
		        <select name='page_format' class='inputElement select2 chzn-select' style="height: 30px;width: 200px;">
		            <option value='A3' {if $settings['page_format'] eq 'A3'} selected {/if}>A3</option>
			    <option value='A4' {if $settings['page_format'] eq 'A4'} selected {/if}>A4</option>
			    <option value='A5'{if $settings['page_format'] eq 'A5'} selected {/if}>A5</option>
			    <option value='A6'{if $settings['page_format'] eq 'A6'} selected {/if}>A6</option>
			</select>    
                    </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                <div class="col-md-5 pr0">
                   <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Page Orientation', $MODULE)}</div>
                </div>
                <div class="col-md-6 pl0">
                   <div class="fieldValue {$WIDTHTYPE}">
		        <select name='page_orientation' class='inputElement select2 chzn-select' style="height: 30px;width: 200px;"> 
		            <option value='P' {if $settings['page_orientation'] eq 'P'} selected {/if}>Portrait</option>      
		            <option value='L' {if $settings['page_orientation'] eq 'L'} selected {/if}>Landscape</option>
			</select>    
                   </div>
                   </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}">{vtranslate('Margins', $MODULE)}</div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                            <div class="col-md-6">
                            <span style='position:relative;margin-left:15px;'>Top</span>
		                    <input type='text' class="inputElement mb5" name='margin_top' style='position:relative;width:100%;margin-left:15px;' value='{$settings['margin_top']}'></div>
                            <div class="col-md-6">
                            <span style='position:relative;margin-left:15px;'>Bottom</span>
                            <input type='text' class="inputElement mb5" name='margin_bottom' style='position:relative;width:100%;margin-left:15px;' value='{$settings['margin_bottom']}'></div>
                            <div class="col-md-6">
                            <span style='position:relative;margin-left:15px;'>Left</span>
                            <input type='text' class="inputElement mb5" name='margin_left' style='position:relative;width:100%;margin-left:15px;' value='{$settings['margin_left']}'></div>
                            <div class="col-md-6">
                            <span style='position:relative;margin-left:15px;'>Right</span>
                            <input type='text' class="inputElement mb5" name='margin_right' style='position:relative;width:100%;margin-left:15px;' value='{$settings['margin_right']}'></div>
                    </div>
                    </div>
                </div> 
	      </table>
	 </div>
     </div>
        <div class="contents tabbable">
            <ul class="nav nav-tabs pdfContentTabs massEditTabs">
                <li id='body-tab' class="active"><a data-toggle="tab" href="#body"><strong>{vtranslate('Body', $QUALIFIED_MODULE)}</strong></a></li>
                <li id='header-tab'><a data-toggle="tab" href="#header"><strong>{vtranslate('Header', $QUALIFIED_MODULE)}</strong></a></li>
                <li id='footer-tab'><a data-toggle="tab" href="#footer"><strong>{vtranslate('Footer', $QUALIFIED_MODULE)}</strong></a></li>

            </ul>

     <div class="row-fluid padding-bottom1per active" id='body'>
	<textarea id="templatecontent" name="templatecontent">{$RECORD->get('body')}</textarea>
     </div>
     <div class="row-fluid padding-bottom1per inactive" id='header'>
        <textarea id="templatecontent-header" name="templatecontent-header">{$RECORD->get('header')}</textarea>
     </div>
     <div class="row-fluid padding-bottom1per inactive" id='footer'>
        <textarea id="templatecontent-footer" name="templatecontent-footer">{$RECORD->get('footer')}</textarea>
     </div>
     <input type='hidden' id='textarea-type' value='templatecontent'>
	</div>
	     <div class="modal-overlay-footer clearfix" style="border-left-width: 0px;">
		     <div class="row clearfix">
        		    <div class=' textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
                        	    <button type='submit' class='btn btn-success saveButton'>{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
                                	    <a class='cancelLink' href="javascript:history.back()" type="reset">{vtranslate('LBL_CANCEL', $MODULE)}</a>
	                    </div>
        	      </div>
	     </div>
           </div>
         </div>
      </form>
  </div>
</div>
</div>
{/strip}
