/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_Index_Js("Settings_Head_Index_Js", {

	showMessage: function (customParams) {
		var params = {};
		params.animation = "show";
		params.type = 'info';
		params.title = app.vtranslate('JS_MESSAGE');

		if (typeof customParams != 'undefined') {
			var params = jQuery.extend(params, customParams);
		}
		Head_Helper_Js.showPnotify(params);
	}
}, {

	registerWidgetsEvents: function () {
		var widgets = jQuery('div.widgetContainer');
		widgets.on({
			shown: function (e) {
				var widgetContainer = jQuery(e.currentTarget);
				var quickWidgetHeader = widgetContainer.closest('.quickWidget').find('.quickWidgetHeader');
				var imageEle = quickWidgetHeader.find('.imageElement')
				var imagePath = imageEle.data('downimage');
				imageEle.attr('src', imagePath);
			},
			hidden: function (e) {
				var widgetContainer = jQuery(e.currentTarget);
				var quickWidgetHeader = widgetContainer.closest('.quickWidget').find('.quickWidgetHeader');
				var imageEle = quickWidgetHeader.find('.imageElement');
				var imagePath = imageEle.data('rightimage');
				imageEle.attr('src', imagePath);
			}
		});
	},


	registerAccordionClickEvent: function () {
		function toggleChevron(e) {
			$(e.target)
				.prev('.app-nav')
				.find("i.indicator")
				.toggleClass('fa-chevron-down fa-chevron-right');
		}
		$('#accordion').on('hidden.bs.collapse', toggleChevron);
		$('#accordion').on('shown.bs.collapse', toggleChevron);
	},

	registerBasicSettingsEvents: function () {
		this.registerAccordionClickEvent();
		this.registerFilterSearch();
		if (window.hasOwnProperty('Head_List_Js')) {
			var listInstance = new Head_List_Js();
			setTimeout(function () {
				listInstance.registerFloatingThead();
			}, 10);

			app.event.on('Head.Post.MenuToggle', function () {
				listInstance.reflowList();
			});
			listInstance.registerDynamicDropdownPosition();
		}
	},

	registerFilterSearch: function () {
		var settings = jQuery('.settingsgroup');
		jQuery('.search-list').instaFilta({
			targets: '.menuItemLabel',
			sections: '.instaSearch',
			markMatches: true,
			onFilterComplete: function (matchedItems) {
				$("#accordion").removeClass('hide');
				if (jQuery('.search-list').val().length <= 0) {
					jQuery('.instaSearch').find('.widgetContainer').closest('.panel-collapse').filter('.in').removeClass('in');
					jQuery('.instaSearch').find('.indicator').removeClass('fa-chevron-down').addClass('fa-chevron-right');
					$("#accordion").addClass('hide');
					return;
				}
				jQuery('.instaSearch').find('[data-instafilta-hide="false"]').closest('.panel-collapse').filter(':not(.in)').addClass('in').height('');
				jQuery('.instaSearch').filter(':visible').find('[data-instafilta-hide="false"]').parents('.instaSearch').find('.indicator').removeClass('fa-chevron-right').addClass('fa-chevron-down');
			}
		});
	},

	registerMasqueradeUserOption: function () {
		$('#enable-masquerade-user').change(function () {
			if ($(this).is(':checked'))
				$(this).val('true');
			else
				$(this).val('false');

			var status = $(this).val();

			var params = {
				module: app.getModuleName(),
				parent: app.getParentModuleName(),
				action: 'SaveMasqueradeUserSettings',
				user_status: status,
			}
			app.helper.showProgress();
			app.request.post({
				data: params
			}).then(function (err, data) {
				app.helper.hideProgress();
			});
		});
	},

	registerSettingsSearchDropdownEvent: function () {
		$(document).mouseup(function (e) {
			var container = $(".settingsgroup");
			// if the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				$("#settingsMenuSearch").val('');
				$("#accordion").addClass('hide');
			}
		});

		//For settings index search
		$(document).mouseup(function (e) {
			var container = $(".settings_search");
			// if the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				$("#search_settings").val('');
				$("#search_display").addClass('hide');
			}
		});
	},

	registerIndexBasicSettingsEvents: function () {
		this.registerIndexFilterSearch();
		if (window.hasOwnProperty('Head_List_Js')) {
			var listInstance = new Head_List_Js();
			setTimeout(function () {
				listInstance.registerFloatingThead();
			}, 10);

			app.event.on('Head.Post.MenuToggle', function () {
				listInstance.reflowList();
			});
			listInstance.registerDynamicDropdownPosition();
		}
	},

	registerIndexFilterSearch: function () {
		var settings = jQuery('.settings_search');
		var res = new Array();
		jQuery('.search-settings').instaFilta({
			targets: '.search_display',
			markMatches: true,
			onFilterComplete: function (matchedItems) {
				$("#search_display").removeClass('hide');
				var input = jQuery('.search-settings').val().toLowerCase();
				if (input <= 0) {
					$("#search_display").addClass('hide');
					$('#search_display').hide();
					$('.admin-settings').show();
					return;
				}
				$('#search_display ul').html('');
				var results = $('div#knowledgeDomain').children('span').text();
				search_count = 0;
				$('.admin-settings').find('.module_search').each(function () {
					var ul = $(this).children('.settings-list');
					ul.find('.icons-hover').each(function () {
						parent_a = $(this).closest('.newSearch').html();
						var value = $(this).html();
						if (value.toLowerCase().indexOf(input) > -1) {
							//$("#search_display ul").append("<li class='list_values col-md-6 col-sm-12 col-lg-6'>"+parent_a+"</li>");
							$("#search_display ul").append("<div class='col-lg-6 col-md-6 col-sm-12 d-flex'>" + parent_a + "</div>");
							search_count++;
							$('.admin-settings').hide();
						}
					});
				});
				$("#search_display").show();
				if (search_count == 0) {
					$('#empty_settings').empty();
					$("#search_display ul").append("<div id='empty_settings'>No Result Found</div>");
					$("#search_display").show();
					$('.admin-settings').hide();
				}
			}
		});
	},

	registerIndexSettingsSearchDropdownEvent: function () {
		$(document).mouseup(function (e) {
			var container = $(".settings_search");
			// if the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				$("#search_settings").val('');
				$("#search_display").addClass('hide');
				$('.admin-settings').show();
			}
		});
	},

	registerEvents: function () {
		this._super();
		this.registerWidgetsEvents();
		this.registerBasicSettingsEvents();
		this.registerMasqueradeUserOption();
		this.registerSettingsSearchDropdownEvent();
		this.registerIndexSettingsSearchDropdownEvent();
		this.registerIndexBasicSettingsEvents();
	}
});