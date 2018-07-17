/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_List_Js("Home_List_Js",{},{

});

$(document).on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {                
            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  
        }
    });
});

$(document).ready(function()	{
	$('[data-toggle="popover"]').popover();
	$('.app-nav .module-action-bar').css('height','0px');
	$('.joforce-box').css('margin-top','10px');
	$('footer.app-footer').css("position","absolute").css("width","100%").css("bottom","0px");
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
	if($(this).hasClass('current'))
	{
		var id = $(this).attr('id');
		var site_url = $("#joforce_site_url") .val();
	        var count = $("#"+id).find('.count').html();
        	var module = $("#"+id).data('module');

		if(count > 5) {
			$("#"+id).find('.count').html(count - 5);
		}
		else {
			$("#"+id).find('.count').html('0');
			$("#"+id).find('.count').addClass('zero');
		}

		var params = {};
		params['module'] = 'Home';
		params['action'] = 'ClearNotifications';
		params['moduleName'] = module;

		app.request.post({'data': params}).then(function (err, data) {
	            app.helper.hideProgress();
		});
	
		if(module=="Calendar" || module=="Events" || module=="Tasks")
			var list_url = "Calendar/view/List";
		else
			var list_url = module+"/view/List";

		if( ($("#"+id).find('.count').html()) == 0 )
		{
			url= site_url+list_url;
			$("#"+id).attr('href', url);
			$('#'+id).popover('destroy');
		}
		$("#"+id).removeClass('current');
	}
	});
});
