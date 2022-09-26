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
        <div class="modal-header col-md-12 languageEditor" style="min-height:53px">
	    <strong class=" col-md-6 col-10">{vtranslate('LBL_ADD_LANGUAGE_MESSAGE', $QUALIFIED_MODULE)}</strong>
	    <button type="button" class="close col-md-1 close_language_editor" aria-label="Close" data-dismiss="modal" style="color: inherit;">
		<span aria-hidden="true" class="fa fa-close"></span>
	    </button>
        </div>

	<div class="modal-body">
	    <form class="main-menu-modal-form" id="languageeditor" method="post" action="index.php" enctype="multipart/form-data" >
        	<div class="language-content">
			<div style="margin-bottom:10px;" class="row">
                       	<span class="col-md-6 mt-3">{vtranslate('LBL_LANGUAGE_NAME', $QUALIFIED_MODULE)} :</span>
                        <input class="inputElement col-md-6" name="language-name" id="language-name" >
                    </div>

                    <div style="margin-bottom:10px;" class="row">
                       	<span class="col-md-6 mt-3">{vtranslate('LBL_LANGUAGE_CODE', $QUALIFIED_MODULE)} :</span>
                        <input class="inputElement col-md-6" name="language-code" id="language-code">
                    </div>
		
		    <div style="margin-bottom:10px;" class="row">
                    	<span class="col-md-6 mt-3">{vtranslate('LBL_COPY_FILES_FROM', $QUALIFIED_MODULE)} :</span>
                        <select class="select2 inputElement col-md-6" name="language-to-copy" value='' id="language-to-copy">
			    <option value=''></option>
                            {foreach item=LANGUAGE key=code from=$LANGUAGES}
                            	<option value="{$code}">{vtranslate($LANGUAGE, $MODULE)}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
	    </form>
	</div>

	<div class="modal-footer ">
	    <center>
		<button class="btn btn-primary save-section" id="save-section" type="submit" name="saveButton">
		    <strong>Save</strong>
		</button>
                <a href="#" class="cancelLink  btn btn-secondary" type="reset" data-dismiss="modal">Cancel</a>
            </center>
	</div>
    </div>
</div>
