<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChildCategory extends Model
{
    public function subcategory(){
        return $this->belongsTo('App\SubCategory');
    }
    
    public function model(){
        return $this->hasMany('App\Model','childcategory_id');
    }
}
