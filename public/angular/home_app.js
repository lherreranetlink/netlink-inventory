
app.controller('homeCTRL', function($scope,$http,$window, $q ,$timeout) {
    $scope.name = "check out";
    $scope.add_checkout_url = add_checkout_url;
    $scope.change_location_url = change_location_url;
    $scope.url = url;
    $scope.mfg_url = mfg_url;
    $scope.category_url = category_url;
    $scope.customer_url = customer_url;
    $scope.location_url = location_url;
    $scope.sublocation_url = sublocation_url;
    $scope.get_checkins_by_barcode_url = get_checkins_by_barcode_url;
    $scope.childlocation_url = childlocation_url;
    $scope.location_change_redirect = location_change_redirect;
    $scope.checkin_back_redirect = checkin_back_redirect;
    $scope.checkin_back_url = checkin_back_url;
    
    $scope.manufacturers=[];
    $scope.checkin = [];
    $scope.categories=[];
    $scope.locations=[];
    $scope.sublocations=[];
    $scope.childlocations=[];
    $scope.customers=[];
    $scope.selectedMfg = [];
    $scope.selectedCategory = [];
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
    $scope.data = {
        inout_id:"",
        model_id:"",
        mac:"",
        identifier:"",
        customer_id:"",
        location_id: "",
        sublocation_id: "",
        childlocation_id: ""
    };
    $scope.checkinBackClick = function(item){
        $scope.data.customer_id = "";
        $scope.data = {
            inout_id:item.id,
            model_id:item.model_id,
            mac:item.mac,
            identifier:item.identifier,
            customer_id:"",
            location_id: "",
            sublocation_id: "",
            childlocation_id: ""
        };
    };
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
    
    
    $scope.more_checkins_found = false;
    //Get check-in data by bar code.....................................
    $scope.searchLoading = false;
    $scope.checkinSingleNew = false;
    $scope.processingDelay = false;
    
    
    
    
    $scope.$watch('barcode', function() {
        if($scope.barcode.length==0){
            $scope.showSuggestion = false;
            $scope.checkin = [];
        }
  
    }); 
    $scope.ajax_done = false;
    $scope.showSuggestion = false;
    $scope.getProductDetail = function(){
        $scope.ajax_done = false;
        $scope.processingDelay = true;
        var barcodeObj = {
            barcode:$scope.barcode
        };
        
        if($scope.barcode!=null||$scope.barcode!=""||$scope.barcode!=" "){
            $http.post($scope.get_checkins_by_barcode_url,barcodeObj).then(function success1(response) {
                $scope.checkin = response.data;
                $scope.ajax_done = true;
                $scope.showSuggestion = true;
            }, function error1(response) {
                
            }).finally(function(){
                $scope.searchLoading = false;
                $scope.processingDelay = false;
                //$scope.ajax_done = false;
            });
        }
        
    };
    
   
    //check out modal pop up process.........................................
    $scope.checkOutModal = function(item){
        $scope.customerLoading = true;
        $scope.data = {
            inout_id:item.id,
            model_id:item.model_id,
            mac:item.mac,
            identifier:item.identifier,
            customer_id:"",
            location_id: "",
            sublocation_id: "",
            childlocation_id: ""
        };
        $http.get($scope.customer_url).then(function success(response) {
            $scope.customers = response.data;

        }, function error(response) {

        }).finally(function(){
            $scope.customerLoading = false;
        });
    };
    //.............................................................................
    
        
    $scope.changeLocationClick = function(item){
        $scope.data.customer_id = "";
        $scope.data = {
            inout_id:item.id,
            model_id:item.model_id,
            mac:item.mac,
            identifier:item.identifier,
            customer_id:"",
            location_id: "",
            sublocation_id: "",
            childlocation_id: ""
        };
    };
    
    //Drop down changes while check out or location change..........................
    $scope.changeCustomer = function(item){
        $scope.data.customer_id = item.id;
    };
    
    //Get Location.................................................
    $scope.locLoading = true;
    $http.get($scope.location_url).then(function success(response) {
        $scope.locations = response.data;
           
    }, function error(response) {
       
    }).finally(function(){
        $scope.locLoading = false;
    });
    
    $scope.locationChange = function(item){
        $scope.data.location_id = item.id; 
        $scope.data.sublocation_id = ""; 
        $scope.data.childlocation_id = "";
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
    $scope.sublocationChange = function(item){
        $scope.data.sublocation_id = item.id;
        $scope.data.childlocation_id = "";
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
        $scope.data.childlocation_id = item.id;
    };
    
    $scope.cancelButtonPress = function(){
        //$scope.showSuggestion = false;
        $scope.barcode = "";
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //Chnage category and manufacturer......................................
    $scope.changeCategoryManufacturer = function(item){
        
    };
    
    $scope.changeModel = function(item){
        $scope.data.model_id = item.id;
    };

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    //Finally add new checkout to DB.....................................................
    $scope.cancelCheckOut = function(){
        $scope.data = {
            inout_id:"",
            model_id:"",
            mac:"",
            identifier:"",
            customer_id:"",
            location_id: "",
            sublocation_id: "",
            childlocation_id: ""
        };
    };
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.checkoutAddLoading = false;
    $scope.addCheckout = function(){
        $scope.checkoutAddLoading = true;
        $http.post($scope.add_checkout_url,$scope.data).then(function success1(response) {
            $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
            if(response.status==200){
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
    $scope.cancelChangeLocationProcess = function(){
        $scope.data = {
            inout_id:"",
            model_id:"",
            mac:"",
            identifier:"",
            customer_id:"",
            location_id: "",
            sublocation_id: "",
            childlocation_id: ""
        };
    };
    $scope.final_location_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.changeLocationProcessLoading = false;
    $scope.changeLocationProcess = function(){
        $scope.changeLocationProcessLoading = true;
        $http.post($scope.change_location_url,$scope.data).then(function success2(response) {
            $scope.final_location_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
            if(response.status==200){
                $window.location.href = $scope.location_change_redirect;
            }
            
        }, function error2(response) {
            $scope.final_location_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }).finally(function(){
            $scope.changeLocationProcessLoading = false;
        });
    };
    
    
    //Finally checkin back.............................................................
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.changeLocationProcessLoading = false;
    $scope.checkInBackProcess = function(){
        $scope.changeLocationProcessLoading = true;
        $http.post($scope.checkin_back_url,$scope.data).then(function success2(response) {
            $scope.final_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
            if(response.status==200){
                $window.location.href = $scope.checkin_back_redirect;
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




