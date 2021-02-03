<form class="form form-horizontal" id="updateCompanyDetailsForm" method="post" action="index.php" enctype="multipart/form-data" style="display: inline-block;">
    <input type="hidden" name="module" value="Head" />
    <input type="hidden" name="parent" value="Settings" />
    <input type="hidden" name="action" value="CompanyDetailsSave" />
    <div class="form-group companydetailsedit" style="margin-top: 3%;">
        <label class="col-sm-4 fieldLabel control-label"> {vtranslate('LBL_COMPANY_LOGO',$QUALIFIED_MODULE)}</label>
        <div class="fieldValue col-sm-8 "  >
            <div class="company-logo-content">
                <img src="{$COMPANY_DETAILS_MODULE_MODEL->getLogoPath()}" class="alignMiddle company_logo" style="max-width:200px; max-height: 100px;"/>
                <br><hr>
                <input type="file" name="logo" id="logoFile" />
            </div>
            <br>
            <div class="alert alert-info" >
                {vtranslate('LBL_LOGO_RECOMMENDED_MESSAGE',$QUALIFIED_MODULE)}
            </div>
        </div>
    </div>
     <table class="table editview-table no-border">
        <tbody>
        {foreach from=$COMPANY_DETAILS_MODULE_MODEL->getFields() item=FIELD_TYPE key=FIELD}
            {if $FIELD neq 'logoname' && $FIELD neq 'logo' && $FIELD neq 'siteicon' && $FIELD neq 'brandinglogo' }
               <tr>
                    <td class=" fieldLabel"style="text-align: left;width: 40%;" ><label>{vtranslate($FIELD,$QUALIFIED_MODULE)}{if $FIELD eq 'organizationname'}&nbsp;<span class=""></span>{/if}</label></td>

                    <td class=" fieldValue" >            
                        <div class="fieldValue col-sm-12">
                            {if $FIELD eq 'address'}
                                <textarea class="form-control inputElement resize-vertical" rows="2" name="{$FIELD}">{$COMPANY_DETAILS_MODULE_MODEL->get($FIELD)}</textarea>
                            {else if $FIELD eq 'website'}
                                <input type="text" class="inputElement" data-rule-url="true" name="{$FIELD}" value="{$COMPANY_DETAILS_MODULE_MODEL->get($FIELD)}"/>
                            {else}
                                <input type="text" {if $FIELD eq 'organizationname'} data-rule-required="true" {/if} class="inputElement" name="{$FIELD}" value="{$COMPANY_DETAILS_MODULE_MODEL->get($FIELD)}"/>
                            {/if}
                        </div>
                    </td>
                </tr> 
            {/if} 
        {/foreach}
        </tbody>
    </table>
    {if $ENABLECHECKBOX eq 1}
        <div class="text-left">
            <input type="checkbox" id="enablepercentagecompletion" name="enablepercentagecompletion" value="1" {if $ENABLEPERCENTAGECOMPLETION eq 1}checked{/if}>
            <label for="enablepercentagecompletion" style="margin-left: 2%;"> Show percentage of Completion </label>
        </div>
    {/if}
</form>
