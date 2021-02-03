$(document).ready(function() {
    $(".quickCreateModule").click(function() {
	thisdata = $(this).attr('data-url');
	var captured = /module=([^&]+)/.exec(thisdata)[1];     
	module = captured ? captured : 'myDefaultValue';
    	var makeDiv = "";
    	var fieldName = "";
     	fieldNameArray = new Array();

        var url = 'module=DuplicateCheck&parent=Settings&view=GetFieldsName&moduleName='+module;
        var postParams  = app.convertUrlToDataParams(url);

	app.request.get({data:postParams}).then(function(err,data){
	    setTimeout(function () {
		var count = data[1][0];
		var i = 0;
            	for( i=1;i<=count;i++ )	{
                    var fieldName = data[1][i];
		    $(".recordEditView [name='"+fieldName+"']").addClass("quickcreate_check");
                    fieldNameArray.push(data[1][i]);
		    var $eventSelect = $(".quickcreate_check");
                    $eventSelect.on("change", function (e) {
                    	var fieldName = $(this).attr("name");
                        var fieldValues = $(this).val();
                        var fieldId = $(this).attr("id");
			div = '.recordEditView';
                        QuickValidate(module,fieldName,fieldValues,fieldId,div);
                    });
		}
	    }, 200);
	});

        $(document).on('focusout', ".quickcreate_check", function(){
	    var fieldName = $(this).attr("name");
       	    var fieldValues = $(this).val();
            var fieldId = $(this).attr("id");
	    div = '.recordEditView';
	    QuickValidate(module,fieldName,fieldValues,fieldId,div);
	});
		
        $(document).on('click', ".vtSmackQuickClass", function(e){
            e.stopImmediatePropagation();
            var message = "Duplicate found. Do you still want to save this record ?";
            app.helper.showConfirmationBox({'message': message}).then(function(e) {
                $('.recordEditView button[type="button"]').prop("type", "submit");
                $('.recordEditView .btn-success').removeClass("vtSmackQuickClass");
                $('.recordEditView').submit();
            });
        });
    });

    $("#Leads_detailView_basicAction_LBL_CONVERT_LEAD").click(function() {
            setTimeout(function(){
		$('.fieldInfo').each(function() {
		    module = $(this).data('modulename');
	    	    var makeDiv = "";
	    	    var fieldName = "";
      		    fieldNameArray = new Array();

        	    var url = 'module=DuplicateCheck&parent=Settings&view=GetFieldsName&moduleName='+module;
        	    var postParams  = app.convertUrlToDataParams(url);

		    app.request.get({data:postParams}).then(function(err,data){
		    	setTimeout(function () {
			    var count = data[1][0];
			    var i = 0;
	            	    for( i=1;i<=count;i++ ) {
	                        var fieldName = data[1][i];
			    	$("#convertLeadForm [name='"+fieldName+"']").addClass("quickcreate_check");
	                    	fieldNameArray.push(data[1][i]);
			        var $eventSelect = $(".quickcreate_check");
	                    	$eventSelect.on("change", function (e) {
				    module_name = $(this).parent().data('modulename');
	                    	    var fieldName = $(this).attr("name");
	                            var fieldValues = $(this).val();
            	 	            var fieldId = $(this).attr("id");
				    div = '#convertLeadForm';
                        	    QuickValidate(module_name,fieldName,fieldValues,fieldId, div);
	                    	});
			    }
		    	}, 200);
		    });

		});
	        $(document).on('focusout', ".quickcreate_check", function(){
			module_name = $(this).parent().data('modulename');
		    	var fieldName = $(this).attr("name");
	 	    	var fieldValues = $(this).val();
            		var fieldId = $(this).attr("id");
			div = '#convertLeadForm';
		    	QuickValidate(module_name,fieldName,fieldValues,fieldId, div);
		});
		
	        $(document).on('click', ".vtSmackQuickClass", function(e){
            		e.stopImmediatePropagation();
		        var message = "Duplicate found. Do you still want to save this record ?";
		        app.helper.showConfirmationBox({'message': message}).then(function(e) {
                	    $('#convertLeadForm button[type="button"]').prop("type", "submit");
	                    $('#convertLeadForm .btn-success').removeClass("vtSmackQuickClass");
            		    $('#convertLeadForm').submit();
		        });
		});
	    }, 1000);
    });
});

function QuickValidate(module,fieldName,fieldValues,fieldId,div) {
    var site_url = jQuery('#joforce_site_url').val();
    if(fieldValues!="") {
    	var addCustomClass = $('[name="'+fieldName+'"]').addClass("vtSmackQuickError");
	var url = 'module=DuplicateCheck&parent=Settings&view=ValidateDuplicate&moduleName='+module+"&fieldName="+fieldName+"&fieldValues="+fieldValues;
        var postParams  = app.convertUrlToDataParams(url);
        app.request.get({data:postParams}).then(function(err,data){
		console.log(err,data);
	    var resultantCount = data[0][0];
            var count = resultantCount + 2;
            var fieldlabel = data[0][1]['fieldlabel'];
	    var count_of_fields=data[0][2][0];
            var count_of_fieldName=data[0][2][1];
	    var uitype =data[1][0];
            var crosscount=data[1][2];
            makeDiv = "";
	    if((crosscount > 0) || (resultantCount > 0)) {
            	makeDiv += "";
                makeDiv += fieldlabel+" already exists with : "+"<br/>";
            }
            if(resultantCount < 1) {
       		$(div+' button[type="button"]').prop("type", "submit");
                $(div+" .btn-success").removeClass("vtSmackQuickClass");
		$(div+' .recordEditView').submit();
	    } else {
                $(div+" .btn-success").addClass("vtSmackQuickClass");
                $(div+' button[type="submit"]').prop("type", "button");
                for(i=3;i<=count;i++) {
		    if(count_of_fields==1) {
		    	var recordname=data[0][i][count_of_fieldName];
		    } else {
                        if(data[0][i]['firstname']) {
                            var recordname = data[0][i][count_of_fieldName[0]] + ' '+data[0][i][count_of_fieldName[1]];
    			} else {
			    var recordname = data[0][i][count_of_fieldName[1]];
			}
		    }	
                    var recordid = data[0][i]['recordid'];
                    var urlpath = site_url+module+"/view/Detail/"+recordid;
                    makeDiv +="<u style='z-index:99999999;'><a href="+urlpath+" style='color:white;z-index:99999999;' target=_blank>"+recordname+" "+" (#"+recordid+" ) "+"</a></u>";
                    makeDiv +="<br/>";
                }
		    console.log($(div+' #'+fieldId), makeDiv);
                vtUtils.showValidationMessage($(div+' #'+fieldId), makeDiv, 'anything else');
                vtUtils.showValidationMessage($(div+' #'+fieldId+'_chzn'), makeDiv, 'anything else');
            }
            if((uitype == 11) || (uitype==13)){
            	for(i=0;i<crosscount;i++){
                    var crossmodulename=data[1][3][i]['modulename'];
                    var crossrecordid=data[1][3][i]['recordid'];

                    if(crossmodulename == 'Accounts')
                    	var crossrecordname=data[1][3][i][0];
		    else
                        var crossrecordname=data[1][3][i][0] +' '+data[1][3][i][1];

                    var modulepath = "index.php?module="+crossmodulename+"&view=Detail&record="+crossrecordid;
                    makeDiv +=" <u><a href="+modulepath+" style='color:white' target=_blank>"+crossrecordname+' '+' ( #'+crossrecordid+' '+ crossmodulename +")<a></u><br/>";
		}
                vtUtils.showValidationMessage($(div+' #'+fieldId), makeDiv, 'anything else');
		makeDiv="";
	    }
	});
    }       
}
