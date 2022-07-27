{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<div class="col-sm-12 col-xs-12 setpipeline mt0 card">
    <div class="" style="position: relative; clear:both;">
	<div id="table-content" class="table-container ps-container" style="">
	<div class="userFilter card-header-new row mb20 justify-content-between" style="">
		<h4 class="col-md-6 col-sm-6 mt10">Kanban</h4>
		<a class="btn btn-default addButton pull-right" id="add-pipeline" href="{$SITEURL}Pipeline/Settings/List?mode=create" data-searchvalue="Active"><i class="fa fa-plus"></i>Add Kanban view</a>
	    </div>

	    <table id="listview-table" class="table listview-table mt20" style="min-width:97%;">
		<thead>
		    <tr class="listViewContentHeader">
			<th nowrap="" class="floatThead-col">{vtranslate('LBL_MODULE')}</th>
			<th nowrap="" class="floatThead-col">{vtranslate('LBL_PICKLIST')}</th>
			<th nowrap="" class="floatThead-col">{vtranslate('LBL_RECORDS_PER_PAGE', $QUALIFIED_MODULE)}</th>
			<th style="width:10% !important" class="floatThead-col">{vtranslate('LBL_ACTIONS')}</th>
		    </tr>
		</thead>
		<tbody class="overflow-y">
		    {if $count gt 0}
			{foreach item="pipeline" from=$pipeline_modules}
			    {assign var=fieldlabel value=Settings_Pipeline_Module_Model::getPicklistFieldLabel($pipeline.picklist_name , $pipeline.tabid)}
			    <tr class="listViewEntries" data-id="" id="kanba_pipeline_{$pipeline.pipeline_id}">
			    	<td class="listViewEntryValue textOverflowEllipsis">{vtranslate($pipeline.tabname)}</td>
			    	<td class="listViewEntryValue textOverflowEllipsis">{vtranslate($fieldlabel)}</td>
				<td class="listViewEntryValue textOverflowEllipsis">{$pipeline.records_per_page}</td>
				<td width="10%">
                                    <div class="table-actions">
                                        <a class="pipeline-edit ml10" href="{$SITEURL}Pipeline/Settings/List?mode=edit&id={$pipeline.pipeline_id}" data-id="{$pipeline.pipeline_id}">
                                            <i class="fa fa-pencil" title="Edit" data-id="{$pipeline.pipeline_id}"></i>
                                        </a>
                                        <span class="pipeline-delete" data-id="{$pipeline.pipeline_id}">
                                            <i class="fa fa-trash" title="Delete" data-id="{$pipeline.pipeline_id}"></i>
                                        </span>
                                    </div>
                                </td>
		            </tr>
		    	{/foreach}
		    {else}
			<tr class="listViewEntries">
			    <td></td>
			    <td>{vtranslate('NO MODULE ADDED FOR PIPELINE')}</td>
			    <td></td>
			</tr>
		    {/if}
		</tbody>
	    </table>
	</div>
    </div>
</div>
