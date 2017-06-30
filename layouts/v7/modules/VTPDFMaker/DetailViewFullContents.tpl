{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}
{strip}
    <input id="recordId" type="hidden" value="{$RECORD->getId()}" />
    <div class="col-sm-12 col-xs-12">
        {include file="DetailViewHeader.tpl"|vtemplate_path:$MODULE}
	{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
        <div class="detailview-content container-fluid">
            <div class="details row">
                <div class="block">
                    {assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
                    <div>
                        <h4>{vtranslate('PDF Maker - Properties of ', $MODULE_NAME)} " {$RECORD->get('name')} "</h4>
                    </div>
                    <hr>
	<table class="table detailview-table no-border">
		<tbody> 
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Name', $MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('name'))}</td>
			</tr>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Description', $MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('description'))}</td>
			</tr>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Module',$MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('module'))}</td>
			</tr>
			<tr>
				<td class="fieldLabel {$WIDTHTYPE}"><label class="muted marginRight10px">{vtranslate('Message',$MODULE_NAME)}</label></td>
				<td class="fieldValue {$WIDTHTYPE}">{decode_html($RECORD->get('body'))}</td>
			</tr>
		</tbody>
	</table>
</div></div></div>
{/strip}
