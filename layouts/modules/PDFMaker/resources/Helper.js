jQuery.Class("PDFMaker_Helper_Js", {
    exportPDF: function(recordId) {
        var site_url = jQuery('#joforce_site_url').val();
        var selected_template = jQuery('#pdf_template').val();
        var source_module = jQuery('#source_module').val();
        if(selected_template == 'Select Template' || selected_template == null)
                Head_Helper_Js.showPnotify('Please select a template to export', 'failure');
        else
                window.location.href=site_url+'index.php?module=PDFMaker&view=DownloadPDF&recordId=' + recordId +'&selected_template='+selected_template+'&sourceModule='+source_module;
    },
    
    sendEmail: function(recordId){
        var source_module = app.getModuleName();
            var url = "index.php?module=PDFMaker&action=CheckServerInfo";
            var postParams  = app.convertUrlToDataParams(url);

            app.request.post({data:postParams}).then(function(err,response){
                if(response == true){
                    var url = 'index.php?module=PDFMaker&view=ComposeEmail&sourceId=' + recordId + '&source_module=' + source_module;
                    var postParams  = app.convertUrlToDataParams(url);

                    app.request.post({data:postParams}).then(function(err,data){
                        var modalContainer = app.helper.showModal(data, {cb: function(){
							var emailEditInstance = new Emails_MassEdit_Js();
    	                    emailEditInstance.registerEvents();
						}
					});
                });
        }
        else {
			app.helper.showErrorNotification({'message':app.vtranslate('JS_EMAIL_SERVER_CONFIGURATION')});
        }
	  });

    },

}, {});

$(document).ready(function() {
    var site_url = jQuery('#joforce_site_url').val();
    $('head').append('<link rel="stylesheet" href="'+site_url+'layouts/modules/PDFMaker/public/css/font-awesome.css" type="text/css" />');
    $('.pdfTabs li').click(function(){
        var id = $(this).attr('id');
        if(id == 'div1' ){
            $('#division1').removeClass('inactive').addClass('active');
            $('#division2, #division4, #division3, #division5').removeClass('active').addClass('inactive');
	}
	else if(id == 'div2'){
            $('#division2').removeClass('inactive').addClass('active');
	    $('#division1, #division4, #division3, #division5').removeClass('active').addClass('inactive');
	}
        else if(id == 'div3'){
            $('#division3').removeClass('inactive').addClass('active');
            $('#division1, #division2, #division4, #division5').removeClass('active').addClass('inactive');
        }
        else if(id == 'div4'){
            $('#division4').removeClass('inactive').addClass('active');
            $('#division1, #division2, #division3, #division5').removeClass('active').addClass('inactive');
        }
        else if(id == 'div5'){
            $('#division5').removeClass('inactive').addClass('active');
            $('#division1, #division2, #division3, #division4').removeClass('active').addClass('inactive');
        }

    });

    $('.pdfContentTabs li').click(function(){
        var id = $(this).attr('id');
        if(id == 'body-tab' ){
	    $('#textarea-type').val('templatecontent');
            $('#body').removeClass('inactive').addClass('active');
            $('#header, #footer').removeClass('active').addClass('inactive');
        }
        else if(id == 'header-tab'){
            $('#textarea-type').val('templatecontent-header');
            $('#header').removeClass('inactive').addClass('active');
            $('#body, #footer').removeClass('active').addClass('inactive');
        }
        else if(id == 'footer-tab'){
            $('#textarea-type').val('templatecontent-footer');
            $('#footer').removeClass('inactive').addClass('active');
            $('#body, #header').removeClass('active').addClass('inactive');
        }
    });

    var first = $(location).attr('pathname');
    first.indexOf(1);
    first.toLowerCase();
    var module = app.getModuleName();
    var second = first.split("/"+module)[1];
    if(second != undefined){

        first = first.split("/"+module)[0];
        var fullurl = url + first;
        view = second.split("/")[1];
        if (view == "Detail") {
		var type = view;
                var url = "index.php?module=PDFMaker&action=AddSideBar&moduleName=" + module+'&type='+type;
                var postParams  = app.convertUrlToDataParams(url);

                app.request.post({data:postParams}).then(function(err,data){

                    if(data != null && data.result == 'Completed')
                        window.location.reload();
                    else
                        return false;
                });
	}
    }
});
