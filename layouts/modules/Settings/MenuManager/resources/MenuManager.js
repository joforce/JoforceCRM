/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *************************************************************************************/
$(document).ready(function () {
    jQuery.Class('Settings_MenuManager_Js', {}, {

        getContainer: function () {
            return jQuery('.settingsmenu-starts');
        },

        registerAddModule: function (container) {
            var thisInstance = this;
            container.on('click', '.menuEditorAddItem', function (e) {
                var element = jQuery(e.currentTarget);
                var params = {
                    module: app.getModuleName(),
                    parent: app.getParentModuleName(),
                    view: 'EditAjax',
                    mode: 'showAddModule',
                    appname: element.data('appname')
                }
                app.helper.showProgress();
                app.request.post({
                    data: params
                }).then(function (err, data) {
                    app.helper.hideProgress();
                    app.helper.showModal(data, {
                        cb: function (data) {
                            thisInstance.registerAddModulePreSaveEvents(data);
                        }
                    });
                });
            });
        },

        setSaveButtonState: function (container) {
            var appname = container.find('#appname').val();
            if (!container.find('.modulesContainer[data-appname=' + appname + ']').find('.addModule').length) {
                container.find('[type="submit"]').attr('disabled', 'disabled');
            } else {
                container.find('[type="submit"]').removeAttr('disabled');
            }
        },

        registerAddModulePreSaveEvents: function (data) {
            var self = this;
            var container = data.find('.addModuleContainer');

            container.on('click', '.addModule', function (e) {
                var element = jQuery(e.currentTarget);
                element.toggleClass('selectedModule');
            });

            container.on('click', '.moduleSelection li a', function () {
                var selText = $(this).text();
                var appname = $(this).data('appname');
                $(this).parents('.btn-group').find('.dropdown-toggle').html(selText + '   <span class="caret"></span>');
                container.find('.modulesContainer').addClass('hide');
                container.find('.modulesContainer[data-appname=' + appname + ']').removeClass('hide').find('.addModule').removeClass('selectedModule');
                container.find('#appname').val(appname);
                self.setSaveButtonState(container);
            });

            self.setSaveButtonState(container);

            container.find("[name='saveButton']").on('click', function (e) {
                var modulesContainer = container.find('.modulesContainer').not('.hide');
                var modules = modulesContainer.find('.addModule');
                var selectedModules = modules.filter('.selectedModule');
                if (!selectedModules.length) {
                    app.helper.showAlertNotification({
                        'message': app.vtranslate('JS_PLEASE_SELECT_A_MODULE')
                    });
                } else {
                    jQuery(this).attr('disabled', 'disabled');
                    var appname = container.find('#appname').val();
                    var sourceModules = [];
                    selectedModules.each(function (i, element) {
                        var selectedModule = jQuery(element);
                        sourceModules.push(selectedModule.data('module'));
                    });

                    if (sourceModules.length) {
                        var params = {
                            module: app.getModuleName(),
                            parent: app.getParentModuleName(),
                            sourceModules: sourceModules,
                            appname: appname,
                            action: 'SaveAjax',
                            mode: 'addModule'
                        };
                        app.helper.showProgress();
                        app.request.post({
                            data: params
                        }).then(function (err, data) {
                            app.helper.showSuccessNotification({
                                message: app.vtranslate('JS_MODULE_ADD_SUCCESS')
                            });
                            app.helper.hideProgress();
                            window.location.reload();
                        });
                        app.helper.hideModal();
                    }
                }
            });
        },

        registerRemoveModule: function (container) {
            var thisInstance = this;
            container.on('click', '.menuEditorRemoveItem', function (e) {
                var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
                var element = jQuery(e.currentTarget);
                app.helper.showConfirmationBox({
                    'message': message
                }).then(function () {
                    thisInstance.removeModule(container, element);
                });
            });
        },

        removeModule: function (container, element) {
            var parent = element.closest('.modules');
            var params = {
                module: app.getModuleName(),
                parent: app.getParentModuleName(),
                action: 'SaveAjax',
                mode: 'removeModule',
                sourceModule: parent.data('module'),
                appname: parent.closest('.appContainer').data('appname')
            }

            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(function (err, data) {
                app.helper.hideProgress();
                element.closest('.modules').fadeOut(500, function () {
                    app.helper.showSuccessNotification({
                        message: app.vtranslate('JS_MODULE_REMOVED')
                    });
                    jQuery(this).remove();
                });
            });
        },

        registerSortModule: function (container) {
            var sortableElement = container.find('.sortable');
            var thisInstance = this;
            var stopSorting = false;
            var move = false;
            sortableElement.sortable({
                items: '.modules',
                'revert': true,
                receive: function (event, ui) {
                    move = true;
                    if (jQuery(ui.item).hasClass("noConnect")) {
                        stopSorting = true;
                        jQuery(ui.sender).sortable("cancel");
                    }
                },
                over: function (event, ui) {
                    stopSorting = false;
                },
                stop: function (e, ui) {
                    var element = jQuery(ui.item);
                    var parent = element.closest('.sortable');
                    parent.find('.menuEditorAddItem').appendTo(parent);
                    var appname = parent.data('appname');
                    var moduleSequenceArray = {}
                    jQuery.each(parent.find('.modules'), function (i, element) {
                        moduleSequenceArray[jQuery(element).data('module')] = ++i;
                    });
                    var moved = move;
                    if (move) {
                        move = false;
                    }
                    if (!stopSorting) {
                        thisInstance.saveSequence(moduleSequenceArray, appname, moved);
                    } else {
                        if (!element.hasClass('noConnect')) {
                            thisInstance.saveSequence(moduleSequenceArray, appname);
                        } else {
                            app.helper.showErrorNotification({
                                message: app.vtranslate('JS_MODULE_NOT_DRAGGABLE')
                            });
                        }
                    }
                }
            });
            sortableElement.disableSelection();
        },

        registerSortMainMenu: function (container) {
            var sortableElement = container.find('.sortable-main-menu');
            var thisInstance = this;
            var stopSorting = false;
            var move = false;
            sortableElement.sortable({
                items: '.main-menu-container',
                'revert': true,
                receive: function (event, ui) {
                    move = true;
                    if (jQuery(ui.item).hasClass("noConnect")) {
                        stopSorting = true;
                        jQuery(ui.sender).sortable("cancel");
                    }
                },
                over: function (event, ui) {
                    stopSorting = false;
                },
                stop: function (e, ui) {
                    var element = jQuery(ui.item);
                    var parent = element.closest('.sortable-main-menu');
                    parent.find('.main-menu-container').appendTo(parent);
                    var moduleSequenceArray = {}
                    jQuery.each(parent.find('.main-menu-container'), function (i, element) {
                        moduleSequenceArray[jQuery(element).data('menuname')] = ++i;
                    });
                    var moved = move;
                    if (move) {
                        move = false;
                    }
                    if (!stopSorting) {
                        thisInstance.saveMainMenuSequence(moduleSequenceArray, moved, 'saveSequence');
                    } else {
                        if (!element.hasClass('noConnect')) {
                            thisInstance.saveMainMenuSequence(moduleSequenceArray, '', 'saveSequence');
                        } else {
                            app.helper.showErrorNotification({
                                message: app.vtranslate('JS_MODULE_NOT_DRAGGABLE')
                            });
                        }
                    }
                }
            });
            sortableElement.disableSelection();
        },

        saveMainMenuSequence: function (moduleSequenceArray, move, mode) {
            var params = {
                module: app.getModuleName(),
                parent: app.getParentModuleName(),
                action: 'ChangeMainMenuSequenceAjax',
                mode: mode,
                sequence: JSON.stringify(moduleSequenceArray),
            }

            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(function (err, data) {
                if (move) {
                    app.helper.showSuccessNotification({
                        message: app.vtranslate('JS_MODULE_MOVED_SUCCESSFULLY')
                    });
                } else {
                    app.helper.showSuccessNotification({
                        message: app.vtranslate('JS_MODULE_SEQUENCE_SAVED')
                    })
                }
                app.helper.hideProgress();
                app.event.trigger('POST.MENU.MOVE', params);
            });
        },

        registerAddMainMenuModal: function (container) {
            var thisInstance = this;
            container.on('click', '.add-main-menu , .add-link', function (e) {
                var element = jQuery(e.currentTarget);
                var type = $(this).data('mode');
                var params = {
                    module: app.getModuleName(),
                    parent: app.getParentModuleName(),
                    view: 'AddMainMenu',
                    mode: 'ShowAddMenuModal',
                    type: type
                }
                app.helper.showProgress();
                app.request.post({
                    data: params
                }).then(function (err, data) {
                    app.helper.hideProgress();
                    app.helper.showModal(data, {
                        cb: function (data) {
                            thisInstance.registerSaveMainMenu(data);
                        }
                    });
                });
            });
        },

        registerSaveMainMenu: function (container) {
            var thisInstance = this;
            container.on('click', '#save-main-menu', function (e) {
                var element = jQuery(e.currentTarget);
                var type = $(this).data("type");
                if (type == "module") {
                    var tabid = $("#select-module").val();
                    if (!tabid.length) {
                        app.helper.showAlertNotification({
                            'message': app.vtranslate('JS_PLEASE_SELECT_A_MODULE')
                        });
                    } else {
                        jQuery(this).attr('disabled', 'disabled');
                    }

                    if (tabid.length) {
                        var params = {
                            module: app.getModuleName(),
                            parent: app.getParentModuleName(),
                            action: 'ChangeMainMenuSequenceAjax',
                            mode: 'addModule',
                            tabid: tabid,
                            type: type
                        }
                        app.helper.showProgress();
                        app.request.post({
                            data: params
                        }).then(function (err, data) {
                            app.helper.showSuccessNotification({
                                message: app.vtranslate('JS_MODULE_ADD_SUCCESS')
                            });
                            app.helper.hideProgress();
                        });

                        app.helper.hideModal();
                    }
                } else {
                    var linkname = $("#linkname").val();
                    var linkurl = $("#linkurl").val();
                    if (!linkname.length || !linkurl.length) {
                        app.helper.showAlertNotification({
                            'message': app.vtranslate('Please Enter values')
                        });
                    } else {
                        jQuery(this).attr('disabled', 'disabled');
                    }

                    if (linkname.length && linkurl.length) {
                        var params = {
                            module: app.getModuleName(),
                            parent: app.getParentModuleName(),
                            action: 'ChangeMainMenuSequenceAjax',
                            mode: 'addModule',
                            type: type,
                            linkname: linkname,
                            linkurl: linkurl
                        }
                        app.helper.showProgress();
                        app.request.post({
                            data: params
                        }).then(function (err, data) {
                            app.helper.showSuccessNotification({
                                message: app.vtranslate('JS_MODULE_ADD_SUCCESS')
                            });
                            app.helper.hideProgress();
                        });

                        app.helper.hideModal();
                    }
                }
                window.location.reload();
            });
        },

        registerRemoveMainMenu: function (container) {
            var thisInstance = this;
            container.on('click', '.remove-main-menu', function (e) {
                var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
                var element = jQuery(e.currentTarget);
                app.helper.showConfirmationBox({
                    'message': message
                }).then(function () {
                    thisInstance.removeMainMenu(container, element);
                });
            });
        },

        removeMainMenu: function (container, element) {
            var parent = element.closest('.main-menu-container');
            var params = {
                module: app.getModuleName(),
                parent: app.getParentModuleName(),
                action: 'ChangeMainMenuSequenceAjax',
                mode: 'removeModule',
                menuname: parent.data('menuname'),
            }

            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(function (err, data) {
                if (err == null) {
                    if (data.success == true) {
                        element.closest('.main-menu-container').fadeOut(500, function () {
                            jQuery(this).remove();

                            setTimeout(function () {
                                app.helper.hideProgress();
                                app.helper.showSuccessNotification({
                                    message: app.vtranslate('JS_MODULE_REMOVED')
                                });
                            }, 3000);
                        });
                    } else {
                        app.helper.hideProgress();
                        app.helper.showErrorNotification({
                            message: app.vtranslate('Something went wrong')
                        });
                    }
                } else {
                    app.helper.hideProgress();
                    app.helper.showErrorNotification({
                        message: app.vtranslate('Something went wrong')
                    });
                }
            });
        },

        saveSequence: function (moduleSequenceArray, appname, move) {
            var params = {
                module: app.getModuleName(),
                parent: app.getParentModuleName(),
                action: 'SaveAjax',
                mode: 'saveSequence',
                sequence: JSON.stringify(moduleSequenceArray),
                appname: appname
            }

            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(function (err, data) {
                if (move) {
                    app.helper.showSuccessNotification({
                        message: app.vtranslate('JS_MODULE_MOVED_SUCCESSFULLY')
                    });
                } else {
                    app.helper.showSuccessNotification({
                        message: app.vtranslate('JS_MODULE_SEQUENCE_SAVED')
                    })
                }
                app.helper.hideProgress();
                app.event.trigger('POST.MENU.MOVE', params);
            });
        },

        registerAddNewSection: function (container) {
            var thisInstance = this;
            container.on('click', '#add_new_menu_bar', function (e) {
                var element = jQuery(e.currentTarget);
                var params = {
                    module: app.getModuleName(),
                    parent: app.getParentModuleName(),
                    view: 'AddSection',
                }
                app.helper.showProgress();
                app.request.post({
                    data: params
                }).then(function (err, data) {
                    app.helper.hideProgress();
                    app.helper.showModal(data, {
                        cb: function (data) {
                            thisInstance.registerSaveSection(data);
                        }
                    });
                });
            });
        },

        registerSaveSection: function (container) {
            var thisInstance = this;
            container.on('click', '#save-section', function (e) {
                var section_name = $("#section-name").val();
                var module_id = $("#select-module").val();
                var icon_info = $("#icon-info").val();
                if (!icon_info.length) {
                    //var icon_info = "fa fa-eye";
                    var icon_info = '';
                }

                if (!section_name.length || !module_id.length) {
                    app.helper.showAlertNotification({
                        'message': app.vtranslate('JS_PLEASE_ENTER_VALUES')
                    });
                } else {
                    jQuery(this).attr('disabled', 'disabled');
                }

                if (section_name.length && module_id.length) {
                    var params = {
                        module: app.getModuleName(),
                        parent: app.getParentModuleName(),
                        action: 'SaveSection',
                        mode: 'addSection',
                        section_name: section_name,
                        tabid: module_id,
                        icon_info: icon_info
                    }
                    app.helper.showProgress();
                    app.request.post({
                        data: params
                    }).then(function (err, data) {
                        app.helper.showSuccessNotification({
                            message: app.vtranslate('JS_MODULE_ADD_SUCCESS')
                        });
                        app.helper.hideProgress();
                        window.location.reload();
                    });

                    app.helper.hideModal();
                }
            });
        },

        registerDeleteSectionEvent: function (container) {
            var thisInstance = this;
            container.on('click', '.delete-section', function (e) {
                var appName = $(this).parent().data('app-name');
                var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
                app.helper.showConfirmationBox({
                    'message': message
                }).then(function () {
                    thisInstance.deleteSection(appName, container);
                });
            });
        },

        deleteSection: function (appName, container) {
            var params = {
                module: app.getModuleName(),
                parent: app.getParentModuleName(),
                action: 'SaveSection',
                mode: 'deleteSection',
                appname: appName
            }

            app.helper.showProgress();
            app.request.post({
                data: params
            }).then(function (err, data) {
                app.helper.showSuccessNotification({
                    message: app.vtranslate('JS_SECTION_DELETED_SUCCESSFULLY')
                });
                app.helper.hideProgress();
                window.location.reload();
            });
        },

        registerEvents: function () {
            var container = this.getContainer();
            this.registerAddModule(container);
            this.registerRemoveModule(container);
            this.registerSortModule(container);
            this.registerSortMainMenu(container);
            this.registerRemoveMainMenu(container);
            this.registerAddMainMenuModal(container);
            this.registerSaveMainMenu(container);
            this.registerAddNewSection(container);
            this.registerSaveSection(container);
            this.registerDeleteSectionEvent(container);
            var instance = new Settings_Head_Index_Js();
            instance.registerBasicSettingsEvents();
        }

    });

    window.onload = function () {
        var settingMenuManagerInstance = new Settings_MenuManager_Js();
        settingMenuManagerInstance.registerEvents();
    };
});