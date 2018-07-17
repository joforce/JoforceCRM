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
<div class="modal-content" id="add-language-modal">
        <div class="modal-header" style="min-height:53px">
                <strong>Give language name and code to create new language</strong>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal" style="color: inherit;">
                        <span aria-hidden="true" class="fa fa-close"></span>
                </button>
        </div>

	<div class="modal-body">
	<form class="main-menu-modal-form" action>
        	<div class="language-content">
                	<div style="margin-bottom:10px;">
                        	<span>Language Name:</span>
                                <input class="inputElement" name="language-name" id="language-name">
                        </div>

                        <div style="margin-bottom:10px;">
                        	<span>Language Code:</span>
                                <input class="inputElement" name="language-code" id="language-code">
                        </div>
		
			<div style="margin-bottom:10px;">
                                <span>Copy Language Files From:</span>
                                <select class="select2 inputElement" name="language-to-copy" value='' id="language-to-copy">
				<option value=''><option>
                                {foreach item=LANGUAGE key=code from=$LANGUAGES}
                                        <option value="{$code}">{$LANGUAGE}</option>
                                {/foreach}
                                </select>
                        </div>
                </div>
	</form>
	</div>

	<div class="modal-footer ">
                <center>
                        <button class="btn btn-success save-section" id="save-section" type="submit" name="saveButton">
                                <strong>Save</strong>
                        </button>
                        <a href="#" class="cancelLink" type="reset" data-dismiss="modal">Cancel</a>
                </center>
	</div>
</div>
</div>
