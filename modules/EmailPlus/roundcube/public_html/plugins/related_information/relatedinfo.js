$(document).ready(function() {
	$("#related-info li").on("click", function() {
		var id = $(this).data('id');
		$('#related-info li').removeClass('active');
		$(this).addClass('active');
		$('#relatedInformation div').find('.tab-pane').removeClass('active');
		$('div #'+ id).addClass('active');
		//jQuery("#detailedInfo").css("display", "none");
		//jQuery("#eventsInfo").css("display", "none");
		//jQuery("#opportunityInfo").css("display", "none");
		//jQuery("#quotesInfo").css("display", "none");
		//jQuery("#salesorderInfo").css("display", "none");
		//jQuery("#tasksInfo").css("display", "none");
		//jQuery("#"+id).css("display", "block");
		//jQuery("#"+tab).css("background", "#fff"); 
	});
});
