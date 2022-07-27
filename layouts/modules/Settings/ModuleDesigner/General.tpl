<input type='hidden' name='module_directory_template' value='Module 6.x'>
<input type='hidden' name='module_manifest_template' value='module.xml.php'>

<h2 class="general-header"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/module.png" alt="{vtranslate('LBL_MODULE_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_MODULE', $QUALIFIED_MODULE)}</h2>

<table id="md-module-name" class='table table-borderless col-sm-12'>
    <tbody class="row col-sm-12">
	<tr class="col-sm-6">
	    <td class='col-sm-4'>{vtranslate('LBL_SYSTEM_MODULE_NAME', $QUALIFIED_MODULE)}</td>
	    <td class='col-sm-5 p0'>
		<input type="text" name="module_name" class="md-medium-text-input inputElement" maxlength="25" onkeyup="md_setModuleName(this)" onfocusout="md_updateFieldsTableName(this)" placeholder='Contacts' id="module_name"/>
		<input type="hidden" name="old_module_table_name" />
	    </td>
	    <td class="col-sm-3">
		<a href="javascript:showLoadModulePopup()"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/load.png" alt="{vtranslate('LBL_LOAD_MODULE_ALT', $QUALIFIED_MODULE)}" title="{vtranslate('LBL_LOAD_MODULE', $QUALIFIED_MODULE)}" /></a> &nbsp;
		<a href="javascript:showUploadModulePopup()"><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/upload.png" alt="{vtranslate('LBL_UPLOAD_MODULE_ALT', $QUALIFIED_MODULE)}" title="{vtranslate('LBL_UPLOAD_MODULE', $QUALIFIED_MODULE)}" /></a>
	    </td>
	</tr>
	<tr class="col-sm-6">
	    <td class='col-sm-4'>{vtranslate('LBL_VERSION', $QUALIFIED_MODULE)}</td>
	    <td colspan="2" class="col-sm-8 p0">
		<input type="text" name="module_version" class="md-medium-text-input inputElement" maxlength="25" placeholder='1.0'/>
	    </td>
	</tr>
	<tr class="md-module-name-translation col-sm-6">
	    <td class="col-sm-4">{vtranslate('LBL_MODULE_NAME_TRANSLATION', $QUALIFIED_MODULE)} <em>en_us</em></td>
	    <td colspan="2" class="col-sm-8 p0"><input type="text" name="module_label_en_us" class="md-medium-text-input inputElement" placeholder='Contacts'/></td>
	</tr>
	<tr class="md-module-name-translation col-sm-6">
	    <td class="col-md-4">{vtranslate('LBL_MODULE_NAME_SINGLE_TRANSLATION', $QUALIFIED_MODULE)} <em>en_us</em></td>
		<td colspan="2" class="col-sm-8 p0"><input type="text" name="module_label_single_en_us" class="md-medium-text-input inputElement" placeholder='Contact'/></td>
	</tr>
    </tbody>
</table>

<h2><img src="{$SITEURL}layouts/modules/Settings/{$MODULE}/assets/images/menu.png" alt="{vtranslate('LBL_PARENT_TAB_ALT', $QUALIFIED_MODULE)}" /> {vtranslate('LBL_PARENT_TAB', $QUALIFIED_MODULE)}</h2>


<table class='table table-borderless'>
    <tbody class=" row col-sm-12">
	<tr class="col-sm-6 col-sm-6">
	    <td class='col-sm-4'>{vtranslate('LBL_PARENT_TAB_CHOICE', $QUALIFIED_MODULE)}</td>
	    <td class="col-sm-8">
		<select name="module_parent_tab" class='select2'>
		    <option value="">{vtranslate('LBL_SELECT_OPTION', $QUALIFIED_MODULE)}</option>
		    {foreach item=SECTION_ICON key=SECTION_NAME from=$SECTION_ARRAY}
	            	<option value="{ucfirst(strtolower($SECTION_NAME))}">{vtranslate($SECTION_NAME, 'Head')}</option>
		    {/foreach}
		</select>
	    </td>
	</tr>
    </tbody>
    <div class="row">
        <td><button class="btn btn-primary general-next-btn">Next</button></td>
    </div>
</table>

<input type="hidden" name="md-languages" value="en_us"/>
<input type="hidden" name="md_modified_module" />
<input type="hidden" name="md_modified_module_path" />

{literal}
<script type="text/javascript">
$(document).ready(function(){
    $(".general-next-btn").click(function(){
	var value=document.getElementById("module_name").value;
	if(value=='' ) {
	    alert("Enter the Module Name");
	} else {
            $("#md-tab-general").removeClass("active");
            $("#md-tab-general").css('pointer-events', 'auto');
            $("#md-tab-general").css('opacity','1');
            $("#md-tab-general").addClass("changed");
	    $("#md-tab-blocks-fields").addClass("active");
	    $("#md-page-general").css("display","none");
	    $("#md-page-blocks-fields").css("display","block");
	    $(".tab-pane").css("visibility","visible");
	    $("#md-tab-blocks-fields").css('pointer-events', 'auto');
	    $("#md-tab-blocks-fields").css('opacity','1');
	}	
    });

    $(".blk-next-btn").click(function(){
	$("#md-tab-blocks-fields").removeClass("active");
	$("#md-tab-blocks-fields").css('pointer-events', 'auto');
	$("#md-tab-blocks-fields").css('opacity','1');
	$("#md-tab-blocks-fields").addClass("changed");
	$("#md-tab-custom-links").addClass("active");
	$("#md-page-blocks-fields").css("display","none");
	$("#md-page-custom-links").css("display","block");
	$(".tab-pane").css("visibility","visible");
	$("#md-tab-custom-links").css('pointer-events', 'auto');
	$("#md-tab-custom-links").css("opacity","1");
    });

    $(".custom-next-btn").click(function(){
	$("#md-tab-custom-links").removeClass("active");
	$("#md-tab-custom-links").css('pointer-events', 'auto');
	$("#md-tab-custom-links").css("opacity","1");
	$("#md-tab-custom-links").addClass("changed");
	$("#md-tab-related-lists").addClass("active");
	$("#md-page-custom-links").css("display","none");
	$("#md-page-related-lists").css("display","block");
	$(".tab-pane").css("visibility","visible");
	$("#md-tab-related-lists").css('pointer-events', 'auto');
	$("#md-tab-related-lists").css("opacity","1");
    });

    $(".related-next-btn").click(function(){
	$("#md-tab-related-lists").removeClass("active");
	$("#md-tab-related-lists").css('pointer-events', 'auto');
	$("#md-tab-related-lists").css("opacity","1");
	$("#md-tab-related-lists").addClass("changed");
	$("#md-tab-events").addClass("active");
	$("#md-page-related-lists").css("display","none");
	$("#md-page-events").css("display","block");
	$(".tab-pane").css("visibility","visible");
	$("#md-tab-events").css('pointer-events', 'auto');
	$("#md-tab-events").css("opacity","1");
    });

    $(".events-next-btn").click(function(){
	$("#md-tab-events").removeClass("active");
	$("#md-tab-events").css('pointer-events', 'auto');
	$("#md-tab-events").css("opacity","1");
	$("#md-tab-events").addClass("changed");
	$("#md-tab-filters").addClass("active");
	$("#md-page-events").css("display","none");
	$("#md-page-filters").css("display","block");
	$(".tab-pane").css("visibility","visible");
	$("#md-tab-filters").css('pointer-events', 'auto');
	$("#md-tab-filters").css("opacity","1");
    });

    $(".filter-next-btn").click(function(){
	$("#md-tab-filters").removeClass("active");
	$("#md-tab-filters").css('pointer-events', 'auto');
	$("#md-tab-filters").css("opacity","1");
	$("#md-tab-filters").addClass("changed");
	$("#md-tab-custom").addClass("active");
	$("#md-page-filters").css("display","none");
	$("#md-page-custom").css("display","block");
	$(".tab-pane").css("visibility","visible");
	$("#md-tab-custom").css('pointer-events', 'auto');
	$("#md-tab-custom").css("opacity","1");
    });

    $(".cust-next-btn").click(function(){
	$("#md-tab-custom").removeClass("active");
	$("#md-tab-custom").css('pointer-events', 'auto');
	$("#md-tab-custom").css("opacity","1");
	$("#md-tab-custom").addClass("changed");
	$("#md-tab-export").addClass("active");
	$("#md-page-custom").css("display","none");
	$("#md-page-export").css("display","block");
	$(".tab-pane").css("visibility","visible");
	$("#md-tab-export").css("pointer-events","auto");
	$("#md-tab-export").css("opacity","1");
    });

    $(".export-prev-btn").click(function(){
	$("#md-tab-export").removeClass("active");
	$("#md-tab-export").css("pointer-events","auto");
	$("#md-tab-export").css("opacity","1");
	$("#md-tab-export").addClass("changed");
	$("#md-tab-custom").addClass("active");
	$("#md-page-export").css("display","none");
	$("#md-page-custom").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });

    $(".cust-prev-btn").click(function(){
	$("#md-tab-custom").removeClass("active");
	$("#md-tab-custom").css("pointer-events","auto");
	$("#md-tab-custom").css("opacity","1");
	$("#md-tab-custom").addClass("changed");	
	$("#md-tab-filters").addClass("active");
	$("#md-page-custom").css("display","none");
	$("#md-page-filters").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });

    $(".filter-prev-btn").click(function(){
	$("#md-tab-filters").removeClass("active");
	$("#md-tab-filters").css("pointer-events","auto");
	$("#md-tab-filters").css("opacity","1");
	$("#md-tab-filters").addClass("changed");
	$("#md-tab-events").addClass("active");
	$("#md-page-filters").css("display","none");
	$("#md-page-events").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });

    $(".events-prev-btn").click(function(){
	$("#md-tab-events").removeClass("active");
	$("#md-tab-events").css("pointer-events","auto");
	$("#md-tab-events").css("opacity","1");
	$("#md-tab-events").addClass("changed");	
	$("#md-tab-related-lists").addClass("active");
	$("#md-page-events").css("display","none");
	$("#md-page-related-lists").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });

    $(".related-prev-btn").click(function(){
	$("#md-tab-related-lists").removeClass("active");
	$("#md-tab-related-lists").css("pointer-events","auto");
	$("#md-tab-related-lists").css("opacity","1");
	$("#md-tab-related-lists").addClass("changed");
	$("#md-tab-custom-links").addClass("active");
	$("#md-page-related-lists").css("display","none");
	$("#md-page-custom-links").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });

    $(".custom-prev-btn").click(function(){
	$("#md-tab-custom-links").removeClass("active");
	$("#md-tab-custom-links").css("pointer-events","auto");
	$("#md-tab-custom-links").css("opacity","1");
	$("#md-tab-custom-links").addClass("changed");
	$("#md-tab-blocks-fields").addClass("active");
	$("#md-page-custom-links").css("display","none");
	$("#md-page-blocks-fields").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });

    $(".blk-prev-btn").click(function(){
	$("#md-tab-blocks-fields").removeClass("active");
	$("#md-tab-blocks-fields").css("pointer-events","auto");
	$("#md-tab-blocks-fields").css("opacity","1");
	$("#md-tab-blocks-fields").addClass("changed");
	$("#md-tab-general").addClass("active");
	$("#md-page-blocks-fields").css("display","none");
	$("#md-page-general").css("display","block");
	$(".tab-pane").css("visibility","visible");
    });	
});

</script>
{/literal}
