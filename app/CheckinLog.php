<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CheckinLog extends Model
{
    public function model(){
        return $this->belongsTo('App\ProductModel');
    }
    
    public function user(){
        return $this->belongsTo('App\User','checkin_by','id');
    }
    
    public function inout(){
        return $this->belongsTo('App\InOut','inout_id','id');
    }
}
