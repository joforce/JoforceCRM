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
            <span data-label="{$LABEL}" class="fa fa-pencil editor" style="display:none;"></span>
            <div class="language-edit" style="display:none;">
            	<span class="fa fa-check save-edit" data-label="{$LABEL}" data-hint="{$HINT}"></span>
                <span class="fa fa-close close-edit" ></span>
            </div>
        </td>
    </tr>
{/foreach}
