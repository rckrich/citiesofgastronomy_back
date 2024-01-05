<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;
use Illuminate\Support\Facades\Log;

class BannersController extends Controller
{

    public function store(Request $request)
    {
        $status = 200;$mensaje = '';$objBanner = [];
        $banner = $request->file("banner");
        //$idOwner = $request->input("idOwner");
        $idSection = $request->input("idSection");
        $idOwner = $request->input("idOwner");
        Log::info($banner);
        Log::info("idSection: ".$idSection);
        Log::info("idOwner: ".$idOwner);

        if($request->file("banner")){
            $objBanner = (New Banners())->storeBanner( $banner, $idSection, $idOwner);
            try{
                $request->validate ([
                    'banner' => 'image|max:50000'
                ]);
            } catch ( \Exception $e ) {

            }
        };
        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objBanner
        ]);
    }
    public function update(Request $request)
    {
        $status = 200;$mensaje = '';$objBanner = [];
        $banner = $request->file("banner");
        $idBanner = $request->input("idBanner");
        Log::info($banner);
        Log::info("idBanner: ".$idBanner);

        if($request->file("banner")){
            $objBanner = (New Banners())->changeBanner( $banner, $idBanner);
            try{
                $request->validate ([
                    'banner' => 'image|max:50000'
                ]);
            } catch ( \Exception $e ) {

            }
        };
        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objBanner
        ]);
    }
    public function delete(Request $request){
        $id = $request->input("idBanner");
        $status = 200;  $mess = 'ok';

        try{
            $objCity = Banners::find($id);
            $objCity -> delete();
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mess="Error";
        };

        return response()->json([
            'status' => $status,
            'message' => $mess
        ]);
    }
}
