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
   <form  name = 'EditServerDetails' method = 'POST' action = 'index.php?module=EmailPlus&action=SaveServerDetails' id='submitServerDetails'>
      <table class='table table-bordered themeTableColor'>
         <thead>
            <tr class="">
               <th colspan="2" class="mediumWidthType">
                  <span class="alignMiddle">{vtranslate('IMAP Configuration', $moduleName)}</span>
               </th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td width="40%"><label class="muted pull-right marginRight10px">{vtranslate('Account Type', $moduleName)} </label> </td>
               <td style="border-left: none;">
                  <select id = 'type'  name='type' style = 'width:300px;' onchange='choice()'/>
                     <option>Select</option>
                     <option value='gmail' id='gmail' {if $TYPE eq 'gmail'} selected {/if}>Gmail</option>
                     <option value='yahoo' id ='yahoo' {if $TYPE eq 'yahoo'} selected {/if}>Yahoo</option>
                     <option value='other' id='other' {if $TYPE eq 'other'} selected {/if}>Other</option>
                  </select>
               </td>
            </tr>
            <tr>
               <td width="40%"><label class="muted pull-right marginRight10px">{vtranslate('Server Name', $moduleName)} </label> </td>
               <td style="border-left: none;">
                  <input type='text' id = 'servername' value="{$SERVER}" name='server' style = 'width:300px;' required />
               </td>
            </tr>
            <tr>
               <td width="40%"><label class="muted pull-right marginRight10px">{vtranslate('Port', $moduleName)} </label> </td>
               <td style="border-left: none;">
                  <input type='text' id = 'port' value="{$PORT}" name='port' style = 'width:300px;' required />
               </td>
            </tr>
            <tr>
               <td width="40%"><label class="muted pull-right marginRight10px">{vtranslate('Email', $moduleName)} </label> </td>
               <td style="border-left: none;">
                  <input type='email' id = 'uname' value="{$email}" name='email' style = 'width:300px;' required data-validation-engine='validate[required]' />
               </td>
            </tr>
            <tr>
               <td width="40%"><label class="muted pull-right marginRight10px">{vtranslate('Password', $moduleName)} </label> </td>
               <td style="border-left: none;">
                  <input type='password' id = 'pwd' value='{$password}' name='pwd' style = 'width:300px;' required data-validation-engine='validate[required]' />
               </td>
            </tr>
         </tbody>
      </table>
      <br>
      <a class="cancelLink" style='position:ablsolute;float:right;' title="cancel" type="reset" href="index.php?module=EmailPlus&view=List">Cancel</a>
      <button type="submit" style = 'position:ablsolute;float:right;'class="btn btn-success">Save</button>
   </form>
</div>


