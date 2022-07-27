/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_Detail_Js("Contacts_Detail_Js", {}, {
	registerAjaxPreSaveEvents: function (container) {
		var thisInstance = this;
		app.event.on(Head_Detail_Js.PreAjaxSaveEvent, function (e) {
			if (!thisInstance.checkForPortalUser(container)) {
				e.preventDefault();
			}
		});
	},
	/**
	 * Function to check for Portal User
	 */
	checkForPortalUser: function (form) {
		var element = jQuery('[name="portal"]', form);
		var response = element.is(':checked');
		var primaryEmailField = jQuery('[name="email"]');
		var primaryEmailValue = primaryEmailField.val();
		if (response) {
			if (primaryEmailField.length == 0) {
				app.helper.showErrorNotification({message: app.vtranslate('JS_PRIMARY_EMAIL_FIELD_DOES_NOT_EXISTS')});
				return false;
			}
			if (primaryEmailValue == "") {
				app.helper.showErrorNotification({message: app.vtranslate('JS_PLEASE_ENTER_PRIMARY_EMAIL_VALUE_TO_ENABLE_PORTAL_USER')});
				return false;
			}
		}
		return true;
	},

	/**
	 * Function to open modal to convert a contact to user
	 */
	registerShowAddUserModal : function() {
		var thisInstance = this;
		$('#convert-masquerade-user').click( function() {
			var record_id = $(this).data('recordid');
			var params = {};
			params['module'] = 'Users';
			params['view'] = 'AddMasqueradeUser'
			params['record_id'] = record_id;
			params['related_module'] = app.getModuleName();
		
			app.request.post({data: params}).then(function(err, data){
                                app.helper.hideProgress();
                                app.helper.showModal(data, {cb: function(data){
                                        thisInstance.registerSaveUser(data);
					vtUtils.registerEventForDateFields($('.masquerade-modal-form').find('.dateField'));
                                }});
                        });
		});
	},
	
	/**
	 * Function to save the user
	 **/
	registerSaveUser : function (formdata) {
		var thisInstance = this;
		var form = $(".masquerade-modal-form");
                $("#save-masquerade-user").click(function (e) {
			var user_name = $('input[name="user_name"]').val();
			var first_name = $('input[name="first_name"]').val();
			var last_name = $('input[name="last_name"]').val();
			var email = $('input[name="email1"]').val();
			if(!user_name.length || !first_name.length || !last_name.length || !email.length) {
				app.helper.showAlertNotification({
                                        'message' : app.vtranslate('JS_MANDATORY_FIELDS_ARE_EMPTY')
                                });
			}
			else {
				form.submit();
			}
                });
	},

        /**
         * Function to save the user
         **/
	deleteMasqueradeUser : function() {
		$('#remove-masquerade-user').live('click', function() {
			conf_msg = 'Are you sure want to delete this user?';

			app.helper.showConfirmationBox({'message': conf_msg}).then(function () {
				var params = {
					module: 'Contacts',
					action: 'DeleteMasqueradeUser',
					record_id: jQuery('#recordId').val()
				};
				app.helper.showProgress();
				app.request.post({ data: params }).then(function(err, data) {
					app.helper.hideProgress();
					window.location.reload();
				});
				return true;
			});
		});
	},

	/**
	 * Function which will register all the events
	 */
	registerEvents: function () {
		var form = this.getForm();
		this._super();
		this.registerAjaxPreSaveEvents(form);
		this.registerShowAddUserModal();
		this.deleteMasqueradeUser();
	}
})
