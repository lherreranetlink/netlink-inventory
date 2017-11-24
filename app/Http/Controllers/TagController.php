<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Manufacturer;
use App\InOut;
use App\User;
use App\CheckinLog;
use App\CheckoutLog;
use App\LocationChangeLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    
    private $data;
    public function __construct()
    {
        $this->data['title'] = "Inventory";
        $this->data['sidebar'] = "tag-user.sidebar";
        $this->data['header'] = "tag-user.header";
    }

    public function tagLogin(Request $request){
        if($request->getMethod()=="POST"){
            //echo $request['barcode'];
            $user = User::where(array('tag_code'=>$request['barcode']))->first();
            if(count($user)==0||$user->deleted!=0){
                $this->data['msg'] = "The code is not authorized, Please contact Netlink Developement.(".$request['barcode'].")";
            }
            else{
                $this->data['msg'] = "Authorized";
                $this->addSession($user,$request['barcode']);
                echo session('user-email');
                return redirect('/tag-user/home');
            }
        }
        return view('auth.tag-login',['data'=>$this->data]);
    }
    
    private function addSession($user,$barcode){
       session(['user-barcode' => $barcode]);
       session(['user-email' => $user->email]);
       session(['user-name' => $user->name]);
       session(['user-id' => $user->id]);
       session(['user-role' => $user->role]);
       return true;
    }
    
    public function index(){
        $this->data['title'] = "Dashboard";
        return view('tag-user.home',['data'=>$this->data]);
    }
    
    public function logout(Request $request){
        $request->session()->forget('user-barcode');
        $request->session()->forget('user-email');
        $request->session()->forget('user-name');
        $request->session()->forget('user-id');
        $request->session()->flush();
        
        return redirect('/tag-user/login');
        
    }
    public function bulkCheckin(Request $request){
        //$this->data['checkins'] = CheckinLog::orderBy('created_at', 'desc')->paginate(15);
        return view('tag-user.bulk-checkin',['data'=>$this->data]);
    }
    public function bulkCheckout(Request $request){
        //$this->data['checkins'] = CheckinLog::orderBy('created_at', 'desc')->paginate(15);
        return view('tag-user.bulk-checkout',['data'=>$this->data]);
    }
    
    
    
    
}
