/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/

Head_Index_Js("Settings_ExtensionStore_ExtensionStore_Js", {
    showPopover: function (e) {
        var ele = jQuery(e);
        var options = {
            placement: ele.data('position'),
            trigger: 'hover'
        };
        ele.popover(options);
    }
}, {
    /**
     * Function to get import module index params
     */
    getImportModuleIndexParams: function () {
        var params = {
            'module': app.getModuleName(),
            'parent': app.getParentModuleName(),
            'view': 'ExtensionStore'
        };
        return params;
    },
    /**
     * Function to get import module with respect to view
     */
    getImportModuleStepView: function (params) {
        var aDeferred = jQuery.Deferred();
        app.helper.showProgress();
        app.request.post({
            data: params
        }).then(
            function (error, data) {
                app.helper.hideProgress();
                if (error) {
                    aDeferred.reject(error);
                }
                aDeferred.resolve(data);
            }
        );
        return aDeferred.promise();
    },
    /**
     * Function to register raty
     */
    registerRaty: function () {
        jQuery('.rating').raty({
            score: function () {
                return this.getAttribute('data-score');
            },
            readOnly: function () {
                return this.getAttribute('data-readonly');
            }
        });
    },
    /**
     * Function to register event for index of import module
     */
    registerEventForIndexView: function () {
        this.registerRaty();
        var detailContentsHolder = jQuery('.contentsDiv');
        jQuery('.descriptions').each(function (index, value) {
            var description = value.innerText;
            var shortText = jQuery.trim(description).substring(0, 250)
                .split(" ").slice(0, -1).join(" ") + "...";
            var scrollHeight = value.scrollHeight;
            if (scrollHeight > 250) {
                value.innerText = shortText;
            }
        });
        //app.helper.showScroll(jQuery('.descriptions'), {'height': '150px', 'width': '100%'});
        this.registerEventsForExtensionStore(detailContentsHolder);
    },

    getContainer: function () {
        return jQuery('.contentsDiv');
    },
    /**
     * Function to register event related to Import extrension Modules in index
     */
    registerEventsForExtensionStore: function (container) {
        var thisInstance = this;
        jQuery(container).find('.moreDetails, .buy').on('click', function (e) {
            let element = jQuery(e.currentTarget);
            let url = element.data('url');
            window.open(url);
            return;
        });
        jQuery("label").hover(function () {
            jQuery(this).css("color", "white");
            jQuery(this).css("cursor", "pointer");
        }, function () {
            jQuery(this).css("color", "");
        });
    },


    registerEventForSearchExtension: function (container) {
        var thisInstance = this;
        container.on('change', '#searchNewExtn', function (e) {

            var currentTarget = jQuery(e.currentTarget);
            if (e.which === 13) {
                alert("search item");
                var searchTerm = jQuery.trim(currentTarget.val());
                if (!searchTerm) {
                    alert(app.vtranslate('JS_PLEASE_ENTER_SOME_VALUE'));
                    currentTarget.focus();
                    return false;
                }
                var params = {
                    'module': app.getModuleName(),
                    'parent': app.getParentModuleName(),
                    'view': 'ExtensionStore',
                    'mode': 'searchNewExtn',
                    'searchTerm': searchTerm,
                    'type': 'Extension'
                };

                app.helper.showProgress();
                app.request.post({
                    data: params
                }).then(
                    function (error, data) {
                        app.helper.hideProgress();
                        jQuery('#extensionContainer').html(data);
                        thisInstance.registerEventForIndexView();
                    }
                );
            }
        });
    },
    updateTrialStatus: function (trialStatus, extensionName) {
        var trialParams = {
            'module': app.getModuleName(),
            'parent': app.getParentModuleName(),
            'action': 'Basic',
            'mode': 'updateTrialMode',
            'extensionName': extensionName
        };
        if (trialStatus) {
            trialParams['trial'] = 1;
        } else {
            trialParams['trial'] = 0;
        }
        this.getImportModuleStepView(trialParams).then(function (data) {
            return data;
        });
    },
    installExtension: function (e) {
        var thisInstance = this;
        var element = jQuery(e.currentTarget);
        thisInstance.ExtensionDetails(element);
    },
    /**
     * Function to download Extension
     */
    ExtensionDetails: function (element) {
        var thisInstance = this;
        var extensionContainer = element.closest('.extension_container');
        var extensionId = extensionContainer.find('[name="extensionId"]').val();
        var moduleAction = extensionContainer.find('[name="moduleAction"]').val();
        var extensionName = extensionContainer.find('[name="extensionName"]').val();
        var params = {
            'module': app.getModuleName(),
            'parent': app.getParentModuleName(),
            'view': 'ExtensionStore',
            'mode': 'detail',
            'extensionId': extensionId,
            'moduleAction': moduleAction,
            'extensionName': extensionName
        };

        this.getImportModuleStepView(params).then(function (data) {
            var detailContentsHolder = jQuery('.contentsDiv');
            detailContentsHolder.html(data);
            thisInstance.registerEventsForExtensionStoreDetail(detailContentsHolder);
        });
    },
    /**
     * Function to register event related to Import extrension Modules in detail
     */
    registerEventsForExtensionStoreDetail: function (container) {
        var container = jQuery(container);
        var thisInstance = this;
        this.registerRaty();
        jQuery('.carousel').carousel({
            interval: 3000
        });

        container.find('#declineExtension').on('click', function () {
            var params = thisInstance.getImportModuleIndexParams();
            thisInstance.getImportModuleStepView(params).then(function (data) {
                var detailContentsHolder = jQuery('.contentsDiv');
                detailContentsHolder.html(data);
                thisInstance.registerEventForIndexView();
            });
        });

        container.off().on('click', '.writeReview', function (e) {
            var customerReviewModal = jQuery(container).find('.customerReviewModal').clone(true, true);
            customerReviewModal.removeClass('hide');

            var callBackFunction = function (data) {
                var form = data.find('.customerReviewForm');
                form.find('.rating').raty();
                var params = {
                    submitHandler: function (form) {
                        var form = jQuery(form);
                        if (this.numberOfInvalids() > 0) {
                            return false;
                        }
                        var review = form.find('[name="customerReview"]').val();
                        var listingId = form.find('[name="extensionId"]').val();
                        var rating = form.find('[name="score"]').val();
                        var params = {
                            'module': app.getModuleName(),
                            'parent': app.getParentModuleName(),
                            'action': 'Basic',
                            'mode': 'postReview',
                            'comment': review,
                            'listing': listingId,
                            'rating': rating
                        }
                        app.helper.showProgress();
                        app.request.post({
                            data: params
                        }).then(function (error, result) {
                            app.helper.hideModal();
                            if (!error) {
                                if (result) {
                                    var html = '<div class="row" style="margin: 8px 0 15px;">' +
                                        '<div class="col-sm-3 col-xs-3">' +
                                        '<div data-score="' + rating + '" class="rating" data-readonly="true"></div>' +
                                        '<div>' + result.Customer.firstname + ' ' + result.Customer.lastname + '</div>' +
                                        '<div class="muted">' + (result.createdon).substring(4) + '</div>' +
                                        '</div>' +
                                        '<div class="col-sm-9 col-xs-9">' + result.comment + '</div>' +
                                        '</div><hr>';
                                    container.find('.customerReviewContainer').append(html);
                                    thisInstance.registerRaty();
                                }
                                app.helper.hideProgress();
                            } else {
                                app.helper.hideProgress();
                                app.helper.showErrorNotification({
                                    "message": error
                                });
                                return false;
                            }
                        });
                    }
                };
                form.vtValidate(params);
            }
            var params = {};
            params.cb = callBackFunction;
            app.helper.showModal(customerReviewModal, params);
        });
    },

    registerExtensionTabs: function (container) {
        var thisInstance = this;
        container.on('click', '.extensionTab', function (e) {
            var element = jQuery(e.currentTarget);
            var params = {
                'module': app.getModuleName(),
                'parent': app.getParentModuleName(),
                'view': 'ExtensionStore',
                'mode': 'getExtensionByType',
                'type': element.data('type')
            };

            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(
                function (error, data) {
                    jQuery('.extensionTab').removeClass('active').removeClass('btn-primary');
                    element.addClass('active').addClass('btn-primary');
                    app.helper.hideProgress();
                    jQuery('#extensionContainer').html(data);
                    thisInstance.registerEventForIndexView();
                }
            );
        });
    },

    registerEvents: function () {
        var container = jQuery('.contentsDiv');
        this._super();
        this.registerEventForIndexView();
        this.registerEventForSearchExtension(container);
        this.registerExtensionTabs(container);
    }
});

jQuery(document).ready(function () {
    var settingExtensionStoreInstance = new Settings_ExtensionStore_ExtensionStore_Js();
    var mode = jQuery('[name="mode"]').val();
    if (mode == 'detail') {
        settingExtensionStoreInstance.registerEventsForExtensionStoreDetail(jQuery('.contentsDiv'));
    }
});
$("#searchNewExtn").keyup(function () {

    // Retrieve the input field text and reset the count to zero
    var filter = $(this).val(),
        count = 0;

    // Loop through the comment list
    $('#extension-store div.col-lg-4.col-md-6.col-sm-6').each(function () {


        // If the list item does not contain the text phrase fade it out
        if ($(this).text().search(new RegExp(filter, "i")) < 0) {
            $(this).hide(); // MY CHANGE

            // Show the list item if the phrase matches and increase the count by 1
        } else {
            $(this).show(); // MY CHANGE
            count++;
        }

    });

});