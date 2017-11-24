<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Manufacturer;
use App\Location;
use App\Category;
use App\SubCategory;

use App\SubLocation;
use App\ChildLocation;

use App\ChildCategory;
use App\User;
use App\Customer;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    private $data;
    public function __construct()
    {
        $this->data['title'] = "Admin";
    }
    
    public function index()
    {
        $this->data['title'] = "Admin";
        return view('home',['data'=>$this->data]);
    }
    //Manufacturer.........................................................................
    public function manufacturers(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:manufacturers'
            ]);
            $manu = new Manufacturer();
            $manu->name = $request['name'];
            $manu->created_by = Auth::user()->id;
            $manu->save();
            return redirect('/manufacturers');
        }
        //$this->data['title'] = "Manufacturers";
        $this->data['manufacturers'] = Manufacturer::all();
        return view('admin/manufacturers',['data'=>$this->data]);
    }
    public function deleteManufacturer(Request $request){
        $id = $request['id'];
        $manu = Manufacturer::find($id);
        $manu->deleted = 1;
        $manu->save();
        return redirect('/manufacturers');
    }
    
    public function manufacturersEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                //'host_id'=>'required'
            ]);
            $manu = Manufacturer::find($request['id']);
            $manu->name = $request['name'];
            $manu->deleted = $request['deleted'];
            
            $manu->save();
            return redirect('/manufacturers');
        }
        //$this->data['title'] = "Manufacturers";
        $this->data['manufacturer'] = Manufacturer::find($request['id']);
        return view('admin/manufacturers-edit',['data'=>$this->data]);
    }
    
    
    //Customer..................................................................................
    public function customers(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:customers'
            ]);
            $customer = new Customer();
            $customer->name = $request['name'];
            $customer->address = $request['address'];
            $customer->contact = $request['contact'];
            $customer->created_by = Auth::user()->id;
            $customer->save();
            return redirect('/customers');
        }
        //$this->data['title'] = "Manufacturers";
        $this->data['customers'] = Customer::all();
        return view('admin.customers',['data'=>$this->data]);
    }
    public function deleteCustomer(Request $request){
        $id = $request['id'];
        $customer = Customer::find($id);
        $customer->deleted = 1;
        $customer->save();
        return redirect('/customers');
    }
    
    public function customersEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                //'host_id'=>'required'
            ]);
            $customer = Customer::find($request['id']);
            $customer->name = $request['name'];
            $customer->address = $request['address'];
            $customer->contact = $request['contact'];
            $customer->deleted = $request['deleted'];
            
            $customer->save();
            return redirect('/customers');
        }
        //$this->data['title'] = "Manufacturers";
        $this->data['customer'] = Customer::find($request['id']);
        return view('admin.customers-edit',['data'=>$this->data]);
    }
    
    
    //Categories..................................................................................
    public function categories(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:categories'
            ]);
            $category = new Category();
            $category->name = $request['name'];
            $category->description = $request['description'];
            $category->created_by = Auth::user()->id;
            $category->save();
            return redirect('/categories');
        }
        
        //$this->data['categories'] = Category::where(array('deleted'=>0))->get();
        $this->data['categories'] = Category::all();
        return view('admin.categories',['data'=>$this->data]);
    }
    public function deleteCategory(Request $request){
        $id = $request['id'];
        $subcategories = SubCategory::where('category_id', $id)->get();
        foreach($subcategories as $subcategory){
            $sub_des = ChildCategory::where('subcategory_id', $subcategory['id'])->update(['deleted' => 1]);
        }
        $sub = SubCategory::where('category_id', $id)->update(['deleted' => 1]);
        $category = Category::find($id);
        $category->deleted = 1;
        $category->save();
        return redirect('/categories');
    }
    
    public function categoriesEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                //'host_id'=>'required'
            ]);
            $category = Category::find($request['id']);
            $category->name = $request['name'];
            $category->deleted = $request['deleted'];
            $category->save();
            return redirect('/categories');
        }
        //$this->data['title'] = "Manufacturers";
        $this->data['category'] = Category::find($request['id']);
        return view('admin.categories-edit',['data'=>$this->data]);
    }
    
    
    
    
    //Locations..................................................................................
    public function locations(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:locations'
            ]);
            $location = new Location();
            $location->name = $request['name'];
            if(isset($request['description'])){
                $location->description = $request['description'];
            }
            $location->created_by = Auth::user()->id;
            $location->save();
            return redirect('/locations');
        }
        
        $this->data['locations'] = Location::all();
        return view('admin.locations',['data'=>$this->data]);
    }
    public function deleteLocation(Request $request){
        $id = $request['id'];
        
        $sublocations = SubLocation::where('location_id', $id)->get();
        foreach($sublocations as $sublocation){
            $sub_des = ChildLocation::where('sublocation_id', $sublocation['id'])->update(['deleted' => 1]);
        }
        $sub = SubLocation::where('location_id', $id)->update(['deleted' => 1]);
        
        $location = Location::find($id);
        $location->deleted = 1;
        $location->save();
        return redirect('/locations');
    }
    
    public function locationsEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                //'host_id'=>'required'
            ]);
            $location = Location::find($request['id']);
            $location->name = $request['name'];
            if(isset($request['description'])){
                $location->description = $request['description'];
            }
            $location->deleted = $request['deleted'];
            $location->save();
            return redirect('/locations');
        }
        //$this->data['title'] = "Manufacturers";
        $this->data['location'] = Location::find($request['id']);
        return view('admin.locations-edit',['data'=>$this->data]);
    }
    
    
    
    //Users..................................................................................
    public function users(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'email'=> 'required',
                'name'=> 'required'
            ]);
            $user = new User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->role = $request['role'];
            $user->tag_code = $request['barcode'];
            $user->created_by = Auth::user()->id;
            $user->save();
            return redirect('/users');
        }
        
        $this->data['users'] = User::all();
        return view('admin.users',['data'=>$this->data]);
    }
    public function deleteUser(Request $request){
        $id = $request['id'];
        $user = User::find($id);
        $user->deleted = 1;
        $user->save();
        return redirect('/users');
    }
    
    public function editUser(Request $request){
        $user = User::find($request['id']);
        if(isset($request['password'])&&$request['password']!=""){
            $user->password = Hash::make($request['password']);
        }
        if(isset($request['barcode'])&&$request['barcode']!=""){
            $user->tag_code = $request['barcode'];
        }
        
        $user->deleted = $request['deleted'];
        $user->save();
        return redirect('/users');  
    }
    
    
    
    
    
    
    
    
    
    
    
    
    //Subcategories..................................................................................
    public function subcategories(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:sub_categories,name,NULL,id,category_id,'.$request['category_id'],
                'category_id'=> 'required'
            ]);
            $subcat = new SubCategory();
            $subcat->name = $request['name'];
            $subcat->category_id = $request['category_id'];
            $subcat->created_by = Auth::user()->id;
            $subcat->save();
            return redirect('/subcategories');
        }
        $this->data['categories'] = Category::all();
        $this->data['subcategories'] = SubCategory::all();
        return view('admin.subcategories',['data'=>$this->data]);
    }
    public function deleteSubCategory(Request $request){
        $id = $request['id'];
        $child = ChildCategory::where('subcategory_id', $id)->update(['deleted' => 1]);
        $subcat = SubCategory::find($id);
        $subcat->deleted = 1;
        $subcat->save();
        return redirect('/categories/subcategories/'.$request['category_id']);
    }
    
    public function subcategoriesEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
            ]);
            $subcat = SubCategory::find($request['id']);
            $subcat->name = $request['name'];
            $subcat->deleted = $request['deleted'];
            $subcat->save();
           return redirect('/categories/subcategories/'.$request['category_id']);
        }
        $this->data['categories'] = Category::all();
        $this->data['subcategory'] = SubCategory::find($request['id']);
        return view('admin.subcategories-edit',['data'=>$this->data]);
    }
    
    
    
    
    
    
    
   
    //ChildCategories..................................................................................
    public function childcategories(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:child_categories,name,NULL,id,subcategory_id,'.$request['sub_category_id'],
                'sub_category_id'=>'required'
            ]);
            $childcat = new ChildCategory();
            $childcat->name = $request['name'];
            $childcat->subcategory_id = $request['sub_category_id'];
            $childcat->created_by = Auth::user()->id;
            $childcat->save();
            return redirect('/childcategories');
        }
        $this->data['subcategories'] = SubCategory::all();
        $this->data['childcategories'] = ChildCategory::all();
        return view('admin.childcategories',['data'=>$this->data]);
    }
    public function deleteChildCategory(Request $request){
        $id = $request['id'];
        $category = ChildCategory::find($id);
        $category->deleted = 1;
        $category->save();
        return redirect('/categories/subcategories/childcategories/'.$request['sub_category_id']);
    }
    
    public function childcategoriesEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'sub_category_id'=>'required'
            ]);
            $childcat = ChildCategory::find($request['id']);
            $childcat->name = $request['name'];
            $childcat->subcategory_id = $request['sub_category_id'];
            $childcat->deleted = $request['deleted'];
            $childcat->save();
            return redirect('/categories/subcategories/childcategories/'.$request['sub_category_id']);
        }
        $this->data['subcategories'] = SubCategory::all();
        $this->data['childcategory'] = ChildCategory::find($request['id']);
        return view('admin.childcategories-edit',['data'=>$this->data]);
    }
    
    
    
    
    
    
    
     // Sub Locations..................................................................................
    public function sublocations(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'location_id'=>'required'
            ]);
            $subloc = new SubLocation();
            $subloc->name = $request['name'];
            if(isset($request['description'])){
                $subloc->description = $request['description'];
            }
            $subloc->location_id = $request['location_id'];
            $subloc->created_by = Auth::user()->id;
            $subloc->save();
            return redirect('/sublocations');
        }
        $this->data['locations'] = Location::all();
        $this->data['sublocations'] = SubLocation::all();
        return view('admin.sublocations',['data'=>$this->data]);
    }
    public function deleteSubLocation(Request $request){
        $id = $request['id'];
        $child = ChildLocation::where('sublocation_id', $id)->update(['deleted' => 1]);
        $sub = SubLocation::find($id);
        $sub->deleted = 1;
        $sub->save();
        return redirect('/locations/sublocations/'.$request['location_id']);
    }
    
    public function sublocationsEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'location_id'=>'required'
            ]);
            $sub = SubLocation::find($request['id']);
            $sub->name = $request['name'];
            if(isset($request['description'])){
                $sub->description = $request['description'];
            }
            $sub->location_id = $request['location_id'];
            $sub->deleted = $request['deleted'];
            $sub->save();
            
            return redirect('/locations/sublocations/'.$request['location_id']);
        }
        $this->data['locations'] = Location::all();
        $this->data['sublocation'] = SubLocation::find($request['id']);
        return view('admin.sublocations-edit',['data'=>$this->data]);
    }
    
    
    // Child Locations..................................................................................
    public function childlocations(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'sublocation_id'=>'required'
                
            ]);
            $childLoc = new ChildLocation();
            $childLoc->name = $request['name'];
            if(isset($request['description'])){
                $childLoc->description = $request['description'];
            }
            $childLoc->sublocation_id = $request['sublocation_id'];
            $childLoc->created_by = Auth::user()->id;
            
            $childLoc->save();
            return redirect('/childlocations');
        }
        $this->data['sublocations'] = SubLocation::all();
        $this->data['childlocations'] = ChildLocation::all();
        return view('admin.childlocations',['data'=>$this->data]);
    }
    public function deleteChildLocation(Request $request){
        $id = $request['id'];
        $child = ChildLocation::find($id);
        $child->deleted = 1;
        $child->save();
        return redirect('/locations/sublocations/childlocations/'.$request['sublocation_id']);
    }
    
    public function childlocationsEdit(Request $request)
    {
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'sublocation_id'=>'required'
            ]);
            $child = ChildLocation::find($request['id']);
            $child->name = $request['name'];
            if(isset($request['description'])){
                $child->description = $request['description'];
            }
            $child->sublocation_id = $request['sublocation_id'];
            $child->deleted = $request['deleted'];
            $child->save();
            return redirect('/locations/sublocations/childlocations/'.$request['sublocation_id']);
        }
        $this->data['sublocations'] = SubLocation::all();
        $this->data['childlocation'] = ChildLocation::find($request['id']);
        return view('admin.childlocations-edit',['data'=>$this->data]);
    }
    
    
    public function addCustomerAjax(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:customers'
            ]);
            $cust = new Customer();
            $cust->name = trim($request['name']);
            $cust->contact_person = trim($request['contact_person']);
            $cust->address = trim($request['address']);
            $cust->contact = trim($request['contact']);
            
            $cust->save();
            $response['name'] = $cust->name;
            $response['address'] = $cust->address;
            return response()->json($response);
        }
    }
    
    
    public function subcategoriesByCat(Request $request, $category_id=null){
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:sub_categories,name,NULL,id,category_id,'.$request['category_id'],
                'category_id'=> 'required'
            ]);
            $subcat = new SubCategory();
            $subcat->name = $request['name'];
            $subcat->category_id = $request['category_id'];
            $subcat->created_by = Auth::user()->id;
            $subcat->save();
            return redirect('categories/subcategories/'.$request['category_id']);
        }
        $this->data['subcategories'] = SubCategory::where(array("category_id"=>$category_id))->get();
        $this->data['category_id'] = $category_id;
        $this->data['category'] = Category::find($category_id);
        return view('admin.subcategories-by-category',['data'=>$this->data]);
    }
    
    public function childcategoriesBySub(Request $request, $subcategory_id=null){
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required|unique:child_categories,name,NULL,id,subcategory_id,'.$request['sub_category_id'],
                'sub_category_id'=>'required'
            ]);
            $childcat = new ChildCategory();
            $childcat->name = $request['name'];
            $childcat->subcategory_id = $request['sub_category_id'];
            $childcat->created_by = Auth::user()->id;
            $childcat->save();
            return redirect('/categories/subcategories/childcategories/'.$request['sub_category_id']);
        }
        $this->data['childcategories'] = ChildCategory::where(array("subcategory_id"=>$subcategory_id))->get();
        $this->data['subcategory_id'] = $subcategory_id;
        $this->data['subcategory'] = SubCategory::find($subcategory_id);
        return view('admin.childcategories-by-subcategory',['data'=>$this->data]);
    }
    
    
    public function sublocationsByLoc(Request $request, $location_id=null){
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'location_id'=>'required'
            ]);
            $subloc = new SubLocation();
            $subloc->name = $request['name'];
            $subloc->description = $request['description'];
            $subloc->location_id = $request['location_id'];
            $subloc->created_by = Auth::user()->id;
            $subloc->save();
            return redirect('/locations/sublocations/'.$request['location_id']);
        }
        $this->data['sublocations'] = SubLocation::where(array("location_id"=>$location_id))->get();
        $this->data['location_id'] = $location_id;
        $this->data['location'] = Location::find($location_id);
        return view('admin.sublocations-by-location',['data'=>$this->data]);
    }
    
    public function childlocationsBySub(Request $request, $sublocation_id=null){
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'name'=> 'required',
                'sublocation_id'=>'required'
                
            ]);
            $childLoc = new ChildLocation();
            $childLoc->name = $request['name'];
            $childLoc->sublocation_id = $request['sublocation_id'];
            $childLoc->description = $request['description'];
            $childLoc->created_by = Auth::user()->id;
            
            $childLoc->save();
            return redirect('/locations/sublocations/childlocations/'.$request['sublocation_id']);
        }
        $this->data['childlocations'] = ChildLocation::where(array("sublocation_id"=>$sublocation_id))->get();
        $this->data['sublocation_id'] = $sublocation_id;
        $this->data['sublocation'] = SubLocation::find($sublocation_id);
        return view('admin.childlocations-by-sublocation',['data'=>$this->data]);
    }
    
    
    
}
