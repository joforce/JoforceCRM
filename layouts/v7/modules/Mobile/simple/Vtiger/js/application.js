/*************************************************************************************
 ** The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): JoForce.com
 *
 **************************************************************************************/
window.mobileapp = angular.module('mobileapp', ['ngMaterial', 'ngTouch', 'ngAnimate']);
mobileapp.factory('$api', function ($http) {
    var APIBASE = 'api.php', APIVERSION = 'v2';

    return function (operation, params, next) {
        if (typeof params == 'function') {
            next = params;
            params = {};
        }
        if (typeof params == 'undefined')
            params = {};

        params._operation = operation;

        var options = {};
        options.method = 'POST';
        options.url = APIBASE;
        options.data = params;
        options.headers = {'X-API-VERSION': APIVERSION};

        $http(options).success(function (data, status, headers, config) {
            if (next) {
                next(!data.success ? new Error(data.error.message) : null,
                        data.success ? data.result : null);
            }
        });
    };
});
