<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildLocation extends Model
{
    public function sublocation(){
        return $this->belongsTo('App\SubLocation');
    }
    public function inouts(){
        return $this->hasMany('App\InOut','childlocation_id');
    }
}
