<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    protected $fillable = [
        'name'
    ];
     
    public function models(){
        return $this->hasMany('App\Model','manufacturer_id');
    }
    
    public function inouts(){
        return $this->hasMany('App\InOut','manufacturer_id');
    }
    
}
