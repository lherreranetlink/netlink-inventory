<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubLocation extends Model
{
    public function location(){
        return $this->belongsTo('App\Location');
    }
    
    public function childlocations(){
        return $this->hasMany('App\ChildLocation','sublocation_id');
    }
    
    public function inouts(){
        return $this->hasMany('App\InOut','sublocation_id');
    }
}
