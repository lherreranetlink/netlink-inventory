<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use App\SpreadSheet;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadForm()
    {
        return view('excel_upload_form');
    }

    public function uploadSubmit(Request $request)
    {
       // $spreadSheet = SpreadSheet::create($request->all());
        $filename = $request->spreadsheet->store("spreadsheets");
        $contents = File::get(storage_path('app/'.$filename));
        echo $contents;
            /*ProductsPhoto::create([
                'product_id' => $product->id,
                'filename' => $filename
            ]);*/
        //return 'Upload successful!';
    }
}
