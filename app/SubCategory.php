<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    public function category(){
        return $this->belongsTo('App\Category');
    }
    
    public function model(){
        return $this->hasMany('App\Model','subcategory_id');
    }
    
    public function childcategories(){
        return $this->hasMany('App\ChildCategory','subcategory_id');
    }
}
