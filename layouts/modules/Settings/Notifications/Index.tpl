{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{strip}
<div class="setnotifications card">
        <div class="col-sm-12 ml20">
	    
		<div class="editViewHeader  mr10 mt0 card-header-new row notification-header">
			<h1 class="col-md-11 col-sm-11">Notifications</h1>
			<span class="fa fa-info-circle alertShow pull-right ml20 mt15 "></span>
		</div>
		<div class="alerthide">
    <div class="col-sm-12 tooltiptext ">
	<div class="row">

	
		<div class="col-md-6"></div>
	    <div class="vt-default-callout vt-info-callout col-md-5 pull-right ">
		<h4 class="vt-callout-header" >
		    <span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE_NAME)}
		</h4>
		<p >{vtranslate('LBL_NOTIFICATION_SETTINGS_INFO', $QUALIFIED_MODULE_NAME)}</p>
	    </div>
		
	
	</div>
    </div>
	</div>
			{* <div class="row">
		<div class="col-md-6"></div>
		<div class=" col-md-6 pull-right vt-default-callout vt-info-callout pl10" >
		    <h4 class="vt-callout-header"><span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE_NAME)}</h4>
		    <p>{vtranslate('LBL_NOTIFICATION_SETTINGS_INFO', $QUALIFIED_MODULE_NAME)}</p>
		</div>
	    </div> *}
	</div>

        <form method="POST" id="notification-editor-form" class="form-horizontal mt20" action="{$SITEURL}index.php?module=Notifications&parent=Settings&action=SaveSettings">
	    <div class="d-flex justify-content-between align-items-center" style="margin:0 auto;width:90%;">
		<div class="checkbutton notification_checkbutton">
					<input type="checkbox" id="global-notification" name="global-notification" {if $GLOBAL_SETTINGS} checked{/if} data-value="{if $GLOBAL_SETTINGS}enabled{else}disabled{/if}" />
					<label>Enable Notifications</label>
			</div>
			<div>
				<button class="btn btn-success save-section pull-right" id="save-settings" type="submit">
							<strong>{vtranslate('LBL_SAVE', $MODULE)}</strong>
				</button>
			</div>
	    </div>

	    <div id="notification-editor-div" {if !$GLOBAL_SETTINGS} style="display:none;" {/if} >
		<table id="listview-table" class="table listview-table" style="max-width: 90%;table-layout: fixed;margin: auto;border-left: solid 1px #ccc !important;border-right: solid 1px #ccc !important;border-bottom: solid 1px #ccc !important;">
		    <thead>
{**		    	<tr class="listViewContentHeader">
			    <th><label>Notifications For All record I can view</label></th>
			    <th>
				<input type="checkbox" id="notification-for-all" name="notification-for-all" {if $notify_all}checked{/if} data-value="{if $notify_all}enabled{else}disabled{/if}" />
				<div class="input-info-addon">
				    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="Enable this to notify activities which are related to all records you can view & edit"></i>
				</div>
			    </th>
			    <th>
			    </th>
		    	</tr>
		    </thead>**}
		    <tbody class="overflow-y">
		    	<tr class="listViewContentHeader text-center">
		    	    <td><label>Module</label></td>
			    <td>
				<label>Updates on record (assigned to me)</label>
                                <div class="input-info-addon">
                                    <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="When Record is assigned to you and any updates on the records which are assigned to you"></i>
                                </div>
			    </td>
			    <td><label>Updates on following record</label></td>
		    	</tr>
		    	{foreach from=$PERMITTED_MODULES key=PERMITTED_MODULE item=PERMITTED_MODULE_SETTINGS}
			    {assign var=tabid value=getTabid($PERMITTED_MODULE)}
			    {if in_array($tabid, $user_permitted_modules)}
			    	<tr class="listViewEntries text-center">
			    	    <td class="listViewEntryValue textOverflowEllipsis">
				    	<span class=""><b>{vtranslate($PERMITTED_MODULE, $PERMITTED_MODULE)}</b></span>
				    </td>
			    	    <td class="listViewEntryValue textOverflowEllipsis">
				    	<input type="checkbox" name="{$PERMITTED_MODULE}_assigned" class="check-box" {if $PERMITTED_MODULE_SETTINGS['assigned'] == 1} checked {/if} />
				    </td>
			    	    <td class="listViewEntryValue textOverflowEllipsis">
				    	<input type="checkbox" name="{$PERMITTED_MODULE}_following" class="check-box" {if $PERMITTED_MODULE_SETTINGS['following'] == 1} checked {/if} />
				    </td>
			    	</tr>
			    {/if}
		    	{/foreach}
		    </tbody>
	    	</table>
	    </div>
	</form>
{/strip}
