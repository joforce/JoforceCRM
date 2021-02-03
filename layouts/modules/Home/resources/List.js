/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head.Class("Home_List_Js",{},{

    getContainer : function() {
        return jQuery('#global-notification-dropdown');
    },

    getModuleName: function () {
        return 'Home';
    },

    registerNotificationShowEvent : function () {
	var thisInstance = this;
	$(document).on('click', '.global-notification-toggle', function () {
	    var params = {
		'module' : 'Home',
		'view' : 'ShowNotifications',
	    }

            app.request.get({"data":params}).then(function(err,data){
                if(err === null){
                    $('#global-notification-dropdown').html(data);
                    thisInstance.registerNotificationMarkSeenEvent();
                }
            });
        });
    },

    registerNotificationMarkSeenEvent : function() {
	$(document).on('click', '#global-notification-dropdown', function(e) {
            e.stopPropagation();
            e.preventDefault();
        });

	$(document).on('click', '#global-notification-dropdown a', function(e) {
            window.location.href = $(this).attr('href');
        });
	$('#global-notification-dropdown .mark-as-seen').on('click', function(e){
	    id = $(this).attr('id');
	    element = $('#'+id);
            if(element.hasClass('seen_notification')) {
                app.helper.showSuccessNotification({"message":'Already Marked as Seen'});
            } else {
                notification_id = element.data('notificationid');
                notify_ids = {};
                notify_ids[0] = notification_id;
                params = {
                        'module' : 'Home',
                        'action' : 'UpdateNotifications',
                        'mark_all' : false,
                        'notify_ids' : notify_ids
                };

                app.request.post({"data":params}).then(function(err,data){
                    if(err === null && data.success == true) {
                        element.addClass('seen_notification');
                        $('#notification_number_'+notification_id).css('background', '#f0f0f0');
			noti_count_value = (parseInt($('.notification_count').text()) - 1);
			if(noti_count_value == 0) {
			    $('.notification_count').hide();
			} else {
			    $('.notification_count').html(noti_count_value);
			}
                        app.helper.showSuccessNotification({"message":'Marked as Seen'});
                    } else {
                        app.helper.showErrorNotification({"message":'Something went wrong'});
                    }
                });
            }
        });
    },

    /**
     *  Mark all notifications as read
     **/
    MarkAllNotificationsAsRead : function() {
	$('#global-notification-dropdown').on('click', '#mark-all-as-read', function () {
            con_message = 'Are you sure you want to mark all as read?';
            app.helper.showConfirmationBox({'message': con_message}).then(function () {
                params = {
                        'module' : 'Home',
                        'action' : 'UpdateNotifications',
                        'mark_all' : true,
                };
                app.request.post({"data":params}).then(function(err,data){
                    if(err === null && data.success == true) {
			$('.global-notifications').each(function() {
			    notification_id = $(this).data('notificationid');
			    $('#notification_number_'+notification_id).css('background', '#f0f0f0');

			    if($(this).hasClass('.seen_notification') == false) {
				$('#noti_marker_'+notification_id).addClass('seen_notification');
			    }
			});
			$('.notification_count').hide();
                        app.helper.showSuccessNotification({"message":'Marked All notifications as Read!'});
                    } else {
                        app.helper.showErrorNotification({"message":'Something went wrong'});
                    }
                });
            });
        });
    },

    /**
     *  Delete all notifications
     **/
    clearAllNotifications : function() {
	$('#global-notification-dropdown').on('click', '#clear-all-notifications', function () {
	    con_message = 'Are you sure you want to clear all notifications?';
	    app.helper.showConfirmationBox({'message': con_message}).then(function () {
		params = {
                        'module' : 'Home',
                        'action' : 'ClearNotifications',
                        'moduleName' : 'All',
                };

                app.request.post({"data":params}).then(function(err,data){
                    if(err === null && data.success == true) {
			site_url = $('#joforce_site_url').val()
			zero_notification_content = '<li class="empty_notification_li" style="margin: auto; width: 250px;height:250px;"> <img src="'+site_url+'layouts/skins/images/notification/ezgif.com-crop.gif" alt="No Notifications" class="empty_notification_image" id="empty_notification_image" />';
			$('#global-notification-dropdown').html(zero_notification_content);
			$('.notification_count').hide();
                        app.helper.showSuccessNotification({"message":'Cleared All notifications!'});
                    } else {
                        app.helper.showErrorNotification({"message":'Something went wrong'});
                    }
                });
	    });
	});
    },


    hidePopoverModal : function() {
	$(document).on('click', function (e) {
	    $('[data-toggle="popover"]').each(function () {
	        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) { 
        	    (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false
	        }
	    });
	});
    },

    registerPopoverAjaxEvents : function() {
	$(document).ready(function() {
	    $('[data-toggle="popover"]').popover();
	    $('.app-nav .module-action-bar').css('height','0px');
	    $('.joforce-box').css('margin-top','10px');
	    $('footer.app-footer').css("width","100%").css("bottom","0px");
	    $('#page').css("min-height","auto");

	    $(".notification-link").on('shown.bs.popover', function() {
		var id = $(this).attr('id');
		$("#"+id).addClass('current');
		var module = $(this).data('module');

		var params = {};
		params['module'] = 'Home';
		params['view'] = 'NotificationListAjax';
		params['moduleName'] = module;

		var viewed_notification = $('#viewed-notification-ids-'+module).val();
		app.request.get({'data': params}).then(function (err, data) {
		    app.helper.hideProgress();
		    $('.popover-content').html(data); 
		});
	    });

	    $('.notification-link').on('hidden.bs.popover', function () {
		/*if($(this).hasClass('current')) {
		    var id = $(this).attr('id'); var site_url = $("#joforce_site_url") .val(); var count = $("#"+id).find('.count').html(); var module = $("#"+id).data('module');

		    if(count > 5) $("#"+id).find('.count').html(count - 5);
		    else  $("#"+id).find('.count').html('');

		    var params = {'module':'Home','action':'ClearNotifications','moduleName':module};
		    app.request.post({'data': params}).then(function (err, data) {
		        app.helper.hideProgress();
		    });
	
		    if(module=="Calendar" || module=="Events" || module=="Tasks") var list_url = "Calendar/view/List";
		    else var list_url = module+"/view/List";

		    count_value = $("#"+id).find('.count').html();
		    if( count_value == 0 || count_value == '' || count_value == null) { url= site_url+list_url; $("#"+id).attr('href', url); $('#'+id).popover('destroy'); $("#"+id).find('.count').removeClass('count');
		    }
		    $("#"+id).removeClass('current');
	    	}*/
	    });
	});
    },

    registerShowAllNotifications : function () {
	var thisInstance = this;
	$(document).on('click', '#show-all-notifications', function (e) {
	    $('[data-toggle="popover"]').each(function (e) {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false
                }
            });
	    $('.global-notification-toggle').click();
	    e.stopPropagation();
	});
    },

    /**
     * Function to register events
     **/
    registerEvents : function() {
        var container = this.getContainer();
	//register Notifications event
	this.registerNotificationShowEvent();
	this.registerNotificationMarkSeenEvent();
	this.clearAllNotifications();
	this.MarkAllNotificationsAsRead();

        if($('.joforce-dash-container').length) {
            var dashboard_contaner = $('.joforce-dash-container');
            this.hidePopoverModal();
            this.registerPopoverAjaxEvents();
	    this.registerShowAllNotifications();

	    var Head = new Head_Index_Js();
	    Head.registerEvents();
        }
    }
});

$(document).ready(function () {
	if($('.joforce-dash-container').length < 1) {
		var NotificationsInstance = new Home_List_Js();
		NotificationsInstance.registerEvents();
	}
});
