<!--<h2><img src="layouts/modules/Settings/{$MODULE}/assets/images/template.png" alt="{vtranslate('LBL_TEMPLATE_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_TEMPLATE', $QUALIFIED_MODULE)}</h2>

<table class='table table-borderless'>
<tr>
	<td class='col-sm-4'>{vtranslate('LBL_DIRECTORY_TEMPLATE', $QUALIFIED_MODULE)}</td>
	<td>
		<select name="module_directory_template" onChange="md_selectDirectory(this)" class='select2'>
			<option value="">{vtranslate('LBL_SELECT_OPTION', $QUALIFIED_MODULE)}</option>
			{foreach item=template from=$LIST_DIR_TEMPLATES}
			<option value="{$template}">{$template}</option>
			{/foreach}
		</select>
	</td>
</tr>
<tr>
	<td>{vtranslate('LBL_MANIFEST_TEMPLATE', $QUALIFIED_MODULE)}</td>
	<td>
		<select name="module_manifest_template" class='select2'>
			<option value="">{vtranslate('LBL_SELECT_OPTION', $QUALIFIED_MODULE)}</option>
			{foreach item=template from=$LIST_MANIFEST_TEMPLATES}
			<option value="{$template}">{$template}</option>
			{/foreach}
		</select>
	</td>
</tr>
</table>-->
	<input type='hidden' name='module_directory_template' value='Module 6.x'>
	<input type='hidden' name='module_manifest_template' value='module.xml.php'>

<h2><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/module.png" alt="{vtranslate('LBL_MODULE_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_MODULE', $QUALIFIED_MODULE)}</h2>

<table id="md-module-name" class='table table-borderless'>
<tr>
	<td class='col-sm-4'>{vtranslate('LBL_SYSTEM_MODULE_NAME', $QUALIFIED_MODULE)}</td>
	<td>
		<input type="text" name="module_name" class="md-medium-text-input inputElement" maxlength="25" onkeyup="md_setModuleName(this)" onfocusout="md_updateFieldsTableName(this)" placeholder='Contacts'/>
		<input type="hidden" name="old_module_table_name" />
	</td>
	<td>
		<a href="javascript:showLoadModulePopup()"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/load.png" alt="{vtranslate('LBL_LOAD_MODULE_ALT', $QUALIFIED_MODULE)}" title="{vtranslate('LBL_LOAD_MODULE', $QUALIFIED_MODULE)}" /></a> &nbsp;
		<a href="javascript:showUploadModulePopup()"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/upload.png" alt="{vtranslate('LBL_UPLOAD_MODULE_ALT', $QUALIFIED_MODULE)}" title="{vtranslate('LBL_UPLOAD_MODULE', $QUALIFIED_MODULE)}" /></a>
	</td>
</tr>
<tr>
	<td class='col-sm-4'>{vtranslate('LBL_VERSION', $QUALIFIED_MODULE)}</td>
	<td colspan="2">
		<input type="text" name="module_version" class="md-medium-text-input inputElement" maxlength="25" placeholder='1.0'/>
	</td>
</tr>
                                                <tr class="md-module-name-translation">
                                                                <td>{vtranslate('LBL_MODULE_NAME_TRANSLATION', $QUALIFIED_MODULE)} <em>en_us</em></td>
                                                                <td colspan="2"><input type="text" name="module_label_en_us" class="md-medium-text-input inputElement" placeholder='Contacts'/></td>
                                                                </tr>

                                                <tr class="md-module-name-translation">
                                                                <td>{vtranslate('LBL_MODULE_NAME_SINGLE_TRANSLATION', $QUALIFIED_MODULE)} <em>en_us</em></td>
                                                                <td colspan="2"><input type="text" name="module_label_single_en_us" class="md-medium-text-input inputElement" placeholder='Contact'/></td>
                                                                </tr>
</table>

<h2><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/menu.png" alt="{vtranslate('LBL_PARENT_TAB_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_PARENT_TAB', $QUALIFIED_MODULE)}</h2>

<table class='table table-borderless'>
<tr>
	<td class='col-sm-4'>{vtranslate('LBL_PARENT_TAB_CHOICE', $QUALIFIED_MODULE)}</td>
	<td>
		<select name="module_parent_tab" class='select2'>
			<option value="">{vtranslate('LBL_SELECT_OPTION', $QUALIFIED_MODULE)}</option>
			{foreach item=parent_tab from=$LIST_PARENT_TABS}
				<option value="{$parent_tab.parenttab_label}">{vtranslate($parent_tab.parenttab_label)}</option>
			{/foreach}
		</select>
	</td>
</tr>
</table>

<input type="hidden" name="md-languages" />
<input type="hidden" name="md_modified_module" />
<input type="hidden" name="md_modified_module_path" />
