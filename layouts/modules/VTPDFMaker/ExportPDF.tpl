{*<!--
/* +**********************************************************************************
 * The contents of this file are subject to the JoForce Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Developer of the Original Code is JoForce.
 * All Rights Reserved.
 * ********************************************************************************** */
-->*}
    
{if $COUNT < 1}
    <span class='no-data'>{vtranslate('LBL_NO_TEMPLATE', $MODULE)}</span>
{else}
    <input type='hidden' value='{$TEMPLATE}' id='pdf_template'>
    <input id='source_module' type='hidden' value='{$source_module}'/>
    <div class='sidebar-block'>
        <a onclick='VTPDFMaker_Helper_Js.exportPDF({$RECORD});'><i class="fa fa-file-pdf-o"></i><strong>  Export  </strong></a>
    </div>
    <br>

{/if}


