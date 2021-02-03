Head_Edit_Js("Project_Edit_Js",{},{
	
    getPopUpParams : function(container) {
        var params = this._super(container);
        var sourceFieldElement = jQuery('input[class="sourceField"]',container);

        if(sourceFieldElement.attr('name') == 'contact_id') {
            var form = container.closest('form');
            var parentIdElement  = form.find('[name="parent_id"]');
            var closestContainer = parentIdElement.closest('td');
            var referenceModule = closestContainer.find('[name="popupReferenceModule"]');
            if(parentIdElement.length > 0 && parentIdElement.val().length > 0 && referenceModule.length >0) {
                params['related_parent_id'] = parentIdElement.val();
                params['related_parent_module'] = referenceModule.val();
            }
        }
        return params;
    },

    openPopUp : function(e){
        var thisInstance = this;
        var parentElem = thisInstance.getParentElement(jQuery(e.target));

        var params = this.getPopUpParams(parentElem);
        params.view = 'Popup';

        var isMultiple = false;
        if(params.multi_select) {
                isMultiple = true;
        }

        var sourceFieldElement = jQuery('input[class="sourceField"]',parentElem);

        var prePopupOpenEvent = jQuery.Event(Head_Edit_Js.preReferencePopUpOpenEvent);
        sourceFieldElement.trigger(prePopupOpenEvent);

        if(prePopupOpenEvent.isDefaultPrevented()) {
                return ;
        }
        var popupInstance = Head_Popup_Js.getInstance();

        app.event.off(Head_Edit_Js.popupSelectionEvent);
        app.event.one(Head_Edit_Js.popupSelectionEvent,function(e,data) {
            var responseData = JSON.parse(data);
            var dataList = new Array();
            for(var id in responseData){
                    var data = {
                            'name' : responseData[id].name,
                            'id' : id
                    }
                    dataList.push(data);
                    if(!isMultiple) {
                            thisInstance.setReferenceFieldValue(parentElem, data);
                    }
            }

            if(isMultiple) {
                sourceFieldElement.trigger(Head_Edit_Js.refrenceMultiSelectionEvent,{'data':dataList});
            }
            sourceFieldElement.trigger(Head_Edit_Js.postReferenceSelectionEvent,{'data':responseData});
        });
        popupInstance.showPopup(params,Head_Edit_Js.popupSelectionEvent,function() {});
    },

	referenceModulePopupRegisterEvent : function(container) {
		var thisInstance = this;
		container.off('click', '.relatedPopup');
		container.on("click",'.relatedPopup',function(e) {
			qc_container = thisInstance.getParentElement($(this).next('.createReferenceRecord'));
                        var referenceModuleName = thisInstance.getReferencedModuleName(qc_container);
                        var quickCreateNode = jQuery('#quickCreateModules').find('[data-name="'+ referenceModuleName +'"]');
                        if(quickCreateNode.length <= 0) {
                                var notificationOptions = {
                                        'title' : app.vtranslate('JS_NO_CREATE_OR_NOT_QUICK_CREATE_ENABLED')
                                }
                                app.helper.showAlertNotification(notificationOptions);
                        } else {
                                thisInstance.openPopUp(e);
                        }
		});
		container.on('change','.referenceModulesList',function(e){
			var element = jQuery(e.currentTarget);
			var popupReferenceModule = element.val();
			var relationid_display = jQuery("#linktoaccountscontacts_display").parent().parent();   
			var source_module = relationid_display.children();  
			source_module.val(popupReferenceModule);
		});
	},

	registerBasicEvents : function(container){
		this._super(container);
        this.referenceModulePopupRegisterEvent(container);
	}
});

