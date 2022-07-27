<style>
    .formelement {
        padding: 1%;
    }

    .nextlink {
        text-align: end;
        padding: 2%;
    }

    .formelement_center {
        display: flex;
        justify-content: center;
    }

    .form_inner_element {
        padding: 1%;
        display: inline-block;
        width: 70%;
    }
    .form-group .offon{ margin-top:20px}
    .form_trigger_element {
        padding: 1%;
        display: inline-block;
        width: 100% !important;
        margin-bottom: 1%;
        min-height: 200px;
        min-width: 832px;
    }

    .add_border {
        border: 1px solid #fff;
        border-radius: 20px;
    }

    .add_border_withoutradious {
        border: 1px solid #ccc;
        margin-right: 2%;
        width:100%!important;
    }
    .formelement .add_border .form-group .col-form-label{ font-size: 16px;}
    @media screen and (max-width:1280px){
        .formelement .add_border .form-group .col-form-label{
            font-size:14px;
        }
    }
    @media screen and (min-width:300px) and (max-width:600px){
        .formelement .form_fields,.form_trigger_element{
            margin-left:2%;
        }
    }

    .condition_group {
        width: 48%;
        min-width: 350px;
        min-height: 177px;
    }
    .condition_group .Condition_add{
        min-height: 146px;
        /* width:449px!important; */
    }
    {* .conditionGroup .contents{
        overflow: scroll;
    } *}
    .fields_inline {
        display: inline-block;
        width: 100%;
        padding-top: 15px;
    }
    #accordion .card-header .form-group .div_field .col-form-label{ margin-top: 17px;}
    #collapseOne .card-body .form-group .col-form-label{ margin-top: 17px;}

    .condition_group .form-group {
        margin-right: 0;
        margin-bottom: 0;
    }

    .padding {
        padding: 1%;
    }

    .Condition_add {
        display: inline-flex;
    }
    .controls .card{ width: auto;}


    .left {
        float: left;
    }

    .right {
        float: right;
    }

    .workflow .EditWorkflow .form_trigger_element .contentsBackground .form-group .controls .input-group .dateField{ margin-top: 10px;}
    .workflow .controls .input-group .input-group-addon{ background-color: transparent; border: none;}
    .workflow .controls .input-group .input-group-addon .fa-calendar{ position: absolute;
    right: 0;
    top: 0px;
    padding: 13px 11px;
    z-index: 2;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;
    background: #dde2ef;}
    .workflow .controls .input-group .input-group-addon .fa-clock-o{ background-color: #dde2ef;position: absolute;
    right: 0;
    top: 10px;
    padding: 13px 11px;
    z-index: 2;
    -webkit-border-top-right-radius: 5px;
    -webkit-border-bottom-right-radius: 5px;}
    .workflow .contents .conditionRow .Condition_add .fields_inline .select2-container .select2-arrow{ margin-right: 22px;}



    .controls {
        text-align: initial;
    }

    .weekDaySelect {
        display: grid;
    }
    .conditionGroup{
        display: grid;
        padding-right: 15px;
        padding-left: 15px;
    }
   
    .form_inner_element .form-group .pull-right{
        margin-right: 100px;
    }
    .workflowPopover{
        min-width: 650px !important;
        {* top: 89px !important; *}
    }
    .or_condition{
        margin-bottom: 4%;
    }
    .form-group{
        margin-bottom: none;
    }
    .card-header .form-group{
        display: inline-flex;
        width: 100%;
    }
    .form-group .div_field{
        width: 80%;
    }
    .form-group .div_field label{
        width: 33%;
    }
    .form-group .div_active{
        width: 15%;
        text-align: initial;
    }
    .form-group .div_arrow{
        width: 5%;
    }
    #accordion{
        margin-bottom: 15px;
    }
    .form_trigger_element{
        background-color: #ffffff;
         /* box-shadow: -8px 12px 18px 0 #dadee8; */
         border: 1px solid #cecece;
    }
    .form_trigger_element input,.form_trigger_element textarea,.form_trigger_element  .select2{
        background-color: #ffff;
    }
    .formelement .form_fields{
        margin-bottom: 3%;
        margin-top: 2%; background-color: #ffffff; border:solid 1px #cecece;
    }
    .show_small{
        min-height: 80px;
    }

    .modal-body{
        background-color:#fff;
    }
</style>
<div class="">
<div class="editViewPageDiv mt0 ">
    <div class=" mt0" id="EditView">
        <form name="EditWorkflow" action="{$SITEURL}index.php" method="post" id="workflow_edit" class="form-horizontal">
            {assign var=WORKFLOW_MODEL_OBJ value=$WORKFLOW_MODEL->getWorkflowObject()}
            <input type="hidden" name="record" value="{$RECORDID}" id="record" />
            <input type="hidden" name="module" value="Workflows" />
            <input type="hidden" name="action" value="SaveWorkflow" />
            <input type="hidden" name="parent" value="Settings" />
            <input type="hidden" name="returnsourcemodule" value="{$RETURN_SOURCE_MODULE}" />
            <input type="hidden" name="returnpage" value="{$RETURN_PAGE}" />
            <input type="hidden" name="returnsearch_value" value="{$RETURN_SEARCH_VALUE}" />
            <input type="hidden" name="andcondition_count" id="andcondition_count" value="{$ADVANCE_CRITERIA_COUNT}" />
            <div class="editViewHeader workflow ">
                <input type="hidden" name="conditions" id="advanced_filter" value='' />
                <div class="editMode transform mt0 card" style="width:100%">
                    <div class="editViewBody">
                        <div class="editViewContents">
                            <div class="formelement ">
                            <div class="add_border form_trigger_element form_fields show_small">
                                <div id="accordion">
                                <div class="">
                                  <div class="card-header pb0" id="headingOne">
                                  <div class="form-group ">
                                        <div class="div_field">
                                            <label for="name" class="col-sm-3 pull-left col-form-label">
                                                <b>{vtranslate('LBL_WORKFLOW_NAME', $QUALIFIED_MODULE)}</b>
                                            </label>
                                            <div class="col-sm-7 pull-left controls">
                                                <input class="form-control inputElement" id="name" name="workflowname"
                                                    value="{$WORKFLOW_MODEL_OBJ->workflowname}"
                                                    data-rule-required="true"
                                                    placeholder="{vtranslate('LBL_WORKFLOW_NAME', $QUALIFIED_MODULE)}">
                                            </div>
                                        </div>
                                        <div class="div_active offon">
                                            <label class="fancy-checkbox">
                                        <input style="opacity: 0;" type="checkbox" name="status" id="status" data-on-color="success" class="taskStatus"  {if $WORKFLOW_MODEL_OBJ->status eq '1'} checked="" value="on" {else} value="off" {/if} />
                                            </label>
                                        </div>
                                        <div class="div_arrow d-flex align-items-center">
                                        <a data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fa fa-angle-down "></i>
                                    </a>
                                    </div>
                                    </div>
                                  </div>
                                  <div id="collapseOne" class="collapse  show mt0 pt0" aria-labelledby="headingOne" data-parent="#accordion" style="height: 0px;">
                                    <div class="card-body pt0">
                                    <div class="form-group">
                                    <div class="div_field">
                                        <label for="name" class="col-sm-3 pull-left col-form-label">
                                            <b>{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}</b>
                                        </label>
                                        <div class="col-sm-7 pull-left controls">
                                            <textarea class="form-control inputElement" name="summary" id="summary"
                                                placeholder="Description">{$WORKFLOW_MODEL->get('summary')}</textarea>
                                        </div>
                                    </div>
                                    </div>
                                <div class="form-group">
                                    <div class="div_field">
                                    <label for="module_name" class="col-sm-3 pull-left col-form-label">
                                        <b>{vtranslate('LBL_TARGET_MODULE', $QUALIFIED_MODULE)}</b>
                                    </label>
                                    <div class="col-sm-7 pull-left controls">
                                        {if $MODE eq 'edit'}
                                            <div class="pull-left">
                                                <input type='text' disabled='disabled' class="inputElement"
                                                    value="{vtranslate($MODULE_MODEL->getName(), $MODULE_MODEL->getName())}">
                                                <input type='hidden' id="module_name" name='module_name'
                                                    value="{$MODULE_MODEL->get('name')}">
                                            </div>
                                            
                                        {else}
                                            <select class="select2 inputElement col-sm-6 pull-left" id="module_name"
                                                name="module_name" required="true" data-placeholder="Select Module..."
                                                style="text-align: left">
                                                {foreach from=$ALL_MODULES key=TABID item=MODULE_MODEL}
                                                    {assign var=TARGET_MODULE_NAME value=$MODULE_MODEL->getName()}
                                                    {assign var=SINGLE_MODULE value="SINGLE_$TARGET_MODULE_NAME"}
                                                    <option value="{$MODULE_MODEL->getName()}"
                                                        {if $SELECTED_MODULE == $MODULE_MODEL->getName()} selected {/if}
                                                        data-create-label="{vtranslate($SINGLE_MODULE, $TARGET_MODULE_NAME)} {vtranslate('LBL_CREATION', $QUALIFIED_MODULE)}"
                                                        data-update-label="{vtranslate($SINGLE_MODULE, $TARGET_MODULE_NAME)} {vtranslate('LBL_UPDATED', $QUALIFIED_MODULE)}">
                                                        {if $MODULE_MODEL->getName() eq 'Calendar'}
                                                            {vtranslate('LBL_TASK', $MODULE_MODEL->getName())}
                                                        {else}
                                                            {vtranslate($MODULE_MODEL->getName(), $MODULE_MODEL->getName())}
                                                        {/if}
                                                    </option>
                                                {/foreach}
                                            </select>
                                        {/if}
                                    </div>
                                    </div>
                                </div>
                                  </div>
                                </div>
                                </div>
                                </div>    
                                </div>
                                <div class="form-group hide">
                                    <div class="nextlink">
                                        <a href='#' data-group="trigger">+ Add Trigger</a>
                                    </div>
                                </div>
                                <div class="add_border form_trigger_element rulesbg {if empty($RECORDID)} hide {/if}">
                                    {include file='WorkFlowTrigger.tpl'|@vtemplate_path:$QUALIFIED_MODULE}
                                </div>
                                <div class="form-group hide">
                                    <div class="nextlink">
                                        <a href='#' data-group="condition">+ Add Condition and Action</a>
                                    </div>
                                </div>
                                <div id="workflow_condition" class="{if empty($RECORDID)} hide {/if}">
                                </div>
                               {*  <div class="form-group hide">
                                    <div class="nextlink">
                                        <a href='#' data-group="action">+ Add Action</a>
                                    </div>
                                </div> *}
                            </div>
                        </div>
                        <div class="row clearfix">
					<div class='textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
						<button type='submit' class='btn btn-primary saveButton' >{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
						<a class='cancelLink btn btn-danger' href="javascript:history.back()" type="">{vtranslate('LBL_CANCEL', $MODULE)}</a>
					</div>
				</div>
                    </div>
        </form>
    </div>
</div>
</div>
