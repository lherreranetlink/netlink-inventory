
app.controller('stockCTRL', function($scope,$http,$window) {
    $scope.name = "Model App Angular";
    $scope.mfg_url = mfg_url;
    $scope.category_url = category_url;
    $scope.subcategory_url = subcategory_url;
    $scope.childcategory_url = childcategory_url;
    $scope.search_url = search_url;
    //$scope.keyup_search_url = keyup_search_url;
    $scope.url = url;
    
    
    
    $scope.is_subcategory = false;
    $scope.is_childcategory = false;
    
    $scope.subLoading = false;
    $scope.childLoading = false;
    
    $scope.selectedCategory = [];
    $scope.selectedSubCategory = [];
    $scope.selectedChildCategory = [];
    $scope.manufacturers = [];
    $scope.categories = [];
    $scope.subcategories = [];
    $scope.childcategories = [];
    
    $scope.search = "";
    
    
  $scope.sortKey = 'checkin_at';   //set the sortKey to the param passed
  $scope.reverse = !$scope.reverse;
  $scope.sort = function(keyname){
        //alert("sd");
        $scope.sortKey = keyname;   //set the sortKey to the param passed
        $scope.reverse = !$scope.reverse; //if true make it false and vice versa
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
    
    //Chnage Category Dropdown......................................
    $scope.categoryChange = function(item){
        $scope.data.category_id = item.id; 
        $scope.data.subcategory_id = ""; 
        $scope.data.childcategory_id = "";
        $scope.subLoading = true;
        var objCat = {
            category_id:item.id
        };
        $http.post($scope.subcategory_url,objCat).then(function success(response) {
            if(response.data.length!=0){
                $scope.subcategories = response.data;
                $scope.is_subcategory = true;
                $scope.selectedSubCategory = response.data;
                
            }
            else{
                $scope.subcategories = [];
                $scope.childcategories = [];
                $scope.is_subcategory = false;
                $scope.is_childcategory = false;
            }
        }, function error(response) {
            
        }).finally(function(){
            $scope.subLoading = false;
        });
    };
    
    //Chnage Sub Category Dropdown...................................
    $scope.subcategoryChange = function(item){
        $scope.data.subcategory_id = item.id;
        $scope.data.childcategory_id = "";
        $scope.childLoading = true;
        var objSubCat = {
            subcategory_id:item.id
        };
        $http.post($scope.childcategory_url,objSubCat).then(function success(response) {
            if(response.data.length!=0){
                $scope.childcategories = response.data;
                $scope.is_childcategory = true;
                 
            }
            else{
                $scope.childcategories = [];
                $scope.is_childcategory = false;
            }
            
        }, function error(response) {
            
        }).finally(function(){
            $scope.childLoading = false;
        });
    };
    
    $scope.childcategoryChange = function(item){
        $scope.data.childcategory_id = item.id;  
    };
    
    
    
    //$scope.$watchGroup(['data.manufacturer_id','data.category_id','data.subcategory_id','data.childcategory_id','data.keyword'], function () {
        //$scope.searchStock();
    //});
    
    
    
    $scope.searchBtnClick = function(){
        $scope.search_result.content = [];
        $scope.searchStock();
    };
    $scope.searchByKeyUp = function(){
        //$scope.search_result.content = [];
        //$scope.searchStockInputKeyup();
    };
    $scope.resetBtnClick = function(){
        $window.location.href = $scope.url;
    };
    
    //Finally add new model to DB.....................................................
    $scope.search_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    $scope.data = {
        manufacturer_id:"",
        category_id:"",
        subcategory_id : "",
        childcategory_id : "",
        keyword: ""
    };
    $scope.searching = false;
    $scope.searchStock = function(){
        $scope.searching = true;
        $http.post($scope.search_url,$scope.data).then(function success(response) {
            $scope.search_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }, function error(response) {
            $scope.search_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }).finally(function(){
            $scope.searching = false;
        });
    };
    
    $scope.searchStockInputKeyup = function(){
        $scope.searching = true;
        $http.post($scope.search_url,$scope.data).then(function success(response) {
            $scope.search_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }, function error(response) {
            $scope.search_result = {
                content:response.data,
                statuscode: response.status,
                statustext:response.statustext
            };
        }).finally(function(){
            $scope.searching = false;
        });
    };
    
    
});




