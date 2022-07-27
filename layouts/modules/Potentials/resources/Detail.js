/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_Detail_Js("Potentials_Detail_Js",{

	//cache will store the convert Potential data(Model)
	cache : {},

	//Holds detail view instance
	detailCurrentInstance : false,

},{

	detailViewRecentContactsLabel : 'Contacts',
	detailViewRecentProductsTabLabel : 'Products',

	//constructor
	init : function() {
		this._super();
		Potentials_Detail_Js.detailCurrentInstance = this;
	},

	/*
	 * function to enable all the input and textarea elements
	 */
	removeDisableAttr : function(moduleBlock) {
		moduleBlock.find('input,textarea,select').removeAttr('disabled');
	},

	/*
	 * function to disable all the input and textarea elements
	 */
	addDisableAttr : function(moduleBlock) {
		moduleBlock.find('input,textarea,select').attr('disabled', 'disabled');
	},

	/*
	 * function to check which module is selected 
	 * to disable or enable all the elements with in the block
	 */
	checkingModuleSelection : function(element) {
		var instance = this;
		var module = jQuery(element).val();
		var moduleBlock = jQuery(element).closest('.accordion-group').find('#'+module+'_FieldInfo');
		if(jQuery(element).is(':checked')) {
			instance.removeDisableAttr(moduleBlock);
		} else {
			instance.addDisableAttr(moduleBlock);
		}
	},

	registerForReferenceField : function() {
		var referenceField = jQuery('.reference', container);
		if(referenceField.length > 0) {
			jQuery('#ProjectModule').attr('readonly', 'readonly');
		}
	},

	/**
	 * Function which will register all the events
	 */
	registerEvents : function() {
		this._super();
		var detailContentsHolder = this.getContentHolder();
		var thisInstance = this;

		detailContentsHolder.on('click','.moreRecentContacts', function(){ 
			var recentContactsTab = thisInstance.getTabByLabel(thisInstance.detailViewRecentContactsLabel); 
			recentContactsTab.trigger('click'); 
		}); 

		detailContentsHolder.on('click','.moreRecentProducts', function(){ 
			var recentProductsTab = thisInstance.getTabByLabel(thisInstance.detailViewRecentProductsTabLabel); 
			recentProductsTab.trigger('click'); 
		});
	}
});
