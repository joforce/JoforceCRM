{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
<div class="detailViewContainer">
    <div class="col-sm-12">
	<div class=" detailview-header-block">
	    <div class="detailview-header">
		<div class="row">
		    {include file="DetailViewHeaderTitle.tpl"|vtemplate_path:$MODULE_NAME MODULE=$MODULE_NAME}
		    {include file="DetailViewActions.tpl"|vtemplate_path:Head MODULE=$MODULE_NAME}
                </div>
            </div>
	    {include file='DetailViewBlockView.tpl'|@vtemplate_path:Head RECORD_STRUCTURE=$RECORD_STRUCTURE MODULE_NAME=$MODULE_NAME}
	</div>
    </div>
</div></div>
{/strip}
