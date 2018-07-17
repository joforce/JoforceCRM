$(document).ready(function(){
	$(".quick-create-contact").click(function(){
		var $this = $(this);
		var contact_name = $this.data('contact-name');
        var contact_email = $this.data('contact-email');
        if(!contact_name){
            contact_name = contact_email.replace(/@[^@]+$/, '');
            contact_name = toTitleCase(contact_name.replace(/\./gi, " "));
        }
        var module_name = $this.data('module-name');        
	    var quickcreateUrl = 'index.php?lastname=' + contact_name + '&module=' + module_name + '&view=QuickCreateAjax&email='+contact_email;
	    	var quickCreateParams = {};
        	var relatedParams = {};	
        	var postQuickCreateSave = function(data) {}
			relatedParams['contact_id'] = null;
            quickCreateParams['noCache'] = true;
            quickCreateParams['data'] = relatedParams;

            quickCreateParams['callbackFunction'] = postQuickCreateSave;
            var crm = parent.parent;
            var headerInstance = new crm.Head_Index_Js();
            headerInstance.getQuickCreateForm(quickcreateUrl, module_name, quickCreateParams).then(function(data) {
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

    });

    function toTitleCase(str) {
        return str.replace(/(?:^|\s)\w/g, function(match) {
            return match.toUpperCase();
        });
    }
})