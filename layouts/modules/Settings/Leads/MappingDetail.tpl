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
    <div class="leadsFieldMappingListPageDiv">
            <div class="row settingsHeader">
                <span class="col-sm-12">
                    <span class="pull-right">
                        {foreach item=LINK_MODEL from=$MODULE_MODEL->getDetailViewLinks()}
                            <button type="button" class="btn btn-default" onclick={$LINK_MODEL->getUrl()}><strong>{vtranslate($LINK_MODEL->getLabel(), $QUALIFIED_MODULE)}</strong></button>
                        {/foreach}
                    </span>
                </span>
            </div>
            <br/>
            <div class="contents table-container" id="detailView">
                <table id="listview-table" class="table listview-table" style="margin-bottom:2%;">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{vtranslate('LBL_FIELD_LABEL', $QUALIFIED_MODULE)}</th>
                            <th>{vtranslate('LBL_FIELD_TYPE', $QUALIFIED_MODULE)}</th>
                            <th colspan="3" >{vtranslate('LBL_MAPPING_WITH_OTHER_MODULES', $QUALIFIED_MODULE)}</th>
                        </tr>
                        <tr>
                            {foreach key=key item=LABEL from=$MODULE_MODEL->getHeaders()}
                                <th><b>{vtranslate($LABEL, $LABEL)}</b></th>
                            {/foreach}
                            <th><strong>{vtranslate('LBL_ACTIONS', $QUALIFIED_MODULE)}</strong></th>                        
                        </tr>
                    </thead>
                    <tbody>
                        {foreach key=MAPPING_ID item=MAPPING from=$MODULE_MODEL->getMapping()}
                            <tr class="listViewEntries" data-cfmid="{$MAPPING_ID}">
                                <td>{vtranslate({$MAPPING['Leads']['label']}, 'Leads')}</td>
                                <td>{vtranslate({$MAPPING['Leads']['fieldDataType']}, $QUALIFIED_MODULE)}</td>
                                <td>{vtranslate({$MAPPING['Accounts']['label']}, 'Accounts')}</td>
                                <td>{vtranslate({$MAPPING['Contacts']['label']}, 'Contacts')}</td>
                                <td>{vtranslate({$MAPPING['Potentials']['label']}, 'Potentials')}</td>
                                <td>
                                    {if $MAPPING['editable'] eq 1}
                                        {foreach item=LINK_MODEL from=$MODULE_MODEL->getMappingLinks()}
                                            <div class="table-actions">
                                                <span>
                                                    <a onclick={$LINK_MODEL->getUrl()}><i title="{vtranslate($LINK_MODEL->getLabel(), $MODULE)}" class="fa fa-trash alignMiddle"></i></a>
                                                </span>
                                            </div>
                                        {/foreach}
                                    {/if}
                                </td>                                
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
            <div id="scroller_wrapper" class="bottom-fixed-scroll">
                <div id="scroller" class="scroller-div"></div>
            </div>
    </div>
{/strip}
