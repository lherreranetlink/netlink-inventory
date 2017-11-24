
app.controller('profileCtrl', function($scope,$http,$window) {
  $scope.processing = false;
  $scope.redirect_url = redirect_url;
  $scope.change_profile_pic_url = change_profile_pic_url;
  
  $scope.user_id = user_id;
  $scope.checkins_url = checkins_url;
  $scope.checkouts_url = checkouts_url;
  $scope.location_change_url = location_change_url;
  
  $scope.data_processing = false;
  $scope.search = "";
  
  
  $scope.sortKey = 'created_at';   //set the sortKey to the param passed
  $scope.reverse = !$scope.reverse;
  $scope.sort = function(keyname){
        //alert("sd");
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
    };
    
   
  //Profile Pic........................................
  $scope.myImage='';
  $scope.myCroppedImage='';
  $scope.processing = false;
    var handleFileSelect=function(evt) {
      var file=evt.currentTarget.files[0];
      var reader = new FileReader();
      reader.onload = function (evt) {
        $scope.$apply(function($scope){
          $scope.myImage=evt.target.result;
        });
      };
      reader.readAsDataURL(file);
    };
    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
    
    
  $scope.changePic = function(){
      if($scope.myCroppedImage==''||$scope.myImage==''){
          alert("Please select image");
      }
      else{
          $scope.processing = true;
          var data = {
              file:$scope.myCroppedImage
          };
          $http.post($scope.change_profile_pic_url,data).then(function mySuccess(response) {
                $scope.result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
                $window.location.href = $scope.redirect_url;

            }, function myError(response) {
                $scope.result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
            }).finally(function(){
                $scope.processing = false;
            });
      }
  };
  
  
  
    //____________________________________________________________________________
    ///DATA processing .......................................................
    //CHeck-Ins................................................................
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    var userId = {
        id:$scope.user_id
    };
    //On Refresh checkins.........................................
    $scope.data_processing = true;
    $scope.checkins = [];
    $scope.checkin_active = "active";
    $scope.checkout_active = "";
    $scope.location_active = "";
    $http.post($scope.checkins_url,userId).then(function success(response) {
        $scope.checkins = response.data;
        $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };

    }, function error(response) {
        $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
    }).finally(function(){
        $scope.data_processing = false;
    });
    
    //checkins on click................................................................
    $scope.checkinClick = function(event){
        $scope.data_processing = true;
        $scope.checkins = [];
        $scope.checkin_active = "active";
        $scope.checkout_active = "";
        $scope.location_active = "";
        $scope.search = "";
        $http.post($scope.checkins_url,userId).then(function success(response) {
            $scope.checkins = response.data;
            $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };

        }, function error(response) {
            $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
        }).finally(function(){
            $scope.data_processing = false;
        });
    };
    //______________________________________________________________________________
    //CHeck-out.............
    $scope.checkoutClick = function(event){
        $scope.data_processing = true;
        $scope.checkouts = [];
        $scope.checkin_active = "";
        $scope.checkout_active = "active";
        $scope.location_active = "";
        $scope.search = "";
        $http.post($scope.checkouts_url,userId).then(function success(response) {
            $scope.checkouts = response.data;
            $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };

        }, function error(response) {
            $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
        }).finally(function(){
            $scope.data_processing = false;
        });
    };
    
    //__________________________________________________________________________________
    //Location Change........
    $scope.locationClick = function(event){
        $scope.data_processing = true;
        $scope.location_change = [];
        $scope.checkin_active = "";
        $scope.checkout_active = "";
        $scope.location_active = "active";
        $scope.search = "";
        $http.post($scope.location_change_url,userId).then(function success(response) {
            $scope.location_change = response.data;
            $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };

        }, function error(response) {
            $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
        }).finally(function(){
            $scope.data_processing = false;
        });
    };
  
  
 
  
});




