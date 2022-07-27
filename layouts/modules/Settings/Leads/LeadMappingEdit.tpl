{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ********************************************************************************/
-->*}

{strip}
    <div class="leadsFieldMappingEditPageDiv mt0 card">
    <div class="row settingsHeader pl20 pr20 ml20">
            <span class="col-sm-12 col-md-12 card-header-new row lead_mapping_header_setting_edit">
                    <h4 >Add Lead Conversion Data Mapping </h4>
                    
                </span>
            </div>
            <div class="editViewContainer pb0 ">
                <form id="leadsMapping" method="POST">
                    <div class="editViewBody ">
                        <div class="editViewContents table-container" >
                            <input type="hidden" id="restrictedFieldsList" value={ZEND_JSON::encode($RESTRICTED_FIELD_IDS_LIST)} />
                            <table class="table listview-table-norecords- {if in_array($MODULE,array('Leads'))} ms_srn_table_align {/if}" width="100%" id="convertLeadMapping">
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th>{vtranslate('LBL_FIELD_LABEL', $QUALIFIED_MODULE)}</th>
                                        <th>{vtranslate('LBL_FIELD_TYPE', $QUALIFIED_MODULE)}</th>
                                        <th colspan="3" >{vtranslate('LBL_MAPPING_WITH_OTHER_MODULES', $QUALIFIED_MODULE)}</th>
                                    </tr>
                                    <tr>
                                        {foreach key=key item=LABEL from=$MODULE_MODEL->getHeaders()}
                                            <td><b>{vtranslate($LABEL, $LABEL)}</b></td>
                                        {/foreach}
                                        <td><strong>{vtranslate('LBL_ACTIONS', $QUALIFIED_MODULE)}</strong></td>                                    
                                    </tr>
                                    {foreach key=MAPPING_ID item=MAPPING_ARRAY from=$MODULE_MODEL->getMapping()  name="mappingLoop"}
                                        <tr class="listViewEntries" sequence-number="{$smarty.foreach.mappingLoop.iteration}">
                                            <td>
                                                <input type="hidden" name="mapping[{$smarty.foreach.mappingLoop.iteration}][mappingId]" value="{$MAPPING_ID}"/>
                                                <select class="leadsFields select2 col-sm-12" name="mapping[{$smarty.foreach.mappingLoop.iteration}][lead]" {if $MAPPING_ARRAY['editable'] eq 0} disabled {/if}>
                                                    {foreach key=FIELD_TYPE item=FIELDS_INFO from=$LEADS_MODULE_MODEL->getFields()}
                                                        {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                            <option data-type="{$FIELD_TYPE}" {if $FIELD_ID eq $MAPPING_ARRAY['Leads']['id']} selected {/if} label="{vtranslate($FIELD_OBJECT->get('label'), $LEADS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                    {vtranslate($FIELD_OBJECT->get('label'), $LEADS_MODULE_MODEL->getName())}
                                                            </option>
                                                        {/foreach}
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td class="selectedFieldDataType">{vtranslate($MAPPING_ARRAY['Leads']['fieldDataType'], $QUALIFIED_MODULE)}</td>
                                            <td>
                                                <select class="accountsFields select2 col-sm-12" name="mapping[{$smarty.foreach.mappingLoop.iteration}][account]" {if $MAPPING_ARRAY['editable'] eq 0} disabled {/if}>
                                                    <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                        {foreach key=FIELD_TYPE item=FIELDS_INFO from=$ACCOUNTS_MODULE_MODEL->getFields()}
                                                            {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                                {if $MAPPING_ARRAY['Leads']['fieldDataType'] eq $FIELD_TYPE}
                                                                    <option data-type="{$FIELD_TYPE}" {if $FIELD_ID eq $MAPPING_ARRAY['Accounts']['id']} selected {/if} label="{vtranslate($FIELD_OBJECT->get('label'), $ACCOUNTS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                            {vtranslate($FIELD_OBJECT->get('label'), $ACCOUNTS_MODULE_MODEL->getName())}
                                                                    </option>
                                                                {/if}
                                                            {/foreach}
                                                        {/foreach}
                                                </select>
                                            </td>
                                            <td>
                                                <select class="contactFields select2 col-sm-12" name="mapping[{$smarty.foreach.mappingLoop.iteration}][contact]" {if $MAPPING_ARRAY['editable'] eq 0} disabled {/if}>
                                                    <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                    {foreach key=FIELD_TYPE item=FIELDS_INFO from=$CONTACTS_MODULE_MODEL->getFields()}
                                                        {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                            {if $MAPPING_ARRAY['Leads']['fieldDataType'] eq $FIELD_TYPE}
                                                                <option data-type="{$FIELD_TYPE}" {if $FIELD_ID eq $MAPPING_ARRAY['Contacts']['id']} selected {/if} label="{vtranslate($FIELD_OBJECT->get('label'), $CONTACTS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                    {vtranslate($FIELD_OBJECT->get('label'), $CONTACTS_MODULE_MODEL->getName())}
                                                                </option>
                                                            {/if}
                                                        {/foreach}
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td>
                                                <select class="potentialFields select2 col-sm-12" name="mapping[{$smarty.foreach.mappingLoop.iteration}][potential]" {if $MAPPING_ARRAY['editable'] eq 0} disabled {/if}>
                                                    <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                    {foreach key=FIELD_TYPE item=FIELDS_INFO from=$POTENTIALS_MODULE_MODEL->getFields()}
                                                        {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                            {if $MAPPING_ARRAY['Leads']['fieldDataType'] eq $FIELD_TYPE}
                                                                <option data-type="{$FIELD_TYPE}" {if $FIELD_ID eq $MAPPING_ARRAY['Potentials']['id']} selected {/if} label="{vtranslate($FIELD_OBJECT->get('label'), $POTENTIALS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                    {vtranslate($FIELD_OBJECT->get('label'), $POTENTIALS_MODULE_MODEL->getName())}
                                                                </option>
                                                            {/if}
                                                        {/foreach}
                                                    {/foreach}
                                                </select>
                                            </td>
                                            <td>
                                                {if $MAPPING_ARRAY['editable'] eq 1}
                                                    {foreach item=LINK_MODEL from=$MODULE_MODEL->getMappingLinks()}
                                                        <div class="table-actions">
                                                            <span class="actionImages">
                                                                <i title="{vtranslate($LINK_MODEL->getLabel(), $MODULE)}" class="fa fa-trash deleteMapping"></i>
                                                            </span>
                                                        </div>
                                                    {/foreach}
                                                {/if}
                                            </td>                                            
                                        </tr>
                                    {/foreach}
                                    <tr class="hide newMapping listViewEntries">
                                        
                                        <td>
                                            <select class="leadsFields newSelect col-sm-12">
                                                <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                {foreach key=FIELD_TYPE item=FIELDS_INFO from=$LEADS_MODULE_MODEL->getFields()}
                                                    {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                        <option data-type="{$FIELD_TYPE}" label="{vtranslate($FIELD_OBJECT->get('label'), $LEADS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                {vtranslate($FIELD_OBJECT->get('label'), $LEADS_MODULE_MODEL->getName())}
                                                        </option>
                                                    {/foreach}
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td class="selectedFieldDataType"></td>
                                        <td>
                                            <select class="accountsFields newSelect col-sm-12">
                                                <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                {foreach key=FIELD_TYPE item=FIELDS_INFO from=$ACCOUNTS_MODULE_MODEL->getFields()}
                                                    {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                        <option data-type="{$FIELD_TYPE}" label="{vtranslate($FIELD_OBJECT->get('label'), $ACCOUNTS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                {vtranslate($FIELD_OBJECT->get('label'), $ACCOUNTS_MODULE_MODEL->getName())}
                                                        </option>
                                                    {/foreach}
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td>
                                            <select class="contactFields newSelect col-sm-12">
                                                <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                {foreach key=FIELD_TYPE item=FIELDS_INFO from=$CONTACTS_MODULE_MODEL->getFields()}
                                                    {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                        <option data-type="{$FIELD_TYPE}" label="{vtranslate($FIELD_OBJECT->get('label'), $CONTACTS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                {vtranslate($FIELD_OBJECT->get('label'), $CONTACTS_MODULE_MODEL->getName())}
                                                        </option>
                                                    {/foreach}
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td>
                                            <select class="potentialFields newSelect col-sm-12">
                                                <option data-type="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" label="{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}" value="0">{vtranslate('LBL_NONE', $QUALIFIED_MODULE)}</option>
                                                {foreach key=FIELD_TYPE item=FIELDS_INFO from=$POTENTIALS_MODULE_MODEL->getFields()}
                                                    {foreach key=FIELD_ID item=FIELD_OBJECT from=$FIELDS_INFO}
                                                        <option data-type="{$FIELD_TYPE}" label="{vtranslate($FIELD_OBJECT->get('label'), $POTENTIALS_MODULE_MODEL->getName())}" value="{$FIELD_ID}">
                                                                {vtranslate($FIELD_OBJECT->get('label'), $POTENTIALS_MODULE_MODEL->getName())}
                                                        </option>
                                                    {/foreach}
                                                {/foreach}
                                            </select>
                                        </td>
                                        <td>
                                            {foreach item=LINK_MODEL from=$MODULE_MODEL->getMappingLinks()}
                                                <div class="table-actions">
                                                    <span class="actionImages">
                                                        <i title="{vtranslate($LINK_MODEL->getLabel(), $MODULE)}" class="fa fa-trash deleteMapping"></i>
                                                    </span>
                                                </div>
                                            {/foreach}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <span class="col-sm-4">
                                    <button id="addMapping" class="btn btn-success addButton" type="button">
                                        <i class="fa fa-plus"></i>&nbsp;&nbsp;{vtranslate('LBL_ADD_MAPPING', $QUALIFIED_MODULE)}
                                    </button>
                                </span>
                                <span class="col-sm-8">
                                    <span class="pull-right">
                                        <button type="submit" class="btn btn-primary"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>
                                        <a class="cancelLink btn btn-danger" type="" href="{$MODULE_MODEL->getDetailViewUrl()}">Cancel</a>
                                    </span>
                                </span>
                            </div>
			</div>
                    </div>
		</form>
            </div>
    </div>
{/strip}
