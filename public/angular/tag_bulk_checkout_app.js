
app.controller('bulkCheckoutCTRL', function($scope,$http,$window) {
    $scope.name = "check out";
    $scope.add_bulk_checkout_url = add_bulk_checkout_url;
    $scope.change_bulk_location_url = change_bulk_location_url;
    $scope.url = url;
    $scope.mfg_url = mfg_url;
    $scope.category_url = category_url;
    $scope.customer_url = customer_url;
    $scope.location_url = location_url;
    $scope.sublocation_url = sublocation_url;
    $scope.get_checkins_by_barcode_url = get_checkins_by_barcode_url;
    $scope.childlocation_url = childlocation_url;
    
    $scope.manufacturers=[];
    $scope.scanned_barcode=[];
    $scope.checkins = [];
    $scope.valid_checkouts = [];
    $scope.categories=[];
    $scope.locations=[];
    $scope.sublocations=[];
    $scope.childlocations=[];
    $scope.customers=[];
    $scope.selectedMfg = [];
    $scope.selectedCategory = [];
    $scope.selectedCustomer = [];
    
    
    $scope.selectedLocation = [];
    $scope.selectedSubLocation = [];
    $scope.selectedChildLocation = [];
    
    
    $scope.barcode = "";
    $scope.is_manual = false;
    $scope.manual = "Yes";
    $scope.toggleManualBarcode = function(item){
        if(item=="Yes"){
            $scope.is_manual = true;
            $scope.manual = "No";
        }
        else{
            $scope.is_manual = false;
            $scope.manual = "Yes";
        }
    }
    
    
    //Get Manufacturers.................................................
    $scope.mfgLoading = true;
    $http.get($scope.mfg_url).then(function mySucces(response) {
        $scope.manufacturers = response.data;
            
    }, function myError(response) {

    }).finally(function(){
        $scope.mfgLoading = false;
        //$scope.showBtn = false;
    });
    
    //Get Categories.................................................
    $scope.catLoading = true;
    $http.get($scope.category_url).then(function success(response) {
        $scope.categories = response.data;
           
    }, function error(response) {
       
    }).finally(function(){
        $scope.catLoading = false;
    });
    
    
    
    //Get check-in data by bar code.....................................
    
    $scope.getProductDetail = function(){
        //$scope.searchLoading = true;
        //$scope.showSuggestion = true;
        if($scope.scanned_barcode.indexOf($scope.barcode) == -1 &&$scope.barcode.length!=0) {
            
            var barcodeObj = {
                barcode:$scope.barcode
            };
            if($scope.barcode.length!=0){
                 $http.post($scope.get_checkins_by_barcode_url,barcodeObj).then(function success1(response) {
                    if(response.data.length==0){
                        $scope.showErrorForBarcode = false;
                    }
                    else if(response.data.length==1){
                        if(response.data[0].checkout_at==null&&(response.data[0].checkout_at==0||response.data[0].checkout_at==null)){
                            response.data[0].valid = true;
                            response.data[0].valid_color = 'green';
                            $scope.valid_checkouts.push(response.data[0]);
                            //$scope.scanned_barcode.push($scope.barcode);
                        }
                        else{
                            response.data[0].valid = false;
                            response.data[0].valid_color = 'red';
                            
                        }
                        $scope.checkins.push(response.data[0]);
                        $scope.scanned_barcode.push($scope.barcode);
                        $scope.showErrorForBarcode = false;
                    }
                    else{
                        $scope.showErrorForBarcode = true;
                    }


                }, function error1(response) {

                }).finally(function(){
                    $scope.barcode = "";
                });

            }
        }
        
    };
    
   
    //check out modal pop up process.........................................
    $scope.checkOutModal = function(item){
        $scope.customerLoading = true;
        $http.get($scope.customer_url).then(function success(response) {
            $scope.customers = response.data;

        }, function error(response) {

        }).finally(function(){
            $scope.customerLoading = false;
        });
    };
    //Drop down changes while check out or location change..........................
    $scope.changeCustomer = function(item){
        for(var i=0; i<$scope.valid_checkouts.length; i++){
            $scope.valid_checkouts[i]['checkout_customer_id'] = item.id;
        }
    };
    //.............................................................................
    
        
    $scope.changeLocationClick = function(item){
        for(var i=0; i<$scope.valid_checkouts.length; i++){
            $scope.valid_checkouts[i]['checkout_customer_id'] = "";
        }
        
    };
    
    
    
    //Get Location.................................................
    $scope.locLoading = true;
    $http.get($scope.location_url).then(function success(response) {
        $scope.locations = response.data;
           
    }, function error(response) {
       $scope.locLoading = false;
    }).finally(function(){
        $scope.locLoading = false;
    });
    
    $scope.subLoading = false;
    $scope.locationChange = function(item){
        for(var i=0; i<$scope.valid_checkouts.length; i++){
            $scope.valid_checkouts[i]['checkout_location_id'] = item.id;
        }
        
        $scope.subLoading = true;
        var objCat = {
            location_id:item.id
        };
        $http.post($scope.sublocation_url,objCat).then(function success(response) {
            if(response.data.length!=0){
                $scope.sublocations = response.data;
                $scope.is_sublocation = true;  
            }
            else{
                $scope.sublocations = [];
                $scope.childlocations = [];
                $scope.is_sublocation = false;
                $scope.is_childlocation = false;
            }
        }, function error(response) {
            
        }).finally(function(){
            $scope.subLoading = false;
        });
    };
    
    $scope.childLoading = false;
    $scope.sublocationChange = function(item){
        for(var i=0; i<$scope.valid_checkouts.length; i++){
            $scope.valid_checkouts[i]['checkout_sublocation_id'] = item.id;
        }
        $scope.childLoading = true;
        var objSubCat = {
            sublocation_id:item.id
        };
        $http.post($scope.childlocation_url,objSubCat).then(function success(response) {
            if(response.data.length!=0){
                $scope.childlocations = response.data;
                $scope.is_childlocation = true;
                 
            }
            else{
                $scope.childlocations = [];
                $scope.is_childlocation = false;
            }
            
        }, function error(response) {
            
        }).finally(function(){
            $scope.childLoading = false;
        });
    };
    $scope.childlocationChange = function(item){
        for(var i=0; i<$scope.valid_checkouts.length; i++){
            $scope.valid_checkouts[i]['checkout_childlocation_id'] = item.id;
        }
    };
    
    
    
    
    
    
    
    //Chnage category and manufacturer......................................
    $scope.changeCategoryManufacturer = function(item){
        
    };
    
    $scope.changeModel = function(item){
        $scope.data.model_id = item.id;
    };

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //Finally add new checkout to DB.....................................................
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.checkoutAddLoading = false;
    $scope.addCheckout = function(){
        $scope.checkoutAddLoading = true;
        var objCheckout = {
            checkouts: $scope.valid_checkouts
        };
        $http.post($scope.add_bulk_checkout_url,objCheckout).then(function success1(response) {
            $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
            if(response.status==200&&response.data.msg=='success'){
                $window.location.href = $scope.url;
            }
            
        }, function error1(response) {
            $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }).finally(function(){
            $scope.checkoutAddLoading = false;
        });
    };
    //................................................................................
    
    
    //Finally change location.....................................................
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.changeLocationProcessLoading = false;
    $scope.changeLocationProcess = function(){
        $scope.changeLocationProcessLoading = true;
        var objLocationCheckout = {
            checkouts: $scope.valid_checkouts
        };
        $http.post($scope.change_bulk_location_url,objLocationCheckout).then(function success2(response) {
            $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
            if(response.status==200&&response.data.msg=='success'){
                $window.location.href = $scope.url;
            }
            
        }, function error2(response) {
            $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }).finally(function(){
            $scope.changeLocationProcessLoading = false;
        });
    };
    
    
});



