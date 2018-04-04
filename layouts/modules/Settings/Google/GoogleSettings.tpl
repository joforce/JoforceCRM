<div class="listViewPageDiv detailViewContainer col-sm-12 joforce-bg" id="listViewContent">
        <div class="col-sm-12">
               	<div class="row">
                       	<div class=" vt-default-callout vt-info-callout">
                               	<h4 class="vt-callout-header"><span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE_NAME)}</h4>
	                        <p>{vtranslate('LBL_GOOGLE_SETTINGS_INFO', 'Head')}</p>
                        </div>
               	</div>
	</div>
        <br>

	<div class = "settings" style="margin-left:10%;">
		<div class="col-md-offset-2 col-md-6 mt50">
			<form id="google-contact-settings-form" class="settings-form" action="{$SITEURL}Google/Settings/SaveGoogleSettings/{$BLOCKID}/{$FIELDID}" name="google-settings-form">
				{if $RESULT}
			        <div id = 'notificationArea' class = "alert alert-success notificationArea"> {vtranslate('Saved Successfully', 'Head')} <i class="fa fa-times-circle fa-2x pull-right" style = 'cursor:pointer;' onclick = "clearNotificationArea('notificationArea');"></i> </div>
				{/if}
				<div class="form-group">
					<label class="client-id-header" id="client-id-header">Client ID</label>
					<input type="text" value="{$CLIENT_ID}" name="client-id" class="client-id inputElement" id="client-id">
				</div>
				<div class="form-group">
					<label class="client-secret-header" id="client-secret-header">Client Secret</label>
					<input type="text" value="{$CLIENT_SECRET}" name="client-secret" class="client-secret inputElement" id="client-secret">
				</div>
				<input type="submit" class="btn btn-primary pull-right mt20" value="{vtranslate('LBL_SAVE', 'Head')}">
			</form>
		</div>
	</div>
	
	<div class="col-sm-12">
		<b><h4>Google Calendar Configuration Steps</h4></b>
		<p>1. Go to Google Developer Console</p>
		<p>2. Select "Create a new project" and click Continue</p>
		<p>3. Click "Go to credentials"</p>
		<p>4. Now select "Google Calendar API" for the queston - "Which API are you using?". Choose "Web browser (Javascript)" for the question - "Where will you be calling the API from?". Select "User data" for the question - "What data will you be accessing?". Then click on "What credentials do i need?"</p>
		<p>5. Provide a name to create OAuth 2.0 Client Id. Enter you domain name in "Authorized JavaScript origins". Enter your redirect URL (https://demo.smackcoders.com/vtiger/modules/GoogleCalSync/GoogleCalendarAuth.php) - "Authorized redirect URIs". Then click on "Create client ID"</p>
		<p>6. Then provide the email and Product name which will be shown to the users. You can also provide your logo and url by clicking on the customization options. Then click "Continue"</p>
		<p>7. Then Client ID will be shown. Copy and paste the Client Id in vtiger module app settings Client Id. Then click "Done"</p>
		<p>8. Now click on the "Create credentials" > API Key > Server Key". Provide a name and click on "Create". You will get the API key. Use this as developer key in the Vtiger Google App Settings.</p>
		<p>9. Click on the "OAuth 2.0 client IDs" > Name given to create OAuth Client Id in step 5. You will see the Client ID and Client secret. Copy the Client secret and use it.</p>
		<p>10. Go to Google Extension and click "Sign in with Google". Now user will be redirected to the google to get the authorisation. After that user can able to sync the calendar.</p>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		if (document.querySelector('.settingsNav') !== null) {
            $('.main-container .content-area').css('padding-left','240px');
        }
	});
    function clearNotificationArea(id) {
        jQuery('#' + id).html('');
        jQuery('#' + id).removeClass();
    }

</script>
<style>
.notificationArea   {
    text-align: center;
    display: inline-block;
}
</style>
