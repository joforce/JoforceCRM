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
    <div class="editContainer card m30 Report_page_edit_view" style="padding-left: 2%;padding-right: 2%">
    <div class="card-header-new mb20"><h3>Report</h3></div>
        <div class="row ">

            {assign var=LABELS value = ["step1" => "LBL_REPORT_DETAILS", "step2" => "LBL_SELECT_COLUMNS", "step3" => "LBL_FILTERS"]}
            {include file="BreadCrumbs.tpl"|vtemplate_path:$MODULE ACTIVESTEP=1 BREADCRUMB_LABELS=$LABELS MODULE=$MODULE}
        </div>
        <div class="clearfix"></div>
{/strip}