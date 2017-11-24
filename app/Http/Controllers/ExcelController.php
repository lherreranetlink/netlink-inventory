<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function index()
    {
        // Possible to import or convert from and to:
        // xlsx, xlsm, xltx, xltm, xls, xlt, ods, ots, slk, xml, gnumeric, htm, html, csv, txt, pdf.

        /*Excel::create('Laravel Excel', function($excel) {
            $excel->sheet('Productos', function($sheet) {
                //$products = Product::all();
                $sheet->fromArray(array("tumama" => "tupapa"));
            });
        })->export('pdf');*/
    }

    public function store(Request $request)
    {
        if ($_FILES['selected_file']['error'] > 0 ){
            return 'Error: ' . $_FILES['selected_file']['error'] . '<br>';
        } else {
            return "File Uploaded Successfully";
        }
    }
}
