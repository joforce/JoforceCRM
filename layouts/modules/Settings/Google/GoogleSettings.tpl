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
	
	<div class="col-sm-12" style="margin-bottom:2%;">
		<b><h4>Google Calendar Configuration Steps</h4></b>
		<p>1. Go to <a href="https://console.developers.google.com/" target="_blank">Google Developer Console</a></p>
		<p>2. Select "Create a new project" and click Continue (or) select any one of the project from project list.</p>
		<p>3. Click on navigation menu in the top left and select Credentials under API & Services.</p>
		<p>4. Then click Create Credentials and select OAuth client ID.</p>
		<p>5. Select Web Application under Application type, Then you need to enter the required fields. Such as your domain in Authorized JavaScript origins and redirect url (http://www.yourcrm.com?index.php?module=Google&view=List&operation=sync&sourcemodule=Calendar&service=GoogleCalendar) in Authorized redirect URIs.</p>
		<p>6. Click on Create button. You will get the Client ID and Client Secret.</p>
		<p>7. Go to Google Extension and click "Sign in with Google". Now user will be redirected to the google to get the authorisation. After that user can able to sync the calendar.</p>
	</div>
