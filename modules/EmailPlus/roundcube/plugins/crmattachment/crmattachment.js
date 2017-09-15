window.rcmail && rcmail.addEventListener('init', function (evt) {
        var currentWin = window.currentWin = getCurrentWindow();
        var url = document.location.origin;
        var first = $(location).attr('pathname');
        first.indexOf(1);
        first.toLowerCase();
        first = first.split("/modules")[0];
        var fullurl = url + first;

        //Add to mail
        rcmail.register_command('crmattachment.attachFiles', function (data) {
                var ts = new Date().getTime(),
                                frame_name = 'rcmupload' + ts,
                                frame = rcmail.async_upload_form_frame(frame_name);
                data._uploadid = ts;
                jQuery.ajax({
                        url: "?_task=mail&_action=plugin.crmattachment.attachFiles&_id=" + rcmail.env.compose_id,
                        type: "POST",
                        data: data,
                        success: function (data) {
                                var doc = frame[0].contentWindow.document;
                                var body = $('html', doc);
                                body.html(data);
                        }
                });
        }, true);

        //Attach document from crm
        rcmail.register_command('crmattachment.attachCRMFiles', function (data) {
                if (currentWin != false) {
		        var url = document.location.origin;
		        var first = $(location).attr('pathname');
		        first.indexOf(1);
		        first.toLowerCase();
		        first = first.split("/modules")[0];
		        var fullurl = url + first;
		        var eventName = 'postSelection'+ Math.floor(Math.random() * 10000);
			window.open(fullurl+'/index.php?view=Popup&module=Documents&src_module=Emails&src_field=composeEmail&triggerEventName='+eventName, '' ,'width=900,height=650,resizable=0,scrollbars=1');
                	jQuery.initWindowMsg();

			cb = function (data){
                                var responseData = JSON.parse(data);
                                var ids = [];
                                for (var id in responseData) {
                                        ids.push(id);
                                }
                                rcmail.command('crmattachment.attachFiles', {ids: ids, _uploadid: new Date().getTime()});
                        }
                	jQuery.windowMsg(eventName, function(data) {
                        	cb(data);
                	});
		}
        }, true);
});



function getCurrentWindow() {
        if (opener !== null) {
                return opener.parent;
        } else if (typeof parent.app == "object") {
                return parent;
        }
        return false;
}


