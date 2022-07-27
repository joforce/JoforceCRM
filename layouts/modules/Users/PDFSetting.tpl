<form class="form form-horizontal" id="PDFSetting" method="POST" action="index.php" enctype="multipart/form-data" style="display: inline-block;">

    <input type="hidden" name="module" value="PDFMaker" />
    <input type="hidden" name="action" value="Save" />
    <input type="hidden" name="parent" value="PDFSetting" />
    <input type="hidden" name="record" value="2" />
    <div class="blockData">
        <br>
        <div class="block">
            <div style="text-align: center;">
                <h4 class="">{vtranslate('PDF Settings', $MODULE)}</h4>
            </div> 
            <hr>
               <table class="table table-bordered blockContainer showInlineTable"> 
             
                <div class="col-md-6 col-lg-10 offset-md-2 row-space">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}"><span class="inline-label">{vtranslate('Page Format', $MODULE)}</span></div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                        <select name='page_format' class='inputElement select2 chzn-select' style="height: 30px;width: 200px;">
                            <option value='A3' {if $settings['page_format'] eq 'A3'} selected {/if}>A3</option>
                        <option value='A4' {if $settings['page_format'] eq 'A4'} selected {/if}>A4</option>
                        <option value='A5'{if $settings['page_format'] eq 'A5'} selected {/if}>A5</option>
                        <option value='A6'{if $settings['page_format'] eq 'A6'} selected {/if}>A6</option>
                    </select>    
                    </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-10 offset-md-2 row-space"> 
                    <div class="col-md-5 pr0">
                       <div class="fieldLabel {$WIDTHTYPE}"><span class="inline-label">{vtranslate('Page Orientation', $MODULE)}</span></div>
                    </div>
                    <div class="col-md-6 pl0">
                       <div class="fieldValue {$WIDTHTYPE}">
                        <select name='page_orientation' class='inputElement select2 chzn-select' style="height: 30px;width: 200px;"> 
                            <option value='P' {if $settings['page_orientation'] eq 'P'} selected {/if}>Portrait</option>      
                            <option value='L' {if $settings['page_orientation'] eq 'L'} selected {/if}>Landscape</option>
                        </select>    
                       </div>
                       </div>
                </div>
                <div class="col-md-6 col-lg-10 offset-md-2 row-space">
                    <div class="col-md-5 pr0">
                    <div class="fieldLabel {$WIDTHTYPE}"><span class="inline-label">{vtranslate('Margins', $MODULE)}</span></div>
                    </div>
                    <div class="col-md-6 pl0">
                    <div class="fieldValue {$WIDTHTYPE}">
                            <div class="col-md-6" style="margin-left: -8%;">
                            <span style='position:relative;margin-left:15px;'>Top</span>
                            <input type='text' class="inputElement mb5" name='margin_top' style='position:relative;width:100%;margin-left:15px;' value='10%'></div>
                            <div class="col-md-6">
                            <span style='position:relative;margin-left:15px;'>Bottom</span>
                            <input type='text' class="inputElement mb5" name='margin_bottom' style='position:relative;width:100%;margin-left:15px;' value='10%'></div>
                            <div class="col-md-6" style="margin-left: -8%;">
                            <span style='position:relative;margin-left:15px;'>Left</span>
                            <input type='text' class="inputElement mb5" name='margin_left' style='position:relative;width:100%;margin-left:15px;' value='10%'></div>
                            <div class="col-md-6">
                            <span style='position:relative;margin-left:15px;'>Right</span>
                            <input type='text' class="inputElement mb5" name='margin_right' style='position:relative;width:100%;margin-left:15px;' value='10%'></div>
                    </div>
                    </div>
                </div> 
          </table>
        </div>
        <br>
    </div>
</form>
