{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
<style>
.searchBox .select2-choice{
	height:34px !important;
}
</style>
{strip}
<div class="dropdown" style="width:100%;">
			<i class="Gsearch"></i><input type="text" id="joforce-search-box"  style="flex:1;" class="keyword-input form-control" name="s" id="s" placeholder="search...">
			<span class='icon-down {if $LEFTPANELHIDE eq '1'}search-open-1{/if} {if $LEFTPANELHIDE eq '0'}search-open-0{/if}' id="joforce-advanced-search" data-toggle="dropdown" aria-expanded="false" aria-hidden="true" style="margin-left:unset;"></span>
			<div id="searchResults-container new" class="searchBox dropdown-menu">
			    <div style="padding:15px;background-color:#fff;padding-top:20px">
				<label>Select Module</label>
				{assign var=modulesList value=Head_Menu_Model::getAll(true)}
				<select id="joforce-select-search-box" class="select2 form-control" >
				    <option value="" disabled selected>Search</option>
				    {foreach item=modulelabel key=modulename from=$modulesList}
					    <option value="{$modulename}">{vtranslate($modulename, 'Head')}</option>
				    {/foreach}
				</select>		
			    </div>
			    <div class="searchResults">
				<input type="hidden" value="{$SEARCH_VALUE|escape:"html"}" id="searchValue">
				<div class="">
				    <div class="container-fluid moduleResults-container" style="background-color:#fff !important;">
					<input type="hidden" name="groupStart" value="{$GROUP_START}" class="groupStart"/>
					<label>Select Field</label>
					<select class="select2 form-control" id="filterField" name="role2fieldnames[]" {if empty($SELECTED_MODULE_FIELDS) }  placeholder="{vtranslate("LBL_SELECT",$QUALIFIED_MODULE)}" {/if}>
					    {foreach key=FIELD_NAME item=FIELD_MODEL from=$MODULE_FIELDS}
						<option class="role2fieldnames_{$FIELD_NAME}" value="{$FIELD_NAME}"
						{if is_array($SELECTED_MODULE_FIELDS)} 
						    {if in_array($FIELD_NAME, $SELECTED_MODULE_FIELDS)} selected {/if}
						{/if}>
						{vtranslate($FIELD_MODEL->label,$SELECTED_MODULE_NAME)}
						</option>
					    {/foreach}
					</select>	
				    </div>
				</div>
			    </div>

			    <div class="conditionComparator" style="padding:15px;background-color:#fff;">
				<label>Select Condition</label>
				<select id="filterCondition" class="{if empty($NOCHOSEN)}select2{/if} form-control" name="comparator">
				    <option value="equal">equal to</option>
				    <option value="notequal">not equal to</option>
				    <option value="starts">starts with</option>
				    <option value="ends">ends with</option>
				    <option value="contains">contains</option>
				    <option value="notcontains">not contains</option>
				</select>
			    </div>

			    <div class="dropdown-search" style="padding:15px;background-color:#fff;padding-top:0px;">
				<label>Enter Value</label>
				<input id="filterValue" class="form-control" type="text" value="" style="height:34px;"/>
			    </div>

			    <input id="joforce-search-btn" type="button" value="Search" class="btn btn-primary" style="float:right;margin-right:15px;"/>
			</div>
		    </div>
			
{/strip}
