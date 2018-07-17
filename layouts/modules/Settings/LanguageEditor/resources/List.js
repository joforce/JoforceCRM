/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/
jQuery.Class('Settings_LanguageEditor_Js', {}, {

        getContainer : function() {
                return jQuery('#listViewContent');
        },

	getModuleName: function () {
                return 'LanguageEditor';
        },

	/**
         * Function to register the change event for layout editor modules list
         */
        registerModulesChangeEvent: function (container) {
                var thisInstance = this;
                var contentsDiv = $("#languageEditorDiv");

                vtUtils.showSelect2ElementView(container.find('[name="lanugageEditorModules"]'));
		vtUtils.showSelect2ElementView(container.find('[name="lanugageEditorLanguages"]'));

                container.on('change', '[name="lanugageEditorModules"], [name="lanugageEditorLanguages"]', function (e) {
                        var currentTarget = jQuery(e.currentTarget);
                        var selectedModule = $("#lanugageEditorModules").val();
			var selectedLanugage = $("#lanugageEditorLanguages").val();
		
                        if (selectedModule == '' || selectedLanugage == '') {
				contentsDiv.html('');
                                return false;
                        }
			
                        thisInstance.getModuleRelatedLanguageEditor(selectedModule, selectedLanugage).then(
                                function (data) {
                                        contentsDiv.html(data);
					thisInstance.registerHoveringEditAction(container);
			                thisInstance.registerEditAction(container);
			                thisInstance.registerCancelAction(container);
                                }
                        );
                });
        },

	/**
         * Function to get the respective module layout editor through pjax
         */
        getModuleRelatedLanguageEditor: function (selectedModule, selectedLanugage) {
                var thisInstance = this;
                var aDeferred = jQuery.Deferred();
                app.helper.showProgress();

                var params = {};
                params['module'] = thisInstance.getModuleName();
                params['parent'] = app.getParentModuleName();
                params['view'] = 'ListAjax';
                params['moduleName'] = selectedModule;
		params['languageFolder'] = selectedLanugage;

                app.request.get({'data': params}).then(
                        function (err, data) {
                                app.helper.hideProgress();
                                if (err === null) {
                                        aDeferred.resolve(data);
                                } else {
                                        aDeferred.reject();
                                }
                        });
                return aDeferred.promise();
        },

	/**
	 *  Function to show edit option while hovering
	 **/
	registerHoveringEditAction: function(container) {
                container.on({
			mouseenter: function() {
                        if(!$(this).hasClass("active-editable"))
                        {
                                $(this).children('span').show();
                                $($(this).children('.editor')).click( function () {
                                        label = $(this).data('label');console.log(label);
                                        $(this).prev('input').removeAttr('readonly');
                                        $(this).closest('td').addClass('active-editable');
                                        $(this).next('div').show();
                                        $(this).hide();
                                });
                        }
                }, mouseleave: function() {
                        $(this).children('span').hide();
                }
		}, '.meaning');
        },

	/**
	 *  Function to save the updated value
	 **/	
	registerEditAction : function (container) {
		container.on('click', '.save-edit', function (e) {
			var value = $(this).closest('div').prevAll('input').val();
			var label = $(this).data('label');
			var resource = $(this).data('hint');
			var file_path = $("#file-path").val();
			var thisInstance = this;
                        	var element = jQuery(e.currentTarget);
	                        var params = {
        	                        module: app.getModuleName(),
                	                parent: app.getParentModuleName(),
                        	        action: 'SaveFile',
					file_path: file_path,
					value: value,
					label: label,
					resource: resource,
        	                }
                	        app.helper.showProgress();
                        	app.request.post({data: params}).then(function(err, data){
                                	app.helper.hideProgress();
	                });
		$(this).closest('td').removeClass('active-editable');
		$(this).closest('div').prevAll('input').attr('readonly', true);
		$(this).closest('div').hide();
		});
	},
	
	/**
	 * Function to cancel the edit and add action
	 **/
	registerCancelAction : function (container) {
		container.on('click', ".close-edit", function () {
			if(!$(this).closest('tr').hasClass('dummy-row'))
			{
				$(this).closest('div').prevAll('input').attr('readonly', true);
				$(this).closest('div').hide();
				$(this).closest('td').removeClass('active-editable');
			}
			else
			{
				$(this).closest('tr').remove();
			}
		});
	},

	/**
	 *  Function to show the modal for adding new language
	 **/
	registerAddNewLanguageAction : function(container) {
		var thisInstance = this;
		$("#add-language").click( function() {
			params = {
				module: app.getModuleName(),
                                parent: app.getParentModuleName(),
                                view: 'CreateLanguage',
			}
			app.helper.showProgress();
			app.request.get({data: params}).then(function(err, data){
				app.helper.hideProgress();
                                app.helper.showModal(data, {cb: function(data){
                                	thisInstance.registerAddLanguageSaveEvents(data);
                                }});
                        });
		});
	},

	/**
	 *  Function to save the new language
	 **/
	registerAddLanguageSaveEvents : function (data) {
		var self = this;
                var container = data.find('#add-language-modal');

                container.find('[type="submit"]').on('click', function(e) {
		
			var language_name = $("#language-name").val();console.log(language_name);
        	        var language_code = $("#language-code").val();
                	var language_to_copy = $("#language-to-copy").val();

			if(language_name.length && language_code.length && language_to_copy.length) {
				container.find('[type="submit"]').attr('disabled', true);
	                        var params = {
        	                        module: app.getModuleName(),
                                        parent: app.getParentModuleName(),
                                        language_name: language_name,
                                        language_code: language_code ,
					language_to_copy: language_to_copy,
                                        action: 'SaveLanguage',
                                };
                                app.helper.showProgress();
                                app.request.post({data: params}).then(function(err, data) {
                	                app.helper.showSuccessNotification({message: app.vtranslate('JS_LANGUAGE_ADD_SUCCESS')});
                                        app.helper.hideProgress();
                                        window.location.reload();
                                });
                                app.helper.hideModal();
                        }
			else {
                                app.helper.showAlertNotification({
                                        'message' : app.vtranslate('JS_PLEASE_GIVE_VALUES_IN_ALL_FIELDS')
                                });
                        }
                });
	},

	/**
	 *  Function to add new label and save new label
	 **/
	registerAddNewLabel : function (container) {
		container.on('click', '#add-new-label, #add-new-js-label', function() {
			var resource = $(this).data('hint');
			var file_path = $("#file-path").val();
			$tr = $("#dummy").clone();    
			$tr.insertAfter($(this).closest("tr")).show();
			$(".save-add-edit").click( function () {
				var meaning = $(this).closest('div').prevAll('input').val();
				var label = $(this).closest('td').prev('td').children('input').val();
				if(!meaning.length || !label.length)
				{
					app.helper.showAlertNotification({
	                                        'message' : app.vtranslate('JS_PLEASE_GIVE_VALUES_IN_BOTH')
	                                });
				}
				else
				{
					var params = {
                	                        module: app.getModuleName(),
                        	                parent: app.getParentModuleName(),
                                	        action: 'SaveFile',
                                        	file_path: file_path,
	                                        value: meaning,
        	                                label: label,
                	                        resource: resource,
        	        	                }
                                	app.helper.showProgress();
					var new_tr = $('<tr><td>'+label+'</td><td name="'+label+'" class="'+label+' meaning"><input type="text" value="'+ meaning +'" class="'+ label +'" readonly /><span data-label="'+label+'" class="fa fa-pencil editor" style="display:none;"></span><div style="display:none;"><span class="fa fa-check save-edit" data-label="'+label+'" data-hint="'+resource+'"></span><span class="fa fa-close close-edit" ></span></div></td></tr>');
                                        $(this).closest('tr').replaceWith(new_tr);
	                                app.request.post({data: params}).then(function(err, data){
        	                                app.helper.hideProgress();
	        	                });
				}
			});
		});
	},

	/**
	 * Function to register language editor related events
	 **/
	registerEvents : function() {
                var container = this.getContainer();
                this.registerModulesChangeEvent(container);
		this.registerHoveringEditAction(container);
		this.registerEditAction(container);
		this.registerCancelAction(container);
		this.registerAddNewLanguageAction(container);
		this.registerAddNewLabel(container);
	}
});

window.onload = function() {
        var settingsLanguageEditorInstance = new Settings_LanguageEditor_Js();
        settingsLanguageEditorInstance.registerEvents();
};
