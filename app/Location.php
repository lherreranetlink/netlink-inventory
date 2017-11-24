<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function sublocations(){
        return $this->hasMany('App\SubLocation','location_id');
    }
    
    public function inouts(){
        return $this->hasMany('App\InOut','location_id');
    }
}
