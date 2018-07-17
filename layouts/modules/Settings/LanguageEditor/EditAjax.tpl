{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<div>
	{if !$NOTHING}
	<form method="post" action="{$SITEURL}index.php?module=LanguageEditor&parent=Settings&action=SaveFile">
		<input type="hidden" name="file-path" id="file-path" value="{$FILE_PATH}" />
		
		<table class="table table-responsive language-table">
			<thead>
				<th></th>
				<th></th>
			</thead>
			
			<tbody>
				{if $LANGUAGE_STRING_ARRAY}
				<tr style="border-bottom: 4px solid #ccc;">
                                        <td><b>LABELS</b></td>
                                        <td><div class="fa fa-plus" id="add-new-label" type="button" data-hint="lbl"></div></td>
                                </tr>
				{foreach item=MEANING key=LABEL from=$LANGUAGE_STRING_ARRAY }
				<tr>
					<td>{$LABEL}</td>
					<td name="{$LABEL}" class="{$LABEL} meaning">
						<input type="text" value="{$MEANING}" class="language-input {$LABEL}" readonly />
						<span data-label="{$LABEL}" class="fa fa-pencil editor" style="display:none;"></span>
						<div class="language-edit" style="display:none;">
	                                                <span class="fa fa-check save-edit" data-label="{$LABEL}" data-hint="lbl"></span>
        	                                        <span class="fa fa-close close-edit" ></span>
						</div>
					</td>
				</tr>
				{/foreach}
				{/if}
				
				<tr style="display:none" class="dummy-row" id="dummy">
                                        <td><input type="text" value='' class="language-input new-label" style="border-bottom: 1px solid #1C7C54;" /></td>
                                        <td name="" class="meaning active-editable">
                                                <input type="text" value="" class="language-input new-value" />
                                                <span data-label="" class="fa fa-pencil editor" style="display:none;"></span>
                                                <div class="language-edit">
                                                        <span class="fa fa-check save-add-edit new-one" data-label="" ></span>
                                                        <span class="fa fa-close close-edit new-one" ></span>
                                                </div>
                                        </td>
                                </tr>
			
				<tr style="border-bottom: 4px solid #ccc;">
					<td></td>
					<td></td>
				</tr>
				<tr style="border-bottom: 4px solid #ccc;">
                                        <td><b>JS LABELS</b></td>
                                        <td><div class="fa fa-plus" id="add-new-js-label" type="button" data-hint="js_lbl"></div></td>
                                </tr>
				
				{if $JS_LANGUAGE_STRING_ARRAY}
				{foreach item=JS_MEANING key=JS_LABEL from=$JS_LANGUAGE_STRING_ARRAY }
                                <tr>
                                        <td>{$JS_LABEL}</td>
                                        <td name="{$JS_LABEL}" class="{$JS_LABEL} meaning" id="{$JS_LABEL}">
						<input type="text" value="{$JS_MEANING}" class="language-input {$JS_LABEL}" readonly />
						<span data-label="{$JS_LABEL}" class="fa fa-pencil editor" style="display:none;"></span>
						<div class="language-edit" style="display:none;">
                                                        <span class="fa fa-check save-edit" data-label="{$JS_LABEL}" data-hint="js_lbl"></span>
                                                        <span class="fa fa-close close-edit"></span>
                                                </div>
					</td>
                                </tr>
                                {/foreach}
				{/if}
			</tbody>
		</table>
	</form>
	{/if}
</div>
