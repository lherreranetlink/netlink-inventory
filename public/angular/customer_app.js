
app.directive('onReadFile', function ($parse) {
	return {
		restrict: 'A',
		scope: false,
		link: function(scope, element, attrs) {
            var fn = $parse(attrs.onReadFile);
            
			element.on('change', function(onChangeEvent) {
				var reader = new FileReader();
                
				reader.onload = function(onLoadEvent) {
					scope.$apply(function() {
						fn(scope, {$fileContent:onLoadEvent.target.result});
					});
				};

				reader.readAsText((onChangeEvent.srcElement || onChangeEvent.target).files[0]);
			});
		}
	};
});

app.controller('customerCTRL', function($scope,$http,$window) {
  $scope.insert_customer_url = insert_customer_url;
  $scope.processed = [];
  $scope.waiting_text = false;
  $scope.waiting_text_global = false;
  $scope.total = 0;
  $scope.total_success = 0;
  $scope.total_failed = 0;
  $scope.showContent = function($fileContent){
        $scope.waiting_text_global = true;
        $scope.processed = [];
        var allTextLines = $fileContent.split('\r');
        var headers = allTextLines[0].split(',');
        for ( var i = 1; i < allTextLines.length; i++) {  
            $scope.waiting_text = true;
            var data = allTextLines[i].split(',');
            if (data.length == headers.length) {
                var tarr = [];
                for ( var j = 0; j < 4; j++) {
                    
                        var tmpp = data[j].replace(/\"/g,'');
                        //tmpp = tmpp.replace(/\"/g,'');
                        if(j!=2){
                            tmpp = tmpp.replace(/\n/g,'');
                        }
                        if(j==2||j==3){
                            tmpp = tmpp.replace(/\n/g,' | ');
                        }
                        tarr.push(tmpp);
                    
                }
                var postData = {
                    name:tarr[0],
                    contact_person:tarr[1],
                    address:tarr[2],
                    contact:tarr[3],
                };
                $scope.waiting_text_global = true;
                $http.post($scope.insert_customer_url,postData).then(function success1(response) {
                    var newObj = {
                        name : response.data.name,
                        address : response.data.address,
                        success : true,
                        error:""
                    };
                    $scope.processed.push(newObj); 
                    $scope.total_success +=1;
                    
                       
                }, function error1(response) {
                    var newObj = {
                        name : response.data.name,
                        address : response.data.address,
                        success : false,
                        error:response.statustext
                    }
                    $scope.processed.push(newObj); 
                    $scope.total_failed +=1;
                    
                
                }).finally(function(){
                    $scope.waiting_text = false;
                    $scope.total +=1;
                    
                });
                
            }
            
        }
        $scope.waiting_text_global = false;
    };
    
  

});






