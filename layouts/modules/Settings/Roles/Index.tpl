{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Settings/Roles/views/Index.php *}

{strip}
    {assign var = LOGO value = Head_CompanyDetails_Model::getInstanceById()->getLogo()}
    {assign var = LOGO_NAME value = $LOGO->get('imagename') }
    {assign var = COMPANY_LOGO value = $LOGO->get('imagepath')}
    {assign var = COMPANY_NAME value = getCompanyDetails()}

    <div class="listViewPageDiv " id="listViewContent">
            <br>
            <div class="clearfix treeView">
                <ul>
                    <li data-role="{$ROOT_ROLE->getParentRoleString()}" data-roleid="{$ROOT_ROLE->getId()}">
                        <div class="toolbar-handle">
                            {if $COMPANY_LOGO}
                                {if $LOGO_NAME == 'JoForce-Logo.png'}
                                <a href="javascript:;" class="btn app-MARKETING droppable" >{$COMPANY_NAME['companyname']}</a>
                                {else}
                                <a href="javascript:;" class="btn app-MARKETING droppable" data-toggle="tooltip" data-placement="top" data-animation="true" title="{$COMPANY_NAME['companyname']}"><img src="{$COMPANY_LOGO}" style="max-width:70px;"/></a>
                                {/if}
                            {else}
                                <a href="javascript:;" class="btn app-MARKETING droppable"> {$COMPANY_NAME['companyname']}</a>
                             {/if}
                            <div class="toolbar" title="{vtranslate('LBL_ADD_RECORD', $QUALIFIED_MODULE)}">
                                &nbsp;<a href="{$ROOT_ROLE->getCreateChildUrl()}" data-url="{$ROOT_ROLE->getCreateChildUrl()}" data-action="modal"><span class="icon-plus-sign"></span></a>
                            </div>
                        </div>
                        {assign var="ROLE" value=$ROOT_ROLE}
                        {include file=vtemplate_path("RoleTree.tpl", "Settings:Roles")}
                    </li>
                </ul>
            </div>
    </div>
{/strip}
