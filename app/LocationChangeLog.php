<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocationChangeLog extends Model
{
    public function model(){
        return $this->belongsTo('App\ProductModel');
    }
    
    public function user(){
        return $this->belongsTo('App\User','location_change_by','id');
    }
    
    public function inout(){
        return $this->belongsTo('App\InOut','inout_id','id');
    }
}
