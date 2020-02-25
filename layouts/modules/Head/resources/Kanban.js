/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_List_Js("Head_Kanban_Js", {}, {

    getListViewContainer : function() {
        return jQuery('#taskManagementContainer');
    },

    registerButtonClicks : function () {
	$("#forecast-view").click( function() {
		var site_url = jQuery('#joforce_site_url').val();
		var cvid = $(this).data('cvid');
		var module_name = $(this).data('modulename');
		var pagenumber = $('#pageNumber').val();
                window.location.href = site_url + module_name+"/view/Kanban/" + cvid + '?page=' + pagenumber;
	});

	$("#backto-list-view").click( function() {
                var site_url = jQuery('#joforce_site_url').val();
		var cvid = $(this).data('cvid');
		var module_name = $(this).data('modulename');
                window.location.href = site_url + module_name+"/view/List/" + cvid;
        });
    },

    registerChangeCustomViewEvent: function () {
        var thisInstance = this;
        var sourceModuleName = jQuery('#source_module_name').val();
        var moduleName = app.getModuleName();
	var site_url = $('#joforce_site_url').val();

        jQuery('#moduleFilters').change(function () {
            viewId = jQuery(this).val();
            var content = thisInstance.getListViewContainer();

            app.helper.showProgress();
            var params = {
                'module': moduleName,
                'view': 'Kanban',
                'sourceModule': sourceModuleName,
                'viewname': viewId
            };

            app.request.post({data:params}).then(function (error, response) {
                if(error === null) {
                    content.empty();
                    content.html(response);
                    app.helper.hideProgress();

		    urlPath = site_url + moduleName + '/view/Kanban/'+viewId+'?page=1';
		    window.history.pushState({},"", urlPath); //Change the browser-URL without reloading the page

                    thisInstance.triggerListSortable();
                    thisInstance.registerChangeCustomViewEvent();
		    thisInstance.changeTextColor();
                }
            });
        });
    },

    /**
     * Function to register the drag and drop functionality
     **/
     triggerListSortable : function() {
	    $( ".draggable" ).sortable({
	        connectWith: ".draggable",
        	cursor: "move",
	        items: "li:not(.ui-state-disabled)",
        	cancel: ".ui-state-disabled",
		cursorAt: { top: 52, left: 107 },
	        over: function() {
        	    $(this).addClass('droppable-bg-sales-stages');
	        },
	        out: function(event) {
        	    $('#'+event.target.id).removeClass('droppable-bg-sales-stages');
	        },
        	receive: function( event, ui ) {
	            // Disable the Dropped LI until stage updated
        	    ui.item.addClass('ui-state-disabled');
	            var target_id = event.target.id;
        	    var new_stage = target_id.split('-');
	            var recd_id = ui.item.attr('data-potential-id');  //potential id
		    var amount = parseInt($("#amount-"+recd_id).html() , 10); 
		    var new_stage_id = new_stage[1];  // new sales stage
		    var old_stage_id = ui.item.attr('data-stageid'); 
		    var site_url = jQuery('#joforce_site_url').val();
		    var module_name = $('#module_name').val();
		    var picklist_name = $('#picklist_name').val();
		    var picklist_id = $('#picklist_id').val();

		    var params = {
			    'module': app.getModuleName(),
			    'action': 'UpdatePicklistValue',
			    'record_id': recd_id,
			    'picklist_name': picklist_name,
			    'picklist_id': picklist_id,
			    'old_stage_id': old_stage_id,
			    'new_stage_id': new_stage_id
	            };

		    ui.item.removeClass('ui-state-disabled'); // Remove the blur effect while drag and drop

	            app.request.post({data:params}).then(function (error, data) {
        	        if(error === null){
			    $("#sortlist-"+recd_id).attr("data-stageid", new_stage_id);
			    new_stage_color = $('#forecast-div-'+new_stage_id).data('color');

			    if (new_stage_color != null && new_stage_color !== undefined && new_stage_color != '') {
			    	$("#sortlist-"+recd_id).css({'border' : '2px solid '+new_stage_color});
			    } else {
				$("#sortlist-"+recd_id).css({'border' : '2px solid #d2d6de'});
			    }
                            // Remove the disabled state of LI
                            ui.item.removeClass('ui-state-disabled');
                	} else {
                            // Remove the disabled state of LI
                            ui.item.removeClass('ui-state-disabled');
                            var error_message = '';
                            var status_code = data.status;
                            var errors = data.responseJSON;
                            $.each(errors, function (index, value) {
                                error_message += value;
                            });
                            // Show Notification
                            notify('', error_message, 'danger');
			}
	            });
	        },
	    });
	},

    getTotalCount : function() {
        $(document).on('click', '.showTotalCountIcon_kanban', function() {
            params = {
                module: app.getModuleName(),
                source_module: jQuery('#source_module_name').val(),
                view: 'KanbanAjax',
                mode: 'getListViewCount',
                cvid : $('#moduleFilters').val(),
            };

            app.request.get({data:params}).then(function (err, data) {
                var response;
                if (typeof data !== "object") {
                    response = JSON.parse(data);
                } else {
                    response = data;
                }
                $('.showTotalCountIcon_kanban').hide();
                $('.toalcount_value').html(data.count);
            });
        });
    },

    goToNextOrPrevious: function () {
        $('#PreviousPageButton_kanban').click( function() {
	    var site_url = jQuery('#joforce_site_url').val();
            exist = $('#previousPageExist').val();
            if(exist) {
                next_page = parseInt($('#pageNumber').val()) - 1;
                source_module = $('#source_module_name').val();
		window.location.href = site_url+source_module+'/view/Kanban/10?page='+next_page;
            }
        });

        $('#NextPageButton_kanban').click( function() {
	    var site_url = jQuery('#joforce_site_url').val();
            exist = $('#nextPageExist').val();
            if(exist) {
                next_page = parseInt($('#pageNumber').val()) + 1;
                source_module = $('#source_module_name').val();
		window.location.href = site_url+source_module+'/view/Kanban/10?page='+next_page;
            }
        });
    },

    jumpToPage: function() {
        $(document).on('click','#PageJump_kanban',function() {
            total_pages = $('#total_pages').val();
            $('#totalPageCount_kanban').html(total_pages);
        });

        $(document).on('click','#pageToJumpSubmit_kanban', function() {
            input_value = $('#pageToJump_kanban').val();
            current_element = $('#pageToJump_kanban');
            total_pages = $('#total_pages').val();
            var negativeRegex= /(^[-]+\d+)$/ ;

            if(input_value == 0 || input_value == null) {
                var errorInfo = app.vtranslate('JS_VALUE_SHOULD_BE_GREATER_THAN_ZERO');
                vtUtils.showValidationMessage(current_element, errorInfo, {
                    position : {
                        my: 'top left',
                        at: 'bottom left',
                        container: current_element.closest('.listViewBasicAction')
                    }
               });
               return false;
            } else if (isNaN(input_value) || input_value < 0 || input_value.match(negativeRegex)) {
                errorInfo = app.vtranslate('JS_ACCEPT_POSITIVE_NUMBER');
                vtUtils.showValidationMessage(current_element, errorInfo, {
                    position : {
                        my: 'top left',
                        at: 'bottom left',
                        container: current_element.closest('.listViewBasicAction')
                    }
               });
               return false;
            } else if(input_value > total_pages) {
                    app.helper.showErrorNotification({message: app.vtranslate('JS_PAGE_NOT_EXIST')});
                    return false;
            } else {
                source_module = $('#source_module_name').val();
		var site_url = jQuery('#joforce_site_url').val();
		window.location.href = site_url+source_module+'/view/Kanban?page='+input_value;
            }
        });
        $(document).on('click','#PageJumpDropDown_kanban', function(e) {
            e.stopPropagation();
            e.preventDefault();
        });
    },

    changeTextColor : function () {
	$('.forecast-div').each( function () {
	    color = $(this).data('color');
	    if(color != null && color !== undefined && color !== '') {
		picklistid = $(this).data('pickid');
		tobbar_id = 'pipe_stage_'+picklistid;
		$('#'+tobbar_id).css("color", isDark($('#'+tobbar_id).css("background")) ? 'white' : 'black');
	    }
	});
    },
    /**
     * Function which will register all the events
     **/
    registerEvents: function () {
	var thisInstance = this;
        thisInstance.registerButtonClicks();
	thisInstance.triggerListSortable();
        thisInstance.registerChangeCustomViewEvent();
        var params = {
            autoHideScrollbar: false
        };
        app.helper.showVerticalScroll(jQuery('.scrollable'),params);
        thisInstance.getTotalCount();
        thisInstance.goToNextOrPrevious();
        thisInstance.jumpToPage();
	thisInstance.changeTextColor();
    }
});


function isDark( color ) {
    var match = /rgb\((\d+).*?(\d+).*?(\d+)\)/.exec(color);
    return parseFloat(match[1])
         + parseFloat(match[2])
         + parseFloat(match[3])
           < 3 * 256 / 2; // r+g+b should be less than half of max (3 * 256)
}
