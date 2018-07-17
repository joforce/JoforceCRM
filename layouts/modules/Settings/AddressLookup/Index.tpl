{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}

{literal}
<script type="text/javascript">
makeOption = '';
function flashNotification(type, message)   {

    // showing notification
        var params = {
            type: type,
            text: message,
            animation: 'show'
        };


        Head_Helper_Js.showPnotify(params);
}
	
$('.submitcheck').prop("type", "button");

$(".submitcheck").live('click', function(){
        $('.submitcheck').prop("type", "submit");
});

$('.removeRow').live('click', function() {
        $conf="Are you sure want to delete this?";
        if(confirm($conf)==true)
        {

	$(this).parent().parent().remove();
        return true;
        }
        else
        return false;
});


$('select').live('click', function () {
        var value = $(this).val();

});
$(document).ready(function() {
             var warnMessage = null;
                   $("input[type='checkbox'], .dupecheck").change(function() {            
                           warnMessage = "You have unsaved changes on this page!";              
                           $(".submitcheck").addClass("formChanges");
});             
        $(".formChanges").live('click', function(){        
                warnMessage = null;             
                $(".submitcheck").removeClass("formChanges");
                $("#vtaddresslookup").submit();
                return false;
});             
        window.onbeforeunload = function () {
                if (warnMessage != null) return warnMessage;
        }
});

</script>
{/literal}
{strip}
<div class="joforce-bg">
<div>
                <h2 style="margin-bottom: -43px; padding: 17px;">
        <i style = "padding: 10px;" class="fa fa-map-marker" aria-hidden="true"></i>
                         {vtranslate('Address Lookup', $MODULE)}
                </h2>
</div>
<br/><br/>
<div>
                <div >
                        <h4 style="margin:6px; float:left;padding-left:10px;">{vtranslate('LBL_CONFIGURE_MAPPING_FOR', $MODULE)}</h4>
                        <select class="select2 col-sm-3 pull-left" id="modulename" style="float: right;" onchange="location.href='{$SITEURL}AddressLookup/Settings/List?sourceModule='+this.value" name="modulename">

                        {foreach item=MODULE_NAME from=$ENABLED_MODULES}
                            <option value="{$MODULE_NAME[1]}" {if $MODULE_NAME[1] eq $SELECTED_MODULE_NAME} selected {/if} >{vtranslate($MODULE_NAME[0], $MODULE)}</option>
                        {/foreach}
                    </select>
</div></div>
<form method="post" id ="vtaddresslookup" action="{$SITEURL}index.php?module=AddressLookup&parent=Settings&action=SaveFields" class="form-inline form-horizontal recordEditView">
<div class="onoffswitch1 pull-left ml10 mr10">
    <input type="checkbox" style="display:none;" name="isenabled" class="onoffswitch1-checkbox" id="myonoffswitch1" {if $ENABLE_CHECK eq 1}checked="yes"{/if} style="float: left; margin-top:0px;" value="1">
    <label class="onoffswitch1-label" for="myonoffswitch1">
        <span class="onoffswitch1-inner"></span>
        <span class="onoffswitch1-switch"></span>
    </label>
    </div>
        <div>
        <b>{vtranslate('LBL_ENTER_API_KEY', $MODULE)}</b><sup style="color:red;"><b>*</b></sup>
        <input id="APIkey" type="text"  name="APIkey" value="{$APIkey}"><br>        <a class="jo-link" href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true">{vtranslate('LBL_CLICK_TO_CREATE_API_KEY', $MODULE)}</a>
        </div>
<br/>
<br/><br/>
<div class="scroll" style = "margin: 0 20px;">
<table id = "addFormFieldRow" class="table"><thead>
<th>{vtranslate('LBL_STREET', $MODULE)}<sup style="color:red;">*</sup></th>
<th>{vtranslate('LBL_AREA', $MODULE)}</th>
<th>{vtranslate('LBL_LOCALITY', $MODULE)}</th>
<th>{vtranslate('LBL_CITY', $MODULE)}</th>
<th>{vtranslate('LBL_STATE', $MODULE)}</th>
<th>{vtranslate('LBL_COUNTRY', $MODULE)}</th>
<th>{vtranslate('LBL_POSTAL_CODE', $MODULE)}</th>
<th>{vtranslate('LBL_ACTION', $MODULE)}</th>
</thead>
<tbody>
<input type="hidden" name="modulename" id="module" value="{$SELECTED_MODULE_NAME}"/>
{for $i=0 to $COUNT_OF_ROWS}
<tr><td><select class="select2 dupecheck" style="float: left;" name="street[]" ><option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_STREET[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2  dupecheck" style="float: left;" name="area[]" >
<option value="">{vtranslate('LBL_SELECT_OPTION', $MODULE)}</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_AREA[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2 dupecheck" style="float: left;" name="locality[]">
<option value="">{vtranslate('LBL_SELECT_OPTION', $MODULE)}</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}" {if $FIELD_NAME['fieldid'] eq $SELECTED_LOCALITY[$i]} selected {/if}  >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>
<td><select class="select2 dupecheck" style="float: left;" name="city[]" ><option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_CITY[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2 dupecheck" style="float:left;" name="state[]" >
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_STATE[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>
<td><select class="select2 dupecheck" style="float:left;" name="country[]" >
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_COUNTRY[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2 dupecheck" style="float:left;" name="postalcode[]">
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_POSTALCODE[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'], $MODULE)}</option>
                        {/foreach}
                    </select></td>
<td><i style="margin-left:20px;" class="fa fa-trash removeRow"></i></td></tr>
{/for}
</tbody>
</table></div>
<br/><br/>
<div style = "margin: 0 50px;"id="addMore" class="pointer"> <span onclick = "addOneMore();"><b>{vtranslate('LBL_ADD', $MODULE)}&nbsp;&nbsp;</b><i class="fa fa-plus" title="Add More"> </i></span></div>
<div class="pull-right" style = "margin: 0 50px;">
<button type="submit" id="savebutton" class="btn btn-primary input-small submitcheck" name="submitCustomModule" >{vtranslate('LBL_SAVE', $MODULE)}</button>
&nbsp;&nbsp;<button class="btn joforce-default input-small" onclick="window.location.reload();">{vtranslate('LBL_CANCEL', $MODULE)}</button></div>
</form></div>
{/strip}


