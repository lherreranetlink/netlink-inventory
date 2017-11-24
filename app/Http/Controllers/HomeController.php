<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manufacturer;
use App\InOut;
use App\CheckinLog;
use App\CheckoutLog;
use App\LocationChangeLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $data;
    public function __construct()
    {
        $this->data['title'] = "Inventory";
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['title'] = "Dashboard";
        return view('home',['data'=>$this->data]);
    }
    
    public function search(){
        //$this->data['title'] = "Search";
        return view('search',['data'=>$this->data]);
    }
    
    public function accessDenied(){
        $this->data['title'] = "Access Denied!!";
        return view('access-denied',['data'=>$this->data]);
    }
    public function userDeleted(){
        $this->data['title'] = "User Has been deleted!!";
        return view('user-deleted',['data'=>$this->data]);
    }
    public function checkIn($barcode=null){
        if(isset($barcode)&&$barcode!=""){
            $this->data['barcode'] = $barcode;
        }
        return view('check-in',['data'=>$this->data]);
    }
    
    public function checkOut(){ 
        return view('check-out',['data'=>$this->data]);
    }
    public function checkinBack(){ 
        return view('check-in-back',['data'=>$this->data]);
    }
    
    
    public function processCheckinAdd(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'manufacturer_id'=> 'required',
                'model_id'=> 'required',
                'condition'=> 'required'
            ]);
            
            $inout = InOut::where('mac', $request['mac'])->first();
            
            if(count($inout)==0){
                $inout = InOut::where('identifier', $request['identifier'])->first();
                if(count($inout)==0){
                    $inout = new InOut();
                }
            }
            
            $inout->manufacturer_id = $request['manufacturer_id'];
            $inout->model_id = $request['model_id'];
            $inout->condition = $request['condition'];
            $inout->checkin_at = @date("Y-m-d H:i:s");
            $inout->checkin_by = Auth::user()->id;
            $inout->checkout_at = null;
            $inout->checkout_by = 0;
            $inout->customer_id = 0;
            
            if(isset($request['mac'])&&$request['mac']!=""){
                $inout->mac = $request['mac'];
            }
            
            if(isset($request['identifier'])&&$request['identifier']!=""){
                $inout->identifier = $request['identifier'];
            }
            if(isset($request['identifier2'])&&$request['identifier2']!=""){
                $inout->identifier2 = $request['identifier2'];
            }
            if(isset($request['location_id'])&&$request['location_id']!=""){
                $inout->location_id = $request['location_id'];
            }
            if(isset($request['sublocation_id'])&&$request['sublocation_id']!=""){
                $inout->sublocation_id = $request['sublocation_id'];
            }
            if(isset($request['childlocation_id'])&&$request['childlocation_id']!=""){
                $inout->childlocation_id = $request['childlocation_id'];
            }
            
            //Note......................................................................
            if(isset($request['used_note'])&&$request['used_note']!=""){
                $inout->used_note = $request['used_note'];
            }
            if(isset($request['refurbished_note'])&&$request['refurbished_note']!=""){
                $inout->refurbished_note = $request['refurbished_note'];
            }
            
            if($inout->save()){
                $checkin_log = new CheckinLog();
                $checkin_log->model_id = $request['model_id'];
                $checkin_log->checkin_by = Auth::user()->id;
                $checkin_log->inout_id = $inout->id;
                $checkin_log->save();
                   
            }
            $response['msg'] = "success";
            return response()->json($response);
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    public function checkinHistory(){
        $this->data['checkins'] = CheckinLog::orderBy('created_at', 'desc')->paginate(15);
        return view('checkin-history',['data'=>$this->data]);
    }
    
    public function checkoutHistory(){
        $this->data['checkouts'] = CheckoutLog::orderBy('created_at', 'desc')->paginate(15);
        return view('checkout-history',['data'=>$this->data]);
    }
    
    public function locationChangeHistory(){
        $this->data['locations'] = LocationChangeLog::orderBy('created_at', 'desc')->paginate(15);
        return view('location-change-history',['data'=>$this->data]);
    }
    
    public function getCheckinByBarcode(Request $request){
        $barcode = $request['barcode'];
        $response = array();
        $error = array();
        if($barcode==""||$barcode==" "){
            return response()->json($error);
        }
       
        $result = array();
        $matchThese = ['mac' => $barcode];
        $inout = InOut::where($matchThese)->first();
        if(count($inout)<=0){
            $matchThese = ['identifier' => $barcode];
            $inout = InOut::where($matchThese)->first();
            if(count($inout)<=0){
                $matchThese = ['identifier2' => $barcode];
                $inout = InOut::where($matchThese)->first();
            }
        }
        if(count($inout)>0){
            $result['id'] = $inout['id'];
            $result['model_id'] = $inout['model_id'];
            $result['checkin_at'] = $inout['checkin_at'];
            $result['checkout_at'] = $inout['checkout_at'];
            $result['checkout_by'] = $inout['checkout_by'];
            
            $result['condition'] = $inout['condition'];
            $result['mac'] = $inout['mac'];
            $result['identifier'] = $inout['identifier'];
            $result['identifier2'] = $inout['identifier2'];
            $result['manufacturer'] = $inout->manufacturer->name;
            $result['model_number'] = $inout->model->model_number;
            $result['is_identifier'] = $inout->model->identifier;
            $result['picture'] = $inout->model->picture;
            $result['identifier_name'] = $inout->model->identifier_name;
            $result['identifier_name2'] = $inout->model->identifier_name2;
            
            
            if($inout['checkout_by']!=0){
                $result['checkout_by_name'] = $inout->checkoutBy->name;
            }
            else{
                $result['checkout_by_name'] = "";
            }
            
            if($inout['customer_id']!=0){
                $result['customer'] = $inout->customer->name;
            }
            else{
                $result['customer'] = "";
            }
            
            
            
            
            if($inout['location_id']!=0){
                $result['location'] = $inout->location->name;
            }
            else{
                $result['location'] = "";
            }

            if($inout['sublocation_id']!=0){
                $result['sublocation'] = $inout->sublocation->name;
            }
            else{
                $result['sublocation'] = "";
            }
            if($inout['childlocation_id']!=0){
                $result['childlocation'] = $inout->childlocation->name;
            }
            else{
                $result['childlocation'] = "";
            }


            if($inout->model->category_id!=0){
                $result['category'] = $inout->model->category->name;
            }
            else{
                $result['category'] = "";
            }
            if($inout->model->subcategory_id!=0){
                $result['subcategory'] = $inout->model->subcategory->name;
            }
            else{
                $result['subcategory'] = "";
            }
            if($inout->model->childcategory_id!=0){
                $result['childcategory'] = $inout->model->childcategory->name;
            }
            else{
                $result['childcategory'] = "";
            }


            if($inout->checkin_by!=0){
                $result['checkin_by'] = $inout->checkinBy->name;
            }
            else{
                $result['checkin_by'] = "";
            }

            return response()->json($result);
        }
        return response()->json($response);
    }
    
    
    
    //Check Out.....................................................................
    public function processCheckoutAdd(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'model_id'=> 'required',
                'inout_id'=> 'required',
                'customer_id'=> 'required'
            ]);
            
            $inout = InOut::find($request['inout_id']);
            $inout->checkout_at = @date("Y-m-d H:i:s");
            $inout->checkout_by = Auth::user()->id;
            if(isset($request['customer_id'])&&$request['customer_id']!=""){
                $inout->customer_id = $request['customer_id'];
            }
           
            if($inout->save()){
                $checkout_log = new CheckoutLog();
                $checkout_log->inout_id = $inout->id;
                $checkout_log->model_id = $request['model_id'];
                $checkout_log->checkout_by = Auth::user()->id;
                $checkout_log->save();
                   
            }
            $response['msg'] = "success";
            return response()->json($response);
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    
    //Location change...................................................................
    public function locationChange(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'model_id'=> 'required',
                'inout_id'=> 'required'
            ]);
            
            $inout = InOut::find($request['inout_id']);
            $inout->location_id = $request['location_id'];
            if($request['sublocation_id']!=""){
                $inout->sublocation_id = $request['sublocation_id'];
            }
            else{
                $inout->sublocation_id = 0;
            }
            if($request['childlocation_id']!=""){
                $inout->childlocation_id = $request['childlocation_id'];
            }
            else{
                $inout->childlocation_id = 0;
            }
            if($inout->save()){
                $location_log = new LocationChangeLog();
                $location_log->inout_id = $inout->id;
                $location_log->model_id = $request['model_id'];
                $location_log->location_change_by = Auth::user()->id;
                $location_log->location_id = $request['location_id'];
                if($request['sublocation_id']!=""){
                    $location_log->sublocation_id = $request['sublocation_id'];
                }
                else{
                    $location_log->sublocation_id = 0;
                }
                if($request['childlocation_id']!=""){
                    $location_log->childlocation_id = $request['childlocation_id'];
                }
                else{
                    $location_log->childlocation_id = 0;
                }
                $location_log->save();         
            }
            $response['msg'] = "success";
            return response()->json($response);
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    //check in back with location...................................................................
    public function checkinBackProcess(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'model_id'=> 'required',
                'inout_id'=> 'required'
            ]);
            
            $inout = InOut::find($request['inout_id']);
            $inout->location_id = $request['location_id'];
            $inout->checkout_by = 0;
            $inout->checkout_at = null;
            if($request['sublocation_id']!=""){
                $inout->sublocation_id = $request['sublocation_id'];
            }
            else{
                $inout->sublocation_id = 0;
            }
            if($request['childlocation_id']!=""){
                $inout->childlocation_id = $request['childlocation_id'];
            }
            else{
                $inout->childlocation_id = 0;
            }
            if($inout->save()){
                //location change log....................
                /*
                $location_log = new LocationChangeLog();
                $location_log->inout_id = $inout->id;
                $location_log->model_id = $request['model_id'];
                $location_log->location_change_by = Auth::user()->id;
                $location_log->location_id = $request['location_id'];
                if($request['sublocation_id']!=""){
                    $location_log->sublocation_id = $request['sublocation_id'];
                }
                else{
                    $location_log->sublocation_id = 0;
                }
                if($request['childlocation_id']!=""){
                    $location_log->childlocation_id = $request['childlocation_id'];
                }
                else{
                    $location_log->childlocation_id = 0;
                }
                $location_log->save();
                 * 
                 */ 
                //Check in log...........................
                $checkin_log = new CheckinLog();
                $checkin_log->model_id = $request['model_id'];
                $checkin_log->checkin_by = Auth::user()->id;
                $checkin_log->inout_id = $inout->id;
                $checkin_log->save();
            }
            $response['msg'] = "success";
            return response()->json($response);
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    
    
    public function bulkCheckin(Request $request){
        //$this->data['checkins'] = CheckinLog::orderBy('created_at', 'desc')->paginate(15);
        return view('bulk-checkin',['data'=>$this->data]);
    }
    public function bulkCheckout(Request $request){
        //$this->data['checkins'] = CheckinLog::orderBy('created_at', 'desc')->paginate(15);
        return view('bulk-checkout',['data'=>$this->data]);
    }
    
    
    public function processBulkCheckinAdd(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            $this->validate($request, [
                'manufacturer_id'=> 'required',
                'model_id'=> 'required',
                'number_checkins'=>'required',
                'condition'=> 'required'
            ]);

            for($i=0;$i<$request['number_checkins'];$i++){
                //$inout = new InOut();
                if(isset($request['mac'])&&isset($request['mac'][$i])&&trim($request['mac'][$i])!=""){
                    $inout = InOut::where('mac', $request['mac'][$i])->first();
                    
                    if((count($inout)==0||$inout->count())&&isset($request['identifier'][$i])&&$request['identifier'][$i]!=""){
                        $inout = InOut::where('identifier', $request['identifier'][$i])->first();
                        if(count($inout)==0||$inout->count()){
                            $inout = new InOut();
                        }
                    }
                    else{
                        $inout = new InOut();
                        //next time if problem occurs make this above line commented.----Sunny
                    }
                    
                }
                else{
                    $inout = InOut::where('identifier', $request['identifier'][$i])->first();
                    if(count($inout)==0||$inout->count()){
                        $inout = new InOut();
                    }
                }

                $inout->manufacturer_id = $request['manufacturer_id'];
                $inout->model_id = $request['model_id'];
                $inout->condition = $request['condition'];
                $inout->checkin_at = @date("Y-m-d H:i:s");
                $inout->checkin_by = Auth::user()->id;
                $inout->checkout_at = null;
                $inout->checkout_by = 0;
                $inout->customer_id = 0;

                if(isset($request['location_id']) && $request['location_id']!=""){
                    $inout->location_id = $request['location_id'];
                }
                if(isset($request['sublocation_id'])&&$request['sublocation_id']!=""){
                    $inout->sublocation_id = $request['sublocation_id'];
                }
                if(isset($request['childlocation_id'])&&$request['childlocation_id']!=""){
                    $inout->childlocation_id = $request['childlocation_id'];
                }
                if(isset($request['mac'][$i])&&isset($request['identifier'][$i])&&$request['mac'][$i]==""&&$request['identifier'][$i]==""){
                    
                }
                else{
                    if(isset($request['mac'][$i])&&$request['mac'][$i]!=""){
                        $inout->mac = $request['mac'][$i];
                    }

                    if(isset($request['identifier'][$i])&&$request['identifier'][$i]!=""){
                        $inout->identifier = $request['identifier'][$i];
                    }
                    if(isset($request['identifier2'][$i])&&$request['identifier2'][$i]!=""){
                        $inout->identifier2 = $request['identifier2'][$i];
                    }
                    if($inout->save()){
                        $checkin_log = new CheckinLog();
                        $checkin_log->model_id = $request['model_id'];
                        $checkin_log->checkin_by = Auth::user()->id;
                        $checkin_log->inout_id = $inout->id;
                        $checkin_log->save();

                    }
                }
                
            }
            
            
            $response['msg'] = "success";
            return response()->json($response);
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    
    
    
    
    
    ///BULK.......................
    
    //
    
    
    //Bulk Check Out.....................................................................
    public function processBulkCheckoutAdd(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            foreach($request['checkouts'] as $checkout){
                //echo $checkout['checkout_at'];
                $inout = InOut::find($checkout['id']);
                $inout->checkout_at = @date("Y-m-d H:i:s");
                $inout->checkout_by = Auth::user()->id;
                if(isset($checkout['checkout_customer_id'])&&$checkout['checkout_customer_id']!=""){
                    $inout->customer_id = $checkout['checkout_customer_id'];
                }
                if($inout->save()){
                    $checkout_log = new CheckoutLog();
                    $checkout_log->inout_id = $inout->id;
                    $checkout_log->model_id = $checkout['model_id'];
                    $checkout_log->checkout_by = Auth::user()->id;
                    $checkout_log->save();
                }
            }
            
            $response['msg'] = "success";
            return response()->json($response);
            
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    
    //Bulk Location change...................................................................
    public function bulkLocationChange(Request $request){
        $response = array();
        if($request->getMethod()=="POST"){
            foreach($request['checkouts'] as $checkout){          
                $inout = InOut::find($checkout['id']);
                //$inout->location_id = $checkout['checkout_location_id'];
                if(isset($checkout['checkout_location_id'])&&$checkout['checkout_location_id']!=""){
                    $inout->location_id = $checkout['checkout_location_id'];
                }
                else{
                    $inout->location_id = 0;
                }
                if(isset($checkout['checkout_sublocation_id'])&&$checkout['checkout_sublocation_id']!=""){
                    $inout->sublocation_id = $checkout['checkout_sublocation_id'];
                }
                else{
                    $inout->sublocation_id = 0;
                }
                if(isset($checkout['checkout_childlocation_id'])&&$checkout['checkout_childlocation_id']!=""){
                    $inout->childlocation_id = $checkout['checkout_childlocation_id'];
                }
                else{
                    $inout->childlocation_id = 0;
                }
                if($inout->save()){
                    $location_log = new LocationChangeLog();
                    $location_log->inout_id = $inout->id;
                    $location_log->model_id = $checkout['model_id'];
                    $location_log->location_change_by = Auth::user()->id;
                    $location_log->location_id = $checkout['location_id'];
                    if(isset($checkout['checkout_sublocation_id'])&&$checkout['checkout_sublocation_id']!=""){
                        $location_log->sublocation_id = $checkout['checkout_sublocation_id'];
                    }
                    else{
                        $location_log->sublocation_id = 0;
                    }
                    if(isset($checkout['checkout_childlocation_id'])&&$checkout['checkout_childlocation_id']!=""){
                        $location_log->childlocation_id = $checkout['checkout_childlocation_id'];
                    }
                    else{
                        $location_log->childlocation_id = 0;
                    }
                    
                    $location_log->save();         
                }
            }
            $response['msg'] = "success";
            return response()->json($response);
        }
        $response['msg'] = "failed";
        return response()->json($response);
    }
    
    
    public function stock(){
        return view('stock',['data'=>$this->data]);
    }
    
    public function searchStock(Request $request){
        $response = array();
        $condition = array();
        $result = array();
        $flag = 0;
        //$updateCond = array(); 
        if($request->getMethod()=="POST"){
            if($request['manufacturer_id']!=""){
                $condition = array(
                    "manufacturer_id" =>$request['manufacturer_id']
                );
            }
            if($request['category_id']!=""){
                $condition = array(
                    "manufacturer_id" =>$request['manufacturer_id'],
                    "category_id" =>$request['category_id']
                );
            }
            if($request['subcategory_id']!=""){
                $condition = array(
                    "manufacturer_id" =>$request['manufacturer_id'],
                    "category_id" =>$request['category_id'],
                    "subcategory_id" =>$request['subcategory_id']
                );
            }
            if($request['childcategory_id']!=""){
                $condition = array(
                    "manufacturer_id" =>$request['manufacturer_id'],
                    "category_id" =>$request['category_id'],
                    "subcategory_id" =>$request['subcategory_id'],
                    "childcategory_id" =>$request['childcategory_id']
                );
            }
            
            
            //$matchThese = $condition;
            if($request['keyword']==""){
                $models = \App\ProductModel::where($condition)->get();
            }
            else{
                $models = \App\ProductModel::where($condition)->Where('model_number', 'like', '%' .$request['keyword'] . '%')->get();
            }
            
            //return response()->json($models);
            
            foreach($models as $model){
                if($model['category_id']!=0){
                    $category = $model->category->name;
                }
                else{
                    $category = "";
                }
                if($model['subcategory_id']!=0){
                    $subcategory = $model->subcategory->name;
                }
                else{
                    $subcategory = "";
                }
                if($model['childcategory_id']!=0){
                    $childcategory = $model->childcategory->name;
                }
                else{
                    $childcategory = "";
                }
                
                //if($inout->location->name)
                
                
                $secondCondition = ['model_id' => $model['id'], 'checkout_by' => 0];
                $inouts = InOut::where($secondCondition)->get();
                //return response()->json($inouts);
                foreach($inouts as $inout){
                    if($inout['location_id']!=0){
                        $location = $inout->location->name;
                    }
                    else{
                        $location = "";
                    }
                    if($inout['sublocation_id']!=0){
                        $sublocation = $inout->sublocation->name;
                    }
                    else{
                        $sublocation = "";
                    }
                    if($inout['childlocation_id']!=0){
                        $childlocation = $inout->childlocation->name;
                    }
                    else{
                        $childlocation = "";
                    }
                    
                    $result[] = array(
                        'model_number'=>$model['model_number'],
                        'description'=>$model['description'],
                        'identifier_name'=>$model['identifier_name'],
                        'is_identifier'=>$model['identifier'],
                        'picture'=>$model['picture'],
                        'manufacturer'=>$model->manufacturer->name,
                        'category'=>$category,
                        'subcategory'=>$subcategory,
                        'childcategory'=>$childcategory,
                        'checkin_by'=>$inout->checkinBy->name,
                        'location'=>$location,
                        'sublocation'=>$sublocation,
                        'childlocation'=>$childlocation,
                        'mac'=>$inout['mac'],
                        'identifier'=>$inout['identifier'],
                        'condition'=>$inout['condition'],
                        'checkin_at'=>$inout['checkin_at'],
                    
                    );
                }
           
            }
            return response()->json($result);
        }
        return response()->json($response);
    }
    
}
