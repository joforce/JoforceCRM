/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_List_Js("Potentials_List_Js",{})

triggerSortable();

$(document).ready(function() {
	
	triggerSortable();

});

function triggerSortable()
{
 $( ".draggable" ).sortable({
        connectWith: ".draggable",
        cursor: "move",
        items: "li:not(.ui-state-disabled)",
        cancel: ".ui-state-disabled",
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
            var potential_id = ui.item.attr('data-potential-id');  //potential id
	    var amount = parseInt($("#amount-"+potential_id).html() , 10); 
	    var new_stage_id = new_stage[1];  // new sales stage
	    var old_stage_id = ui.item.attr('data-stageid'); 
	    var old_column_count = parseInt($("#total_count-"+old_stage_id).html() , 10); 
	    var new_column_count = parseInt($("#total_count-"+new_stage_id).html() , 10); 
	    var old_column_amount = parseInt($("#total_amount-"+old_stage_id).html() , 10); 
	    var new_column_amount = parseInt($("#total_amount-"+new_stage_id).html() , 10); 
	    var site_url = jQuery('#joforce_site_url').val();
	    
	    
            $.ajax({
                type: 'POST',
                url: 'index.php?module=Potentials&action=UpdateSalesStage&potential_id=' +  potential_id + '&sales_stage_id=' + new_stage_id,
                data: {
		    site_url: site_url,
                    _method: 'PUT'
                },
                success: function (data) {
		
		//update the values
		$("#total_count-"+old_stage_id).html(old_column_count - 1);
		$("#total_count-"+new_stage_id).html(new_column_count + 1);
		$("#total_amount-"+old_stage_id).html(old_column_amount - amount);
		$("#total_amount-"+new_stage_id).html(new_column_amount + amount);
		if($("#total_count-"+old_stage_id).html() > 1)
			$("#opportunity-"+old_stage_id).html("Opportunities");
		else
			$("#opportunity-"+old_stage_id).html("Opportunity");

		if($("#total_count-"+new_stage_id).html() > 1)
                        $("#opportunity-"+new_stage_id).html("Opportunities");
                else
                        $("#opportunity-"+new_stage_id).html("Opportunity");
		
		$("#sortlist-"+potential_id).attr("data-stageid", new_stage_id);
                    // Remove the disabled state of LI
                    ui.item.removeClass('ui-state-disabled')
                },
                error: function (data) {
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
}
