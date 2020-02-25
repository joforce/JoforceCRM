/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_Index_Js("Settings_Head_Index_Js",{

	showMessage : function(customParams){
		var params = {};
		params.animation = "show";
		params.type = 'info';
		params.title = app.vtranslate('JS_MESSAGE');

		if(typeof customParams != 'undefined') {
			var params = jQuery.extend(params,customParams);
		}
		Head_Helper_Js.showPnotify(params);
	}
},{
	registerDeleteShortCutEvent : function(shortCutBlock) {
		var thisInstance = this;
		if(typeof shortCutBlock == 'undefined') {
			var shortCutBlock = jQuery('.moduleBlock');
		};
		shortCutBlock.find('.unpin').on('click',function(e) {
			var actionEle = jQuery(e.currentTarget);
			var closestBlock = actionEle.closest('.moduleBlock');
			var fieldId = actionEle.data('id');
			var shortcutBlockActionUrl = closestBlock.data('actionurl');
			var actionUrl = shortcutBlockActionUrl+'&pin=false';
			app.request.post({'url':actionUrl}).then(function(err, data) {
				if(err === null) {
					closestBlock.remove();
					thisInstance.registerSettingShortCutAlignmentEvent();
					var menuItemId = '#'+fieldId+'_menuItem';
					var shortCutActionEle = jQuery(menuItemId);
					var imagePath = shortCutActionEle.data('pinimageurl');
					shortCutActionEle.attr('src',imagePath).data('action','pin');
					app.helper.showSuccessNotification({'message':app.vtranslate('JS_SUCCESSFULLY_UNPINNED')});
				}
			});
			e.stopPropagation();
		});
	},

	registerPinUnpinShortCutEvent : function() {
		var thisInstance = this;
		var widget = jQuery('#accordion');
		widget.on('click','.pinUnpinShortCut',function(e){
			var shortCutActionEle = jQuery(e.currentTarget);
			var url = shortCutActionEle.data('actionurl');
			var shortCutElementActionStatus = shortCutActionEle.data('action');
			if(shortCutElementActionStatus == 'pin'){
				var actionUrl = url+'&pin=true';
			} else {
				actionUrl = url+'&pin=false';
			}
			var progressIndicatorElement = jQuery.progressIndicator({
				'blockInfo' : {
				'enabled' : true
				}
			});
			app.request.post({'url':actionUrl}).then(function(err, data) {
				if(data.SUCCESS == 'OK') {
					if (shortCutElementActionStatus == 'pin') {
						var imagePath = shortCutActionEle.data('unpinimageurl');
						var unpinTitle = shortCutActionEle.data('unpintitle');
						shortCutActionEle.attr('src',imagePath).data('action','unpin').attr('title',unpinTitle);
						var shortCutsMainContainer = jQuery('#settingsShortCutsContainer').find('.col-lg-12:last-child');
						if (shortCutsMainContainer.length > 0) {
							var url = 'module=Head&parent=Settings&view=IndexAjax&mode=getSettingsShortCutBlock&fieldid='+shortCutActionEle.data('id');
							app.request.post({url:url}).then(function(err, data){
								var newBlock = jQuery(data).appendTo(shortCutsMainContainer);
								thisInstance.registerSettingShortCutAlignmentEvent();
								thisInstance.registerDeleteShortCutEvent(newBlock);
							});
						}
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
						app.helper.showSuccessNotification({'message':app.vtranslate('JS_SUCCESSFULLY_PINNED')});
					} else {
						var imagePath = shortCutActionEle.data('pinimageurl');
						var pinTitle = shortCutActionEle.data('pintitle');
						shortCutActionEle.attr('src',imagePath).data('action','pin').attr('title',pinTitle);
						jQuery('#shortcut_'+shortCutActionEle.data('id')).remove();
						thisInstance.registerSettingShortCutAlignmentEvent();
						progressIndicatorElement.progressIndicator({'mode' : 'hide'});
						app.helper.showSuccessNotification({'message':app.vtranslate('JS_SUCCESSFULLY_UNPINNED')});
					}
				}
			});
			e.preventDefault();
		});
	},

	registerSettingsShortcutClickEvent : function() {
		jQuery('#settingsShortCutsContainer').on('click','.moduleBlock',function(e){
			var url = jQuery(e.currentTarget).data('url');
			window.location.href = url;
		});
	},

	registerSettingShortCutAlignmentEvent : function() {
		jQuery('#settingsShortCutsContainer').find('.moduleBlock').removeClass('marginLeftZero');
		jQuery('#settingsShortCutsContainer').find('.moduleBlock:nth-child(4n+1)').addClass('marginLeftZero');
	},

	registerWidgetsEvents : function() {
		var widgets = jQuery('div.widgetContainer');
		widgets.on({
			shown: function(e) {
				var widgetContainer = jQuery(e.currentTarget);
				var quickWidgetHeader = widgetContainer.closest('.quickWidget').find('.quickWidgetHeader');
				var imageEle = quickWidgetHeader.find('.imageElement')
				var imagePath = imageEle.data('downimage');
				imageEle.attr('src',imagePath);
			},
			hidden: function(e) {
				var widgetContainer = jQuery(e.currentTarget);
				var quickWidgetHeader = widgetContainer.closest('.quickWidget').find('.quickWidgetHeader');
				var imageEle = quickWidgetHeader.find('.imageElement');
				var imagePath = imageEle.data('rightimage');
				imageEle.attr('src',imagePath);
			}
		});
	},

	registerAddShortcutDragDropEvent : function() {
		var thisInstance = this;

		jQuery( ".menuItemLabel" ).draggable({
			appendTo: "body",
			helper: "clone"
		});
		jQuery( "#settingsShortCutsContainer" ).droppable({
			activeClass: "ui-state-default",
			hoverClass: "ui-state-hover",
			accept: ".menuItemLabel",
			drop: function( event, ui ) {
				var actionElement = ui.draggable.find('.pinUnpinShortCut');
				var pinStatus = actionElement.data('action');
				if(pinStatus === 'unpin') {
					app.helper.showSuccessNotification({'message':app.vtranslate('JS_SHORTCUT_ALREADY_ADDED')});
				} else {
					actionElement.trigger('click');
				}
			}
		});
	},

	registerEventForShowOrHideSettingsLinks: function () {
		jQuery('.slidingDiv').hide();
		jQuery('.show_hide').click(function (e) {
			jQuery(this).next(".slidingDiv").slideToggle('fast');
		});
	},

	registerAccordionClickEvent : function() {
		function toggleChevron(e) {
			$(e.target)
				.prev('.app-nav')
				.find("i.indicator")
				.toggleClass('fa-chevron-down fa-chevron-right');
		}
		$('#accordion').on('hidden.bs.collapse', toggleChevron);
		$('#accordion').on('shown.bs.collapse', toggleChevron);
	},

	registerBasicSettingsEvents : function() {
			this.registerAccordionClickEvent();
			this.registerFilterSearch();
			if(window.hasOwnProperty('Head_List_Js')) {
				var listInstance = new Head_List_Js();
				setTimeout(function(){
					listInstance.registerFloatingThead();
				}, 10);

				app.event.on('Head.Post.MenuToggle', function() {
					listInstance.reflowList();
				});
				listInstance.registerDynamicDropdownPosition();
			}
	},

	registerFilterSearch : function () {
		var settings = jQuery('.settingsgroup');
			jQuery('.search-list').instaFilta({
				targets: '.menuItemLabel',
				sections : '.instaSearch',
				markMatches: true,
				onFilterComplete: function(matchedItems) {
					$("#accordion").removeClass('hide');
					if(jQuery('.search-list').val().length <= 0){
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

	registerMasqueradeUserOption : function() {
		$('#enable-masquerade-user').change( function() {
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
                        app.request.post({data: params}).then(function(err, data){
                                app.helper.hideProgress();
                        });
		});
        },

        registerSettingsSearchDropdownEvent : function() {
                $(document).mouseup(function(e) {
                    var container = $(".settingsgroup");
                    // if the target of the click isn't the container nor a descendant of the container
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        $("#settingsMenuSearch").val('');
                        $("#accordion").addClass('hide');
                    }
                });

		//For settings index search
		$(document).mouseup(function(e) {
                    var container = $(".settings_search");
                    // if the target of the click isn't the container nor a descendant of the container
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                        $("#search_settings").val('');
                        $("#search_display").addClass('hide');
                    }
                });
        },

	registerIndexBasicSettingsEvents : function() {
		this.registerIndexFilterSearch();
		if(window.hasOwnProperty('Head_List_Js')) {
			var listInstance = new Head_List_Js();
			setTimeout(function(){
                        	listInstance.registerFloatingThead();
                        }, 10);

                        app.event.on('Head.Post.MenuToggle', function() {
                        	listInstance.reflowList();
                        });
                        listInstance.registerDynamicDropdownPosition();
		}
        },

	registerIndexFilterSearch : function () {
		var settings = jQuery('.settings_search');
		var res = new Array();
		jQuery('.search-settings').instaFilta({
			targets: '.search_display',
			markMatches: true,
			onFilterComplete: function(matchedItems) {
				$("#search_display").removeClass('hide');
				var input  = jQuery('.search-settings').val();
				if(input <= 0){
					$("#search_display").addClass('hide');
					$('#search_display').hide();
					$('.admin-settings').show();
					return;
				}
				$('#search_display ul').html('');
				var results = $('div#knowledgeDomain').children('span').text();
				search_count = 0;
				$('.admin-settings').find('.module_search').each(function(){
					var ul = $(this).children('.settings-list');						
					ul.find('.para_list').each(function() {
						parent_a = $(this).closest('.list_values').html();
						var value = $(this).html();
     						if(value.toLowerCase().indexOf(input) > -1){
     							$("#search_display ul").append("<li class='list_values col-md-1'>"+parent_a+"</li>");
     							search_count++;
     							$('.admin-settings').hide();
     				   		}
					});
				});
				$("#search_display").show();
     				if(search_count == 0){   		
			   		$('#empty_settings').empty();
     				   	$("#search_display ul").append("<li id='empty_settings'>No Result Found</li>");
     				   	$("#search_display").show();
     				   	$('.admin-settings').hide();
     				}
			}
		});
	},

	registerIndexSettingsSearchDropdownEvent : function() {
		$(document).mouseup(function(e) {
		    var container = $(".settings_search");
		    // if the target of the click isn't the container nor a descendant of the container
		    if (!container.is(e.target) && container.has(e.target).length === 0) {
		        $("#search_settings").val('');
			$("#search_display").addClass('hide');
			$('.admin-settings').show();
		    }
		});
	},

	registerEvents: function() {
		this._super();
		this.registerSettingsShortcutClickEvent();
		this.registerDeleteShortCutEvent();
		this.registerWidgetsEvents();
		this.registerPinUnpinShortCutEvent();
		this.registerAddShortcutDragDropEvent();
		this.registerSettingShortCutAlignmentEvent();
		this.registerBasicSettingsEvents();
		this.registerMasqueradeUserOption();
		this.registerSettingsSearchDropdownEvent();
		this.registerIndexSettingsSearchDropdownEvent();
		this.registerIndexBasicSettingsEvents();
	}
});
