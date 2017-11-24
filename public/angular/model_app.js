
app.controller('modelCTRL', function($scope,$http,$window) {
    $scope.name = "Model App Angular";
    $scope.mfg_url = mfg_url;
    $scope.category_url = category_url;
    $scope.subcategory_url = subcategory_url;
    $scope.childcategory_url = childcategory_url;
    $scope.add_model_url = add_model_url;
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
    $scope.available_models = [];
    
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
    
    
    $scope.$watch('data.identifier', function() {
        if($scope.data.identifier=="no"){
            $scope.data.identifier_name = "";
            
        }
        if($scope.data.identifier=="yes"){
            $scope.data.identifier_name = "Serial #";
            
        }   
    });
    
    
    
    
    
    
    
 
    
    
    
    
    
    
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
    
    
    
    
    
    
    
    
    
    
    //Finally add new model to DB.....................................................
    $scope.data = {
        manufacturer_id:"",
        model_number:"",
        category_id:"",
        subcategory_id : "",
        childcategory_id : "",
        is_mac : "no",
        identifier : "no",
        identifier_name : "Serial #",
        identifier_name2 : "",
        description : "",
        low_stock:0,
        picture : ""
    };
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    
    $scope.modelAddLoading = false;
    $scope.addModel = function(){
        $scope.data.picture = $scope.myCroppedImage;
        if($scope.myCroppedImage==''||$scope.myImage==''){
            alert("Please select image");
        }
        else{
            $scope.modelAddLoading = true;
            $http.post($scope.add_model_url,$scope.data).then(function success(response) {
                $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
                if(response.status==200){
                    $window.location.href = $scope.url;
                }

            }, function error(response) {
                $scope.final_result = {
                    content:response.data,
                    statuscode: response.status,
                    statustext:response.statustext
                };
            }).finally(function(){
                $scope.modelAddLoading = false;
            });
        }
    }
    
    
});




