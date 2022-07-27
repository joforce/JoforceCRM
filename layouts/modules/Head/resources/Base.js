/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/
Head.Class('Head_Base_Js', {}, {

    _components: {},

    addComponents: function () {},

    init: function () {
        this.addComponents();
    },

    intializeComponents: function () {
        for (var componentName in this._components) {
            var componentInstance = this._components[componentName];
            componentInstance.registerEvents();
        }
    },
}); // tooltip class jquery
$(document).ready(function () {

    $('.alertShow1').hover(
        function () {
            $('.tooltiptext1').css("visibility", "visible")
        },
        function () {
            $('.tooltiptext1').css("visibility", "hidden")
        }

    )

    $('.alertShow').hover(
        function () {
            $('.tooltiptext').css("visibility", "visible")
        },
        function () {
            $('.tooltiptext').css("visibility", "hidden")
        }

    )
    var height_div = $(".commentContainer.commentsRelatedContainer").height();
    //alert(height_div); 
    if (height_div <= 450) {
        $('.showcomments').parent().addClass('max-height-comments');
    } else if (height_div >= 650) {
        $('.showcomments').parent().removeClass('max-height-comments');
    }
    var height_div1 = $("#detailView").height();
    //alert(height_div1); 
    if (height_div1 <= 450) {
        $('#detailView').parent().addClass('max-height-comments');
    } else if (height_div1 >= 650) {
        $('#detailView').parent().removeClass('max-height-comments');
    }
})