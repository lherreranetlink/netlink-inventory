
app.controller('reportCTRL', function($scope,$http,$window) {
    $scope.manufacturer_report_url = manufacturer_report_url;
    $scope.category_report_url = category_report_url;
    
    
    
    //Manufacturer Report..........................................................
    $scope.manufacturers = [];
    $scope.manufacturerLoadWait = true;
    $http.get($scope.manufacturer_report_url).then(function success(response) {
        $scope.manufacturers = response.data;
        $scope.manufacturerLoadWait = false;

    }, function error(response) {
       
    }).finally(function(){
        $scope.manufacturerLoadWait = false;
    });
    //.............................................................................
    
    //Category Report..........................................................
    $scope.categories = [];
    $scope.categoryLoadWait = true;
    $http.get($scope.category_report_url).then(function success(response) {
        $scope.categories = response.data;
        $scope.categoryLoadWait = false;

    }, function error(response) {
       
    }).finally(function(){
        $scope.categoryLoadWait = false;
    });
    //.............................................................................
    
    
    
    
    $scope.items = [];
    $scope.manufacturerClick = function(item){
        $scope.items = item;
    };
    $scope.categoryClick = function(item){
        $scope.items = item;
    };
    
    $scope.closeReportModal = function(){
        $scope.items = [];
    };
});




