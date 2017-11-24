<?php

namespace App\Http\Controllers;
use App\User;

use App\CheckinLog;
use App\CheckoutLog;
use App\LocationChangeLog;
//use App\FaxNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    

    private $data;
    public function __construct() {
        //$this->middleware('auth');
        $this->data['title'] = "Profile";
    }
    
    
    public function profile(Request $request){
        return view('profile',['data'=>$this->data]);
        
    }
    
    
    public function changePassword(Request $request){
        $user = User::find($request['id']);
        if(isset($request['password'])&&$request['password']!=""){
            $user->password = Hash::make($request['password']);
        }
        if($user->save()){
            Auth::logout();
        }
        
        return redirect('/profile');  
    }
    
    
    
    public function changeProfilePic(Request $request){
        $response = array();
        $response['file'] = $request['file'];
        $user = User::find(Auth::user()->id);
        $user->profile_pic = $request['file'];
        $user->save();
        return response()->json($response);
    }
    
    
    //AJAX...................................................
    public function checkins(Request $request){
        $id = $request['id'];
        $checkins = CheckinLog::where(array('checkin_by'=>$id))->with(['model','inout'])->get();
        return response()->json($checkins);
    }
    public function checkouts(Request $request){
        $id = $request['id'];
        $checkouts = CheckoutLog::where(array('checkout_by'=>$id))->with(['model','inout'])->get();
        return response()->json($checkouts);
        
    }
    public function locationChange(Request $request){
        $id = $request['id'];
        $location_changes = LocationChangeLog::where(array('location_change_by'=>$id))->with(['model','inout'])->get();
        return response()->json($location_changes);  
    }
    
    
    
    
}
