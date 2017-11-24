var app = angular.module('masterApp', ['ngFileUpload','ngImgCrop','angularUtils.directives.dirPagination']);
app.controller('masterController', ['$scope','$http', function($scope,$http){
           
}]);

app.filter('prettyJSON', function () {
    function prettyPrintJson(json) {
      return JSON ? JSON.stringify(json, null, '  ') : 'your browser doesnt support JSON so cant pretty print';
    }
    return prettyPrintJson;
});

app.filter('cmdate', [
    '$filter', function($filter) {
        return function(input, format) {
            return $filter('date')(new Date(input), format);
        };
    }
]);

