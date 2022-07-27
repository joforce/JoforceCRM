/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

(function( $ ) {

  var ProgressIndicatorHelper = function() {
	var thisInstance = this;

	this.defaults = {
		'position' : 'append',
		'mode' : 'show',
		'blockInfo' : {
			'elementToBlock' : 'body'
		},
		'message' : ''
	}

	this.imageContainerCss = {
		'text-align' : 'center'
	}

	this.blockOverlayCSS = {
		'opacity' : '0.2'
	}

	this.blockCss = {
		'border':		'',
		'backgroundColor':'',
		'background-clip': 'border-box',
		'border-radius': '2px'
	}

	this.showTopCSS ={
		'left'	  : '48.2%',
		'position': 'fixed',
		'top'	  : '4.5%',
		'z-index' : '100000'
	}

	this.showOnTop = false;

	this.init = function(element, options){
		if(typeof options == 'undefined'){
			options = {};
		}

		thisInstance.options = $.extend(true, this.defaults, options);
		thisInstance.container = element;
		thisInstance.position = options.position;
		if(typeof options.imageContainerCss != 'undefined'){
			thisInstance.imageContainerCss = $.extend(true,this.imageContainerCss,options.imageContainerCss);
		}
		if(this.isBlockMode()) {
			thisInstance.elementToBlock = $(thisInstance.options.blockInfo.elementToBlock);
		}
		return this;
	}

	this.initActions = function() {
		if(this.options.mode == 'show'){
			this.show();
		}else if(this.options.mode == 'hide') {
			this.hide();
		}
	}
	
	this.getImagePath = function() {
		if(this.options.smallLoadingImage == true && typeof this.options.smallLoadingImage != 'undefined' ) {
			return app.vimage_path('loading.gif');
		} else {
			return app.vimage_path('loading.gif');
		}
	}

	this.isPageBlockMode = function() {
		if ( (typeof this.elementToBlock  != 'undefined' )&& this.elementToBlock.is('body')) {
			return true;
		}
		return false;
	}

	this.isBlockMode = function() {
		if((typeof this.options.blockInfo != 'undefined')  && (this.options.blockInfo.enabled==true)) {
			return true;
		}
		return false;
	}

	this.show = function(){
		// TODO use app.vimage_path
		var imagePath = this.getImagePath();
		var imageHtml = '<div class="imageHolder">'+
							'<img class="loadinImg alignMiddle" src="'+imagePath+'" />'+
						'</div>';
		var jQImageHtml = jQuery(imageHtml).css(this.imageContainerCss);
		if(thisInstance.options.message.length > 0) {
			var jQMessage = thisInstance.options.message;
			if(!(jQMessage instanceof jQuery)){
				jQMessage = jQuery('<span></span>').html(jQMessage)
			}
			var messageContainer = jQuery('<div class="message"></div>').append(jQMessage);
			jQImageHtml.append(messageContainer);
		}

		if(this.isBlockMode()) {
			jQImageHtml.addClass('blockMessageContainer');
		}

		switch(thisInstance.position) {
				case "prepend":
						thisInstance.container.prepend(jQImageHtml);
						break;
				case "html":
						thisInstance.container.html(jQImageHtml);
						break;
				case "replace":
						thisInstance.container.replaceWith(jQImageHtml);
						break;
				default:
					thisInstance.container.append(jQImageHtml);
		}
		if(this.isBlockMode()) {
			thisInstance.blockedElement = thisInstance.elementToBlock;
			if(thisInstance.isPageBlockMode()) {
				$.blockUI({
					'message' : thisInstance.container,
					'overlayCSS' : thisInstance.blockOverlayCSS,
					'css' : thisInstance.blockCss
				});
			}else{
				thisInstance.elementToBlock.block({
					'message' : thisInstance.container,
					'overlayCSS' : thisInstance.blockOverlayCSS,
					'css' : thisInstance.blockCss
				})
			}
		}

		if(thisInstance.showOnTop) {
			this.container.css(this.showTopCSS).appendTo('body');
		}
	}

	this.hide = function() {
		$('.imageHolder',this.container).remove();
		if(typeof this.blockedElement != 'undefined') {
			if(this.isPageBlockMode()) {
				$.unblockUI();
			}
			else{
				this.blockedElement.unblock();
			}
		}
		this.container.removeData('progressIndicator');
	}

  }

  $.fn.progressIndicator = function(options) {
	var element = this;
	if(this.length <= 0) {
		element = jQuery('body');
	}
	return element.each(function(index, element){
		var jQueryObject = $(element);
		if(typeof jQueryObject.data('progressIndicator') != 'undefined'){
			var progressIndicatorInstance = jQueryObject.data('progressIndicator');

		}else{
			var progressIndicatorInstance = new ProgressIndicatorHelper();
			jQueryObject.data('progressIndicator',progressIndicatorInstance);
		}
		progressIndicatorInstance.init(jQueryObject, options).initActions();
	});

  };

  $.progressIndicator = function(options) {
	  var progressImageContainer = jQuery('<div></div>');
	  var progressIndicatorInstance = new ProgressIndicatorHelper();
	  progressIndicatorInstance.init(progressImageContainer, options);
	  if(!progressIndicatorInstance.isBlockMode()) {
		  progressIndicatorInstance.showOnTop = true;
	  }
	  progressIndicatorInstance.initActions();
	  return progressImageContainer.data('progressIndicator',progressIndicatorInstance);
  }

  //Change the z-index of the block overlay value
  $.blockUI.defaults.baseZ = 10000;
})( jQuery );

