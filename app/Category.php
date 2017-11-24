<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function subcategories(){
        return $this->hasMany('App\SubCategory','category_id');
    }
    
    public function models(){
        return $this->hasMany('App\ProductModel','category_id');
    }
}
