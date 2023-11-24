<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banners;

class BannersController extends Controller
{

    public function store(Request $request)
    {
        $status = 200;$mensaje = '';$objBanner = [];
        $banner = $request->file("banner");
        //$idOwner = $request->input("idOwner");
        $idSection = $request->input("idSection");

        if($request->file("banner")){
            $objBanner = (New Banners())->storeBanner($banner, $idSection);
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

}
