<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function inout(){
        return $this->hasMany('App\InOut','customer_id');
    }
}
