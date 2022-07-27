<form class="form form-horizontal" id="loginlogo" method="post" action="index.php" enctype="multipart/form-data" style="display: inline-block;width: 100%;margin-left: 30%;">
    <input type="hidden" name="module" value="Head" />
    <input type="hidden" name="parent" value="Settings" />
    <input type="hidden" name="action" value="LoginlogoSave" />

    
    <div class="form-group companydetailsedit" style="margin-top: 3%;"> 
        <div class="fieldValue col-sm-8 "  >
            <div class="company-logo-content" style="margin-left: 12%;">
            
                 <img src="{$LOGINIMAGE->getLogoPath()}" class="alignMiddle company_logo" style="max-width:200px;"/>
                 <br><hr>
                <input type="file" name="logo" id="logoFile" />
            </div>
            <br>
            <div class="alert alert-info" >
                {vtranslate('LBL_LOGO_RECOMMENDED_MESSAGE',$QUALIFIED_MODULE)}
            </div>
        </div>
    </div>  
     
</form>
