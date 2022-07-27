/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head.Class("Head_DashBoard_Js", {

	gridster: false,

	//static property which will store the instance of dashboard
	currentInstance: false,
	dashboardTabsLimit: 10,

	addWidget: function (element, url) {
		var element = jQuery(element);
		var linkId = element.data('linkid');
		var name = element.data('name');

		// After adding widget, we should remove that widget from Add Widget drop down menu from active tab
		var activeTabId = Head_DashBoard_Js.currentInstance.getActiveTabId();
		jQuery('a[data-name="' + name + '"]', "#tab_" + activeTabId).parent().hide();
		var widgetContainer = jQuery('<li class="new dashboardWidget col-sm-6 loadcompleted ' + name + '" id="' + linkId + '" data-name="' + name + '" data-mode="open"></li>');
		widgetContainer.data('url', url);
		var width = 1;
		var height = 1.5;
		Head_DashBoard_Js.gridster.add_widget(widgetContainer, width, height);
		Head_DashBoard_Js.currentInstance.loadWidget(widgetContainer);

		element.closest('li').css({
			'display': 'none'
		});
	},

	addMiniListWidget: function (element, url) {
		// 1. Show popup window for selection (module, filter, fields)
		// 2. Compute the dynamic mini-list widget url
		// 3. Add widget with URL to the page.

		element = jQuery(element);

		app.request.post({
			"url": "index.php?module=Home&view=MiniListWizard&step=step1"
		}).then(function (err, res) {
			var callback = function (data) {
				var wizardContainer = jQuery(data);
				var form = jQuery('form', wizardContainer);

				var moduleNameSelectDOM = jQuery('select[name="module"]', wizardContainer);
				var filteridSelectDOM = jQuery('select[name="filterid"]', wizardContainer);
				var fieldsSelectDOM = jQuery('select[name="fields"]', wizardContainer);

				var moduleNameSelect2 = vtUtils.showSelect2ElementView(moduleNameSelectDOM, {
					placeholder: app.vtranslate('JS_SELECT_MODULE')
				});
				var filteridSelect2 = vtUtils.showSelect2ElementView(filteridSelectDOM, {
					placeholder: app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION')
				});
				var fieldsSelect2 = vtUtils.showSelect2ElementView(fieldsSelectDOM, {
					placeholder: app.vtranslate('JS_PLEASE_SELECT_ATLEAST_ONE_OPTION'),
					closeOnSelect: true,
					maximumSelectionSize: 2
				});
				var footer = jQuery('.modal-footer', wizardContainer);

				filteridSelectDOM.closest('tr').hide();
				fieldsSelectDOM.closest('tr').hide();
				footer.hide();

				moduleNameSelect2.change(function () {
					if (!moduleNameSelect2.val()) return;

					var moduleNameSelect2Params = {
						module: 'Home',
						view: 'MiniListWizard',
						step: 'step2',
						selectedModule: moduleNameSelect2.val()
					};

					app.request.post({
						"data": moduleNameSelect2Params
					}).then(function (err, res) {
						filteridSelectDOM.empty().html(res).trigger('change');
						filteridSelect2.closest('tr').show();
						fieldsSelect2.closest('tr').hide();
						footer.hide();
					})
				});
				filteridSelect2.change(function () {
					if (!filteridSelect2.val()) return;

					var selectedModule = moduleNameSelect2.val();
					var filteridSelect2Params = {
						module: 'Home',
						view: 'MiniListWizard',
						step: 'step3',
						selectedModule: selectedModule,
						filterid: filteridSelect2.val()
					};

					app.request.post({
						"data": filteridSelect2Params
					}).then(function (err, res) {
						fieldsSelectDOM.empty().html(res).trigger('change');
						var translatedModuleNames = JSON.parse(jQuery("#minilistWizardContainer").find("#translatedModuleNames").val());
						var fieldsLabelText = app.vtranslate('JS_EDIT_FIELDS', translatedModuleNames[selectedModule], translatedModuleNames[selectedModule]);
						fieldsSelect2.closest('tr').find('.fieldLabel label').text(fieldsLabelText);
						fieldsSelect2.closest('tr').show();
					});
				});
				fieldsSelect2.change(function () {
					if (!fieldsSelect2.val()) {
						footer.hide();
					} else {
						footer.show();
					}
				});

				form.submit(function (e) {
					e.preventDefault();
					//To disable savebutton after one submit to prevent multiple submits
					jQuery("[name='saveButton']").attr('disabled', 'disabled');
					var selectedModule = moduleNameSelect2.val();
					var selectedFilterId = filteridSelect2.val();
					var selectedFields = fieldsSelect2.val();
					if (typeof selectedFields != 'object') selectedFields = [selectedFields];

					// TODO mandatory field validation

					finializeAdd(selectedModule, selectedFilterId, selectedFields);
				});
			}
			app.helper.showModal(res, {
				"cb": callback
			});
		});

		function finializeAdd(moduleName, filterid, fields) {
			var data = {
				module: moduleName
			}
			if (typeof fields != 'object') fields = [fields];
			data['fields'] = fields;

			url += '&filterid=' + filterid + '&data=' + JSON.stringify(data);
			var linkId = element.data('linkid');
			var name = element.data('name');
			var widgetContainer = jQuery('<li class="new dashboardWidget col-sm-6 loadcompleted" id="' + linkId + "-" + filterid + '" data-name="' + name + '" data-mode="open"></li>');
			widgetContainer.data('url', url);
			var width = element.data('width');
			var height = element.data('height');
			Head_DashBoard_Js.gridster.add_widget(widgetContainer, width, height);
			Head_DashBoard_Js.currentInstance.loadWidget(widgetContainer);
			app.helper.hideModal();
		}
	},

	addNoteBookWidget: function (element, url) {
		// 1. Show popup window for selection (module, filter, fields)
		// 2. Compute the dynamic mini-list widget url
		// 3. Add widget with URL to the page.

		element = jQuery(element);


		app.request.get({
			"url": "index.php?module=Home&view=AddNotePad"
		}).then(function (err, res) {
			var callback = function (data) {
				var wizardContainer = jQuery(data);
				var form = jQuery('form', wizardContainer);
				var params = {
					submitHandler: function (form) {
						//To prevent multiple click on save
						var form = jQuery(form);
						jQuery("[name='saveButton']").attr('disabled', 'disabled');
						var notePadName = form.find('[name="notePadName"]').val();
						var notePadContent = form.find('[name="notePadContent"]').val();
						var linkId = element.data('linkid');
						var noteBookParams = {
							'module': app.getModuleName(),
							'action': 'NoteBook',
							'mode': 'NoteBookCreate',
							'notePadName': notePadName,
							'notePadContent': notePadContent,
							'linkId': linkId,
							'tab': jQuery(".tab-pane.active").data("tabid")
						}
						app.request.post({
							"data": noteBookParams
						}).then(function (err, data) {
							if (data) {
								var widgetId = data.widgetId;
								app.helper.hideModal();

								url += '&widgetid=' + widgetId;

								var name = element.data('name');
								var widgetContainer = jQuery('<li class="new dashboardWidget col-sm-6 loadcompleted" id="' + linkId + "-" + widgetId + '" data-name="' + name + '" data-mode="open"></li>');
								widgetContainer.data('url', url);
								var width = element.data('width');
								var height = element.data('height');
								Head_DashBoard_Js.gridster.add_widget(widgetContainer, width, height);
								Head_DashBoard_Js.currentInstance.loadWidget(widgetContainer);
							}
						});
						return false;
					}
				}
				form.vtValidate(params);
			}
			app.helper.showModal(res, {
				"cb": callback
			});
		});

	}

}, {


	container: false,
	instancesCache: {},

	init: function () {
		Head_DashBoard_Js.currentInstance = this;
		this.addComponents();
	},

	addComponents: function () {
		this.addComponent('Head_Index_Js');
	},

	getDashboardContainer: function () {
		return jQuery(".dashBoardContainer");
	},

	getContainer: function (tabid) {
		if (typeof tabid == 'undefined') {
			tabid = this.getActiveTabId();
		}
		return jQuery(".gridster_" + tabid).find('ul');
	},

	getWidgetInstance: function (widgetContainer) {
		var id = widgetContainer.attr('id');
		if (!(id in this.instancesCache)) {
			var widgetName = widgetContainer.data('name');
			if (widgetName === "ChartReportWidget") {
				widgetName += "_" + id;
			}
			this.instancesCache[id] = Head_Widget_Js.getInstance(widgetContainer, widgetName);
		} else {
			this.instancesCache[id].init(widgetContainer);
		}
		return this.instancesCache[id];
	},

	getActiveTabId: function () {
		return jQuery(".tab-pane.active").data("tabid");
	},

	getActiveTabName: function () {
		return jQuery(".tab-pane.active").data("tabname");
	},

	getgridColumns: function () {
		var _device_width = $(window).innerWidth();
		var gridWidth = _device_width;

		if (_device_width < 480) {
			gridWidth = 1;
		} else if (_device_width >= 480 && _device_width < 768) {
			gridWidth = 1;
		} else if (_device_width >= 768 && _device_width < 992) {
			gridWidth = 2;
		} else if (_device_width >= 992 && _device_width < 1440) {
			gridWidth = 3;
		} else {
			gridWidth = 4;
		}
		return gridWidth;
	},

	registerGridster: function () {
		var thisInstance = this;
		var widgetMargin = 10;
		var activeTabId = this.getActiveTabId();
		var activeGridster = jQuery(".gridster_" + activeTabId);
		var items = activeGridster.find('ul li');
		items.detach();

		// Constructing the grid based on window width
		var cols = this.getgridColumns();
		$(".mainContainer").css('min-width', "500px");
		var col_width = (cols === 1) ? (Math.floor(($(".mainContainer").width() - 41) / cols) - (2 * widgetMargin)) : (Math.floor(($(window).width() - 140) / cols) - (2 * widgetMargin));


		col_width = 710;
		Head_DashBoard_Js.gridster = this.getContainer().gridster({
			widget_margins: [widgetMargin, widgetMargin],
			widget_base_dimensions: [col_width, 'auto'],
			min_cols: 1,
			max_cols: 4,
			min_rows: 20,
			resize: {
				enabled: true,
				stop: function (e, ui, widget) {
					var widgetName = widget.data('name');
					/**
					 * we are setting default height in DashBoardWidgetContents.tpl
					 * need to overwrite based on resized widget height
					 */
					var widgetChartContainer = widget.find(".widgetChartContainer");
					if (widgetChartContainer.length > 0) {
						widgetChartContainer.css("height", widget.height() - 60);
					}
					widgetChartContainer.html('');
					Head_Widget_Js.getInstance(widget, widgetName);
					widget.trigger(Head_Widget_Js.widgetPostResizeEvent);
				}
			},
			draggable: {
				'stop': function (event, ui) {
					thisInstance.savePositions(activeGridster.find('.dashboardWidget'));
				}
			}
		}).data('gridster');


		items.sort(function (a, b) {
			var widgetA = jQuery(a);
			var widgetB = jQuery(b);
			var rowA = parseInt(widgetA.attr('data-row'));
			var rowB = parseInt(widgetB.attr('data-row'));
			var colA = parseInt(widgetA.attr('data-col'));
			var colB = parseInt(widgetB.attr('data-col'));

			if (rowA === rowB && colA === colB) {
				return 0;
			}

			if (rowA > rowB || (rowA === rowB && colA > colB)) {
				return 1;
			}
			return -1;
		});
		jQuery.each(items, function (i, e) {
			var item = $(this);
			var columns = parseInt(item.attr("data-sizex")) > cols ? cols : parseInt(item.attr("data-sizex"));
			var rows = parseInt(item.attr("data-sizey"));
			if (item.attr("data-position") == "false") {
				Head_DashBoard_Js.gridster.add_widget(item, columns, rows);
			} else {
				Head_DashBoard_Js.gridster.add_widget(item, columns, rows);
			}
		});
		//used when after gridster is loaded
		thisInstance.savePositions(activeGridster.find('.dashboardWidget'));
	},

	savePositions: function (widgets) {
		var widgetRowColPositions = {}
		for (var index = 0, len = widgets.length; index < len; ++index) {
			var widget = jQuery(widgets[index]);
			widgetRowColPositions[widget.attr('id')] = JSON.stringify({
				row: widget.attr('data-row'),
				col: widget.attr('data-col')
			});
		}
		var params = {
			module: 'Head',
			action: 'SaveWidgetPositions',
			positionsmap: widgetRowColPositions
		};
		app.request.post({
			"data": params
		}).then(function (err, data) {});
	},

	getDashboardWidgets: function () {
		return jQuery('.dashboardWidget', jQuery('.tab-pane.active'));
	},

	loadWidgets: function () {
		var thisInstance = this;
		var widgetList = thisInstance.getDashboardWidgets();
		widgetList.each(function (index, widgetContainerELement) {
			if (thisInstance.isScrolledIntoView(widgetContainerELement)) {
				thisInstance.loadWidget(jQuery(widgetContainerELement));
				jQuery(widgetContainerELement).addClass('loadcompleted');
			}
		});
	},

	isScrolledIntoView: function (elem) {
		var viewportWidth = jQuery(window).width(),
			viewportHeight = jQuery(window).height(),

			documentScrollTop = jQuery(document).scrollTop(),
			documentScrollLeft = jQuery(document).scrollLeft(),

			minTop = documentScrollTop,
			maxTop = documentScrollTop + viewportHeight,
			minLeft = documentScrollLeft,
			maxLeft = documentScrollLeft + viewportWidth,

			$targetElement = jQuery(elem),
			elementOffset = $targetElement.offset();
		if (
			(elementOffset.top > minTop && elementOffset.top < maxTop) &&
			(elementOffset.left > minLeft && elementOffset.left < maxLeft)
		) {
			return true;
		} else {
			return false;
		}
	},

	loadWidget: function (widgetContainer) {
		var thisInstance = this;
		var urlParams = widgetContainer.data('url');
		var mode = widgetContainer.data('mode');

		var activeTabId = this.getActiveTabId();
		urlParams += "&tab=" + activeTabId;
		app.helper.showProgress();
		if (mode == 'open') {
			app.request.post({
				"url": urlParams
			}).then(function (err, data) {
				widgetContainer.prepend(data);
				vtUtils.applyFieldElementsView(widgetContainer);
				thisInstance.getWidgetInstance(widgetContainer);
				widgetContainer.trigger(Head_Widget_Js.widgetPostLoadEvent);
				app.helper.hideProgress();
			});
		} else {}
	},

	registerRefreshWidget: function () {
		var thisInstance = this;
		this.getContainer().on('click', 'a[name="drefresh"]', function (e) {
			var element = $(e.currentTarget);
			var parent = element.closest('li');
			var widgetInstnace = thisInstance.getWidgetInstance(parent);
			widgetInstnace.refreshWidget();
			return;
		});
	},

	removeWidget: function () {
		this.getContainer().on('click', 'li a[name="dclose"]', function (e) {
			var element = $(e.currentTarget);
			var listItem = jQuery(element).parents('li');
			var width = listItem.attr('data-sizex');
			var height = listItem.attr('data-sizey');

			var url = element.data('url');
			var parent = element.closest('.dashBoardWidgetFooter').parent();
			var widgetName = parent.data('name');
			var widgetTitle = parent.find('.dashboardTitle').attr('title');
			var activeTabId = element.closest(".tab-pane").data("tabid");

			var message = app.vtranslate('JS_ARE_YOU_SURE_TO_DELETE_WIDGET', widgetTitle);
			app.helper.showConfirmationBox({
				'message': message,
				'htmlSupportEnable': false
			}).then(function (e) {
				app.helper.showProgress();
				app.request.post({
					"url": url
				}).then(
					function (err, response) {
						if (err == null) {

							var nonReversableWidgets = ['MiniList', 'Notebook', 'ChartReportWidget']

							parent.fadeOut('slow', function () {
								Head_DashBoard_Js.gridster.remove_widget(parent);
								parent.remove();
							});
							if (jQuery.inArray(widgetName, nonReversableWidgets) == -1) {
								var data = '<li><a onclick="Head_DashBoard_Js.addWidget(this, \'' + response.url + '\')" href="javascript:void(0);"';
								data += 'data-width=' + width + ' data-height=' + height + ' data-linkid=' + response.linkid + ' data-name=' + response.name + '>' + response.title + '</a></li>';
								var divider = jQuery('.widgetsList .divider', '#tab_' + activeTabId);
								if (divider.length) {
									jQuery(data).insertBefore(divider);
								} else {
									jQuery(data).insertAfter(jQuery('.widgetsList li:last', '#tab_' + activeTabId));
								}
							}
						}
						app.helper.hideProgress();
					}
				);
			});
		});
	},

	registerLazyLoadWidgets: function () {
		var thisInstance = this;
		jQuery(window).bind("scroll", function () {
			var widgetList = jQuery('.dashboardWidget').not('.loadcompleted');
			if (!widgetList[0]) {
				// We shouldn't unbind as we might have widgets in another tab
				//jQuery(window).unbind('scroll');
			}
			widgetList.each(function (index, widgetContainerELement) {
				if (thisInstance.isScrolledIntoView(widgetContainerELement)) {
					thisInstance.loadWidget(jQuery(widgetContainerELement));
					jQuery(widgetContainerELement).addClass('loadcompleted');
				}
			});
		});
	},

	registerWidgetFullScreenView: function () {
		var thisInstance = this;
		this.getContainer().on('click', 'a[name="widgetFullScreen"]', function (e) {
			var currentTarget = jQuery(e.currentTarget);
			var widgetContainer = currentTarget.closest('li');
			var widgetName = widgetContainer.data('name');
			var widgetTitle = widgetContainer.find('.dashboardTitle').text();
			var widgetId = widgetContainer.attr('id');
			var data = widgetContainer.find('input.widgetData').val();
			var chartType = '';
			if (widgetContainer.find('input[name="charttype"]').length) {
				chartType = widgetContainer.find('input[name="charttype"]').val();
			}
			var clickThrough = 0;
			if (widgetContainer.find('input[name="clickthrough"]').length) {
				clickThrough = widgetContainer.find('input[name="clickthrough"]').val();
			}
			var fullscreenview = '<div class="fullscreencontents modal-dialog modal-lg">\n\
									<div class="modal-content">\n\
									<div class="modal-header backgroundColor">\n\
										<div class="clearfix">\n\
											<div class="row">\n\
											<h4 class="col-sm-10">' + widgetTitle + '</h4>\n\
											<button data-dismiss="modal" class="close btn-close  pull-right" title="' + app.vtranslate('JS_CLOSE') + '"><span aria-hidden="true" class="fa fa-close"></span></button>\n\
											</div>\n\
										</div>\n\
									</div>\n\
									<div class="modal-body" style="overflow:auto;">\n\
										<ul style="list-style: none;"><li id="fullscreenpreview" class="dashboardWidget fullscreenview" data-name="' + widgetName + '">\n\
											<div class="dashboardWidgetContent" style="min-height:500px;width:100%;min-width:600px; margin: 0 auto" data-displaymode="fullscreen">';
			if (chartType != '') {
				fullscreenview += ' <input type="hidden" value="' + chartType + '" name="charttype">\n\
												<input type="hidden" value="' + clickThrough + '" name="clickthrough">\n\
												<div id="chartDiv" name="chartcontent" style="width:100%;height:100%" data-mode="preview"></div> \n\
												<input class="widgetData" type="hidden" value="" name="data">';
			} else {
				fullscreenview += ' <div class="dashboardWidgetContent " style="width:100%;height:100%" data-displaymode="fullscreen">\n\
													<div id="chartDiv" class="widgetChartContainer" style="width:100%;height:100%"></div>\n\
														<input class="widgetData" type="hidden" value="" name="data">';
			}
			fullscreenview += '</div></ul></li></div></div></div>';

			var callback = function (modalData) {
				var element = jQuery(modalData);
				var modal = jQuery(".myModal", element);
				modal.parent().css({
					'top': '30px',
					'left': '30px',
					'right': '30px',
					'bottom': '30px'
				});
				modal.css('height', 'auto');
				var modalWidgetContainer = jQuery('.fullscreenview');
				modalWidgetContainer.find('.widgetData').val(data);
				if (chartType != '') {
					//Chart report widget 
					var chartClassName = chartType.toCamelCase();
					var chartClass = window["Report_" + chartClassName + "_Js"];
					chartClass('Head_ChartReportWidget_Widget_Js', {}, {
						init: function () {
							this._super(modalWidgetContainer);
						}
					});
				}
				var widgetInstance = Head_Widget_Js.getInstance(modalWidgetContainer, widgetName);
				modalWidgetContainer.trigger(Head_Widget_Js.widgetPostLoadEvent);
			}
			app.helper.showModal(fullscreenview, {
				"cb": callback
			});
		});
	},

	registerFilterInitiater: function () {
		var container = this.getContainer();
		container.on('click', 'a[name="dfilter"]', function (e) {
			var widgetContainer = jQuery(e.currentTarget).closest('.dashboardWidget');
			var filterContainer = widgetContainer.find('.filterContainer');
			var dashboardWidgetFooter = jQuery('.dashBoardWidgetFooter', widgetContainer);

			filterContainer.slideToggle(500);

			var callbackFunction = function () {
				filterContainer.slideToggle(500);
			}
			//adding clickoutside event on the dashboardWidgetHeader
			var helper = new Head_Helper_Js();
			helper.addClickOutSideEvent(dashboardWidgetFooter, callbackFunction);

			return false;
		})
	},

	registerDeleteDashboardTab: function () {
		var self = this;
		var dashBoardContainer = this.getDashboardContainer();
		dashBoardContainer.off("click", '.deleteTab');
		dashBoardContainer.on("click", '.deleteTab', function (e) {
			// To prevent tab click event
			e.preventDefault();
			e.stopPropagation();

			var currentTarget = jQuery(e.currentTarget);
			var tab = currentTarget.closest(".dashboardTab");

			var tabId = tab.data("tabid");
			var tabName = tab.data("tabname");
			var message = app.vtranslate('JS_ARE_YOU_SURE_TO_DELETE_DASHBOARDTAB', tabName);
			app.helper.showConfirmationBox({
				'message': message,
				'htmlSupportEnable': false
			}).then(function (e) {
				app.helper.showProgress();
				var data = {
					'module': 'Head',
					'action': 'DashBoardTab',
					'mode': 'deleteTab',
					'tabid': tabId
				}

				app.request.post({
					"data": data
				}).then(function (err, data) {
					app.helper.hideProgress();
					if (err == null) {
						jQuery('li[data-tabid="' + tabId + '"]').remove();
						jQuery('.tab-content #tab_' + tabId).remove();

						if (jQuery('.dashboardTab.active').length <= 0) {
							// click the first tab if none of the tabs are active
							var firstTab = jQuery('.dashboardTab').get(0);
							jQuery(firstTab).find('a').click();
						}


						app.helper.showSuccessNotification({
							"message": ''
						});
						if (jQuery('.dashboardTab').length < Head_DashBoard_Js.dashboardTabsLimit) {
							var element = dashBoardContainer.find('li.disabled');
							self.removeQtip(element);
						}

					} else {
						app.helper.showErrorNotification({
							"message": err
						});
					}
				});
			});
		});
	},

	removeQtip: function (element) {
		jQuery(element).qtip("destroy");
		element.removeClass('disabled');
	},

	registerQtipMessage: function () {
		var dashBoardContainer = this.getDashboardContainer();
		var element = dashBoardContainer.find('li.disabled');
		var title = app.vtranslate("JS_TABS_LIMIT_EXCEEDED")
		jQuery(element).qtip({
			content: title,
			hide: {
				event: 'click mouseleave',
			},
			position: {
				my: 'bottom center',
				at: 'top left',
				adjust: {
					x: 30,
					y: 10
				}
			},
			style: {
				classes: 'qtip-dark'
			}
		});
	},
	registerDashBoardTabRename: function () {
		var container = this.getContainer();
		var dashBoardContainer = jQuery(container).closest(".dashBoardContainer");

		dashBoardContainer.on("dblclick", ".dashboardTab", function (e) {
			e.preventDefault();
			e.stopPropagation();

			var currentTarget = jQuery(e.currentTarget);
			if (jQuery(".editTabName:visible").length > 0) {
				return;
			}
			var nameEle = currentTarget.find(".name");
			var oldName = nameEle.attr("value");
			var editEle = currentTarget.find(".editTabName");

			nameEle.addClass("hide");
			editEle.removeClass("hide");
			editEle.find("input").val(oldName);

			currentTarget.on("clickoutside", function (e) {
				var newName = editEle.find("input").val();
				var tabId = currentTarget.data("tabid");

				if (newName.trim() == "") {
					vtUtils.showValidationMessage(editEle, app.vtranslate('JS_TAB_NAME_SHOULD_NOT_BE_EMPTY'), {
						position: {
							my: 'top left',
							at: 'bottom left',
							container: editEle.closest('.dashboardTab')
						}
					});
					return false;
				}
				vtUtils.hideValidationMessage(editEle);

				if (newName.length > 50) {
					vtUtils.showValidationMessage(editEle, app.vtranslate('JS_TAB_LABEL_EXCEEDS_CHARS', 50), {
						position: {
							my: 'bottom left',
							at: 'top left',
							container: jQuery('.module-action-content')
						}
					});
					return false;
				} else {
					vtUtils.hideValidationMessage(editEle);
				}
				currentTarget.off("clickoutside");
				if (newName != oldName) {

					var data = {
						'module': 'Head',
						'action': 'DashBoardTab',
						'mode': 'renameTab',
						'tabid': tabId,
						'tabname': newName
					}
					currentTarget.find('.name > strong').text(newName);
					app.helper.showProgress();
					app.request.post({
						data: data
					}).then(function (err, data) {
						app.helper.hideProgress();
						if (err == null) {
							app.helper.showSuccessNotification({
								"message": ''
							});
							currentTarget.data('tabname', newName);
						} else {
							app.helper.showErrorNotification({
								"message": err
							});
							currentTarget.find('.name > strong').text(oldName);
						}
					})
				}
				nameEle.attr("value", newName);

				editEle.addClass("hide");
				nameEle.removeClass("hide");
			})
		});
	},

	registerDashBoardTabClick: function () {
		var site_url = jQuery('#joforce_site_url').val();
		var thisInstance = this;
		var container = this.getContainer();
		var dashBoardContainer = jQuery(container).closest(".dashBoardContainer");

		dashBoardContainer.on("shown.bs.tab", ".dashboardTab", function (e) {
			var currentTarget = jQuery(e.currentTarget);
			var tabid = currentTarget.data('tabid');
			app.changeURL(site_url + "Home/DashBoard/" + tabid);

			// If tab is already loaded earlier then we shouldn't reload tab and register gridster
			if (typeof jQuery("#tab_" + tabid).find(".dashBoardTabContainer").val() !== 'undefined') {
				// We should overwrite gridster with current tab which is clicked

				var widgetMargin = 10;
				var cols = thisInstance.getgridColumns();
				$(".mainContainer").css('min-width', "500px");
				var col_width = (cols === 1) ? (Math.floor(($(".mainContainer").width() - 41) / cols) - (2 * widgetMargin)) : (Math.floor(($(window).width() - 41) / cols) - (2 * widgetMargin));

				Head_DashBoard_Js.gridster = thisInstance.getContainer(tabid).gridster({
					// Need to set the base dimensions to eliminate widgets overlapping
					widget_base_dimensions: [col_width, 300]
				}).data("gridster");

				return;
			}
			var data = {
				'module': 'Home',
				'view': 'DashBoardTab',
				'mode': 'getTabContents',
				'tabid': tabid
			}

			app.request.post({
				"data": data
			}).then(function (err, data) {
				if (err === null) {
					var dashBoardModuleName = jQuery("#tab_" + tabid, ".tab-content").html(data).find('[name="dashBoardModuleName"]').val();
					if (typeof dashBoardModuleName != 'undefined' && dashBoardModuleName.length > 0) {
						var dashBoardInstanceClassName = app.getModuleSpecificViewClass(app.view(), dashBoardModuleName);
						if (dashBoardInstanceClassName != null) {
							var dashBoardInstance = new window[dashBoardInstanceClassName]();
						}
					}
					app.event.trigger("post.DashBoardTab.load", dashBoardInstance);
				}
			});
		});
	},

	registerRearrangeTabsEvent: function () {
		var dashBoardContainer = this.getDashboardContainer();

		// on click of Rearrange button
		dashBoardContainer.on("click", 'ul.moreDashBoards .reArrangeTabs', function (e) {
			var currentEle = jQuery(e.currentTarget);
			dashBoardContainer.find(".dashBoardDropDown").addClass('hide');

			var sortableContainer = dashBoardContainer.find(".tabContainer");
			var sortableEle = sortableContainer.find(".sortable");

			currentEle.addClass("hide");
			dashBoardContainer.find(".deleteTab").addClass("hide");
			dashBoardContainer.find(".moveTab").removeClass("hide");
			dashBoardContainer.find(".updateSequence").removeClass("hide");

			sortableEle.sortable({
				'containment': sortableContainer,
				stop: function () {}
			});
		});

		// On click of save sequence
		dashBoardContainer.find(".updateSequence").on("click", function (e) {
			var reArrangedList = {};
			var currEle = jQuery(e.currentTarget);
			jQuery(".sortable li").each(function (i, el) {
				var el = jQuery(el);
				var tabid = el.data("tabid");
				reArrangedList[tabid] = ++i;
			});

			var data = {
				"module": "Head",
				"action": "DashBoardTab",
				"mode": "updateTabSequence",
				"sequence": JSON.stringify(reArrangedList)
			}

			app.request.post({
				"data": data
			}).then(function (err, data) {
				if (err == null) {
					currEle.addClass("hide");
					dashBoardContainer.find(".moveTab").addClass("hide");
					dashBoardContainer.find(".reArrangeTabs").removeClass("hide");
					dashBoardContainer.find(".deleteTab").removeClass("hide");
					dashBoardContainer.find(".dashBoardDropDown").removeClass('hide');

					var sortableEle = dashBoardContainer.find(".tabContainer").find(".sortable");
					sortableEle.sortable('destroy');

					app.helper.showSuccessNotification({
						"message": ''
					});
				} else {
					app.helper.showErrorNotification({
						"message": err
					});
				}
			});
		});

	},

	registerEvents: function () {
		var thisInstance = this;
		this.registerLazyLoadWidgets();
		this.registerDashBoardTabClick();
		this.registerDashBoardTabRename();
		this.registerDeleteDashboardTab();
		this.registerRearrangeTabsEvent();
		this.registerQtipMessage();
		app.event.off("post.DashBoardTab.load");
		app.event.on("post.DashBoardTab.load", function (event, dashBoardInstance) {
			var instance = thisInstance;
			if (typeof dashBoardInstance != 'undefined') {
				instance = dashBoardInstance;
				instance.registerEvents();
				return;
			}
			instance.registerGridster();
			instance.loadWidgets();
			instance.registerRefreshWidget();
			instance.removeWidget();
			instance.registerWidgetFullScreenView();
			instance.registerFilterInitiater();
		});
		app.event.trigger("post.DashBoardTab.load");
		var gridcontainer = jQuery(".gridster_2").find('ul').find('li');
		if (gridcontainer.length == '0') {
			jQuery(".gridster_2").find('ul').height(500);
		}
	}
});