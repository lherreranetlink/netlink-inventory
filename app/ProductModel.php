<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'models';
    public function manufacturer(){
        return $this->belongsTo('App\Manufacturer');
    }
    public function inouts(){
        return $this->hasMany('App\InOut','model_id');
    }
    
    public function category(){
        return $this->belongsTo('App\Category');
    }
    
    public function subcategory(){
        return $this->belongsTo('App\SubCategory');
    }
    
    public function childcategory(){
        return $this->belongsTo('App\ChildCategory');
    }
    
    public function checkins(){
        return $this->hasMany('App\CheckinLog','model_id');
    }
    public function checkouts(){
        return $this->hasMany('App\CheckoutLog','model_id');
    }
    
    public function locationchnage(){
        return $this->hasMany('App\LocationChangeLog','model_id');
    }
    
}
