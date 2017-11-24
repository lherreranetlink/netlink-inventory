<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InOut extends Model
{
     protected $fillable = ['mac','identifier','checkout_at', 'checkout_by','customer_id'];
     //protected $guarded = ["*"];
    public function model(){
        return $this->belongsTo('App\ProductModel');
    }
    
    public function manufacturer(){
        return $this->belongsTo('App\Manufacturer');
    }
    
    public function customer(){
        return $this->belongsTo('App\Customer');
    }
    
    public function checkins(){
        return $this->hasMany('App\CheckinLog','inout_id');
    }
    public function checkouts(){
        return $this->hasMany('App\CheckoutLog','inout_id');
    }
    public function checkoutBy(){
        return $this->belongsTo('App\User','checkout_by','id');
    }
    public function checkinBy(){
        return $this->belongsTo('App\User','checkin_by','id');
    }
    
    
    public function location(){
        return $this->belongsTo('App\Location');
    }
    
    public function sublocation(){
        return $this->belongsTo('App\SubLocation');
    }
    
    public function childlocation(){
        return $this->belongsTo('App\ChildLocation');
    }
}
