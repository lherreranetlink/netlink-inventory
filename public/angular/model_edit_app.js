
app.controller('modelEditCTRL', function($scope,$http,$window) {
    $scope.name = "Model App Angular";
    $scope.mfg_url = mfg_url;
    $scope.category_url = category_url;
    $scope.subcategory_url = subcategory_url;
    $scope.childcategory_url = childcategory_url;
    $scope.edit_model_url = edit_model_url;
    $scope.url = url;
    $scope.model_id = model_id;
    $scope.is_subcategory = false;
    $scope.is_childcategory = false;
    $scope.old_model = [];
    $scope.subLoading = false;
    $scope.childLoading = false;
    //var one = $scope.data.category_id;
    //var two = $scope.data.subcategory_id;
    //var three = $scope.data.childcategory_id;
    
    
    
    $scope.get_model_detail_url = get_model_detail_url;
    
    
    
    $scope.selectedCategory = [];
    $scope.selectedSubCategory = [];
    $scope.selectedChildCategory = [];
    $scope.manufacturers = [];
    $scope.categories = [];
    $scope.subcategories = [];
    $scope.childcategories = [];
    $scope.available_models = [];
    
    
    
    
    
    $scope.updateCategory = false;
    $scope.updateCategoryBtnClick = function(){
        if($scope.updateCategory==false){
            $scope.updateCategory= true;
        }
        else{
            $scope.updateCategory= false;
            $scope.data.category_id = $scope.originalCategory;
            $scope.data.subcategory_id = $scope.originalSubCategory;
            $scope.data.childcategory_id = $scope.originalChildCategory;
        }
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
        //for()
        
    }, function error(response) {
       
    }).finally(function(){
        $scope.catLoading = false;
    });
    
    
    //Chnage Category Dropdown......................................
    $scope.subLoading = false;
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
    $scope.childLoading = false;
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
    
    $scope.editPic = false;
    $scope.togglePic = function(){
        if($scope.editPic == false){
            $scope.editPic = true;
        }
        else{
            $scope.editPic = false;
        }
    };
    
    
    
    
    
    
    
    //Get Model Detail.................................................................
    var objGetModelDetail = {
        id: $scope.model_id
    };
    $http.post($scope.get_model_detail_url,objGetModelDetail).then(function success(response) {
        $scope.old_model = response.data;
        $scope.data = response.data;
        $scope.originalCategory = response.data.category_id;
        $scope.originalSubCategory = response.data.subcategory_id;
        $scope.originalChildCategory = response.data.childcategory_id;
        
        if(response.data.picture==null){
            $scope.data.picture = "";
        }
        $scope.data.low_stock = parseInt(response.data.low_stock);

    }, function error(response) {

    }).finally(function(){
        //$scope.catLoading = false;
    });
   
    
    
    
    //Finally submit form for edit data.....................................................
    $scope.data = [];
    //$scope.data.picture = "";
    $scope.final_result = {
        content:"",
        statuscode: "",
        statustext:""
    };
    $scope.modelAddLoading = false;
    $scope.editModelProcess = function(){
        if($scope.editPic){
            if($scope.myCroppedImage==''||$scope.myImage==''){
                alert("Please select image");
            }
            else{
                $scope.data.picture = $scope.myCroppedImage;
                $scope.modelAddLoading = true;
                $http.post($scope.edit_model_url,$scope.data).then(function success(response) {
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
        else{
            $scope.data.picture = $scope.myCroppedImage;
            $scope.modelAddLoading = true;
            $http.post($scope.edit_model_url,$scope.data).then(function success(response) {
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
    };
    
    
});




