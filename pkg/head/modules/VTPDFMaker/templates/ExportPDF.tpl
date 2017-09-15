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


