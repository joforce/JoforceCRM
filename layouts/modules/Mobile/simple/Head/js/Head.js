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
mobileapp.controller('HeadBodyController', function ($scope, $api, $mdUtil, $mdSidenav) {

    $scope.userinfo = null;
    $scope.defaultApp  = null;
    $scope.dynamicTheme = null;
    $scope.modules = null;
    $scope.pageTitle = "Dashboard";

    /* Use this function when you aren't sure to $apply or $digest */
    function scopeApply(fn) {
        $scope.$$phase ? fn() : $scope.$apply(fn);
    }
    
    $scope.init = function () {
        $api('userInfo', function (e, r) {
            if (r) {
                scopeApply(function () {
                    $scope.userinfo = r.userinfo;
                    $scope.apps = r.apps;
                    $scope.menus = r.menus;
                    $scope.edition = r.edition;
                    $scope.selectedApp = r.defaultApp.toUpperCase();
                    $scope.dynamicTheme = r.defaultApp.toUpperCase();
                    $scope.$root.$emit('UserInfo.Changed');
                });
            }
        });
    };
    
    $scope.navigationToggle = (function () {
        return $mdUtil.debounce(function () {
            $mdSidenav('left').toggle();
        }, 200);
    })();
    
    $scope.$watch('selectedApp', function(newValue, oldValue){
        
        if (newValue !== oldValue) {
            $scope.dynamicTheme = newValue.toUpperCase();//r.defaultApp.toUpperCase();
        }
    });
    
    $scope.loadList = function(module){
        window.location.href = "index.php?module="+module+"&view=List&app="+$scope.selectedApp;
        $scope.pageTitle = module;
    };

    $scope.logout = function () {
        $api('logout', function (e, r) {
            if (r) {
                window.location.reload();
            }
        });
    };
      
});

