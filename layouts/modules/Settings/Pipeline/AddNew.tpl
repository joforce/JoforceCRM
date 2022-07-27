{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<div class="editViewPageDiv editViewContainer card mt0  pipeline_page_style" id="EditViewPipeline" style="padding-top:0px;">
	<div class="ml15 mr15 mt20 card-header-new">
            <h3>{vtranslate('KANBAN_SETTINGS', $QUALIFIED_MODULE)}</h3>&nbsp;{vtranslate('PIPELINE_CONFIGURATION', $QUALIFIED_MODULE)}
        </div>
	<br>
        <form class="pipeline-action-form main-menu-modal-form ml20" action method="POST">
	    <div> <h4>{vtranslate('Pipeline', $QUALIFIED_MODULE)}</h4> </div>
            <hr>

	    <input type="hidden" name="module" id="module" value="{$MODULE}">
            <input type="hidden" name="action" id="action" value="SaveAjax">
	    <input type="hidden" name="parent" id="parent" value="Settings">
	    <input type="hidden" name="pipe-mode" id="pipe-mode" value="{$MODE}">
            <input type="hidden" name="pipelineid" id="pipelineid" value="{$pipelineid}">
	    <div class="blockData">
                <br>
                <div class="hide errorMessage">
                    <div class="alert alert-danger">
                    	{vtranslate('LBL_TESTMAILSTATUS', $QUALIFIED_MODULE)}
                        <strong>{vtranslate('LBL_MAILSENDERROR', $QUALIFIED_MODULE)}</strong>
                    </div>
                </div>
		<div class="block">
		    <table class="table editview-table no-border">
			<tbody>
               		    <tr style="" class="pip">
				<td class="fieldLabel col-md-6">
	                    	    <label>{vtranslate('LBL_SELECT_MODULE')}  </label>
				</td>
				<td class="fieldValue col-md-6">
				    <div class="col-lg-6 col-md-8 col-sm-12">
	                  	    	{if $MODE eq 'create'}
	                            	    <select class="select2 inputElement" name="kanban-module" id="kanban-module" required data-rule-required="true" aria-required="true" tabindex="-1" title="">
	                                        <option value=''>{vtranslate('LBL_SELECT')}</option>
                                        	{foreach item=modulelabel key=modulename from=$ALL_MODULES}
                                    		    <option class="option" value="{$modulename}">{vtranslate($modulelabel, 'Head')}</option>
                                    		{/foreach}
                            		    </select>
		            	    	{elseif $MODE eq 'edit'}
		                    	    <input class="inputElement" value="{$sel_modulename}" disabled required style="cursor: not-allowed;">
					    <input type="hidden" name="kanban-module" id="kanban-module" value="{$sel_modulename}"/>
	            	    		{/if}
			    	    </div>
				</td>
			    </tr>

            	    	    <tr style="" class="pip">
	                        <td class="fieldLabel col-md-6">
				    <label>{vtranslate('LBL_PICKLIST')} </label>
				</td>
				<td class="fieldValue col-md-6">
	            		    <div id="pipeline-select" class="col-lg-6 col-md-8 col-sm-12">
		                    	{if empty($sel_picklist)}
	        	            	    <select class="select2 inputElement" name="pipe-picklists" value='' id="pipe-picklists" required>
                	            		<option>{vtranslate('LBL_SELECT')}</option>
                            		    </select>
	                    		{else}
        	            		    <select class="select2 inputElement" name="pipe-picklists" value='{$sel_picklist}' id="pipe-picklists" required>
                            	    		<option>{vtranslate('LBL_SELECT')}</option>
	                    	    		{foreach from=$picklists item=picklistlabel  key=picklistname}
		                    		    <option value="{$picklistname}" {if $sel_picklist eq $picklistname} selected {/if}>{vtranslate($picklistlabel)}</option>
                	    	    		{/foreach}
                            		    </select>
	                    		{/if}
                    	    	    </div>
				</td>
		    	    </tr>

			    <tr style="" class="">
				<td class="fieldLabel col-md-6">
				    <label>{vtranslate('LBL_SELECT_FIELD',$QUALIFIED_MODULE)}</label>
				</td>
				<td class="fieldValue col-md-6">
			            <div class="col-lg-12 col-md-8 col-sm-12 ">
			            	<select class=" col-lg-6 pull-left select2 form-control" id="role2fieldnames" multiple required name="role2fieldnames[]" {if empty($SELECTED_MODULE_FIELDS) }  placeholder="{vtranslate("LBL_SELECT",$QUALIFIED_MODULE)}" {/if}>
	                		    {foreach key=FIELD_NAME item=FIELD_MODEL from=$MODULE_FIELDS}
			                    	<option class="role2fieldnames_{$FIELD_NAME}" value="{$FIELD_NAME}"
                			            {if is_array($SELECTED_MODULE_FIELDS)} 
		        	                    	{if in_array($FIELD_NAME, $SELECTED_MODULE_FIELDS)} selected {/if}
		                	            {/if}>
		                        	    {vtranslate($FIELD_MODEL->label,$SELECTED_MODULE_NAME)}
			                    	</option>
        	        		    {/foreach}
			            	</select>
				    	{* <div class="alert alert-info alert-mini alertShow  ml20 mt15"></div> *}
										<span class="fa fa-info-circle alertShow  ml20 mt15 "></span>
										<div class="alerthide">
					<div class="col-sm-12 tooltiptext ">
					<div class="row">
					<div class="col-md-6"></div>
						<div class=" vt-default-callout vt-info-callout col-md-5 pull-right">
						<h4 class="vt-callout-header">
							<i class="fa fa-info-circle help-icon">NOTE:</i> 
						</h4>
						<p> {vtranslate('NAMEFIELDS_INFO', $QUALIFIED_MODULE)}</p>
						</div>
					</div>
					</div>
					</div>
		            	    </div>
				</td>
			    </tr>

	        	    <tr style="" class="">
        	    		<td class="fieldLabel col-md-6">
				    <label>{vtranslate('LBL_RECORDS_PER_PAGE', $QUALIFIED_MODULE)}</label>
				</td>
				<td class="fieldValue col-md-6">
                		    <div id="pipeline-select" class="col-lg-12 col-md-8 col-sm-12 pip">
		                    	<input name="records_per_page" id="records_per_page" type="number" class="records_per_page inputElement col-lg-6 pull-left" {if empty($pipeline_info['records_per_page'])} value="100" {else} value="{$pipeline_info['records_per_page']}" {/if} style="width:100%;" min="100" max="1000">
								
				    	{* <div class="alert alert-info alert-mini col-lg-6"><i class="fa fa-info-circle help-icon"></i> NOTE: {vtranslate('MINIMUM_AND_AMXIMUN', $QUALIFIED_MODULE)}</div> *}
									<span class="fa fa-info-circle alertShow1  ml20 mt15 "></span>
										<div class="alerthide1 mb50 mt0">
									<div class="col-sm-12 tooltiptext1 ">
									<div class="row">
									<div class="col-md-6"></div>
										<div class=" vt-default-callout vt-info-callout col-md-5 mt0 pull-right ">
										<h4 class="vt-callout-header">
											<i class="fa fa-info-circle help-icon">NOTE:</i> 
										</h4>
										<p> {vtranslate('MINIMUM_AND_AMXIMUN', $QUALIFIED_MODULE)}</p>
										</div>
									</div>
									</div>
									</div>
        	            	    </div>
				</td>
		            </tr>
                	</tbody>
		    </div>
		</table>
	    </div>
	</form>

	<br>
	<div class='modal-overlay-footer clearfix'>
            <div class="row clearfix">
                <div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
                    <button class="btn btn-primary save-section" id="save-pipeline" type="submit" name="saveButton">
                    	<strong>{vtranslate('LBL_SAVE', $MODULE)}</strong>
                    </button>
		    <a class='cancelLink btn btn-danger' href="{$SITEURL}Pipeline/Settings/Index">{vtranslate('LBL_CANCEL', $MODULE)}</a>
                </div>
            </div>
        </div>
</div>
