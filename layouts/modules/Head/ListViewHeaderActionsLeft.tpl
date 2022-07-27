{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}
<div class="table-actions table-left-column">
	<div class="dropdown" style="border:none !important;float:left;top: 9px;left: 7px;">
        	<span class="input" style="opacity:1;" title="{vtranslate('LBL_CLICK_HERE_TO_SELECT_ALL_RECORDS',$MODULE)}">
                	<input class="listViewEntriesMainCheckBox" type="checkbox">
                </span>
  	</div>
	<div class="" style="margin-left:25px;width:0px;">
		{include file="ListViewHeaderMoreActionsLeft.tpl"|vtemplate_path:$MODULE}
	</div>
	<button class="d-none btn btn-success btn-sm" data-trigger="listSearch">Search</button>
</div>


<script>
	
</script>