Head_Edit_Js("VTPDFMaker_Edit_Js",{},{
	
	/**
	 * Function to register event for ckeditor for description field
	 */
	registerEventForCkEditor : function(){
		var templateContentElement = jQuery("#templatecontent");
		var templateContentElementHeader = jQuery("#templatecontent-header");
		var templateContentElementFooter = jQuery("#templatecontent-footer");
		if(templateContentElement.length > 0 || templateContentElementHeader.length > 0 || templateContentElementFooter.length > 0) {
			var ckEditorInstance = new Head_CkEditor_Js();
			ckEditorInstance.loadCkEditor(templateContentElement);
                        ckEditorInstance.loadCkEditor(templateContentElementHeader);
                        ckEditorInstance.loadCkEditor(templateContentElementFooter);
		}
		this.registerFillTemplateContentEvent();
	},
	
	/**
	 * Function which will register module change event
	 */
	registerChangeEventForModule : function(){
		var thisInstance = this;
		var advaceFilterInstance = Head_AdvanceFilter_Js.getInstance();
		var filterContainer = advaceFilterInstance.getFilterContainer();
		filterContainer.on('change','select[name="modulename"]',function(e){
			var selected_module = $('#moduleName').val();
			thisInstance.loadFields('modulename', 'templateFields');
		});
	},

	/**
	  * Function to add fields to ckeditor for date, terms and conditions field
	  */	
        addFields : function(){
                var thisInstance = this;
                var advaceFilterInstance = Head_AdvanceFilter_Js.getInstance();
                var filterContainer = advaceFilterInstance.getFilterContainer();
                filterContainer.on('change','select[name="termsandconditions"]',function(e){
                        var value = $('#termsandconditions').val();
			if(value != 'Select'){
		              templatecontenttype = $('#textarea-type').val();
	                      CKEDITOR.instances[templatecontenttype].insertHtml('$terms-and-condition$');

			}
                });

                filterContainer.on('change','select[name="currentdate_time"]',function(e){
			var value = $('#currentdate_time').val();
			if(value != 'Select'){
                              templatecontenttype = $('#textarea-type').val();
	                      CKEDITOR.instances[templatecontenttype].insertHtml('$custom-'+value+'$');

			}
                });

                filterContainer.on('change','select[name="companyFields"]',function(e){
                        var value = $('#companyFields').val();
			if(value == 'logo'){
			    var url = document.location.origin;
			    var first = $(location).attr('pathname');
			    first = first.split("/index.php")[0];
			    var site_url = url + first;
                            templatecontenttype = $('#textarea-type').val();
			    var logo = $('#logo').val();
                            CKEDITOR.instances[templatecontenttype].insertHtml('<img style="width:200px;" src="'+site_url+'/test/logo/'+logo+'">');
			}
			else if(value != 'Select'){
                              templatecontenttype = $('#textarea-type').val();
        	              CKEDITOR.instances[templatecontenttype].insertHtml('$company-'+value+'$');

			}
		});

                filterContainer.on('change','select[name="product_block"]',function(e){
                        var value = $('#product_block').val();
                        if(value != 'Select'){
                              templatecontenttype = $('#textarea-type').val();
                              CKEDITOR.instances[templatecontenttype].insertHtml(value);

                        }
                });

                filterContainer.on('change','select[name="product_tax_block"]',function(e){
                        var value_type = $('#product_tax_block').val();
			if(value_type == 'group'){
			     var value = '<table border="1" cellpadding="1" cellspacing="1" style="width:500px;"><tbody><tr><td><strong>Sno.</strong></td><td><strong>Product Name</strong></td><td><strong>Quantity</strong></td><td><strong>List Price</strong></td><td><strong>Total</strong></td></tr><tr><td colspan="5">$productblock_start$</td></tr><tr><td>$productblock_sno$</td><td>$products-productname$</td><td>$products-quantity$</td><td>$products-listprice$</td><td>$products-total$</td></tr><tr><td colspan="5">$productblock_end$</td></tr><tr><td colspan="4" rowspan="1">Items Total</td><td>$pdt-subtotal$</td></tr><tr><td colspan="4" rowspan="1">Discount</td><td>$pdt-discount_amount$</td></tr><tr><td colspan="4" rowspan="1">Tax</td><td>$pdt-tax_totalamount$</td></tr><tr><td colspan="4" rowspan="1"><span class="pull-right">Shipping & Handling Charges</span></td><td>$pdt-s_h_amount$</td></tr><tr><td colspan="4" rowspan="1">Taxes For Shipping and Handling</td><td>$pdt-shtax_totalamount$</td></tr><tr><td colspan="4" rowspan="1">Adjustment</td><td>$pdt-adjustment$</td></tr><tr><td colspan="4" rowspan="1">Grand Total</td><td>$pdt-total$</td></tr></tbody></table><br />'

			}
			else if(value_type == 'individual'){
                             var value = '<table border="1" cellpadding="1" cellspacing="1" style="width:500px;"><tbody><tr><td><strong>Sno.</strong></td><td><strong>Product Name</strong></td><td><strong>Quantity</strong></td><td><strong>List Price</strong></td><td><strong>Total</strong></td></tr><tr><td colspan="5">$productblock_start$</td></tr><tr><td>$productblock_sno$</td><td>$products-productname$</td><td>$products-quantity$</td><td>$products-listprice$</td><td>$products-total$</td></tr><tr><td colspan="5">$productblock_end$</td></tr><tr><td colspan="4" rowspan="1">Items Total</td><td>$pdt-subtotal$</td></tr><tr><td colspan="4" rowspan="1">Discount</td><td>$pdt-discount_amount$</td></tr><tr><td colspan="4" rowspan="1"><span class="pull-right">Shipping & Handling Charges</span></td><td>$pdt-s_h_amount$</td></tr><tr><td colspan="4" rowspan="1">Taxes For Shipping and Handling</td><td>$pdt-shtax_totalamount$</td></tr><tr><td colspan="4" rowspan="1">Adjustment</td><td>$pdt-adjustment$</td></tr><tr><td colspan="4" rowspan="1">Grand Total</td><td>$pdt-total$</td></tr></tbody></table><br />'
			}	
                        if(value_type != 'Select'){
                              templatecontenttype = $('#textarea-type').val();
                              CKEDITOR.instances[templatecontenttype].insertHtml(value);

                        }
                });


                        thisInstance.loadFields('Products', 'product_fields');
                        thisInstance.loadFields('Services', 'service_fields');
        },

	registerChangeEventForCompany : function(){
                var thisInstance = this;
                var advaceFilterInstance = Head_AdvanceFilter_Js.getInstance();
                var filterContainer = advaceFilterInstance.getFilterContainer();
                filterContainer.on('change','select[name="companydetails"]',function(e){
                    var options = "<option value='organizationname'>Company Name</option><option value='address'>Address</option><option value='city'>City</option><option value='state'>State</option><option value='country'>Country</option><option value='code'>Code</option><option value='phone'>Phone</option><option value='fax'>Fax</option><option value='website'>Website</option><option value='logo'>Logo</option><option value='vatid'>Vatid</option>";
                    var fieldSelectElement = jQuery('select[name="companyFields"]');
                    fieldSelectElement.empty().html(options).trigger("liszt:updated");
                    return fieldSelectElement;
		});
	},

	/**
	 * Function to load condition list for the selected field
	 * @params : fieldSelect - select element which will represents field list
	 * @return : select element which will represent the condition element
	 */
	loadFields : function(name, fields) {
		if(name == 'Products' || name == 'Services'){
                        var moduleName = name;
		}
		else{
			var moduleName = jQuery('select[name='+name+']').val();
		}
		var allFields = jQuery('[name="moduleFields"]').data('value');
		var fieldSelectElement = jQuery('select[name='+fields+']');
		var options = '';
		for(var key in allFields) {
			//IE Browser consider the prototype properties also, it should consider has own properties only.
			if(allFields.hasOwnProperty(key) && key == moduleName) {
				var moduleSpecificFields = allFields[key];
				var len = moduleSpecificFields.length;
				for (var i = 0; i < len; i++) {
					var fieldName = moduleSpecificFields[i][0].split(':');
					if(name == 'Products' && fieldName[1] == '(Vendors)Vendor Name' || name == 'Services' && fieldName[1] == '(Users)User Name'){
						break;
					}
					options += '<option value="'+moduleSpecificFields[i][1]+'"';
					if(fieldName[0] == moduleName) {
						options += '>'+fieldName[1]+'</option>';
					} else {
						options += '>'+moduleSpecificFields[i][0]+'</option>';
					}
				}
			}
		}
		if(options == '')
			options = '<option value="">NONE</option>';
		
		fieldSelectElement.empty().html(options).trigger("liszt:updated");
		return fieldSelectElement;
		
	},
	
	registerFillTemplateContentEvent : function() {
		jQuery('#templateFields').change(function(e){
              templatecontenttype = $('#textarea-type').val();
			var value = jQuery(e.currentTarget).val();
                        CKEDITOR.instances[templatecontenttype].insertHtml(value);

		});

                jQuery('#product_fields').change(function(e){
              templatecontenttype = $('#textarea-type').val();

                        var value = jQuery(e.currentTarget).val();
                        CKEDITOR.instances[templatecontenttype].insertHtml(value);

                });

                jQuery('#service_fields').change(function(e){
              templatecontenttype = $('#textarea-type').val();

                        var value = jQuery(e.currentTarget).val();
                        CKEDITOR.instances[templatecontenttype].insertHtml(value);

                });

                jQuery('#header_footer').change(function(e){
              templatecontenttype = $('#textarea-type').val();

                        var value = jQuery(e.currentTarget).val();
  			CKEDITOR.instances[templatecontenttype].insertHtml(value);
                });


	},

	/**
	 * Registered the events for this page
	 */
	registerEvents : function() {
		this.registerEventForCkEditor();
		this.registerChangeEventForModule();
		this.addFields();
		this.registerChangeEventForCompany();
		//jQuery('#EditView').validationEngine();
		this._super();
	}
});

