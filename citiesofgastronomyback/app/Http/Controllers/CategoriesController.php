<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Recipes;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{



    public function findCategory($id){

        $obj = [];
        try{
                $obj = Categories::where('id', $id);
        }catch(\Exception $e){};

        return response()->json([
            'category' => $obj
        ]);
    }


    public function store(Request $request){
        $objItem = [];$message='';$coincidencia = '';$status = 200;
        $name = $request->input("name");

        $id = $request->input("id");
        $obj = (New Categories())->findName($name, $id);
        Log::info($name);
        if( count($obj) > 0){
            $cant1 = strlen($name);
            $cant2 = strlen($obj[0]["name"]);
            if($cant1 == $cant2){
                $coincidencia = 'si';

                $status = 400; $message = 'The name of this filter already exists in the database';
            };
        };

        if($coincidencia != 'si'){
            if(  !$request->input("id")  ){
                $message = 'The filter was successfully created';
                Log::info("::CREA Categories");
                $objItem = new Categories;
                $objItem->created_at = date("Y-m-d H:i:s");
                //$objItem->active = '1';
            }else{
                $message = 'The filter was successfully edited';
                Log::info("::MODIFICA Categories");
                $objItem = Categories::findOrFail( $request->input("id")  );
            };
            $objItem->name = $request->input("name");
            $objItem->updated_at = date("Y-m-d H:i:s");
            $objItem -> save();
        };

        return response()->json([
            'category' => $objItem,
            'message' => $message,
            'status' => $status
        ]);
    }

    public function delete($id){
        //Log::info("Categories Delete ::");
        $status = 200;$message = 'The filter was successfully deleted';

        $obj = (New Recipes())->where('idCategory', $id)->get();
        //Log::info($obj);

        if( count($obj) > 0){
                $status = 400;
                $message = 'This filter cannot be deleted because it is being used by a Recipe. Please reassign the filter and try again';
        }else{
            $objFilter = Categories::find($id);
            if($objFilter != NULL){
                $objFilter->delete();
            };
        };

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }



}
