{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}
{literal}
<style type="text/css">
.moveleft {
  float: left;
  width: 125px;
  text-align: right;
  margin: 2px 10px;
  display: inline
}
.moveright {
  float: left;
  text-align: left;
  margin: 2px 10px;
  display: inline
}
/*.tab{
    padding-left:5em;
}*/
.checked{
    color: blue;
}
.fancy-checkbox input[type="checkbox"],
.fancy-checkbox .checked {
    display: none;
}
 
.fancy-checkbox input[type="checkbox"]:checked ~ .checked
{
    display: inline-block;
}
 
.fancy-checkbox input[type="checkbox"]:checked ~ .unchecked
{
    display: none;
}
.circle
{
    border: green;
    padding: 5px 10px;
    display: inline-block;
    -moz-border-radius: 100px;
    -webkit-border-radius: 100px;
    border-radius: 100px;
    -moz-box-shadow: 0px 0px 2px #888;
    -webkit-box-shadow: 0px 0px 2px #888;
    box-shadow: 0px 0px 2px #888;
}
</style>
<script type="text/javascript">

function getModuleID(){
 var site_url = jQuery('#joforce_site_url').val();
 var moduleName = $("#layoutEditorModules").val();
  location.href=site_url+"DuplicateCheck/Settings/List?sourceModule="+moduleName;

}



$(document).ready(function() {
	     var warnMessage = null;
	           $("input[type='checkbox']").change(function() {            
                	   warnMessage = "You have unsaved changes on this page!";
                	  $("#vtduplicateform").addClass("formChanged");
                      $(".btn-success").addClass("formChanges");
});
//	$(".formChanges").live('click', function(){        
        $(document).on('click', ".formChanges", function(){        
		warnMessage = null;
		$("#vtduplicateform").removeClass("formChanged");
        $(".btn-success").removeClass("formChanges");
        $("#vtduplicateform").submit();
});
        window.onbeforeunload = function () {
            if (warnMessage != "null") return warnMessage;
        }	
	if(($('#layoutEditorModules').val() == 'Leads')|| ($('#layoutEditorModules').val() == 'Contacts') || ($('#layoutEditorModules').val() == 'Accounts'))
		$('#crosscheck').show();
	else
		$('#crosscheck').hide();		


$('[rel="tooltip"]').tooltip();

});	
</script>
{/literal}
{strip}
    <div class="container-fluid joforce-bg" id="layoutEditorContainer">


        <input id="selectedModuleName" type="hidden" value="{$SELECTED_MODULE_NAME}" />
        <div class="widget_header row-fluid">
            <div class="col-sm-12">
                <h3>Duplicate Check</h3>
            </div><!--<br/><br/><br/>-->

            <div>
                <div class='row'>
		<div class='col-sm-3'>
                <h4 style="float:left;">Duplicate Check Rule For</h4></div>
                <div class='col-sm-2' style="top:10px"> <select class="select2" id="layoutEditorModules" onchange="getModuleID()" name="layoutEditorModules">
                        {foreach item=MODULE_NAME from=$SUPPORTED_MODULES}
                            <option value="{$MODULE_NAME}" {if $MODULE_NAME eq $SELECTED_MODULE_NAME} selected {/if}>{vtranslate($MODULE_NAME, $QUALIFIED_MODULE)}</option>
                        {/foreach}
                    </select></div>
		</div>
	<form method="POST" action="{$SITEURL}index.php?module=DuplicateCheck&parent=Settings&view=UpdateFields" id="vtduplicateform">
	<div class="pull-right col-sm-5" style='top:-30px;'>
	 <label class="fancy-checkbox" ><h4 style="float: left;">Enable / Disable</h4>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox"  name="isenabled" id="isenabled" {if $MODULE_ENABLED_VALUE eq 1}checked="yes"{/if} style="float: left;" value="1" /><i style="margin-top: -4px;" class="fa fa-toggle-on fa-2x fa-rotate-180 inactive unchecked" aria-hidden="true"></i><span class="tab"></span><i class="fa fa-toggle-on active fa-2x checked" style="position:relative;top:9px;" aria-hidden="true"></i><span class="tab"></span></label></div>
    </div>
</div>
        

        <div class="contents tabbable">
<br>
         <hr class='hr-dotted' style='width:100%;'> 
            <div class="tab-content layoutContent padding20 themeTableColor overflowVisible">
              
                                  
                                    <div class="tab-pane active" id="detailViewLayout"> <h4 style="position: relative; top: 25px;">Configure Field Rule</h4>  <div class="col-sm-7 marginLeftZero" style="float:right !important;">  <input type="hidden" name="modulename" id="modulename" value="{$SELECTED_MODULE_NAME}" /><div><button class="btn joforce-default" onclick="window.location.reload();" style="float:right" value="Save">Cancel</button></div><div><button class="btn btn-primary" name="savebutton" style="float:right" type="submit" value="Save">Save</button>
<span id="crosscheck"style="float: right; margin-top:-37px"> For CrossCheck &nbsp;&nbsp;&nbsp;<input type="checkbox" name="ischecked" style="top:5px" id="ischecked" {if $CROSSCHECKVALUE eq 1} checked="yes"{/if}>&nbsp;&nbsp;<i id="crosscheckhelp" data-original-title = "Content" rel = "tooltip" title="Check Duplicates across Contacts , Leads and Organization for Email and Phone Field" class="fa fa-question-circle" aria-hidden="true"></i> </span>
                <span style="float:left; margin-top:-30px"> Click <i class="fa fa-exchange" rel="tooltip1" data-original-title = "Content" title="To choose what to check in this module" aria-hidden="true" > </i> to choose what to check in this module </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
		 
                                    </div><br/></div></div>
                    <div id="moduleBlocks" style="display:block;">
                        {foreach key=BLOCK_LABEL_KEY item=BLOCK_MODEL from=$BLOCKS}
                            {assign var=FIELDS_LIST value=$BLOCK_MODEL->getLayoutBlockActiveFields()}
                            {assign var=BLOCK_ID value=$BLOCK_MODEL->get('id')}
                            {$ALL_BLOCK_LABELS[$BLOCK_ID] = $BLOCK_LABEL_KEY}
                            <div id="block_{$BLOCK_ID}" class="editFieldsTable block_{$BLOCK_ID} marginBottom10px border1px {if $IS_BLOCK_SORTABLE} blockSortable{/if}" data-block-id="{$BLOCK_ID}" data-sequence="{$BLOCK_MODEL->get('sequence')}" style="border-radius: 4px 4px 0px 0px;background: white;height:auto;">
                                <div class="row-fluid layoutBlockHeader">
                                    <div class="blockLabel col-sm-5 padding10 marginLeftZero">
                                     
                                        <strong>{vtranslate($BLOCK_LABEL_KEY, $SELECTED_MODULE_NAME)} </strong>
                                    </div>
                                   
                                </div><br><br>
                                <div class="blockFieldsList {if $SELECTED_MODULE_MODEL->isFieldsSortableAllowed($BLOCK_LABEL_KEY)}blockFieldsSortable {/if} row-fluid" style="padding:5px;min-height: 27px;display:flex;position:relative">
                                    <ul name="sortable1" class="connectedSortable col-sm-6" style="list-style-type: none; float: left;min-height: 1px;padding:2px;">
                                        {foreach item=FIELD_MODEL from=$FIELDS_LIST name=fieldlist}
                                            {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
                                            {if $smarty.foreach.fieldlist.index % 2 eq 0}
                                                <li>
                                                    <div class="opacity editFields marginLeftZero border1px" style='height:40px;padding:10px' data-block-id="{$BLOCK_ID}" data-field-id="{$FIELD_MODEL->get('id')}" data-sequence="{$FIELD_MODEL->get('sequence')}">
                                                        <div class="row-fluid padding1per">
                                                            {assign var=IS_MANDATORY value=$FIELD_MODEL->isMandatory()}
                                                            <span class="">
                                                                {if $FIELD_MODEL->isEditable()}
                                                                  
                                                                {/if}
                                                            </span>
                                                            <div class="col-sm-11 marginLeftZero" style="word-wrap: break-word;">
                                                                <span class="fieldLabel">{vtranslate($FIELD_MODEL->get('label'), $SELECTED_MODULE_NAME)}
                                                                {if $IS_MANDATORY}<span class="redColor">*</span>{/if}</span>                                          <span class="btn-group pull-right"><label class="fancy-checkbox"><input type="checkbox" name ="fieldID[]" id="match{vtranslate($FIELD_MODEL->get('id'), $SELECTED_MODULE_NAME)}" class="data-id" {if vtranslate($FIELD_MODEL->get('id'))|in_array:$FIELDSTOMATCH}checked="yes"{/if} value ="{vtranslate($FIELD_MODEL->get('id'), $SELECTED_MODULE_NAME)}" /><i class="fa fa-exchange unchecked circle" aria-hidden="true"></i><span class="tab"></span><i class="fa fa-exchange checked circle" aria-hidden="true"></i><span class="tab"></span></label>   </span><!-- <span> </span> -->

                                                         
                    </div>


                </div>
            </div>
        </li>
    {/if}
{/foreach}
</ul>
<ul {if $SELECTED_MODULE_MODEL->isFieldsSortableAllowed($BLOCK_LABEL_KEY)}name="sortable2"{/if} class="connectedSortable col-sm-6" style="list-style-type: none; margin: 0; float: left;min-height: 1px;padding:2px;">
    {foreach item=FIELD_MODEL from=$FIELDS_LIST name=fieldlist1}
        {assign var=FIELD_INFO value=$FIELD_MODEL->getFieldInfo()}
        {if $smarty.foreach.fieldlist1.index % 2 neq 0}
            <li>
                <div class="opacity editFields marginLeftZero border1px" style='height:40px;padding:10px' data-block-id="{$BLOCK_ID}" data-field-id="{$FIELD_MODEL->get('id')}" data-sequence="{$FIELD_MODEL->get('sequence')}">
                    <div class="row-fluid padding1per">
                        {assign var=IS_MANDATORY value=$FIELD_MODEL->isMandatory()}
                       
                        <div class="col-sm-11 marginLeftZero" style="word-wrap: break-word;">
                            <span class="fieldLabel">
                                {if $IS_MANDATORY}
                                    <span class="redColor">*</span>
                                {/if}
                                {vtranslate($FIELD_MODEL->get('label'), $SELECTED_MODULE_NAME)}&nbsp;
                            </span><span class="btn-group pull-right"><label class="fancy-checkbox"> <input type="checkbox" name ="fieldID[]" id="match{vtranslate($FIELD_MODEL->get('id'), $SELECTED_MODULE_NAME)}" class="data-id" {if vtranslate($FIELD_MODEL->get('id'))|in_array:$FIELDSTOMATCH}checked="yes"{/if}  value="{vtranslate($FIELD_MODEL->get('id'), $SELECTED_MODULE_NAME)}"/><span class="tab"></span><i class="fa fa-exchange unchecked circle" aria-hidden="true"></i><span class="tab"></span><i class="fa fa-exchange checked circle" aria-hidden="true"></i></label></span>
                                                               
                            
</div>
</div>
</div>
</li>
{/if}
{/foreach}
</ul> 
</div>
</div>
{/foreach}
</div>
<input type="hidden" class="inActiveFieldsArray" value='{ZEND_JSON::encode($IN_ACTIVE_FIELDS)}' />




</div>
<div><button class="btn joforce-default" onclick="window.location.reload();" style="float:right" value="Save">Cancel</button></div><div><button class="btn btn-primary" name="savebutton" style="float:right" type="submit" value="Save">Save</button></div>
</div>
</div>
</div>
</form>
{/strip}

