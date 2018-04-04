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

 .onoffswitch1 {
    position: relative; width: 90px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}

.onoffswitch1-checkbox {
    display: none;
}

.onoffswitch1-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 30px;
}

.onoffswitch1-inner {
    display: block; width: 200%; margin-left: -100%;
    -moz-transition: margin 0.3s ease-in 0s; -webkit-transition: margin 0.3s ease-in 0s;
    -o-transition: margin 0.3s ease-in 0s; transition: margin 0.3s ease-in 0s;
}

.onoffswitch1-inner:before, .onoffswitch1-inner:after {
    display: block; float: left; width: 40%; height: 30px; padding: 0; line-height: 30px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box;
    border-radius: 30px;
    box-shadow: 0px 15px 0px rgba(0,0,0,0.08) inset;
}

.onoffswitch1-inner:before {
    content: "Enabled";
    width: 100px;
    padding-left: 10px;
    background-color: #0066cc; color: #FFFFFF;
    border-radius: 30px 0 0 30px;
}
.onoffswitch1-inner:after {
    content: "Disabled";
    padding-right: 8px;
    background-color: #EEEEEE; color: #999999;
    text-align: right;
    border-radius: 0 30px 30px 0;
}

.onoffswitch1-switch {
    display: block; width: 27px;height:30px; margin: 1px;
    background: #FFFFFF;
    border: 2px solid #999999; border-radius: 30px;
    position: absolute; top: 0; bottom: 0; right: 56px;
    -moz-transition: all 0.3s ease-in 0s; -webkit-transition: all 0.3s ease-in 0s;
    -o-transition: all 0.3s ease-in 0s; transition: all 0.3s ease-in 0s;
    background-image: -moz-linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
    background-image: -webkit-linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
    background-image: -o-linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
    background-image: linear-gradient(center top, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0) 80%);
    box-shadow: 0 1px 1px white inset;
}

.onoffswitch1-checkbox:checked + .onoffswitch1-label .onoffswitch1-inner {
    margin-left: 0;
}

.onoffswitch1-checkbox:checked + .onoffswitch1-label .onoffswitch1-switch {
    right: -11px;
}

.pointer{
 cursor: pointer;
}
.unchecked{
        color:grey;
}

.checked{
    color: green;
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
.scroll {
    overflow-y: auto;
    overflow-x:auto;
    height:300px; 
}
</style>
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
<div>
                <h2 style="margin-bottom: -43px; padding: 17px;">
        <i style = "padding: 10px;" class="fa fa-map-marker" aria-hidden="true"></i>
                         Address Lookup
                </h2>
</div>
<br/><br/>
<div>
                <div >
                        <h4 style="margin:6px; float:left;padding-left:10px;">Configure Field Mapping For</h4>
                        <select class="select2 col-sm-3 pull-left" id="modulename" style="float: right;" onchange="location.href='{$SITEURL}AddressLookup/Settings/List/'+this.value" name="modulename">

                        {foreach item=MODULE_NAME from=$ENABLED_MODULES}
                            <option value="{$MODULE_NAME[1]}" {if $MODULE_NAME[1] eq $SELECTED_MODULE_NAME} selected {/if} >{vtranslate($MODULE_NAME[0])}</option>
                        {/foreach}
                    </select>
</div></div>
<form method="post" id ="vtaddresslookup" action="{$SITEURL}index.php?module=AddressLookup&parent=Settings&action=SaveFields" class="form-inline form-horizontal recordEditView">
<div class="onoffswitch1 pull-left" style="margin-left:25px;">
    <input type="checkbox" style="display:none;" name="isenabled" class="onoffswitch1-checkbox" id="myonoffswitch1" {if $ENABLE_CHECK eq 1}checked="yes"{/if} style="float: left; margin-top:0px;margin-left:20px;" value="1">
    <label class="onoffswitch1-label" for="myonoffswitch1">
        <span class="onoffswitch1-inner"></span>
        <span class="onoffswitch1-switch"></span>
    </label>
    </div>
        <div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <b>Enter Your API Key</b><sup style="color:red;"><b>*</b></sup>&nbsp;&nbsp;&nbsp;&nbsp;<input id="APIkey" type="text"  name="APIkey" value="{$APIkey}"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="https://console.developers.google.com/flows/enableapi?apiid=maps_backend,geocoding_backend,directions_backend,distance_matrix_backend,elevation_backend&keyType=CLIENT_SIDE&reusekey=true">Click Here to Create API Key</a>
        </div>
<br/>
<br/><br/>
<div class="scroll" style = "margin: 0 20px;">
<table id = "addFormFieldRow" class="table"><thead>
<th>Street<sup style="color:red;">*</sup></th>
<th>Area</th>
<th>Locality</th>
<th>City</th>
<th>State</th>
<th>Country</th>
<th>Postal Code</th>
<th>Action</th>
</thead>
<tbody>
<input type="hidden" name="modulename" id="module" value="{$SELECTED_MODULE_NAME}"/>
{for $i=0 to $COUNT_OF_ROWS}
<tr><td><select class="select2 dupecheck" style="float: left;" name="street[]" ><option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_STREET[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2  dupecheck" style="float: left;" name="area[]" >
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_AREA[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2 dupecheck" style="float: left;" name="locality[]">
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}" {if $FIELD_NAME['fieldid'] eq $SELECTED_LOCALITY[$i]} selected {/if}  >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>
<td><select class="select2 dupecheck" style="float: left;" name="city[]" ><option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_CITY[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2 dupecheck" style="float:left;" name="state[]" >
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_STATE[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>
<td><select class="select2 dupecheck" style="float:left;" name="country[]" >
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_COUNTRY[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>

<td><select class="select2 dupecheck" style="float:left;" name="postalcode[]">
<option value="">Select an Option</option>
                        {foreach item=FIELD_NAME from=$SELECTED_MODULE_FIELDS_LIST}
                            <option value="{$FIELD_NAME['fieldid']}"  {if $FIELD_NAME['fieldid'] eq $SELECTED_POSTALCODE[$i]} selected {/if} >{vtranslate($FIELD_NAME['fieldlabel'])}</option>
                        {/foreach}
                    </select></td>
<td><i style="margin-left:20px;" class="fa fa-trash removeRow"></i></td></tr>
{/for}
</tbody>
</table></div>
<br/><br/>
<div style = "margin: 0 50px;"id="addMore" class="pointer"> <span onclick = "addOneMore();"><b>Add&nbsp;&nbsp;</b><i class="fa fa-plus" title="Add More"> </i></span></div>
<div class="pull-right" style = "margin: 0 50px;">
<button type="submit" id="savebutton" class="btn btn-primary input-small submitcheck" name="submitCustomModule" >Save</button>
&nbsp;&nbsp;<button class="btn btn-default input-small" onclick="window.location.reload();">Cancel</button></div>
</form>
{/strip}


