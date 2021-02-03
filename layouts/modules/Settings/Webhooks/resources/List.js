/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Settings_Head_List_Js("Settings_Webhooks_List_Js",{
	
},{
	
	/*
	 * function to trigger delete record action
	 * @params: delete record url.
	 */
    DeleteRecord : function(deleteRecordActionUrl) {
        var thisInstance = this;
        app.helper.showConfirmationBox({
            message:app.vtranslate('LBL_DELETE_CONFIRMATION')
        }).then(function() {
            app.request.post({'url':deleteRecordActionUrl+'&ajaxDelete=true'}).then(
            function(e,res){
                if(!e) {
                    app.helper.showSuccessNotification({
                        'message' : app.vtranslate('JS_WEBHOOK_DELETED_SUCCESSFULLY')
                    });
					jQuery('#recordsCount').val('');
					jQuery('#totalPageCount').text('');
					thisInstance.loadListViewRecords().then(function(){
						thisInstance.updatePagination();
					});
                } else {
                    app.helper.showErrorNotification({
                        'message' : e
                    });
                }
            });
        });
	},
    
	/*
	 * function to load the contents from the url through pjax
	 */
	loadContents : function(url) {
		var aDeferred = jQuery.Deferred();
		app.request.pjax({'url':url}).then(
			function(e,data){
				jQuery('.contentsDiv').html(data);
				aDeferred.resolve(data);
			},
			function(error, err){
				aDeferred.reject();
			}
		);
		return aDeferred.promise();
	},
	
	/**
	 * Function to register events
	 */
	registerEvents : function(){
		this._super();
	}
})
