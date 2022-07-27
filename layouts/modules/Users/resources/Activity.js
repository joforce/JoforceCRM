/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Users_Detail_Js("Settings_Users_Activity_Js",{
    getUserActivityFilters : function(filtertype, date) {
        var aDeferred = jQuery.Deferred();
        var params = {
            'module' : app.getModuleName(),
            'action': "ActivityAjax",
            'mode' : 'getUserActivityFilters',
            'filtertype': filtertype,
            'date': jQuery('#'+ date).val(),
            'user_id': jQuery('#activityusersFilter').val()
        };
        app.helper.showProgress();
        app.request.post({"data" : params}).then(
            function(err, responseData) {
                app.helper.hideProgress();
                if(responseData == null){
                    app.helper.showErrorNotification({"message": app.vtranslate('JS_NO_RECORDS_RELATED_TO_THIS_FILTER')});
                } else {
                    window.location.reload();
                }
            },
            function(textStatus, errorThrown){}
        );
        return aDeferred.promise();
    }},
    {
    
  /*  registerEvents: function () {
	varthisInstance = this;
        varthisInstance.getUserActivityFilters();
       
    },*/
});


jQuery(document).ready(function() {
    jQuery('.activityContents').on('click', '.by_date_link', function() {
        jQuery(this).addClass('hide');
        jQuery('.editByDate').removeClass('hide');
    });

    jQuery('.activityContents').on('click', '.cancelByDate', function() {
        jQuery('.by_date_link').removeClass('hide');
        jQuery('.editByDate').addClass('hide');
    });

});
