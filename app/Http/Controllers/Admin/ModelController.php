<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


use App\ProductModel;

use App\Manufacturer;
use App\Category;
use App\SubCategory;
use App\ChildCategory;

use App\Location;
use App\SubLocation;
use App\ChildLocation;

use App\Customer;
use Illuminate\Support\Facades\Auth;

class ModelController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['title'] = "Models";
    }
    
    
    public function models(Request $request)
    {
        if(isset($request['manufacturer_id'])&&$request['manufacturer_id']!=""){
            $this->data['models'] = ProductModel::where(array('manufacturer_id'=>$request['manufacturer_id']))->get();
            $this->data['selected_manu'] = $request['manufacturer_id'];
        }
        else{
            $this->data['models'] = ProductModel::all();
            $this->data['selected_manu'] = "";
        }
        $this->data['manufacturers'] = Manufacturer::all();
        
        return view('models',['data'=>$this->data]);
    }
    
    public function modelAdd(Request $request){
        return view('model-form',['data'=>$this->data]);
    }
    public function modelEdit(Request $request){
        $model = ProductModel::find($request['id']);
        return view('model-edit',['data'=>$model]);
    }
    public function processModelAdd(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'manufacturer_id'=> 'required',
                'model_number'=> 'required|unique:models',
                'category_id'=> 'required',
                'low_stock'=>'required'
            ]);
            $model = new ProductModel();
            $model->manufacturer_id = $request['manufacturer_id'];
            $model->model_number = $request['model_number'];
            $model->category_id = $request['category_id'];
            
            if(isset($request['subcategory_id'])&&$request['subcategory_id']!=""){
                $model->subcategory_id = $request['subcategory_id'];
            }
            
            if(isset($request['childcategory_id'])&&$request['childcategory_id']!=""){
                $model->childcategory_id = $request['childcategory_id'];
            }
            if(isset($request['identifier_name'])&&$request['identifier_name']!=""){
                $model->identifier_name = $request['identifier_name'];
            }
            if(isset($request['identifier_name2'])&&$request['identifier_name2']!=""){
                $model->identifier_name2 = $request['identifier_name2'];
            }
            if(isset($request['description'])&&$request['description']!=""){
                $model->description = $request['description'];
            }
            if(isset($request['picture'])&&$request['picture']!=""){
                $model->picture = $request['picture'];
            }
            $model->is_mac = $request['is_mac'];
            $model->identifier = $request['identifier'];
            $model->low_stock = $request['low_stock'];
            $model->save();
            $response['msg'] = "success";
            return response()->json($response);
        }
        return response()->json($response);
    }
    
    
    
    public function processModelEdit(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'manufacturer_id'=> 'required',
                'model_number'=> 'required',
                'category_id'=> 'required',
                'low_stock'=>'required'
            ]);
            $model = ProductModel::find($request['id']);
            $model->manufacturer_id = $request['manufacturer_id'];
            $model->model_number = $request['model_number'];
            $model->category_id = $request['category_id'];
            
            if(isset($request['subcategory_id'])&&$request['subcategory_id']!=""){
                $model->subcategory_id = $request['subcategory_id'];
            }
            
            if(isset($request['childcategory_id'])&&$request['childcategory_id']!=""){
                $model->childcategory_id = $request['childcategory_id'];
            }
            if(isset($request['identifier_name'])&&$request['identifier_name']!=""){
                $model->identifier_name = $request['identifier_name'];
            }
            if(isset($request['description'])&&$request['description']!=""){
                $model->description = $request['description'];
            }
            if(isset($request['picture'])&&$request['picture']!=""){
                $model->picture = $request['picture'];
            }
            $model->is_mac = $request['is_mac'];
            $model->identifier = $request['identifier'];
            $model->low_stock = $request['low_stock'];
            $model->save();
            $response['msg'] = "success";
            return response()->json($response);
        }
        return response()->json($response);
    }
    
    
    
    public function deleteModel(Request $request){
        $id = $request['id'];
        $model = ProductModel::find($id);
        $model->deleted = 1;
        $model->save();
        return redirect('/models');
    }
    
   
    
    
    
    
    //Ajax....................................................
    public function getManufacturers(){
        $mfg = Manufacturer::where(array('deleted'=>0))->get();
        return response()->json($mfg);
    }
    public function getCategories(){
        $mfg = Category::where(array('deleted'=>0))->get();
        return response()->json($mfg);
    }
    
    public function getSubCategoriesbyCat(Request $request){
        $category_id = $request['category_id'];
        $subcategory = SubCategory::where(array('category_id'=>$category_id))->get();
        return response()->json($subcategory);
    }
    
    public function getChildCategoriesbyCat(Request $request){
        $subcategory_id = $request['subcategory_id'];
        $childcategory = ChildCategory::where(array('subcategory_id'=>$subcategory_id))->get();
        return response()->json($childcategory);
    }
    //Ajax location..............................................
    public function getLocations(){
        $locations = Location::where(array('deleted'=>0))->get();
        return response()->json($locations);
    }
    
    public function getCustomers(){
        $customers = Customer::where(array('deleted'=>0))->get();
        return response()->json($customers);
    }
    
    public function getSubLocationsbyCat(Request $request){
        $location_id = $request['location_id'];
        $sublocation = SubLocation::where(array('location_id'=>$location_id))->get();
        return response()->json($sublocation);
    }
    
    public function getChildLocationsbyCat(Request $request){
        $sublocation_id = $request['sublocation_id'];
        $childlocation = ChildLocation::where(array('sublocation_id'=>$sublocation_id))->get();
        return response()->json($childlocation);
    }
    
    
    public function getModelByMfg(Request $request){
        $manufacturer_id = $request['manufacturer_id'];
        $models = ProductModel::where(array('manufacturer_id'=>$manufacturer_id))->get();
        return response()->json($models);
    }
    
    public function getModelByIDAjax(Request $request){
        $id = $request['id'];
        $models = ProductModel::find($id);
        return response()->json($models);
    }
    
    
}
