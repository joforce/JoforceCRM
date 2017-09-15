$(document).ready(function() {

	var s = document.createElement("script");
	s.type = "text/javascript";
	s.src = "http://maps.google.com/maps/api/js?sensor=false&libraries=places&language=en-AU";

	var module = app.getModuleName();
	var record_view = app.getViewName();

	if(record_view == "Edit")
    {
      var url = 'index.php?module=AddressLookup&action=GetFieldsName&moduleName='+module;
                  var postParams  = app.convertUrlToDataParams(url);

                   app.request.get({data:postParams}).then(function(err,data){

            var api_key = data[0];
            var result = data[1];
            if(result == "No field to map") {
                return false;
            }
            else if(result == "Not Enabled")    {
                return false;
            }
            else    {
                var fields = data[1][0];
                var fieldsCount = fields.length;
                for(var fieldCount = 0; fieldCount < fieldsCount; fieldCount ++)    {
                    if(api_key.length > 2)   {
                        var fieldName = fields[fieldCount];
                        $('[name="'+fieldName+'"]').addClass("vtsmackstreet");
                    }
                }
            }

            if(api_key.length > 2)   {
                s.src +="&key="+api_key;
                $("head").append(s);
            }
        })
	}

    $(".vtsmackstreet").live('click',function()	{
        var fieldName =	$(this).attr("name");

        $(this).addClass("vtsmackgoogleLookup");

                var url = 'index.php?module=AddressLookup&action=GetFields&moduleName='+module+'&fieldName='+fieldName;
                var postParams  = app.convertUrlToDataParams(url);
                        app.request.get({data:postParams}).then(function(err,data){
                /**
                 * Auto Complete Object
                 * @type {google.maps.places.Autocomplete}
                 * @type Geo code
                 */
                var auto_complete = new google.maps.places.Autocomplete($('.vtsmackgoogleLookup')[0], {});
                auto_complete.addListener('place_changed', function()    {
                    var streetNumber, route, street, area, locality, city, state, country, postal_code;
                    // Get the place details from the auto_complete object.
                    var place = auto_complete.getPlace();

                   // Get each component of the address from the place details
                    // and fill the corresponding field on the form.
                    for (var i = 0; i < place.address_components.length; i++) {
                        var addressType = place.address_components[i].types[0];
                        var val = place.address_components[i]['long_name'];
                        switch(addressType) {
                            case'street_number':
                                streetNumber = val;
                                break;
                            case 'route':
                                route = val;
                                break;
                            case 'sublocality_level_2':
                                street = val;
                                break;
                            case 'sublocality_level_1':
                                area = val;
                                break;
                            case 'locality':
                                locality = val;
                                break;
                            case 'administrative_area_level_2':
                                city = val;
                                break;
                            case 'administrative_area_level_1':
                                state = val;
                                break;
                            case 'country':
                                country = val;
                                break;
                            case 'postal_code':
                                postal_code = val;
                                break;
                            default:
                                break;

                        }
                    }

                    var address = place.name;
                    var values = [address, area, locality, city, state, country, postal_code];
                    var fieldNameCount = data.length;
                    for(i = 0; i < fieldNameCount; i++)  {
                        var fieldName = data[i];
                        var fieldValue = values[i];
                        // Assigning the values to the form
                        $('[name="'+fieldName+'"]').val(fieldValue);
                    }
                });
            }
        )
    });

    $(".vtsmackstreet").live("focusout", function() {
        $(this).removeClass("vtsmackgoogleLookup");
    });

});

function addOneMore(){
    var url=document.location;
    pathname=document.location.pathname;
    modulename =pathname.split('/'); 
    selectedModuleName = modulename[6];
    var makeUI = "";
    var url = 'index.php?module=AddressLookup&parent=Settings&view=AddField&modulename='+selectedModuleName;
    var postParams  = app.convertUrlToDataParams(url);

    app.request.get({data:postParams}).then(function(err,data){

            var fieldCount = data[0];
            var fieldNames = ["street","area","locality","city","state","country","postalcode"];
            makeUI+= '<tr>';
            for(var i = 0;i < fieldNames.length;i ++){ 
            makeUI+= '<td><select class="select2 dupecheck" style="" name="'+fieldNames[i]+'[]" ><option value="">Select an Option</option>';
            for(var j = 1;j <= fieldCount;j ++){
            var fieldId = data[j]['fieldid'];
            var fieldName = data[j]['fieldlabel'];           
            makeUI+='<option value="'+fieldId+'">'+fieldName+'</option>';
            }
            makeUI+='</select></td>';
            }
            makeUI+='<td><i style="margin-left:20px;" class="fa fa-trash removeRow"></i></td></tr>';
            $("tbody:last").append(makeUI);
            var container = jQuery("#addFormFieldRow");

            app.changeSelectElementView(container);

    }
    ) 
}

/**
 * Show Notification
 * @param type
 * @param message
 */
function flashNotification(type, message) {

	// showing notification
	var params = {
		type: type,
		text: message,
		animation: 'show'
	};

	Head_Helper_Js.showPnotify(params);
}


