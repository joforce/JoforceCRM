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
    jQuery.Class('Settings_Pipeline_Js', {}, {

        getContainer: function () {
            return jQuery('.settingsPageDiv');
        },

        getModuleName: function () {
            return 'Pipeline';
        },

        /**
         * Function to delete the pipeline
         **/
        registerDeleteAction: function (container) {
            var thisInstance = this;
            container.on('click', '.pipeline-delete', function (e) {
                var message = app.vtranslate('LBL_DELETE_CONFIRMATION');
                var element = jQuery(e.currentTarget);
                var pipeline_id = $(this).data('id');
                app.helper.showConfirmationBox({
                    'message': message
                }).then(function () {
                    var params = {
                        module: app.getModuleName(),
                        parent: app.getParentModuleName(),
                        action: 'SaveAjax',
                        mode: 'delete',
                        pipeline_id: pipeline_id,
                    }
                    app.helper.showProgress();
                    app.request.post({
                        data: params
                    }).then(function (err, data) {
                        app.helper.hideProgress();
                        app.helper.showSuccessNotification({
                            message: app.vtranslate('JS_PIPELINE_REMOVED')
                        });
                        $('#kanba_pipeline_' + pipeline_id).remove();
                    });
                });
            });
        },

        /**
         * Function to register the module change event for module picklists
         */
        registerModulesChangeEvent: function (container) {
            var thisInstance = this;
            picklistContentsDiv = $("#pipeline-select");
            fieldContentsDiv = $('#role2fieldnames');

            vtUtils.showSelect2ElementView(container.find('[name="lanugageEditorModules"]'));
            vtUtils.showSelect2ElementView(container.find('[name="lanugageEditorLanguages"]'));

            container.on('change', '[name="kanban-module"]', function (e) {
                var currentTarget = jQuery(e.currentTarget);
                var selectedModule = $(this).val();

                if (selectedModule == '') {
                    picklistContentsDiv.html('');
                    fieldContentsDiv.html('');
                    return false;
                }
                thisInstance.getModuleRelatedPicklist(selectedModule).then(function (data) {
                    picklistContentsDiv.html(data.picklists);
                    fieldContentsDiv.html(data.fields);
                });
            });
        },

        /**
         * Function to get the module related picklist
         */
        getModuleRelatedPicklist: function (selectedModule) {
            var thisInstance = this;
            var aDeferred = jQuery.Deferred();
            app.helper.showProgress();

            var params = {};
            params['module'] = app.getModuleName();
            params['parent'] = app.getParentModuleName();
            params['view'] = 'Ajax';
            params['mode'] = 'getpicklist',
                params['moduleName'] = selectedModule;

            app.request.get({
                'data': params
            }).then(function (err, data) {
                app.helper.hideProgress();
                if (err === null) {
                    result = data;
                    aDeferred.resolve(data);
                } else {
                    aDeferred.reject();
                }
            });
            return aDeferred.promise();
        },

        registerLimitNumberofFields: function (container) {
            $("#role2fieldnames").select2({
                maximumSelectionSize: 3,
                closeOnSelect: false
            });
        },

        /**
         *  Function to save the pipeline
         **/
        registerPipelineSaveEvents: function () {
            var self = this;

            var container = $('#EditViewPipeline');
            var form = $('.pipeline-action-form');

            container.find('[type="submit"]').on('click', function (e) {
                var module_name = $("#kanban-module").val();
                var picklistname = $("#pipe-picklists").val();
                var pipelineid = $("#pipelineid").val();
                var records_per_page = jQuery('#records_per_page').val();
                var fields = $('#role2fieldnames').val();

                if (records_per_page == null || records_per_page == undefined || records_per_page == 0 || records_per_page < 100) {
                    records_per_page = 100;
                } else if (records_per_page !== null && records_per_page !== undefined && records_per_page > 1000) {
                    records_per_page = 1000;
                }

                if (picklistname.length > 0 && module_name.length > 0 && fields.length > 0) {
                    if (picklistname == 'Select' || module_name == 'Select' || module_name == null || picklistname == null) {
                        app.helper.showAlertNotification({
                            message: app.vtranslate('JS_CHOOSE_ALL')
                        });
                        return false;
                    }
                    form.submit();
                } else {
                    app.helper.showAlertNotification({
                        'message': app.vtranslate('JS_CHOOSE_ALL')
                    });
                }
            });
        },

        /**
         * Function to register events
         **/
        registerEvents: function () {
            var container = this.getContainer();
            this.registerDeleteAction(container);

            if ($('#EditViewPipeline').length) {
                var editview_contaner = $('#EditViewPipeline');
                this.registerModulesChangeEvent(editview_contaner);
                this.registerPipelineSaveEvents(editview_contaner);
                this.registerLimitNumberofFields(editview_contaner);
            }
        }
    });

    window.onload = function () {
        var settingsPipelineInstance = new Settings_Pipeline_Js();
        settingsPipelineInstance.registerEvents();
    };
})