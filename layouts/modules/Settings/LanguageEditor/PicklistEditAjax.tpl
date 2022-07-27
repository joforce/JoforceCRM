{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{foreach item=MEANING key=LABEL from=$LANGUAGE_STRING_ARRAY }
    <tr class="le-row">
	<td>{$LABEL}</td>
	<td name="{$LABEL}" class="meaning">
	    <input type="text" value="{$MEANING}" class="language-input" readonly />
            <span data-label="{$LABEL}" class="fa fa-pencil editor" ></span>
            <div class="language-edit" style="display:none">
            	<button class=" btn-success btn-small save  save-edit p0" name="save"><span class="fa fa-check " data-label="{$LABEL}" data-hint="{$HINT}"></span></button>
                <button class=" btn-danger btn-small  cancel close-edit p0 ml0" name="Cancel"><span class="fa fa-close " ></span></button>
            </div>
            {* <div class="btn-group inline-save hide">
			<button class="button btn-success btn-small save" name="save"><i class="fa fa-check"></i></button>
			<button class="button btn-danger btn-small cancel" name="Cancel"><i class="fa fa-close"></i></button>
		</div> *}
        </td>
    </tr>
    <script>
    
    </script>
{/foreach}
