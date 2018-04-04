{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}

<div  class='container row-fluid'>
   <br><br>
	<form class="col-lg-offset-3 col-lg-6 mt30"  name = 'EditServerDetails' method = 'POST' action = 'index.php?module=EmailPlus&action=SaveServerDetails' id='submitServerDetails'>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('Account Type', $moduleName)}</label>
         <select class="inputElement select2 form-control" id = 'type'  name='type' style = 'width:300px;' onchange='choice()'/>
            <option>Select</option>
            <option value='gmail' id='gmail' {if $TYPE eq 'gmail'} selected {/if}>Gmail</option>
            <option value='yahoo' id ='yahoo' {if $TYPE eq 'yahoo'} selected {/if}>Yahoo</option>
            <option value='other' id='other' {if $TYPE eq 'other'} selected {/if}>Other</option>
         </select>
      </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('Server Name', $moduleName)}</label>
         <input class="inputElement form-control" type='text' id = 'servername' value="{$SERVER}" name='server' style = 'width:300px;' required />
         </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('Port', $moduleName)}</label>
         <input class="inputElement" type='text' id = 'port' value="{$PORT}" name='port' style = 'width:300px;' required />
         </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('Email', $moduleName)}</label>
         <input class="inputElement" type='email' id = 'uname' value="{$email}" name='email' style = 'width:300px;' required data-validation-engine='validate[required]' />
      </div>
      <div class="form-group">
         <label class="serversettings-label">{vtranslate('Password', $moduleName)}</label>
         <input class="inputElement" type='password' id = 'pwd' value='{$password}' name='pwd' style = 'width:300px;' required data-validation-engine='validate[required]' />
      </div>
      <div class="p30 mt40">
         <a class="cancelLink" style='position:ablsolute;float:right;' title="cancel" type="reset" href="{$SITE_URL}EmailPlus/view/List">Cancel</a>
         <button type="submit" style = 'position:ablsolute;float:right;'class="btn btn-success">Save</button>
      </div>
   </form>
</div>


