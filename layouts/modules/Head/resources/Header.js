/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

jQuery.Class("Head_Header_Js", {
   
    previewFile : function(e,recordId) {
        e.stopPropagation();
        var currentTarget = e.currentTarget;
        var currentTargetObject = jQuery(currentTarget);
        if(typeof recordId == 'undefined') {
            if(currentTargetObject.closest('tr').length) {
                recordId = currentTargetObject.closest('tr').data('id');
            } else {
                recordId = currentTargetObject.data('id');
            }
        }
        var fileLocationType = currentTargetObject.data('filelocationtype');
        var fileName = currentTargetObject.data('filename'); 
        if(fileLocationType == 'I'){
            var params = {
                module : 'Documents',
                view : 'FilePreview',
                record : recordId
            };
            app.request.post({"data":params}).then(function(err,data){
                app.helper.showModal(data);
            });
        } else {
            var win = window.open(fileName, '_blank');
            win.focus();
        }
    }
},{
});

$(document).ready(function(){
                $('.list-group .lists li.dropdown').hover(function(){
                        $(this).children('i.hide-icon').toggle();
                });

                $('.dropdown-filter .btn-filter').click(function(){
                    $('.filter-open').toggle();
                    /*$('.filter-open').modal();*/
                });

                $('.global-nav').on('click',function(){
                    $('.filter-open').hide();
                });

                $('.dropdown-filter').parent().siblings().click(function(){
                    $('.filter-open').hide();
                });
                
                $('.list-group .list-header').click(function(){
                        $(this).closest('i').toggleClass('fa-chevron-right').toggleClass('fa-chevron-down');
                        $(this).siblings('.lists').slideToggle();
                });
        });
