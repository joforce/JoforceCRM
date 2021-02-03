{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{strip}
<!--LIST VIEW RECORD ACTIONS-->

<div class="table-actions">
    <a data-id="{$LISTVIEW_ENTRY->getId()}" href="{$LISTVIEW_ENTRY->getFullDetailViewUrl()}" title="{vtranslate('LBL_DETAILS', $MODULE)}"> <i class="fa fa-eye"> </i></a>
    <a data-id="{$LISTVIEW_ENTRY->getId()}" href="javascript:void(0);" data-url="{$LISTVIEW_ENTRY->getEditViewUrl()}" name="editlink" title="{vtranslate('LBL_EDIT', $MODULE)}"><i class="fa fa-pencil"></i></a>
    <a data-id="{$LISTVIEW_ENTRY->getId()}" class="deleteRecordButton" title="{vtranslate('LBL_DELETE', $MODULE)}"><i class="fa fa-trash"></i></a>
</div>
{/strip}
