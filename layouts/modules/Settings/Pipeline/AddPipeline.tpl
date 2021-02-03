{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<div class="modal-dialog">
    <div class="modal-content" id="add-pipeline-modal">
        <div class="modal-header p10" style="min-height:50px">
            <strong>Select module and picklist to kanban view for the selected module</strong>
            <button type="button" class="close pipeline-close" aria-label="Close" data-dismiss="modal" style="color: inherit;">
                <span aria-hidden="true" class="fa fa-close"></span>
            </button>
        </div>

	<div class="modal-body">
            <form class="main-menu-modal-form" action>
		<input type="hidden" class="" name="pipe-mode" id="pipe-mode" value="{$MODE}">
            	<input type="hidden" class="" name="pipelineid" id="pipelineid" value="{$pipelineid}">
            	<div class="language-content">
               	    <div style="margin-bottom:10px;" class="col-lg-12 col-md-12">
                    	<label class="col-lg-4 col-md-4 fieldLabel pr0 pl0">{vtranslate('LBL_SELECT_MODULE')}  </label>
			<div class="col-lg-8 col-md-8 fieldValue pl0 pr0">
                  	    {if $MODE eq 'showmodal'}
                            	<select class="select2 inputElement" name="kanban-module" id="kanban-module" required data-rule-required="true" aria-required="true" tabindex="-1" title="">
                                    <option value=''>{vtranslate('LBL_SELECT')}</option>
                                    {foreach item=modulelabel key=modulename from=$ALL_MODULES}
                                    	<option class="option" value="{$modulename}">{vtranslate($modulelabel, 'Head')}</option>
                                    {/foreach}
                            	</select>
	            	    {elseif $MODE eq 'edit'}
	                    	<input class="inputElement" name="kanban-module" id="kanban-module" value="{$sel_modulename}" disabled required style="cursor: not-allowed;">
	            	    {/if}
			</div>
                    </div>

            	    <div style="margin-bottom:10px;" class="col-lg-12 col-md-12">
                        <label class="col-lg-4 col-md-4 fieldLabel pr0 pl0">{vtranslate('LBL_PICKLIST')} </label>
            		<div id="pipeline-select" class="col-lg-8 col-md-8 fieldValue pl0 pr0">
	                    {if empty($sel_picklist)}
        	            	<select class="select2 inputElement" name="pipe-picklists" value='' id="pipe-picklists" required>
                	            <option>{vtranslate('LBL_SELECT')}</option>
                            	</select>
	                    {else}
        	            	<select class="select2 inputElement" name="pipe-picklists" value='' id="pipe-picklists" required>
                            	    <option>{vtranslate('LBL_SELECT')}</option>
	                    	    {foreach from=$picklists item=picklistlabel  key=picklistname}
		                    	<option value="{$picklistname}" {if $sel_picklist eq $picklistname} selected {/if}>{vtranslate($picklistlabel)}</option>
                	    	    {/foreach}
                            	</select>
	                    {/if}
                    	</div>
		    </div>

	            <div style="margin-bottom:10px;" class="col-lg-12 col-md-12">
        	    	<label class="col-lg-4 col-md-4 fieldLabel pr0 pl0">{vtranslate('LBL_RECORDS_PER_PAGE', $QUALIFIED_MODULE)}</label>
                	<div id="pipeline-select" class="col-lg-8 col-md-8 fieldValue pl0 pr0">
	                    <input name="records_per_page" id="records_per_page" type="number" class="records_per_page inputElement" {if empty($sel_picklist['records_per_page'])} value="100" {else} value="{$sel_picklist['records_per_page']}" {/if} style="width:100%;" min="100" max="1000">
			    <i class="fa fa-info-circle help-icon" rel="tooltip" title=""></i><div class="alert alert-info alert-mini">NOTE: {vtranslate('MINIMUM_AND_AMXIMUN', $QUALIFIED_MODULE)}</div>
        	        </div>
	            </div>

		    <div style="margin-bottom:10px;" class="col-lg-12 col-md-12">
			<label class="col-lg-4 col-md-4 fieldLabel pr0 pl0">{vtranslate('LBL_SELECT_FIELD',$QUALIFIED_MODULE)}</label>
		        <div class="col-lg-8 col-md-8 fieldValue pl0 pr0">
		            <select class="select2 form-control" id="role2fieldnames" multiple required name="role2fieldnames[]" {if empty($SELECTED_MODULE_FIELDS) }  placeholder="{vtranslate("LBL_SELECT",$QUALIFIED_MODULE)}" {/if}>
                		{foreach key=FIELD_NAME item=FIELD_MODEL from=$MODULE_FIELDS}
		                    <option class="role2fieldnames_{$FIELD_NAME}" value="{$FIELD_NAME}"
                		        {if is_array($SELECTED_MODULE_FIELDS)} 
		                            {if in_array($FIELD_NAME, $SELECTED_MODULE_FIELDS)} selected {/if}
		                        {/if}>
		                        {vtranslate($FIELD_MODEL->label,$SELECTED_MODULE_NAME)}
		                    </option>
                		{/foreach}
		            </select>
		        </div>
		    </div>
                </div>
	    </form>
	</div>

	<div class="modal-footer ">
            <center>
            	<button class="btn btn-primary save-section" id="save-pipeline" type="submit" name="saveButton">
                    <strong>Save</strong>
            	</button>
            	<a href="#" class="cancelLink btn btn-secondary" type="reset" data-dismiss="modal">Cancel</a>
            </center>
    	</div>
    </div>  
</div>
