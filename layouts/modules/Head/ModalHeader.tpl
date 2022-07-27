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
    <div class="modal-header {$PAGE} {if in_array($MODULE,array('Products'))} product_select_popup_header {elseif in_array($MODULE,array(''))} Layout_editor_page {/if}">
        <div class=" w-100 d-flex justify-content-between module-header-class ">
        <div>
        <h4 class="">
                {$TITLE}
            </h4>
            </div>
            <div class="" >
                <button type="button" class="close " aria-label="Close" data-dismiss="modal">
                    <span aria-hidden="true" class='fa fa-close'></span>
                </button>
            </div>
            
        </div>
    </div>
{/strip}    