<form class="form form-horizontal" id="Brandinglogo" method="post" action="index.php" enctype="multipart/form-data" style="display: inline-block;width: 100%;">
    <input type="hidden" name="module" value="Head" />
    <input type="hidden" name="parent" value="Settings" />
    <input type="hidden" name="action" value="BrandinglogoSave" />

    <div class="form-group companydetailsedit" style="margin-top: 3%;">
         <center><label class="col-sm-4 fieldLabel col-form-label"> {vtranslate('LBL_BRANDING_LOGO',$QUALIFIED_MODULE)}</label> </center>
        <div class="fieldValue col-sm-8 "  >
            <div class="company-logo-content">
                <img src="{$COMPANY_DETAILS_MODULE_MODEL->getBrandLogoPath('brandinglogo')}" class="alignMiddle company_logo" style="max-width:200px; max-height: 100px;"/>
                <br><hr>
                <input type="file" name="logo" id="logoFile" />
            </div>
            <br>
            <div class="alert alert-info" >
                {vtranslate('LBL_LOGO_RECOMMENDED_MESSAGE',$QUALIFIED_MODULE)}
            </div>
        </div>
        <center> <label class="col-sm-4 fieldLabel col-form-label"> {vtranslate('LBL_SITE_LOGO',$QUALIFIED_MODULE)}</label> </center>
        <div class="fieldValue col-sm-8 ">
            <div class="company-logo-content" >
                <img src="{$COMPANY_DETAILS_MODULE_MODEL->getBrandLogoPath('siteicon')}" class="alignMiddle company_logo" style="max-width:200px; max-height: 100px;"/>
                <br><hr>
                <input type="file" name="siteicon" id="siteicon" />
            </div>
            <br>
            <div class="alert alert-info" >
                {vtranslate('LBL_SITELOGO_RECOMMENDED_MESSAGE',$QUALIFIED_MODULE)}
            </div>
        </div>
    </div>    
</form>
