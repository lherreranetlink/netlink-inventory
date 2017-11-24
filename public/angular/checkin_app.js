
app.controller('checkinCTRL', function($scope,$http,$window) {
    $scope.name = "checkin";
    $scope.mfg_url = mfg_url;
    $scope.model_url = model_url;
    $scope.location_url = location_url;
    $scope.sublocation_url = sublocation_url;
    $scope.childlocation_url = childlocation_url;
    $scope.add_checkin_url = add_checkin_url;
    $scope.url = url;
    $scope.is_sublocation = false;
    $scope.is_childlocation = false;
    
    
    $scope.subLoading = false;
    $scope.childLoading = false;
    
    
    $scope.selectedLocation = [];
    $scope.selectedSubLocation = [];
    $scope.selectedChildLocation = [];
    $scope.selectedMfg = [];
    $scope.selectedModel = [];
    $scope.manufacturers = [];
    $scope.models = [];
    $scope.locations = [];
    $scope.sublocations = [];
    $scope.childlocations = [];
    
    
    $scope.selected_model = [];
    
    $scope.onConditionChnage = function(){
        $scope.data.used_note = "";
        $scope.data.refurbished_note = "";
    };
    
    
    $scope.data = {
        manufacturer_id:"",
        model_id:"",
        condition:"",
        mac:"",
        identifier:"",
        identifier2:"",
        location_id: "",
        sublocation_id: "",
        childlocation_id: "",
        used_note:"",
        refurbished_note:""
        
        
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
    
    //Get Models by munufacturer.................................................
    $scope.changeMfg = function(item){
        $scope.selected_model=[];
        $scope.modelLoading = true;
        $scope.data.manufacturer_id = item.id;
        $scope.data.model_id = "";
        var objmodel = {
            manufacturer_id:item.id
        };
        $http.post($scope.model_url,objmodel).then(function mySucces(response) {
            $scope.models = response.data;

        }, function myError(response) {

        }).finally(function(){
            $scope.modelLoading = false;
        });
    };
    
    //Get Models by munufacturer.................................................
    $scope.changeModel = function(item){
        $scope.data.model_id = item.id;
        $scope.selected_model=item;
    };
    
    //Get Location.................................................
    $scope.catLoading = true;
    $http.get($scope.location_url).then(function success(response) {
        $scope.locations = response.data;
        
           
    }, function error(response) {
       
    }).finally(function(){
        $scope.catLoading = false;
    });
    
    
    //Chnage Location Dropdown......................................
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
    
    //Chnage Sub Location Dropdown...................................
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
    
    
    
    //Finally add new model to DB.....................................................
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.checkinAddLoading = false;
    $scope.addCheckin = function(){
        $scope.checkinAddLoading = true;
        $http.post($scope.add_checkin_url,$scope.data).then(function success1(response) {
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
            $scope.checkinAddLoading = false;
        });
    };
    
    
});




