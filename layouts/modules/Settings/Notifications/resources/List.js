/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/
jQuery.Class('Settings_Notifications_Js', {}, {
    getContainer : function() {
	return jQuery('#listViewContent');
    },

    getModuleName: function () {
	return 'Notifications';
    },

    /**
     * Function to click save button
     **/
     registerClickSaveEvent: function (container) {
	var thisInstance = this;
	var form = jQuery('#notification-editor-form');
	
	form.on('submit', function (e) {
	    e.preventDefault();
	    var data = form.serializeArray();
	    thisInstance.registerSaveEvent(data);
	    window.onbeforeunload = null; //To prevent chrome and firefox alert
            return false;
	});
    },
	
    /**
     * Function to save notification settings
     **/
    registerSaveEvent: function (data) {
	var aDeferred = jQuery.Deferred();
	var updatedFields = {};
	var global_settings = $('#global-notification').data('value');

	jQuery.each(data, function (key, value) {
	    updatedFields[key] = value;
	});
	var params = {
                        'module': app.getModuleName(),
                        'parent': app.getParentModuleName(),
                        'action': 'SaveSettings',
                        'updatedFields': JSON.stringify(updatedFields),
			'global_settings' : global_settings,
	};

	app.request.post({"data": params}).then(function (err, data) {
	    if (err === null) {
		is_saved = data.saved;
		response_msg = data.message;
		if(is_saved == 'true') {
		    app.helper.showSuccessNotification({message: app.vtranslate(response_msg)});
		    aDeferred.resolve(data);
		} else {
		    app.helper.showAlertNotification({message: app.vtranslate(response_msg)});
		    aDeferred.reject();
		}
            } else {
            	aDeferred.reject();
	    }
        });
	return aDeferred.promise();
    },

    /**
     * Function to register Accordion Action
     **/
    registerAccordionAction : function() {
	var acc = document.getElementsByClassName("accordion-module");
	var i;

	for (i = 0; i < acc.length; i++) {
	    acc[i].addEventListener("click", function() {
	        this.classList.toggle("active-accordion");
	        $(this).siblings('.accordion-panel').slideToggle();
	        $(this).children('.toggle-icon').toggleClass('fa-caret-up').toggleClass('fa-caret-down');
	    });
	}
    },

    /**
     * Function to register global notification settings events
     **/
    registerGlobalSettings : function(container) {
	container.on('change', '#global-notification', function() {
	    if($('#global-notification').is(':checked')) {
		$('#global-notification').attr('data-value','enabled');
		$('#notification-editor-div').show();
	    } else {
		$('#global-notification').attr('data-value', 'disabled');
		$('#notification-editor-div').hide();
	    }
	});
    },
		
    /**
     * Function to register notifications related events
     **/
    registerEvents : function() {
	var container = this.getContainer();
	this.registerClickSaveEvent(container);
	this.registerGlobalSettings(container);
	this.registerAccordionAction();
	$('[data-toggle="tooltip"]').tooltip();
    }
});

window.onload = function() {
    var settingsNotificationsInstance = new Settings_Notifications_Js();
    settingsNotificationsInstance.registerEvents();
};
