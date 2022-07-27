/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_List_Js("Settings_Head_List_Js",{
	
	triggerDelete : function(event,url){
		var instance = Head_List_Js.getInstance();
		instance.DeleteRecord(url);
	}
},{
	
	/*
	 * Function to register the list view delete record click event
	 */
	DeleteRecord: function(url){
		var thisInstance = this;
		var css = jQuery.extend({'text-align' : 'left'},css);
		app.helper.showProgress();
		app.request.get({'url' : url}).then(
			function(err, data) {
                app.helper.hideProgress();
				if(err === null) {
					var params = {};
					params.cb = function(container){
						thisInstance.postDeleteAction(container);
					};
					
					app.helper.showModal(data, params);
				}
		});
	},
	
	/**
	 * Function to load list view after deletion of record from list view
	 */
	postDeleteAction : function(container){
		var thisInstance = this;
		var deleteConfirmForm = jQuery(container).find('#DeleteModal');
		deleteConfirmForm.on('submit', function(e){
			e.preventDefault();
			app.helper.showProgress();
			var deleteActionUrl = deleteConfirmForm.serializeFormData();
			
			app.request.post({'data' : deleteActionUrl}).then(
				function(err, data) {
					app.helper.hideModal();
					app.helper.hideProgress();
					if(err === null) {
						app.helper.showSuccessNotification({'message' : app.vtranslate('JS_RECORD_DELETED_SUCCESSFULLY')});
						jQuery('#recordsCount').val('');
						jQuery('#totalPageCount').text('');
						jQuery('#pageNumber').val(1);
						thisInstance.loadListViewRecords();
					}
				});
		});
	},
    
	registerEvents : function() {
		this.registerRowClickEvent();
		this.initializePaginationEvents();
		this.registerEmailFieldClickEvent();
        this.registerDynamicDropdownPosition('table-actions', 'listview-table');
	
	}
});
function checkWidth() {
    if ($(window).width() < 500) {
		 $('.table-toggle').removeClass('fixed-scroll-table');
		 $('.table-toggle1').removeClass('table form-horizontal no-border');
		
    } 
	else {
		$('.table-toggle').addClass('fixed-scroll-table');
		$('.table-toggle1').addClass('table form-horizontal no-border');
    }
	
}
//$(window).resize(checkWidth);
window.onload = function() {
$(window).resize(checkWidth);
if ($(window).width() < 500) {
	$('.table-toggle').removeClass('fixed-scroll-table');
	$('.table-toggle1').removeClass('table form-horizontal no-border');
	
} 
}