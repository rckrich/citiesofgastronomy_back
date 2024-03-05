<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chef;
use App\Models\SocialNetwork;
use App\Models\SocialNetworkType;
use Illuminate\Support\Facades\Log;

class ChefController extends Controller
{


    public function findChef($id){

        $obj = [];
        try{
                $obj = Chef::findOrFail($id);
        }catch(\Exception $e){};
        $social = (New SocialNetworkType())->list(2);

        return response()->json([
            'chef' => $obj,
            'SocialNetworkType' => $social
        ]);
    }

    public function create(){

        $social = (New SocialNetworkType())->list(2);

        return response()->json([
            'SocialNetworkType' => $social
        ]);
    }

    public function store(Request $request){
        $messaje = 'The chef was successfully created';

        if(  !$request->input("id")  ){
            Log::info("::CREA Chef");
            $objItem = new Chef;
            $objItem->created_at = date("Y-m-d H:i:s");
            //$objItem->active = '1';
        }else{
            Log::info("::MODIFICA Chef");
            $objItem = Chef::findOrFail( $request->input("id")  );
        };
        $objItem->name = $request->input("name");
        $objItem->updated_at = date("Y-m-d H:i:s");
        $objItem -> save();

        $objLink = (New SocialNetwork()) -> storeLink( $request , $objItem->id, 2  );

        return response()->json([
            'chef' => $objItem,
            'messaje' => $messaje
        ]);
    }
}
