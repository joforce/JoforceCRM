{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): JoForce.com
************************************************************************************}
{* modules/Settings/MenuManager/views/Index.php *}

{* START YOUR IMPLEMENTATION FROM BELOW. Use {debug} for information *}
<div class="listViewPageDiv detailViewContainer col-sm-12 joforce-bg" id="listViewContent">
	<div class ="add_section modal-dialog" id="add-section-modalbody" style="width: 600px;margin: 30px auto;position: relative;">
	</div>

        <div class="col-sm-12">
                <div class="row">
                        <div class=" vt-default-callout vt-info-callout">
                                <h4 class="vt-callout-header"><span class="fa fa-info-circle"></span>{vtranslate('LBL_INFO', $QUALIFIED_MODULE_NAME)}</h4>
                                <p>{vtranslate('LBL_NOTIFICATION_SETTINGS_INFO', $QUALIFIED_MODULE_NAME)}</p>
                        </div>
                </div>
        </div>

	<div class="notification-enable">
		<input type="checkbox" id="global-notification-box" name="global-notification-box" {if $GLOBAL_SETTINGS} checked{/if} />
		<input type="hidden" name="global-notification" id="global-notification" value="{if $GLOBAL_SETTINGS} enabled {else} disabled {/if}" />
		<label for="global-notification-box">Enable Notifications</label>
	</div>

	<form type="POST" id="notification-editor-form" class="form-horizontal">
		<div id="notification-editor-div" {if !$GLOBAL_SETTINGS} style="display:none;" {/if} >
			{foreach from=$PERMITTED_MODULES key=PERMITTED_MODULE item=PERMITTED_MODULE_SETTINGS}
				<div class="notification-editor-module">
					<span class="accordion-module"><b>{vtranslate($PERMITTED_MODULE, $PERMITTED_MODULE)}</b>
					<span class="toggle-icon fa fa-caret-down" style="display: block;"></span></span>
					<div class="accordion-panel">
						<div class="notification-message">
							<input type="checkbox" name="{$PERMITTED_MODULE}_assigned" class="check-box" 
								{if $PERMITTED_MODULE_SETTINGS['assigned'] == 'true'} checked {/if} />
							<label>Record is assigned to you</label>
						</div>
						<div class="notification-message">
							<input type="checkbox" name="{$PERMITTED_MODULE}_following" class="check-box" 
								{if $PERMITTED_MODULE_SETTINGS['following'] == 'true'} checked {/if} />
							<label>Updates on following record</label>
						</div>
					</div>					
				</div>
			{/foreach}
		</div>

		<div class='modal-overlay-footer clearfix'>
                        <div class=" row clearfix">
       	                        <div class=' textAlignCenter col-lg-12 col-md-12 col-sm-12 '>
               	                        <button type='submit' class='btn btn-primary saveButton'>{vtranslate('LBL_SAVE', $MODULE)}</button>&nbsp;&nbsp;
                                </div>
                        </div>
                </div>
	</form>
</div>
