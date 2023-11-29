<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Images;
use App\Models\Links;
use App\Models\Files;
use App\Models\Banners;
use Illuminate\Support\Facades\Log;

class CitiesContoller extends Controller
{
    public function citiesStore(Request $request){
        //Log::info("Guarda City");
        //Log::info($request);

        $objCity = (New Cities())->store($request);
        //Log::info(":: BACK RESULT");
        //Log::info($objCity);

        //$objCities =(New Cities())->list();
        $objBanners = (New Banners())->list(1, 0);
        return response()->json([
            'cities' => $objCity
        ]);
    }


    public function citiesUpdate(Request $request){
        //Log::info("Guarda City");
        //Log::info($request);

        $objCity = (New Cities())->citiesUpdate($request, 'base');
        //Log::info(":: BACK RESULT");
        //Log::info($objCity);

        //$objCities =(New Cities())->list();
        $objBanners = (New Banners())->list(1, 0);
        return response()->json([
            'cities' => $objCity
        ]);
    }


    public function citiesUpdateComplete(Request $request){
        //Log::info("Guarda City");
        //Log::info($request);

        $objCity = (New Cities())->citiesUpdate($request, 'complete');
        //Log::info(":: BACK RESULT");
        //Log::info($objCity);

        return response()->json([
            'cities' => $objCity
        ]);
    }


    public function serchList(Request $request){
        //Log::info("### search :::");
        //Log::info($request->search);
        $objCities =(New Cities())->searchList($request->search);
        //Log::info($objCities);
        return response()->json([
            'cities' => $objCities
        ]);
    }
    public function list(){
        $objCities =(New Cities())->list();
        $objBanners = (New Banners())->list(1, 0);
        Log::info($objCities);
        return response()->json([
            'bannerCities' => $objBanners,
            'cities' => $objCities
        ]);
    }

    public function find($cityId){

        $objCity = (New Cities())->serch($cityId);
        $objgallery = (New Images())->list(1, $cityId);
        $objLinks = (New Links())->list(1, $cityId);
        $objFiles = (New Files())->list(1, $cityId);
        $objBanners = (New Banners())->list(1, $cityId);

        return response()->json([
            'cities' => $objCity,
            'gallery' => $objgallery,
            'links' => $objLinks,
            'files' => $objFiles,
            'bannerCities' => $objBanners
        ]);
    }


    public function delete($id){
        $status = 400;$mess = 'ok';
        try{
            $objCity = Cities::find($id);
            $objCity->active = 0;
            $objCity->updated_at = date("Y-m-d H:i:s");
            $objCity -> save();
        } catch ( \Exception $e ) {
            Log::info($e);
            $status = 400;$mess="Error";
        };
        return response()->json([
            'status' => $status,
            'message' => $mess
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
                $objCity->completeDescription = $request->input("completeDescription");
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

            /////////////////////////////   GALLERY
            $cant_gallery = $request->input("cant_gallery");

            for($i = 1; $i < $cant_gallery+1; $i++){
                $idg = 'image'.$i;
                $image = $request->file($idg);
                $idg = 'idImage'.$i;
                $idImage = $request->input($idg);
                $idg = 'deleteImage'.$i;
                $deleteImage = $request->input($idg);

                if(!$idImage){
                    if($image){

                        try{
                            $objGallery =(New Images())->storeIMG($image, $id, 1);
                            /*
                            $request->validate ([
                                $idg => 'image|max:50000'
                            ]);
                            $photo =  $request->file($idg)->store('public/images/gallery');
                            $photo = str_replace('public/', 'storage/', $photo);

                            $objCity = new Images;
                            $objCity->image = $photo;
                            $objCity->name = $id;
                            $objCity->active = 1;
                            $objCity->idSection = 1;
                            $objCity->created_at = date("Y-m-d H:i:s");
                            $objCity->updated_at = date("Y-m-d H:i:s");
                            $objCity -> save();//*/
                        } catch ( \Exception $e ) {

                        }
                    };
                }else{
                    if($deleteImage){

                    };
                };

            }
            /////////////////////////////   LINKS
            $cant_links = $request->input("cant_links");

            for($i = 1; $i < $cant_links+1; $i++){
                $idg = 'link'.$i;
                $link = $request->input($idg);
                $idg = 'idLink'.$i;
                $idLink = $request->input($idg);
                $idg = 'deleteLink'.$i;
                $deleteLink = $request->input($idg);

                if(!$idLink){
                    if($link){
                        try{
                            $objGallery =(New Links())->storeLINK($link, $id, 1);
                        } catch ( \Exception $e ) {

                        }
                    };
                }else{
                    if($deleteLink){

                    };
                };

            }



            /////////////////////////////   FILES
            $cant_files = $request->input("cant_files");

            for($i = 1; $i < $cant_files+1; $i++){
                $idg = 'file'.$i;
                $file = $request->file($idg);
                $idg = 'idFile'.$i;
                $idFile = $request->input($idg);
                $idg = 'title'.$i;
                $title = $request->input($idg);
                $idg = 'deleteFile'.$i;
                $deleteFile = $request->input($idg);
                if(!$idFile){
                    if($file){

                        $objGallery =(New Files())->storeFILE($file, $id, 1, $title);
                        try{
                        } catch ( \Exception $e ) {

                        }
                    };
                }else{
                    if($deleteFile){

                    };
                };

            }
            /////////////////////////////////////////////////////////////////


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
