$(document).ready(function() {

    jQuery("#create_record").on('change', function(e) {
        var quickCreateParams = {};
        var relatedParams = {};
        var postQuickCreateSave = function(data) {}
        var module = document.getElementById('create_record').value;
        if (module != 'select') {

            var name = document.getElementsByClassName('namefield');
	    if(name[0] == undefined){
                var from_name = '';
	    }
	    else{
                var from_name = name[0].innerHTML;
	    }
		var id = $('.selected').attr('id');
		var folder = $('#'+id+ ' a').html();
		if(folder == 'Sent'){
                        var from_email = $('.rcmContactAddress').eq(1).attr('title');
			var from_name = $('.rcmContactAddress').eq(1).html();
		}	
		else{
	                var from_email = $('.rcmContactAddress').attr('title');
                        var from_name = $('.rcmContactAddress').html();
		}
	    var recordId = $('input[name="vt_name"]:checked').val();
	    if(recordId != undefined && module == 'ModComments'){
                var quickcreateUrl = 'index.php?module=' + module + '&view=QuickCreateAjax&related_to='+recordId;
		}
	    else if(module == 'Events'){
		    var subject  = $('h1.voice').html();
		    if(recordId != undefined)
			    var quickcreateUrl = 'index.php?module=' + module + '&view=QuickCreateAjax&_mlinkto='+recordId+'&subject='+subject;
		    else
			    var quickcreateUrl = 'index.php?module=' + module + '&view=QuickCreateAjax&subject='+subject;
	    }
	    else if(module == 'HelpDesk'){
		    var subject  = $('h1.voice').html();
		    if(recordId != undefined)
			    var quickcreateUrl = 'index.php?module=' + module + '&view=QuickCreateAjax&contact_id='+recordId+'&ticket_title='+subject;
		    else
			    var quickcreateUrl = 'index.php?module=' + module + '&view=QuickCreateAjax&ticket_title='+subject;
	    }
            else if (module == 'Accounts')
                var quickcreateUrl = 'index.php?accountname=' + from_name + '&email1=' + from_email + '&module=' + module + '&view=QuickCreateAjax';
            else
	    {
                var quickcreateUrl = 'index.php?lastname=' + from_name + '&module=' + module + '&view=QuickCreateAjax&email='+from_email;

	    }
	relatedParams['contact_id'] = recordId;
            quickCreateParams['noCache'] = true;
            quickCreateParams['data'] = relatedParams;

            quickCreateParams['callbackFunction'] = postQuickCreateSave;
            var crm = parent.parent;
            var headerInstance = new crm.Head_Index_Js();
            headerInstance.getQuickCreateForm(quickcreateUrl, module, quickCreateParams).then(function(data) {
                                var callbackparams = {
                                        'cb' : function (container){
                                                headerInstance.registerPostReferenceEvent(container);
                                                crm.app.event.trigger('post.QuickCreateForm.show',form);
                                                crm.app.helper.registerLeavePageWithoutSubmit(form);
                                                crm.app.helper.registerModalDismissWithoutSubmit(form);
                                        },
                                        backdrop : 'static',
                                        keyboard : false
                                        }

                                crm.app.helper.showModal(data, callbackparams);
                                var form = crm.jQuery('form[name="QuickCreate"]');
                                var moduleName = form.find('[name="module"]').val();
                                crm.app.helper.showVerticalScroll(crm.jQuery('form[name="QuickCreate"] .modal-body'), {'autoHideScrollbar': true});

                                var targetInstance = headerInstance;
                                var moduleInstance = crm.Head_Edit_Js.getInstanceByModuleName(moduleName);
                                if(typeof(moduleInstance.quickCreateSave) === 'function'){
                                        targetInstance = moduleInstance;
                                        targetInstance.registerBasicEvents(form);
                                }

                                crm.vtUtils.applyFieldElementsView(form);
                                targetInstance.quickCreateSave(form,quickCreateParams);
                                crm.app.helper.hideProgress();

            });
        }
    });

    var url = document.location;
    if (window.location.href.indexOf("view") > -1) {
        var viewModeCaptured = /view=([^&]+)/.exec(url)[1];
        view = viewModeCaptured ? viewModeCaptured : "myDefaultValue";
        var module = app.getModuleName();
        if (view == 'Detail' && (module == 'Contacts' || module == 'Leads' || module == 'Accounts')) {
//            $('#' + module + '_detailView_basicAction_LBL_SEND_EMAIL').hide();
  //          $('#' + module + '_detailView_basicAction_LBL_EDIT').after($('<span class="btn-group" style="position:relative;left:5px;"><button class="btn btn-lg" onclick="sendEmailPopup()"><strong>Send Email</strong></button></span>'));

        }
    }
    jQuery('.related').on('click', 'li', function(e, urlAttributes) {
        $(document).ajaxComplete(function(event, request, settings) {
            var relatedModule = $('.relatedModuleName').val();
            if (relatedModule == 'Emails') {
                $("button[name='composeEmail']").hide();
                if (($('#emailPopup').length) == 0) {
                    $("button[name='composeEmail']").after('<span id = "emailPopup" class="btn-group"><button class="btn addButton" onclick="sendEmailPopup()"><i class="icon-plus icon-white"> </i><strong> Add Email</strong></button></span>');
                }
            }
        });
    });
    $("button[name='composeEmail']").on('click', function(e) {
        e.stopPropagation();
        $("button[name='composeEmail']").hide();
        $("button[name='composeEmail']").after('<span id = "emailPopup" class="btn-group"><button class="btn addButton" onclick="sendEmailPopup()"><i class="icon-plus icon-white"> </i><strong> Add Email</strong></button></span>');
        sendEmailPopup();
    });

});

//send Email
function sendEmailPopup() {
    var moduleName = app.getModuleName();
    var recordId = app.getRecordId();
    urldata = {
        "type": "POST",
        "data": 'module=EmailPlus&action=FetchEmailAndLogin&recordId=' + recordId + '&moduleName=' + moduleName,
        "async": false,
        "url": 'index.php'
    }
    AppConnector.request(urldata).then(
        function(data) {
	    if(data.result == 'IoncubeNotAvailable'){
		msg = 'IonCube Extension not available. Please install Ioncube Loader to continue';
                Head_Helper_Js.showPnotify(msg, 'failure');

	    }
	    else if(data.result == 'Failed'){
	        msg = "Please fill IMAP Configuration <a href = 'index.php?module=EmailPlus&view=ServerSettings'> HERE </a>";
		Head_Helper_Js.showPnotify(msg, 'failure');
	    }
	    else{
                var emailId = data.result;
                if (emailId == undefined) {
                    emailId = '';
            }
            var popupWinRef = window.open('modules/EmailPlus/roundcube/?_task=mail&_action=compose&_to=' + emailId + '&_extwin=1', '', 'width=900,height=650,resizable=0,scrollbars=1');
	   }
        });
}

//select emails from vtiger modules
function selectEmailId(module) {
    var url = document.location.origin;
    var first = $(location).attr('pathname');
    first.indexOf(1);
    first.toLowerCase();
    first = first.split("/modules")[0];
    var fullurl = url + first;
    var eventName = 'postSelection' + Math.floor(Math.random() * 10000);
    var popupWinValue = window.open(fullurl + '/index.php?module=' + module + '&src_module=Emails&view=EmailsRelatedModulePopup&triggerEventName=' + eventName, '', 'width=900,height=650,resizable=0,scrollbars=1');
    jQuery.initWindowMsg();
    cb = function(data) {
        var responseData = JSON.parse(data);
        for (var id in responseData) {
            var data = {
                'name': responseData[id].name,
                'id': id,
                'emailid': responseData[id].email
            };
            var textarea = document.getElementById('_to');
            var word = data.emailid;
            var textValue = textarea.value;
            if (textValue.indexOf(word) == -1) {
                var div = document.getElementById('_to');
                if (div.value != '') {
                    div.innerHTML = div.innerHTML + ', ' + data.emailid;
                } else {
                    div.value = data.emailid;
                }
            }
        }
    };
    jQuery.windowMsg(eventName, function(data) {
        cb(data);
    });

}

function choice() {
    y = document.getElementById("type").value;
    if (y == 'gmail') {
        document.getElementById("servername").value = 'ssl://imap.gmail.com';
        document.getElementById("port").value = '993';
    } else if (y == 'yahoo') {
        document.getElementById("servername").value = 'ssl://imap.mail.yahoo.com';
        document.getElementById("port").value = '993';
    } else {
        document.getElementById("servername").value = '';
        document.getElementById("port").value = '';

    }

}
