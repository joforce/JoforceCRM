/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

mobileapp.controller('VtigerEditController', function ($scope, $api, $mdToast, $animate) {
    var url = jQuery.url();
    $scope.module = url.param('module');
    $scope.record = url.param('record');
    $scope.describeObject = null;
    $scope.fields = null;
    $scope.createable = null;
    $scope.updateable = null;
    $scope.deleteable = null;
    $scope.fieldsData = null;
    $scope.editdata = [];

    $api('describe', {module: $scope.module}, function (e, r) {
        $scope.describeObject = r.describe;
        $scope.fields = $scope.describeObject.fields;
        $scope.createable = $scope.describeObject.createable;
        $scope.updateable = $scope.describeObject.updateable;
        $scope.deleteable = $scope.describeObject.deleteable;
        $scope.loadFields();
    });

    $scope.gobacktoUrl = function () {
        window.history.back();
    };
    $scope.loadFields = function () {
        $api('fetchRecord', {module: $scope.module, record: $scope.record}, function (e, r) {

            var processedData = [];
            for (var index in $scope.fields) {
                var value = r.record[$scope.fields[index].name];
                if (typeof value === 'object') {
                    processedData.push({label: $scope.fields[index].label, value: value.label, name: $scope.fields[index].name, editable: $scope.fields[index].editable, mandatory: $scope.fields[index].mandatory});

                } else {
                    processedData.push({label: $scope.fields[index].label, value: value, name: $scope.fields[index].name, editable: $scope.fields[index].editable, mandatory: $scope.fields[index].mandatory});
                }
            }
            $scope.fieldsData = processedData;
        });
    };
    $scope.saveThisRecord = function () {
        $scope.editdata = {};
        for (var index in $scope.fieldsData) {
            $scope.editdata[$scope.fieldsData[index].name] = $scope.fieldsData[index].value;
        }
        $api('saveRecord', {module: $scope.module, record: $scope.record, values: $scope.editdata}, function (e, r) {
            console.log(r);
            var toast = $mdToast.simple().content('Record Saved Successfully!'). position($scope.getToastPosition()).hideDelay(1000);
            $mdToast.show(toast);

        });

    };

    $scope.toastPosition = {
        bottom: true,
        top: false,
        left: false,
        right: true
    };
    $scope.getToastPosition = function () {
        return Object.keys($scope.toastPosition)
                .filter(function (pos) {
                    return $scope.toastPosition[pos];
                })
                .join(' ');
    };
});
