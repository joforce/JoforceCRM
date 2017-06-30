mobileapp.controller('VtigerDetailController', function ($scope, $api) {
    var url = jQuery.url();
    $scope.module = url.param('module');
    $scope.record = url.param('record');
    $scope.describeObject = null;
    $scope.fields = null;
    $scope.createable = null;
    $scope.updateable = null;
    $scope.deleteable = null;
    $scope.recordData = null;
    
    $api('describe', {module:$scope.module}, function(e, r) {
        $scope.describeObject = r.describe;
        $scope.fields = $scope.describeObject.fields;
        $scope.createable = $scope.describeObject.createable;
        $scope.updateable = $scope.describeObject.updateable;
        $scope.deleteable = $scope.describeObject.deleteable;
        $scope.loadRecord();
    });
    
    $scope.gobacktoUrl = function(){
        window.history.back();
    };
    
    $scope.loadRecord = function () {
        $api('fetchRecord', {module:$scope.module, record:$scope.record}, function(e,r) {
            
            var processedData = [];
            for(var index in $scope.fields) {
                var value = r.record[$scope.fields[index].name];
                if(typeof value === 'object') {
                    processedData.push({label:$scope.fields[index].label, value:value.label});
                    
                } else {
                    processedData.push({label:$scope.fields[index].label, value:value});
                }
            }
            
            $scope.recordData = processedData;
        });
    };
    
    $scope.detailViewEditEvent = function(id){
        window.location.href = "index.php?module=" + $scope.module + "&view=Edit&record="+$scope.record+"&app=" + $scope.selectedApp;
    };
    
    $scope.isUpdateable = function() {
        return ($scope.updateable)? true : false;
    };
    
    $scope.isDeleteable = function() {
        return ($scope.deleteable)? true : false;
    };
});


/** WIP inline EDIT Controller */
mobileapp.controller('InlineEditorController', function($scope){

	// $scope is a special object that makes
	// its properties available to the view as
	// variables. Here we set some default values:

	$scope.showtooltip = false;
	$scope.value = 'Edit me.';

	// Some helper functions that will be
	// available in the angular declarations

	$scope.hideTooltip = function(){

		// When a model is changed, the view will be automatically
		// updated by by AngularJS. In this case it will hide the tooltip.

		$scope.showtooltip = false;
	};

	$scope.toggleTooltip = function(e){
		e.stopPropagation();
		$scope.showtooltip = !$scope.showtooltip;
	};
});