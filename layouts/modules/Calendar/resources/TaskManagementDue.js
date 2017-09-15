Head_Index_Js("Head_TaskManagementDue_Js",{},{

        overlayContainer : false,
        getOverlayContainer : function(){
                if(this.overlayContainer === false){
                        this.overlayContainer = jQuery('#taskManagementDueContainer');
                }
                return this.overlayContainer;
        },

        getModuleName : function(){
                return "Calendar";
        },
        getColors : function(){
          return jQuery('input[name="colors"]').val();
        },

	registerTaskManagementByDue : function (){

	},
        registerEvents : function(){
alert('TEST');
                var thisInstance = this;
//              this.loadContents();
              this.registerTaskManagementByDue();
	});
