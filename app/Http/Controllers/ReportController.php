<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getItemsByManufacturers(){
        $response = array();
        $result = array();
        $items = array();
        $manufacturers = \App\Manufacturer::all();
        foreach($manufacturers as $manu){
            $match = array('checkout_by'=>0,'manufacturer_id'=>$manu->id);
            $inouts = \App\InOut::where($match)->get();
            foreach($inouts as $inout){
                if(isset($inout->model)){
                    $items[] = array(
                        'mac'=>$inout->mac,
                        'identifier'=>$inout->identifier,
                        'model_number'=>$inout->model->model_number,
                    );
                }
            }
            $result = array(
              'manufacturer' =>$manu->name,
              'count'=>count($inouts),
              'items'=>$items
            );
            $response[] = $result;
            $items = array();
        }
        return response()->json($response);
    }
    
    public function getItemsByCategories(){
        $response = array();
        $result = array();
        $items = array();
        $categories = \App\Category::all();
        foreach($categories as $cat){
            $models = $cat->models;
            $items = array();
            foreach($models as $mod){
                $model_id = $mod->id;
                $result = \App\InOut::where(array('model_id'=>$model_id,'checkout_by'=>0))->get();
                if(count($result)>0){
                    $items[] = $result;
                }
                
            }
            $result = array(
              'category' =>$cat->name,
              'count'=>count($items),
              'items'=>$items
            );
            $response[] = $result;
            $items = array();
        }
        return response()->json($response);
    }
}
