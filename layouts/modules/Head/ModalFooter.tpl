{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is: vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
 * Contributor(s): JoForce.com
  *
 ********************************************************************************/
-->*}
{strip}
	<div class="modal-footer ">
        <center>
            {if $BUTTON_NAME neq null}
                {assign var=BUTTON_LABEL value=$BUTTON_NAME}
            {else}
                {assign var=BUTTON_LABEL value={vtranslate('LBL_SAVE', $MODULE)}}
            {/if}
            <button {if $BUTTON_ID neq null} id="{$BUTTON_ID}" {/if} class="btn btn-primary " type="" name="saveButton" style="background-color:#0162e8!important;border-color:#0162e8!important;"  ><strong>{$BUTTON_LABEL}</strong></button>
            <a href="#" class="cancelLink btn btn-danger" type="" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
             {if $BACK != 'false' || $BACK == '1'} 
                <a class="btn back-button close" {if $BACK_ID neq null} id="{$BACK_ID}" {/if} onclick="{$BACK_URL}" data-dismiss="modal"><strong>Back</strong></a>
            {/if}
        </center>
	</div>
{/strip}
