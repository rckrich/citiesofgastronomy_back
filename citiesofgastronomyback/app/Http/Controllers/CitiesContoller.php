<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use Illuminate\Support\Facades\Log;

class CitiesContoller extends Controller
{
    public function list(){
        $objCities =(New Cities())->list();
        //Log::info($objCities);
        return response()->json([
            'cities' => $objCities,
            'bannerCities' => []
        ]);
    }

    public function find($cityId){
        $objCity = (New Cities())->serch($cityId);
        return response()->json([
            'datta' => $objCity
        ]);
    }

    public function store(Request $request)
    {
        /*
        //Verifica si existe el usuario
        if($request->accessToken){
            $userdatta = (New user_token())->show($request->accessToken);
            if(count($userdatta)>0){
                $user=$userdatta;
                $id_user = $user[0]["id"];
            };
        };
        //*/
        $objCity = (New Cities())->store($request);
        return response()->json([
            'status' =>  $objCity["status"],
            'message' =>  $objCity["mensaje"],
            'datta' =>  $objCity["datta"]
        ]);
    }











    public function update(Request $request, string $id)
    {
        $image = '';$status = 200;$mensaje="La ciudad se ha guardado correctamente";

        $photo = '';
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    $photo =  $request->file("photo")->store('public/images/cities');
                    $photo = str_replace('public/', 'storage/', $photo);
                } catch ( \Exception $e ) {

                }
            };


            $objCity=[];
            try{
                $request->validate ([
                    'name' => 'required|string'
                ]);

                $objCity = Cities::find($id);
                $objCity->idContinent = $request->input("idContinent");
                $objCity->name = $request->input("name");
                $objCity->country = $request->input("country");
                $objCity->population = $request->input("population");
                $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
                $objCity->designationyear = $request->input("designationyear");
                if($photo){
                    $objCity->photo = $photo;
                };
                $objCity->updated_at = date("Y-m-d H:i:s");
                $objCity -> save();
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Formato de nombre incorrecto";
            };

        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objCity
        ]);
    }














    public function updateComplete(Request $request, string $id)
    {
        $image = '';$status = 200;$mensaje="La ciudad se ha guardado correctamente";

        $photo = '';
            if($request->file("photo")){
                try{
                    $request->validate ([
                        'photo' => 'image|max:50000'
                    ]);
                    $photo =  $request->file("photo")->store('public/images/cities');
                    $photo = str_replace('public/', 'storage/', $photo);
                } catch ( \Exception $e ) {

                }
            };

            $logo = '';
            if($request->file("logo")){
                try{
                    $request->validate ([
                        'logo' => 'image|max:50000'
                    ]);
                    $logo =  $request->file("logo")->store('public/images/cities');
                    $logo = str_replace('public/', 'storage/', $logo);
                } catch ( \Exception $e ) {

                }
            };

            $banner = '';
            if($request->file("banner")){
                try{
                    $request->validate ([
                        'banner' => 'image|max:50000'
                    ]);
                    $banner =  $request->file("logo")->store('public/images/cities');
                    $banner = str_replace('public/', 'storage/', $banner);
                } catch ( \Exception $e ) {

                }
            };

            $objCity=[];
            try{
                $request->validate ([
                    'name' => 'required|string'
                ]);

                $objCity = Cities::find($id);
                $objCity->idContinent = $request->input("idContinent");
                $objCity->name = $request->input("name");
                $objCity->country = $request->input("country");
                $objCity->population = $request->input("population");
                $objCity->restaurantFoodStablishments = $request->input("restaurantFoodStablishments");
                $objCity->description = $request->input("description");
                $objCity->designationyear = $request->input("designationyear");
                $objCity->completeInfo = $request->input("completeInfo");
                if($photo){
                    $objCity->photo = $photo;
                };
                if($logo){
                    $objCity->logo =  $logo;

                };
                if($banner){
                    $objCity->banner =  $banner;
                };
                $objCity->updated_at = date("Y-m-d H:i:s");
                $objCity -> save();
            } catch ( \Exception $e ) {
                Log::info($e);
                $status = 400;$mensaje="Formato de nombre incorrecto";
            };

        return response()->json([
            'status' =>  $status,
            'message' =>  $mensaje,
            'datta' =>  $objCity
        ]);
    }















    public function destroy(string $id)
    {
        $objCity = Cities::find($id);
        $objCity->active = '0';
        $objCity -> save();

        return response()->json([
            'status' =>  200,
            'message' =>  'Delete'
        ]);
    }
}
