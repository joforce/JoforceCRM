/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/
Head.Class('Head_Index_Js', {
	files: [],
	hideNC: true,

	getInstance: function () {
		return new Head_Index_Js();
	},

	registerWidgetsEvents: function () {
		var widgets = $('div.widgetContainer');
		widgets.on({
			'shown.bs.collapse': function (e) {
				var widgetContainer = jQuery(e.currentTarget);
				Head_Index_Js.loadWidgets(widgetContainer);
				var key = widgetContainer.attr('id');
				Head_Index_Js.cacheSet(key, 1);
			},
			'hidden.bs.collapse': function (e) {
				var widgetContainer = jQuery(e.currentTarget);
				var imageEle = widgetContainer.parent().find('.imageElement');
				var imagePath = imageEle.data('rightimage');
				imageEle.attr('src', imagePath);
				var key = widgetContainer.attr('id');
				Head_Index_Js.cacheSet(key, 0);
			}
		});
	},

	/**
	 * Function is used to load the sidebar widgets
	 * @param widgetContainer - widget container
	 * @param open - widget should be open or closed
	 */
	loadWidgets: function (widgetContainer, open) {
		var message = jQuery('.loadingWidgetMsg').html();
		if (widgetContainer.html() == '') {
			var imageEle = widgetContainer.parent().find('.imageElement');
			var imagePath = imageEle.data('downimage');
			imageEle.attr('src', imagePath);
			widgetContainer.css('height', 'auto');
			return;
		}

		widgetContainer.progressIndicator({
			'message': message
		});
		var url = widgetContainer.data('url');
		var listViewWidgetParams = {
			"type": "GET",
			"url": "index.php",
			"dataType": "html",
			"data": url
		}
		var postParams = app.convertUrlToDataParams(url);

		app.request.get({
			data: postParams,
			dataType: "html"
		}).then(
			function (err, data) {
				if (typeof open == 'undefined') open = true;
				if (open) {
					widgetContainer.progressIndicator({
						'mode': 'hide'
					});
					var imageEle = widgetContainer.parent().find('.imageElement');
					var imagePath = imageEle.data('downimage');
					imageEle.attr('src', imagePath);
					widgetContainer.css('height', 'auto');
				}
				widgetContainer.html(data);
				var label = widgetContainer.closest('.quickWidget').find('.quickWidgetHeader').data('label');
				jQuery('.bodyContents').trigger('Head.Widget.Load.' + label, jQuery(widgetContainer));
			}
		);
	},

	loadWidgetsOnLoad: function () {
		var widgets = jQuery('div.widgetContainer');
		widgets.each(function (index, element) {
			var widgetContainer = jQuery(element);
			var key = widgetContainer.attr('id');
			var value = Head_Index_Js.cacheGet(key);
			if (value != null) {
				if (value == 1) {
					Head_Index_Js.loadWidgets(widgetContainer);
					widgetContainer.addClass('in');
				} else {
					var imageEle = widgetContainer.parent().find('.imageElement');
					var imagePath = imageEle.data('rightimage');
					imageEle.attr('src', imagePath);
				}
			}

		});

	},

	cacheSet: function (key, value) {
		key = this.cacheNSKey(key);
		jQuery.jStorage.set(key, value);
	},

	cacheNSKey: function (key) { // Namespace in client-storage
		return 'vtiger6.' + key;
	},
	cacheGet: function (key, defvalue) {
		key = this.cacheNSKey(key);
		return jQuery.jStorage.get(key, defvalue);
	},

	/**
	 * Function to show the content of a file in an iframe
	 * @param {type} e
	 * @param {type} recordId
	 * @returns {undefined}
	 */
	previewFile: function (e, recordId, attachmentId) {
		e.stopPropagation();
		if (recordId) {
			var params = {
				module: 'ModComments',
				view: 'FilePreview',
				record: recordId,
				attachmentid: attachmentId
			};
			app.request.post({
				data: params
			}).then(function (err, res) {
				app.helper.showModal(res);
				jQuery('.filePreview .preview-area').height(jQuery(window).height() - 143);
			});
		}
	},

	/**
	 * Function to show email preview in popup
	 */
	showEmailPreview: function (recordId, parentId) {
		var popupInstance = Head_Popup_Js.getInstance();
		var params = {};
		params['module'] = "Emails";
		params['view'] = "ComposeEmail";
		params['mode'] = "emailPreview";
		params['record'] = recordId;
		params['parentId'] = parentId;
		params['relatedLoad'] = true;

		var callback = function (data) {
			emailPreviewClass = app.getModuleSpecificViewClass('EmailPreview', 'Head');
			_controller = new window[emailPreviewClass]();
			_controller.registerEventsForActionButtons();
			var descriptionContent = data.find('#iframeDescription').val();
			var frameElement = jQuery("#emailPreviewIframe")[0].contentWindow.document;
			frameElement.open();
			frameElement.close();
			jQuery('#emailPreviewIframe').contents().find('html').html(descriptionContent);
			jQuery("#emailPreviewIframe").height(jQuery('#emailPreviewIframe').contents().find('html').height());
			jQuery('#emailPreviewIframe').contents().find('html').find('a').on('click', function (e) {
				e.preventDefault();
				var url = jQuery(e.currentTarget).attr('href');
				window.open(url, '_blank');
			});
		}
		popupInstance.showPopup(params, null, callback);
	},

	/**
	 * Function to show compose email popup based on number of
	 * email fields in given module,if email fields are more than
	 * one given option for user to select email for whom mail should
	 * be sent,or else straight away open compose email popup
	 * @params : accepts params object
	 *
	 * @cb: callback function to recieve the child window reference.
	 */

	showComposeEmailPopup: function (params, cb) {
		var currentModule = "Emails";
		app.helper.showProgress();
		app.helper.checkServerConfig(currentModule).then(function (data) {
			if (data == true) {
				app.request.post({
					data: params
				}).then(function (err, data) {
					if (err === null) {
						data = jQuery(data);
						var form = data.find('#SendEmailFormStep1');
						var emailFields = form.find('.emailField');
						var length = emailFields.length;
						var emailEditInstance = new Emails_MassEdit_Js();

						var prefsNeedToUpdate = form.find('#prefsNeedToUpdate').val();
						if (prefsNeedToUpdate && length > 1) {
							app.helper.hideProgress();
							app.helper.showModal(data);
							emailEditInstance.registerEmailFieldSelectionEvent();
							return true;
						}

						if (length > 1) {
							var saveRecipientPref = form.find('#saveRecipientPrefs').is(':checked');
							if (saveRecipientPref) {
								var params = form.serializeFormData();
								emailEditInstance.showComposeEmailForm(params).then(function (response) {
									jQuery(document).on('shown.bs.modal', function () {
										if (typeof cb === 'function') cb(response);
									});
								});
							} else {
								app.helper.hideProgress();
								app.helper.showModal(data);
								emailEditInstance.registerEmailFieldSelectionEvent();
							}
						} else {
							emailFields.attr('checked', 'checked');
							var params = form.serialize();
							emailEditInstance.showComposeEmailForm(params).then(function (response) {
								jQuery(document).on('shown.bs.modal', function () {
									if (typeof cb === 'function') cb(response);
								});
							});
						}
					}
				});
			} else {
				app.helper.showAlertBox({
					'message': app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION')
				});
			}
		});
	},

	showRecipientPreferences: function (module) {
		var params = {
			module: module,
			view: "RecipientPreferences",
		};

		var callback = function (data) {
			var form = jQuery(data).find('#recipientsForm');
			if (form.find('#multiEmailContainer').height() > 300) {
				app.helper.showVerticalScroll(form.find('#multiEmailContainer'), {
					setHeight: '300px',
					autoHideScrollbar: false,
				});
			}

			form.on('submit', function (e) {
				e.preventDefault();
				form.find('.savePreference').attr('disabled', true);
				var params = form.serialize();
				app.helper.hideModal();
				app.helper.showProgress();
				app.request.post({
					"data": params
				}).then(function (err, data) {
					if (err == null) {
						app.helper.hideProgress();
						app.helper.showSuccessNotification({
							"message": ''
						});
					} else {
						app.helper.showErrorNotification({
							"message": ''
						});
					}
				});
			});
		}

		app.helper.showProgress();
		app.request.post({
			"data": params
		}).then(function (err, data) {
			if (err == null) {
				app.helper.hideProgress();
				app.helper.showModal(data, {
					"cb": callback
				});
			}
		});
	},

	/**
	 * Function to show record address in Google Map
	 * @param {type} e
	 * @returns {undefined}
	 */
	showMap: function (e) {
		var currentElement = jQuery(e);
		var params1 = {
			'module': 'Google',
			'action': 'MapAjax',
			'mode': 'getLocation',
			'recordid': currentElement.data('record'),
			'source_module': currentElement.data('module')
		};
		app.request.post({
			"data": params1
		}).then(function (error, response) {
			var result = JSON.parse(response);
			var address = result.address;
			var location = jQuery.trim((address).replace(/\,/g, " "));
			if (location == '' || location == null) {
				app.helper.showAlertNotification({
					message: app.vtranslate('Please add address information to view on map')
				});
				return false;
			} else {
				var params = {
					'module': 'Google',
					'view': 'Map',
					'mode': 'showMap',
					'viewtype': 'detail',
					'record': currentElement.data('record'),
					'source_module': currentElement.data('module')
				};
				var popupInstance = Head_Popup_Js.getInstance();
				popupInstance.showPopup(params, '', function (data) {
					var mapInstance = new Google_Map_Js();
					mapInstance.showMap(data);
				});
			}
		});
	},

	/**
	 * Function registers event for Calendar Reminder popups
	 */
	registerActivityReminder: function () {
		var activityReminderInterval = app.getActivityReminderInterval();
		if (activityReminderInterval != '') {
			var cacheActivityReminder = app.storage.get('activityReminder', 0);
			var currentTime = new Date().getTime() / 1000;
			var nextActivityReminderCheck = app.storage.get('nextActivityReminderCheckTime', 0);
			//If activity Reminder Changed, nextActivityReminderCheck should reset
			if (activityReminderInterval != cacheActivityReminder) {
				nextActivityReminderCheck = 0;
			}
			if (currentTime >= nextActivityReminderCheck) {
				Head_Index_Js.requestReminder();
			} else {
				var nextInterval = nextActivityReminderCheck - currentTime;
				setTimeout(function () {
					Head_Index_Js.requestReminder()
				}, nextInterval * 1000);
			}
		}
	},

	/**
	 * Function request for reminder popups
	 */
	requestReminder: function () {
		var activityReminder = app.getActivityReminderInterval();
		if (!activityReminder);
		return;
		var currentTime = new Date().getTime() / 1000;
		//requestReminder function should call after activityreminder popup interval
		setTimeout(function () {
			Head_Index_Js.requestReminder()
		}, activityReminder * 1000);
		app.storage.set('activityReminder', activityReminder);
		//setting next activity reminder check time
		app.storage.set('nextActivityReminderCheckTime', currentTime + parseInt(activityReminder));

		app.request.post({
			'data': {
				'module': 'Calendar',
				'action': 'ActivityReminder',
				'mode': 'getReminders'
			}
		}).then(function (e, res) {
			if (!res.hasOwnProperty('result')) {
				for (i = 0; i < res.length; i++) {
					var record = res[i];
					if (typeof record == 'object') {
						Head_Index_Js.showReminderPopup(record);
					}
				}
			}
		});
	},

	/**
	 * Function display the Reminder popup
	 */
	showReminderPopup: function (record) {
		var notifyParams = {
			'title': record.activitytype + ' - ' +
				'<a target="_blank" href="index.php?module=Calendar&view=Detail&record=' + record.id + '">' + record.subject + '</a>&nbsp;&nbsp;' +
				'<i id="reminder-postpone-' + record.id + '" title="' + app.vtranslate('JS_POSTPONE') + '" class="cursorPointer fa fa-clock-o"></i>',
			'message': '<div class="col-sm-12">' +
				'<div class="row">' +
				'<div class="col-sm-12 font13px">' +
				app.vtranslate('JS_START_DATE_TIME') + ' : ' + record.date_start +
				'</div>' +
				'<div class="col-sm-12 font13px">' +
				app.vtranslate('JS_END_DATE_TIME') + ' : ' + record.due_date +
				'</div>' +
				'</div>' +
				'</div>'
		};
		var settings = {
			'element': 'body',
			'type': 'danger',
			'delay': 0
		};

		jQuery.notify(notifyParams, settings);
		jQuery('#reminder-postpone-' + record.id).on('click', function (e) {
			jQuery(e.currentTarget).closest('.notificationHeader').find('[data-notify="dismiss"]').trigger('click');
			app.request.post({
				'data': {
					'module': 'Calendar',
					'action': 'ActivityReminder',
					'mode': 'postpone',
					'record': record.id
				}
			}).then(function (e, res) {});
		});
		jQuery('#reminder-postpone-' + record.id).closest('[data-notify="container"]').draggable({
			'containment': 'body'
		});
	}

}, {
	_SearchIntiatedEventName: 'VT_SEARCH_INTIATED',
	usernames: [],
	userList: {},
	autoFillElement: false,

	init: function () {
		this.addComponents();
	},

	addComponents: function () {
		this.addComponent('Head_BasicSearch_Js');
	},

	registerModuleQtips: function () {
		jQuery('.module-qtip').qtip({
			position: {
				my: 'left center',
				at: 'center right',
				adjust: {
					y: 1
				}
			},
			style: {
				classes: 'qtip-dark qtip-shadow module-name-tooltip'
			},
			show: {
				delay: 500
			}
		});
	},

	registerEvents: function () {
		Head_Index_Js.registerWidgetsEvents();
		Head_Index_Js.loadWidgetsOnLoad();
		this.registerMenuToggle();
		this.registerMenuToggleOnHover();
		this.registerGlobalSearch();
		this.registerGlobalSearchBtn();
		this.getModuleRelatedPicklist()
		this.registerAppTriggerEvent();
		this.registerModuleQtips();
		//		this.registerListEssentialsToggleEvent();
		this.registerAdvanceSeachIntiator();
		this.registerQuickCreateEvent();
		this.registerCloseQuickEvent();
		this.registerQuickCreateSubMenus();
		// this.registerPostQuickCreateEvent();
		this.registerEventForTaskManagement();
		this.registerEventForTaskManagementDue();
		this.registerFileChangeEvent();
		this.registerMultiUpload();
		this.registerHoverEventOnAttachment();
		this.mentionerCallBack();
		Head_Index_Js.registerActivityReminder();
		//reference preview event registeration
		this.registerReferencePreviewEvent();
	},

	registerEventForTaskManagement: function () {
		var globalNav = jQuery('.global-nav');
		globalNav.on("click", ".taskManagement", function (e) {
			if (jQuery("#taskManagementContainer").length > 0) {
				app.helper.hidePageOverlay();
				return false;
			}

			var params = {
				'module': 'Calendar',
				'view': 'TaskManagement',
				'mode': 'showManagementView'
			}
			app.helper.showProgress();
			app.request.post({
				"data": params
			}).then(function (err, data) {
				if (err === null) {
					app.helper.loadPageOverlay(data, {
						'ignoreScroll': true,
						'backdrop': 'static'
					}).then(function () {
						app.helper.hideProgress();
						$('#overlayPage').find('.data').css('height', '100vh');

						var taskManagementPageOffset = jQuery('.taskManagement').offset();
						$('#overlayPage').find(".arrow").css("left", taskManagementPageOffset.left + 13);
						$('#overlayPage').find(".arrow").addClass("show");

						vtUtils.showSelect2ElementView($('#overlayPage .data-header').find('select[name="assigned_user_id"]'), {
							placeholder: "User : All"
						});
						vtUtils.showSelect2ElementView($('#overlayPage .data-header').find('select[name="taskstatus"]'), {
							placeholder: "Status : All"
						});
						var js = new Head_TaskManagement_Js();
						js.registerEvents();
					});
				} else {
					app.helper.showErrorNotification({
						"message": err
					});
				}
			});
		});
	},

	registerEventForTaskManagementDue: function () {
		var globalNav = jQuery('.global-nav');
		globalNav.on("click", ".taskManagementDue", function (e) {
			if (jQuery("#taskManagementDueContainer").length > 0) {
				app.helper.hidePageOverlay();
				return false;
			}

			var params = {
				'module': 'Calendar',
				'view': 'TaskManagementByDueDate',
				'mode': 'showManagementView'
			}
			app.helper.showProgress();
			app.request.post({
				"data": params
			}).then(function (err, data) {
				if (err === null) {
					app.helper.loadPageOverlay(data, {
						'ignoreScroll': true,
						'backdrop': 'static'
					}).then(function () {
						app.helper.hideProgress();
						$('#overlayPage').find('.data').css('height', '100vh');

						var taskManagementPageOffset = jQuery('.taskManagementDue').offset();
						$('#overlayPage').find(".arrow").css("left", taskManagementPageOffset.left + 13);
						$('#overlayPage').find(".arrow").addClass("show");

						vtUtils.showSelect2ElementView($('#overlayPage .data-header').find('select[name="assigned_user_id"]'), {
							placeholder: "User : All"
						});
						vtUtils.showSelect2ElementView($('#overlayPage .data-header').find('select[name="taskstatus"]'), {
							placeholder: "Status : All"
						});
						//                                                var js = new Head_TaskManagementDue_Js();
						//                                                js.registerEvents();
					});
				} else {
					app.helper.showErrorNotification({
						"message": err
					});
				}
			});
		});
	},

	// registerPostQuickCreateEvent : function(){
	// 	var thisInstance = this;

	// 	app.event.on("post.QuickCreateForm.show",function(event,form){
	// 		$('#goToFullForm').on('click', function(e) {
	// 			window.onbeforeunload = true;
	// 			var form = jQuery(e.currentTarget).closest('form');
	// 			var editViewUrl = jQuery(e.currentTarget).data('editViewUrl');
	// 			if (typeof goToFullFormCallBack != "undefined") {
	// 				goToFullFormCallBack(form);
	// 			}
	// 			thisInstance.quickCreateGoToFullForm(form, editViewUrl);
	// 		});
	// 	});
	// },

	/**
	 * Function to navigate from quickcreate to editView Fullform
	 * @param accepts form element as parameter
	 */
	quickCreateGoToFullForm: function (form, editViewUrl) {
		var formData = form.serializeFormData();
		//As formData contains information about both view and action removed action and directed to view
		delete formData.module;
		delete formData.action;
		delete formData.picklistDependency;
		var formDataUrl = jQuery.param(formData);
		var completeUrl = editViewUrl;
		var parts = formDataUrl.split("&");
		var variables = formDataUrl.substring(formDataUrl.indexOf('&') + 1);
		window.location.href = completeUrl + '?' + parts[0] + '&' + variables;
	},

	registerQuickCreateSubMenus: function () {
		jQuery("#quickCreateModules").on("click", ".quickCreateModuleSubmenu", function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			jQuery(e.currentTarget).closest('.dropdown').toggleClass('open');
		});
	},

	/**
	 * Function to register Quick Create Event
	 * @returns {undefined}
	 */
	registerQuickCreateEvent: function () {
		var thisInstance = this;
		jQuery("#quickCreateModules").on("click", ".quickCreateModule", function (e, params) {
			var quickCreateElem = jQuery(e.currentTarget);
			var quickCreateUrl = quickCreateElem.data('url');
			var quickCreateModuleName = quickCreateElem.data('name');
			if (typeof params === 'undefined') {
				params = {};
			}
			if (typeof params.callbackFunction === 'undefined') {
				params.callbackFunction = function (data, err) {
					//fix for Refresh list view after Quick create
					var parentModule = app.getModuleName();
					var viewname = app.view();
					if ((quickCreateModuleName == parentModule) && (viewname == "List")) {
						var listinstance = app.controller();
						listinstance.loadListViewRecords();
					}
				};
			}
			app.helper.showProgress();
			thisInstance.getQuickCreateForm(quickCreateUrl, quickCreateModuleName, params).then(function (data) {
				app.helper.hideProgress();
				var callbackparams = {
					'cb': function (container) {
						thisInstance.registerPostReferenceEvent(container);
						app.event.trigger('post.QuickCreateForm.show', form);
						// app.helper.registerLeavePageWithoutSubmit(form);
						// app.helper.registerModalDismissWithoutSubmit(form);
					},
					backdrop: 'static',
					keyboard: false
				}

				app.helper.showModal(data, callbackparams);
				var form = jQuery('form[name="QuickCreate"]');
				var moduleName = form.find('[name="module"]').val();
				app.helper.showVerticalScroll(jQuery('form[name="QuickCreate"] .modal-body'), {
					'autoHideScrollbar': true
				});

				var targetInstance = thisInstance;
				var moduleInstance = Head_Edit_Js.getInstanceByModuleName(moduleName);
				if (typeof (moduleInstance.quickCreateSave) === 'function') {
					targetInstance = moduleInstance;
					targetInstance.registerBasicEvents(form);
				}

				vtUtils.applyFieldElementsView(form);
				targetInstance.quickCreateSave(form, params);
				app.helper.hideProgress();
			});
		});
	},

	registerCloseQuickEvent: function () {
		jQuery(".quick-panel").on("click", ".close", function () {
			$(".quick-panel").css("width:0%!important");
			if ($("#rightpanelhide").val() === "0") {
				$('#menu-toggle-action').click();
			}
			$(".main-container").removeClass('panel-width');
		});
		jQuery(".quick-panel").on("click", ".cancelLink", function () {
			$(".quick-panel").css("width:0%!important");
			if ($("#rightpanelhide").val() === "0") {
				$('#menu-toggle-action').click();
			}
			$(".main-container").removeClass('panel-width');
		});
		closeModal = function () {
			$(".quick-panel").css("width:0%!important");
			if ($("#rightpanelhide").val() === "0") {
				$('#menu-toggle-action').click();
			}
			$(".main-container").removeClass('panel-width');
		}
	},

	/**
	 * Function to register quick create tab events
	 */
	registerQuickcreateTabEvents: function (form) {
		var thisInstance = this;
		var tabElements = form.closest('.modal-content').find('.nav.nav-pills , .nav.nav-tabs').find('a');

		//This will remove the name attributes and assign it to data-element-name . We are doing this to avoid
		//Multiple element to send as in calendar
		var quickCreateTabOnHide = function (tabElement) {
			var container = jQuery(tabElement.attr('data-target'));

			container.find('[name]').each(function (index, element) {
				element = jQuery(element);
				element.attr('data-element-name', element.attr('name')).removeAttr('name');
			});
		};

		//This will add the name attributes and get value from data-element-name . We are doing this to avoid
		//Multiple element to send as in calendar
		var quickCreateTabOnShow = function (tabElement) {
			var container = jQuery(tabElement.attr('data-target'));

			container.find('[data-element-name]').each(function (index, element) {
				element = jQuery(element);
				element.attr('name', element.attr('data-element-name')).removeAttr('data-element-name');
			});
		};

		tabElements.on('shown.bs.tab', function (e) {
			var previousTab = jQuery(e.relatedTarget);
			var currentTab = jQuery(e.currentTarget);

			quickCreateTabOnHide(previousTab);
			quickCreateTabOnShow(currentTab);

			if (form.find('[name="module"]').val() === 'Calendar') {
				var sourceModule = currentTab.data('source-module');
				form.find('[name="calendarModule"]').val(sourceModule);
				var moduleInstance = Head_Edit_Js.getInstanceByModuleName('Calendar');
				moduleInstance.registerEventForPicklistDependencySetup(form);
			}

			//while switching tabs we have to show scroll bar
			//thisInstance.showQuickCreateScrollBar(form);
			//while switching tabs we have to clear the invalid fields list
			//form.data('jqv').InvalidFields = [];
		});

		//remove name attributes for inactive tab elements
		quickCreateTabOnHide(tabElements.closest('li').filter(':not(.active)').find('a'));
	},

	/**
	 * Register Quick Create Save Event
	 * @param {type} form
	 * @returns {undefined}
	 */
	quickCreateSave: function (form, invokeParams) {
		var params = {
			submitHandler: function (form) {
				// to Prevent submit if already submitted
				jQuery("button[name='saveButton']").attr("disabled", "disabled");
				if (this.numberOfInvalids() > 0) {
					return false;
				}
				var formData = jQuery(form).serialize();
				app.request.post({
					data: formData
				}).then(function (err, data) {
					app.event.trigger("post.QuickCreateForm.save", data, jQuery(form).serializeFormData());
					if (err === null) {
						app.helper.hideModal();
						app.helper.showSuccessNotification({
							"message": ''
						});
						invokeParams.callbackFunction(data, err);
						//To unregister onbefore unload event registered for quickcreate
						window.onbeforeunload = null;
					} else {
						app.helper.showErrorNotification({
							"message": err
						});
					}
					if (data.sourceModule === "Events") {
						location.reload();
					} else {
						closeModal();
					}
				});
			},
			validationMeta: quickcreate_uimeta
		};
		form.vtValidate(params);
	},

	/**
	 * Function to get Quick Create Form
	 * @param {type} url
	 * @param {type} moduleName
	 * @returns {unresolved}
	 */
	getQuickCreateForm: function (url, moduleName, params) {
		var aDeferred = jQuery.Deferred();
		var requestParams = app.convertUrlToDataParams(url);
		jQuery.extend(requestParams, params.data);
		app.request.post({
			data: requestParams
		}).then(function (err, data) {
			aDeferred.resolve(data);
		});
		return aDeferred.promise();
	},

	registerMenuToggle: function () {
		var thisInstance = this;
		jQuery("#menu-toggle-action").on('click', function (e) {
			e.preventDefault();
			lph_value = $('#leftpanelhide').val(); //leftpanelhide value.
			if (lph_value == 1) {
				$('#joforce-advanced-search').removeClass('search-open-1')
				$('#joforce-advanced-search').addClass('search-open-0')
				thisInstance.removeEssentialsClasses(0);
				showpanel = 0;
			} else {
				$('#joforce-advanced-search').removeClass('search-open-0')
				$('#joforce-advanced-search').addClass('search-open-1')
				thisInstance.addEssentialClasses(0);
				showpanel = 1;
			}
			$('#leftpanelhide').val(showpanel);
			var params = {
				'module': 'Users',
				'action': 'IndexAjax',
				'mode': 'toggleLeftPanel',
				'showPanel': showpanel
			}
			app.request.post({
				data: params
			});
			//			app.event.trigger("Head.Post.MenuToggle");
		});

		$('#responsive-menu-toggle-action').on('click', function () {
			lph_value = $('#leftpanelhide').val(); //leftpanelhide value.
			if ($('#sidebar-essentials').hasClass('shrinked-sidebar')) {
				$('#sidebar-essentials').show();
				thisInstance.removeEssentialsClasses(1);
				showpanel = 0;
			} else {
				$('#sidebar-essentials').hide();
				thisInstance.addEssentialClasses(1);
				showpanel = 1;
			}
		});
	},

	removeEssentialsClasses: function (hover) {
		$('.sidebar-essentials').removeClass('shrinked-sidebar');
		$('#sidebar-essentials .module-name').removeClass('hide');

		$('#sidebar-essentials .menu-name').removeClass('hide');
		$('#sidebar-essentials .dropdown-icon').removeClass('hide'); //settings more menu icon
		jQuery('#sidebar-more-menu-list .content').removeClass('active');

		if (hover == 0) {
			jQuery('#menu-toggle-action').removeClass('fa-align-justify').addClass('fa-align-left');

			jQuery('.module-header').removeClass('full-header'); //module header
			$('#topbar-elements').removeClass('full-topbar');
			$('.logo-container').removeClass('half-image');

			$(".main-container .content-area").removeClass("full-width");
			$(".logo-container.app-navigator-container").removeClass('logo-shrinked').addClass('logo-expand');
			$(".logo-container.app-navigator-container .expanded").removeClass('hide');
			$(".logo-container.app-navigator-container .shrinked").addClass('hide');
			$(".detail-view-header").addClass('detail-view-header-shrinked');
		}
	},

	addEssentialClasses: function (hover) {
		$('.sidebar-essentials').addClass('shrinked-sidebar');
		$('#sidebar-essentials .module-name').addClass('hide');

		$('#sidebar-essentials .menu-name').addClass('hide'); //settings more menu name
		$('#sidebar-essentials .dropdown-icon').addClass('hide'); //settings more menu icon
		jQuery('#sidebar-more-menu-list .content').removeClass('active'); //close opened dropdown

		if (hover == 0) {
			jQuery('#menu-toggle-action').removeClass('fa-align-left').addClass('fa-align-justify');

			jQuery('.module-header').addClass('full-header'); //module header
			$('#topbar-elements').addClass('full-topbar');
			$('.logo-container').addClass('half-image');

			$(".main-container .content-area").addClass("full-width");

			$(".logo-container.app-navigator-container").removeClass('logo-expand').addClass('logo-shrinked');
			$(".logo-container.app-navigator-container .expanded").addClass('hide');
			$(".logo-container.app-navigator-container .shrinked").removeClass('hide');
			$(".detail-view-header").removeClass('detail-view-header-shrinked');
		}
	},

	registerMenuToggleOnHover: function () {
		var thisInstance = this;
		$('#sidebar-essentials').on("mouseenter", function () {
			lph_value = $('#leftpanelhide').val(); //leftpanelhide value
			if (lph_value == 1) {
				thisInstance.removeEssentialsClasses(1);
			}
		}).on("mouseleave", function () {
			lph_value = $('#leftpanelhide').val(); //leftpanelhide value
			if (lph_value == 1) {
				thisInstance.addEssentialClasses(1);
			}
		});
	},

	registerAppTriggerEvent: function () {
		var toggleAppMenu = function (type) {
			var appMenu = jQuery('.app-menu');
			//var appNav = jQuery('.app-nav');
			//var app = appNav.offset();
			var nav = $('.app-nav');
			if (nav.length) {
				var contentNav = nav.offset().top;
				// ...continue to set up the menu
			}
			appMenu.appendTo('#page');
			appMenu.css({
				'top': contentNav + nav.height(),
				'left': 0
			});
			if (typeof type === 'undefined') {
				type = appMenu.is(':hidden') ? 'show' : 'hide';
			}
			if (type == 'show') {
				appMenu.show(200, function () {});
			} else {
				appMenu.hide(200, function () {});
			}
		};

		jQuery('.app-icon, .app-navigator').on('click', function (e) {
			e.stopPropagation();
			toggleAppMenu();
		});

		jQuery('html').on('click', function () {
			toggleAppMenu('hide');
		});

		jQuery(document).keyup(function (e) {
			if (e.keyCode == 27) {
				if (!jQuery('.app-menu').is(':hidden')) {
					toggleAppMenu('hide');
				}
			}
		});
	},

	registerGlobalSearchBtn: function () {
		var thisInstance = this;
		jQuery('#joforce-search-btn').on('click', function (e) {
			// if(e.which == 13) {
			var element = jQuery(e.currentTarget);
			var data = {};
			data['searchValue'] = $('#filterValue').val();
			data['searchCondition'] = $('#filterCondition').val();
			data['searchField'] = $('#filterField').val();
			data['searchModule'] = $('#searchValue').val();
			element.trigger(thisInstance._SearchIntiatedEventName, data);
			// }
		});
	},

	registerGlobalSearch: function () {
		var thisInstance = this;
		jQuery('#joforce-search-box').on('keypress', function (e) {
			if (e.which == 13) {
				var element = jQuery(e.currentTarget);
				var searchValue = element.val();
				var data = {};
				data['searchValue'] = searchValue;
				element.trigger(thisInstance._SearchIntiatedEventName, data);
				$(this).removeClass('fa-eye');
			}
		});

		jQuery('#joforce-select-search-box').on('click', function (e) {
			var selectedModule = jQuery(e.currentTarget).val();
			var fieldContentsDiv = $('#filterField')
			if (selectedModule == '') {
				fieldContentsDiv.html('');
				return false;
			}
			thisInstance.getModuleRelatedPicklist(selectedModule).then(function (data) {
				fieldContentsDiv.html(data.fields);
			});
			$('#searchValue').val(selectedModule);
		});
	},

	getModuleRelatedPicklist: function (selectedModule) {
		var thisInstance = this;
		var aDeferred = jQuery.Deferred();
		app.helper.showProgress();

		var params = {};
		params['module'] = selectedModule;
		params['parent'] = 'Head'
		params['view'] = 'ListAjax';
		params['mode'] = 'getpicklist',
			params['moduleName'] = selectedModule;
		app.request.get({
			'data': params
		}).then(function (err, data) {
			app.helper.hideProgress();
			if (err === null) {
				result = data;
				aDeferred.resolve(data);
			} else {
				aDeferred.reject();
			}
		});
		return aDeferred.promise();
	},

	registerAdvanceSeachIntiator: function () {
		jQuery('#adv-search').on('click', function (e) {
			var advanceSearchInstance = new Head_AdvanceSearch_Js();
			advanceSearchInstance.advanceSearchTriggerIntiatorHandler();
			//			advanceSearchInstance.initiateSearch().then(function() {
			//				advanceSearchInstance.selectBasicSearchValue();
			//			});
		});
	},

	/**
	 * Function which will handle the reference auto complete event registrations
	 * @params - container <jQuery> - element in which auto complete fields needs to be searched
	 */
	registerAutoCompleteFields: function (container) {
		var thisInstance = this;
		container.find('input.autoComplete').autocomplete({
			'minLength': '3',
			'source': function (request, response) {
				//element will be array of dom elements
				//here this refers to auto complete instance
				var inputElement = jQuery(this.element[0]);
				var searchValue = request.term;
				var params = thisInstance.getReferenceSearchParams(inputElement);
				params.module = app.getModuleName();
				if (jQuery('#QuickCreate').length > 0) {
					params.module = container.find('[name="module"]').val();
				}
				params.search_value = searchValue;
				if (params.search_module && params.search_module != 'undefined') {
					thisInstance.searchModuleNames(params).then(function (data) {
						var reponseDataList = new Array();
						var serverDataFormat = data;
						if (serverDataFormat.length <= 0) {
							jQuery(inputElement).val('');
							serverDataFormat = new Array({
								'label': 'No Results Found',
								'type': 'no results'
							});
						}
						for (var id in serverDataFormat) {
							var responseData = serverDataFormat[id];
							reponseDataList.push(responseData);
						}
						response(reponseDataList);
					});
				} else {
					jQuery(inputElement).val('');
					serverDataFormat = new Array({
						'label': 'No Results Found',
						'type': 'no results'
					});
					response(serverDataFormat);
				}
			},
			'select': function (event, ui) {
				var selectedItemData = ui.item;
				//To stop selection if no results is selected
				if (typeof selectedItemData.type != 'undefined' && selectedItemData.type == "no results") {
					return false;
				}
				var element = jQuery(this);
				var parent = element.closest('td');
				if (parent.length == 0) {
					parent = element.closest('.fieldValue');
				}
				var sourceField = parent.find('.sourceField');
				selectedItemData.record = selectedItemData.id;
				selectedItemData.source_module = parent.find('input[name="popupReferenceModule"]').val();
				selectedItemData.selectedName = selectedItemData.label;
				var fieldName = sourceField.attr("name");
				parent.find('input[name="' + fieldName + '"]').val(selectedItemData.id);
				element.attr("value", selectedItemData.id);
				element.data("value", selectedItemData.id);
				parent.find('.clearReferenceSelection').removeClass('hide');
				parent.find('.referencefield-wrapper').addClass('selected');
				element.attr("disabled", "disabled");
				//trigger reference field selection event
				sourceField.trigger(Head_Edit_Js.referenceSelectionEvent, selectedItemData);
				//trigger post reference selection
				sourceField.trigger(Head_Edit_Js.postReferenceSelectionEvent, {
					'data': selectedItemData
				});
			}
		});
	},

	/**
	 * Function to register clear reference selection event
	 * @param <jQUery> container
	 */
	registerClearReferenceSelectionEvent: function (container) {
		container.find('.clearReferenceSelection').on('click', function (e) {
			e.preventDefault();
			var element = jQuery(e.currentTarget);
			var parentTdElement = element.closest('td');
			if (parentTdElement.length == 0) {
				parentTdElement = element.closest('.fieldValue');
			}
			var inputElement = parentTdElement.find('.inputElement');
			var fieldName = parentTdElement.find('.sourceField').attr("name");

			parentTdElement.find('.referencefield-wrapper').removeClass('selected');
			inputElement.removeAttr("disabled").removeAttr('readonly');
			inputElement.attr("value", "");
			inputElement.val("");
			parentTdElement.find('input[name="' + fieldName + '"]').val("");
			element.addClass('hide');
			element.trigger(Head_Edit_Js.referenceDeSelectionEvent);
		});
	},

	/**
	 * Function which will register event for create of reference record
	 * This will allow users to create reference record from edit view of other record
	 */
	registerReferenceCreate: function (container) {
		var thisInstance = this;
		container.on('click', '.createReferenceRecord', function (e) {
			var element = jQuery(e.currentTarget);
			var controlElementTd = thisInstance.getParentElement(element);
			thisInstance.referenceCreateHandler(controlElementTd);
		});
	},

	/**
	 * Funtion to register popup search event for reference field
	 * @param <jQuery> container
	 */
	referenceModulePopupRegisterEvent: function (container) {
		var thisInstance = this;
		container.off('click', '.relatedPopup');
		container.on("click", '.relatedPopup', function (e) {
			qc_container = thisInstance.getParentElement($(this).next('.createReferenceRecord'));
			var referenceModuleName = thisInstance.getReferencedModuleName(container);
			var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="' + referenceModuleName + '"]');
			if (quickCreateNode.length <= 0) {
				var notificationOptions = {
					'title': app.vtranslate('JS_NO_CREATE_OR_NOT_QUICK_CREATE_ENABLED')
				}
				app.helper.showAlertNotification(notificationOptions);
			} else {
				thisInstance.openPopUp(e);
			}
		});
		container.on('change', '.referenceModulesList', function (e) {
			var element = jQuery(e.currentTarget);
			var closestTD = thisInstance.getParentElement(element).next();
			var popupReferenceModule = element.val();
			var referenceModuleElement = jQuery('input[name="popupReferenceModule"]', closestTD).length ?
				jQuery('input[name="popupReferenceModule"]', closestTD) : jQuery('input.popupReferenceModule', closestTD);
			var prevSelectedReferenceModule = referenceModuleElement.val();
			referenceModuleElement.val(popupReferenceModule);

			//If Reference module is changed then we should clear the previous value
			if (prevSelectedReferenceModule != popupReferenceModule) {
				closestTD.find('.clearReferenceSelection').trigger('click');
			}
		});
	},

	/**
	 * Function to open popup list modal
	 */
	openPopUp: function (e) {
		var thisInstance = this;
		var parentElem = thisInstance.getParentElement(jQuery(e.target));

		var params = this.getPopUpParams(parentElem);
		params.view = 'Popup';

		var isMultiple = false;
		if (params.multi_select) {
			isMultiple = true;
		}

		var sourceFieldElement = jQuery('input[class="sourceField"]', parentElem);

		var prePopupOpenEvent = jQuery.Event(Head_Edit_Js.preReferencePopUpOpenEvent);
		sourceFieldElement.trigger(prePopupOpenEvent);

		if (prePopupOpenEvent.isDefaultPrevented()) {
			return;
		}
		var popupInstance = Head_Popup_Js.getInstance();

		app.event.off(Head_Edit_Js.popupSelectionEvent);
		app.event.one(Head_Edit_Js.popupSelectionEvent, function (e, data) {
			var responseData = JSON.parse(data);
			var dataList = new Array();
			jQuery.each(responseData, function (key, value) {
				var counter = 0;
				for (var valuekey in value) {
					if (valuekey == 'name') continue;
					if (typeof valuekey == 'object') continue;
					//					var referenceModule = value[valuekey].module;
					//					if(typeof referenceModule == "undefined") {
					//						referenceModule = value.module;
					//					}
					//					if(parentElem.find('[name="popupReferenceModule"]').val() != referenceModule) continue;
					//					
					var data = {
						'name': value.name,
						'id': key
					}
					if (valuekey == 'info') {
						data['name'] = value.name;
					}
					dataList.push(data);
					if (!isMultiple && counter === 0) {
						counter++;
						thisInstance.setReferenceFieldValue(parentElem, data);
					}
				}
			});

			if (isMultiple) {
				sourceFieldElement.trigger(Head_Edit_Js.refrenceMultiSelectionEvent, {
					'data': dataList
				});
			}
			sourceFieldElement.trigger(Head_Edit_Js.postReferenceSelectionEvent, {
				'data': responseData
			});
		});
		popupInstance.showPopup(params, Head_Edit_Js.popupSelectionEvent, function () {});
	},

	/**
	 * Functions changes the value of max upload size variable
	 * @param {type} container
	 * @returns {unresolved}
	 */
	getMaxiumFileUploadingSize: function (container) {
		//TODO : get it from the server
		return container.find('.maxUploadSize').data('value');
	},

	/**
	 * Function display file size in kb,mb,gb etc
	 */
	convertFileSizeInToDisplayFormat: function (fileSizeInBytes) {
		var i = -1;
		var byteUnits = [' kB', ' MB', ' GB', ' TB', 'PB', 'EB', 'ZB', 'YB'];
		do {
			fileSizeInBytes = fileSizeInBytes / 1024;
			i++;
		} while (fileSizeInBytes > 1024);

		return Math.max(fileSizeInBytes, 0.1).toFixed(1) + byteUnits[i];

	},

	/**
	 * Function will trigger whenever customer filename got added or changed
	 * @returns {undefined}
	 */
	registerFileChangeEvent: function () {
		var thisInstance = this;
		var container = jQuery('body');
		Head_Index_Js.files = '';
		container.on('change', 'input[name="filename[]"],input[name="imagename[]"]', function (e) {
			if (e.target.type == "text") return false;

			var files_uploaded = [];
			var fileSize = 0;
			jQuery.each(e.target.files, function (key, element) {
				files_uploaded[key] = element;
				fileSize += Number(element['size']);
			});


			Head_Index_Js.files = files_uploaded;
			var element = container.find('input[name="filename[]"],input[name="imagename[]"]');
			//ignore all other types than file 
			if (element.attr('type') != 'file') {
				return;
			}
			var uploadFileSizeHolder = element.closest('.fileUploadContainer').find('.uploadedFileSize');
			var maxFileSize = thisInstance.getMaxiumFileUploadingSize(container);
			if (fileSize > maxFileSize) {
				alert(app.vtranslate('JS_EXCEEDS_MAX_UPLOAD_SIZE'));
				var removeFileLinks = jQuery('.MultiFile-remove');
				jQuery(removeFileLinks[removeFileLinks.length - 1]).click();
			} else {
				if (container.length > 1) {
					jQuery('div.fieldsContainer').find('form#I_form').find('input[name="filename"]').css('width', '80px');
					jQuery('div.fieldsContainer').find('form#W_form').find('input[name="filename"]').css('width', '80px');
				} else {
					container.find('input[name="filename[]"]').css('width', '80px');
				}
			}
		});
	},


	/**
	 * Will register multiple file upload plugin
	 * @returns {undefined}
	 * Reference: http://www.fyneworks.com/jquery/multifile/
	 */
	registerMultiUpload: function () {
		var indexInstance = Head_Index_Js.getInstance();
		if (jQuery('input[type="file"].multi').is(":visible")) { //if the container is visible on the page
			jQuery('input[type="file"]').MultiFile();
			indexInstance.registerHoverEventOnAttachment();
		} else {
			setTimeout(indexInstance.registerMultiUpload, 50); //wait 50 ms, then try again
		}
	},

	//removed toggle class for quickcreate

	/**
	 * Function register on mouseover and mouseout events
	 * @returns {undefined}
	 */
	registerHoverEventOnAttachment: function () {
		jQuery('body').on('mouseover', '.filePreview', function (e) {
			jQuery(e.currentTarget).closest('div').find('a[name="downloadfile"] i').removeClass('hide').css('display', 'block');
		}).on('mouseout', '.filePreview', function (e) {
			jQuery(e.currentTarget).closest('div').find('a[name="downloadfile"] i').addClass('hide');
		});
	},
	/*
	 * Function to get reference select popup parameters
	 */
	getPopUpParams: function (container) {
		var params = {};
		var sourceModule = app.getModuleName();
		var editTaskContainer = jQuery('[name="editTask"]');
		if (editTaskContainer.length > 0) {
			sourceModule = editTaskContainer.find('#sourceModule').val();
		}
		var quickCreateConatiner = jQuery('[name="QuickCreate"]');
		if (quickCreateConatiner.length != 0) {
			sourceModule = quickCreateConatiner.find('input[name="module"]').val();
		}
		var searchResultContainer = jQuery('#searchResults-container');
		if (searchResultContainer.length) {
			sourceModule = jQuery('select#searchModuleList').val();
		}
		var popupReferenceModuleElement = jQuery('input[name="popupReferenceModule"]', container).length ?
			jQuery('input[name="popupReferenceModule"]', container) : jQuery('input.popupReferenceModule', container);
		var popupReferenceModule = popupReferenceModuleElement.val();
		var sourceFieldElement = jQuery('input[class="sourceField"]', container);
		if (!sourceFieldElement.length) {
			sourceFieldElement = jQuery('input.sourceField', container);
		}
		var sourceField = sourceFieldElement.attr('name');
		var sourceRecordElement = jQuery('input[name="record"]');
		var sourceRecordId = '';
		var recordId = app.getRecordId();
		if (sourceRecordElement.length > 0) {
			sourceRecordId = sourceRecordElement.val();
		} else if (recordId) {
			sourceRecordId = recordId;
		} else if (app.view() == 'List') {
			var editRecordId = jQuery('#listview-table').find('tr.listViewEntries.edited').data('id');
			if (editRecordId) {
				sourceRecordId = editRecordId;
			}
		}

		if (searchResultContainer.length) {
			sourceRecordId = searchResultContainer.find('tr.listViewEntries.edited').data('id')
		}

		var isMultiple = false;
		if (sourceFieldElement.data('multiple') == true) {
			isMultiple = true;
		}

		// TODO : Need to recheck. We don't have reference field module name if that module is disabled
		if (typeof popupReferenceModule == "undefined") {
			popupReferenceModule = "undefined";
		}

		var params = {
			'module': popupReferenceModule,
			'src_module': sourceModule,
			'src_field': sourceField,
			'src_record': sourceRecordId
		}

		if (isMultiple) {
			params.multi_select = true;
		}
		return params;
	},

	/*
	 * Function to set reference field value
	 */
	setReferenceFieldValue: function (container, params) {
		var sourceField = container.find('input.sourceField').attr('name');
		var fieldElement = container.find('input[name="' + sourceField + '"]');
		var sourceFieldDisplay = sourceField + "_display";
		var fieldDisplayElement = container.find('input[name="' + sourceFieldDisplay + '"]');
		var popupReferenceModuleElement = container.find('input[name="popupReferenceModule"]').length ? container.find('input[name="popupReferenceModule"]') : container.find('input.popupReferenceModule');
		var popupReferenceModule = popupReferenceModuleElement.val();
		var selectedName = params.name;
		var id = params.id;

		if (id && selectedName) {
			if (!fieldDisplayElement.length) {
				fieldElement.attr('value', id);
				fieldElement.val(selectedName);
			} else {
				fieldElement.val(id);
				fieldDisplayElement.val(selectedName);
				if (selectedName) {
					fieldDisplayElement.attr('readonly', 'readonly');
				} else {
					fieldDisplayElement.removeAttr("readonly");
				}
			}

			if (selectedName) {
				fieldElement.parent().find('.clearReferenceSelection').removeClass('hide');
				fieldElement.parent().find('.referencefield-wrapper').addClass('selected');
			} else {
				fieldElement.parent().find('.clearReferenceSelection').addClass('hide');
				fieldElement.parent().find('.referencefield-wrapper').removeClass('selected');
			}
			fieldElement.trigger(Head_Edit_Js.referenceSelectionEvent, {
				'source_module': popupReferenceModule,
				'record': id,
				'selectedName': selectedName
			});
		}
	},

	/*
	 * Function to get referenced module name
	 */
	getReferencedModuleName: function (parentElement) {
		var referenceModuleElement = jQuery('input[name="popupReferenceModule"]', parentElement).length ?
			jQuery('input[name="popupReferenceModule"]', parentElement) : jQuery('input.popupReferenceModule', parentElement);
		return referenceModuleElement.val();
	},

	/*
	 * Function to show quick create modal while creating from reference field
	 */
	referenceCreateHandler: function (container) {
		var thisInstance = this;
		var postQuickCreateSave = function (data) {
			var module = thisInstance.getReferencedModuleName(container);
			var params = {};
			params.name = data._recordLabel;
			params.id = data._recordId;
			params.module = module;
			thisInstance.setReferenceFieldValue(container, params);

			var tdElement = thisInstance.getParentElement(container.find('[value="' + module + '"]'));
			var sourceField = tdElement.find('input[class="sourceField"]').attr('name');
			var fieldElement = tdElement.find('input[name="' + sourceField + '"]');
			thisInstance.autoFillElement = fieldElement;
			thisInstance.postRefrenceSearch(params, container);

			tdElement.find('input[class="sourceField"]').trigger(Head_Edit_Js.postReferenceQuickCreateSave, {
				'data': data
			});
		}

		var referenceModuleName = this.getReferencedModuleName(container);
		var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="' + referenceModuleName + '"]');
		if (quickCreateNode.length <= 0) {
			var notificationOptions = {
				'title': app.vtranslate('JS_NO_CREATE_OR_NOT_QUICK_CREATE_ENABLED')
			}
			app.helper.showAlertNotification(notificationOptions);
		}
		quickCreateNode.trigger('click', [{
			'callbackFunction': postQuickCreateSave
		}]);
	},

	/**
	 * Function to get reference search params
	 */
	getReferenceSearchParams: function (element) {
		var tdElement = this.getParentElement(element);
		var params = {};
		var referenceModuleElement = jQuery('input[name="popupReferenceModule"]', tdElement).length ?
			jQuery('input[name="popupReferenceModule"]', tdElement) : jQuery('input.popupReferenceModule', tdElement);
		var searchModule = referenceModuleElement.val();
		params.search_module = searchModule;
		return params;
	},

	searchModuleNames: function (params) {
		var aDeferred = jQuery.Deferred();

		if (typeof params.module == 'undefined') {
			params.module = app.getModuleName();
		}

		if (typeof params.action == 'undefined') {
			params.action = 'BasicAjax';
		}

		if (typeof params.base_record == 'undefined') {
			var record = jQuery('[name="record"]');
			var recordId = app.getRecordId();
			if (record.length) {
				params.base_record = record.val();
			} else if (recordId) {
				params.base_record = recordId;
			} else if (app.view() == 'List') {
				var editRecordId = jQuery('#listview-table').find('tr.listViewEntries.edited').data('id');
				if (editRecordId) {
					params.base_record = editRecordId;
				}
			}
		}
		app.request.get({
			data: params
		}).then(
			function (err, res) {
				aDeferred.resolve(res);
			},
			function (error) {
				//TODO : Handle error
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},

	/*
	 * Function to get Field parent element
	 */
	getParentElement: function (element) {
		var parent = element.closest('td');
		// added to support from all views which may not be table format
		if (parent.length === 0) {
			parent = element.closest('.td').length ?
				element.closest('.td') : element.closest('.fieldValue');
		}
		return parent;
	},

	getUserNameForId: function (id) {
		for (var key in userList) {
			if (userList[key] === id) {
				return key;
			}
		}
		return null;
	},

	mentionerCallBack: function () {
		jQuery(document).on('textComplete:select', '.mention_listener', function (e, word, strategy) {
			//First charecter is " " if user mentioned in the begining
			//Removing it here
			var value = $(e.currentTarget).val();
			value = app.getDecodedValue(value);
			if (value.charAt(0) === ' ') value = value.substr(1);
			$(e.currentTarget).val(value);
			Head_Index_Js.hideNC = false;
		});
	},

	registerChangeTemplateEvent: function (container, recordId) {
		var sourceModule = container.find('#sourceModuleName').val();
		var thisInstance = this;
		var select = container.find('#fieldList');
		select.on("change", function () {
			var templateId = select.val();
			thisInstance.showQuickPreviewForId(recordId, sourceModule, app.getAppName(), templateId);
		});
	},

	registerMoreRecentUpdatesClickEvent: function (container, recordId) {
		var moduleName = container.find('#sourceModuleName').val();
		container.find('.moreRecentUpdates').on('click', function () {
			let site_url = $('#joforce_site_url').val();
			var recentUpdateURL = site_url + "index.php?view=Detail&mode=showRecentActivities&page=1&module=" + moduleName + "&record=" + recordId + "&tab_label=LBL_UPDATES";
			window.location.href = recentUpdateURL;
		});
	},

	registerNavigationEvents: function (container) {
		this.registerNextRecordClickEvent(container);
		this.registerPreviousRecordClickEvent(container);
	},

	registerNextRecordClickEvent: function (container) {
		var self = this;
		container.find('#quickPreviewNextRecordButton').on('click', function (e) {
			var element = jQuery(e.currentTarget);
			var nextRecordId = element.data('record') || element.data('id');
			var moduleName = container.find('#sourceModuleName').val();
			var templateId, fieldList = container.find('#fieldList');
			if (fieldList.length) {
				templateId = fieldList.val();
			}
			self.showQuickPreviewForId(nextRecordId, moduleName, templateId, false, 'navigation');
		});
	},

	registerPreviousRecordClickEvent: function (container) {
		var self = this;
		container.find('#quickPreviewPreviousRecordButton').on('click', function (e) {
			var element = jQuery(e.currentTarget);
			var prevRecordId = element.data('record') || element.data('id');
			var moduleName = container.find('#sourceModuleName').val();
			var templateId, fieldList = container.find('#fieldList');
			if (fieldList.length) {
				templateId = fieldList.val();
			}
			self.showQuickPreviewForId(prevRecordId, moduleName, templateId, false, 'navigation');
		});
	},

	_showInventoryQuickPreviewForId: function (recordId, moduleName, templateId, isReference, mode) {
		var thisInstance = this;
		var params = {};
		if (typeof moduleName === 'undefined') {
			moduleName = app.module();
		}
		params['module'] = moduleName;
		params['record'] = recordId;
		params['view'] = 'RecordQuickPreview';
		if (isReference == true) {
			params['navigation'] = 'false';
		} else {
			params['navigation'] = 'true';
		}

		if (templateId) {
			params['templateid'] = templateId;
		}

		if (mode) {
			params['preview_mode'] = mode;
		}

		app.helper.showProgress();
		app.request.get({
			data: params
		}).then(function (err, response) {
			app.helper.hideProgress();
			$("#quickviewcontent").html(response);
			$("#quickviewcontent").removeClass("hide");
			$("#table-content").css({
				"width": "68%"
			});
			jQuery('#quickviewcontent').css({
				'background': 'white',
				'float': 'right',
				'left': '68%',
				'position': 'absolute',
				'top': '50px',
				'height': '100%',
				'overflow-x': 'hidden',
				'overflow-y': 'auto'
			});
			var container = $("#quickviewcontent");
			if (mode == 'navigation') {
				$("#table-content").find($('.activeview').removeClass('activeview'));
				jQuery("#quick_preview_" + recordId).addClass('activeview');
			}
			thisInstance.registerNavigationEvents(container);
			thisInstance.registerMoreRecentUpdatesClickEvent(container, recordId);
			thisInstance.closeQuickview(container, recordId, moduleName);
		});
	},

	_showQuickPreviewForId: function (recordId, moduleName, isReference, mode) {
		var self = this;
		var params = {};
		if (typeof moduleName === 'undefined') {
			moduleName = app.module();
		}
		params['module'] = moduleName;
		params['record'] = recordId;
		params['view'] = 'RecordQuickPreview';
		if (isReference === true) {
			params['navigation'] = 'false';
		} else {
			params['navigation'] = 'true';
		}

		app.helper.showProgress();
		app.request.get({
			data: params
		}).then(function (err, response) {
			app.helper.hideProgress();
			$("#quickviewcontent").html(response);
			$("#quickviewcontent").removeClass("hide");
			$("#table-content").css({
				"width": "68%"
			});
			jQuery('#quickviewcontent').css({
				'background': 'white',
				'float': 'right',
				'left': '69%',
				'position': 'absolute',
				'top': '0px',
				'height': '100%',
				'overflow-x': 'hidden',
				'overflow-y': 'auto'
			});
			var container = $("#quickviewcontent");
			if (mode == 'navigation') {
				$("#table-content").find($('.activeview').removeClass('activeview'));
				jQuery("#quick_preview_" + recordId).addClass('activeview');
			}
			self.registerNavigationEvents(container);
			self.registerMoreRecentUpdatesClickEvent(container, recordId);
			self.closeQuickview(container, recordId, moduleName);
		});
	},

	closeQuickview: function (container, recordId, moduleName) {
		var self = this;
		container.find('.close').on('click', function () {
			$("#quickviewcontent").addClass("hide");
			$("#table-content").css({
				"width": "98%"
			});
			$('.fl-scrolls').css("width", "100%");
			$('.more-actions-right').removeClass('quickview-more-actions');
			jQuery("#" + moduleName + "_listView_row_" + recordId).find($('.quickView').removeClass('activeview'));
		});
	},

	isInventoryModule: function (moduleName) {
		var inventoryModules = jQuery('#inventoryModules').val();
		return inventoryModules.indexOf(moduleName) !== -1;
	},

	showQuickPreviewForId: function (recordId, moduleName, templateId, isReference, mode) {
		var self = this;
		if (self.isInventoryModule(moduleName)) {
			self._showInventoryQuickPreviewForId(recordId, moduleName, templateId, isReference, mode);
		} else {
			self._showQuickPreviewForId(recordId, moduleName, isReference, mode);
		}
	},

	registerReferencePreviewEvent: function () {
		var self = this;
		var view = app.view();
		jQuery('body').on('click', '.js-reference-display-value', function (e) {
			e.preventDefault();
			e.stopPropagation();
			var currentTarget = jQuery(this);
			if (currentTarget.closest('#popupPageContainer').length) {
				return; //no action in reference selection popup
			}
			var href = currentTarget.attr('href');
			if (view === 'List') {
				if (currentTarget.data('timer')) {
					//if list view single click has set a time, clear it
					clearTimeout(currentTarget.data('timer'));
					currentTarget.data('timer', null);
				}
				//perform show preview only after 500ms in list view to support double click edit action
				if (!currentTarget.data('preview-timer') && typeof href != 'undefined') {
					currentTarget.data('preview-timer', setTimeout(function () {
						var data = app.convertUrlToDataParams(href);
						self.showQuickPreviewForId(data.record, data.module, app.getAppName(), '', true);
						currentTarget.data('preview-timer', null);
					}, 500));
				}
			} else {
				var data = app.convertUrlToDataParams(href);
				self.showQuickPreviewForId(data.record, data.module, app.getAppName(), '', true);
			}
		});

		if (view === 'List') {
			/*
			 * when reference display value is double clicked in list view, 
			 * should initiate inline edit instead of showing preview
			 */
			jQuery('body').on('dblclick', '.js-reference-display-value', function (e) {
				e.preventDefault();
				var currentTarget = jQuery(this);
				if (currentTarget.data('preview-timer')) {
					clearTimeout(currentTarget.data('preview-timer'));
					currentTarget.data('preview-timer', null);
				};
			});
		}
	},

	registerPostReferenceEvent: function (container) {
		var thisInstance = this;

		container.find('.sourceField').on(Head_Edit_Js.postReferenceSelectionEvent, function (e, result) {
			var dataList = result.data;
			var element = jQuery(e.currentTarget);

			if (typeof element.data('autofill') != 'undefined') {
				thisInstance.autoFillElement = element;
				if (typeof (dataList.id) == 'undefined') {
					thisInstance.postRefrenceComplete(dataList, container);
				} else {
					thisInstance.postRefrenceSearch(dataList, container);
				}
			}
		});
	},

	postRefrenceComplete: function (data, container) {
		var thisInstance = this;
		if (!data)
			return;

		jQuery.each(data, function (id, value) {
			thisInstance.fillReferenceFieldValue(value, container);
		});
	},

	getRelatedFieldElements: function (container, autoFillData) {
		var parentElems = {};
		if (autoFillData) {
			var field = container.find('#' + autoFillData.fieldname + '_display').closest('td');
			parentElems['parent_id'] = field;
		}
		return parentElems;
	},

	fillReferenceFieldValue: function (data, container) {
		var thisInstance = this;
		var autoFillElement = this.autoFillElement;
		var autoFillData = autoFillElement.data('autofill');
		var completedValues = [];
		for (var index in autoFillData) {
			var value = autoFillData[index];
			var referenceContainer = thisInstance.getRelatedFieldElements(container, value);
			jQuery.each(data, function (datakey, datavalue) {
				for (var name in datavalue) {
					if (typeof datavalue[name] == 'object') {
						var key = name;
						var dataList = {
							'name': datavalue[key].name,
							'id': datavalue[key].id
						}

						if (value.module == datavalue[key].module) {
							var autoFillElement = thisInstance.autoFillElement;
							var autoFillData = value;
							var popupReferenceModuleElement = autoFillElement.parent().parent().find('[name=popupReferenceModule]').length ?
								autoFillElement.parent().parent().find('[name=popupReferenceModule]') : autoFillElement.parent().parent().find('.popupReferenceModule');
							var module = popupReferenceModuleElement.val();
							var elementName = autoFillElement.attr('name');
							var selectedName = container.find('#' + elementName + '_display').val();
							var message = app.vtranslate('JS_OVERWRITE_AUTOFILL_MSG1') + ' ' + app.vtranslate('SINGLE_' + autoFillData.module) + " " + app.vtranslate('JS_OVERWRITE_AUTOFILL_MSG2') + " " + app.vtranslate('SINGLE_' + module) + ' (' + selectedName + ') ' + app.vtranslate('SINGLE_' + autoFillData.module) + " ?";
							var parentId = container.find('[name=' + autoFillData.fieldname + ']').val();

							if (parentId != dataList.id && parentId) {
								if (jQuery.inArray(datavalue[key].module, completedValues) === -1) {
									completedValues.push(datavalue[key].module);
									thisInstance.confirmAndFillDetails(referenceContainer[key], dataList, message);
								}
							} else {
								thisInstance.setReferenceFieldValue(referenceContainer[key], dataList);
							}
						}
					}
				}
			});
		}
	},

	confirmAndFillDetails: function (container, data, message) {
		var thisInstance = this;
		app.helper.showConfirmationBox({
			'message': message
		}).then(
			function (e) {
				thisInstance.setReferenceFieldValue(container, data);
			},
			function (error, err) {}
		);
	},

	postRefrenceSearch: function (resultData, container) {
		var thisInstance = this;
		var module;
		if (!resultData.module) {
			var autoFillElement = this.autoFillElement;
			var popupReferenceModuleElement = autoFillElement.parent().parent().find('[name=popupReferenceModule]').length ?
				autoFillElement.parent().parent().find('[name=popupReferenceModule]') : autoFillElement.parent().parent().find('.popupReferenceModule');
			module = popupReferenceModuleElement.val();
		} else {
			module = resultData.module;
		}
		if (!resultData.id)
			return;

		var params = {
			module: module,
			action: 'RelationAjax',
			mode: 'getRelatedRecordInfo',
			id: resultData.id
		};

		app.request.post({
			'data': params
		}).then(function (err, data) {
			if (err == null) {
				thisInstance.postRefrenceComplete(data, container);
			}
		});
	}
});

$(document).ready(function () {
	var module_name = app.getModuleName();
	if (module_name == 'Potentials') {
		$("#header-actions").find(".active").removeClass('active');
		var view_type = $("#potential-view-type").val();
		if (view_type == 'List') {
			$("#backto-list-view").addClass('active');
		} else {
			$("#forecast-view").addClass('active');
		}
	}

	if ($('#present-dashboard-tab')) {
		var present_tab = $('#present-dashboard-tab').val();
		if (present_tab == 'DASHLETS')
			$('#dashlets-option').addClass('active-dashboard');
		else
			$('#dashboard-option').addClass('active-dashboard');
	}

	$('.ui.accordion').accordion();
});

function checkWidth() {
	if ($(window).width() < 500) {
		$('.table-toggle').removeClass('fixed-scroll-table');
		$('.table-toggle1').removeClass('table form-horizontal no-border');
		$('.table_step').removeClass('table editview-table no-border');
	} else {
		$('.table-toggle').addClass('fixed-scroll-table');
		$('.table-toggle1').addClass('table form-horizontal no-border');
		$('.table_step').addClass('table editview-table no-border');
	}

}
//$(window).resize(checkWidth);
window.onload = function () {
	$(window).resize(checkWidth);
	if ($(window).width() < 500) {
		$('.table-toggle').removeClass('fixed-scroll-table');
		$('.table-toggle1').removeClass('table form-horizontal no-border');
		$('.table_step').removeClass('table editview-table no-border');

	}
}