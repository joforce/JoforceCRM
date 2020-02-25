$(document).ready(function() {
    var url = document.location; 
    var module = app.getModuleName();

    var currentId = app.getRecordId();
    var record_view = app.getViewName();    
    if(window.location.href.indexOf("isDuplicate") > -1)  {
	var dupPage = /isDuplicate=([^&]+)/.exec(url)[1];
	dupPage = dupPage ? dupPage : 'myDefaultValue';
    }
  
    if(dupPage == "true") {
	setTimeout(function() {
	    if($('button[type="submit"]')) {
		$(".btn-success").addClass("vtSmackCustomClass");
		$('button[type="submit"]').prop("type", "button");
	    } 
	},200);
    }
    
    makeDiv ="";
    var fieldName = "";
    fieldNameArray = new Array();

    var url = 'module=DuplicateCheck&parent=Settings&view=GetFieldsName&moduleName='+module;
    var postParams  = app.convertUrlToDataParams(url);
    if(record_view == "Edit"){
	app.request.get({data:postParams}).then(function(err,data){
	    var isenable=data[0]; 
            var count = data[1][0];
            var i = 0;
            for( i=1;i<=count;i++ ) {
	    	if(isenable>0) {
                    var fieldName = data[1][i];
		    $('[name="'+fieldName+'"]').addClass("vtsmack");
                    fieldNameArray.push(data[1][i]);
		    var $eventSelect = $(".vtsmack");
                    $eventSelect.on("change", function (e) {
                    	var fieldName = $(this).attr("name");
                    	var fieldValues = $(this).val();
                    	var fieldId = $(this).attr("id");
                    	Validate(module,fieldName,fieldValues,fieldId,currentId,record_view,dupPage);
		    });
		}
            }
	});
    }

    $(document).on('focusin', ".vtsmack", function(){        
	$(".vtSmackClass").remove();
    });

    $(document).on('focusout', ".vtsmack", function(){  
	var fieldName = $(this).attr("name");
        var fieldValues = $(this).val();
        var fieldId = $(this).attr("id");
        Validate(module,fieldName,fieldValues,fieldId,currentId,record_view,dupPage);
    });

    $(document).on('click', ".vtSmackCustomClass", function(){        
	var message = "Duplicate found. Do you still want to save this record ?";
        app.helper.showConfirmationBox({'message': message}).then( function(e){
	    $(".btn-success").removeClass("vtSmackCustomClass");
	    $('button[type="button"]').prop("type", "submit");
	    $('#EditView').submit();
	});
    });
});

//Save Settigns
$(document).on('click', '#vtduplicateform_submit', function() {
    var form = $('#vtduplicateform');
    array_data = form.serializeArray();
    formData = {};
    fieldids = [];
    $.each(array_data, function( index, value_array ) {
          name = value_array.name;
          value = value_array.value;
          if(name == 'fieldID' || name == 'fieldID[]') {
                fieldids.push( value );
          } else {
              formData[name] = value;
          }
    });
    formData.fieldID = fieldids;
    source_module = formData.modulename;
    app.request.post({data: formData}).then(function (err, data) {
	if(err == null) {
	    var msg = 'Settings for '+source_module+' saved successfully.';
	    app.helper.showSuccessNotification({'message' : msg});
	    window.onbeforeunload = null; //To prevent chrome and firefox alert
	}
    });
});

$(document).ready(function() {
    var warnMessage = null;
    $("input[type='checkbox']").change(function() {
	warnMessage = "You have unsaved changes on this page!";
	$("#vtduplicateform").addClass("formChanged");
	$(".btn-success").addClass("formChanges");
    });

    $(document).on('click', ".formChanges", function(){
    	warnMessage = null;
        $("#vtduplicateform").removeClass("formChanged");
        $(".btn-success").removeClass("formChanges");
        $("#vtduplicateform").submit();
    });

    window.onbeforeunload = function () {
        if (warnMessage != "null") return warnMessage;
    }

    module = $('#layoutEditorModules').val();
    if((module == 'Leads')|| (module == 'Contacts') || (module == 'Accounts'))
        $('#crosscheck').show();
    else
        $('#crosscheck').hide();

    $('[rel="tooltip"]').tooltip();
});

function Validate(module,fieldName,fieldValues,fieldId,currentId,record_view,dupPage)
{
    var site_url = jQuery('#joforce_site_url').val();
    if(fieldValues!="") {
	var addCustomClass = $('[name="'+fieldName+'"]').addClass("vtSmackCustomError");
	var url = 'module=DuplicateCheck&parent=Settings&view=ValidateDuplicate&record_id='+currentId+'&record_view='+record_view+'&moduleName='+module+"&fieldName="+fieldName+"&fieldValues="+fieldValues;
	var postParams  = app.convertUrlToDataParams(url);

	app.request.get({data:postParams}).then(function(err,data) {
	    var resultantCount = data[0][0];
            var count = resultantCount + 2;
            var count_of_fields=data[0][2][0];
            var count_of_fieldName=data[0][2][1];
            var fieldlabel = data[0][1]['fieldlabel'];
            var uitype =data[1][0];
            var crosscount=data[1][2];
            makeDiv = "";
      
    	    if((crosscount > 0) || (resultantCount > 0)){
       		makeDiv += "";
                makeDiv += fieldlabel+" already exists with : "+"<br/>";
	    }

	    if(resultantCount < 1) {
	     	$('[name="'+fieldName+'"]').removeClass("vtsmackCustomClass");
	      	$(".btn-success").removeClass("vtSmackCustomClass");
	      	$('button[type="button"]').prop("type", "submit");
            } else {
                $(".btn-success").addClass("vtSmackCustomClass");
                $('button[type="submit"]').prop("type", "button");
                for(i=3;i<=count;i++) {
                    if(dupPage != "true") {
                        if(data[0][i]['recordid']==currentId) {
                            continue;
                        }
                    }
    		    if(count_of_fields==1) {
                        var recordname=data[0][i][count_of_fieldName]; 
		    } else {
                        if(data[0][i]['firstname']) {
                            var recordname = data[0][i][count_of_fieldName[0]] + ' '+data[0][i][count_of_fieldName[1]];
			} else {
                            var recordname = data[0][i][count_of_fieldName[1]];
                        }
                        var recordid = data[0][i]['recordid'];          
                       
                        var urlpath = site_url+module+"/view/Detail/"+recordid;
                        makeDiv +="<u><a href="+urlpath+" style='color:white' target=_blank>"+recordname+' '+' ( #'+recordid+' ) '+"</a></u>";
                        makeDiv +="<br/>";
		    }
                    vtUtils.showValidationMessage($('#'+fieldId), makeDiv, 'anything else');
                    vtUtils.showValidationMessage($('#'+fieldId+'_chzn'), makeDiv, 'anything else');
		}
    
	        if((uitype == 11) || (uitype==13)){
		    for(i=0;i<crosscount;i++){  
		        var crossmodulename=data[1][3][i]['modulename'];
		        var crossrecordid=data[1][3][i]['recordid'];
      
		        if(crossmodulename == 'Accounts')
		            var crossrecordname=data[1][3][i][0];
		        else
		            var crossrecordname=data[1][3][i][0] +' '+result.result[1][3][i][1];
      
		        var modulepath = "index.php?module="+crossmodulename+"&view=Detail&record="+crossrecordid;
		        makeDiv +=" <u><a href="+modulepath+" style='color:white' target=_blank>"+crossrecordname+' '+' ( #'+crossrecordid+' '+ crossmodulename +")<a></u><br/>";
		    }
                    vtUtils.showValidationMessage($('#'+fieldId), makeDiv, 'anything else');
		    makeDiv = "";
		}
	    }
	});
    }
}

function getModuleDuplicateCheck(selectedModule) {
    var thisInstance = this;
    var aDeferred = jQuery.Deferred();
    app.helper.showProgress();

    var params = {};
    params['module'] = app.getModuleName();
    params['parent'] = app.getParentModuleName();
    params['view'] = 'List';
    params['sourceModule'] = selectedModule;
    app.request.pjax({'data': params}).then(function (err, data) {
        app.helper.hideProgress();
        if (err === null) {
            aDeferred.resolve(data);
        } else {
            aDeferred.reject();
        }
    });
    return aDeferred.promise();
}

function registerModuleChangeEvent() {
    var thisInstance = this;
    var container = jQuery('#layoutEditorContainer');
    var contentsDiv = container.closest('.settingsPageDiv');

    container.on('change', '#layoutEditorModules', function (e) {
    	var currentTarget = jQuery(e.currentTarget);
    	var selectedModule = currentTarget.val();

    	if (selectedModule == '') {
            return false;
    	}
    	thisInstance.getModuleDuplicateCheck(selectedModule).then(function (data) {
	    contentsDiv.html(data);
	    $('[name="layoutEditorModules"]').select2({
		closeOnSelect: true
	    });
    	});
    });
}
