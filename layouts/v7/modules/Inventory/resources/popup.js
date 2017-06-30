/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Vtiger_Popup_Js('Inventory_Popup_Js',{},{
    
    registerSubproductsClick : function() {
        var self = this;
        var popupPageContainer = this.getPopupPageContainer();
        this.parentProductEle = popupPageContainer.clone(true, true);
        popupPageContainer.on('click','.subproducts', function(e){
            e.stopPropagation();
            var rowElement = jQuery(e.currentTarget).closest('tr');
            
            var params = {};
			params.view = 'SubProductsPopup';
			params.module = self.getModuleName();
			params.multi_select = true;
			params.subProductsPopup = true;
			params.productid = rowElement.data('id');
			jQuery('#recordsCount').val('');
			jQuery('#pageNumber').val("1");
			jQuery('#pageToJump').val('1');
			jQuery('#orderBy').val('');
			jQuery("#sortOrder").val('');
            app.request.get({'data':params}).then(function(error, data){
                jQuery('#popupContentsDiv').html(data);
                jQuery('#totalPageCount').text('');
                self.registerEventForBackToProductsButtonClick();
            });
        });
    },
    
    getCompleteParams : function() {
        var params = this._super();
        var subProductsPopup = jQuery('#subProductsPopup').val();
        var parentProductId = jQuery('#parentProductId').val();
        if(typeof subProductsPopup != "undefined" && typeof parentProductId != "undefined") {
            params['subProductsPopup'] = subProductsPopup;
            params['productid'] = parentProductId;
            params['view'] = 'SubProductsPopupAjax';
        }
        return params;
        
    },
    
    /**
	 * Function to register event for back to products button click
	 */
	registerEventForBackToProductsButtonClick : function(){
        var self = this;
		jQuery('#backToProducts').on('click',function(){
			self.getPopupPageContainer().html(self.parentProductEle.html());
		})
	},
    
    registerEvents : function(){
        this._super();
        this.registerSubproductsClick();
    }
});

