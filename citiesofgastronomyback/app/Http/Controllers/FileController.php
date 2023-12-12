<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Files;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{

    public function store(Request $request)
    {
        $status = 200;$mensaje = '';$objFILE = [];
        $pdf = $request->file("pdf");
        $id = $request->input("id");
        $title = $request->input("title");
        $idSection = $request->input("idSection");
        $idOwner = $request->input("idOwner");
        Log::info("###-Z");
        Log::info($request);
            if(!$id){
                if($request->file("pdf")){
                    Log::info("*1");
                    $objFILE = (New Files())->storeFILE($pdf, $idOwner, $idSection, $title, 0);
                };
            }else{
                Log::info("*2");
                $objFILE = (New Files())->saveFILE($id, $pdf, $idOwner, $idSection, $title, 1);
            };
            try{
                /*
                $request->validate ([
                    'pdf' => 'image|max:50000'
                ]);//*/
            } catch ( \Exception $e ) {

            }
        //Log::info($objFILE);
        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objFILE
        ]);
    }

}
