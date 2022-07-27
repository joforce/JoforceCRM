{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}

<!-- joforce-bg-list -->
<div  class='col-sm-12 col-xs-12 airmail mt0 '>
  
   <div class="card">
   <div class="card-header-new mb20 ml10"><h3>AirMail</h3></div>
   <form class="offset-lg-3 col-lg-6"  name = 'EditServerDetails' method = 'POST' action = 'index.php?module=EmailPlus&action=SaveServerDetails' id='submitServerDetails' style="background:#fff;">
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('LBL_ACCOUNT_TYPE', $MODULE)}</label>
         <select class="inputElement select2 form-control" id = 'type'  name='type' style = 'width:300px;' onchange='choice()'/>
            <option>Select</option>
            <option value='gmail' id='gmail' {if $TYPE eq 'gmail'} selected {/if}>Gmail</option>
            <option value='yahoo' id ='yahoo' {if $TYPE eq 'yahoo'} selected {/if}>Yahoo</option>
            <option value='other' id='other' {if $TYPE eq 'other'} selected {/if}>Other</option>
         </select>
      </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('LBL_SERVER_NAME', $MODULE)}</label>
         <input class="inputElement form-control" type='text' id = 'servername' value="{$SERVER}" name='server' style = 'width:300px;' required />
         </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('LBL_PORT', $MODULE)}</label>
         <input class="inputElement" type='text' id = 'port' value="{$PORT}" name='port' style = 'width:300px;' required />
         </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('LBL_EMAIL', $MODULE)}</label>
         <input class="inputElement" type='email' id = 'uname' value="{$email}" name='email' style = 'width:300px;' required data-validation-engine='validate[required]' />
      </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('LBL_PASSWORD', $MODULE)}</label>
         <input class="inputElement" type='password' id = 'pwd' value='{$password}' name='pwd' style = 'width:300px;' required data-validation-engine='validate[required]' />
      </div>
      {if $CURRENT_USER_MODEL->isAdminUser()}
      <div class="form-group">
        <label class="serversettings-label">{vtranslate('LBL_ENABLE_Users', $MODULE)}</label>
         <input type="radio" id="manual_enable" name="enable" value="manual_enable"  {if $ENABLETYPE == 'manual_enable'} checked {/if}>
         <label class="serversettings-label" style = 'width:300px;'>Manually Enable in each user profile</label><br>
         <label class="serversettings-label"></label>
         <input type="radio" id="selected_user" name="enable" value="selected_user" {if $ENABLETYPE == 'selected_user'} checked {/if}>
         <label class="serversettings-label" style = 'width:300px;' >Enable for selected user</label><br>      
      </div>
      <div id = "select_user"  {if $ENABLETYPE != 'selected_user'}style="display:none;"{/if}>    
         <select class="select2-container select2" id="ddl" name="optiontopermit" onchange="configureDropDownLists(this,document.getElementById('ddl2'))" style ="width:30% !important">
            <option value="">Select</option>
            <!-- <option value="allusers">All users</option> -->
            <option value="users">Users</option>
            <option value="roles">Roles</option>
         </select> 
         
         <select class="select2-container select2 hide" name="selectedrole" id="ddl3"  onchange="configureDropDownLists(this,document.getElementById('ddl2'))"  style ="width:30% !important">
         <option value="">Select</option>            
         </select> 

         <select class="select2 hide" multiple="true" name="selecteduser[]"  id="ddl2" style ="width:30% !important" >
           <option value="">Select user</option>   
         </select>            
      </div> 
      {/if}

      <div class="d-flex justify-content-center" style ="">
         <a class="cancelLink btn btn-danger" style='position:ablsolute;float:right;' title="cancel" type="" href="{$SITE_URL}EmailPlus/view/List">{vtranslate('LBL_CANCEL', $MODULE)}</a>
         <button type="submit" style = 'position:ablsolute;float:right;'class="btn btn-primary">{vtranslate('LBL_SAVE', $MODULE)}</button>
      </div>
   </form>
</div> 
</div>

<script type="text/javascript"> 
   $( document ).ready(function() {
     $("input:radio").click(function() {
       if($(this).val() == "selected_user") {
         $("#select_user").show();
       } else {
         $("#select_user").hide();   
       }
     });
   });
</script>
