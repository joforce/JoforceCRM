{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 ************************************************************************************}

<div class="table-actions">
        {if $MODULE_MODEL->isFilterColumnEnabled()}
        <div id="listColumnFilterContainer">
		 <i class="fa fa-search cursorPointer" id="joforce-table-search" style="{if $CURRENT_CV_MODEL and !($CURRENT_CV_MODEL->isCvEditable())}margin-top:5px !important;margin-left:18px !important;{/if}"></i>

                <div class="listColumnFilter {if $CURRENT_CV_MODEL and !($CURRENT_CV_MODEL->isCvEditable())}disabled{/if}"  
                        {if $CURRENT_CV_MODEL->isCvEditable()}
                                title="{vtranslate('LBL_CLICK_HERE_TO_MANAGE_LIST_COLUMNS',$MODULE)}"
                        {else}
                                {if $CURRENT_CV_MODEL->get('viewname') eq 'All' and !$CURRENT_USER_MODEL->isAdminUser()}                           
                                        title="{vtranslate('LBL_SHARED_LIST_NON_ADMIN_MESSAGE',$MODULE)}"
                                {elseif !$CURRENT_CV_MODEL->isMine()}
                                        {assign var=CURRENT_CV_USER_ID value=$CURRENT_CV_MODEL->get('userid')}
                                        {if !Head_Functions::isUserExist($CURRENT_CV_USER_ID)}
                                                {assign var=CURRENT_CV_USER_ID value=Users::getActiveAdminId()}
                                        {/if}
                                        title="{vtranslate('LBL_SHARED_LIST_OWNER_MESSAGE',$MODULE, getUserFullName($CURRENT_CV_USER_ID))}"
                                {/if}
                        {/if}
                                {if $MODULE eq 'Documents'}style="width: 10%;"{/if}
                                        data-toggle="tooltip" data-placement="bottom" data-container="body"><i class="fa fa-th-large"></i>
                 </div>
        </div>
        {/if}
</div>
